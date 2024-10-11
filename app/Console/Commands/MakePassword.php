<?php

namespace App\Console\Commands;

use App\Model\Statistics\Stats;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class MakePassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:user-password {password : Password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'make user password';

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
        $inputPassword = $this->argument('password');
        echo Hash::make($inputPassword);
    }
}
