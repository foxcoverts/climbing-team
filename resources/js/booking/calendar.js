import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import momentPlugin from "@fullcalendar/moment";

const calendarEl = document.getElementById("fullcalendar");
window.Calendar = new Calendar(calendarEl, {
    plugins: [dayGridPlugin, timeGridPlugin, momentPlugin],
    views: {
        dayGridMonth: {
            titleFormat: "{MMMM} YYYY",
            fixedWeekCount: false,
        },
        timeGridDay: {
            titleFormat: "{{ddd, D} MMM}, YYYY",
            buttonText: "day",
            allDaySlot: false,
            scrollTime: "09:00",
            slotMinTime: "08:00",
            slotMaxTime: "20:00",
            expandRows: true,
            dayHeaders: false,
        },
    },
    eventTimeFormat: "HH:mm",
    slotLabelFormat: "HH:mm",
    initialView: "dayGridMonth",
    contentHeight: "auto",
    stickyHeaderDates: true,
    customButtons: {
        create: {
            text: "Add Booking",
            click() {
                window.location = "/booking/create";
            },
        },
    },
    headerToolbar: {
        left: "title",
        center: "",
        right: "dayGridMonth,timeGridDay today prev,next",
    },
    footerToolbar: {
        right: "create",
    },
    firstDay: 1,
    navLinks: true,
    events: {
        url: "/api/booking",
        success(content, response) {
            return content.data;
        },
        eventDataTransform(content) {
            return {
                id: content.id,
                title:
                    content.activity +
                    " for " +
                    content.group_name +
                    " at " +
                    content.location +
                    " (" +
                    content.status +
                    ")",
                start: content.start_at,
                end: content.end_at,
                url: content.url,
                color: bookingStatusToColor(content.status),
                extendedProps: {
                    activity: content.activity,
                    groupName: content.group_name,
                    location: content.location,
                    notes: content.notes,
                    status: content.status,
                },
            };
        },
    },
    eventDidMount(info) {
        if (info.view.type == "timeGridDay") {
            var container = info.el.getElementsByClassName(
                "fc-event-title-container"
            )[0];

            var notesEl = document.createElement("div");
            notesEl.innerHTML = info.event.extendedProps.notes;
            container.appendChild(notesEl);
        }
        if (info.isPast || info.event.extendedProps.status == "cancelled") {
            info.el.style.opacity = "0.5";
        }
    },
});

function bookingStatusToColor(status) {
    switch (status) {
        case "tentative":
            return "darkOrange";
        case "cancelled":
            return "silver";
        case "confirmed":
        default:
            return "dodgerBlue";
    }
}

window.Calendar.render();
