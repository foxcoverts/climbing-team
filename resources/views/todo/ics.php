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

    /*
    - [x] $table->ulid('id')->primary(); // VTODO: UID
    - [x] $table->string('summary'); // VTODO: SUMMARY
    - [x] $table->text('description'); // VTODO: DESCRIPTION
    - [x] $table->string('location')->nullable(); // VTODO: LOCATION
    - [x] $table->unsignedTinyInteger('priority')->default(5); // VTODO: PRIORITY (HIGH = 1, MEDIUM = 5, LOW = 9)
    - [x] $table->enum('status', ['needs-action', 'in-process', 'completed', 'cancelled'])->default('needs-action'); // VTODO: STATUS
    - [x] $table->timestamp('due_at', 6)->nullable(); // VTODO: DUE
    - [x] $table->timestamp('started_at', 6)->nullable(); // VTODO: DTSTART
    - [x] $table->timestamp('completed_at', 6)->nullable(); // VTODO: COMPLETED
    - [x] $table->unsignedInteger('sequence')->default(0); // VTODO: SEQUENCE
    - [ ] $table->timestamps(6); // VTODO: CREATED, LAST-MODIFIED

    - [x] VTODO:ORGANIZER
    */

    $calendar->addTodo($vtodo);
}

$eventFactory = new EventFactory;
$calendarFactory = new CalendarFactory(eventFactory: $eventFactory);
echo $calendarFactory->createCalendar($calendar);
