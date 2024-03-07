import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import momentPlugin from "@fullcalendar/moment";
import superagent from "superagent";

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
    headerToolbar: {
        left: "title",
        center: "",
        right: "dayGridMonth,timeGridDay today prev,next",
    },
    firstDay: 1,
    navLinks: true,
    events: function (info, successCallback, failureCallback) {
        superagent
            .get("/api/booking")
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
                        })
                    );
                }
            });
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
        case "cancelled":
            return "silver";
        case "tentative":
        case "confirmed":
        default:
            return "limeGreen";
    }
}

window.Calendar.render();
