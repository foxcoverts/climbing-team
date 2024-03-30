<?php

namespace App\Enums;

enum Accreditation: string
{
    case ManageBookings = 'manage-bookings';
    case ManageQualifications = 'manage-qualifications';
    case ManageUsers = 'manage-users';
    case PermitHolder = 'permit-holder';
}
