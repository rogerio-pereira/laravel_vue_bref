<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserUpdateRequest;
use App\Jobs\ResizeImageJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function loggedUser()
    {
        return Auth::user();
    }

    public function update(UserUpdateRequest $request)
    {
        $data = $request->validated();

        $file = $request->file('file');
        if($file) {
            $userId = Auth::user()->id;
            $ext = $file->extension();
            $fileName = "{$userId}.{$ext}";

            $file = Storage::putFileAs('profiles', $file, $fileName);
            $data['picture'] = $file;
            ResizeImageJob::dispatch($file);
        }

        $user = Auth::user();
        $user->update($data);

        return $user;
    }
}
