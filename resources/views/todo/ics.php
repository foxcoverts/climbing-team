<?php

use App\Enums\TodoStatus;
use App\iCal\Domain\Entity\Calendar;
use App\iCal\Domain\Entity\Todo;
use App\iCal\Domain\Enum\CalendarMethod;
use App\iCal\Domain\Enum\TodoStatus as VTodoStatus;
use App\iCal\Domain\ValueObject\Sequence;
use App\iCal\Presentation\Factory\CalendarFactory;
use App\iCal\Presentation\Factory\EventFactory;
use Eluceo\iCal\Domain\ValueObject\DateTime;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Location;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\Timestamp;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;

if (! isset($method) || ! $method instanceof CalendarMethod) {
    $method = CalendarMethod::Publish;
}

$calendar = new Calendar;
$calendar->setMethod($method);

foreach ($todos as $todo) {
    $organiser = new Organizer(
        new EmailAddress($todo->uid),
        config('mail.from.name')
    );

    $vtodo = new Todo(new UniqueIdentifier($todo->uid));
    $vtodo->setSequence(new Sequence($todo->sequence));
    $vtodo->setOrganizer($organiser);
    $vtodo->setSummary($todo->summary);
    $vtodo->setLastModified(new Timestamp($todo->updated_at));

    if (! empty($todo->description)) {
        $vtodo->setDescription($todo->description);
    }

    if (! empty($todo->location)) {
        $vtodo->setLocation(new Location($todo->location));
    }

    $vtodo->setPriority($todo->priority);

    $vtodo->setStatus(match ($todo->status) {
        TodoStatus::NeedsAction => VTodoStatus::NeedsAction,
        TodoStatus::InProcess => VTodoStatus::InProcess,
        TodoStatus::Completed => VTodoStatus::Completed,
        TodoStatus::Cancelled => VTodoStatus::Cancelled,
    });

    if (! empty($todo->due_at)) {
        $vtodo->setDue(new DateTime($todo->due_at, true));
    }

    if (! empty($todo->started_at)) {
        $vtodo->setStart(new DateTime($todo->started_at, true));
    }

    if (! empty($todo->completed_at)) {
        $vtodo->setCompleted(new DateTime($todo->completed_at, true));
    }

    $calendar->addTodo($vtodo);
}

$eventFactory = new EventFactory;
$calendarFactory = new CalendarFactory(eventFactory: $eventFactory);
echo $calendarFactory->createCalendar($calendar);
