<?php

use App\iCal\Domain\Entity\Calendar;
use App\iCal\Domain\Entity\Todo;
use App\iCal\Domain\Enum\CalendarMethod;
use App\iCal\Presentation\Factory\CalendarFactory;
use App\iCal\Presentation\Factory\EventFactory;
use Eluceo\iCal\Domain\ValueObject\EmailAddress;
use Eluceo\iCal\Domain\ValueObject\Organizer;
use Eluceo\iCal\Domain\ValueObject\UniqueIdentifier;

if (! isset($method) || ! $method instanceof CalendarMethod) {
    $method = CalendarMethod::Publish;
}

$calendar = new Calendar();
$calendar->setMethod($method);

foreach ($todos as $todo) {
    $organiser = new Organizer(
        new EmailAddress($todo->uid),
        config('mail.from.name')
    );

    $vtodo = new Todo(new UniqueIdentifier($todo->uid));
    $vtodo->setOrganizer($organiser);
    $vtodo->setSummary($todo->summary);

    if (! empty($todo->description)) {
        $vtodo->setDescription($todo->description);
    }

    // $table->ulid('id')->primary(); // VTODO: UID
    // $table->string('summary'); // VTODO: SUMMARY
    // $table->text('description'); // VTODO: DESCRIPTION
    // $table->string('location')->nullable(); // VTODO: LOCATION
    // $table->unsignedTinyInteger('priority')->default(5); // VTODO: PRIORITY (HIGH = 1, MEDIUM = 5, LOW = 9)
    // $table->enum('status', ['needs-action', 'in-process', 'completed', 'cancelled'])->default('needs-action'); // VTODO: STATUS
    // $table->timestamp('due_at', 6)->nullable(); // VTODO: DUE
    // $table->timestamp('started_at', 6)->nullable(); // VTODO: DTSTART
    // $table->timestamp('completed_at', 6)->nullable(); // VTODO: COMPLETED
    // $table->unsignedInteger('sequence')->default(0); // VTODO: SEQUENCE
    // $table->timestamps(6); // VTODO: CREATED, LAST-MODIFIED

    // VTODO:ORGANIZER

    $calendar->addTodo($vtodo);
}

$eventFactory = new EventFactory();
$calendarFactory = new CalendarFactory(eventFactory: $eventFactory);
echo $calendarFactory->createCalendar($calendar);
