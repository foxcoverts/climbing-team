<?php

use App\Enums\BookingAttendeeResponse;
use App\Enums\BookingAttendeeStatus;
use App\Enums\BookingStatus;
use App\Enums\CommentNotificationOption;
use App\Enums\GirlguidingScheme;
use App\Enums\MountainTrainingAward;
use App\Enums\ScoutPermitActivity;
use App\Enums\ScoutPermitCategory;
use App\Enums\ScoutPermitType;

return [
    'booking' => [
        'attendee' => [
            'response' => [
                BookingAttendeeResponse::Yes->value => 'Yes',
                BookingAttendeeResponse::No->value => 'No',
                BookingAttendeeResponse::Maybe->value => 'Maybe',
            ],
            'status' => [
                BookingAttendeeStatus::Accepted->value => 'Going',
                BookingAttendeeStatus::Tentative->value => 'Maybe',
                BookingAttendeeStatus::Declined->value => 'Can\'t go',
                BookingAttendeeStatus::NeedsAction->value => 'Invited',
            ],
        ],
        'status' => [
            'new' => 'New',
            BookingStatus::Tentative->value => 'Tentative',
            BookingStatus::Confirmed->value => 'Confirmed',
            BookingStatus::Cancelled->value => 'Cancelled',
        ],
    ],
    'girlguiding' => [
        'scheme' => [
            GirlguidingScheme::Climbing->value => 'Climbing',
        ],
    ],
    'mountain-training' => [
        'award' => [
            MountainTrainingAward::IndoorClimbingAssistant->value => 'Indoor Climbing Assistant',
            MountainTrainingAward::ClimbingWallInstructor->value => 'Climbing Wall Instructor',
            MountainTrainingAward::ClimbingWallInstructorAbseil->value => 'Climbing Wall Instructor Abseil',
            MountainTrainingAward::ClimbingWallDevelopmentInstructor->value => 'Climbing Wall Development Instructor',
            MountainTrainingAward::RockClimbingInstructor->value => 'Rock Climbing Instructor',
            MountainTrainingAward::RockClimbingDevelopmentInstructor->value => 'Rock Climbing Development Instructor',
        ],
    ],
    'notification' => [
        'comment-option' => [
            CommentNotificationOption::All->value => 'All',
            CommentNotificationOption::Reply->value => 'Replies',
            CommentNotificationOption::Leader->value => 'Lead Instructor',
            CommentNotificationOption::None->value => 'None',
        ],
    ],
    'qualification' => [
        'type' => [
            App\Models\GirlguidingQualification::class => 'Girlguiding',
            App\Models\MountainTrainingQualification::class => 'Mountain Training',
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
    'todo' => [
        'due_ago' => 'Due :ago',
        'due_date' => 'Due on :date',
        'priority' => [
            '1' => 'High',
            '2' => 'High (2)',
            '3' => 'High (3)',
            '4' => 'High (4)',
            '5' => 'Medium',
            '6' => 'Low (6)',
            '7' => 'Low (7)',
            '8' => 'Low (8)',
            '9' => 'Low',
        ],
        'status' => [
            'in-process' => 'In Process',
            'needs-action' => 'Not started',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ],
    ],
    'user' => [
        'accreditation' => [
            'kit-checker' => 'Kit Checker',
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
