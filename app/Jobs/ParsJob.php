<?php

namespace App\Jobs;

use App\Models\Pars;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

set_time_limit(4000000);
ini_set('max_input_time', 10000000);
ini_set('max_execution_time', 4000000);
ini_set('memory_limit', '40480M');
ini_set('default_socket_timeout', 6000000);

class ParsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $timeout = 150000;
    public $data;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->data= 1;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Pars::Pars6();
    }
}
