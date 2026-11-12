@props(['action', 'filters' => [], 'levels' => true, 'categories' => null, 'duration' => false, 'placeholder' => 'Search...'])
<form method="GET" action="{{ $action }}" class="card mb-8 grid gap-3 p-4 sm:grid-cols-2 lg:grid-cols-5">
    <div class="lg:col-span-2">
        <label class="sr-only" for="filter-q">Search</label>
        <input id="filter-q" type="search" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="{{ $placeholder }}" class="input">
    </div>
    @if($levels)
        <select name="level" class="input" aria-label="Difficulty">
            <option value="">All levels</option>
            @foreach(\App\Support\Level::ALL as $key => $label)
                <option value="{{ $key }}" @selected(($filters['level'] ?? '') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    @endif
    @if($categories)
        <select name="category" class="input" aria-label="Category">
            <option value="">All categories</option>
            @foreach($categories as $category)
                <option value="{{ $category->slug }}" @selected(($filters['category'] ?? '') === $category->slug)>{{ $category->name }}</option>
            @endforeach
        </select>
    @endif
    @if($duration)
        <select name="duration" class="input" aria-label="Duration">
            <option value="">Any duration</option>
            @foreach(($duration === 'weeks' ? ['short' => 'Up to 4 weeks', 'medium' => '5 to 8 weeks', 'long' => 'Over 8 weeks'] : ['short' => 'Up to 15 min', 'medium' => '16 to 30 min', 'long' => 'Over 30 min']) as $key => $label)
                <option value="{{ $key }}" @selected(($filters['duration'] ?? '') === $key)>{{ $label }}</option>
            @endforeach
        </select>
    @endif
    <div class="flex gap-2">
        <button class="btn btn-primary flex-1">Apply</button>
        <a href="{{ $action }}" class="btn btn-outline">Reset</a>
    </div>
</form>
