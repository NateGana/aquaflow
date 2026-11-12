<section class="card mt-8 max-w-4xl p-6 sm:p-8">
    <h2 class="text-lg">Weekly exercises</h2>
    <p class="mt-1 text-sm text-slate-500">Add, edit or remove the individual exercises that make up the weekly schedule.</p>

    <div class="mt-5 space-y-4">
        @forelse($program->exercises as $exercise)
            <div class="rounded-xl border border-slate-200 p-4">
                <form method="POST" action="{{ route('admin.programs.exercises.update', [$program, $exercise]) }}" class="grid gap-3 sm:grid-cols-6">
                    @csrf @method('PATCH')
                    <div><label class="label">Week</label><input type="number" name="week" min="1" max="52" value="{{ $exercise->week }}" class="input" required></div>
                    <div><label class="label">Day</label><input type="number" name="day" min="1" max="7" value="{{ $exercise->day }}" class="input" required></div>
                    <div class="sm:col-span-2"><label class="label">Title</label><input name="title" value="{{ $exercise->title }}" class="input" required></div>
                    <div><label class="label">Distance (m)</label><input type="number" name="distance_m" min="0" value="{{ $exercise->distance_m }}" class="input"></div>
                    <div class="flex items-end"><button class="btn btn-outline w-full">Save</button></div>
                    <div class="sm:col-span-6"><label class="label">Description</label><input name="description" value="{{ $exercise->description }}" class="input"></div>
                </form>
                <form method="POST" action="{{ route('admin.programs.exercises.destroy', [$program, $exercise]) }}" class="mt-3" data-confirm="Remove this exercise?">@csrf @method('DELETE')
                    <button class="text-xs font-semibold text-red-600 hover:underline">Remove exercise</button>
                </form>
            </div>
        @empty
            <p class="text-sm text-slate-500">No exercises yet. Add the first one below.</p>
        @endforelse
    </div>

    <form method="POST" action="{{ route('admin.programs.exercises.store', $program) }}" class="mt-6 grid gap-3 rounded-xl bg-mist/50 p-4 sm:grid-cols-6">
        @csrf
        <h3 class="text-base sm:col-span-6">Add an exercise</h3>
        <div><label class="label">Week</label><input type="number" name="week" min="1" max="52" value="1" class="input" required></div>
        <div><label class="label">Day</label><input type="number" name="day" min="1" max="7" value="1" class="input" required></div>
        <div class="sm:col-span-2"><label class="label">Title</label><input name="title" class="input" required></div>
        <div><label class="label">Distance (m)</label><input type="number" name="distance_m" min="0" class="input"></div>
        <div class="flex items-end"><button class="btn btn-primary w-full">Add</button></div>
        <div class="sm:col-span-6"><label class="label">Description</label><input name="description" class="input"></div>
    </form>
</section>
