import "./bootstrap";

import Alpine from "alpinejs";
import ajax from "@imacrayon/alpine-ajax";
import checkboxesData from "./alpine/data/checkboxes.js";
import Clipboard from "@ryangjchandler/alpine-clipboard";
import focus from "@alpinejs/focus";
import intersect from "@alpinejs/intersect";
import mask from "@alpinejs/mask";
import metaEnterDirective from "./alpine/directive/meta-enter.js";
import persist from "@alpinejs/persist";
import phoneMagic from "./alpine/magic/phone.js";

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

Alpine.plugin(ajax);
Alpine.plugin(Clipboard);
Alpine.plugin(focus);
Alpine.plugin(intersect);
Alpine.plugin(mask);
Alpine.plugin(persist);
Alpine.data("checkboxes", checkboxesData);
Alpine.directive("meta-enter", metaEnterDirective);
Alpine.magic("phone", phoneMagic);
Alpine.start();
