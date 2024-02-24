<?php
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
    'user' => [
        'accreditation' => [
            'manage-bookings' => 'Manage Bookings',
            'manage-users' => 'Manage Users',
        ],
        'role' => [
            'guest' => 'Guest',
            'team-member' => 'Team Member',
            'team-leader' => 'Team Leader',
        ],
    ]
];
