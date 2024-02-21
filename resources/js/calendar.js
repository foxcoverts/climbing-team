import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import listPlugin from "@fullcalendar/list";
import timeGridPlugin from "@fullcalendar/timegrid";
import momentPlugin from "@fullcalendar/moment";

const calendarEl = document.getElementById("fullcalendar");
window.Calendar = new Calendar(calendarEl, {
    plugins: [dayGridPlugin, listPlugin, timeGridPlugin, momentPlugin],
    views: {
        list: {
            type: "list",
            titleFormat: "{{D} MMM}, YYYY",
            buttonText: "list",
            duration: { days: 90 },
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
            slotMaxTime: "20:00",
            expandRows: true,
            dayHeaders: false,
        },
    },

    listDayFormat: "ddd, D MMM, YYYY",
    eventTimeFormat: "HH:mm",
    slotLabelFormat: "HH:mm",
    initialView: "list",
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
        right: "dayGridMonth,list,timeGridDay today prev,next",
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
        if (info.event.extendedProps.status == "cancelled") {
            info.el.style.color = "silver";
        }
        if (info.view.type == "timeGridDay") {
            var container = info.el.getElementsByClassName(
                "fc-event-title-container"
            )[0];

            var notesEl = document.createElement("div");
            notesEl.innerHTML = info.event.extendedProps.notes;
            container.appendChild(notesEl);
        }
    },
    viewClassNames(info) {
        switch (info.view.type) {
            case "dayGridMonth":
                window.location.hash = "#calendar";
                break;
            case "timeGridDay":
            case "listDay":
                window.location.hash = "#day";
                break;
            case "list":
            default:
                window.location.hash = "#list";
                break;
        }
        return [];
    },
    validRange(nowDate) {
        return { start: nowDate };
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

function locationHashChange() {
    switch (location.hash) {
        case "#calendar":
            window.Calendar.changeView("dayGridMonth");
            break;
        case "#list":
            window.Calendar.changeView("list");
            break;
        case "#day":
            window.Calendar.changeView("timeGridDay");
            break;
    }
}
window.addEventListener("hashchange", locationHashChange);
locationHashChange();

window.Calendar.render();
