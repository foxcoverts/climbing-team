<?php

use App\Enums\ScoutPermitActivity;
use App\Enums\ScoutPermitCategory;
use App\Enums\ScoutPermitType;

return [
    'attendee' => [
        'status' => [
            'accepted' => 'Going',
            'tentative' => 'Maybe',
            'declined' => 'Can\'t go',
            'needs-action' => 'Invited',
        ],
    ],
    'booking' => [
        'status' => [
            'confirmed' => 'Confirmed',
            'tentative' => 'Tentative',
            'cancelled' => 'Cancelled',
        ],
    ],
    'qualification' => [
        'type' => [
            App\Models\ScoutPermit::class => 'Scout Permit',
        ],
    ],
    'scout-permit' => [
        'activity' => [
            ScoutPermitActivity::ClimbingAndAbseiling->value => 'Climbing and Abseiling',
        ],
        'category' => [
            ScoutPermitCategory::ArtificialTopRope->value => 'Artificial Top Rope',
            ScoutPermitCategory::NaturalTopRope->value => 'Natural Top Rope',
            ScoutPermitCategory::ArtificialLeadClimbing->value => 'Artificial Lead Climbing',
            ScoutPermitCategory::NaturalLeadClimbing->value => 'Natural Lead Climbing',
        ],
        'permit-type' => [
            ScoutPermitType::Personal->value => 'Personal',
            ScoutPermitType::Leadership->value => 'Leadership',
            ScoutPermitType::Supervisory->value => 'Supervisory',
        ],
    ],
    'user' => [
        'accreditation' => [
            'manage-bookings' => 'Manage Bookings',
            'manage-qualifications' => 'Manage Qualifications',
            'manage-users' => 'Manage Users',
        ],
        'role' => [
            'guest' => 'Guest',
            'team-member' => 'Team Member',
            'team-leader' => 'Team Leader',
        ],
        'section' => [
            'squirrel' => 'Squirrel',
            'beaver' => 'Beaver',
            'cub' => 'Cub',
            'scout' => 'Scout',
            'explorer' => 'Explorer',
            'network' => 'Network',
            'adult' => 'Adult Member',
            'parent' => 'Parent',
        ],
    ],
];
