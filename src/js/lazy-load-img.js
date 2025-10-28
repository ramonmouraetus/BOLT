(function () {
  let RUNS = 0;
  let RAN = false;

  function runLazyLoad() {
    if ( RUNS === 0 || RAN ) return RUNS++;
    RAN++;


    !(function (t) {
      var e = {};
      function r(n) {
        if (e[n]) return e[n].exports;
        var o = (e[n] = { i: n, l: !1, exports: {} });
        return t[n].call(o.exports, o, o.exports, r), (o.l = !0), o.exports;
      }
      (r.m = t),
        (r.c = e),
        (r.d = function (t, e, n) {
          r.o(t, e) || Object.defineProperty(t, e, { enumerable: !0, get: n });
        }),
        (r.r = function (t) {
          "undefined" != typeof Symbol &&
            Symbol.toStringTag &&
            Object.defineProperty(t, Symbol.toStringTag, { value: "Module" }),
            Object.defineProperty(t, "__esModule", { value: !0 });
        }),
        (r.t = function (t, e) {
          if ((1 & e && (t = r(t)), 8 & e)) return t;
          if (4 & e && "object" == typeof t && t && t.__esModule) return t;
          var n = Object.create(null);
          if (
            (r.r(n),
            Object.defineProperty(n, "default", { enumerable: !0, value: t }),
            2 & e && "string" != typeof t)
          )
            for (var o in t)
              r.d(
                n,
                o,
                function (e) {
                  return t[e];
                }.bind(null, o)
              );
          return n;
        }),
        (r.n = function (t) {
          var e =
            t && t.__esModule
              ? function () {
                  return t.default;
                }
              : function () {
                  return t;
                };
          return r.d(e, "a", e), e;
        }),
        (r.o = function (t, e) {
          return Object.prototype.hasOwnProperty.call(t, e);
        }),
        (r.p = "/"),
        r((r.s = 0));
    })([
      function (t, e, r) {
        t.exports = r(1);
      },
      function (t, e) {
        function r(t, e) {
          if (!(t instanceof e))
            throw new TypeError("Cannot call a class as a function");
        }
        function n(t, e) {
          for (var r = 0; r < e.length; r++) {
            var n = e[r];
            (n.enumerable = n.enumerable || !1),
              (n.configurable = !0),
              "value" in n && (n.writable = !0),
              Object.defineProperty(t, n.key, n);
          }
        }
        var o = (function () {
          function t() {
            var e =
              arguments.length > 0 && void 0 !== arguments[0]
                ? arguments[0]
                : {};
            r(this, t),
              (this.context = this.createContext(e)),
              (this.attribToSet = this.context.attribToSet),
              (this.attribToGet = this.context.attribToGet),
              (this.tags = document.querySelectorAll(
                ""
                  .concat(this.context.selector, ".")
                  .concat(this.context.classToRemove)
              )),
              (this.observer = null),
              this.init();
          }
          var e, o, i;
          return (
            (e = t),
            (o = [
              {
                key: "createContext",
                value: function (t) {
                  return {
                    attribToSet: t.attribToSet || "src",
                    attribToGet: t.attribToGet || "src",
                    classToRemove: t.classToRemove || "",
                    selector: t.selector || "img[data-src]",
                    classToAdd: t.classToAdd || [],
                  };
                },
              },
              {
                key: "init",
                value: function () {
                  (this.observer = new IntersectionObserver(
                    this.onIntersect.bind(this),
                    { rootMargin: "100% 0px" }
                  )),
                    this.waitForImgs();
                },
              },
              {
                key: "onIntersect",
                value: function (t) {
                  var e = this;
                  Array.from(t)
                    .filter(function (t) {
                      return t.isIntersecting;
                    })
                    .forEach(function (t) {
                      e.observer.unobserve(t.target), e.loadImg(t.target);
                    });
                },
              },
              {
                key: "waitForImgs",
                value: function () {
                  var t = this;
                  this.imgInterval = setInterval(function () {
                    return t.tryToObserveImg();
                  }, 100);
                },
              },
              {
                key: "tryToObserveImg",
                value: function () {
                  var t = this;
                  this.tags.length &&
                    (Array.from(this.tags).forEach(function (e) {
                      return t.observer.observe(e);
                    }),
                    clearInterval(this.imgInterval));
                },
              },
              {
                key: "loadImg",
                value: function (t) {
                  try {
                    Array.from(t.classList).includes(
                      this.context.classToRemove
                    ) &&
                      (t.setAttribute(
                        [this.attribToSet],
                        t.dataset[this.attribToGet]
                      ),
                      t.classList.remove(this.context.classToRemove)),
                      this.context.classToAdd.map(function (e) {
                        t.classList.add(e);
                      }),
                      this.observer.unobserve(t);
                  } catch (t) {}
                },
              },
            ]) && n(e.prototype, o),
            i && n(e, i),
            t
          );
        })();
        (window.lazyParams = { classToRemove: "lazy-loading" }),
          (window.lazyTag = new o(window.lazyParams));
      },
    ]);


    const imageAnimation = document.querySelector('.image-effect-scale .loader-wrapper');
    if (imageAnimation) {
      imageAnimation.classList.remove('loader-wrapper');
      imageAnimation.style.display = 'none';
    }
  }

  if ( window.bolt_info?.is_single || window.bolt_info?.is_page ) {
    setTimeout(() => {
      runLazyLoad();
    }, 7000);

    const eventProcessedFlags = {};

    const interactions = ['scroll', 'mousemove', 'touchmove', 'click'];
    const interactionHandler = function(e) {
        if (!eventProcessedFlags.user_interaction) {
            eventProcessedFlags.user_interaction = true;

            interactions.forEach(interaction => {
                document.removeEventListener(interaction, interactionHandler);
            });

            runLazyLoad();
        }
    };

    interactions.forEach(interaction => {
        document.addEventListener(interaction, interactionHandler);
    });
  } else {
    RUNS = 1;
  }

  if (window.BOLT_RUN_ASSETS) {
    runLazyLoad();
  } else {
    document.addEventListener("BOLT_RUN_ASSETS", runLazyLoad);
  }
})();
