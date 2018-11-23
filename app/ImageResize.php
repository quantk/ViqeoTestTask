<?php

namespace App;

use App\Jobs\ResizeImageJob;
use App\Service\StatusModel;
use Illuminate\Database\Eloquent\Model;

class ImageResize extends Model
{
    protected static $unguarded = true;
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    public function dispatch($delay = null)
    {
        ResizeImageJob::dispatch($this)->delay($delay);
    }

    public function complete()
    {
        $this->status = StatusModel::COMPLETED;
    }

    public function isCompleted(): bool
    {
        return StatusModel::COMPLETED === $this->status;
    }
}
