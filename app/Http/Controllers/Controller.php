<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{

    /**
     * Send Successful Response - Helper function
     * @param mixed $data
     * @param string $message
     * @param integer $code - HttpStatusCode
     */
    static function returnSuccess($data, $message = "successful", $code = 200)
    {
        return response()->json([
            "code"      => "00",
            "message"   => $message,
            "data"      => $data
        ], $code);
    }

    /**
     * Send Failed Response - Helper function
     * @param string $message
     * @param integer $code - HttpStatusCode
     */
    static function returnFailed($message = "failed", $code = 400)
    {
        return response()->json([
            "code"      => "02",
            "message"   => $message,
        ], $code);
    }

    public static function respondWithToken($token)
    {
        return [
            'secret' => $token,
            'type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ];
    }
}
