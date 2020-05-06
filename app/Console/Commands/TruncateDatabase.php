<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TruncateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:truncate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate all the tables in the database';

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
     * @return mixed
     */
    public function handle()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        $db = 'Tables_in_' . config('database.connections.mysql.database');
        collect(DB::select('SHOW TABLES'))->map->{$db}
            ->filter(function ($table) {
                return ! Str::contains($table, ['roles', 'permissions']);
            })
            ->each(function ($table) {
                DB::table($table)->delete();
                $this->info("{$table} table truncated.");
            });
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
    }
}
