<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileKeyController extends Controller
{
    /**
     * Display a listing of the logged in user's keys.
     */
    public function index(Request $request)
    {
        return view('key.index', [
            'keys' => $request->user()->keys,
        ]);
    }
}
