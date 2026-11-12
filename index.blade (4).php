@extends('layouts.admin')
@section('title', 'Users')

@section('content')
<x-page-header title="Users" subtitle="Search accounts, open profiles, change roles and activate or deactivate access."/>

<form method="GET" action="{{ route('admin.users.index') }}" class="mb-6 flex gap-2">
    <label class="sr-only" for="user-q">Search users</label>
    <input id="user-q" type="search" name="q" value="{{ $q }}" placeholder="Search by name or email..." class="input max-w-md">
    <button class="btn btn-primary">Search</button>
    @if($q !== '')<a href="{{ route('admin.users.index') }}" class="btn btn-outline">Reset</a>@endif
</form>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500"><tr><th class="px-6 py-3 font-semibold">Name</th><th class="px-3 py-3 font-semibold">Email</th><th class="px-3 py-3 font-semibold">Level</th><th class="px-3 py-3 font-semibold">Role</th><th class="px-3 py-3 font-semibold">Status</th><th class="px-6 py-3"></th></tr></thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($users as $user)
                    <tr>
                        <td class="px-6 py-3 font-medium text-navy">{{ $user->name }}</td>
                        <td class="px-3 py-3">{{ $user->email }}</td>
                        <td class="px-3 py-3">{{ $user->levelLabel() }}</td>
                        <td class="px-3 py-3">{{ $user->role?->label ?? 'None' }}</td>
                        <td class="px-3 py-3"><span class="badge {{ $user->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">{{ $user->is_active ? 'Active' : 'Deactivated' }}</span></td>
                        <td class="px-6 py-3 text-right"><a href="{{ route('admin.users.show', $user) }}" class="btn btn-outline btn-sm">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-slate-500">No users found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="border-t border-slate-100 px-6 py-4">{{ $users->links() }}</div>
</div>
@endsection
