<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SendTextController extends Controller
{
    public function send(Request $request)
    {
        // Untuk debugging dulu
        return response()->json([
            'status' => 'success',
            'data' => $request->all(),
        ]);
    }
}
