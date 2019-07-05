function onLoad() {
    select("select").forEach(function(selectBox) {
        selectBox.onchange = function() {
            var order = this.value;
            var new_url = "index.php";
            var query = location.search;
            if (query.match(/order=(desc|asc)/) != null) {
                query = query.replace(/order=(desc|asc)/, "order=" + order);
            } else {
                if (query.length > 0) {
                    query = query + "&order=" + order;
                } else {
                    query = "?order=" + order;
                }
            }
            location.href = new_url + query;
        };
    });
    select(".contents a").forEach(function(a) {
        if (a.href.indexOf("http") == 0
            && a.href.indexOf("http://" + location.hostname) != 0
            && a.href.indexOf("https://" + location.hostname) != 0) {
            var aUrl = new URL(a.href);
            a.target = aUrl.hostname;
            a.appendChild(newElement("img", {"src": "images/linkmark.svg"}));
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
        var h1 = heightOf(".centerpane .index_category_menu");
        var h2 = heightOf(".centerpane .index_latest_articles");
        if (h1 < h2) {
            setCss({
                ".centerpane .index_category_menu": {
                    "height": heightOf(".centerpane .index_latest_articles") + "px"
                }
            });
        } else if (h1 > h2) {
            setCss({
                ".centerpane .index_latest_articles": {
                    "height": heightOf(".centerpane .index_category_menu") + "px",
                }
            });
        }
        select(".centerpane .index_category_menu li").forEach(function(li) {
            li.onclick = function() {
                location.href = this.select("a")[0].href;
            };
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
