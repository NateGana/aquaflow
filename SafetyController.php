<?php

namespace App\Http\Controllers;

use App\Models\SafetyGuide;
use Illuminate\Http\Request;

class SafetyController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only('q');

        return view('safety.index', [
            'guides' => SafetyGuide::filter($filters)->orderBy('sort_order')->orderBy('id')->get(),
            'filters' => $filters,
        ]);
    }
}
