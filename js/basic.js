function onLoad() {
    select(".contents a").forEach(function(a) {
        if (a.href.indexOf("http://" + location.hostname) != 0
	    && a.href.indexOf("https://" + location.hostname) != 0
	    && a.href.indexOf("/") != 0) {
	    a.target = "_blank";
            a.appendChild(newElement("img", {"src": "images/linkmark.png"}));
        }
    });
}

function onResize() {
    if (window.innerWidth >= 1200) {
        setCss({
            ".pankuzu": {"margin-left": "auto"},
            ".menubutton": {"display": "none"},
            ".rightpane": {
                "margin-left": "10px",
                "width": "936px"
            },
            ".leftpane": {
                "width": "240px",
                "display": "block",
                "overflow-y": "auto",
                "height": "",
                "position": "",
                "margin-right": "10px",
                "box-shadow": ""
            }
        });
    } else if (window.innerWidth < 1200) {
        if (window.innerWidth > 500) {
            setCss({
                ".rightpane": {
                    "width": (document.body.clientWidth - 20) + "px",
                    "margin-left": "10px"
                },
                ".leftpane": {
                    "width": "400px",
                    "box-shadow": "-1px 3px 10px -3px rgba(0, 0, 0, 0.5)"
                }
            });
        } else {
            setCss({
                ".rightpane": {
                    "width": "100%",
                    "margin-left": "0"
                },
                ".leftpane": {
                    "width": "100%",
                    "box-shadow": ""
                }
            });
        }
        setCss({
            ".pankuzu": {
                "margin-left": "12px"
            },
            ".menubutton": {
                "display": "none"
            },
            ".leftpane": {
                "margin-right": "0",
                "position": "fixed",
                "height": "100%",
                "overflow-y": "scroll"
            }
        });
        if (select(".menubutton img")[0].src == "images/closemenubutton.svg") {
            setCss({
                ".leftpane": {
                    "display": "block"
                }
            });
        } else {
            setCss({
                ".leftpane": {
                    "display": "none"
                }
            });
        }
        if (pagename() == "article.php") {
            setCss({
                "textarea": {
                    "width": "100%"
                },
                "input[type='text']": {
                    "width": "70%"
                }
            });
        } else if (pagename() == "index.php") {
            setCss({
                ".centerpane .index_category_menu": {
                    "height": ""
                },
                ".centerpane .index_latest_articles": {
                    "height": ""
                }
            });
            select(".centerpane .index_category_menu li").forEach(function(li) {
                li.onclick = function() {
                    location.href = this.select("a")[0].href;
                };
            });
        }
    }
    if (window.innerHeight > document.body.offsetHeight) {
        setCss({
            ".footer": {
                "height": (heightOf(".footer") + (window.innerHeight - document.body.offsetHeight)) + "px"
            }
        });
    }
}

window.onload = function() {
    onLoad();
    onResize();
};

window.onresize =function() {
    onResize();
};
