<?php

namespace App\Enums;

enum CommentNotificationOption: string
{
    case All = 'all';
    case Reply = 'reply';
    case Leader = 'leader';
    case None = 'none';
}
