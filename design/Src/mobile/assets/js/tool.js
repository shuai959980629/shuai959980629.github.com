var tool = {
    addHandler: function(element, type, handler) {
        if (element.addEventListener) {
            element.addEventListener(type, handler, false);
        } else if (element.attachEvent) {
            element.attachEvent("on" + type, handler);
        } else {
            element["on" + type] = handler;
        }
    },
    removeHandler: function(element, type, handler) {
        if (element.removeEventListener) {
            element.removeEventListener(type, handler, false);
        } else if (element.detachEvent) {
            element.detachEvent("on" + type, handler);
        } else {
            element["on" + type] = null;
        }
    },
    addUrlPara:function(name, value) {
        var currentUrl = window.location.href.split('#')[0];
        if (/\?/g.test(currentUrl)) {
            if (/name=[-\w]{4,25}/g.test(currentUrl)) {
                currentUrl = currentUrl.replace(/name=[-\w]{4,25}/g, name + "=" + value);
            } else {
                currentUrl += "&" + name + "=" + value;
            }
        } else {
            currentUrl += "?" + name + "=" + value;
        }
        if (window.location.href.split('#')[1]) {
            window.location.href = currentUrl + '#' + window.location.href.split('#')[1];
        } else {
            window.location.href = currentUrl;
        }
    }
};