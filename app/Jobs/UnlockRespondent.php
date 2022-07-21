<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Respondent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UnlockRespondent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $respondent;
    public function __construct($respondent)
    {
        $this->respondent = Respondent::find($respondent);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->respondent->locked = 0;
        $this->respondent->lock_agent = 0;

        $this->respondent->save();
    }
}
