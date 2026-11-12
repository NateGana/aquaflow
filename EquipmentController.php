<?php

namespace App\Http\Controllers;

use App\Models\EquipmentGuide;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only('q');

        return view('equipment.index', [
            'items' => EquipmentGuide::filter($filters)->orderBy('id')->get(),
            'filters' => $filters,
        ]);
    }
}
