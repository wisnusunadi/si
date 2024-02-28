<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Get the database information from the .env file 
        $backupPath = storage_path('backups');
        $backupFileName = 'backup-' . date('Y-m-d-H-i-s') . '.sql';
        $backupFile = $backupPath . '/' . $backupFileName;

        // Create the backup directory if it doesn't exist
        if (!is_dir($backupPath)) {
            mkdir($backupPath, 0755, true);
        }

        // Dump the database into the backup file
        $this->info('Backing up database...');
        $dumpCommand = sprintf(
            'mysqldump --user=%s --password=%s --host=%s %s > %s',
            env('DB_USERNAME'),
            env('DB_PASSWORD'),
            env('DB_HOST'),
            env('DB_DATABASE'),
            $backupFile
        );

        shell_exec($dumpCommand);

        // sending email
        $this->info('Sending email...');

        $mailData = [
            'subject' => 'Database Backup',
            'body' => 'Database backup is attached to this email.',
        ];

        $mailAddress = 'dimasmail0101@gmail.com';

        Mail::raw($mailData['body'], function ($message) use ($mailAddress, $mailData, $backupFile) {
            $message->to($mailAddress)
                ->subject($mailData['subject'])
                ->attach($backupFile);
        });

        $this->info('Database backup has been emailed to ' . $mailAddress);
    }
}
