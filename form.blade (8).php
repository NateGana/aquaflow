@extends('layouts.admin')
@section('title', ucfirst($title))

@section('content')
<x-page-header :title="ucfirst($title)">
    <x-slot:actions><a href="{{ route($route.'.index') }}" class="btn btn-outline">Back to list</a></x-slot:actions>
</x-page-header>

<form method="POST" enctype="multipart/form-data" action="{{ $item ? route($route.'.update', $item) : route($route.'.store') }}" class="card max-w-4xl space-y-5 p-6 sm:p-8">
    @csrf
    @if($item) @method('PUT') @endif

    @foreach($fields as $field)
        @php
            $name = $field['name'];
            $type = $field['type'] ?? 'text';
            $current = $item ? $item->{$name} : ($field['default'] ?? null);
            $value = old($name, $type === 'lines' ? implode("\n", (array) $current) : $current);
        @endphp
        <div>
            @if($type === 'checkbox')
                <label class="flex items-center gap-2 text-sm font-semibold text-navy">
                    <input type="checkbox" name="{{ $name }}" value="1" class="rounded border-slate-300 text-ocean focus:ring-aqua" @checked(old($name, $current))> {{ $field['label'] }}
                </label>
            @else
                <label for="f-{{ $name }}" class="label">{{ $field['label'] }}</label>
                @if($type === 'textarea' || $type === 'lines')
                    <textarea id="f-{{ $name }}" name="{{ $name }}" rows="{{ $type === 'lines' ? 5 : 4 }}" class="input">{{ $value }}</textarea>
                @elseif($type === 'select')
                    <select id="f-{{ $name }}" name="{{ $name }}" class="input">
                        @foreach($field['options'] as $optionValue => $optionLabel)<option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>@endforeach
                    </select>
                @elseif($type === 'file')
                    @if($item && $item->{$name})<img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($item->{$name}) }}" alt="" class="mb-2 h-24 rounded-lg object-cover">@endif
                    <input id="f-{{ $name }}" type="file" name="{{ $name }}" accept="image/png,image/jpeg,image/webp" class="block w-full text-sm file:mr-3 file:rounded-lg file:border-0 file:bg-mist file:px-3 file:py-2 file:text-sm file:font-semibold file:text-ocean">
                @else
                    <input id="f-{{ $name }}" type="{{ $type }}" name="{{ $name }}" value="{{ $type === 'date' && $value instanceof \Carbon\CarbonInterface ? $value->toDateString() : $value }}" class="input">
                @endif
            @endif
        </div>
    @endforeach

    <div class="flex gap-3 pt-2">
        <button class="btn btn-primary">Save</button>
        <a href="{{ route($route.'.index') }}" class="btn btn-outline">Cancel</a>
    </div>
</form>

@if($extra)
    @include($extra, $extraData)
@endif
@endsection
