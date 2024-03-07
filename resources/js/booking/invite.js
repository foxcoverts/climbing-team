import { Calendar } from "@fullcalendar/core";
import listPlugin from "@fullcalendar/list";
import momentPlugin from "@fullcalendar/moment";
import superagent from "superagent";

const calendarEl = document.getElementById("fullcalendar");
window.Calendar = new Calendar(calendarEl, {
    plugins: [listPlugin, momentPlugin],
    views: {
        list: {
            type: "list",
            titleFormat: function () {
                return "Booking Invites";
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
    events: function (info, successCallback, failureCallback) {
        superagent
            .get("/api/booking/invite")
            .type("json")
            .set("accept", "application/json")
            .query({
                start: info.start.toISOString(),
                end: info.end.toISOString(),
            })
            .end((err, res) => {
                if (err) {
                    failureCallback(err);
                } else {
                    successCallback(
                        res.body.data.map((content) => {
                            if (Date.parse(content.end_at) < Date.now()) {
                                return false;
                            }

                            return {
                                id: content.id,
                                title:
                                    content.activity +
                                    " for " +
                                    content.group_name +
                                    " at " +
                                    content.location +
                                    attendanceToTitle(content.attendance),
                                start: content.start_at,
                                end: content.end_at,
                                url: content.url + "/attendance",
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
                        })
                    );
                }
            });
    },
    validRange(nowDate) {
        return { start: nowDate };
    },
});

function attendanceToTitle(attendance) {
    switch (attendance) {
        case "tentative":
            return " (maybe)";
        case "needs-action":
            return " (invited)";
        case null:
            return " (new booking)";
        default:
            return "";
    }
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

window.Calendar.render();
