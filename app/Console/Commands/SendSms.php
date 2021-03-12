<?php

namespace App\Console\Commands;

use App\Services\SendSmsNotificationService;
use Illuminate\Console\Command;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:sms';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to send pending sms';
    /**
     * @var SendSmsNotificationService
     */
    private $sendSmsNotificationService;

    /**
     *
     */

    /**
     * Create a new command instance.
     *
     * @param SendSmsNotificationService $sendSmsNotificationService
     */
    public function __construct(SendSmsNotificationService $sendSmsNotificationService)
    {
        parent::__construct();

        $this->sendSmsNotificationService = $sendSmsNotificationService;
    }

    /**
     * Execute the console command.
     *
     * @return bool
     */
    public function handle(): bool
    {
        $this->sendSmsNotificationService->sendSms();

        $this->info('sms sent successfully.');

        return true;
    }
}
