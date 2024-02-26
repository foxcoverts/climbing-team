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
                return "Deleted Bookings";
            },
            duration: { years: 1 },
        },
    },
    initialView: "list",
    listDayFormat: "ddd, D MMM, YYYY",
    eventTimeFormat: "HH:mm",
    contentHeight: "auto",
    stickyHeaderDates: true,
    headerToolbar: {
        left: "title",
        center: "",
        right: "",
    },
    navLinks: true,
    events: {
        url: "/api/trash/booking",
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
                className: ["fc-event-" + content.status],
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
    validRange(nowDate) {
        return { start: nowDate };
    },
});

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

window.Calendar.render();
