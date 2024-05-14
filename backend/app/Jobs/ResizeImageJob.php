<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class ResizeImageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $imagePath
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $image = Storage::get($this->imagePath);
        
        $scaled = ImageManager::gd()
                        ->read($image)
                        ->scaleDown(250,250)
                        ->encode();
        Storage::put($this->imagePath, $scaled);
    }
}
