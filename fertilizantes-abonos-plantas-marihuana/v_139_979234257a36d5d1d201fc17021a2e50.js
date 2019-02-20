function formatedNumberToFloat(e, t, n) {
    return e = e.replace(n, ""), 1 === t ? parseFloat(e.replace(",", "").replace(" ", "")) : 2 === t ? parseFloat(e.replace(" ", "").replace(",", ".")) : 3 === t ? parseFloat(e.replace(".", "").replace(" ", "").replace(",", ".")) : 4 === t ? parseFloat(e.replace(",", "").replace(" ", "")) : e
}

function formatNumber(e, t, n, o) {
    for (var i = (e = e.toFixed(t)) + "", r = i.split("."), a = 2 === r.length ? r[0] : i, s = ("0." + (2 === r.length ? r[1] : 0)).substr(2), l = a.length, c = 1; c < 4; c++) e >= Math.pow(10, 3 * c) && (a = a.substring(0, l - 3 * c) + n + a.substring(l - 3 * c));
    return 0 === parseInt(t) ? a : a + o + (0 < s ? s : "00")
}

function formatCurrency(e, t, n, o) {
    var i = "";
    return e = ps_round(e = parseFloat(e.toFixed(10)), priceDisplayPrecision), 0 < o && (i = " "), 1 == t ? n + i + formatNumber(e, priceDisplayPrecision, ",", ".") : 2 == t ? formatNumber(e, priceDisplayPrecision, " ", ",") + i + n : 3 == t ? n + i + formatNumber(e, priceDisplayPrecision, ".", ",") : 4 == t ? formatNumber(e, priceDisplayPrecision, ",", ".") + i + n : 5 == t ? n + i + formatNumber(e, priceDisplayPrecision, "'", ".") : e
}

function ps_round_helper(e, t) {
    return 0 <= e ? (tmp_value = Math.floor(e + .5), (3 == t && e == -.5 + tmp_value || 4 == t && e == .5 + 2 * Math.floor(tmp_value / 2) || 5 == t && e == .5 + 2 * Math.floor(tmp_value / 2) - 1) && (tmp_value -= 1)) : (tmp_value = Math.ceil(e - .5), (3 == t && e == .5 + tmp_value || 4 == t && e == 2 * Math.ceil(tmp_value / 2) - .5 || 5 == t && e == 2 * Math.ceil(tmp_value / 2) - .5 + 1) && (tmp_value += 1)), tmp_value
}

function ps_log10(e) {
    return Math.log(e) / Math.LN10
}

function ps_round_half_up(e, t) {
    var n = Math.pow(10, t),
        o = e * n;
    return (o = 5 <= Math.floor(10 * o) - 10 * Math.floor(o) ? Math.ceil(o) : Math.floor(o)) / n
}

function ps_round(e, t) {
    "undefined" == typeof roundMode && (roundMode = 2), void 0 === t && (t = 2);
    var n = roundMode;
    if (0 === n) return ceilf(e, t);
    if (1 === n) return floorf(e, t);
    if (2 === n) return ps_round_half_up(e, t);
    if (3 == n || 4 == n || 5 == n) {
        var o = 14 - Math.floor(ps_log10(Math.abs(e))),
            i = Math.pow(10, Math.abs(t));
        if (t < o && o - t < 15) {
            var r = Math.pow(10, Math.abs(o));
            tmp_value = 0 <= o ? e * r : e / r, tmp_value = ps_round_helper(tmp_value, roundMode), r = Math.pow(10, Math.abs(t - o)), tmp_value /= r
        } else if (tmp_value = 0 <= t ? e * i : e / i, 1e15 <= Math.abs(tmp_value)) return e;
        return tmp_value = ps_round_helper(tmp_value, roundMode), 0 < t ? tmp_value /= i : tmp_value *= i, tmp_value
    }
}

function autoUrl(e, t) {
    var n, o;
    0 != (n = (o = document.getElementById(e)).options[o.selectedIndex].value) && (location.href = t + n)
}

function autoUrlNoList(e, t) {
    var n;
    n = document.getElementById(e).checked, location.href = t + (1 == n ? 1 : 0)
}

function toggle(e, t) {
    e.style.display = t ? "" : "none"
}

function toggleMultiple(e) {
    for (var t = e.length, n = 0; n < t; n++) e[n].style && toggle(e[n], "none" == e[n].style.display)
}

function showElemFromSelect(e, t) {
    for (var n = document.getElementById(e), o = 0; o < n.length; ++o) {
        var i = document.getElementById(t + n.options[o].value);
        null != i && toggle(i, o == n.selectedIndex)
    }
}

function openCloseAllDiv(e, t) {
    for (var n = $("*[name=" + e + "]"), o = 0; o < n.length; ++o) toggle(n[o], t)
}

function toggleDiv(e, t) {
    $("*[name=" + e + "]").each(function() {
        "open" == t ? ($("#buttonall").data("status", "close"), $(this).hide()) : ($("#buttonall").data("status", "open"), $(this).show())
    })
}

function toggleButtonValue(e, t, n) {
    $("#" + e).find("i").first().hasClass("process-icon-compress") ? ($("#" + e).find("i").first().removeClass("process-icon-compress").addClass("process-icon-expand"), $("#" + e).find("span").first().html(t)) : ($("#" + e).find("i").first().removeClass("process-icon-expand").addClass("process-icon-compress"), $("#" + e).find("span").first().html(n))
}

function toggleElemValue(e, t, n) {
    var o = document.getElementById(e);
    o && (o.value = o.value && o.value != n ? n : t)
}

function addBookmark(e, t) {
    return window.sidebar && window.sidebar.addPanel ? window.sidebar.addPanel(t, e, "") : window.external && "AddFavorite" in window.external ? window.external.AddFavorite(e, t) : void 0
}

function writeBookmarkLink(e, t, n, o) {
    var i = "";
    o && (i = writeBookmarkLinkObject(e, t, '<img src="' + o + '" alt="' + escape(n) + '" title="' + removeQuotes(n) + '" />') + "&nbsp"), i += writeBookmarkLinkObject(e, t, n), (window.sidebar || window.opera && window.print || window.external && "AddFavorite" in window.external) && $(".add_bookmark, #header_link_bookmark").append(i)
}

function writeBookmarkLinkObject(e, t, n) {
    return window.sidebar || window.external ? "<a href=\"javascript:addBookmark('" + escape(e) + "', '" + removeQuotes(t) + "')\">" + n + "</a>" : window.opera && window.print ? '<a rel="sidebar" href="' + escape(e) + '" title="' + removeQuotes(t) + '">' + n + "</a>" : ""
}

function checkCustomizations() {
    var e = new RegExp(" ?filled ?");
    if ("undefined" != typeof customizationFields)
        for (var t = 0; t < customizationFields.length; t++)
            if (1 == parseInt(customizationFields[t][1]) && ("" == $("#" + customizationFields[t][0]).html() || $("#" + customizationFields[t][0]).text() != $("#" + customizationFields[t][0]).val()) && !e.test($("#" + customizationFields[t][0]).attr("class"))) return !1;
    return !0
}

function emptyCustomizations() {
    if (customizationId = null, "undefined" != typeof customizationFields) {
        $(".customization_block .success").fadeOut(function() {
            $(this).remove()
        }), $(".customization_block .error").fadeOut(function() {
            $(this).remove()
        });
        for (var e = 0; e < customizationFields.length; e++) $("#" + customizationFields[e][0]).html(""), $("#" + customizationFields[e][0]).val("")
    }
}

function ceilf(e, t) {
    void 0 === t && (t = 0);
    var n = 0 === t ? 1 : Math.pow(10, t),
        o = (e * n).toString();
    return 0 === o[o.length - 1] ? e : Math.ceil(e * n) / n
}

function floorf(e, t) {
    void 0 === t && (t = 0);
    var n = 0 === t ? 1 : Math.pow(10, t),
        o = (e * n).toString();
    return 0 === o[o.length - 1] ? e : Math.floor(e * n) / n
}

function setCurrency(e) {
    $.ajax({
        type: "POST",
        headers: {
            "cache-control": "no-cache"
        },
        url: baseDir + "index.php?rand=" + (new Date).getTime(),
        data: "controller=change-currency&id_currency=" + parseInt(e),
        success: function(e) {
            location.reload(!0)
        }
    })
}

function isArrowKey(e) {
    var t = e.keyCode ? e.keyCode : e.charCode;
    return 37 <= t && t <= 40
}

