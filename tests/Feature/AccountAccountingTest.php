<?php

namespace Tests\Feature;

use App\Http\Controllers\AccountController;
use App\Imports\AccountsImport;
use App\Models\Account;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class AccountAccountingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('accounts');
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('entry_type');
            $table->string('vendor_type');
            $table->string('vendor_name');
            $table->string('purpose');
            $table->text('details')->nullable();
            $table->string('country')->nullable();
            $table->string('last_status')->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('balance', 12, 2)->default(0);
            $table->string('document')->nullable();
            $table->timestamps();
        });
    }

    protected function tearDown(): void
    {
        Schema::dropIfExists('accounts');

        parent::tearDown();
    }

    public function test_moving_a_transaction_recalculates_both_vendor_balances(): void
    {
        Account::create($this->accountData('2026-01-01', 'Received', 'Old Vendor', 100));
        Account::create($this->accountData('2026-01-02', 'Payment', 'Old Vendor', 20));
        $moved = Account::create($this->accountData('2026-01-03', 'Payment', 'Old Vendor', 30));
        Account::create($this->accountData('2026-01-01', 'Received', 'New Vendor', 50));

        $request = Request::create('/admin/accounts/'.$moved->id, 'PUT', [
            'date' => '2026-01-03',
            'entry_type' => 'Payment',
            'vendor_type' => 'Agent',
            'vendor_name' => 'New Vendor',
            'purpose' => 'Others',
            'amount' => 30,
            'last_status' => 'Pending',
        ]);

        app(AccountController::class)->update($request, $moved->id);

        $this->assertSame(80.0, (float) Account::where('vendor_name', 'Old Vendor')->orderByDesc('date')->first()->balance);
        $this->assertSame(20.0, (float) Account::where('vendor_name', 'New Vendor')->orderByDesc('date')->first()->balance);
    }

    public function test_monthly_report_applies_a_single_from_date(): void
    {
        Account::create($this->accountData('2026-01-31', 'Received', 'Vendor', 10));
        Account::create($this->accountData('2026-02-01', 'Received', 'Vendor', 20));

        $request = Request::create('/admin/accounts/report', 'GET', [
            'from_date' => '2026-02-01',
            'month' => '2026-01',
        ]);

        $view = app(AccountController::class)->monthlyReport($request);

        $this->assertSame(['2026-02-01'], $view->getData()['accounts']->map(fn ($account) => (string) $account->date)->all());
        $this->assertSame('From February 1, 2026', $view->getData()['reportLabel']);
    }

    public function test_account_import_validation_rejects_negative_amounts(): void
    {
        $validator = Validator::make([
            'date' => '2026-01-01',
            'entry_type' => 'Received',
            'vendor_type' => 'Client',
            'vendor_name' => 'Vendor',
            'purpose' => 'Others',
            'amount' => -1,
        ], (new AccountsImport())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('amount', $validator->errors()->toArray());
    }

    public function test_pdf_opening_balance_uses_the_same_non_date_filters_as_its_rows(): void
    {
        Account::create(array_replace(
            $this->accountData('2026-01-01', 'Received', 'Vendor', 100),
            ['country' => 'Canada']
        ));
        Account::create(array_replace(
            $this->accountData('2026-01-02', 'Received', 'Vendor', 500),
            ['country' => 'United States']
        ));
        Account::create(array_replace(
            $this->accountData('2026-01-03', 'Payment', 'Vendor', 200),
            ['country' => 'Canada']
        ));
        Account::create(array_replace(
            $this->accountData('2026-02-01', 'Received', 'Vendor', 50),
            ['country' => 'Canada']
        ));

        $pdf = \Mockery::mock(\Barryvdh\DomPDF\PDF::class);
        $pdf->shouldReceive('download')->once()->with('accounts.pdf')->andReturn(response('pdf'));
        Pdf::shouldReceive('loadView')
            ->once()
            ->withArgs(function ($viewName, $data) {
                return $viewName === 'admin.accounts.pdf'
                    && (float) $data['openingBalance'] === 100.0
                    && $data['accounts']->count() === 1;
            })
            ->andReturn($pdf);

        $request = Request::create('/admin/accounts/export/pdf', 'GET', [
            'search' => 'Canada',
            'country' => 'Canada',
            'entry_type' => 'Received',
            'from_date' => '2026-02-01',
        ]);

        $response = app(AccountController::class)->exportPDF($request);

        $this->assertSame('pdf', $response->getContent());
    }

    private function accountData(string $date, string $entryType, string $vendor, float $amount): array
    {
        return [
            'date' => $date,
            'entry_type' => $entryType,
            'vendor_type' => 'Client',
            'vendor_name' => $vendor,
            'purpose' => 'Others',
            'amount' => $amount,
            'balance' => 0,
            'last_status' => 'Pending',
        ];
    }
}
