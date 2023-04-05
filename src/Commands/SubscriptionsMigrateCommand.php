<?php

namespace Smartech\Subscriptions\Commands;

use Illuminate\Console\Command;

class SubscriptionsMigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute subscriptions migration files';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $path = dirname(dirname(dirname(__FILE__))) . "/database/migrations";

        $this->call('migrate', [
            '--path' => $this->getRelativePath($path),
        ]);
    }

    /**
     * Get Relative path
     * @param string $path
     *
     * @return string
     */
    function getRelativePath($path = '')
    {
        return str_replace(app()->basePath(), "", $path);
    }
}
