<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:send-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send booking reminder emails for upcoming check-ins';

    protected EmailService $emailService;

    /**
     * Create a new command instance.
     */
    public function __construct(EmailService $emailService)
    {
        parent::__construct();
        $this->emailService = $emailService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting to send booking reminder emails...');

        $sentCount = $this->emailService->sendTodaysReminders();

        if ($sentCount > 0) {
            $this->info("Successfully sent {$sentCount} reminder emails.");
        } else {
            $this->info('No reminder emails to send today.');
        }

        return Command::SUCCESS;
    }
}
