<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Events\ImportCompleted;
use Facades\App\Services\Excel\Excel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ImportFile implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;

    public $failOnTimeout = true;

    public $path;

    public $userId;

    public $teamId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($path, $userId, $teamId)
    {
        $this->path = $path;
        $this->userId = $userId;
        $this->teamId = $teamId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Excel::import($this->path, $this->teamId);

        Storage::delete($this->path);

        ImportCompleted::dispatch($this->userId);
    }
}
