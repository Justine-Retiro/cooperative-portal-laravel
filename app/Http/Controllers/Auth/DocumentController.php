<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\File;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function show($path)
    {
        // Implement any access control checks here
        if (!auth()->user()->canAccessDocument($path)) {
            abort(403);
        }

        $path = storage_path('app/public/' . $path);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
}
