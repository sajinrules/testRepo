/*
 * Superfish v1.7.2 - jQuery menu widget
 * Copyright (c) 2013 Joel Birch
 *
 * Dual licensed under the MIT and GPL licenses:
 *http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 */

(function () {
    function e(e) {
        this.path = e;
        var t = this.path.split("."),
            n = t.slice(0, t.length - 1).join("."),
            r = t[t.length - 1];
        this.at_2x_path = n + "@2x." + r
    }
    function t(t) {
        this.el = t, this.path = new e(this.el.getAttribute("src"));
        var n = this;
        this.path.check_2x_variant(function (e) {
            e && n.swap()
        })
    }
    var n = typeof exports == "undefined" ? window : exports;
    n.RetinaImagePath = e, e.confirmed_paths = [], e.prototype.is_external = function () {
        return !!this.path.match(/^https?\:/i) && !this.path.match("//" + document.domain)
    }, e.prototype.check_2x_variant = function (t) {
        var n, r = this;
        if (this.is_external()) return t(!1);
        if (this.at_2x_path in e.confirmed_paths) return t(!0);
        n = new XMLHttpRequest, n.open("HEAD", this.at_2x_path), n.onreadystatechange = function () {
            return n.readyState != 4 ? t(!1) : n.status >= 200 && n.status <= 399 ? (e.confirmed_paths.push(r.at_2x_path), t(!0)) : t(!1)
        }, n.send()
    }, n.RetinaImage = t, t.prototype.swap = function (e) {
        function t() {
            n.el.complete ? (n.el.setAttribute("width", n.el.offsetWidth), n.el.setAttribute("height", n.el.offsetHeight), n.el.setAttribute("src", e)) : setTimeout(t, 5)
        }
        typeof e == "undefined" && (e = this.path.at_2x_path);
        var n = this;
        t()
    }, n.devicePixelRatio > 1 && (window.onload = function () {
        var e = document.getElementsByTagName("img"),
            n = [],
            r, i;
        for (r = 0; r < e.length; r++) i = e[r], n.push(new t(i))
    })
})(), define("retina", function () {}), define("modules/translate3d-support", [], function () {
    function e() {
        var e = "translate3d(0px, 0px, 0px)",
            t = document.createElement("div");
        t.style.cssText = "  -moz-transform:" + e + "; -ms-transform:" + e + "; -o-transform:" + e + "; -webkit-transform:" + e + "; transform:" + e;
        var n = /translate3d\(0px, 0px, 0px\)/g,
            r = t.style.cssText.match(n);
        return r !== null && r.length == 1
    }
    return e
}),
function (e) {
    var t = function () {
        var t = {
            bcClass: "sf-breadcrumb",
            menuClass: "sf-js-enabled",
            anchorClass: "sf-with-ul",
            menuArrowClass: "sf-arrows"
        }, n = /iPhone|iPad|iPod/i.test(navigator.userAgent),
            r = function () {
                var e = document.documentElement.style;
                return "behavior" in e && "fill" in e && /iemobile/i.test(navigator.userAgent)
            }(),
            i = function () {
                n && e(window).load(function () {
                    e("body").children().on("click", e.noop)
                })
            }(),
            s = function (e, n) {
                var r = t.menuClass;
                n.cssArrows && (r += " " + t.menuArrowClass), e.toggleClass(r)
            }, o = function (n, r) {
                return n.find("li." + r.pathClass).slice(0, r.pathLevels).addClass(r.hoverClass + " " + t.bcClass).filter(function () {
                    return e(this).children("ul").hide().show().length
                }).removeClass(r.pathClass)
            }, u = function (e) {
                e.children("a").toggleClass(t.anchorClass)
            }, a = function (e) {
                var t = e.css("ms-touch-action");
                t = t === "pan-y" ? "auto" : "pan-y", e.css("ms-touch-action", t)
            }, f = function (t, i) {
                var s = "li:has(ul)";
                e.fn.hoverIntent && !i.disableHI ? t.hoverIntent(c, h, s) : t.on("mouseenter.superfish", s, c).on("mouseleave.superfish", s, h);
                var o = "MSPointerDown.superfish";
                n || (o += " touchend.superfish"), r && (o += " mousedown.superfish"), t.on("focusin.superfish", "li", c).on("focusout.superfish", "li", h).on(o, "a", l)
            }, l = function (t) {
                var n = e(this),
                    r = n.siblings("ul");
                r.length > 0 && r.is(":hidden") && (n.one("click.superfish", !1), t.type === "MSPointerDown" ? n.trigger("focus") : e.proxy(c, n.parent("li"))())
            }, c = function () {
                var t = e(this),
                    n = v(t);
                clearTimeout(n.sfTimer), t.siblings().superfish("hide").end().superfish("show")
            }, h = function () {
                var t = e(this),
                    r = v(t);
                n ? e.proxy(p, t, r)() : (clearTimeout(r.sfTimer), r.sfTimer = setTimeout(e.proxy(p, t, r), r.delay))
            }, p = function (t) {
                t.retainPath = e.inArray(this[0], t.$path) > -1, this.superfish("hide"), this.parents("." + t.hoverClass).length || (t.onIdle.call(d(this)), t.$path.length && e.proxy(c, t.$path)())
            }, d = function (e) {
                return e.closest("." + t.menuClass)
            }, v = function (e) {
                return d(e).data("sf-options")
            };
        return {
            hide: function (t) {
                if (this.length) {
                    var n = this,
                        r = v(n);
                    if (!r) return this;
                    var i = r.retainPath === !0 ? r.$path : "",
                        s = n.find("li." + r.hoverClass).add(this).not(i).removeClass(r.hoverClass).children("ul"),
                        o = r.speedOut;
                    t && (s.show(), o = 0), r.retainPath = !1, r.onBeforeHide.call(s), s.stop(!0, !0).animate(r.animationOut, o, function () {
                        var t = e(this);
                        r.onHide.call(t)
                    })
                }
                return this
            },
            show: function () {
                var e = v(this);
                if (!e) return this;
                var t = this.addClass(e.hoverClass),
                    n = t.children("ul");
                return e.onBeforeShow.call(n), n.stop(!0, !0).animate(e.animation, e.speed, function () {
                    e.onShow.call(n)
                }), this
            },
            destroy: function () {
                return this.each(function () {
                    var n = e(this),
                        r = n.data("sf-options"),
                        i = n.find("li:has(ul)");
                    if (!r) return !1;
                    clearTimeout(r.sfTimer), s(n, r), u(i), a(n), n.off(".superfish").off(".hoverIntent"), i.children("ul").attr("style", function (e, t) {
                        return t.replace(/display[^;]+;?/g, "")
                    }), r.$path.removeClass(r.hoverClass + " " + t.bcClass).addClass(r.pathClass), n.find("." + r.hoverClass).removeClass(r.hoverClass), r.onDestroy.call(n), n.removeData("sf-options")
                })
            },
            init: function (n) {
                return this.each(function () {
                    var r = e(this);
                    if (r.data("sf-options")) return !1;
                    var i = e.extend({}, e.fn.superfish.defaults, n),
                        l = r.find("li:has(ul)");
                    i.$path = o(r, i), r.data("sf-options", i), s(r, i), u(l), a(r), f(r, i), l.not("." + t.bcClass).superfish("hide", !0), i.onInit.call(this)
                })
            }
        }
    }();
    e.fn.superfish = function (n, r) {
        return t[n] ? t[n].apply(this, Array.prototype.slice.call(arguments, 1)) : typeof n == "object" || !n ? t.init.apply(this, arguments) : e.error("Method " + n + " does not exist on jQuery.fn.superfish")
    }, e.fn.superfish.defaults = {
        hoverClass: "sfHover",
        pathClass: "overrideThisToUse",
        pathLevels: 1,
        delay: 800,
        animation: {
            opacity: "show"
        },
        animationOut: {
            opacity: "hide"
        },
        speed: "normal",
        speedOut: "fast",
        cssArrows: !0,
        disableHI: !1,
        onInit: e.noop,
        onBeforeShow: e.noop,
        onShow: e.noop,
        onBeforeHide: e.noop,
        onHide: e.noop,
        onIdle: e.noop,
        onDestroy: e.noop
    }, e.fn.extend({
        hideSuperfishUl: t.hide,
        showSuperfishUl: t.show
    })
}(jQuery), define("superfish", function () {}), define("modules/resize", [], function () {
    function e(e, t) {
        var n = 0;
        return jQuery(window).resize(function () {
            clearTimeout(n), n = setTimeout(e, t)
        })
    }
    return e
}), define("modules/image-load", [], function () {
    function t(t, n, r) {
        var i = 0,
            s = !1;
        t.one("load", function () {
            i++, i == t.length && !s && n()
        }).each(function () {
            this.complete && e(this).load()
        });
        if (0 == t.length) {
            n();
            return
        }
        typeof r != "undefined" && setTimeout(function () {
            i < t.length && (s = !0, n())
        }, r)
    }
    var e = jQuery;
    return t
}), define("modules/navigation", ["../superfish", "modules/resize", "modules/image-load"], function (e, t, n) {
    function b(e) {
        if (g) return;
        var t = i.scrollTop(),
            n = !1;
        typeof e != "undefined" && e === !0 && (n = !0), t > s && (!y || n) ? (f.css({
            height: o
        }), a.css({
            maxHeight: o
        }), h.css({
            height: o,
            lineHeight: o + "px"
        }), y = !0) : t < s && (y || n) && (f.css({
            height: p
        }), a.css({
            maxHeight: p
        }), h.css({
            height: p,
            lineHeight: p + "px"
        }), y = !1)
    }
    function w() {
        var e = "fixed";
        i.width() < 980 ? (e = "absolute", g = !0) : g = !1, u.css({
            position: e
        })
    }
    var r = jQuery,
        i = r(window),
        s = 1,
        o = 50,
        u = r("header"),
        a = u.find(".logo img"),
        f = u.find("> .container"),
        l = u.find(".navigation"),
        c = l.find("> ul"),
        h = c.find("> li > a"),
        p = u.height(),
        d = parseInt(r("html").css("marginTop")),
        v = c.find("#menu-item-search"),
        m = v.find(".search-template"),
        g = !1,
        y = !1;
    c.superfish({
        delay: 100
    }), c.find(".sub-menu .sub-menu").parent().addClass("menu-item-parent"), u.css({
        width: "100%",
        top: d,
        zIndex: 200
    }), i.scroll(b), n(a, function () {
        a.height() > p && (p = a.height(), b(!0))
    }, 5e3), t(w, 100), w(), v.find("> a").click(function (e) {
        e.preventDefault(), e.stopPropagation(), m.toggleClass("visible")
    }), m.click(function (e) {
        e.stopPropagation()
    }), r(document).click(function () {
        m.removeClass("visible")
    })
}), define("modules/navigation-mobile", ["modules/resize"], function (e) {
    function l() {
        n.hasClass("pushed-left") && (n.removeClass("pushed-left"), setTimeout(function () {
            s.css({
                display: "none"
            })
        }, 330))
    }
    var t = jQuery,
        n = t("body"),
        r = t(document),
        i = t("body > .layout"),
        s = t(".navigation-mobile"),
        o = s.find(".navigation-close"),
        u = t(".navigation-button"),
        a = !1,
        f = "ontouchstart" in window || "onmsgesturechange" in window;
    f && s.css({
        overflowY: "scroll"
    }), u.click(function (e) {
        e.preventDefault(), e.stopPropagation(), n.hasClass("pushed-left") ? l() : (s.css({
            display: "block",
            height: i.outerHeight()
        }), n.toggleClass("pushed-left"))
    }), r.on("touchstart", function (e) {
        var n = e.originalEvent.touches[0] || e.originalEvent.changedTouches[0],
            r = t(n.target);
        if (r.is(s) || r.parents(".navigation-mobile").length) a = !0
    }).on("touchend", function () {
        setTimeout(function () {
            a = !1
        }, 1e3)
    }), o.click(l), s.click(function (e) {
        e.stopPropagation()
    }), r.click(function (e) {
        l()
    }), e(function () {
        if (a) return;
        l()
    }, 100)
}), define("modules/loadCss", [], function () {
    return function (e) {
        var t = document.createElement("link");
        t.type = "text/css", t.rel = "stylesheet", t.href = e, document.getElementsByTagName("head")[0].appendChild(t)
    }
}), define("modules/element-query", ["modules/resize"], function (e) {
    function n(e, t) {
        var n = e.attr(t);
        return typeof n != "undefined" && n !== !1
    }
    function r(e, t, r) {
        var i = null,
            s = e.outerWidth(),
            o = r == "min" ? "data-minwidth" : "data-maxwidth";
        for (var u = 0; u < t.length; u++) {
            var a = t[u];
            "min" == r ? a.value <= s && (i = a) : a.value >= s && (i = a)
        }
        if (i) {
            if (n(e, o)) {
                var f = parseInt(e.attr(o));
                if (f == i.value) return
            }
            e.attr(o, i.value), i.callback()
        } else e.removeAttr(o)
    }
    function i(t, n) {
        function f() {
            r(n, i, "min"), r(n, s, "max")
        }
        if (0 == t instanceof Array) return;
        var i = [],
            s = [];
        for (var o = 0; o < t.length; o++) {
            var u = t[o];
            if (0 == u instanceof Object) continue;
            if (!u.hasOwnProperty("operator") || !u.hasOwnProperty("value")) continue;
            var a = u.hasOwnProperty("callback") ? u.callback : function () {};
            switch (u.operator) {
                case "min-width":
                    (function (e, t) {
                        i.push({
                            value: e,
                            callback: t
                        })
                    })(u.value, a);
                    break;
                case "max-width":
                    (function (e, t) {
                        s.push({
                            value: e,
                            callback: t
                        })
                    })(u.value, a)
            }
        }
        f(), e(f, 100)
    }
    var t = jQuery;
    return i
}), define("modules/portfolio-listing", ["modules/loadCss", "modules/element-query", "modules/resize", "modules/image-load"], function (e, t, n, r) {
    function u() {
        o.each(function (e) {
            var t = i(this);
            setTimeout(function () {
                t.addClass("start-animation")
            }, e * 150)
        }), require(["jquery.mfp.min"], function () {
            e(theme_uri.css + "/magnific-popup.css"), s.find(".item-view-image-icon").magnificPopup({
                type: "image",
                closeBtnInside: !1,
                removalDelay: 300,
                mainClass: "portfolio-list-mfp"
            })
        }), require(["jquery.isotope.min"], function () {
            e(theme_uri.css + "/isotope.css"), s.each(function () {
                var e = i(this),
                    t = e.find(".isotope"),
                    n = e.find(".filter a"),
                    r = e.hasClass(".portfolio-style1") ? "fitRows" : "masonry";
                a(e, t), t.isotope({
                    itemSelector: ".item",
                    layoutMode: r,
                    animationEngine: "best-available",
                }), n.click(function (e) {
                    e.preventDefault();
                    var r = i(this),
                        s = r.attr("data-filter");
                    t.isotope({
                        filter: s
                    }), n.removeClass("current"), r.addClass("current"),
                    r.isotope("reLayout")
                })
            })
        })
    }
    function a(e, r) {
        t([{
            operator: "max-width",
            value: 730
        }, {
            operator: "max-width",
            value: 630
        }, {
            operator: "max-width",
            value: 480
        }], e), n(function () {
            r.isotope("reLayout")
        }, 100)
    }
    var i = jQuery,
        s = i(".portfolio-list");
    if (!s.length) return;
    var o = s.find("img");
    r(o, u)
}), define("modules/vertical-center", [], function () {
    var e = jQuery;
    return function (t) {
        t.each(function () {
            var t = e(this),
                n = t.parent(),
                r = (n.height() - t.outerHeight()) * .5,
                i = (n.width() - t.outerWidth()) * .5;
            t.css({
                top: r,
                left: i
            })
        })
    }
}), define("modules/portfolio", ["modules/loadCss", "modules/vertical-center"], function (e, t) {
    var n = jQuery;
    if (!n("body.single-portfolio").length) return;
    var r = n(".portfolio-related .image-overlay");
    r.length && (n(window).resize(function () {
        t(r)
    }), t(r));
    var i = n(".flexslider");
    if (i.find(".slides > li").length < 2) return;
    require(["jquery.flexslider-min"], function () {
        e(theme_uri.css + "/flexslider.css?bust=v2"), i.flexslider()
    })
}), define("modules/responsive-media", ["modules/resize"], function (e) {
    function r() {
        n.each(function () {
            var e = t(this),
                n = e.parent(),
                r = n.width();
            e.css({
                width: r,
                height: r * e.data("aspectRatio")
            })
        })
    }
    var t = jQuery,
        n = t('iframe[src^="http://www.youtube.com"],iframe[src^="http://player.vimeo.com"]');
    n.each(function () {
        t(this).data("aspectRatio", this.height / this.width).removeAttr("height").removeAttr("width")
    }), e(r, 100), r()
}), define("modules/comment-respond", [], function () {
    var e = jQuery,
        t = e("#respond"),
        n = e("#respond-wrap"),
        r = t.find("#cancel-comment-reply-link"),
        i = t.find('input[name="comment_parent"]');
    e(".comment-reply-link").each(function () {
        var n = e(this),
            s = n.parents().eq(2);
        n.click(function () {
            var e = n.parents(".comment").attr("data-id");
            return i.val(e), t.insertAfter(s), r.show(), !1
        })
    }), r.click(function (e) {
        e.preventDefault(), r.hide(), t.appendTo(n), i.val(0)
    })
}), define("modules/widget-testimonials", ["modules/resize"], function (e) {
    function r(e, n, r) {
        var i = n.width(),
            s = r.eq(e),
            o = s.find(".name");
        r.removeClass("current"), s.addClass("current"), n.stop().animate({
            height: s.height()
        }, {
            speed: 300
        }), o.css({
            visibility: "hidden",
            opacity: 0
        }), r.each(function (n) {
            var r = t(this);
            r.stop().animate({
                left: (n - e) * i
            }, {
                speed: 300,
                complete: function () {
                    o.css({
                        visibility: "visible",
                        opacity: 1
                    })
                }
            })
        })
    }
    function i(n, r) {
        e(function () {
            var e = r.filter(".current"),
                i = e.index();
            r.each(function (e) {
                var r = t(this),
                    s = n.width();
                r.css({
                    width: s,
                    left: (e - i) * s
                })
            }), n.stop().animate({
                height: e.height()
            }, {
                speed: 300
            })
        }, 100)
    }
    var t = jQuery,
        n = t(".widget_bj_testimonials");
    if (!n.length) return;
    n.each(function () {
        var e = t(this),
            n = e.find("ul"),
            s = n.find("> li"),
            o = s.eq(0),
            u = e.find(".testimonials-controls"),
            a = u.find(".next"),
            f = u.find(".previous");
        if (s.length < 2) {
            u.hide();
            return
        }
        s.each(function (e) {
            var r = t(this),
                i = n.width();
            r.css({
                position: "absolute",
                width: i,
                top: 0,
                left: e * i,
                display: "block"
            })
        }), o.addClass("current"), n.css({
            height: o.height()
        }), a.click(function (e) {
            e.preventDefault();
            var t = s.filter(".current").index(),
                i = t + 1;
            i >= s.length && (i = 0), r(i, n, s)
        }), f.click(function (e) {
            e.preventDefault();
            var t = s.filter(".current").index(),
                i = t - 1;
            i < 0 && (i = s.length - 1), r(i, n, s)
        }), i(n, s)
    })
}), define("modules/testimonials", ["modules/element-query"], function (e) {
    function r() {
        n.each(function () {
            var n = t(this);
            e([{
                operator: "max-width",
                value: 580
            }], n)
        })
    }
    function i() {
        var e = t(".testimonials");
        e.each(function () {
            var e = t(this),
                n = e.find(".testimonial"),
                r = e.find(".testimonials-controls");
            if (n.length < 2) {
                r.hide();
                return
            }
            n.not(":first-child").hide(), n.eq(0).addClass("current"), r.find(".next").click(function (e) {
                e.preventDefault();
                var t = n.filter(".current").index(),
                    r = t + 1;
                r >= n.length && (r = 0), s(r, n)
            }), r.find(".previous").click(function (e) {
                e.preventDefault();
                var t = n.filter(".current").index(),
                    r = t - 1;
                r < 0 && (r = n.length - 1), s(r, n)
            })
        })
    }
    function s(e, t) {
        var n = t.filter(".current"),
            r = t.eq(e);
        if (n.is(":animated") || r.is(":animated")) return;
        n.fadeOut({
            complete: function () {
                r.fadeIn({
                    complete: function () {
                        t.removeClass("current"), r.addClass("current"),
                        console.log('fade in 3');
                        r.isotope("reLayout")
                    }
                })
            }
        })
    }
    var t = jQuery,
        n = t(".testimonial");
    if (!n.length) return;
    r(), i()
}), define("modules/image-carousel", ["modules/image-load"], function (e) {
    var t = jQuery,
        n = t(".image-carousel");
    if (!n.length) return;
    require(["jquery.flexisel"], function () {
        n.each(function () {
            var n = t(this),
                r = n.find("img"),
                i = n.attr("data-items");
            e(r, function () {
                n.flexisel({
                    visibleItems: i
                })
            })
        })
    })
}), define("modules/accordion", [], function () {
    function n(e) {
        var t = e.find(".tab-button span"),
            n = e.find(".body");
        e.hasClass("closed") ? (n.slideDown(function () {
            t.attr("class", "icon-minus")
        }), e.removeClass("closed")) : (n.slideUp(function () {
            t.attr("class", "icon-plus")
        }), e.addClass("closed"))
    }
    var e = jQuery,
        t = e(".accordion,.toggle");
    if (!t.length) return;
    t.each(function () {
        var t = e(this),
            r = t.find(".tab"),
            i = r.find(".header"),
            s = t.hasClass("toggle"),
            o = !1,
            u = [];
        r.each(function () {
            var t = e(this);
            if (t.hasClass("keep-open")) {
                if (s) return;
                if (!o) {
                    o = !0;
                    return
                }
            }
            u.push(t)
        }), !s && !o && u.length && (u.shift(), o = !0);
        for (var a = 0; a < u.length; a++) n(u[a]);
        i.click(function () {
            var t = e(this),
                i = t.parent();
            s || r.not(i).each(function () {
                e(this).hasClass("closed") || n(e(this))
            }), n(i)
        })
    })
}), define("modules/post-slider", ["modules/resize", "modules/element-query"], function (e, t) {
    var n = jQuery,
        r = n(".post-slider");
    if (!r.length) return;
    require(["jquery.iosslider.min"], function () {
        r.each(function () {
            function a() {
                if (u) return;
                o.iosSlider("update")
            }
            var r = n(this),
                i = r.find(".nav-next"),
                s = r.find(".nav-prev"),
                o = r.find(".slider-wrap"),
                u = !0;
            t([{
                operator: "max-width",
                value: 724
            }, {
                operator: "max-width",
                value: 480
            }], o), e(a, 100), o.iosSlider({
                desktopClickDrag: !0,
                snapToChildren: !0,
                scrollbar: !0,
                scrollbarLocation: "bottom",
                scrollbarMargin: "0",
                scrollbarHeight: "2px",
                navNextSelector: i,
                navPrevSelector: s
            }), i.click(function (e) {
                e.preventDefault()
            }), s.click(function (e) {
                e.preventDefault()
            }), u = !1
        })
    })
}), define("modules/post-gallery", ["modules/loadCss"], function (e) {
    var t = jQuery;
    if (!t("body.blog,body.single-post,body.archive").length) return;
    var n = t(".flexslider");
    if (n.find(".slides > li").length < 2) return;
    require(["jquery.flexslider-min"], function () {
        e(theme_uri.css + "/flexslider.css"), n.flexslider()
    })
}), define("modules/horizontal-tab", ["modules/image-load", "modules/element-query", "modules/resize"], function (e, t, n) {
    function s(e, t) {
        var n = e.height(),
            r = t.height(),
            i = (r - n) * .5,
            s = t.position(),
            o = s.top + i;
        e.css({
            top: o
        })
    }
    function o(e, t) {
        var n = t.position(),
            r = n.left;
        e.css({
            left: r
        })
    }
    function u(e, t, n, r) {
        s(e, t), n.width() < 768 ? o(e, t) : e.css({
            left: r
        })
    }
    function a() {
        i.each(function () {
            var e = r(this),
                i = e.find(".titles-container .titles li"),
                s = e.find(".tabs-container > li"),
                o = e.find(".titles-container .pointer"),
                a = o.css("left");
            s.not(":first-child").hide(), s.eq(0).addClass("current"), t([{
                operator: "max-width",
                value: 767
            }], e), u(o, i.eq(0), e, a), i.click(function (t) {
                var n = r(this),
                    i = n.index(),
                    f = s.filter(".current");
                if (i == f.index()) return;
                u(o, n, e, a), f.stop().fadeOut({
                    complete: function () {
                        f.removeClass("current"), s.eq(i).addClass("current").fadeIn({
	                        complete:function(){
		                        // truong
		                        console.log('fade in 2');
	                        }
                        })   
                    }
                })
            }), n(function () {
                u(o, i.eq(s.filter(".current").index()), e, a)
            }, 100)
        })
    }
    var r = jQuery,
        i = r(".horizontal-tab");
    if (!i.length) return;
    e(r("img"), a, 5e3)
}), define("modules/tab", [], function () {
    var e = jQuery,
        t = e(".tabs");
    if (!t.length) return;
    t.each(function () {
        var t = e(this),
            n = t.find(".head li"),
            r = t.find(".tab-content");
        r.not(":first-child").hide(), n.eq(0).addClass("current"), n.click(function (t) {
            var i = e(this),
                s = i.index(),
                o = n.filter(".current");
            if (i.hasClass("current")) return;
            r.eq(o.index()).stop().fadeOut({
                complete: function () {
                    o.removeClass("current"), i.addClass("current"), r.eq(s).fadeIn({
	                    complete: function () {
						// truong
						jQuery(window).trigger('resize');
                    }
                  })
                }
            })
        })
    })
}), define("modules/element-visible", ["modules/resize"], function (e) {
    function r(r, s) {
        var o = r;
        r instanceof jQuery == 0 && (o = t(r)), o.each(function () {
            function u() {
                !o && i(r) && (o = !0, s(r))
            }
            var r = t(this),
                o = !1;
            e(u, 100), n.scroll(u), u()
        })
    }
    function i(e) {
        var t = n.scrollTop(),
            r = t + n.height(),
            i = e.offset().top,
            s = i + e.height();
        return s <= r && i >= t
    }
    var t = jQuery,
        n = t(window);
    return r
}), define("modules/parallax-background", ["modules/element-visible", "modules/resize"], function (e, t) {
    function s(e, t) {
        var n = e.attr(t);
        return typeof n != "undefined" && n !== !1
    }
    function o(e, t) {
        return (t.width() - e.width()) * .5
    }
    function u(e) {
        var t = 0;
        return e.each(function () {
            t += n(this).outerHeight(!0)
        }), t
    }
    function a(e, t, r) {
        e.each(function () {
            var e = n(this),
                i = o(e, t);
            e.data("css", {
                top: r,
                left: i,
                opacity: .95
            }), r += e.outerHeight(!0)
        })
    }
    function f(e, t, r, i, s, u) {
        e.each(function () {
            var e = n(this),
                a = o(e, t);
            switch (r) {
                case "from-bottom":
                    e.css({
                        top: i + u,
                        left: a
                    });
                    break;
                case "from-top":
                    e.css({
                        top: i - u,
                        left: a
                    });
                    break;
                case "from-left":
                    e.css({
                        top: i,
                        left: a - s
                    });
                    break;
                case "from-right":
                    e.css({
                        top: i,
                        left: a + s
                    })
            }
            i += e.outerHeight(!0)
        })
    }
    function l(e, t, r, i) {
        var s = t.height(),
            o = t.width(),
            l = u(e),
            c = (s - l) * .5,
            h = s * .1,
            p = o * .1;
        return e.addClass("notransition"), e.each(function () {
            var e = n(this);
            e.css({
                width: e.width(),
                WebkitTransitionDuration: i + "s",
                MozTransitionDuration: i + "s",
                MsTransitionDuration: i + "s",
                OTransitionDuration: i + "s",
                transitionDuration: i + "s"
            })
        }), f(e, t, r, c, p, h), a(e, t, c), r == "from-top" && (e = n(e.get().reverse())), e.removeClass("notransition").css({
            visibility: "visible"
        }), e
    }
    function c(r) {
        var i = r.attr("data-titleanimation"),
            s = parseFloat(r.attr("data-titleanimationtime")),
            o = r.find(".title,hr,.subtitle"),
            f = !1;
        e(r, function () {
            f = !0;
            var e = l(o, r, i, s);
            e.each(function (e) {
                var t = n(this);
                e ? function (t) {
                    setTimeout(function () {
                        t.css(t.data("css"))
                    }, e * 220)
                }(t) : t.css(t.data("css"))
            })
        }), t(function () {
            if (f) {
                var e = u(o),
                    t = (r.height() - e) * .5;
                a(o, r, t), o.each(function () {
                    var e = n(this);
                    e.css(e.data("css"))
                })
            }
        }, 100)
    }
    var n = jQuery,
        r = n(".parallax"),
        i = window.navigator.userAgent;
    if (i.match(/iPad/i) || i.match(/iPhone/i)) {
        r.css({
            backgroundAttachment: "scroll"
        });
        return
    }
    if (!r.length) return;
    require(["jquery.parallax"], function () {
        r.each(function () {
            var e = n(this),
                t = e.attr("data-xpos"),
                r = e.attr("data-speed");
            e.parallax(t, r);
            if (!s(e, "data-titleanimation")) return;
            c(e)
        })
    })
}), define("modules/gmap", [], function () {
    var e = jQuery,
        t = e(".gmap");
    if (!t.length) return;
    var n = "async!https://maps.google.com/maps/api/js?key=" + gkey + "&sensor=false&language=en";
    require([n, "gmap3.min"], function () {
        t.each(function () {
            var t = e(this),
                n = t.find(".gmap-marker"),
                r = parseInt(t.attr("data-zoom")),
                i = t.attr("data-address"),
                s = t.attr("data-controls") === "true",
                o = t.attr("data-lat"),
                u = t.attr("data-lng"),
                a = [];
            n.each(function () {
                var t = e(this),
                    n = t.attr("data-lat"),
                    r = t.attr("data-lng"),
                    s = t.attr("data-address"),
                    o = t.attr("data-icon"),
                    u = {};
                if (n.length && r.length) n = parseFloat(n), r = parseFloat(r), u.latLng = [n, r];
                else {
                    if (!i.length) return;
                    u.address = s
                }
                o.length ? u.options = {
                    icon: o
                } : u.options = {
                    icon: theme_uri.img + "/gmap-marker.png"
                }, a.push(u)
            });
            var f = {
                map: {
                    options: {
                        zoom: r,
                        disableDefaultUI: !s,
                        scrollwheel: !1,
                        draggable: !1
                    }
                }
            };
            o.length && u.length ? (o = parseFloat(o), u = parseFloat(u), f.map.options.center = [o, u]) : i.length ? f.map.address = i : f.map.options.center = [29.697421, 52.470375], a.length && (f.marker = {
                values: a
            }), t.gmap3(f)
        })
    })
}), define("modules/animation", ["modules/element-visible"], function (e) {
    var t = jQuery,
        n = t(".animate"),
        r = 0,
        i = 0;
    e(n, function (e) {
        r ? (clearTimeout(i), setTimeout(function () {
            e.addClass("start-animation")
        }, r * 150), i = setTimeout(function () {
            r = 0
        }, 200)) : e.addClass("start-animation"), r++
    })
}), define("modules/progressbar", [], function () {
    var e = jQuery,
        t = e(".progressbar.animate");
    t.each(function () {
        var t = e(this),
            n = t.find(".progress-inner");
        n.css("left", -n.width())
    })
}), jQuery(function (e) {
    var t = e("body");
    require(["retina", "modules/translate3d-support", "modules/navigation", "modules/navigation-mobile", "modules/portfolio-listing", "modules/portfolio", "modules/responsive-media", "modules/comment-respond", "modules/widget-testimonials", "modules/testimonials", "modules/image-carousel", "modules/accordion", "modules/post-slider", "modules/post-gallery", "modules/horizontal-tab", "modules/tab", "modules/parallax-background", "modules/gmap", "modules/animation", "modules/progressbar"], function (e, n) {
        n() && t.addClass("px-translate3d")
    })
}), define("theme-dev", function () {});