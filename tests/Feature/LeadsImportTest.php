<?php

namespace Tests\Feature;

use App\Imports\LeadsImport;
use App\Models\Lead;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class LeadsImportTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('activity_types');
        Schema::dropIfExists('countries');

        Schema::create('countries', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('activity_types', function ($table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('countries')->insert([
            'name' => 'France',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('activity_types')->insert([
            'name' => 'Consulting',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function test_it_maps_worksheet_names_to_foreign_keys_and_preserves_status(): void
    {
        $lead = (new LeadsImport)->model([
            'company_name' => 'Acme Ltd',
            'director' => 'Jane Doe',
            'phone' => '+33 1 00 00 00 00',
            'email' => 'jane@example.com',
            'city' => 'Paris',
            'address' => '1 Rue de la Paix',
            'country' => 'France',
            'activity_type' => 'Consulting',
            'status' => 'Email Sent',
        ]);

        $this->assertInstanceOf(Lead::class, $lead);
        $this->assertSame(1, $lead->country_id);
        $this->assertSame(1, $lead->activity_type_id);
        $this->assertSame('Email Sent', $lead->status);
    }
}
