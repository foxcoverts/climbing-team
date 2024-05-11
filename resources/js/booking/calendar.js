import { Calendar } from "@fullcalendar/core";
import dayGridPlugin from "@fullcalendar/daygrid";
import timeGridPlugin from "@fullcalendar/timegrid";
import listPlugin from "@fullcalendar/list";
import momentPlugin from "@fullcalendar/moment";
import moment from "moment";

const progressBarEl = document.createElement('div');
progressBarEl.classList.add('h-2', 'transition');
progressBarEl.style.opacity = 0;

var progressBarInterval = null;

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
    initialView:
        history.state && history.state.view
            ? history.state.view
            : window.innerWidth < 600
            ? "listMonth"
            : "dayGridMonth",
    initialDate:
        history.state && history.state.date ? history.state.date : new Date(),
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
    loading(loadingState) {
        if (loadingState) {
            progressBarEl.dataset.percent = 0;
            progressBarEl.style.transitionProperty = 'none';
            progressBarEl.style.width = '0';
            progressBarEl.classList.remove('bg-green-600', 'animate-pulse');
            progressBarEl.classList.add('bg-blue-600');
            progressBarEl.style.opacity = '1';

            if (progressBarInterval != null) {
                clearInterval(progressBarInterval);
            }

            progressBarInterval = setInterval(function () {
                progressBarEl.dataset.percent = Math.min(100, parseInt(progressBarEl.dataset.percent) + Math.random() * 5);
                progressBarEl.style.transitionProperty = 'background-color, opacity, width';
                progressBarEl.style.width = progressBarEl.dataset.percent + '%';

                if (progressBarEl.dataset.percent == 100) {
                    clearInterval(progressBarInterval);
                    progressBarInterval = null;

                    progressBarEl.classList.add('animate-pulse');
                }
            }, 100);
        } else {
            progressBarEl.style.width = '100%';
            progressBarEl.classList.add('bg-green-600');
            progressBarEl.classList.remove('bg-blue-600', 'animate-pulse');
            clearInterval(progressBarInterval);
            progressBarInterval = setTimeout(function () {
                progressBarEl.style.opacity = '0';
                progressBarInterval = null;
            }, 2000);
        }
    },
    eventDidMount(info) {
        if (info.view.type == "timeGridDay") {
            var container = info.el.getElementsByClassName(
                "fc-event-title-container"
            )[0];

            if (info.event.extendedProps.notes) {
                var notesEl = document.createElement("div");
                notesEl.innerHTML = info.event.extendedProps.notes;
                container.appendChild(notesEl);
            }

            if (info.event.extendedProps.attendance) {
                var attendanceEl = document.createElement("div");
                attendanceEl.innerHTML = attendanceToLabel(
                    info.event.extendedProps.attendance
                );
                container.appendChild(attendanceEl);
            }
        }
        if (info.isPast || info.event.extendedProps.status == "cancelled") {
            info.el.style.opacity = "0.5";
        }
    },
    datesSet(info) {
        var start = moment(info.start);
        var state = {
            view: info.view.type,
            date: start.format("YYYY-MM-DD"),
        };
        if (info.view.type != "timeGridDay") {
            start.add(7, "days");
            state.date = start.format("YYYY-MM-01");
        }
        history.replaceState(state, null);
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
        color: attendanceToColor(content.attendance, content.status),
        extendedProps: {
            activity: content.activity,
            groupName: content.group_name,
            location: content.location,
            notes: content.notes,
            status: content.status,
            attendance: content.attendance,
        },
    };
}

function attendanceToLabel(attendance) {
    switch (attendance) {
        case "needs-action":
            return "You have been invited.";
        case "tentative":
            return "You maybe attending.";
        case "accepted":
            return "You are going.";
        case "declined":
            return "You can't go.";
        default:
            return "";
    }
}

function attendanceToColor(attendance, status) {
    switch (status) {
        case "cancelled":
            return "silver";
    }
    switch (attendance) {
        case "needs-action":
            return "deepSkyBlue";
        case "tentative":
            return "gold";
        case "accepted":
            return "limeGreen";
        case "declined":
            return "fireBrick";
        default:
            return "silver";
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

    if (window.innerWidth < 600) {
        // Before calendar, below site header
        calendarEl.parentNode.insertBefore(progressBarEl, calendarEl);
    } else {
        // After calendar toolbar, before calendar main
        calendarEl.insertBefore(progressBarEl, calendarEl.getElementsByClassName('fc-view-harness')[0]);
    }
}

window.Calendar.render();
onWindowResize();
