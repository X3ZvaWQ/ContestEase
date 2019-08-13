<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function info(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'ret'  => 200,
            'desc' => 'successful',
            'data' => $user->info()
        ]);
    }
}
