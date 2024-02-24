import "./bootstrap";

import Alpine from "alpinejs";
import checkboxes from "./alpine/checkboxes.js";

window.Alpine = Alpine;

Alpine.data("checkboxes", checkboxes);
Alpine.start();
