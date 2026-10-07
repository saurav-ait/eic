<?php

namespace Tests\Feature;

use Tests\TestCase;

class LeadStatusSchemaTest extends TestCase
{
    public function test_lead_status_migration_contract_includes_email_sent(): void
    {
        $migrationFiles = [
            database_path('migrations/2026_04_03_182342_create_leads_table.php'),
            database_path('migrations/2026_05_06_102224_update_leads_table_structure.php'),
            database_path('migrations/2026_05_12_063656_add_email_sent_status_to_leads_table.php'),
        ];

        foreach ($migrationFiles as $migrationFile) {
            $this->assertFileExists($migrationFile);
            $contents = file_get_contents($migrationFile);
            $this->assertNotFalse($contents);
            $this->assertStringContainsString("'Email Sent'", $contents, "Expected {$migrationFile} to include the Email Sent status.");
        }
    }
}
