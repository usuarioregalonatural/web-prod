/**
 *  @Website: apollotheme.com - prestashop template provider
 *  @author Apollotheme <apollotheme@gmail.com>
 *  @copyright  2007-2018 Apollotheme
 *  @description: 
 */

var LeoImage360 = (function() {
    var s, u;
    s = u = (function() {
        var N = {
            version: "v3.3-b5",
            UUID: 0,
            storage: {},
            $uuid: function(R) {
                return (R.$J_UUID || (R.$J_UUID = ++H.UUID))
            },
            getStorage: function(R) {
                return (H.storage[R] || (H.storage[R] = {}))
            },
            $F: function() {},
            leofalse: function() {
                return false
            },
            leotrue: function() {
                return true
            },
            stylesId: "mjs-" + Math.floor(Math.random() * new Date().getTime()),
            defined: function(R) {
                return (undefined != R)
            },
            ifndef: function(S, R) {
                return (undefined != S) ? S : R
            },
            exists: function(R) {
                return !!(R)
            },
            jTypeOf: function(R) {
                if (!H.defined(R)) {
                    return false
                }
                if (R.$J_TYPE) {
                    return R.$J_TYPE
                }
                if (!!R.nodeType) {
                    if (1 == R.nodeType) {
                        return "element"
                    }
                    if (3 == R.nodeType) {
                        return "textnode"
                    }
                }
                if (R.length && R.item) {
                    return "collection"
                }
                if (R.length && R.callee) {
                    return "arguments"
                }
                if ((R instanceof window.Object || R instanceof window.Function) && R.constructor === H.Class) {
                    return "class"
                }
                if (R instanceof window.Array) {
                    return "array"
                }
                if (R instanceof window.Function) {
                    return "function"
                }
                if (R instanceof window.String) {
                    return "string"
                }
                if (H.browser.trident) {
                    if (H.defined(R.cancelBubble)) {
                        return "event"
                    }
                } else {
                    if (R === window.event || R.constructor == window.Event || R.constructor == window.MouseEvent || R.constructor == window.UIEvent || R.constructor == window.KeyboardEvent || R.constructor == window.KeyEvent) {
                        return "event"
                    }
                }
                if (R instanceof window.Date) {
                    return "date"
                }
                if (R instanceof window.RegExp) {
                    return "regexp"
                }
                if (R === window) {
                    return "window"
                }
                if (R === document) {
                    return "document"
                }
                return typeof(R)
            },
            extend: function(W, V) {
                if (!(W instanceof window.Array)) {
                    W = [W]
                }
                if (!V) {
                    return W[0]
                }
                for (var U = 0, S = W.length; U < S; U++) {
                    if (!H.defined(W)) {
                        continue
                    }
                    for (var T in V) {
                        if (!Object.prototype.hasOwnProperty.call(V, T)) {
                            continue
                        }
                        try {
                            W[U][T] = V[T]
                        } catch (R) {}
                    }
                }
                return W[0]
            },
            implement: function(V, U) {
                if (!(V instanceof window.Array)) {
                    V = [V]
                }
                for (var T = 0, R = V.length; T < R; T++) {
                    if (!H.defined(V[T])) {
                        continue
                    }
                    if (!V[T].prototype) {
                        continue
                    }
                    for (var S in (U || {})) {
                        if (!V[T].prototype[S]) {
                            V[T].prototype[S] = U[S]
                        }
                    }
                }
                return V[0]
            },
            nativize: function(T, S) {
                if (!H.defined(T)) {
                    return T
                }
                for (var R in (S || {})) {
                    if (!T[R]) {
                        T[R] = S[R]
                    }
                }
                return T
            },
            $try: function() {
                for (var S = 0, R = arguments.length; S < R; S++) {
                    try {
                        return arguments[S]()
                    } catch (T) {}
                }
                return null
            },
            $A: function(T) {
                if (!H.defined(T)) {
                    return H.$([])
                }
                if (T.toArray) {
                    return H.$(T.toArray())
                }
                if (T.item) {
                    var S = T.length || 0,
                        R = new Array(S);
                    while (S--) {
                        R[S] = T[S]
                    }
                    return H.$(R)
                }
                return H.$(Array.prototype.slice.call(T))
            },
            now: function() {
                return new Date().getTime()
            },
            detach: function(V) {
                var T;
                switch (H.jTypeOf(V)) {
                    case "object":
                        T = {};
                        for (var U in V) {
                            T[U] = H.detach(V[U])
                        }
                        break;
                    case "array":
                        T = [];
                        for (var S = 0, R = V.length; S < R; S++) {
                            T[S] = H.detach(V[S])
                        }
                        break;
                    default:
                        return V
                }
                return H.$(T)
            },
            $: function(T) {
                var R = true;
                if (!H.defined(T)) {
                    return null
                }
                if (T.$J_EXT) {
                    return T
                }
                switch (H.jTypeOf(T)) {
                    case "array":
                        T = H.nativize(T, H.extend(H.Array, {
                            $J_EXT: H.$F
                        }));
                        T.jEach = T.forEach;
                        return T;
                        break;
                    case "string":
                        var S = document.getElementById(T);
                        if (H.defined(S)) {
                            return H.$(S)
                        }
                        return null;
                        break;
                    case "window":
                    case "document":
                        H.$uuid(T);
                        T = H.extend(T, H.Doc);
                        break;
                    case "element":
                        H.$uuid(T);
                        T = H.extend(T, H.Element);
                        break;
                    case "event":
                        T = H.extend(T, H.Event);
                        break;
                    case "textnode":
                    case "function":
                    case "array":
                    case "date":
                    default:
                        R = false;
                        break
                }
                if (R) {
                    return H.extend(T, {
                        $J_EXT: H.$F
                    })
                } else {
                    return T
                }
            },
            $new: function(R, T, S) {
                return H.$(H.doc.createElement(R)).setProps(T || {}).jSetCss(S || {})
            },
            addCSS: function(S, U, Y) {
                var V, T, W, X = [],
                    R = -1;
                Y || (Y = H.stylesId);
                V = H.$(Y) || H.$new("style", {
                    id: Y,
                    type: "text/css"
                }).jAppendTo((document.head || document.body), "top");
                T = V.sheet || V.styleSheet;
                if ("string" != H.jTypeOf(U)) {
                    for (var W in U) {
                        X.push(W + ":" + U[W])
                    }
                    U = X.join(";")
                }
                if (T.insertRule) {
                    R = T.insertRule(S + " {" + U + "}", T.cssRules.length)
                } else {
                    R = T.addRule(S, U)
                }
                return R
            },
            removeCSS: function(U, R) {
                var T, S;
                T = H.$(U);
                if ("element" !== H.jTypeOf(T)) {
                    return
                }
                S = T.sheet || T.styleSheet;
                if (S.deleteRule) {
                    S.deleteRule(R)
                } else {
                    if (S.removeRule) {
                        S.removeRule(R)
                    }
                }
            },
            generateUUID: function() {
                return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function(T) {
                    var S = Math.random() * 16 | 0,
                        R = T == "x" ? S : (S & 3 | 8);
                    return R.toString(16)
                }).toUpperCase()
            },
            getAbsoluteURL: (function() {
                var R;
                return function(S) {
                    if (!R) {
                        R = document.createElement("a")
                    }
                    R.setAttribute("href", S);
                    return ("!!" + R.href).replace("!!", "")
                }
            })(),
            getHashCode: function(T) {
                var U = 0,
                    R = T.length;
                for (var S = 0; S < R; ++S) {
                    U = 31 * U + T.charCodeAt(S);
                    U %= 4294967296
                }
                return U
            }
        };
        var H = N;
        var I = N.$;
        if (!window.leoimageJS) {
            window.leoimageJS = N;
            window.$mjs = N.$
        }
        H.Array = {
            $J_TYPE: "array",
            indexOf: function(U, V) {
                var R = this.length;
                for (var S = this.length, T = (V < 0) ? Math.max(0, S + V) : V || 0; T < S; T++) {
                    if (this[T] === U) {
                        return T
                    }
                }
                return -1
            },
            contains: function(R, S) {
                return this.indexOf(R, S) != -1
            },
            forEach: function(R, U) {
                for (var T = 0, S = this.length; T < S; T++) {
                    if (T in this) {
                        R.call(U, this[T], T, this)
                    }
                }
            },
            filter: function(R, W) {
                var V = [];
                for (var U = 0, S = this.length; U < S; U++) {
                    if (U in this) {
                        var T = this[U];
                        if (R.call(W, this[U], U, this)) {
                            V.push(T)
                        }
                    }
                }
                return V
            },
            map: function(R, V) {
                var U = [];
                for (var T = 0, S = this.length; T < S; T++) {
                    if (T in this) {
                        U[T] = R.call(V, this[T], T, this)
                    }
                }
                return U
            }
        };
        H.implement(String, {
            $J_TYPE: "string",
            jTrim: function() {
                return this.replace(/^\s+|\s+$/g, "")
            },
            eq: function(R, S) {
                return (S || false) ? (this.toString() === R.toString()) : (this.toLowerCase().toString() === R.toLowerCase().toString())
            },
            jCamelize: function() {
                return this.replace(/-\D/g, function(R) {
                    return R.charAt(1).toUpperCase()
                })
            },
            dashize: function() {
                return this.replace(/[A-Z]/g, function(R) {
                    return ("-" + R.charAt(0).toLowerCase())
                })
            },
            jToInt: function(R) {
                return parseInt(this, R || 10)
            },
            toFloat: function() {
                return parseFloat(this)
            },
            jToBool: function() {
                return !this.replace(/true/i, "").jTrim()
            },
            has: function(S, R) {
                R = R || "";
                return (R + this + R).indexOf(R + S + R) > -1
            }
        });
        N.implement(Function, {
            $J_TYPE: "function",
            jBind: function() {
                var S = H.$A(arguments),
                    R = this,
                    T = S.shift();
                return function() {
                    return R.apply(T || null, S.concat(H.$A(arguments)))
                }
            },
            jBindAsEvent: function() {
                var S = H.$A(arguments),
                    R = this,
                    T = S.shift();
                return function(U) {
                    return R.apply(T || null, H.$([U || (H.browser.ieMode ? window.event : null)]).concat(S))
                }
            },
            jDelay: function() {
                var S = H.$A(arguments),
                    R = this,
                    T = S.shift();
                return window.setTimeout(function() {
                    return R.apply(R, S)
                }, T || 0)
            },
            jDefer: function() {
                var S = H.$A(arguments),
                    R = this;
                return function() {
                    return R.jDelay.apply(R, S)
                }
            },
            interval: function() {
                var S = H.$A(arguments),
                    R = this,
                    T = S.shift();
                return window.setInterval(function() {
                    return R.apply(R, S)
                }, T || 0)
            }
        });
        var O = {},
            G = navigator.userAgent.toLowerCase(),
            F = G.match(/(webkit|gecko|trident|presto)\/(\d+\.?\d*)/i),
            K = G.match(/(edge|opr)\/(\d+\.?\d*)/i) || G.match(/(crios|chrome|safari|firefox|opera|opr)\/(\d+\.?\d*)/i),
            M = G.match(/version\/(\d+\.?\d*)/i),
            B = document.documentElement.style;

        function C(S) {
            var R = S.charAt(0).toUpperCase() + S.slice(1);
            return S in B || ("Webkit" + R) in B || ("Moz" + R) in B || ("ms" + R) in B || ("O" + R) in B
        }
        H.browser = {
            features: {
                xpath: !!(document.evaluate),
                air: !!(window.runtime),
                query: !!(document.querySelector),
                fullScreen: !!(document.fullscreenEnabled || document.msFullscreenEnabled || document.exitFullscreen || document.cancelFullScreen || document.webkitexitFullscreen || document.webkitCancelFullScreen || document.mozCancelFullScreen || document.oCancelFullScreen || document.msCancelFullScreen),
                xhr2: !!(window.ProgressEvent) && !!(window.FormData) && (window.XMLHttpRequest && "withCredentials" in new XMLHttpRequest),
                transition: C("transition"),
                transform: C("transform"),
                perspective: C("perspective"),
                animation: C("animation"),
                requestAnimationFrame: false,
                multibackground: false,
                cssFilters: false,
                canvas: false,
                svg: (function() {
                    return document.implementation.hasFeature("http://www.w3.org/TR/SVG11/feature#Image", "1.1")
                })()
            },
            touchScreen: function() {
                return "ontouchstart" in window || (window.DocumentTouch && document instanceof DocumentTouch) || (navigator.maxTouchPoints > 0) || (navigator.msMaxTouchPoints > 0)
            }(),
            mobile: G.match(/(android|bb\d+|meego).+|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od|ad)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/) ? true : false,
            engine: (F && F[1]) ? F[1].toLowerCase() : (window.opera) ? "presto" : !!(window.ActiveXObject) ? "trident" : (undefined !== document.getBoxObjectFor || null != window.mozInnerScreenY) ? "gecko" : (null !== window.WebKitPoint || !navigator.taintEnabled) ? "webkit" : "unknown",
            version: (F && F[2]) ? parseFloat(F[2]) : 0,
            uaName: (K && K[1]) ? K[1].toLowerCase() : "",
            uaVersion: (K && K[2]) ? parseFloat(K[2]) : 0,
            cssPrefix: "",
            cssDomPrefix: "",
            domPrefix: "",
            ieMode: 0,
            platform: G.match(/ip(?:ad|od|hone)/) ? "ios" : (G.match(/(?:webos|android)/) || navigator.platform.match(/mac|win|linux/i) || ["other"])[0].toLowerCase(),
            backCompat: document.compatMode && "backcompat" == document.compatMode.toLowerCase(),
            scrollbarsWidth: 0,
            getDoc: function() {
                return (document.compatMode && "backcompat" == document.compatMode.toLowerCase()) ? document.body : document.documentElement
            },
            requestAnimationFrame: window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame || undefined,
            cancelAnimationFrame: window.cancelAnimationFrame || window.mozCancelAnimationFrame || window.mozCancelAnimationFrame || window.oCancelAnimationFrame || window.msCancelAnimationFrame || window.webkitCancelRequestAnimationFrame || undefined,
            ready: false,
            onready: function() {
                if (H.browser.ready) {
                    return
                }
                var U, T;
                H.browser.ready = true;
                H.body = H.$(document.body);
                H.win = H.$(window);
                try {
                    var S = H.$new("div").jSetCss({
                        width: 100,
                        height: 100,
                        overflow: "scroll",
                        position: "absolute",
                        top: -9999
                    }).jAppendTo(document.body);
                    H.browser.scrollbarsWidth = S.offsetWidth - S.clientWidth;
                    S.jRemove()
                } catch (R) {}
                try {
                    U = H.$new("div");
                    T = U.style;
                    T.cssText = "background:url(https://),url(https://),red url(https://)";
                    H.browser.features.multibackground = (/(url\s*\(.*?){3}/).test(T.background);
                    T = null;
                    U = null
                } catch (R) {}
                if (!H.browser.cssTransformProp) {
                    H.browser.cssTransformProp = H.normalizeCSS("transform").dashize()
                }
                try {
                    U = H.$new("div");
                    U.style.cssText = H.normalizeCSS("filter").dashize() + ":blur(2px);";
                    H.browser.features.cssFilters = !!U.style.length && (!H.browser.ieMode || H.browser.ieMode > 9);
                    U = null
                } catch (R) {}
                if (!H.browser.features.cssFilters) {
                    H.$(document.documentElement).jAddClass("no-cssfilters-leoimage")
                }
                try {
                    H.browser.features.canvas = (function() {
                        var V = H.$new("canvas");
                        return !!(V.getContext && V.getContext("2d"))
                    })()
                } catch (R) {}
                if (undefined === window.TransitionEvent && undefined !== window.WebKitTransitionEvent) {
                    O.transitionend = "webkitTransitionEnd"
                }
                H.Doc.jCallEvent.call(H.$(document), "domready")
            }
        };
        (function() {
            var W = [],
                V, U, S;

            function R() {
                return !!(arguments.callee.caller)
            }
            switch (H.browser.engine) {
                case "trident":
                    if (!H.browser.version) {
                        H.browser.version = !!(window.XMLHttpRequest) ? 3 : 2
                    }
                    break;
                case "gecko":
                    H.browser.version = (K && K[2]) ? parseFloat(K[2]) : 0;
                    break
            }
            H.browser[H.browser.engine] = true;
            if (K && "crios" === K[1]) {
                H.browser.uaName = "chrome"
            }
            if (!!window.chrome) {
                H.browser.chrome = true
            }
            if (K && "opr" === K[1]) {
                H.browser.uaName = "opera";
                H.browser.opera = true
            }
            if ("safari" === H.browser.uaName && (M && M[1])) {
                H.browser.uaVersion = parseFloat(M[1])
            }
            if ("android" == H.browser.platform && H.browser.webkit && (M && M[1])) {
                H.browser.androidBrowser = true
            }
            V = ({
                gecko: ["-moz-", "Moz", "moz"],
                webkit: ["-webkit-", "Webkit", "webkit"],
                trident: ["-ms-", "ms", "ms"],
                presto: ["-o-", "O", "o"]
            })[H.browser.engine] || ["", "", ""];
            H.browser.cssPrefix = V[0];
            H.browser.cssDomPrefix = V[1];
            H.browser.domPrefix = V[2];
            H.browser.ieMode = (!H.browser.trident) ? undefined : (document.documentMode) ? document.documentMode : function() {
                var X = 0;
                if (H.browser.backCompat) {
                    return 5
                }
                switch (H.browser.version) {
                    case 2:
                        X = 6;
                        break;
                    case 3:
                        X = 7;
                        break
                }
                return X
            }();
            W.push(H.browser.platform + "-leoimage");
            if (H.browser.mobile) {
                W.push("mobile-leoimage")
            }
            if (H.browser.androidBrowser) {
                W.push("android-browser-leoimage")
            }
            if (H.browser.ieMode) {
                H.browser.uaName = "ie";
                H.browser.uaVersion = H.browser.ieMode;
                W.push("ie" + H.browser.ieMode + "-leoimage");
                for (U = 11; U > H.browser.ieMode; U--) {
                    W.push("lt-ie" + U + "-leoimage")
                }
            }
            if (H.browser.webkit && H.browser.version < 536) {
                H.browser.features.fullScreen = false
            }
            if (H.browser.requestAnimationFrame) {
                H.browser.requestAnimationFrame.call(window, function() {
                    H.browser.features.requestAnimationFrame = true
                })
            }
            if (H.browser.features.svg) {
                W.push("svg-leoimage")
            } else {
                W.push("no-svg-leoimage")
            }
            S = (document.documentElement.className || "").match(/\S+/g) || [];
            document.documentElement.className = H.$(S).concat(W).join(" ");
            try {
                document.documentElement.setAttribute("data-leoimage-ua", H.browser.uaName);
                document.documentElement.setAttribute("data-leoimage-ua-ver", H.browser.uaVersion)
            } catch (T) {}
            if (H.browser.ieMode && H.browser.ieMode < 9) {
                document.createElement("figure");
                document.createElement("figcaption")
            }
        })();
        (function() {
            H.browser.fullScreen = {
                capable: H.browser.features.fullScreen,
                enabled: function() {
                    return !!(document.fullscreenElement || document[H.browser.domPrefix + "FullscreenElement"] || document.fullScreen || document.webkitIsFullScreen || document[H.browser.domPrefix + "FullScreen"])
                },
                request: function(R, S) {
                    S || (S = {});
                    if (this.capable) {
                        H.$(document).jAddEvent(this.changeEventName, this.onchange = function(T) {
                            if (this.enabled()) {
                                S.onEnter && S.onEnter()
                            } else {
                                H.$(document).jRemoveEvent(this.changeEventName, this.onchange);
                                S.onExit && S.onExit()
                            }
                        }.jBindAsEvent(this));
                        H.$(document).jAddEvent(this.errorEventName, this.onerror = function(T) {
                            S.fallback && S.fallback();
                            H.$(document).jRemoveEvent(this.errorEventName, this.onerror)
                        }.jBindAsEvent(this));
                        (R[H.browser.domPrefix + "RequestFullscreen"] || R[H.browser.domPrefix + "RequestFullScreen"] || R.requestFullscreen || function() {}).call(R)
                    } else {
                        if (S.fallback) {
                            S.fallback()
                        }
                    }
                },
                cancel: (document.exitFullscreen || document.cancelFullScreen || document[H.browser.domPrefix + "ExitFullscreen"] || document[H.browser.domPrefix + "CancelFullScreen"] || function() {}).jBind(document),
                changeEventName: document.msExitFullscreen ? "MSFullscreenChange" : (document.exitFullscreen ? "" : H.browser.domPrefix) + "fullscreenchange",
                errorEventName: document.msExitFullscreen ? "MSFullscreenError" : (document.exitFullscreen ? "" : H.browser.domPrefix) + "fullscreenerror",
                prefix: H.browser.domPrefix,
                activeElement: null
            }
        })();
        var Q = /\S+/g,
            E = /^(border(Top|Bottom|Left|Right)Width)|((padding|margin)(Top|Bottom|Left|Right))$/,
            J = {
                "float": ("undefined" === typeof(B.styleFloat)) ? "cssFloat" : "styleFloat"
            },
            L = {
                fontWeight: true,
                lineHeight: true,
                opacity: true,
                zIndex: true,
                zoom: true
            },
            D = (window.getComputedStyle) ? function(T, R) {
                var S = window.getComputedStyle(T, null);
                return S ? S.getPropertyValue(R) || S[R] : null
            } : function(U, S) {
                var T = U.currentStyle,
                    R = null;
                R = T ? T[S] : null;
                if (null == R && U.style && U.style[S]) {
                    R = U.style[S]
                }
                return R
            };

        function P(T) {
            var R, S;
            S = (H.browser.webkit && "filter" == T) ? false : (T in B);
            if (!S) {
                R = H.browser.cssDomPrefix + T.charAt(0).toUpperCase() + T.slice(1);
                if (R in B) {
                    return R
                }
            }
            return T
        }
        H.normalizeCSS = P;
        H.Element = {
            jHasClass: function(R) {
                return !(R || "").has(" ") && (this.className || "").has(R, " ")
            },
            jAddClass: function(V) {
                var S = (this.className || "").match(Q) || [],
                    U = (V || "").match(Q) || [],
                    R = U.length,
                    T = 0;
                for (; T < R; T++) {
                    if (!H.$(S).contains(U[T])) {
                        S.push(U[T])
                    }
                }
                this.className = S.join(" ");
                return this
            },
            jRemoveClass: function(W) {
                var S = (this.className || "").match(Q) || [],
                    V = (W || "").match(Q) || [],
                    R = V.length,
                    U = 0,
                    T;
                for (; U < R; U++) {
                    if ((T = H.$(S).indexOf(V[U])) > -1) {
                        S.splice(T, 1)
                    }
                }
                this.className = W ? S.join(" ") : "";
                return this
            },
            jToggleClass: function(R) {
                return this.jHasClass(R) ? this.jRemoveClass(R) : this.jAddClass(R)
            },
            jGetCss: function(S) {
                var T = S.jCamelize(),
                    R = null;
                S = J[T] || (J[T] = P(T));
                R = D(this, S);
                if ("auto" === R) {
                    R = null
                }
                if (null !== R) {
                    if ("opacity" == S) {
                        return H.defined(R) ? parseFloat(R) : 1
                    }
                    if (E.test(S)) {
                        R = parseInt(R, 10) ? R : "0px"
                    }
                }
                return R
            },
            jSetCssProp: function(S, R) {
                var U = S.jCamelize();
                try {
                    if ("opacity" == S) {
                        this.jSetOpacity(R);
                        return this
                    }
                    S = J[U] || (J[U] = P(U));
                    this.style[S] = R + (("number" == H.jTypeOf(R) && !L[U]) ? "px" : "")
                } catch (T) {}
                return this
            },
            jSetCss: function(S) {
                for (var R in S) {
                    this.jSetCssProp(R, S[R])
                }
                return this
            },
            jGetStyles: function() {
                var R = {};
                H.$A(arguments).jEach(function(S) {
                    R[S] = this.jGetCss(S)
                }, this);
                return R
            },
            jSetOpacity: function(T, R) {
                var S;
                R = R || false;
                this.style.opacity = T;
                T = parseInt(parseFloat(T) * 100);
                if (R) {
                    if (0 === T) {
                        if ("hidden" != this.style.visibility) {
                            this.style.visibility = "hidden"
                        }
                    } else {
                        if ("visible" != this.style.visibility) {
                            this.style.visibility = "visible"
                        }
                    }
                }
                if (H.browser.ieMode && H.browser.ieMode < 9) {
                    if (!isNaN(T)) {
                        if (!~this.style.filter.indexOf("Alpha")) {
                            this.style.filter += " progid:DXImageTransform.Microsoft.Alpha(Opacity=" + T + ")"
                        } else {
                            this.style.filter = this.style.filter.replace(/Opacity=\d*/i, "Opacity=" + T)
                        }
                    } else {
                        this.style.filter = this.style.filter.replace(/progid:DXImageTransform.Microsoft.Alpha\(Opacity=\d*\)/i, "").jTrim();
                        if ("" === this.style.filter) {
                            this.style.removeAttribute("filter")
                        }
                    }
                }
                return this
            },
            setProps: function(R) {
                for (var S in R) {
                    if ("class" === S) {
                        this.jAddClass("" + R[S])
                    } else {
                        this.setAttribute(S, "" + R[S])
                    }
                }
                return this
            },
            jGetTransitionDuration: function() {
                var S = 0,
                    R = 0;
                S = this.jGetCss("transition-duration");
                R = this.jGetCss("transition-delay");
                S = S.indexOf("ms") > -1 ? parseFloat(S) : S.indexOf("s") > -1 ? parseFloat(S) * 1000 : 0;
                R = R.indexOf("ms") > -1 ? parseFloat(R) : R.indexOf("s") > -1 ? parseFloat(R) * 1000 : 0;
                return S + R
            },
            hide: function() {
                return this.jSetCss({
                    display: "none",
                    visibility: "hidden"
                })
            },
            show: function() {
                return this.jSetCss({
                    display: "",
                    visibility: "visible"
                })
            },
            jGetSize: function() {
                return {
                    width: this.offsetWidth,
                    height: this.offsetHeight
                }
            },
            getInnerSize: function(S) {
                var R = this.jGetSize();
                R.width -= (parseFloat(this.jGetCss("border-left-width") || 0) + parseFloat(this.jGetCss("border-right-width") || 0));
                R.height -= (parseFloat(this.jGetCss("border-top-width") || 0) + parseFloat(this.jGetCss("border-bottom-width") || 0));
                if (!S) {
                    R.width -= (parseFloat(this.jGetCss("padding-left") || 0) + parseFloat(this.jGetCss("padding-right") || 0));
                    R.height -= (parseFloat(this.jGetCss("padding-top") || 0) + parseFloat(this.jGetCss("padding-bottom") || 0))
                }
                return R
            },
            jGetScroll: function() {
                return {
                    top: this.scrollTop,
                    left: this.scrollLeft
                }
            },
            jGetFullScroll: function() {
                var R = this,
                    S = {
                        top: 0,
                        left: 0
                    };
                do {
                    S.left += R.scrollLeft || 0;
                    S.top += R.scrollTop || 0;
                    R = R.parentNode
                } while (R);
                return S
            },
            jGetPosition: function() {
                var V = this,
                    S = 0,
                    U = 0;
                if (H.defined(document.documentElement.getBoundingClientRect)) {
                    var R = this.getBoundingClientRect(),
                        T = H.$(document).jGetScroll(),
                        W = H.browser.getDoc();
                    return {
                        top: R.top + T.y - W.clientTop,
                        left: R.left + T.x - W.clientLeft
                    }
                }
                do {
                    S += V.offsetLeft || 0;
                    U += V.offsetTop || 0;
                    V = V.offsetParent
                } while (V && !(/^(?:body|html)$/i).test(V.tagName));
                return {
                    top: U,
                    left: S
                }
            },
            jGetRect: function() {
                var S = this.jGetPosition();
                var R = this.jGetSize();
                return {
                    top: S.top,
                    bottom: S.top + R.height,
                    left: S.left,
                    right: S.left + R.width
                }
            },
            changeContent: function(S) {
                try {
                    this.innerHTML = S
                } catch (R) {
                    this.innerText = S
                }
                return this
            },
            jRemove: function() {
                return (this.parentNode) ? this.parentNode.removeChild(this) : this
            },
            kill: function() {
                H.$A(this.childNodes).jEach(function(R) {
                    if (3 == R.nodeType || 8 == R.nodeType) {
                        return
                    }
                    H.$(R).kill()
                });
                this.jRemove();
                this.jClearEvents();
                if (this.$J_UUID) {
                    H.storage[this.$J_UUID] = null;
                    delete H.storage[this.$J_UUID]
                }
                return null
            },
            append: function(T, S) {
                S = S || "bottom";
                var R = this.firstChild;
                ("top" == S && R) ? this.insertBefore(T, R): this.appendChild(T);
                return this
            },
            jAppendTo: function(T, S) {
                var R = H.$(T).append(this, S);
                return this
            },
            enclose: function(R) {
                this.append(R.parentNode.replaceChild(this, R));
                return this
            },
            hasChild: function(R) {
                if ("element" !== H.jTypeOf("string" == H.jTypeOf(R) ? R = document.getElementById(R) : R)) {
                    return false
                }
                return (this == R) ? false : (this.contains && !(H.browser.webkit419)) ? (this.contains(R)) : (this.compareDocumentPosition) ? !!(this.compareDocumentPosition(R) & 16) : H.$A(this.byTag(R.tagName)).contains(R)
            }
        };
        H.Element.jGetStyle = H.Element.jGetCss;
        H.Element.jSetStyle = H.Element.jSetCss;
        if (!window.Element) {
            window.Element = H.$F;
            if (H.browser.engine.webkit) {
                window.document.createElement("iframe")
            }
            window.Element.prototype = (H.browser.engine.webkit) ? window["[[DOMElement.prototype]]"] : {}
        }
        H.implement(window.Element, {
            $J_TYPE: "element"
        });
        H.Doc = {
            jGetSize: function() {
                if (H.browser.touchScreen || H.browser.presto925 || H.browser.webkit419) {
                    return {
                        width: window.innerWidth,
                        height: window.innerHeight
                    }
                }
                return {
                    width: H.browser.getDoc().clientWidth,
                    height: H.browser.getDoc().clientHeight
                }
            },
            jGetScroll: function() {
                return {
                    x: window.pageXOffset || H.browser.getDoc().scrollLeft,
                    y: window.pageYOffset || H.browser.getDoc().scrollTop
                }
            },
            jGetFullSize: function() {
                var R = this.jGetSize();
                return {
                    width: Math.max(H.browser.getDoc().scrollWidth, R.width),
                    height: Math.max(H.browser.getDoc().scrollHeight, R.height)
                }
            }
        };
        H.extend(document, {
            $J_TYPE: "document"
        });
        H.extend(window, {
            $J_TYPE: "window"
        });
        H.extend([H.Element, H.Doc], {
            jFetch: function(U, S) {
                var R = H.getStorage(this.$J_UUID),
                    T = R[U];
                if (undefined !== S && undefined === T) {
                    T = R[U] = S
                }
                return (H.defined(T) ? T : null)
            },
            jStore: function(T, S) {
                var R = H.getStorage(this.$J_UUID);
                R[T] = S;
                return this
            },
            jDel: function(S) {
                var R = H.getStorage(this.$J_UUID);
                delete R[S];
                return this
            }
        });
        if (!(window.HTMLElement && window.HTMLElement.prototype && window.HTMLElement.prototype.getElementsByClassName)) {
            H.extend([H.Element, H.Doc], {
                getElementsByClassName: function(R) {
                    return H.$A(this.getElementsByTagName("*")).filter(function(T) {
                        try {
                            return (1 == T.nodeType && T.className.has(R, " "))
                        } catch (S) {}
                    })
                }
            })
        }
        H.extend([H.Element, H.Doc], {
            byClass: function() {
                return this.getElementsByClassName(arguments[0])
            },
            byTag: function() {
                return this.getElementsByTagName(arguments[0])
            }
        });
        if (H.browser.fullScreen.capable && !document.requestFullScreen) {
            H.Element.requestFullScreen = function() {
                H.browser.fullScreen.request(this)
            }
        }
        H.Event = {
            $J_TYPE: "event",
            isQueueStopped: H.leofalse,
            stop: function() {
                return this.stopDistribution().stopDefaults()
            },
            stopDistribution: function() {
                if (this.stopPropagation) {
                    this.stopPropagation()
                } else {
                    this.cancelBubble = true
                }
                return this
            },
            stopDefaults: function() {
                if (this.preventDefault) {
                    this.preventDefault()
                } else {
                    this.returnValue = false
                }
                return this
            },
            stopQueue: function() {
                this.isQueueStopped = H.leotrue;
                return this
            },
            getClientXY: function() {
                var S, R;
                S = ((/touch/i).test(this.type)) ? this.changedTouches[0] : this;
                return (!H.defined(S)) ? {
                    x: 0,
                    y: 0
                } : {
                    x: S.clientX,
                    y: S.clientY
                }
            },
            jGetPageXY: function() {
                var S, R;
                S = ((/touch/i).test(this.type)) ? this.changedTouches[0] : this;
                return (!H.defined(S)) ? {
                    x: 0,
                    y: 0
                } : {
                    x: S.pageX || S.clientX + H.browser.getDoc().scrollLeft,
                    y: S.pageY || S.clientY + H.browser.getDoc().scrollTop
                }
            },
            getTarget: function() {
                var R = this.target || this.srcElement;
                while (R && 3 == R.nodeType) {
                    R = R.parentNode
                }
                return R
            },
            getRelated: function() {
                var S = null;
                switch (this.type) {
                    case "mouseover":
                    case "pointerover":
                    case "MSPointerOver":
                        S = this.relatedTarget || this.fromElement;
                        break;
                    case "mouseout":
                    case "pointerout":
                    case "MSPointerOut":
                        S = this.relatedTarget || this.toElement;
                        break;
                    default:
                        return S
                }
                try {
                    while (S && 3 == S.nodeType) {
                        S = S.parentNode
                    }
                } catch (R) {
                    S = null
                }
                return S
            },
            getButton: function() {
                if (!this.which && this.button !== undefined) {
                    return (this.button & 1 ? 1 : (this.button & 2 ? 3 : (this.button & 4 ? 2 : 0)))
                }
                return this.which
            },
            isTouchEvent: function() {
                return (this.pointerType && ("touch" === this.pointerType || this.pointerType === this.MSPOINTER_TYPE_TOUCH)) || (/touch/i).test(this.type)
            },
            isPrimaryTouch: function() {
                return this.pointerType ? (("touch" === this.pointerType || this.MSPOINTER_TYPE_TOUCH === this.pointerType) && this.isPrimary) : 1 === this.changedTouches.length && (this.targetTouches.length ? this.targetTouches[0].identifier == this.changedTouches[0].identifier : true)
            }
        };
        H._event_add_ = "addEventListener";
        H._event_del_ = "removeEventListener";
        H._event_prefix_ = "";
        if (!document.addEventListener) {
            H._event_add_ = "attachEvent";
            H._event_del_ = "detachEvent";
            H._event_prefix_ = "on"
        }
        H.Event.Custom = {
            type: "",
            x: null,
            y: null,
            timeStamp: null,
            button: null,
            target: null,
            relatedTarget: null,
            $J_TYPE: "event.custom",
            isQueueStopped: H.leofalse,
            events: H.$([]),
            pushToEvents: function(R) {
                var S = R;
                this.events.push(S)
            },
            stop: function() {
                return this.stopDistribution().stopDefaults()
            },
            stopDistribution: function() {
                this.events.jEach(function(S) {
                    try {
                        S.stopDistribution()
                    } catch (R) {}
                });
                return this
            },
            stopDefaults: function() {
                this.events.jEach(function(S) {
                    try {
                        S.stopDefaults()
                    } catch (R) {}
                });
                return this
            },
            stopQueue: function() {
                this.isQueueStopped = H.leotrue;
                return this
            },
            getClientXY: function() {
                return {
                    x: this.clientX,
                    y: this.clientY
                }
            },
            jGetPageXY: function() {
                return {
                    x: this.x,
                    y: this.y
                }
            },
            getTarget: function() {
                return this.target
            },
            getRelated: function() {
                return this.relatedTarget
            },
            getButton: function() {
                return this.button
            },
            getOriginalTarget: function() {
                return this.events.length > 0 ? this.events[0].getTarget() : undefined
            }
        };
        H.extend([H.Element, H.Doc], {
            jAddEvent: function(T, V, W, Z) {
                var Y, R, U, X, S;
                if ("string" == H.jTypeOf(T)) {
                    S = T.split(" ");
                    if (S.length > 1) {
                        T = S
                    }
                }
                if (H.jTypeOf(T) == "array") {
                    H.$(T).jEach(this.jAddEvent.jBindAsEvent(this, V, W, Z));
                    return this
                }
                if (!T || !V || H.jTypeOf(T) != "string" || H.jTypeOf(V) != "function") {
                    return this
                }
                if (T == "domready" && H.browser.ready) {
                    V.call(this);
                    return this
                }
                T = O[T] || T;
                W = parseInt(W || 50);
                if (!V.$J_EUID) {
                    V.$J_EUID = Math.floor(Math.random() * H.now())
                }
                Y = H.Doc.jFetch.call(this, "_EVENTS_", {});
                R = Y[T];
                if (!R) {
                    Y[T] = R = H.$([]);
                    U = this;
                    if (H.Event.Custom[T]) {
                        H.Event.Custom[T].handler.add.call(this, Z)
                    } else {
                        R.handle = function(aa) {
                            aa = H.extend(aa || window.e, {
                                $J_TYPE: "event"
                            });
                            H.Doc.jCallEvent.call(U, T, H.$(aa))
                        };
                        this[H._event_add_](H._event_prefix_ + T, R.handle, false)
                    }
                }
                X = {
                    type: T,
                    fn: V,
                    priority: W,
                    euid: V.$J_EUID
                };
                R.push(X);
                R.sort(function(ab, aa) {
                    return ab.priority - aa.priority
                });
                return this
            },
            jRemoveEvent: function(X) {
                var V = H.Doc.jFetch.call(this, "_EVENTS_", {}),
                    T, R, S, Y, W, U;
                W = arguments.length > 1 ? arguments[1] : -100;
                if ("string" == H.jTypeOf(X)) {
                    U = X.split(" ");
                    if (U.length > 1) {
                        X = U
                    }
                }
                if (H.jTypeOf(X) == "array") {
                    H.$(X).jEach(this.jRemoveEvent.jBindAsEvent(this, W));
                    return this
                }
                X = O[X] || X;
                if (!X || H.jTypeOf(X) != "string" || !V || !V[X]) {
                    return this
                }
                T = V[X] || [];
                for (S = 0; S < T.length; S++) {
                    R = T[S];
                    if (-100 == W || !!W && W.$J_EUID === R.euid) {
                        Y = T.splice(S--, 1)
                    }
                }
                if (0 === T.length) {
                    if (H.Event.Custom[X]) {
                        H.Event.Custom[X].handler.jRemove.call(this)
                    } else {
                        this[H._event_del_](H._event_prefix_ + X, T.handle, false)
                    }
                    delete V[X]
                }
                return this
            },
            jCallEvent: function(V, X) {
                var U = H.Doc.jFetch.call(this, "_EVENTS_", {}),
                    T, R, S;
                V = O[V] || V;
                if (!V || H.jTypeOf(V) != "string" || !U || !U[V]) {
                    return this
                }
                try {
                    X = H.extend(X || {}, {
                        type: V
                    })
                } catch (W) {}
                if (undefined === X.timeStamp) {
                    X.timeStamp = H.now()
                }
                T = U[V] || [];
                for (S = 0; S < T.length && !(X.isQueueStopped && X.isQueueStopped()); S++) {
                    T[S].fn.call(this, X)
                }
            },
            jRaiseEvent: function(S, R) {
                var V = ("domready" == S) ? false : true,
                    U = this,
                    T;
                S = O[S] || S;
                if (!V) {
                    H.Doc.jCallEvent.call(this, S);
                    return this
                }
                if (U === document && document.createEvent && !U.dispatchEvent) {
                    U = document.documentElement
                }
                if (document.createEvent) {
                    T = document.createEvent(S);
                    T.initEvent(R, true, true)
                } else {
                    T = document.createEventObject();
                    T.eventType = S
                }
                if (document.createEvent) {
                    U.dispatchEvent(T)
                } else {
                    U.fireEvent("on" + R, T)
                }
                return T
            },
            jClearEvents: function() {
                var S = H.Doc.jFetch.call(this, "_EVENTS_");
                if (!S) {
                    return this
                }
                for (var R in S) {
                    H.Doc.jRemoveEvent.call(this, R)
                }
                H.Doc.jDel.call(this, "_EVENTS_");
                return this
            }
        });
        (function(R) {
            if ("complete" === document.readyState) {
                return R.browser.onready.jDelay(1)
            }
            if (R.browser.webkit && R.browser.version < 420) {
                (function() {
                    (R.$(["loaded", "complete"]).contains(document.readyState)) ? R.browser.onready(): arguments.callee.jDelay(50)
                })()
            } else {
                if (R.browser.trident && R.browser.ieMode < 9 && window == top) {
                    (function() {
                        (R.$try(function() {
                            R.browser.getDoc().doScroll("left");
                            return true
                        })) ? R.browser.onready(): arguments.callee.jDelay(50)
                    })()
                } else {
                    R.Doc.jAddEvent.call(R.$(document), "DOMContentLoaded", R.browser.onready);
                    R.Doc.jAddEvent.call(R.$(window), "load", R.browser.onready)
                }
            }
        })(N);
        H.Class = function() {
            var V = null,
                S = H.$A(arguments);
            if ("class" == H.jTypeOf(S[0])) {
                V = S.shift()
            }
            var R = function() {
                for (var Y in this) {
                    this[Y] = H.detach(this[Y])
                }
                if (this.constructor.$parent) {
                    this.$parent = {};
                    var aa = this.constructor.$parent;
                    for (var Z in aa) {
                        var X = aa[Z];
                        switch (H.jTypeOf(X)) {
                            case "function":
                                this.$parent[Z] = H.Class.wrap(this, X);
                                break;
                            case "object":
                                this.$parent[Z] = H.detach(X);
                                break;
                            case "array":
                                this.$parent[Z] = H.detach(X);
                                break
                        }
                    }
                }
                var W = (this.init) ? this.init.apply(this, arguments) : this;
                delete this.caller;
                return W
            };
            if (!R.prototype.init) {
                R.prototype.init = H.$F
            }
            if (V) {
                var U = function() {};
                U.prototype = V.prototype;
                R.prototype = new U;
                R.$parent = {};
                for (var T in V.prototype) {
                    R.$parent[T] = V.prototype[T]
                }
            } else {
                R.$parent = null
            }
            R.constructor = H.Class;
            R.prototype.constructor = R;
            H.extend(R.prototype, S[0]);
            H.extend(R, {
                $J_TYPE: "class"
            });
            return R
        };
        N.Class.wrap = function(R, S) {
            return function() {
                var U = this.caller;
                var T = S.apply(R, arguments);
                return T
            }
        };
        (function(U) {
            var T = U.$;
            var R = 5,
                S = 300;
            U.Event.Custom.btnclick = new U.Class(U.extend(U.Event.Custom, {
                type: "btnclick",
                init: function(X, W) {
                    var V = W.jGetPageXY();
                    this.x = V.x;
                    this.y = V.y;
                    this.clientX = W.clientX;
                    this.clientY = W.clientY;
                    this.timeStamp = W.timeStamp;
                    this.button = W.getButton();
                    this.target = X;
                    this.pushToEvents(W)
                }
            }));
            U.Event.Custom.btnclick.handler = {
                options: {
                    threshold: S,
                    button: 1
                },
                add: function(V) {
                    this.jStore("event:btnclick:options", U.extend(U.detach(U.Event.Custom.btnclick.handler.options), V || {}));
                    this.jAddEvent("mousedown", U.Event.Custom.btnclick.handler.handle, 1);
                    this.jAddEvent("mouseup", U.Event.Custom.btnclick.handler.handle, 1);
                    this.jAddEvent("click", U.Event.Custom.btnclick.handler.onclick, 1);
                    if (U.browser.trident && U.browser.ieMode < 9) {
                        this.jAddEvent("dblclick", U.Event.Custom.btnclick.handler.handle, 1)
                    }
                },
                jRemove: function() {
                    this.jRemoveEvent("mousedown", U.Event.Custom.btnclick.handler.handle);
                    this.jRemoveEvent("mouseup", U.Event.Custom.btnclick.handler.handle);
                    this.jRemoveEvent("click", U.Event.Custom.btnclick.handler.onclick);
                    if (U.browser.trident && U.browser.ieMode < 9) {
                        this.jRemoveEvent("dblclick", U.Event.Custom.btnclick.handler.handle)
                    }
                },
                onclick: function(V) {
                    V.stopDefaults()
                },
                handle: function(Y) {
                    var X, V, W;
                    V = this.jFetch("event:btnclick:options");
                    if (Y.type != "dblclick" && Y.getButton() != V.button) {
                        return
                    }
                    if (this.jFetch("event:btnclick:ignore")) {
                        this.jDel("event:btnclick:ignore");
                        return
                    }
                    if ("mousedown" == Y.type) {
                        X = new U.Event.Custom.btnclick(this, Y);
                        this.jStore("event:btnclick:btnclickEvent", X)
                    } else {
                        if ("mouseup" == Y.type) {
                            X = this.jFetch("event:btnclick:btnclickEvent");
                            if (!X) {
                                return
                            }
                            W = Y.jGetPageXY();
                            this.jDel("event:btnclick:btnclickEvent");
                            X.pushToEvents(Y);
                            if (Y.timeStamp - X.timeStamp <= V.threshold && Math.sqrt(Math.pow(W.x - X.x, 2) + Math.pow(W.y - X.y, 2)) <= R) {
                                this.jCallEvent("btnclick", X)
                            }
                            document.jCallEvent("mouseup", Y)
                        } else {
                            if (Y.type == "dblclick") {
                                X = new U.Event.Custom.btnclick(this, Y);
                                this.jCallEvent("btnclick", X)
                            }
                        }
                    }
                }
            }
        })(N);
        (function(S) {
            var R = S.$;
            S.Event.Custom.mousedrag = new S.Class(S.extend(S.Event.Custom, {
                type: "mousedrag",
                state: "dragstart",
                dragged: false,
                init: function(W, V, U) {
                    var T = V.jGetPageXY();
                    this.x = T.x;
                    this.y = T.y;
                    this.clientX = V.clientX;
                    this.clientY = V.clientY;
                    this.timeStamp = V.timeStamp;
                    this.button = V.getButton();
                    this.target = W;
                    this.pushToEvents(V);
                    this.state = U
                }
            }));
            S.Event.Custom.mousedrag.handler = {
                add: function() {
                    var U = S.Event.Custom.mousedrag.handler.handleMouseMove.jBindAsEvent(this),
                        T = S.Event.Custom.mousedrag.handler.handleMouseUp.jBindAsEvent(this);
                    this.jAddEvent("mousedown", S.Event.Custom.mousedrag.handler.handleMouseDown, 1);
                    this.jAddEvent("mouseup", S.Event.Custom.mousedrag.handler.handleMouseUp, 1);
                    document.jAddEvent("mousemove", U, 1);
                    document.jAddEvent("mouseup", T, 1);
                    this.jStore("event:mousedrag:listeners:document:move", U);
                    this.jStore("event:mousedrag:listeners:document:end", T)
                },
                jRemove: function() {
                    this.jRemoveEvent("mousedown", S.Event.Custom.mousedrag.handler.handleMouseDown);
                    this.jRemoveEvent("mouseup", S.Event.Custom.mousedrag.handler.handleMouseUp);
                    R(document).jRemoveEvent("mousemove", this.jFetch("event:mousedrag:listeners:document:move") || S.$F);
                    R(document).jRemoveEvent("mouseup", this.jFetch("event:mousedrag:listeners:document:end") || S.$F);
                    this.jDel("event:mousedrag:listeners:document:move");
                    this.jDel("event:mousedrag:listeners:document:end")
                },
                handleMouseDown: function(U) {
                    var T;
                    if (1 != U.getButton()) {
                        return
                    }
                    T = new S.Event.Custom.mousedrag(this, U, "dragstart");
                    this.jStore("event:mousedrag:dragstart", T)
                },
                handleMouseUp: function(U) {
                    var T;
                    T = this.jFetch("event:mousedrag:dragstart");
                    if (!T) {
                        return
                    }
                    U.stopDefaults();
                    T = new S.Event.Custom.mousedrag(this, U, "dragend");
                    this.jDel("event:mousedrag:dragstart");
                    this.jCallEvent("mousedrag", T)
                },
                handleMouseMove: function(U) {
                    var T;
                    T = this.jFetch("event:mousedrag:dragstart");
                    if (!T) {
                        return
                    }
                    U.stopDefaults();
                    if (!T.dragged) {
                        T.dragged = true;
                        this.jCallEvent("mousedrag", T)
                    }
                    T = new S.Event.Custom.mousedrag(this, U, "dragmove");
                    this.jCallEvent("mousedrag", T)
                }
            }
        })(N);
        (function(S) {
            var R = S.$;
            S.Event.Custom.dblbtnclick = new S.Class(S.extend(S.Event.Custom, {
                type: "dblbtnclick",
                timedout: false,
                tm: null,
                init: function(V, U) {
                    var T = U.jGetPageXY();
                    this.x = T.x;
                    this.y = T.y;
                    this.clientX = U.clientX;
                    this.clientY = U.clientY;
                    this.timeStamp = U.timeStamp;
                    this.button = U.getButton();
                    this.target = V;
                    this.pushToEvents(U)
                }
            }));
            S.Event.Custom.dblbtnclick.handler = {
                options: {
                    threshold: 200
                },
                add: function(T) {
                    this.jStore("event:dblbtnclick:options", S.extend(S.detach(S.Event.Custom.dblbtnclick.handler.options), T || {}));
                    this.jAddEvent("btnclick", S.Event.Custom.dblbtnclick.handler.handle, 1)
                },
                jRemove: function() {
                    this.jRemoveEvent("btnclick", S.Event.Custom.dblbtnclick.handler.handle)
                },
                handle: function(V) {
                    var U, T;
                    U = this.jFetch("event:dblbtnclick:event");
                    T = this.jFetch("event:dblbtnclick:options");
                    if (!U) {
                        U = new S.Event.Custom.dblbtnclick(this, V);
                        U.tm = setTimeout(function() {
                            U.timedout = true;
                            V.isQueueStopped = S.leofalse;
                            this.jCallEvent("btnclick", V);
                            this.jDel("event:dblbtnclick:event")
                        }.jBind(this), T.threshold + 10);
                        this.jStore("event:dblbtnclick:event", U);
                        V.stopQueue()
                    } else {
                        clearTimeout(U.tm);
                        this.jDel("event:dblbtnclick:event");
                        if (!U.timedout) {
                            U.pushToEvents(V);
                            V.stopQueue().stop();
                            this.jCallEvent("dblbtnclick", U)
                        } else {}
                    }
                }
            }
        })(N);
        (function(X) {
            var W = X.$;

            function R(Y) {
                return Y.pointerType ? (("touch" === Y.pointerType || Y.MSPOINTER_TYPE_TOUCH === Y.pointerType) && Y.isPrimary) : 1 === Y.changedTouches.length && (Y.targetTouches.length ? Y.targetTouches[0].identifier == Y.changedTouches[0].identifier : true)
            }

            function T(Y) {
                if (Y.pointerType) {
                    return ("touch" === Y.pointerType || Y.MSPOINTER_TYPE_TOUCH === Y.pointerType) ? Y.pointerId : null
                } else {
                    return Y.changedTouches[0].identifier
                }
            }

            function U(Y) {
                if (Y.pointerType) {
                    return ("touch" === Y.pointerType || Y.MSPOINTER_TYPE_TOUCH === Y.pointerType) ? Y : null
                } else {
                    return Y.changedTouches[0]
                }
            }
            X.Event.Custom.tap = new X.Class(X.extend(X.Event.Custom, {
                type: "tap",
                id: null,
                init: function(Z, Y) {
                    var aa = U(Y);
                    this.id = aa.pointerId || aa.identifier;
                    this.x = aa.pageX;
                    this.y = aa.pageY;
                    this.pageX = aa.pageX;
                    this.pageY = aa.pageY;
                    this.clientX = aa.clientX;
                    this.clientY = aa.clientY;
                    this.timeStamp = Y.timeStamp;
                    this.button = 0;
                    this.target = Z;
                    this.pushToEvents(Y)
                }
            }));
            var S = 10,
                V = 200;
            X.Event.Custom.tap.handler = {
                add: function(Y) {
                    this.jAddEvent(["touchstart", window.navigator.pointerEnabled ? "pointerdown" : "MSPointerDown"], X.Event.Custom.tap.handler.onTouchStart, 1);
                    this.jAddEvent(["touchend", window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp"], X.Event.Custom.tap.handler.onTouchEnd, 1);
                    this.jAddEvent("click", X.Event.Custom.tap.handler.onClick, 1)
                },
                jRemove: function() {
                    this.jRemoveEvent(["touchstart", window.navigator.pointerEnabled ? "pointerdown" : "MSPointerDown"], X.Event.Custom.tap.handler.onTouchStart);
                    this.jRemoveEvent(["touchend", window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp"], X.Event.Custom.tap.handler.onTouchEnd);
                    this.jRemoveEvent("click", X.Event.Custom.tap.handler.onClick)
                },
                onClick: function(Y) {
                    Y.stopDefaults()
                },
                onTouchStart: function(Y) {
                    if (!R(Y)) {
                        this.jDel("event:tap:event");
                        return
                    }
                    this.jStore("event:tap:event", new X.Event.Custom.tap(this, Y));
                    this.jStore("event:btnclick:ignore", true)
                },
                onTouchEnd: function(ab) {
                    var Z = X.now(),
                        aa = this.jFetch("event:tap:event"),
                        Y = this.jFetch("event:tap:options");
                    if (!aa || !R(ab)) {
                        return
                    }
                    this.jDel("event:tap:event");
                    if (aa.id == T(ab) && ab.timeStamp - aa.timeStamp <= V && Math.sqrt(Math.pow(U(ab).pageX - aa.x, 2) + Math.pow(U(ab).pageY - aa.y, 2)) <= S) {
                        this.jDel("event:btnclick:btnclickEvent");
                        ab.stop();
                        aa.pushToEvents(ab);
                        this.jCallEvent("tap", aa)
                    }
                }
            }
        })(N);
        H.Event.Custom.dbltap = new H.Class(H.extend(H.Event.Custom, {
            type: "dbltap",
            timedout: false,
            tm: null,
            init: function(S, R) {
                this.x = R.x;
                this.y = R.y;
                this.clientX = R.clientX;
                this.clientY = R.clientY;
                this.timeStamp = R.timeStamp;
                this.button = 0;
                this.target = S;
                this.pushToEvents(R)
            }
        }));
        H.Event.Custom.dbltap.handler = {
            options: {
                threshold: 300
            },
            add: function(R) {
                this.jStore("event:dbltap:options", H.extend(H.detach(H.Event.Custom.dbltap.handler.options), R || {}));
                this.jAddEvent("tap", H.Event.Custom.dbltap.handler.handle, 1)
            },
            jRemove: function() {
                this.jRemoveEvent("tap", H.Event.Custom.dbltap.handler.handle)
            },
            handle: function(T) {
                var S, R;
                S = this.jFetch("event:dbltap:event");
                R = this.jFetch("event:dbltap:options");
                if (!S) {
                    S = new H.Event.Custom.dbltap(this, T);
                    S.tm = setTimeout(function() {
                        S.timedout = true;
                        T.isQueueStopped = H.leofalse;
                        this.jCallEvent("tap", T)
                    }.jBind(this), R.threshold + 10);
                    this.jStore("event:dbltap:event", S);
                    T.stopQueue()
                } else {
                    clearTimeout(S.tm);
                    this.jDel("event:dbltap:event");
                    if (!S.timedout) {
                        S.pushToEvents(T);
                        T.stopQueue().stop();
                        this.jCallEvent("dbltap", S)
                    } else {}
                }
            }
        };
        (function(W) {
            var V = W.$;

            function R(X) {
                return X.pointerType ? (("touch" === X.pointerType || X.MSPOINTER_TYPE_TOUCH === X.pointerType) && X.isPrimary) : 1 === X.changedTouches.length && (X.targetTouches.length ? X.targetTouches[0].identifier == X.changedTouches[0].identifier : true)
            }

            function T(X) {
                if (X.pointerType) {
                    return ("touch" === X.pointerType || X.MSPOINTER_TYPE_TOUCH === X.pointerType) ? X.pointerId : null
                } else {
                    return X.changedTouches[0].identifier
                }
            }

            function U(X) {
                if (X.pointerType) {
                    return ("touch" === X.pointerType || X.MSPOINTER_TYPE_TOUCH === X.pointerType) ? X : null
                } else {
                    return X.changedTouches[0]
                }
            }
            var S = 10;
            W.Event.Custom.touchdrag = new W.Class(W.extend(W.Event.Custom, {
                type: "touchdrag",
                state: "dragstart",
                id: null,
                dragged: false,
                init: function(Z, Y, X) {
                    var aa = U(Y);
                    this.id = aa.pointerId || aa.identifier;
                    this.clientX = aa.clientX;
                    this.clientY = aa.clientY;
                    this.pageX = aa.pageX;
                    this.pageY = aa.pageY;
                    this.x = aa.pageX;
                    this.y = aa.pageY;
                    this.timeStamp = Y.timeStamp;
                    this.button = 0;
                    this.target = Z;
                    this.pushToEvents(Y);
                    this.state = X
                }
            }));
            W.Event.Custom.touchdrag.handler = {
                add: function() {
                    var Y = W.Event.Custom.touchdrag.handler.onTouchMove.jBind(this),
                        X = W.Event.Custom.touchdrag.handler.onTouchEnd.jBind(this);
                    this.jAddEvent(["touchstart", window.navigator.pointerEnabled ? "pointerdown" : "MSPointerDown"], W.Event.Custom.touchdrag.handler.onTouchStart, 1);
                    this.jAddEvent(["touchend", window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp"], W.Event.Custom.touchdrag.handler.onTouchEnd, 1);
                    this.jAddEvent(["touchmove", window.navigator.pointerEnabled ? "pointermove" : "MSPointerMove"], W.Event.Custom.touchdrag.handler.onTouchMove, 1);
                    this.jStore("event:touchdrag:listeners:document:move", Y);
                    this.jStore("event:touchdrag:listeners:document:end", X);
                    V(document).jAddEvent(window.navigator.pointerEnabled ? "pointermove" : "MSPointerMove", Y, 1);
                    V(document).jAddEvent(window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp", X, 1)
                },
                jRemove: function() {
                    this.jRemoveEvent(["touchstart", window.navigator.pointerEnabled ? "pointerdown" : "MSPointerDown"], W.Event.Custom.touchdrag.handler.onTouchStart);
                    this.jRemoveEvent(["touchend", window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp"], W.Event.Custom.touchdrag.handler.onTouchEnd);
                    this.jRemoveEvent(["touchmove", window.navigator.pointerEnabled ? "pointermove" : "MSPointerMove"], W.Event.Custom.touchdrag.handler.onTouchMove);
                    V(document).jRemoveEvent(window.navigator.pointerEnabled ? "pointermove" : "MSPointerMove", this.jFetch("event:touchdrag:listeners:document:move") || W.$F, 1);
                    V(document).jRemoveEvent(window.navigator.pointerEnabled ? "pointerup" : "MSPointerUp", this.jFetch("event:touchdrag:listeners:document:end") || W.$F, 1);
                    this.jDel("event:touchdrag:listeners:document:move");
                    this.jDel("event:touchdrag:listeners:document:end")
                },
                onTouchStart: function(Y) {
                    var X;
                    if (!R(Y)) {
                        return
                    }
                    X = new W.Event.Custom.touchdrag(this, Y, "dragstart");
                    this.jStore("event:touchdrag:dragstart", X)
                },
                onTouchEnd: function(Y) {
                    var X;
                    X = this.jFetch("event:touchdrag:dragstart");
                    if (!X || !X.dragged || X.id != T(Y)) {
                        return
                    }
                    X = new W.Event.Custom.touchdrag(this, Y, "dragend");
                    this.jDel("event:touchdrag:dragstart");
                    this.jCallEvent("touchdrag", X)
                },
                onTouchMove: function(Y) {
                    var X;
                    X = this.jFetch("event:touchdrag:dragstart");
                    if (!X || !R(Y)) {
                        return
                    }
                    if (X.id != T(Y)) {
                        this.jDel("event:touchdrag:dragstart");
                        return
                    }
                    if (!X.dragged && Math.sqrt(Math.pow(U(Y).pageX - X.x, 2) + Math.pow(U(Y).pageY - X.y, 2)) > S) {
                        X.dragged = true;
                        this.jCallEvent("touchdrag", X)
                    }
                    if (!X.dragged) {
                        return
                    }
                    X = new W.Event.Custom.touchdrag(this, Y, "dragmove");
                    this.jCallEvent("touchdrag", X)
                }
            }
        })(N);
        H.Event.Custom.touchpinch = new H.Class(H.extend(H.Event.Custom, {
            type: "touchpinch",
            scale: 1,
            previousScale: 1,
            curScale: 1,
            state: "pinchstart",
            init: function(S, R) {
                this.timeStamp = R.timeStamp;
                this.button = 0;
                this.target = S;
                this.x = R.touches[0].clientX + (R.touches[1].clientX - R.touches[0].clientX) / 2;
                this.y = R.touches[0].clientY + (R.touches[1].clientY - R.touches[0].clientY) / 2;
                this._initialDistance = Math.sqrt(Math.pow(R.touches[0].clientX - R.touches[1].clientX, 2) + Math.pow(R.touches[0].clientY - R.touches[1].clientY, 2));
                this.pushToEvents(R)
            },
            update: function(R) {
                var S;
                this.state = "pinchupdate";
                if (R.changedTouches[0].identifier != this.events[0].touches[0].identifier || R.changedTouches[1].identifier != this.events[0].touches[1].identifier) {
                    return
                }
                S = Math.sqrt(Math.pow(R.changedTouches[0].clientX - R.changedTouches[1].clientX, 2) + Math.pow(R.changedTouches[0].clientY - R.changedTouches[1].clientY, 2));
                this.previousScale = this.scale;
                this.scale = S / this._initialDistance;
                this.curScale = this.scale / this.previousScale;
                this.x = R.changedTouches[0].clientX + (R.changedTouches[1].clientX - R.changedTouches[0].clientX) / 2;
                this.y = R.changedTouches[0].clientY + (R.changedTouches[1].clientY - R.changedTouches[0].clientY) / 2;
                this.pushToEvents(R)
            }
        }));
        H.Event.Custom.touchpinch.handler = {
            add: function() {
                this.jAddEvent("touchstart", H.Event.Custom.touchpinch.handler.handleTouchStart, 1);
                this.jAddEvent("touchend", H.Event.Custom.touchpinch.handler.handleTouchEnd, 1);
                this.jAddEvent("touchmove", H.Event.Custom.touchpinch.handler.handleTouchMove, 1)
            },
            jRemove: function() {
                this.jRemoveEvent("touchstart", H.Event.Custom.touchpinch.handler.handleTouchStart);
                this.jRemoveEvent("touchend", H.Event.Custom.touchpinch.handler.handleTouchEnd);
                this.jRemoveEvent("touchmove", H.Event.Custom.touchpinch.handler.handleTouchMove)
            },
            handleTouchStart: function(S) {
                var R;
                if (S.touches.length != 2) {
                    return
                }
                S.stopDefaults();
                R = new H.Event.Custom.touchpinch(this, S);
                this.jStore("event:touchpinch:event", R)
            },
            handleTouchEnd: function(S) {
                var R;
                R = this.jFetch("event:touchpinch:event");
                if (!R) {
                    return
                }
                S.stopDefaults();
                this.jDel("event:touchpinch:event")
            },
            handleTouchMove: function(S) {
                var R;
                R = this.jFetch("event:touchpinch:event");
                if (!R) {
                    return
                }
                S.stopDefaults();
                R.update(S);
                this.jCallEvent("touchpinch", R)
            }
        };
        (function(W) {
            var U = W.$;
            W.Event.Custom.mousescroll = new W.Class(W.extend(W.Event.Custom, {
                type: "mousescroll",
                init: function(ac, ab, ae, Y, X, ad, Z) {
                    var aa = ab.jGetPageXY();
                    this.x = aa.x;
                    this.y = aa.y;
                    this.timeStamp = ab.timeStamp;
                    this.target = ac;
                    this.delta = ae || 0;
                    this.deltaX = Y || 0;
                    this.deltaY = X || 0;
                    this.deltaZ = ad || 0;
                    this.deltaFactor = Z || 0;
                    this.deltaMode = ab.deltaMode || 0;
                    this.isMouse = false;
                    this.pushToEvents(ab)
                }
            }));
            var V, S;

            function R() {
                V = null
            }

            function T(X, Y) {
                return (X > 50) || (1 === Y && !("win" == W.browser.platform && X < 1)) || (0 === X % 12) || (0 == X % 4.000244140625)
            }
            W.Event.Custom.mousescroll.handler = {
                eventType: "onwheel" in document || W.browser.ieMode > 8 ? "wheel" : "mousewheel",
                add: function() {
                    this.jAddEvent(W.Event.Custom.mousescroll.handler.eventType, W.Event.Custom.mousescroll.handler.handle, 1)
                },
                jRemove: function() {
                    this.jRemoveEvent(W.Event.Custom.mousescroll.handler.eventType, W.Event.Custom.mousescroll.handler.handle, 1)
                },
                handle: function(ac) {
                    var ad = 0,
                        aa = 0,
                        Y = 0,
                        X = 0,
                        ab, Z;
                    if (ac.detail) {
                        Y = ac.detail * -1
                    }
                    if (ac.wheelDelta !== undefined) {
                        Y = ac.wheelDelta
                    }
                    if (ac.wheelDeltaY !== undefined) {
                        Y = ac.wheelDeltaY
                    }
                    if (ac.wheelDeltaX !== undefined) {
                        aa = ac.wheelDeltaX * -1
                    }
                    if (ac.deltaY) {
                        Y = -1 * ac.deltaY
                    }
                    if (ac.deltaX) {
                        aa = ac.deltaX
                    }
                    if (0 === Y && 0 === aa) {
                        return
                    }
                    ad = 0 === Y ? aa : Y;
                    X = Math.max(Math.abs(Y), Math.abs(aa));
                    if (!V || X < V) {
                        V = X
                    }
                    ab = ad > 0 ? "floor" : "ceil";
                    ad = Math[ab](ad / V);
                    aa = Math[ab](aa / V);
                    Y = Math[ab](Y / V);
                    if (S) {
                        clearTimeout(S)
                    }
                    S = setTimeout(R, 200);
                    Z = new W.Event.Custom.mousescroll(this, ac, ad, aa, Y, 0, V);
                    Z.isMouse = T(V, ac.deltaMode || 0);
                    this.jCallEvent("mousescroll", Z)
                }
            }
        })(N);
        H.win = H.$(window);
        H.doc = H.$(document);
        return N
    })();
    (function(D) {
        if (!D) {
            throw "LeoImageJS not found"
        }
        var C = D.$;
        var B = window.URL || window.webkitURL || null;
        s.ImageLoader = new D.Class({
            img: null,
            ready: false,
            options: {
                onprogress: D.$F,
                onload: D.$F,
                onabort: D.$F,
                onerror: D.$F,
                oncomplete: D.$F,
                onxhrerror: D.$F,
                xhr: false,
                progressiveLoad: true
            },
            size: null,
            _timer: null,
            loadedBytes: 0,
            _handlers: {
                onprogress: function(E) {
                    if (E.target && (200 === E.target.status || 304 === E.target.status) && E.lengthComputable) {
                        this.options.onprogress.jBind(null, (E.loaded - (this.options.progressiveLoad ? this.loadedBytes : 0)) / E.total).jDelay(1);
                        this.loadedBytes = E.loaded
                    }
                },
                onload: function(E) {
                    if (E) {
                        C(E).stop()
                    }
                    this._unbind();
                    if (this.ready) {
                        return
                    }
                    this.ready = true;
                    this._cleanup();
                    !this.options.xhr && this.options.onprogress.jBind(null, 1).jDelay(1);
                    this.options.onload.jBind(null, this).jDelay(1);
                    this.options.oncomplete.jBind(null, this).jDelay(1)
                },
                onabort: function(E) {
                    if (E) {
                        C(E).stop()
                    }
                    this._unbind();
                    this.ready = false;
                    this._cleanup();
                    this.options.onabort.jBind(null, this).jDelay(1);
                    this.options.oncomplete.jBind(null, this).jDelay(1)
                },
                onerror: function(E) {
                    if (E) {
                        C(E).stop()
                    }
                    this._unbind();
                    this.ready = false;
                    this._cleanup();
                    this.options.onerror.jBind(null, this).jDelay(1);
                    this.options.oncomplete.jBind(null, this).jDelay(1)
                }
            },
            _bind: function() {
                C(["load", "abort", "error"]).jEach(function(E) {
                    this.img.jAddEvent(E, this._handlers["on" + E].jBindAsEvent(this).jDefer(1))
                }, this)
            },
            _unbind: function() {
                if (this._timer) {
                    try {
                        clearTimeout(this._timer)
                    } catch (E) {}
                    this._timer = null
                }
                C(["load", "abort", "error"]).jEach(function(F) {
                    this.img.jRemoveEvent(F)
                }, this)
            },
            _cleanup: function() {
                this.jGetSize();
                if (this.img.jFetch("new")) {
                    var E = this.img.parentNode;
                    this.img.jRemove().jDel("new").jSetCss({
                        position: "static",
                        top: "auto"
                    });
                    E.kill()
                }
            },
            loadBlob: function(F) {
                var G = new XMLHttpRequest(),
                    E;
                C(["abort", "progress"]).jEach(function(H) {
                    G["on" + H] = C(function(I) {
                        this._handlers["on" + H].call(this, I)
                    }).jBind(this)
                }, this);
                G.onerror = C(function() {
                    this.options.onxhrerror.jBind(null, this).jDelay(1);
                    this.options.xhr = false;
                    this._bind();
                    this.img.src = F
                }).jBind(this);
                G.onload = C(function() {
                    if (200 !== G.status && 304 !== G.status) {
                        this._handlers.onerror.call(this);
                        return
                    }
                    E = G.response;
                    this._bind();
                    if (B && !D.browser.trident && !("ios" === D.browser.platform && D.browser.version < 537)) {
                        this.img.setAttribute("src", B.createObjectURL(E))
                    } else {
                        this.img.src = F
                    }
                }).jBind(this);
                G.open("GET", F);
                G.responseType = "blob";
                G.send()
            },
            init: function(F, E) {
                this.options = D.extend(this.options, E);
                this.img = C(F) || D.$new("img", {}, {
                    "max-width": "none",
                    "max-height": "none"
                }).jAppendTo(D.$new("div").jAddClass("leoimage-temporary-img").jSetCss({
                    position: "absolute",
                    top: -10000,
                    width: 10,
                    height: 10,
                    overflow: "hidden"
                }).jAppendTo(document.body)).jStore("new", true);
                if (D.browser.features.xhr2 && this.options.xhr && "string" == D.jTypeOf(F)) {
                    this.loadBlob(F);
                    return
                }
                var G = function() {
                    if (this.isReady()) {
                        this._handlers.onload.call(this)
                    } else {
                        this._handlers.onerror.call(this)
                    }
                    G = null
                }.jBind(this);
                this._bind();
                if ("string" == D.jTypeOf(F)) {
                    this.img.src = F
                } else {
                    if (D.browser.trident && 5 == D.browser.version && D.browser.ieMode < 9) {
                        this.img.onreadystatechange = function() {
                            if (/loaded|complete/.test(this.img.readyState)) {
                                this.img.onreadystatechange = null;
                                G && G()
                            }
                        }.jBind(this)
                    }
                    this.img.src = F.getAttribute("src")
                }
                this.img && this.img.complete && G && (this._timer = G.jDelay(100))
            },
            destroy: function() {
                this._unbind();
                this._cleanup();
                this.ready = false;
                return this
            },
            isReady: function() {
                var E = this.img;
                return (E.naturalWidth) ? (E.naturalWidth > 0) : (E.readyState) ? ("complete" == E.readyState) : E.width > 0
            },
            jGetSize: function() {
                return this.size || (this.size = {
                    width: this.img.naturalWidth || this.img.width,
                    height: this.img.naturalHeight || this.img.height
                })
            }
        })
    })(s);
    (function(C) {
        if (!C) {
            throw "LeoImageJS not found"
        }
        if (C.FX) {
            return
        }
        var B = C.$;
        C.FX = new C.Class({
            init: function(E, D) {
                var F;
                this.el = C.$(E);
                this.options = C.extend(this.options, D);
                this.timer = false;
                this.easeFn = this.cubicBezierAtTime;
                F = C.FX.Transition[this.options.transition] || this.options.transition;
                if ("function" === C.jTypeOf(F)) {
                    this.easeFn = F
                } else {
                    this.cubicBezier = this.parseCubicBezier(F) || this.parseCubicBezier("ease")
                }
                if ("string" == C.jTypeOf(this.options.cycles)) {
                    this.options.cycles = "infinite" === this.options.cycles ? Infinity : parseInt(this.options.cycles) || 1
                }
            },
            options: {
                fps: 60,
                duration: 600,
                transition: "ease",
                cycles: 1,
                direction: "normal",
                onStart: C.$F,
                onComplete: C.$F,
                onBeforeRender: C.$F,
                onAfterRender: C.$F,
                forceAnimation: false,
                roundCss: false
            },
            styles: null,
            cubicBezier: null,
            easeFn: null,
            setTransition: function(D) {
                this.options.transition = D;
                D = C.FX.Transition[this.options.transition] || this.options.transition;
                if ("function" === C.jTypeOf(D)) {
                    this.easeFn = D
                } else {
                    this.easeFn = this.cubicBezierAtTime;
                    this.cubicBezier = this.parseCubicBezier(D) || this.parseCubicBezier("ease")
                }
            },
            start: function(F) {
                var D = /\%$/,
                    E;
                this.styles = F || {};
                this.cycle = 0;
                this.state = 0;
                this.curFrame = 0;
                this.pStyles = {};
                this.alternate = "alternate" === this.options.direction || "alternate-reverse" === this.options.direction;
                this.continuous = "continuous" === this.options.direction || "continuous-reverse" === this.options.direction;
                for (E in this.styles) {
                    D.test(this.styles[E][0]) && (this.pStyles[E] = true);
                    if ("reverse" === this.options.direction || "alternate-reverse" === this.options.direction || "continuous-reverse" === this.options.direction) {
                        this.styles[E].reverse()
                    }
                }
                this.startTime = C.now();
                this.finishTime = this.startTime + this.options.duration;
                this.options.onStart.call();
                if (0 === this.options.duration) {
                    this.render(1);
                    this.options.onComplete.call()
                } else {
                    this.loopBind = this.loop.jBind(this);
                    if (!this.options.forceAnimation && C.browser.features.requestAnimationFrame) {
                        this.timer = C.browser.requestAnimationFrame.call(window, this.loopBind)
                    } else {
                        this.timer = this.loopBind.interval(Math.round(1000 / this.options.fps))
                    }
                }
                return this
            },
            stopAnimation: function() {
                if (this.timer) {
                    if (!this.options.forceAnimation && C.browser.features.requestAnimationFrame && C.browser.cancelAnimationFrame) {
                        C.browser.cancelAnimationFrame.call(window, this.timer)
                    } else {
                        clearInterval(this.timer)
                    }
                    this.timer = false
                }
            },
            stop: function(D) {
                D = C.defined(D) ? D : false;
                this.stopAnimation();
                if (D) {
                    this.render(1);
                    this.options.onComplete.jDelay(10)
                }
                return this
            },
            calc: function(F, E, D) {
                F = parseFloat(F);
                E = parseFloat(E);
                return (E - F) * D + F
            },
            loop: function() {
                var E = C.now(),
                    D = (E - this.startTime) / this.options.duration,
                    F = Math.floor(D);
                if (E >= this.finishTime && F >= this.options.cycles) {
                    this.stopAnimation();
                    this.render(1);
                    this.options.onComplete.jDelay(10);
                    return this
                }
                if (this.alternate && this.cycle < F) {
                    for (var G in this.styles) {
                        this.styles[G].reverse()
                    }
                }
                this.cycle = F;
                if (!this.options.forceAnimation && C.browser.features.requestAnimationFrame) {
                    this.timer = C.browser.requestAnimationFrame.call(window, this.loopBind)
                }
                this.render((this.continuous ? F : 0) + this.easeFn(D % 1))
            },
            render: function(D) {
                var E = {},
                    G = D;
                for (var F in this.styles) {
                    if ("opacity" === F) {
                        E[F] = Math.round(this.calc(this.styles[F][0], this.styles[F][1], D) * 100) / 100
                    } else {
                        E[F] = this.calc(this.styles[F][0], this.styles[F][1], D);
                        this.pStyles[F] && (E[F] += "%")
                    }
                }
                this.options.onBeforeRender(E, this.el);
                this.set(E);
                this.options.onAfterRender(E, this.el)
            },
            set: function(D) {
                return this.el.jSetCss(D)
            },
            parseCubicBezier: function(D) {
                var E, F = null;
                if ("string" !== C.jTypeOf(D)) {
                    return null
                }
                switch (D) {
                    case "linear":
                        F = B([0, 0, 1, 1]);
                        break;
                    case "ease":
                        F = B([0.25, 0.1, 0.25, 1]);
                        break;
                    case "ease-in":
                        F = B([0.42, 0, 1, 1]);
                        break;
                    case "ease-out":
                        F = B([0, 0, 0.58, 1]);
                        break;
                    case "ease-in-out":
                        F = B([0.42, 0, 0.58, 1]);
                        break;
                    case "easeInSine":
                        F = B([0.47, 0, 0.745, 0.715]);
                        break;
                    case "easeOutSine":
                        F = B([0.39, 0.575, 0.565, 1]);
                        break;
                    case "easeInOutSine":
                        F = B([0.445, 0.05, 0.55, 0.95]);
                        break;
                    case "easeInQuad":
                        F = B([0.55, 0.085, 0.68, 0.53]);
                        break;
                    case "easeOutQuad":
                        F = B([0.25, 0.46, 0.45, 0.94]);
                        break;
                    case "easeInOutQuad":
                        F = B([0.455, 0.03, 0.515, 0.955]);
                        break;
                    case "easeInCubic":
                        F = B([0.55, 0.055, 0.675, 0.19]);
                        break;
                    case "easeOutCubic":
                        F = B([0.215, 0.61, 0.355, 1]);
                        break;
                    case "easeInOutCubic":
                        F = B([0.645, 0.045, 0.355, 1]);
                        break;
                    case "easeInQuart":
                        F = B([0.895, 0.03, 0.685, 0.22]);
                        break;
                    case "easeOutQuart":
                        F = B([0.165, 0.84, 0.44, 1]);
                        break;
                    case "easeInOutQuart":
                        F = B([0.77, 0, 0.175, 1]);
                        break;
                    case "easeInQuint":
                        F = B([0.755, 0.05, 0.855, 0.06]);
                        break;
                    case "easeOutQuint":
                        F = B([0.23, 1, 0.32, 1]);
                        break;
                    case "easeInOutQuint":
                        F = B([0.86, 0, 0.07, 1]);
                        break;
                    case "easeInExpo":
                        F = B([0.95, 0.05, 0.795, 0.035]);
                        break;
                    case "easeOutExpo":
                        F = B([0.19, 1, 0.22, 1]);
                        break;
                    case "easeInOutExpo":
                        F = B([1, 0, 0, 1]);
                        break;
                    case "easeInCirc":
                        F = B([0.6, 0.04, 0.98, 0.335]);
                        break;
                    case "easeOutCirc":
                        F = B([0.075, 0.82, 0.165, 1]);
                        break;
                    case "easeInOutCirc":
                        F = B([0.785, 0.135, 0.15, 0.86]);
                        break;
                    case "easeInBack":
                        F = B([0.6, -0.28, 0.735, 0.045]);
                        break;
                    case "easeOutBack":
                        F = B([0.175, 0.885, 0.32, 1.275]);
                        break;
                    case "easeInOutBack":
                        F = B([0.68, -0.55, 0.265, 1.55]);
                        break;
                    default:
                        D = D.replace(/\s/g, "");
                        if (D.match(/^cubic-bezier\((?:-?[0-9\.]{0,}[0-9]{1,},){3}(?:-?[0-9\.]{0,}[0-9]{1,})\)$/)) {
                            F = D.replace(/^cubic-bezier\s*\(|\)$/g, "").split(",");
                            for (E = F.length - 1; E >= 0; E--) {
                                F[E] = parseFloat(F[E])
                            }
                        }
                }
                return B(F)
            },
            cubicBezierAtTime: function(P) {
                var D = 0,
                    O = 0,
                    L = 0,
                    Q = 0,
                    N = 0,
                    J = 0,
                    K = this.options.duration;

                function I(R) {
                    return ((D * R + O) * R + L) * R
                }

                function H(R) {
                    return ((Q * R + N) * R + J) * R
                }

                function F(R) {
                    return (3 * D * R + 2 * O) * R + L
                }

                function M(R) {
                    return 1 / (200 * R)
                }

                function E(R, S) {
                    return H(G(R, S))
                }

                function G(Y, Z) {
                    var X, W, V, S, R, U;

                    function T(aa) {
                        if (aa >= 0) {
                            return aa
                        } else {
                            return 0 - aa
                        }
                    }
                    for (V = Y, U = 0; U < 8; U++) {
                        S = I(V) - Y;
                        if (T(S) < Z) {
                            return V
                        }
                        R = F(V);
                        if (T(R) < 0.000001) {
                            break
                        }
                        V = V - S / R
                    }
                    X = 0;
                    W = 1;
                    V = Y;
                    if (V < X) {
                        return X
                    }
                    if (V > W) {
                        return W
                    }
                    while (X < W) {
                        S = I(V);
                        if (T(S - Y) < Z) {
                            return V
                        }
                        if (Y > S) {
                            X = V
                        } else {
                            W = V
                        }
                        V = (W - X) * 0.5 + X
                    }
                    return V
                }
                L = 3 * this.cubicBezier[0];
                O = 3 * (this.cubicBezier[2] - this.cubicBezier[0]) - L;
                D = 1 - L - O;
                J = 3 * this.cubicBezier[1];
                N = 3 * (this.cubicBezier[3] - this.cubicBezier[1]) - J;
                Q = 1 - J - N;
                return E(P, M(K))
            }
        });
        C.FX.Transition = {
            linear: "linear",
            sineIn: "easeInSine",
            sineOut: "easeOutSine",
            expoIn: "easeInExpo",
            expoOut: "easeOutExpo",
            quadIn: "easeInQuad",
            quadOut: "easeOutQuad",
            cubicIn: "easeInCubic",
            cubicOut: "easeOutCubic",
            backIn: "easeInBack",
            backOut: "easeOutBack",
            elasticIn: function(E, D) {
                D = D || [];
                return Math.pow(2, 10 * --E) * Math.cos(20 * E * Math.PI * (D[0] || 1) / 3)
            },
            elasticOut: function(E, D) {
                return 1 - C.FX.Transition.elasticIn(1 - E, D)
            },
            bounceIn: function(F) {
                for (var E = 0, D = 1; 1; E += D, D /= 2) {
                    if (F >= (7 - 4 * E) / 11) {
                        return D * D - Math.pow((11 - 6 * E - 11 * F) / 4, 2)
                    }
                }
            },
            bounceOut: function(D) {
                return 1 - C.FX.Transition.bounceIn(1 - D)
            },
            none: function(D) {
                return 0
            }
        }
    })(s);
    (function(C) {
        if (!C) {
            throw "LeoImageJS not found"
        }
        if (C.PFX) {
            return
        }
        var B = C.$;
        C.PFX = new C.Class(C.FX, {
            init: function(D, E) {
                this.el_arr = D;
                this.options = C.extend(this.options, E);
                this.timer = false;
                this.$parent.init()
            },
            start: function(H) {
                var D = /\%$/,
                    G, F, E = H.length;
                this.styles_arr = H;
                this.pStyles_arr = new Array(E);
                for (F = 0; F < E; F++) {
                    this.pStyles_arr[F] = {};
                    for (G in H[F]) {
                        D.test(H[F][G][0]) && (this.pStyles_arr[F][G] = true);
                        if ("reverse" === this.options.direction || "alternate-reverse" === this.options.direction || "continuous-reverse" === this.options.direction) {
                            this.styles_arr[F][G].reverse()
                        }
                    }
                }
                this.$parent.start({});
                return this
            },
            render: function(D) {
                for (var E = 0; E < this.el_arr.length; E++) {
                    this.el = C.$(this.el_arr[E]);
                    this.styles = this.styles_arr[E];
                    this.pStyles = this.pStyles_arr[E];
                    this.$parent.render(D)
                }
            }
        })
    })(s);
    (function(C) {
        if (!C) {
            throw "LeoImageJS not found";
            return
        }
        if (C.Tooltip) {
            return
        }
        var B = C.$;
        C.Tooltip = function(E, F) {
            var D = this.tooltip = C.$new("div", null, {
                position: "absolute",
                "z-index": 999
            }).jAddClass("LeoImageToolboxTooltip");
            C.$(E).jAddEvent("mouseover", function() {
                D.jAppendTo(document.body)
            });
            C.$(E).jAddEvent("mouseout", function() {
                D.jRemove()
            });
            C.$(E).jAddEvent("mousemove", function(K) {
                var M = 20,
                    J = C.$(K).jGetPageXY(),
                    I = D.jGetSize(),
                    H = C.$(window).jGetSize(),
                    L = C.$(window).jGetScroll();

                function G(P, N, O) {
                    return (O < (P - N) / 2) ? O : ((O > (P + N) / 2) ? (O - N) : (P - N) / 2)
                }
                D.jSetCss({
                    left: L.x + G(H.width, I.width + 2 * M, J.x - L.x) + M,
                    top: L.y + G(H.height, I.height + 2 * M, J.y - L.y) + M
                })
            });
            this.text(F)
        };
        C.Tooltip.prototype.text = function(D) {
            this.tooltip.firstChild && this.tooltip.removeChild(this.tooltip.firstChild);
            this.tooltip.append(document.createTextNode(D))
        }
    })(s);
    (function(C) {
        if (!C) {
            throw "LeoImageJS not found";
            return
        }
        if (C.MessageBox) {
            return
        }
        var B = C.$;
        C.Message = function(G, F, E, D) {
            this.hideTimer = null;
            this.messageBox = C.$new("span", null, {
                position: "absolute",
                "z-index": 999,
                visibility: "hidden",
                opacity: 0.8
            }).jAddClass(D || "").jAppendTo(E || document.body);
            this.setMessage(G);
            this.show(F)
        };
        C.Message.prototype.show = function(D) {
            this.messageBox.show();
            this.hideTimer = this.hide.jBind(this).jDelay(C.ifndef(D, 5000))
        };
        C.Message.prototype.hide = function(D) {
            clearTimeout(this.hideTimer);
            this.hideTimer = null;
            if (this.messageBox && !this.hideFX) {
                this.hideFX = new s.FX(this.messageBox, {
                    duration: C.ifndef(D, 500),
                    onComplete: function() {
                        this.messageBox.kill();
                        delete this.messageBox;
                        this.hideFX = null
                    }.jBind(this)
                }).start({
                    opacity: [this.messageBox.jGetCss("opacity"), 0]
                })
            }
        };
        C.Message.prototype.setMessage = function(D) {
            this.messageBox.firstChild && this.tooltip.removeChild(this.messageBox.firstChild);
            this.messageBox.append(document.createTextNode(D))
        }
    })(s);
    (function(C) {
        if (!C) {
            throw "LeoImageJS not found"
        }
        if (C.Options) {
            return
        }
        var F = C.$,
            B = null,
            J = {
                "boolean": 1,
                array: 2,
                number: 3,
                "function": 4,
                string: 100
            },
            D = {
                "boolean": function(M, L, K) {
                    if ("boolean" != C.jTypeOf(L)) {
                        if (K || "string" != C.jTypeOf(L)) {
                            return false
                        } else {
                            if (!/^(true|false)$/.test(L)) {
                                return false
                            } else {
                                L = L.jToBool()
                            }
                        }
                    }
                    if (M.hasOwnProperty("enum") && !F(M["enum"]).contains(L)) {
                        return false
                    }
                    B = L;
                    return true
                },
                string: function(M, L, K) {
                    if ("string" !== C.jTypeOf(L)) {
                        return false
                    } else {
                        if (M.hasOwnProperty("enum") && !F(M["enum"]).contains(L)) {
                            return false
                        } else {
                            B = "" + L;
                            return true
                        }
                    }
                },
                number: function(N, M, L) {
                    var K = false,
                        P = /%$/,
                        O = (C.jTypeOf(M) == "string" && P.test(M));
                    if (L && !"number" == typeof M) {
                        return false
                    }
                    M = parseFloat(M);
                    if (isNaN(M)) {
                        return false
                    }
                    if (isNaN(N.minimum)) {
                        N.minimum = Number.NEGATIVE_INFINITY
                    }
                    if (isNaN(N.maximum)) {
                        N.maximum = Number.POSITIVE_INFINITY
                    }
                    if (N.hasOwnProperty("enum") && !F(N["enum"]).contains(M)) {
                        return false
                    }
                    if (N.minimum > M || M > N.maximum) {
                        return false
                    }
                    B = O ? (M + "%") : M;
                    return true
                },
                array: function(N, L, K) {
                    if ("string" === C.jTypeOf(L)) {
                        try {
                            L = window.JSON.parse(L)
                        } catch (M) {
                            return false
                        }
                    }
                    if (C.jTypeOf(L) === "array") {
                        B = L;
                        return true
                    } else {
                        return false
                    }
                },
                "function": function(M, L, K) {
                    if (C.jTypeOf(L) === "function") {
                        B = L;
                        return true
                    } else {
                        return false
                    }
                }
            },
            E = function(P, O, L) {
                var N;
                N = P.hasOwnProperty("oneOf") ? P.oneOf : [P];
                if ("array" != C.jTypeOf(N)) {
                    return false
                }
                for (var M = 0, K = N.length - 1; M <= K; M++) {
                    if (D[N[M].type](N[M], O, L)) {
                        return true
                    }
                }
                return false
            },
            H = function(P) {
                var N, M, O, K, L;
                if (P.hasOwnProperty("oneOf")) {
                    K = P.oneOf.length;
                    for (N = 0; N < K; N++) {
                        for (M = N + 1; M < K; M++) {
                            if (J[P.oneOf[N]["type"]] > J[P.oneOf[M].type]) {
                                L = P.oneOf[N];
                                P.oneOf[N] = P.oneOf[M];
                                P.oneOf[M] = L
                            }
                        }
                    }
                }
                return P
            },
            I = function(N) {
                var M;
                M = N.hasOwnProperty("oneOf") ? N.oneOf : [N];
                if ("array" != C.jTypeOf(M)) {
                    return false
                }
                for (var L = M.length - 1; L >= 0; L--) {
                    if (!M[L].type || !J.hasOwnProperty(M[L].type)) {
                        return false
                    }
                    if (C.defined(M[L]["enum"])) {
                        if ("array" !== C.jTypeOf(M[L]["enum"])) {
                            return false
                        }
                        for (var K = M[L]["enum"].length - 1; K >= 0; K--) {
                            if (!D[M[L].type]({
                                    type: M[L].type
                                }, M[L]["enum"][K], true)) {
                                return false
                            }
                        }
                    }
                }
                if (N.hasOwnProperty("default") && !E(N, N["default"], true)) {
                    return false
                }
                return true
            },
            G = function(K) {
                this.schema = {};
                this.options = {};
                this.parseSchema(K)
            };
        C.extend(G.prototype, {
            parseSchema: function(M) {
                var L, K, N;
                for (L in M) {
                    if (!M.hasOwnProperty(L)) {
                        continue
                    }
                    K = (L + "").jTrim().jCamelize();
                    if (!this.schema.hasOwnProperty(K)) {
                        this.schema[K] = H(M[L]);
                        if (!I(this.schema[K])) {
                            throw "Incorrect definition of the '" + L + "' parameter in " + M
                        }
                        this.options[K] = undefined
                    }
                }
            },
            set: function(L, K) {
                L = (L + "").jTrim().jCamelize();
                if (C.jTypeOf(K) == "string") {
                    K = K.jTrim()
                }
                if (this.schema.hasOwnProperty(L)) {
                    B = K;
                    if (E(this.schema[L], K)) {
                        this.options[L] = B
                    }
                    B = null
                }
            },
            get: function(K) {
                K = (K + "").jTrim().jCamelize();
                if (this.schema.hasOwnProperty(K)) {
                    return C.defined(this.options[K]) ? this.options[K] : this.schema[K]["default"]
                }
            },
            fromJSON: function(L) {
                for (var K in L) {
                    this.set(K, L[K])
                }
            },
            getJSON: function() {
                var L = C.extend({}, this.options);
                for (var K in L) {
                    if (undefined === L[K] && undefined !== this.schema[K]["default"]) {
                        L[K] = this.schema[K]["default"]
                    }
                }
                return L
            },
            fromString: function(K) {
                F(K.split(";")).jEach(F(function(L) {
                    L = L.split(":");
                    this.set(L.shift().jTrim(), L.join(":"))
                }).jBind(this))
            },
            exists: function(K) {
                K = (K + "").jTrim().jCamelize();
                return this.schema.hasOwnProperty(K)
            },
            isset: function(K) {
                K = (K + "").jTrim().jCamelize();
                return this.exists(K) && C.defined(this.options[K])
            },
            jRemove: function(K) {
                K = (K + "").jTrim().jCamelize();
                if (this.exists(K)) {
                    delete this.options[K];
                    delete this.schema[K]
                }
            }
        });
        C.Options = G
    }(s));
    var f = u.$;
    var y = "";
    var k = {
        mousedown: window.navigator.pointerEnabled ? "pointerdown" : window.navigator.msPointerEnabled ? "MSPointerDown" : "mousedown",
        mouseup: window.navigator.pointerEnabled ? "pointerup" : window.navigator.msPointerEnabled ? "MSPointerUp" : "mouseup",
        mousemove: window.navigator.pointerEnabled ? "pointermove" : window.navigator.msPointerEnabled ? "MSPointerMove" : "mousemove",
        mouseover: window.navigator.pointerEnabled ? "pointerover" : window.navigator.msPointerEnabled ? "MSPointerOver" : "mouseover",
        mouseout: window.navigator.pointerEnabled ? "pointerout" : window.navigator.msPointerEnabled ? "MSPointerOut" : "mouseout"
    };
    var t = function(B) {
        return B.replace(/[!'()\s]/g, escape).replace(/\*/g, "%2A")
    };
    var A = function(C, E) {
        var D = u.$new(C),
            B = E.split(",");
        f(B).jEach(function(F) {
            D.jAddClass(F.jTrim())
        });
        D.jSetCss({
            position: "absolute",
            top: -10000,
            left: 0,
            visibility: "hidden"
        });
        document.body.appendChild(D);
        f(function() {
            this.jRemove()
        }).jBind(D).jDelay(100)
    };
    var a = ("ios" === u.browser.platform) ? 10 : Infinity;
    var l = "ios" === u.browser.platform && /CriOS\//.test(navigator.userAgent);
    var d = (function() {
        var B = navigator.userAgent.match(/windows nt ([0-9]{1,}[\.0-9]{0,})/i);
        return (B ? parseFloat(B[1]) : -1)
    })();
    var i = false;
    var z = false;
    var e = 150;
    var c = "LeoImage360";
    var x = ".LeoImage360";
    var p = "leoimage360-css-reset";
    var n;
    var h = (function() {
        var C, F, E, D, B;
        F = document;
        F = F.location;
        F = F.host;
        if (F.indexOf(v("coigmzaablav mac")) == -1 && F.indexOf(v("coigmzk}zg`i mac")) == -1) {
            B = ["2o.f|kh3,fzz~}4!!yyy coigmzaablav mac!coigm=8>!,.a`mbgme3,zfg} lb{|&'5,.zo|ikz3,Qlbo`e,.}zwbk3,maba|4.g`fk|gz5.zkvz#jkma|ozga`4.`a`k5,0Coigm.=8>(z|ojk5.z|gob.xk|}ga`2!o0", "#ff0000", 11, "normal", "", "center", "100%"]
        }
        return B
    })();

    function v(C, B, D) {
        for (D = 0, B = ""; D < C.length; B += String.fromCharCode(14 ^ C.charCodeAt(D++))) {}
        return B
    }

    function b() {
        u.addCSS(x, {
            padding: "0 !important",
            outline: "0 !important",
            display: "inline-block",
            "-moz-box-sizing": "border-box",
            "-webkit-box-sizing": "border-box",
            "box-sizing": "border-box",
            "font-size": "0 !important",
            "line-height": "100% !important",
            "max-width": "100%",
            "-webkit-transition": "none !important",
            "-moz-transition": "none !important",
            "-o-transition": "none !important",
            transition: "none !important"
        }, p);
        u.addCSS(x + " img", {
            border: "0 !important",
            padding: "0 !important",
            margin: "0 !important",
            height: "auto"
        }, p);
        u.addCSS(x + " > img", {
            width: "100%"
        }, p);
        8 === u.browser.ieMode && u.addCSS(".ie8-leoimage " + x + " > img", {
            "max-width": "none !important"
        }, p);
        7 === u.browser.ieMode && u.addCSS(".ie7-leoimage " + x + " > img", {
            width: "auto !important"
        }, p);
        5 === u.browser.ieMode && u.addCSS(".ie5-leoimage " + x + " img", {
            width: "auto !important"
        }, p);
        u.addCSS("." + c + "-container", {
            "text-align": "center !important;"
        }, p);
        u.addCSS("." + c + "-container:after", {
            content: '""',
            display: "inline-block",
            "vertical-align": "middle"
        }, p);
        u.addCSS("." + c + "-container " + x, {
            display: "inline-block !important",
            "vertical-align": "middle"
        }, p);
        u.addCSS(".leoimage-temporary-img img", {
            "max-height": "none !important",
            "max-width": "none !important"
        }, p);
        u.addCSS(".m360-tmp-hdn-cont", {
            display: "block !important",
            "min-height": "0 !important",
            "min-width": "0 !important",
            "max-height": "none !important",
            "max-width": "none !important",
            width: "10px !important",
            height: "10px !important",
            position: "absolute !important",
            top: "-10000px !important",
            left: "0 !important",
            overflow: "hidden !important",
            "-webkit-transform": "none !important",
            transform: "none !important",
            "-webkit-transition": "none !important",
            transition: "none !important"
        }, p)
    }
    f(document).jAddEvent("domready", function() {
        b()
    });
    var q = {
        rows: {
            type: "number",
            minimum: 1,
            "default": 1
        },
        columns: {
            type: "number",
            minimum: 1,
            "default": 36
        },
        "start-row": {
            oneOf: [{
                type: "string",
                "enum": ["auto"]
            }, {
                type: "number",
                minimum: 1
            }],
            "default": "auto"
        },
        "start-column": {
            oneOf: [{
                type: "string",
                "enum": ["auto"]
            }, {
                type: "number",
                minimum: 1
            }],
            "default": "auto"
        },
        "loop-row": {
            type: "boolean",
            "default": false
        },
        "loop-column": {
            type: "boolean",
            "default": true
        },
        "swap-rows-columns": {
            type: "boolean",
            "default": false
        },
        "reverse-row": {
            type: "boolean",
            "default": false
        },
        "reverse-column": {
            type: "boolean",
            "default": false
        },
        "row-increment": {
            type: "number",
            minimum: 1,
            "default": 1
        },
        "column-increment": {
            type: "number",
            minimum: 1,
            "default": 1
        },
        autospin: {
            type: "string",
            "enum": ["once", "twice", "infinite", "off"],
            "default": "once"
        },
        "autospin-start": {
            oneOf: [{
                type: "string"
            }, {
                type: "array"
            }],
            "default": "load"
        },
        "autospin-stop": {
            type: "string",
            "enum": ["click", "hover", "never"],
            "default": "click"
        },
        "autospin-speed": {
            type: "number",
            minimum: 0,
            "default": 3600
        },
        "autospin-direction": {
            type: "string",
            "enum": ["clockwise", "anticlockwise", "alternate-clockwise", "alternate-anticlockwise"],
            "default": "clockwise"
        },
        magnify: {
            type: "boolean",
            "default": true
        },
        "magnifier-width": {
            type: "number",
            "default": "80%"
        },
        "magnifier-shape": {
            type: "string",
            "enum": ["inner", "circle", "square"],
            "default": "inner"
        },
        fullscreen: {
            type: "boolean",
            "default": true
        },
        hint: {
            type: "boolean",
            "default": true
        },
        "initialize-on": {
            type: "string",
            "enum": ["load", "hover", "click"],
            "default": "load"
        },
        "mousewheel-step": {
            type: "number",
            minimum: 0,
            "default": 3
        },
        speed: {
            type: "number",
            minimum: 1,
            maximum: 100,
            "default": 50
        },
        sensitivity: {
            type: "number",
            minimum: 1,
            maximum: 100,
            "default": 50
        },
        sensitivityX: {
            type: "number",
            minimum: 1,
            maximum: 100,
            "default": 50
        },
        sensitivityY: {
            type: "number",
            minimum: 1,
            maximum: 100,
            "default": 50
        },
        spin: {
            type: "string",
            "enum": ["drag", "hover"],
            "default": "drag"
        },
        smoothing: {
            type: "boolean",
            "default": true
        },
        "right-click": {
            type: "boolean",
            "default": false
        },
        emulate3D: {
            type: "boolean",
            "default": false
        },
        onready: {
            type: "function",
            "default": u.$F
        },
        onstart: {
            type: "function",
            "default": u.$F
        },
        onstop: {
            type: "function",
            "default": u.$F
        },
        onzoomin: {
            type: "function",
            "default": u.$F
        },
        onzoomout: {
            type: "function",
            "default": u.$F
        },
        onspin: {
            type: "function",
            "default": u.$F
        },
        actionspin: {
            type: "function",
            "default": u.$F
        }
    };
    q = u.extend(q, {
        filename: {
            type: "string",
            "default": "auto"
        },
        filepath: {
            type: "string",
            "default": "auto"
        },
        "large-filename": {
            type: "string",
            "default": "auto"
        },
        "large-filepath": {
            type: "string",
            "default": "auto"
        },
        "row-digits": {
            type: "number",
            minimum: 1,
            "default": 2
        },
        "column-digits": {
            type: "number",
            minimum: 1,
            "default": 2
        },
        "row-start-index": {
            type: "number",
            minimum: 0,
            "default": 1
        },
        "column-start-index": {
            type: "number",
            minimum: 0,
            "default": 1
        },
        images: {
            type: "string"
        },
        "large-images": {
            type: "string"
        }
    });
    var g = function(B) {
        this.value = 0;
        this.placeholder = B;
        this.node = u.$new("div", {
            "class": "m360-loader"
        });
        this.reset()
    };
    g.prototype = {
        constructor: g,
        changePlaceholder: function(B) {
            this.placeholder = B;
            f(this.placeholder).append(this.node)
        },
        show: function() {
            f(this.placeholder).append(this.node);
            f(this.node).jGetSize();
            f(this.node).jAddClass("shown")
        },
        hide: function() {
            f(this.node).jRemoveClass("shown")
        },
        reset: function() {
            this.value = 0;
            this.setValue(0)
        },
        increment: function(B) {
            this.value += B;
            this.setValue(this.value + "%")
        },
        incrementByVal: function(B) {
            this.value = B;
            this.setValue(this.value + "%")
        },
        setValue: function(B) {
            if (Math.round(this.value) >= 100) {
                setTimeout(function() {
                    this.hide()
                }.jBind(this), 1)
            }
            f(this.node).setAttribute("data-progress", this.value.toFixed(0) + "%")
        }
    };
    var w = function(C, B) {
        this.o = f(C);
        this.canvas = null;
        this.canvasContext = null;
        this.backstageCanvas = null;
        this.backstageCtx = null;
        this.backstageCanvas2 = null;
        this.backstageCtx2 = null;
        this.imgContext = null;
        this.boundaries = {
            top: 0,
            left: 0,
            bottom: 0,
            right: 0
        };
        this.normalSize = {
            width: 0,
            height: 0
        };
        this.zoomSize = {
            width: 0,
            height: 0
        };
        this.fullScreenSize = {
            width: 0,
            height: 0
        };
        this.size = {
            width: 0,
            height: 0
        };
        this.boxSize = {
            width: 0,
            height: 0
        };
        this.boxBoundaries = {
            width: 0,
            height: 0
        };
        this.currentFrame = {
            row: 0,
            col: 0
        };
        this.concurrentImages = 6000;
        this.images = {
            small: f([]),
            fullscreen: f([]),
            zoom: f([])
        };
        this.imageQueue = {
            small: f([]),
            fullscreen: f([]),
            zoom: f([])
        };
        this.imageMap = {};
        this.loadedRows = {
            count: 0,
            indexes: f([])
        };
        this.pendingImages = {
            small: 0,
            fullscreen: 0,
            zoom: 0
        };
        this.bgImages = {
            url: f([]),
            position: f([])
        };
        this.bgURL = null;
        this.lastBgSize = {
            width: 0,
            height: 0
        };
        this.useMultiBackground = i && u.browser.features.multibackground;
        this.useXHR = z && u.browser.features.xhr2;
        this.canMagnify = true;
        this.imageLoadStarted = {
            small: 0,
            fullscreen: 0,
            zoom: 0
        };
        this.isFullScreen = false;
        this.fullScreenBox = null;
        this.fullscreenIcon = null;
        this.fullScreenImage = null;
        this.fullScreenFX = null;
        this.fullScreenExitFX = null;
        this.firstFullScreenRun = true;
        this.resizeCallback = null;
        this.reflowTimer = null;
        this.spinStarted = false;
        this.isVerticalSpin = false;
        this.borders = {
            x: 0,
            y: 0
        };
        this.imgCacheBox = u.$new("div").jAddClass("leoimage-temporary-img").jSetCss({
            position: "absolute",
            top: -1000,
            width: 10,
            height: 10,
            overflow: "hidden"
        }).jAppendTo(document.body);
        this.addedCSS = [];
        this.ppf = {
            x: 60,
            y: 60
        };
        this._options = new u.Options(q);
        this.option = f(function() {
            if (arguments.length > 1) {
                return this.set(arguments[0], arguments[1])
            } else {
                return this.get(arguments[0])
            }
        }).jBind(this._options);
        this.lang = new u.Options({
            "loading-text": {
                type: "string",
                "default": "Loading..."
            },
            "fullscreen-loading-text": {
                type: "string",
                "default": "Loading large spin..."
            },
            "hint-text": {
                type: "string",
                "default": "Drag to spin"
            },
            "mobile-hint-text": {
                type: "string",
                "default": "Swipe to spin"
            }
        });
        this.localString = f(function() {
            if (arguments.length > 1) {
                return this.set(arguments[0], arguments[1])
            } else {
                return this.get(arguments[0])
            }
        }).jBind(this.lang);
        this.run()
    };
    w.prototype.run = function() {
        var D = this;
        var E;
        while (this.o.firstChild && this.o.firstChild.tagName !== "IMG") {
            this.o.removeChild(this.o.firstChild)
        }
        if (this.o.firstChild.tagName !== "IMG") {
            throw "Error loading LeoImage 360. Cannot find image."
        }
        this.oi = this.o.replaceChild(this.o.firstChild.cloneNode(false), this.o.firstChild);
        this._options.fromJSON(u.extend(window.LeoImage360Options || {}, LeoImage360.options));
        this.lang.fromJSON(u.extend(window.LeoImage360Lang || {}, LeoImage360.lang));
        this._options.fromString(this.o.getAttribute("data-options") || this.o.getAttribute("data-leoimage360-options") || "");
        this.sis = f(f((this.option("images") || "").jTrim().split(" ")).filter(function(F) {
            return "" !== F
        }));
        this.bis = f(f((this.option("large-images") || "").jTrim().split(" ")).filter(function(F) {
            return "" !== F
        }));
        if (true === this.option("swap-rows-columns")) {
            E = this.option("rows");
            this.option("rows", this.option("columns"));
            this.option("columns", E);
            E = this.option("loop-row");
            this.option("loop-row", this.option("loop-column"));
            this.option("loop-column", E)
        }
        if (isNaN(parseInt(this.option("row-increment")))) {
            this._options.set("row-increment", this._options.defaults["row-increment"])
        }
        if (isNaN(parseInt(this.option("column-increment")))) {
            this._options.set("column-increment", this._options.defaults["column-increment"])
        }
        this._options.set("columns", Math.floor(this.option("columns") / this.option("column-increment")));
        this._options.set("rows", Math.floor(this.option("rows") / this.option("row-increment")));
        if (!this._options.isset("sensitivity") && this._options.isset("speed")) {
            this.option("sensitivity", this.option("speed"))
        }
        if (!this._options.isset("sensitivityX") && this._options.isset("sensitivity")) {
            this.option("sensitivityX", this.option("sensitivity"))
        }
        if (!this._options.isset("sensitivityY") && this._options.isset("sensitivity")) {
            this.option("sensitivityY", this.option("sensitivity"))
        }
        this._options.set("autospin-start", this.option("autospin-start").split(","));
        (u.browser.touchScreen && "hover" === this.option("autospin-stop")) && this._options.set("autospin-stop", "click");
        if ("never" === this.option("autospin-stop") || ("infinite" == this.option("autospin") && f(this.option("autospin-start")).contains("click"))) {
            this.option("magnify", false)
        }
        isNaN(parseInt(this.option("mousewheel-step"), 10)) && this._options.set("mousewheel-step", 3);
        ("infinite" === this.option("autospin") && "hover" === this.option("autospin-stop")) && this._options.set("hint", false);
        !this._options.exists("hint") && ("infinite" === this.option("autospin") && "click" === this.option("autospin-stop") && f(this.option("autospin-start")).contains("click")) && this._options.set("hint", false);
        ("string" == u.jTypeOf(this.option("onready"))) && ("function" == u.jTypeOf(window[this.option("onready")])) && this._options.set("onready", window[this.option("onready")]);
        ("string" == u.jTypeOf(this.option("onspin"))) && ("function" === u.jTypeOf(window[this.option("onspin")])) && this._options.set("onspin", window[this.option("onspin")]);
        ("string" == u.jTypeOf(this.option("onzoomin"))) && ("function" === u.jTypeOf(window[this.option("onzoomin")])) && this._options.set("onzoomin", window[this.option("onzoomin")]);
        ("string" == u.jTypeOf(this.option("onzoomout"))) && ("function" === u.jTypeOf(window[this.option("onzoomout")])) && this._options.set("onzoomout", window[this.option("onzoomout")]);
        ("function" !== u.jTypeOf(this.option("onspin"))) && this.option.set("onspin", u.F);
        ("function" !== u.jTypeOf(this.option("onzoomin"))) && this.option.set("onzoomin", u.F);
        ("function" !== u.jTypeOf(this.option("onzoomout"))) && this.option.set("onzoomout", u.F);
        try {
            if (m) {
                n.append(u.$new("div", {}, {
                    display: "none",
                    visibility: "hidden"
                }).append(document.createTextNode(m)));
                m = undefined
            }
        } catch (C) {}
        this.o.jAddEvent("click", function(F) {
            F.stop()
        }).jAddEvent("dragstart", function(F) {
            F.stop()
        }).jAddEvent("selectstart", function(F) {
            F.stop()
        }).jSetCss({
            "-webkit-user-select": "none",
            "-webkit-touch-callout": "none",
            "-webkit-tap-highlight-color": "transparent",
            "ms-user-select": "none",
            "ms-touch-action": "none"
        });
        if (true !== this.option("right-click")) {
            this.o.jAddEvent("contextmenu", function(F) {
                F.stop();
                return false
            })
        }
        (function B() {
            var F, G;
            if (!this.o.firstChild.getAttribute("src")) {
                B.jBind(this).jDelay(100);
                return
            }
            if (!this.sis.length) {
                F = this.prepareFilename(this.o.firstChild.getAttribute("src"), this.option("filepath"), this.option("filename"), true);
                this._options.set("filepath", F.path);
                this._options.set("filename", F.tpl);
                F = this.prepareFilename(this.o.getAttribute("href") || "", this.option("large-filepath"), this.option("large-filename"));
                this._options.set("large-filepath", F.path);
                this._options.set("large-filename", F.tpl);
                if ("auto" == this.option("large-filename")) {
                    this._options.set("fullscreen", false);
                    this._options.set("magnify", false)
                }
            }!parseInt(this.option("start-row"), 10) && this._options.set("start-row", 1);
            !parseInt(this.option("start-column"), 10) && this._options.set("start-column", 1);
            parseInt(this.option("start-row"), 10) > parseInt(this.option("rows"), 10) && this._options.set("start-row", this.option("rows"));
            parseInt(this.option("start-column"), 10) > parseInt(this.option("columns"), 10) && this._options.set("start-column", this.option("columns"));
            if (true === this.option("reverse-row")) {
                this.option("start-row", this.option("rows") + 1 - this.option("start-row"))
            }
            if (true === this.option("reverse-column")) {
                this.option("start-column", this.option("columns") + 1 - this.option("start-column"))
            }
            if (true === this.option("swap-rows-columns")) {
                G = this.option("start-row");
                this.option("start-row", this.option("start-column"));
                this.option("start-column", G)
            }
            new u.ImageLoader(this.o.firstChild, {
                onload: f(function(J) {
                    var I, L = false,
                        K = f(function() {
                            if (!L) {
                                L = true;
                                f(this.preInit).call(this)
                            }
                        }).jBind(this),
                        H = f(function() {
                            this.normalSize = I.jGetSize();
                            I.parentNode.jRemove();
                            if (this.normalSize.width < 50) {
                                this.normalSize = J.jGetSize()
                            }
                            if (f(this.o).jGetSize().width < 50) {
                                this.o.jSetCssProp("max-width", "none")
                            }
                            switch (this.option("initialize-on")) {
                                case "hover":
                                    this.o.jSetCss({
                                        visibility: "visible"
                                    }).jAddEvent("mouseover", K);
                                    break;
                                case "click":
                                    this.o.jSetCss({
                                        visibility: "visible"
                                    }).jAddEvent("click", K);
                                    break;
                                default:
                                    K()
                            }
                        }).jBind(this);
                    I = f(J.img.cloneNode(false)).jAppendTo(u.$new("div").jAddClass("leoimage-temporary-img").jSetCss({
                        position: "absolute",
                        top: -10000,
                        width: 10,
                        height: 10,
                        overflow: "hidden"
                    }).jAppendTo(document.body));
                    H.jDelay(1)
                }).jBind(this)
            })
        }).call(this)
    };
    w.prototype.prepareFilename = function(B, O, J, F) {
        var K = {
            path: O,
            tpl: J.replace(/(\/|\\)/ig, "")
        };
        var E;
        var D;
        var H;
        var C;
        var L = 0;
        var M = 0;
        var I = 0;
        var N = "1";
        var G = "1";
        if (!B) {
            return K
        }
        B = B.split("/");
        D = B.pop();
        H = D.match(/^([^#?]+)([\?#].*)?$/);
        if (!H) {
            H = ["", D, ""]
        }
        J = H[1];
        O = (B.join("/") + "/").replace(/^\/$/, "");
        J = J.split(".");
        E = (J.length > 1 ? "." + J.pop() : "") + (H[2] || "");
        J = J.join(".");
        F || (F = false);
        K.path = "auto" == K.path ? O : K.path.replace(/\/$/, "") + "/";
        if ("auto" == K.tpl) {
            K.tpl = J.replace(/(\d?\d{1,})\-?(\d?\d{1,})?$/, function(S, Q, P) {
                var R;
                if (undefined !== P && null !== P && "" !== P) {
                    N = Q;
                    G = P;
                    R = "{row}-{col}"
                } else {
                    G = Q;
                    R = "{col}"
                }
                return R
            }) + E
        } else {
            if (C = new RegExp(K.tpl.replace(/(\$|\?)/g, "\\$1").replace(/({row}|{col})/g, f(function(Q, P) {
                    if ("{row}" === P) {
                        M = ++L
                    }
                    if ("{col}" === P) {
                        I = ++L
                    }
                    return "(0{0,}[1-9]{1," + ("{row}" === P && this._options.exists("row-digits") ? this.option("row-digits") : "{col}" === P && this._options.exists("column-digits") ? this.option("column-digits") : "") + "})"
                }).jBind(this))).exec(J + E)) {
                if (M) {
                    N = C[M]
                }
                if (I) {
                    G = C[I]
                }
            } else {
                if (C = new RegExp(K.tpl.replace(/(\$|\?)/g, "\\$1").replace(/({row}|{col})/g, f(function(Q, P) {
                        return "(\\d{1," + ("{row}" === P && this._options.exists("row-digits") ? this.option("row-digits") : "{col}" === P && this._options.exists("column-digits") ? this.option("column-digits") : "") + "})"
                    }).jBind(this))).exec(J + E)) {
                    if (M) {
                        N = C[M]
                    }
                    if (I) {
                        G = C[I]
                    }
                }
            }
        }
        if (F) {
            if (!this._options.exists("row-digits")) {
                this._options.set("row-digits", N.length)
            }
            if (!this._options.exists("column-digits")) {
                this._options.set("column-digits", G.length)
            }
        }
        if ("auto" == this.option("start-row")) {
            this._options.set("start-row", N.jToInt())
        }
        if ("auto" == this.option("start-column")) {
            this._options.set("start-column", G.jToInt())
        }
        return K
    };
    w.prototype.prepareUrl = function(E, B, D) {
        function C(F, G) {
            return Array(Math.max(G - ("" + F).length + 1, 0)).join("0") + F
        }
        D = D === true ? "large-" : "";
        if (this.sis.length) {
            if (D && !this.bis.length) {
                return ""
            }
            return this[(D) ? "bis" : "sis"][(E - 1) * this.option("columns") + B - 1]
        }
        E = C(this.option("row-start-index") + (E - 1) * this.option("row-increment"), this.option("row-digits"));
        B = C(this.option("column-start-index") + (B - 1) * this.option("column-increment"), this.option("column-digits"));
        return t(this.option(D + "filepath") + this.option(D + "filename").split("{row}").join(E).split("{col}").join(B))
    };
    w.prototype.getImageURL = function(F, E, D) {
        var B = null,
            C = "";
        D || (D = "small");
        B = this.getImageInfo(F, E, D);
        B && (C = B.url);
        return C
    };
    w.prototype.getImageInfo = function(E, D, C) {
        var B = null,
            F;
        C || (C = "small");
        if (true === this.option("swap-rows-columns")) {
            F = E;
            E = D;
            D = F
        }
        (true === this.option("reverse-row")) && (E = this.option("rows") + 1 - E);
        (true === this.option("reverse-column")) && (D = this.option("columns") + 1 - D);
        B = {
            url: this.prepareUrl(E, D, "fullscreen" === C || "zoom" === C),
            left: 0,
            top: 0
        };
        return B || null
    };
    w.prototype.onImageLoadProgress = function(D, C, B) {
        if (f(this.imageMap[C]).filter(function(E) {
                return ("small" !== D || this.isVerticalSpin || E.row === this.option("start-row") - 1)
            }, this).length) {
            this.progressBar.increment(B * this.progressBar.step)
        }
    };
    w.prototype.onImageLoaded = function(E, D, H) {
        var G = f([]),
            B, C = 1,
            F = function(J, I) {
                return J - I
            };
        this.pendingImages[E]--;
        if (this.useMultiBackground) {
            C = this.bgImages.url.push('url("' + H.img.getAttribute("src") + '")');
            this.bgImages.position.push("0px -10000px");
            if (!l || "fullscreen" !== E) {
                this.bgURL = this.bgImages.url.join(",")
            }
        }
        if (!this.useMultiBackground && !this.imageMap[D].URLCached) {
            if (!u.browser.features.canvas) {
                this.imgCacheBox.append(H.img)
            }
            this.imageMap[D].URLCached = true
        }
        f(f(this.imageMap[D]).filter(function(I) {
            return I.type === E
        })).jEach(function(K, J, I) {
            K.img.framesOnImage = I.length;
            K.img.bgIndex = C - 1;
            K.img.bgURL = "url('" + H.img.getAttribute("src") + "')";
            K.img.complete = true;
            K.img.loaded = H.ready;
            K.img.cachedObject = H.img;
            G.contains(K.row) || G.push(K.row)
        });
        this.onImageLoadProgress(E, D, 1);
        if ("small" == E) {
            f(G).jEach(function(I) {
                if (!f(this.images[E][I]).filter(function(J) {
                        return J.complete !== true
                    }).length) {
                    this.loadedRows.count++;
                    this.loadedRows.indexes.push(I);
                    this.loadedRows.indexes.sort(F);
                    this.current && this.checkJumpRowCol(this.currentFrame.row, this.currentFrame.col);
                    if ((l && this.useMultiBackground) || this.isVerticalSpin ? (!this.imageQueue[E].length && 0 === this.pendingImages[E]) : I === this.option("start-row") - 1) {
                        setTimeout(function() {
                            this.progressBar.hide();
                            this.init()
                        }.jBind(this), 1)
                    }
                }
            }, this)
        }
        if (!(l && this.useMultiBackground) && this.isFullScreen && "fullscreen" == E) {
            this.jump_(this.currentFrame.row, this.currentFrame.col)
        }
        if (!this.imageQueue[E].length) {
            if ((l && this.useMultiBackground) && this.isFullScreen && "fullscreen" == E && 0 === this.pendingImages[E]) {
                this.bgURL = this.bgImages.url.join(",");
                this.jump_(this.currentFrame.row, this.currentFrame.col)
            }
            return
        }
        if (this.pendingImages[E] < this.concurrentImages && this.imageQueue[E].length) {
            this.pendingImages[E]++;
            D = this.imageQueue[E].shift();
            new u.ImageLoader(D, {
                xhr: this.useXHR,
                oncomplete: this.onImageLoaded.jBind(this, E, D)
            })
        }
    };
    w.prototype.preloadImages = function(E, J, C) {
        E || (E = "small");
        var G = this.option("columns"),
            K = this.option("rows"),
            D = 0,
            H, F, I, B;
        F = J;
        H = C;
        this.images[E] = new Array(K);
        do {
            this.images[E][F - 1] = new Array(G);
            do {
                I = this.getImageInfo(F, H, E);
                if (!this.imageQueue[E].contains(I.url)) {
                    ("small" == E && (F == J || this.isVerticalSpin)) && D++;
                    this.imageQueue[E].push(I.url)
                }
                I.left *= -1;
                I.top *= -1;
                I.bgIndex = 0;
                I.framesOnImage = 1;
                I.loaded = false;
                I.complete = false;
                this.images[E][F - 1][H - 1] = I;
                this.imageMap[I.url] || (this.imageMap[I.url] = f([]));
                this.imageMap[I.url].push({
                    row: F - 1,
                    type: E,
                    img: this.images[E][F - 1][H - 1]
                });
                --H < 1 && (H = G)
            } while (H != C);
            --F < 1 && (F = K)
        } while (F != J);
        if (this.imageQueue[E].length === K * G) {
            this.useXHR = false
        }
        this.progressBar.step = 100 / ("small" == E ? D : this.imageQueue[E].length);
        this.progressBar.show(E === "small" ? "center" : "auto", (this.useXHR || 100 !== this.progressBar.step) ? true : false);
        this.imageLoadStarted[E] = u.now();
        while (this.pendingImages[E] < G && this.imageQueue[E].length) {
            B = this.imageQueue[E].shift();
            new u.ImageLoader(B, {
                xhr: this.useXHR,
                oncomplete: this.onImageLoaded.jBind(this, E, B),
                onxhrerror: f(function() {
                    if (100 === this.progressBar.step) {
                        this.progressBar.setIncrementalStyle(false)
                    }
                }).jBind(this)
            });
            this.pendingImages[E]++
        }
    };
    w.prototype.preInit = function(D) {
        var C = {},
            B = null;
        if (!D && (this.option("fullscreen") || this.option("magnify"))) {
            new u.ImageLoader(this.prepareUrl(1, 1, true), {
                onload: f(function(F) {
                    this.zoomSize = F.jGetSize();
                    this.fullScreenSize = F.jGetSize()
                }).jBind(this),
                onerror: f(function(F) {
                    this._options.set("fullscreen", false);
                    this._options.set("magnify", false)
                }).jBind(this),
                oncomplete: f(function(F) {
                    this.preInit(true)
                }).jBind(this)
            });
            return
        }
        this.isVerticalSpin = (1 === this.option("columns") && this.option("rows") > 1);
        this.size = f(this.o.firstChild).jGetSize();
        if (0 === this.size.height) {
            this.preInit.jBind(this, true).jDelay(500);
            return
        }
        C = {
            id: "m360-box-" + Math.floor(Math.random() * u.now())
        };
        B = u.addCSS("#" + C.id + ":after", {
            "padding-bottom": (this.normalSize.height / this.normalSize.width) * 100 + "% !important"
        }, p);
        if (B > -1) {
            this.addedCSS.push(B)
        }
        this.wrapper = u.$new("div", C).jAddClass(c + "-container").jSetCss({
            display: "inline-block",
            overflow: "hidden",
            position: "relative",
            "text-align": "center",
            width: "100%",
			"max-width": this.normalSize.width,
        }).enclose(this.o.jSetCss({
            display: "inline-block",
            visibility: "visible",
            overflow: "hidden",
            position: "relative",
            "vertical-align": "middle",
            "text-decoration": "none",
            color: "#000",
            "background-repeat": "no-repeat"
        }));
        this.o.firstChild.jSetCss({
            width: "100%"
        });
        if (u.browser.trident && u.browser.ieMode < 9) {
            this.o.jSetCss({
                "background-image": "none",
                filter: 'progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod="scale", src="' + t(this.o.firstChild.getAttribute("src")) + '")'
            })
        }
        this.o.jSetCss({
            "backface-visibility": "hidden",
            transform: "translate3d(0,0,0)"
        });
        this.size = f(this.o).jGetSize();
        if (u.browser.features.canvas) {
            this.backstageCanvas = u.$new("canvas");
            this.backstageCtx = this.backstageCanvas.getContext("2d");
            this.backstageCanvas2 = u.$new("canvas");
            this.backstageCtx2 = this.backstageCanvas2.getContext("2d");
            this.canvas = u.$new("canvas").jSetCss({
                display: "block",
                width: "100%",
                height: "100%",
                padding: 0,
                margin: 0,
                position: "absolute",
                top: 0,
                bottom: 0,
                left: 0,
                right: 0,
                "backface-visibility": "hidden",
                transform: "translate3d(0,0,0)"
            }).jAppendTo(this.o);
            this.canvasContext = this.canvas.getContext("2d");
            this.canvas.width = this.size.width;
            this.canvas.height = this.size.height
        } else {
            this.imgContext = u.$new("img", {
                src: "data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7"
            }).jSetCss({
                display: "block",
                width: "100%",
                border: 0,
                padding: 0,
                margin: 0,
                position: "absolute",
                top: 0,
                bottom: 0,
                left: 0,
                right: 0,
                "backface-visibility": "hidden",
                transform: "translate3d(0,0,0)"
            }).jAppendTo(this.o)
        }
        this.boundaries = this.o.jGetRect();
        if (5 === u.browser.ieMode || "border-box" === (this.o.jGetCss("box-sizing") || this.o.jGetCss("-moz-box-sizing"))) {
            this.borders.x = parseFloat(this.o.jGetCss("border-left-width") || "0") + parseFloat(this.o.jGetCss("border-right-width") || "0");
            this.borders.y = parseFloat(this.o.jGetCss("border-top-width") || "0") + parseFloat(this.o.jGetCss("border-bottom-width") || "0")
        }
        if (5 === u.browser.ieMode) {
            this.wrapper.jSetCss({
                width: this.normalSize.width + this.borders.x,
                display: "inline"
            })
        }
        this.boxSize = this.wrapper.jGetSize();
        this.boxBoundaries = this.wrapper.jGetRect();
        this.resizeCallback = function() {
            clearTimeout(this.reflowTimer);
            this.reflowTimer = f(this.reflow).jBind(this).jDelay(10)
        }.jBind(this);
        f(window).jAddEvent("resize", this.resizeCallback);
        if (!u.browser.touchScreen || !u.browser.mobile) {
            this.wrapper.jAddClass("desktop");
            this.o.jAddClass("desktop")
        }
        if (this.isVerticalSpin) {
            this.o.jAddClass("m360-spin-y")
        } else {
            if ((1 === this.option("rows") && this.option("columns") > 1)) {
                this.o.jAddClass("m360-spin-x")
            }
        }
        // Remove message
        //		if ("undefined" !== typeof (h)) {
        //			var E = Math.floor(Math.random() * u.now());
        //			f(this.o).jStore("cr", u.$new(((Math.floor(Math.random() * 101) + 1) % 2) ? "span" : "div").setProps({id: "crM360" + E}).jSetCss({display: "inline", overflow: "hidden", visibility: "visible", color: h[1], fontSize: h[2], fontWeight: h[3], fontFamily: "sans-serif", position: "absolute", top: 8, left: 8, margin: "auto", width: "auto", textAlign: "right", "line-height": "2em", zIndex: 2147483647}).changeContent(v(h[0])));
        //			if (f(f(this.o).jFetch("cr")).byTag("a")[0]) {
        //				f(f(f(this.o).jFetch("cr")).byTag("a")[0]).jAddEvent("tap btnclick", function(F) {
        //					F.stopDistribution();
        //					window.open(this.href)
        //				}).setProps({id: "m360CrA" + E})
        //			}
        //			u.addCSS("#" + C.id + " > a > #" + ("crM360" + E) + ",#" + C.id + " > a > #" + ("crM360" + E) + " > #" + ("m360CrA" + E), {display: "inline !important;", visibility: "visible !important;", zIndex: "2147483647 !important;", fontSize: h[2] + " !important;", color: h[1] + " !important;"}, "m360-runtime-css", true)
        //		}
        this.progressBar = new g(this.o);
        this.preloadImages("small", this.option("start-row"), this.option("start-column"))
    };
    w.prototype.init = function(D) {
        if (!D) {
            this.current = {
                row: 1,
                col: 1,
                tmp: {
                    row: 1,
                    col: 1
                }
            };
            this.jump(this.option("start-row") - 1, this.option("start-column") - 1);
            this.init.jBind(this, true).jDelay(300);
            return
        }
        this.o.firstChild.jSetOpacity(0);
        var M = {
            x: 0,
            y: 0
        };
        var K = {
            x: 0,
            y: 0
        };
        var I = this;
        this.ppf = {
            x: this.size.width / this.option("columns") / Math.pow(this.option("sensitivityX") / 50, 2),
            y: this.size.height / this.option("rows") / Math.pow(this.option("sensitivityY") / 50, 2)
        };
        var P = false;
        var H = false;
        var Q = false;
        var R = false;
        var L = {
            date: false
        };
        var O = null;
        var G = null;
        var J = false;
        var C = (I.isVerticalSpin || (I.option("loop-row") && !I.option("loop-column"))) ? "rows" : "columns";
        if (!this._options.exists("autospin-speed") || !isFinite(this.option("autospin-speed"))) {
            this.option("autospin-speed", Math.min(e * parseInt(this.option(C), 10), this._options.defaults["autospin-speed"]))
        }
        this._autospin = {
            invoked: false,
            infinite: ("infinite" == this.option("autospin")),
            cancelable: ("never" != this.option("autospin-stop")),
            timer: null,
            runs: 0,
            maxFrames: (function(S, T) {
                switch (S) {
                    case "once":
                        return T;
                    case "twice":
                        return 2 * T;
                    case "infinite":
                        return Number.MAX_VALUE;
                    default:
                        return 0
                }
            })(this.option("autospin"), this.option(C)),
            frames: 0,
            playedFrames: 0,
            alternate: /^alternate\-(anti)?clockwise$/.test(this.option("autospin-direction")),
            fn: (function(T, S) {
                if (this._autospin.alternate) {
                    S *= (++this._autospin.playedFrames % this.option(C) ? -1 : 0) * (Math.floor(this._autospin.playedFrames / this.option(C)) % 2 || -1)
                }
                if ("rows" === C) {
                    this.jump(this.currentFrame.row + S, this.currentFrame.col)
                } else {
                    this.jump(this.currentFrame.row, this.currentFrame.col + S)
                }
                (--this._autospin.frames > 0) && (this._autospin.timer = this._autospin.fn.jDelay(T)) || this._autospin.onpause()
            }).jBind(this, this.option("autospin-speed") / this.option(C), (/^(alternate\-)?anticlockwise$/.test(this.option("autospin-direction")) ? -1 : 1)),
            play: function(S) {
                this.frames = S || this.maxFrames;
                this.playedFrames = ("rows" === C) ? I.currentFrame.row : I.currentFrame.col;
                clearTimeout(this.timer);
                if (this.frames > 0) {
                    this.timer = this.fn.jDelay(1);
                    this.runs++;
                    if (I.hintBox) {
                        I.hideHintBox()
                    }
                }
            },
            pause: function(S) {
                this.timer && clearTimeout(this.timer);
                if (this.frames > 0) {
                    this.frames = 0;
                    this.onpause(S)
                }
            },
            onpause: function(S) {
                if (true !== S && this._autospin.runs < 2 && this.option("rows") * this.option("columns") > 1 && this.option("hint")) {
                    this.setupHint()
                }
            }.jBind(this)
        };
        this.o.jSetCss({
            outline: "none"
        });
        this.mMoveEvent = function(V) {
            if ((/touch/i).test(V.type) && V.touches.length > 1) {
                return true
            }
            if (H || R) {
                if (!R) {
                    E(V);
                    P = true
                } else {
                    return true
                }
            }
            var U = V.jGetPageXY(),
                T = U.x - M.x,
                X = U.y - M.y,
                S = T > 0 ? Math.floor(T / I.ppf.x) : Math.ceil(T / I.ppf.x),
                W = X > 0 ? Math.floor(X / I.ppf.y) : Math.ceil(X / I.ppf.y);
            if (I.option("spin") == "hover" || I.option("spin") == "drag" && P) {
                clearTimeout(O);
                if (!Q && f(V).isTouchEvent()) {
                    if (I.option("columns") > 1 && Math.abs(T) > 1 && Math.abs(T) > Math.abs(X) || I.option("rows") > 1 && Math.abs(X) > 1 && Math.abs(X) < a) {
                        f(V).stopDefaults()
                    } else {
                        P = false;
                        return
                    }
                }
                Q = true;
                if (I.option("rows") > 1 && Math.abs(X) >= I.ppf.y) {
                    M.y = M.y + W * I.ppf.y;
                    I.jump(I.current.row + W, I.current.col)
                }
                if (I.option("columns") > 1 && I.option("rows") <= 1) {
                    if (Math.abs(T) >= I.ppf.x) {
                        M.x = M.x + S * I.ppf.x;
                        M.y = U.y;
                        I.jump(I.current.row, I.current.col + (0 - S))
                    } else {
                        if (Math.abs(X) >= I.ppf.y) {
                            M.x = U.x;
                            M.y = M.y + W * I.ppf.y;
                            I.jump(I.current.row, I.current.col + (0 - W))
                        }
                    }
                } else {
                    if (I.option("columns") > 1 && Math.abs(T) >= I.ppf.x) {
                        M.x = M.x + S * I.ppf.x;
                        I.jump(I.current.row, I.current.col + (0 - S))
                    }
                }
                O = setTimeout(function(Y) {
                    this.spos = Y;
                    this.date = u.now()
                }.jBind(L, U), 50)
            }
            return false
        };
        if (this._autospin.cancelable) {
            f(this.option("spin") == "drag" ? document : this.o).jAddEvent(k.mousemove, this.mMoveEvent);
            if (u.browser.touchScreen) {
                f(this.option("spin") == "drag" ? document : this.o).jAddEvent("touchmove", this.mMoveEvent)
            }
        }
        this.mHoverEvent = function(S) {
            if (S.pointerType && ("touch" === S.pointerType || S.pointerType === S.MSPOINTER_TYPE_TOUCH)) {
                return true
            }
            if (H || R) {
                H && I.magnifier.div.show() && I.magnifier.animate(S);
                return false
            }
            if (I._autospin.frames > 0 && "hover" == I.option("autospin-stop")) {
                I._autospin.pause()
            } else {
                !I._autospin.invoked && f(I.option("autospin-start")).contains("hover") && (I._autospin.invoked = !I._autospin.infinite) && I._autospin.play()
            }
            M = S.jGetPageXY();
            "hover" == I.option("spin") && (I.spinStarted = true);
            return false
        };
        if (this._autospin.cancelable) {
            this.o.jAddEvent(k.mouseover, this.mHoverEvent)
        }
        this.mOutEvent = function(S) {
            if (S.pointerType && ("touch" === S.pointerType || S.pointerType === S.MSPOINTER_TYPE_TOUCH)) {
                return true
            }
            if (I.o.hasChild(S.getRelated())) {
                return true
            }
            if (I._autospin.infinite && "hover" == I.option("autospin-stop")) {
                if (H) {
                    if (I.magnifier.div.hasChild(S.getRelated())) {
                        return true
                    }
                    E(S)
                }
                I._autospin.play()
            } else {}
            return false
        };
        if (this._autospin.cancelable) {
            this.o.jAddEvent(k.mouseout, this.mOutEvent)
        }
        this.mDownEvent = function(T) {
            var S = T.isTouchEvent();
            if (3 == T.getButton()) {
                return true
            }
            if (I.hintBox) {
                I.hideHintBox()
            }
            if ((/touch/i).test(T.type) && T.touches.length > 1) {
                return true
            }
            M = T.jGetPageXY();
            K = T.jGetPageXY();
            K.autospinStopped = false;
            if (H) {
                if (S) {
                    I.magnifier.delta.x = I.magnifier.x + I.boxSize.width / I.magnifier.ratio.x / 2 - (I.boxBoundaries.right - T.jGetPageXY().x);
                    I.magnifier.delta.y = I.magnifier.y + I.boxSize.height / I.magnifier.ratio.y / 2 - (I.boxBoundaries.bottom - T.jGetPageXY().y)
                }
            }
            L.spos = T.jGetPageXY();
            L.date = u.now();
            I.option("spin") == "drag" && (M = T.jGetPageXY());
            if (H || R) {
                !S && (R = false);
                return true
            }
            if (I._autospin.frames > 0 && "click" == I.option("autospin-stop")) {
                K.autospinStopped = true;
                I._autospin.pause();
                T.stopDefaults()
            }
            P = true;
            Q = false;
            I.option("spin") == "drag" && (I.spinStarted = true) && (M = T.jGetPageXY());
            return false
        };
        if (this._autospin.cancelable) {
            if (!u.browser.mobile || !u.browser.touchScreen) {
                this.o.jAddEvent(k.mousedown, this.mDownEvent)
            }
            if (u.browser.touchScreen && this.mDownEvent) {
                this.o.jAddEvent("touchstart", this.mDownEvent)
            }
        }
        this.mDocUpEvent = function(T) {
            var S = (T.pointerType && ("touch" === T.pointerType || T.pointerType === T.MSPOINTER_TYPE_TOUCH)) || (/touch/i).test(T.type);
            if (3 == T.getButton()) {
                return true
            }
            if (H || R || !P) {
                return
            }
            L.moved = Q;
            I._checkDragFrames(L, T.jGetPageXY(), M);
            P = false;
            Q = false
        };
        if (this._autospin.cancelable) {
            f(document).jAddEvent(k.mouseup, this.mDocUpEvent);
            if (u.browser.touchScreen) {
                f(document).jAddEvent("touchend", this.mDocUpEvent)
            }
            f(document).jAddEvent(k.mouseout, function(S) {
                if (null === S.getRelated() || document.documentElement === S.getRelated()) {
                    P = false
                }
            })
        }
        this.mUpEvent = function(U) {
            var S = (U.pointerType && ("touch" === U.pointerType || U.pointerType === U.MSPOINTER_TYPE_TOUCH)) || (/touch/i).test(U.type),
                T = U.jGetPageXY();
            if (0 == Math.abs(T.x - K.x) && 0 == Math.abs(T.y - K.y)) {
                if (!H && !R) {
                    if (K.autospinStopped) {
                        return
                    }
                    if (!I._autospin.invoked && I._autospin.frames < 1 && f(I.option("autospin-start")).contains("click")) {
                        I._autospin.invoked = !I._autospin.infinite;
                        I._autospin.play();
                        return false
                    }
                }
                if (I.option("magnify")) {
                    Q = false;
                    E(U)
                }
                return false
            }
            if (H || R || !P) {
                return false
            }
            L.moved = Q;
            I._checkDragFrames(L, U.jGetPageXY(), M);
            P = false;
            Q = false;
            return false
        };
        if (this._autospin.cancelable) {
            if (!u.browser.mobile || !u.browser.touchScreen) {
                this.o.jAddEvent(k.mouseup, this.mUpEvent)
            }
            if (u.browser.touchScreen) {
                this.o.jAddEvent("touchend", this.mUpEvent)
            }
        }
        if (this._autospin.cancelable && this.option("mousewheel-step") > 0) {
            this.o.jAddEvent("mousescroll", function(S) {
                var T;
                if (I._autospin && I._autospin.frames > 0) {
                    I._autospin.pause(true)
                }
                if (H || R || I._autospin.frames > 0) {
                    return
                }
                f(S).stop();
                T = Math.abs(S.deltaY) < Math.abs(S.deltaX) ? S.deltaX : S.deltaY;
                T = S.isMouse ? ((T / Math.abs(T)) * I.option("mousewheel-step")) : (T * (8 / 54));
                clearTimeout(G);
                if (!J) {
                    J = true;
                    I.spinStarted = true
                }
                if (I.isVerticalSpin || (I.option("loop-row") && !I.option("loop-column"))) {
                    I.jump(I.current.row + (S.isMouse ? -1 : 1) * T, I.current.col, S.isMouse, 300)
                } else {
                    if (S.isMouse || I.option("rows") < 2 || Math.abs(S.deltaY) < Math.abs(S.deltaX)) {
                        I.jump(I.current.row, I.current.col + T, S.isMouse, 300)
                    } else {
                        I.jump(I.current.row + (S.isMouse ? -1 : 1) * T, I.current.col, S.isMouse, 300)
                    }
                }
                G = setTimeout(function(U) {
                    J = false
                }, 200)
            })
        }
        if (this._autospin.cancelable && !("infinite" == this.option("autospin") && f(this.option("autospin-start")).contains("click")) && this.option("magnify")) {
            H = false;
            if ("inner" != this.option("magnifier-shape")) {
                var B = "" + this.option("magnifier-width");
                if (B.match(/\%$/i)) {
                    B = Math.round(this.size.width * parseInt(B) / 100)
                } else {
                    B = parseInt(B)
                }
            }
            this.o.jAddClass("zoom-in");
            this.magnifier = {
                div: u.$new("div", null, {
                    position: "absolute",
                    "z-index": 9999,
                    left: 0,
                    top: 0,
                    width: (B || this.boxSize.width) + "px",
                    height: (B || this.boxSize.height) + "px",
                    "background-repeat": "no-repeat",
                    "border-radius": ("circle" != I.option("magnifier-shape")) ? 0 : B / 2
                }).jAddEvent("dragstart selectstart", function(S) {
                    S.stop()
                }),
                image: null,
                ratio: {
                    x: 0,
                    y: 0
                },
                pos: {
                    x: 0,
                    y: 0
                },
                delta: {
                    x: 0,
                    y: 0
                },
                size: {
                    width: (B || this.boxSize.width),
                    height: (B || this.boxSize.height)
                },
                ddx: 0,
                ddy: 0,
                fadeFX: null,
                moveTimer: null,
                startTime: null,
                spin: this,
                start: function(T, U, S) {
                    if (!I.canMagnify) {
                        return
                    }
                    if ("inner" == I.option("magnifier-shape")) {
                        this.ratio.x = I.zoomSize.width / I.boxSize.width;
                        this.ratio.y = I.zoomSize.height / I.boxSize.height
                    } else {
                        this.ratio.x = I.zoomSize.width / I.size.width;
                        this.ratio.y = I.zoomSize.height / I.size.height
                    }
                    H = true;
                    P = false;
                    if ("inner" == I.option("magnifier-shape")) {
                        this.size = I.zoomSize;
                        this.div.jSetCss({
                            width: "100%",
                            height: "100%"
                        })
                    }
                    this.image = f(T.img);
                    this.image.jSetCss({
                        width: I.zoomSize.width,
                        height: I.zoomSize.height
                    }).jAppendTo(this.div);
                    this.div.jSetOpacity(0);
                    this.div.jAppendTo(I.isFullScreen ? I.fullScreenBox : I.wrapper);
                    I.o.jRemoveClass("zoom-in");
                    this.animate(null, U, S);
                    this.fadeFX.stop();
                    this.fadeFX.options.duration = 400;
                    this.fadeFX.options.onComplete = function() {
                        I.option("onzoomin").call(null, I.o)
                    };
                    this.fadeFX.start({
                        opacity: [0, 1]
                    });
                    this.registerEvents()
                },
                stop: function(S) {
                    this.unRegisterEvents();
                    H = false;
                    R = false;
                    this.fadeFX.stop();
                    this.fadeFX.options.onComplete = function() {
                        clearTimeout(I.magnifier.moveTimer);
                        I.magnifier.moveTimer = null;
                        I.magnifier.image.jRemove();
                        I.magnifier.div.jRemove();
                        I.magnifier.pos = {
                            x: 0,
                            y: 0
                        };
                        I.magnifier.delta = {
                            x: 0,
                            y: 0
                        };
                        I.magnifier.ddx = 0;
                        I.magnifier.ddy = 0;
                        I.o.jAddClass("zoom-in");
                        I.option("onzoomout").call(null, I.o)
                    };
                    this.fadeFX.options.duration = 200;
                    this.fadeFX.start({
                        opacity: [this.fadeFX.el.jGetCss("opacity"), 0]
                    })
                },
                animate: function(W, V, U) {
                    if (!H) {
                        return
                    }
                    var T, X, S = U || (W && W.isTouchEvent()) || false;
                    if (W) {
                        if (S && !W.isPrimaryTouch()) {
                            return
                        }
                        f(W).stopDefaults()
                    }
                    V = V || W.jGetPageXY();
                    if (V.x > I.boxBoundaries.right || V.x < I.boxBoundaries.left || V.y > I.boxBoundaries.bottom || V.y < I.boxBoundaries.top) {
                        return
                    }
                    if (W && S) {
                        W.stop()
                    }
                    this.touchEvent = S;
                    if ("inner" === I.option("magnifier-shape")) {
                        V.x -= this.delta.x;
                        V.y -= this.delta.y;
                        if (this.ratio.x <= 1) {
                            T = I.boxSize.width / 2;
                            T -= I.boxSize.width / this.ratio.x / 2
                        } else {
                            T = (W && S) ? (I.boxBoundaries.right - V.x) : V.x - I.boxBoundaries.left;
                            T -= I.boxSize.width / this.ratio.x / 2;
                            T = Math.max(0, Math.min(T, I.boxSize.width - I.boxSize.width / this.ratio.x))
                        }
                        if (this.ratio.y <= 1) {
                            X = I.boxSize.height / 2;
                            X -= I.boxSize.height / this.ratio.y / 2
                        } else {
                            X = (W && S) ? (I.boxBoundaries.bottom - V.y) : V.y - I.boxBoundaries.top;
                            X -= I.boxSize.height / this.ratio.y / 2;
                            X = Math.max(0, Math.min(X, I.boxSize.height - I.boxSize.height / this.ratio.y))
                        }
                    } else {
                        T = V.x;
                        X = V.y
                    }
                    this.x = T;
                    this.y = X;
                    if (!this.moveTimer) {
                        this.move(1)
                    }
                },
                move: function(V) {
                    var Y, X, aa, Z, W, U, T, S;
                    isFinite(V) || (V = 0.2);
                    if ("inner" != I.option("magnifier-shape")) {
                        clearTimeout(this.moveTimer);
                        this.moveTimer = null;
                        Y = this.x;
                        X = this.y;
                        W = Math.round(Y - this.size.width / 2) - I.boxBoundaries.left;
                        U = Math.round(X - this.size.height / 2) - I.boxBoundaries.top;
                        if (this.touchEvent) {
                            U -= this.size.height / 2 + 22
                        }
                        T = Math.round(0 - ((Y - I.boundaries.left - I.borders.x) * this.ratio.x - (this.size.width / 2)));
                        S = Math.round(0 - ((X - I.boundaries.top - I.borders.y) * this.ratio.y - (this.size.height / 2)))
                    } else {
                        aa = Math.ceil((this.x - this.ddx) * V);
                        Z = Math.ceil((this.y - this.ddy) * V);
                        if (!aa && !Z) {
                            clearTimeout(this.moveTimer);
                            this.moveTimer = null;
                            return
                        }
                        this.ddx += aa;
                        this.ddy += Z;
                        W = 0;
                        U = 0;
                        T = -(this.ddx * (this.size.width / I.boxSize.width));
                        S = -(this.ddy * (this.size.height / I.boxSize.height))
                    }
                    if (H) {
                        this.div.jSetCss({
                            left: W,
                            top: U
                        });
                        this.image.jSetCss(u.browser.features.transform ? 9 === u.browser.ieMode || u.browser.presto ? {
                            top: 0,
                            left: 0,
                            transform: "translate(" + T + "px," + S + "px)"
                        } : {
                            top: 0,
                            left: 0,
                            transform: "translate3d(" + T + "px," + S + "px, 0)"
                        } : {
                            left: T,
                            top: S
                        })
                    }
                    if ("inner" == I.option("magnifier-shape")) {
                        this.moveTimer = setTimeout(this.moveBind, 40)
                    }
                },
                registerEvents: function() {
                    var S = I.isFullScreen ? I.fullScreenBox : I.wrapper;
                    if (I._autospin.cancelable) {
                        if (!u.browser.mobile || !u.browser.touchScreen) {
                            S.jAddEvent(k.mousedown, I.mDownEvent);
                            S.jAddEvent(k.mouseup, I.mUpEvent)
                        }
                        S.jAddEvent(k.mousemove, this.animateBind);
                        if (u.browser.touchScreen) {
                            S.jAddEvent("touchstart", I.mDownEvent);
                            S.jAddEvent("touchend", I.mUpEvent);
                            S.jAddEvent("touchmove", this.animateBind)
                        }
                    }
                },
                unRegisterEvents: function() {
                    var S = I.isFullScreen ? I.fullScreenBox : I.wrapper;
                    if (I._autospin.cancelable) {
                        if (!u.browser.mobile || !u.browser.touchScreen) {
                            S.jRemoveEvent(k.mousedown, I.mDownEvent);
                            S.jRemoveEvent(k.mouseup, I.mUpEvent)
                        }
                        S.jRemoveEvent(k.mousemove, this.animateBind);
                        if (u.browser.touchScreen) {
                            S.jRemoveEvent("touchstart", I.mDownEvent);
                            S.jRemoveEvent("touchend", I.mUpEvent);
                            S.jRemoveEvent("touchmove", this.animateBind)
                        }
                    }
                }
            };
            this.magnifier.fadeFX = new u.FX(this.magnifier.div);
            this.magnifier.animateBind = this.magnifier.animate.jBindAsEvent(this.magnifier);
            this.magnifier.moveBind = this.magnifier.move.jBind(this.magnifier);
            this.magnifier.div.jAddClass("m360-magnifier").jAddClass("m360-magnifier-" + this.option("magnifier-shape"));
            if ("inner" != this.option("magnifier-shape")) {
                I.magnifier.div.jAddEvent("mousescroll", function(U) {
                    var T = U.isMouse ? 5 : 8 / 54,
                        S;
                    f(U).stop();
                    T = (100 + T * Math.abs(U.delta)) / 100;
                    if (U.delta < 0) {
                        T = 1 / T
                    }
                    S = Math.max(100, Math.round(I.magnifier.size.width * T));
                    S = Math.min(S, I.size.width);
                    this.jSetCss({
                        width: S,
                        height: S,
                        "border-radius": ("circle" != I.option("magnifier-shape")) ? 0 : S / 2
                    });
                    I.magnifier.size = this.jGetSize();
                    I.magnifier.move(1)
                })
            }
            var F = this.loader = u.$new("div").jAddClass("m360-magnifier-loader-holder").append(u.$new("div").jAddClass("m360-loader")).jAddEvent(k.mousedown + " " + k.mousemove, function(S) {
                S.stopDistribution()
            });
            F.toggle = function(S) {
                if (S) {
                    F.timer = setTimeout(function() {
                        I.o.append(F)
                    }, 400)
                } else {
                    if (F.timer) {
                        clearTimeout(F.timer);
                        F.timer = false
                    }
                    (I.o === this.parentNode) && I.o.removeChild(F)
                }
            };

            function E(X, T) {
                var W, V, U, S = false;
                if (Q) {
                    return
                }
                if (!I.canMagnify) {
                    return
                }
                if (u.jTypeOf(X) == "event") {
                    S = (X.pointerType && ("touch" === X.pointerType || X.pointerType === X.MSPOINTER_TYPE_TOUCH)) || (/touch/i).test(X.type);
                    if ((U = f(X.getTarget())).jHasClass("icon")) {
                        if (H && U.jHasClass("magnify")) {
                            return
                        }
                        if (!H && U.jHasClass("spin")) {
                            return
                        }
                    }
                    X.stop()
                }
                if (H && T) {
                    return
                }
                if (!H && false == T) {
                    return
                }
                if (H && !T) {
                    I.magnifier.stop(u.jTypeOf(X) == "event" ? X.jGetPageXY() : null);
                    U && U.jHasClass("spin") && I._autospin.infinite && I._autospin.play()
                } else {
                    W = I.checkJumpRowCol(I.current.row, I.current.col);
                    V = (u.jTypeOf(X) == "event") ? X.jGetPageXY() : X;
                    F && F.toggle(true);
                    R = true;
                    P = false;
                    I._autospin.pause();
                    new u.ImageLoader(I.getImageURL(W.row + 1, W.col + 1, "zoom"), {
                        onload: f(function(Z, Y) {
                            I.resizeCallback();
                            I.magnifier.start(Y, V, Z)
                        }).jBind(null, S),
                        onerror: function() {
                            R = false
                        },
                        oncomplete: function() {
                            F && F.toggle(false)
                        }
                    })
                }
                return false
            }
            this._showM = E.jBind(this, {
                x: I.boxBoundaries.left + (I.boxBoundaries.right - I.boxBoundaries.left) / 2,
                y: I.boxBoundaries.top + (I.boxBoundaries.bottom - I.boxBoundaries.top) / 2
            }, true);
            this._hideM = E.jBind(this, null, false)
        }
        if (this._autospin.cancelable && this.option("fullscreen")) {
            this.fullscreenIcon = u.$new("button").jAddClass("m360-icon m360-icon-fullscreen-open").jAddEvent(k.mousedown, function(S) {
                S.stopDistribution()
            }).jAddEvent("click", function(S) {
                if (3 == S.getButton()) {
                    return true
                }
                S.stop();
                this.enterFullscreen();
                return false
            }.jBindAsEvent(this)).jAppendTo(this.wrapper);
            u.browser.touchScreen && this.fullscreenIcon.jAddEvent("touchstart", function(S) {
                S.stopDistribution()
            })
        }
        this.resizeCallback();
        if (this._autospin.maxFrames > 0 && f(this.option("autospin-start")).contains("load")) {
            this._autospin.play()
        } else {
            this.jump(this.currentFrame.row, this.currentFrame.col);
            if (this.option("rows") * this.option("columns") > 1 && this.option("hint") && this._autospin.cancelable) {
                this.setupHint()
            }
        }
        var N = f(this.o).jFetch("cr");
        if (N) {
            f(this.o).append(N)
        }
        ("function" == u.jTypeOf(this.option("onready"))) && this.option("onready").call(null, this.o)
    };
    w.prototype._checkDragFrames = function(F, E, G) {
        if (!this.option("smoothing") || !F.date) {
            return
        }
        var D = u.now() - F.date;
        F.date = false;
        if (D <= 0) {
            return
        }
        if (D < 50) {
            D = 50
        }
        var C = 0.01;
        ppf = this.ppf, loopR = this.option("loop-row") && this.loadedRows.count == this.option("rows"), loopC = this.option("loop-column"), dx = E.x - F.spos.x, dy = E.y - F.spos.y;

        function B(K) {
            var L = K == "x" ? dx : dy;
            var H = L / D;
            var J = (H / 2) * (H / C);
            var I;
            if (K == "x") {
                if (Math.abs(dx) < Math.abs(dy)) {
                    I = J - (F.spos.x - G.x)
                } else {
                    I = Math.abs(L + (L > 0 ? J : (0 - J))) - Math.abs(F.spos.x - G.x);
                    I = L > 0 ? (0 - I) : I
                }
            } else {
                if (!loopR || Math.abs(dx) >= Math.abs(dy)) {
                    I = J - (F.spos.y - G.y)
                } else {
                    I = Math.abs(L + (L > 0 ? J : (0 - J))) - Math.abs(F.spos.y - G.y);
                    I = L > 0 ? (0 - I) : I
                }
            }
            I /= ppf[K];
            return I > 0 ? Math.floor(I) : Math.ceil(I)
        }
        this.jump(this.current.row + B("y"), this.current.col + B("x"), true, 2 * Math.abs(Math.floor(Math.max(Math.abs(dx), Math.abs(dy)) / D / C)))
    };
    w.prototype.jump = function(E, C, B, D) {
        this.current.row = E;
        this.current.col = C;
        this.fx && this.fx.stop();
        if (!B) {
            this.current.tmp.row = E;
            this.current.tmp.col = C;
            this.jump_(E, C);
            return
        }
        this.fx = new u.FX(this.o, {
            duration: D,
            transition: u.FX.Transition.quadOut,
            onBeforeRender: (function(F) {
                this.current.tmp.col = F.col;
                this.current.tmp.row = F.row;
                this.jump_(F.row, F.col)
            }).jBind(this)
        }).start({
            col: [this.current.tmp.col, C],
            row: [this.current.tmp.row, E]
        })
    };
    w.prototype.checkJumpRowCol = function(E, C) {
        var B, D;
        E = Math.round(E);
        C = Math.round(C);
        if (this.option("loop-row")) {
            E %= this.option("rows");
            E < 0 && (E += this.option("rows"))
        }
        if (this.loadedRows.indexes.contains(E)) {
            E = this.loadedRows.indexes.indexOf(E)
        } else {
            var B = (E - this.currentFrame.row) / Math.abs(E - this.currentFrame.row);
            var D = this.loadedRows.indexes.filter(function(F) {
                return (F - E) * B > 0
            });
            E = D.length ? this.loadedRows.indexes.indexOf(D[(B < 0) ? D.length - 1 : 0]) : (!this.option("loop-row") || this.loadedRows.count < this.option("rows")) ? this.loadedRows.indexes.indexOf(this.currentFrame.row) : (B < 0 ? this.loadedRows.count - 1 : 0)
        }
        if (!this.option("loop-column") && (!this._autospin || this._autospin.frames < 1)) {
            C > (this.option("columns") - 1) && (C = this.option("columns") - 1);
            C < 0 && (C = 0)
        }
        C %= this.option("columns");
        C < 0 && (C += this.option("columns"));
        (!this.option("loop-column")) && (this.current.col = C);
        this.currentFrame = {
            row: this.loadedRows.indexes[E],
            col: C
        };
        if (!this.option("loop-row") || this.loadedRows.count < this.option("rows")) {
            this.current.row = this.currentFrame.row
        }
        return u.detach(this.currentFrame)
    };
    w.prototype.jump_ = function(F, B) {
        var D = this.checkJumpRowCol(F, B),
            E, C, G;
        F = D.row;
        B = D.col;
        E = this.images.small[F][B];
        if (this.isFullScreen && !(l && this.useMultiBackground && this.imageQueue.fullscreen.length) && this.images.fullscreen[F] && this.images.fullscreen[F][B] && this.images.fullscreen[F][B].loaded) {
            E = this.images.fullscreen[F][B]
        }
        if (!E.loaded) {
            return
        }
        if (u.browser.features.canvas) {
            this.ctxDraw(E.cachedObject)
        } else {
            this.imgContext.src = E.url
        }
		
		this.option("actionspin").call(null, this.o, B);
        if (this.spinStarted && (!this.lastFrame || this.lastFrame.row != F || this.lastFrame.col != B)) {
            this.option("onspin").call(null, this.o);
            this.spinStarted = false;
            if (this.hintBox) {
                this.hideHintBox()
            }
        }
        this.lastFrame = D
    };
    w.prototype.ctxDraw = function(D) {
        var C = 0,
            H = this.size.width,
            F = this.size.height,
            B = D.naturalWidth,
            G = D.naturalHeight;
        if ((window.devicePixelRatio || 1) >= 2 && ((B * G) / (H * F) >= 2)) {
            H *= 2;
            F *= 2
        }
        C = Math.ceil(Math.log(Math.max(D.naturalWidth / H, D.naturalHeight / F)) / Math.LN2);
        this.backstageCanvas.width = B;
        this.backstageCanvas.height = G;
        this.backstageCanvas2.width = B;
        this.backstageCanvas2.height = G;
        this.backstageCtx.drawImage(D, 0, 0, B, G);
        for (var E = C - 1; E > 0; E--) {
            this.backstageCanvas2.width *= 0.5;
            this.backstageCanvas2.height *= 0.5;
            this.backstageCtx2.drawImage(this.backstageCanvas, 0, 0, this.backstageCanvas.width, this.backstageCanvas.height, 0, 0, this.backstageCanvas2.width, this.backstageCanvas2.height);
            this.backstageCanvas.width *= 0.5;
            this.backstageCanvas.height *= 0.5;
            this.backstageCtx.drawImage(this.backstageCanvas2, 0, 0, this.backstageCanvas2.width, this.backstageCanvas2.height, 0, 0, this.backstageCanvas.width, this.backstageCanvas.height)
        }
        this.canvas.width = H;
        this.canvas.height = F;
        this.canvasContext.drawImage(this.backstageCanvas, 0, 0, this.backstageCanvas.width, this.backstageCanvas.height, 0, 0, this.canvas.width, this.canvas.height)
    };
    w.prototype.reflow = function() {
        var B, D, C;
        this.boundaries = this.o.jGetRect();
        this.boxSize = this.wrapper.jGetSize();
        this.boxBoundaries = this.wrapper.jGetRect();
        if (this.isFullScreen) {
            D = f(document).jGetSize();
            C = this.fullScreenSize.width / this.fullScreenSize.height;
            if (u.browser.trident && u.browser.backCompat) {
                this.fullScreenBox.jSetCss({
                    width: D.width,
                    height: D.height
                })
            }
            this.boxSize = this.fullScreenBox.jGetSize();
            this.boxBoundaries = this.fullScreenBox.jGetRect();
            this.o.jSetCss(this.adjustFSSize(D))
        }
        B = f(this.o).jGetSize();
        if (this.isFullScreen && 11 == u.browser.ieMode && window.parent !== window.window) {
            this.boxSize.width = this.fullScreenBox.clientWidth;
            this.boxSize.height = this.fullScreenBox.clientHeight;
            B.width = this.o.clientWidth;
            B.height = this.o.clientHeight
        }
        if (B.width <= 0 || B.height <= 0) {
            return
        }
        B.width -= this.borders.x;
        B.height -= this.borders.y;
        this.size = B;
        if (this.option("magnify")) {
            if ((this.size.width * this.size.height) / (this.zoomSize.width * this.zoomSize.height) > 0.8) {
                this._hideM && this._hideM();
                this.canMagnify = false;
                this.o.jRemoveClass("zoom-in")
            } else {
                this.canMagnify = true;
                this.o.jAddClass("zoom-in")
            }
        }
        this.ppf = {
            x: this.size.width / this.option("columns") / Math.pow(this.option("sensitivityX") / 50, 2),
            y: this.size.height / this.option("rows") / Math.pow(this.option("sensitivityY") / 50, 2)
        };
        if (this.current) {
            this.jump_(this.current.row, this.current.col)
        }
    };
    w.prototype.spin = function(B) {
        (this._hideM) && this._hideM();
        if (this.hintBox) {
            this.hideHintBox()
        }
        this.spinStarted = true;
        (B || null) ? this.jump(this.current.row, this.current.col + B, true, 100 * (Math.log(Math.abs(B) / Math.log(2)))): this._autospin.play(Number.MAX_VALUE)
    };
    w.prototype.rotate = function(B, C) {
        if (!B) {
            B = 0
        }
        if (!C) {
            C = 0
        }
        if (!B && !C) {
            return
        }
        if (this._hideM) {
            this._hideM()
        }
        if (this.hintBox) {
            this.hideHintBox()
        }
        this.spinStarted = true;
        this.jump(this.current.row + C, this.current.col + B, true, 100 * (Math.log(Math.max(Math.abs(B), Math.abs(C)) / Math.log(2))))
    };
    w.prototype.rotateX = function(B) {
        this.rotate(B, 0)
    };
    w.prototype.rotateY = function(B) {
        this.rotate(0, B)
    };
    w.prototype.magnify = function(B) {
        (u.defined(B) ? B : true) ? this._showM && this._showM(): this._hideM && this._hideM()
    };
    w.prototype.stop = function() {
        var D, B, E, C = window.URL || window.webkitURL || null;
        if (this._autospin && this._autospin.frames > 0) {
            this._autospin.pause()
        }
        if (this.isFullScreen) {
            this.o.firstChild.jSetCss({});
            this.o.jSetCss({
                width: "",
                height: "100%",
                "max-height": "",
                "max-width": ""
            }).jAppendTo(this.wrapper)
        }
        if (this.fullScreenBox) {
            if (this.fullScreenScrollCallback) {
                f(window).jRemoveEvent("scroll", this.fullScreenScrollCallback)
            }
            if (this.fullScreenScrollCallbackTimer) {
                clearTimeout(this.fullScreenScrollCallbackTimer)
            }
            this.fullScreenBox.kill();
            this.fullScreenBox = null
        }
        if (this.magnifier && this.magnifier.div) {
            this.magnifier.div.kill();
            this.magnifier = null
        }
        if (this.fullscreenIcon) {
            this.fullscreenIcon.kill();
            this.fullscreenIcon = null
        }
        f(window).jRemoveEvent("resize", this.resizeCallback);
        clearTimeout(this.reflowTimer);
        this.reflowTimer = null;
        this.o.jClearEvents();
        while (this.o.firstChild != this.o.lastChild) {
            this.o.removeChild(this.o.lastChild)
        }
        if (this.option("spin") == "drag") {
            f(document).jRemoveEvent([k.mousemove, "touchmove"], this.mMoveEvent)
        }
        f(document).jRemoveEvent([k.mouseup, k.mouseout, "touchend"], this.mDocUpEvent);
        this.o.replaceChild(this.oi, this.o.firstChild);
        this.o.jSetCssProp("background", "");
        this.o.jRemoveClass("zoom-in");
        if (this.wrapper) {
            this.wrapper.parentNode.replaceChild(this.o, this.wrapper);
            this.wrapper.kill();
            this.wrapper = null
        }
        for (D = 0, B = this.addedCSS.length; D < B; D++) {
            u.removeCSS("leoimage360-css", this.addedCSS[D])
        }
        f(this.imgCacheBox).jRemove();
        if (C) {
            for (E in this.imageMap) {
                f(this.imageMap[E]).jEach(function(G) {
                    var F = G.img.bgURL.replace(/^url\s*\(\s*["']?/, "").replace(/["']?\s*\)$/, "");
                    if (/^blob\:/.test(F)) {
                        C.revokeObjectURL(F)
                    }
                })
            }
        }
    };
    w.prototype.enterFullscreen = function() {
        if (!this._autospin.cancelable || !this.option("fullscreen")) {
            return
        }
        this._hideM && this._hideM();
        var E = f(document).jGetSize(),
            D = f(window).jGetScroll(),
            C = f(document).jGetFullSize(),
            B = window.parent !== window.window;
        if (B) {
            if (C.height <= E.height) {
                D.y = this.boxBoundaries.top
            }
            E.height = Math.min(E.height, this.fullScreenSize.height);
            if (E.height + D.y > C.height) {
                E.width -= u.browser.scrollbarsWidth
            }
        }
        if (!this.fullScreenBox) {
            this.fullScreenBox = u.$new("div", {}, {
                display: "block",
                overflow: "hidden",
                position: "absolute",
                zIndex: 20000,
                "text-align": "center",
                "vertical-align": "middle",
                opacity: 1,
                width: this.boxSize.width,
                height: this.boxSize.height,
                top: this.boxBoundaries.top,
                left: this.boxBoundaries.left
            }).jAddClass(c + "-fullscreen");
            if (!u.browser.touchScreen || !u.browser.mobile) {
                this.fullScreenBox.jAddClass("desktop")
            }
            if (u.browser.ieMode) {
                this.fullScreenBox.jAddClass("leoimage-for-ie" + u.browser.ieMode)
            }
            if (u.browser.ieMode && u.browser.ieMode < 8) {
                this.fullScreenBox.append(u.$new("span", null, {
                    display: "inline-block",
                    height: "100%",
                    width: 1,
                    "vertical-align": "middle"
                }))
            }
            this.adjustFSSize = function(I) {
                var F, H, G = this.fullScreenSize.width / this.fullScreenSize.height;
                F = Math.min(this.fullScreenSize.width, I.width * 0.98);
                H = Math.min(this.fullScreenSize.height, I.height * 0.98);
                if (F / H > G) {
                    F = H * G
                } else {
                    if (F / H <= G) {
                        H = F / G
                    }
                }
                return {
                    width: Math.floor(F),
                    height: Math.floor(H)
                }
            };
            if (u.browser.trident && u.browser.backCompat) {
                this.fullScreenScrollCallback = function() {
                    var F = f(window).jGetScroll(),
                        G = this.fullScreenBox.jGetPosition();
                    this.fullScreenScrollCallbackTimer && clearTimeout(this.fullScreenScrollCallbackTimer);
                    this.fullScreenScrollCallbackTimer = setTimeout(function() {
                        new u.FX(this.fullScreenBox, {
                            duration: 250
                        }).start({
                            top: [G.top, F.y],
                            left: [G.left, F.x]
                        })
                    }.jBind(this), 300)
                }.jBind(this)
            }
            if (this._autospin.cancelable) {
                if (!u.browser.mobile || !u.browser.touchScreen) {
                    this.fullScreenBox.jAddEvent(k.mousedown, this.mDownEvent);
                    this.fullScreenBox.jAddEvent(k.mouseup, this.mUpEvent)
                }
                if (u.browser.touchScreen) {
                    this.fullScreenBox.jAddEvent("touchstart", this.mDownEvent);
                    this.fullScreenBox.jAddEvent("touchend", this.mUpEvent)
                }
            }
        }
        this.fullScreenImage || (this.fullScreenImage = f(this.o.firstChild.cloneNode(false)).jSetCss({
            position: "relative",
            "z-index": 1
        }));
        this.fullScreenImage.jSetCss({
            "margin-top": "-100%",
            "margin-left": "100%"
        }).jSetOpacity(0);
        if (E.width / E.height > this.fullScreenSize.width / this.fullScreenSize.height) {
            this.fullScreenImage.jSetCss({
                width: "auto",
                height: "98%",
                "max-height": this.fullScreenSize.height,
                display: "inline-block",
                "vertical-align": "middle"
            })
        } else {
            this.fullScreenImage.jSetCss({
                width: "98%",
                "max-width": this.fullScreenSize.width,
                height: "auto",
                display: "inline-block",
                "vertical-align": "middle"
            })
        }
        this.fullScreenBox.jAppendTo(document.body).append(this.fullScreenImage);
        this.fullScreenBox.show();
        this.o.jSetCss({
            "max-width": this.fullScreenSize.width,
            "max-height": this.fullScreenSize.height,
            width: this.fullScreenImage.jGetSize().width + this.borders.x,
            height: "auto",
            "background-size": this.fullScreenImage.jGetSize().width + "px " + this.fullScreenImage.jGetSize().height + "px",
            "z-index": 2
        }).jAppendTo(this.fullScreenBox, "top");
        s.browser.features.fullScreen && this.fullScreenBox.jSetOpacity(1);
        s.browser.fullScreen.request(this.fullScreenBox, {
            onEnter: this.onEnteredFullScreen.jBind(this),
            onExit: this.onExitFullScreen.jBind(this),
            fallback: function() {
                this.fullScreenFX || (this.fullScreenFX = new u.FX(this.fullScreenBox, {
                    duration: 400,
                    transition: u.FX.Transition.expoOut,
                    onStart: (function() {
                        this.fullScreenBox.jSetCss({
                            width: this.boxSize.width,
                            height: this.boxSize.height,
                            top: this.boxBoundaries.top,
                            left: this.boxBoundaries.left
                        }).jAppendTo(document.body)
                    }).jBind(this),
                    onAfterRender: (function(F) {
                        this.o.jSetCss(this.fullScreenImage.jGetSize());
                        this.size = this.o.jGetSize();
                        this.jump_(this.current.row, this.current.col)
                    }).jBind(this),
                    onComplete: (function() {
                        this.onEnteredFullScreen(true)
                    }).jBind(this)
                }));
                this.fullScreenFX.start({
                    width: [this.boxSize.width, E.width],
                    height: [this.boxSize.height, E.height],
                    top: [this.boxBoundaries.top, 0 + D.y],
                    left: [this.boxBoundaries.left, 0 + D.x],
                    opacity: [0, 1]
                })
            }.jBind(this)
        })
    };
    w.prototype.onEnteredFullScreen = function(I) {
        var G, B, E = 0,
            H = 0,
            C = window.parent !== window.window,
            J, D = this.getCurrentFrame();
        if (I && !this.isFullScreen && !C && !(u.browser.trident && u.browser.backCompat)) {
            this.fullScreenBox.jSetCss({
                display: "block",
                position: "fixed",
                top: 0,
                bottom: 0,
                left: 0,
                right: 0,
                width: "auto",
                height: "auto"
            })
        }
        this.isFullScreen = true;
        this.o.firstChild.jSetCss({
            width: "100%",
            height: "auto",
            "max-width": this.fullScreenSize.width,
            "max-height": this.fullScreenSize.height
        });
        this.resizeCallback();
        if (this.firstFullScreenRun) {
            setTimeout((function() {
                this.progressBar.reset();
                this.progressBar.changePlaceholder(this.fullScreenBox);
                this.preloadImages("fullscreen", this.currentFrame.row + 1, this.currentFrame.col + 1)
            }).jBind(this), 1);
            this.firstFullScreenRun = false
        }
        this.jump_(this.current.row, this.current.col);
        if (this.fullScreenScrollCallback) {
            f(window).jAddEvent("scroll", this.fullScreenScrollCallback)
        }
        if (!this.leaveFSButton) {
            this.leaveFSButton = u.$new("button").jAddClass("m360-icon m360-icon-fullscreen-close").jAppendTo(this.fullScreenBox).jAddEvent(k.mousedown, function(K) {
                K.stopDistribution()
            }).jAddEvent("click", function(L) {
                var K;
                if (3 == L.getButton()) {
                    return true
                }
                L.stop();
                if (K = this.fullScreenBox.jFetch("fullscreen:pseudo:event:keydown")) {
                    u.doc.jRemoveEvent("keydown", K);
                    this.fullScreenBox.jDel("fullscreen:pseudo:event:keydown")
                }
                this.exitFullscreen();
                return false
            }.jBindAsEvent(this));
            u.browser.touchScreen && this.leaveFSButton.jAddEvent("touchstart", function(K) {
                K.stopDistribution()
            })
        }
        this.leaveFSButton.show();
        if (I) {
            var F = function(K) {
                if (K.keyCode == 27) {
                    u.doc.jRemoveEvent("keydown", F);
                    this.exitFullscreen()
                }
            }.jBindAsEvent(this);
            this.fullScreenBox.jStore("fullscreen:pseudo:event:keydown", F);
            u.doc.jAddEvent("keydown", F);
            !u.browser.touchScreen && (this.leaveFSMessage = new u.Message("Press ESC key to leave full-screen", 4000, this.fullScreenBox, c + "-message"))
        }
        this.fullscreenIcon.hide()
    };
    w.prototype.exitFullscreen = function() {
        var D = this.fullScreenBox.jGetSize(),
            C = this.fullScreenBox.jGetRect(),
            B = this.checkJumpRowCol(this.current.row, this.current.col);
        this.leaveFSMessage && this.leaveFSMessage.hide(0);
        this._hideM && this._hideM();
        if (D.width / D.height > this.fullScreenSize.width / this.fullScreenSize.height) {
            this.fullScreenImage.jSetCss({
                width: "auto",
                height: "98%",
                "max-height": this.fullScreenSize.height,
                display: "inline-block",
                "vertical-align": "middle"
            })
        } else {
            this.fullScreenImage.jSetCss({
                width: "98%",
                "max-width": this.fullScreenSize.width,
                height: "auto",
                display: "inline-block",
                "vertical-align": "middle"
            })
        }
        if (s.browser.fullScreen.capable && s.browser.fullScreen.enabled()) {
            s.browser.fullScreen.cancel()
        } else {
            this.leaveFSButton.hide();
            this.fullScreenExitFX || (this.fullScreenExitFX = new u.FX(this.fullScreenBox, {
                duration: 300,
                transition: u.FX.Transition.expoOut,
                onStart: (function() {
                    this.isFullScreen = false;
                    this.fullScreenBox.jSetCss({
                        position: "absolute"
                    }).jAppendTo(document.body)
                }).jBind(this),
                onAfterRender: (function(E) {
                    this.o.jSetCss(this.fullScreenImage.jGetSize());
                    this.size = this.o.jGetSize();
                    this.jump_(this.current.row, this.current.col)
                }).jBind(this),
                onComplete: (function() {
                    this.onExitFullScreen(true)
                }).jBind(this)
            }));
            this.fullScreenExitFX.start({
                width: [D.width, this.boxSize.width],
                height: [D.height, this.boxSize.height],
                top: [0 + C.top, this.boxBoundaries.top],
                left: [0 + C.left, this.boxBoundaries.left],
                opacity: [1, 0.5]
            })
        }
    };
    w.prototype.onExitFullScreen = function(C) {
        var B, D = this.getCurrentFrame();
        if (!this.fullScreenBox) {
            return
        }
        this.fullscreenProgressBox && this.fullscreenProgressBox.jRemove() && delete this.fullscreenProgressBox;
        if (this.fullScreenScrollCallback) {
            f(window).jRemoveEvent("scroll", this.fullScreenScrollCallback)
        }
        this.isFullScreen = false;
        this.o.jAppendTo(this.wrapper).jSetOpacity(0).jSetCss({
            width: "",
            height: "",
            "max-height": "",
            "max-width": "100%",
            "z-index": ""
        });
        this.o.firstChild.jSetCss({
            width: "",
            height: "",
            "max-width": "",
            "max-height": ""
        });
        this.resizeCallback();
        this.leaveFSButton.hide();
        this.fullscreenIcon.show();
        this.o.jSetOpacity(1);
        this.fullScreenBox.hide()
    };
    w.prototype.setupHint = function() {
        if (!this.hintBox) {
            this.hintBox = u.$new("div", {
                "class": "m360-hint"
            });
            this.hintMessage = u.$new("span", {
                "class": "m360-hint-message"
            }).append(document.createTextNode(this.localString(u.browser.touchScreen ? "mobile-hint-text" : "hint-text"))).jAppendTo(this.hintBox);
            f(this.hintBox).jAppendTo(this.o)
        }
        if (this.option("mousewheel-step") > 0) {
            var B = function(D) {
                this.o.jRemoveEvent("mousescroll", B);
                this.hideHintBox()
            }.jBindAsEvent(this);
            this.o.jAddEvent("mousescroll", B)
        }
        if ("hover" === this.option("spin")) {
            var C = function() {
                this.hideHintBox();
                this.o.jRemoveEvent("mousemove", C)
            }.jBindAsEvent(this);
            this.o.jAddEvent("mousemove", C)
        }
    };
    w.prototype.hideHintBox = function() {
        if (!this.hintBox || this.hintBox.hidding) {
            return
        }
        this.hintBox.hidding = true;
        new u.FX(this.hintBox, {
            duration: 200,
            onComplete: function() {
                this.hintBox.jRemove();
                delete this.hintBox
            }.jBind(this)
        }).start({
            opacity: [this.hintBox.jGetCss("opacity"), 0]
        })
    };
    w.prototype.getCurrentFrame = function() {
        var B = this.checkJumpRowCol(this.current.row, this.current.col);
        B.row++;
        B.col++;
        return B
    };
    var m = function() {
        return "mgctlbxN$M360 mgctlbxV$" + "v4.6.5".replace("v", "") + " mgctlbxL$" + "m".toUpperCase() + ((window.mgctlbx$Pltm && "string" == u.jTypeOf(window.mgctlbx$Pltm)) ? " mgctlbxP$" + window.mgctlbx$Pltm.toLowerCase() : "")
    };
    var j = f([]);
    var o = {
        version: "v4.6.5 for LeoImageToolbox.com",
        options: {},
        lang: {},
        callbacks: {},
        start: function(C) {
            var B = null;
            u.$A((C ? [f(C)] : document.byClass("LeoImage360"))).jEach((function(D) {
                if (f(D)) {
                    !j.filter(function(E) {
                        return E.o === D
                    }).length && j.push(B = new w(D))
                }
            }).jBind(this));
            return B
        },
        stop: function(E) {
            var C, D, B;
            if (E) {
                (D = r(E)) && (D = j.splice(j.indexOf(D), 1)) && D[0].stop() && (delete D[0]);
                return
            }
            while (C = j.length) {
                D = j.splice(C - 1, 1);
                D[0].stop();
                delete D[0]
            }
        },
        spin: function(D, C) {
            var B;
            (B = r(D)) && B.spin(C)
        },
        jump: function(D, C) {
            var B = null;
            if (B = r(D)) {
                B._hideM && B._hideM();
                B.hintBox && B.hideHintBox();
                B.jump(B.current.row + C, B.current.col)
            }
        },
        pause: function(C) {
            var B;
            (B = r(C)) && B._autospin.pause()
        },
        magnifyOn: function(C) {
            var B;
            (B = r(C)) && B.magnify(true)
        },
        magnifyOff: function(C) {
            var B;
            (B = r(C)) && B.magnify(false)
        },
        fullscreen: function(C) {
            var B;
            (B = r(C)) && B.enterFullscreen()
        },
        getCurrentFrame: function(D) {
            var B, C = null;
            if (B = r(D)) {
                C = B.getCurrentFrame();
                C.column = C.col;
                delete C.col
            }
            return C
		}, setCurrentFrame: function(D, row, col) {
			var B, C = null;
			if (B = r(D)) {
				B.jump(row, col);
				return true;
			}
			return false
        },
        registerCallback: function(B, C) {
            o.callbacks[B] = C
        }
    };

    function r(D) {
        var C = [],
            B = null;
        (D && (B = f(D))) && (C = j.filter(function(E) {
            return E.o === B
        }));
        return C.length ? C[0] : null
    }
    f(document).jAddEvent("domready", function() {
        m = m();
        n = u.$new("div", {
            "class": "m360-tmp-hdn-cont"
        }).jAppendTo(document.body);
        o.start()
    });
    return o
})();
