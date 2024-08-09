<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Jurnal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Notif;

class CheckJournalEntries extends Command
{
    protected $signature = 'check:journal_entries';
    protected $description = 'Check if users have filled their journal entries and send notification if not';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Assuming 'employee' is the role for employees
        $employees = User::where('role', 'employee')->get();
        $today = Carbon::today();

        foreach ($employees as $employee) {
            $journalEntry = Jurnal::where('employee_id', $employee->id)->whereDate('created_at', $today)->first();

            if (!$journalEntry) {
                Notification::send($employee, new Notif(
                    $employee,
                    @$employee->name . ' klik untuk membuat jurnal.',
                    'Klik disini untuk melihat detailnya.',
                    'warning',
                    url('/employee/jurnal'), // Menggunakan helper url() dengan path relatif
                    'bx bx-info-circle',
                    $employee->id,
                    ""
                ));
            }
        }

        return 0;
    }
}
