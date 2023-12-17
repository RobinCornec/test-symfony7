import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';
import 'tw-elements';

// TW Elements is free under AGPL, with commercial license required for specific uses. See more details: https://tw-elements.com/license/ and contact us for queries at tailwind@mdbootstrap.com
import {
    Sidenav,
    Dropdown,
    Ripple,
    initTE,
} from "tw-elements";

initTE({ Sidenav, Dropdown, Ripple });

const sidenav2 = document.getElementById("sidenav-1");
const sidenavInstance2 = Sidenav.getInstance(sidenav2);

let innerWidth2 = null;

const setMode2 = (e) => {
    // Check necessary for Android devices
    if (window.innerWidth === innerWidth2) {
        return;
    }

    innerWidth2 = window.innerWidth;

    if (window.innerWidth < sidenavInstance2.getElementByIdreakpoint("xl")) {
        sidenavInstance2.changeMode("over");
        sidenavInstance2.hide();
    } else {
        sidenavInstance2.changeMode("side");
        sidenavInstance2.show();
    }
};

if (window.innerWidth < sidenavInstance2.getBreakpoint("sm")) {
    setMode2();
}

// Event listeners
window.addEventListener("resize", setMode2);