<?php

namespace App\Enums;

enum BookingSettingCommentMail: string
{
    case All = 'all';
    case Updates = 'updates';
    case None = 'none';
}
