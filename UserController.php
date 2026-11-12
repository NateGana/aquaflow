<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q'));

        return view('admin.users.index', [
            'users' => User::with('role')
                ->when($q !== '', fn ($query) => $query->where(fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%")))
                ->latest()->paginate(15)->withQueryString(),
            'q' => $q,
        ]);
    }

    public function show(User $user)
    {
        return view('admin.users.show', [
            'member' => $user->load('role'),
            'roles' => Role::orderBy('id')->get(),
            'stats' => [
                'lessons' => $user->lessonProgress()->whereNotNull('completed_at')->count(),
                'sessions' => $user->records()->count(),
                'distance' => (int) $user->records()->sum('distance_m'),
                'quizzes' => $user->quizAttempts()->whereNotNull('completed_at')->count(),
                'points' => $user->points,
            ],
            'achievements' => $user->achievements()->get(),
            'history' => $user->lessonProgress()->with('lesson')->latest('updated_at')->limit(8)->get(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role_id' => ['sometimes', 'exists:roles,id'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot change your own role or status.');
        }

        $user->update($data);

        return back()->with('success', 'User updated.');
    }
}
