<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\File;

class CleanImagesJob extends Job
{
    public function handle(): void {
        $files = array_map(static function($item) {
            return $item->getPathname();
        }, File::allFiles(app()->basePath("public/images")));
        File::delete($files);
    }

}
