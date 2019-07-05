<?php
function array_last($array) {
    return $array[count($array) - 1];
}

function ttPutMarkdown($filePath, $allowHtml = true) {
    echo ttGetMarkdown($filePath, $allowHtml);
}

$counter = 0;
function ttConvMdParts($parts) {
    global $counter;
    $parts = str_replace("\\*", "[:asterisk:]", $parts);
    $parts = str_replace("\\`", "[:backquote:]", $parts);
    $parts = str_replace("\\_", "[:underscore:]", $parts);
    $linkPattern = '/\[([^\]]*)\]\(([^\)]*)\)/';
    preg_match_all($linkPattern, $parts, $out, PREG_SET_ORDER);
    foreach ($out as $m) {
        $converted = str_replace("_", "[:underscore:]", $m[2]);
        $subject = "[{$m[1]}]({$m[2]})";
        $parts = str_replace($subject, "<a href=\"$converted\">{$m[1]}</a>", $parts);
    }
    $boldItalicPattern = '/\*\*\*([^\*]*)\*\*\*/';
    $parts = preg_replace($boldItalicPattern, "<strong><i>$1</i></strong>", $parts);
    $boldPattern = '/\*\*([^\*]*)\*\*/';
    $parts = preg_replace($boldPattern, "<strong>$1</strong>", $parts);
    $italicPattern = '/\*([^\*]*)\*/';
    $parts = preg_replace($italicPattern, "<i>$1</i>", $parts);
    $strikePattern = '/\~\~([^\~]*)\~\~/';
    $parts = preg_replace($strikePattern, "<strike>$1</strike>", $parts);
    $underlinePattern = '/_([^_]*)_/';
    $parts = preg_replace($underlinePattern, "<u>$1</u>", $parts);
    $codePattern = '/\`([^\`]*)\`/';
    $parts = preg_replace($codePattern, "<span class=\"code-span\">$1</span>", $parts);
    if (mb_substr($parts, 0, 1) == "※") {
	$parts = "<span class=\"come\">$parts</span>";
    } else {
	$comePattern = '/(\(?※[0-9]*\)?)/';
	$parts = preg_replace($comePattern, "<span class=\"come\">$1</span>", $parts);
    }
    $parts = str_replace("[:asterisk:]", "*", $parts);
    $parts = str_replace("[:backquote:]", "`", $parts);
    $parts = str_replace("[:underscore:]", "_", $parts);
    return $parts;
}

function ttGetMarkdown($filePath, $allowHtml = true) {
    $fp = fopen($filePath, "r");
    if ($fp == null) {
        return "";
    }
    $result = ttGetMarkdownByLambda(function () use (&$fp) {
        $line = fgets($fp);
        return $line;
    }, $allowHtml);
    fclose($fp);
    return $result;
}

function ttGetMarkdownFromText($text, $allowHtml = true) {
    $lines = explode("\n", $text);
    $lines[] = "&nbsp;\n";
    return ttGetMarkdownByLambda(function() use (&$lines) {
        $line = array_shift($lines);
        return $line;
    }, $allowHtml);
}

