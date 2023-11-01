<?php

namespace SoeurngSar\LaravelScoutOpenSearch\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use SoeurngSar\LaravelScoutOpenSearch\ProgressReportable;

class QueueableJob implements ShouldQueue
{
    use Queueable;
    use ProgressReportable;

    public ?int $timeout = null;

    public function handle(): void
    {
    }
}
