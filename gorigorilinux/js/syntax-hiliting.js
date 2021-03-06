var syntaxHiliter = {
    commonCLikeKeywords: [
        "auto", "break", "case", "char", "const", "continue",
        "default", "do", "double", "else", "enum", "extern", "float",
        "for", "goto", "if", "int", "long", "register", "return",
        "signed", "sizeof", "short", "static", "struct", "switch",
        "typedef", "union", "unsigned", "void", "volatile", "while",
    ],
    commonOOPKeywords: [
        "class", "private", "public", "protected", "import",
        "package", "extends", "implements", "abstract",
        "new", "module", "var", "requires", "exports",
	"boolean", "true", "false", "interface", "virtual"
   ],
    shellKeywords: [
        "alias", "if", "then", "else", "elif", "while", "for", "in", "case",
        "do", "done", "fi", "esac", "function", "exit", "source", "export"
    ],
    valaKeywords: [
	"bool", "string", "unowned", "weak", "signal", "out", "ref", "connect",
	"delete", "get", "set"
    ],
    markUpWithSpan: function(text, keywords, className) {
        var pattern = '(' + keywords.map(e => '\\b' + e + '\\b').join('|') + ')';
        var regex = new RegExp(pattern, 'g');
        return text.replace(regex, '<span class="' + className + '">$1</span>');
    },
    execute: function(text, type) {
        switch (type) {
        case "java":
            text = text.split("\n")
		.map(line => line
		     .replace(/"([^"]+)"/g, '<span klass="str">"$1"</span>')
		     .replace(/(\/\/.*)$/, '<span klass="cmt">$1</span>')
		     .replace(/(@[A-Z][a-zA-Z0-9_]*)\b/, '<span klass="dcv">$1</span>')
		     .replace(/\b([A-Z][A-Z0-9_]*)\b/g, '<span klass="cnst">$1</span>')
		     .replace(/\b([A-Z][a-zA-Z0-9_]+)\b/g, '<span klass="cls">$1</span>')
		     .replace(/\b([a-z][A-Za-z0-9_]*)\(/g, '<span klass="fnc">$1</span>('))
		.join("\n");
            text = this.markUpWithSpan(text, this.commonCLikeKeywords.concat(this.commonOOPKeywords), "kw");
            text = text.replace(/klass/g, "class");
            break;
        case "php":
            text = this.markUpWithSpan(text, this.commonCLikeKeywords, "kw");
            break;
        case "h":
        case "c":
            text = text.replace(/"([^"]+)"/, '<span klass="str">"$1"</span>');
            text = text.replace(/(#.*)/, '<span klass="dcv">$1</span>');
            text = this.markUpWithSpan(text, this.commonCLikeKeywords, "kw");
            text = text.replace("klass", "class");
            break;
	case "c++":
            text = text.replace(/"([^"]+)"/, '<span klass="str">"$1"</span>');
            text = text.replace(/(#.*)/, '<span klass="dcv">$1</span>');
            text = this.markUpWithSpan(text, this.commonCLikeKeywords.concat(this.commonOOPKeywords), "kw");
            text = text.replace("klass", "class");
	    break;
        case "vala":
            text = text.split("\n")
		.map(line => line
		     .replace(/"([^"]+)"/g, '<span klass="str">"$1"</span>')
		     .replace(/(\/\/.*)$/, '<span klass="cmt">$1</span>')
		     .replace(/(\[[A-Z][a-zA-Z0-9_]*\])/, '<span klass="dcv">$1</span>')
		     .replace(/\b([A-Z][A-Z0-9_]*)\b/g, '<span klass="cnst">$1</span>')
		     .replace(/\b([A-Z][a-zA-Z0-9_]+)\b/g, '<span klass="cls">$1</span>')
		     .replace(/\b([a-z][A-Za-z0-9_]*)\(/g, '<span klass="fnc">$1</span>('))
		.join("\n");
            text = this.markUpWithSpan(text, this.commonCLikeKeywords.concat(this.commonOOPKeywords).concat(this.valaKeywords), "kw");
            text = text.replace(/klass/g, "class");
            break;
        case "html":
            text = this.markUpWithSpan(text, this.commonCLikeKeywords, "kw");
            break;
        case "js":
            text = this.markUpWithSpan(text, this.commonCLikeKeywords, "kw");
            break;
        case "sh":
        case "bash":
        case "terminal":
            text = text.split("\n")
                .map(e => e.replace(/^([^ ]*[\$#])([^!])/g, '<span class="ppt">$1</span>$2')
		     .replace(/"([^"]+)"/g, '<span klass="str">"$1"</span>')
		     .replace(/(\$\{?[A-Za-z0-9_]+\}?)/g, '<span class="cls">$1</span>')
		     .replace(/^(\#\!.*)/, '<span class="dcv">$1</span>'))
                .join("\n");
            text = this.markUpWithSpan(text, this.shellKeywords, "kw");
            break;
        default:
            text = text;
            break;
        }
        return text;
    },
    executeAll: function() {
        var elements = select("div.code");
        for (var i = 0; i < elements.length; i++) {
            var name = elements[i].getAttribute("name");
            var pre = elements[i].select("pre")[0];
            var type = "";
	    if (name != null) {
		if (name == "ターミナル") {
		    type = "text";
		} else {
                    type = name.substring(name.lastIndexOf('.') + 1);
		}
		var text = pre.innerHTML;
		pre.innerHTML = this.execute(text, type);
		pre.select(".str, .cmt, .dcv").forEach(function(elem) {
		    elem.innerHTML = elem.innerHTML.replace(/<[^>]*>/g, '')
		});
	    }
        }
    }
};