function ttGetMarkdownByLambda($getLineFunc, $allowHtml = true) {
    $result = "";
    $prevType = "";
    $line = "";
    $indent = 0;
    $prevIndent = 0;
    $codeName = "";
    $tableCount = 0;
    $end = false;
    $indentList = array(0);
    while ($line = $getLineFunc()) {
        if (preg_match("/^ *$/", $line)) {
            $type = "blank";
        } else {
            $line = rtrim($line);
            $len = strlen($line);
            if ($len >= 2 && substr($line, 0, 2) == "--") {
		$result .= "<!-- $line -->\n";
		continue;
            } else if ($len >= 2 && substr($line, 0, 2) == "# ") {
                $type = "h3";
            } else if ($len >= 3 && substr($line, 0, 3) == "## ") {
                $type = "h4";
            } else if ($len >= 4 && substr($line, 0, 4) == "### ") {
                $type = "h5";
            } else if ($len >= 5 && substr($line, 0, 5) == "#### ") {
                $type = "h6";
            } else if ($len >= 1 && $line[0] == ">") {
                $type = "blockquote";
            } else if ($len >= 1 && $line[0] == '!') {
                $type = "img";
            } else if ($len >= 1 && $line[0] == '|') {
                $type = "table";
	    } else if (preg_match('/^( *[\*-] *)+$/', $line, $group)) {
		$type = "hr";
            } else if (preg_match("/^( *)[0-9]+\.(.*)/", $line, $group)) {
                $type = "ol";
                $indent = strlen($group[1]);
                $line = trim($group[2]);
            } else if (preg_match("/^( *)[\*-] (.*)/", $line, $group)) {
                $type = "ul";
                $indent = strlen($group[1]);
                $line = trim($group[2]);
            } else if ($len >= 3 && substr($line, 0, 3) == '```') {
                $type = "code";
                $codeName = substr($line, 3);
                if ($codeName == "") {
                    $codeName = "No Title";
                }
            } else if ($len >= 1 && $line[0] == ":") {
                $type = "sample";
            } else if ($allowHtml == true && $len >= 1 && ltrim($line[0]) == "<") {
                $type = "html";
            } else {
		$type = "p";
            }
        }

        switch ($prevType) {
            case "blockquote":
		if ($type != "blockquote") {
                    $result .= "</blockquote>\n";
		}
		break;
            case "sample":
		if ($type != "sample") {
                    $result .= "</pre>\n";
		}
		break;
            case "p":
		if ($type != "p") {
                    $result .= "</p>\n";
		}
		break;
            case "table":
		if ($type != "table") {
                    $result .= "</table>\n";
		}
		break;
            case "ol":
            case "ul":
		if ($type != "ol" && $type != "ul") {
                    do {
			$prevIndent = array_pop($indentList);
			if ($prevType == "ul") {
                            $result .= "</ul>\n";
			} else if ($prevType == "ol") {
                            $result .= "</ol>\n";
			}
                    } while ($prevIndent > 0);
		}
		break;
        }
        
        switch ($type) {
	    case "hr":
		$result .= "<hr class=\"style1\">\n";
		break;
		
            case "comment":
		$result .= "<!-- " . substr($line, 2) . " -->\n";
		break;
		
            case "h3":
		$result .= "<h3>" . ttConvMdParts(substr($line, 2)) . "</h3>\n";
		break;

            case "h4":
		$result .= "<h4>" . ttConvMdParts(substr($line, 3)) . "</h4>\n";
		break;

            case "h5":
		$result .= "<h5>" . ttConvMdParts(substr($line, 4)) . "</h5>\n";
		break;
                
            case "h6":
		$result .= "<h6>" . ttConvMdParts(substr($line, 5)) . "</h6>\n";
		break;
                
            case "blockquote":
		if ($prevType != "blockquote") {
                    $result .= "<blockquote>\n";
		}
		$inBlock = [];
		do {
		    $quotedLine = trim(substr($line, 1));
		    if ($quotedLine == "") {
			$quotedLine = "&nbsp;";
		    }
		    $inBlock[] = $quotedLine;
		    $line = $getLineFunc();
		} while ($line != false && trim($line)[0] == ">");
		$inBlock[] = " \n";
		$result .= ttGetMarkdownByLambda(function() use (&$inBlock) {
                    return array_shift($inBlock);
		});
		$result .= "</blockquote>\n";
		break;

            case "img":
		if (preg_match('/\!\[([^\]]+)\]\(([^}]+) flat\)/', $line, $matches) > 0) {
                    $result .= "<img class=\"figure-flat\" src=\"" . $matches[2] . "\" alt=\"" . $matches[1] . "\">\n";
		} else if (preg_match('/\!\[([^\]]+)\]\(([^\]]+)\)/', $line, $matches) > 0) {
                    $result .= "<img class=\"figure\" src=\"" . $matches[2] . "\" alt=\"" . $matches[1] . "\">\n";
		}                
		break;
		
            case "ul":
            case "ol":
		if ($prevType == "ul" || $prevType == "ol") {
                    $len = count($indentList);
                    $prevIndent = $indentList[$len - 1];
                    if (count($indentList) >= 2) {
			if ($indentList[$len - 1] > $indent && $indent > $indentList[$len - 2]) {
                            $indent = $indentList[$len - 1];
			}
                    }
                    if ($prevIndent != $indent) {
			if ($prevIndent < $indent) {
                            if ($type == "ul") {
				$result .= "<ul>\n";
                            } else if ($type == "ol") {
				$result .= "<ol>\n";
                            }
                            array_push($indentList, $indent);
			} else if ($prevIndent > $indent) {
                            do {
				if ($prevType == "ul") {
                                    $result .= "</ul>\n";
				} else if ($prevType == "ol") {
                                    $result .= "</ol>\n";
				}
				array_pop($indentList);
				$len--;
				$prevIndent = $indentList[$len - 1];
                            } while ($prevIndent > $indent);
			} else {
                            if ($prevType != $type) {
				if ($prevType == "ul") {
                                    $result .= "</ul>\n<ol>\n";
				} else if ($prevType == "ol") {
                                    $result .= "</ol>\n<ul>\n";
				}
                            }
			}
                    }
		} else if ($type == "ol" && $prevType != "ol") {
                    $result .= "<ol>\n";
		} else if ($type == "ul" && $prevType != "ul") {
                    $result .= "<ul>\n";
		}
		$result .= "<li>" . ttConvMdParts($line) . "</li>\n";
		break;

            case "table":
		if ($prevType != "table") {
                    $result .= "<table>\n";
                    $line2 = $getLineFunc($fp);
                    if ($line2 === false) {
			break;
                    }
                    $line2 = rtrim($line2);
                    if (preg_match('/^ *\|-+/', $line2)) {
			$result .= "<tr>";
			$line = trim($line);
			$line = substr($line, 1, strlen($line) - 2);
			foreach (explode("|", $line) as $td) {
                            $result .= "<th>" . ttConvMdParts(trim($td)) . "</th>";
			}
			$result .= "</tr>\n";
                    } else {
			$result .= "<tr>";
			$line = trim($line);
			$line = substr($line, 1, strlen($line) - 2);
			foreach (explode("|", $line) as $td) {
                            $result .= "<td>" . ttConvMdParts(trim($td)) . "</td>";
			}
			$result .= "</tr>\n<tr>";
			$line2 = trim($line2);
			$line2 = substr($line2, 1, strlen($line2) - 2);
			foreach (explode("|", $line2) as $td) {
                            $result .= "<td>" . ttConvMdParts(trim($td)) . "</td>";
			}
			$result .= "</tr>\n";
                    }
		} else {
                    $result .= "<tr>";
                    $line = trim($line);
                    $line = substr($line, 1, strlen($line) - 2);
                    foreach (explode("|", $line) as $td) {
			$result .= "<td>" . ttConvMdParts(trim($td)) . "</td>";
                    }
                    $result .= "</tr>\n";
		}
		break;

            case "sample":
		if ($prevType != "sample") {
                    $result .= "<pre class=\"sample\">\n";
		}
		$result .= ttConvMdParts(substr($line, 1)) . "\n";
		break;
		
            case "p":
		if ($prevType == "p") {
                    $result .= "<br>\n";
		} else {
                    $result .= "<p>";
		}
		
		$result .= ttConvMdParts($line);
		break;

            case "html":
		$result .= $line;
		break;

            case "code":
		$result .= "<div class=\"code\" name=\"$codeName\">\n";
		$result .= "<pre>";
		while (($line = $getLineFunc()) !== false) {
                    $len = strlen($line);
                    if ($len >= 3 && substr($line, 0, 3) == '```') {
			break;
                    } else {
			$result .= ttConvMdParts(htmlspecialchars($line));
                    }
		}
		$result = rtrim($result);
		$result .= "</pre>\n";
		$result .= "</div>\n";
		break;
        }

        $prevType = $type;
        if ($type == "ol" || $type == "ul") {
            $prevIndent = $indent;
            $indent = 0;
        }
    }
    return $result;
}
