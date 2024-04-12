<?php

namespace App\Enums;

enum Accreditation: string
{
    case KitChecker = 'kit-checker';
    case ManageBookings = 'manage-bookings';
    case ManageQualifications = 'manage-qualifications';
    case ManageUsers = 'manage-users';
}
