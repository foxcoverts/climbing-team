<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MailLog;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreMailLogController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function __invoke(Request $request)
    {
        MailLog::create([
            'body' => $request->getContent(),
        ]);

        // I would prefer to return a HTTP_CREATED response but the forwardemail API says it expects HTTP_OK.
        return response()->json("ok", Response::HTTP_OK);
    }
}
