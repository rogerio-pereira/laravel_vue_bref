<?php

namespace App\Console\Commands;

use App\Models\RandomSchedule;
use Illuminate\Console\Command;

class RandomNumberCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:random';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a random number and save it to random_scheduler table.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $number = rand(0, 100000);
        
        RandomSchedule::create([
            'number' => $number
        ]);
    }
}
