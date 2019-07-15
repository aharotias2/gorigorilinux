function onLoad() {
    setCss({".contents .article dt a img": {
        "vertical-align": "middle",
        "margin": "3px"
    }});

    select("table").forEach(function(table) {
        var wrapper1 = newElement("div", {"class": "table-wrapper"});
        var wrapper2 = newElement("div", {"class": "table-scroll"});
        table.wrap(wrapper2);
        wrapper2.wrap(wrapper1);
    });

    select(".contents .figure").forEach(function(figure) {
        var wrapper = newElement("div", {"class": "figure-image"});
        figure.wrap(wrapper);
        wrapper.appendChild(newElement("div", {"class": "figcaption"}, [figure.alt]));
    });
    
    select(".article .figure-flat").forEach(function(figure) {
        var wrapper = newElement("div", {"class": "figure-image"});
        figure.wrap(wrapper);
        wrapper.appendChild(newElement("div", {"class": "figcaption"}, [figure.alt]));
    });

    select(".article h3").forEach(function(h3) {
        var datetime = select(".article .datetime")[0];
        h3.parentNode.insertBefore(datetime, h3.nextSibling);
    });
    
    select(".nav ul li a").forEach(function(element) {
        if (element.href.indexOf(location.search) >= 0) {
            element.href = 'javascript:void(0);';
        }
    });

    var outlineBlock = select(".outline")[0];
    if (outlineBlock != null) {
        var outline = newElement("ul");
        outlineBlock.appendChild(outline);
        var i = 1;
        var j = 1;
        var h4h5 = select(".article h4, .article h5");
        var prev = "";
        var h5list = null;
        for (var k = 0; k < h4h5.length; k++) {
            if (Object.values(h4h5[k].parentNode.classList).includes("code-header")) {
                continue;
            }
            if (h4h5[k].tagName == "H4") {
                if (prev == "H5") {
                    outline.appendChild(newElement("li", null, [h5list]));
                    h5list = null;
                }
                j = 1;
                h4h5[k].id = "outline" + i;
                h4h5[k].innerHTML = i + ". " + h4h5[k].innerHTML;
                i++;
                outline.appendChild(newElement("li", null, [
                    newElement("a", {"href": "#" + h4h5[k].id}, [h4h5[k].innerHTML])
                ]));
            } else if (h4h5[k].tagName == "H5") {
                if (prev == "H4") {
                    h5list = newElement("ul");
                }
                h4h5[k].id = "outline" + (i - 1) + "-" + j;
                h4h5[k].innerHTML = (i - 1) + "." + j + ". " + h4h5[k].innerHTML;
                j++;
                h5list.appendChild(newElement("li", null, [
                    newElement("a", {"href": "#" + h4h5[k].id}, [h4h5[k].innerHTML])
                ]));
            }
            prev = h4h5[k].tagName;
            if (h4h5[k].tagName == "H4") {
                h4h5[k].appendChild(newElement("a", null, [newElement("img", {
                    "class": "uparrow",
                    "src": "images/up-arrow.svg",
                    "onclick": function() {
                        scrollTo(0, 0);
                    }
                })]));
            }
        }

        if (h5list != null) {
            outline.appendChild(newElement("li", null, [h5list]));
        }
        
        setCss({".contents .article h4 .uparrow": {
            "float": "right",
            "margin-right": "30px"
        }});
        outlineBlock.insertBefore(newElement("h4", null, ["目次"]), outlineBlock.children[0]);
    }

    select(".code").forEach(function(code) {
        var codeName = code.getAttribute("name");
        if (codeName != null) {
            var pre = code.select("pre")[0];
            code.insertBefore(
                newElement(
                    "div", {
                        "class": "code-header"
                    }, [
                        newElement("h5", {}, [code.getAttribute("name")])
                    ]
                ),
                pre
            );
            pre.innerHTML = pre.innerHTML.replace(/\[Enter\]/g, "<img src=\"images/enter.svg\">");
        }
    });
                
    select("#comment_switcher").onclick = function() {
        setCss({
            ".comment_switch": {
                "display": "none"
            },
            ".post_block": {
                "display": "block"
            }
        });
        onResize();
    };

    select("#comment_cancel").onclick = function(event) {
        event.preventDefault();
        setCss({
            ".comment_switch": {
                "display": "block"
            },
            ".post_block": {
                "display": "none"
            }
        });
        select("#comment_text").value = "";
        select("#comment_user").value = "";
        select("#comment_mail").value = "";
    };
    
    select("#comment_submit").onclick = function() {
        if (select("#comment_text").value == "") {
            event.preventDefault();
            alert("コメント欄が空です。");
            return false;
        }
        if (select("#comment_user").value == "") {
            event.preventDefault();
            alert("お名前欄が空です");
            return false;
        }
    };

    select(".reply_button").forEach(function(replyButton) {
        replyButton.onclick = function() {
            if (replyButton.innerHTML == '返信') {
                var anchor = this.value;
                var entry = select("#commentform input[name='entry']").value;
                var articleName = select("#commentform input[name='article_name']").value;
                var form = newElement("form", {"action": "post.php", "method": "POST"});
                form.innerHTML = 'コメント:<br>'
                    + '<textarea rows="5" cols="80" name="comment"></textarea><br>'
                    + 'お名前:<br><input type="text" name="user_name"><br>'
                    + 'メール:<br><input type="text" name="mail_address"><br>'
                    + '<input type="hidden" name="entry" value="' + entry + '">'
                    + '<input type="hidden" name="article_name" value="' + articleName + '">'
                    + '<input type="hidden" name="anchor" value="' + anchor + '"><br>'
                    + '<button name="submit">コメントする</button>';
                this.parentNode.appendChild(form);
                this.innerHTML = 'キャンセル';
                this.style.width = "100px";
            } else if (this.innerHTML == 'キャンセル') {
                this.parentNode.select("form")[0].remove();
                this.innerHTML = '返信';
                this.style.width =  "40px";
            }
        };
    });

    select(".delete_button").forEach(function(deleteButton) {
        deleteButton.onclick = function() {
            if (this.innerHTML.trim() == '削除') {
                var anchor = this.value;
                var entry = select("#commentform input[name='entry']").value;
                var article_name = select("#commentform input[name='article_name']").value;
                var form = newElement("form", {"action": "delete.php", "method": "POST"});
                form.innerHTML = '<input type="hidden" name="entry" value="' + entry + '">'
                    + '<input type="hidden" name="article_name" value="' + article_name + '">'
                    + '<input type="hidden" name="anchor" value="' + anchor + '"><br>'
                    + '<button name="delete_button">コメントを削除する</button>';
                this.parentNode.appendChild(form);
                this.innerHTML = 'キャンセル';
                this.style.width = "100px";
            } else if (this.innerHTML == 'キャンセル') {
                this.parentNode.select("form")[0].remove();
                this.innerHTML = '削除';
                this.style.width = "40px";
            }
        };
    });
    
    select(".menubutton").forEach(function(menubutton) {
        menubutton.onclick = function() {
            var img = this.select("img")[0];
            var src = img.getAttribute("src");
            if (src == "images/menubutton.svg") {
                select(".leftpane")[0].style.display =  "block";
            } else {
                select(".leftpane")[0].style.display = "none";
            }
        };
    });

    select(".nav ul li ul li").forEach(function(li) {
        li.onclick = function() {
            location.href = this.select("a")[0].href;
        };
    });
    select(".latest_article_lite").forEach(function(latest_article_lite) {
        latest_article_lite.onclick = function() {
            location.href = this.children[0].children[0].href;
        }
    });
    select(".contents a").forEach(function(a) {
        if (a.href.indexOf("http") == 0
            && a.href.indexOf("http://" + location.hostname) != 0
            && a.href.indexOf("https://" + location.hostname) != 0) {
            var aUrl = new URL(a.href);
            a.target = aUrl.hostname;
            a.appendChild(newElement("img", {"src": "images/linkmark.svg", "style": "padding-left:2px;"}));
        }
    });

    select("#good-button").onclick = function(event) {
        var goodButton = this;
        var badButton = select("#bad-button");
        goodButton.disabled = "disabled";
        var goodParam = 0;
        var badParam = 0;
        if (goodButton.classList.length == 0) {
            goodParam = 1;
        } else {
            goodParam = -1;
        }
        if (badButton.classList.contains("goodbad-pressed")) {
            badParam = -1;
        }
        ajax({
            method: "POST",
            url: "add-goodbad.php",
            params: {
                'article_name': articleName,
                'good': goodParam,
                'bad': badParam
            },
            onSuccess: function(data) {
                var goodValue = Number(goodButton.select(".count")[0].innerHTML);
                var badValue = Number(badButton.select(".count")[0].innerHTML);
                goodButton.select(".count")[0].innerHTML = goodValue + goodParam;
                badButton.select(".count")[0].innerHTML = badValue + badParam;
                session.good = goodValue + goodParam;
                session.bad = badValue + badParam;
                if (goodParam > 0) {
                    goodButton.classList.add('goodbad-pressed');
                    badButton.classList.remove('goodbad-pressed');
                } else {
                    goodButton.classList.remove('goodbad-pressed');
                }
                goodButton.disabled = "";
            },
            onFail: function() {
                return;
            }
        });
    };
    
    select("#bad-button").onclick = function(event) {
        var badButton = this;
        var goodButton = select("#good-button");
        badButton.disabled = "disabled";
        var badParam = 0;
        var goodParam = 0;
        if (badButton.classList.length == 0) {
            badParam = 1;
        } else {
            badParam = -1;
        }
        if (goodButton.classList.contains("goodbad-pressed")) {
            goodParam = -1;
        }
        ajax({
            method: "POST",
            url: "add-goodbad.php",
            params: {
                "article_name": articleName,
                "good": goodParam,
                "bad": badParam
            },
            onSuccess: function(data) {
                var badValue = Number(badButton.select(".count")[0].innerHTML);
                var goodValue = Number(goodButton.select(".count")[0].innerHTML);
                badButton.select(".count")[0].innerHTML = badValue + badParam;
                goodButton.select(".count")[0].innerHTML = goodValue + goodParam;
                session.good = goodValue + goodParam;
                session.bad = badValue + badParam;
                if (badParam > 0) {
                    badButton.classList.add("goodbad-pressed");
                    goodButton.classList.remove("goodbad-pressed");
                } else {
                    badButton.classList.remove("goodbad-pressed");
                }
                badButton.disabled = "";
            },
            onFail: function() {
                return;
            }
        });
    };
    select(".cp_navi_2 > ul > li").forEach(function(e) {
	var childDiv = e.select("div")[0];
	if (childDiv != null) {
	    childDiv.style.display = "none";
	}
	e.onclick = function() {
	    var e1 = this;
	    select(".cp_navi_2 > ul > li").forEach(function(e2) {
		if (!e1.isSameNode(e2)) {
		    var childDiv = e2.select("div");
		    if (childDiv.length > 0) {
			e2.select("div")[0].style.display = "none";
		    }
		} else {
		    var childDiv = e1.select("div")[0];
		    var display = childDiv.style.display;
		    if (display == "none") {
			childDiv.style.display = "block";
		    } else {
			childDiv.style.display = "none";
		    }
		}
	    });
	};
    });
    onResize();
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
            },
            "textarea": {
                "width": (select(".article")[0].clientWidth - 100) + "px"
            },
            "input[type='text']": {
                "width": "20em"
            }
        });
        var rightHeight = select(".rightpane")[0].scrollHeight;
        var leftHeight = select(".leftpane")[0].scrollHeight - 22;
        if (leftHeight > rightHeight) {
            setCss({
                ".rightpane": {
                    "height": (leftHeight) + "px"
                }
            });
        }
    } else if (window.innerWidth < 1200) {
        if (window.innerWidth > 500) {
            setCss({
                ".rightpane": {
                    "width": (document.body.clientWidth - 20) + "px",
                    "margin-left": "10px",
                    "height": ""
                },
                ".leftpane": {
                    "width": "400px",
                    "box-shadow": "-1px 3px 10px -3px rgba(0, 0, 0, 0.5)"
                }
            });
            setCss({
                "textarea": {
                    "width": (select(".article")[0].clientWidth - 80) + "px",
                }
            });
        } else {
            setCss({
                ".rightpane": {
                    "width": "100%",
                    "margin-left": "0",
                    "height": ""
                },
                ".leftpane": {
                    "width": "100%",
                    "box-shadow": ""
                }
            });
            setCss({
                ".comment_block textarea": {
                    "width": (select(".rightpane")[0].clientWidth - 40) + "px",
                }
            });
        }
        setCss({
            ".pankuzu": {
                "margin-left": "6px"
            },
            ".menubutton": {
                "display": "block"
            },
            ".leftpane": {
                "margin-right": "0",
                "position": "fixed",
                "height": "100%",
                "overflow-y": "scroll"
            },
            "input[type='text']": {
                "width": "70%"
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
    }
    ["pre", ".table-scroll"].forEach(function(e) {
        select(e).forEach(function(element, index) {
            if (element.scrollWidth > element.clientWidth) {
                element.style["overflow-x"] = "scroll";
            } else {
                element.style["overflow-x"] = "hidden";
            }
        });
    });
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
};

window.onresize =function() {
    onResize();
};
