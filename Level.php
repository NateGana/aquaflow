<?php

namespace App\Support;

class Level
{
    public const ALL = [
        'beginner' => 'Beginner',
        'intermediate' => 'Intermediate',
        'advanced' => 'Advanced',
    ];

    public const SKILLS = [
        'technique' => 'Swimming Techniques',
        'breathing' => 'Breathing Control',
        'confidence' => 'Water Confidence',
        'endurance' => 'Swimming Endurance',
    ];

    public const STYLES = [
        'freestyle' => 'Freestyle',
        'breaststroke' => 'Breaststroke',
        'backstroke' => 'Backstroke',
        'butterfly' => 'Butterfly',
        'mixed' => 'Mixed',
        'drills' => 'Drills',
    ];

    public const ACTIVITIES = [
        'Freestyle practice' => 'Freestyle practice',
        'Breaststroke practice' => 'Breaststroke practice',
        'Backstroke practice' => 'Backstroke practice',
        'Butterfly practice' => 'Butterfly practice',
        'Endurance swim' => 'Endurance swim',
        'Technique drills' => 'Technique drills',
        'Water confidence' => 'Water confidence',
        'Swimming lesson' => 'Swimming lesson',
    ];

    public static function label(string $level): string
    {
        return self::ALL[$level] ?? ucfirst($level);
    }
}
