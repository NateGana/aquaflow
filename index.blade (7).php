@extends('layouts.admin')
@section('title', $title)

@section('content')
<x-page-header :title="$title">
    <x-slot:actions>
        <a href="{{ route($route.'.create') }}" class="btn btn-primary"><x-icon name="plus" class="h-4 w-4"/> New {{ strtolower($singular) }}</a>
    </x-slot:actions>
</x-page-header>

<form method="GET" action="{{ route($route.'.index') }}" class="mb-6 flex gap-2">
    <label class="sr-only" for="crud-q">Search</label>
    <input id="crud-q" type="search" name="q" value="{{ $q }}" placeholder="Search..." class="input max-w-md">
    <button class="btn btn-primary">Search</button>
    @if($q !== '')<a href="{{ route($route.'.index') }}" class="btn btn-outline">Reset</a>@endif
</form>

<div class="card overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs text-slate-500">
                <tr>@foreach($columns as $column)<th class="px-4 py-3 font-semibold first:px-6">{{ $column['label'] }}</th>@endforeach<th class="px-6 py-3"></th></tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($items as $item)
                    <tr>
                        @foreach($columns as $column)
                            @php $value = data_get($item, $column['key']); @endphp
                            <td class="max-w-xs truncate px-4 py-3 first:px-6 {{ $loop->first ? 'font-medium text-navy' : '' }}">
                                @switch($column['badge'] ?? null)
                                    @case('level')<x-level-badge :level="$value"/>@break
                                    @case('bool')<span class="badge {{ $value ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $value ? 'Yes' : 'No' }}</span>@break
                                    @case('date'){{ $value?->format('d M Y') }}@break
                                    @default {{ $value }}
                                @endswitch
                            </td>
                        @endforeach
                        <td class="whitespace-nowrap px-6 py-3 text-right">
                            @if(Route::has($route.'.show'))<a href="{{ route($route.'.show', $item) }}" class="btn btn-outline btn-sm">View</a>@endif
                            <a href="{{ route($route.'.edit', $item) }}" class="btn btn-outline btn-sm"><x-icon name="edit" class="h-3.5 w-3.5"/> Edit</a>
                            <form method="POST" action="{{ route($route.'.destroy', $item) }}" class="inline" data-confirm="Delete this {{ strtolower($singular) }}? This cannot be undone.">@csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" aria-label="Delete"><x-icon name="trash" class="h-3.5 w-3.5"/></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="{{ count($columns) + 1 }}" class="px-6 py-10 text-center text-slate-500">Nothing here yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="border-t border-slate-100 px-6 py-4">{{ $items->links() }}</div>
</div>
@endsection
