import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import momentPlugin from "@fullcalendar/moment";

const calendarEl = document.getElementById("fullcalendar");
window.Calendar = new Calendar(calendarEl, {
    plugins: [dayGridPlugin, listPlugin, timeGridPlugin, momentPlugin],
    views: {
        listMonth: {
            buttonText: "month",
        },
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
            slotMaxTime: "21:00",
            expandRows: true,
            dayHeaders: false,
        },
    },
    initialView: window.innerWidth < 600 ? "listMonth" : "dayGridMonth",
    listDayFormat: "ddd, D MMM, YYYY",
    listDaySideFormat: false,
    eventTimeFormat: "HH:mm",
    slotLabelFormat: "HH:mm",
    contentHeight: "auto",
    stickyHeaderDates: true,
    headerToolbar: {
        left: "title",
        center: "",
        right:
            (window.innerWidth < 600 ? "listMonth" : "dayGridMonth") +
            ",timeGridDay today prev,next",
    },
    firstDay: 1,
    navLinks: true,
    windowResize: onWindowResize,
    datesSet: onWindowResize,
    events: function (info, successCallback, failureCallback) {
        window
            .axios("/api/booking", {
                params: {
                    start: info.start.toISOString(),
                    end: info.end.toISOString(),
                },
            })
            .then((res) => {
                successCallback(res.data.data.map(transformEvent));
            })
            .catch(failureCallback);
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

function transformEvent(content) {
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
}

function bookingStatusToColor(status) {
    switch (status) {
        case "cancelled":
            return "silver";
        case "tentative":
        case "confirmed":
        default:
            return "limeGreen";
    }
}

function onWindowResize() {
    if (window.Calendar.view.type == "timeGridDay") {
    } else if (
        window.Calendar.view.type == "dayGridMonth" &&
        window.innerWidth < 600
    ) {
        window.Calendar.changeView("listMonth");
    } else if (
        window.Calendar.view.type == "listMonth" &&
        window.innerWidth >= 600
    ) {
        window.Calendar.changeView("dayGridMonth");
    }
}

window.Calendar.render();
