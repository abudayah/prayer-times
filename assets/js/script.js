
function updateClock() {
    var current = new Date();
    var dname = current.getDay(),
        mon = current.getMonth(),
        num = current.getDate(),
        yr = current.getFullYear(),
        hr = current.getHours(),
        min = current.getMinutes(),
        sec = current.getSeconds(),
        pr = "AM";

    if (hr == 0) {
        hr = 12;
    }
    if (hr > 12) {
        hr = hr - 12;
        pr = "PM";
    }
    Number.prototype.pad = function (digits) {
        for (var n = this.toString(); n.length < digits; n = 0 + n);
        return n;
    }

    var months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Decr"];
    var weeks = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fr", "Sat"];
    var ids = ["hour", "min", "sec", "period"];
    var values = [hr.pad(2), min.pad(2), sec.pad(2), pr];

    for (var i = 0; i < ids.length; i++)
        document.getElementById(ids[i]).firstChild.nodeValue = values[i];
}

$(function () {
    /** this is the timer option used in the Prayers App */
    $('.timer').startTimer({
        onComplete: function () {
            /* reloading the page to update the content*/
            location.reload();
        }
    });
    /** End of the timer option used */
});

/** initializing the diigital clock */
$(function initClock() {
    updateClock();
    window.setInterval("updateClock()", 1);
});

