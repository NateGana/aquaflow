<?php

namespace App\Http\Controllers;

use App\Models\Technique;
use Illuminate\Http\Request;

class TechniqueController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['q', 'level']);

        return view('techniques.index', [
            'techniques' => Technique::filter($filters)->orderBy('id')->get(),
            'filters' => $filters,
        ]);
    }

    public function show(Technique $technique)
    {
        return view('techniques.show', [
            'technique' => $technique,
            'lessons' => $technique->relatedLessons()->load('category'),
            'others' => Technique::whereKeyNot($technique->id)->orderBy('id')->limit(4)->get(),
        ]);
    }
}
