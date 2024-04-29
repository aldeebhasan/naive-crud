<?php

namespace Aldeebhasan\NaiveCrud\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Queue\SerializesModels;

class CompletedExportJob
{
    use Queueable, SerializesModels;

    public function __construct(
        private readonly Authenticatable $user,
        private readonly string $path,
        private readonly ?string $notificationClass = null
    )
    {
    }

    public function handle()
    {
        if ($this->notificationClass) {
            $this->user->notify(new $this->notificationClass($this->path));
        }
    }
}
