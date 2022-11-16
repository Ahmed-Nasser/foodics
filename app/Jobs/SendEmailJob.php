<?php

namespace App\Jobs;

use App\Mail\IngredientAmountAlert;
use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Ingredient $ingredient;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Ingredient $ingredient)
    {
        $this->ingredient = $ingredient;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $email = new IngredientAmountAlert($this->ingredient);
        Mail::to('please add a registered email on mailgun OR configured Receipt mailgun')->send($email);
    }
}
