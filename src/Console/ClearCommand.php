<?php

namespace Chapdel\AuthLog\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Chapdel\AuthLog\AuthLog;

class ClearCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'authlog:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear old records from the authlog';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Clearing auth log...');

        $days = config('authlog.older');
        $from = Carbon::now()->subDays($days)->format('Y-m-d H:i:s');

        AuthLog::where('login_at', '<', $from)->delete();

        $this->info('Auth log cleared successfully.');
    }
}
