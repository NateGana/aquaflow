<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;

class AchievementController extends Controller
{
    public function __invoke(Request $request)
    {
        $earned = $request->user()->achievements()->get()->keyBy('id');

        return view('achievements.index', [
            'achievements' => Achievement::orderBy('id')->get(),
            'earned' => $earned,
        ]);
    }
}
