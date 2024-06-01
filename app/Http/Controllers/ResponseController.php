<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ResponseController extends Controller
{
    public function sendResponse($message, $data = [])
    {
        $response = [
            'status' => true,
            'message' => $message,
            'data' => (object) $data,
        ];

        return response()->json($response, 200);
    }

    public function sendError($error, $code = 404)
    {
        $response = [
            'status' => false,
            'message' => $error
        ];

        return response()->json($response, $code);
    }
}
