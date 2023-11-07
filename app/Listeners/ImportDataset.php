<?php

namespace App\Listeners;

use App\Events\AfterDatasetCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ImportDataset
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(AfterDatasetCreated $event): void
    {
        //
    }
}
