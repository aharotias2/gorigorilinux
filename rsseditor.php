#!/usr/bin/php-cgi
<?php session_start(); ?>
<?php if ($_SESSION['role'] !== "admin") { ?>

    <!DOCTYPE html>
    <html lang="ja">
        <head><meta http-equiv="refresh" content="0; URL='index.php'" /></head>
        <body></body>
    </html>

<?php } else { ?>
    <?php
    $rssfile = fopen("rss.xml", "r") or die();
    while (($line = fgets($rssfile)) !== false && $line != "<rss version=\"2.0\" xmlns:atom=\"http://www.w3.org/2005/Atom\">\n");
    ?>
    <!DOCTYPE html>
    <html lang="ja">
        <head>
            <meta charset="UTF-8">
            <title>RSSフィードの編集</title>
            <?php include("closed/php/css.php"); ?>
            <style type="text/css">
             .contents {
                 background-color: rgba(40, 50, 60, 0.4);
                 padding: 30px;
             }
             td {
                 padding: 2px;
             }
             input, textarea {
                 background-color: #303840;
                 color: #D0D8E0;
                 padding: 5px;
                 border: 1px inset #707880;
                 border-radius: 3px;
             }
             button {
                 padding: 5px;
                 background-color: orangered;
                 border: 2px outset orangered;
                 color: white;
                 font-weight: bold;
                 width: 160px;
                 border-radius: 3px;
             }
             #rsscontents {
                 display: none;
             }
             #rssChannelItem {
                 display: none;
             }
             .selectboxes {
                 margin: 5px 30px;
             }
             select {
                 padding: 5px;
                 background-color: #606870;
                 border: 1px outset #303840;
                 border-radius: 3px;
                 color: #E0E8F0;
             }
             .channelItemBlock {
                 border: 2px groove #707880;
                 margin-top: 20px;
                 margin-bottom: 20px;
                 padding: 10px 5px;
             }
             .required {
                 color: red;
             }
             .deleteItem {
                 width: 50px;
                 padding: 10px;
                 float: right;
                 position: relative;
                 top: 0px;
                 right: 20px;
                 display: none;
             }
            </style>
            <script type="text/javascript">
             function renameItemProperties() {
                 select("form .channelItemBlock h2").forEach(function(itemName, itemNumber) {
                     itemName.innerHTML = "Item No. " + (itemNumber + 1);
                 });
                 ["big_cate", "mid_cate", "small_cate", "item_title", "item_link",
                  "item_description", "item_author", "item_category", "item_comment",
                  "item_guid", "item_publication_date", "item_source", "item_year",
                  "item_month", "item_day", "item_hours", "item_minutes"].forEach(function(name) {
                      select("form ." + name).forEach(function(itemTitle, itemNumber) {
                          itemTitle.setAttribute("name", name + itemNumber);
                      });
                  });
             }

	     function setDateString() {
                 var itemDate = this.parentNode.children;
                 var year = itemDate[0].value;
                 var month = itemDate[1].value;
                 var day = itemDate[2].value;
                 var hours = itemDate[3].value;
                 if (hours < 0) {
                     hours = 24 + hours;
                 }
                 var minutes = itemDate[4].value;
                 var date = new Date(year + "-" + month + "-" + day + " " + hours + ":" + minutes + ":00");
                 this.parentNode.parentNode.children[1].value = date.toUTCString();
             }
             
             function bigCateChange(event) {
                 var bigCate = this;
                 this.parentNode.select(".mid_cate")[0].innerHTML = "<option value=\"\"></option>";
                 this.parentNode.select(".small_cate")[0].innerHTML = "<option value=\"\"></option>";
                 this.parentNode.select(".articles")[0].innerHTML = "<option value=\"\"></option>";
                 var reqUri = "get-article-list.php?cate=" + this.value;
                 console.log("Request: " + reqUri);
                 ajax({
                     method: "GET",
                     url: reqUri,
                     type: "json",
                     onSuccess: function(resText) {
                         console.log("Get response: " + resText);
			 var result = JSON.parse(resText);
                         var midCate = bigCate.parentNode.select(".mid_cate")[0];
                         result.forEach(function(midCateName) {
                             var midOpt = newElement("option",
                                                     {"value":midCateName},
                                                     [midCateName]);
                             midCate.appendChild(midOpt);
                         });
                     },
                     onFail: null
                 });
             }

             function midCateChange(event) {
                 var bigCate = this.parentNode.select(".big_cate")[0];
                 this.parentNode.select(".small_cate")[0].innerHTML = "<option value=\"\"></option>";
                 this.parentNode.select(".articles")[0].innerHTML = "<option value=\"\"></option>";
                 var reqUri = "get-article-list.php?cate=" + bigCate.value + "/" + this.value;
                 console.log("Request: " + reqUri);
                 ajax({
                     method: "GET",
                     url: reqUri,
                     onSuccess: function(resText) {
                         console.log("Get response: " + resText);
                         var result = JSON.parse(resText);
                         var smallCate = bigCate.parentNode.select(".small_cate")[0];
                         result.forEach(function(smallCateName) {
                             var smallOpt = newElement("option",
                                                       {"value":smallCateName},
                                                       [smallCateName]);
                             smallCate.appendChild(smallOpt);
                         });
                     },
                     onFail: null
                 });
             }

             function smallCateChange(event) {
                 var smallCate = this;
                 this.parentNode.select(".articles")[0].innerHTML = "<option value=\"\"></option>";
                 var bigCate = this.parentNode.select(".big_cate")[0].value;
                 var midCate = this.parentNode.select(".mid_cate")[0].value;
                 var reqUri = "get-article-list.php?cate=" + bigCate + "/" + midCate + "/" + this.value;
                 console.log("Request: " + reqUri);
                 ajax({
                     method: "GET",
                     url: reqUri,
                     onSuccess: function(resText) {
                         console.log("Get response: " + resText);
                         var result = JSON.parse(resText);
                         var articles = smallCate.parentNode.select(".articles")[0];
                         result.forEach(function(articleName) {
                             var articleOpt = newElement("option",
                                                         {"value":articleName},
                                                         [articleName]);
                             articles.appendChild(articleOpt);
                         });
                     },
                     onFail: null
                 });
             }

             function articleChange(event) {
                 var bigCate = this.parentNode.select(".big_cate")[0];
                 var midCate = this.parentNode.select(".mid_cate")[0];
                 var smallCate = this.parentNode.select(".small_cate")[0];
                 var articles = this;
                 var reqUri = "get-article-list.php?article="
                            + bigCate.value + "/"
                            + midCate.value + "/"
                            + smallCate.value + "/"
                            + this.value;
                 console.log("Request: " + reqUri);
                 ajax({
                     method: "GET",
                     url: reqUri,
                     onSuccess: function(resText) {
                         console.log("Get response: " + resText);
                         var result = JSON.parse(resText);
                         var itemBlock = articles.parentNode.parentNode.select(".channelItemTable")[0];
                         itemBlock.select(".item_title")[0].value = result.itemTitle;
                         itemBlock.select(".item_link")[0].value = encodeURI(result.itemLink);
                         itemBlock.select(".item_description")[0].innerHTML = result.itemDescription;
                         itemBlock.select(".item_author")[0].value = result.itemAuthor;
                         itemBlock.select(".item_category")[0].value = result.itemCategory;
                         itemBlock.select(".item_comment")[0].value = result.itemComment;
                         itemBlock.select(".item_guid")[0].value = encodeURI(result.itemGuid);
                         itemBlock.select(".item_source")[0].value = result.itemSource;
			 console.log(result.itemPubDate);
			 var ftime = result.itemPubDate.split('-');
			 itemBlock.select(".item_year")[0].value = ftime[0];
			 itemBlock.select(".item_month")[0].value = ftime[1];
			 itemBlock.select(".item_day")[0].value = ftime[2];
			 itemBlock.select(".item_hours")[0].value = ftime[3];
			 var itemMinutes = Number(ftime[4]);
			 if (itemMinutes < 10) {
			     itemMinutes = "00";
			 } else {
			     itemMinutes = Math.round(itemMinutes / 10) * 10;
			 }
			 itemBlock.select(".item_minutes")[0].value = itemMinutes;
			 itemBlock.select(".item_minutes")[0].onchange = setDateString;
			 itemBlock.select(".item_minutes")[0].onchange();
                     },
                     onFail: null
                 });
             }

             window.onload = function() {
                 setInterval(function() {
                     var date = new Date();
                     date.setHours(date.getHours());
                     var dateinput = select("#lastBuildDate");
                     dateinput.value = date.toUTCString();
                 }, 1);

                 var submitButton = select("#formSubmit");
                 var rssForm = select("#rssForm");
                 for (var i = 0; i < 20; i++) {
                     var itemTable = select("#rssChannelItem").children[0].cloneNode(true);
                     var block = newElement("div", {"class": "channelItemBlock"}, [
                         newElement("h2", {}, ["Item No. " + (i + 1)]),
                         itemTable
                     ]);
                     var dateSelectors = block.select(".itemDate select");
                     dateSelectors[0].onchange = setDateString;
                     dateSelectors[1].onchange = setDateString;
                     dateSelectors[2].onchange = setDateString;
                     dateSelectors[3].onchange = setDateString;
                     dateSelectors[4].onchange = setDateString;
                     dateSelectors[0].onchange();
                     rssForm.insertBefore(block, submitButton);
                 }

                 renameItemProperties();
                 
                 var rss = select("#rsscontents");
                 if (rss.children.length > 0) {
                     var itemCount = 0;
                     rss.children[0].children.forEach(function(child, index) {
                         if (child.innerHTML != "") {
                             var value = child.innerHTML; 
                             console.log(child.tagName + ": " + child.innerHTML);
                             try {
                                 function setIfExists(destSelector, child, srcSelector, getFunction) {
                                     var a = child.select(srcSelector)[0];
                                     if (a != null) {
                                         getFunction(select(destSelector)[0], a);
                                     }
                                 }
                                 switch (child.tagName) {
                                     case "TITLE":
                                         select("input[name='title']")[0].setAttribute("value", value);
                                         break;
                                     case "CH-LINK":
                                         console.log("link found\n");
                                         select("input[name='link']")[0].setAttribute("value", encodeURI(value));
                                         break;
                                     case "DESCRIPTION":
                                         console.log("description found\n");
                                         select("input[name='description']")[0].setAttribute("value", value);
                                         break;
                                     case "LANGUAGE":
                                         select("input[name='language']")[0].setAttribute("value", value);
                                         break;
                                     case "RATING":
                                         select("input[name='rating']")[0].setAttribute("value", value);
                                         break;
                                     case "COPYRIGHT":
                                         select("input[name='copyright']")[0].setAttribute("value", value);
                                         break;
                                     case "PUBDATE":
                                         select("input[name='pub_date']")[0].setAttribute("value", value);
                                         break;
                                     case "LASTBUILDDATE":
                                         select("input[name='last_build_date']")[0].setAttribute("value", value);
                                         break;
                                     case "CATEGORY":
                                         select("input[name='category']")[0].setAttribute("value", value);
                                         break;
                                     case "DOCS":
                                         select("input[name='docs']")[0].setAttribute("value", value);
                                         break;
                                     case "TTL":
                                         select("input[name='ttl']")[0].setAttribute("value", value);
                                         break;
                                     case "MANAGINGEDITOR":
                                         select("input[name='managing_editor']")[0].setAttribute("value", value);
                                         break;
                                     case "WEBMASTER":
                                         select("input[name='web_master']")[0].setAttribute("value", value);
                                         break;
                                     case "SKIPHOURS":
                                         child.children.forEach(function(hour) {
                                             select("input[name='skip_hour']")[hour.innerHTML].checked = true;
                                         });
                                         break;
                                     case "SKIPDAYS":
                                         child.children.forEach(function(day) {
                                             select("input[name='skip_day']")[day.innerHTML].checked = true;
                                         });
                                         break;
                                     case "IMAGE":
                                         type_a = function(a, b) { a.value = b.innerHTML; };
                                         type_b = function(a, b) { a.innerHTML = b.innerHTML; };
                                         setIfExists("input[name='image_title']", child, "title", type_a);
                                         setIfExists("input[name='image_url']", child, "url", type_a);
                                         setIfExists("input[name='image_link']", child, "ch-link", type_a);
                                         setIfExists("input[name='image_width']", child, "width", type_a);
                                         setIfExists("input[name='image_height']", child, "height", type_a);
                                         setIfExists("input[name='image_description']", child, "description", type_a);
                                         break;
                                     case "ITEM":
                                         type_a = function(a, b) { a.value = b.innerHTML; };
                                         type_b = function(a, b) { a.innerHTML = b.innerHTML; };
                                         setIfExists("input[name='item_title" + itemCount + "']", child, "title", type_a);
                                         setIfExists("input[name='item_link" + itemCount + "']", child, "ch-link", type_a);
                                         setIfExists("textarea[name='item_description" + itemCount + "']", child, "description", type_b);
                                         setIfExists("input[name='item_author" + itemCount + "']", child, "author", type_a);
                                         setIfExists("input[name='item_category" + itemCount + "']", child, "category", type_a);
                                         setIfExists("input[name='item_comment" + itemCount + "']", child, "comments", type_a);
                                         setIfExists("input[name='item_guid" + itemCount + "']", child, "guid", type_a);
                                         setIfExists("input[name='item_publication_date" + itemCount + "']", child, "pubDate", type_a);
                                         setIfExists("input[name='item_source" + itemCount + "']", child, "source", type_a);
                                         var pubDate = child.select("pubDate")[0];
                                         if (pubDate != null && pubDate.innerHTML != null) {
                                             var itemDate = new Date(pubDate.innerHTML);
                                             var dateInputs = select("input[name='item_publication_date" + itemCount + "']")[0];
                                             dateInputs.parentNode.select("select[name='item_year" + itemCount + "']")[0].value = itemDate.getFullYear();
                                             dateInputs.parentNode.select("select[name='item_month" + itemCount + "']")[0].value = itemDate.getMonth() + 1;
                                             dateInputs.parentNode.select("select[name='item_day" + itemCount + "']")[0].value = itemDate.getDate();
                                             dateInputs.parentNode.select("select[name='item_hours" + itemCount + "']")[0].value = itemDate.getHours();
                                             var minutes = Math.floor(itemDate.getMinutes() / 10) * 10;
                                             if (minutes == 0) {
                                                 minutes = "00";
                                             }
                                             dateInputs.parentNode.select("select[name='item_minutes" + itemCount + "']")[0].value = minutes;
                                             console.log("itemDate.getMinutes(): " + minutes);
                                         }
                                         itemCount++;
                                         break;
                                 }
                             } catch (e) {
                                 console.log("ERROR: " + e.name + " " + e.message);
                                 console.log(e.stack);
                             }
                         }
                     });
                 }
                 
                 select("form .selectboxes .big_cate").forEach(function(bigCate) {
                     bigCate.onchange = bigCateChange;
                 });
                 
                 select("form .selectboxes .mid_cate").forEach(function(midCate) {
                     midCate.onchange = midCateChange;
                 });

                 select("form .selectboxes .small_cate").forEach(function(smallCate) {
                     smallCate.onchange = smallCateChange;
                 });

                 select("form .selectboxes .articles").forEach(function(articles) {
                     articles.onchange = articleChange;
                 });

                 select("#addNewItem").onclick = function(event) {
                     event.preventDefault();
                     var newItemBlock = select("#rssChannelItem").children[0].cloneNode(true);
                     newItemBlock.select(".deleteItem")[0].style.display = "block";
                     newItemBlock.select(".deleteItem")[0].onclick = function(event) {
                         this.parentNode.parentNode.remove();
                         select(".channelItemBlock").forEach(function(block) {
                             renameItemProperties();
                         });
                     };
                     newItemBlock.select(".big_cate")[0].onchange = bigCateChange;
                     newItemBlock.select(".mid_cate")[0].onchange = midCateChange;
                     newItemBlock.select(".small_cate")[0].onchange = smallCateChange;
                     newItemBlock.select(".articles")[0].onchange = articleChange;
                     var dateSelectors = newItemBlock.select(".itemDate select");
                     dateSelectors[0].onchange = setDateString;
                     dateSelectors[1].onchange = setDateString;
                     dateSelectors[2].onchange = setDateString;
                     dateSelectors[3].onchange = setDateString;
                     dateSelectors[4].onchange = setDateString;
                     dateSelectors[0].onchange();
                     var channelItemBlock = newElement("div", {"class":"channelItemBlock"}, null);
                     channelItemBlock.appendChild(newElement("h2", {}, ["Item No. 1"]));
                     channelItemBlock.appendChild(newItemBlock);
                     this.parentNode.insertBefore(channelItemBlock, this.nextSibling);
                     renameItemProperties();                 
                 }

                 select("#formSubmit").onclick = function(event) {
                     function isChecked(name) {
                         var form = document.forms[0];
                         var target = form.select("input[name='" + name + "']")[0];
                         var checkbox = target.parentNode.parentNode.select("input[type='checkbox']")[0];
                         return checkbox.checked;
                     }
                     function disable(name) {
                         var form = document.forms[0];
                         var target = form.select("input[name='" + name + "']")[0];
                         target.disabled = true;
                     }
                     ["language", "copyright", "pub_date", "last_build_date", "category",
                      "docs", "ttl", "managing_editor", "web_master", "skip_hour[]", "skip_day[]"
                     ].forEach(function(name) {
                         if (!isChecked(name)) {
                             disable(name);
                         }
                     });
                     ["item_comment", "item_guid", "item_publication_date", "item_source"].forEach(function(className) {
                         select("input." + className).forEach(function(element) {
                             if (!element.parentNode.parentNode.select("input[type='checkbox']")[0].checked) {
                                 element.disabled = true;
                             }
                         });
                     });
                     select(".itemDate select").forEach(function(selectbox) {
                         selectbox.disabled = true;
                     });
                     select(".selectboxes select").forEach(function(selectbox) {
                         selectbox.disabled = true;
                     });
                     select(".deleteItem").forEach(function(deleteButton) {
                         deleteButton.disabled = true;
                     });
                     document.forms[0].submit();
                     return true;
                 };
             };
            </script>
        </head>
        <body>
            <div class="contents">
                <div id="rsscontents">
                    <?php
                    while (($line = fgets($rssfile)) !== false) {
                        if (strpos($line, "atom:link") !== false) {
                            continue;
                        }
                        if ($line == "</rss>\n") {
                            break;
                        }
                        if (strpos($line, '<link>') >= 0) {
                            $line = str_replace('<link>', '<ch-link>', $line);
                            $line = str_replace('</link>', '</ch-link>', $line);
                            echo $line;
                        } else {
                            echo $line;
                        }
                    }
                    fclose($rssfile);
                    ?>
                </div>
                <h1>RSSフィードの編集</h1>
                <form id="rssForm" action="post_rss.php" method="POST">
                    <input type="hidden" name="rating" value="">
                    
                    <table>
                        <tr>
                            <td><span class="required">*</span></td>
                            <td>Channel Title</td>
                            <td><input type="text" name="title" size="30"></td>
                        </tr>
                        <tr>
                            <td><span class="required">*</span></td>
                            <td>Channel Title Link</td>
                            <td><input type="text" name="link" size="30"></td>
                        </tr>
                        <tr>
                            <td><span class="required">*</span></td>
                            <td>Channel Description</td>
                            <td><input type="text" name="description" size="100"></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Channel Language</td>
                            <td><input type="text" name="language" size="2"></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Copyright Identifier</td>
                            <td><input type="text" name="copyright" size="30"></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Publication Date</td>
                            <td><input type="text" name="pub_date" size="30"></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Last Build Date</td>
                            <td><input type="text" id="lastBuildDate" name="last_build_date" size="30"></td>
                        </tr>
                        
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Channel Category</td>
                            <td><input type="text" name="category" size="30"></td>
                        </tr>
                        
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Channel docs</td>
                            <td><input type="text" name="docs" size="30"></td>
                        </Tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Channel ttl (time to live)</td>
                            <td><input type="text" name="ttl" size="5"></td>
                        </tr>

                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Managing Editor</td>
                            <td><input type="text" name="managing_editor" size="30"></td>
                        </tr>

                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Webmaster</td>
                            <td><input type="text" name="web_master" size="30"></td>
                        </tr>

                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Skip Hours</td>
                            <td>
                                <input type="checkbox" name="skip_hour[]" value="00"> 0時
                                <input type="checkbox" name="skip_hour[]" value="01"> 1時
                                <input type="checkbox" name="skip_hour[]" value="02"> 2時
                                <input type="checkbox" name="skip_hour[]" value="03"> 3時
                                <input type="checkbox" name="skip_hour[]" value="04"> 4時
                                <input type="checkbox" name="skip_hour[]" value="05"> 5時
                                <input type="checkbox" name="skip_hour[]" value="06"> 6時
                                <input type="checkbox" name="skip_hour[]" value="07"> 7時
                                <input type="checkbox" name="skip_hour[]" value="08"> 8時
                                <input type="checkbox" name="skip_hour[]" value="09"> 9時
                                <input type="checkbox" name="skip_hour[]" value="10"> 10時
                                <input type="checkbox" name="skip_hour[]" value="11"> 11時
                                <input type="checkbox" name="skip_hour[]" value="12"> 12時<br>
                                <input type="checkbox" name="skip_hour[]" value="13"> 13時
                                <input type="checkbox" name="skip_hour[]" value="14"> 14時
                                <input type="checkbox" name="skip_hour[]" value="15"> 15時
                                <input type="checkbox" name="skip_hour[]" value="16"> 16時
                                <input type="checkbox" name="skip_hour[]" value="17"> 17時
                                <input type="checkbox" name="skip_hour[]" value="18"> 18時
                                <input type="checkbox" name="skip_hour[]" value="19"> 19時
                                <input type="checkbox" name="skip_hour[]" value="20"> 20時
                                <input type="checkbox" name="skip_hour[]" value="21"> 21時
                                <input type="checkbox" name="skip_hour[]" value="22"> 22時
                                <input type="checkbox" name="skip_hour[]" value="23"> 23時
                            </td>
                        </tr>

                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Skip Days</td>
                            <td>
                                <input type="checkbox" name="skip_day[]" value="Sunday"> 日
                                <input type="checkbox" name="skip_day[]" value="Monday"> 月
                                <input type="checkbox" name="skip_day[]" value="Tuesday"> 火
                                <input type="checkbox" name="skip_day[]" value="Wednseday"> 水
                                <input type="checkbox" name="skip_day[]" value="Thursday"> 木
                                <input type="checkbox" name="skip_day[]" value="Friday"> 金
                                <input type="checkbox" name="skip_day[]" value="Saturday"> 土
                            </td>
                        </tr>

                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Use Image</td>
                            <td><input type="checkbox" name="use_image" value="true"></td>
                        </tr>
                        
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Image Location (URL)</td>
                            <td><input type="text" size="40" name="image_url"></td>
                        </tr>
                        
                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Image ALT Text</td>
                            <td><input type="text" size="40" name="image_title"></td>
                        </tr>

                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Image Link (URL)</td>
                            <td><input type="text" size="40" name="image_link"></td>
                        </tr>

                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Image Width</td>
                            <td><input type="text" size="4" name="image_width"> Max Length: 1-144</td>
                        </tr>

                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Image Height</td>
                            <td><input type="text" size="4" name="image_height"> Max-Length: 1-400</td>
                        </tr>

                        <tr>
                            <td><input type="checkbox"></td>
                            <td>Image Description</td>
                            <td><input type="text" size="70" name="image_description"></td>
                        </tr>
                    </table>

                    <button id="addNewItem" type="button">+ 記事を追加する</button>
                    <button id="formSubmit" type="button">変更を保存する</button>
                </form>
            </div>

            <div id="rssChannelItem">
                <div>
                    <button type="button" class="deleteItem">削除</button>
                    <div class="selectboxes">
                        <select class="big_cate">
                            <option value=""></option>
                            <option value="ITニュース">ITニュース</option>
                            <option value="Linux">Linux</option>
                            <option value="アプリケーション">アプリケーション</option>
                            <option value="プログラミング">プログラミング</option>
                            <option value="雑文">雑文</option>
                        </select>
                        <select class="mid_cate">
                            <option value=""></option>
                        </select>
                        <select class="small_cate">
                            <option value=""></option>
                        </select>
                        <select class="articles">
                            <option value=""></option>
                        </select>
                    </div>
                    <table class="channelItemTable">
                        <tr>
                            <td></td>
                            <td>Item Title</td>
                            <td><input type="text" size="30" class="item_title"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Item Link (URL)</td>
                            <td><input type="text" size="60" class="item_link"></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Item Description</td>
                            <td><textarea cols="80" rows="10" class="item_description"></textarea></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>Item Author</td>
                            <td><input type="text" size="30" class="item_author"></td>
                        </tr>   
                        <tr>
                            <td></td>
                            <td>Item Category</td>
                            <td><input type="text" size="30" class="item_category"></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Item Comment</td>
                            <td><input type="text" size="60" class="item_comment"></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Item Guid</td>
                            <td><input type="text" size="60" class="item_guid"></td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Item Publication Date</td>
                            <td>
                                <span class="itemDate">
                                    <select class="item_year">
                                        <option value="2019">2019</option>
                                    </select>年
                                    <select class="item_month">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                    </select>月
                                    <select class="item_day">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                        <option value="24">24</option>
                                        <option value="25">25</option>
                                        <option value="26">26</option>
                                        <option value="27">27</option>
                                        <option value="28">28</option>
                                        <option value="29">29</option>
                                        <option value="30">30</option>
                                        <option value="31">31</option>
                                    </select>日
                                    <select class="item_hours">
                                        <option value="0">0</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                        <option value="6">6</option>
                                        <option value="7">7</option>
                                        <option value="8">8</option>
                                        <option value="9">9</option>
                                        <option value="10">10</option>
                                        <option value="11">11</option>
                                        <option value="12">12</option>
                                        <option value="13">13</option>
                                        <option value="14">14</option>
                                        <option value="15">15</option>
                                        <option value="16">16</option>
                                        <option value="17">17</option>
                                        <option value="18">18</option>
                                        <option value="19">19</option>
                                        <option value="20">20</option>
                                        <option value="21">21</option>
                                        <option value="22">22</option>
                                        <option value="23">23</option>
                                    </select>時
                                    <select class="item_minutes">
                                        <option value="00">00</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option>
                                        <option value="30">30</option>
                                        <option value="40">40</option>
                                        <option value="50">50</option>
                                    </select>分
                                </span>
                                <input type="text" readonly size="40" class="item_publication_date">
                            </td>
                        </tr>
                        <tr>
                            <td><input type="checkbox" checked></td>
                            <td>Item Source</td>
                            <td><input type="text" size="60" class="item_source"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </body>
    </html>

<?php } ?>
