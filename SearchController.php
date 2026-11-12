<?php

namespace App\Http\Controllers;

use App\Models\EquipmentGuide;
use App\Models\SafetyGuide;
use App\Models\SwimmingLesson;
use App\Models\Technique;
use App\Models\TrainingProgram;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __invoke(Request $request)
    {
        $term = trim((string) $request->query('q'));
        $filters = ['q' => $term, 'level' => $request->query('level')];
        $searching = mb_strlen($term) >= 2;

        $results = $searching ? [
            'Lessons' => SwimmingLesson::published()->filter($filters)->ordered()->limit(8)->get(),
            'Techniques' => Technique::filter($filters)->limit(8)->get(),
            'Training programs' => TrainingProgram::filter($filters)->ordered()->limit(8)->get(),
            'Equipment guides' => EquipmentGuide::filter($filters)->limit(8)->get(),
            'Safety articles' => SafetyGuide::filter($filters)->limit(8)->get(),
        ] : [];

        return view('search.index', [
            'term' => $term,
            'level' => $filters['level'],
            'searching' => $searching,
            'results' => $results,
            'count' => collect($results)->sum(fn ($group) => $group->count()),
        ]);
    }
}
