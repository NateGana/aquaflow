@php
    $messages = collect([
        ['type' => 'success', 'text' => session('success')],
        ['type' => 'error', 'text' => session('error')],
        ['type' => 'success', 'text' => session('status')],
    ])->filter(fn ($m) => $m['text']);
@endphp
<div class="pointer-events-none fixed right-4 top-4 z-[70] flex w-full max-w-sm flex-col gap-3">
    @foreach($messages as $message)
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 6000)" x-show="show" x-transition.opacity role="status"
             class="pointer-events-auto flex items-start gap-3 rounded-xl border bg-white p-4 shadow-xl {{ $message['type'] === 'success' ? 'border-emerald-200' : 'border-red-200' }}">
            <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full text-white {{ $message['type'] === 'success' ? 'bg-emerald-500' : 'bg-red-500' }}">
                <x-icon :name="$message['type'] === 'success' ? 'check' : 'alert'" class="h-3.5 w-3.5"/>
            </span>
            <p class="flex-1 text-sm font-medium text-navy">{{ $message['text'] }}</p>
            <button type="button" @click="show = false" class="text-slate-400 hover:text-slate-600" aria-label="Dismiss"><x-icon name="x" class="h-4 w-4"/></button>
        </div>
    @endforeach
    @if($errors->any())
        <div x-data="{ show: true }" x-show="show" role="alert" class="pointer-events-auto rounded-xl border border-red-200 bg-white p-4 shadow-xl">
            <div class="flex items-start gap-3">
                <span class="grid h-6 w-6 shrink-0 place-items-center rounded-full bg-red-500 text-white"><x-icon name="alert" class="h-3.5 w-3.5"/></span>
                <div class="flex-1 text-sm">
                    <p class="font-semibold text-navy">Please fix the following:</p>
                    <ul class="mt-1 list-disc space-y-0.5 pl-4 text-slate-600">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
                <button type="button" @click="show = false" class="text-slate-400 hover:text-slate-600" aria-label="Dismiss"><x-icon name="x" class="h-4 w-4"/></button>
            </div>
        </div>
    @endif
</div>
