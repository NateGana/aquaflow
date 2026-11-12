@php $model = $model ?? false; @endphp
<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="label">Date</label>
        <input type="date" name="swam_on" class="input" max="{{ today()->toDateString() }}" required @if($model) x-model="edit.swam_on" @else value="{{ old('swam_on', today()->toDateString()) }}" @endif>
    </div>
    <div>
        <label class="label">Swimming style</label>
        <select name="style" class="input" required @if($model) x-model="edit.style" @endif>
            @foreach($styles as $key => $label)<option value="{{ $key }}" @if(! $model) @selected(old('style', 'freestyle') === $key) @endif>{{ $label }}</option>@endforeach
        </select>
    </div>
    <div>
        <label class="label">Distance (meters)</label>
        <input type="number" name="distance_m" min="1" max="100000" class="input" required @if($model) x-model="edit.distance_m" @else value="{{ old('distance_m') }}" @endif>
    </div>
    <div>
        <label class="label">Duration (minutes)</label>
        <input type="number" name="duration_min" min="1" max="1440" class="input" required @if($model) x-model="edit.duration_min" @else value="{{ old('duration_min') }}" @endif>
    </div>
    <div>
        <label class="label">Laps</label>
        <input type="number" name="laps" min="0" max="5000" class="input" @if($model) x-model="edit.laps" @else value="{{ old('laps') }}" @endif>
    </div>
    <div class="sm:col-span-2">
        <label class="label">Notes</label>
        <textarea name="notes" rows="2" maxlength="1000" class="input" @if($model) x-model="edit.notes" @endif>@if(! $model){{ old('notes') }}@endif</textarea>
    </div>
</div>
