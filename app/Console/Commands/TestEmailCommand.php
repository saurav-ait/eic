<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\LeadEmail;

class TestEmailCommand extends Command
{
    protected $signature = 'test:email {email}';
    protected $description = 'Test email sending with Mailgun';

    public function handle()
    {
        $email = $this->argument('email');
        
        $this->info('Testing email configuration...');
        $this->info('Mail Driver: ' . config('mail.default'));
        $this->info('Mailgun Domain: ' . config('services.mailgun.domain'));
        $this->info('From Address: ' . config('mail.from.address'));
        $this->info('');
        
        $this->info('Sending test email to: ' . $email);
        
        try {
            Mail::to($email)->send(new LeadEmail(
                'Test Email from Lead Management',
                'This is a test email to verify Mailgun configuration is working correctly.'
            ));
            
            $this->info('✅ Email sent successfully!');
            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email:');
            $this->error($e->getMessage());
            return 1;
        }
    }
}
