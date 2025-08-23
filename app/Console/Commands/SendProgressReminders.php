<?php

namespace App\Console\Commands;

use App\Models\Progress;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Console\Command;
use Carbon\Carbon;

class SendProgressReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'notifications:send-reminders';

    /**
     * The console command description.
     */
    protected $description = 'Send reminder notifications for progress deadlines';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Sending progress reminders...');
        
        // Get progress with due date tomorrow (reminder 1 day before)
        $progressDueTomorrow = Progress::where('due_date', Carbon::tomorrow()->format('Y-m-d'))
            ->where('status', '!=', 'completed')
            ->get();

        // Get progress that are overdue
        $overdueProgress = Progress::where('due_date', '<', Carbon::today()->format('Y-m-d'))
            ->where('status', '!=', 'completed')
            ->get();

        $reminderCount = 0;

        // Send reminders for progress due tomorrow
        foreach ($progressDueTomorrow as $progress) {
            $employees = User::where('role', 'pegawai')->get();
            
            foreach ($employees as $employee) {
                // Check if employee hasn't submitted this progress yet
                $hasSubmitted = $progress->submissions()->where('submitted_by', $employee->id)->exists();
                
                if (!$hasSubmitted) {
                    Notification::create([
                        'user_id' => $employee->id,
                        'title' => 'Reminder: Progress Deadline Besok',
                        'message' => "Progress '{$progress->title}' akan berakhir besok ({$progress->due_date->format('d/m/Y')}). Segera submit progress Anda!",
                        'type' => 'progress_due'
                    ]);
                    $reminderCount++;
                }
            }
        }

        // Send reminders for overdue progress
        foreach ($overdueProgress as $progress) {
            $employees = User::where('role', 'pegawai')->get();
            
            foreach ($employees as $employee) {
                // Check if employee hasn't submitted this progress yet
                $hasSubmitted = $progress->submissions()->where('submitted_by', $employee->id)->exists();
                
                if (!$hasSubmitted) {
                    $daysPast = Carbon::today()->diffInDays($progress->due_date);
                    
                    Notification::create([
                        'user_id' => $employee->id,
                        'title' => 'Progress Terlambat',
                        'message' => "Progress '{$progress->title}' sudah terlambat {$daysPast} hari. Segera submit progress Anda!",
                        'type' => 'progress_reminder'
                    ]);
                    $reminderCount++;
                }
            }
        }

        // Send daily reminder for pending progress submissions
        $pendingProgress = Progress::whereDoesntHave('submissions')
            ->where('due_date', '>=', Carbon::today())
            ->get();

        foreach ($pendingProgress as $progress) {
            $employees = User::where('role', 'pegawai')->get();
            
            foreach ($employees as $employee) {
                // Only send reminder every 3 days to avoid spam
                $lastReminder = Notification::where('user_id', $employee->id)
                    ->where('type', 'progress_reminder')
                    ->where('message', 'like', "%{$progress->title}%")
                    ->latest()
                    ->first();
                
                if (!$lastReminder || $lastReminder->created_at->diffInDays(Carbon::now()) >= 3) {
                    Notification::create([
                        'user_id' => $employee->id,
                        'title' => 'Pengingat Progress',
                        'message' => "Jangan lupa untuk mengerjakan progress '{$progress->title}'. Deadline: {$progress->due_date->format('d/m/Y')}",
                        'type' => 'progress_reminder'
                    ]);
                    $reminderCount++;
                }
            }
        }

        $this->info("Sent {$reminderCount} reminder notifications.");
        
        return Command::SUCCESS;
    }
}