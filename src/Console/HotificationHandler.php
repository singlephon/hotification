<?php

namespace Singlephon\Hotification\Console;

use Illuminate\Console\Command;
use Modules\Central\Models\Tenant;
use Modules\System\Models\Notification;

class HotificationHandler extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotification:handler {--email} {--notify} {--summarize} {--prune} {--clear}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->start();
    }

    public function start()
    {
        $this->email();
        $this->notify();
        $this->summarize();
        $this->prune();
        $this->clear_all();
    }


    /**
     * Send unread notifications through email
     * @return void
     */
    public function email()
    {
        if (! $this->option('email'))
            return;
    }

    /**
     *
     * @return void
     */
    public function notify()
    {
        if (! $this->option('notify'))
            return;
    }

    /**
     * @return void
     */
    public function summarize()
    {
        if (! $this->option('summarize'))
            return;
    }

    public function prune()
    {
        if (! $this->option('prune'))
            return;

        $this->call('model:prune', [
            '--model' => 'Singlephon\Hotification\Models\DatabaseNotification'
        ]);
    }

    public function clear_all(): void
    {
        if (! $this->option('clear'))
            return;

        Notification::query()
            ->delete();
    }
}
