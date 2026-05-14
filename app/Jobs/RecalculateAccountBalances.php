<?php

namespace App\Jobs;

use App\Models\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateAccountBalances implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $vendorName;

    /**
     * Create a new job instance.
     *
     * @param string|null $vendorName If null, all vendors will be recalculated.
     */
    public function __construct(?string $vendorName = null)
    {
        $this->vendorName = $vendorName;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $incomeTypes = ['Received', 'Receivable'];
        $expenseTypes = ['Payment', 'Payable', 'Purchase', 'Salary', 'Office costs'];

        $vendors = $this->vendorName
            ? [$this->vendorName]
            : Account::distinct()->pluck('vendor_name');

        foreach ($vendors as $vendor) {
            $accounts = Account::where('vendor_name', $vendor)
                ->orderBy('date')
                ->orderBy('id')
                ->get();

            $balance = 0;

            foreach ($accounts as $acc) {
                if (in_array($acc->entry_type, $incomeTypes)) {
                    $balance += $acc->amount;
                } elseif (in_array($acc->entry_type, $expenseTypes)) {
                    $balance -= $acc->amount;
                }
                $acc->updateQuietly(['balance' => $balance]);
            }
        }
    }
}
