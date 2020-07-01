<?php

namespace Mabadir\ActivityTracker\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Mabadir\ActivityTracker\Jobs\CaptureActivityJob;

class TrackController extends Controller
{
    public function __invoke(Request $request)
    {
        $visitor_id = $request->cookie('visitor_id') ?? (string) Str::uuid();
        $cookie = cookie('visitor_id', $visitor_id, 30*24*60);

        CaptureActivityJob::dispatch([
            'type' => $request->type,
            'payload' => $request->payload,
            'user_id' => auth()->check()?auth()->id():null,
            'visitor_id' => $visitor_id,
        ]);

        return response('ok')->cookie($cookie);
    }
}