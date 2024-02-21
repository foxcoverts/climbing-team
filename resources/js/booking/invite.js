import { Calendar } from "@fullcalendar/core";
import listPlugin from "@fullcalendar/list";
import momentPlugin from "@fullcalendar/moment";

const calendarEl = document.getElementById("fullcalendar");
window.Calendar = new Calendar(calendarEl, {
    plugins: [listPlugin, momentPlugin],
    views: {
        list: {
            type: "list",
            titleFormat: function () {
                return "Booking Invites";
            },
            buttonText: "list",
            duration: { years: 1 },
        },
    },

    listDayFormat: "ddd, D MMM, YYYY",
    eventTimeFormat: "HH:mm",
    initialView: "list",
    contentHeight: "auto",
    stickyHeaderDates: true,
    headerToolbar: {
        left: "title",
        center: "",
        right: "",
    },
    firstDay: 1,
    navLinks: true,
    events: {
        url: "/api/booking/invite",
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
                url: content.url + "/attendance",
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

window.Calendar.render();
