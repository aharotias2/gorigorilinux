function setCss(defs) {
    function innerSetCss(selector, properties) {
        var list = document.querySelectorAll(selector);
        for (var i = 0; i < list.length; i++) {
            var elem = list[i];
            var styleNames = Object.keys(properties);
            for (var j = 0; j < styleNames.length; j++) {
                elem.style[styleNames[j]] = properties[styleNames[j]];
            }
        }
    }
    
    var selectors = Object.keys(defs);
    for (var i = 0; i < selectors.length; i++) {
        innerSetCss(selectors[i], defs[selectors[i]]);
    }
}

function getCss(selector, property) {
    return document.querySelector(selector).style[property];
}

function heightOf(selector) {
    return document.querySelector(selector).clientHeight;
}

function widthOf(selector) {
    return document.querySelector(selector).clientWidth;
}

function pagename() {
    var url = location.pathname;
    var page = url.slice(url.lastIndexOf('/') + 1, url.length);
    return page;
}

function newElement(elementName, attributes, children) {
    var e = document.createElement(elementName);
    if (attributes != null) {
        Object.keys(attributes).forEach(function(attr) {
            if (attr == "class") {
                attributes[attr].split(" ").forEach(function(className) {
                    e.classList.add(className);
                });
            } else {
                e[attr] = attributes[attr];
            }
        });
    }
    if (children != null) {
        children.forEach(function(child) {
            if (typeof child == "string") {
                e.innerHTML += child;
            } else {
                e.appendChild(child);
            }
        });
    }
    return e;
}

Element.prototype.select = function(selector) {
    var result = null;
    if (selector.charAt(0) == "#") {
        result = this.querySelector(selector);
    } else {
        var nodeList = this.querySelectorAll(selector);
	if (nodeList != null) {
            result = Array.prototype.slice.call(nodeList, 0);
	}
    }
    return result != null ? result : {};
};

Element.prototype.wrap = function(wrapper) {
    var parent = this.parentNode;
    parent.insertBefore(wrapper, this);
    wrapper.appendChild(this);
};

function select(selector) {
    return document.body.select(selector);
}

HTMLCollection.prototype.forEach = function(callback) {
    var length = this.length;
    for (var i = 0; i < length; i++) {
        callback(this[i], i);
    }
};


// Ajax

function ajax(request) {
    req = new XMLHttpRequest();
    req.onreadystatechange = function() {
        if (req.readyState == 4) { // complete
            if (req.status == 200) { // success
                if (request.onSuccess != null && typeof request.onSuccess == "function") {
                    request.onSuccess(req.responseText);
                }
            } else {
                if (request.onFail != null && typeof request.onFail == "function") {
                    request.onFail(req.responseText);
                }
            }
        } else {

        }
    };

    if (request.method == "GET") {
        req.open(request.method, request.url, true);
        req.send(null);
    } else if (request.method == "POST") {
        parameters = "";
        if (request.params) {
            Object.keys(request.params).forEach(function(key) {
                if (parameters.length > 0) {
                    parameters += "&";
                }
                parameters += key + "=" + request.params[key];
            });
        }
        req.open(request.method, request.url, true);
        req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        req.send(parameters);
    }   
}

