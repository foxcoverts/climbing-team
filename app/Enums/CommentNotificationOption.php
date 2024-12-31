<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CommentNotificationOption: string implements HasLabel
{
    case All = 'all';
    case Reply = 'reply';
    case Leader = 'leader';
    case None = 'none';

    public function getLabel(): ?string {
        return __("app.notification.comment-option.{$this->value}");
    }
}
