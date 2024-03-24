import "./bootstrap";

import Alpine from "alpinejs";
import checkboxesData from "./alpine/data/checkboxes.js";
import Clipboard from "@ryangjchandler/alpine-clipboard";
import focus from "@alpinejs/focus";
import metaEnterDirective from "./alpine/directive/meta-enter.js";

window.dateString = function (date) {
    var time = Date.parse(date);
    if (isNaN(time)) {
        time = Date.now();
    }
    return new Date(time).toLocaleString(undefined, {
        weekday: "short",
        year: "numeric",
        month: "short",
        day: "numeric",
    });
};

window.Alpine = Alpine;

Alpine.plugin(Clipboard);
Alpine.plugin(focus);
Alpine.data("checkboxes", checkboxesData);
Alpine.directive("meta-enter", metaEnterDirective);
Alpine.start();