function removeQuotes(e) {
    return (e = (e = (e = e.replace(/\\"/g, "")).replace(/"/g, "")).replace(/\\'/g, "")).replace(/'/g, "")
}

function sprintf(e) {
    for (var t = 1; t < arguments.length; t++) e = e.replace(/%s/, arguments[t]);
    return e
}

function fancyMsgBox(e, t) {
    t && (e = "<h2>" + t + "</h2><p>" + e + "</p>"), e += '<br/><p class="submit" style="text-align:right; padding-bottom: 0"><input class="button" type="button" value="OK" onclick="$.fancybox.close();" /></p>', $.prototype.fancybox && $.fancybox(e, {
        autoDimensions: !1,
        autoSize: !1,
        width: 500,
        height: "auto",
        openEffect: "none",
        closeEffect: "none"
    })
}

function fancyChooseBox(e, t, n, o) {
    var i, r;
    i = "", t && (i = "<h2>" + t + "</h2><p>" + e + "</p>"), i += '<br/><p class="submit" style="text-align:right; padding-bottom: 0">';
    var a = 0;
    for (var s in n) n.hasOwnProperty(s) && (r = n[s], void 0 === o && (o = 0), o = escape(JSON.stringify(o)), i += '<button type="submit" class="button btn-default button-medium" style="margin-right: 5px;" value="true" onclick="' + (r ? "$.fancybox.close();window['" + r + "'](JSON.parse(unescape('" + o + "')), " + a + ")" : "$.fancybox.close()") + '" >', i += "<span>" + s + "</span></button>", a++);
    i += "</p>", $.prototype.fancybox && $.fancybox(i, {
        autoDimensions: !1,
        width: 500,
        height: "auto",
        openEffect: "none",
        closeEffect: "none"
    })
}

function toggleLayer(e, t) {
    t ? $(e).show() : $(e).hide()
}

function openCloseLayer(e, t) {
    t ? "open" == t ? $(e).show() : "close" == t && $(e).hide() : "none" == $(e).css("display") ? $(e).show() : $(e).hide()
}

function updateTextWithEffect(e, t, n, o, i, r) {
    e.text() !== t && ("fade" === o ? e.fadeOut(n, function() {
        $(this).addClass(r), "fade" === i ? $(this).text(t).fadeIn(n) : "slide" === i ? $(this).text(t).slideDown(n) : "show" === i && $(this).text(t).show(n, function() {})
    }) : "slide" === o ? e.slideUp(n, function() {
        $(this).addClass(r), "fade" === i ? $(this).text(t).fadeIn(n) : "slide" === i ? $(this).text(t).slideDown(n) : "show" === i && $(this).text(t).show(n)
    }) : "hide" === o && e.hide(n, function() {
        $(this).addClass(r), "fade" === i ? $(this).text(t).fadeIn(n) : "slide" === i ? $(this).text(t).slideDown(n) : "show" === i && $(this).text(t).show(n)
    }))
}

function dbg(e) {}

function print_r(e, t, n) {
    for (property in n = n || 0, t = t || 1, returnString = "<ol>", e) "domConfig" != property && (returnString += "<li><strong>" + property + "</strong> <small>(" + typeof e[property] + ")</small>", "number" != typeof e[property] && "boolean" != typeof e[property] || (returnString += " : <em>" + e[property] + "</em>"), "string" == typeof e[property] && e[property] && (returnString += ': <div style="background:#C9C9C9;border:1px solid black; overflow:auto;"><code>' + e[property].replace(/</g, "&amp;lt;").replace(/>/g, "&amp;gt;") + "</code></div>"), "object" == typeof e[property] && n < t && (returnString += print_r(e[property], t, n + 1)), returnString += "</li>");
    return returnString += "</ol>", 0 == n && (winpop = window.open("", "", "width=800,height=600,scrollbars,resizable"), winpop.document.write("<pre>" + returnString + "</pre>"), winpop.document.close()), returnString
}

function in_array(e, t) {
    for (var n in t)
        if (t[n] + "" == e + "") return !0;
    return !1
}

function isCleanHtml(e) {
    var t = new RegExp("(onmousedown|onmousemove|onmmouseup|onmouseover|onmouseout|onload|onunload|onfocus|onblur|onchange|onsubmit|ondblclick|onclick|onkeydown|onkeyup|onkeypress|onmouseenter|onmouseleave|onerror|onselect|onreset|onabort|ondragdrop|onresize|onactivate|onafterprint|onmoveend|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onmove|onbounce|oncellchange|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondeactivate|ondrag|ondragend|ondragenter|onmousewheel|ondragleave|ondragover|ondragstart|ondrop|onerrorupdate|onfilterchange|onfinish|onfocusin|onfocusout|onhashchange|onhelp|oninput|onlosecapture|onmessage|onmouseup|onmovestart|onoffline|ononline|onpaste|onpropertychange|onreadystatechange|onresizeend|onresizestart|onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onsearch|onselectionchange|onselectstart|onstart|onstop)[s]*=", "im");
    return !(/<[\s]*script/im.test(e) || t.test(e) || /.*script\:/im.test(e) || /<[\s]*(i?frame|embed|object)/im.test(e))
}

function getStorageAvailable() {
    test = "foo", storage = window.localStorage || window.sessionStorage;
    try {
        return storage.setItem(test, test), storage.removeItem(test), storage
    } catch (e) {
        return null
    }
}! function(e, t) {
    "object" == typeof module && "object" == typeof module.exports ? module.exports = e.document ? t(e, !0) : function(e) {
        if (!e.document) throw new Error("jQuery requires a window with a document");
        return t(e)
    } : t(e)
}("undefined" != typeof window ? window : this, function(e, t) {
    var n = [],
        o = n.slice,
        i = n.concat,
        r = n.push,
        a = n.indexOf,
        s = {},
        l = s.toString,
        c = s.hasOwnProperty,
        u = "".trim,
        d = {},
        p = function(e, t) {
            return new p.fn.init(e, t)
        },
        f = /^[\s\uFEFF\xA0]+|[\s\uFEFF\xA0]+$/g,
        h = /^-ms-/,
        m = /-([\da-z])/gi,
        g = function(e, t) {
            return t.toUpperCase()
        };

    function v(e) {
        var t = e.length,
            n = p.type(e);
        return "function" !== n && !p.isWindow(e) && (!(1 !== e.nodeType || !t) || "array" === n || 0 === t || "number" == typeof t && 0 < t && t - 1 in e)
    }
    p.fn = p.prototype = {
        jquery: "1.11.0",
        constructor: p,
        selector: "",
        length: 0,
        toArray: function() {
            return o.call(this)
        },
        get: function(e) {
            return null != e ? e < 0 ? this[e + this.length] : this[e] : o.call(this)
        },
        pushStack: function(e) {
            var t = p.merge(this.constructor(), e);
            return t.prevObject = this, t.context = this.context, t
        },
        each: function(e, t) {
            return p.each(this, e, t)
        },
        map: function(e) {
            return this.pushStack(p.map(this, function(t, n) {
                return e.call(t, n, t)
            }))
        },
        slice: function() {
            return this.pushStack(o.apply(this, arguments))
        },
        first: function() {
            return this.eq(0)
        },
        last: function() {
            return this.eq(-1)
        },
        eq: function(e) {
            var t = this.length,
                n = +e + (e < 0 ? t : 0);
            return this.pushStack(0 <= n && n < t ? [this[n]] : [])
        },
        end: function() {
            return this.prevObject || this.constructor(null)
        },
        push: r,
        sort: n.sort,
        splice: n.splice
    }, p.extend = p.fn.extend = function() {
        var e, t, n, o, i, r, a = arguments[0] || {},
            s = 1,
            l = arguments.length,
            c = !1;
        for ("boolean" == typeof a && (c = a, a = arguments[s] || {}, s++), "object" == typeof a || p.isFunction(a) || (a = {}), s === l && (a = this, s--); s < l; s++)
            if (null != (i = arguments[s]))
                for (o in i) e = a[o], a !== (n = i[o]) && (c && n && (p.isPlainObject(n) || (t = p.isArray(n))) ? (t ? (t = !1, r = e && p.isArray(e) ? e : []) : r = e && p.isPlainObject(e) ? e : {}, a[o] = p.extend(c, r, n)) : void 0 !== n && (a[o] = n));
        return a
    }, p.extend({
        expando: "jQuery" + ("1.11.0" + Math.random()).replace(/\D/g, ""),
        isReady: !0,
        error: function(e) {
            throw new Error(e)
        },
        noop: function() {},
        isFunction: function(e) {
            return "function" === p.type(e)
        },
        isArray: Array.isArray || function(e) {
            return "array" === p.type(e)
        },
        isWindow: function(e) {
            return null != e && e == e.window
        },
        isNumeric: function(e) {
            return 0 <= e - parseFloat(e)
        },
        isEmptyObject: function(e) {
            var t;
            for (t in e) return !1;
            return !0
        },
        isPlainObject: function(e) {
            var t;
            if (!e || "object" !== p.type(e) || e.nodeType || p.isWindow(e)) return !1;
            try {
                if (e.constructor && !c.call(e, "constructor") && !c.call(e.constructor.prototype, "isPrototypeOf")) return !1
            } catch (e) {
                return !1
            }
            if (d.ownLast)
                for (t in e) return c.call(e, t);
            for (t in e);
            return void 0 === t || c.call(e, t)
        },
        type: function(e) {
            return null == e ? e + "" : "object" == typeof e || "function" == typeof e ? s[l.call(e)] || "object" : typeof e
        },
        globalEval: function(t) {
            t && p.trim(t) && (e.execScript || function(t) {
                e.eval.call(e, t)
            })(t)
        },
        camelCase: function(e) {
            return e.replace(h, "ms-").replace(m, g)
        },
        nodeName: function(e, t) {
            return e.nodeName && e.nodeName.toLowerCase() === t.toLowerCase()
        },
        each: function(e, t, n) {
            var o = 0,
                i = e.length,
                r = v(e);
            if (n) {
                if (r)
                    for (; o < i && !1 !== t.apply(e[o], n); o++);
                else
                    for (o in e)
                        if (!1 === t.apply(e[o], n)) break
            } else if (r)
                for (; o < i && !1 !== t.call(e[o], o, e[o]); o++);
            else
                for (o in e)
                    if (!1 === t.call(e[o], o, e[o])) break;
            return e
        },
        trim: u && !u.call("\ufeff ") ? function(e) {
            return null == e ? "" : u.call(e)
        } : function(e) {
            return null == e ? "" : (e + "").replace(f, "")
        },
        makeArray: function(e, t) {
            var n = t || [];
            return null != e && (v(Object(e)) ? p.merge(n, "string" == typeof e ? [e] : e) : r.call(n, e)), n
        },
        inArray: function(e, t, n) {
            var o;
            if (t) {
                if (a) return a.call(t, e, n);
                for (o = t.length, n = n ? n < 0 ? Math.max(0, o + n) : n : 0; n < o; n++)
                    if (n in t && t[n] === e) return n
            }
            return -1
        },
        merge: function(e, t) {
            for (var n = +t.length, o = 0, i = e.length; o < n;) e[i++] = t[o++];
            if (n != n)
                for (; void 0 !== t[o];) e[i++] = t[o++];
            return e.length = i, e
        },
        grep: function(e, t, n) {
            for (var o = [], i = 0, r = e.length, a = !n; i < r; i++) !t(e[i], i) !== a && o.push(e[i]);
            return o
        },
        map: function(e, t, n) {
            var o, r = 0,
                a = e.length,
                s = [];
            if (v(e))
                for (; r < a; r++) null != (o = t(e[r], r, n)) && s.push(o);
            else
                for (r in e) null != (o = t(e[r], r, n)) && s.push(o);
            return i.apply([], s)
        },
        guid: 1,
        proxy: function(e, t) {
            var n, i, r;
            return "string" == typeof t && (r = e[t], t = e, e = r), p.isFunction(e) ? (n = o.call(arguments, 2), (i = function() {
                return e.apply(t || this, n.concat(o.call(arguments)))
            }).guid = e.guid = e.guid || p.guid++, i) : void 0
        },
        now: function() {
            return +new Date
        },
        support: d
    }), p.each("Boolean Number String Function Array Date RegExp Object Error".split(" "), function(e, t) {
        s["[object " + t + "]"] = t.toLowerCase()
    });
    var y = function(e) {
        var t, n, o, i, r, a, s, l, c, u, d, p, f, h, m, g, v, y = "sizzle" + -new Date,
            b = e.document,
            x = 0,
            w = 0,
            C = ne(),
            k = ne(),
            T = ne(),
            $ = function(e, t) {
                return e === t && (c = !0), 0
            },
            E = "undefined",
            S = {}.hasOwnProperty,
            N = [],
            B = N.pop,
            _ = N.push,
            A = N.push,
            D = N.slice,
            j = N.indexOf || function(e) {
                for (var t = 0, n = this.length; t < n; t++)
                    if (this[t] === e) return t;
                return -1
            },
            O = "checked|selected|async|autofocus|autoplay|controls|defer|disabled|hidden|ismap|loop|multiple|open|readonly|required|scoped",
            I = "[\\x20\\t\\r\\n\\f]",
            L = "(?:\\\\.|[\\w-]|[^\\x00-\\xa0])+",
            M = L.replace("w", "w#"),
            H = "\\[" + I + "*(" + L + ")" + I + "*(?:([*^$|!~]?=)" + I + "*(?:(['\"])((?:\\\\.|[^\\\\])*?)\\3|(" + M + ")|)|)" + I + "*\\]",
            P = ":(" + L + ")(?:\\(((['\"])((?:\\\\.|[^\\\\])*?)\\3|((?:\\\\.|[^\\\\()[\\]]|" + H.replace(3, 8) + ")*)|.*)\\)|)",
            F = new RegExp("^" + I + "+|((?:^|[^\\\\])(?:\\\\.)*)" + I + "+$", "g"),
            R = new RegExp("^" + I + "*," + I + "*"),
            q = new RegExp("^" + I + "*([>+~]|" + I + ")" + I + "*"),
            W = new RegExp("=" + I + "*([^\\]'\"]*?)" + I + "*\\]", "g"),
            z = new RegExp(P),
            Q = new RegExp("^" + M + "$"),
            U = {
                ID: new RegExp("^#(" + L + ")"),
                CLASS: new RegExp("^\\.(" + L + ")"),
                TAG: new RegExp("^(" + L.replace("w", "w*") + ")"),
                ATTR: new RegExp("^" + H),
                PSEUDO: new RegExp("^" + P),
                CHILD: new RegExp("^:(only|first|last|nth|nth-last)-(child|of-type)(?:\\(" + I + "*(even|odd|(([+-]|)(\\d*)n|)" + I + "*(?:([+-]|)" + I + "*(\\d+)|))" + I + "*\\)|)", "i"),
                bool: new RegExp("^(?:" + O + ")$", "i"),
                needsContext: new RegExp("^" + I + "*[>+~]|:(even|odd|eq|gt|lt|nth|first|last)(?:\\(" + I + "*((?:-\\d)?\\d*)" + I + "*\\)|)(?=[^-]|$)", "i")
            },
            V = /^(?:input|select|textarea|button)$/i,
            X = /^h\d$/i,
            J = /^[^{]+\{\s*\[native \w/,
            K = /^(?:#([\w-]+)|(\w+)|\.([\w-]+))$/,
            Y = /[+~]/,
            G = /'|\\/g,
            Z = new RegExp("\\\\([\\da-f]{1,6}" + I + "?|(" + I + ")|.)", "ig"),
            ee = function(e, t, n) {
                var o = "0x" + t - 65536;
                return o != o || n ? t : o < 0 ? String.fromCharCode(o + 65536) : String.fromCharCode(o >> 10 | 55296, 1023 & o | 56320)
            };
        try {
            A.apply(N = D.call(b.childNodes), b.childNodes), N[b.childNodes.length].nodeType
        } catch (t) {
            A = {
                apply: N.length ? function(e, t) {
                    _.apply(e, D.call(t))
                } : function(e, t) {
                    for (var n = e.length, o = 0; e[n++] = t[o++];);
                    e.length = n - 1
                }
            }
        }

        function te(e, t, i, r) {
            var s, l, c, p, m, g, x, w, C, k;
            if ((t ? t.ownerDocument || t : b) !== d && u(t), i = i || [], !e || "string" != typeof e) return i;
            if (1 !== (p = (t = t || d).nodeType) && 9 !== p) return [];
            if (f && !r) {
                if (s = K.exec(e))
                    if (c = s[1]) {
                        if (9 === p) {
                            if (!(l = t.getElementById(c)) || !l.parentNode) return i;
                            if (l.id === c) return i.push(l), i
                        } else if (t.ownerDocument && (l = t.ownerDocument.getElementById(c)) && v(t, l) && l.id === c) return i.push(l), i
                    } else {
                        if (s[2]) return A.apply(i, t.getElementsByTagName(e)), i;
                        if ((c = s[3]) && n.getElementsByClassName && t.getElementsByClassName) return A.apply(i, t.getElementsByClassName(c)), i
                    } if (n.qsa && (!h || !h.test(e))) {
                    if (w = x = y, C = t, k = 9 === p && e, 1 === p && "object" !== t.nodeName.toLowerCase()) {
                        for (g = pe(e), (x = t.getAttribute("id")) ? w = x.replace(G, "\\$&") : t.setAttribute("id", w), w = "[id='" + w + "'] ", m = g.length; m--;) g[m] = w + fe(g[m]);
                        C = Y.test(e) && ue(t.parentNode) || t, k = g.join(",")
                    }
                    if (k) try {
                        return A.apply(i, C.querySelectorAll(k)), i
                    } catch (e) {} finally {
                        x || t.removeAttribute("id")
                    }
                }
            }
            return function(e, t, i, r) {
                var s, l, c, u, d, p = pe(e);
                if (!r && 1 === p.length) {
                    if (2 < (l = p[0] = p[0].slice(0)).length && "ID" === (c = l[0]).type && n.getById && 9 === t.nodeType && f && o.relative[l[1].type]) {
                        if (!(t = (o.find.ID(c.matches[0].replace(Z, ee), t) || [])[0])) return i;
                        e = e.slice(l.shift().value.length)
                    }
                    for (s = U.needsContext.test(e) ? 0 : l.length; s-- && (c = l[s], !o.relative[u = c.type]);)
                        if ((d = o.find[u]) && (r = d(c.matches[0].replace(Z, ee), Y.test(l[0].type) && ue(t.parentNode) || t))) {
                            if (l.splice(s, 1), !(e = r.length && fe(l))) return A.apply(i, r), i;
                            break
                        }
                }
                return a(e, p)(r, t, !f, i, Y.test(e) && ue(t.parentNode) || t), i
            }(e.replace(F, "$1"), t, i, r)
        }

        function ne() {
            var e = [];
            return function t(n, i) {
                return e.push(n + " ") > o.cacheLength && delete t[e.shift()], t[n + " "] = i
            }
        }

        function oe(e) {
            return e[y] = !0, e
        }

        function ie(e) {
            var t = d.createElement("div");
            try {
                return !!e(t)
            } catch (e) {
                return !1
            } finally {
                t.parentNode && t.parentNode.removeChild(t), t = null
            }
        }

        function re(e, t) {
            for (var n = e.split("|"), i = e.length; i--;) o.attrHandle[n[i]] = t
        }

        function ae(e, t) {
            var n = t && e,
                o = n && 1 === e.nodeType && 1 === t.nodeType && (~t.sourceIndex || 1 << 31) - (~e.sourceIndex || 1 << 31);
            if (o) return o;
            if (n)
                for (; n = n.nextSibling;)
                    if (n === t) return -1;
            return e ? 1 : -1
        }

        function se(e) {
            return function(t) {
                return "input" === t.nodeName.toLowerCase() && t.type === e
            }
        }

        function le(e) {
            return function(t) {
                var n = t.nodeName.toLowerCase();
                return ("input" === n || "button" === n) && t.type === e
            }
        }

        function ce(e) {
            return oe(function(t) {
                return t = +t, oe(function(n, o) {
                    for (var i, r = e([], n.length, t), a = r.length; a--;) n[i = r[a]] && (n[i] = !(o[i] = n[i]))
                })
            })
        }

        function ue(e) {
            return e && typeof e.getElementsByTagName !== E && e
        }
        for (t in n = te.support = {}, r = te.isXML = function(e) {
                var t = e && (e.ownerDocument || e).documentElement;
                return !!t && "HTML" !== t.nodeName
            }, u = te.setDocument = function(e) {
                var t, i = e ? e.ownerDocument || e : b,
                    a = i.defaultView;
                return i !== d && 9 === i.nodeType && i.documentElement ? (p = (d = i).documentElement, f = !r(i), a && a !== a.top && (a.addEventListener ? a.addEventListener("unload", function() {
                    u()
                }, !1) : a.attachEvent && a.attachEvent("onunload", function() {
                    u()
                })), n.attributes = ie(function(e) {
                    return e.className = "i", !e.getAttribute("className")
                }), n.getElementsByTagName = ie(function(e) {
                    return e.appendChild(i.createComment("")), !e.getElementsByTagName("*").length
                }), n.getElementsByClassName = J.test(i.getElementsByClassName) && ie(function(e) {
                    return e.innerHTML = "<div class='a'></div><div class='a i'></div>", e.firstChild.className = "i", 2 === e.getElementsByClassName("i").length
                }), n.getById = ie(function(e) {
                    return p.appendChild(e).id = y, !i.getElementsByName || !i.getElementsByName(y).length
                }), n.getById ? (o.find.ID = function(e, t) {
                    if (typeof t.getElementById !== E && f) {
                        var n = t.getElementById(e);
                        return n && n.parentNode ? [n] : []
                    }
                }, o.filter.ID = function(e) {
                    var t = e.replace(Z, ee);
                    return function(e) {
                        return e.getAttribute("id") === t
                    }
                }) : (delete o.find.ID, o.filter.ID = function(e) {
                    var t = e.replace(Z, ee);
                    return function(e) {
                        var n = typeof e.getAttributeNode !== E && e.getAttributeNode("id");
                        return n && n.value === t
                    }
                }), o.find.TAG = n.getElementsByTagName ? function(e, t) {
                    return typeof t.getElementsByTagName !== E ? t.getElementsByTagName(e) : void 0
                } : function(e, t) {
                    var n, o = [],
                        i = 0,
                        r = t.getElementsByTagName(e);
                    if ("*" === e) {
                        for (; n = r[i++];) 1 === n.nodeType && o.push(n);
                        return o
                    }
                    return r
                }, o.find.CLASS = n.getElementsByClassName && function(e, t) {
                    return typeof t.getElementsByClassName !== E && f ? t.getElementsByClassName(e) : void 0
                }, m = [], h = [], (n.qsa = J.test(i.querySelectorAll)) && (ie(function(e) {
                    e.innerHTML = "<select t=''><option selected=''></option></select>", e.querySelectorAll("[t^='']").length && h.push("[*^$]=" + I + "*(?:''|\"\")"), e.querySelectorAll("[selected]").length || h.push("\\[" + I + "*(?:value|" + O + ")"), e.querySelectorAll(":checked").length || h.push(":checked")
                }), ie(function(e) {
                    var t = i.createElement("input");
                    t.setAttribute("type", "hidden"), e.appendChild(t).setAttribute("name", "D"), e.querySelectorAll("[name=d]").length && h.push("name" + I + "*[*^$|!~]?="), e.querySelectorAll(":enabled").length || h.push(":enabled", ":disabled"), e.querySelectorAll("*,:x"), h.push(",.*:")
                })), (n.matchesSelector = J.test(g = p.webkitMatchesSelector || p.mozMatchesSelector || p.oMatchesSelector || p.msMatchesSelector)) && ie(function(e) {
                    n.disconnectedMatch = g.call(e, "div"), g.call(e, "[s!='']:x"), m.push("!=", P)
                }), h = h.length && new RegExp(h.join("|")), m = m.length && new RegExp(m.join("|")), t = J.test(p.compareDocumentPosition), v = t || J.test(p.contains) ? function(e, t) {
                    var n = 9 === e.nodeType ? e.documentElement : e,
                        o = t && t.parentNode;
                    return e === o || !(!o || 1 !== o.nodeType || !(n.contains ? n.contains(o) : e.compareDocumentPosition && 16 & e.compareDocumentPosition(o)))
                } : function(e, t) {
                    if (t)
                        for (; t = t.parentNode;)
                            if (t === e) return !0;
                    return !1
                }, $ = t ? function(e, t) {
                    if (e === t) return c = !0, 0;
                    var o = !e.compareDocumentPosition - !t.compareDocumentPosition;
                    return o || (1 & (o = (e.ownerDocument || e) === (t.ownerDocument || t) ? e.compareDocumentPosition(t) : 1) || !n.sortDetached && t.compareDocumentPosition(e) === o ? e === i || e.ownerDocument === b && v(b, e) ? -1 : t === i || t.ownerDocument === b && v(b, t) ? 1 : l ? j.call(l, e) - j.call(l, t) : 0 : 4 & o ? -1 : 1)
                } : function(e, t) {
                    if (e === t) return c = !0, 0;
                    var n, o = 0,
                        r = e.parentNode,
                        a = t.parentNode,
                        s = [e],
                        u = [t];
                    if (!r || !a) return e === i ? -1 : t === i ? 1 : r ? -1 : a ? 1 : l ? j.call(l, e) - j.call(l, t) : 0;
                    if (r === a) return ae(e, t);
                    for (n = e; n = n.parentNode;) s.unshift(n);
                    for (n = t; n = n.parentNode;) u.unshift(n);
                    for (; s[o] === u[o];) o++;
                    return o ? ae(s[o], u[o]) : s[o] === b ? -1 : u[o] === b ? 1 : 0
                }, i) : d
            }, te.matches = function(e, t) {
                return te(e, null, null, t)
            }, te.matchesSelector = function(e, t) {
                if ((e.ownerDocument || e) !== d && u(e), t = t.replace(W, "='$1']"), !(!n.matchesSelector || !f || m && m.test(t) || h && h.test(t))) try {
                    var o = g.call(e, t);
                    if (o || n.disconnectedMatch || e.document && 11 !== e.document.nodeType) return o
                } catch (e) {}
                return 0 < te(t, d, null, [e]).length
            }, te.contains = function(e, t) {
                return (e.ownerDocument || e) !== d && u(e), v(e, t)
            }, te.attr = function(e, t) {
                (e.ownerDocument || e) !== d && u(e);
                var i = o.attrHandle[t.toLowerCase()],
                    r = i && S.call(o.attrHandle, t.toLowerCase()) ? i(e, t, !f) : void 0;
                return void 0 !== r ? r : n.attributes || !f ? e.getAttribute(t) : (r = e.getAttributeNode(t)) && r.specified ? r.value : null
            }, te.error = function(e) {
                throw new Error("Syntax error, unrecognized expression: " + e)
            }, te.uniqueSort = function(e) {
                var t, o = [],
                    i = 0,
                    r = 0;
                if (c = !n.detectDuplicates, l = !n.sortStable && e.slice(0), e.sort($), c) {
                    for (; t = e[r++];) t === e[r] && (i = o.push(r));
                    for (; i--;) e.splice(o[i], 1)
                }
                return l = null, e
            }, i = te.getText = function(e) {
                var t, n = "",
                    o = 0,
                    r = e.nodeType;
                if (r) {
                    if (1 === r || 9 === r || 11 === r) {
                        if ("string" == typeof e.textContent) return e.textContent;
                        for (e = e.firstChild; e; e = e.nextSibling) n += i(e)
                    } else if (3 === r || 4 === r) return e.nodeValue
                } else
                    for (; t = e[o++];) n += i(t);
                return n
            }, (o = te.selectors = {
                cacheLength: 50,
                createPseudo: oe,
                match: U,
                attrHandle: {},
                find: {},
                relative: {
                    ">": {
                        dir: "parentNode",
                        first: !0
                    },
                    " ": {
                        dir: "parentNode"
                    },
                    "+": {
                        dir: "previousSibling",
                        first: !0
                    },
                    "~": {
                        dir: "previousSibling"
                    }
                },
                preFilter: {
                    ATTR: function(e) {
                        return e[1] = e[1].replace(Z, ee), e[3] = (e[4] || e[5] || "").replace(Z, ee), "~=" === e[2] && (e[3] = " " + e[3] + " "), e.slice(0, 4)
                    },
                    CHILD: function(e) {
                        return e[1] = e[1].toLowerCase(), "nth" === e[1].slice(0, 3) ? (e[3] || te.error(e[0]), e[4] = +(e[4] ? e[5] + (e[6] || 1) : 2 * ("even" === e[3] || "odd" === e[3])), e[5] = +(e[7] + e[8] || "odd" === e[3])) : e[3] && te.error(e[0]), e
                    },
                    PSEUDO: function(e) {
                        var t, n = !e[5] && e[2];
                        return U.CHILD.test(e[0]) ? null : (e[3] && void 0 !== e[4] ? e[2] = e[4] : n && z.test(n) && (t = pe(n, !0)) && (t = n.indexOf(")", n.length - t) - n.length) && (e[0] = e[0].slice(0, t), e[2] = n.slice(0, t)), e.slice(0, 3))
                    }
                },
                filter: {
                    TAG: function(e) {
                        var t = e.replace(Z, ee).toLowerCase();
                        return "*" === e ? function() {
                            return !0
                        } : function(e) {
                            return e.nodeName && e.nodeName.toLowerCase() === t
                        }
                    },
                    CLASS: function(e) {
                        var t = C[e + " "];
                        return t || (t = new RegExp("(^|" + I + ")" + e + "(" + I + "|$)")) && C(e, function(e) {
                            return t.test("string" == typeof e.className && e.className || typeof e.getAttribute !== E && e.getAttribute("class") || "")
                        })
                    },
                    ATTR: function(e, t, n) {
                        return function(o) {
                            var i = te.attr(o, e);
                            return null == i ? "!=" === t : !t || (i += "", "=" === t ? i === n : "!=" === t ? i !== n : "^=" === t ? n && 0 === i.indexOf(n) : "*=" === t ? n && -1 < i.indexOf(n) : "$=" === t ? n && i.slice(-n.length) === n : "~=" === t ? -1 < (" " + i + " ").indexOf(n) : "|=" === t && (i === n || i.slice(0, n.length + 1) === n + "-"))
                        }
                    },
                    CHILD: function(e, t, n, o, i) {
                        var r = "nth" !== e.slice(0, 3),
                            a = "last" !== e.slice(-4),
                            s = "of-type" === t;
                        return 1 === o && 0 === i ? function(e) {
                            return !!e.parentNode
                        } : function(t, n, l) {
                            var c, u, d, p, f, h, m = r !== a ? "nextSibling" : "previousSibling",
                                g = t.parentNode,
                                v = s && t.nodeName.toLowerCase(),
                                b = !l && !s;
                            if (g) {
                                if (r) {
                                    for (; m;) {
                                        for (d = t; d = d[m];)
                                            if (s ? d.nodeName.toLowerCase() === v : 1 === d.nodeType) return !1;
                                        h = m = "only" === e && !h && "nextSibling"
                                    }
                                    return !0
                                }
                                if (h = [a ? g.firstChild : g.lastChild], a && b) {
                                    for (f = (c = (u = g[y] || (g[y] = {}))[e] || [])[0] === x && c[1], p = c[0] === x && c[2], d = f && g.childNodes[f]; d = ++f && d && d[m] || (p = f = 0) || h.pop();)
                                        if (1 === d.nodeType && ++p && d === t) {
                                            u[e] = [x, f, p];
                                            break
                                        }
                                } else if (b && (c = (t[y] || (t[y] = {}))[e]) && c[0] === x) p = c[1];
                                else
                                    for (;
                                        (d = ++f && d && d[m] || (p = f = 0) || h.pop()) && ((s ? d.nodeName.toLowerCase() !== v : 1 !== d.nodeType) || !++p || (b && ((d[y] || (d[y] = {}))[e] = [x, p]), d !== t)););
                                return (p -= i) === o || p % o == 0 && 0 <= p / o
                            }
                        }
                    },
                    PSEUDO: function(e, t) {
                        var n, i = o.pseudos[e] || o.setFilters[e.toLowerCase()] || te.error("unsupported pseudo: " + e);
                        return i[y] ? i(t) : 1 < i.length ? (n = [e, e, "", t], o.setFilters.hasOwnProperty(e.toLowerCase()) ? oe(function(e, n) {
                            for (var o, r = i(e, t), a = r.length; a--;) e[o = j.call(e, r[a])] = !(n[o] = r[a])
                        }) : function(e) {
                            return i(e, 0, n)
                        }) : i
                    }
                },
                pseudos: {
                    not: oe(function(e) {
                        var t = [],
                            n = [],
                            o = a(e.replace(F, "$1"));
                        return o[y] ? oe(function(e, t, n, i) {
                            for (var r, a = o(e, null, i, []), s = e.length; s--;)(r = a[s]) && (e[s] = !(t[s] = r))
                        }) : function(e, i, r) {
                            return t[0] = e, o(t, null, r, n), !n.pop()
                        }
                    }),
                    has: oe(function(e) {
                        return function(t) {
                            return 0 < te(e, t).length
                        }
                    }),
                    contains: oe(function(e) {
                        return function(t) {
                            return -1 < (t.textContent || t.innerText || i(t)).indexOf(e)
                        }
                    }),
                    lang: oe(function(e) {
                        return Q.test(e || "") || te.error("unsupported lang: " + e), e = e.replace(Z, ee).toLowerCase(),
                            function(t) {
                                var n;
                                do {
                                    if (n = f ? t.lang : t.getAttribute("xml:lang") || t.getAttribute("lang")) return (n = n.toLowerCase()) === e || 0 === n.indexOf(e + "-")
                                } while ((t = t.parentNode) && 1 === t.nodeType);
                                return !1
                            }
                    }),
                    target: function(t) {
                        var n = e.location && e.location.hash;
                        return n && n.slice(1) === t.id
                    },
                    root: function(e) {
                        return e === p
                    },
                    focus: function(e) {
                        return e === d.activeElement && (!d.hasFocus || d.hasFocus()) && !!(e.type || e.href || ~e.tabIndex)
                    },
                    enabled: function(e) {
                        return !1 === e.disabled
                    },
                    disabled: function(e) {
                        return !0 === e.disabled
                    },
                    checked: function(e) {
                        var t = e.nodeName.toLowerCase();
                        return "input" === t && !!e.checked || "option" === t && !!e.selected
                    },
                    selected: function(e) {
                        return e.parentNode && e.parentNode.selectedIndex, !0 === e.selected
                    },
                    empty: function(e) {
                        for (e = e.firstChild; e; e = e.nextSibling)
                            if (e.nodeType < 6) return !1;
                        return !0
                    },
                    parent: function(e) {
                        return !o.pseudos.empty(e)
                    },
                    header: function(e) {
                        return X.test(e.nodeName)
                    },
                    input: function(e) {
                        return V.test(e.nodeName)
                    },
                    button: function(e) {
                        var t = e.nodeName.toLowerCase();
                        return "input" === t && "button" === e.type || "button" === t
                    },
                    text: function(e) {
                        var t;
                        return "input" === e.nodeName.toLowerCase() && "text" === e.type && (null == (t = e.getAttribute("type")) || "text" === t.toLowerCase())
                    },
                    first: ce(function() {
                        return [0]
                    }),
                    last: ce(function(e, t) {
                        return [t - 1]
                    }),
                    eq: ce(function(e, t, n) {
                        return [n < 0 ? n + t : n]
                    }),
                    even: ce(function(e, t) {
                        for (var n = 0; n < t; n += 2) e.push(n);
                        return e
                    }),
                    odd: ce(function(e, t) {
                        for (var n = 1; n < t; n += 2) e.push(n);
                        return e
                    }),
                    lt: ce(function(e, t, n) {
                        for (var o = n < 0 ? n + t : n; 0 <= --o;) e.push(o);
                        return e
                    }),
                    gt: ce(function(e, t, n) {
                        for (var o = n < 0 ? n + t : n; ++o < t;) e.push(o);
                        return e
                    })
                }
            }).pseudos.nth = o.pseudos.eq, {
                radio: !0,
                checkbox: !0,
                file: !0,
                password: !0,
                image: !0
            }) o.pseudos[t] = se(t);
        for (t in {
                submit: !0,
                reset: !0
            }) o.pseudos[t] = le(t);

        function de() {}

        function pe(e, t) {
            var n, i, r, a, s, l, c, u = k[e + " "];
            if (u) return t ? 0 : u.slice(0);
            for (s = e, l = [], c = o.preFilter; s;) {
                for (a in (!n || (i = R.exec(s))) && (i && (s = s.slice(i[0].length) || s), l.push(r = [])), n = !1, (i = q.exec(s)) && (n = i.shift(), r.push({
                        value: n,
                        type: i[0].replace(F, " ")
                    }), s = s.slice(n.length)), o.filter) !(i = U[a].exec(s)) || c[a] && !(i = c[a](i)) || (n = i.shift(), r.push({
                    value: n,
                    type: a,
                    matches: i
                }), s = s.slice(n.length));
                if (!n) break
            }
            return t ? s.length : s ? te.error(e) : k(e, l).slice(0)
        }

        function fe(e) {
            for (var t = 0, n = e.length, o = ""; t < n; t++) o += e[t].value;
            return o
        }

        function he(e, t, n) {
            var o = t.dir,
                i = n && "parentNode" === o,
                r = w++;
            return t.first ? function(t, n, r) {
                for (; t = t[o];)
                    if (1 === t.nodeType || i) return e(t, n, r)
            } : function(t, n, a) {
                var s, l, c = [x, r];
                if (a) {
                    for (; t = t[o];)
                        if ((1 === t.nodeType || i) && e(t, n, a)) return !0
                } else
                    for (; t = t[o];)
                        if (1 === t.nodeType || i) {
                            if ((s = (l = t[y] || (t[y] = {}))[o]) && s[0] === x && s[1] === r) return c[2] = s[2];
                            if ((l[o] = c)[2] = e(t, n, a)) return !0
                        }
            }
        }

        function me(e) {
            return 1 < e.length ? function(t, n, o) {
                for (var i = e.length; i--;)
                    if (!e[i](t, n, o)) return !1;
                return !0
            } : e[0]
        }

        function ge(e, t, n, o, i) {
            for (var r, a = [], s = 0, l = e.length, c = null != t; s < l; s++)(r = e[s]) && (!n || n(r, o, i)) && (a.push(r), c && t.push(s));
            return a
        }

        function ve(e, t, n, o, i, r) {
            return o && !o[y] && (o = ve(o)), i && !i[y] && (i = ve(i, r)), oe(function(r, a, s, l) {
                var c, u, d, p = [],
                    f = [],
                    h = a.length,
                    m = r || function(e, t, n) {
                        for (var o = 0, i = t.length; o < i; o++) te(e, t[o], n);
                        return n
                    }(t || "*", s.nodeType ? [s] : s, []),
                    g = !e || !r && t ? m : ge(m, p, e, s, l),
                    v = n ? i || (r ? e : h || o) ? [] : a : g;
                if (n && n(g, v, s, l), o)
                    for (c = ge(v, f), o(c, [], s, l), u = c.length; u--;)(d = c[u]) && (v[f[u]] = !(g[f[u]] = d));
                if (r) {
                    if (i || e) {
                        if (i) {
                            for (c = [], u = v.length; u--;)(d = v[u]) && c.push(g[u] = d);
                            i(null, v = [], c, l)
                        }
                        for (u = v.length; u--;)(d = v[u]) && -1 < (c = i ? j.call(r, d) : p[u]) && (r[c] = !(a[c] = d))
                    }
                } else v = ge(v === a ? v.splice(h, v.length) : v), i ? i(null, a, v, l) : A.apply(a, v)
            })
        }

        function ye(e) {
            for (var t, n, i, r = e.length, a = o.relative[e[0].type], l = a || o.relative[" "], c = a ? 1 : 0, u = he(function(e) {
                    return e === t
                }, l, !0), d = he(function(e) {
                    return -1 < j.call(t, e)
                }, l, !0), p = [function(e, n, o) {
                    return !a && (o || n !== s) || ((t = n).nodeType ? u(e, n, o) : d(e, n, o))
                }]; c < r; c++)
                if (n = o.relative[e[c].type]) p = [he(me(p), n)];
                else {
                    if ((n = o.filter[e[c].type].apply(null, e[c].matches))[y]) {
                        for (i = ++c; i < r && !o.relative[e[i].type]; i++);
                        return ve(1 < c && me(p), 1 < c && fe(e.slice(0, c - 1).concat({
                            value: " " === e[c - 2].type ? "*" : ""
                        })).replace(F, "$1"), n, c < i && ye(e.slice(c, i)), i < r && ye(e = e.slice(i)), i < r && fe(e))
                    }
                    p.push(n)
                } return me(p)
        }
        return de.prototype = o.filters = o.pseudos, o.setFilters = new de, a = te.compile = function(e, t) {
            var n, i, r, a, l, c, u = [],
                p = [],
                f = T[e + " "];
            if (!f) {
                for (t || (t = pe(e)), n = t.length; n--;)(f = ye(t[n]))[y] ? u.push(f) : p.push(f);
                f = T(e, (i = p, a = 0 < (r = u).length, l = 0 < i.length, c = function(e, t, n, c, u) {
                    var p, f, h, m = 0,
                        g = "0",
                        v = e && [],
                        y = [],
                        b = s,
                        w = e || l && o.find.TAG("*", u),
                        C = x += null == b ? 1 : Math.random() || .1,
                        k = w.length;
                    for (u && (s = t !== d && t); g !== k && null != (p = w[g]); g++) {
                        if (l && p) {
                            for (f = 0; h = i[f++];)
                                if (h(p, t, n)) {
                                    c.push(p);
                                    break
                                } u && (x = C)
                        }
                        a && ((p = !h && p) && m--, e && v.push(p))
                    }
                    if (m += g, a && g !== m) {
                        for (f = 0; h = r[f++];) h(v, y, t, n);
                        if (e) {
                            if (0 < m)
                                for (; g--;) v[g] || y[g] || (y[g] = B.call(c));
                            y = ge(y)
                        }
                        A.apply(c, y), u && !e && 0 < y.length && 1 < m + r.length && te.uniqueSort(c)
                    }
                    return u && (x = C, s = b), v
                }, a ? oe(c) : c))
            }
            return f
        }, n.sortStable = y.split("").sort($).join("") === y, n.detectDuplicates = !!c, u(), n.sortDetached = ie(function(e) {
            return 1 & e.compareDocumentPosition(d.createElement("div"))
        }), ie(function(e) {
            return e.innerHTML = "<a href='#'></a>", "#" === e.firstChild.getAttribute("href")
        }) || re("type|href|height|width", function(e, t, n) {
            return n ? void 0 : e.getAttribute(t, "type" === t.toLowerCase() ? 1 : 2)
        }), n.attributes && ie(function(e) {
            return e.innerHTML = "<input/>", e.firstChild.setAttribute("value", ""), "" === e.firstChild.getAttribute("value")
        }) || re("value", function(e, t, n) {
            return n || "input" !== e.nodeName.toLowerCase() ? void 0 : e.defaultValue
        }), ie(function(e) {
            return null == e.getAttribute("disabled")
        }) || re(O, function(e, t, n) {
            var o;
            return n ? void 0 : !0 === e[t] ? t.toLowerCase() : (o = e.getAttributeNode(t)) && o.specified ? o.value : null
        }), te
    }(e);
    p.find = y, p.expr = y.selectors, p.expr[":"] = p.expr.pseudos, p.unique = y.uniqueSort, p.text = y.getText, p.isXMLDoc = y.isXML, p.contains = y.contains;
    var b = p.expr.match.needsContext,
        x = /^<(\w+)\s*\/?>(?:<\/\1>|)$/,
        w = /^.[^:#\[\.,]*$/;

    function C(e, t, n) {
        if (p.isFunction(t)) return p.grep(e, function(e, o) {
            return !!t.call(e, o, e) !== n
        });
        if (t.nodeType) return p.grep(e, function(e) {
            return e === t !== n
        });
        if ("string" == typeof t) {
            if (w.test(t)) return p.filter(t, e, n);
            t = p.filter(t, e)
        }
        return p.grep(e, function(e) {
            return 0 <= p.inArray(e, t) !== n
        })
    }
    p.filter = function(e, t, n) {
        var o = t[0];
        return n && (e = ":not(" + e + ")"), 1 === t.length && 1 === o.nodeType ? p.find.matchesSelector(o, e) ? [o] : [] : p.find.matches(e, p.grep(t, function(e) {
            return 1 === e.nodeType
        }))
    }, p.fn.extend({
        find: function(e) {
            var t, n = [],
                o = this,
                i = o.length;
            if ("string" != typeof e) return this.pushStack(p(e).filter(function() {
                for (t = 0; t < i; t++)
                    if (p.contains(o[t], this)) return !0
            }));
            for (t = 0; t < i; t++) p.find(e, o[t], n);
            return (n = this.pushStack(1 < i ? p.unique(n) : n)).selector = this.selector ? this.selector + " " + e : e, n
        },
        filter: function(e) {
            return this.pushStack(C(this, e || [], !1))
        },
        not: function(e) {
            return this.pushStack(C(this, e || [], !0))
        },
        is: function(e) {
            return !!C(this, "string" == typeof e && b.test(e) ? p(e) : e || [], !1).length
        }
    });
    var k, T = e.document,
        $ = /^(?:\s*(<[\w\W]+>)[^>]*|#([\w-]*))$/;
    (p.fn.init = function(e, t) {
        var n, o;
        if (!e) return this;
        if ("string" == typeof e) {
            if (!(n = "<" === e.charAt(0) && ">" === e.charAt(e.length - 1) && 3 <= e.length ? [null, e, null] : $.exec(e)) || !n[1] && t) return !t || t.jquery ? (t || k).find(e) : this.constructor(t).find(e);
            if (n[1]) {
                if (t = t instanceof p ? t[0] : t, p.merge(this, p.parseHTML(n[1], t && t.nodeType ? t.ownerDocument || t : T, !0)), x.test(n[1]) && p.isPlainObject(t))
                    for (n in t) p.isFunction(this[n]) ? this[n](t[n]) : this.attr(n, t[n]);
                return this
            }
            if ((o = T.getElementById(n[2])) && o.parentNode) {
                if (o.id !== n[2]) return k.find(e);
                this.length = 1, this[0] = o
            }
            return this.context = T, this.selector = e, this
        }
        return e.nodeType ? (this.context = this[0] = e, this.length = 1, this) : p.isFunction(e) ? void 0 !== k.ready ? k.ready(e) : e(p) : (void 0 !== e.selector && (this.selector = e.selector, this.context = e.context), p.makeArray(e, this))
    }).prototype = p.fn, k = p(T);
    var E = /^(?:parents|prev(?:Until|All))/,
        S = {
            children: !0,
            contents: !0,
            next: !0,
            prev: !0
        };

    function N(e, t) {
        for (;
            (e = e[t]) && 1 !== e.nodeType;);
        return e
    }
    p.extend({
        dir: function(e, t, n) {
            for (var o = [], i = e[t]; i && 9 !== i.nodeType && (void 0 === n || 1 !== i.nodeType || !p(i).is(n));) 1 === i.nodeType && o.push(i), i = i[t];
            return o
        },
        sibling: function(e, t) {
            for (var n = []; e; e = e.nextSibling) 1 === e.nodeType && e !== t && n.push(e);
            return n
        }
    }), p.fn.extend({
        has: function(e) {
            var t, n = p(e, this),
                o = n.length;
            return this.filter(function() {
                for (t = 0; t < o; t++)
                    if (p.contains(this, n[t])) return !0
            })
        },
        closest: function(e, t) {
            for (var n, o = 0, i = this.length, r = [], a = b.test(e) || "string" != typeof e ? p(e, t || this.context) : 0; o < i; o++)
                for (n = this[o]; n && n !== t; n = n.parentNode)
                    if (n.nodeType < 11 && (a ? -1 < a.index(n) : 1 === n.nodeType && p.find.matchesSelector(n, e))) {
                        r.push(n);
                        break
                    } return this.pushStack(1 < r.length ? p.unique(r) : r)
        },
        index: function(e) {
            return e ? "string" == typeof e ? p.inArray(this[0], p(e)) : p.inArray(e.jquery ? e[0] : e, this) : this[0] && this[0].parentNode ? this.first().prevAll().length : -1
        },
        add: function(e, t) {
            return this.pushStack(p.unique(p.merge(this.get(), p(e, t))))
        },
        addBack: function(e) {
            return this.add(null == e ? this.prevObject : this.prevObject.filter(e))
        }
    }), p.each({
        parent: function(e) {
            var t = e.parentNode;
            return t && 11 !== t.nodeType ? t : null
        },
        parents: function(e) {
            return p.dir(e, "parentNode")
        },
        parentsUntil: function(e, t, n) {
            return p.dir(e, "parentNode", n)
        },
        next: function(e) {
            return N(e, "nextSibling")
        },
        prev: function(e) {
            return N(e, "previousSibling")
        },
        nextAll: function(e) {
            return p.dir(e, "nextSibling")
        },
        prevAll: function(e) {
            return p.dir(e, "previousSibling")
        },
        nextUntil: function(e, t, n) {
            return p.dir(e, "nextSibling", n)
        },
        prevUntil: function(e, t, n) {
            return p.dir(e, "previousSibling", n)
        },
        siblings: function(e) {
            return p.sibling((e.parentNode || {}).firstChild, e)
        },
        children: function(e) {
            return p.sibling(e.firstChild)
        },
        contents: function(e) {
            return p.nodeName(e, "iframe") ? e.contentDocument || e.contentWindow.document : p.merge([], e.childNodes)
        }
    }, function(e, t) {
        p.fn[e] = function(n, o) {
            var i = p.map(this, t, n);
            return "Until" !== e.slice(-5) && (o = n), o && "string" == typeof o && (i = p.filter(o, i)), 1 < this.length && (S[e] || (i = p.unique(i)), E.test(e) && (i = i.reverse())), this.pushStack(i)
        }
    });
    var B, _ = /\S+/g,
        A = {};

    function D() {
        T.addEventListener ? (T.removeEventListener("DOMContentLoaded", j, !1), e.removeEventListener("load", j, !1)) : (T.detachEvent("onreadystatechange", j), e.detachEvent("onload", j))
    }

    function j() {
        (T.addEventListener || "load" === event.type || "complete" === T.readyState) && (D(), p.ready())
    }
    p.Callbacks = function(e) {
        var t, n, o, i, r, a, s, l, c = [],
            u = !(e = "string" == typeof e ? A[e] || (n = A[t = e] = {}, p.each(t.match(_) || [], function(e, t) {
                n[t] = !0
            }), n) : p.extend({}, e)).once && [],
            d = function(t) {
                for (i = e.memory && t, r = !0, s = l || 0, l = 0, a = c.length, o = !0; c && s < a; s++)
                    if (!1 === c[s].apply(t[0], t[1]) && e.stopOnFalse) {
                        i = !1;
                        break
                    } o = !1, c && (u ? u.length && d(u.shift()) : i ? c = [] : f.disable())
            },
            f = {
                add: function() {
                    if (c) {
                        var t = c.length;
                        ! function t(n) {
                            p.each(n, function(n, o) {
                                var i = p.type(o);
                                "function" === i ? e.unique && f.has(o) || c.push(o) : o && o.length && "string" !== i && t(o)
                            })
                        }(arguments), o ? a = c.length : i && (l = t, d(i))
                    }
                    return this
                },
                remove: function() {
                    return c && p.each(arguments, function(e, t) {
                        for (var n; - 1 < (n = p.inArray(t, c, n));) c.splice(n, 1), o && (n <= a && a--, n <= s && s--)
                    }), this
                },
                has: function(e) {
                    return e ? -1 < p.inArray(e, c) : !(!c || !c.length)
                },
                empty: function() {
                    return c = [], a = 0, this
                },
                disable: function() {
                    return c = u = i = void 0, this
                },
                disabled: function() {
                    return !c
                },
                lock: function() {
                    return u = void 0, i || f.disable(), this
                },
                locked: function() {
                    return !u
                },
                fireWith: function(e, t) {
                    return !c || r && !u || (t = [e, (t = t || []).slice ? t.slice() : t], o ? u.push(t) : d(t)), this
                },
                fire: function() {
                    return f.fireWith(this, arguments), this
                },
                fired: function() {
                    return !!r
                }
            };
        return f
    }, p.extend({
        Deferred: function(e) {
            var t = [
                    ["resolve", "done", p.Callbacks("once memory"), "resolved"],
                    ["reject", "fail", p.Callbacks("once memory"), "rejected"],
                    ["notify", "progress", p.Callbacks("memory")]
                ],
                n = "pending",
                o = {
                    state: function() {
                        return n
                    },
                    always: function() {
                        return i.done(arguments).fail(arguments), this
                    },
                    then: function() {
                        var e = arguments;
                        return p.Deferred(function(n) {
                            p.each(t, function(t, r) {
                                var a = p.isFunction(e[t]) && e[t];
                                i[r[1]](function() {
                                    var e = a && a.apply(this, arguments);
                                    e && p.isFunction(e.promise) ? e.promise().done(n.resolve).fail(n.reject).progress(n.notify) : n[r[0] + "With"](this === o ? n.promise() : this, a ? [e] : arguments)
                                })
                            }), e = null
                        }).promise()
                    },
                    promise: function(e) {
                        return null != e ? p.extend(e, o) : o
                    }
                },
                i = {};
            return o.pipe = o.then, p.each(t, function(e, r) {
                var a = r[2],
                    s = r[3];
                o[r[1]] = a.add, s && a.add(function() {
                    n = s
                }, t[1 ^ e][2].disable, t[2][2].lock), i[r[0]] = function() {
                    return i[r[0] + "With"](this === i ? o : this, arguments), this
                }, i[r[0] + "With"] = a.fireWith
            }), o.promise(i), e && e.call(i, i), i
        },
        when: function(e) {
            var t, n, i, r = 0,
                a = o.call(arguments),
                s = a.length,
                l = 1 !== s || e && p.isFunction(e.promise) ? s : 0,
                c = 1 === l ? e : p.Deferred(),
                u = function(e, n, i) {
                    return function(r) {
                        n[e] = this, i[e] = 1 < arguments.length ? o.call(arguments) : r, i === t ? c.notifyWith(n, i) : --l || c.resolveWith(n, i)
                    }
                };
            if (1 < s)
                for (t = new Array(s), n = new Array(s), i = new Array(s); r < s; r++) a[r] && p.isFunction(a[r].promise) ? a[r].promise().done(u(r, i, a)).fail(c.reject).progress(u(r, n, t)) : --l;
            return l || c.resolveWith(i, a), c.promise()
        }
    }), p.fn.ready = function(e) {
        return p.ready.promise().done(e), this
    }, p.extend({
        isReady: !1,
        readyWait: 1,
        holdReady: function(e) {
            e ? p.readyWait++ : p.ready(!0)
        },
        ready: function(e) {
            if (!0 === e ? !--p.readyWait : !p.isReady) {
                if (!T.body) return setTimeout(p.ready);
                (p.isReady = !0) !== e && 0 < --p.readyWait || (B.resolveWith(T, [p]), p.fn.trigger && p(T).trigger("ready").off("ready"))
            }
        }
    }), p.ready.promise = function(t) {
        if (!B)
            if (B = p.Deferred(), "complete" === T.readyState) setTimeout(p.ready);
            else if (T.addEventListener) T.addEventListener("DOMContentLoaded", j, !1), e.addEventListener("load", j, !1);
        else {
            T.attachEvent("onreadystatechange", j), e.attachEvent("onload", j);
            var n = !1;
            try {
                n = null == e.frameElement && T.documentElement
            } catch (t) {}
            n && n.doScroll && function e() {
                if (!p.isReady) {
                    try {
                        n.doScroll("left")
                    } catch (t) {
                        return setTimeout(e, 50)
                    }
                    D(), p.ready()
                }
            }()
        }
        return B.promise(t)
    };
    var O, I = "undefined";
    for (O in p(d)) break;
    d.ownLast = "0" !== O, d.inlineBlockNeedsLayout = !1, p(function() {
            var e, t, n = T.getElementsByTagName("body")[0];
            n && ((e = T.createElement("div")).style.cssText = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px;margin-top:1px", t = T.createElement("div"), n.appendChild(e).appendChild(t), typeof t.style.zoom !== I && (t.style.cssText = "border:0;margin:0;width:1px;padding:1px;display:inline;zoom:1", (d.inlineBlockNeedsLayout = 3 === t.offsetWidth) && (n.style.zoom = 1)), n.removeChild(e), e = t = null)
        }),
        function() {
            var e = T.createElement("div");
            if (null == d.deleteExpando) {
                d.deleteExpando = !0;
                try {
                    delete e.test
                } catch (e) {
                    d.deleteExpando = !1
                }
            }
            e = null
        }(), p.acceptData = function(e) {
            var t = p.noData[(e.nodeName + " ").toLowerCase()],
                n = +e.nodeType || 1;
            return (1 === n || 9 === n) && (!t || !0 !== t && e.getAttribute("classid") === t)
        };
    var L = /^(?:\{[\w\W]*\}|\[[\w\W]*\])$/,
        M = /([A-Z])/g;

    function H(e, t, n) {
        if (void 0 === n && 1 === e.nodeType) {
            var o = "data-" + t.replace(M, "-$1").toLowerCase();
            if ("string" == typeof(n = e.getAttribute(o))) {
                try {
                    n = "true" === n || "false" !== n && ("null" === n ? null : +n + "" === n ? +n : L.test(n) ? p.parseJSON(n) : n)
                } catch (e) {}
                p.data(e, t, n)
            } else n = void 0
        }
        return n
    }

    function P(e) {
        var t;
        for (t in e)
            if (("data" !== t || !p.isEmptyObject(e[t])) && "toJSON" !== t) return !1;
        return !0
    }

    function F(e, t, o, i) {
        if (p.acceptData(e)) {
            var r, a, s = p.expando,
                l = e.nodeType,
                c = l ? p.cache : e,
                u = l ? e[s] : e[s] && s;
            if (u && c[u] && (i || c[u].data) || void 0 !== o || "string" != typeof t) return u || (u = l ? e[s] = n.pop() || p.guid++ : s), c[u] || (c[u] = l ? {} : {
                toJSON: p.noop
            }), ("object" == typeof t || "function" == typeof t) && (i ? c[u] = p.extend(c[u], t) : c[u].data = p.extend(c[u].data, t)), a = c[u], i || (a.data || (a.data = {}), a = a.data), void 0 !== o && (a[p.camelCase(t)] = o), "string" == typeof t ? null == (r = a[t]) && (r = a[p.camelCase(t)]) : r = a, r
        }
    }

    function R(e, t, n) {
        if (p.acceptData(e)) {
            var o, i, r = e.nodeType,
                a = r ? p.cache : e,
                s = r ? e[p.expando] : p.expando;
            if (a[s]) {
                if (t && (o = n ? a[s] : a[s].data)) {
                    i = (t = p.isArray(t) ? t.concat(p.map(t, p.camelCase)) : t in o ? [t] : (t = p.camelCase(t)) in o ? [t] : t.split(" ")).length;
                    for (; i--;) delete o[t[i]];
                    if (n ? !P(o) : !p.isEmptyObject(o)) return
                }(n || (delete a[s].data, P(a[s]))) && (r ? p.cleanData([e], !0) : d.deleteExpando || a != a.window ? delete a[s] : a[s] = null)
            }
        }
    }
    p.extend({
        cache: {},
        noData: {
            "applet ": !0,
            "embed ": !0,
            "object ": "clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        },
        hasData: function(e) {
            return !!(e = e.nodeType ? p.cache[e[p.expando]] : e[p.expando]) && !P(e)
        },
        data: function(e, t, n) {
            return F(e, t, n)
        },
        removeData: function(e, t) {
            return R(e, t)
        },
        _data: function(e, t, n) {
            return F(e, t, n, !0)
        },
        _removeData: function(e, t) {
            return R(e, t, !0)
        }
    }), p.fn.extend({
        data: function(e, t) {
            var n, o, i, r = this[0],
                a = r && r.attributes;
            if (void 0 === e) {
                if (this.length && (i = p.data(r), 1 === r.nodeType && !p._data(r, "parsedAttrs"))) {
                    for (n = a.length; n--;) 0 === (o = a[n].name).indexOf("data-") && H(r, o = p.camelCase(o.slice(5)), i[o]);
                    p._data(r, "parsedAttrs", !0)
                }
                return i
            }
            return "object" == typeof e ? this.each(function() {
                p.data(this, e)
            }) : 1 < arguments.length ? this.each(function() {
                p.data(this, e, t)
            }) : r ? H(r, e, p.data(r, e)) : void 0
        },
        removeData: function(e) {
            return this.each(function() {
                p.removeData(this, e)
            })
        }
    }), p.extend({
        queue: function(e, t, n) {
            var o;
            return e ? (t = (t || "fx") + "queue", o = p._data(e, t), n && (!o || p.isArray(n) ? o = p._data(e, t, p.makeArray(n)) : o.push(n)), o || []) : void 0
        },
        dequeue: function(e, t) {
            t = t || "fx";
            var n = p.queue(e, t),
                o = n.length,
                i = n.shift(),
                r = p._queueHooks(e, t);
            "inprogress" === i && (i = n.shift(), o--), i && ("fx" === t && n.unshift("inprogress"), delete r.stop, i.call(e, function() {
                p.dequeue(e, t)
            }, r)), !o && r && r.empty.fire()
        },
        _queueHooks: function(e, t) {
            var n = t + "queueHooks";
            return p._data(e, n) || p._data(e, n, {
                empty: p.Callbacks("once memory").add(function() {
                    p._removeData(e, t + "queue"), p._removeData(e, n)
                })
            })
        }
    }), p.fn.extend({
        queue: function(e, t) {
            var n = 2;
            return "string" != typeof e && (t = e, e = "fx", n--), arguments.length < n ? p.queue(this[0], e) : void 0 === t ? this : this.each(function() {
                var n = p.queue(this, e, t);
                p._queueHooks(this, e), "fx" === e && "inprogress" !== n[0] && p.dequeue(this, e)
            })
        },
        dequeue: function(e) {
            return this.each(function() {
                p.dequeue(this, e)
            })
        },
        clearQueue: function(e) {
            return this.queue(e || "fx", [])
        },
        promise: function(e, t) {
            var n, o = 1,
                i = p.Deferred(),
                r = this,
                a = this.length,
                s = function() {
                    --o || i.resolveWith(r, [r])
                };
            for ("string" != typeof e && (t = e, e = void 0), e = e || "fx"; a--;)(n = p._data(r[a], e + "queueHooks")) && n.empty && (o++, n.empty.add(s));
            return s(), i.promise(t)
        }
    });
    var q = /[+-]?(?:\d*\.|)\d+(?:[eE][+-]?\d+|)/.source,
        W = ["Top", "Right", "Bottom", "Left"],
        z = function(e, t) {
            return e = t || e, "none" === p.css(e, "display") || !p.contains(e.ownerDocument, e)
        },
        Q = p.access = function(e, t, n, o, i, r, a) {
            var s = 0,
                l = e.length,
                c = null == n;
            if ("object" === p.type(n))
                for (s in i = !0, n) p.access(e, t, s, n[s], !0, r, a);
            else if (void 0 !== o && (i = !0, p.isFunction(o) || (a = !0), c && (a ? (t.call(e, o), t = null) : (c = t, t = function(e, t, n) {
                    return c.call(p(e), n)
                })), t))
                for (; s < l; s++) t(e[s], n, a ? o : o.call(e[s], s, t(e[s], n)));
            return i ? e : c ? t.call(e) : l ? t(e[0], n) : r
        },
        U = /^(?:checkbox|radio)$/i;
    ! function() {
        var e = T.createDocumentFragment(),
            t = T.createElement("div"),
            n = T.createElement("input");
        if (t.setAttribute("className", "t"), t.innerHTML = "  <link/><table></table><a href='/a'>a</a>", d.leadingWhitespace = 3 === t.firstChild.nodeType, d.tbody = !t.getElementsByTagName("tbody").length, d.htmlSerialize = !!t.getElementsByTagName("link").length, d.html5Clone = "<:nav></:nav>" !== T.createElement("nav").cloneNode(!0).outerHTML, n.type = "checkbox", n.checked = !0, e.appendChild(n), d.appendChecked = n.checked, t.innerHTML = "<textarea>x</textarea>", d.noCloneChecked = !!t.cloneNode(!0).lastChild.defaultValue, e.appendChild(t), t.innerHTML = "<input type='radio' checked='checked' name='t'/>", d.checkClone = t.cloneNode(!0).cloneNode(!0).lastChild.checked, d.noCloneEvent = !0, t.attachEvent && (t.attachEvent("onclick", function() {
                d.noCloneEvent = !1
            }), t.cloneNode(!0).click()), null == d.deleteExpando) {
            d.deleteExpando = !0;
            try {
                delete t.test
            } catch (e) {
                d.deleteExpando = !1
            }
        }
        e = t = n = null
    }(),
    function() {
        var t, n, o = T.createElement("div");
        for (t in {
                submit: !0,
                change: !0,
                focusin: !0
            }) n = "on" + t, (d[t + "Bubbles"] = n in e) || (o.setAttribute(n, "t"), d[t + "Bubbles"] = !1 === o.attributes[n].expando);
        o = null
    }();
    var V = /^(?:input|select|textarea)$/i,
        X = /^key/,
        J = /^(?:mouse|contextmenu)|click/,
        K = /^(?:focusinfocus|focusoutblur)$/,
        Y = /^([^.]*)(?:\.(.+)|)$/;

    function G() {
        return !0
    }

    function Z() {
        return !1
    }

    function ee() {
        try {
            return T.activeElement
        } catch (e) {}
    }

    function te(e) {
        var t = ne.split("|"),
            n = e.createDocumentFragment();
        if (n.createElement)
            for (; t.length;) n.createElement(t.pop());
        return n
    }
    p.event = {
        global: {},
        add: function(e, t, n, o, i) {
            var r, a, s, l, c, u, d, f, h, m, g, v = p._data(e);
            if (v) {
                for (n.handler && (n = (l = n).handler, i = l.selector), n.guid || (n.guid = p.guid++), (a = v.events) || (a = v.events = {}), (u = v.handle) || ((u = v.handle = function(e) {
                        return typeof p === I || e && p.event.triggered === e.type ? void 0 : p.event.dispatch.apply(u.elem, arguments)
                    }).elem = e), s = (t = (t || "").match(_) || [""]).length; s--;) h = g = (r = Y.exec(t[s]) || [])[1], m = (r[2] || "").split(".").sort(), h && (c = p.event.special[h] || {}, h = (i ? c.delegateType : c.bindType) || h, c = p.event.special[h] || {}, d = p.extend({
                    type: h,
                    origType: g,
                    data: o,
                    handler: n,
                    guid: n.guid,
                    selector: i,
                    needsContext: i && p.expr.match.needsContext.test(i),
                    namespace: m.join(".")
                }, l), (f = a[h]) || ((f = a[h] = []).delegateCount = 0, c.setup && !1 !== c.setup.call(e, o, m, u) || (e.addEventListener ? e.addEventListener(h, u, !1) : e.attachEvent && e.attachEvent("on" + h, u))), c.add && (c.add.call(e, d), d.handler.guid || (d.handler.guid = n.guid)), i ? f.splice(f.delegateCount++, 0, d) : f.push(d), p.event.global[h] = !0);
                e = null
            }
        },
        remove: function(e, t, n, o, i) {
            var r, a, s, l, c, u, d, f, h, m, g, v = p.hasData(e) && p._data(e);
            if (v && (u = v.events)) {
                for (c = (t = (t || "").match(_) || [""]).length; c--;)
                    if (h = g = (s = Y.exec(t[c]) || [])[1], m = (s[2] || "").split(".").sort(), h) {
                        for (d = p.event.special[h] || {}, f = u[h = (o ? d.delegateType : d.bindType) || h] || [], s = s[2] && new RegExp("(^|\\.)" + m.join("\\.(?:.*\\.|)") + "(\\.|$)"), l = r = f.length; r--;) a = f[r], !i && g !== a.origType || n && n.guid !== a.guid || s && !s.test(a.namespace) || o && o !== a.selector && ("**" !== o || !a.selector) || (f.splice(r, 1), a.selector && f.delegateCount--, d.remove && d.remove.call(e, a));
                        l && !f.length && (d.teardown && !1 !== d.teardown.call(e, m, v.handle) || p.removeEvent(e, h, v.handle), delete u[h])
                    } else
                        for (h in u) p.event.remove(e, h + t[c], n, o, !0);
                p.isEmptyObject(u) && (delete v.handle, p._removeData(e, "events"))
            }
        },
        trigger: function(t, n, o, i) {
            var r, a, s, l, u, d, f, h = [o || T],
                m = c.call(t, "type") ? t.type : t,
                g = c.call(t, "namespace") ? t.namespace.split(".") : [];
            if (s = d = o = o || T, 3 !== o.nodeType && 8 !== o.nodeType && !K.test(m + p.event.triggered) && (0 <= m.indexOf(".") && (m = (g = m.split(".")).shift(), g.sort()), a = m.indexOf(":") < 0 && "on" + m, (t = t[p.expando] ? t : new p.Event(m, "object" == typeof t && t)).isTrigger = i ? 2 : 3, t.namespace = g.join("."), t.namespace_re = t.namespace ? new RegExp("(^|\\.)" + g.join("\\.(?:.*\\.|)") + "(\\.|$)") : null, t.result = void 0, t.target || (t.target = o), n = null == n ? [t] : p.makeArray(n, [t]), u = p.event.special[m] || {}, i || !u.trigger || !1 !== u.trigger.apply(o, n))) {
                if (!i && !u.noBubble && !p.isWindow(o)) {
                    for (l = u.delegateType || m, K.test(l + m) || (s = s.parentNode); s; s = s.parentNode) h.push(s), d = s;
                    d === (o.ownerDocument || T) && h.push(d.defaultView || d.parentWindow || e)
                }
                for (f = 0;
                    (s = h[f++]) && !t.isPropagationStopped();) t.type = 1 < f ? l : u.bindType || m, (r = (p._data(s, "events") || {})[t.type] && p._data(s, "handle")) && r.apply(s, n), (r = a && s[a]) && r.apply && p.acceptData(s) && (t.result = r.apply(s, n), !1 === t.result && t.preventDefault());
                if (t.type = m, !i && !t.isDefaultPrevented() && (!u._default || !1 === u._default.apply(h.pop(), n)) && p.acceptData(o) && a && o[m] && !p.isWindow(o)) {
                    (d = o[a]) && (o[a] = null), p.event.triggered = m;
                    try {
                        o[m]()
                    } catch (t) {}
                    p.event.triggered = void 0, d && (o[a] = d)
                }
                return t.result
            }
        },
        dispatch: function(e) {
            e = p.event.fix(e);
            var t, n, i, r, a, s = [],
                l = o.call(arguments),
                c = (p._data(this, "events") || {})[e.type] || [],
                u = p.event.special[e.type] || {};
            if ((l[0] = e).delegateTarget = this, !u.preDispatch || !1 !== u.preDispatch.call(this, e)) {
                for (s = p.event.handlers.call(this, e, c), t = 0;
                    (r = s[t++]) && !e.isPropagationStopped();)
                    for (e.currentTarget = r.elem, a = 0;
                        (i = r.handlers[a++]) && !e.isImmediatePropagationStopped();)(!e.namespace_re || e.namespace_re.test(i.namespace)) && (e.handleObj = i, e.data = i.data, void 0 !== (n = ((p.event.special[i.origType] || {}).handle || i.handler).apply(r.elem, l)) && !1 === (e.result = n) && (e.preventDefault(), e.stopPropagation()));
                return u.postDispatch && u.postDispatch.call(this, e), e.result
            }
        },
        handlers: function(e, t) {
            var n, o, i, r, a = [],
                s = t.delegateCount,
                l = e.target;
            if (s && l.nodeType && (!e.button || "click" !== e.type))
                for (; l != this; l = l.parentNode || this)
                    if (1 === l.nodeType && (!0 !== l.disabled || "click" !== e.type)) {
                        for (i = [], r = 0; r < s; r++) void 0 === i[n = (o = t[r]).selector + " "] && (i[n] = o.needsContext ? 0 <= p(n, this).index(l) : p.find(n, this, null, [l]).length), i[n] && i.push(o);
                        i.length && a.push({
                            elem: l,
                            handlers: i
                        })
                    } return s < t.length && a.push({
                elem: this,
                handlers: t.slice(s)
            }), a
        },
        fix: function(e) {
            if (e[p.expando]) return e;
            var t, n, o, i = e.type,
                r = e,
                a = this.fixHooks[i];
            for (a || (this.fixHooks[i] = a = J.test(i) ? this.mouseHooks : X.test(i) ? this.keyHooks : {}), o = a.props ? this.props.concat(a.props) : this.props, e = new p.Event(r), t = o.length; t--;) e[n = o[t]] = r[n];
            return e.target || (e.target = r.srcElement || T), 3 === e.target.nodeType && (e.target = e.target.parentNode), e.metaKey = !!e.metaKey, a.filter ? a.filter(e, r) : e
        },
        props: "altKey bubbles cancelable ctrlKey currentTarget eventPhase metaKey relatedTarget shiftKey target timeStamp view which".split(" "),
        fixHooks: {},
        keyHooks: {
            props: "char charCode key keyCode".split(" "),
            filter: function(e, t) {
                return null == e.which && (e.which = null != t.charCode ? t.charCode : t.keyCode), e
            }
        },
        mouseHooks: {
            props: "button buttons clientX clientY fromElement offsetX offsetY pageX pageY screenX screenY toElement".split(" "),
            filter: function(e, t) {
                var n, o, i, r = t.button,
                    a = t.fromElement;
                return null == e.pageX && null != t.clientX && (i = (o = e.target.ownerDocument || T).documentElement, n = o.body, e.pageX = t.clientX + (i && i.scrollLeft || n && n.scrollLeft || 0) - (i && i.clientLeft || n && n.clientLeft || 0), e.pageY = t.clientY + (i && i.scrollTop || n && n.scrollTop || 0) - (i && i.clientTop || n && n.clientTop || 0)), !e.relatedTarget && a && (e.relatedTarget = a === e.target ? t.toElement : a), e.which || void 0 === r || (e.which = 1 & r ? 1 : 2 & r ? 3 : 4 & r ? 2 : 0), e
            }
        },
        special: {
            load: {
                noBubble: !0
            },
            focus: {
                trigger: function() {
                    if (this !== ee() && this.focus) try {
                        return this.focus(), !1
                    } catch (e) {}
                },
                delegateType: "focusin"
            },
            blur: {
                trigger: function() {
                    return this === ee() && this.blur ? (this.blur(), !1) : void 0
                },
                delegateType: "focusout"
            },
            click: {
                trigger: function() {
                    return p.nodeName(this, "input") && "checkbox" === this.type && this.click ? (this.click(), !1) : void 0
                },
                _default: function(e) {
                    return p.nodeName(e.target, "a")
                }
            },
            beforeunload: {
                postDispatch: function(e) {
                    void 0 !== e.result && (e.originalEvent.returnValue = e.result)
                }
            }
        },
        simulate: function(e, t, n, o) {
            var i = p.extend(new p.Event, n, {
                type: e,
                isSimulated: !0,
                originalEvent: {}
            });
            o ? p.event.trigger(i, null, t) : p.event.dispatch.call(t, i), i.isDefaultPrevented() && n.preventDefault()
        }
    }, p.removeEvent = T.removeEventListener ? function(e, t, n) {
        e.removeEventListener && e.removeEventListener(t, n, !1)
    } : function(e, t, n) {
        var o = "on" + t;
        e.detachEvent && (typeof e[o] === I && (e[o] = null), e.detachEvent(o, n))
    }, p.Event = function(e, t) {
        return this instanceof p.Event ? (e && e.type ? (this.originalEvent = e, this.type = e.type, this.isDefaultPrevented = e.defaultPrevented || void 0 === e.defaultPrevented && (!1 === e.returnValue || e.getPreventDefault && e.getPreventDefault()) ? G : Z) : this.type = e, t && p.extend(this, t), this.timeStamp = e && e.timeStamp || p.now(), void(this[p.expando] = !0)) : new p.Event(e, t)
    }, p.Event.prototype = {
        isDefaultPrevented: Z,
        isPropagationStopped: Z,
        isImmediatePropagationStopped: Z,
        preventDefault: function() {
            var e = this.originalEvent;
            this.isDefaultPrevented = G, e && (e.preventDefault ? e.preventDefault() : e.returnValue = !1)
        },
        stopPropagation: function() {
            var e = this.originalEvent;
            this.isPropagationStopped = G, e && (e.stopPropagation && e.stopPropagation(), e.cancelBubble = !0)
        },
        stopImmediatePropagation: function() {
            this.isImmediatePropagationStopped = G, this.stopPropagation()
        }
    }, p.each({
        mouseenter: "mouseover",
        mouseleave: "mouseout"
    }, function(e, t) {
        p.event.special[e] = {
            delegateType: t,
            bindType: t,
            handle: function(e) {
                var n, o = e.relatedTarget,
                    i = e.handleObj;
                return (!o || o !== this && !p.contains(this, o)) && (e.type = i.origType, n = i.handler.apply(this, arguments), e.type = t), n
            }
        }
    }), d.submitBubbles || (p.event.special.submit = {
        setup: function() {
            return !p.nodeName(this, "form") && void p.event.add(this, "click._submit keypress._submit", function(e) {
                var t = e.target,
                    n = p.nodeName(t, "input") || p.nodeName(t, "button") ? t.form : void 0;
                n && !p._data(n, "submitBubbles") && (p.event.add(n, "submit._submit", function(e) {
                    e._submit_bubble = !0
                }), p._data(n, "submitBubbles", !0))
            })
        },
        postDispatch: function(e) {
            e._submit_bubble && (delete e._submit_bubble, this.parentNode && !e.isTrigger && p.event.simulate("submit", this.parentNode, e, !0))
        },
        teardown: function() {
            return !p.nodeName(this, "form") && void p.event.remove(this, "._submit")
        }
    }), d.changeBubbles || (p.event.special.change = {
        setup: function() {
            return V.test(this.nodeName) ? (("checkbox" === this.type || "radio" === this.type) && (p.event.add(this, "propertychange._change", function(e) {
                "checked" === e.originalEvent.propertyName && (this._just_changed = !0)
            }), p.event.add(this, "click._change", function(e) {
                this._just_changed && !e.isTrigger && (this._just_changed = !1), p.event.simulate("change", this, e, !0)
            })), !1) : void p.event.add(this, "beforeactivate._change", function(e) {
                var t = e.target;
                V.test(t.nodeName) && !p._data(t, "changeBubbles") && (p.event.add(t, "change._change", function(e) {
                    !this.parentNode || e.isSimulated || e.isTrigger || p.event.simulate("change", this.parentNode, e, !0)
                }), p._data(t, "changeBubbles", !0))
            })
        },
        handle: function(e) {
            var t = e.target;
            return this !== t || e.isSimulated || e.isTrigger || "radio" !== t.type && "checkbox" !== t.type ? e.handleObj.handler.apply(this, arguments) : void 0
        },
        teardown: function() {
            return p.event.remove(this, "._change"), !V.test(this.nodeName)
        }
    }), d.focusinBubbles || p.each({
        focus: "focusin",
        blur: "focusout"
    }, function(e, t) {
        var n = function(e) {
            p.event.simulate(t, e.target, p.event.fix(e), !0)
        };
        p.event.special[t] = {
            setup: function() {
                var o = this.ownerDocument || this,
                    i = p._data(o, t);
                i || o.addEventListener(e, n, !0), p._data(o, t, (i || 0) + 1)
            },
            teardown: function() {
                var o = this.ownerDocument || this,
                    i = p._data(o, t) - 1;
                i ? p._data(o, t, i) : (o.removeEventListener(e, n, !0), p._removeData(o, t))
            }
        }
    }), p.fn.extend({
        on: function(e, t, n, o, i) {
            var r, a;
            if ("object" == typeof e) {
                for (r in "string" != typeof t && (n = n || t, t = void 0), e) this.on(r, t, n, e[r], i);
                return this
            }
            if (null == n && null == o ? (o = t, n = t = void 0) : null == o && ("string" == typeof t ? (o = n, n = void 0) : (o = n, n = t, t = void 0)), !1 === o) o = Z;
            else if (!o) return this;
            return 1 === i && (a = o, (o = function(e) {
                return p().off(e), a.apply(this, arguments)
            }).guid = a.guid || (a.guid = p.guid++)), this.each(function() {
                p.event.add(this, e, o, n, t)
            })
        },
        one: function(e, t, n, o) {
            return this.on(e, t, n, o, 1)
        },
        off: function(e, t, n) {
            var o, i;
            if (e && e.preventDefault && e.handleObj) return o = e.handleObj, p(e.delegateTarget).off(o.namespace ? o.origType + "." + o.namespace : o.origType, o.selector, o.handler), this;
            if ("object" == typeof e) {
                for (i in e) this.off(i, t, e[i]);
                return this
            }
            return (!1 === t || "function" == typeof t) && (n = t, t = void 0), !1 === n && (n = Z), this.each(function() {
                p.event.remove(this, e, n, t)
            })
        },
        trigger: function(e, t) {
            return this.each(function() {
                p.event.trigger(e, t, this)
            })
        },
        triggerHandler: function(e, t) {
            var n = this[0];
            return n ? p.event.trigger(e, t, n, !0) : void 0
        }
    });
    var ne = "abbr|article|aside|audio|bdi|canvas|data|datalist|details|figcaption|figure|footer|header|hgroup|mark|meter|nav|output|progress|section|summary|time|video",
        oe = / jQuery\d+="(?:null|\d+)"/g,
        ie = new RegExp("<(?:" + ne + ")[\\s/>]", "i"),
        re = /^\s+/,
        ae = /<(?!area|br|col|embed|hr|img|input|link|meta|param)(([\w:]+)[^>]*)\/>/gi,
        se = /<([\w:]+)/,
        le = /<tbody/i,
        ce = /<|&#?\w+;/,
        ue = /<(?:script|style|link)/i,
        de = /checked\s*(?:[^=]|=\s*.checked.)/i,
        pe = /^$|\/(?:java|ecma)script/i,
        fe = /^true\/(.*)/,
        he = /^\s*<!(?:\[CDATA\[|--)|(?:\]\]|--)>\s*$/g,
        me = {
            option: [1, "<select multiple='multiple'>", "</select>"],
            legend: [1, "<fieldset>", "</fieldset>"],
            area: [1, "<map>", "</map>"],
            param: [1, "<object>", "</object>"],
            thead: [1, "<table>", "</table>"],
            tr: [2, "<table><tbody>", "</tbody></table>"],
            col: [2, "<table><tbody></tbody><colgroup>", "</colgroup></table>"],
            td: [3, "<table><tbody><tr>", "</tr></tbody></table>"],
            _default: d.htmlSerialize ? [0, "", ""] : [1, "X<div>", "</div>"]
        },
        ge = te(T).appendChild(T.createElement("div"));

    function ve(e, t) {
        var n, o, i = 0,
            r = typeof e.getElementsByTagName !== I ? e.getElementsByTagName(t || "*") : typeof e.querySelectorAll !== I ? e.querySelectorAll(t || "*") : void 0;
        if (!r)
            for (r = [], n = e.childNodes || e; null != (o = n[i]); i++) !t || p.nodeName(o, t) ? r.push(o) : p.merge(r, ve(o, t));
        return void 0 === t || t && p.nodeName(e, t) ? p.merge([e], r) : r
    }

    function ye(e) {
        U.test(e.type) && (e.defaultChecked = e.checked)
    }

    function be(e, t) {
        return p.nodeName(e, "table") && p.nodeName(11 !== t.nodeType ? t : t.firstChild, "tr") ? e.getElementsByTagName("tbody")[0] || e.appendChild(e.ownerDocument.createElement("tbody")) : e
    }

    function xe(e) {
        return e.type = (null !== p.find.attr(e, "type")) + "/" + e.type, e
    }

    function we(e) {
        var t = fe.exec(e.type);
        return t ? e.type = t[1] : e.removeAttribute("type"), e
    }

    function Ce(e, t) {
        for (var n, o = 0; null != (n = e[o]); o++) p._data(n, "globalEval", !t || p._data(t[o], "globalEval"))
    }

    function ke(e, t) {
        if (1 === t.nodeType && p.hasData(e)) {
            var n, o, i, r = p._data(e),
                a = p._data(t, r),
                s = r.events;
            if (s)
                for (n in delete a.handle, a.events = {}, s)
                    for (o = 0, i = s[n].length; o < i; o++) p.event.add(t, n, s[n][o]);
            a.data && (a.data = p.extend({}, a.data))
        }
    }

    function Te(e, t) {
        var n, o, i;
        if (1 === t.nodeType) {
            if (n = t.nodeName.toLowerCase(), !d.noCloneEvent && t[p.expando]) {
                for (o in (i = p._data(t)).events) p.removeEvent(t, o, i.handle);
                t.removeAttribute(p.expando)
            }
            "script" === n && t.text !== e.text ? (xe(t).text = e.text, we(t)) : "object" === n ? (t.parentNode && (t.outerHTML = e.outerHTML), d.html5Clone && e.innerHTML && !p.trim(t.innerHTML) && (t.innerHTML = e.innerHTML)) : "input" === n && U.test(e.type) ? (t.defaultChecked = t.checked = e.checked, t.value !== e.value && (t.value = e.value)) : "option" === n ? t.defaultSelected = t.selected = e.defaultSelected : ("input" === n || "textarea" === n) && (t.defaultValue = e.defaultValue)
        }
    }
    me.optgroup = me.option, me.tbody = me.tfoot = me.colgroup = me.caption = me.thead, me.th = me.td, p.extend({
        clone: function(e, t, n) {
            var o, i, r, a, s, l = p.contains(e.ownerDocument, e);
            if (d.html5Clone || p.isXMLDoc(e) || !ie.test("<" + e.nodeName + ">") ? r = e.cloneNode(!0) : (ge.innerHTML = e.outerHTML, ge.removeChild(r = ge.firstChild)), !(d.noCloneEvent && d.noCloneChecked || 1 !== e.nodeType && 11 !== e.nodeType || p.isXMLDoc(e)))
                for (o = ve(r), s = ve(e), a = 0; null != (i = s[a]); ++a) o[a] && Te(i, o[a]);
            if (t)
                if (n)
                    for (s = s || ve(e), o = o || ve(r), a = 0; null != (i = s[a]); a++) ke(i, o[a]);
                else ke(e, r);
            return 0 < (o = ve(r, "script")).length && Ce(o, !l && ve(e, "script")), o = s = i = null, r
        },
        buildFragment: function(e, t, n, o) {
            for (var i, r, a, s, l, c, u, f = e.length, h = te(t), m = [], g = 0; g < f; g++)
                if ((r = e[g]) || 0 === r)
                    if ("object" === p.type(r)) p.merge(m, r.nodeType ? [r] : r);
                    else if (ce.test(r)) {
                for (s = s || h.appendChild(t.createElement("div")), l = (se.exec(r) || ["", ""])[1].toLowerCase(), u = me[l] || me._default, s.innerHTML = u[1] + r.replace(ae, "<$1></$2>") + u[2], i = u[0]; i--;) s = s.lastChild;
                if (!d.leadingWhitespace && re.test(r) && m.push(t.createTextNode(re.exec(r)[0])), !d.tbody)
                    for (i = (r = "table" !== l || le.test(r) ? "<table>" !== u[1] || le.test(r) ? 0 : s : s.firstChild) && r.childNodes.length; i--;) p.nodeName(c = r.childNodes[i], "tbody") && !c.childNodes.length && r.removeChild(c);
                for (p.merge(m, s.childNodes), s.textContent = ""; s.firstChild;) s.removeChild(s.firstChild);
                s = h.lastChild
            } else m.push(t.createTextNode(r));
            for (s && h.removeChild(s), d.appendChecked || p.grep(ve(m, "input"), ye), g = 0; r = m[g++];)
                if ((!o || -1 === p.inArray(r, o)) && (a = p.contains(r.ownerDocument, r), s = ve(h.appendChild(r), "script"), a && Ce(s), n))
                    for (i = 0; r = s[i++];) pe.test(r.type || "") && n.push(r);
            return s = null, h
        },
        cleanData: function(e, t) {
            for (var o, i, r, a, s = 0, l = p.expando, c = p.cache, u = d.deleteExpando, f = p.event.special; null != (o = e[s]); s++)
                if ((t || p.acceptData(o)) && (a = (r = o[l]) && c[r])) {
                    if (a.events)
                        for (i in a.events) f[i] ? p.event.remove(o, i) : p.removeEvent(o, i, a.handle);
                    c[r] && (delete c[r], u ? delete o[l] : typeof o.removeAttribute !== I ? o.removeAttribute(l) : o[l] = null, n.push(r))
                }
        }
    }), p.fn.extend({
        text: function(e) {
            return Q(this, function(e) {
                return void 0 === e ? p.text(this) : this.empty().append((this[0] && this[0].ownerDocument || T).createTextNode(e))
            }, null, e, arguments.length)
        },
        append: function() {
            return this.domManip(arguments, function(e) {
                1 !== this.nodeType && 11 !== this.nodeType && 9 !== this.nodeType || be(this, e).appendChild(e)
            })
        },
        prepend: function() {
            return this.domManip(arguments, function(e) {
                if (1 === this.nodeType || 11 === this.nodeType || 9 === this.nodeType) {
                    var t = be(this, e);
                    t.insertBefore(e, t.firstChild)
                }
            })
        },
        before: function() {
            return this.domManip(arguments, function(e) {
                this.parentNode && this.parentNode.insertBefore(e, this)
            })
        },
        after: function() {
            return this.domManip(arguments, function(e) {
                this.parentNode && this.parentNode.insertBefore(e, this.nextSibling)
            })
        },
        remove: function(e, t) {
            for (var n, o = e ? p.filter(e, this) : this, i = 0; null != (n = o[i]); i++) t || 1 !== n.nodeType || p.cleanData(ve(n)), n.parentNode && (t && p.contains(n.ownerDocument, n) && Ce(ve(n, "script")), n.parentNode.removeChild(n));
            return this
        },
        empty: function() {
            for (var e, t = 0; null != (e = this[t]); t++) {
                for (1 === e.nodeType && p.cleanData(ve(e, !1)); e.firstChild;) e.removeChild(e.firstChild);
                e.options && p.nodeName(e, "select") && (e.options.length = 0)
            }
            return this
        },
        clone: function(e, t) {
            return e = null != e && e, t = null == t ? e : t, this.map(function() {
                return p.clone(this, e, t)
            })
        },
        html: function(e) {
            return Q(this, function(e) {
                var t = this[0] || {},
                    n = 0,
                    o = this.length;
                if (void 0 === e) return 1 === t.nodeType ? t.innerHTML.replace(oe, "") : void 0;
                if (!("string" != typeof e || ue.test(e) || !d.htmlSerialize && ie.test(e) || !d.leadingWhitespace && re.test(e) || me[(se.exec(e) || ["", ""])[1].toLowerCase()])) {
                    e = e.replace(ae, "<$1></$2>");
                    try {
                        for (; n < o; n++) 1 === (t = this[n] || {}).nodeType && (p.cleanData(ve(t, !1)), t.innerHTML = e);
                        t = 0
                    } catch (e) {}
                }
                t && this.empty().append(e)
            }, null, e, arguments.length)
        },
        replaceWith: function() {
            var e = arguments[0];
            return this.domManip(arguments, function(t) {
                e = this.parentNode, p.cleanData(ve(this)), e && e.replaceChild(t, this)
            }), e && (e.length || e.nodeType) ? this : this.remove()
        },
        detach: function(e) {
            return this.remove(e, !0)
        },
        domManip: function(e, t) {
            e = i.apply([], e);
            var n, o, r, a, s, l, c = 0,
                u = this.length,
                f = this,
                h = u - 1,
                m = e[0],
                g = p.isFunction(m);
            if (g || 1 < u && "string" == typeof m && !d.checkClone && de.test(m)) return this.each(function(n) {
                var o = f.eq(n);
                g && (e[0] = m.call(this, n, o.html())), o.domManip(e, t)
            });
            if (u && (n = (l = p.buildFragment(e, this[0].ownerDocument, !1, this)).firstChild, 1 === l.childNodes.length && (l = n), n)) {
                for (r = (a = p.map(ve(l, "script"), xe)).length; c < u; c++) o = l, c !== h && (o = p.clone(o, !0, !0), r && p.merge(a, ve(o, "script"))), t.call(this[c], o, c);
                if (r)
                    for (s = a[a.length - 1].ownerDocument, p.map(a, we), c = 0; c < r; c++) o = a[c], pe.test(o.type || "") && !p._data(o, "globalEval") && p.contains(s, o) && (o.src ? p._evalUrl && p._evalUrl(o.src) : p.globalEval((o.text || o.textContent || o.innerHTML || "").replace(he, "")));
                l = n = null
            }
            return this
        }
    }), p.each({
        appendTo: "append",
        prependTo: "prepend",
        insertBefore: "before",
        insertAfter: "after",
        replaceAll: "replaceWith"
    }, function(e, t) {
        p.fn[e] = function(e) {
            for (var n, o = 0, i = [], a = p(e), s = a.length - 1; o <= s; o++) n = o === s ? this : this.clone(!0), p(a[o])[t](n), r.apply(i, n.get());
            return this.pushStack(i)
        }
    });
    var $e, Ee, Se, Ne, Be = {};

    function _e(t, n) {
        var o = p(n.createElement(t)).appendTo(n.body),
            i = e.getDefaultComputedStyle ? e.getDefaultComputedStyle(o[0]).display : p.css(o[0], "display");
        return o.detach(), i
    }

    function Ae(e) {
        var t = T,
            n = Be[e];
        return n || ("none" !== (n = _e(e, t)) && n || ((t = (($e = ($e || p("<iframe frameborder='0' width='0' height='0'/>")).appendTo(t.documentElement))[0].contentWindow || $e[0].contentDocument).document).write(), t.close(), n = _e(e, t), $e.detach()), Be[e] = n), n
    }(Ne = T.createElement("div")).innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", (Ee = Ne.getElementsByTagName("a")[0]).style.cssText = "float:left;opacity:.5", d.opacity = /^0.5/.test(Ee.style.opacity), d.cssFloat = !!Ee.style.cssFloat, Ne.style.backgroundClip = "content-box", Ne.cloneNode(!0).style.backgroundClip = "", d.clearCloneStyle = "content-box" === Ne.style.backgroundClip, Ee = Ne = null, d.shrinkWrapBlocks = function() {
        var e, t, n;
        if (null == Se) {
            if (!(e = T.getElementsByTagName("body")[0])) return;
            t = T.createElement("div"), n = T.createElement("div"), e.appendChild(t).appendChild(n), Se = !1, typeof n.style.zoom !== I && (n.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;padding:0;margin:0;border:0;width:1px;padding:1px;zoom:1", n.innerHTML = "<div></div>", n.firstChild.style.width = "5px", Se = 3 !== n.offsetWidth), e.removeChild(t), e = t = n = null
        }
        return Se
    };
    var De, je, Oe = /^margin/,
        Ie = new RegExp("^(" + q + ")(?!px)[a-z%]+$", "i"),
        Le = /^(top|right|bottom|left)$/;

    function Me(e, t) {
        return {
            get: function() {
                var n = e();
                if (null != n) return n ? void delete this.get : (this.get = t).apply(this, arguments)
            }
        }
    }
    e.getComputedStyle ? (De = function(e) {
            return e.ownerDocument.defaultView.getComputedStyle(e, null)
        }, je = function(e, t, n) {
            var o, i, r, a, s = e.style;
            return a = (n = n || De(e)) ? n.getPropertyValue(t) || n[t] : void 0, n && ("" !== a || p.contains(e.ownerDocument, e) || (a = p.style(e, t)), Ie.test(a) && Oe.test(t) && (o = s.width, i = s.minWidth, r = s.maxWidth, s.minWidth = s.maxWidth = s.width = a, a = n.width, s.width = o, s.minWidth = i, s.maxWidth = r)), void 0 === a ? a : a + ""
        }) : T.documentElement.currentStyle && (De = function(e) {
            return e.currentStyle
        }, je = function(e, t, n) {
            var o, i, r, a, s = e.style;
            return null == (a = (n = n || De(e)) ? n[t] : void 0) && s && s[t] && (a = s[t]), Ie.test(a) && !Le.test(t) && (o = s.left, (r = (i = e.runtimeStyle) && i.left) && (i.left = e.currentStyle.left), s.left = "fontSize" === t ? "1em" : a, a = s.pixelLeft + "px", s.left = o, r && (i.left = r)), void 0 === a ? a : a + "" || "auto"
        }),
        function() {
            var t, n, o, i, r, a, s = T.createElement("div"),
                l = "border:0;width:0;height:0;position:absolute;top:0;left:-9999px";

            function c() {
                var t, n, s = T.getElementsByTagName("body")[0];
                s && (t = T.createElement("div"), n = T.createElement("div"), t.style.cssText = l, s.appendChild(t).appendChild(n), n.style.cssText = "-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;position:absolute;display:block;padding:1px;border:1px;width:4px;margin-top:1%;top:1%", p.swap(s, null != s.style.zoom ? {
                    zoom: 1
                } : {}, function() {
                    o = 4 === n.offsetWidth
                }), a = !(r = !(i = !0)), e.getComputedStyle && (r = "1%" !== (e.getComputedStyle(n, null) || {}).top, i = "4px" === (e.getComputedStyle(n, null) || {
                    width: "4px"
                }).width), s.removeChild(t), n = s = null)
            }
            s.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", (t = s.getElementsByTagName("a")[0]).style.cssText = "float:left;opacity:.5", d.opacity = /^0.5/.test(t.style.opacity), d.cssFloat = !!t.style.cssFloat, s.style.backgroundClip = "content-box", s.cloneNode(!0).style.backgroundClip = "", d.clearCloneStyle = "content-box" === s.style.backgroundClip, t = s = null, p.extend(d, {
                reliableHiddenOffsets: function() {
                    if (null != n) return n;
                    var e, t, o, i = T.createElement("div"),
                        r = T.getElementsByTagName("body")[0];
                    return r ? (i.setAttribute("className", "t"), i.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", (e = T.createElement("div")).style.cssText = l, r.appendChild(e).appendChild(i), i.innerHTML = "<table><tr><td></td><td>t</td></tr></table>", (t = i.getElementsByTagName("td"))[0].style.cssText = "padding:0;margin:0;border:0;display:none", o = 0 === t[0].offsetHeight, t[0].style.display = "", t[1].style.display = "none", n = o && 0 === t[0].offsetHeight, r.removeChild(e), i = r = null, n) : void 0
                },
                boxSizing: function() {
                    return null == o && c(), o
                },
                boxSizingReliable: function() {
                    return null == i && c(), i
                },
                pixelPosition: function() {
                    return null == r && c(), r
                },
                reliableMarginRight: function() {
                    var t, n, o, i;
                    if (null == a && e.getComputedStyle) {
                        if (!(t = T.getElementsByTagName("body")[0])) return;
                        n = T.createElement("div"), o = T.createElement("div"), n.style.cssText = l, t.appendChild(n).appendChild(o), (i = o.appendChild(T.createElement("div"))).style.cssText = o.style.cssText = "-webkit-box-sizing:content-box;-moz-box-sizing:content-box;box-sizing:content-box;display:block;padding:0;margin:0;border:0", i.style.marginRight = i.style.width = "0", o.style.width = "1px", a = !parseFloat((e.getComputedStyle(i, null) || {}).marginRight), t.removeChild(n)
                    }
                    return a
                }
            })
        }(), p.swap = function(e, t, n, o) {
            var i, r, a = {};
            for (r in t) a[r] = e.style[r], e.style[r] = t[r];
            for (r in i = n.apply(e, o || []), t) e.style[r] = a[r];
            return i
        };
    var He = /alpha\([^)]*\)/i,
        Pe = /opacity\s*=\s*([^)]*)/,
        Fe = /^(none|table(?!-c[ea]).+)/,
        Re = new RegExp("^(" + q + ")(.*)$", "i"),
        qe = new RegExp("^([+-])=(" + q + ")", "i"),
        We = {
            position: "absolute",
            visibility: "hidden",
            display: "block"
        },
        ze = {
            letterSpacing: 0,
            fontWeight: 400
        },
        Qe = ["Webkit", "O", "Moz", "ms"];

    function Ue(e, t) {
        if (t in e) return t;
        for (var n = t.charAt(0).toUpperCase() + t.slice(1), o = t, i = Qe.length; i--;)
            if ((t = Qe[i] + n) in e) return t;
        return o
    }

    function Ve(e, t) {
        for (var n, o, i, r = [], a = 0, s = e.length; a < s; a++)(o = e[a]).style && (r[a] = p._data(o, "olddisplay"), n = o.style.display, t ? (r[a] || "none" !== n || (o.style.display = ""), "" === o.style.display && z(o) && (r[a] = p._data(o, "olddisplay", Ae(o.nodeName)))) : r[a] || (i = z(o), (n && "none" !== n || !i) && p._data(o, "olddisplay", i ? n : p.css(o, "display"))));
        for (a = 0; a < s; a++)(o = e[a]).style && (t && "none" !== o.style.display && "" !== o.style.display || (o.style.display = t ? r[a] || "" : "none"));
        return e
    }

    function Xe(e, t, n) {
        var o = Re.exec(t);
        return o ? Math.max(0, o[1] - (n || 0)) + (o[2] || "px") : t
    }

    function Je(e, t, n, o, i) {
        for (var r = n === (o ? "border" : "content") ? 4 : "width" === t ? 1 : 0, a = 0; r < 4; r += 2) "margin" === n && (a += p.css(e, n + W[r], !0, i)), o ? ("content" === n && (a -= p.css(e, "padding" + W[r], !0, i)), "margin" !== n && (a -= p.css(e, "border" + W[r] + "Width", !0, i))) : (a += p.css(e, "padding" + W[r], !0, i), "padding" !== n && (a += p.css(e, "border" + W[r] + "Width", !0, i)));
        return a
    }

    function Ke(e, t, n) {
        var o = !0,
            i = "width" === t ? e.offsetWidth : e.offsetHeight,
            r = De(e),
            a = d.boxSizing() && "border-box" === p.css(e, "boxSizing", !1, r);
        if (i <= 0 || null == i) {
            if (((i = je(e, t, r)) < 0 || null == i) && (i = e.style[t]), Ie.test(i)) return i;
            o = a && (d.boxSizingReliable() || i === e.style[t]), i = parseFloat(i) || 0
        }
        return i + Je(e, t, n || (a ? "border" : "content"), o, r) + "px"
    }

    function Ye(e, t, n, o, i) {
        return new Ye.prototype.init(e, t, n, o, i)
    }
    p.extend({
        cssHooks: {
            opacity: {
                get: function(e, t) {
                    if (t) {
                        var n = je(e, "opacity");
                        return "" === n ? "1" : n
                    }
                }
            }
        },
        cssNumber: {
            columnCount: !0,
            fillOpacity: !0,
            fontWeight: !0,
            lineHeight: !0,
            opacity: !0,
            order: !0,
            orphans: !0,
            widows: !0,
            zIndex: !0,
            zoom: !0
        },
        cssProps: {
            float: d.cssFloat ? "cssFloat" : "styleFloat"
        },
        style: function(e, t, n, o) {
            if (e && 3 !== e.nodeType && 8 !== e.nodeType && e.style) {
                var i, r, a, s = p.camelCase(t),
                    l = e.style;
                if (t = p.cssProps[s] || (p.cssProps[s] = Ue(l, s)), a = p.cssHooks[t] || p.cssHooks[s], void 0 === n) return a && "get" in a && void 0 !== (i = a.get(e, !1, o)) ? i : l[t];
                if ("string" == (r = typeof n) && (i = qe.exec(n)) && (n = (i[1] + 1) * i[2] + parseFloat(p.css(e, t)), r = "number"), null != n && n == n && ("number" !== r || p.cssNumber[s] || (n += "px"), d.clearCloneStyle || "" !== n || 0 !== t.indexOf("background") || (l[t] = "inherit"), !(a && "set" in a && void 0 === (n = a.set(e, n, o))))) try {
                    l[t] = "", l[t] = n
                } catch (e) {}
            }
        },
        css: function(e, t, n, o) {
            var i, r, a, s = p.camelCase(t);
            return t = p.cssProps[s] || (p.cssProps[s] = Ue(e.style, s)), (a = p.cssHooks[t] || p.cssHooks[s]) && "get" in a && (r = a.get(e, !0, n)), void 0 === r && (r = je(e, t, o)), "normal" === r && t in ze && (r = ze[t]), "" === n || n ? (i = parseFloat(r), !0 === n || p.isNumeric(i) ? i || 0 : r) : r
        }
    }), p.each(["height", "width"], function(e, t) {
        p.cssHooks[t] = {
            get: function(e, n, o) {
                return n ? 0 === e.offsetWidth && Fe.test(p.css(e, "display")) ? p.swap(e, We, function() {
                    return Ke(e, t, o)
                }) : Ke(e, t, o) : void 0
            },
            set: function(e, n, o) {
                var i = o && De(e);
                return Xe(0, n, o ? Je(e, t, o, d.boxSizing() && "border-box" === p.css(e, "boxSizing", !1, i), i) : 0)
            }
        }
    }), d.opacity || (p.cssHooks.opacity = {
        get: function(e, t) {
            return Pe.test((t && e.currentStyle ? e.currentStyle.filter : e.style.filter) || "") ? .01 * parseFloat(RegExp.$1) + "" : t ? "1" : ""
        },
        set: function(e, t) {
            var n = e.style,
                o = e.currentStyle,
                i = p.isNumeric(t) ? "alpha(opacity=" + 100 * t + ")" : "",
                r = o && o.filter || n.filter || "";
            ((n.zoom = 1) <= t || "" === t) && "" === p.trim(r.replace(He, "")) && n.removeAttribute && (n.removeAttribute("filter"), "" === t || o && !o.filter) || (n.filter = He.test(r) ? r.replace(He, i) : r + " " + i)
        }
    }), p.cssHooks.marginRight = Me(d.reliableMarginRight, function(e, t) {
        return t ? p.swap(e, {
            display: "inline-block"
        }, je, [e, "marginRight"]) : void 0
    }), p.each({
        margin: "",
        padding: "",
        border: "Width"
    }, function(e, t) {
        p.cssHooks[e + t] = {
            expand: function(n) {
                for (var o = 0, i = {}, r = "string" == typeof n ? n.split(" ") : [n]; o < 4; o++) i[e + W[o] + t] = r[o] || r[o - 2] || r[0];
                return i
            }
        }, Oe.test(e) || (p.cssHooks[e + t].set = Xe)
    }), p.fn.extend({
        css: function(e, t) {
            return Q(this, function(e, t, n) {
                var o, i, r = {},
                    a = 0;
                if (p.isArray(t)) {
                    for (o = De(e), i = t.length; a < i; a++) r[t[a]] = p.css(e, t[a], !1, o);
                    return r
                }
                return void 0 !== n ? p.style(e, t, n) : p.css(e, t)
            }, e, t, 1 < arguments.length)
        },
        show: function() {
            return Ve(this, !0)
        },
        hide: function() {
            return Ve(this)
        },
        toggle: function(e) {
            return "boolean" == typeof e ? e ? this.show() : this.hide() : this.each(function() {
                z(this) ? p(this).show() : p(this).hide()
            })
        }
    }), ((p.Tween = Ye).prototype = {
        constructor: Ye,
        init: function(e, t, n, o, i, r) {
            this.elem = e, this.prop = n, this.easing = i || "swing", this.options = t, this.start = this.now = this.cur(), this.end = o, this.unit = r || (p.cssNumber[n] ? "" : "px")
        },
        cur: function() {
            var e = Ye.propHooks[this.prop];
            return e && e.get ? e.get(this) : Ye.propHooks._default.get(this)
        },
        run: function(e) {
            var t, n = Ye.propHooks[this.prop];
            return this.pos = t = this.options.duration ? p.easing[this.easing](e, this.options.duration * e, 0, 1, this.options.duration) : e, this.now = (this.end - this.start) * t + this.start, this.options.step && this.options.step.call(this.elem, this.now, this), n && n.set ? n.set(this) : Ye.propHooks._default.set(this), this
        }
    }).init.prototype = Ye.prototype, (Ye.propHooks = {
        _default: {
            get: function(e) {
                var t;
                return null == e.elem[e.prop] || e.elem.style && null != e.elem.style[e.prop] ? (t = p.css(e.elem, e.prop, "")) && "auto" !== t ? t : 0 : e.elem[e.prop]
            },
            set: function(e) {
                p.fx.step[e.prop] ? p.fx.step[e.prop](e) : e.elem.style && (null != e.elem.style[p.cssProps[e.prop]] || p.cssHooks[e.prop]) ? p.style(e.elem, e.prop, e.now + e.unit) : e.elem[e.prop] = e.now
            }
        }
    }).scrollTop = Ye.propHooks.scrollLeft = {
        set: function(e) {
            e.elem.nodeType && e.elem.parentNode && (e.elem[e.prop] = e.now)
        }
    }, p.easing = {
        linear: function(e) {
            return e
        },
        swing: function(e) {
            return .5 - Math.cos(e * Math.PI) / 2
        }
    }, p.fx = Ye.prototype.init, p.fx.step = {};
    var Ge, Ze, et, tt, nt, ot, it, rt = /^(?:toggle|show|hide)$/,
        at = new RegExp("^(?:([+-])=|)(" + q + ")([a-z%]*)$", "i"),
        st = /queueHooks$/,
        lt = [function(e, t, n) {
            var o, i, r, a, s, l, c, u, f = this,
                h = {},
                m = e.style,
                g = e.nodeType && z(e),
                v = p._data(e, "fxshow");
            for (o in n.queue || (null == (s = p._queueHooks(e, "fx")).unqueued && (s.unqueued = 0, l = s.empty.fire, s.empty.fire = function() {
                    s.unqueued || l()
                }), s.unqueued++, f.always(function() {
                    f.always(function() {
                        s.unqueued--, p.queue(e, "fx").length || s.empty.fire()
                    })
                })), 1 === e.nodeType && ("height" in t || "width" in t) && (n.overflow = [m.overflow, m.overflowX, m.overflowY], c = p.css(e, "display"), u = Ae(e.nodeName), "none" === c && (c = u), "inline" === c && "none" === p.css(e, "float") && (d.inlineBlockNeedsLayout && "inline" !== u ? m.zoom = 1 : m.display = "inline-block")), n.overflow && (m.overflow = "hidden", d.shrinkWrapBlocks() || f.always(function() {
                    m.overflow = n.overflow[0], m.overflowX = n.overflow[1], m.overflowY = n.overflow[2]
                })), t)
                if (i = t[o], rt.exec(i)) {
                    if (delete t[o], r = r || "toggle" === i, i === (g ? "hide" : "show")) {
                        if ("show" !== i || !v || void 0 === v[o]) continue;
                        g = !0
                    }
                    h[o] = v && v[o] || p.style(e, o)
                } if (!p.isEmptyObject(h))
                for (o in v ? "hidden" in v && (g = v.hidden) : v = p._data(e, "fxshow", {}), r && (v.hidden = !g), g ? p(e).show() : f.done(function() {
                        p(e).hide()
                    }), f.done(function() {
                        var t;
                        for (t in p._removeData(e, "fxshow"), h) p.style(e, t, h[t])
                    }), h) a = pt(g ? v[o] : 0, o, f), o in v || (v[o] = a.start, g && (a.end = a.start, a.start = "width" === o || "height" === o ? 1 : 0))
        }],
        ct = {
            "*": [function(e, t) {
                var n = this.createTween(e, t),
                    o = n.cur(),
                    i = at.exec(t),
                    r = i && i[3] || (p.cssNumber[e] ? "" : "px"),
                    a = (p.cssNumber[e] || "px" !== r && +o) && at.exec(p.css(n.elem, e)),
                    s = 1,
                    l = 20;
                if (a && a[3] !== r)
                    for (r = r || a[3], i = i || [], a = +o || 1; a /= s = s || ".5", p.style(n.elem, e, a + r), s !== (s = n.cur() / o) && 1 !== s && --l;);
                return i && (a = n.start = +a || +o || 0, n.unit = r, n.end = i[1] ? a + (i[1] + 1) * i[2] : +i[2]), n
            }]
        };

    function ut() {
        return setTimeout(function() {
            Ge = void 0
        }), Ge = p.now()
    }

    function dt(e, t) {
        var n, o = {
                height: e
            },
            i = 0;
        for (t = t ? 1 : 0; i < 4; i += 2 - t) o["margin" + (n = W[i])] = o["padding" + n] = e;
        return t && (o.opacity = o.width = e), o
    }

    function pt(e, t, n) {
        for (var o, i = (ct[t] || []).concat(ct["*"]), r = 0, a = i.length; r < a; r++)
            if (o = i[r].call(n, t, e)) return o
    }

    function ft(e, t, n) {
        var o, i, r = 0,
            a = lt.length,
            s = p.Deferred().always(function() {
                delete l.elem
            }),
            l = function() {
                if (i) return !1;
                for (var t = Ge || ut(), n = Math.max(0, c.startTime + c.duration - t), o = 1 - (n / c.duration || 0), r = 0, a = c.tweens.length; r < a; r++) c.tweens[r].run(o);
                return s.notifyWith(e, [c, o, n]), o < 1 && a ? n : (s.resolveWith(e, [c]), !1)
            },
            c = s.promise({
                elem: e,
                props: p.extend({}, t),
                opts: p.extend(!0, {
                    specialEasing: {}
                }, n),
                originalProperties: t,
                originalOptions: n,
                startTime: Ge || ut(),
                duration: n.duration,
                tweens: [],
                createTween: function(t, n) {
                    var o = p.Tween(e, c.opts, t, n, c.opts.specialEasing[t] || c.opts.easing);
                    return c.tweens.push(o), o
                },
                stop: function(t) {
                    var n = 0,
                        o = t ? c.tweens.length : 0;
                    if (i) return this;
                    for (i = !0; n < o; n++) c.tweens[n].run(1);
                    return t ? s.resolveWith(e, [c, t]) : s.rejectWith(e, [c, t]), this
                }
            }),
            u = c.props;
        for (function(e, t) {
                var n, o, i, r, a;
                for (n in e)
                    if (i = t[o = p.camelCase(n)], r = e[n], p.isArray(r) && (i = r[1], r = e[n] = r[0]), n !== o && (e[o] = r, delete e[n]), (a = p.cssHooks[o]) && "expand" in a)
                        for (n in r = a.expand(r), delete e[o], r) n in e || (e[n] = r[n], t[n] = i);
                    else t[o] = i
            }(u, c.opts.specialEasing); r < a; r++)
            if (o = lt[r].call(c, e, u, c.opts)) return o;
        return p.map(u, pt, c), p.isFunction(c.opts.start) && c.opts.start.call(e, c), p.fx.timer(p.extend(l, {
            elem: e,
            anim: c,
            queue: c.opts.queue
        })), c.progress(c.opts.progress).done(c.opts.done, c.opts.complete).fail(c.opts.fail).always(c.opts.always)
    }
    p.Animation = p.extend(ft, {
        tweener: function(e, t) {
            p.isFunction(e) ? (t = e, e = ["*"]) : e = e.split(" ");
            for (var n, o = 0, i = e.length; o < i; o++) n = e[o], ct[n] = ct[n] || [], ct[n].unshift(t)
        },
        prefilter: function(e, t) {
            t ? lt.unshift(e) : lt.push(e)
        }
    }), p.speed = function(e, t, n) {
        var o = e && "object" == typeof e ? p.extend({}, e) : {
            complete: n || !n && t || p.isFunction(e) && e,
            duration: e,
            easing: n && t || t && !p.isFunction(t) && t
        };
        return o.duration = p.fx.off ? 0 : "number" == typeof o.duration ? o.duration : o.duration in p.fx.speeds ? p.fx.speeds[o.duration] : p.fx.speeds._default, (null == o.queue || !0 === o.queue) && (o.queue = "fx"), o.old = o.complete, o.complete = function() {
            p.isFunction(o.old) && o.old.call(this), o.queue && p.dequeue(this, o.queue)
        }, o
    }, p.fn.extend({
        fadeTo: function(e, t, n, o) {
            return this.filter(z).css("opacity", 0).show().end().animate({
                opacity: t
            }, e, n, o)
        },
        animate: function(e, t, n, o) {
            var i = p.isEmptyObject(e),
                r = p.speed(t, n, o),
                a = function() {
                    var t = ft(this, p.extend({}, e), r);
                    (i || p._data(this, "finish")) && t.stop(!0)
                };
            return a.finish = a, i || !1 === r.queue ? this.each(a) : this.queue(r.queue, a)
        },
        stop: function(e, t, n) {
            var o = function(e) {
                var t = e.stop;
                delete e.stop, t(n)
            };
            return "string" != typeof e && (n = t, t = e, e = void 0), t && !1 !== e && this.queue(e || "fx", []), this.each(function() {
                var t = !0,
                    i = null != e && e + "queueHooks",
                    r = p.timers,
                    a = p._data(this);
                if (i) a[i] && a[i].stop && o(a[i]);
                else
                    for (i in a) a[i] && a[i].stop && st.test(i) && o(a[i]);
                for (i = r.length; i--;) r[i].elem !== this || null != e && r[i].queue !== e || (r[i].anim.stop(n), t = !1, r.splice(i, 1));
                (t || !n) && p.dequeue(this, e)
            })
        },
        finish: function(e) {
            return !1 !== e && (e = e || "fx"), this.each(function() {
                var t, n = p._data(this),
                    o = n[e + "queue"],
                    i = n[e + "queueHooks"],
                    r = p.timers,
                    a = o ? o.length : 0;
                for (n.finish = !0, p.queue(this, e, []), i && i.stop && i.stop.call(this, !0), t = r.length; t--;) r[t].elem === this && r[t].queue === e && (r[t].anim.stop(!0), r.splice(t, 1));
                for (t = 0; t < a; t++) o[t] && o[t].finish && o[t].finish.call(this);
                delete n.finish
            })
        }
    }), p.each(["toggle", "show", "hide"], function(e, t) {
        var n = p.fn[t];
        p.fn[t] = function(e, o, i) {
            return null == e || "boolean" == typeof e ? n.apply(this, arguments) : this.animate(dt(t, !0), e, o, i)
        }
    }), p.each({
        slideDown: dt("show"),
        slideUp: dt("hide"),
        slideToggle: dt("toggle"),
        fadeIn: {
            opacity: "show"
        },
        fadeOut: {
            opacity: "hide"
        },
        fadeToggle: {
            opacity: "toggle"
        }
    }, function(e, t) {
        p.fn[e] = function(e, n, o) {
            return this.animate(t, e, n, o)
        }
    }), p.timers = [], p.fx.tick = function() {
        var e, t = p.timers,
            n = 0;
        for (Ge = p.now(); n < t.length; n++)(e = t[n])() || t[n] !== e || t.splice(n--, 1);
        t.length || p.fx.stop(), Ge = void 0
    }, p.fx.timer = function(e) {
        p.timers.push(e), e() ? p.fx.start() : p.timers.pop()
    }, p.fx.interval = 13, p.fx.start = function() {
        Ze || (Ze = setInterval(p.fx.tick, p.fx.interval))
    }, p.fx.stop = function() {
        clearInterval(Ze), Ze = null
    }, p.fx.speeds = {
        slow: 600,
        fast: 200,
        _default: 400
    }, p.fn.delay = function(e, t) {
        return e = p.fx && p.fx.speeds[e] || e, t = t || "fx", this.queue(t, function(t, n) {
            var o = setTimeout(t, e);
            n.stop = function() {
                clearTimeout(o)
            }
        })
    }, (it = T.createElement("div")).setAttribute("className", "t"), it.innerHTML = "  <link/><table></table><a href='/a'>a</a><input type='checkbox'/>", et = it.getElementsByTagName("a")[0], ot = (nt = T.createElement("select")).appendChild(T.createElement("option")), tt = it.getElementsByTagName("input")[0], et.style.cssText = "top:1px", d.getSetAttribute = "t" !== it.className, d.style = /top/.test(et.getAttribute("style")), d.hrefNormalized = "/a" === et.getAttribute("href"), d.checkOn = !!tt.value, d.optSelected = ot.selected, d.enctype = !!T.createElement("form").enctype, nt.disabled = !0, d.optDisabled = !ot.disabled, (tt = T.createElement("input")).setAttribute("value", ""), d.input = "" === tt.getAttribute("value"), tt.value = "t", tt.setAttribute("type", "radio"), d.radioValue = "t" === tt.value, et = tt = nt = ot = it = null;
    var ht = /\r/g;
    p.fn.extend({
        val: function(e) {
            var t, n, o, i = this[0];
            return arguments.length ? (o = p.isFunction(e), this.each(function(n) {
                var i;
                1 === this.nodeType && (null == (i = o ? e.call(this, n, p(this).val()) : e) ? i = "" : "number" == typeof i ? i += "" : p.isArray(i) && (i = p.map(i, function(e) {
                    return null == e ? "" : e + ""
                })), (t = p.valHooks[this.type] || p.valHooks[this.nodeName.toLowerCase()]) && "set" in t && void 0 !== t.set(this, i, "value") || (this.value = i))
            })) : i ? (t = p.valHooks[i.type] || p.valHooks[i.nodeName.toLowerCase()]) && "get" in t && void 0 !== (n = t.get(i, "value")) ? n : "string" == typeof(n = i.value) ? n.replace(ht, "") : null == n ? "" : n : void 0
        }
    }), p.extend({
        valHooks: {
            option: {
                get: function(e) {
                    var t = p.find.attr(e, "value");
                    return null != t ? t : p.text(e)
                }
            },
            select: {
                get: function(e) {
                    for (var t, n, o = e.options, i = e.selectedIndex, r = "select-one" === e.type || i < 0, a = r ? null : [], s = r ? i + 1 : o.length, l = i < 0 ? s : r ? i : 0; l < s; l++)
                        if (!(!(n = o[l]).selected && l !== i || (d.optDisabled ? n.disabled : null !== n.getAttribute("disabled")) || n.parentNode.disabled && p.nodeName(n.parentNode, "optgroup"))) {
                            if (t = p(n).val(), r) return t;
                            a.push(t)
                        } return a
                },
                set: function(e, t) {
                    for (var n, o, i = e.options, r = p.makeArray(t), a = i.length; a--;)
                        if (o = i[a], 0 <= p.inArray(p.valHooks.option.get(o), r)) try {
                            o.selected = n = !0
                        } catch (e) {
                            o.scrollHeight
                        } else o.selected = !1;
                    return n || (e.selectedIndex = -1), i
                }
            }
        }
    }), p.each(["radio", "checkbox"], function() {
        p.valHooks[this] = {
            set: function(e, t) {
                return p.isArray(t) ? e.checked = 0 <= p.inArray(p(e).val(), t) : void 0
            }
        }, d.checkOn || (p.valHooks[this].get = function(e) {
            return null === e.getAttribute("value") ? "on" : e.value
        })
    });
    var mt, gt, vt = p.expr.attrHandle,
        yt = /^(?:checked|selected)$/i,
        bt = d.getSetAttribute,
        xt = d.input;
    p.fn.extend({
        attr: function(e, t) {
            return Q(this, p.attr, e, t, 1 < arguments.length)
        },
        removeAttr: function(e) {
            return this.each(function() {
                p.removeAttr(this, e)
            })
        }
    }), p.extend({
        attr: function(e, t, n) {
            var o, i, r = e.nodeType;
            if (e && 3 !== r && 8 !== r && 2 !== r) return typeof e.getAttribute === I ? p.prop(e, t, n) : (1 === r && p.isXMLDoc(e) || (t = t.toLowerCase(), o = p.attrHooks[t] || (p.expr.match.bool.test(t) ? gt : mt)), void 0 === n ? o && "get" in o && null !== (i = o.get(e, t)) ? i : null == (i = p.find.attr(e, t)) ? void 0 : i : null !== n ? o && "set" in o && void 0 !== (i = o.set(e, n, t)) ? i : (e.setAttribute(t, n + ""), n) : void p.removeAttr(e, t))
        },
        removeAttr: function(e, t) {
            var n, o, i = 0,
                r = t && t.match(_);
            if (r && 1 === e.nodeType)
                for (; n = r[i++];) o = p.propFix[n] || n, p.expr.match.bool.test(n) ? xt && bt || !yt.test(n) ? e[o] = !1 : e[p.camelCase("default-" + n)] = e[o] = !1 : p.attr(e, n, ""), e.removeAttribute(bt ? n : o)
        },
        attrHooks: {
            type: {
                set: function(e, t) {
                    if (!d.radioValue && "radio" === t && p.nodeName(e, "input")) {
                        var n = e.value;
                        return e.setAttribute("type", t), n && (e.value = n), t
                    }
                }
            }
        }
    }), gt = {
        set: function(e, t, n) {
            return !1 === t ? p.removeAttr(e, n) : xt && bt || !yt.test(n) ? e.setAttribute(!bt && p.propFix[n] || n, n) : e[p.camelCase("default-" + n)] = e[n] = !0, n
        }
    }, p.each(p.expr.match.bool.source.match(/\w+/g), function(e, t) {
        var n = vt[t] || p.find.attr;
        vt[t] = xt && bt || !yt.test(t) ? function(e, t, o) {
            var i, r;
            return o || (r = vt[t], vt[t] = i, i = null != n(e, t, o) ? t.toLowerCase() : null, vt[t] = r), i
        } : function(e, t, n) {
            return n ? void 0 : e[p.camelCase("default-" + t)] ? t.toLowerCase() : null
        }
    }), xt && bt || (p.attrHooks.value = {
        set: function(e, t, n) {
            return p.nodeName(e, "input") ? void(e.defaultValue = t) : mt && mt.set(e, t, n)
        }
    }), bt || (mt = {
        set: function(e, t, n) {
            var o = e.getAttributeNode(n);
            return o || e.setAttributeNode(o = e.ownerDocument.createAttribute(n)), o.value = t += "", "value" === n || t === e.getAttribute(n) ? t : void 0
        }
    }, vt.id = vt.name = vt.coords = function(e, t, n) {
        var o;
        return n ? void 0 : (o = e.getAttributeNode(t)) && "" !== o.value ? o.value : null
    }, p.valHooks.button = {
        get: function(e, t) {
            var n = e.getAttributeNode(t);
            return n && n.specified ? n.value : void 0
        },
        set: mt.set
    }, p.attrHooks.contenteditable = {
        set: function(e, t, n) {
            mt.set(e, "" !== t && t, n)
        }
    }, p.each(["width", "height"], function(e, t) {
        p.attrHooks[t] = {
            set: function(e, n) {
                return "" === n ? (e.setAttribute(t, "auto"), n) : void 0
            }
        }
    })), d.style || (p.attrHooks.style = {
        get: function(e) {
            return e.style.cssText || void 0
        },
        set: function(e, t) {
            return e.style.cssText = t + ""
        }
    });
    var wt = /^(?:input|select|textarea|button|object)$/i,
        Ct = /^(?:a|area)$/i;
    p.fn.extend({
        prop: function(e, t) {
            return Q(this, p.prop, e, t, 1 < arguments.length)
        },
        removeProp: function(e) {
            return e = p.propFix[e] || e, this.each(function() {
                try {
                    this[e] = void 0, delete this[e]
                } catch (e) {}
            })
        }
    }), p.extend({
        propFix: {
            for: "htmlFor",
            class: "className"
        },
        prop: function(e, t, n) {
            var o, i, r = e.nodeType;
            if (e && 3 !== r && 8 !== r && 2 !== r) return (1 !== r || !p.isXMLDoc(e)) && (t = p.propFix[t] || t, i = p.propHooks[t]), void 0 !== n ? i && "set" in i && void 0 !== (o = i.set(e, n, t)) ? o : e[t] = n : i && "get" in i && null !== (o = i.get(e, t)) ? o : e[t]
        },
        propHooks: {
            tabIndex: {
                get: function(e) {
                    var t = p.find.attr(e, "tabindex");
                    return t ? parseInt(t, 10) : wt.test(e.nodeName) || Ct.test(e.nodeName) && e.href ? 0 : -1
                }
            }
        }
    }), d.hrefNormalized || p.each(["href", "src"], function(e, t) {
        p.propHooks[t] = {
            get: function(e) {
                return e.getAttribute(t, 4)
            }
        }
    }), d.optSelected || (p.propHooks.selected = {
        get: function(e) {
            var t = e.parentNode;
            return t && (t.selectedIndex, t.parentNode && t.parentNode.selectedIndex), null
        }
    }), p.each(["tabIndex", "readOnly", "maxLength", "cellSpacing", "cellPadding", "rowSpan", "colSpan", "useMap", "frameBorder", "contentEditable"], function() {
        p.propFix[this.toLowerCase()] = this
    }), d.enctype || (p.propFix.enctype = "encoding");
    var kt = /[\t\r\n\f]/g;
    p.fn.extend({
        addClass: function(e) {
            var t, n, o, i, r, a, s = 0,
                l = this.length,
                c = "string" == typeof e && e;
            if (p.isFunction(e)) return this.each(function(t) {
                p(this).addClass(e.call(this, t, this.className))
            });
            if (c)
                for (t = (e || "").match(_) || []; s < l; s++)
                    if (o = 1 === (n = this[s]).nodeType && (n.className ? (" " + n.className + " ").replace(kt, " ") : " ")) {
                        for (r = 0; i = t[r++];) o.indexOf(" " + i + " ") < 0 && (o += i + " ");
                        a = p.trim(o), n.className !== a && (n.className = a)
                    } return this
        },
        removeClass: function(e) {
            var t, n, o, i, r, a, s = 0,
                l = this.length,
                c = 0 === arguments.length || "string" == typeof e && e;
            if (p.isFunction(e)) return this.each(function(t) {
                p(this).removeClass(e.call(this, t, this.className))
            });
            if (c)
                for (t = (e || "").match(_) || []; s < l; s++)
                    if (o = 1 === (n = this[s]).nodeType && (n.className ? (" " + n.className + " ").replace(kt, " ") : "")) {
                        for (r = 0; i = t[r++];)
                            for (; 0 <= o.indexOf(" " + i + " ");) o = o.replace(" " + i + " ", " ");
                        a = e ? p.trim(o) : "", n.className !== a && (n.className = a)
                    } return this
        },
        toggleClass: function(e, t) {
            var n = typeof e;
            return "boolean" == typeof t && "string" === n ? t ? this.addClass(e) : this.removeClass(e) : this.each(p.isFunction(e) ? function(n) {
                p(this).toggleClass(e.call(this, n, this.className, t), t)
            } : function() {
                if ("string" === n)
                    for (var t, o = 0, i = p(this), r = e.match(_) || []; t = r[o++];) i.hasClass(t) ? i.removeClass(t) : i.addClass(t);
                else(n === I || "boolean" === n) && (this.className && p._data(this, "__className__", this.className), this.className = this.className || !1 === e ? "" : p._data(this, "__className__") || "")
            })
        },
        hasClass: function(e) {
            for (var t = " " + e + " ", n = 0, o = this.length; n < o; n++)
                if (1 === this[n].nodeType && 0 <= (" " + this[n].className + " ").replace(kt, " ").indexOf(t)) return !0;
            return !1
        }
    }), p.each("blur focus focusin focusout load resize scroll unload click dblclick mousedown mouseup mousemove mouseover mouseout mouseenter mouseleave change select submit keydown keypress keyup error contextmenu".split(" "), function(e, t) {
        p.fn[t] = function(e, n) {
            return 0 < arguments.length ? this.on(t, null, e, n) : this.trigger(t)
        }
    }), p.fn.extend({
        hover: function(e, t) {
            return this.mouseenter(e).mouseleave(t || e)
        },
        bind: function(e, t, n) {
            return this.on(e, null, t, n)
        },
        unbind: function(e, t) {
            return this.off(e, null, t)
        },
        delegate: function(e, t, n, o) {
            return this.on(t, e, n, o)
        },
        undelegate: function(e, t, n) {
            return 1 === arguments.length ? this.off(e, "**") : this.off(t, e || "**", n)
        }
    });
    var Tt = p.now(),
        $t = /\?/,
        Et = /(,)|(\[|{)|(}|])|"(?:[^"\\\r\n]|\\["\\\/bfnrt]|\\u[\da-fA-F]{4})*"\s*:?|true|false|null|-?(?!0\d)\d+(?:\.\d+|)(?:[eE][+-]?\d+|)/g;
    p.parseJSON = function(t) {
        if (e.JSON && e.JSON.parse) return e.JSON.parse(t + "");
        var n, o = null,
            i = p.trim(t + "");
        return i && !p.trim(i.replace(Et, function(e, t, i, r) {
            return n && t && (o = 0), 0 === o ? e : (n = i || t, o += !r - !i, "")
        })) ? Function("return " + i)() : p.error("Invalid JSON: " + t)
    }, p.parseXML = function(t) {
        var n;
        if (!t || "string" != typeof t) return null;
        try {
            e.DOMParser ? n = (new DOMParser).parseFromString(t, "text/xml") : ((n = new ActiveXObject("Microsoft.XMLDOM")).async = "false", n.loadXML(t))
        } catch (t) {
            n = void 0
        }
        return n && n.documentElement && !n.getElementsByTagName("parsererror").length || p.error("Invalid XML: " + t), n
    };
    var St, Nt, Bt = /#.*$/,
        _t = /([?&])_=[^&]*/,
        At = /^(.*?):[ \t]*([^\r\n]*)\r?$/gm,
        Dt = /^(?:GET|HEAD)$/,
        jt = /^\/\//,
        Ot = /^([\w.+-]+:)(?:\/\/(?:[^\/?#]*@|)([^\/?#:]*)(?::(\d+)|)|)/,
        It = {},
        Lt = {},
        Mt = "*/".concat("*");
    try {
        Nt = location.href
    } catch (t) {
        (Nt = T.createElement("a")).href = "", Nt = Nt.href
    }

    function Ht(e) {
        return function(t, n) {
            "string" != typeof t && (n = t, t = "*");
            var o, i = 0,
                r = t.toLowerCase().match(_) || [];
            if (p.isFunction(n))
                for (; o = r[i++];) "+" === o.charAt(0) ? (o = o.slice(1) || "*", (e[o] = e[o] || []).unshift(n)) : (e[o] = e[o] || []).push(n)
        }
    }

    function Pt(e, t, n, o) {
        var i = {},
            r = e === Lt;

        function a(s) {
            var l;
            return i[s] = !0, p.each(e[s] || [], function(e, s) {
                var c = s(t, n, o);
                return "string" != typeof c || r || i[c] ? r ? !(l = c) : void 0 : (t.dataTypes.unshift(c), a(c), !1)
            }), l
        }
        return a(t.dataTypes[0]) || !i["*"] && a("*")
    }

    function Ft(e, t) {
        var n, o, i = p.ajaxSettings.flatOptions || {};
        for (o in t) void 0 !== t[o] && ((i[o] ? e : n || (n = {}))[o] = t[o]);
        return n && p.extend(!0, e, n), e
    }
    St = Ot.exec(Nt.toLowerCase()) || [], p.extend({
        active: 0,
        lastModified: {},
        etag: {},
        ajaxSettings: {
            url: Nt,
            type: "GET",
            isLocal: /^(?:about|app|app-storage|.+-extension|file|res|widget):$/.test(St[1]),
            global: !0,
            processData: !0,
            async: !0,
            contentType: "application/x-www-form-urlencoded; charset=UTF-8",
            accepts: {
                "*": Mt,
                text: "text/plain",
                html: "text/html",
                xml: "application/xml, text/xml",
                json: "application/json, text/javascript"
            },
            contents: {
                xml: /xml/,
                html: /html/,
                json: /json/
            },
            responseFields: {
                xml: "responseXML",
                text: "responseText",
                json: "responseJSON"
            },
            converters: {
                "* text": String,
                "text html": !0,
                "text json": p.parseJSON,
                "text xml": p.parseXML
            },
            flatOptions: {
                url: !0,
                context: !0
            }
        },
        ajaxSetup: function(e, t) {
            return t ? Ft(Ft(e, p.ajaxSettings), t) : Ft(p.ajaxSettings, e)
        },
        ajaxPrefilter: Ht(It),
        ajaxTransport: Ht(Lt),
        ajax: function(e, t) {
            "object" == typeof e && (t = e, e = void 0), t = t || {};
            var n, o, i, r, a, s, l, c, u = p.ajaxSetup({}, t),
                d = u.context || u,
                f = u.context && (d.nodeType || d.jquery) ? p(d) : p.event,
                h = p.Deferred(),
                m = p.Callbacks("once memory"),
                g = u.statusCode || {},
                v = {},
                y = {},
                b = 0,
                x = "canceled",
                w = {
                    readyState: 0,
                    getResponseHeader: function(e) {
                        var t;
                        if (2 === b) {
                            if (!c)
                                for (c = {}; t = At.exec(r);) c[t[1].toLowerCase()] = t[2];
                            t = c[e.toLowerCase()]
                        }
                        return null == t ? null : t
                    },
                    getAllResponseHeaders: function() {
                        return 2 === b ? r : null
                    },
                    setRequestHeader: function(e, t) {
                        var n = e.toLowerCase();
                        return b || (e = y[n] = y[n] || e, v[e] = t), this
                    },
                    overrideMimeType: function(e) {
                        return b || (u.mimeType = e), this
                    },
                    statusCode: function(e) {
                        var t;
                        if (e)
                            if (b < 2)
                                for (t in e) g[t] = [g[t], e[t]];
                            else w.always(e[w.status]);
                        return this
                    },
                    abort: function(e) {
                        var t = e || x;
                        return l && l.abort(t), C(0, t), this
                    }
                };
            if (h.promise(w).complete = m.add, w.success = w.done, w.error = w.fail, u.url = ((e || u.url || Nt) + "").replace(Bt, "").replace(jt, St[1] + "//"), u.type = t.method || t.type || u.method || u.type, u.dataTypes = p.trim(u.dataType || "*").toLowerCase().match(_) || [""], null == u.crossDomain && (n = Ot.exec(u.url.toLowerCase()), u.crossDomain = !(!n || n[1] === St[1] && n[2] === St[2] && (n[3] || ("http:" === n[1] ? "80" : "443")) === (St[3] || ("http:" === St[1] ? "80" : "443")))), u.data && u.processData && "string" != typeof u.data && (u.data = p.param(u.data, u.traditional)), Pt(It, u, t, w), 2 === b) return w;
            for (o in (s = u.global) && 0 == p.active++ && p.event.trigger("ajaxStart"), u.type = u.type.toUpperCase(), u.hasContent = !Dt.test(u.type), i = u.url, u.hasContent || (u.data && (i = u.url += ($t.test(i) ? "&" : "?") + u.data, delete u.data), !1 === u.cache && (u.url = _t.test(i) ? i.replace(_t, "$1_=" + Tt++) : i + ($t.test(i) ? "&" : "?") + "_=" + Tt++)), u.ifModified && (p.lastModified[i] && w.setRequestHeader("If-Modified-Since", p.lastModified[i]), p.etag[i] && w.setRequestHeader("If-None-Match", p.etag[i])), (u.data && u.hasContent && !1 !== u.contentType || t.contentType) && w.setRequestHeader("Content-Type", u.contentType), w.setRequestHeader("Accept", u.dataTypes[0] && u.accepts[u.dataTypes[0]] ? u.accepts[u.dataTypes[0]] + ("*" !== u.dataTypes[0] ? ", " + Mt + "; q=0.01" : "") : u.accepts["*"]), u.headers) w.setRequestHeader(o, u.headers[o]);
            if (u.beforeSend && (!1 === u.beforeSend.call(d, w, u) || 2 === b)) return w.abort();
            for (o in x = "abort", {
                    success: 1,
                    error: 1,
                    complete: 1
                }) w[o](u[o]);
            if (l = Pt(Lt, u, t, w)) {
                w.readyState = 1, s && f.trigger("ajaxSend", [w, u]), u.async && 0 < u.timeout && (a = setTimeout(function() {
                    w.abort("timeout")
                }, u.timeout));
                try {
                    b = 1, l.send(v, C)
                } catch (e) {
                    if (!(b < 2)) throw e;
                    C(-1, e)
                }
            } else C(-1, "No Transport");

            function C(e, t, n, o) {
                var c, v, y, x, C, k = t;
                2 !== b && (b = 2, a && clearTimeout(a), l = void 0, r = o || "", w.readyState = 0 < e ? 4 : 0, c = 200 <= e && e < 300 || 304 === e, n && (x = function(e, t, n) {
                    for (var o, i, r, a, s = e.contents, l = e.dataTypes;
                        "*" === l[0];) l.shift(), void 0 === i && (i = e.mimeType || t.getResponseHeader("Content-Type"));
                    if (i)
                        for (a in s)
                            if (s[a] && s[a].test(i)) {
                                l.unshift(a);
                                break
                            } if (l[0] in n) r = l[0];
                    else {
                        for (a in n) {
                            if (!l[0] || e.converters[a + " " + l[0]]) {
                                r = a;
                                break
                            }
                            o || (o = a)
                        }
                        r = r || o
                    }
                    return r ? (r !== l[0] && l.unshift(r), n[r]) : void 0
                }(u, w, n)), x = function(e, t, n, o) {
                    var i, r, a, s, l, c = {},
                        u = e.dataTypes.slice();
                    if (u[1])
                        for (a in e.converters) c[a.toLowerCase()] = e.converters[a];
                    for (r = u.shift(); r;)
                        if (e.responseFields[r] && (n[e.responseFields[r]] = t), !l && o && e.dataFilter && (t = e.dataFilter(t, e.dataType)), l = r, r = u.shift())
                            if ("*" === r) r = l;
                            else if ("*" !== l && l !== r) {
                        if (!(a = c[l + " " + r] || c["* " + r]))
                            for (i in c)
                                if ((s = i.split(" "))[1] === r && (a = c[l + " " + s[0]] || c["* " + s[0]])) {
                                    !0 === a ? a = c[i] : !0 !== c[i] && (r = s[0], u.unshift(s[1]));
                                    break
                                } if (!0 !== a)
                            if (a && e.throws) t = a(t);
                            else try {
                                t = a(t)
                            } catch (e) {
                                return {
                                    state: "parsererror",
                                    error: a ? e : "No conversion from " + l + " to " + r
                                }
                            }
                    }
                    return {
                        state: "success",
                        data: t
                    }
                }(u, x, w, c), c ? (u.ifModified && ((C = w.getResponseHeader("Last-Modified")) && (p.lastModified[i] = C), (C = w.getResponseHeader("etag")) && (p.etag[i] = C)), 204 === e || "HEAD" === u.type ? k = "nocontent" : 304 === e ? k = "notmodified" : (k = x.state, v = x.data, c = !(y = x.error))) : (y = k, (e || !k) && (k = "error", e < 0 && (e = 0))), w.status = e, w.statusText = (t || k) + "", c ? h.resolveWith(d, [v, k, w]) : h.rejectWith(d, [w, k, y]), w.statusCode(g), g = void 0, s && f.trigger(c ? "ajaxSuccess" : "ajaxError", [w, u, c ? v : y]), m.fireWith(d, [w, k]), s && (f.trigger("ajaxComplete", [w, u]), --p.active || p.event.trigger("ajaxStop")))
            }
            return w
        },
        getJSON: function(e, t, n) {
            return p.get(e, t, n, "json")
        },
        getScript: function(e, t) {
            return p.get(e, void 0, t, "script")
        }
    }), p.each(["get", "post"], function(e, t) {
        p[t] = function(e, n, o, i) {
            return p.isFunction(n) && (i = i || o, o = n, n = void 0), p.ajax({
                url: e,
                type: t,
                dataType: i,
                data: n,
                success: o
            })
        }
    }), p.each(["ajaxStart", "ajaxStop", "ajaxComplete", "ajaxError", "ajaxSuccess", "ajaxSend"], function(e, t) {
        p.fn[t] = function(e) {
            return this.on(t, e)
        }
    }), p._evalUrl = function(e) {
        return p.ajax({
            url: e,
            type: "GET",
            dataType: "script",
            async: !1,
            global: !1,
            throws: !0
        })
    }, p.fn.extend({
        wrapAll: function(e) {
            if (p.isFunction(e)) return this.each(function(t) {
                p(this).wrapAll(e.call(this, t))
            });
            if (this[0]) {
                var t = p(e, this[0].ownerDocument).eq(0).clone(!0);
                this[0].parentNode && t.insertBefore(this[0]), t.map(function() {
                    for (var e = this; e.firstChild && 1 === e.firstChild.nodeType;) e = e.firstChild;
                    return e
                }).append(this)
            }
            return this
        },
        wrapInner: function(e) {
            return this.each(p.isFunction(e) ? function(t) {
                p(this).wrapInner(e.call(this, t))
            } : function() {
                var t = p(this),
                    n = t.contents();
                n.length ? n.wrapAll(e) : t.append(e)
            })
        },
        wrap: function(e) {
            var t = p.isFunction(e);
            return this.each(function(n) {
                p(this).wrapAll(t ? e.call(this, n) : e)
            })
        },
        unwrap: function() {
            return this.parent().each(function() {
                p.nodeName(this, "body") || p(this).replaceWith(this.childNodes)
            }).end()
        }
    }), p.expr.filters.hidden = function(e) {
        return e.offsetWidth <= 0 && e.offsetHeight <= 0 || !d.reliableHiddenOffsets() && "none" === (e.style && e.style.display || p.css(e, "display"))
    }, p.expr.filters.visible = function(e) {
        return !p.expr.filters.hidden(e)
    };
    var Rt = /%20/g,
        qt = /\[\]$/,
        Wt = /\r?\n/g,
        zt = /^(?:submit|button|image|reset|file)$/i,
        Qt = /^(?:input|select|textarea|keygen)/i;

    function Ut(e, t, n, o) {
        var i;
        if (p.isArray(t)) p.each(t, function(t, i) {
            n || qt.test(e) ? o(e, i) : Ut(e + "[" + ("object" == typeof i ? t : "") + "]", i, n, o)
        });
        else if (n || "object" !== p.type(t)) o(e, t);
        else
            for (i in t) Ut(e + "[" + i + "]", t[i], n, o)
    }
    p.param = function(e, t) {
        var n, o = [],
            i = function(e, t) {
                t = p.isFunction(t) ? t() : null == t ? "" : t, o[o.length] = encodeURIComponent(e) + "=" + encodeURIComponent(t)
            };
        if (void 0 === t && (t = p.ajaxSettings && p.ajaxSettings.traditional), p.isArray(e) || e.jquery && !p.isPlainObject(e)) p.each(e, function() {
            i(this.name, this.value)
        });
        else
            for (n in e) Ut(n, e[n], t, i);
        return o.join("&").replace(Rt, "+")
    }, p.fn.extend({
        serialize: function() {
            return p.param(this.serializeArray())
        },
        serializeArray: function() {
            return this.map(function() {
                var e = p.prop(this, "elements");
                return e ? p.makeArray(e) : this
            }).filter(function() {
                var e = this.type;
                return this.name && !p(this).is(":disabled") && Qt.test(this.nodeName) && !zt.test(e) && (this.checked || !U.test(e))
            }).map(function(e, t) {
                var n = p(this).val();
                return null == n ? null : p.isArray(n) ? p.map(n, function(e) {
                    return {
                        name: t.name,
                        value: e.replace(Wt, "\r\n")
                    }
                }) : {
                    name: t.name,
                    value: n.replace(Wt, "\r\n")
                }
            }).get()
        }
    }), p.ajaxSettings.xhr = void 0 !== e.ActiveXObject ? function() {
        return !this.isLocal && /^(get|post|head|put|delete|options)$/i.test(this.type) && Kt() || function() {
            try {
                return new e.ActiveXObject("Microsoft.XMLHTTP")
            } catch (e) {}
        }()
    } : Kt;
    var Vt = 0,
        Xt = {},
        Jt = p.ajaxSettings.xhr();

    function Kt() {
        try {
            return new e.XMLHttpRequest
        } catch (e) {}
    }
    e.ActiveXObject && p(e).on("unload", function() {
        for (var e in Xt) Xt[e](void 0, !0)
    }), d.cors = !!Jt && "withCredentials" in Jt, (Jt = d.ajax = !!Jt) && p.ajaxTransport(function(e) {
        var t;
        if (!e.crossDomain || d.cors) return {
            send: function(n, o) {
                var i, r = e.xhr(),
                    a = ++Vt;
                if (r.open(e.type, e.url, e.async, e.username, e.password), e.xhrFields)
                    for (i in e.xhrFields) r[i] = e.xhrFields[i];
                for (i in e.mimeType && r.overrideMimeType && r.overrideMimeType(e.mimeType), e.crossDomain || n["X-Requested-With"] || (n["X-Requested-With"] = "XMLHttpRequest"), n) void 0 !== n[i] && r.setRequestHeader(i, n[i] + "");
                r.send(e.hasContent && e.data || null), t = function(n, i) {
                    var s, l, c;
                    if (t && (i || 4 === r.readyState))
                        if (delete Xt[a], t = void 0, r.onreadystatechange = p.noop, i) 4 !== r.readyState && r.abort();
                        else {
                            c = {}, s = r.status, "string" == typeof r.responseText && (c.text = r.responseText);
                            try {
                                l = r.statusText
                            } catch (n) {
                                l = ""
                            }
                            s || !e.isLocal || e.crossDomain ? 1223 === s && (s = 204) : s = c.text ? 200 : 404
                        } c && o(s, l, c, r.getAllResponseHeaders())
                }, e.async ? 4 === r.readyState ? setTimeout(t) : r.onreadystatechange = Xt[a] = t : t()
            },
            abort: function() {
                t && t(void 0, !0)
            }
        }
    }), p.ajaxSetup({
        accepts: {
            script: "text/javascript, application/javascript, application/ecmascript, application/x-ecmascript"
        },
        contents: {
            script: /(?:java|ecma)script/
        },
        converters: {
            "text script": function(e) {
                return p.globalEval(e), e
            }
        }
    }), p.ajaxPrefilter("script", function(e) {
        void 0 === e.cache && (e.cache = !1), e.crossDomain && (e.type = "GET", e.global = !1)
    }), p.ajaxTransport("script", function(e) {
        if (e.crossDomain) {
            var t, n = T.head || p("head")[0] || T.documentElement;
            return {
                send: function(o, i) {
                    (t = T.createElement("script")).async = !0, e.scriptCharset && (t.charset = e.scriptCharset), t.src = e.url, t.onload = t.onreadystatechange = function(e, n) {
                        (n || !t.readyState || /loaded|complete/.test(t.readyState)) && (t.onload = t.onreadystatechange = null, t.parentNode && t.parentNode.removeChild(t), t = null, n || i(200, "success"))
                    }, n.insertBefore(t, n.firstChild)
                },
                abort: function() {
                    t && t.onload(void 0, !0)
                }
            }
        }
    });
    var Yt = [],
        Gt = /(=)\?(?=&|$)|\?\?/;
    p.ajaxSetup({
        jsonp: "callback",
        jsonpCallback: function() {
            var e = Yt.pop() || p.expando + "_" + Tt++;
            return this[e] = !0, e
        }
    }), p.ajaxPrefilter("json jsonp", function(t, n, o) {
        var i, r, a, s = !1 !== t.jsonp && (Gt.test(t.url) ? "url" : "string" == typeof t.data && !(t.contentType || "").indexOf("application/x-www-form-urlencoded") && Gt.test(t.data) && "data");
        return s || "jsonp" === t.dataTypes[0] ? (i = t.jsonpCallback = p.isFunction(t.jsonpCallback) ? t.jsonpCallback() : t.jsonpCallback, s ? t[s] = t[s].replace(Gt, "$1" + i) : !1 !== t.jsonp && (t.url += ($t.test(t.url) ? "&" : "?") + t.jsonp + "=" + i), t.converters["script json"] = function() {
            return a || p.error(i + " was not called"), a[0]
        }, t.dataTypes[0] = "json", r = e[i], e[i] = function() {
            a = arguments
        }, o.always(function() {
            e[i] = r, t[i] && (t.jsonpCallback = n.jsonpCallback, Yt.push(i)), a && p.isFunction(r) && r(a[0]), a = r = void 0
        }), "script") : void 0
    }), p.parseHTML = function(e, t, n) {
        if (!e || "string" != typeof e) return null;
        "boolean" == typeof t && (n = t, t = !1), t = t || T;
        var o = x.exec(e),
            i = !n && [];
        return o ? [t.createElement(o[1])] : (o = p.buildFragment([e], t, i), i && i.length && p(i).remove(), p.merge([], o.childNodes))
    };
    var Zt = p.fn.load;
    p.fn.load = function(e, t, n) {
        if ("string" != typeof e && Zt) return Zt.apply(this, arguments);
        var o, i, r, a = this,
            s = e.indexOf(" ");
        return 0 <= s && (o = e.slice(s, e.length), e = e.slice(0, s)), p.isFunction(t) ? (n = t, t = void 0) : t && "object" == typeof t && (r = "POST"), 0 < a.length && p.ajax({
            url: e,
            type: r,
            dataType: "html",
            data: t
        }).done(function(e) {
            i = arguments, a.html(o ? p("<div>").append(p.parseHTML(e)).find(o) : e)
        }).complete(n && function(e, t) {
            a.each(n, i || [e.responseText, t, e])
        }), this
    }, p.expr.filters.animated = function(e) {
        return p.grep(p.timers, function(t) {
            return e === t.elem
        }).length
    };
    var en = e.document.documentElement;

    function tn(e) {
        return p.isWindow(e) ? e : 9 === e.nodeType && (e.defaultView || e.parentWindow)
    }
    p.offset = {
        setOffset: function(e, t, n) {
            var o, i, r, a, s, l, c = p.css(e, "position"),
                u = p(e),
                d = {};
            "static" === c && (e.style.position = "relative"), s = u.offset(), r = p.css(e, "top"), l = p.css(e, "left"), ("absolute" === c || "fixed" === c) && -1 < p.inArray("auto", [r, l]) ? (a = (o = u.position()).top, i = o.left) : (a = parseFloat(r) || 0, i = parseFloat(l) || 0), p.isFunction(t) && (t = t.call(e, n, s)), null != t.top && (d.top = t.top - s.top + a), null != t.left && (d.left = t.left - s.left + i), "using" in t ? t.using.call(e, d) : u.css(d)
        }
    }, p.fn.extend({
        offset: function(e) {
            if (arguments.length) return void 0 === e ? this : this.each(function(t) {
                p.offset.setOffset(this, e, t)
            });
            var t, n, o = {
                    top: 0,
                    left: 0
                },
                i = this[0],
                r = i && i.ownerDocument;
            return r ? (t = r.documentElement, p.contains(t, i) ? (typeof i.getBoundingClientRect !== I && (o = i.getBoundingClientRect()), n = tn(r), {
                top: o.top + (n.pageYOffset || t.scrollTop) - (t.clientTop || 0),
                left: o.left + (n.pageXOffset || t.scrollLeft) - (t.clientLeft || 0)
            }) : o) : void 0
        },
        position: function() {
            if (this[0]) {
                var e, t, n = {
                        top: 0,
                        left: 0
                    },
                    o = this[0];
                return "fixed" === p.css(o, "position") ? t = o.getBoundingClientRect() : (e = this.offsetParent(), t = this.offset(), p.nodeName(e[0], "html") || (n = e.offset()), n.top += p.css(e[0], "borderTopWidth", !0), n.left += p.css(e[0], "borderLeftWidth", !0)), {
                    top: t.top - n.top - p.css(o, "marginTop", !0),
                    left: t.left - n.left - p.css(o, "marginLeft", !0)
                }
            }
        },
        offsetParent: function() {
            return this.map(function() {
                for (var e = this.offsetParent || en; e && !p.nodeName(e, "html") && "static" === p.css(e, "position");) e = e.offsetParent;
                return e || en
            })
        }
    }), p.each({
        scrollLeft: "pageXOffset",
        scrollTop: "pageYOffset"
    }, function(e, t) {
        var n = /Y/.test(t);
        p.fn[e] = function(o) {
            return Q(this, function(e, o, i) {
                var r = tn(e);
                return void 0 === i ? r ? t in r ? r[t] : r.document.documentElement[o] : e[o] : void(r ? r.scrollTo(n ? p(r).scrollLeft() : i, n ? i : p(r).scrollTop()) : e[o] = i)
            }, e, o, arguments.length, null)
        }
    }), p.each(["top", "left"], function(e, t) {
        p.cssHooks[t] = Me(d.pixelPosition, function(e, n) {
            return n ? (n = je(e, t), Ie.test(n) ? p(e).position()[t] + "px" : n) : void 0
        })
    }), p.each({
        Height: "height",
        Width: "width"
    }, function(e, t) {
        p.each({
            padding: "inner" + e,
            content: t,
            "": "outer" + e
        }, function(n, o) {
            p.fn[o] = function(o, i) {
                var r = arguments.length && (n || "boolean" != typeof o),
                    a = n || (!0 === o || !0 === i ? "margin" : "border");
                return Q(this, function(t, n, o) {
                    var i;
                    return p.isWindow(t) ? t.document.documentElement["client" + e] : 9 === t.nodeType ? (i = t.documentElement, Math.max(t.body["scroll" + e], i["scroll" + e], t.body["offset" + e], i["offset" + e], i["client" + e])) : void 0 === o ? p.css(t, n, a) : p.style(t, n, o, a)
                }, t, r ? o : void 0, r, null)
            }
        })
    }), p.fn.size = function() {
        return this.length
    }, p.fn.andSelf = p.fn.addBack, "function" == typeof define && define.amd && define("jquery", [], function() {
        return p
    });
    var nn = e.jQuery,
        on = e.$;
    return p.noConflict = function(t) {
        return e.$ === p && (e.$ = on), t && e.jQuery === p && (e.jQuery = nn), p
    }, typeof t === I && (e.jQuery = e.$ = p), p
}), void 0 === jQuery.migrateMute && (jQuery.migrateMute = !0),
    function(e, t, n) {
        function o(n) {
            var o = t.console;
            r[n] || (r[n] = !0, e.migrateWarnings.push(n), o && o.warn && !e.migrateMute && (o.warn("JQMIGRATE: " + n), e.migrateTrace && o.trace && o.trace()))
        }

        function i(t, i, r, a) {
            if (Object.defineProperty) try {
                return Object.defineProperty(t, i, {
                    configurable: !0,
                    enumerable: !0,
                    get: function() {
                        return o(a), r
                    },
                    set: function(e) {
                        o(a), r = e
                    }
                }), n
            } catch (t) {}
            e._definePropertyBroken = !0, t[i] = r
        }
        var r = {};
        e.migrateWarnings = [], !e.migrateMute && t.console && t.console.log && t.console.log("JQMIGRATE: Logging is active"), e.migrateTrace === n && (e.migrateTrace = !0), e.migrateReset = function() {
            r = {}, e.migrateWarnings.length = 0
        }, "BackCompat" === document.compatMode && o("jQuery is not compatible with Quirks Mode");
        var a = e("<input/>", {
                size: 1
            }).attr("size") && e.attrFn,
            s = e.attr,
            l = e.attrHooks.value && e.attrHooks.value.get || function() {
                return null
            },
            c = e.attrHooks.value && e.attrHooks.value.set || function() {
                return n
            },
            u = /^(?:input|button)$/i,
            d = /^[238]$/,
            p = /^(?:autofocus|autoplay|async|checked|controls|defer|disabled|hidden|loop|multiple|open|readonly|required|scoped|selected)$/i,
            f = /^(?:checked|selected)$/i;
        i(e, "attrFn", a || {}, "jQuery.attrFn is deprecated"), e.attr = function(t, i, r, l) {
            var c = i.toLowerCase(),
                h = t && t.nodeType;
            return l && (s.length < 4 && o("jQuery.fn.attr( props, pass ) is deprecated"), t && !d.test(h) && (a ? i in a : e.isFunction(e.fn[i]))) ? e(t)[i](r) : ("type" === i && r !== n && u.test(t.nodeName) && t.parentNode && o("Can't change the 'type' of an input or button in IE 6/7/8"), !e.attrHooks[c] && p.test(c) && (e.attrHooks[c] = {
                get: function(t, o) {
                    var i, r = e.prop(t, o);
                    return !0 === r || "boolean" != typeof r && (i = t.getAttributeNode(o)) && !1 !== i.nodeValue ? o.toLowerCase() : n
                },
                set: function(t, n, o) {
                    var i;
                    return !1 === n ? e.removeAttr(t, o) : ((i = e.propFix[o] || o) in t && (t[i] = !0), t.setAttribute(o, o.toLowerCase())), o
                }
            }, f.test(c) && o("jQuery.fn.attr('" + c + "') may use property instead of attribute")), s.call(e, t, i, r))
        }, e.attrHooks.value = {
            get: function(e, t) {
                var n = (e.nodeName || "").toLowerCase();
                return "button" === n ? l.apply(this, arguments) : ("input" !== n && "option" !== n && o("jQuery.fn.attr('value') no longer gets properties"), t in e ? e.value : null)
            },
            set: function(e, t) {
                var i = (e.nodeName || "").toLowerCase();
                return "button" === i ? c.apply(this, arguments) : ("input" !== i && "option" !== i && o("jQuery.fn.attr('value', val) no longer sets properties"), e.value = t, n)
            }
        };
        var h, m, g = e.fn.init,
            v = e.parseJSON,
            y = /^([^<]*)(<[\w\W]+>)([^>]*)$/;
        e.fn.init = function(t, n, i) {
            var r;
            return t && "string" == typeof t && !e.isPlainObject(n) && (r = y.exec(e.trim(t))) && r[0] && ("<" !== t.charAt(0) && o("$(html) HTML strings must start with '<' character"), r[3] && o("$(html) HTML text after last tag is ignored"), "#" === r[0].charAt(0) && (o("HTML string cannot start with a '#' character"), e.error("JQMIGRATE: Invalid selector string (XSS)")), n && n.context && (n = n.context), e.parseHTML) ? g.call(this, e.parseHTML(r[2], n, !0), n, i) : g.apply(this, arguments)
        }, e.fn.init.prototype = e.fn, e.parseJSON = function(e) {
            return e || null === e ? v.apply(this, arguments) : (o("jQuery.parseJSON requires a valid JSON string"), null)
        }, e.uaMatch = function(e) {
            e = e.toLowerCase();
            var t = /(chrome)[ \/]([\w.]+)/.exec(e) || /(webkit)[ \/]([\w.]+)/.exec(e) || /(opera)(?:.*version|)[ \/]([\w.]+)/.exec(e) || /(msie) ([\w.]+)/.exec(e) || e.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec(e) || [];
            return {
                browser: t[1] || "",
                version: t[2] || "0"
            }
        }, e.browser || (m = {}, (h = e.uaMatch(navigator.userAgent)).browser && (m[h.browser] = !0, m.version = h.version), m.chrome ? m.webkit = !0 : m.webkit && (m.safari = !0), e.browser = m), i(e, "browser", e.browser, "jQuery.browser is deprecated"), e.sub = function() {
            function t(e, n) {
                return new t.fn.init(e, n)
            }
            e.extend(!0, t, this), t.superclass = this, ((t.fn = t.prototype = this()).constructor = t).sub = this.sub, t.fn.init = function(o, i) {
                return i && i instanceof e && !(i instanceof t) && (i = t(i)), e.fn.init.call(this, o, i, n)
            }, t.fn.init.prototype = t.fn;
            var n = t(document);
            return o("jQuery.sub() is deprecated"), t
        }, e.ajaxSetup({
            converters: {
                "text json": e.parseJSON
            }
        });
        var b = e.fn.data;
        e.fn.data = function(t) {
            var i, r, a = this[0];
            return !a || "events" !== t || 1 !== arguments.length || (i = e.data(a, t), r = e._data(a, t), i !== n && i !== r || r === n) ? b.apply(this, arguments) : (o("Use of jQuery.fn.data('events') is deprecated"), r)
        };
        var x = /\/(java|ecma)script/i,
            w = e.fn.andSelf || e.fn.addBack;
        e.fn.andSelf = function() {
            return o("jQuery.fn.andSelf() replaced by jQuery.fn.addBack()"), w.apply(this, arguments)
        }, e.clean || (e.clean = function(t, i, r, a) {
            i = (i = !(i = i || document).nodeType && i[0] || i).ownerDocument || i, o("jQuery.clean() is deprecated");
            var s, l, c, u, d = [];
            if (e.merge(d, e.buildFragment(t, i).childNodes), r)
                for (c = function(e) {
                        return !e.type || x.test(e.type) ? a ? a.push(e.parentNode ? e.parentNode.removeChild(e) : e) : r.appendChild(e) : n
                    }, s = 0; null != (l = d[s]); s++) e.nodeName(l, "script") && c(l) || (r.appendChild(l), l.getElementsByTagName !== n && (u = e.grep(e.merge([], l.getElementsByTagName("script")), c), d.splice.apply(d, [s + 1, 0].concat(u)), s += u.length));
            return d
        });
        var C = e.event.add,
            k = e.event.remove,
            T = e.event.trigger,
            $ = e.fn.toggle,
            E = e.fn.live,
            S = e.fn.die,
            N = "ajaxStart|ajaxStop|ajaxSend|ajaxComplete|ajaxError|ajaxSuccess",
            B = RegExp("\\b(?:" + N + ")\\b"),
            _ = /(?:^|\s)hover(\.\S+|)\b/,
            A = function(t) {
                return "string" != typeof t || e.event.special.hover ? t : (_.test(t) && o("'hover' pseudo-event is deprecated, use 'mouseenter mouseleave'"), t && t.replace(_, "mouseenter$1 mouseleave$1"))
            };
        e.event.props && "attrChange" !== e.event.props[0] && e.event.props.unshift("attrChange", "attrName", "relatedNode", "srcElement"), e.event.dispatch && i(e.event, "handle", e.event.dispatch, "jQuery.event.handle is undocumented and deprecated"), e.event.add = function(e, t, n, i, r) {
            e !== document && B.test(t) && o("AJAX events should be attached to document: " + t), C.call(this, e, A(t || ""), n, i, r)
        }, e.event.remove = function(e, t, n, o, i) {
            k.call(this, e, A(t) || "", n, o, i)
        }, e.fn.error = function() {
            var e = Array.prototype.slice.call(arguments, 0);
            return o("jQuery.fn.error() is deprecated"), e.splice(0, 0, "error"), arguments.length ? this.bind.apply(this, e) : (this.triggerHandler.apply(this, e), this)
        }, e.fn.toggle = function(t, n) {
            if (!e.isFunction(t) || !e.isFunction(n)) return $.apply(this, arguments);
            o("jQuery.fn.toggle(handler, handler...) is deprecated");
            var i = arguments,
                r = t.guid || e.guid++,
                a = 0,
                s = function(n) {
                    var o = (e._data(this, "lastToggle" + t.guid) || 0) % a;
                    return e._data(this, "lastToggle" + t.guid, o + 1), n.preventDefault(), i[o].apply(this, arguments) || !1
                };
            for (s.guid = r; i.length > a;) i[a++].guid = r;
            return this.click(s)
        }, e.fn.live = function(t, n, i) {
            return o("jQuery.fn.live() is deprecated"), E ? E.apply(this, arguments) : (e(this.context).on(t, this.selector, n, i), this)
        }, e.fn.die = function(t, n) {
            return o("jQuery.fn.die() is deprecated"), S ? S.apply(this, arguments) : (e(this.context).off(t, this.selector || "**", n), this)
        }, e.event.trigger = function(e, t, n, i) {
            return n || B.test(e) || o("Global events are undocumented and deprecated"), T.call(this, e, t, n || document, i)
        }, e.each(N.split("|"), function(t, n) {
            e.event.special[n] = {
                setup: function() {
                    var t = this;
                    return t !== document && (e.event.add(document, n + "." + e.guid, function() {
                        e.event.trigger(n, null, t, !0)
                    }), e._data(this, n, e.guid++)), !1
                },
                teardown: function() {
                    return this !== document && e.event.remove(document, n + "." + e._data(this, n)), !1
                }
            }
        })
    }(jQuery, window), jQuery.easing.jswing = jQuery.easing.swing, jQuery.extend(jQuery.easing, {
        def: "easeOutQuad",
        swing: function(e, t, n, o, i) {
            return jQuery.easing[jQuery.easing.def](e, t, n, o, i)
        },
        easeInQuad: function(e, t, n, o, i) {
            return o * (t /= i) * t + n
        },
        easeOutQuad: function(e, t, n, o, i) {
            return -o * (t /= i) * (t - 2) + n
        },
        easeInOutQuad: function(e, t, n, o, i) {
            return (t /= i / 2) < 1 ? o / 2 * t * t + n : -o / 2 * (--t * (t - 2) - 1) + n
        },
        easeInCubic: function(e, t, n, o, i) {
            return o * (t /= i) * t * t + n
        },
        easeOutCubic: function(e, t, n, o, i) {
            return o * ((t = t / i - 1) * t * t + 1) + n
        },
        easeInOutCubic: function(e, t, n, o, i) {
            return (t /= i / 2) < 1 ? o / 2 * t * t * t + n : o / 2 * ((t -= 2) * t * t + 2) + n
        },
        easeInQuart: function(e, t, n, o, i) {
            return o * (t /= i) * t * t * t + n
        },
        easeOutQuart: function(e, t, n, o, i) {
            return -o * ((t = t / i - 1) * t * t * t - 1) + n
        },
        easeInOutQuart: function(e, t, n, o, i) {
            return (t /= i / 2) < 1 ? o / 2 * t * t * t * t + n : -o / 2 * ((t -= 2) * t * t * t - 2) + n
        },
        easeInQuint: function(e, t, n, o, i) {
            return o * (t /= i) * t * t * t * t + n
        },
        easeOutQuint: function(e, t, n, o, i) {
            return o * ((t = t / i - 1) * t * t * t * t + 1) + n
        },
        easeInOutQuint: function(e, t, n, o, i) {
            return (t /= i / 2) < 1 ? o / 2 * t * t * t * t * t + n : o / 2 * ((t -= 2) * t * t * t * t + 2) + n
        },
        easeInSine: function(e, t, n, o, i) {
            return -o * Math.cos(t / i * (Math.PI / 2)) + o + n
        },
        easeOutSine: function(e, t, n, o, i) {
            return o * Math.sin(t / i * (Math.PI / 2)) + n
        },
        easeInOutSine: function(e, t, n, o, i) {
            return -o / 2 * (Math.cos(Math.PI * t / i) - 1) + n
        },
        easeInExpo: function(e, t, n, o, i) {
            return 0 == t ? n : o * Math.pow(2, 10 * (t / i - 1)) + n
        },
        easeOutExpo: function(e, t, n, o, i) {
            return t == i ? n + o : o * (1 - Math.pow(2, -10 * t / i)) + n
        },
        easeInOutExpo: function(e, t, n, o, i) {
            return 0 == t ? n : t == i ? n + o : (t /= i / 2) < 1 ? o / 2 * Math.pow(2, 10 * (t - 1)) + n : o / 2 * (2 - Math.pow(2, -10 * --t)) + n
        },
        easeInCirc: function(e, t, n, o, i) {
            return -o * (Math.sqrt(1 - (t /= i) * t) - 1) + n
        },
        easeOutCirc: function(e, t, n, o, i) {
            return o * Math.sqrt(1 - (t = t / i - 1) * t) + n
        },
        easeInOutCirc: function(e, t, n, o, i) {
            return (t /= i / 2) < 1 ? -o / 2 * (Math.sqrt(1 - t * t) - 1) + n : o / 2 * (Math.sqrt(1 - (t -= 2) * t) + 1) + n
        },
        easeInElastic: function(e, t, n, o, i) {
            var r = 1.70158,
                a = 0,
                s = o;
            return 0 == t ? n : 1 == (t /= i) ? n + o : (a || (a = .3 * i), s < Math.abs(o) ? (s = o, r = a / 4) : r = a / (2 * Math.PI) * Math.asin(o / s), -s * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * i - r) * (2 * Math.PI) / a) + n)
        },
        easeOutElastic: function(e, t, n, o, i) {
            var r = 1.70158,
                a = 0,
                s = o;
            return 0 == t ? n : 1 == (t /= i) ? n + o : (a || (a = .3 * i), s < Math.abs(o) ? (s = o, r = a / 4) : r = a / (2 * Math.PI) * Math.asin(o / s), s * Math.pow(2, -10 * t) * Math.sin((t * i - r) * (2 * Math.PI) / a) + o + n)
        },
        easeInOutElastic: function(e, t, n, o, i) {
            var r = 1.70158,
                a = 0,
                s = o;
            return 0 == t ? n : 2 == (t /= i / 2) ? n + o : (a || (a = i * (.3 * 1.5)), s < Math.abs(o) ? (s = o, r = a / 4) : r = a / (2 * Math.PI) * Math.asin(o / s), t < 1 ? s * Math.pow(2, 10 * (t -= 1)) * Math.sin((t * i - r) * (2 * Math.PI) / a) * -.5 + n : s * Math.pow(2, -10 * (t -= 1)) * Math.sin((t * i - r) * (2 * Math.PI) / a) * .5 + o + n)
        },
        easeInBack: function(e, t, n, o, i, r) {
            return null == r && (r = 1.70158), o * (t /= i) * t * ((r + 1) * t - r) + n
        },
        easeOutBack: function(e, t, n, o, i, r) {
            return null == r && (r = 1.70158), o * ((t = t / i - 1) * t * ((r + 1) * t + r) + 1) + n
        },
        easeInOutBack: function(e, t, n, o, i, r) {
            return null == r && (r = 1.70158), (t /= i / 2) < 1 ? o / 2 * (t * t * ((1 + (r *= 1.525)) * t - r)) + n : o / 2 * ((t -= 2) * t * ((1 + (r *= 1.525)) * t + r) + 2) + n
        },
        easeInBounce: function(e, t, n, o, i) {
            return o - jQuery.easing.easeOutBounce(e, i - t, 0, o, i) + n
        },
        easeOutBounce: function(e, t, n, o, i) {
            return (t /= i) < 1 / 2.75 ? o * (7.5625 * t * t) + n : t < 2 / 2.75 ? o * (7.5625 * (t -= 1.5 / 2.75) * t + .75) + n : t < 2.5 / 2.75 ? o * (7.5625 * (t -= 2.25 / 2.75) * t + .9375) + n : o * (7.5625 * (t -= 2.625 / 2.75) * t + .984375) + n
        },
        easeInOutBounce: function(e, t, n, o, i) {
            return t < i / 2 ? .5 * jQuery.easing.easeInBounce(e, 2 * t, 0, o, i) + n : .5 * jQuery.easing.easeOutBounce(e, 2 * t - i, 0, o, i) + .5 * o + n
        }
    }), $(document).ready(function() {
        $("form").submit(function() {
            $(this).find(".hideOnSubmit").hide()
        }), $.fn.checkboxChange = function(e, t) {
            $(this).prop("checked") && e ? e.call(this) : t && t.call(this), $(this).attr("eventCheckboxChange") || ($(this).on("change", function() {
                $(this).checkboxChange(e, t)
            }), $(this).attr("eventCheckboxChange", !0))
        }, $("a._blank, a.js-new-window").attr("target", "_blank")
    });
var responsiveflag = !1;

function highdpiInit() {
    if ("1px" == $(".replace-2x").css("font-size"))
        for (var e = $("img.replace-2x").get(), t = 0; t < e.length; t++) {
            src = e[t].src, extension = src.substr(src.lastIndexOf(".") + 1), src = src.replace("." + extension, "2x." + extension);
            var n = new Image;
            n.src = src, 0 != n.height ? e[t].src = src : e[t].src = e[t].src
        }
}

function scrollCompensate() {
    var e = document.createElement("p");
    e.style.width = "100%", e.style.height = "200px";
    var t = document.createElement("div");
    t.style.position = "absolute", t.style.top = "0px", t.style.left = "0px", t.style.visibility = "hidden", t.style.width = "200px", t.style.height = "150px", t.style.overflow = "hidden", t.appendChild(e), document.body.appendChild(t);
    var n = e.offsetWidth;
    t.style.overflow = "scroll";
    var o = e.offsetWidth;
    return n == o && (o = t.clientWidth), document.body.removeChild(t), n - o
}

function responsiveResize() {
    compensante = scrollCompensate(), $(window).width() + scrollCompensate() <= 767 && 0 == responsiveflag ? (accordion("enable"), accordionFooter("enable"), responsiveflag = !0) : 768 <= $(window).width() + scrollCompensate() && (accordion("disable"), accordionFooter("disable"), responsiveflag = !1), blockHover()
}

function blockHover(e) {
    var t = 1170 == $("body").find(".container").width();
    $(".product_list").is(".grid") && (t ? $(".product_list .button-container").hide() : $(".product_list .button-container").show()), $(document).off("mouseenter").on("mouseenter", ".product_list.grid li.ajax_block_product .product-container", function(e) {
        if (t) {
            var n = $(this).parent().outerHeight(),
                o = $(this).parent().find(".button-container").outerHeight() + $(this).parent().find(".comments_note").outerHeight() + $(this).parent().find(".functional-buttons").outerHeight();
            $(this).parent().addClass("hovered").css({
                height: n + o,
                "margin-bottom": -1 * o
            }), $(this).find(".button-container").show()
        }
    }), $(document).off("mouseleave").on("mouseleave", ".product_list.grid li.ajax_block_product .product-container", function(e) {
        t && ($(this).parent().removeClass("hovered").css({
            height: "auto",
            "margin-bottom": "0"
        }), $(this).find(".button-container").hide())
    })
}

function quick_view() {
    $(document).on("click", ".quick-view:visible, .quick-view-mobile:visible", function(e) {
        e.preventDefault();
        var t = this.rel,
            n = ""; - 1 != t.indexOf("#") && (n = t.substring(t.indexOf("#"), t.length), t = t.substring(0, t.indexOf("#"))), -1 != t.indexOf("?") ? t += "&" : t += "?", $.prototype.fancybox && $.fancybox({
            padding: 0,
            width: 1087,
            height: 610,
            type: "iframe",
            href: t + "content_only=1" + n
        })
    })
}

function dropDown() {
    elementClick = "#header .current", elementSlide = "ul.toogle_content", activeClass = "active", $(elementClick).on("click", function(e) {
        e.stopPropagation();
        var t = $(this).next(elementSlide);
        t.is(":hidden") ? (t.slideDown(), $(this).addClass(activeClass)) : (t.slideUp(), $(this).removeClass(activeClass)), $(elementClick).not(this).next(elementSlide).slideUp(), $(elementClick).not(this).removeClass(activeClass), e.preventDefault()
    }), $(elementSlide).on("click", function(e) {
        e.stopPropagation()
    }), $(document).on("click", function(e) {
        e.stopPropagation();
        var t = $(elementClick).next(elementSlide);
        $(t).slideUp(), $(elementClick).removeClass("active")
    })
}

function accordionFooter(e) {
    "enable" == e ? ($(".prefooter-blocks .block_footer h4").on("click", function() {
        $(this).toggleClass("active").parent().find(".toggle-footer").stop().slideToggle("medium")
    }), $(".prefooter-blocks").addClass("accordion").find(".toggle-footer").slideUp("fast")) : ($(".block_footer h4").removeClass("active").off().parent().find(".toggle-footer").removeAttr("style").slideDown("fast"), $(".prefooter-blocks").removeClass("accordion"))
}

function accordion(e) {
    leftColumnBlocks = $(".sidebar"), "enable" == e ? ($(".sidebar div.heading_block h4").on("click", function(e) {
        $(this).toggleClass("active").parent().parent().find(".block_content").stop().slideToggle("medium")
    }), $(".sidebar").addClass("accordion").find(".block .block_content").slideUp("fast"), "undefined" != typeof ajaxCart && ajaxCart.collapse()) : ($(".sidebar div.heading_block h4").removeClass("active").off().parent().parent().find(".block_content").removeAttr("style").slideDown("fast"), $(".sidebar").removeClass("accordion"))
}
var contentWrapper = $("div.content"),
    searchBox = $("#search_block_top i"),
    searchBoxInput = $('#search_block_top input[type="submit"]'),
    sliderRange = $("#slider-range"),
    gridViewContainer = $("#gridview"),
    listViewContainer = $("#listview"),
    qtyWantedInput = $("#quantity_wanted"),
    defaultSlider = $(".slider"),
    miniSliderDiv = $(".mini_slider"),
    productListContainer = $(".product_list_ph");

function get_grid() {
    gridViewContainer.addClass("active"), listViewContainer.removeClass("active"), productListContainer.removeClass("list"), productListContainer.addClass("grid")
}

function get_list() {
    listViewContainer.addClass("active"), gridViewContainer.removeClass("active"), productListContainer.removeClass("grid"), productListContainer.addClass("list")
}

function hideDD() {
    $(".topbar .select-options").removeClass("active")
}

function changeSelectedLink(e) {
    e.parents(".portfolio-filter").find(".btn-primary").removeClass("btn-primary"), e.addClass("btn-primary")
}

function installCarousels() {
    $(".owl-carousel-ph").each(function() {
        if (0 == $(this).parents(".tab-pane").length) {
            var e = $(this).data("max-items");
            $(this).owlCarousel({
                autoplay: autoplayInfo,
                autoplayTimeout: autoplay_speed,
                autoplayHoverPause: !0,
                items: e,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    991: {
                        items: 4
                    },
                    1199: {
                        items: e
                    }
                },
                pagination: !1
            });
            var t = $(this).data("owlCarousel");
            $(this).parents(".carousel-style").find(".arrow-prev").on("click", function(e) {
                t.prev(), e.preventDefault()
            }), $(this).parents(".carousel-style").find(".arrow-next").on("click", function(e) {
                t.next(), e.preventDefault()
            })
        }
    })
}

function installCarouselForTab(e) {
    var t = $("#tab" + e).find(".owl-carousel-ph"),
        n = t.data("max-items"),
        o = n - 1,
        i = n - 1;
    t.owlCarousel({
        autoplay: autoplayInfo,
        autoplayTimeout: autoplay_speed,
        autoplayHoverPause: !0,
        items: n,
        responsive: {
            0: {
                items: 1
            },
            768: {
                items: i
            },
            991: {
                items: o
            },
            1199: {
                items: n
            }
        },
        pagination: !1
    }), t.trigger("refresh.owl.carousel"), $("#tab" + e).find(".arrow-prev").on("click", function(e) {
        t.trigger("prev.owl.carousel"), e.preventDefault()
    }), $("#tab" + e).find(".arrow-next").on("click", function(e) {
        t.trigger("next.owl.carousel"), e.preventDefault()
    })
}
if (jQuery(document).ready(function(e) {
        if (e("body.cms div.rte li").wrapInner("<span/>"), e(".top-sticky").affix({}), e("#ph_megamenu > li").on({
                mouseenter: function() {
                    contentWrapper.addClass("blur")
                },
                mouseleave: function() {
                    contentWrapper.removeClass("blur")
                }
            }), e(".topbar .select-options").on("click", function(t) {
                e(".topbar .select-options").not(this).removeClass("active"), "options" == e(t.target).parent().parent().attr("class") ? hideDD() : e(this).hasClass("active") && e(t.target).is("p") ? hideDD() : e(this).toggleClass("active"), t.stopPropagation()
            }), e(document).on("click", function() {
                hideDD()
            }), e(".topbar .select-options ul").on("click", function() {
                var t = e(this).text();
                e(".topbar .select-options.active p").text(t), hideDD()
            }), searchBox.on("click", function() {
                searchBoxInput.trigger("click")
            }), searchBox.on("mouseenter", function() {
                searchBoxInput.addClass("hover")
            }), searchBox.on("mouseleave", function() {
                searchBoxInput.removeClass("hover")
            }), e(".shopping_cart").on({
                mouseenter: function() {
                    e(this).find("a.cart-contents").addClass("active")
                },
                mouseleave: function() {
                    e(this).find("a.cart-contents").removeClass("active")
                }
            }), e("#categories_block_left.simple ul li").on("mouseenter", function() {
                var t = e(this).parent().width();
                e(this).addClass("active"), e(".sidebar").hasClass("right-column") ? e(this).hover().find("ul").first().css({
                    width: t,
                    right: +t
                }).show() : e(this).hover().find("ul").first().css({
                    width: t,
                    right: -t
                }).show()
            }), e("#categories_block_left.simple ul li").mouseleave(function() {
                e(this).removeClass("active"), e(this).find("ul").first().css({
                    right: "0"
                }).hide()
            }), 0 < e(".list-style-buttons").length) {
            var t = e("a.switcher");
            t.attr("id"), t.attr("class").split(" "), "undefined" !== e.cookie("view") && e.cookie("view", "grid", {
                expires: 7,
                path: "/"
            }), "list" == e.cookie("view") && get_list(), "grid" == e.cookie("view") && get_grid(), listViewContainer.on("click", function(t) {
                e.cookie("view", "list"), get_list(), t.preventDefault()
            }), gridViewContainer.on("click", function(t) {
                e.cookie("view", "grid"), get_grid(), t.preventDefault()
            })
        }
        var n;
        0 == e(".contact-form-box").length && e("[data-toggle='tooltip']").tooltip(), e(".form-date select, .date-select select").selectBox({
            menuTransition: "slide",
            menuSpeed: "fast",
            keepInViewport: !1
        }), 0 < e('input[type="file"]').length && e(":file").filestyle({
            buttonName: "button",
            icon: !1
        }), e().appear && (1 == e.browser.mobile ? e("body").removeClass("cssAnimate") : (e(".cssAnimate .animated").appear(function() {
            var e = jQuery(this);
            e.each(function() {
                null != e.data("time") ? n = setTimeout(function() {
                    e.addClass("activate"), e.addClass(e.data("fx"))
                }, e.data("time")) : (e.addClass("activate"), e.addClass(e.data("fx")))
            })
        }, {
            accX: 50,
            accY: -150
        }), clearTimeout(n))), e("#quantity_wanted_p a.more").on("click", function(t) {
            t.preventDefault(), fieldName = e(this).attr("field");
            var n = parseInt(qtyWantedInput.val(), 10);
            isNaN(n) ? qtyWantedInput.val(0) : qtyWantedInput.val(n + 1)
        }), e("#quantity_wanted_p a.less").on("click", function(t) {
            t.preventDefault(), fieldName = e(this).attr("field");
            var n = parseInt(qtyWantedInput.val(), 10);
            !isNaN(n) && 0 < n ? qtyWantedInput.val(n - 1) : qtyWantedInput.val(0)
        })
    }), "undefined" == typeof jQuery) throw new Error("Bootstrap's JavaScript requires jQuery");

function openBranch(e, t) {
    e.addClass("OPEN").removeClass("CLOSE"), t ? e.parent().find("ul:first").show() : e.parent().find("ul:first").slideDown()
}

function closeBranch(e, t) {
    e.addClass("CLOSE").removeClass("OPEN"), t ? e.parent().find("ul:first").hide() : e.parent().find("ul:first").slideUp()
}

function equalHeights() {}

function toggleBranch(e, t) {
    e.hasClass("OPEN") ? closeBranch(e, t) : openBranch(e, t)
}! function(e) {
    var t = jQuery.fn.jquery.split(" ")[0].split(".");
    if (t[0] < 2 && t[1] < 9 || 1 == t[0] && 9 == t[1] && t[2] < 1) throw new Error("Bootstrap's JavaScript requires jQuery version 1.9.1 or higher")
}(),
function(e) {
    "use strict";
    e.fn.emulateTransitionEnd = function(t) {
        var n = !1,
            o = this;
        return e(this).one("bsTransitionEnd", function() {
            n = !0
        }), setTimeout(function() {
            n || e(o).trigger(e.support.transition.end)
        }, t), this
    }, e(function() {
        e.support.transition = function() {
            var e = document.createElement("bootstrap"),
                t = {
                    WebkitTransition: "webkitTransitionEnd",
                    MozTransition: "transitionend",
                    OTransition: "oTransitionEnd otransitionend",
                    transition: "transitionend"
                };
            for (var n in t)
                if (void 0 !== e.style[n]) return {
                    end: t[n]
                };
            return !1
        }(), e.support.transition && (e.event.special.bsTransitionEnd = {
            bindType: e.support.transition.end,
            delegateType: e.support.transition.end,
            handle: function(t) {
                return e(t.target).is(this) ? t.handleObj.handler.apply(this, arguments) : void 0
            }
        })
    })
}(jQuery),
function(e) {
    "use strict";
    var t = '[data-dismiss="alert"]',
        n = function(n) {
            e(n).on("click", t, this.close)
        };
    n.VERSION = "3.3.1", n.TRANSITION_DURATION = 150, n.prototype.close = function(t) {
        function o() {
            a.detach().trigger("closed.bs.alert").remove()
        }
        var i = e(this),
            r = i.attr("data-target");
        r || (r = (r = i.attr("href")) && r.replace(/.*(?=#[^\s]*$)/, ""));
        var a = e(r);
        t && t.preventDefault(), a.length || (a = i.closest(".alert")), a.trigger(t = e.Event("close.bs.alert")), t.isDefaultPrevented() || (a.removeClass("in"), e.support.transition && a.hasClass("fade") ? a.one("bsTransitionEnd", o).emulateTransitionEnd(n.TRANSITION_DURATION) : o())
    };
    var o = e.fn.alert;
    e.fn.alert = function(t) {
        return this.each(function() {
            var o = e(this),
                i = o.data("bs.alert");
            i || o.data("bs.alert", i = new n(this)), "string" == typeof t && i[t].call(o)
        })
    }, e.fn.alert.Constructor = n, e.fn.alert.noConflict = function() {
        return e.fn.alert = o, this
    }, e(document).on("click.bs.alert.data-api", t, n.prototype.close)
}(jQuery),
function(e) {
    "use strict";

    function t(t) {
        return this.each(function() {
            var o = e(this),
                i = o.data("bs.button"),
                r = "object" == typeof t && t;
            i || o.data("bs.button", i = new n(this, r)), "toggle" == t ? i.toggle() : t && i.setState(t)
        })
    }
    var n = function(t, o) {
        this.$element = e(t), this.options = e.extend({}, n.DEFAULTS, o), this.isLoading = !1
    };
    n.VERSION = "3.3.1", n.DEFAULTS = {
        loadingText: "loading..."
    }, n.prototype.setState = function(t) {
        var n = "disabled",
            o = this.$element,
            i = o.is("input") ? "val" : "html",
            r = o.data();
        t += "Text", null == r.resetText && o.data("resetText", o[i]()), setTimeout(e.proxy(function() {
            o[i](null == r[t] ? this.options[t] : r[t]), "loadingText" == t ? (this.isLoading = !0, o.addClass(n).attr(n, n)) : this.isLoading && (this.isLoading = !1, o.removeClass(n).removeAttr(n))
        }, this), 0)
    }, n.prototype.toggle = function() {
        var e = !0,
            t = this.$element.closest('[data-toggle="buttons"]');
        if (t.length) {
            var n = this.$element.find("input");
            "radio" == n.prop("type") && (n.prop("checked") && this.$element.hasClass("active") ? e = !1 : t.find(".active").removeClass("active")), e && n.prop("checked", !this.$element.hasClass("active")).trigger("change")
        } else this.$element.attr("aria-pressed", !this.$element.hasClass("active"));
        e && this.$element.toggleClass("active")
    };
    var o = e.fn.button;
    e.fn.button = t, e.fn.button.Constructor = n, e.fn.button.noConflict = function() {
        return e.fn.button = o, this
    }, e(document).on("click.bs.button.data-api", '[data-toggle^="button"]', function(n) {
        var o = e(n.target);
        o.hasClass("btn") || (o = o.closest(".btn")), t.call(o, "toggle"), n.preventDefault()
    }).on("focus.bs.button.data-api blur.bs.button.data-api", '[data-toggle^="button"]', function(t) {
        e(t.target).closest(".btn").toggleClass("focus", /^focus(in)?$/.test(t.type))
    })
}(jQuery),
function(e) {
    "use strict";

    function t(t) {
        return this.each(function() {
            var o = e(this),
                i = o.data("bs.carousel"),
                r = e.extend({}, n.DEFAULTS, o.data(), "object" == typeof t && t),
                a = "string" == typeof t ? t : r.slide;
            i || o.data("bs.carousel", i = new n(this, r)), "number" == typeof t ? i.to(t) : a ? i[a]() : r.interval && i.pause().cycle()
        })
    }
    var n = function(t, n) {
        this.$element = e(t), this.$indicators = this.$element.find(".carousel-indicators"), this.options = n, this.paused = this.sliding = this.interval = this.$active = this.$items = null, this.options.keyboard && this.$element.on("keydown.bs.carousel", e.proxy(this.keydown, this)), "hover" == this.options.pause && !("ontouchstart" in document.documentElement) && this.$element.on("mouseenter.bs.carousel", e.proxy(this.pause, this)).on("mouseleave.bs.carousel", e.proxy(this.cycle, this))
    };
    n.VERSION = "3.3.1", n.TRANSITION_DURATION = 600, n.DEFAULTS = {
        interval: 5e3,
        pause: "hover",
        wrap: !0,
        keyboard: !0
    }, n.prototype.keydown = function(e) {
        if (!/input|textarea/i.test(e.target.tagName)) {
            switch (e.which) {
                case 37:
                    this.prev();
                    break;
                case 39:
                    this.next();
                    break;
                default:
                    return
            }
            e.preventDefault()
        }
    }, n.prototype.cycle = function(t) {
        return t || (this.paused = !1), this.interval && clearInterval(this.interval), this.options.interval && !this.paused && (this.interval = setInterval(e.proxy(this.next, this), this.options.interval)), this
    }, n.prototype.getItemIndex = function(e) {
        return this.$items = e.parent().children(".item"), this.$items.index(e || this.$active)
    }, n.prototype.getItemForDirection = function(e, t) {
        var n = "prev" == e ? -1 : 1,
            o = (this.getItemIndex(t) + n) % this.$items.length;
        return this.$items.eq(o)
    }, n.prototype.to = function(e) {
        var t = this,
            n = this.getItemIndex(this.$active = this.$element.find(".item.active"));
        return e > this.$items.length - 1 || e < 0 ? void 0 : this.sliding ? this.$element.one("slid.bs.carousel", function() {
            t.to(e)
        }) : n == e ? this.pause().cycle() : this.slide(n < e ? "next" : "prev", this.$items.eq(e))
    }, n.prototype.pause = function(t) {
        return t || (this.paused = !0), this.$element.find(".next, .prev").length && e.support.transition && (this.$element.trigger(e.support.transition.end), this.cycle(!0)), this.interval = clearInterval(this.interval), this
    }, n.prototype.next = function() {
        return this.sliding ? void 0 : this.slide("next")
    }, n.prototype.prev = function() {
        return this.sliding ? void 0 : this.slide("prev")
    }, n.prototype.slide = function(t, o) {
        var i = this.$element.find(".item.active"),
            r = o || this.getItemForDirection(t, i),
            a = this.interval,
            s = "next" == t ? "left" : "right",
            l = "next" == t ? "first" : "last",
            c = this;
        if (!r.length) {
            if (!this.options.wrap) return;
            r = this.$element.find(".item")[l]()
        }
        if (r.hasClass("active")) return this.sliding = !1;
        var u = r[0],
            d = e.Event("slide.bs.carousel", {
                relatedTarget: u,
                direction: s
            });
        if (this.$element.trigger(d), !d.isDefaultPrevented()) {
            if (this.sliding = !0, a && this.pause(), this.$indicators.length) {
                this.$indicators.find(".active").removeClass("active");
                var p = e(this.$indicators.children()[this.getItemIndex(r)]);
                p && p.addClass("active")
            }
            var f = e.Event("slid.bs.carousel", {
                relatedTarget: u,
                direction: s
            });
            return e.support.transition && this.$element.hasClass("slide") ? (r.addClass(t), r[0].offsetWidth, i.addClass(s), r.addClass(s), i.one("bsTransitionEnd", function() {
                r.removeClass([t, s].join(" ")).addClass("active"), i.removeClass(["active", s].join(" ")), c.sliding = !1, setTimeout(function() {
                    c.$element.trigger(f)
                }, 0)
            }).emulateTransitionEnd(n.TRANSITION_DURATION)) : (i.removeClass("active"), r.addClass("active"), this.sliding = !1, this.$element.trigger(f)), a && this.cycle(), this
        }
    };
    var o = e.fn.carousel;
    e.fn.carousel = t, e.fn.carousel.Constructor = n, e.fn.carousel.noConflict = function() {
        return e.fn.carousel = o, this
    };
    var i = function(n) {
        var o, i = e(this),
            r = e(i.attr("data-target") || (o = i.attr("href")) && o.replace(/.*(?=#[^\s]+$)/, ""));
        if (r.hasClass("carousel")) {
            var a = e.extend({}, r.data(), i.data()),
                s = i.attr("data-slide-to");
            s && (a.interval = !1), t.call(r, a), s && r.data("bs.carousel").to(s), n.preventDefault()
        }
    };
    e(document).on("click.bs.carousel.data-api", "[data-slide]", i).on("click.bs.carousel.data-api", "[data-slide-to]", i), e(window).on("load", function() {
        e('[data-ride="carousel"]').each(function() {
            var n = e(this);
            t.call(n, n.data())
        })
    })
}(jQuery),
function(e) {
    "use strict";

    function t(t) {
        var n, o = t.attr("data-target") || (n = t.attr("href")) && n.replace(/.*(?=#[^\s]+$)/, "");
        return e(o)
    }

    function n(t) {
        return this.each(function() {
            var n = e(this),
                i = n.data("bs.collapse"),
                r = e.extend({}, o.DEFAULTS, n.data(), "object" == typeof t && t);
            !i && r.toggle && "show" == t && (r.toggle = !1), i || n.data("bs.collapse", i = new o(this, r)), "string" == typeof t && i[t]()
        })
    }
    var o = function(t, n) {
        this.$element = e(t), this.options = e.extend({}, o.DEFAULTS, n), this.$trigger = e(this.options.trigger).filter('[href="#' + t.id + '"], [data-target="#' + t.id + '"]'), this.transitioning = null, this.options.parent ? this.$parent = this.getParent() : this.addAriaAndCollapsedClass(this.$element, this.$trigger), this.options.toggle && this.toggle()
    };
    o.VERSION = "3.3.1", o.TRANSITION_DURATION = 0, o.DEFAULTS = {
        toggle: !0,
        trigger: '[data-toggle="collapse"]'
    }, o.prototype.dimension = function() {
        return this.$element.hasClass("width") ? "width" : "height"
    }, o.prototype.show = function() {
        if (!this.transitioning && !this.$element.hasClass("in")) {
            var t, i = this.$parent && this.$parent.find("> .panel").children(".in, .collapsing");
            if (!(i && i.length && (t = i.data("bs.collapse")) && t.transitioning)) {
                var r = e.Event("show.bs.collapse");
                if (this.$element.trigger(r), !r.isDefaultPrevented()) {
                    i && i.length && (n.call(i, "hide"), t || i.data("bs.collapse", null));
                    var a = this.dimension();
                    this.$element.removeClass("collapse").addClass("collapsing")[a](0).attr("aria-expanded", !0), this.$trigger.removeClass("collapsed").attr("aria-expanded", !0), this.transitioning = 1;
                    var s = function() {
                        this.$element.removeClass("collapsing").addClass("collapse in")[a](""), this.transitioning = 0, this.$element.trigger("shown.bs.collapse")
                    };
                    if (!e.support.transition) return s.call(this);
                    var l = e.camelCase(["scroll", a].join("-"));
                    this.$element.one("bsTransitionEnd", e.proxy(s, this)).emulateTransitionEnd(o.TRANSITION_DURATION)[a](this.$element[0][l])
                }
            }
        }
    }, o.prototype.hide = function() {
        if (!this.transitioning && this.$element.hasClass("in")) {
            var t = e.Event("hide.bs.collapse");
            if (this.$element.trigger(t), !t.isDefaultPrevented()) {
                var n = this.dimension();
                this.$element[n](this.$element[n]())[0].offsetHeight, this.$element.addClass("collapsing").removeClass("collapse in").attr("aria-expanded", !1), this.$trigger.addClass("collapsed").attr("aria-expanded", !1), this.transitioning = 1;
                var i = function() {
                    this.transitioning = 0, this.$element.removeClass("collapsing").addClass("collapse").trigger("hidden.bs.collapse")
                };
                return e.support.transition ? void this.$element[n](0).one("bsTransitionEnd", e.proxy(i, this)).emulateTransitionEnd(o.TRANSITION_DURATION) : i.call(this)
            }
        }
    }, o.prototype.toggle = function() {
        this[this.$element.hasClass("in") ? "hide" : "show"]()
    }, o.prototype.getParent = function() {
        return e(this.options.parent).find('[data-toggle="collapse"][data-parent="' + this.options.parent + '"]').each(e.proxy(function(n, o) {
            var i = e(o);
            this.addAriaAndCollapsedClass(t(i), i)
        }, this)).end()
    }, o.prototype.addAriaAndCollapsedClass = function(e, t) {
        var n = e.hasClass("in");
        e.attr("aria-expanded", n), t.toggleClass("collapsed", !n).attr("aria-expanded", n)
    };
    var i = e.fn.collapse;
    e.fn.collapse = n, e.fn.collapse.Constructor = o, e.fn.collapse.noConflict = function() {
        return e.fn.collapse = i, this
    }, e(document).on("click.bs.collapse.data-api", '[data-toggle="collapse"]', function(o) {
        var i = e(this);
        i.attr("data-target") || o.preventDefault();
        var r = t(i),
            a = r.data("bs.collapse") ? "toggle" : e.extend({}, i.data(), {
                trigger: this
            });
        n.call(r, a)
    })
}(jQuery),
function(e) {
    "use strict";

    function t(t) {
        t && 3 === t.which || (e(".dropdown-backdrop").remove(), e(o).each(function() {
            var o = e(this),
                i = n(o),
                r = {
                    relatedTarget: this
                };
            i.hasClass("open") && (i.trigger(t = e.Event("hide.bs.dropdown", r)), t.isDefaultPrevented() || (o.attr("aria-expanded", "false"), i.removeClass("open").trigger("hidden.bs.dropdown", r)))
        }))
    }

    function n(t) {
        var n = t.attr("data-target");
        n || (n = (n = t.attr("href")) && /#[A-Za-z]/.test(n) && n.replace(/.*(?=#[^\s]*$)/, ""));
        var o = n && e(n);
        return o && o.length ? o : t.parent()
    }
    var o = '[data-toggle="dropdown"]',
        i = function(t) {
            e(t).on("click.bs.dropdown", this.toggle)
        };
    i.VERSION = "3.3.1", i.prototype.toggle = function(o) {
        var i = e(this);
        if (!i.is(".disabled, :disabled")) {
            var r = n(i),
                a = r.hasClass("open");
            if (t(), !a) {
                "ontouchstart" in document.documentElement && !r.closest(".navbar-nav").length && e('<div class="dropdown-backdrop"/>').insertAfter(e(this)).on("click", t);
                var s = {
                    relatedTarget: this
                };
                if (r.trigger(o = e.Event("show.bs.dropdown", s)), o.isDefaultPrevented()) return;
                i.trigger("focus").attr("aria-expanded", "true"), r.toggleClass("open").trigger("shown.bs.dropdown", s)
            }
            return !1
        }
    }, i.prototype.keydown = function(t) {
        if (/(38|40|27|32)/.test(t.which) && !/input|textarea/i.test(t.target.tagName)) {
            var i = e(this);
            if (t.preventDefault(), t.stopPropagation(), !i.is(".disabled, :disabled")) {
                var r = n(i),
                    a = r.hasClass("open");
                if (!a && 27 != t.which || a && 27 == t.which) return 27 == t.which && r.find(o).trigger("focus"), i.trigger("click");
                var s = " li:not(.divider):visible a",
                    l = r.find('[role="menu"]' + s + ', [role="listbox"]' + s);
                if (l.length) {
                    var c = l.index(t.target);
                    38 == t.which && 0 < c && c--, 40 == t.which && c < l.length - 1 && c++, ~c || (c = 0), l.eq(c).trigger("focus")
                }
            }
        }
    };
    var r = e.fn.dropdown;
    e.fn.dropdown = function(t) {
        return this.each(function() {
            var n = e(this),
                o = n.data("bs.dropdown");
            o || n.data("bs.dropdown", o = new i(this)), "string" == typeof t && o[t].call(n)
        })
    }, e.fn.dropdown.Constructor = i, e.fn.dropdown.noConflict = function() {
        return e.fn.dropdown = r, this
    }, e(document).on("click.bs.dropdown.data-api", t).on("click.bs.dropdown.data-api", ".dropdown form", function(e) {
        e.stopPropagation()
    }).on("click.bs.dropdown.data-api", o, i.prototype.toggle).on("keydown.bs.dropdown.data-api", o, i.prototype.keydown).on("keydown.bs.dropdown.data-api", '[role="menu"]', i.prototype.keydown).on("keydown.bs.dropdown.data-api", '[role="listbox"]', i.prototype.keydown)
}(jQuery),
function(e) {
    "use strict";

    function t(t, o) {
        return this.each(function() {
            var i = e(this),
                r = i.data("bs.modal"),
                a = e.extend({}, n.DEFAULTS, i.data(), "object" == typeof t && t);
            r || i.data("bs.modal", r = new n(this, a)), "string" == typeof t ? r[t](o) : a.show && r.show(o)
        })
    }
    var n = function(t, n) {
        this.options = n, this.$body = e(document.body), this.$element = e(t), this.$backdrop = this.isShown = null, this.scrollbarWidth = 0, this.options.remote && this.$element.find(".modal-content").load(this.options.remote, e.proxy(function() {
            this.$element.trigger("loaded.bs.modal")
        }, this))
    };
    n.VERSION = "3.3.1", n.TRANSITION_DURATION = 300, n.BACKDROP_TRANSITION_DURATION = 150, n.DEFAULTS = {
        backdrop: !0,
        keyboard: !0,
        show: !0
    }, n.prototype.toggle = function(e) {
        return this.isShown ? this.hide() : this.show(e)
    }, n.prototype.show = function(t) {
        var o = this,
            i = e.Event("show.bs.modal", {
                relatedTarget: t
            });
        this.$element.trigger(i), this.isShown || i.isDefaultPrevented() || (this.isShown = !0, this.checkScrollbar(), this.setScrollbar(), this.$body.addClass("modal-open"), this.escape(), this.resize(), this.$element.on("click.dismiss.bs.modal", '[data-dismiss="modal"]', e.proxy(this.hide, this)), this.backdrop(function() {
            var i = e.support.transition && o.$element.hasClass("fade");
            o.$element.parent().length || o.$element.appendTo(o.$body), o.$element.show().scrollTop(0), o.options.backdrop && o.adjustBackdrop(), o.adjustDialog(), i && o.$element[0].offsetWidth, o.$element.addClass("in").attr("aria-hidden", !1), o.enforceFocus();
            var r = e.Event("shown.bs.modal", {
                relatedTarget: t
            });
            i ? o.$element.find(".modal-dialog").one("bsTransitionEnd", function() {
                o.$element.trigger("focus").trigger(r)
            }).emulateTransitionEnd(n.TRANSITION_DURATION) : o.$element.trigger("focus").trigger(r)
        }))
    }, n.prototype.hide = function(t) {
        t && t.preventDefault(), t = e.Event("hide.bs.modal"), this.$element.trigger(t), this.isShown && !t.isDefaultPrevented() && (this.isShown = !1, this.escape(), this.resize(), e(document).off("focusin.bs.modal"), this.$element.removeClass("in").attr("aria-hidden", !0).off("click.dismiss.bs.modal"), e.support.transition && this.$element.hasClass("fade") ? this.$element.one("bsTransitionEnd", e.proxy(this.hideModal, this)).emulateTransitionEnd(n.TRANSITION_DURATION) : this.hideModal())
    }, n.prototype.enforceFocus = function() {
        e(document).off("focusin.bs.modal").on("focusin.bs.modal", e.proxy(function(e) {
            this.$element[0] === e.target || this.$element.has(e.target).length || this.$element.trigger("focus")
        }, this))
    }, n.prototype.escape = function() {
        this.isShown && this.options.keyboard ? this.$element.on("keydown.dismiss.bs.modal", e.proxy(function(e) {
            27 == e.which && this.hide()
        }, this)) : this.isShown || this.$element.off("keydown.dismiss.bs.modal")
    }, n.prototype.resize = function() {
        this.isShown ? e(window).on("resize.bs.modal", e.proxy(this.handleUpdate, this)) : e(window).off("resize.bs.modal")
    }, n.prototype.hideModal = function() {
        var e = this;
        this.$element.hide(), this.backdrop(function() {
            e.$body.removeClass("modal-open"), e.resetAdjustments(), e.resetScrollbar(), e.$element.trigger("hidden.bs.modal")
        })
    }, n.prototype.removeBackdrop = function() {
        this.$backdrop && this.$backdrop.remove(), this.$backdrop = null
    }, n.prototype.backdrop = function(t) {
        var o = this,
            i = this.$element.hasClass("fade") ? "fade" : "";
        if (this.isShown && this.options.backdrop) {
            var r = e.support.transition && i;
            if (this.$backdrop = e('<div class="modal-backdrop ' + i + '" />').prependTo(this.$element).on("click.dismiss.bs.modal", e.proxy(function(e) {
                    e.target === e.currentTarget && ("static" == this.options.backdrop ? this.$element[0].focus.call(this.$element[0]) : this.hide.call(this))
                }, this)), r && this.$backdrop[0].offsetWidth, this.$backdrop.addClass("in"), !t) return;
            r ? this.$backdrop.one("bsTransitionEnd", t).emulateTransitionEnd(n.BACKDROP_TRANSITION_DURATION) : t()
        } else if (!this.isShown && this.$backdrop) {
            this.$backdrop.removeClass("in");
            var a = function() {
                o.removeBackdrop(), t && t()
            };
            e.support.transition && this.$element.hasClass("fade") ? this.$backdrop.one("bsTransitionEnd", a).emulateTransitionEnd(n.BACKDROP_TRANSITION_DURATION) : a()
        } else t && t()
    }, n.prototype.handleUpdate = function() {
        this.options.backdrop && this.adjustBackdrop(), this.adjustDialog()
    }, n.prototype.adjustBackdrop = function() {
        this.$backdrop.css("height", 0).css("height", this.$element[0].scrollHeight)
    }, n.prototype.adjustDialog = function() {
        var e = this.$element[0].scrollHeight > document.documentElement.clientHeight;
        this.$element.css({
            paddingLeft: !this.bodyIsOverflowing && e ? this.scrollbarWidth : "",
            paddingRight: this.bodyIsOverflowing && !e ? this.scrollbarWidth : ""
        })
    }, n.prototype.resetAdjustments = function() {
        this.$element.css({
            paddingLeft: "",
            paddingRight: ""
        })
    }, n.prototype.checkScrollbar = function() {
        this.bodyIsOverflowing = document.body.scrollHeight > document.documentElement.clientHeight, this.scrollbarWidth = this.measureScrollbar()
    }, n.prototype.setScrollbar = function() {
        var e = parseInt(this.$body.css("padding-right") || 0, 10);
        this.bodyIsOverflowing && this.$body.css("padding-right", e + this.scrollbarWidth)
    }, n.prototype.resetScrollbar = function() {
        this.$body.css("padding-right", "")
    }, n.prototype.measureScrollbar = function() {
        var e = document.createElement("div");
        e.className = "modal-scrollbar-measure", this.$body.append(e);
        var t = e.offsetWidth - e.clientWidth;
        return this.$body[0].removeChild(e), t
    };
    var o = e.fn.modal;
    e.fn.modal = t, e.fn.modal.Constructor = n, e.fn.modal.noConflict = function() {
        return e.fn.modal = o, this
    }, e(document).on("click.bs.modal.data-api", '[data-toggle="modal"]', function(n) {
        var o = e(this),
            i = o.attr("href"),
            r = e(o.attr("data-target") || i && i.replace(/.*(?=#[^\s]+$)/, "")),
            a = r.data("bs.modal") ? "toggle" : e.extend({
                remote: !/#/.test(i) && i
            }, r.data(), o.data());
        o.is("a") && n.preventDefault(), r.one("show.bs.modal", function(e) {
            e.isDefaultPrevented() || r.one("hidden.bs.modal", function() {
                o.is(":visible") && o.trigger("focus")
            })
        }), t.call(r, a, this)
    })
}(jQuery),
function(e) {
    "use strict";
    var t = function(e, t) {
        this.type = this.options = this.enabled = this.timeout = this.hoverState = this.$element = null, this.init("tooltip", e, t)
    };
    t.VERSION = "3.3.1", t.TRANSITION_DURATION = 150, t.DEFAULTS = {
        animation: !0,
        placement: "top",
        selector: !1,
        template: '<div class="tooltip" role="tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
        trigger: "hover focus",
        title: "",
        delay: 0,
        html: !1,
        container: !1,
        viewport: {
            selector: "body",
            padding: 0
        }
    }, t.prototype.init = function(t, n, o) {
        this.enabled = !0, this.type = t, this.$element = e(n), this.options = this.getOptions(o), this.$viewport = this.options.viewport && e(this.options.viewport.selector || this.options.viewport);
        for (var i = this.options.trigger.split(" "), r = i.length; r--;) {
            var a = i[r];
            if ("click" == a) this.$element.on("click." + this.type, this.options.selector, e.proxy(this.toggle, this));
            else if ("manual" != a) {
                var s = "hover" == a ? "mouseenter" : "focusin",
                    l = "hover" == a ? "mouseleave" : "focusout";
                this.$element.on(s + "." + this.type, this.options.selector, e.proxy(this.enter, this)), this.$element.on(l + "." + this.type, this.options.selector, e.proxy(this.leave, this))
            }
        }
        this.options.selector ? this._options = e.extend({}, this.options, {
            trigger: "manual",
            selector: ""
        }) : this.fixTitle()
    }, t.prototype.getDefaults = function() {
        return t.DEFAULTS
    }, t.prototype.getOptions = function(t) {
        return (t = e.extend({}, this.getDefaults(), this.$element.data(), t)).delay && "number" == typeof t.delay && (t.delay = {
            show: t.delay,
            hide: t.delay
        }), t
    }, t.prototype.getDelegateOptions = function() {
        var t = {},
            n = this.getDefaults();
        return this._options && e.each(this._options, function(e, o) {
            n[e] != o && (t[e] = o)
        }), t
    }, t.prototype.enter = function(t) {
        var n = t instanceof this.constructor ? t : e(t.currentTarget).data("bs." + this.type);
        return n && n.$tip && n.$tip.is(":visible") ? void(n.hoverState = "in") : (n || (n = new this.constructor(t.currentTarget, this.getDelegateOptions()), e(t.currentTarget).data("bs." + this.type, n)), clearTimeout(n.timeout), n.hoverState = "in", n.options.delay && n.options.delay.show ? void(n.timeout = setTimeout(function() {
            "in" == n.hoverState && n.show()
        }, n.options.delay.show)) : n.show())
    }, t.prototype.leave = function(t) {
        var n = t instanceof this.constructor ? t : e(t.currentTarget).data("bs." + this.type);
        return n || (n = new this.constructor(t.currentTarget, this.getDelegateOptions()), e(t.currentTarget).data("bs." + this.type, n)), clearTimeout(n.timeout), n.hoverState = "out", n.options.delay && n.options.delay.hide ? void(n.timeout = setTimeout(function() {
            "out" == n.hoverState && n.hide()
        }, n.options.delay.hide)) : n.hide()
    }, t.prototype.show = function() {
        var n = e.Event("show.bs." + this.type);
        if (this.hasContent() && this.enabled) {
            this.$element.trigger(n);
            var o = e.contains(this.$element[0].ownerDocument.documentElement, this.$element[0]);
            if (n.isDefaultPrevented() || !o) return;
            var i = this,
                r = this.tip(),
                a = this.getUID(this.type);
            this.setContent(), r.attr("id", a), this.$element.attr("aria-describedby", a), this.options.animation && r.addClass("fade");
            var s = "function" == typeof this.options.placement ? this.options.placement.call(this, r[0], this.$element[0]) : this.options.placement,
                l = /\s?auto?\s?/i,
                c = l.test(s);
            c && (s = s.replace(l, "") || "top"), r.detach().css({
                top: 0,
                left: 0,
                display: "block"
            }).addClass(s).data("bs." + this.type, this), this.options.container ? r.appendTo(this.options.container) : r.insertAfter(this.$element);
            var u = this.getPosition(),
                d = r[0].offsetWidth,
                p = r[0].offsetHeight;
            if (c) {
                var f = s,
                    h = this.options.container ? e(this.options.container) : this.$element.parent(),
                    m = this.getPosition(h);
                s = "bottom" == s && u.bottom + p > m.bottom ? "top" : "top" == s && u.top - p < m.top ? "bottom" : "right" == s && u.right + d > m.width ? "left" : "left" == s && u.left - d < m.left ? "right" : s, r.removeClass(f).addClass(s)
            }
            var g = this.getCalculatedOffset(s, u, d, p);
            this.applyPlacement(g, s);
            var v = function() {
                var e = i.hoverState;
                i.$element.trigger("shown.bs." + i.type), i.hoverState = null, "out" == e && i.leave(i)
            };
            e.support.transition && this.$tip.hasClass("fade") ? r.one("bsTransitionEnd", v).emulateTransitionEnd(t.TRANSITION_DURATION) : v()
        }
    }, t.prototype.applyPlacement = function(t, n) {
        var o = this.tip(),
            i = o[0].offsetWidth,
            r = o[0].offsetHeight,
            a = parseInt(o.css("margin-top"), 10),
            s = parseInt(o.css("margin-left"), 10);
        isNaN(a) && (a = 0), isNaN(s) && (s = 0), t.top = t.top + a, t.left = t.left + s, e.offset.setOffset(o[0], e.extend({
            using: function(e) {
                o.css({
                    top: Math.round(e.top),
                    left: Math.round(e.left)
                })
            }
        }, t), 0), o.addClass("in");
        var l = o[0].offsetWidth,
            c = o[0].offsetHeight;
        "top" == n && c != r && (t.top = t.top + r - c);
        var u = this.getViewportAdjustedDelta(n, t, l, c);
        u.left ? t.left += u.left : t.top += u.top;
        var d = /top|bottom/.test(n),
            p = d ? 2 * u.left - i + l : 2 * u.top - r + c,
            f = d ? "offsetWidth" : "offsetHeight";
        o.offset(t), this.replaceArrow(p, o[0][f], d)
    }, t.prototype.replaceArrow = function(e, t, n) {
        this.arrow().css(n ? "left" : "top", 50 * (1 - e / t) + "%").css(n ? "top" : "left", "")
    }, t.prototype.setContent = function() {
        var e = this.tip(),
            t = this.getTitle();
        e.find(".tooltip-inner")[this.options.html ? "html" : "text"](t), e.removeClass("fade in top bottom left right")
    }, t.prototype.hide = function(n) {
        function o() {
            "in" != i.hoverState && r.detach(), i.$element.removeAttr("aria-describedby").trigger("hidden.bs." + i.type), n && n()
        }
        var i = this,
            r = this.tip(),
            a = e.Event("hide.bs." + this.type);
        return this.$element.trigger(a), a.isDefaultPrevented() ? void 0 : (r.removeClass("in"), e.support.transition && this.$tip.hasClass("fade") ? r.one("bsTransitionEnd", o).emulateTransitionEnd(t.TRANSITION_DURATION) : o(), this.hoverState = null, this)
    }, t.prototype.fixTitle = function() {
        var e = this.$element;
        (e.attr("title") || "string" != typeof e.attr("data-original-title")) && e.attr("data-original-title", e.attr("title") || "").attr("title", "")
    }, t.prototype.hasContent = function() {
        return this.getTitle()
    }, t.prototype.getPosition = function(t) {
        var n = (t = t || this.$element)[0],
            o = "BODY" == n.tagName,
            i = n.getBoundingClientRect();
        null == i.width && (i = e.extend({}, i, {
            width: i.right - i.left,
            height: i.bottom - i.top
        }));
        var r = o ? {
                top: 0,
                left: 0
            } : t.offset(),
            a = {
                scroll: o ? document.documentElement.scrollTop || document.body.scrollTop : t.scrollTop()
            },
            s = o ? {
                width: e(window).width(),
                height: e(window).height()
            } : null;
        return e.extend({}, i, a, s, r)
    }, t.prototype.getCalculatedOffset = function(e, t, n, o) {
        return "bottom" == e ? {
            top: t.top + t.height,
            left: t.left + t.width / 2 - n / 2
        } : "top" == e ? {
            top: t.top - o,
            left: t.left + t.width / 2 - n / 2
        } : "left" == e ? {
            top: t.top + t.height / 2 - o / 2,
            left: t.left - n
        } : {
            top: t.top + t.height / 2 - o / 2,
            left: t.left + t.width
        }
    }, t.prototype.getViewportAdjustedDelta = function(e, t, n, o) {
        var i = {
            top: 0,
            left: 0
        };
        if (!this.$viewport) return i;
        var r = this.options.viewport && this.options.viewport.padding || 0,
            a = this.getPosition(this.$viewport);
        if (/right|left/.test(e)) {
            var s = t.top - r - a.scroll,
                l = t.top + r - a.scroll + o;
            s < a.top ? i.top = a.top - s : l > a.top + a.height && (i.top = a.top + a.height - l)
        } else {
            var c = t.left - r,
                u = t.left + r + n;
            c < a.left ? i.left = a.left - c : u > a.width && (i.left = a.left + a.width - u)
        }
        return i
    }, t.prototype.getTitle = function() {
        var e = this.$element,
            t = this.options;
        return e.attr("data-original-title") || ("function" == typeof t.title ? t.title.call(e[0]) : t.title)
    }, t.prototype.getUID = function(e) {
        for (; e += ~~(1e6 * Math.random()), document.getElementById(e););
        return e
    }, t.prototype.tip = function() {
        return this.$tip = this.$tip || e(this.options.template)
    }, t.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".tooltip-arrow")
    }, t.prototype.enable = function() {
        this.enabled = !0
    }, t.prototype.disable = function() {
        this.enabled = !1
    }, t.prototype.toggleEnabled = function() {
        this.enabled = !this.enabled
    }, t.prototype.toggle = function(t) {
        var n = this;
        t && ((n = e(t.currentTarget).data("bs." + this.type)) || (n = new this.constructor(t.currentTarget, this.getDelegateOptions()), e(t.currentTarget).data("bs." + this.type, n))), n.tip().hasClass("in") ? n.leave(n) : n.enter(n)
    }, t.prototype.destroy = function() {
        var e = this;
        clearTimeout(this.timeout), this.hide(function() {
            e.$element.off("." + e.type).removeData("bs." + e.type)
        })
    };
    var n = e.fn.tooltip;
    e.fn.tooltip = function(n) {
        return this.each(function() {
            var o = e(this),
                i = o.data("bs.tooltip"),
                r = "object" == typeof n && n,
                a = r && r.selector;
            (i || "destroy" != n) && (a ? (i || o.data("bs.tooltip", i = {}), i[a] || (i[a] = new t(this, r))) : i || o.data("bs.tooltip", i = new t(this, r)), "string" == typeof n && i[n]())
        })
    }, e.fn.tooltip.Constructor = t, e.fn.tooltip.noConflict = function() {
        return e.fn.tooltip = n, this
    }
}(jQuery),
function(e) {
    "use strict";
    var t = function(e, t) {
        this.init("popover", e, t)
    };
    if (!e.fn.tooltip) throw new Error("Popover requires tooltip.js");
    t.VERSION = "3.3.1", t.DEFAULTS = e.extend({}, e.fn.tooltip.Constructor.DEFAULTS, {
        placement: "right",
        trigger: "click",
        content: "",
        template: '<div class="popover" role="tooltip"><div class="arrow"></div><h3 class="popover-title"></h3><div class="popover-content"></div></div>'
    }), ((t.prototype = e.extend({}, e.fn.tooltip.Constructor.prototype)).constructor = t).prototype.getDefaults = function() {
        return t.DEFAULTS
    }, t.prototype.setContent = function() {
        var e = this.tip(),
            t = this.getTitle(),
            n = this.getContent();
        e.find(".popover-title")[this.options.html ? "html" : "text"](t), e.find(".popover-content").children().detach().end()[this.options.html ? "string" == typeof n ? "html" : "append" : "text"](n), e.removeClass("fade top bottom left right in"), e.find(".popover-title").html() || e.find(".popover-title").hide()
    }, t.prototype.hasContent = function() {
        return this.getTitle() || this.getContent()
    }, t.prototype.getContent = function() {
        var e = this.$element,
            t = this.options;
        return e.attr("data-content") || ("function" == typeof t.content ? t.content.call(e[0]) : t.content)
    }, t.prototype.arrow = function() {
        return this.$arrow = this.$arrow || this.tip().find(".arrow")
    }, t.prototype.tip = function() {
        return this.$tip || (this.$tip = e(this.options.template)), this.$tip
    };
    var n = e.fn.popover;
    e.fn.popover = function(n) {
        return this.each(function() {
            var o = e(this),
                i = o.data("bs.popover"),
                r = "object" == typeof n && n,
                a = r && r.selector;
            (i || "destroy" != n) && (a ? (i || o.data("bs.popover", i = {}), i[a] || (i[a] = new t(this, r))) : i || o.data("bs.popover", i = new t(this, r)), "string" == typeof n && i[n]())
        })
    }, e.fn.popover.Constructor = t, e.fn.popover.noConflict = function() {
        return e.fn.popover = n, this
    }
}(jQuery),
function(e) {
    "use strict";

    function t(n, o) {
        var i = e.proxy(this.process, this);
        this.$body = e("body"), this.$scrollElement = e(e(n).is("body") ? window : n), this.options = e.extend({}, t.DEFAULTS, o), this.selector = (this.options.target || "") + " .nav li > a", this.offsets = [], this.targets = [], this.activeTarget = null, this.scrollHeight = 0, this.$scrollElement.on("scroll.bs.scrollspy", i), this.refresh(), this.process()
    }

    function n(n) {
        return this.each(function() {
            var o = e(this),
                i = o.data("bs.scrollspy"),
                r = "object" == typeof n && n;
            i || o.data("bs.scrollspy", i = new t(this, r)), "string" == typeof n && i[n]()
        })
    }
    t.VERSION = "3.3.1", t.DEFAULTS = {
        offset: 10
    }, t.prototype.getScrollHeight = function() {
        return this.$scrollElement[0].scrollHeight || Math.max(this.$body[0].scrollHeight, document.documentElement.scrollHeight)
    }, t.prototype.refresh = function() {
        var t = "offset",
            n = 0;
        e.isWindow(this.$scrollElement[0]) || (t = "position", n = this.$scrollElement.scrollTop()), this.offsets = [], this.targets = [], this.scrollHeight = this.getScrollHeight();
        var o = this;
        this.$body.find(this.selector).map(function() {
            var o = e(this),
                i = o.data("target") || o.attr("href"),
                r = /^#./.test(i) && e(i);
            return r && r.length && r.is(":visible") && [
                [r[t]().top + n, i]
            ] || null
        }).sort(function(e, t) {
            return e[0] - t[0]
        }).each(function() {
            o.offsets.push(this[0]), o.targets.push(this[1])
        })
    }, t.prototype.process = function() {
        var e, t = this.$scrollElement.scrollTop() + this.options.offset,
            n = this.getScrollHeight(),
            o = this.options.offset + n - this.$scrollElement.height(),
            i = this.offsets,
            r = this.targets,
            a = this.activeTarget;
        if (this.scrollHeight != n && this.refresh(), o <= t) return a != (e = r[r.length - 1]) && this.activate(e);
        if (a && t < i[0]) return this.activeTarget = null, this.clear();
        for (e = i.length; e--;) a != r[e] && t >= i[e] && (!i[e + 1] || t <= i[e + 1]) && this.activate(r[e])
    }, t.prototype.activate = function(t) {
        this.activeTarget = t, this.clear();
        var n = this.selector + '[data-target="' + t + '"],' + this.selector + '[href="' + t + '"]',
            o = e(n).parents("li").addClass("active");
        o.parent(".dropdown-menu").length && (o = o.closest("li.dropdown").addClass("active")), o.trigger("activate.bs.scrollspy")
    }, t.prototype.clear = function() {
        e(this.selector).parentsUntil(this.options.target, ".active").removeClass("active")
    };
    var o = e.fn.scrollspy;
    e.fn.scrollspy = n, e.fn.scrollspy.Constructor = t, e.fn.scrollspy.noConflict = function() {
        return e.fn.scrollspy = o, this
    }, e(window).on("load.bs.scrollspy.data-api", function() {
        e('[data-spy="scroll"]').each(function() {
            var t = e(this);
            n.call(t, t.data())
        })
    })
}(jQuery),
function(e) {
    "use strict";

    function t(t) {
        return this.each(function() {
            var o = e(this),
                i = o.data("bs.tab");
            i || o.data("bs.tab", i = new n(this)), "string" == typeof t && i[t]()
        })
    }
    var n = function(t) {
        this.element = e(t)
    };
    n.VERSION = "3.3.1", n.TRANSITION_DURATION = 150, n.prototype.show = function() {
        var t = this.element,
            n = t.closest("ul:not(.dropdown-menu)"),
            o = t.data("target");
        if (o || (o = (o = t.attr("href")) && o.replace(/.*(?=#[^\s]*$)/, "")), !t.parent("li").hasClass("active")) {
            var i = n.find(".active:last a"),
                r = e.Event("hide.bs.tab", {
                    relatedTarget: t[0]
                }),
                a = e.Event("show.bs.tab", {
                    relatedTarget: i[0]
                });
            if (i.trigger(r), t.trigger(a), !a.isDefaultPrevented() && !r.isDefaultPrevented()) {
                var s = e(o);
                this.activate(t.closest("li"), n), this.activate(s, s.parent(), function() {
                    i.trigger({
                        type: "hidden.bs.tab",
                        relatedTarget: t[0]
                    }), t.trigger({
                        type: "shown.bs.tab",
                        relatedTarget: i[0]
                    })
                })
            }
        }
    }, n.prototype.activate = function(t, o, i) {
        function r() {
            a.removeClass("active").find("> .dropdown-menu > .active").removeClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !1), t.addClass("active").find('[data-toggle="tab"]').attr("aria-expanded", !0), s ? (t[0].offsetWidth, t.addClass("in")) : t.removeClass("fade"), t.parent(".dropdown-menu") && t.closest("li.dropdown").addClass("active").end().find('[data-toggle="tab"]').attr("aria-expanded", !0), i && i()
        }
        var a = o.find("> .active"),
            s = i && e.support.transition && (a.length && a.hasClass("fade") || !!o.find("> .fade").length);
        a.length && s ? a.one("bsTransitionEnd", r).emulateTransitionEnd(n.TRANSITION_DURATION) : r(), a.removeClass("in")
    };
    var o = e.fn.tab;
    e.fn.tab = t, e.fn.tab.Constructor = n, e.fn.tab.noConflict = function() {
        return e.fn.tab = o, this
    };
    var i = function(n) {
        n.preventDefault(), t.call(e(this), "show")
    };
    e(document).on("click.bs.tab.data-api", '[data-toggle="tab"]', i).on("click.bs.tab.data-api", '[data-toggle="pill"]', i)
}(jQuery),
function(e) {
    "use strict";

    function t(t) {
        return this.each(function() {
            var o = e(this),
                i = o.data("bs.affix"),
                r = "object" == typeof t && t;
            i || o.data("bs.affix", i = new n(this, r)), "string" == typeof t && i[t]()
        })
    }
    var n = function(t, o) {
        this.options = e.extend({}, n.DEFAULTS, o), this.$target = e(this.options.target).on("scroll.bs.affix.data-api", e.proxy(this.checkPosition, this)).on("click.bs.affix.data-api", e.proxy(this.checkPositionWithEventLoop, this)), this.$element = e(t), this.affixed = this.unpin = this.pinnedOffset = null, this.checkPosition()
    };
    n.VERSION = "3.3.1", n.RESET = "affix affix-top affix-bottom", n.DEFAULTS = {
        offset: 0,
        target: window
    }, n.prototype.getState = function(e, t, n, o) {
        var i = this.$target.scrollTop(),
            r = this.$element.offset(),
            a = this.$target.height();
        if (null != n && "top" == this.affixed) return i < n && "top";
        if ("bottom" == this.affixed) return null != n ? !(i + this.unpin <= r.top) && "bottom" : !(i + a <= e - o) && "bottom";
        var s = null == this.affixed,
            l = s ? i : r.top;
        return null != n && l <= n ? "top" : null != o && e - o <= l + (s ? a : t) && "bottom"
    }, n.prototype.getPinnedOffset = function() {
        if (this.pinnedOffset) return this.pinnedOffset;
        this.$element.removeClass(n.RESET).addClass("affix");
        var e = this.$target.scrollTop(),
            t = this.$element.offset();
        return this.pinnedOffset = t.top - e
    }, n.prototype.checkPositionWithEventLoop = function() {
        setTimeout(e.proxy(this.checkPosition, this), 1)
    }, n.prototype.checkPosition = function() {
        if (this.$element.is(":visible")) {
            var t = this.$element.height(),
                o = this.options.offset,
                i = o.top,
                r = o.bottom,
                a = e("body").height();
            "object" != typeof o && (r = i = o), "function" == typeof i && (i = o.top(this.$element)), "function" == typeof r && (r = o.bottom(this.$element));
            var s = this.getState(a, t, i, r);
            if (this.affixed != s) {
                null != this.unpin && this.$element.css("top", "");
                var l = "affix" + (s ? "-" + s : ""),
                    c = e.Event(l + ".bs.affix");
                if (this.$element.trigger(c), c.isDefaultPrevented()) return;
                this.affixed = s, this.unpin = "bottom" == s ? this.getPinnedOffset() : null, this.$element.removeClass(n.RESET).addClass(l).trigger(l.replace("affix", "affixed") + ".bs.affix")
            }
            "bottom" == s && this.$element.offset({
                top: a - t - r
            })
        }
    };
    var o = e.fn.affix;
    e.fn.affix = t, e.fn.affix.Constructor = n, e.fn.affix.noConflict = function() {
        return e.fn.affix = o, this
    }, e(window).on("load", function() {
        e('[data-spy="affix"]').each(function() {
            var n = e(this),
                o = n.data();
            o.offset = o.offset || {}, null != o.offsetBottom && (o.offset.bottom = o.offsetBottom), null != o.offsetTop && (o.offset.top = o.offsetTop), t.call(n, o)
        })
    })
}(jQuery), window.Modernizr = function(e, t, n) {
        function o(e) {
            m.cssText = e
        }

        function i(e, t) {
            return typeof e === t
        }

        function r(e, t) {
            return !!~("" + e).indexOf(t)
        }

        function a(e, t) {
            for (var o in e) {
                var i = e[o];
                if (!r(i, "-") && m[i] !== n) return "pfx" != t || i
            }
            return !1
        }

        function s(e, t, o) {
            var r = e.charAt(0).toUpperCase() + e.slice(1),
                s = (e + " " + x.join(r + " ") + r).split(" ");
            return i(t, "string") || i(t, "undefined") ? a(s, t) : function(e, t, o) {
                for (var r in e) {
                    var a = t[e[r]];
                    if (a !== n) return !1 === o ? e[r] : i(a, "function") ? a.bind(o || t) : a
                }
                return !1
            }(s = (e + " " + w.join(r + " ") + r).split(" "), t, o)
        }
        var l, c, u, d = {},
            p = t.documentElement,
            f = "modernizr",
            h = t.createElement(f),
            m = h.style,
            g = t.createElement("input"),
            v = ":)",
            y = " -webkit- -moz- -o- -ms- ".split(" "),
            b = "Webkit Moz O ms",
            x = b.split(" "),
            w = b.toLowerCase().split(" "),
            C = {},
            k = {},
            T = {},
            $ = [],
            E = $.slice,
            S = function(e, n, o, i) {
                var r, a, s, l, c = t.createElement("div"),
                    u = t.body,
                    d = u || t.createElement("body");
                if (parseInt(o, 10))
                    for (; o--;)(s = t.createElement("div")).id = i ? i[o] : f + (o + 1), c.appendChild(s);
                return r = ["&#173;", '<style id="s', f, '">', e, "</style>"].join(""), c.id = f, (u ? c : d).innerHTML += r, d.appendChild(c), u || (d.style.background = "", d.style.overflow = "hidden", l = p.style.overflow, p.style.overflow = "hidden", p.appendChild(d)), a = n(c, e), u ? c.parentNode.removeChild(c) : (d.parentNode.removeChild(d), p.style.overflow = l), !!a
            },
            N = (u = {
                select: "input",
                change: "input",
                submit: "form",
                reset: "form",
                error: "img",
                load: "img",
                abort: "img"
            }, function(e, o) {
                o = o || t.createElement(u[e] || "div");
                var r = (e = "on" + e) in o;
                return r || (o.setAttribute || (o = t.createElement("div")), o.setAttribute && o.removeAttribute && (o.setAttribute(e, ""), r = i(o[e], "function"), i(o[e], "undefined") || (o[e] = n), o.removeAttribute(e))), o = null, r
            }),
            B = {}.hasOwnProperty;
        for (var _ in c = i(B, "undefined") || i(B.call, "undefined") ? function(e, t) {
                return t in e && i(e.constructor.prototype[t], "undefined")
            } : function(e, t) {
                return B.call(e, t)
            }, Function.prototype.bind || (Function.prototype.bind = function(e) {
                var t = this;
                if ("function" != typeof t) throw new TypeError;
                var n = E.call(arguments, 1),
                    o = function() {
                        if (this instanceof o) {
                            var i = function() {};
                            i.prototype = t.prototype;
                            var r = new i,
                                a = t.apply(r, n.concat(E.call(arguments)));
                            return Object(a) === a ? a : r
                        }
                        return t.apply(e, n.concat(E.call(arguments)))
                    };
                return o
            }), C.canvas = function() {
                var e = t.createElement("canvas");
                return !!e.getContext && !!e.getContext("2d")
            }, C.canvastext = function() {
                return !!d.canvas && !!i(t.createElement("canvas").getContext("2d").fillText, "function")
            }, C.touch = function() {
                var n;
                return "ontouchstart" in e || e.DocumentTouch && t instanceof DocumentTouch ? n = !0 : S(["@media (", y.join("touch-enabled),("), f, ")", "{#modernizr{top:9px;position:absolute}}"].join(""), function(e) {
                    n = 9 === e.offsetTop
                }), n
            }, C.postmessage = function() {
                return !!e.postMessage
            }, C.websqldatabase = function() {
                return !!e.openDatabase
            }, C.indexedDB = function() {
                return !!s("indexedDB", e)
            }, C.hashchange = function() {
                return N("hashchange", e) && (t.documentMode === n || 7 < t.documentMode)
            }, C.history = function() {
                return !!e.history && !!history.pushState
            }, C.draganddrop = function() {
                var e = t.createElement("div");
                return "draggable" in e || "ondragstart" in e && "ondrop" in e
            }, C.websockets = function() {
                return "WebSocket" in e || "MozWebSocket" in e
            }, C.rgba = function() {
                return o("background-color:rgba(150,255,150,.5)"), r(m.backgroundColor, "rgba")
            }, C.backgroundsize = function() {
                return s("backgroundSize")
            }, C.borderimage = function() {
                return s("borderImage")
            }, C.borderradius = function() {
                return s("borderRadius")
            }, C.boxshadow = function() {
                return s("boxShadow")
            }, C.textshadow = function() {
                return "" === t.createElement("div").style.textShadow
            }, C.opacity = function() {
                return e = "opacity:.55", o(y.join(e + ";") + ""), /^0.55$/.test(m.opacity);
                var e
            }, C.cssanimations = function() {
                return s("animationName")
            }, C.csscolumns = function() {
                return s("columnCount")
            }, C.cssgradients = function() {
                var e = "background-image:";
                return o((e + "-webkit- ".split(" ").join("gradient(linear,left top,right bottom,from(#9f9),to(white));" + e) + y.join("linear-gradient(left top,#9f9, white);" + e)).slice(0, -e.length)), r(m.backgroundImage, "gradient")
            }, C.csstransforms = function() {
                return !!s("transform")
            }, C.csstransitions = function() {
                return s("transition")
            }, C.fontface = function() {
                var e;
                return S('@font-face {font-family:"font";src:url("https://")}', function(n, o) {
                    var i = t.getElementById("smodernizr"),
                        r = i.sheet || i.styleSheet,
                        a = r ? r.cssRules && r.cssRules[0] ? r.cssRules[0].cssText : r.cssText || "" : "";
                    e = /src/i.test(a) && 0 === a.indexOf(o.split(" ")[0])
                }), e
            }, C.generatedcontent = function() {
                var e;
                return S(["#", f, "{font:0/0 a}#", f, ':after{content:"', v, '";visibility:hidden;font:3px/1 a}'].join(""), function(t) {
                    e = 3 <= t.offsetHeight
                }), e
            }, C.video = function() {
                var e = t.createElement("video"),
                    n = !1;
                try {
                    (n = !!e.canPlayType) && ((n = new Boolean(n)).ogg = e.canPlayType('video/ogg; codecs="theora"').replace(/^no$/, ""), n.h264 = e.canPlayType('video/mp4; codecs="avc1.42E01E"').replace(/^no$/, ""), n.webm = e.canPlayType('video/webm; codecs="vp8, vorbis"').replace(/^no$/, ""))
                } catch (e) {}
                return n
            }, C.audio = function() {
                var e = t.createElement("audio"),
                    n = !1;
                try {
                    (n = !!e.canPlayType) && ((n = new Boolean(n)).ogg = e.canPlayType('audio/ogg; codecs="vorbis"').replace(/^no$/, ""), n.mp3 = e.canPlayType("audio/mpeg;").replace(/^no$/, ""), n.wav = e.canPlayType('audio/wav; codecs="1"').replace(/^no$/, ""), n.m4a = (e.canPlayType("audio/x-m4a;") || e.canPlayType("audio/aac;")).replace(/^no$/, ""))
                } catch (e) {}
                return n
            }, C.localstorage = function() {
                try {
                    return localStorage.setItem(f, f), localStorage.removeItem(f), !0
                } catch (e) {
                    return !1
                }
            }, C.sessionstorage = function() {
                try {
                    return sessionStorage.setItem(f, f), sessionStorage.removeItem(f), !0
                } catch (e) {
                    return !1
                }
            }, C.webworkers = function() {
                return !!e.Worker
            }, C.applicationcache = function() {
                return !!e.applicationCache
            }, C) c(C, _) && (l = _.toLowerCase(), d[l] = C[_](), $.push((d[l] ? "" : "no-") + l));
        return d.input || (d.input = function(n) {
                for (var o = 0, i = n.length; o < i; o++) T[n[o]] = n[o] in g;
                return T.list && (T.list = !!t.createElement("datalist") && !!e.HTMLDataListElement), T
            }("autocomplete autofocus list placeholder max min multiple pattern required step".split(" ")), d.inputtypes = function(e) {
                for (var o, i, r, a = 0, s = e.length; a < s; a++) g.setAttribute("type", i = e[a]), (o = "text" !== g.type) && (g.value = v, g.style.cssText = "position:absolute;visibility:hidden;", /^range$/.test(i) && g.style.WebkitAppearance !== n ? (p.appendChild(g), o = (r = t.defaultView).getComputedStyle && "textfield" !== r.getComputedStyle(g, null).WebkitAppearance && 0 !== g.offsetHeight, p.removeChild(g)) : /^(search|tel)$/.test(i) || (o = /^(url|email)$/.test(i) ? g.checkValidity && !1 === g.checkValidity() : g.value != v)), k[e[a]] = !!o;
                return k
            }("search tel url email datetime date month week time datetime-local number range color".split(" "))), d.addTest = function(e, t) {
                if ("object" == typeof e)
                    for (var o in e) c(e, o) && d.addTest(o, e[o]);
                else {
                    if (e = e.toLowerCase(), d[e] !== n) return d;
                    t = "function" == typeof t ? t() : t, p.className += " " + (t ? "" : "no-") + e, d[e] = t
                }
                return d
            }, o(""), h = g = null,
            function(e, t) {
                function n() {
                    var e = h.elements;
                    return "string" == typeof e ? e.split(" ") : e
                }

                function o(e) {
                    var t = f[e[d]];
                    return t || (t = {}, p++, e[d] = p, f[p] = t), t
                }

                function i(e, n, i) {
                    return n || (n = t), s ? n.createElement(e) : (i || (i = o(n)), !(r = i.cache[e] ? i.cache[e].cloneNode() : u.test(e) ? (i.cache[e] = i.createElem(e)).cloneNode() : i.createElem(e)).canHaveChildren || c.test(e) || r.tagUrn ? r : i.frag.appendChild(r));
                    var r
                }

                function r(e) {
                    e || (e = t);
                    var r, l, c, u, d, p, f = o(e);
                    return h.shivCSS && !a && !f.hasCSS && (f.hasCSS = (u = "article,aside,dialog,figcaption,figure,footer,header,hgroup,main,nav,section{display:block}mark{background:#FF0;color:#000}template{display:none}", d = (c = e).createElement("p"), p = c.getElementsByTagName("head")[0] || c.documentElement, d.innerHTML = "x<style>" + u + "</style>", !!p.insertBefore(d.lastChild, p.firstChild))), s || (r = e, (l = f).cache || (l.cache = {}, l.createElem = r.createElement, l.createFrag = r.createDocumentFragment, l.frag = l.createFrag()), r.createElement = function(e) {
                        return h.shivMethods ? i(e, r, l) : l.createElem(e)
                    }, r.createDocumentFragment = Function("h,f", "return function(){var n=f.cloneNode(),c=n.createElement;h.shivMethods&&(" + n().join().replace(/[\w\-]+/g, function(e) {
                        return l.createElem(e), l.frag.createElement(e), 'c("' + e + '")'
                    }) + ");return n}")(h, l.frag)), e
                }
                var a, s, l = e.html5 || {},
                    c = /^<|^(?:button|map|select|textarea|object|iframe|option|optgroup)$/i,
                    u = /^(?:a|b|code|div|fieldset|h1|h2|h3|h4|h5|h6|i|label|li|ol|p|q|span|strong|style|table|tbody|td|th|tr|ul)$/i,
                    d = "_html5shiv",
                    p = 0,
                    f = {};
                ! function() {
                    try {
                        var e = t.createElement("a");
                        e.innerHTML = "<xyz></xyz>", a = "hidden" in e, s = 1 == e.childNodes.length || function() {
                            t.createElement("a");
                            var e = t.createDocumentFragment();
                            return void 0 === e.cloneNode || void 0 === e.createDocumentFragment || void 0 === e.createElement
                        }()
                    } catch (e) {
                        s = a = !0
                    }
                }();
                var h = {
                    elements: l.elements || "abbr article aside audio bdi canvas data datalist details dialog figcaption figure footer header hgroup main mark meter nav output progress section summary template time video",
                    version: "3.7.0",
                    shivCSS: !1 !== l.shivCSS,
                    supportsUnknownElements: s,
                    shivMethods: !1 !== l.shivMethods,
                    type: "default",
                    shivDocument: r,
                    createElement: i,
                    createDocumentFragment: function(e, i) {
                        if (e || (e = t), s) return e.createDocumentFragment();
                        for (var r = (i = i || o(e)).frag.cloneNode(), a = 0, l = n(), c = l.length; a < c; a++) r.createElement(l[a]);
                        return r
                    }
                };
                e.html5 = h, r(t)
            }(this, t), d._version = "2.8.3", d._prefixes = y, d._domPrefixes = w, d._cssomPrefixes = x, d.hasEvent = N, d.testProp = function(e) {
                return a([e])
            }, d.testAllProps = s, d.testStyles = S, p.className = p.className.replace(/(^|\s)no-js(\s|$)/, "$1$2") + " js " + $.join(" "), d
    }(this, this.document),
    function(e, t, n) {
        function o(e) {
            return "[object Function]" == m.call(e)
        }

        function i(e) {
            return "string" == typeof e
        }

        function r() {}

        function a(e) {
            return !e || "loaded" == e || "complete" == e || "uninitialized" == e
        }

        function s() {
            var e = g.shift();
            v = 1, e ? e.t ? f(function() {
                ("c" == e.t ? d.injectCss : d.injectJs)(e.s, 0, e.a, e.x, e.e, 1)
            }, 0) : (e(), s()) : v = 0
        }

        function l(e, n, o, r, l) {
            return v = 0, n = n || "j", i(e) ? function(e, n, o, i, r, l, c) {
                function u(t) {
                    if (!m && a(p.readyState) && (w.r = m = 1, !v && s(), p.onload = p.onreadystatechange = null, t))
                        for (var o in "img" != e && f(function() {
                                x.removeChild(p)
                            }, 50), $[n]) $[n].hasOwnProperty(o) && $[n][o].onload()
                }
                c = c || d.errorTimeout;
                var p = t.createElement(e),
                    m = 0,
                    y = 0,
                    w = {
                        t: o,
                        s: n,
                        e: r,
                        a: l,
                        x: c
                    };
                1 === $[n] && (y = 1, $[n] = []), "object" == e ? p.data = n : (p.src = n, p.type = e), p.width = p.height = "0", p.onerror = p.onload = p.onreadystatechange = function() {
                    u.call(this, y)
                }, g.splice(i, 0, w), "img" != e && (y || 2 === $[n] ? (x.insertBefore(p, b ? null : h), f(u, c)) : $[n].push(p))
            }("c" == n ? C : w, e, n, this.i++, o, r, l) : (g.splice(this.i++, 0, e), 1 == g.length && s()), this
        }

        function c() {
            var e = d;
            return e.loader = {
                load: l,
                i: 0
            }, e
        }
        var u, d, p = t.documentElement,
            f = e.setTimeout,
            h = t.getElementsByTagName("script")[0],
            m = {}.toString,
            g = [],
            v = 0,
            y = "MozAppearance" in p.style,
            b = y && !!t.createRange().compareNode,
            x = b ? p : h.parentNode,
            w = (p = e.opera && "[object Opera]" == m.call(e.opera), p = !!t.attachEvent && !p, y ? "object" : p ? "script" : "img"),
            C = p ? "script" : w,
            k = Array.isArray || function(e) {
                return "[object Array]" == m.call(e)
            },
            T = [],
            $ = {},
            E = {
                timeout: function(e, t) {
                    return t.length && (e.timeout = t[0]), e
                }
            };
        (d = function(e) {
            function t(e, t, n, i, r) {
                var a = function(e) {
                        e = e.split("!");
                        var t, n, o, i = T.length,
                            r = e.pop(),
                            a = e.length;
                        for (r = {
                                url: r,
                                origUrl: r,
                                prefixes: e
                            }, n = 0; n < a; n++) o = e[n].split("="), (t = E[o.shift()]) && (r = t(r, o));
                        for (n = 0; n < i; n++) r = T[n](r);
                        return r
                    }(e),
                    s = a.autoCallback;
                a.url.split(".").pop().split("?").shift(), a.bypass || (t && (t = o(t) ? t : t[e] || t[i] || t[e.split("/").pop().split("?")[0]]), a.instead ? a.instead(e, t, n, i, r) : ($[a.url] ? a.noexec = !0 : $[a.url] = 1, n.load(a.url, a.forceCSS || !a.forceJS && "css" == a.url.split(".").pop().split("?").shift() ? "c" : void 0, a.noexec, a.attrs, a.timeout), (o(t) || o(s)) && n.load(function() {
                    c(), t && t(a.origUrl, r, i), s && s(a.origUrl, r, i), $[a.url] = 2
                })))
            }

            function n(e, n) {
                function a(e, r) {
                    if (e) {
                        if (i(e)) r || (d = function() {
                            var e = [].slice.call(arguments);
                            p.apply(this, e), f()
                        }), t(e, d, n, 0, c);
                        else if (Object(e) === e)
                            for (l in s = function() {
                                    var t, n = 0;
                                    for (t in e) e.hasOwnProperty(t) && n++;
                                    return n
                                }(), e) e.hasOwnProperty(l) && (!r && !--s && (o(d) ? d = function() {
                                var e = [].slice.call(arguments);
                                p.apply(this, e), f()
                            } : d[l] = function(e) {
                                return function() {
                                    var t = [].slice.call(arguments);
                                    e && e.apply(this, t), f()
                                }
                            }(p[l])), t(e[l], d, n, l, c))
                    } else !r && f()
                }
                var s, l, c = !!e.test,
                    u = e.load || e.both,
                    d = e.callback || r,
                    p = d,
                    f = e.complete || r;
                a(c ? e.yep : e.nope, !!u), u && a(u)
            }
            var a, s, l = this.yepnope.loader;
            if (i(e)) t(e, 0, l, 0);
            else if (k(e))
                for (a = 0; a < e.length; a++) i(s = e[a]) ? t(s, 0, l, 0) : k(s) ? d(s) : Object(s) === s && n(s, l);
            else Object(e) === e && n(e, l)
        }).addPrefix = function(e, t) {
            E[e] = t
        }, d.addFilter = function(e) {
            T.push(e)
        }, d.errorTimeout = 1e4, null == t.readyState && t.addEventListener && (t.readyState = "loading", t.addEventListener("DOMContentLoaded", u = function() {
            t.removeEventListener("DOMContentLoaded", u, 0), t.readyState = "complete"
        }, 0)), e.yepnope = c(), e.yepnope.executeStack = s, e.yepnope.injectJs = function(e, n, o, i, l, c) {
            var u, p, m = t.createElement("script");
            i = i || d.errorTimeout;
            for (p in m.src = e, o) m.setAttribute(p, o[p]);
            n = c ? s : n || r, m.onreadystatechange = m.onload = function() {
                !u && a(m.readyState) && (u = 1, n(), m.onload = m.onreadystatechange = null)
            }, f(function() {
                u || n(u = 1)
            }, i), l ? m.onload() : h.parentNode.insertBefore(m, h)
        }, e.yepnope.injectCss = function(e, n, o, i, a, l) {
            var c;
            i = t.createElement("link"), n = l ? s : n || r;
            for (c in i.href = e, i.rel = "stylesheet", i.type = "text/css", o) i.setAttribute(c, o[c]);
            a || (h.parentNode.insertBefore(i, h), f(n, 0))
        }
    }(this, document), Modernizr.load = function() {
        yepnope.apply(window, [].slice.call(arguments, 0))
    },
    function(e) {
        e.fn.appear = function(t, n) {
            var o = e.extend({
                data: void 0,
                one: !0,
                accX: 0,
                accY: 0
            }, n);
            return this.each(function() {
                var n = e(this);
                if (n.appeared = !1, t) {
                    var i = e(window),
                        r = function() {
                            if (n.is(":visible")) {
                                var e = i.scrollLeft(),
                                    t = i.scrollTop(),
                                    r = n.offset(),
                                    a = r.left,
                                    s = r.top,
                                    l = o.accX,
                                    c = o.accY,
                                    u = n.height(),
                                    d = i.height(),
                                    p = n.width(),
                                    f = i.width();
                                t <= s + u + c && s <= t + d + c && e <= a + p + l && a <= e + f + l ? n.appeared || n.trigger("appear", o.data) : n.appeared = !1
                            } else n.appeared = !1
                        },
                        a = function() {
                            if (n.appeared = !0, o.one) {
                                i.unbind("scroll", r);
                                var a = e.inArray(r, e.fn.appear.checks);
                                0 <= a && e.fn.appear.checks.splice(a, 1)
                            }
                            t.apply(this, arguments)
                        };
                    o.one ? n.one("appear", o.data, a) : n.bind("appear", o.data, a), i.scroll(r), e.fn.appear.checks.push(r), r()
                } else n.trigger("appear", o.data)
            })
        }, e.extend(e.fn.appear, {
            checks: [],
            timeout: null,
            checkAll: function() {
                var t = e.fn.appear.checks.length;
                if (0 < t)
                    for (; t--;) e.fn.appear.checks[t]()
            },
            run: function() {
                e.fn.appear.timeout && clearTimeout(e.fn.appear.timeout), e.fn.appear.timeout = setTimeout(e.fn.appear.checkAll, 20)
            }
        }), e.each(["append", "prepend", "after", "before", "attr", "removeAttr", "addClass", "removeClass", "toggleClass", "remove", "css", "show", "hide"], function(t, n) {
            var o = e.fn[n];
            o && (e.fn[n] = function() {
                var t = o.apply(this, arguments);
                return e.fn.appear.run(), t
            })
        })
    }(jQuery),
    function(e) {
        "function" == typeof define && define.amd ? define(["jquery"], e) : "object" == typeof exports ? e(require("jquery")) : e(jQuery)
    }(function(e) {
        var t = /\+/g;

        function n(e) {
            return i.raw ? e : encodeURIComponent(e)
        }

        function o(n, o) {
            var r = i.raw ? n : function(e) {
                0 === e.indexOf('"') && (e = e.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, "\\"));
                try {
                    return e = decodeURIComponent(e.replace(t, " ")), i.json ? JSON.parse(e) : e
                } catch (e) {}
            }(n);
            return e.isFunction(o) ? o(r) : r
        }
        var i = e.cookie = function(t, r, a) {
            if (1 < arguments.length && !e.isFunction(r)) {
                if ("number" == typeof(a = e.extend({}, i.defaults, a)).expires) {
                    var s = a.expires,
                        l = a.expires = new Date;
                    l.setTime(+l + 864e5 * s)
                }
                return document.cookie = [n(t), "=", (c = r, n(i.json ? JSON.stringify(c) : String(c))), a.expires ? "; expires=" + a.expires.toUTCString() : "", a.path ? "; path=" + a.path : "", a.domain ? "; domain=" + a.domain : "", a.secure ? "; secure" : ""].join("")
            }
            for (var c, u, d = t ? void 0 : {}, p = document.cookie ? document.cookie.split("; ") : [], f = 0, h = p.length; f < h; f++) {
                var m = p[f].split("="),
                    g = (u = m.shift(), i.raw ? u : decodeURIComponent(u)),
                    v = m.join("=");
                if (t && t === g) {
                    d = o(v, r);
                    break
                }
                t || void 0 === (v = o(v)) || (d[g] = v)
            }
            return d
        };
        i.defaults = {}, e.removeCookie = function(t, n) {
            return void 0 !== e.cookie(t) && (e.cookie(t, "", e.extend({}, n, {
                expires: -1
            })), !e.cookie(t))
        }
    }),
    function(e) {
        var t = this.SelectBox = function(e, t) {
            if (e instanceof jQuery) {
                if (!(0 < e.length)) return;
                e = e[0]
            }
            return this.typeTimer = null, this.typeSearch = "", this.isMac = navigator.platform.match(/mac/i), t = "object" == typeof t ? t : {}, this.selectElement = e, !(!t.mobile && navigator.userAgent.match(/iPad|iPhone|Android|IEMobile|BlackBerry/i)) && "select" === e.tagName.toLowerCase() && void this.init(t)
        };
        t.prototype.version = "1.2.0", t.prototype.init = function(t) {
            var n = e(this.selectElement);
            if (n.data("selectBox-control")) return !1;
            var o = e('<a class="selectBox" />'),
                i = n.attr("multiple") || 1 < parseInt(n.attr("size")),
                r = t || {},
                a = parseInt(n.prop("tabindex")) || 0,
                s = this;
            if (o.width(n.outerWidth()).addClass(n.attr("class")).attr("title", n.attr("title") || "").attr("tabindex", a).css("display", "inline-block").bind("focus.selectBox", function() {
                    this !== document.activeElement && document.body !== document.activeElement && e(document.activeElement).blur(), o.hasClass("selectBox-active") || (o.addClass("selectBox-active"), n.trigger("focus"))
                }).bind("blur.selectBox", function() {
                    o.hasClass("selectBox-active") && (o.removeClass("selectBox-active"), n.trigger("blur"))
                }), e(window).data("selectBox-bindings") || e(window).data("selectBox-bindings", !0).bind("scroll.selectBox", r.hideOnWindowScroll ? this.hideMenus : e.noop).bind("resize.selectBox", this.hideMenus), n.attr("disabled") && o.addClass("selectBox-disabled"), n.bind("click.selectBox", function(e) {
                    o.focus(), e.preventDefault()
                }), i) {
                if (t = this.getOptions("inline"), o.append(t).data("selectBox-options", t).addClass("selectBox-inline selectBox-menuShowing").bind("keydown.selectBox", function(e) {
                        s.handleKeyDown(e)
                    }).bind("keypress.selectBox", function(e) {
                        s.handleKeyPress(e)
                    }).bind("mousedown.selectBox", function(t) {
                        1 === t.which && (e(t.target).is("A.selectBox-inline") && t.preventDefault(), o.hasClass("selectBox-focus") || o.focus())
                    }).insertAfter(n), !n[0].style.height) {
                    var l = n.attr("size") ? parseInt(n.attr("size")) : 5,
                        c = o.clone().removeAttr("id").css({
                            position: "absolute",
                            top: "-9999em"
                        }).show().appendTo("body");
                    c.find(".selectBox-options").html("<li><a> </a></li>");
                    var u = parseInt(c.find(".selectBox-options A:first").html("&nbsp;").outerHeight());
                    c.remove(), o.height(u * l)
                }
                this.disableSelection(o)
            } else {
                var d = e('<span class="selectBox-label" />'),
                    p = e('<span class="selectBox-arrow" />');
                d.attr("class", this.getLabelClass()).text(this.getLabelText()), (t = this.getOptions("dropdown")).appendTo("BODY"), o.data("selectBox-options", t).addClass("selectBox-dropdown").append(d).append(p).bind("mousedown.selectBox", function(e) {
                    1 === e.which && (o.hasClass("selectBox-menuShowing") ? s.hideMenus() : (e.stopPropagation(), t.data("selectBox-down-at-x", e.screenX).data("selectBox-down-at-y", e.screenY), s.showMenu()))
                }).bind("keydown.selectBox", function(e) {
                    s.handleKeyDown(e)
                }).bind("keypress.selectBox", function(e) {
                    s.handleKeyPress(e)
                }).bind("open.selectBox", function(e, t) {
                    t && !0 === t._selectBox || s.showMenu()
                }).bind("close.selectBox", function(e, t) {
                    t && !0 === t._selectBox || s.hideMenus()
                }).insertAfter(n);
                var f = o.width() - p.outerWidth() - (parseInt(d.css("paddingLeft")) || 0) - (parseInt(d.css("paddingRight")) || 0);
                d.width(f), this.disableSelection(o)
            }
            n.addClass("selectBox").data("selectBox-control", o).data("selectBox-settings", r).hide()
        }, t.prototype.getOptions = function(t) {
            var n, o = e(this.selectElement),
                i = this,
                r = function(t, n) {
                    return t.children("OPTION, OPTGROUP").each(function() {
                        if (e(this).is("OPTION")) 0 < e(this).length ? i.generateOptions(e(this), n) : n.append("<li> </li>");
                        else {
                            var t = e('<li class="selectBox-optgroup" />');
                            t.text(e(this).attr("label")), n.append(t), n = r(e(this), n)
                        }
                    }), n
                };
            switch (t) {
                case "inline":
                    return n = e('<ul class="selectBox-options" />'), (n = r(o, n)).find("A").bind("mouseover.selectBox", function(t) {
                        i.addHover(e(this).parent())
                    }).bind("mouseout.selectBox", function(t) {
                        i.removeHover(e(this).parent())
                    }).bind("mousedown.selectBox", function(e) {
                        1 === e.which && (e.preventDefault(), o.selectBox("control").hasClass("selectBox-active") || o.selectBox("control").focus())
                    }).bind("mouseup.selectBox", function(t) {
                        1 === t.which && (i.hideMenus(), i.selectOption(e(this).parent(), t))
                    }), this.disableSelection(n), n;
                case "dropdown":
                    n = e('<ul class="selectBox-dropdown-menu selectBox-options" />'), (n = r(o, n)).data("selectBox-select", o).css("display", "none").appendTo("BODY").find("A").bind("mousedown.selectBox", function(t) {
                        1 === t.which && (t.preventDefault(), t.screenX === n.data("selectBox-down-at-x") && t.screenY === n.data("selectBox-down-at-y") && (n.removeData("selectBox-down-at-x").removeData("selectBox-down-at-y"), /android/i.test(navigator.userAgent.toLowerCase()) && /chrome/i.test(navigator.userAgent.toLowerCase()) && i.selectOption(e(this).parent()), i.hideMenus()))
                    }).bind("mouseup.selectBox", function(t) {
                        1 === t.which && (t.screenX === n.data("selectBox-down-at-x") && t.screenY === n.data("selectBox-down-at-y") || (n.removeData("selectBox-down-at-x").removeData("selectBox-down-at-y"), i.selectOption(e(this).parent()), i.hideMenus()))
                    }).bind("mouseover.selectBox", function(t) {
                        i.addHover(e(this).parent())
                    }).bind("mouseout.selectBox", function(t) {
                        i.removeHover(e(this).parent())
                    });
                    var a = o.attr("class") || "";
                    if ("" !== a) {
                        a = a.split(" ");
                        for (var s = 0; s < a.length; s++) n.addClass(a[s] + "-selectBox-dropdown-menu")
                    }
                    return this.disableSelection(n), n
            }
        }, t.prototype.getLabelClass = function() {
            return ("selectBox-label " + (e(this.selectElement).find("OPTION:selected").attr("class") || "")).replace(/\s+$/, "")
        }, t.prototype.getLabelText = function() {
            return e(this.selectElement).find("OPTION:selected").text() || " "
        }, t.prototype.setLabel = function() {
            var t = e(this.selectElement).data("selectBox-control");
            t && t.find(".selectBox-label").attr("class", this.getLabelClass()).text(this.getLabelText())
        }, t.prototype.destroy = function() {
            var t = e(this.selectElement),
                n = t.data("selectBox-control");
            n && (n.data("selectBox-options").remove(), n.remove(), t.removeClass("selectBox").removeData("selectBox-control").data("selectBox-control", null).removeData("selectBox-settings").data("selectBox-settings", null).show())
        }, t.prototype.refresh = function() {
            var t, n = e(this.selectElement).data("selectBox-control"),
                o = n.hasClass("selectBox-dropdown") ? "dropdown" : "inline";
            switch (n.data("selectBox-options").remove(), t = this.getOptions(o), n.data("selectBox-options", t), o) {
                case "inline":
                    n.append(t);
                    break;
                case "dropdown":
                    this.setLabel(), e("BODY").append(t)
            }
            "dropdown" === o && n.hasClass("selectBox-menuShowing") && this.showMenu()
        }, t.prototype.showMenu = function() {
            var t = this,
                n = e(this.selectElement),
                o = n.data("selectBox-control"),
                i = n.data("selectBox-settings"),
                r = o.data("selectBox-options");
            if (o.hasClass("selectBox-disabled")) return !1;
            this.hideMenus();
            var a = parseInt(o.css("borderBottomWidth")) || 0,
                s = parseInt(o.css("borderTopWidth")) || 0,
                l = o.offset(),
                c = i.topPositionCorrelation ? i.topPositionCorrelation : 0,
                u = i.bottomPositionCorrelation ? i.bottomPositionCorrelation : 0,
                d = r.outerHeight(),
                p = o.outerHeight(),
                f = parseInt(r.css("max-height")),
                h = e(window).scrollTop(),
                m = l.top - h,
                g = e(window).height() - (m + p),
                v = g < m && (null == i.keepInViewport || i.keepInViewport),
                y = v ? l.top - d + s + c : l.top + p - a - u;
            if (m < f && g < f)
                if (v) {
                    var b = f - (m - 5);
                    r.css({
                        "max-height": f - b + "px"
                    }), y += b
                } else b = f - (g - 5), r.css({
                    "max-height": f - b + "px"
                });
            if (r.data("posTop", v), r.width(o.innerWidth()).css({
                    top: y,
                    left: o.offset().left
                }).addClass("selectBox-options selectBox-options-" + (v ? "top" : "bottom")), n.triggerHandler("beforeopen")) return !1;
            var x = function() {
                n.triggerHandler("open", {
                    _selectBox: !0
                })
            };
            switch (i.menuTransition) {
                case "fade":
                    r.fadeIn(i.menuSpeed, x);
                    break;
                case "slide":
                    r.slideDown(i.menuSpeed, x);
                    break;
                default:
                    r.show(i.menuSpeed, x)
            }
            i.menuSpeed || x();
            var w = r.find(".selectBox-selected:first");
            this.keepOptionInView(w, !0), this.addHover(w), o.addClass("selectBox-menuShowing selectBox-menuShowing-" + (v ? "top" : "bottom")), e(document).bind("mousedown.selectBox", function(n) {
                if (1 === n.which) {
                    if (e(n.target).parents().andSelf().hasClass("selectBox-options")) return;
                    t.hideMenus()
                }
            })
        }, t.prototype.hideMenus = function() {
            0 !== e(".selectBox-dropdown-menu:visible").length && (e(document).unbind("mousedown.selectBox"), e(".selectBox-dropdown-menu").each(function() {
                var t = e(this),
                    n = t.data("selectBox-select"),
                    o = n.data("selectBox-control"),
                    i = n.data("selectBox-settings"),
                    r = t.data("posTop");
                if (n.triggerHandler("beforeclose")) return !1;
                var a = function() {
                    n.triggerHandler("close", {
                        _selectBox: !0
                    })
                };
                if (i) {
                    switch (i.menuTransition) {
                        case "fade":
                            t.fadeOut(i.menuSpeed, a);
                            break;
                        case "slide":
                            t.slideUp(i.menuSpeed, a);
                            break;
                        default:
                            t.hide(i.menuSpeed, a)
                    }
                    i.menuSpeed || a(), o.removeClass("selectBox-menuShowing selectBox-menuShowing-" + (r ? "top" : "bottom"))
                } else e(this).hide(), e(this).triggerHandler("close", {
                    _selectBox: !0
                }), e(this).removeClass("selectBox-menuShowing selectBox-menuShowing-" + (r ? "top" : "bottom"));
                t.css("max-height", ""), t.removeClass("selectBox-options-" + (r ? "top" : "bottom")), t.data("posTop", !1)
            }))
        }, t.prototype.selectOption = function(t, n) {
            var o = e(this.selectElement);
            t = e(t);
            var i, r = o.data("selectBox-control");
            if (o.data("selectBox-settings"), r.hasClass("selectBox-disabled")) return !1;
            if (0 === t.length || t.hasClass("selectBox-disabled")) return !1;
            o.attr("multiple") ? n.shiftKey && r.data("selectBox-last-selected") ? (t.toggleClass("selectBox-selected"), i = (i = t.index() > r.data("selectBox-last-selected").index() ? t.siblings().slice(r.data("selectBox-last-selected").index(), t.index()) : t.siblings().slice(t.index(), r.data("selectBox-last-selected").index())).not(".selectBox-optgroup, .selectBox-disabled"), t.hasClass("selectBox-selected") ? i.addClass("selectBox-selected") : i.removeClass("selectBox-selected")) : this.isMac && n.metaKey || !this.isMac && n.ctrlKey ? t.toggleClass("selectBox-selected") : (t.siblings().removeClass("selectBox-selected"), t.addClass("selectBox-selected")) : (t.siblings().removeClass("selectBox-selected"), t.addClass("selectBox-selected")), r.hasClass("selectBox-dropdown") && r.find(".selectBox-label").text(t.text());
            var a = 0,
                s = [];
            return o.attr("multiple") ? r.find(".selectBox-selected A").each(function() {
                s[a++] = e(this).attr("rel")
            }) : s = t.find("A").attr("rel"), r.data("selectBox-last-selected", t), o.val() !== s && (o.val(s), this.setLabel(), o.trigger("change")), !0
        }, t.prototype.addHover = function(t) {
            t = e(t), e(this.selectElement).data("selectBox-control").data("selectBox-options").find(".selectBox-hover").removeClass("selectBox-hover"), t.addClass("selectBox-hover")
        }, t.prototype.getSelectElement = function() {
            return this.selectElement
        }, t.prototype.removeHover = function(t) {
            t = e(t), e(this.selectElement).data("selectBox-control").data("selectBox-options").find(".selectBox-hover").removeClass("selectBox-hover")
        }, t.prototype.keepOptionInView = function(t, n) {
            if (t && 0 !== t.length) {
                var o = e(this.selectElement).data("selectBox-control"),
                    i = o.data("selectBox-options"),
                    r = o.hasClass("selectBox-dropdown") ? i : i.parent(),
                    a = parseInt(t.offset().top - r.position().top),
                    s = parseInt(a + t.outerHeight());
                n ? r.scrollTop(t.offset().top - r.offset().top + r.scrollTop() - r.height() / 2) : (a < 0 && r.scrollTop(t.offset().top - r.offset().top + r.scrollTop()), s > r.height() && r.scrollTop(t.offset().top + t.outerHeight() - r.offset().top + r.scrollTop() - r.height()))
            }
        }, t.prototype.handleKeyDown = function(t) {
            var n = e(this.selectElement),
                o = n.data("selectBox-control"),
                i = o.data("selectBox-options"),
                r = n.data("selectBox-settings"),
                a = 0,
                s = 0;
            if (!o.hasClass("selectBox-disabled")) switch (t.keyCode) {
                case 8:
                    t.preventDefault(), this.typeSearch = "";
                    break;
                case 9:
                case 27:
                    this.hideMenus(), this.removeHover();
                    break;
                case 13:
                    o.hasClass("selectBox-menuShowing") ? (this.selectOption(i.find("LI.selectBox-hover:first"), t), o.hasClass("selectBox-dropdown") && this.hideMenus()) : this.showMenu();
                    break;
                case 38:
                case 37:
                    if (t.preventDefault(), o.hasClass("selectBox-menuShowing")) {
                        var l = i.find(".selectBox-hover").prev("LI");
                        for (a = i.find("LI:not(.selectBox-optgroup)").length, s = 0;
                            (0 === l.length || l.hasClass("selectBox-disabled") || l.hasClass("selectBox-optgroup")) && (0 === (l = l.prev("LI")).length && (l = r.loopOptions ? i.find("LI:last") : i.find("LI:first")), !(++s >= a)););
                        this.addHover(l), this.selectOption(l, t), this.keepOptionInView(l)
                    } else this.showMenu();
                    break;
                case 40:
                case 39:
                    if (t.preventDefault(), o.hasClass("selectBox-menuShowing")) {
                        var c = i.find(".selectBox-hover").next("LI");
                        for (a = i.find("LI:not(.selectBox-optgroup)").length, s = 0;
                            (0 === c.length || c.hasClass("selectBox-disabled") || c.hasClass("selectBox-optgroup")) && (0 === (c = c.next("LI")).length && (c = r.loopOptions ? i.find("LI:first") : i.find("LI:last")), !(++s >= a)););
                        this.addHover(c), this.selectOption(c, t), this.keepOptionInView(c)
                    } else this.showMenu()
            }
        }, t.prototype.handleKeyPress = function(t) {
            var n = e(this.selectElement).data("selectBox-control"),
                o = n.data("selectBox-options"),
                i = this;
            if (!n.hasClass("selectBox-disabled")) switch (t.keyCode) {
                case 9:
                case 27:
                case 13:
                case 38:
                case 37:
                case 40:
                case 39:
                    break;
                default:
                    n.hasClass("selectBox-menuShowing") || this.showMenu(), t.preventDefault(), clearTimeout(this.typeTimer), this.typeSearch += String.fromCharCode(t.charCode || t.keyCode), o.find("A").each(function() {
                        if (e(this).text().substr(0, i.typeSearch.length).toLowerCase() === i.typeSearch.toLowerCase()) return i.addHover(e(this).parent()), i.selectOption(e(this).parent(), t), i.keepOptionInView(e(this).parent()), !1
                    }), this.typeTimer = setTimeout(function() {
                        i.typeSearch = ""
                    }, 1e3)
            }
        }, t.prototype.enable = function() {
            var t = e(this.selectElement);
            t.prop("disabled", !1);
            var n = t.data("selectBox-control");
            n && n.removeClass("selectBox-disabled")
        }, t.prototype.disable = function() {
            var t = e(this.selectElement);
            t.prop("disabled", !0);
            var n = t.data("selectBox-control");
            n && n.addClass("selectBox-disabled")
        }, t.prototype.setValue = function(t) {
            var n = e(this.selectElement);
            n.val(t), null === (t = n.val()) && (t = n.children().first().val(), n.val(t));
            var o = n.data("selectBox-control");
            if (o) {
                var i = n.data("selectBox-settings"),
                    r = o.data("selectBox-options");
                this.setLabel(), r.find(".selectBox-selected").removeClass("selectBox-selected"), r.find("A").each(function() {
                    if ("object" == typeof t)
                        for (var n = 0; n < t.length; n++) e(this).attr("rel") == t[n] && e(this).parent().addClass("selectBox-selected");
                    else e(this).attr("rel") == t && e(this).parent().addClass("selectBox-selected")
                }), i.change && i.change.call(n)
            }
        }, t.prototype.setOptions = function(t) {
            var n = e(this.selectElement),
                o = n.data("selectBox-control");
            switch (typeof t) {
                case "string":
                    n.html(t);
                    break;
                case "object":
                    for (var i in n.html(""), t)
                        if (null !== t[i])
                            if ("object" == typeof t[i]) {
                                var r = e('<optgroup label="' + i + '" />');
                                for (var a in t[i]) r.append('<option value="' + a + '">' + t[i][a] + "</option>");
                                n.append(r)
                            } else {
                                var s = e('<option value="' + i + '">' + t[i] + "</option>");
                                n.append(s)
                            }
            }
            o && this.refresh()
        }, t.prototype.disableSelection = function(t) {
            e(t).css("MozUserSelect", "none").bind("selectstart", function(e) {
                e.preventDefault()
            })
        }, t.prototype.generateOptions = function(t, n) {
            var o = e("<li />"),
                i = e("<a />");
            o.addClass(t.attr("class")), o.data(t.data()), i.attr("rel", t.val()).text(t.text()), o.append(i), t.attr("disabled") && o.addClass("selectBox-disabled"), t.attr("selected") && o.addClass("selectBox-selected"), n.append(o)
        }, e.extend(e.fn, {
            selectBox: function(n, o) {
                var i;
                switch (n) {
                    case "control":
                        return e(this).data("selectBox-control");
                    case "settings":
                        if (!o) return e(this).data("selectBox-settings");
                        e(this).each(function() {
                            e(this).data("selectBox-settings", e.extend(!0, e(this).data("selectBox-settings"), o))
                        });
                        break;
                    case "options":
                        if (void 0 === o) return e(this).data("selectBox-control").data("selectBox-options");
                        e(this).each(function() {
                            (i = e(this).data("selectBox")) && i.setOptions(o)
                        });
                        break;
                    case "value":
                        if (void 0 === o) return e(this).val();
                        e(this).each(function() {
                            (i = e(this).data("selectBox")) && i.setValue(o)
                        });
                        break;
                    case "refresh":
                        e(this).each(function() {
                            (i = e(this).data("selectBox")) && i.refresh()
                        });
                        break;
                    case "enable":
                        e(this).each(function() {
                            (i = e(this).data("selectBox")) && i.enable(this)
                        });
                        break;
                    case "disable":
                        e(this).each(function() {
                            (i = e(this).data("selectBox")) && i.disable()
                        });
                        break;
                    case "destroy":
                        e(this).each(function() {
                            (i = e(this).data("selectBox")) && (i.destroy(), e(this).data("selectBox", null))
                        });
                        break;
                    case "instance":
                        return e(this).data("selectBox");
                    default:
                        e(this).each(function(o, i) {
                            e(i).data("selectBox") || e(i).data("selectBox", new t(i, n))
                        })
                }
                return e(this)
            }
        })
    }(jQuery), $(document).ready(function() {
        $("ul.tree.dhtml").hide(), $("ul.tree.dhtml").hasClass("dynamized") || ($("ul.tree.dhtml ul").prev().before("<span class='grower OPEN'> </span>"), $("ul.tree.dhtml ul li:last-child, ul.tree.dhtml li:last-child").addClass("last"), $("ul.tree.dhtml span.grower.OPEN").addClass("CLOSE").removeClass("OPEN").parent().find("ul:first").hide(), $("ul.tree.dhtml").show(), $("ul.tree.dhtml .selected").parents().each(function() {
            $(this).is("ul") && toggleBranch($(this).prev().prev(), !0)
        }), toggleBranch($("ul.tree.dhtml .selected").prev(), !0), $("ul.tree.dhtml span.grower").click(function() {
            toggleBranch($(this))
        }), $("ul.tree.dhtml").addClass("dynamized"), $("ul.tree.dhtml").removeClass("dhtml"))
    }), $(document).ready(function() {
        $("#newsletter-input").on({
            focus: function() {
                $(this).val() != placeholder_blocknewsletter && $(this).val() != msg_newsl || $(this).val("")
            },
            blur: function() {
                "" == $(this).val() && $(this).val(placeholder_blocknewsletter)
            }
        });
        var e = "alert alert-danger";
        "undefined" == typeof nw_error || nw_error || (e = "alert alert-success"), "undefined" != typeof msg_newsl && msg_newsl && ($("#columns").prepend('<div class="clearfix"></div><p class="' + e + '"> ' + alert_blocknewsletter + "</p>"), $("html, body").animate({
            scrollTop: $("#columns").offset().top
        }, "slow"))
    });
var instantSearchQueries = [];

function stopInstantSearchQueries() {
    for (var e = 0; e < instantSearchQueries.length; e++) instantSearchQueries[e].abort();
    instantSearchQueries = []
}

function startSlider() {
    defaultSlider.unslider({
        speed: parseInt(homeslider_speed),
        delay: homeslider_pause,
        keys: !0,
        dots: !0,
        fluid: !0
    });
    var e = defaultSlider.unslider();
    $(".unslider-arrow").on("click", function(t) {
        var n = this.className.split(" ")[1];
        e.data("unslider")[n](), t.preventDefault()
    })
}

function displayNotification_AC() {
    var e = '<div id="adult_wrapper"><div id="deluxe_adult_content" style="background-color:' + user_options_AC.dlxColor + "; opacity:" + user_options_AC.dlxOpacity + '">';
    e += '<div id="adultcontent">', e += '<div id="center">', e += urldecode_AC(nl2br_AC(user_options_AC.messageContent, !0)), e += '<div id="buttons">', e += '<p style="text-align:right" id="ok">', e += '<a href="#" id="adultcontentOK" onClick=\'JavaScript:setCookie_AC("' + user_options_AC.cookieName + "\",365);'>" + user_options_AC.okText + "</a>", e += "</p>", e += '<p style="text-align:inherit" id="warning">', e += '<a id="adultcontentnotOK" href="' + user_options_AC.redirectLink + "\" onClick='JavaScript:killCookies_AC();'>" + user_options_AC.notOkText + "</a>", e += "</p>", e += "</div>", e += "</div", e += "</div", e += "</div></div>", jQuery("body").prepend(e)
}

function getCookie_AC(e) {
    var t, n, o, i = document.cookie.split(";");
    for (t = 0; t < i.length; t++)
        if (n = i[t].substr(0, i[t].indexOf("=")), o = i[t].substr(i[t].indexOf("=") + 1), (n = n.replace(/^\s+|\s+$/g, "")) == e) return unescape(o);
    return null
}

function _setCookie_AC_AC(e, t, n, o, i, r, a, s) {
    var l = e + "=" + escape(t);
    n && (l += "; expires=" + new Date(n, o, i).toGMTString()), r && (l += "; path=" + escape(r)), a && (l += "; domain=" + escape(a)), s && (l += "; secure"), document.cookie = l
}

function setCookie_AC(e, t) {
    var n = new Date;
    n.setDate(n.getDate() + t), _setCookie_AC_AC(escape(e), escape("accepted"), n.getFullYear(), n.getMonth(), n.getDay(), escape("/"), document.domain);
    var o = document.getElementById("adultcontent");
    o && (o.innerHTML = "", null != document.getElementById("deluxe_adult_content") && (document.getElementById("adult_wrapper").style.display = "none"))
}

function getCookie(e) {
    var t = document.cookie,
        n = e + "=",
        o = t.indexOf("; " + n);
    if (-1 == o) {
        if (0 != (o = t.indexOf(n))) return null
    } else {
        o += 2;
        var i = document.cookie.indexOf(";", o); - 1 == i && (i = t.length)
    }
    return decodeURI(t.substring(o + n.length, i))
}

function checkCookie_AC() {
    if (null == getCookie("_gat_UA-70811397-1")) {
        var e = user_options_AC.cookieName,
            t = getCookie_AC(e);
        null != t && "" != t ? setCookie_AC(e, 365) : displayNotification_AC()
    } else setCookie_AC(e, 365)
}

function nl2br_AC(e, t) {
    return (e + "").replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, "$1" + (t || void 0 === t ? "<br />" : "<br>") + "$2")
}

function killCookies_AC() {
    jQuery("div#acontentwarning p#buttons").html("Clearing cookies and redirecting...");
    for (var e = document.cookie.split(";"), t = 0; t < e.length; t++) _setCookie_AC_AC(e[t].split("=")[0], -1, 1970, 1, 1, user_options_AC.cookiePath, user_options_AC.cookieDomain);
    jQuery.getJSON(user_options_AC.ajaxUrl, {
        path: user_options_AC.cookiePath,
        domain: user_options_AC.cookieDomain
    }, function(e) {}), window.location = user_options_AC.redirectLink
}

function urldecode_AC(e) {
    return decodeURIComponent((e + "").replace(/\+/g, "%20"))
}
jQuery.fn.ph_megamenu = function(e) {
    var t = {
        marker: !0,
        marker_content_off: "[+]",
        marker_content_on: "[-]",
        speed: 300
    };
    $.extend(t, e);
    var n, o = $(".ph_megamenu"),
        i = !1;

    function r() {
        return compensante = scrollCompensate(), 991 <= $(document).width() + compensante && $(document).width() + compensante <= 1200 ? n = "medium" : 1200 < $(document).width() + compensante && (n = "big"), n
    }

    function a(e, t) {
        compensante = scrollCompensate(), $(document).width() + compensante <= 990 && 0 == i ? (l("enable"), i = !0, $(o).find(".has-submenu").on("click", ".marker-off", function(e) {
            $(this).parent().parent().find("> .dropdown, > .mega-menu").slideDown(), e.preventDefault(), $(this).hide(), $(this).parent().find(".marker-on").css("display", "inline-block")
        }), $(o).find(".has-submenu").on("click", ".marker-on", function(e) {
            $(this).parent().parent().find(".dropdown, .mega-menu").slideUp(), e.preventDefault(), $(this).hide(), $(this).parent().find(".marker-off").css("display", "inline-block")
        })) : 991 <= $(document).width() + compensante && (l("disable"), i = !1, $(o).find("li"), "medium" == t && 1200 <= $(window).width() + compensante || "big" == t && $(window).width() + compensante <= 1200 || !0 === e ? (t = r(), c(!0)) : c())
    }

    function s(e) {
        "show" == e ? ($("#ph_megamenu_wrapper, a.hide_megamenu").show(), $("a.show_megamenu").hide()) : ($("a.show_megamenu").show(), $("#ph_megamenu_wrapper, a.hide_megamenu").hide())
    }

    function l(e) {
        "enable" == e ? ($("a.show_megamenu").show(), $("#ph_megamenu_wrapper").hide(), $("#ph_megamenu").find(".marker-off").show(), $("#ph_megamenu").addClass("mobile_menu")) : ($("a.show_megamenu,a.hide_megamenu").hide(), $("#ph_megamenu_wrapper").show(), $("#ph_megamenu").find(".marker-on, .marker-off, .dropdown, .megamenu").hide(), $("#ph_megamenu").removeClass("mobile_menu"))
    }

    function c(e) {
        $("#ph_megamenu").outerWidth(), $(o).find(".has-submenu, .mega-menu").each(function(t) {
            var n = $(this).find(".mega-menu");
            if (!0 === e && 0 < n.length) {
                var o = 2;
                $(this).find(".ph-col:not(.ph-hidden-desktop,.ph-hidden-mobile)").each(function() {
                    if ($(this).hasClass("ph-new-row")) return !1;
                    o += $(this).width() + 30
                }), n.css("width", o)
            } else o = n.width();
            var i = $(this).position(),
                r = i.left - $("#ph_megamenu").position().left + o;
            r > $(".container").width() && n.css("left", $("#ph_megamenu").width() - o + $("#ph_megamenu").position().left), r < $(".container").width() && n.css("left", i.left), r == $(".container").width() && n.css("left", i.left);
            var a = $("#ph_megamenu").width(),
                s = $(this).position().left,
                l = $(this).find(".dropdown .dropdown");
            a - s < 440 && l.css("left", "-220px")
        })
    }
    $(o).find(">li").has("ul").each(function() {
        $(this).addClass("has-submenu")
    }), 1 == t.marker && $(o).find("a").each(function() {
        0 < $(this).siblings(".dropdown, .mega-menu").length && ($(this).append("<span class='marker marker-off'>" + t.marker_content_off + "</span>"), $(this).append("<span class='marker marker-on'>" + t.marker_content_on + "</span>"))
    }), $(window).resize(function() {
        a(!1, n)
    }), $(window).load(function() {
        a(!0, r()), c()
    }), $(".ph_megamenu_mobile_toggle").on("click", "a.show_megamenu", function(e) {
        s("show"), e.preventDefault()
    }), $(".ph_megamenu_mobile_toggle").on("click", "a.hide_megamenu", function(e) {
        s("hide"), e.preventDefault()
    })
}, jQuery(window).load(function() {
    checkCookie_AC()
});
var GoogleAnalyticEnhancedECommerce = {
    setCurrency: function(e) {
        ga("set", "&cu", e)
    },
    add: function(e, t, n) {
        var o = {},
            i = {},
            r = ["id", "name", "category", "brand", "variant", "price", "quantity", "coupon", "list", "position", "dimension1"],
            a = ["id", "affiliation", "revenue", "tax", "shipping", "coupon", "list", "step", "option"];
        if (null != e)
            for (var s in n && void 0 !== e.quantity && delete e.quantity, e)
                for (var l = 0; l < r.length; l++) s.toLowerCase() == r[l] && null != e[s] && (o[s.toLowerCase()] = e[s]);
        if (null != t)
            for (var c in t)
                for (var u = 0; u < a.length; u++) c.toLowerCase() == a[u] && (i[c.toLowerCase()] = t[c]);
        n ? ga("ec:addImpression", o) : ga("ec:addProduct", o)
    },
    addProductDetailView: function(e) {
        this.add(e), ga("ec:setAction", "detail"), ga("send", "event", "UX", "detail", "Product Detail View", {
            nonInteraction: 1
        })
    },
    addToCart: function(e) {
        this.add(e), ga("ec:setAction", "add"), ga("send", "event", "UX", "click", "Add to Cart")
    },
    removeFromCart: function(e) {
        this.add(e), ga("ec:setAction", "remove"), ga("send", "event", "UX", "click", "Remove From cart")
    },
    addProductImpression: function(e) {},
    refundByOrderId: function(e) {
        ga("ec:setAction", "refund", {
            id: e.id
        }), ga("send", "event", "Ecommerce", "Refund", {
            nonInteraction: 1
        })
    },
    refundByProduct: function(e) {
        ga("ec:setAction", "refund", {
            id: e.id
        }), ga("send", "event", "Ecommerce", "Refund", {
            nonInteraction: 1
        })
    },
    addProductClick: function(e) {
        jQuery('a[href$="' + e.url + '"].quick-view').on("click", function() {
            GoogleAnalyticEnhancedECommerce.add(e), ga("ec:setAction", "click", {
                list: e.list
            }), ga("send", "event", "Product Quick View", "click", e.list, {
                hitCallback: function() {
                    return !ga.loaded
                }
            })
        })
    },
    addProductClickByHttpReferal: function(e) {
        this.add(e), ga("ec:setAction", "click", {
            list: e.list
        }), ga("send", "event", "Product Click", "click", e.list, {
            nonInteraction: 1,
            hitCallback: function() {
                return !ga.loaded
            }
        })
    },
    addTransaction: function(e) {
        ga("ec:setAction", "purchase", e), ga("send", "event", "Transaction", "purchase", {
            hitCallback: function() {
                $.get(e.url, {
                    orderid: e.id,
                    customer: e.customer
                })
            }
        })
    },
    addCheckout: function(e) {
        ga("ec:setAction", "checkout", {
            step: e
        })
    }
};


function showComments(elemnt) {
  var popup = document.getElementById(elemnt);
  var popupwrap = document.getElementById('hover_bkgr_fricc');
  popup.style.display = "inline-block";
  popupwrap.style.display = "block";
}
function cerrarComentarios(elemnt) {
  var popup = document.getElementById(elemnt);
  var popupwrap = document.getElementById('hover_bkgr_fricc');
  popup.style.display = "none";
  popupwrap.style.display = "none";
}
function addCarro(url, imgurl) {
    $.ajax({
        type: 'post',
        url: url,
        success: function(response) {
            var modal = document.getElementById('layer_cart');
            modal.style.display = "block";
            var imgdiv = document.getElementById('product-image-container').innerHTML='<img src="img/'+imgurl+'" />';
            
        },
        error: function(xhr, status, error) {
            console.log("Status of error message" + status + "Error is" + error);
        }
    });
};
$(document).scroll(function(){
    
    if($('#collapseOne2').hasClass('primera-accion')){

        $('#collapseOne2').removeClass("in"); 
        $('#collapseOne2').removeClass("primera-accion"); 
 }
});


function tocClick(){
    $('#toc_list_cont').toggle();
    
    if ($('#spanShow').text() == "Ocultar"){
            $('#spanShow').text("Mostrar") ;
    }else{$('#spanShow').text("Ocultar") ;}
}


if(document.referrer.split('/')[2]==location.hostname){
    //User came from other domain or from direct
    document.getElementById('link-volver').innerHTML = "Volver atrás";
    document.getElementById('link-volver').href = document.referrer;
}
$(document).on("change","input[name='complejo-radicular-hesi']",function(){var radios=document.getElementsByName('complejo-radicular-hesi');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('complejo-radicular-hesi_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('complejo-radicular-hesi_link').href=newText;document.getElementById('complejo-radicular-hesi-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
function addCarrito(url,imgurl){$.ajax({type:'post',url:url,success:function(response)
{var modal=document.getElementById('myModal');var span=document.getElementsByClassName("close2")[0];modal.style.display="block";document.getElementById('modal-img').src=imgurl;span.onclick=function(){modal.style.display="none";window.onclick=function(event){if(event.target==modal){modal.style.display="none";}}}
document.getElementsByClassName("whatsappBlock2")[0].style.display="block";},error:function(xhr,status,error)
{console.log("Status of error message"+status+"Error is"+error);}});};
$(document).on("change","input[name='fertilizante-amax-root']",function(){var radios=document.getElementsByName('fertilizante-amax-root');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('fertilizante-amax-root_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('fertilizante-amax-root_link').href=newText;document.getElementById('fertilizante-amax-root-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
$(document).on("change","input[name='tnt-complex-hesi']",function(){var radios=document.getElementsByName('tnt-complex-hesi');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('tnt-complex-hesi_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('tnt-complex-hesi_link').href=newText;document.getElementById('tnt-complex-hesi-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
$(document).on("change","input[name='bio-grow-biobizz']",function(){var radios=document.getElementsByName('bio-grow-biobizz');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('bio-grow-biobizz_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('bio-grow-biobizz_link').href=newText;document.getElementById('bio-grow-biobizz-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
$(document).on("change","input[name='delta-9-bioestimulador-cannabiogen']",function(){var radios=document.getElementsByName('delta-9-bioestimulador-cannabiogen');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('delta-9-bioestimulador-cannabiogen_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('delta-9-bioestimulador-cannabiogen_link').href=newText;document.getElementById('delta-9-bioestimulador-cannabiogen-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
$(document).on("change","input[name='big-one-top-crop']",function(){var radios=document.getElementsByName('big-one-top-crop');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('big-one-top-crop_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('big-one-top-crop_link').href=newText;document.getElementById('big-one-top-crop-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
$(document).on("change","input[name='bud-ignitor']",function(){var radios=document.getElementsByName('bud-ignitor');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('bud-ignitor_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('bud-ignitor_link').href=newText;document.getElementById('bud-ignitor-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
$(document).on("change","input[name='bio-bloom-biobizz']",function(){var radios=document.getElementsByName('bio-bloom-biobizz');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('bio-bloom-biobizz_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('bio-bloom-biobizz_link').href=newText;document.getElementById('bio-bloom-biobizz-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
$(document).on("change","input[name='terra-flores-canna']",function(){var radios=document.getElementsByName('terra-flores-canna');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('terra-flores-canna_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('terra-flores-canna_link').href=newText;document.getElementById('terra-flores-canna-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
$(document).on("change","input[name='monster-bloom-grotek']",function(){var radios=document.getElementsByName('monster-bloom-grotek');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('monster-bloom-grotek_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('monster-bloom-grotek_link').href=newText;document.getElementById('monster-bloom-grotek-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
$(document).on("change","input[name='pk-13-14-canna']",function(){var radios=document.getElementsByName('pk-13-14-canna');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('pk-13-14-canna_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('pk-13-14-canna_link').href=newText;document.getElementById('pk-13-14-canna-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
$(document).on("change","input[name='rhizotonic-canna']",function(){var radios=document.getElementsByName('rhizotonic-canna');var value;for(var i=0;i<radios.length;i++){if(radios[i].checked){value=radios[i].value;var res=value.split(",");var link=document.getElementById('rhizotonic-canna_link').href.toString();var reExp=/id_product_attribute=[0-9]*/;var newText=link.replace(reExp,'id_product_attribute='+res[0]);document.getElementById('rhizotonic-canna_link').href=newText;document.getElementById('rhizotonic-canna-price').innerHTML=parseFloat(res[1]).toFixed(2)+' €';}}});
