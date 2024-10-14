<?php

namespace App\Enums;

enum TodoAttendeeStatus: string
{
    /**
     * Person has confirmed they are available to help with a todo.
     */
    case Accepted = 'accepted';

    /**
     * Person has started their work on a todo.
     */
    case InProcess = 'in-process';

    /**
     * Person has responded 'maybe available' to a todo. It should not be assumed they will help.
     */
    case Tentative = 'tentative';

    /**
     * Person has been invited to the todo but has not responded.
     */
    case NeedsAction = 'needs-action';

    /**
     * Person has completed their work on a todo.
     */
    case Completed = 'completed';

    /**
     * Person has confirmed they are not available for a todo.
     */
    case Declined = 'declined';

    /**
     * Compare with another TodoAttendeeStatus.
     */
    public function compare(TodoAttendeeStatus $other): int
    {
        return $this->rank() <=> $other->rank();
    }

    protected function rank(): int
    {
        return match ($this) {
            self::Accepted => 0,
            self::InProcess => 1,
            self::Tentative => 2,
            self::NeedsAction => 3,
            self::Completed => 4,
            self::Declined => 5,
        };
    }
}
