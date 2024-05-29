<?php

use App\Models\RandomSchedule;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return [
        'Laravel' => app()->version(),
        'database' => config('database.default'),
        // 'config/s3' => config('filesystems.disks.s3'),
        RandomSchedule::limit(10)
                ->get(),
        'files' => Storage::allFiles('/'),
    ];
});

require __DIR__.'/auth.php';
