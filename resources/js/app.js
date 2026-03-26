import "./bootstrap";
import "preline";
import { initTooltips } from "flowbite";
// import lottie from "lottie-web";
// import { defineElement } from "@lordicon/element";

// define "lord-icon" custom element with default properties
// defineElement(lottie.loadAnimation);

initTooltips(); // Only needed if dynamically loading content

document.addEventListener("livewire:navigated", () => {
    window.HSStaticMethods.autoInit();
});
