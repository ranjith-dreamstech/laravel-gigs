import { getEventTarget } from "../utils/dom";
if (typeof window.CustomEvent !== "function") {
    function CustomEvent(type, params) {
        params = params || { bubbles: false, cancelable: false, detail: undefined };
        const event = document.createEvent("CustomEvent");
        event.initCustomEvent(type, params.bubbles, params.cancelable, params.detail);
        return event;
    }

    CustomEvent.prototype = Event.prototype;
    window.CustomEvent = CustomEvent;
}
function delta(e) {
    return Math.max(-1, Math.min(1, e.wheelDelta || -e.deltaY));
}
var scroll = function (e) {
    e.preventDefault();
    var ev = new CustomEvent("increment", {
        bubbles: true,
    });
    ev.delta = delta(e);
    getEventTarget(e).dispatchEvent(ev);
};
function scrollMonth(fp) {
    return function (e) {
        e.preventDefault();
        var mDelta = delta(e);
        fp.changeMonth(mDelta);
    };
}
function scrollPlugin() {
    return function (fp) {
        var monthScroller = scrollMonth(fp);
        return {
            onReady: function () {
                if (fp.timeContainer) {
                    fp.timeContainer.addEventListener("wheel", scroll);
                }
                if (fp.yearElements) {
                    fp.yearElements.forEach(function (yearElem) {
                        return yearElem.addEventListener("wheel", scroll);
                    });
                }
                if (fp.monthElements) {
                    fp.monthElements.forEach(function (monthElem) {
                        return monthElem.addEventListener("wheel", monthScroller);
                    });
                }
                fp.loadedPlugins.push("scroll");
            },
            onDestroy: function () {
                if (fp.timeContainer) {
                    fp.timeContainer.removeEventListener("wheel", scroll);
                }
                if (fp.yearElements) {
                    fp.yearElements.forEach(function (yearElem) {
                        return yearElem.removeEventListener("wheel", scroll);
                    });
                }
                if (fp.monthElements) {
                    fp.monthElements.forEach(function (monthElem) {
                        return monthElem.removeEventListener("wheel", monthScroller);
                    });
                }
            },
        };
    };
}
export default scrollPlugin;
