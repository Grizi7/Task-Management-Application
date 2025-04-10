<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Task;
use App\Events\TaskReminder;
use Illuminate\Console\Command;

class SendTaskDueNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:send-due-notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send notifications for tasks due in 1 day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $soon = $now->copy()->addDay();

        $tasks = Task::whereBetween('due_date', [$now, $soon])->get();

        foreach ($tasks as $task) {
            if ($task->user) {
                broadcast(new TaskReminder($task));
            }
        }

        $this->info('Notifications sent.');
    
    }
}
