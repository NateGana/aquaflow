<?php

namespace App\Http\Controllers;

use App\Models\ChallengeParticipant;
use App\Models\SwimmingChallenge;
use App\Services\ChallengeService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChallengeController extends Controller
{
    public function index(Request $request, ChallengeService $service)
    {
        $participations = $request->user()->participations()->with('challenge')->get()->keyBy('challenge_id');

        $challenges = SwimmingChallenge::where('is_active', true)->orderBy('ends_on')->get()
            ->reject(fn ($c) => $c->hasEnded() && ! $participations->has($c->id))
            ->map(function ($challenge) use ($participations, $service) {
                $participant = $participations->get($challenge->id);

                return [
                    'challenge' => $challenge,
                    'participant' => $participant,
                    'value' => $participant ? min($service->progress($participant), $challenge->target) : 0,
                    'percent' => $participant ? ($participant->completed_at ? 100 : $service->percent($participant)) : 0,
                ];
            });

        return view('challenges.index', ['items' => $challenges]);
    }

    public function join(Request $request, SwimmingChallenge $challenge, ChallengeService $service): RedirectResponse
    {
        abort_unless($challenge->isJoinable(), 403, 'This challenge is no longer open.');

        ChallengeParticipant::firstOrCreate(
            ['user_id' => $request->user()->id, 'challenge_id' => $challenge->id],
            ['joined_on' => today()]
        );

        $service->evaluate($request->user());

        return back()->with('success', "You joined {$challenge->title}.");
    }
}
