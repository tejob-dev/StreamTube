// Copyright 2011 Google Inc. All Rights Reserved.
(function () {
    /*

 Copyright The Closure Library Authors.
 SPDX-License-Identifier: Apache-2.0
*/
    var l,
        aa = function (a) {
            var b = 0;
            return function () {
                return b < a.length ? { done: !1, value: a[b++] } : { done: !0 };
            };
        },
        ba =
            "function" == typeof Object.defineProperties
                ? Object.defineProperty
                : function (a, b, c) {
                      if (a == Array.prototype || a == Object.prototype) return a;
                      a[b] = c.value;
                      return a;
                  },
        ca = function (a) {
            a = ["object" == typeof globalThis && globalThis, a, "object" == typeof window && window, "object" == typeof self && self, "object" == typeof global && global];
            for (var b = 0; b < a.length; ++b) {
                var c = a[b];
                if (c && c.Math == Math) return c;
            }
            throw Error("Cannot find global object");
        },
        da = ca(this),
        ea = function (a, b) {
            if (b)
                a: {
                    var c = da;
                    a = a.split(".");
                    for (var d = 0; d < a.length - 1; d++) {
                        var e = a[d];
                        if (!(e in c)) break a;
                        c = c[e];
                    }
                    a = a[a.length - 1];
                    d = c[a];
                    b = b(d);
                    b != d && null != b && ba(c, a, { configurable: !0, writable: !0, value: b });
                }
        };
    ea("Symbol", function (a) {
        if (a) return a;
        var b = function (f, g) {
            this.h = f;
            ba(this, "description", { configurable: !0, writable: !0, value: g });
        };
        b.prototype.toString = function () {
            return this.h;
        };
        var c = "jscomp_symbol_" + ((1e9 * Math.random()) >>> 0) + "_",
            d = 0,
            e = function (f) {
                if (this instanceof e) throw new TypeError("Symbol is not a constructor");
                return new b(c + (f || "") + "_" + d++, f);
            };
        return e;
    });
    ea("Symbol.iterator", function (a) {
        if (a) return a;
        a = Symbol("Symbol.iterator");
        for (var b = "Array Int8Array Uint8Array Uint8ClampedArray Int16Array Uint16Array Int32Array Uint32Array Float32Array Float64Array".split(" "), c = 0; c < b.length; c++) {
            var d = da[b[c]];
            "function" === typeof d &&
                "function" != typeof d.prototype[a] &&
                ba(d.prototype, a, {
                    configurable: !0,
                    writable: !0,
                    value: function () {
                        return fa(aa(this));
                    },
                });
        }
        return a;
    });
    var fa = function (a) {
            a = { next: a };
            a[Symbol.iterator] = function () {
                return this;
            };
            return a;
        },
        p = function (a) {
            return (a.raw = a);
        },
        q = function (a) {
            var b = "undefined" != typeof Symbol && Symbol.iterator && a[Symbol.iterator];
            return b ? b.call(a) : { next: aa(a) };
        },
        ha = function (a) {
            if (!(a instanceof Array)) {
                a = q(a);
                for (var b, c = []; !(b = a.next()).done; ) c.push(b.value);
                a = c;
            }
            return a;
        },
        ia = function (a, b) {
            return Object.prototype.hasOwnProperty.call(a, b);
        },
        ka =
            "function" == typeof Object.assign
                ? Object.assign
                : function (a, b) {
                      for (var c = 1; c < arguments.length; c++) {
                          var d = arguments[c];
                          if (d) for (var e in d) ia(d, e) && (a[e] = d[e]);
                      }
                      return a;
                  };
    ea("Object.assign", function (a) {
        return a || ka;
    });
    var ma =
            "function" == typeof Object.create
                ? Object.create
                : function (a) {
                      var b = function () {};
                      b.prototype = a;
                      return new b();
                  },
        oa = (function () {
            function a() {
                function c() {}
                new c();
                Reflect.construct(c, [], function () {});
                return new c() instanceof c;
            }
            if ("undefined" != typeof Reflect && Reflect.construct) {
                if (a()) return Reflect.construct;
                var b = Reflect.construct;
                return function (c, d, e) {
                    c = b(c, d);
                    e && Reflect.setPrototypeOf(c, e.prototype);
                    return c;
                };
            }
            return function (c, d, e) {
                void 0 === e && (e = c);
                e = ma(e.prototype || Object.prototype);
                return Function.prototype.apply.call(c, e, d) || e;
            };
        })(),
        pa;
    if ("function" == typeof Object.setPrototypeOf) pa = Object.setPrototypeOf;
    else {
        var qa;
        a: {
            var ra = { a: !0 },
                sa = {};
            try {
                sa.__proto__ = ra;
                qa = sa.a;
                break a;
            } catch (a) {}
            qa = !1;
        }
        pa = qa
            ? function (a, b) {
                  a.__proto__ = b;
                  if (a.__proto__ !== b) throw new TypeError(a + " is not extensible");
                  return a;
              }
            : null;
    }
    var ta = pa,
        r = function (a, b) {
            a.prototype = ma(b.prototype);
            a.prototype.constructor = a;
            if (ta) ta(a, b);
            else
                for (var c in b)
                    if ("prototype" != c)
                        if (Object.defineProperties) {
                            var d = Object.getOwnPropertyDescriptor(b, c);
                            d && Object.defineProperty(a, c, d);
                        } else a[c] = b[c];
            a.ya = b.prototype;
        },
        ua = function () {
            this.A = !1;
            this.l = null;
            this.B = void 0;
            this.h = 1;
            this.H = this.j = 0;
            this.o = null;
        },
        wa = function (a) {
            if (a.A) throw new TypeError("Generator is already running");
            a.A = !0;
        };
    ua.prototype.C = function (a) {
        this.B = a;
    };
    var xa = function (a, b) {
        a.o = { Cd: b, bf: !0 };
        a.h = a.j || a.H;
    };
    ua.prototype.return = function (a) {
        this.o = { return: a };
        this.h = this.H;
    };
    var ya = function (a, b, c) {
            a.h = c;
            return { value: b };
        },
        za = function (a) {
            a.j = 0;
            var b = a.o.Cd;
            a.o = null;
            return b;
        },
        Aa = function (a) {
            this.h = new ua();
            this.j = a;
        },
        Da = function (a, b) {
            wa(a.h);
            var c = a.h.l;
            if (c)
                return Ba(
                    a,
                    "return" in c
                        ? c["return"]
                        : function (d) {
                              return { value: d, done: !0 };
                          },
                    b,
                    a.h.return
                );
            a.h.return(b);
            return Ca(a);
        },
        Ba = function (a, b, c, d) {
            try {
                var e = b.call(a.h.l, c);
                if (!(e instanceof Object)) throw new TypeError("Iterator result " + e + " is not an object");
                if (!e.done) return (a.h.A = !1), e;
                var f = e.value;
            } catch (g) {
                return (a.h.l = null), xa(a.h, g), Ca(a);
            }
            a.h.l = null;
            d.call(a.h, f);
            return Ca(a);
        },
        Ca = function (a) {
            for (; a.h.h; )
                try {
                    var b = a.j(a.h);
                    if (b) return (a.h.A = !1), { value: b.value, done: !1 };
                } catch (c) {
                    (a.h.B = void 0), xa(a.h, c);
                }
            a.h.A = !1;
            if (a.h.o) {
                b = a.h.o;
                a.h.o = null;
                if (b.bf) throw b.Cd;
                return { value: b.return, done: !0 };
            }
            return { value: void 0, done: !0 };
        },
        Ea = function (a) {
            this.next = function (b) {
                wa(a.h);
                a.h.l ? (b = Ba(a, a.h.l.next, b, a.h.C)) : (a.h.C(b), (b = Ca(a)));
                return b;
            };
            this.throw = function (b) {
                wa(a.h);
                a.h.l ? (b = Ba(a, a.h.l["throw"], b, a.h.C)) : (xa(a.h, b), (b = Ca(a)));
                return b;
            };
            this.return = function (b) {
                return Da(a, b);
            };
            this[Symbol.iterator] = function () {
                return this;
            };
        },
        Fa = function (a) {
            function b(d) {
                return a.next(d);
            }
            function c(d) {
                return a.throw(d);
            }
            return new Promise(function (d, e) {
                function f(g) {
                    g.done ? d(g.value) : Promise.resolve(g.value).then(b, c).then(f, e);
                }
                f(a.next());
            });
        },
        Ga = function (a) {
            return Fa(new Ea(new Aa(a)));
        },
        Ha = function () {
            for (var a = Number(this), b = [], c = a; c < arguments.length; c++) b[c - a] = arguments[c];
            return b;
        };
    ea("Reflect", function (a) {
        return a ? a : {};
    });
    ea("Reflect.construct", function () {
        return oa;
    });
    ea("Reflect.setPrototypeOf", function (a) {
        return a
            ? a
            : ta
            ? function (b, c) {
                  try {
                      return ta(b, c), !0;
                  } catch (d) {
                      return !1;
                  }
              }
            : null;
    });
    ea("Promise", function (a) {
        function b() {
            this.h = null;
        }
        function c(g) {
            return g instanceof e
                ? g
                : new e(function (h) {
                      h(g);
                  });
        }
        if (a) return a;
        b.prototype.j = function (g) {
            if (null == this.h) {
                this.h = [];
                var h = this;
                this.l(function () {
                    h.A();
                });
            }
            this.h.push(g);
        };
        var d = da.setTimeout;
        b.prototype.l = function (g) {
            d(g, 0);
        };
        b.prototype.A = function () {
            for (; this.h && this.h.length; ) {
                var g = this.h;
                this.h = [];
                for (var h = 0; h < g.length; ++h) {
                    var k = g[h];
                    g[h] = null;
                    try {
                        k();
                    } catch (n) {
                        this.o(n);
                    }
                }
            }
            this.h = null;
        };
        b.prototype.o = function (g) {
            this.l(function () {
                throw g;
            });
        };
        var e = function (g) {
            this.h = 0;
            this.l = void 0;
            this.j = [];
            this.C = !1;
            var h = this.o();
            try {
                g(h.resolve, h.reject);
            } catch (k) {
                h.reject(k);
            }
        };
        e.prototype.o = function () {
            function g(n) {
                return function (m) {
                    k || ((k = !0), n.call(h, m));
                };
            }
            var h = this,
                k = !1;
            return { resolve: g(this.I), reject: g(this.A) };
        };
        e.prototype.I = function (g) {
            if (g === this) this.A(new TypeError("A Promise cannot resolve to itself"));
            else if (g instanceof e) this.L(g);
            else {
                a: switch (typeof g) {
                    case "object":
                        var h = null != g;
                        break a;
                    case "function":
                        h = !0;
                        break a;
                    default:
                        h = !1;
                }
                h ? this.G(g) : this.B(g);
            }
        };
        e.prototype.G = function (g) {
            var h = void 0;
            try {
                h = g.then;
            } catch (k) {
                this.A(k);
                return;
            }
            "function" == typeof h ? this.M(h, g) : this.B(g);
        };
        e.prototype.A = function (g) {
            this.H(2, g);
        };
        e.prototype.B = function (g) {
            this.H(1, g);
        };
        e.prototype.H = function (g, h) {
            if (0 != this.h) throw Error("Cannot settle(" + g + ", " + h + "): Promise already settled in state" + this.h);
            this.h = g;
            this.l = h;
            2 === this.h && this.K();
            this.J();
        };
        e.prototype.K = function () {
            var g = this;
            d(function () {
                if (g.D()) {
                    var h = da.console;
                    "undefined" !== typeof h && h.error(g.l);
                }
            }, 1);
        };
        e.prototype.D = function () {
            if (this.C) return !1;
            var g = da.CustomEvent,
                h = da.Event,
                k = da.dispatchEvent;
            if ("undefined" === typeof k) return !0;
            "function" === typeof g
                ? (g = new g("unhandledrejection", { cancelable: !0 }))
                : "function" === typeof h
                ? (g = new h("unhandledrejection", { cancelable: !0 }))
                : ((g = da.document.createEvent("CustomEvent")), g.initCustomEvent("unhandledrejection", !1, !0, g));
            g.promise = this;
            g.reason = this.l;
            return k(g);
        };
        e.prototype.J = function () {
            if (null != this.j) {
                for (var g = 0; g < this.j.length; ++g) f.j(this.j[g]);
                this.j = null;
            }
        };
        var f = new b();
        e.prototype.L = function (g) {
            var h = this.o();
            g.Qb(h.resolve, h.reject);
        };
        e.prototype.M = function (g, h) {
            var k = this.o();
            try {
                g.call(h, k.resolve, k.reject);
            } catch (n) {
                k.reject(n);
            }
        };
        e.prototype.then = function (g, h) {
            function k(v, A) {
                return "function" == typeof v
                    ? function (C) {
                          try {
                              n(v(C));
                          } catch (O) {
                              m(O);
                          }
                      }
                    : A;
            }
            var n,
                m,
                x = new e(function (v, A) {
                    n = v;
                    m = A;
                });
            this.Qb(k(g, n), k(h, m));
            return x;
        };
        e.prototype.catch = function (g) {
            return this.then(void 0, g);
        };
        e.prototype.Qb = function (g, h) {
            function k() {
                switch (n.h) {
                    case 1:
                        g(n.l);
                        break;
                    case 2:
                        h(n.l);
                        break;
                    default:
                        throw Error("Unexpected state: " + n.h);
                }
            }
            var n = this;
            null == this.j ? f.j(k) : this.j.push(k);
            this.C = !0;
        };
        e.resolve = c;
        e.reject = function (g) {
            return new e(function (h, k) {
                k(g);
            });
        };
        e.race = function (g) {
            return new e(function (h, k) {
                for (var n = q(g), m = n.next(); !m.done; m = n.next()) c(m.value).Qb(h, k);
            });
        };
        e.all = function (g) {
            var h = q(g),
                k = h.next();
            return k.done
                ? c([])
                : new e(function (n, m) {
                      function x(C) {
                          return function (O) {
                              v[C] = O;
                              A--;
                              0 == A && n(v);
                          };
                      }
                      var v = [],
                          A = 0;
                      do v.push(void 0), A++, c(k.value).Qb(x(v.length - 1), m), (k = h.next());
                      while (!k.done);
                  });
        };
        return e;
    });
    ea("Array.prototype.find", function (a) {
        return a
            ? a
            : function (b, c) {
                  a: {
                      var d = this;
                      d instanceof String && (d = String(d));
                      for (var e = d.length, f = 0; f < e; f++) {
                          var g = d[f];
                          if (b.call(c, g, f, d)) {
                              b = g;
                              break a;
                          }
                      }
                      b = void 0;
                  }
                  return b;
              };
    });
    ea("WeakMap", function (a) {
        function b() {}
        function c(k) {
            var n = typeof k;
            return ("object" === n && null !== k) || "function" === n;
        }
        function d(k) {
            if (!ia(k, f)) {
                var n = new b();
                ba(k, f, { value: n });
            }
        }
        function e(k) {
            var n = Object[k];
            n &&
                (Object[k] = function (m) {
                    if (m instanceof b) return m;
                    Object.isExtensible(m) && d(m);
                    return n(m);
                });
        }
        if (
            (function () {
                if (!a || !Object.seal) return !1;
                try {
                    var k = Object.seal({}),
                        n = Object.seal({}),
                        m = new a([
                            [k, 2],
                            [n, 3],
                        ]);
                    if (2 != m.get(k) || 3 != m.get(n)) return !1;
                    m.delete(k);
                    m.set(n, 4);
                    return !m.has(k) && 4 == m.get(n);
                } catch (x) {
                    return !1;
                }
            })()
        )
            return a;
        var f = "$jscomp_hidden_" + Math.random();
        e("freeze");
        e("preventExtensions");
        e("seal");
        var g = 0,
            h = function (k) {
                this.h = (g += Math.random() + 1).toString();
                if (k) {
                    k = q(k);
                    for (var n; !(n = k.next()).done; ) (n = n.value), this.set(n[0], n[1]);
                }
            };
        h.prototype.set = function (k, n) {
            if (!c(k)) throw Error("Invalid WeakMap key");
            d(k);
            if (!ia(k, f)) throw Error("WeakMap key fail: " + k);
            k[f][this.h] = n;
            return this;
        };
        h.prototype.get = function (k) {
            return c(k) && ia(k, f) ? k[f][this.h] : void 0;
        };
        h.prototype.has = function (k) {
            return c(k) && ia(k, f) && ia(k[f], this.h);
        };
        h.prototype.delete = function (k) {
            return c(k) && ia(k, f) && ia(k[f], this.h) ? delete k[f][this.h] : !1;
        };
        return h;
    });
    ea("Map", function (a) {
        if (
            (function () {
                if (!a || "function" != typeof a || !a.prototype.entries || "function" != typeof Object.seal) return !1;
                try {
                    var h = Object.seal({ x: 4 }),
                        k = new a(q([[h, "s"]]));
                    if ("s" != k.get(h) || 1 != k.size || k.get({ x: 4 }) || k.set({ x: 4 }, "t") != k || 2 != k.size) return !1;
                    var n = k.entries(),
                        m = n.next();
                    if (m.done || m.value[0] != h || "s" != m.value[1]) return !1;
                    m = n.next();
                    return m.done || 4 != m.value[0].x || "t" != m.value[1] || !n.next().done ? !1 : !0;
                } catch (x) {
                    return !1;
                }
            })()
        )
            return a;
        var b = new WeakMap(),
            c = function (h) {
                this.j = {};
                this.h = f();
                this.size = 0;
                if (h) {
                    h = q(h);
                    for (var k; !(k = h.next()).done; ) (k = k.value), this.set(k[0], k[1]);
                }
            };
        c.prototype.set = function (h, k) {
            h = 0 === h ? 0 : h;
            var n = d(this, h);
            n.list || (n.list = this.j[n.id] = []);
            n.entry ? (n.entry.value = k) : ((n.entry = { next: this.h, previous: this.h.previous, head: this.h, key: h, value: k }), n.list.push(n.entry), (this.h.previous.next = n.entry), (this.h.previous = n.entry), this.size++);
            return this;
        };
        c.prototype.delete = function (h) {
            h = d(this, h);
            return h.entry && h.list ? (h.list.splice(h.index, 1), h.list.length || delete this.j[h.id], (h.entry.previous.next = h.entry.next), (h.entry.next.previous = h.entry.previous), (h.entry.head = null), this.size--, !0) : !1;
        };
        c.prototype.clear = function () {
            this.j = {};
            this.h = this.h.previous = f();
            this.size = 0;
        };
        c.prototype.has = function (h) {
            return !!d(this, h).entry;
        };
        c.prototype.get = function (h) {
            return (h = d(this, h).entry) && h.value;
        };
        c.prototype.entries = function () {
            return e(this, function (h) {
                return [h.key, h.value];
            });
        };
        c.prototype.keys = function () {
            return e(this, function (h) {
                return h.key;
            });
        };
        c.prototype.values = function () {
            return e(this, function (h) {
                return h.value;
            });
        };
        c.prototype.forEach = function (h, k) {
            for (var n = this.entries(), m; !(m = n.next()).done; ) (m = m.value), h.call(k, m[1], m[0], this);
        };
        c.prototype[Symbol.iterator] = c.prototype.entries;
        var d = function (h, k) {
                var n = k && typeof k;
                "object" == n || "function" == n ? (b.has(k) ? (n = b.get(k)) : ((n = "" + ++g), b.set(k, n))) : (n = "p_" + k);
                var m = h.j[n];
                if (m && ia(h.j, n))
                    for (h = 0; h < m.length; h++) {
                        var x = m[h];
                        if ((k !== k && x.key !== x.key) || k === x.key) return { id: n, list: m, index: h, entry: x };
                    }
                return { id: n, list: m, index: -1, entry: void 0 };
            },
            e = function (h, k) {
                var n = h.h;
                return fa(function () {
                    if (n) {
                        for (; n.head != h.h; ) n = n.previous;
                        for (; n.next != n.head; ) return (n = n.next), { done: !1, value: k(n) };
                        n = null;
                    }
                    return { done: !0, value: void 0 };
                });
            },
            f = function () {
                var h = {};
                return (h.previous = h.next = h.head = h);
            },
            g = 0;
        return c;
    });
    ea("Object.setPrototypeOf", function (a) {
        return a || ta;
    });
    var Ia = function (a, b, c) {
        if (null == a) throw new TypeError("The 'this' value for String.prototype." + c + " must not be null or undefined");
        if (b instanceof RegExp) throw new TypeError("First argument to String.prototype." + c + " must not be a regular expression");
        return a + "";
    };
    ea("String.prototype.repeat", function (a) {
        return a
            ? a
            : function (b) {
                  var c = Ia(this, null, "repeat");
                  if (0 > b || 1342177279 < b) throw new RangeError("Invalid count value");
                  b |= 0;
                  for (var d = ""; b; ) if ((b & 1 && (d += c), (b >>>= 1))) c += c;
                  return d;
              };
    });
    var Ka = function (a, b) {
        a instanceof String && (a += "");
        var c = 0,
            d = !1,
            e = {
                next: function () {
                    if (!d && c < a.length) {
                        var f = c++;
                        return { value: b(f, a[f]), done: !1 };
                    }
                    d = !0;
                    return { done: !0, value: void 0 };
                },
            };
        e[Symbol.iterator] = function () {
            return e;
        };
        return e;
    };
    ea("Array.prototype.entries", function (a) {
        return a
            ? a
            : function () {
                  return Ka(this, function (b, c) {
                      return [b, c];
                  });
              };
    });
    ea("Set", function (a) {
        if (
            (function () {
                if (!a || "function" != typeof a || !a.prototype.entries || "function" != typeof Object.seal) return !1;
                try {
                    var c = Object.seal({ x: 4 }),
                        d = new a(q([c]));
                    if (!d.has(c) || 1 != d.size || d.add(c) != d || 1 != d.size || d.add({ x: 4 }) != d || 2 != d.size) return !1;
                    var e = d.entries(),
                        f = e.next();
                    if (f.done || f.value[0] != c || f.value[1] != c) return !1;
                    f = e.next();
                    return f.done || f.value[0] == c || 4 != f.value[0].x || f.value[1] != f.value[0] ? !1 : e.next().done;
                } catch (g) {
                    return !1;
                }
            })()
        )
            return a;
        var b = function (c) {
            this.h = new Map();
            if (c) {
                c = q(c);
                for (var d; !(d = c.next()).done; ) this.add(d.value);
            }
            this.size = this.h.size;
        };
        b.prototype.add = function (c) {
            c = 0 === c ? 0 : c;
            this.h.set(c, c);
            this.size = this.h.size;
            return this;
        };
        b.prototype.delete = function (c) {
            c = this.h.delete(c);
            this.size = this.h.size;
            return c;
        };
        b.prototype.clear = function () {
            this.h.clear();
            this.size = 0;
        };
        b.prototype.has = function (c) {
            return this.h.has(c);
        };
        b.prototype.entries = function () {
            return this.h.entries();
        };
        b.prototype.values = function () {
            return this.h.values();
        };
        b.prototype.keys = b.prototype.values;
        b.prototype[Symbol.iterator] = b.prototype.values;
        b.prototype.forEach = function (c, d) {
            var e = this;
            this.h.forEach(function (f) {
                return c.call(d, f, f, e);
            });
        };
        return b;
    });
    ea("Array.prototype.keys", function (a) {
        return a
            ? a
            : function () {
                  return Ka(this, function (b) {
                      return b;
                  });
              };
    });
    ea("Object.is", function (a) {
        return a
            ? a
            : function (b, c) {
                  return b === c ? 0 !== b || 1 / b === 1 / c : b !== b && c !== c;
              };
    });
    ea("Array.prototype.includes", function (a) {
        return a
            ? a
            : function (b, c) {
                  var d = this;
                  d instanceof String && (d = String(d));
                  var e = d.length;
                  c = c || 0;
                  for (0 > c && (c = Math.max(c + e, 0)); c < e; c++) {
                      var f = d[c];
                      if (f === b || Object.is(f, b)) return !0;
                  }
                  return !1;
              };
    });
    ea("String.prototype.includes", function (a) {
        return a
            ? a
            : function (b, c) {
                  return -1 !== Ia(this, b, "includes").indexOf(b, c || 0);
              };
    });
    ea("Array.from", function (a) {
        return a
            ? a
            : function (b, c, d) {
                  c =
                      null != c
                          ? c
                          : function (h) {
                                return h;
                            };
                  var e = [],
                      f = "undefined" != typeof Symbol && Symbol.iterator && b[Symbol.iterator];
                  if ("function" == typeof f) {
                      b = f.call(b);
                      for (var g = 0; !(f = b.next()).done; ) e.push(c.call(d, f.value, g++));
                  } else for (f = b.length, g = 0; g < f; g++) e.push(c.call(d, b[g], g));
                  return e;
              };
    });
    ea("Object.entries", function (a) {
        return a
            ? a
            : function (b) {
                  var c = [],
                      d;
                  for (d in b) ia(b, d) && c.push([d, b[d]]);
                  return c;
              };
    });
    ea("Math.trunc", function (a) {
        return a
            ? a
            : function (b) {
                  b = Number(b);
                  if (isNaN(b) || Infinity === b || -Infinity === b || 0 === b) return b;
                  var c = Math.floor(Math.abs(b));
                  return 0 > b ? -c : c;
              };
    });
    ea("Array.prototype.values", function (a) {
        return a
            ? a
            : function () {
                  return Ka(this, function (b, c) {
                      return c;
                  });
              };
    });
    ea("String.prototype.padStart", function (a) {
        return a
            ? a
            : function (b, c) {
                  var d = Ia(this, null, "padStart");
                  b -= d.length;
                  c = void 0 !== c ? String(c) : " ";
                  return (0 < b && c ? c.repeat(Math.ceil(b / c.length)).substring(0, b) : "") + d;
              };
    });
    ea("Math.imul", function (a) {
        return a
            ? a
            : function (b, c) {
                  b = Number(b);
                  c = Number(c);
                  var d = b & 65535,
                      e = c & 65535;
                  return (d * e + (((((b >>> 16) & 65535) * e + d * ((c >>> 16) & 65535)) << 16) >>> 0)) | 0;
              };
    });
    ea("Object.values", function (a) {
        return a
            ? a
            : function (b) {
                  var c = [],
                      d;
                  for (d in b) ia(b, d) && c.push(b[d]);
                  return c;
              };
    });
    var La = La || {},
        t = this || self,
        u = function (a, b, c) {
            a = a.split(".");
            c = c || t;
            a[0] in c || "undefined" == typeof c.execScript || c.execScript("var " + a[0]);
            for (var d; a.length && (d = a.shift()); ) a.length || void 0 === b ? (c[d] && c[d] !== Object.prototype[d] ? (c = c[d]) : (c = c[d] = {})) : (c[d] = b);
        },
        Ma = function (a, b) {
            a = a.split(".");
            b = b || t;
            for (var c = 0; c < a.length; c++) if (((b = b[a[c]]), null == b)) return null;
            return b;
        },
        Na = function () {},
        Oa = function (a) {
            var b = typeof a;
            return "object" != b ? b : a ? (Array.isArray(a) ? "array" : b) : "null";
        },
        Pa = function (a) {
            var b = Oa(a);
            return "array" == b || ("object" == b && "number" == typeof a.length);
        },
        Qa = function (a) {
            var b = typeof a;
            return ("object" == b && null != a) || "function" == b;
        },
        Ta = function (a) {
            return (Object.prototype.hasOwnProperty.call(a, Ra) && a[Ra]) || (a[Ra] = ++Sa);
        },
        Ua = function (a) {
            null !== a && "removeAttribute" in a && a.removeAttribute(Ra);
            try {
                delete a[Ra];
            } catch (b) {}
        },
        Ra = "closure_uid_" + ((1e9 * Math.random()) >>> 0),
        Sa = 0,
        Va = function (a, b, c) {
            return a.call.apply(a.bind, arguments);
        },
        Wa = function (a, b, c) {
            if (!a) throw Error();
            if (2 < arguments.length) {
                var d = Array.prototype.slice.call(arguments, 2);
                return function () {
                    var e = Array.prototype.slice.call(arguments);
                    Array.prototype.unshift.apply(e, d);
                    return a.apply(b, e);
                };
            }
            return function () {
                return a.apply(b, arguments);
            };
        },
        Xa = function (a, b, c) {
            Function.prototype.bind && -1 != Function.prototype.bind.toString().indexOf("native code") ? (Xa = Va) : (Xa = Wa);
            return Xa.apply(null, arguments);
        },
        Ya = function (a, b) {
            var c = Array.prototype.slice.call(arguments, 1);
            return function () {
                var d = c.slice();
                d.push.apply(d, arguments);
                return a.apply(this, d);
            };
        },
        Za = function () {
            return Date.now();
        },
        $a = function (a, b) {
            function c() {}
            c.prototype = b.prototype;
            a.ya = b.prototype;
            a.prototype = new c();
            a.prototype.constructor = a;
            a.Fh = function (d, e, f) {
                for (var g = Array(arguments.length - 2), h = 2; h < arguments.length; h++) g[h - 2] = arguments[h];
                return b.prototype[e].apply(d, g);
            };
        },
        ab = function (a) {
            return a;
        };
    function bb(a) {
        if (Error.captureStackTrace) Error.captureStackTrace(this, bb);
        else {
            var b = Error().stack;
            b && (this.stack = b);
        }
        a && (this.message = String(a));
    }
    $a(bb, Error);
    bb.prototype.name = "CustomError";
    var cb;
    var gb = function (a, b) {
        this.h = (a === eb && b) || "";
        this.j = fb;
    };
    gb.prototype.Ta = !0;
    gb.prototype.Ga = function () {
        return this.h;
    };
    var ib = function (a) {
            return a instanceof gb && a.constructor === gb && a.j === fb ? a.h : "type_error:Const";
        },
        jb = function (a) {
            return new gb(eb, a);
        },
        fb = {},
        eb = {};
    var kb = function (a, b) {
            var c = a.length - b.length;
            return 0 <= c && a.indexOf(b, c) == c;
        },
        lb = function (a) {
            return /^[\s\xa0]*$/.test(a);
        },
        nb = String.prototype.trim
            ? function (a) {
                  return a.trim();
              }
            : function (a) {
                  return /^[\s\xa0]*([\s\S]*?)[\s\xa0]*$/.exec(a)[1];
              },
        ob = /&/g,
        pb = /</g,
        qb = />/g,
        rb = /"/g,
        sb = /'/g,
        tb = /\x00/g,
        ub = /[\x00&<>"']/,
        vb = function (a, b) {
            return -1 != a.toLowerCase().indexOf(b.toLowerCase());
        },
        xb = function (a, b) {
            var c = 0;
            a = nb(String(a)).split(".");
            b = nb(String(b)).split(".");
            for (var d = Math.max(a.length, b.length), e = 0; 0 == c && e < d; e++) {
                var f = a[e] || "",
                    g = b[e] || "";
                do {
                    f = /(\d*)(\D*)(.*)/.exec(f) || ["", "", "", ""];
                    g = /(\d*)(\D*)(.*)/.exec(g) || ["", "", "", ""];
                    if (0 == f[0].length && 0 == g[0].length) break;
                    c = wb(0 == f[1].length ? 0 : parseInt(f[1], 10), 0 == g[1].length ? 0 : parseInt(g[1], 10)) || wb(0 == f[2].length, 0 == g[2].length) || wb(f[2], g[2]);
                    f = f[3];
                    g = g[3];
                } while (0 == c);
            }
            return c;
        },
        wb = function (a, b) {
            return a < b ? -1 : a > b ? 1 : 0;
        };
    function yb() {
        var a = t.navigator;
        return a && (a = a.userAgent) ? a : "";
    }
    function w(a) {
        return -1 != yb().indexOf(a);
    }
    function zb() {
        return w("Trident") || w("MSIE");
    }
    function Ab() {
        return w("Firefox") || w("FxiOS");
    }
    function Bb() {
        return w("Safari") && !(Cb() || w("Coast") || w("Opera") || w("Edge") || w("Edg/") || w("OPR") || Ab() || w("Silk") || w("Android"));
    }
    function Cb() {
        return ((w("Chrome") || w("CriOS")) && !w("Edge")) || w("Silk");
    }
    function Db() {
        return w("iPhone") && !w("iPod") && !w("iPad");
    }
    var Eb = function (a, b) {
            if ("string" === typeof a) return "string" !== typeof b || 1 != b.length ? -1 : a.indexOf(b, 0);
            for (var c = 0; c < a.length; c++) if (c in a && a[c] === b) return c;
            return -1;
        },
        Fb = function (a, b) {
            for (var c = a.length, d = "string" === typeof a ? a.split("") : a, e = 0; e < c; e++) e in d && b.call(void 0, d[e], e, a);
        };
    function Gb(a, b) {
        for (var c = "string" === typeof a ? a.split("") : a, d = a.length - 1; 0 <= d; --d) d in c && b.call(void 0, c[d], d, a);
    }
    var Hb = function (a, b) {
            for (var c = a.length, d = [], e = 0, f = "string" === typeof a ? a.split("") : a, g = 0; g < c; g++)
                if (g in f) {
                    var h = f[g];
                    b.call(void 0, h, g, a) && (d[e++] = h);
                }
            return d;
        },
        Ib = function (a, b) {
            for (var c = a.length, d = Array(c), e = "string" === typeof a ? a.split("") : a, f = 0; f < c; f++) f in e && (d[f] = b.call(void 0, e[f], f, a));
            return d;
        },
        Jb = function (a, b, c) {
            var d = c;
            Fb(a, function (e, f) {
                d = b.call(void 0, d, e, f, a);
            });
            return d;
        },
        Kb = function (a, b) {
            for (var c = a.length, d = "string" === typeof a ? a.split("") : a, e = 0; e < c; e++) if (e in d && b.call(void 0, d[e], e, a)) return !0;
            return !1;
        };
    function Lb(a, b) {
        b = Mb(a, b, void 0);
        return 0 > b ? null : "string" === typeof a ? a.charAt(b) : a[b];
    }
    function Mb(a, b, c) {
        for (var d = a.length, e = "string" === typeof a ? a.split("") : a, f = 0; f < d; f++) if (f in e && b.call(c, e[f], f, a)) return f;
        return -1;
    }
    function Nb(a, b) {
        for (var c = "string" === typeof a ? a.split("") : a, d = a.length - 1; 0 <= d; d--) if (d in c && b.call(void 0, c[d], d, a)) return d;
        return -1;
    }
    function Ob(a, b) {
        return 0 <= Eb(a, b);
    }
    function Pb(a, b) {
        b = Eb(a, b);
        var c;
        (c = 0 <= b) && Qb(a, b);
        return c;
    }
    function Qb(a, b) {
        return 1 == Array.prototype.splice.call(a, b, 1).length;
    }
    function Rb(a, b) {
        var c = 0;
        Gb(a, function (d, e) {
            b.call(void 0, d, e, a) && Qb(a, e) && c++;
        });
    }
    function Sb(a) {
        return Array.prototype.concat.apply([], arguments);
    }
    function Tb(a) {
        var b = a.length;
        if (0 < b) {
            for (var c = Array(b), d = 0; d < b; d++) c[d] = a[d];
            return c;
        }
        return [];
    }
    function Ub(a) {
        for (var b = 0, c = 0, d = {}; c < a.length; ) {
            var e = a[c++],
                f = Qa(e) ? "o" + Ta(e) : (typeof e).charAt(0) + e;
            Object.prototype.hasOwnProperty.call(d, f) || ((d[f] = !0), (a[b++] = e));
        }
        a.length = b;
    }
    function Vb(a, b) {
        a.sort(b || Wb);
    }
    function Wb(a, b) {
        return a > b ? 1 : a < b ? -1 : 0;
    }
    function Xb(a) {
        for (var b = [], c = 0; c < a; c++) b[c] = "";
        return b;
    }
    var Yb = function (a) {
        Yb[" "](a);
        return a;
    };
    Yb[" "] = Na;
    var Zb = function (a, b) {
            try {
                return Yb(a[b]), !0;
            } catch (c) {}
            return !1;
        },
        ac = function (a, b) {
            var c = $b;
            return Object.prototype.hasOwnProperty.call(c, a) ? c[a] : (c[a] = b(a));
        };
    var bc = w("Opera"),
        cc = zb(),
        dc = w("Edge"),
        ec = w("Gecko") && !(vb(yb(), "WebKit") && !w("Edge")) && !(w("Trident") || w("MSIE")) && !w("Edge"),
        fc = vb(yb(), "WebKit") && !w("Edge"),
        gc = w("Macintosh"),
        hc = w("Android"),
        ic = Db(),
        jc = w("iPad"),
        kc = w("iPod"),
        mc = Db() || w("iPad") || w("iPod"),
        nc = function () {
            var a = t.document;
            return a ? a.documentMode : void 0;
        },
        oc;
    a: {
        var pc = "",
            qc = (function () {
                var a = yb();
                if (ec) return /rv:([^\);]+)(\)|;)/.exec(a);
                if (dc) return /Edge\/([\d\.]+)/.exec(a);
                if (cc) return /\b(?:MSIE|rv)[: ]([^\);]+)(\)|;)/.exec(a);
                if (fc) return /WebKit\/(\S+)/.exec(a);
                if (bc) return /(?:Version)[ \/]?(\S+)/.exec(a);
            })();
        qc && (pc = qc ? qc[1] : "");
        if (cc) {
            var rc = nc();
            if (null != rc && rc > parseFloat(pc)) {
                oc = String(rc);
                break a;
            }
        }
        oc = pc;
    }
    var sc = oc,
        $b = {},
        tc = function (a) {
            return ac(a, function () {
                return 0 <= xb(sc, a);
            });
        },
        uc;
    if (t.document && cc) {
        var vc = nc();
        uc = vc ? vc : parseInt(sc, 10) || void 0;
    } else uc = void 0;
    var wc = uc;
    var xc = Ab(),
        yc = w("Android") && !(Cb() || Ab() || w("Opera") || w("Silk")),
        zc = Cb();
    Bb();
    var Ac = {},
        Bc = null,
        Dc = function (a, b) {
            void 0 === b && (b = 0);
            Cc();
            b = Ac[b];
            for (var c = Array(Math.floor(a.length / 3)), d = b[64] || "", e = 0, f = 0; e < a.length - 2; e += 3) {
                var g = a[e],
                    h = a[e + 1],
                    k = a[e + 2],
                    n = b[g >> 2];
                g = b[((g & 3) << 4) | (h >> 4)];
                h = b[((h & 15) << 2) | (k >> 6)];
                k = b[k & 63];
                c[f++] = "" + n + g + h + k;
            }
            n = 0;
            k = d;
            switch (a.length - e) {
                case 2:
                    (n = a[e + 1]), (k = b[(n & 15) << 2] || d);
                case 1:
                    (a = a[e]), (c[f] = "" + b[a >> 2] + b[((a & 3) << 4) | (n >> 4)] + k + d);
            }
            return c.join("");
        },
        Fc = function (a) {
            var b = [];
            Ec(a, function (c) {
                b.push(c);
            });
            return b;
        },
        Ec = function (a, b) {
            function c(k) {
                for (; d < a.length; ) {
                    var n = a.charAt(d++),
                        m = Bc[n];
                    if (null != m) return m;
                    if (!lb(n)) throw Error("Unknown base64 encoding at char: " + n);
                }
                return k;
            }
            Cc();
            for (var d = 0; ; ) {
                var e = c(-1),
                    f = c(0),
                    g = c(64),
                    h = c(64);
                if (64 === h && -1 === e) break;
                b((e << 2) | (f >> 4));
                64 != g && (b(((f << 4) & 240) | (g >> 2)), 64 != h && b(((g << 6) & 192) | h));
            }
        },
        Cc = function () {
            if (!Bc) {
                Bc = {};
                for (var a = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789".split(""), b = ["+/=", "+/", "-_=", "-_.", "-_"], c = 0; 5 > c; c++) {
                    var d = a.concat(b[c].split(""));
                    Ac[c] = d;
                    for (var e = 0; e < d.length; e++) {
                        var f = d[e];
                        void 0 === Bc[f] && (Bc[f] = e);
                    }
                }
            }
        };
    var Hc = "undefined" !== typeof Uint8Array,
        Ic,
        Jc = {};
    var Kc = 0,
        Lc = 0;
    function Mc(a) {
        var b = 0 > a;
        a = Math.abs(a);
        var c = a >>> 0;
        a = Math.floor((a - c) / 4294967296);
        a >>>= 0;
        b && ((a = ~a >>> 0), (c = (~c >>> 0) + 1), 4294967295 < c && ((c = 0), a++, 4294967295 < a && (a = 0)));
        Kc = c;
        Lc = a;
    }
    function Nc(a) {
        var b = +("-" === a[0]);
        Lc = Kc = 0;
        for (var c = a.length, d = 0 + b, e = ((c - b) % 6) + b; e <= c; d = e, e += 6) (d = Number(a.slice(d, e))), (Lc *= 1e6), (Kc = 1e6 * Kc + d), 4294967296 <= Kc && ((Lc += (Kc / 4294967296) | 0), (Kc %= 4294967296));
        b && ((a = Kc), (b = Lc), (b = ~b), a ? (a = ~a + 1) : (b += 1), (b = q([a, b])), (a = b.next().value), (b = b.next().value), (Kc = a), (Lc = b));
    }
    var Oc = function (a, b) {
            this.j = a >>> 0;
            this.h = b >>> 0;
        },
        Qc = function (a) {
            if (!a) return Pc || (Pc = new Oc(0, 0));
            if (!/^\d+$/.test(a)) return null;
            Nc(a);
            return new Oc(Kc, Lc);
        },
        Pc,
        Rc = function (a, b) {
            this.j = a >>> 0;
            this.h = b >>> 0;
        },
        Tc = function (a) {
            if (!a) return Sc || (Sc = new Rc(0, 0));
            if (!/^-?\d+$/.test(a)) return null;
            Nc(a);
            return new Rc(Kc, Lc);
        },
        Sc;
    var Uc = function () {
        this.h = [];
    };
    Uc.prototype.length = function () {
        return this.h.length;
    };
    Uc.prototype.end = function () {
        var a = this.h;
        this.h = [];
        return a;
    };
    var Vc = function (a, b, c) {
            for (; 0 < c || 127 < b; ) a.h.push((b & 127) | 128), (b = ((b >>> 7) | (c << 25)) >>> 0), (c >>>= 7);
            a.h.push(b);
        },
        Wc = function (a, b) {
            for (; 127 < b; ) a.h.push((b & 127) | 128), (b >>>= 7);
            a.h.push(b);
        },
        Xc = function (a, b) {
            if (0 <= b) Wc(a, b);
            else {
                for (var c = 0; 9 > c; c++) a.h.push((b & 127) | 128), (b >>= 7);
                a.h.push(1);
            }
        },
        Yc = function (a, b) {
            a.h.push((b >>> 0) & 255);
            a.h.push((b >>> 8) & 255);
            a.h.push((b >>> 16) & 255);
            a.h.push((b >>> 24) & 255);
        };
    var Zc,
        $c = "undefined" !== typeof TextEncoder;
    var ad = function () {
            this.l = [];
            this.j = 0;
            this.h = new Uc();
        },
        bd = function (a, b) {
            0 !== b.length && (a.l.push(b), (a.j += b.length));
        },
        dd = function (a, b) {
            cd(a, b, 2);
            b = a.h.end();
            bd(a, b);
            b.push(a.j);
            return b;
        },
        ed = function (a, b) {
            var c = b.pop();
            for (c = a.j + a.h.length() - c; 127 < c; ) b.push((c & 127) | 128), (c >>>= 7), a.j++;
            b.push(c);
            a.j++;
        },
        fd = function (a, b) {
            if ((b = b.o)) {
                bd(a, a.h.end());
                for (var c = 0; c < b.length; c++) bd(a, b[c].Ih(Jc) || Ic || (Ic = new Uint8Array(0)));
            }
        },
        cd = function (a, b, c) {
            Wc(a.h, 8 * b + c);
        };
    var gd = function (a, b) {
            this.flag = a;
            this.defaultValue = void 0 === b ? !1 : b;
        },
        hd = function (a, b) {
            this.flag = a;
            this.defaultValue = void 0 === b ? 0 : b;
        };
    var id = new gd(1930),
        jd = new hd(360261971),
        kd = new hd(1921, 72),
        ld = new hd(1920, 24),
        md = new hd(426169222, 1e3),
        nd = new hd(1917, 300),
        od = new hd(1916, 0.001),
        pd = new gd(1954, !0),
        qd = new gd(434462125),
        rd = new gd(1948, !0),
        sd = new gd(370946349),
        td = new hd(406149835),
        ud = new (function () {
            var a = [
                "AxujKG9INjsZ8/gUq8+dTruNvk7RjZQ1oFhhgQbcTJKDnZfbzSTE81wvC2Hzaf3TW4avA76LTZEMdiedF1vIbA4AAABueyJvcmlnaW4iOiJodHRwczovL2ltYXNkay5nb29nbGVhcGlzLmNvbTo0NDMiLCJmZWF0dXJlIjoiVHJ1c3RUb2tlbnMiLCJleHBpcnkiOjE2NTI3NzQ0MDAsImlzVGhpcmRQYXJ0eSI6dHJ1ZX0=",
                "Azuce85ORtSnWe1MZDTv68qpaW3iHyfL9YbLRy0cwcCZwVnePnOmkUJlG8HGikmOwhZU22dElCcfrfX2HhrBPAkAAAB7eyJvcmlnaW4iOiJodHRwczovL2RvdWJsZWNsaWNrLm5ldDo0NDMiLCJmZWF0dXJlIjoiVHJ1c3RUb2tlbnMiLCJleHBpcnkiOjE2NTI3NzQ0MDAsImlzU3ViZG9tYWluIjp0cnVlLCJpc1RoaXJkUGFydHkiOnRydWV9",
                "A16nvcdeoOAqrJcmjLRpl1I6f3McDD8EfofAYTt/P/H4/AWwB99nxiPp6kA0fXoiZav908Z8etuL16laFPUdfQsAAACBeyJvcmlnaW4iOiJodHRwczovL2dvb2dsZXRhZ3NlcnZpY2VzLmNvbTo0NDMiLCJmZWF0dXJlIjoiVHJ1c3RUb2tlbnMiLCJleHBpcnkiOjE2NTI3NzQ0MDAsImlzU3ViZG9tYWluIjp0cnVlLCJpc1RoaXJkUGFydHkiOnRydWV9",
                "AxBHdr0J44vFBQtZUqX9sjiqf5yWZ/OcHRcRMN3H9TH+t90V/j3ENW6C8+igBZFXMJ7G3Pr8Dd13632aLng42wgAAACBeyJvcmlnaW4iOiJodHRwczovL2dvb2dsZXN5bmRpY2F0aW9uLmNvbTo0NDMiLCJmZWF0dXJlIjoiVHJ1c3RUb2tlbnMiLCJleHBpcnkiOjE2NTI3NzQ0MDAsImlzU3ViZG9tYWluIjp0cnVlLCJpc1RoaXJkUGFydHkiOnRydWV9",
                "A88BWHFjcawUfKU3lIejLoryXoyjooBXLgWmGh+hNcqMK44cugvsI5YZbNarYvi3roc1fYbHA1AVbhAtuHZflgEAAAB2eyJvcmlnaW4iOiJodHRwczovL2dvb2dsZS5jb206NDQzIiwiZmVhdHVyZSI6IlRydXN0VG9rZW5zIiwiZXhwaXJ5IjoxNjUyNzc0NDAwLCJpc1N1YmRvbWFpbiI6dHJ1ZSwiaXNUaGlyZFBhcnR5Ijp0cnVlfQ==",
            ];
            a = void 0 === a ? [] : a;
            this.flag = 1932;
            this.defaultValue = a;
        })();
    var vd = function () {
        var a;
        this.h = a = void 0 === a ? {} : a;
    };
    vd.prototype.reset = function () {
        this.h = {};
    };
    function wd(a, b, c) {
        for (var d in a) b.call(c, a[d], d, a);
    }
    function xd(a, b) {
        var c = {},
            d;
        for (d in a) b.call(void 0, a[d], d, a) && (c[d] = a[d]);
        return c;
    }
    function yd(a) {
        var b = zd,
            c;
        for (c in b) if (a.call(void 0, b[c], c, b)) return !0;
        return !1;
    }
    function Ad(a) {
        var b = Bd,
            c;
        for (c in b) if (!a.call(void 0, b[c], c, b)) return !1;
        return !0;
    }
    function Cd(a) {
        var b = [],
            c = 0,
            d;
        for (d in a) b[c++] = a[d];
        return b;
    }
    function Dd(a) {
        var b = [],
            c = 0,
            d;
        for (d in a) b[c++] = d;
        return b;
    }
    function Ed(a, b) {
        var c = Pa(b),
            d = c ? b : arguments;
        for (c = c ? 0 : 1; c < d.length; c++) {
            if (null == a) return;
            a = a[d[c]];
        }
        return a;
    }
    function Fd(a, b) {
        return null !== a && b in a;
    }
    function Gd(a, b) {
        for (var c in a) if (a[c] == b) return !0;
        return !1;
    }
    function Hd(a) {
        var b = Id,
            c;
        for (c in b) if (a.call(void 0, b[c], c, b)) return c;
    }
    function Jd(a) {
        for (var b in a) return !1;
        return !0;
    }
    function Kd(a) {
        for (var b in a) delete a[b];
    }
    function Ld(a, b, c) {
        return null !== a && b in a ? a[b] : c;
    }
    var Md = "constructor hasOwnProperty isPrototypeOf propertyIsEnumerable toLocaleString toString valueOf".split(" ");
    function Nd(a, b) {
        for (var c, d, e = 1; e < arguments.length; e++) {
            d = arguments[e];
            for (c in d) a[c] = d[c];
            for (var f = 0; f < Md.length; f++) (c = Md[f]), Object.prototype.hasOwnProperty.call(d, c) && (a[c] = d[c]);
        }
    }
    var Od,
        Pd = function () {
            if (void 0 === Od) {
                var a = null,
                    b = t.trustedTypes;
                if (b && b.createPolicy) {
                    try {
                        a = b.createPolicy("goog#html", { createHTML: ab, createScript: ab, createScriptURL: ab });
                    } catch (c) {
                        t.console && t.console.error(c.message);
                    }
                    Od = a;
                } else Od = a;
            }
            return Od;
        };
    var Rd = function (a, b) {
        this.h = b === Qd ? a : "";
    };
    l = Rd.prototype;
    l.Ta = !0;
    l.Ga = function () {
        return this.h.toString();
    };
    l.Hc = !0;
    l.Dc = function () {
        return 1;
    };
    l.toString = function () {
        return this.h + "";
    };
    var Sd = function (a) {
            return a instanceof Rd && a.constructor === Rd ? a.h : "type_error:TrustedResourceUrl";
        },
        Qd = {},
        Td = function (a) {
            var b = Pd();
            a = b ? b.createScriptURL(a) : a;
            return new Rd(a, Qd);
        };
    var Vd = function (a, b) {
        this.h = b === Ud ? a : "";
    };
    l = Vd.prototype;
    l.Ta = !0;
    l.Ga = function () {
        return this.h.toString();
    };
    l.Hc = !0;
    l.Dc = function () {
        return 1;
    };
    l.toString = function () {
        return this.h.toString();
    };
    var Wd = function (a) {
            return a instanceof Vd && a.constructor === Vd ? a.h : "type_error:SafeUrl";
        },
        Xd = /^data:(.*);base64,[a-z0-9+\/]+=*$/i,
        Yd = /^(?:(?:https?|mailto|ftp):|[^:/?#]*(?:[/?#]|$))/i,
        Ud = {},
        Zd = new Vd("about:invalid#zClosurez", Ud);
    var $d = {},
        ae = function (a, b) {
            this.h = b === $d ? a : "";
            this.Ta = !0;
        };
    ae.prototype.Ga = function () {
        return this.h;
    };
    ae.prototype.toString = function () {
        return this.h.toString();
    };
    var be = new ae("", $d);
    var ce = {},
        de = function (a, b, c) {
            this.h = c === ce ? a : "";
            this.j = b;
            this.Ta = this.Hc = !0;
        };
    de.prototype.Dc = function () {
        return this.j;
    };
    de.prototype.Ga = function () {
        return this.h.toString();
    };
    de.prototype.toString = function () {
        return this.h.toString();
    };
    var ee = function (a) {
            return a instanceof de && a.constructor === de ? a.h : "type_error:SafeHtml";
        },
        fe = function (a, b) {
            var c = Pd();
            a = c ? c.createHTML(a) : a;
            return new de(a, b, ce);
        }; /*

 SPDX-License-Identifier: Apache-2.0
*/
    var ge = {};
    var he = function () {},
        ie = function (a) {
            this.h = a;
        };
    r(ie, he);
    ie.prototype.toString = function () {
        return this.h.toString();
    };
    var je = function () {},
        ke = function (a) {
            this.h = a;
        };
    r(ke, je);
    ke.prototype.toString = function () {
        return this.h.toString();
    };
    function le(a) {
        var b,
            c = null == (b = Pd()) ? void 0 : b.createScriptURL(a);
        return new ke(null != c ? c : a, ge);
    }
    function me(a) {
        if (a instanceof ke) return a.h;
        throw Error("");
    }
    function ne(a) {
        a = me(a);
        var b = "undefined" !== typeof window ? window.trustedTypes : void 0;
        b = null != b ? b : null;
        return (null == b ? 0 : b.isScriptURL(a)) ? TrustedScriptURL.prototype.toString.apply(a) : a;
    }
    var oe = function () {},
        pe = function (a) {
            this.h = a;
        };
    r(pe, oe);
    pe.prototype.toString = function () {
        return this.h;
    };
    var qe = new pe("about:invalid#zTSz", ge);
    function re(a) {
        if (a instanceof he)
            if (a instanceof ie) a = a.h;
            else throw Error("");
        else a = ee(a);
        return a;
    }
    function se(a) {
        return a instanceof je ? me(a) : Sd(a);
    }
    function te(a) {
        if (a instanceof oe)
            if (a instanceof pe) a = a.h;
            else throw Error("");
        else a = Wd(a);
        return a;
    }
    function ue(a, b) {
        if (void 0 !== a.tagName) {
            if ("script" === a.tagName.toLowerCase()) throw Error("Use setTextContent with a SafeScript.");
            if ("style" === a.tagName.toLowerCase()) throw Error("Use setTextContent with a SafeStyleSheet.");
        }
        a.innerHTML = re(b);
    }
    function ve(a) {
        var b,
            c,
            d = null == (c = (b = ((a.ownerDocument && a.ownerDocument.defaultView) || window).document).querySelector) ? void 0 : c.call(b, "script[nonce]");
        (b = d ? d.nonce || d.getAttribute("nonce") || "" : "") && a.setAttribute("nonce", b);
    }
    function we(a, b) {
        a.write(re(b));
    }
    var xe = function () {},
        ye = function (a) {
            var b = !1,
                c;
            return function () {
                b || ((c = a()), (b = !0));
                return c;
            };
        },
        ze = function (a) {
            var b = a;
            return function () {
                if (b) {
                    var c = b;
                    b = null;
                    c();
                }
            };
        },
        Ae = function (a) {
            var b = 0,
                c = !1,
                d = [],
                e = function () {
                    b = 0;
                    c && ((c = !1), f());
                },
                f = function () {
                    b = t.setTimeout(e, 1e3);
                    var g = d;
                    d = [];
                    a.apply(void 0, g);
                };
            return function (g) {
                d = arguments;
                b ? (c = !0) : f();
            };
        };
    var Be = ye(function () {
        var a = !1;
        try {
            var b = Object.defineProperty({}, "passive", {
                get: function () {
                    a = !0;
                },
            });
            t.addEventListener("test", null, b);
        } catch (c) {}
        return a;
    });
    function Ce(a) {
        return a ? (a.passive && Be() ? a : a.capture || !1) : !1;
    }
    var De = function (a, b, c, d) {
            return a.addEventListener ? (a.addEventListener(b, c, Ce(d)), !0) : !1;
        },
        Ee = function (a, b, c) {
            a.removeEventListener && a.removeEventListener(b, c, Ce(void 0));
        },
        Fe = function (a) {
            var b = void 0 === b ? {} : b;
            if ("function" === typeof window.CustomEvent) var c = new CustomEvent("rum_blp", b);
            else (c = document.createEvent("CustomEvent")), c.initCustomEvent("rum_blp", !!b.bubbles, !!b.cancelable, b.detail);
            a.dispatchEvent(c);
        };
    var Ge = cc || fc;
    var He = /^[\w+/_-]+[=]{0,2}$/,
        Ie = function (a, b) {
            b = (b || t).document;
            return b.querySelector ? ((a = b.querySelector(a)) && (a = a.nonce || a.getAttribute("nonce")) && He.test(a) ? a : "") : "";
        };
    var Je = function (a, b) {
        this.x = void 0 !== a ? a : 0;
        this.y = void 0 !== b ? b : 0;
    };
    Je.prototype.ceil = function () {
        this.x = Math.ceil(this.x);
        this.y = Math.ceil(this.y);
        return this;
    };
    Je.prototype.floor = function () {
        this.x = Math.floor(this.x);
        this.y = Math.floor(this.y);
        return this;
    };
    Je.prototype.round = function () {
        this.x = Math.round(this.x);
        this.y = Math.round(this.y);
        return this;
    };
    Je.prototype.scale = function (a, b) {
        this.x *= a;
        this.y *= "number" === typeof b ? b : a;
        return this;
    };
    var y = function (a, b) {
        this.width = a;
        this.height = b;
    };
    l = y.prototype;
    l.aspectRatio = function () {
        return this.width / this.height;
    };
    l.isEmpty = function () {
        return !(this.width * this.height);
    };
    l.ceil = function () {
        this.width = Math.ceil(this.width);
        this.height = Math.ceil(this.height);
        return this;
    };
    l.floor = function () {
        this.width = Math.floor(this.width);
        this.height = Math.floor(this.height);
        return this;
    };
    l.round = function () {
        this.width = Math.round(this.width);
        this.height = Math.round(this.height);
        return this;
    };
    l.scale = function (a, b) {
        this.width *= a;
        this.height *= "number" === typeof b ? b : a;
        return this;
    };
    var Ke = function (a) {
            return decodeURIComponent(a.replace(/\+/g, " "));
        },
        Le = function (a, b) {
            a.length > b && (a = a.substring(0, b - 3) + "...");
            return a;
        },
        Me = String.prototype.repeat
            ? function (a, b) {
                  return a.repeat(b);
              }
            : function (a, b) {
                  return Array(b + 1).join(a);
              },
        Ne = function (a) {
            return null == a ? "" : String(a);
        },
        Oe = (2147483648 * Math.random()) | 0,
        Pe = function (a) {
            return String(a).replace(/\-([a-z])/g, function (b, c) {
                return c.toUpperCase();
            });
        },
        Qe = function () {
            return "googleAvInapp".replace(/([A-Z])/g, "-$1").toLowerCase();
        },
        Re = function (a) {
            return a.replace(RegExp("(^|[\\s]+)([a-z])", "g"), function (b, c, d) {
                return c + d.toUpperCase();
            });
        },
        Se = function (a) {
            isFinite(a) && (a = String(a));
            return "string" === typeof a ? (/^\s*-?0x/i.test(a) ? parseInt(a, 16) : parseInt(a, 10)) : NaN;
        };
    var Ve = function (a) {
            return a ? new Te(Ue(a)) : cb || (cb = new Te());
        },
        We = function (a) {
            var b = document;
            return "string" === typeof a ? b.getElementById(a) : a;
        },
        Xe = function () {
            var a = document;
            return a.querySelectorAll && a.querySelector ? a.querySelectorAll("SCRIPT") : a.getElementsByTagName("SCRIPT");
        },
        Ze = function (a, b) {
            wd(b, function (c, d) {
                c && "object" == typeof c && c.Ta && (c = c.Ga());
                "style" == d
                    ? (a.style.cssText = c)
                    : "class" == d
                    ? (a.className = c)
                    : "for" == d
                    ? (a.htmlFor = c)
                    : Ye.hasOwnProperty(d)
                    ? a.setAttribute(Ye[d], c)
                    : 0 == d.lastIndexOf("aria-", 0) || 0 == d.lastIndexOf("data-", 0)
                    ? a.setAttribute(d, c)
                    : (a[d] = c);
            });
        },
        Ye = {
            cellpadding: "cellPadding",
            cellspacing: "cellSpacing",
            colspan: "colSpan",
            frameborder: "frameBorder",
            height: "height",
            maxlength: "maxLength",
            nonce: "nonce",
            role: "role",
            rowspan: "rowSpan",
            type: "type",
            usemap: "useMap",
            valign: "vAlign",
            width: "width",
        },
        af = function (a) {
            a = a.document;
            a = $e(a) ? a.documentElement : a.body;
            return new y(a.clientWidth, a.clientHeight);
        },
        bf = function (a) {
            var b = a.scrollingElement ? a.scrollingElement : !fc && $e(a) ? a.documentElement : a.body || a.documentElement;
            a = a.parentWindow || a.defaultView;
            return cc && tc("10") && a.pageYOffset != b.scrollTop ? new Je(b.scrollLeft, b.scrollTop) : new Je(a.pageXOffset || b.scrollLeft, a.pageYOffset || b.scrollTop);
        },
        z = function (a) {
            return a ? a.parentWindow || a.defaultView : window;
        },
        ef = function (a, b, c) {
            var d = arguments,
                e = document,
                f = d[1],
                g = cf(e, String(d[0]));
            f && ("string" === typeof f ? (g.className = f) : Array.isArray(f) ? (g.className = f.join(" ")) : Ze(g, f));
            2 < d.length && df(e, g, d, 2);
            return g;
        },
        df = function (a, b, c, d) {
            function e(h) {
                h && b.appendChild("string" === typeof h ? a.createTextNode(h) : h);
            }
            for (; d < c.length; d++) {
                var f = c[d];
                if (!Pa(f) || (Qa(f) && 0 < f.nodeType)) e(f);
                else {
                    a: {
                        if (f && "number" == typeof f.length) {
                            if (Qa(f)) {
                                var g = "function" == typeof f.item || "string" == typeof f.item;
                                break a;
                            }
                            if ("function" === typeof f) {
                                g = "function" == typeof f.item;
                                break a;
                            }
                        }
                        g = !1;
                    }
                    Fb(g ? Tb(f) : f, e);
                }
            }
        },
        cf = function (a, b) {
            b = String(b);
            "application/xhtml+xml" === a.contentType && (b = b.toLowerCase());
            return a.createElement(b);
        },
        $e = function (a) {
            return "CSS1Compat" == a.compatMode;
        },
        ff = function (a) {
            a && a.parentNode && a.parentNode.removeChild(a);
        },
        gf = function (a) {
            var b;
            if (Ge && !(cc && tc("9") && !tc("10") && t.SVGElement && a instanceof t.SVGElement) && (b = a.parentElement)) return b;
            b = a.parentNode;
            return Qa(b) && 1 == b.nodeType ? b : null;
        },
        hf = function (a, b) {
            if (!a || !b) return !1;
            if (a.contains && 1 == b.nodeType) return a == b || a.contains(b);
            if ("undefined" != typeof a.compareDocumentPosition) return a == b || !!(a.compareDocumentPosition(b) & 16);
            for (; b && a != b; ) b = b.parentNode;
            return b == a;
        },
        Ue = function (a) {
            return 9 == a.nodeType ? a : a.ownerDocument || a.document;
        },
        jf = function (a) {
            try {
                return a.contentWindow || (a.contentDocument ? z(a.contentDocument) : null);
            } catch (b) {}
            return null;
        },
        kf = function (a, b) {
            a && (a = a.parentNode);
            for (var c = 0; a; ) {
                if (b(a)) return a;
                a = a.parentNode;
                c++;
            }
            return null;
        },
        Te = function (a) {
            this.h = a || t.document || document;
        };
    Te.prototype.getElementsByTagName = function (a, b) {
        return (b || this.h).getElementsByTagName(String(a));
    };
    Te.prototype.appendChild = function (a, b) {
        a.appendChild(b);
    };
    Te.prototype.append = function (a, b) {
        df(Ue(a), a, arguments, 1);
    };
    Te.prototype.canHaveChildren = function (a) {
        if (1 != a.nodeType) return !1;
        switch (a.tagName) {
            case "APPLET":
            case "AREA":
            case "BASE":
            case "BR":
            case "COL":
            case "COMMAND":
            case "EMBED":
            case "FRAME":
            case "HR":
            case "IMG":
            case "INPUT":
            case "IFRAME":
            case "ISINDEX":
            case "KEYGEN":
            case "LINK":
            case "NOFRAMES":
            case "NOSCRIPT":
            case "META":
            case "OBJECT":
            case "PARAM":
            case "SCRIPT":
            case "SOURCE":
            case "STYLE":
            case "TRACK":
            case "WBR":
                return !1;
        }
        return !0;
    };
    var mf = function () {
            return !lf() && (w("iPod") || w("iPhone") || w("Android") || w("IEMobile"));
        },
        lf = function () {
            return w("iPad") || (w("Android") && !w("Mobile")) || w("Silk");
        };
    var nf = RegExp("^(?:([^:/?#.]+):)?(?://(?:([^\\\\/?#]*)@)?([^\\\\/?#]*?)(?::([0-9]+))?(?=[\\\\/?#]|$))?([^?#]+)?(?:\\?([^#]*))?(?:#([\\s\\S]*))?$"),
        of = function (a) {
            var b = a.match(nf);
            a = b[1];
            var c = b[3];
            b = b[4];
            var d = "";
            a && (d += a + ":");
            c && ((d = d + "//" + c), b && (d += ":" + b));
            return d;
        },
        pf = function (a, b) {
            if (a) {
                a = a.split("&");
                for (var c = 0; c < a.length; c++) {
                    var d = a[c].indexOf("="),
                        e = null;
                    if (0 <= d) {
                        var f = a[c].substring(0, d);
                        e = a[c].substring(d + 1);
                    } else f = a[c];
                    b(f, e ? Ke(e) : "");
                }
            }
        },
        qf = /#|$/,
        rf = function (a, b) {
            var c = a.search(qf);
            a: {
                var d = 0;
                for (var e = b.length; 0 <= (d = a.indexOf(b, d)) && d < c; ) {
                    var f = a.charCodeAt(d - 1);
                    if (38 == f || 63 == f) if (((f = a.charCodeAt(d + e)), !f || 61 == f || 38 == f || 35 == f)) break a;
                    d += e + 1;
                }
                d = -1;
            }
            if (0 > d) return null;
            e = a.indexOf("&", d);
            if (0 > e || e > c) e = c;
            d += b.length + 1;
            return Ke(a.substr(d, e - d));
        };
    var sf = function (a) {
            try {
                return !!a && null != a.location.href && Zb(a, "foo");
            } catch (b) {
                return !1;
            }
        },
        uf = function (a) {
            var b = void 0 === b ? !1 : b;
            var c = void 0 === c ? t : c;
            for (var d = 0; c && 40 > d++ && ((!b && !sf(c)) || !a(c)); ) c = tf(c);
        },
        vf = function () {
            var a,
                b = (a = void 0 === a ? t : a);
            uf(function (c) {
                b = c;
                return !1;
            });
            return b;
        },
        tf = function (a) {
            try {
                var b = a.parent;
                if (b && b != a) return b;
            } catch (c) {}
            return null;
        },
        wf = function (a, b) {
            if (a) for (var c in a) Object.prototype.hasOwnProperty.call(a, c) && b(a[c], c, a);
        },
        xf = /https?:\/\/[^\/]+/,
        yf = function (a) {
            return ((a = xf.exec(a)) && a[0]) || "";
        },
        zf = function () {
            var a = t;
            var b = void 0 === b ? !0 : b;
            try {
                for (var c = null; c != a; c = a, a = a.parent)
                    switch (a.location.protocol) {
                        case "https:":
                            return !0;
                        case "file:":
                            return b;
                        case "http:":
                            return !1;
                    }
            } catch (d) {}
            return !0;
        },
        Bf = function () {
            var a = Af;
            if (!a) return "";
            var b = RegExp(".*[&#?]google_debug(=[^&]*)?(&.*)?$");
            try {
                var c = b.exec(decodeURIComponent(a));
                if (c) return c[1] && 1 < c[1].length ? c[1].substring(1) : "true";
            } catch (d) {}
            return "";
        },
        Cf = function (a, b) {
            try {
                return !(!a.frames || !a.frames[b]);
            } catch (c) {
                return !1;
            }
        },
        Df = function (a, b) {
            for (var c = 0; 50 > c; ++c) {
                if (Cf(a, b)) return a;
                if (!(a = tf(a))) break;
            }
            return null;
        },
        Ff = function (a, b) {
            0 != a.length &&
                b.head &&
                a.forEach(function (c) {
                    if (c && c && b.head) {
                        var d = Ef("META");
                        b.head.appendChild(d);
                        d.httpEquiv = "origin-trial";
                        d.content = c;
                    }
                });
        },
        Gf = function () {
            var a = window;
            if ("number" !== typeof a.goog_pvsid)
                try {
                    Object.defineProperty(a, "goog_pvsid", { value: Math.floor(Math.random() * Math.pow(2, 52)), configurable: !1 });
                } catch (b) {}
            return Number(a.goog_pvsid) || -1;
        },
        Ef = function (a, b) {
            b = void 0 === b ? document : b;
            return b.createElement(String(a).toLowerCase());
        },
        Hf = function (a) {
            for (var b = a; a && a != a.parent; ) (a = a.parent), sf(a) && (b = a);
            return b;
        };
    var B = function (a, b, c, d) {
        this.top = a;
        this.right = b;
        this.bottom = c;
        this.left = d;
    };
    B.prototype.getWidth = function () {
        return this.right - this.left;
    };
    B.prototype.getHeight = function () {
        return this.bottom - this.top;
    };
    var If = function (a) {
        return new B(a.top, a.right, a.bottom, a.left);
    };
    B.prototype.expand = function (a, b, c, d) {
        Qa(a) ? ((this.top -= a.top), (this.right += a.right), (this.bottom += a.bottom), (this.left -= a.left)) : ((this.top -= a), (this.right += Number(b)), (this.bottom += Number(c)), (this.left -= Number(d)));
        return this;
    };
    B.prototype.ceil = function () {
        this.top = Math.ceil(this.top);
        this.right = Math.ceil(this.right);
        this.bottom = Math.ceil(this.bottom);
        this.left = Math.ceil(this.left);
        return this;
    };
    B.prototype.floor = function () {
        this.top = Math.floor(this.top);
        this.right = Math.floor(this.right);
        this.bottom = Math.floor(this.bottom);
        this.left = Math.floor(this.left);
        return this;
    };
    B.prototype.round = function () {
        this.top = Math.round(this.top);
        this.right = Math.round(this.right);
        this.bottom = Math.round(this.bottom);
        this.left = Math.round(this.left);
        return this;
    };
    var Jf = function (a, b, c) {
        b instanceof Je ? ((a.left += b.x), (a.right += b.x), (a.top += b.y), (a.bottom += b.y)) : ((a.left += b), (a.right += b), "number" === typeof c && ((a.top += c), (a.bottom += c)));
        return a;
    };
    B.prototype.scale = function (a, b) {
        b = "number" === typeof b ? b : a;
        this.left *= a;
        this.right *= a;
        this.top *= b;
        this.bottom *= b;
        return this;
    };
    var Kf = function (a, b, c, d) {
            this.left = a;
            this.top = b;
            this.width = c;
            this.height = d;
        },
        Lf = function (a) {
            return new B(a.top, a.left + a.width, a.top + a.height, a.left);
        };
    l = Kf.prototype;
    l.distance = function (a) {
        var b = a.x < this.left ? this.left - a.x : Math.max(a.x - (this.left + this.width), 0);
        a = a.y < this.top ? this.top - a.y : Math.max(a.y - (this.top + this.height), 0);
        return Math.sqrt(b * b + a * a);
    };
    l.ceil = function () {
        this.left = Math.ceil(this.left);
        this.top = Math.ceil(this.top);
        this.width = Math.ceil(this.width);
        this.height = Math.ceil(this.height);
        return this;
    };
    l.floor = function () {
        this.left = Math.floor(this.left);
        this.top = Math.floor(this.top);
        this.width = Math.floor(this.width);
        this.height = Math.floor(this.height);
        return this;
    };
    l.round = function () {
        this.left = Math.round(this.left);
        this.top = Math.round(this.top);
        this.width = Math.round(this.width);
        this.height = Math.round(this.height);
        return this;
    };
    l.scale = function (a, b) {
        b = "number" === typeof b ? b : a;
        this.left *= a;
        this.width *= a;
        this.top *= b;
        this.height *= b;
        return this;
    };
    var Mf = function (a) {
        a = void 0 === a ? t : a;
        var b = a.context || a.AMP_CONTEXT_DATA;
        if (!b)
            try {
                b = a.parent.context || a.parent.AMP_CONTEXT_DATA;
            } catch (c) {}
        try {
            if (b && b.pageViewId && b.canonicalUrl) return b;
        } catch (c) {}
        return null;
    };
    var Nf = function () {
            this.S = {};
        },
        Qf = function () {
            if (Of) var a = Of;
            else {
                a = ((a = Mf()) ? (sf(a.master) ? a.master : null) : null) || window;
                var b = a.google_persistent_state_async;
                a = null != b && "object" == typeof b && null != b.S && "object" == typeof b.S ? (Of = b) : (a.google_persistent_state_async = Of = new Nf());
            }
            if ((b = Mf(window))) {
                var c = b || Mf();
                c ? ((b = c.pageViewId), (c = c.clientId), "string" === typeof c && (b += c.replace(/\D/g, "").substr(0, 6))) : (b = null);
                b = +b;
            } else (b = Hf(window)), (c = b.google_global_correlator) || (b.google_global_correlator = c = 1 + Math.floor(Math.random() * Math.pow(2, 43))), (b = c);
            c = Pf[7] || "google_ps_7";
            a = a.S;
            var d = a[c];
            a = void 0 === d ? (a[c] = b) : d;
            return a;
        },
        Of = null,
        Rf = {},
        Pf = ((Rf[8] = "google_prev_ad_formats_by_region"), (Rf[9] = "google_prev_ad_slotnames_by_region"), Rf);
    function Sf(a, b) {
        a.google_image_requests || (a.google_image_requests = []);
        var c = Ef("IMG", a.document);
        c.src = b;
        a.google_image_requests.push(c);
    }
    var Uf = function (a, b) {
            var c = "https://pagead2.googlesyndication.com/pagead/gen_204?id=" + b;
            wf(a, function (d, e) {
                d && (c += "&" + e + "=" + encodeURIComponent(d));
            });
            Tf(c);
        },
        Tf = function (a) {
            var b = window;
            b.fetch ? b.fetch(a, { keepalive: !0, credentials: "include", redirect: "follow", method: "get", mode: "no-cors" }) : Sf(b, a);
        };
    var Vf = "function" === typeof Symbol && "symbol" === typeof Symbol() ? Symbol(void 0) : void 0;
    function Wf(a, b) {
        Object.isFrozen(a) || (Vf ? (a[Vf] |= b) : void 0 !== a.Wb ? (a.Wb |= b) : Object.defineProperties(a, { Wb: { value: b, configurable: !0, writable: !0, enumerable: !1 } }));
    }
    function Xf(a) {
        var b;
        Vf ? (b = a[Vf]) : (b = a.Wb);
        return null == b ? 0 : b;
    }
    function Yf(a) {
        Wf(a, 1);
        return a;
    }
    function Zf(a) {
        return Array.isArray(a) ? !!(Xf(a) & 2) : !1;
    }
    function $f(a) {
        if (!Array.isArray(a)) throw Error("cannot mark non-array as immutable");
        Wf(a, 2);
    }
    function ag(a) {
        return null !== a && "object" === typeof a && !Array.isArray(a) && a.constructor === Object;
    }
    var bg,
        cg = Object.freeze(Yf([])),
        dg = function (a) {
            if (Zf(a.ca)) throw Error("Cannot mutate an immutable Message");
        },
        eg = "undefined" != typeof Symbol && "undefined" != typeof Symbol.hasInstance;
    function fg(a) {
        return { value: a, configurable: !1, writable: !1, enumerable: !1 };
    }
    function gg(a) {
        switch (typeof a) {
            case "number":
                return isFinite(a) ? a : String(a);
            case "object":
                if (a && !Array.isArray(a) && Hc && null != a && a instanceof Uint8Array) return Dc(a);
        }
        return a;
    }
    function hg(a) {
        var b = ig;
        b = void 0 === b ? jg : b;
        return kg(a, b);
    }
    function lg(a, b) {
        if (null != a) {
            if (Array.isArray(a)) a = kg(a, b);
            else if (ag(a)) {
                var c = {},
                    d;
                for (d in a) c[d] = lg(a[d], b);
                a = c;
            } else a = b(a);
            return a;
        }
    }
    function kg(a, b) {
        for (var c = a.slice(), d = 0; d < c.length; d++) c[d] = lg(c[d], b);
        Array.isArray(a) && Xf(a) & 1 && Yf(c);
        return c;
    }
    function ig(a) {
        if (a && "object" == typeof a && a.toJSON) return a.toJSON();
        a = gg(a);
        return Array.isArray(a) ? hg(a) : a;
    }
    function jg(a) {
        return Hc && null != a && a instanceof Uint8Array ? new Uint8Array(a) : a;
    }
    var D = function (a, b, c) {
            return -1 === b ? null : b >= a.l ? (a.h ? a.h[b] : void 0) : (void 0 === c ? 0 : c) && a.h && ((c = a.h[b]), null != c) ? c : a.ca[b + a.j];
        },
        E = function (a, b, c, d, e) {
            d = void 0 === d ? !1 : d;
            (void 0 === e ? 0 : e) || dg(a);
            b < a.l && !d ? (a.ca[b + a.j] = c) : ((a.h || (a.h = a.ca[a.l + a.j] = {}))[b] = c);
            return a;
        },
        mg = function (a, b, c, d) {
            c = void 0 === c ? !0 : c;
            d = void 0 === d ? !1 : d;
            var e = D(a, b, d);
            null == e && (e = cg);
            if (Zf(a.ca)) c && ($f(e), Object.freeze(e));
            else if (e === cg || Zf(e)) (e = Yf(e.slice())), E(a, b, e, d);
            return e;
        },
        ng = function (a, b) {
            a = D(a, b);
            return null == a ? a : +a;
        },
        og = function (a, b) {
            a = D(a, b);
            return null == a ? a : !!a;
        },
        pg = function (a, b, c) {
            a = D(a, b);
            return null == a ? c : a;
        },
        qg = function (a, b) {
            a = og(a, b);
            return null == a ? !1 : a;
        },
        rg = function (a, b, c) {
            var d = void 0 === d ? !1 : d;
            return E(a, b, null == c ? Yf([]) : Array.isArray(c) ? Yf(c) : c, d);
        };
    function sg(a, b, c, d) {
        dg(a);
        c !== d ? E(a, b, c) : E(a, b, void 0, !1, !1);
        return a;
    }
    var tg = function (a, b) {
            for (var c = 0, d = 0; d < b.length; d++) {
                var e = b[d];
                null != D(a, e) && (0 !== c && E(a, c, void 0, !1, !0), (c = e));
            }
            return c;
        },
        ug = function (a, b, c) {
            if (-1 === c) return null;
            a.da || (a.da = {});
            var d = a.da[c];
            if (d) return d;
            var e = D(a, c, !1);
            if (null == e) return d;
            b = new b(e);
            Zf(a.ca) && $f(b.ca);
            return (a.da[c] = b);
        },
        vg = function (a, b, c, d) {
            a.da || (a.da = {});
            var e = Zf(a.ca),
                f = a.da[c];
            if (!f) {
                d = mg(a, c, !0, void 0 === d ? !1 : d);
                f = [];
                e = e || Zf(d);
                for (var g = 0; g < d.length; g++) (f[g] = new b(d[g])), e && $f(f[g].ca);
                e && ($f(f), Object.freeze(f));
                a.da[c] = f;
            }
            return f;
        },
        wg = function (a, b, c) {
            var d = void 0 === d ? !1 : d;
            dg(a);
            a.da || (a.da = {});
            var e = c ? c.ca : c;
            a.da[b] = c;
            return E(a, b, e, d);
        },
        xg = function (a, b, c) {
            var d = void 0 === d ? !1 : d;
            dg(a);
            if (c) {
                var e = Yf([]);
                for (var f = 0; f < c.length; f++) e[f] = c[f].ca;
                a.da || (a.da = {});
                a.da[b] = c;
            } else a.da && (a.da[b] = void 0), (e = cg);
            return E(a, b, e, d);
        },
        yg = function (a, b, c, d, e) {
            var f = void 0 === f ? !1 : f;
            dg(a);
            f = vg(a, c, b, f);
            c = d ? d : new c();
            a = mg(a, b);
            void 0 != e ? (f.splice(e, 0, c), a.splice(e, 0, c.ca)) : (f.push(c), a.push(c.ca));
            return c;
        },
        zg = function (a, b, c) {
            b = tg(a, c) === b ? b : -1;
            return pg(a, b, 0);
        },
        Ag = function (a, b, c) {
            return sg(a, b, c, 0);
        };
    var Cg = function (a, b, c) {
        a || (a = Bg);
        Bg = null;
        var d = this.constructor.messageId;
        a || (a = d ? [d] : []);
        this.j = (d ? 0 : -1) - (this.constructor.h || 0);
        this.da = void 0;
        this.ca = a;
        a: {
            d = this.ca.length;
            a = d - 1;
            if (d && ((d = this.ca[a]), ag(d))) {
                this.l = a - this.j;
                this.h = d;
                break a;
            }
            void 0 !== b && -1 < b ? ((this.l = Math.max(b, a + 1 - this.j)), (this.h = void 0)) : (this.l = Number.MAX_VALUE);
        }
        if (c)
            for (b = 0; b < c.length; b++)
                if (((a = c[b]), a < this.l)) (a += this.j), (d = this.ca[a]) ? Array.isArray(d) && Yf(d) : (this.ca[a] = cg);
                else {
                    d = this.h || (this.h = this.ca[this.l + this.j] = {});
                    var e = d[a];
                    e ? Array.isArray(e) && Yf(e) : (d[a] = cg);
                }
    };
    Cg.prototype.toJSON = function () {
        var a = this.ca;
        return bg ? a : hg(a);
    };
    Cg.prototype.aa = function () {
        bg = !0;
        try {
            return JSON.stringify(this.toJSON(), Dg);
        } finally {
            bg = !1;
        }
    };
    var Eg = function (a, b) {
        if (null == b || "" == b) return new a();
        b = JSON.parse(b);
        if (!Array.isArray(b)) throw Error("Expected to deserialize an Array but got " + Oa(b) + ": " + b);
        Bg = b;
        a = new a(b);
        Bg = null;
        return a;
    };
    Cg.prototype.toString = function () {
        return this.ca.toString();
    };
    function Dg(a, b) {
        return gg(b);
    }
    var Bg;
    var Fg = function () {
        Cg.apply(this, arguments);
    };
    r(Fg, Cg);
    if (eg) {
        var Gg = {};
        Object.defineProperties(
            Fg,
            ((Gg[Symbol.hasInstance] = fg(function () {
                throw Error("Cannot perform instanceof checks for MutableMessage");
            })),
            Gg)
        );
    }
    function Hg(a, b, c) {
        if (c) {
            var d = {},
                e;
            for (e in c) {
                var f = c[e],
                    g = f.Ef;
                g ||
                    ((d.vb = f.Jh || f.Lh.qc),
                    f.Be
                        ? ((d.tc = Ig(f.Be)),
                          (g = (function (h) {
                              return function (k, n, m) {
                                  return h.vb(k, n, m, h.tc);
                              };
                          })(d)))
                        : f.ff
                        ? ((d.sc = Jg(f.Ie.h, f.ff)),
                          (g = (function (h) {
                              return function (k, n, m) {
                                  return h.vb(k, n, m, h.sc);
                              };
                          })(d)))
                        : (g = d.vb),
                    (f.Ef = g));
                g(b, a, f.Ie);
                d = { vb: d.vb, tc: d.tc, sc: d.sc };
            }
        }
        fd(b, a);
    }
    var Kg = Symbol();
    function Ig(a) {
        var b = a[Kg];
        if (!b) {
            var c = Lg(a);
            b = function (d, e) {
                return Mg(d, e, c);
            };
            a[Kg] = b;
        }
        return b;
    }
    function Jg(a, b) {
        var c = a[Kg];
        c ||
            ((c = function (d, e) {
                return Hg(d, e, b);
            }),
            (a[Kg] = c));
        return c;
    }
    var Ng = Symbol();
    function Og(a, b) {
        a.push(b);
    }
    function Pg(a, b, c) {
        a.push(b, c.qc);
    }
    function Qg(a, b, c, d, e) {
        var f = Ig(e),
            g = c.qc;
        a.push(b, function (h, k, n) {
            return g(h, k, n, d, f);
        });
    }
    function Rg(a, b, c, d, e, f) {
        var g = Jg(d, f),
            h = c.qc;
        a.push(b, function (k, n, m) {
            return h(k, n, m, d, g);
        });
    }
    function Lg(a) {
        var b = a[Ng];
        if (!b) {
            b = a[Ng] = [];
            var c = Og,
                d = Pg,
                e = Qg,
                f = Rg;
            a = a();
            var g = 0;
            a.length && "number" !== typeof a[0] && (c(b, a[0]), g++);
            for (; g < a.length; ) {
                c = a[g++];
                for (var h = g + 1; h < a.length && "number" !== typeof a[h]; ) h++;
                var k = a[g++];
                h -= g;
                switch (h) {
                    case 0:
                        d(b, c, k);
                        break;
                    case 1:
                        d(b, c, k, a[g++]);
                        break;
                    case 2:
                        e(b, c, k, a[g++], a[g++]);
                        break;
                    case 3:
                        h = a[g++];
                        var n = a[g++],
                            m = a[g++];
                        Array.isArray(m) ? e(b, c, k, h, n, m) : f(b, c, k, h, n, m);
                        break;
                    case 4:
                        f(b, c, k, a[g++], a[g++], a[g++], a[g++]);
                        break;
                    default:
                        throw Error("unexpected number of binary field arguments: " + h);
                }
            }
        }
        return b;
    }
    function Mg(a, b, c) {
        for (var d = c.length, e = 1 == d % 2, f = e ? 1 : 0; f < d; f += 2) (0, c[f + 1])(b, a, c[f]);
        Hg(a, b, e ? c[0] : void 0);
    }
    var Sg = function (a, b) {
        var c = new ad();
        Mg(a, c, Lg(b));
        bd(c, c.h.end());
        a = new Uint8Array(c.j);
        b = c.l;
        for (var d = b.length, e = 0, f = 0; f < d; f++) {
            var g = b[f];
            a.set(g, e);
            e += g.length;
        }
        c.l = [a];
        return a;
    };
    function Tg(a, b) {
        return { Kh: a, qc: b };
    }
    function Ug(a, b, c) {
        b = D(b, c);
        null != b && ("string" === typeof b && Tc(b), null != b && (cd(a, c, 0), "number" === typeof b ? ((a = a.h), Mc(b), Vc(a, Kc, Lc)) : ((c = Tc(b)), Vc(a.h, c.j, c.h))));
    }
    function Vg(a, b, c) {
        b = D(b, c);
        null != b && null != b && (cd(a, c, 0), Xc(a.h, b));
    }
    var Wg = Tg(
            function (a, b, c) {
                if (1 !== a.h()) return !1;
                a = a.B();
                sg(b, c, a, 0);
                return !0;
            },
            function (a, b, c) {
                b = D(b, c);
                if (null != b) {
                    cd(a, c, 1);
                    a = a.h;
                    var d = +b;
                    if (0 === d) (Lc = 0 < 1 / d ? 0 : 2147483648), (Kc = 0);
                    else if (isNaN(d)) (Lc = 2147483647), (Kc = 4294967295);
                    else if (((d = (c = 0 > d ? -2147483648 : 0) ? -d : d), 1.7976931348623157e308 < d)) (Lc = (c | 2146435072) >>> 0), (Kc = 0);
                    else if (2.2250738585072014e-308 > d) (b = d / Math.pow(2, -1074)), (Lc = (c | (b / 4294967296)) >>> 0), (Kc = b >>> 0);
                    else {
                        var e = d;
                        b = 0;
                        if (2 <= e) for (; 2 <= e && 1023 > b; ) b++, (e /= 2);
                        else for (; 1 > e && -1022 < b; ) (e *= 2), b--;
                        d *= Math.pow(2, -b);
                        Lc = (c | ((b + 1023) << 20) | ((1048576 * d) & 1048575)) >>> 0;
                        Kc = (4503599627370496 * d) >>> 0;
                    }
                    Yc(a, Kc);
                    Yc(a, Lc);
                }
            }
        ),
        Xg = Tg(
            function (a, b, c) {
                if (5 !== a.h()) return !1;
                E(b, c, a.H());
                return !0;
            },
            function (a, b, c) {
                b = D(b, c);
                if (null != b) {
                    cd(a, c, 5);
                    a = a.h;
                    var d = +b;
                    0 === d
                        ? 0 < 1 / d
                            ? (Kc = Lc = 0)
                            : ((Lc = 0), (Kc = 2147483648))
                        : isNaN(d)
                        ? ((Lc = 0), (Kc = 2147483647))
                        : ((d = (c = 0 > d ? -2147483648 : 0) ? -d : d),
                          3.4028234663852886e38 < d
                              ? ((Lc = 0), (Kc = (c | 2139095040) >>> 0))
                              : 1.1754943508222875e-38 > d
                              ? ((d = Math.round(d / Math.pow(2, -149))), (Lc = 0), (Kc = (c | d) >>> 0))
                              : ((b = Math.floor(Math.log(d) / Math.LN2)), (d *= Math.pow(2, -b)), (d = Math.round(8388608 * d)), 16777216 <= d && ++b, (Lc = 0), (Kc = (c | ((b + 127) << 23) | (d & 8388607)) >>> 0)));
                    Yc(a, Kc);
                }
            }
        ),
        Yg = Tg(function (a, b, c) {
            if (0 !== a.h()) return !1;
            E(b, c, a.l());
            return !0;
        }, Ug),
        Zg = Tg(function (a, b, c) {
            if (0 !== a.h()) return !1;
            Ag(b, c, a.l());
            return !0;
        }, Ug),
        $g = Tg(
            function (a, b, c) {
                if (0 !== a.h()) return !1;
                E(b, c, a.D());
                return !0;
            },
            function (a, b, c) {
                b = D(b, c);
                null != b && ("string" === typeof b && Qc(b), null != b && (cd(a, c, 0), "number" === typeof b ? ((a = a.h), Mc(b), Vc(a, Kc, Lc)) : ((c = Qc(b)), Vc(a.h, c.j, c.h))));
            }
        ),
        ah = Tg(function (a, b, c) {
            if (0 !== a.h()) return !1;
            E(b, c, a.j());
            return !0;
        }, Vg),
        bh = Tg(function (a, b, c) {
            if (0 !== a.h()) return !1;
            Ag(b, c, a.j());
            return !0;
        }, Vg),
        ch = Tg(
            function (a, b, c) {
                if (0 !== a.h()) return !1;
                E(b, c, a.A());
                return !0;
            },
            function (a, b, c) {
                b = D(b, c);
                null != b && (cd(a, c, 0), a.h.h.push(b ? 1 : 0));
            }
        ),
        dh = Tg(
            function (a, b, c) {
                if (2 !== a.h()) return !1;
                E(b, c, a.J());
                return !0;
            },
            function (a, b, c) {
                b = D(b, c);
                if (null != b) {
                    var d = !1;
                    d = void 0 === d ? !1 : d;
                    if ($c) {
                        if (d && /(?:[^\uD800-\uDBFF]|^)[\uDC00-\uDFFF]|[\uD800-\uDBFF](?![\uDC00-\uDFFF])/.test(b)) throw Error("Found an unpaired surrogate");
                        b = (Zc || (Zc = new TextEncoder())).encode(b);
                    } else {
                        for (var e = 0, f = new Uint8Array(3 * b.length), g = 0; g < b.length; g++) {
                            var h = b.charCodeAt(g);
                            if (128 > h) f[e++] = h;
                            else {
                                if (2048 > h) f[e++] = (h >> 6) | 192;
                                else {
                                    if (55296 <= h && 57343 >= h) {
                                        if (56319 >= h && g < b.length) {
                                            var k = b.charCodeAt(++g);
                                            if (56320 <= k && 57343 >= k) {
                                                h = 1024 * (h - 55296) + k - 56320 + 65536;
                                                f[e++] = (h >> 18) | 240;
                                                f[e++] = ((h >> 12) & 63) | 128;
                                                f[e++] = ((h >> 6) & 63) | 128;
                                                f[e++] = (h & 63) | 128;
                                                continue;
                                            } else g--;
                                        }
                                        if (d) throw Error("Found an unpaired surrogate");
                                        h = 65533;
                                    }
                                    f[e++] = (h >> 12) | 224;
                                    f[e++] = ((h >> 6) & 63) | 128;
                                }
                                f[e++] = (h & 63) | 128;
                            }
                        }
                        b = f.subarray(0, e);
                    }
                    cd(a, c, 2);
                    Wc(a.h, b.length);
                    bd(a, a.h.end());
                    bd(a, b);
                }
            }
        ),
        eh = Tg(
            function (a, b, c, d, e) {
                if (2 !== a.h()) return !1;
                var f = a.o;
                var g = void 0 === g ? !1 : g;
                dg(b);
                b.da || (b.da = {});
                var h = b.da[c];
                h ? (b = h) : ((h = D(b, c, g)), (d = new d(h)), null == h && E(b, c, d.ca, g), (b = b.da[c] = d));
                f.call(a, b, e);
                return !0;
            },
            function (a, b, c, d, e) {
                b = ug(b, d, c);
                null != b && ((c = dd(a, c)), e(b, a), ed(a, c));
            }
        ),
        fh = Tg(
            function (a, b, c, d, e) {
                if (2 !== a.h()) return !1;
                a.o(yg(b, c, d), e);
                return !0;
            },
            function (a, b, c, d, e) {
                b = vg(b, d, c);
                if (null != b)
                    for (d = 0; d < b.length; d++) {
                        var f = dd(a, c);
                        e(b[d], a);
                        ed(a, f);
                    }
            }
        ),
        gh = Tg(
            function (a, b, c) {
                if (0 !== a.h()) return !1;
                E(b, c, a.C());
                return !0;
            },
            function (a, b, c) {
                b = D(b, c);
                null != b && ((b = parseInt(b, 10)), cd(a, c, 0), Xc(a.h, b));
            }
        );
    var F = function () {
        Fg.apply(this, arguments);
    };
    r(F, Fg);
    if (eg) {
        var hh = {};
        Object.defineProperties(F, ((hh[Symbol.hasInstance] = fg(Object[Symbol.hasInstance])), hh));
    }
    var G = function (a) {
        var b = "Jc";
        if (a.Jc && a.hasOwnProperty(b)) return a.Jc;
        b = new a();
        return (a.Jc = b);
    };
    var ih = function () {
            var a = {};
            this.h = function (b, c) {
                return null != a[b] ? a[b] : c;
            };
            this.j = function (b, c) {
                return null != a[b] ? a[b] : c;
            };
            this.l = function () {
                var b = ud.flag,
                    c = ud.defaultValue;
                return null != a[b] ? a[b] : c;
            };
        },
        jh = function (a) {
            return G(ih).h(a.flag, a.defaultValue);
        },
        kh = function (a) {
            return G(ih).j(a.flag, a.defaultValue);
        };
    var lh = function (a, b, c, d) {
        this.h = a;
        this.l = b;
        this.j = c;
        this.o = d;
    };
    var mh = new lh(
        new Set(
            "ARTICLE SECTION NAV ASIDE H1 H2 H3 H4 H5 H6 HEADER FOOTER ADDRESS P HR PRE BLOCKQUOTE OL UL LH LI DL DT DD FIGURE FIGCAPTION MAIN DIV EM STRONG SMALL S CITE Q DFN ABBR RUBY RB RT RTC RP DATA TIME CODE VAR SAMP KBD SUB SUP I B U MARK BDI BDO SPAN BR WBR INS DEL PICTURE PARAM TRACK MAP TABLE CAPTION COLGROUP COL TBODY THEAD TFOOT TR TD TH SELECT DATALIST OPTGROUP OPTION OUTPUT PROGRESS METER FIELDSET LEGEND DETAILS SUMMARY MENU DIALOG SLOT CANVAS FONT CENTER".split(
                " "
            )
        ),
        new Map([
            ["A", new Map([["href", { na: 2 }]])],
            ["AREA", new Map([["href", { na: 2 }]])],
            [
                "LINK",
                new Map([
                    ["href", { na: 2, conditions: new Map([["rel", new Set("alternate author bookmark canonical cite help icon license next prefetch dns-prefetch prerender preconnect preload prev search subresource".split(" "))]]) }],
                ]),
            ],
            ["SOURCE", new Map([["src", { na: 2 }]])],
            ["IMG", new Map([["src", { na: 2 }]])],
            ["VIDEO", new Map([["src", { na: 2 }]])],
            ["AUDIO", new Map([["src", { na: 2 }]])],
        ]),
        new Set(
            "title aria-atomic aria-autocomplete aria-busy aria-checked aria-current aria-disabled aria-dropeffect aria-expanded aria-haspopup aria-hidden aria-invalid aria-label aria-level aria-live aria-multiline aria-multiselectable aria-orientation aria-posinset aria-pressed aria-readonly aria-relevant aria-required aria-selected aria-setsize aria-sort aria-valuemax aria-valuemin aria-valuenow aria-valuetext alt align autocapitalize autocomplete autocorrect autofocus autoplay bgcolor border cellpadding cellspacing checked color cols colspan controls datetime disabled download draggable enctype face formenctype frameborder height hreflang hidden ismap label lang loop max maxlength media minlength min multiple muted nonce open placeholder preload rel required reversed role rows rowspan selected shape size sizes slot span spellcheck start step summary translate type valign value width wrap itemscope itemtype itemid itemprop itemref".split(
                " "
            )
        ),
        new Map([
            ["dir", { na: 3, conditions: new Map([["dir", new Set(["auto", "ltr", "rtl"])]]) }],
            ["async", { na: 3, conditions: new Map([["async", new Set(["async"])]]) }],
            ["cite", { na: 2 }],
            ["loading", { na: 3, conditions: new Map([["loading", new Set(["eager", "lazy"])]]) }],
            ["poster", { na: 2 }],
            ["target", { na: 3, conditions: new Map([["target", new Set(["_self", "_blank"])]]) }],
        ])
    );
    var nh = function (a) {
        this.isValid = a;
    };
    function oh(a) {
        return new nh(function (b) {
            return b.substr(0, a.length + 1).toLowerCase() === a + ":";
        });
    }
    var ph = [
        oh("data"),
        oh("http"),
        oh("https"),
        oh("mailto"),
        oh("ftp"),
        new nh(function (a) {
            return /^[^:]*([/?#]|$)/.test(a);
        }),
    ];
    var qh = function () {
        this.changes = [];
        if (ge !== ge) throw Error("Bad secret");
    };
    new qh();
    var rh = function () {
        this.j = !1;
        this.h = mh;
    };
    rh.prototype.build = function () {
        if (this.j) throw Error("this sanitizer has already called build");
        this.j = !0;
        return new qh();
    };
    function H(a) {
        var b = Ha.apply(1, arguments);
        if (0 === b.length) return le(a[0]);
        for (var c = [a[0]], d = 0; d < b.length; d++) c.push(encodeURIComponent(b[d])), c.push(a[d + 1]);
        return le(c.join(""));
    }
    function sh(a, b) {
        var c = ne(a);
        if (/#/.test(c)) throw Error("");
        var d = /\?/.test(c) ? "&" : "?";
        b.forEach(function (e, f) {
            e = e instanceof Array ? e : [e];
            for (var g = 0; g < e.length; g++) {
                var h = e[g];
                null !== h && void 0 !== h && ((c += d + encodeURIComponent(f) + "=" + encodeURIComponent(String(h))), (d = "&"));
            }
        });
        return le(c);
    }
    var th = function (a, b, c) {
            c = void 0 === c ? {} : c;
            this.error = a;
            this.context = b.context;
            this.msg = b.message || "";
            this.id = b.id || "jserror";
            this.meta = c;
        },
        uh = function (a) {
            return !!(a.error && a.meta && a.id);
        };
    var vh = p(["https://pagead2.googlesyndication.com/pagead/js/err_rep.js"]),
        wh = function () {
            this.j = "jserror";
            this.l = !1;
            this.h = null;
            this.o = !1;
            this.B = Math.random();
            this.A = this.La;
            this.C = null;
        };
    l = wh.prototype;
    l.Zc = function (a) {
        this.j = a;
    };
    l.jc = function (a) {
        this.h = a;
    };
    l.$c = function (a) {
        this.l = a;
    };
    l.bd = function (a) {
        this.o = a;
    };
    l.La = function (a, b, c, d, e) {
        e = void 0 === e ? this.j : e;
        if ((this.o ? this.B : Math.random()) > (void 0 === c ? 0.01 : c)) return this.l;
        uh(b) || (b = new th(b, { context: a, id: e }));
        if (d || this.h) (b.meta = {}), this.h && this.h(b.meta), d && d(b.meta);
        t.google_js_errors = t.google_js_errors || [];
        t.google_js_errors.push(b);
        if (!t.error_rep_loaded) {
            c = H(vh);
            var f;
            a = t.document;
            b = null != (f = this.C) ? f : Td(se(c).toString());
            f = Ef("SCRIPT", a);
            f.src = se(b);
            ve(f);
            (a = a.getElementsByTagName("script")[0]) && a.parentNode && a.parentNode.insertBefore(f, a);
            t.error_rep_loaded = !0;
        }
        return this.l;
    };
    l.bb = function (a, b, c) {
        try {
            return b();
        } catch (d) {
            if (!this.A(a, d, 0.01, c, this.j)) throw d;
        }
    };
    l.Vc = function (a, b, c, d) {
        var e = this;
        return function () {
            var f = Ha.apply(0, arguments);
            return e.bb(
                a,
                function () {
                    return b.apply(c, f);
                },
                d
            );
        };
    };
    var xh = function (a) {
            return a.prerendering ? 3 : { visible: 1, hidden: 2, prerender: 3, preview: 4, unloaded: 5 }[a.visibilityState || a.webkitVisibilityState || a.mozVisibilityState || ""] || 0;
        },
        yh = function (a) {
            var b;
            a.visibilityState ? (b = "visibilitychange") : a.mozVisibilityState ? (b = "mozvisibilitychange") : a.webkitVisibilityState && (b = "webkitvisibilitychange");
            return b;
        };
    var zh = null;
    var Ah = function () {
            var a = void 0 === a ? t : a;
            return (a = a.performance) && a.now && a.timing ? Math.floor(a.now() + a.timing.navigationStart) : Za();
        },
        Bh = function () {
            var a = void 0 === a ? t : a;
            return (a = a.performance) && a.now ? a.now() : null;
        },
        Ch = function (a, b) {
            b = void 0 === b ? t : b;
            var c, d;
            return (null == (c = b.performance) ? void 0 : null == (d = c.timing) ? void 0 : d[a]) || 0;
        },
        Dh = function () {
            var a = void 0 === a ? t : a;
            var b = Math.min(Ch("domLoading", a) || Infinity, Ch("domInteractive", a) || Infinity);
            return Infinity == b ? Math.max(Ch("responseEnd", a), Ch("navigationStart", a)) : b;
        };
    var Eh = function (a, b, c, d, e) {
        this.label = a;
        this.type = b;
        this.value = c;
        this.duration = void 0 === d ? 0 : d;
        this.uniqueId = Math.random();
        this.slotId = e;
    };
    var Fh = t.performance,
        Gh = !!(Fh && Fh.mark && Fh.measure && Fh.clearMarks),
        Hh = ye(function () {
            var a;
            if ((a = Gh)) {
                var b;
                if (null === zh) {
                    zh = "";
                    try {
                        a = "";
                        try {
                            a = t.top.location.hash;
                        } catch (c) {
                            a = t.location.hash;
                        }
                        a && (zh = (b = a.match(/\bdeid=([\d,]+)/)) ? b[1] : "");
                    } catch (c) {}
                }
                b = zh;
                a = !!b.indexOf && 0 <= b.indexOf("1337");
            }
            return a;
        }),
        Ih = function (a, b) {
            this.events = [];
            this.h = b || t;
            var c = null;
            b && ((b.google_js_reporting_queue = b.google_js_reporting_queue || []), (this.events = b.google_js_reporting_queue), (c = b.google_measure_js_timing));
            this.l = Hh() || (null != c ? c : Math.random() < a);
        };
    Ih.prototype.C = function () {
        this.l = !1;
        this.events != this.h.google_js_reporting_queue && (Hh() && Fb(this.events, Jh), (this.events.length = 0));
    };
    Ih.prototype.H = function (a) {
        !this.l || 2048 < this.events.length || this.events.push(a);
    };
    var Jh = function (a) {
        a && Fh && Hh() && (Fh.clearMarks("goog_" + a.label + "_" + a.uniqueId + "_start"), Fh.clearMarks("goog_" + a.label + "_" + a.uniqueId + "_end"));
    };
    Ih.prototype.start = function (a, b) {
        if (!this.l) return null;
        a = new Eh(a, b, Bh() || Ah());
        b = "goog_" + a.label + "_" + a.uniqueId + "_start";
        Fh && Hh() && Fh.mark(b);
        return a;
    };
    Ih.prototype.end = function (a) {
        if (this.l && "number" === typeof a.value) {
            a.duration = (Bh() || Ah()) - a.value;
            var b = "goog_" + a.label + "_" + a.uniqueId + "_end";
            Fh && Hh() && Fh.mark(b);
            this.H(a);
        }
    };
    var Kh = function (a) {
        a = a._google_rum_ns_ = a._google_rum_ns_ || {};
        return (a.pq = a.pq || []);
    };
    var Lh = function (a, b, c) {
            wf(b, function (d, e) {
                var f = c && c[e];
                (!d && 0 !== d) || f || ((a += "&" + encodeURIComponent(e) + "=" + encodeURIComponent(String(d))), c && (c[e] = !0));
            });
            return a;
        },
        Th = function (a, b, c, d, e, f, g, h) {
            f = void 0 === f ? Infinity : f;
            g = void 0 === g ? !1 : g;
            Ih.call(this, a, h);
            var k = this;
            this.J = 0;
            this.L = f;
            this.Y = b;
            this.K = c;
            this.W = d;
            this.Z = e;
            a = this.h.navigator;
            this.U = !("csi.gstatic.com" !== this.K || !a || !a.sendBeacon);
            this.A = {};
            this.I = {};
            (this.h.performance && this.h.performance.now) || Mh(this, "dat", 1);
            a && a.deviceMemory && Mh(this, "dmc", a.deviceMemory);
            this.h === this.h.top && Mh(this, "top", 1);
            this.T = !g;
            this.M = function () {
                k.h.setTimeout(function () {
                    return Nh(k);
                }, 1100);
            };
            this.qa = [];
            this.V = function () {
                Oh(k, 1);
            };
            this.R = function () {
                Oh(k, 2);
            };
            this.pa = Ae(function () {
                Nh(k);
            });
            this.sa = function () {
                var m = k.h.document;
                (null != m.hidden ? m.hidden : null != m.mozHidden ? m.mozHidden : null != m.webkitHidden && m.webkitHidden) && k.pa();
            };
            this.D = this.h.setTimeout(function () {
                return Nh(k);
            }, 5e3);
            this.B = {};
            this.o = b.length + c.length + d.length + e.length + 3;
            this.j = 0;
            Fb(this.events, function (m) {
                return Ph(k, m);
            });
            this.G = [];
            b = Kh(this.h);
            var n = function (m) {
                var x = m[0];
                m = m[1];
                var v = x.length + m.length + 2;
                8e3 < k.o + k.j + v && Nh(k);
                k.G.push([x, m]);
                k.j += v;
                Qh(k);
                return 0;
            };
            Fb(b, function (m) {
                return n(m);
            });
            b.length = 0;
            b.push = n;
            Rh(this);
            Sh(this);
        };
    r(Th, Ih);
    var Sh = function (a) {
            "complete" === a.h.document.readyState
                ? a.h.setTimeout(function () {
                      return Nh(a);
                  }, 0)
                : De(a.h, "load", a.M);
            var b = yh(a.h.document);
            "undefined" !== typeof b && De(a.h, b, a.sa);
            jh(pd) || De(a.h, "unload", a.V);
            De(a.h, "pagehide", a.R);
        },
        Mh = function (a, b, c) {
            c = String(c);
            a.o = null != a.A[b] ? a.o + (c.length - a.A[b].length) : a.o + (b.length + c.length + 2);
            a.A[b] = c;
        },
        Uh = function (a) {
            null != a.A.uet && ((a.o -= 3 + a.A.uet.length + 2), delete a.A.uet);
        },
        Xh = function (a, b, c, d, e) {
            e = void 0 === e ? "" : e;
            var f = Vh(a, b, c, d, e);
            8e3 < a.o + a.j + f && (Nh(a), (f = b.length + c.length + 2));
            Wh(a, b, c, d, e);
            a.j += f;
            Qh(a);
        },
        Vh = function (a, b, c, d, e) {
            return null == a.B[b] ? b.length + c.length + 2 : d ? c.length + (void 0 === e ? "" : e).length : c.length - a.B[b].length;
        },
        Wh = function (a, b, c, d, e) {
            a.B[b] = d && null != a.B[b] ? a.B[b] + ("" + (void 0 === e ? "" : e) + c) : c;
        },
        Qh = function (a) {
            6e3 <= a.o + a.j && Nh(a);
        },
        Nh = function (a) {
            if (a.l && a.T) {
                try {
                    if (a.j) {
                        var b = a.B;
                        a.J++;
                        var c = Yh(a, b);
                        b = !1;
                        try {
                            b = !!(a.U && a.h.navigator && a.h.navigator.sendBeacon(c, null));
                        } catch (d) {
                            a.U = !1;
                        }
                        b || Sf(a.h, c);
                        Rh(a);
                        a.J === a.L && a.C();
                    }
                } catch (d) {
                    new wh().La(358, d);
                }
                a.B = {};
                a.j = 0;
                a.events.length = 0;
                a.h.clearTimeout(a.D);
                a.D = 0;
            }
        },
        Yh = function (a, b) {
            var c = a.Y + "//" + a.K + a.W + a.Z,
                d = {};
            c = Lh(c, a.A, d);
            c = Lh(c, b, d);
            b = a.h;
            b.google_timing_params && ((c = Lh(c, b.google_timing_params, d)), (b.google_timing_params = void 0));
            Fb(a.G, function (e) {
                var f = q(e);
                e = f.next().value;
                f = f.next().value;
                var g = {};
                c = Lh(c, ((g[e] = f), g));
            });
            a.G.length = 0;
            return c;
        },
        Rh = function (a) {
            Mh(a, "puid", (a.J + 1).toString(36) + "~" + Za().toString(36));
        },
        Ph = function (a, b) {
            var c = "met." + b.type,
                d = "number" === typeof b.value ? Math.round(b.value).toString(36) : b.value,
                e = Math.round(b.duration);
            b = "" + b.label + (null != b.slotId ? "_" + b.slotId : "") + ("." + d) + (0 < e ? "_" + e.toString(36) : "");
            Xh(a, c, b, !0, "~");
        };
    Th.prototype.H = function (a) {
        this.l && this.J < this.L && (Ih.prototype.H.call(this, a), Ph(this, a));
    };
    Th.prototype.C = function () {
        Ih.prototype.C.call(this);
        this.h.clearTimeout(this.D);
        this.j = this.D = 0;
        this.B = {};
        Kd(this.I);
        Kd(this.A);
        Ee(this.h, "load", this.M);
        jh(pd) || Ee(this.h, "unload", this.V);
        Ee(this.h, "pagehide", this.R);
    };
    var Oh = function (a, b) {
        Mh(a, "uet", b);
        Fb(a.qa, function (c) {
            try {
                c();
            } catch (d) {}
        });
        Fe(a.h);
        Nh(a);
        Uh(a);
    };
    var Zh = function (a) {
        var b = [],
            c = [],
            d = {},
            e = function (f, g) {
                var h = g + "  ";
                try {
                    if (void 0 === f) b.push("undefined");
                    else if (null === f) b.push("NULL");
                    else if ("string" === typeof f) b.push('"' + f.replace(/\n/g, "\n" + g) + '"');
                    else if ("function" === typeof f) b.push(String(f).replace(/\n/g, "\n" + g));
                    else if (Qa(f)) {
                        f[Ra] || c.push(f);
                        var k = Ta(f);
                        if (d[k]) b.push("*** reference loop detected (id=" + k + ") ***");
                        else {
                            d[k] = !0;
                            b.push("{");
                            for (var n in f) "function" !== typeof f[n] && (b.push("\n"), b.push(h), b.push(n + " = "), e(f[n], h));
                            b.push("\n" + g + "}");
                            delete d[k];
                        }
                    } else b.push(f);
                } catch (m) {
                    b.push("*** " + m + " ***");
                }
            };
        e(a, "");
        for (a = 0; a < c.length; a++) Ua(c[a]);
        return b.join("");
    };
    var $h = function () {
        this.h = new Th(1, "https:", "csi.gstatic.com", "/csi?v=2&s=", "ima", void 0, !0);
        var a = Qf();
        null != a && Mh(this.h, "c", a);
        a = parseInt(this.h.A.c, 10) / 2;
        null != a && Mh(this.h, "slotId", a);
    };
    $h.prototype.isLoggingEnabled = function () {
        return this.h.l;
    };
    var I = function (a, b, c) {
            if (null != c) {
                a = a.h;
                var d = b + "=" + c;
                a.I[d] || (Xh(a, b, c, !1), 1e3 > d.length && (a.I[d] = !0));
            }
        },
        ai = function (a, b) {
            for (var c in b) b[c] = "object" === typeof b[c] ? encodeURIComponent(JSON.stringify(b[c])) : encodeURIComponent(String(b[c]));
            a = a.h;
            c = !1;
            var d = 0,
                e;
            for (e in b) null != a.B[e] && (c = !0), (d += Vh(a, e, b[e], !1));
            (8e3 < a.o + a.j + d || c) && Nh(a);
            for (var f in b) Wh(a, f, b[f], !1);
            a.j += d;
            Qh(a);
        },
        bi = function (a) {
            var b = J().h,
                c = Ah() - 0;
            b.l && b.H(new Eh(a, 4, c, 0, void 0));
        },
        J = function () {
            return G($h);
        };
    var ci = function (a) {
            return /^\s*$/.test(a)
                ? !1
                : /^[\],:{}\s\u2028\u2029]*$/.test(
                      a
                          .replace(/\\["\\\/bfnrtu]/g, "@")
                          .replace(/(?:"[^"\\\n\r\u2028\u2029\x00-\x08\x0a-\x1f]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)[\s\u2028\u2029]*(?=:|,|]|}|$)/g, "]")
                          .replace(/(?:^|:|,)(?:[\s\u2028\u2029]*\[)+/g, "")
                  );
        },
        di = function (a) {
            try {
                return t.JSON.parse(a);
            } catch (b) {}
            a = String(a);
            if (ci(a))
                try {
                    return eval("(" + a + ")");
                } catch (b) {}
            throw Error("Invalid JSON string: " + a);
        },
        ei = function (a) {
            this.h = a;
        };
    ei.prototype.aa = function (a) {
        var b = [];
        fi(this, a, b);
        return b.join("");
    };
    var fi = function (a, b, c) {
            if (null == b) c.push("null");
            else {
                if ("object" == typeof b) {
                    if (Array.isArray(b)) {
                        var d = b;
                        b = d.length;
                        c.push("[");
                        for (var e = "", f = 0; f < b; f++) c.push(e), (e = d[f]), fi(a, a.h ? a.h.call(d, String(f), e) : e, c), (e = ",");
                        c.push("]");
                        return;
                    }
                    if (b instanceof String || b instanceof Number || b instanceof Boolean) b = b.valueOf();
                    else {
                        c.push("{");
                        f = "";
                        for (d in b) Object.prototype.hasOwnProperty.call(b, d) && ((e = b[d]), "function" != typeof e && (c.push(f), gi(d, c), c.push(":"), fi(a, a.h ? a.h.call(b, d, e) : e, c), (f = ",")));
                        c.push("}");
                        return;
                    }
                }
                switch (typeof b) {
                    case "string":
                        gi(b, c);
                        break;
                    case "number":
                        c.push(isFinite(b) && !isNaN(b) ? String(b) : "null");
                        break;
                    case "boolean":
                        c.push(String(b));
                        break;
                    case "function":
                        c.push("null");
                        break;
                    default:
                        throw Error("Unknown type: " + typeof b);
                }
            }
        },
        hi = { '"': '\\"', "\\": "\\\\", "/": "\\/", "\b": "\\b", "\f": "\\f", "\n": "\\n", "\r": "\\r", "\t": "\\t", "\v": "\\u000b" },
        ii = /\uffff/.test("\uffff") ? /[\\"\x00-\x1f\x7f-\uffff]/g : /[\\"\x00-\x1f\x7f-\xff]/g,
        gi = function (a, b) {
            b.push(
                '"',
                a.replace(ii, function (c) {
                    var d = hi[c];
                    d || ((d = "\\u" + (c.charCodeAt(0) | 65536).toString(16).substr(1)), (hi[c] = d));
                    return d;
                }),
                '"'
            );
        };
    var ji = function () {
            this.l = null;
            this.h = "missing-id";
            this.j = !1;
        },
        ki = function (a) {
            var b = null;
            try {
                b = document.getElementsByClassName("lima-exp-data");
            } catch (c) {
                return a.onError("missing-element", a.h), null;
            }
            if (1 < b.length) return a.onError("multiple-elements", a.h), null;
            b = b[0];
            return b ? b.innerHTML : (a.onError("missing-element", a.h), null);
        },
        mi = function () {
            var a = li,
                b = ki(a);
            if (null !== b)
                if (ci(b)) {
                    var c = JSON.parse(b);
                    b = c.experimentIds;
                    var d = c.binaryIdentifier;
                    c = c.adEventId;
                    var e = "string" === typeof d;
                    if ("string" == typeof c) {
                        var f = J();
                        null != c && Mh(f.h, "qqid", c);
                    }
                    e && (a.h = d);
                    if ("string" !== typeof b) a.onError("missing-flags", a.h);
                    else {
                        if (!e) a.onError("missing-binary-id", a.h);
                        a.l = b;
                    }
                } else a.onError("invalid-json", a.h);
        };
    ji.prototype.reset = function () {
        this.l = null;
        this.h = "missing-id";
    };
    var oi = function (a, b, c, d, e) {
        this.id = a;
        this.F = b;
        this.o = c;
        this.h = !1;
        this.l = d;
        this.j = e;
        this.o && ni(this);
    };
    oi.prototype.isSelected = function () {
        return this.h || this.o;
    };
    var ni = function (a) {
            if (a.l && a.j) {
                var b = a.l;
                b && Object.assign(a.j.h, b);
            }
        },
        pi = function () {
            this.h = [];
        },
        qi = function () {
            this.h = new Map();
            this.j = !1;
            this.A = new pi();
            this.B = new oi(0, 0, !1);
            this.l = [this.A];
            this.o = new vd();
        },
        K = function (a) {
            var b = ri;
            if (b.j || b.h.has(a.id) || (null == a.F && null == a.control) || 0 == a.condition) return b.B;
            var c = b.A;
            if (null != a.control)
                for (var d = q(b.l), e = d.next(); !e.done; e = d.next()) {
                    if (((e = e.value), e.h.includes(a.control))) {
                        c = e;
                        break;
                    }
                }
            else null != a.layer && (c = a.layer);
            d = 0;
            null != a.control ? (d = a.control.F) : null != a.F && (d = a.F);
            a = new oi(a.id, d, !!a.Hh, a.flags, b.o);
            c.h.push(a);
            b.l.includes(c) || b.l.push(c);
            b.h.set(a.id, a);
            return a;
        },
        si = function () {
            var a = ri;
            return [].concat(ha(a.h.keys())).filter(function (b) {
                return this.h.get(b).isSelected();
            }, a);
        },
        ti = function (a) {
            var b = ri;
            b.j || (a.h(b.l, b.h), (b.j = !0));
        };
    qi.prototype.reset = function () {
        for (var a = q(this.h), b = a.next(); !b.done; b = a.next()) (b = q(b.value)), b.next(), (b.next().value.h = !1);
        this.j = !1;
        this.o.reset();
    };
    var ri = new qi(),
        vi = function () {
            return ui.h
                .filter(function (a) {
                    return a.isSelected();
                })
                .map(function (a) {
                    return a.id;
                });
        };
    var wi = function () {};
    wi.prototype.h = function (a) {
        a = q(a);
        for (var b = a.next(); !b.done; b = a.next()) {
            var c = 0,
                d = Math.floor(1e3 * Math.random());
            b = q(b.value.h);
            for (var e = b.next(); !e.done; e = b.next())
                if (((e = e.value), (c += e.F), d < c)) {
                    e.h = !0;
                    ni(e);
                    break;
                }
        }
    };
    var yi = function (a) {
        F.call(this, a, -1, xi);
    };
    r(yi, F);
    var xi = [2, 8],
        zi = [3, 4, 5];
    var Bi = function (a) {
        F.call(this, a, -1, Ai);
    };
    r(Bi, F);
    var Ai = [4];
    var Di = function (a) {
        F.call(this, a, -1, Ci);
    };
    r(Di, F);
    var Ci = [5],
        Ei = [1, 2, 3, 6, 7];
    var Gi = function (a) {
        F.call(this, a, -1, Fi);
    };
    r(Gi, F);
    Gi.prototype.getId = function () {
        return pg(this, 1, 0);
    };
    var Fi = [2];
    var Ii = function (a) {
        F.call(this, a, -1, Hi);
    };
    r(Ii, F);
    var Hi = [2];
    var Ki = function (a) {
        F.call(this, a, -1, Ji);
    };
    r(Ki, F);
    var Mi = function (a) {
        F.call(this, a, -1, Li);
    };
    r(Mi, F);
    var Ji = [1, 4, 2, 3],
        Li = [2];
    var Ni = function (a, b) {
            switch (b) {
                case 1:
                    return zg(a, 1, Ei);
                case 2:
                    return zg(a, 2, Ei);
                case 3:
                    return zg(a, 3, Ei);
                case 6:
                    return zg(a, 6, Ei);
                default:
                    return null;
            }
        },
        Oi = function (a, b) {
            if (!a) return null;
            switch (b) {
                case 1:
                    return qg(a, 1);
                case 7:
                    return pg(a, 3, "");
                case 2:
                    return (a = ng(a, 2)), null == a ? 0 : a;
                case 3:
                    return pg(a, 3, "");
                case 6:
                    return mg(a, 4);
                default:
                    return null;
            }
        };
    var Pi = {},
        Qi = ((Pi[47] = xc), Pi);
    function Ri() {
        var a = Si,
            b = vg(new Ki(Ti), Mi, 2);
        1 == b.length &&
            16 == pg(b[0], 1, 0) &&
            vg(b[0], Ii, 2).forEach(function (c) {
                var d = pg(c, 1, 0),
                    e = ug(c, yi, 3),
                    f = a[pg(c, 4, 0)];
                vg(c, Gi, 2).forEach(function (g) {
                    var h = d || pg(g, 4, 0),
                        k = g.getId(),
                        n = e || ug(g, yi, 3);
                    n = n ? zg(n, 3, zi) : null;
                    n = Qi[n];
                    g = Ui(vg(g, Di, 2));
                    K({ id: k, F: h, layer: f, condition: n, flags: g });
                });
            });
    }
    function Ui(a) {
        if (a.length) {
            var b = {};
            a.forEach(function (c) {
                var d = tg(c, Ei),
                    e = ug(c, Bi, 4);
                e && ((c = Ni(c, d)), (d = Oi(e, d)), (b[c] = d));
            });
            return b;
        }
    }
    var Vi = function (a) {
        this.ids = a;
    };
    Vi.prototype.h = function (a, b) {
        a = q(this.ids);
        for (var c = a.next(); !c.done; c = a.next()) if ((c = b.get(c.value))) (c.h = !0), ni(c);
    };
    var Wi = function (a, b) {
        this.ids = a;
        this.j = b;
    };
    r(Wi, Vi);
    Wi.prototype.h = function (a, b) {
        Vi.prototype.h.call(this, a, b);
        var c = [];
        a = [];
        for (var d = q(this.ids), e = d.next(); !e.done; e = d.next()) (e = e.value), b.get(e) ? c.push(e) : a.push(e);
        b = c.map(String).join(",") || "0";
        a = a.map(String).join(",") || "0";
        I(J(), "sei", b);
        I(J(), "nsei", a);
        I(J(), "bi", this.j);
    };
    var Xi = function () {
        ji.apply(this, arguments);
    };
    r(Xi, ji);
    Xi.prototype.onError = function (a, b) {
        var c = J();
        I(c, "eee", a);
        I(c, "bi", b);
    };
    function Yi() {
        return Zi.split(",")
            .map(function (a) {
                return parseInt(a, 10);
            })
            .filter(function (a) {
                return !isNaN(a);
            });
    }
    var ui = new pi(),
        $i = new pi(),
        aj = new pi(),
        bj = new pi(),
        cj = new pi(),
        dj = new pi(),
        ej = new pi(),
        fj = new pi(),
        gj = new pi();
    K({ id: 318475490, F: 0 });
    K({ id: 324123032, F: 0 });
    K({ id: 418572103, F: 0 });
    K({ id: 420706097, F: 10 });
    K({ id: 420706098, F: 10 });
    K({ id: 21062100, F: 0 });
    K({ id: 420706105, F: 0 });
    K({ id: 420706106, F: 0 });
    K({ id: 21064018, F: 0 });
    K({ id: 21064020, F: 0 });
    K({ id: 21064022, F: 0 });
    K({ id: 21064024, F: 0 });
    K({ id: 21064075, F: 0 });
    K({ id: 21064201, F: 0 });
    K({ id: 420706142, F: 0 });
    K({ id: 21064347, F: 0 });
    K({ id: 44745813, F: 0 });
    K({ id: 44746068, F: 0 });
    K({ id: 21064565, F: 0 });
    K({ id: 21064567, F: 0 });
    K({ id: 418572006, F: 10 });
    var hj = K({ id: 44744588, F: 10 }),
        ij = K({ id: 44747319, F: 10 });
    K({ id: 44740339, F: 10 });
    var jj = K({ id: 44740340, F: 10 });
    K({ id: 44749839, F: 0 });
    var kj = K({ id: 44749840, F: 0 });
    K({ id: 44749841, F: 0 });
    var lj = K({ id: 44749842, F: 0 });
    K({ id: 44749843, F: 1 });
    var mj = K({ id: 44749844, F: 1 });
    K({ id: 44749845, F: 1 });
    var nj = K({ id: 44749846, F: 1 });
    K({ id: 44714743, F: 0 });
    K({ id: 44719216, F: 0 });
    K({ id: 44730895, F: 10 });
    K({ id: 44730896, F: 10 });
    K({ id: 44736292, F: 10 });
    K({ id: 44736293, F: 10 });
    K({ id: 31061774, F: 10 });
    var oj = K({ id: 31061775, F: 10 });
    K({ id: 44715336, F: 10 });
    K({ id: 44729309, F: 10 });
    K({ id: 75259410, F: 0 });
    K({ id: 75259412, F: 0 });
    K({ id: 75259413, F: 0 });
    K({ id: 44725355, F: 50, layer: bj });
    var pj = K({ id: 44725356, F: 50, layer: bj });
    K({ id: 44724516, F: 0 });
    K({ id: 44726389, F: 10 });
    K({ id: 44752711, F: 50 });
    K({ id: 44752052, F: 50 });
    K({ id: 44752657, F: 50 });
    K({ id: 44730464, F: 10 });
    K({ id: 44730465, F: 10 });
    K({ id: 44733378, F: 10 });
    K({ id: 44727953, F: 0 });
    K({ id: 44729911, F: 0 });
    K({ id: 44730425, F: 0 });
    K({ id: 44730426, F: 0 });
    K({ id: 44733246, F: 10 });
    K({ id: 44750823, F: 100, layer: dj });
    K({ id: 44750824, F: 100, layer: dj });
    K({ id: 44750822, F: 100, layer: dj });
    K({ id: 44754419, F: 10 });
    K({ id: 44754420, F: 10 });
    K({ id: 44737473, F: 100, layer: $i });
    K({ id: 44737475, F: 100, layer: $i });
    K({ id: 44751785, F: 10 });
    K({ id: 44751786, F: 10 });
    K({ id: 44751889, F: 10 });
    K({ id: 44751890, F: 10 });
    K({ id: 44738437, F: 100, layer: ej });
    var qj = K({ id: 44738438, F: 100, layer: ej });
    K({ id: 44750813, F: 10 });
    K({ id: 44750814, F: 10 });
    K({ id: 44752995, F: 10 });
    K({ id: 44752996, F: 10 });
    K({ id: 44748968, F: 0 });
    var rj = K({ id: 44748969, F: 0 });
    K({ id: 44752538, F: 0 });
    K({ id: 44754608, F: 10 });
    K({ id: 44754609, F: 10 });
    K({ id: 44757674, F: 10 });
    var sj = K({ id: 44757675, F: 10 });
    K({ id: 44756935, F: 10 });
    K({ id: 44756936, F: 10 });
    K({ id: 44756710, F: 10 });
    K({ id: 44756711, F: 10 });
    K({ id: 44757316, F: 0 });
    K({ id: 44758347, F: 100, layer: fj });
    K({ id: 44758348, F: 100, layer: fj });
    K({ id: 44757910, F: 10 });
    K({ id: 44757911, F: 10 });
    K({ id: 44760639, F: 10 });
    K({ id: 44760640, F: 10 });
    K({ id: 44758266, F: 10, layer: aj });
    K({ id: 44758267, F: 10, layer: aj });
    K({ id: 44760810, F: 0, layer: gj });
    var tj = {},
        Si = ((tj[32] = ui), (tj[35] = cj), tj);
    Si = void 0 === Si ? {} : Si;
    if (!/^\{+IMA_EXPERIMENT_STATE_JSPB\}+$/.test("{{IMA_EXPERIMENT_STATE_JSPB}}"))
        try {
            var Ti = JSON.parse("{{IMA_EXPERIMENT_STATE_JSPB}}");
            Ti instanceof Array && Ri();
        } catch (a) {
            I(J(), "espe", a.message);
        }
    if ("undefined" === typeof window.v8_flag_map) {
        var li = G(Xi);
        li.j || (mi(), (li.j = !0));
        var Zi = li.l,
            uj;
        li.j || (mi(), (li.j = !0));
        uj = li.h;
        if (null != Zi) {
            var vj = new Wi(Yi(), uj);
            ti(vj);
        }
    }
    ri.reset();
    ti(new wi());
    t.console && "function" === typeof t.console.log && Xa(t.console.log, t.console);
    var wj = function (a) {
        for (var b = [], c = (a = z(a.ownerDocument)); c != a.top; c = c.parent)
            if (c.frameElement) b.push(c.frameElement);
            else break;
        return b;
    };
    function xj(a) {
        a && "function" == typeof a.dispose && a.dispose();
    }
    var L = function () {
        this.J = this.J;
        this.H = this.H;
    };
    L.prototype.J = !1;
    L.prototype.Ia = function () {
        return this.J;
    };
    L.prototype.dispose = function () {
        this.J || ((this.J = !0), this.N());
    };
    var zj = function (a, b) {
            yj(a, Ya(xj, b));
        },
        yj = function (a, b) {
            a.J ? b() : (a.H || (a.H = []), a.H.push(b));
        };
    L.prototype.N = function () {
        if (this.H) for (; this.H.length; ) this.H.shift()();
    };
    var Aj = function (a, b) {
        this.type = a;
        this.currentTarget = this.target = b;
        this.defaultPrevented = this.j = !1;
    };
    Aj.prototype.stopPropagation = function () {
        this.j = !0;
    };
    Aj.prototype.preventDefault = function () {
        this.defaultPrevented = !0;
    };
    var Bj = (function () {
        if (!t.addEventListener || !Object.defineProperty) return !1;
        var a = !1,
            b = Object.defineProperty({}, "passive", {
                get: function () {
                    a = !0;
                },
            });
        try {
            t.addEventListener("test", Na, b), t.removeEventListener("test", Na, b);
        } catch (c) {}
        return a;
    })();
    var Cj = function (a, b) {
        Aj.call(this, a ? a.type : "");
        this.relatedTarget = this.currentTarget = this.target = null;
        this.button = this.screenY = this.screenX = this.clientY = this.clientX = 0;
        this.key = "";
        this.keyCode = 0;
        this.metaKey = this.shiftKey = this.altKey = this.ctrlKey = !1;
        this.state = null;
        this.pointerId = 0;
        this.pointerType = "";
        this.h = null;
        a && this.init(a, b);
    };
    $a(Cj, Aj);
    var Dj = { 2: "touch", 3: "pen", 4: "mouse" };
    Cj.prototype.init = function (a, b) {
        var c = (this.type = a.type),
            d = a.changedTouches && a.changedTouches.length ? a.changedTouches[0] : null;
        this.target = a.target || a.srcElement;
        this.currentTarget = b;
        (b = a.relatedTarget) ? ec && (Zb(b, "nodeName") || (b = null)) : "mouseover" == c ? (b = a.fromElement) : "mouseout" == c && (b = a.toElement);
        this.relatedTarget = b;
        d
            ? ((this.clientX = void 0 !== d.clientX ? d.clientX : d.pageX), (this.clientY = void 0 !== d.clientY ? d.clientY : d.pageY), (this.screenX = d.screenX || 0), (this.screenY = d.screenY || 0))
            : ((this.clientX = void 0 !== a.clientX ? a.clientX : a.pageX), (this.clientY = void 0 !== a.clientY ? a.clientY : a.pageY), (this.screenX = a.screenX || 0), (this.screenY = a.screenY || 0));
        this.button = a.button;
        this.keyCode = a.keyCode || 0;
        this.key = a.key || "";
        this.ctrlKey = a.ctrlKey;
        this.altKey = a.altKey;
        this.shiftKey = a.shiftKey;
        this.metaKey = a.metaKey;
        this.pointerId = a.pointerId || 0;
        this.pointerType = "string" === typeof a.pointerType ? a.pointerType : Dj[a.pointerType] || "";
        this.state = a.state;
        this.h = a;
        a.defaultPrevented && Cj.ya.preventDefault.call(this);
    };
    Cj.prototype.stopPropagation = function () {
        Cj.ya.stopPropagation.call(this);
        this.h.stopPropagation ? this.h.stopPropagation() : (this.h.cancelBubble = !0);
    };
    Cj.prototype.preventDefault = function () {
        Cj.ya.preventDefault.call(this);
        var a = this.h;
        a.preventDefault ? a.preventDefault() : (a.returnValue = !1);
    };
    var Ej = "closure_listenable_" + ((1e6 * Math.random()) | 0),
        Fj = function (a) {
            return !(!a || !a[Ej]);
        };
    var Gj = 0;
    var Hj = function (a, b, c, d, e) {
            this.listener = a;
            this.proxy = null;
            this.src = b;
            this.type = c;
            this.capture = !!d;
            this.Vb = e;
            this.key = ++Gj;
            this.Ib = this.Pb = !1;
        },
        Ij = function (a) {
            a.Ib = !0;
            a.listener = null;
            a.proxy = null;
            a.src = null;
            a.Vb = null;
        };
    var Jj = function (a) {
        this.src = a;
        this.listeners = {};
        this.h = 0;
    };
    Jj.prototype.add = function (a, b, c, d, e) {
        var f = a.toString();
        a = this.listeners[f];
        a || ((a = this.listeners[f] = []), this.h++);
        var g = Kj(a, b, d, e);
        -1 < g ? ((b = a[g]), c || (b.Pb = !1)) : ((b = new Hj(b, this.src, f, !!d, e)), (b.Pb = c), a.push(b));
        return b;
    };
    Jj.prototype.remove = function (a, b, c, d) {
        a = a.toString();
        if (!(a in this.listeners)) return !1;
        var e = this.listeners[a];
        b = Kj(e, b, c, d);
        return -1 < b ? (Ij(e[b]), Qb(e, b), 0 == e.length && (delete this.listeners[a], this.h--), !0) : !1;
    };
    var Lj = function (a, b) {
        var c = b.type;
        c in a.listeners && Pb(a.listeners[c], b) && (Ij(b), 0 == a.listeners[c].length && (delete a.listeners[c], a.h--));
    };
    Jj.prototype.Cb = function (a, b, c, d) {
        a = this.listeners[a.toString()];
        var e = -1;
        a && (e = Kj(a, b, c, d));
        return -1 < e ? a[e] : null;
    };
    var Kj = function (a, b, c, d) {
        for (var e = 0; e < a.length; ++e) {
            var f = a[e];
            if (!f.Ib && f.listener == b && f.capture == !!c && f.Vb == d) return e;
        }
        return -1;
    };
    var Mj = "closure_lm_" + ((1e6 * Math.random()) | 0),
        Nj = {},
        Oj = 0,
        Qj = function (a, b, c, d, e) {
            if (d && d.once) return Pj(a, b, c, d, e);
            if (Array.isArray(b)) {
                for (var f = 0; f < b.length; f++) Qj(a, b[f], c, d, e);
                return null;
            }
            c = Rj(c);
            return Fj(a) ? a.P(b, c, Qa(d) ? !!d.capture : !!d, e) : Sj(a, b, c, !1, d, e);
        },
        Sj = function (a, b, c, d, e, f) {
            if (!b) throw Error("Invalid event type");
            var g = Qa(e) ? !!e.capture : !!e,
                h = Tj(a);
            h || (a[Mj] = h = new Jj(a));
            c = h.add(b, c, d, g, f);
            if (c.proxy) return c;
            d = Uj();
            c.proxy = d;
            d.src = a;
            d.listener = c;
            if (a.addEventListener) Bj || (e = g), void 0 === e && (e = !1), a.addEventListener(b.toString(), d, e);
            else if (a.attachEvent) a.attachEvent(Vj(b.toString()), d);
            else if (a.addListener && a.removeListener) a.addListener(d);
            else throw Error("addEventListener and attachEvent are unavailable.");
            Oj++;
            return c;
        },
        Uj = function () {
            var a = Wj,
                b = function (c) {
                    return a.call(b.src, b.listener, c);
                };
            return b;
        },
        Pj = function (a, b, c, d, e) {
            if (Array.isArray(b)) {
                for (var f = 0; f < b.length; f++) Pj(a, b[f], c, d, e);
                return null;
            }
            c = Rj(c);
            return Fj(a) ? a.Gb(b, c, Qa(d) ? !!d.capture : !!d, e) : Sj(a, b, c, !0, d, e);
        },
        Xj = function (a, b, c, d, e) {
            if (Array.isArray(b)) for (var f = 0; f < b.length; f++) Xj(a, b[f], c, d, e);
            else (d = Qa(d) ? !!d.capture : !!d), (c = Rj(c)), Fj(a) ? a.Wa(b, c, d, e) : a && (a = Tj(a)) && (b = a.Cb(b, c, d, e)) && Yj(b);
        },
        Yj = function (a) {
            if ("number" !== typeof a && a && !a.Ib) {
                var b = a.src;
                if (Fj(b)) Lj(b.o, a);
                else {
                    var c = a.type,
                        d = a.proxy;
                    b.removeEventListener ? b.removeEventListener(c, d, a.capture) : b.detachEvent ? b.detachEvent(Vj(c), d) : b.addListener && b.removeListener && b.removeListener(d);
                    Oj--;
                    (c = Tj(b)) ? (Lj(c, a), 0 == c.h && ((c.src = null), (b[Mj] = null))) : Ij(a);
                }
            }
        },
        Vj = function (a) {
            return a in Nj ? Nj[a] : (Nj[a] = "on" + a);
        },
        Wj = function (a, b) {
            if (a.Ib) a = !0;
            else {
                b = new Cj(b, this);
                var c = a.listener,
                    d = a.Vb || a.src;
                a.Pb && Yj(a);
                a = c.call(d, b);
            }
            return a;
        },
        Tj = function (a) {
            a = a[Mj];
            return a instanceof Jj ? a : null;
        },
        Zj = "__closure_events_fn_" + ((1e9 * Math.random()) >>> 0),
        Rj = function (a) {
            if ("function" === typeof a) return a;
            a[Zj] ||
                (a[Zj] = function (b) {
                    return a.handleEvent(b);
                });
            return a[Zj];
        };
    var M = function () {
        L.call(this);
        this.o = new Jj(this);
        this.Ob = this;
        this.pa = null;
    };
    $a(M, L);
    M.prototype[Ej] = !0;
    l = M.prototype;
    l.addEventListener = function (a, b, c, d) {
        Qj(this, a, b, c, d);
    };
    l.removeEventListener = function (a, b, c, d) {
        Xj(this, a, b, c, d);
    };
    l.dispatchEvent = function (a) {
        var b,
            c = this.pa;
        if (c) for (b = []; c; c = c.pa) b.push(c);
        c = this.Ob;
        var d = a.type || a;
        if ("string" === typeof a) a = new Aj(a, c);
        else if (a instanceof Aj) a.target = a.target || c;
        else {
            var e = a;
            a = new Aj(d, c);
            Nd(a, e);
        }
        e = !0;
        if (b)
            for (var f = b.length - 1; !a.j && 0 <= f; f--) {
                var g = (a.currentTarget = b[f]);
                e = ak(g, d, !0, a) && e;
            }
        a.j || ((g = a.currentTarget = c), (e = ak(g, d, !0, a) && e), a.j || (e = ak(g, d, !1, a) && e));
        if (b) for (f = 0; !a.j && f < b.length; f++) (g = a.currentTarget = b[f]), (e = ak(g, d, !1, a) && e);
        return e;
    };
    l.N = function () {
        M.ya.N.call(this);
        if (this.o) {
            var a = this.o,
                b = 0,
                c;
            for (c in a.listeners) {
                for (var d = a.listeners[c], e = 0; e < d.length; e++) ++b, Ij(d[e]);
                delete a.listeners[c];
                a.h--;
            }
        }
        this.pa = null;
    };
    l.P = function (a, b, c, d) {
        return this.o.add(String(a), b, !1, c, d);
    };
    l.Gb = function (a, b, c, d) {
        return this.o.add(String(a), b, !0, c, d);
    };
    l.Wa = function (a, b, c, d) {
        this.o.remove(String(a), b, c, d);
    };
    var ak = function (a, b, c, d) {
        b = a.o.listeners[String(b)];
        if (!b) return !0;
        b = b.concat();
        for (var e = !0, f = 0; f < b.length; ++f) {
            var g = b[f];
            if (g && !g.Ib && g.capture == c) {
                var h = g.listener,
                    k = g.Vb || g.src;
                g.Pb && Lj(a.o, g);
                e = !1 !== h.call(k, d) && e;
            }
        }
        return e && !d.defaultPrevented;
    };
    M.prototype.Cb = function (a, b, c, d) {
        return this.o.Cb(String(a), b, c, d);
    };
    var bk = function (a, b) {
        this.l = a;
        this.o = b;
        this.j = 0;
        this.h = null;
    };
    bk.prototype.get = function () {
        if (0 < this.j) {
            this.j--;
            var a = this.h;
            this.h = a.next;
            a.next = null;
        } else a = this.l();
        return a;
    };
    var ck = function (a, b) {
        a.o(b);
        100 > a.j && (a.j++, (b.next = a.h), (a.h = b));
    };
    var dk,
        ek = function () {
            var a = t.MessageChannel;
            "undefined" === typeof a &&
                "undefined" !== typeof window &&
                window.postMessage &&
                window.addEventListener &&
                !w("Presto") &&
                (a = function () {
                    var e = cf(document, "IFRAME");
                    e.style.display = "none";
                    document.documentElement.appendChild(e);
                    var f = e.contentWindow;
                    e = f.document;
                    e.open();
                    e.close();
                    var g = "callImmediate" + Math.random(),
                        h = "file:" == f.location.protocol ? "*" : f.location.protocol + "//" + f.location.host;
                    e = Xa(function (k) {
                        if (("*" == h || k.origin == h) && k.data == g) this.port1.onmessage();
                    }, this);
                    f.addEventListener("message", e, !1);
                    this.port1 = {};
                    this.port2 = {
                        postMessage: function () {
                            f.postMessage(g, h);
                        },
                    };
                });
            if ("undefined" !== typeof a && !zb()) {
                var b = new a(),
                    c = {},
                    d = c;
                b.port1.onmessage = function () {
                    if (void 0 !== c.next) {
                        c = c.next;
                        var e = c.yd;
                        c.yd = null;
                        e();
                    }
                };
                return function (e) {
                    d.next = { yd: e };
                    d = d.next;
                    b.port2.postMessage(0);
                };
            }
            return function (e) {
                t.setTimeout(e, 0);
            };
        };
    function fk(a) {
        t.setTimeout(function () {
            throw a;
        }, 0);
    }
    var gk = function () {
        this.j = this.h = null;
    };
    gk.prototype.add = function (a, b) {
        var c = hk.get();
        c.set(a, b);
        this.j ? (this.j.next = c) : (this.h = c);
        this.j = c;
    };
    gk.prototype.remove = function () {
        var a = null;
        this.h && ((a = this.h), (this.h = this.h.next), this.h || (this.j = null), (a.next = null));
        return a;
    };
    var hk = new bk(
            function () {
                return new ik();
            },
            function (a) {
                return a.reset();
            }
        ),
        ik = function () {
            this.next = this.scope = this.h = null;
        };
    ik.prototype.set = function (a, b) {
        this.h = a;
        this.scope = b;
        this.next = null;
    };
    ik.prototype.reset = function () {
        this.next = this.scope = this.h = null;
    };
    var nk = function (a, b) {
            jk || kk();
            lk || (jk(), (lk = !0));
            mk.add(a, b);
        },
        jk,
        kk = function () {
            if (t.Promise && t.Promise.resolve) {
                var a = t.Promise.resolve(void 0);
                jk = function () {
                    a.then(ok);
                };
            } else
                jk = function () {
                    var b = ok;
                    "function" !== typeof t.setImmediate || (t.Window && t.Window.prototype && !w("Edge") && t.Window.prototype.setImmediate == t.setImmediate) ? (dk || (dk = ek()), dk(b)) : t.setImmediate(b);
                };
        },
        lk = !1,
        mk = new gk(),
        ok = function () {
            for (var a; (a = mk.remove()); ) {
                try {
                    a.h.call(a.scope);
                } catch (b) {
                    fk(b);
                }
                ck(hk, a);
            }
            lk = !1;
        };
    var pk = function (a) {
        if (!a) return !1;
        try {
            return !!a.$goog_Thenable;
        } catch (b) {
            return !1;
        }
    };
    var rk = function (a) {
            this.h = 0;
            this.C = void 0;
            this.o = this.j = this.l = null;
            this.A = this.B = !1;
            if (a != Na)
                try {
                    var b = this;
                    a.call(
                        void 0,
                        function (c) {
                            qk(b, 2, c);
                        },
                        function (c) {
                            qk(b, 3, c);
                        }
                    );
                } catch (c) {
                    qk(this, 3, c);
                }
        },
        sk = function () {
            this.next = this.context = this.onRejected = this.j = this.h = null;
            this.l = !1;
        };
    sk.prototype.reset = function () {
        this.context = this.onRejected = this.j = this.h = null;
        this.l = !1;
    };
    var tk = new bk(
            function () {
                return new sk();
            },
            function (a) {
                a.reset();
            }
        ),
        uk = function (a, b, c) {
            var d = tk.get();
            d.j = a;
            d.onRejected = b;
            d.context = c;
            return d;
        };
    rk.prototype.then = function (a, b, c) {
        return vk(this, "function" === typeof a ? a : null, "function" === typeof b ? b : null, c);
    };
    rk.prototype.$goog_Thenable = !0;
    rk.prototype.H = function (a, b) {
        return vk(this, null, a, b);
    };
    rk.prototype.catch = rk.prototype.H;
    rk.prototype.cancel = function (a) {
        if (0 == this.h) {
            var b = new wk(a);
            nk(function () {
                xk(this, b);
            }, this);
        }
    };
    var xk = function (a, b) {
            if (0 == a.h)
                if (a.l) {
                    var c = a.l;
                    if (c.j) {
                        for (var d = 0, e = null, f = null, g = c.j; g && (g.l || (d++, g.h == a && (e = g), !(e && 1 < d))); g = g.next) e || (f = g);
                        e && (0 == c.h && 1 == d ? xk(c, b) : (f ? ((d = f), d.next == c.o && (c.o = d), (d.next = d.next.next)) : yk(c), zk(c, e, 3, b)));
                    }
                    a.l = null;
                } else qk(a, 3, b);
        },
        Bk = function (a, b) {
            a.j || (2 != a.h && 3 != a.h) || Ak(a);
            a.o ? (a.o.next = b) : (a.j = b);
            a.o = b;
        },
        vk = function (a, b, c, d) {
            var e = uk(null, null, null);
            e.h = new rk(function (f, g) {
                e.j = b
                    ? function (h) {
                          try {
                              var k = b.call(d, h);
                              f(k);
                          } catch (n) {
                              g(n);
                          }
                      }
                    : f;
                e.onRejected = c
                    ? function (h) {
                          try {
                              var k = c.call(d, h);
                              void 0 === k && h instanceof wk ? g(h) : f(k);
                          } catch (n) {
                              g(n);
                          }
                      }
                    : g;
            });
            e.h.l = a;
            Bk(a, e);
            return e.h;
        };
    rk.prototype.D = function (a) {
        this.h = 0;
        qk(this, 2, a);
    };
    rk.prototype.G = function (a) {
        this.h = 0;
        qk(this, 3, a);
    };
    var qk = function (a, b, c) {
            if (0 == a.h) {
                a === c && ((b = 3), (c = new TypeError("Promise cannot resolve to itself")));
                a.h = 1;
                a: {
                    var d = c,
                        e = a.D,
                        f = a.G;
                    if (d instanceof rk) {
                        Bk(d, uk(e || Na, f || null, a));
                        var g = !0;
                    } else if (pk(d)) d.then(e, f, a), (g = !0);
                    else {
                        if (Qa(d))
                            try {
                                var h = d.then;
                                if ("function" === typeof h) {
                                    Ck(d, h, e, f, a);
                                    g = !0;
                                    break a;
                                }
                            } catch (k) {
                                f.call(a, k);
                                g = !0;
                                break a;
                            }
                        g = !1;
                    }
                }
                g || ((a.C = c), (a.h = b), (a.l = null), Ak(a), 3 != b || c instanceof wk || Dk(a, c));
            }
        },
        Ck = function (a, b, c, d, e) {
            var f = !1,
                g = function (k) {
                    f || ((f = !0), c.call(e, k));
                },
                h = function (k) {
                    f || ((f = !0), d.call(e, k));
                };
            try {
                b.call(a, g, h);
            } catch (k) {
                h(k);
            }
        },
        Ak = function (a) {
            a.B || ((a.B = !0), nk(a.J, a));
        },
        yk = function (a) {
            var b = null;
            a.j && ((b = a.j), (a.j = b.next), (b.next = null));
            a.j || (a.o = null);
            return b;
        };
    rk.prototype.J = function () {
        for (var a; (a = yk(this)); ) zk(this, a, this.h, this.C);
        this.B = !1;
    };
    var zk = function (a, b, c, d) {
            if (3 == c && b.onRejected && !b.l) for (; a && a.A; a = a.l) a.A = !1;
            if (b.h) (b.h.l = null), Ek(b, c, d);
            else
                try {
                    b.l ? b.j.call(b.context) : Ek(b, c, d);
                } catch (e) {
                    Fk.call(null, e);
                }
            ck(tk, b);
        },
        Ek = function (a, b, c) {
            2 == b ? a.j.call(a.context, c) : a.onRejected && a.onRejected.call(a.context, c);
        },
        Dk = function (a, b) {
            a.A = !0;
            nk(function () {
                a.A && Fk.call(null, b);
            });
        },
        Fk = fk,
        wk = function (a) {
            bb.call(this, a);
        };
    $a(wk, bb);
    wk.prototype.name = "cancel";
    var Gk = function (a, b) {
        M.call(this);
        this.j = a || 1;
        this.h = b || t;
        this.l = Xa(this.xf, this);
        this.A = Za();
    };
    $a(Gk, M);
    l = Gk.prototype;
    l.enabled = !1;
    l.Ea = null;
    l.xf = function () {
        if (this.enabled) {
            var a = Za() - this.A;
            0 < a && a < 0.8 * this.j ? (this.Ea = this.h.setTimeout(this.l, this.j - a)) : (this.Ea && (this.h.clearTimeout(this.Ea), (this.Ea = null)), this.dispatchEvent("tick"), this.enabled && (this.stop(), this.start()));
        }
    };
    l.start = function () {
        this.enabled = !0;
        this.Ea || ((this.Ea = this.h.setTimeout(this.l, this.j)), (this.A = Za()));
    };
    l.stop = function () {
        this.enabled = !1;
        this.Ea && (this.h.clearTimeout(this.Ea), (this.Ea = null));
    };
    l.N = function () {
        Gk.ya.N.call(this);
        this.stop();
        delete this.h;
    };
    var Hk = function (a, b, c) {
            if ("function" === typeof a) c && (a = Xa(a, c));
            else if (a && "function" == typeof a.handleEvent) a = Xa(a.handleEvent, a);
            else throw Error("Invalid listener argument");
            return 2147483647 < Number(b) ? -1 : t.setTimeout(a, b || 0);
        },
        Ik = function () {
            var a = null;
            return new rk(function (b, c) {
                a = Hk(function () {
                    b("timed out");
                }, 200);
                -1 == a && c(Error("Failed to schedule timer."));
            }).H(function (b) {
                t.clearTimeout(a);
                throw b;
            });
        };
    var Jk = function () {
        return Math.round(Date.now() / 1e3);
    };
    var Kk = function () {
        this.h = {};
        return this;
    };
    Kk.prototype.remove = function (a) {
        var b = this.h;
        a in b && delete b[a];
    };
    Kk.prototype.set = function (a, b) {
        this.h[a] = b;
    };
    var Lk = function (a, b) {
        a.h.eb = Ld(a.h, "eb", 0) | b;
    };
    Kk.prototype.get = function (a) {
        return Ld(this.h, a, null);
    };
    Kk.prototype.aa = function () {
        var a = [],
            b;
        for (b in this.h) a.push(b + this.h[b]);
        return a.join("_");
    };
    var Mk = null,
        Nk = function () {
            this.h = {};
            this.j = 0;
        },
        Ok = function () {
            Mk || (Mk = new Nk());
            return Mk;
        },
        Pk = function (a, b) {
            a.h[b.getName()] = b;
        };
    Nk.prototype.aa = function (a) {
        var b = [];
        a || (a = 0);
        for (var c in this.h) {
            var d = this.h[c];
            d instanceof Qk ? d.h && (a |= d.A) : (d = this.h[c].aa()) && b.push(c + d);
        }
        b.push("eb" + String(a));
        return b.join("_");
    };
    var Rk = function (a, b) {
        this.o = a;
        this.l = !0;
        this.h = b;
    };
    Rk.prototype.getName = function () {
        return this.o;
    };
    Rk.prototype.aa = function () {
        return this.l ? this.j() : "";
    };
    Rk.prototype.j = function () {
        return String(this.h);
    };
    var Qk = function (a, b) {
        Rk.call(this, String(a), b);
        this.A = a;
        this.h = !!b;
    };
    r(Qk, Rk);
    Qk.prototype.j = function () {
        return this.h ? "1" : "0";
    };
    var Sk = function (a, b) {
        Rk.call(this, a, b);
    };
    r(Sk, Rk);
    Sk.prototype.j = function () {
        return this.h ? Math.round(this.h.top) + "." + Math.round(this.h.left) + "." + (Math.round(this.h.top) + Math.round(this.h.height)) + "." + (Math.round(this.h.left) + Math.round(this.h.width)) : "";
    };
    var Tk = function (a) {
        if (a.match(/^-?[0-9]+\.-?[0-9]+\.-?[0-9]+\.-?[0-9]+$/)) {
            a = a.split(".");
            var b = Number(a[0]),
                c = Number(a[1]);
            return new Sk("", new Kf(c, b, Number(a[3]) - c, Number(a[2]) - b));
        }
        return new Sk("", new Kf(0, 0, 0, 0));
    };
    var Vk = function (a, b) {
            if ("string" === typeof b) (b = Uk(a, b)) && (a.style[b] = void 0);
            else
                for (var c in b) {
                    var d = a,
                        e = b[c],
                        f = Uk(d, c);
                    f && (d.style[f] = e);
                }
        },
        Wk = {},
        Uk = function (a, b) {
            var c = Wk[b];
            if (!c) {
                var d = Pe(b);
                c = d;
                void 0 === a.style[d] && ((d = (fc ? "Webkit" : ec ? "Moz" : cc ? "ms" : null) + Re(d)), void 0 !== a.style[d] && (c = d));
                Wk[b] = c;
            }
            return c;
        },
        Xk = function (a, b) {
            var c = a.style[Pe(b)];
            return "undefined" !== typeof c ? c : a.style[Uk(a, b)] || "";
        },
        Yk = function (a, b) {
            var c = Ue(a);
            return c.defaultView && c.defaultView.getComputedStyle && (a = c.defaultView.getComputedStyle(a, null)) ? a[b] || a.getPropertyValue(b) || "" : "";
        },
        Zk = function (a) {
            try {
                return a.getBoundingClientRect();
            } catch (b) {
                return { left: 0, top: 0, right: 0, bottom: 0 };
            }
        },
        $k = function (a) {
            var b = Ue(a),
                c = new Je(0, 0);
            var d = b ? Ue(b) : document;
            d = !cc || 9 <= Number(wc) || $e(Ve(d).h) ? d.documentElement : d.body;
            if (a == d) return c;
            a = Zk(a);
            b = bf(Ve(b).h);
            c.x = a.left + b.x;
            c.y = a.top + b.y;
            return c;
        },
        al = function (a, b) {
            var c = new Je(0, 0),
                d = z(Ue(a));
            if (!Zb(d, "parent")) return c;
            do {
                if (d == b) var e = $k(a);
                else (e = Zk(a)), (e = new Je(e.left, e.top));
                c.x += e.x;
                c.y += e.y;
            } while (d && d != b && d != d.parent && (a = d.frameElement) && (d = d.parent));
            return c;
        },
        bl = function () {
            var a = "100%";
            "number" == typeof a && (a = Math.round(a) + "px");
            return a;
        },
        dl = function (a) {
            var b = cl;
            if ("none" != (Yk(a, "display") || (a.currentStyle ? a.currentStyle.display : null) || (a.style && a.style.display))) return b(a);
            var c = a.style,
                d = c.display,
                e = c.visibility,
                f = c.position;
            c.visibility = "hidden";
            c.position = "absolute";
            c.display = "inline";
            a = b(a);
            c.display = d;
            c.position = f;
            c.visibility = e;
            return a;
        },
        cl = function (a) {
            var b = a.offsetWidth,
                c = a.offsetHeight,
                d = fc && !b && !c;
            return (void 0 === b || d) && a.getBoundingClientRect ? ((a = Zk(a)), new y(a.right - a.left, a.bottom - a.top)) : new y(b, c);
        },
        hl = function (a) {
            var b = Ue(a),
                c = cc && a.currentStyle;
            if (c && $e(Ve(b).h) && "auto" != c.width && "auto" != c.height && !c.boxSizing) return (b = el(a, c.width, "width", "pixelWidth")), (a = el(a, c.height, "height", "pixelHeight")), new y(b, a);
            c = new y(a.offsetWidth, a.offsetHeight);
            if (cc) {
                b = fl(a, "paddingLeft");
                var d = fl(a, "paddingRight"),
                    e = fl(a, "paddingTop"),
                    f = fl(a, "paddingBottom");
                b = new B(e, d, f, b);
            } else (b = Yk(a, "paddingLeft")), (d = Yk(a, "paddingRight")), (e = Yk(a, "paddingTop")), (f = Yk(a, "paddingBottom")), (b = new B(parseFloat(e), parseFloat(d), parseFloat(f), parseFloat(b)));
            !cc || 9 <= Number(wc)
                ? ((d = Yk(a, "borderLeftWidth")), (e = Yk(a, "borderRightWidth")), (f = Yk(a, "borderTopWidth")), (a = Yk(a, "borderBottomWidth")), (a = new B(parseFloat(f), parseFloat(e), parseFloat(a), parseFloat(d))))
                : ((d = gl(a, "borderLeft")), (e = gl(a, "borderRight")), (f = gl(a, "borderTop")), (a = gl(a, "borderBottom")), (a = new B(f, e, a, d)));
            return new y(c.width - a.left - b.left - b.right - a.right, c.height - a.top - b.top - b.bottom - a.bottom);
        },
        el = function (a, b, c, d) {
            if (/^\d+px?$/.test(b)) return parseInt(b, 10);
            var e = a.style[c],
                f = a.runtimeStyle[c];
            a.runtimeStyle[c] = a.currentStyle[c];
            a.style[c] = b;
            b = a.style[d];
            a.style[c] = e;
            a.runtimeStyle[c] = f;
            return +b;
        },
        fl = function (a, b) {
            return (b = a.currentStyle ? a.currentStyle[b] : null) ? el(a, b, "left", "pixelLeft") : 0;
        },
        il = { thin: 2, medium: 4, thick: 6 },
        gl = function (a, b) {
            if ("none" == (a.currentStyle ? a.currentStyle[b + "Style"] : null)) return 0;
            b = a.currentStyle ? a.currentStyle[b + "Width"] : null;
            return b in il ? il[b] : el(a, b, "left", "pixelLeft");
        };
    var jl = function (a) {
            var b = new Kf(-Number.MAX_VALUE / 2, -Number.MAX_VALUE / 2, Number.MAX_VALUE, Number.MAX_VALUE),
                c = new Kf(0, 0, 0, 0);
            if (!a || 0 == a.length) return c;
            for (var d = 0; d < a.length; d++) {
                a: {
                    var e = b;
                    var f = a[d],
                        g = Math.max(e.left, f.left),
                        h = Math.min(e.left + e.width, f.left + f.width);
                    if (g <= h) {
                        var k = Math.max(e.top, f.top);
                        f = Math.min(e.top + e.height, f.top + f.height);
                        if (k <= f) {
                            e.left = g;
                            e.top = k;
                            e.width = h - g;
                            e.height = f - k;
                            e = !0;
                            break a;
                        }
                    }
                    e = !1;
                }
                if (!e) return c;
            }
            return b;
        },
        kl = function (a, b) {
            var c = a.getBoundingClientRect();
            a = al(a, b);
            return new Kf(Math.round(a.x), Math.round(a.y), Math.round(c.right - c.left), Math.round(c.bottom - c.top));
        },
        ll = function (a, b, c) {
            if (b && c) {
                a: {
                    var d = Math.max(b.left, c.left);
                    var e = Math.min(b.left + b.width, c.left + c.width);
                    if (d <= e) {
                        var f = Math.max(b.top, c.top),
                            g = Math.min(b.top + b.height, c.top + c.height);
                        if (f <= g) {
                            d = new Kf(d, f, e - d, g - f);
                            break a;
                        }
                    }
                    d = null;
                }
                e = d ? d.height * d.width : 0;
                f = d ? b.height * b.width : 0;
                d = d && f ? Math.round((e / f) * 100) : 0;
                Pk(a, new Rk("vp", d));
                d && 0 < d ? ((e = Lf(b)), (f = Lf(c)), (e = e.top >= f.top && e.top < f.bottom)) : (e = !1);
                Pk(a, new Qk(512, e));
                d && 0 < d ? ((e = Lf(b)), (f = Lf(c)), (e = e.bottom <= f.bottom && e.bottom > f.top)) : (e = !1);
                Pk(a, new Qk(1024, e));
                d && 0 < d ? ((e = Lf(b)), (f = Lf(c)), (e = e.left >= f.left && e.left < f.right)) : (e = !1);
                Pk(a, new Qk(2048, e));
                d && 0 < d ? ((b = Lf(b)), (c = Lf(c)), (c = b.right <= c.right && b.right > c.left)) : (c = !1);
                Pk(a, new Qk(4096, c));
            }
        };
    var ml = function (a, b) {
        var c = 0;
        Ed(z(), "ima", "video", "client", "tagged") && (c = 1);
        var d = null;
        a && (d = a());
        if (d) {
            a = Ok();
            a.h = {};
            var e = new Qk(32, !0);
            e.l = !1;
            Pk(a, e);
            e = z().document;
            e = e.visibilityState || e.webkitVisibilityState || e.mozVisibilityState || e.msVisibilityState || "";
            Pk(a, new Qk(64, "hidden" != e.toLowerCase().substring(e.length - 6) ? !0 : !1));
            try {
                var f = z().top;
                try {
                    var g = !!f.location.href || "" === f.location.href;
                } catch (m) {
                    g = !1;
                }
                if (g) {
                    var h = wj(d);
                    var k = h && 0 != h.length ? "1" : "0";
                } else k = "2";
            } catch (m) {
                k = "2";
            }
            Pk(a, new Qk(256, "2" == k));
            Pk(a, new Qk(128, "1" == k));
            h = g = z().top;
            "2" == k && (h = z());
            f = kl(d, h);
            Pk(a, new Sk("er", f));
            try {
                var n = h.document && !h.document.body ? null : af(h || window);
            } catch (m) {
                n = null;
            }
            n ? ((h = bf(Ve(h.document).h)), Pk(a, new Qk(16384, !!h)), (n = h ? new Kf(h.x, h.y, n.width, n.height) : null)) : (n = null);
            Pk(a, new Sk("vi", n));
            if (n && "1" == k) {
                k = wj(d);
                d = [];
                for (h = 0; h < k.length; h++) (e = kl(k[h], g)) && d.push(e);
                d.push(n);
                n = jl(d);
            }
            ll(a, f, n);
            a.j && Pk(a, new Rk("ts", Jk() - a.j));
            a.j = Jk();
        } else (a = Ok()), (a.h = {}), (a.j = Jk()), Pk(a, new Qk(32, !1));
        this.l = a;
        this.h = new Kk();
        this.h.set("ve", 4);
        c && Lk(this.h, 1);
        Ed(z(), "ima", "video", "client", "crossdomainTag") && Lk(this.h, 4);
        Ed(z(), "ima", "video", "client", "sdkTag") && Lk(this.h, 8);
        Ed(z(), "ima", "video", "client", "jsTag") && Lk(this.h, 2);
        b && Ld(b, "fullscreen", !1) && Lk(this.h, 16);
        this.j = b = null;
        if (c && ((c = Ed(z(), "ima", "video", "client")), c.getEData)) {
            this.j = c.getEData();
            if ((c = Ed(z(), "ima", "video", "client", "getLastSnapshotFromTop")))
                if ((a = c()))
                    this.j.extendWithDataFromTopIframe(a.tagstamp, a.playstamp, a.lactstamp),
                        (c = this.l),
                        (b = a.er),
                        (a = a.vi),
                        b &&
                            a &&
                            ((b = Tk(b).h),
                            (a = Tk(a).h),
                            (k = null),
                            Ld(c.h, "er", null) && ((k = Ld(c.h, "er", null).h), (k.top += b.top), (k.left += b.left), Pk(c, new Sk("er", k))),
                            Ld(c.h, "vi", null) && ((n = Ld(c.h, "vi", null).h), (n.top += b.top), (n.left += b.left), (d = []), d.push(n), d.push(b), d.push(a), (b = jl(d)), ll(c, k, b), Pk(c, new Sk("vi", a))));
            a: {
                if (this.j) {
                    if (this.j.getTagLoadTimestamp) {
                        b = this.j.getTagLoadTimestamp();
                        break a;
                    }
                    if (this.j.getTimeSinceTagLoadSeconds) {
                        b = this.j.getTimeSinceTagLoadSeconds();
                        break a;
                    }
                }
                b = null;
            }
        }
        c = this.h;
        a = window.performance && window.performance.timing && window.performance.timing.domLoading && 0 < window.performance.timing.domLoading ? Math.round(window.performance.timing.domLoading / 1e3) : null;
        c.set.call(c, "td", Jk() - (null != a ? a : null != b ? b : Jk()));
    };
    ml.prototype.aa = function () {
        var a = [],
            b = Number(this.h.get("eb"));
        this.h.remove("eb");
        var c = this.h.aa();
        c && a.push(c);
        this.j && (c = this.j.serialize()) && a.push(c);
        (c = this.l.aa(b)) && a.push(c);
        this.h.set("eb", b);
        return a.join("_");
    };
    var nl = new Gk(200),
        ol = function (a, b) {
            try {
                return new ml(a, b).aa();
            } catch (c) {
                return "tle;" + Le(c.name, 12) + ";" + Le(c.message, 40);
            }
        },
        pl = function (a, b) {
            Qj(nl, "tick", function () {
                var c = ol(b);
                a(c);
            });
            nl.start();
            nl.dispatchEvent("tick");
        };
    var rl = function (a) {
        F.call(this, a, -1, ql);
    };
    r(rl, F);
    var wl = function () {
            return [1, fh, sl, tl, 2, fh, ul, vl];
        },
        sl = function (a) {
            F.call(this, a);
        };
    r(sl, F);
    sl.prototype.getError = function () {
        return ug(this, xl, 7);
    };
    var tl = function () {
            return [1, $g, 2, eh, yl, zl, 3, eh, yl, zl, 4, dh, 5, dh, 6, ch, 7, eh, xl, Al];
        },
        yl = function (a) {
            F.call(this, a);
        };
    r(yl, F);
    var zl = function () {
            return [1, $g, 2, $g, 3, $g];
        },
        xl = function (a) {
            F.call(this, a);
        };
    r(xl, F);
    var Al = function () {
            return [4, gh, 5, dh];
        },
        ul = function (a) {
            F.call(this, a);
        };
    r(ul, F);
    ul.prototype.getError = function () {
        return ug(this, Bl, 10);
    };
    var vl = function () {
            return [1, dh, 2, dh, 3, Yg, 7, Yg, 8, Xg, 4, ah, 5, ah, 6, ah, 9, ch, 10, eh, Bl, Cl];
        },
        Bl = function (a) {
            F.call(this, a);
        };
    r(Bl, F);
    var Cl = function () {
            return [1, gh];
        },
        ql = [1, 2];
    var Dl = function (a) {
        F.call(this, a);
    };
    r(Dl, F);
    Dl.prototype.setValue = function (a) {
        return E(this, 1, a);
    };
    Dl.prototype.getVersion = function () {
        return D(this, 5);
    };
    var El;
    El = ["av.default", "js", "unreleased"].slice(-1)[0];
    var Fl = document,
        N = window;
    var Gl = RegExp("^https?://(\\w|-)+\\.cdn\\.ampproject\\.(net|org)(\\?|/|$)"),
        Kl = function (a) {
            a = a || Hl();
            for (var b = new Il(t.location.href, !1), c = null, d = a.length - 1, e = d; 0 <= e; --e) {
                var f = a[e];
                !c && Gl.test(f.url) && (c = f);
                if (f.url && !f.Kc) {
                    b = f;
                    break;
                }
            }
            e = null;
            f = a.length && a[d].url;
            0 != b.depth && f && (e = a[d]);
            return new Jl(b, e, c);
        },
        Hl = function () {
            var a = t,
                b = [],
                c = null;
            do {
                var d = a;
                if (sf(d)) {
                    var e = d.location.href;
                    c = (d.document && d.document.referrer) || null;
                } else (e = c), (c = null);
                b.push(new Il(e || ""));
                try {
                    a = d.parent;
                } catch (f) {
                    a = null;
                }
            } while (a && d != a);
            d = 0;
            for (a = b.length - 1; d <= a; ++d) b[d].depth = a - d;
            d = t;
            if (d.location && d.location.ancestorOrigins && d.location.ancestorOrigins.length == b.length - 1) for (a = 1; a < b.length; ++a) (e = b[a]), e.url || ((e.url = d.location.ancestorOrigins[a - 1] || ""), (e.Kc = !0));
            return b;
        },
        Jl = function (a, b, c) {
            this.h = a;
            this.j = b;
            this.l = c;
        },
        Il = function (a, b) {
            this.url = a;
            this.Kc = !!b;
            this.depth = null;
        };
    var Ll = function () {
            this.l = "&";
            this.j = {};
            this.o = 0;
            this.h = [];
        },
        Ml = function (a, b) {
            var c = {};
            c[a] = b;
            return [c];
        },
        Ol = function (a, b, c, d, e) {
            var f = [];
            wf(a, function (g, h) {
                (g = Nl(g, b, c, d, e)) && f.push(h + "=" + g);
            });
            return f.join(b);
        },
        Nl = function (a, b, c, d, e) {
            if (null == a) return "";
            b = b || "&";
            c = c || ",$";
            "string" == typeof c && (c = c.split(""));
            if (a instanceof Array) {
                if (((d = d || 0), d < c.length)) {
                    for (var f = [], g = 0; g < a.length; g++) f.push(Nl(a[g], b, c, d + 1, e));
                    return f.join(c[d]);
                }
            } else if ("object" == typeof a) return (e = e || 0), 2 > e ? encodeURIComponent(Ol(a, b, c, d, e + 1)) : "...";
            return encodeURIComponent(String(a));
        },
        Pl = function (a, b, c) {
            a.h.push(b);
            a.j[b] = c;
        },
        Ql = function (a, b, c, d) {
            a.h.push(b);
            a.j[b] = Ml(c, d);
        },
        Sl = function (a, b, c) {
            b = b + "//pagead2.googlesyndication.com" + c;
            var d = Rl(a) - c.length;
            if (0 > d) return "";
            a.h.sort(function (m, x) {
                return m - x;
            });
            c = null;
            for (var e = "", f = 0; f < a.h.length; f++)
                for (var g = a.h[f], h = a.j[g], k = 0; k < h.length; k++) {
                    if (!d) {
                        c = null == c ? g : c;
                        break;
                    }
                    var n = Ol(h[k], a.l, ",$");
                    if (n) {
                        n = e + n;
                        if (d >= n.length) {
                            d -= n.length;
                            b += n;
                            e = a.l;
                            break;
                        }
                        c = null == c ? g : c;
                    }
                }
            a = "";
            null != c && (a = e + "trn=" + c);
            return b + a;
        },
        Rl = function (a) {
            var b = 1,
                c;
            for (c in a.j) b = c.length > b ? c.length : b;
            return 3997 - b - a.l.length - 1;
        };
    var Tl = function () {
            var a = void 0 === a ? N : a;
            this.j = "http:" === a.location.protocol ? "http:" : "https:";
            this.h = Math.random();
        },
        Vl = function () {
            var a = Ul,
                b = window.google_srt;
            0 <= b && 1 >= b && (a.h = b);
        },
        Wl = function (a, b, c, d, e) {
            if ((d ? a.h : Math.random()) < (e || 0.01))
                try {
                    if (c instanceof Ll) var f = c;
                    else
                        (f = new Ll()),
                            wf(c, function (h, k) {
                                var n = f,
                                    m = n.o++;
                                Pl(n, m, Ml(k, h));
                            });
                    var g = Sl(f, a.j, "/pagead/gen_204?id=" + b + "&");
                    g && Sf(t, g);
                } catch (h) {}
        };
    var Yl = function () {
        var a = Xl;
        this.B = Ul;
        this.A = "jserror";
        this.l = !0;
        this.j = null;
        this.C = this.La;
        this.h = void 0 === a ? null : a;
        this.o = !1;
    };
    l = Yl.prototype;
    l.jc = function (a) {
        this.j = a;
    };
    l.Zc = function (a) {
        this.A = a;
    };
    l.$c = function (a) {
        this.l = a;
    };
    l.bd = function (a) {
        this.o = a;
    };
    l.bb = function (a, b, c) {
        try {
            if (this.h && this.h.l) {
                var d = this.h.start(a.toString(), 3);
                var e = b();
                this.h.end(d);
            } else e = b();
        } catch (h) {
            b = this.l;
            try {
                Jh(d), (b = this.C(a, new th(h, { message: Zl(h) }), void 0, c));
            } catch (k) {
                this.La(217, k);
            }
            if (b) {
                var f, g;
                null == (f = window.console) || null == (g = f.error) || g.call(f, h);
            } else throw h;
        }
        return e;
    };
    l.Vc = function (a, b, c, d) {
        var e = this;
        return function () {
            var f = Ha.apply(0, arguments);
            return e.bb(
                a,
                function () {
                    return b.apply(c, f);
                },
                d
            );
        };
    };
    l.La = function (a, b, c, d, e) {
        e = e || this.A;
        try {
            var f = new Ll();
            Ql(f, 1, "context", a);
            uh(b) || (b = new th(b, { message: Zl(b) }));
            b.msg && Ql(f, 2, "msg", b.msg.substring(0, 512));
            var g = b.meta || {};
            if (this.j)
                try {
                    this.j(g);
                } catch (k) {}
            if (d)
                try {
                    d(g);
                } catch (k) {}
            Pl(f, 3, [g]);
            var h = Kl();
            h.j && Ql(f, 4, "top", h.j.url || "");
            Pl(f, 5, [{ url: h.h.url || "" }, { url: h.h.url ? of(h.h.url) : "" }]);
            Wl(this.B, e, f, this.o, c);
        } catch (k) {
            try {
                Wl(this.B, e, { context: "ecmserr", rctx: a, msg: Zl(k), url: h && h.h.url }, this.o, c);
            } catch (n) {}
        }
        return this.l;
    };
    var Zl = function (a) {
        var b = a.toString();
        a.name && -1 == b.indexOf(a.name) && (b += ": " + a.name);
        a.message && -1 == b.indexOf(a.message) && (b += ": " + a.message);
        if (a.stack) {
            a = a.stack;
            var c = b;
            try {
                -1 == a.indexOf(c) && (a = c + "\n" + a);
                for (var d; a != d; ) (d = a), (a = a.replace(/((https?:\/..*\/)[^\/:]*:\d+(?:.|\n)*)\2/, "$1"));
                b = a.replace(/\n */g, "\n");
            } catch (e) {
                b = c;
            }
        }
        return b;
    };
    var $l = function () {
        this.h = function () {
            return [];
        };
    };
    var Ul,
        am,
        Xl = new Ih(1, window);
    (function (a) {
        Ul = null != a ? a : new Tl();
        "number" !== typeof window.google_srt && (window.google_srt = Math.random());
        Vl();
        am = new Yl();
        am.jc(function () {});
        am.bd(!0);
        "complete" == window.document.readyState
            ? window.google_measure_js_timing || Xl.C()
            : Xl.l &&
              De(window, "load", function () {
                  window.google_measure_js_timing || Xl.C();
              });
    })();
    var bm = [0, 2, 1],
        cm = null;
    document.addEventListener &&
        document.addEventListener(
            "mousedown",
            function (a) {
                cm = a;
            },
            !0
        );
    window.mb = function (a) {
        if (a) {
            var b;
            if ((b = window.event || cm)) {
                var c;
                (c = b.which ? 1 << bm[b.which - 1] : b.button) && b.shiftKey && (c |= 8);
                c && b.altKey && (c |= 16);
                c && b.ctrlKey && (c |= 32);
                b = c;
            } else b = null;
            if ((c = b))
                if (window.css) window.css(a.id, "mb", c, void 0, void 0);
                else if (a) {
                    b = a.href;
                    var d = b.indexOf("&mb=");
                    if (0 > d) c = b + "&mb=" + c;
                    else {
                        d += 4;
                        var e = b.indexOf("&", d);
                        c = 0 <= e ? b.substring(0, d) + c + b.substring(e) : b.substring(0, d) + c;
                    }
                    a.href = 2e3 < c.length ? b : c;
                }
        }
    };
    var dm = function (a) {
        var b = {};
        Fb(a, function (c) {
            var d = c.event,
                e = b[d];
            b.hasOwnProperty(d) ? null !== e && (c.h(e) || (b[d] = null)) : (b[d] = c);
        });
        Rb(a, function (c) {
            return null === b[c.event];
        });
    };
    var em = { NONE: 0, Zf: 1 },
        fm = { Xf: 0, eh: 1, bh: 2, fh: 3 },
        gm = { Jf: "a", Yf: "d", xh: "v" };
    var hm = function () {
        this.X = 0;
        this.h = !1;
        this.j = -1;
        this.$a = !1;
        this.oa = 0;
    };
    hm.prototype.isVisible = function () {
        return this.$a ? 0.3 <= this.X : 0.5 <= this.X;
    };
    var im = { Wf: 0, cg: 1 },
        jm = { 668123728: 0, 668123729: 1 },
        km = { 44731964: 0, 44731965: 1 },
        lm = { NONE: 0, Eg: 1, hg: 2 },
        mm = { 480596784: 0, 480596785: 1, 21063355: 2 };
    var nm = function () {
            this.h = null;
            this.l = !1;
            this.j = null;
        },
        om = function (a) {
            a.l = !0;
            return a;
        },
        pm = function (a, b) {
            a.j &&
                Fb(b, function (c) {
                    c = a.j[c];
                    void 0 !== c && a.setValue(c);
                });
        },
        qm = function (a) {
            nm.call(this);
            this.o = a;
        };
    r(qm, nm);
    qm.prototype.setValue = function (a) {
        if (null !== this.h || !Gd(this.o, a)) return !1;
        this.h = a;
        return !0;
    };
    var rm = function () {
        nm.call(this);
    };
    r(rm, nm);
    rm.prototype.setValue = function (a) {
        if (null !== this.h || "number" !== typeof a) return !1;
        this.h = a;
        return !0;
    };
    var sm = function () {
        nm.call(this);
    };
    r(sm, nm);
    sm.prototype.setValue = function (a) {
        if (null !== this.h || "string" !== typeof a) return !1;
        this.h = a;
        return !0;
    };
    var tm = function () {
        this.h = {};
        this.j = !0;
        this.l = {};
    };
    tm.prototype.enable = function () {
        this.j = !0;
    };
    tm.prototype.isEnabled = function () {
        return this.j;
    };
    tm.prototype.reset = function () {
        this.h = {};
        this.j = !0;
        this.l = {};
    };
    var um = function (a, b, c) {
            a.h[b] || (a.h[b] = new qm(c));
            return a.h[b];
        },
        vm = function (a) {
            a.h.queryid || (a.h.queryid = new sm());
        },
        wm = function (a, b, c) {
            (a = a.h[b]) && a.setValue(c);
        },
        xm = function (a, b) {
            if (Fd(a.l, b)) return a.l[b];
            if ((a = a.h[b])) return a.h;
        },
        ym = function (a) {
            var b = {},
                c = xd(a.h, function (d) {
                    return d.l;
                });
            wd(
                c,
                function (d, e) {
                    d = void 0 !== a.l[e] ? String(a.l[e]) : d.l && null !== d.h ? String(d.h) : "";
                    0 < d.length && (b[e] = d);
                },
                a
            );
            return b;
        },
        zm = function (a) {
            a = ym(a);
            var b = [];
            wd(a, function (c, d) {
                d in Object.prototype || ("undefined" != typeof c && b.push([d, ":", c].join("")));
            });
            return b;
        },
        Am = function () {
            var a = P().featureSet,
                b = vi();
            a.j &&
                Fb(Cd(a.h), function (c) {
                    return pm(c, b);
                });
        };
    var Bm = function (a) {
        um(a, "od", em);
        om(um(a, "opac", im));
        om(um(a, "sbeos", im));
        om(um(a, "prf", im));
        om(um(a, "mwt", im));
        um(a, "iogeo", im);
    };
    var Cm = !cc && !Bb();
    var Dm = function () {
        this.h = this.Ua = null;
    };
    var Em = function () {};
    Em.prototype.h = function () {
        return 0;
    };
    Em.prototype.l = function () {
        return 0;
    };
    Em.prototype.o = function () {
        return 0;
    };
    Em.prototype.j = function () {
        return 0;
    };
    var Gm = function () {
        if (!Fm()) throw Error();
    };
    r(Gm, Em);
    var Fm = function () {
        return !(!N || !N.performance);
    };
    Gm.prototype.h = function () {
        return Fm() && N.performance.now ? N.performance.now() : Em.prototype.h.call(this);
    };
    Gm.prototype.l = function () {
        return Fm() && N.performance.memory ? N.performance.memory.totalJSHeapSize || 0 : Em.prototype.l.call(this);
    };
    Gm.prototype.o = function () {
        return Fm() && N.performance.memory ? N.performance.memory.usedJSHeapSize || 0 : Em.prototype.o.call(this);
    };
    Gm.prototype.j = function () {
        return Fm() && N.performance.memory ? N.performance.memory.jsHeapSizeLimit || 0 : Em.prototype.j.call(this);
    };
    var Hm = function () {};
    Hm.prototype.isVisible = function () {
        return 1 === xh(Fl);
    };
    var Im = function (a, b) {
            this.h = a;
            this.depth = b;
        },
        Km = function (a) {
            a = a || Hl();
            var b = Math.max(a.length - 1, 0),
                c = Kl(a);
            a = c.h;
            var d = c.j,
                e = c.l,
                f = [];
            c = function (h, k) {
                return null == h ? k : h;
            };
            e && f.push(new Im([e.url, e.Kc ? 2 : 0], c(e.depth, 1)));
            d && d != e && f.push(new Im([d.url, 2], 0));
            a.url && a != e && f.push(new Im([a.url, 0], c(a.depth, b)));
            var g = Ib(f, function (h, k) {
                return f.slice(0, f.length - k);
            });
            !a.url || ((e || d) && a != e) || ((d = yf(a.url)) && g.push([new Im([d, 1], c(a.depth, b))]));
            g.push([]);
            return Ib(g, function (h) {
                return Jm(b, h);
            });
        };
    function Jm(a, b) {
        var c = Jb(
                b,
                function (e, f) {
                    return Math.max(e, f.depth);
                },
                -1
            ),
            d = Xb(c + 2);
        d[0] = a;
        Fb(b, function (e) {
            return (d[e.depth + 1] = e.h);
        });
        return d;
    }
    var Lm = function () {
        var a = Km();
        return Ib(a, function (b) {
            return Nl(b);
        });
    };
    var Mm = function () {
            this.j = new Hm();
            this.h = Fm() ? new Gm() : new Em();
        },
        Om = function () {
            Nm();
            var a = N.document;
            return !!(a && a.body && a.body.getBoundingClientRect && "function" === typeof N.setInterval && "function" === typeof N.clearInterval && "function" === typeof N.setTimeout && "function" === typeof N.clearTimeout);
        };
    Mm.prototype.setTimeout = function (a, b) {
        return N.setTimeout(a, b);
    };
    Mm.prototype.clearTimeout = function (a) {
        N.clearTimeout(a);
    };
    var Pm = function () {
        Nm();
        return Lm();
    };
    var Qm = function () {},
        Nm = function () {
            var a = G(Qm);
            if (!a.h) {
                if (!N) throw Error("Context has not been set and window is undefined.");
                a.h = G(Mm);
            }
            return a.h;
        };
    var Rm = function (a) {
        F.call(this, a);
    };
    r(Rm, F);
    var Sm = function () {
        return [1, Wg, 2, Zg, 3, Zg, 4, Zg, 5, bh];
    };
    var Tm = function (a) {
            this.l = a;
            this.h = -1;
            this.j = this.o = 0;
        },
        Um = function (a, b) {
            return function () {
                var c = Ha.apply(0, arguments);
                if (-1 < a.h) return b.apply(null, ha(c));
                try {
                    return (a.h = a.l.h.h()), b.apply(null, ha(c));
                } finally {
                    (a.o += a.l.h.h() - a.h), (a.h = -1), (a.j += 1);
                }
            };
        };
    var Vm = function (a, b) {
        this.j = a;
        this.l = b;
        this.h = new Tm(a);
    };
    var Wm = function () {};
    var Xm = { Yg: 1, Ch: 2, Mg: 3 };
    Td(ib(jb("https://pagead2.googlesyndication.com/pagead/osd.js")));
    var $m = function () {
        this.o = void 0;
        this.j = this.C = 0;
        this.B = -1;
        this.featureSet = new tm();
        om(um(this.featureSet, "mv", lm)).j = void 0 === mm ? null : mm;
        um(this.featureSet, "omid", im);
        om(um(this.featureSet, "epoh", im));
        om(um(this.featureSet, "epph", im));
        om(um(this.featureSet, "umt", im)).j = void 0 === jm ? null : jm;
        om(um(this.featureSet, "phel", im));
        om(um(this.featureSet, "phell", im));
        om(um(this.featureSet, "oseid", Xm));
        var a = this.featureSet;
        a.h.sloi || (a.h.sloi = new rm());
        om(a.h.sloi);
        um(this.featureSet, "mm", gm);
        om(um(this.featureSet, "ovms", fm));
        om(um(this.featureSet, "xdi", im));
        om(um(this.featureSet, "amp", im));
        om(um(this.featureSet, "prf", im));
        om(um(this.featureSet, "gtx", im));
        om(um(this.featureSet, "mvp_lv", im));
        om(um(this.featureSet, "ssmol", im)).j = void 0 === km ? null : km;
        this.h = new Vm(Nm(), this.featureSet);
        this.A = this.l = !1;
        this.flags = new Wm();
    };
    $m.prototype.Uc = function (a) {
        if ("string" === typeof a && 0 != a.length) {
            var b = this.featureSet;
            if (b.j) {
                a = a.split("&");
                for (var c = a.length - 1; 0 <= c; c--) {
                    var d = a[c].split("="),
                        e = decodeURIComponent(d[0]);
                    1 < d.length ? ((d = decodeURIComponent(d[1])), (d = /^[0-9]+$/g.exec(d) ? parseInt(d, 10) : d)) : (d = 1);
                    (e = b.h[e]) && e.setValue(d);
                }
            }
        }
    };
    var P = function () {
        return G($m);
    };
    var an = function (a, b, c, d, e) {
        if ((d ? a.l : Math.random()) < (e || a.h))
            try {
                if (c instanceof Ll) var f = c;
                else
                    (f = new Ll()),
                        wf(c, function (h, k) {
                            var n = f,
                                m = n.o++;
                            Pl(n, m, Ml(k, h));
                        });
                var g = Sl(f, a.j, "/pagead/gen_204?id=" + b + "&");
                g && (Nm(), Sf(N, g));
            } catch (h) {}
    };
    var dn = function () {
        var a = bn;
        this.B = cn;
        this.A = "jserror";
        this.l = !0;
        this.j = null;
        this.C = this.La;
        this.h = void 0 === a ? null : a;
        this.o = !1;
    };
    l = dn.prototype;
    l.jc = function (a) {
        this.j = a;
    };
    l.Zc = function (a) {
        this.A = a;
    };
    l.$c = function (a) {
        this.l = a;
    };
    l.bd = function (a) {
        this.o = a;
    };
    l.bb = function (a, b, c) {
        var d = this;
        return Um(P().h.h, function () {
            try {
                if (d.h && d.h.l) {
                    var e = d.h.start(a.toString(), 3);
                    var f = b();
                    d.h.end(e);
                } else f = b();
            } catch (h) {
                var g = d.l;
                try {
                    Jh(e), (g = d.C(a, new en(fn(h)), void 0, c));
                } catch (k) {
                    d.La(217, k);
                }
                if (!g) throw h;
            }
            return f;
        })();
    };
    l.Vc = function (a, b, c, d) {
        var e = this;
        return Um(P().h.h, function () {
            var f = Ha.apply(0, arguments);
            return e.bb(
                a,
                function () {
                    return b.apply(c, f);
                },
                d
            );
        });
    };
    l.La = function (a, b, c, d, e) {
        e = e || this.A;
        try {
            var f = new Ll();
            Ql(f, 1, "context", a);
            uh(b) || (b = new en(fn(b)));
            b.msg && Ql(f, 2, "msg", b.msg.substring(0, 512));
            var g = b.meta || {};
            if (this.j)
                try {
                    this.j(g);
                } catch (k) {}
            if (d)
                try {
                    d(g);
                } catch (k) {}
            Pl(f, 3, [g]);
            var h = Kl();
            h.j && Ql(f, 4, "top", h.j.url || "");
            Pl(f, 5, [{ url: h.h.url || "" }, { url: h.h.url ? of(h.h.url) : "" }]);
            an(this.B, e, f, this.o, c);
        } catch (k) {
            try {
                an(this.B, e, { context: "ecmserr", rctx: a, msg: fn(k), url: h && h.h.url }, this.o, c);
            } catch (n) {}
        }
        return this.l;
    };
    var fn = function (a) {
            var b = a.toString();
            a.name && -1 == b.indexOf(a.name) && (b += ": " + a.name);
            a.message && -1 == b.indexOf(a.message) && (b += ": " + a.message);
            if (a.stack) {
                a = a.stack;
                var c = b;
                try {
                    -1 == a.indexOf(c) && (a = c + "\n" + a);
                    for (var d; a != d; ) (d = a), (a = a.replace(/((https?:\/..*\/)[^\/:]*:\d+(?:.|\n)*)\2/, "$1"));
                    b = a.replace(/\n */g, "\n");
                } catch (e) {
                    b = c;
                }
            }
            return b;
        },
        en = function (a) {
            th.call(this, Error(a), { message: a });
        };
    r(en, th);
    var cn,
        gn,
        bn = new Ih(1, window),
        hn = function () {
            N && "undefined" != typeof N.google_measure_js_timing && (N.google_measure_js_timing || bn.C());
        };
    cn = new (function () {
        var a = "https:";
        N && N.location && "http:" === N.location.protocol && (a = "http:");
        this.j = a;
        this.h = 0.01;
        this.l = Math.random();
    })();
    gn = new dn();
    N &&
        N.document &&
        ("complete" == N.document.readyState
            ? hn()
            : bn.l &&
              De(N, "load", function () {
                  hn();
              }));
    var jn = function (a) {
            gn.jc(function (b) {
                Fb(a, function (c) {
                    c(b);
                });
            });
        },
        kn = function (a, b) {
            return gn.bb(a, b, void 0);
        },
        ln = function (a, b, c, d) {
            return gn.Vc(a, b, c, d);
        },
        mn = function (a, b, c, d) {
            gn.La(a, b, c, d);
        };
    var nn = Date.now(),
        on = -1,
        pn = -1,
        qn,
        rn = -1,
        sn = !1,
        tn = function () {
            return Date.now() - nn;
        },
        un = function () {
            var a = P().o,
                b = 0 <= pn ? tn() - pn : -1,
                c = sn ? tn() - on : -1,
                d = 0 <= rn ? tn() - rn : -1;
            if (947190542 == a) return 100;
            if (79463069 == a) return 200;
            a = [2e3, 4e3];
            var e = [250, 500, 1e3];
            mn(637, Error(), 0.001);
            var f = b;
            -1 != c && c < b && (f = c);
            for (b = 0; b < a.length; ++b)
                if (f < a[b]) {
                    var g = e[b];
                    break;
                }
            void 0 === g && (g = e[a.length]);
            return -1 != d && 1500 < d && 4e3 > d ? 500 : g;
        };
    var vn = function (a, b, c) {
        var d = new B(0, 0, 0, 0);
        this.time = a;
        this.volume = null;
        this.l = b;
        this.h = d;
        this.j = c;
    };
    var wn = function (a, b, c, d, e, f, g) {
        this.l = a;
        this.j = b;
        this.A = c;
        this.h = d;
        this.o = e;
        this.C = f;
        this.B = g;
    };
    wn.prototype.getTimestamp = function () {
        return this.C;
    };
    var xn = { currentTime: 1, duration: 2, isVpaid: 4, volume: 8, isYouTube: 16, isPlaying: 32 },
        Id = {
            wc: "start",
            FIRST_QUARTILE: "firstquartile",
            MIDPOINT: "midpoint",
            THIRD_QUARTILE: "thirdquartile",
            COMPLETE: "complete",
            je: "metric",
            vc: "pause",
            pd: "resume",
            SKIPPED: "skip",
            VIEWABLE_IMPRESSION: "viewable_impression",
            ke: "mute",
            we: "unmute",
            FULLSCREEN: "fullscreen",
            ee: "exitfullscreen",
            hd: "bufferstart",
            gd: "bufferfinish",
            kd: "fully_viewable_audible_half_duration_impression",
            od: "measurable_impression",
            Xd: "abandon",
            jd: "engagedview",
            IMPRESSION: "impression",
            be: "creativeview",
            LOADED: "loaded",
            $g: "progress",
            Of: "close",
            Pf: "collapse",
            le: "overlay_resize",
            me: "overlay_unmeasurable_impression",
            ne: "overlay_unviewable_impression",
            pe: "overlay_viewable_immediate_impression",
            oe: "overlay_viewable_end_of_session_impression",
            ce: "custom_metric_viewable",
            Rg: "verification_debug",
            Yd: "audio_audible",
            $d: "audio_measurable",
            Zd: "audio_impression",
        },
        yn = "start firstquartile midpoint thirdquartile resume loaded".split(" "),
        zn = ["start", "firstquartile", "midpoint", "thirdquartile"],
        An = ["abandon"],
        Bn = {
            uh: -1,
            wc: 0,
            FIRST_QUARTILE: 1,
            MIDPOINT: 2,
            THIRD_QUARTILE: 3,
            COMPLETE: 4,
            je: 5,
            vc: 6,
            pd: 7,
            SKIPPED: 8,
            VIEWABLE_IMPRESSION: 9,
            ke: 10,
            we: 11,
            FULLSCREEN: 12,
            ee: 13,
            kd: 14,
            od: 15,
            Xd: 16,
            jd: 17,
            IMPRESSION: 18,
            be: 19,
            LOADED: 20,
            ce: 21,
            hd: 22,
            gd: 23,
            Zd: 24,
            $d: 25,
            Yd: 26,
        };
    var Bd = { Ff: "addEventListener", ig: "getMaxSize", jg: "getScreenSize", kg: "getState", lg: "getVersion", ah: "removeEventListener", Fg: "isViewable" },
        Cn = function (a) {
            var b = a !== a.top,
                c = a.top === Hf(a),
                d = -1,
                e = 0;
            if (b && c && a.top.mraid) {
                d = 3;
                var f = a.top.mraid;
            } else d = (f = a.mraid) ? (b ? (c ? 2 : 1) : 0) : -1;
            f &&
                (f.IS_GMA_SDK || (e = 2),
                Ad(function (g) {
                    return "function" === typeof f[g];
                }) || (e = 1));
            return { wa: f, compatibility: e, vf: d };
        };
    var Dn = function () {
        var a = window.document;
        return a && "function" === typeof a.elementFromPoint;
    };
    var En = function (a, b, c) {
        try {
            a && (b = b.top);
            var d = void 0;
            var e = b;
            c = void 0 === c ? !1 : c;
            a && null !== e && e != e.top && (e = e.top);
            try {
                d = (void 0 === c ? 0 : c) ? new y(e.innerWidth, e.innerHeight).round() : af(e || window).round();
            } catch (k) {
                d = new y(-12245933, -12245933);
            }
            a = d;
            var f = bf(Ve(b.document).h);
            if (-12245933 == a.width) {
                var g = a.width;
                var h = new B(g, g, g, g);
            } else h = new B(f.y, f.x + a.width, f.y + a.height, f.x);
            return h;
        } catch (k) {
            return new B(-12245933, -12245933, -12245933, -12245933);
        }
    };
    var Fn = function (a, b) {
        b = Math.pow(10, b);
        return Math.floor(a * b) / b;
    };
    function Gn(a, b, c, d) {
        if (!a) return { value: d, done: !1 };
        d = b(d, a);
        var e = c(d, a);
        return !e && Zb(a, "parentElement") ? Gn(gf(a), b, c, d) : { done: e, value: d };
    }
    var Hn = function (a, b, c, d) {
        if (!a) return d;
        d = Gn(a, b, c, d);
        if (!d.done)
            try {
                var e = Ue(a),
                    f = e && z(e);
                return Hn(f && f.frameElement, b, c, d.value);
            } catch (g) {}
        return d.value;
    };
    function In(a) {
        var b = !cc || tc(8);
        return Hn(
            a,
            function (c, d) {
                c = Zb(d, "style") && d.style && Xk(d, "visibility");
                return { hidden: "hidden" === c, visible: b && "visible" === c };
            },
            function (c) {
                return c.hidden || c.visible;
            },
            { hidden: !1, visible: !1 }
        ).hidden;
    }
    var Jn = function (a) {
            return Hn(
                a,
                function (b, c) {
                    return !(!Zb(c, "style") || !c.style || "none" !== Xk(c, "display"));
                },
                function (b) {
                    return b;
                },
                !1
            )
                ? !0
                : In(a);
        },
        Kn = function (a) {
            return new B(a.top, a.right, a.bottom, a.left);
        },
        Ln = function (a) {
            var b = a.top || 0,
                c = a.left || 0;
            return new B(b, c + (a.width || 0), b + (a.height || 0), c);
        },
        Mn = function (a) {
            return null != a && 0 <= a && 1 >= a;
        };
    function Nn() {
        var a = yb();
        return a
            ? Kb("Android TV;AppleTV;Apple TV;GoogleTV;HbbTV;NetCast.TV;Opera TV;POV_TV;SMART-TV;SmartTV;TV Store;AmazonWebAppPlatform;MiBOX".split(";"), function (b) {
                  return vb(a, b);
              }) ||
              (vb(a, "OMI/") && !vb(a, "XiaoMi/"))
                ? !0
                : vb(a, "Presto") && vb(a, "Linux") && !vb(a, "X11") && !vb(a, "Android") && !vb(a, "Mobi")
            : !1;
    }
    function On() {
        var a = yb();
        return vb(a, "AppleTV") || vb(a, "Apple TV") || vb(a, "CFNetwork") || vb(a, "tvOS");
    }
    function Pn() {
        var a;
        (a = vb(yb(), "CrKey") || vb(yb(), "PlayStation") || vb(yb(), "Roku") || Nn() || vb(yb(), "Xbox") || On()) || ((a = yb()), (a = vb(a, "sdk_google_atv_x86") || vb(a, "Android TV")));
        return a;
    }
    var Qn = function () {
            this.H = !1;
            this.l = !sf(N.top);
            this.isMobileDevice = lf() || mf();
            var a = Hl();
            a = 0 < a.length && null != a[a.length - 1] && null != a[a.length - 1].url ? ((a = a[a.length - 1].url.match(nf)[3] || null) ? decodeURI(a) : a) || "" : "";
            this.domain = a;
            this.h = new B(0, 0, 0, 0);
            this.A = new y(0, 0);
            this.o = new y(0, 0);
            this.C = new B(0, 0, 0, 0);
            this.B = 0;
            this.J = !1;
            this.j = !(!N || !Cn(N).wa);
            this.update(N);
        },
        Rn = function (a, b) {
            b && b.screen && (a.A = new y(b.screen.width, b.screen.height));
        },
        Sn = function (a, b) {
            var c = a.h ? new y(a.h.getWidth(), a.h.getHeight()) : new y(0, 0);
            b = void 0 === b ? N : b;
            null !== b && b != b.top && (b = b.top);
            var d = 0,
                e = 0;
            try {
                var f = b.document,
                    g = f.body,
                    h = f.documentElement;
                if ("CSS1Compat" == f.compatMode && h.scrollHeight) (d = h.scrollHeight != c.height ? h.scrollHeight : h.offsetHeight), (e = h.scrollWidth != c.width ? h.scrollWidth : h.offsetWidth);
                else {
                    var k = h.scrollHeight,
                        n = h.scrollWidth,
                        m = h.offsetHeight,
                        x = h.offsetWidth;
                    h.clientHeight != m && ((k = g.scrollHeight), (n = g.scrollWidth), (m = g.offsetHeight), (x = g.offsetWidth));
                    k > c.height ? (k > m ? ((d = k), (e = n)) : ((d = m), (e = x))) : k < m ? ((d = k), (e = n)) : ((d = m), (e = x));
                }
                var v = new y(e, d);
            } catch (A) {
                v = new y(-12245933, -12245933);
            }
            a.o = v;
        };
    Qn.prototype.update = function (a) {
        a && a.document && ((this.C = En(!1, a, this.isMobileDevice)), (this.h = En(!0, a, this.isMobileDevice)), Sn(this, a), Rn(this, a));
    };
    var Tn = function () {
            var a = Q();
            if (0 < a.B || a.J) return !0;
            a = Nm().j.isVisible();
            var b = 0 === xh(Fl);
            return a || b;
        },
        Q = function () {
            return G(Qn);
        };
    var Un = function (a) {
        this.l = a;
        this.j = 0;
        this.h = null;
    };
    Un.prototype.cancel = function () {
        Nm().clearTimeout(this.h);
        this.h = null;
    };
    Un.prototype.schedule = function () {
        var a = this;
        this.h = Nm().setTimeout(
            Um(
                P().h.h,
                ln(143, function () {
                    a.j++;
                    a.l.sample();
                })
            ),
            un()
        );
    };
    var Vn = function (a, b, c) {
        this.l = a;
        this.pa = void 0 === c ? "na" : c;
        this.B = [];
        this.isInitialized = !1;
        this.o = new vn(-1, !0, this);
        this.h = this;
        this.J = b;
        this.I = this.D = !1;
        this.U = "uk";
        this.M = !1;
        this.A = !0;
    };
    Vn.prototype.G = function () {
        return !1;
    };
    Vn.prototype.initialize = function () {
        return (this.isInitialized = !0);
    };
    Vn.prototype.nb = function () {
        return this.h.U;
    };
    Vn.prototype.Db = function () {
        return this.h.I;
    };
    var Xn = function (a, b, c) {
        if (!a.I || (void 0 === c ? 0 : c)) (a.I = !0), (a.U = b), (a.J = 0), a.h != a || Wn(a);
    };
    Vn.prototype.getName = function () {
        return this.h.pa;
    };
    Vn.prototype.Ra = function () {
        return this.h.V();
    };
    Vn.prototype.V = function () {
        return {};
    };
    Vn.prototype.Ha = function () {
        return this.h.J;
    };
    var Yn = function (a, b) {
        Ob(a.B, b) || (a.B.push(b), b.pb(a.h), b.Sa(a.o), b.Da() && (a.D = !0));
    };
    Vn.prototype.R = function () {
        var a = Q();
        a.h = En(!0, this.l, a.isMobileDevice);
    };
    Vn.prototype.T = function () {
        Rn(Q(), this.l);
    };
    Vn.prototype.W = function () {
        return this.o.h;
    };
    var Zn = function (a) {
        a = a.h;
        a.T();
        a.R();
        var b = Q();
        b.C = En(!1, a.l, b.isMobileDevice);
        Sn(Q(), a.l);
        a.o.h = a.W();
    };
    Vn.prototype.sample = function () {};
    var $n = function (a, b) {
        a.h != a ? $n(a.h, b) : a.A !== b && ((a.A = b), Wn(a));
    };
    Vn.prototype.isActive = function () {
        return this.h.A;
    };
    var ao = function (a) {
            a.D = a.B.length
                ? Kb(a.B, function (b) {
                      return b.Da();
                  })
                : !1;
        },
        bo = function (a) {
            var b = Tb(a.B);
            Fb(b, function (c) {
                c.Sa(a.o);
            });
        },
        Wn = function (a) {
            var b = Tb(a.B);
            Fb(b, function (c) {
                c.pb(a.h);
            });
            a.h != a || bo(a);
        };
    l = Vn.prototype;
    l.pb = function (a) {
        var b = this.h;
        this.h = a.Ha() >= this.J ? a : this;
        b !== this.h ? ((this.A = this.h.A), Wn(this)) : this.A !== this.h.A && ((this.A = this.h.A), Wn(this));
    };
    l.Sa = function (a) {
        if (a.j === this.h) {
            var b = this.o,
                c = this.D;
            if ((c = a && (void 0 === c || !c || b.volume == a.volume) && b.l == a.l)) (b = b.h), (c = a.h), (c = b == c ? !0 : b && c ? b.top == c.top && b.right == c.right && b.bottom == c.bottom && b.left == c.left : !1);
            this.o = a;
            !c && bo(this);
        }
    };
    l.Da = function () {
        return this.D;
    };
    l.dispose = function () {
        this.M = !0;
    };
    l.Ia = function () {
        return this.M;
    };
    var co = function (a, b, c, d) {
        this.element = a;
        this.h = new B(0, 0, 0, 0);
        this.o = new B(0, 0, 0, 0);
        this.j = b;
        this.featureSet = c;
        this.G = d;
        this.D = !1;
        this.timestamp = -1;
        this.C = new wn(b.o, this.h, new B(0, 0, 0, 0), 0, 0, tn(), 0);
    };
    l = co.prototype;
    l.uc = function () {
        return !0;
    };
    l.Kb = function () {};
    l.dispose = function () {
        if (!this.Ia()) {
            var a = this.j;
            Pb(a.B, this);
            a.D && this.Da() && ao(a);
            this.Kb();
            this.D = !0;
        }
    };
    l.Ia = function () {
        return this.D;
    };
    l.Ra = function () {
        return this.j.Ra();
    };
    l.Ha = function () {
        return this.j.Ha();
    };
    l.nb = function () {
        return this.j.nb();
    };
    l.Db = function () {
        return this.j.Db();
    };
    l.pb = function () {};
    l.Sa = function () {
        this.Pa();
    };
    l.Da = function () {
        return this.G;
    };
    var eo = function (a) {
        this.A = !1;
        this.h = a;
        this.o = function () {};
    };
    l = eo.prototype;
    l.Ha = function () {
        return this.h.Ha();
    };
    l.nb = function () {
        return this.h.nb();
    };
    l.Db = function () {
        return this.h.Db();
    };
    l.create = function (a, b, c) {
        var d = null;
        this.h && ((d = this.Lb(a, b, c)), Yn(this.h, d));
        return d;
    };
    l.ld = function () {
        return this.yb();
    };
    l.yb = function () {
        return !1;
    };
    l.init = function (a) {
        return this.h.initialize() ? (Yn(this.h, this), (this.o = a), !0) : !1;
    };
    l.pb = function (a) {
        0 == a.Ha() && this.o(a.nb(), this);
    };
    l.Sa = function () {};
    l.Da = function () {
        return !1;
    };
    l.dispose = function () {
        this.A = !0;
    };
    l.Ia = function () {
        return this.A;
    };
    l.Ra = function () {
        return {};
    };
    var fo = function (a, b, c) {
            this.l = void 0 === c ? 0 : c;
            this.j = a;
            this.h = null == b ? "" : b;
        },
        go = function (a) {
            switch (Math.trunc(a.l)) {
                case -16:
                    return -16;
                case -8:
                    return -8;
                case 0:
                    return 0;
                case 8:
                    return 8;
                case 16:
                    return 16;
                default:
                    return 16;
            }
        },
        ho = function (a, b) {
            return a.l < b.l ? !0 : a.l > b.l ? !1 : a.j < b.j ? !0 : a.j > b.j ? !1 : typeof a.h < typeof b.h ? !0 : typeof a.h > typeof b.h ? !1 : a.h < b.h;
        };
    var io = function () {
        this.l = 0;
        this.h = [];
        this.j = !1;
    };
    io.prototype.add = function (a, b, c) {
        ++this.l;
        a = new fo(a, b, c);
        this.h.push(new fo(a.j, a.h, a.l + this.l / 4096));
        this.j = !0;
        return this;
    };
    var jo = function (a, b) {
            Fb(b.h, function (c) {
                a.add(c.j, c.h, go(c));
            });
        },
        ko = function (a, b) {
            var c = void 0 === c ? 0 : c;
            var d = void 0 === d ? !0 : d;
            wf(b, function (e, f) {
                (d && void 0 === e) || a.add(f, e, c);
            });
            return a;
        },
        mo = function (a) {
            var b = lo;
            a.j &&
                (Vb(a.h, function (c, d) {
                    return ho(d, c) ? 1 : ho(c, d) ? -1 : 0;
                }),
                (a.j = !1));
            return Jb(
                a.h,
                function (c, d) {
                    d = b(d);
                    return "" + c + ("" != c && "" != d ? "&" : "") + d;
                },
                ""
            );
        };
    var lo = function (a) {
        var b = a.j;
        a = a.h;
        return "" === a ? b : "boolean" === typeof a ? (a ? b : "") : Array.isArray(a) ? (0 === a.length ? b : b + "=" + a.join()) : b + "=" + (Ob(["mtos", "tos", "p"], b) ? a : encodeURIComponent(a));
    };
    var no = function (a) {
        var b = void 0 === b ? !0 : b;
        this.h = new io();
        void 0 !== a && jo(this.h, a);
        b && this.h.add("v", El, -16);
    };
    no.prototype.toString = function () {
        var a = "//pagead2.googlesyndication.com//pagead/gen_204",
            b = mo(this.h);
        0 < b.length && (a += "?" + b);
        return a;
    };
    var oo = function (a) {
            var b = [],
                c = [];
            wd(a, function (d, e) {
                if (!(e in Object.prototype) && "undefined" != typeof d)
                    switch ((Array.isArray(d) && (d = d.join(",")), (d = [e, "=", d].join("")), e)) {
                        case "adk":
                        case "r":
                        case "tt":
                        case "error":
                        case "mtos":
                        case "tos":
                        case "p":
                        case "bs":
                            b.unshift(d);
                            break;
                        case "req":
                        case "url":
                        case "referrer":
                        case "iframe_loc":
                            c.push(d);
                            break;
                        default:
                            b.push(d);
                    }
            });
            return b.concat(c);
        },
        po = function () {
            if (El && "unreleased" !== El) return El;
        },
        qo = function (a) {
            var b = void 0 === b ? 4e3 : b;
            a = a.toString();
            if (!/&v=[^&]+/.test(a)) {
                var c = po();
                a = c ? a + "&v=" + encodeURIComponent(c) : a;
            }
            b = a = a.substring(0, b);
            Nm();
            Sf(N, b);
        };
    var ro = function () {
        this.h = 0;
    };
    var so = function (a, b, c) {
        Fb(a.l, function (d) {
            var e = a.h;
            if (!d.h && (d.l(b, c), d.o())) {
                d.h = !0;
                var f = d.j(),
                    g = new io();
                g.add("id", "av-js");
                g.add("type", "verif");
                g.add("vtype", d.A);
                d = G(ro);
                g.add("i", d.h++);
                g.add("adk", e);
                ko(g, f);
                e = new no(g);
                qo(e);
            }
        });
    };
    var to = function () {
        this.j = this.l = this.o = this.h = 0;
    };
    to.prototype.update = function (a, b, c) {
        a && ((this.h += b), (this.j += b), (this.o += b), (this.l = Math.max(this.l, this.o)));
        if (void 0 === c ? !a : c) this.o = 0;
    };
    var uo = [1, 0.75, 0.5, 0.3, 0],
        vo = function (a) {
            this.j = a = void 0 === a ? uo : a;
            this.h = Ib(this.j, function () {
                return new to();
            });
        },
        xo = function (a, b) {
            return wo(
                a,
                function (c) {
                    return c.h;
                },
                void 0 === b ? !0 : b
            );
        },
        zo = function (a, b) {
            return yo(a, b, function (c) {
                return c.h;
            });
        },
        Ao = function (a, b) {
            return wo(
                a,
                function (c) {
                    return c.l;
                },
                void 0 === b ? !0 : b
            );
        },
        Bo = function (a, b) {
            return yo(a, b, function (c) {
                return c.l;
            });
        },
        Co = function (a, b) {
            return yo(a, b, function (c) {
                return c.j;
            });
        },
        Do = function (a) {
            Fb(a.h, function (b) {
                b.j = 0;
            });
        };
    vo.prototype.update = function (a, b, c, d, e, f) {
        f = void 0 === f ? !0 : f;
        b = e ? Math.min(a, b) : b;
        for (e = 0; e < this.j.length; e++) {
            var g = this.j[e],
                h = 0 < b && b >= g;
            g = !(0 < a && a >= g) || c;
            this.h[e].update(f && h, d, !f || g);
        }
    };
    var wo = function (a, b, c) {
            a = Ib(a.h, function (d) {
                return b(d);
            });
            return c ? a : Eo(a);
        },
        yo = function (a, b, c) {
            var d = Nb(a.j, function (e) {
                return b <= e;
            });
            return -1 == d ? 0 : c(a.h[d]);
        },
        Eo = function (a) {
            return Ib(a, function (b, c, d) {
                return 0 < c ? d[c] - d[c - 1] : d[c];
            });
        };
    var Fo = function () {
            this.j = new vo();
            this.T = new to();
            this.G = this.C = -1;
            this.W = 1e3;
            this.Y = new vo([1, 0.9, 0.8, 0.7, 0.6, 0.5, 0.4, 0.3, 0.2, 0.1, 0]);
            this.L = this.I = -1;
        },
        Go = function (a, b) {
            return Ao(a.j, void 0 === b ? !0 : b);
        };
    Fo.prototype.update = function (a, b, c, d) {
        this.C = -1 != this.C ? Math.min(this.C, b.X) : b.X;
        this.G = Math.max(this.G, b.X);
        this.I = -1 != this.I ? Math.min(this.I, b.oa) : b.oa;
        this.L = Math.max(this.L, b.oa);
        this.Y.update(b.oa, c.oa, b.h, a, d);
        this.j.update(b.X, c.X, b.h, a, d);
        c = d || c.$a != b.$a ? c.isVisible() && b.isVisible() : c.isVisible();
        b = !b.isVisible() || b.h;
        this.T.update(c, a, b);
    };
    Fo.prototype.Ka = function () {
        return this.T.l >= this.W;
    };
    if (Fl && Fl.URL) {
        var Ho,
            Af = Fl.URL;
        Ho = !!Af && 0 < Bf().length;
        gn.$c(!Ho);
    }
    var Io = function (a, b, c, d) {
        var e = void 0 === e ? !1 : e;
        c = ln(d, c);
        De(a, b, c, { capture: e });
    };
    var Jo = new B(0, 0, 0, 0);
    function Ko(a, b) {
        b = Lo(b);
        return 0 === b ? 0 : Lo(a) / b;
    }
    function Lo(a) {
        return Math.max(a.bottom - a.top, 0) * Math.max(a.right - a.left, 0);
    }
    function Mo(a, b) {
        if (!a || !b) return !1;
        for (var c = 0; null !== a && 100 > c++; ) {
            if (a === b) return !0;
            try {
                if ((a = gf(a) || a)) {
                    var d = Ue(a),
                        e = d && z(d),
                        f = e && e.frameElement;
                    f && (a = f);
                }
            } catch (g) {
                break;
            }
        }
        return !1;
    }
    function No(a, b, c) {
        if (!a || !b) return !1;
        b = Jf(If(a), -b.left, -b.top);
        a = (b.left + b.right) / 2;
        b = (b.top + b.bottom) / 2;
        sf(window.top) && window.top && window.top.document && (window = window.top);
        if (!Dn()) return !1;
        a = window.document.elementFromPoint(a, b);
        if (!a) return !1;
        b = (b = (b = Ue(c)) && b.defaultView && b.defaultView.frameElement) && Mo(b, a);
        var d = a === c;
        a =
            !d &&
            a &&
            kf(a, function (e) {
                return e === c;
            });
        return !(b || d || a);
    }
    function Oo(a, b, c, d) {
        return Q().l
            ? !1
            : 0 >= a.getWidth() || 0 >= a.getHeight()
            ? !0
            : c && d
            ? kn(208, function () {
                  return No(a, b, c);
              })
            : !1;
    }
    var Po = new B(0, 0, 0, 0),
        Ro = function (a, b, c) {
            L.call(this);
            this.position = If(Po);
            this.ac = this.Ub();
            this.Mc = -2;
            this.yf = Date.now();
            this.Ud = -1;
            this.lastUpdateTime = b;
            this.Xb = null;
            this.Sb = !1;
            this.fc = null;
            this.opacity = -1;
            this.requestSource = c;
            this.Oc = function () {};
            this.Vd = function () {};
            this.ra = new Dm();
            this.ra.Ua = a;
            this.ra.h = a;
            this.Ja = !1;
            this.Ya = { Qc: null, Pc: null };
            this.Sd = !0;
            this.Jb = null;
            this.qb = this.cf = !1;
            P().C++;
            this.ma = this.Fc();
            this.Td = -1;
            this.ba = null;
            this.Ye = !1;
            this.featureSet = new tm();
            Bm(this.featureSet);
            Qo(this);
            1 == this.requestSource ? wm(this.featureSet, "od", 1) : wm(this.featureSet, "od", 0);
        };
    r(Ro, L);
    Ro.prototype.N = function () {
        this.ra.h && (this.Ya.Qc && (Ee(this.ra.h, "mouseover", this.Ya.Qc), (this.Ya.Qc = null)), this.Ya.Pc && (Ee(this.ra.h, "mouseout", this.Ya.Pc), (this.Ya.Pc = null)));
        this.Jb && this.Jb.dispose();
        this.ba && this.ba.dispose();
        delete this.ac;
        delete this.Oc;
        delete this.Vd;
        delete this.ra.Ua;
        delete this.ra.h;
        delete this.Ya;
        delete this.Jb;
        delete this.ba;
        delete this.featureSet;
        L.prototype.N.call(this);
    };
    Ro.prototype.Za = function () {
        return this.ba ? this.ba.h : this.position;
    };
    Ro.prototype.Uc = function (a) {
        P().Uc(a);
    };
    var Qo = function (a) {
        a = a.ra.Ua;
        var b;
        if ((b = a && a.getAttribute)) b = /-[a-z]/.test("googleAvInapp") ? !1 : Cm && a.dataset ? "googleAvInapp" in a.dataset : a.hasAttribute ? a.hasAttribute("data-" + Qe()) : !!a.getAttribute("data-" + Qe());
        b && (Q().j = !0);
    };
    Ro.prototype.Da = function () {
        return !1;
    };
    Ro.prototype.Ub = function () {
        return new Fo();
    };
    Ro.prototype.la = function () {
        return this.ac;
    };
    var So = function (a, b) {
            b != a.qb && ((a.qb = b), (a = Q()), b ? a.B++ : 0 < a.B && a.B--);
        },
        To = function (a, b) {
            if (a.ba) {
                if (b.getName() === a.ba.getName()) return;
                a.ba.dispose();
                a.ba = null;
            }
            b = b.create(a.ra.h, a.featureSet, a.Da());
            if ((b = null != b && b.uc() ? b : null)) a.ba = b;
        },
        Uo = function (a, b, c) {
            if (!a.Xb || -1 == a.lastUpdateTime || -1 === b.getTimestamp() || -1 === a.Xb.getTimestamp()) return 0;
            a = b.getTimestamp() - a.Xb.getTimestamp();
            return a > c ? 0 : a;
        };
    Ro.prototype.Hd = function (a) {
        return Uo(this, a, 1e4);
    };
    var Vo = function (a, b, c) {
            if (a.ba) {
                a.ba.Pa();
                var d = a.ba.C,
                    e = d.l,
                    f = e.h;
                if (null != d.A) {
                    var g = d.j;
                    a.fc = new Je(g.left - f.left, g.top - f.top);
                }
                f = a.kc() ? Math.max(d.h, d.o) : d.h;
                g = {};
                null !== e.volume && (g.volume = e.volume);
                e = a.Hd(d);
                a.Xb = d;
                a.dd(f, b, c, !1, g, e, d.B);
            }
        },
        Wo = function (a) {
            if (a.Sb && a.Jb) {
                var b = 1 == xm(a.featureSet, "od"),
                    c = Q().h,
                    d = a.Jb,
                    e = a.ba ? a.ba.getName() : "ns",
                    f = new y(c.getWidth(), c.getHeight());
                c = a.kc();
                a = { wf: e, fc: a.fc, Df: f, kc: c, X: a.ma.X, zf: b };
                if ((b = d.j)) {
                    b.Pa();
                    e = b.C;
                    f = e.l.h;
                    var g = null,
                        h = null;
                    null != e.A && f && ((g = e.j), (g = new Je(g.left - f.left, g.top - f.top)), (h = new y(f.right - f.left, f.bottom - f.top)));
                    e = c ? Math.max(e.h, e.o) : e.h;
                    c = { wf: b.getName(), fc: g, Df: h, kc: c, zf: !1, X: e };
                } else c = null;
                c && so(d, a, c);
            }
        };
    l = Ro.prototype;
    l.dd = function (a, b, c, d, e, f, g) {
        this.Ja ||
            (this.Sb &&
                ((a = this.xc(a, c, e, g)),
                (d = d && this.ma.X >= (this.$a() ? 0.3 : 0.5)),
                this.ed(f, a, d),
                (this.lastUpdateTime = b),
                0 < a.X && -1 === this.Td && (this.Td = b),
                -1 == this.Ud && this.Ka() && (this.Ud = b),
                -2 == this.Mc && (this.Mc = Lo(this.Za()) ? a.X : -1),
                (this.ma = a)),
            this.Oc(this));
    };
    l.ed = function (a, b, c) {
        this.la().update(a, b, this.ma, c);
    };
    l.Fc = function () {
        return new hm();
    };
    l.xc = function (a, b, c, d) {
        c = this.Fc();
        c.h = b;
        b = Nm().j;
        b = 0 === xh(Fl) ? -1 : b.isVisible() ? 0 : 1;
        c.j = b;
        c.X = this.zc(a);
        c.$a = this.$a();
        c.oa = d;
        return c;
    };
    l.zc = function (a) {
        return 0 === this.opacity && 1 === xm(this.featureSet, "opac") ? 0 : a;
    };
    l.$a = function () {
        return !1;
    };
    l.kc = function () {
        return this.Ye || this.cf;
    };
    l.ta = function () {
        return 0;
    };
    l.Ka = function () {
        return this.ac.Ka();
    };
    l.Jd = function () {
        return this.Ja ? 2 : this.Ka() ? 4 : 3;
    };
    l.Fd = function () {
        return 0;
    };
    var Xo = function (a, b, c) {
        b && (a.Oc = b);
        c && (a.Vd = c);
    };
    var Yo = function () {};
    Yo.prototype.next = function () {
        return Zo;
    };
    var Zo = { done: !0, value: void 0 };
    Yo.prototype.jb = function () {
        return this;
    };
    var $o = function () {
            this.o = this.h = this.l = this.j = this.A = 0;
        },
        ap = function (a) {
            var b = {};
            b = ((b.ptlt = Za() - a.A), b);
            var c = a.j;
            c && (b.pnk = c);
            (c = a.l) && (b.pnc = c);
            (c = a.o) && (b.pnmm = c);
            (a = a.h) && (b.pns = a);
            return b;
        };
    var bp = function () {
        hm.call(this);
        this.fullscreen = !1;
        this.volume = void 0;
        this.paused = !1;
        this.mediaTime = -1;
    };
    r(bp, hm);
    var cp = function (a) {
        return Mn(a.volume) && 0 < a.volume;
    };
    var ep = function (a, b, c, d) {
            c = void 0 === c ? !0 : c;
            d =
                void 0 === d
                    ? function () {
                          return !0;
                      }
                    : d;
            return function (e) {
                var f = e[a];
                if (Array.isArray(f) && d(e)) return dp(f, b, c);
            };
        },
        fp = function (a, b) {
            return function (c) {
                return b(c) ? c[a] : void 0;
            };
        },
        gp = function (a) {
            return function (b) {
                for (var c = 0; c < a.length; c++) if (a[c] === b.e || (void 0 === a[c] && !b.hasOwnProperty("e"))) return !0;
                return !1;
            };
        },
        dp = function (a, b, c) {
            return void 0 === c || c
                ? Hb(a, function (d, e) {
                      return Ob(b, e);
                  })
                : Ib(b, function (d, e, f) {
                      return a.slice(0 < e ? f[e - 1] + 1 : 0, d + 1).reduce(function (g, h) {
                          return g + h;
                      }, 0);
                  });
        };
    var hp = gp([void 0, 1, 2, 3, 4, 8, 16]),
        ip = gp([void 0, 4, 8, 16]),
        jp = {
            sv: "sv",
            v: "v",
            cb: "cb",
            e: "e",
            nas: "nas",
            msg: "msg",
            if: "if",
            sdk: "sdk",
            p: "p",
            p0: fp("p0", ip),
            p1: fp("p1", ip),
            p2: fp("p2", ip),
            p3: fp("p3", ip),
            cp: "cp",
            tos: "tos",
            mtos: "mtos",
            amtos: "amtos",
            mtos1: ep("mtos1", [0, 2, 4], !1, ip),
            mtos2: ep("mtos2", [0, 2, 4], !1, ip),
            mtos3: ep("mtos3", [0, 2, 4], !1, ip),
            mcvt: "mcvt",
            ps: "ps",
            scs: "scs",
            bs: "bs",
            vht: "vht",
            mut: "mut",
            a: "a",
            a0: fp("a0", ip),
            a1: fp("a1", ip),
            a2: fp("a2", ip),
            a3: fp("a3", ip),
            ft: "ft",
            dft: "dft",
            at: "at",
            dat: "dat",
            as: "as",
            vpt: "vpt",
            gmm: "gmm",
            std: "std",
            efpf: "efpf",
            swf: "swf",
            nio: "nio",
            px: "px",
            nnut: "nnut",
            vmer: "vmer",
            vmmk: "vmmk",
            vmiec: "vmiec",
            nmt: "nmt",
            tcm: "tcm",
            bt: "bt",
            pst: "pst",
            vpaid: "vpaid",
            dur: "dur",
            vmtime: "vmtime",
            dtos: "dtos",
            dtoss: "dtoss",
            dvs: "dvs",
            dfvs: "dfvs",
            dvpt: "dvpt",
            fmf: "fmf",
            vds: "vds",
            is: "is",
            i0: "i0",
            i1: "i1",
            i2: "i2",
            i3: "i3",
            ic: "ic",
            cs: "cs",
            c: "c",
            c0: fp("c0", ip),
            c1: fp("c1", ip),
            c2: fp("c2", ip),
            c3: fp("c3", ip),
            mc: "mc",
            nc: "nc",
            mv: "mv",
            nv: "nv",
            qmt: fp("qmtos", hp),
            qnc: fp("qnc", hp),
            qmv: fp("qmv", hp),
            qnv: fp("qnv", hp),
            raf: "raf",
            rafc: "rafc",
            lte: "lte",
            ces: "ces",
            tth: "tth",
            femt: "femt",
            femvt: "femvt",
            emc: "emc",
            emuc: "emuc",
            emb: "emb",
            avms: "avms",
            nvat: "nvat",
            qi: "qi",
            psm: "psm",
            psv: "psv",
            psfv: "psfv",
            psa: "psa",
            pnk: "pnk",
            pnc: "pnc",
            pnmm: "pnmm",
            pns: "pns",
            ptlt: "ptlt",
            pngs: "pings",
            veid: "veid",
            ssb: "ssb",
            ss0: fp("ss0", ip),
            ss1: fp("ss1", ip),
            ss2: fp("ss2", ip),
            ss3: fp("ss3", ip),
            dc_rfl: "urlsigs",
            obd: "obd",
            omidp: "omidp",
            omidr: "omidr",
            omidv: "omidv",
            omida: "omida",
            omids: "omids",
            omidpv: "omidpv",
            omidam: "omidam",
            omidct: "omidct",
            omidia: "omidia",
        };
    Object.assign({}, jp, {
        avid: (function (a) {
            return function () {
                return a;
            };
        })("audio"),
        avas: "avas",
        vs: "vs",
    });
    var kp = {
        atos: "atos",
        avt: ep("atos", [2]),
        davs: "davs",
        dafvs: "dafvs",
        dav: "dav",
        ss: (function (a, b) {
            return function (c) {
                return void 0 === c[a] && void 0 !== b ? b : c[a];
            };
        })("ss", 0),
        t: "t",
    };
    var lp = function () {
        this.j = this.h = "";
    };
    var mp = function () {},
        np = function (a, b) {
            var c = {};
            if (void 0 !== a)
                if (null != b)
                    for (var d in b) {
                        var e = b[d];
                        d in Object.prototype || (null != e && (c[d] = "function" === typeof e ? e(a) : a[e]));
                    }
                else Nd(c, a);
            return mo(ko(new io(), c));
        };
    var op = function () {
            var a = {};
            this.j =
                ((a.vs = [1, 0]),
                (a.vw = [0, 1]),
                (a.am = [2, 2]),
                (a.a = [4, 4]),
                (a.f = [8, 8]),
                (a.bm = [16, 16]),
                (a.b = [32, 32]),
                (a.avw = [0, 64]),
                (a.avs = [64, 0]),
                (a.pv = [256, 256]),
                (a.gdr = [0, 512]),
                (a.p = [0, 1024]),
                (a.r = [0, 2048]),
                (a.m = [0, 4096]),
                (a.um = [0, 8192]),
                (a.ef = [0, 16384]),
                (a.s = [0, 32768]),
                (a.pmx = [0, 16777216]),
                a);
            this.h = {};
            for (var b in this.j) 0 < this.j[b][1] && (this.h[b] = 0);
            this.l = 0;
        },
        pp = function (a, b) {
            var c = a.j[b],
                d = c[1];
            a.l += c[0];
            0 < d && 0 == a.h[b] && (a.h[b] = 1);
        },
        qp = function (a) {
            var b = Dd(a.j),
                c = 0,
                d;
            for (d in a.h) Ob(b, d) && 1 == a.h[d] && ((c += a.j[d][1]), (a.h[d] = 2));
            return c;
        },
        rp = function (a) {
            var b = 0,
                c;
            for (c in a.h) {
                var d = a.h[c];
                if (1 == d || 2 == d) b += a.j[c][1];
            }
            return b;
        };
    var sp = function () {
        this.j = this.h = 0;
    };
    sp.prototype.update = function (a, b) {
        32 <= a || (this.j & (1 << a) && !b ? (this.h &= ~(1 << a)) : this.j & (1 << a) || !b || (this.h |= 1 << a), (this.j |= 1 << a));
    };
    var tp = function () {
        Fo.call(this);
        this.l = new to();
        this.R = this.J = this.K = 0;
        this.H = -1;
        this.Z = new to();
        this.A = new to();
        this.h = new vo();
        this.B = this.o = -1;
        this.D = new to();
        this.W = 2e3;
        this.M = new sp();
        this.V = new sp();
        this.U = new sp();
    };
    r(tp, Fo);
    var up = function (a, b, c) {
        var d = a.R;
        sn || c || -1 == a.H || (d += b - a.H);
        return d;
    };
    tp.prototype.update = function (a, b, c, d) {
        if (!b.paused) {
            Fo.prototype.update.call(this, a, b, c, d);
            var e = cp(b) && cp(c),
                f = 0.5 <= (d ? Math.min(b.X, c.X) : c.X);
            Mn(b.volume) && ((this.o = -1 != this.o ? Math.min(this.o, b.volume) : b.volume), (this.B = Math.max(this.B, b.volume)));
            f && ((this.K += a), (this.J += e ? a : 0));
            this.h.update(b.X, c.X, b.h, a, d, e);
            this.l.update(!0, a);
            this.A.update(e, a);
            this.D.update(c.fullscreen, a);
            this.Z.update(e && !f, a);
            a = Math.floor(b.mediaTime / 1e3);
            this.M.update(a, b.isVisible());
            this.V.update(a, 1 <= b.X);
            this.U.update(a, cp(b));
        }
    };
    var vp = function () {
        this.j = !1;
    };
    vp.prototype.A = function (a) {
        this.j || (this.h(a) ? ((a = this.J.report(this.l, a)), (this.o |= a), (a = 0 == a)) : (a = !1), (this.j = a));
    };
    var wp = function (a, b) {
        this.j = !1;
        this.l = a;
        this.J = b;
        this.o = 0;
    };
    r(wp, vp);
    wp.prototype.h = function () {
        return !0;
    };
    wp.prototype.B = function () {
        return !1;
    };
    wp.prototype.getId = function () {
        var a = this,
            b = Hd(function (c) {
                return c == a.l;
            });
        return Bn[b].toString();
    };
    wp.prototype.toString = function () {
        var a = "";
        this.B() && (a += "c");
        this.j && (a += "s");
        0 < this.o && (a += ":" + this.o);
        return this.getId() + a;
    };
    var xp = function (a, b) {
        wp.call(this, a, b);
        this.C = [];
    };
    r(xp, wp);
    xp.prototype.A = function (a, b) {
        b = void 0 === b ? null : b;
        null != b && this.C.push(b);
        wp.prototype.A.call(this, a);
    };
    var yp = function (a, b, c, d) {
        co.call(this, a, b, c, d);
    };
    r(yp, co);
    l = yp.prototype;
    l.yc = function () {
        if (this.element) {
            var a = this.element,
                b = this.j.h.l;
            try {
                try {
                    var c = Kn(a.getBoundingClientRect());
                } catch (n) {
                    c = new B(0, 0, 0, 0);
                }
                var d = c.right - c.left,
                    e = c.bottom - c.top,
                    f = al(a, b),
                    g = f.x,
                    h = f.y;
                var k = new B(Math.round(h), Math.round(g + d), Math.round(h + e), Math.round(g));
            } catch (n) {
                k = If(Jo);
            }
            this.h = k;
        }
    };
    l.ud = function () {
        this.o = this.j.o.h;
    };
    l.Kd = function (a) {
        return Oo(a, this.o, this.element, 1 == xm(this.featureSet, "od"));
    };
    l.wd = function () {
        this.timestamp = tn();
    };
    l.Pa = function () {
        this.wd();
        this.yc();
        if (this.element && "number" === typeof this.element.videoWidth && "number" === typeof this.element.videoHeight) {
            var a = this.element;
            var b = new y(a.videoWidth, a.videoHeight);
            a = this.h;
            var c = a.getWidth(),
                d = a.getHeight(),
                e = b.width;
            b = b.height;
            0 >= e ||
                0 >= b ||
                0 >= c ||
                0 >= d ||
                ((e /= b),
                (a = If(a)),
                e > c / d
                    ? ((c /= e), (d = (d - c) / 2), 0 < d && ((d = a.top + d), (a.top = Math.round(d)), (a.bottom = Math.round(d + c))))
                    : ((d *= e), (c = Math.round((c - d) / 2)), 0 < c && ((c = a.left + c), (a.left = Math.round(c)), (a.right = Math.round(c + d)))));
            this.h = a;
        }
        this.ud();
        a = this.h;
        c = this.o;
        a = a.left <= c.right && c.left <= a.right && a.top <= c.bottom && c.top <= a.bottom ? new B(Math.max(a.top, c.top), Math.min(a.right, c.right), Math.min(a.bottom, c.bottom), Math.max(a.left, c.left)) : new B(0, 0, 0, 0);
        c = a.top >= a.bottom || a.left >= a.right ? new B(0, 0, 0, 0) : a;
        a = this.j.o;
        b = e = d = 0;
        0 < (this.h.bottom - this.h.top) * (this.h.right - this.h.left) && (this.Kd(c) ? (c = new B(0, 0, 0, 0)) : ((d = Q().A), (b = new B(0, d.height, d.width, 0)), (d = Ko(c, this.h)), (e = Ko(c, Q().h)), (b = Ko(c, b))));
        c = c.top >= c.bottom || c.left >= c.right ? new B(0, 0, 0, 0) : Jf(c, -this.h.left, -this.h.top);
        Tn() || (e = d = 0);
        this.C = new wn(a, this.h, c, d, e, this.timestamp, b);
    };
    l.getName = function () {
        return this.j.getName();
    };
    var zp = new B(0, 0, 0, 0),
        Ap = function (a, b, c) {
            co.call(this, null, a, b, c);
            this.B = a.isActive();
            this.A = 0;
        };
    r(Ap, yp);
    l = Ap.prototype;
    l.uc = function () {
        this.l();
        return !0;
    };
    l.Sa = function () {
        yp.prototype.Pa.call(this);
    };
    l.wd = function () {};
    l.yc = function () {};
    l.Pa = function () {
        this.l();
        yp.prototype.Pa.call(this);
    };
    l.pb = function (a) {
        a = a.isActive();
        a !== this.B && (a ? this.l() : ((Q().h = new B(0, 0, 0, 0)), (this.h = new B(0, 0, 0, 0)), (this.o = new B(0, 0, 0, 0)), (this.timestamp = -1)));
        this.B = a;
    };
    function Bp(a) {
        return [a.top, a.left, a.bottom, a.right];
    }
    var Cp = {},
        Dp = ((Cp.firstquartile = 0), (Cp.midpoint = 1), (Cp.thirdquartile = 2), (Cp.complete = 3), Cp),
        Ep = function (a, b, c, d, e, f, g) {
            f = void 0 === f ? null : f;
            g = void 0 === g ? [] : g;
            Ro.call(this, b, c, d);
            this.Tc = e;
            this.Cc = 0;
            this.ga = {};
            this.fa = new op();
            this.Wd = {};
            this.ia = "";
            this.Va = null;
            this.sa = !1;
            this.h = [];
            this.ab = f;
            this.B = g;
            this.A = null;
            this.l = -1;
            this.U = this.G = void 0;
            this.K = this.I = 0;
            this.R = -1;
            this.Z = this.Y = !1;
            this.M = this.D = this.j = this.ub = this.qa = 0;
            new vo();
            this.T = this.V = 0;
            this.W = -1;
            this.ea = 0;
            this.C = xe;
            this.L = [this.Ub()];
            this.Fa = 2;
            this.fb = {};
            this.fb.pause = "p";
            this.fb.resume = "r";
            this.fb.skip = "s";
            this.fb.mute = "m";
            this.fb.unmute = "um";
            this.fb.exitfullscreen = "ef";
            this.o = null;
            this.pa = !1;
        };
    r(Ep, Ro);
    Ep.prototype.Da = function () {
        return !0;
    };
    var Fp = function (a) {
        return void 0 === a ? a : Number(a) ? Fn(a, 3) : 0;
    };
    l = Ep.prototype;
    l.Hd = function (a) {
        return Uo(this, a, Math.max(1e4, this.l / 3));
    };
    l.dd = function (a, b, c, d, e, f, g) {
        var h = this,
            k = this.C(this) || {};
        Nd(k, e);
        this.l = k.duration || this.l;
        this.G = k.isVpaid || this.G;
        this.U = k.isYouTube || this.U;
        e = Gp(this, b);
        1 === Hp(this) && (f = e);
        Ro.prototype.dd.call(this, a, b, c, d, k, f, g);
        this.ab &&
            this.ab.j &&
            Fb(this.B, function (n) {
                n.A(h);
            });
    };
    l.ed = function (a, b, c) {
        Ro.prototype.ed.call(this, a, b, c);
        Ip(this).update(a, b, this.ma, c);
        this.Z = cp(this.ma) && cp(b);
        -1 == this.R && this.Y && (this.R = this.la().l.h);
        this.fa.l = 0;
        a = this.Ka();
        b.isVisible() && pp(this.fa, "vs");
        a && pp(this.fa, "vw");
        Mn(b.volume) && pp(this.fa, "am");
        cp(b) && pp(this.fa, "a");
        this.qb && pp(this.fa, "f");
        -1 != b.j && (pp(this.fa, "bm"), 1 == b.j && pp(this.fa, "b"));
        cp(b) && b.isVisible() && pp(this.fa, "avs");
        this.Z && a && pp(this.fa, "avw");
        0 < b.X && pp(this.fa, "pv");
        Jp(this, this.la().l.h, !0) && pp(this.fa, "gdr");
        2e3 <= Bo(this.la().j, 1) && pp(this.fa, "pmx");
    };
    l.Ub = function () {
        return new tp();
    };
    l.la = function () {
        return this.ac;
    };
    var Ip = function (a, b) {
        return a.L[null != b && b < a.L.length ? b : a.L.length - 1];
    };
    Ep.prototype.Fc = function () {
        return new bp();
    };
    Ep.prototype.xc = function (a, b, c, d) {
        a = Ro.prototype.xc.call(this, a, b, c, void 0 === d ? -1 : d);
        a.fullscreen = this.qb;
        a.paused = 2 == this.ea;
        a.volume = c.volume;
        Mn(a.volume) || (this.qa++, (b = this.ma), Mn(b.volume) && (a.volume = b.volume));
        c = c.currentTime;
        a.mediaTime = void 0 !== c && 0 <= c ? c : -1;
        return a;
    };
    var Hp = function (a) {
            var b = !!xm(P().featureSet, "umt");
            return a.G || (!b && !a.U) ? 0 : 1;
        },
        Gp = function (a, b) {
            2 == a.ea ? (b = 0) : -1 == a.lastUpdateTime ? (b = 0) : ((b -= a.lastUpdateTime), (b = b > Math.max(1e4, a.l / 3) ? 0 : b));
            var c = a.C(a) || {};
            c = void 0 !== c.currentTime ? c.currentTime : a.I;
            var d = c - a.I,
                e = 0;
            0 <= d ? ((a.K += b), (a.T += Math.max(b - d, 0)), (e = Math.min(d, a.K))) : (a.V += Math.abs(d));
            0 != d && (a.K = 0);
            -1 == a.W && 0 < d && (a.W = 0 <= rn ? tn() - rn : -1);
            a.I = c;
            return e;
        };
    Ep.prototype.zc = function (a) {
        return Q().H ? 0 : this.qb ? 1 : Ro.prototype.zc.call(this, a);
    };
    Ep.prototype.ta = function () {
        return 1;
    };
    Ep.prototype.getDuration = function () {
        return this.l;
    };
    var Kp = function (a, b) {
            Kb(a.B, function (c) {
                return c.l == b.l;
            }) || a.B.push(b);
        },
        Lp = function (a) {
            var b = zo(a.la().h, 1);
            return Jp(a, b);
        },
        Jp = function (a, b, c) {
            return 15e3 <= b ? !0 : a.Y ? ((void 0 === c ? 0 : c) ? !0 : 0 < a.l ? b >= a.l / 2 : 0 < a.R ? b >= a.R : !1) : !1;
        },
        Mp = function (a) {
            var b = {},
                c = Q();
            b.insideIframe = c.l;
            b.unmeasurable = a.Ja;
            b.position = a.Za();
            b.exposure = a.ma.X;
            b.documentSize = c.o;
            b.viewportSize = new y(c.h.getWidth(), c.h.getHeight());
            null != a.o && (b.presenceData = a.o);
            b.screenShare = a.ma.oa;
            return b;
        },
        Np = function (a) {
            var b = Fn(a.ma.X, 2),
                c = a.fa.l,
                d = a.ma,
                e = Ip(a),
                f = Fp(e.o),
                g = Fp(e.B),
                h = Fp(d.volume),
                k = Fn(e.C, 2),
                n = Fn(e.G, 2),
                m = Fn(d.X, 2),
                x = Fn(e.I, 2),
                v = Fn(e.L, 2);
            d = Fn(d.oa, 2);
            a = If(a.Za());
            a.round();
            e = Go(e, !1);
            return { Cf: b, Eb: c, bc: f, Yb: g, zb: h, cc: k, Zb: n, X: m, dc: x, $b: v, oa: d, position: a, ec: e };
        },
        Pp = function (a, b) {
            Op(a.h, b, function () {
                return { Cf: 0, Eb: void 0, bc: -1, Yb: -1, zb: -1, cc: -1, Zb: -1, X: -1, dc: -1, $b: -1, oa: -1, position: void 0, ec: [] };
            });
            a.h[b] = Np(a);
        },
        Op = function (a, b, c) {
            for (var d = a.length; d < b + 1; ) a.push(c()), d++;
        },
        Sp = function (a, b, c) {
            var d = a.Wd[b];
            if (null != d) return d;
            d = Qp(a, b);
            var e = Hd(function (f) {
                return f == b;
            });
            a = Rp(a, d, d, c, Dp[Id[e]]);
            "fully_viewable_audible_half_duration_impression" == b && (a.std = "csm");
            return a;
        },
        Tp = function (a, b, c) {
            var d = [b];
            if (a != b || c != b) d.unshift(a), d.push(c);
            return d;
        },
        Rp = function (a, b, c, d, e) {
            if (a.Ja) return { if: 0, vs: 0 };
            var f = If(a.Za());
            f.round();
            var g = Q(),
                h = P(),
                k = a.la(),
                n = a.ba ? a.ba.getName() : "ns",
                m = {};
            m["if"] = g.l ? 1 : void 0;
            m.sdk = a.A ? a.A : void 0;
            m.t = a.yf;
            m.p = [f.top, f.left, f.bottom, f.right];
            m.tos = xo(k.j, !1);
            m.mtos = Go(k);
            m.mcvt = k.T.l;
            m.ps = void 0;
            m.vht = up(k, tn(), 2 == a.ea);
            m.mut = k.Z.l;
            m.a = Fp(a.ma.volume);
            m.mv = Fp(k.B);
            m.fs = a.qb ? 1 : 0;
            m.ft = k.D.h;
            m.at = k.A.h;
            m.as = 0 < k.o ? 1 : 0;
            m.atos = xo(k.h);
            m.ssb = xo(k.Y, !1);
            m.amtos = Ao(k.h, !1);
            m.uac = a.qa;
            m.vpt = k.l.h;
            "nio" == n && ((m.nio = 1), (m.avms = "nio"));
            m.gmm = "4";
            m.gdr = Jp(a, k.l.h, !0) ? 1 : 0;
            m.efpf = a.Fa;
            if ("gsv" == n || "nis" == n) (f = a.ba), 0 < f.A && (m.nnut = f.A);
            m.tcm = Hp(a);
            m.nmt = a.V;
            m.bt = a.T;
            m.pst = a.W;
            m.vpaid = a.G;
            m.dur = a.l;
            m.vmtime = a.I;
            m.is = a.fa.l;
            1 <= a.h.length && ((m.i0 = a.h[0].Eb), (m.a0 = [a.h[0].zb]), (m.c0 = [a.h[0].X]), (m.ss0 = [a.h[0].oa]), (f = a.h[0].position), (m.p0 = f ? Bp(f) : void 0));
            2 <= a.h.length &&
                ((m.i1 = a.h[1].Eb),
                (m.a1 = Tp(a.h[1].bc, a.h[1].zb, a.h[1].Yb)),
                (m.c1 = Tp(a.h[1].cc, a.h[1].X, a.h[1].Zb)),
                (m.ss1 = Tp(a.h[1].dc, a.h[1].oa, a.h[1].$b)),
                (f = a.h[1].position),
                (m.p1 = f ? Bp(f) : void 0),
                (m.mtos1 = a.h[1].ec));
            3 <= a.h.length &&
                ((m.i2 = a.h[2].Eb),
                (m.a2 = Tp(a.h[2].bc, a.h[2].zb, a.h[2].Yb)),
                (m.c2 = Tp(a.h[2].cc, a.h[2].X, a.h[2].Zb)),
                (m.ss2 = Tp(a.h[2].dc, a.h[2].oa, a.h[2].$b)),
                (f = a.h[2].position),
                (m.p2 = f ? Bp(f) : void 0),
                (m.mtos2 = a.h[2].ec));
            4 <= a.h.length &&
                ((m.i3 = a.h[3].Eb),
                (m.a3 = Tp(a.h[3].bc, a.h[3].zb, a.h[3].Yb)),
                (m.c3 = Tp(a.h[3].cc, a.h[3].X, a.h[3].Zb)),
                (m.ss3 = Tp(a.h[3].dc, a.h[3].oa, a.h[3].$b)),
                (f = a.h[3].position),
                (m.p3 = f ? Bp(f) : void 0),
                (m.mtos3 = a.h[3].ec));
            m.cs = rp(a.fa);
            b &&
                ((m.ic = qp(a.fa)),
                (m.dvpt = k.l.j),
                (m.dvs = Co(k.j, 0.5)),
                (m.dfvs = Co(k.j, 1)),
                (m.davs = Co(k.h, 0.5)),
                (m.dafvs = Co(k.h, 1)),
                c && ((k.l.j = 0), Do(k.j), Do(k.h)),
                a.Ka() && ((m.dtos = k.K), (m.dav = k.J), (m.dtoss = a.Cc + 1), c && ((k.K = 0), (k.J = 0), a.Cc++)),
                (m.dat = k.A.j),
                (m.dft = k.D.j),
                c && ((k.A.j = 0), (k.D.j = 0)));
            m.ps = [g.o.width, g.o.height];
            m.bs = [g.h.getWidth(), g.h.getHeight()];
            m.scs = [g.A.width, g.A.height];
            m.dom = g.domain;
            a.ub && (m.vds = a.ub);
            if (0 < a.B.length || a.ab)
                (b = Tb(a.B)),
                    a.ab && b.push(a.ab),
                    (m.pings = Ib(b, function (x) {
                        return x.toString();
                    }));
            b = Ib(
                Hb(a.B, function (x) {
                    return x.B();
                }),
                function (x) {
                    return x.getId();
                }
            );
            Ub(b);
            m.ces = b;
            a.j && (m.vmer = a.j);
            a.D && (m.vmmk = a.D);
            a.M && (m.vmiec = a.M);
            m.avms = a.ba ? a.ba.getName() : "ns";
            a.ba && Nd(m, a.ba.Ra());
            d ? ((m.c = Fn(a.ma.X, 2)), (m.ss = Fn(a.ma.oa, 2))) : (m.tth = tn() - qn);
            m.mc = Fn(k.G, 2);
            m.nc = Fn(k.C, 2);
            m.mv = Fp(k.B);
            m.nv = Fp(k.o);
            m.lte = Fn(a.Mc, 2);
            d = Ip(a, e);
            Go(k);
            m.qmtos = Go(d);
            m.qnc = Fn(d.C, 2);
            m.qmv = Fp(d.B);
            m.qnv = Fp(d.o);
            m.qas = 0 < d.o ? 1 : 0;
            m.qi = a.ia;
            m.avms || (m.avms = "geo");
            m.psm = k.M.j;
            m.psv = k.M.h;
            m.psfv = k.V.h;
            m.psa = k.U.h;
            h = zm(h.featureSet);
            h.length && (m.veid = h);
            a.o && Nd(m, ap(a.o));
            m.avas = a.Fd();
            m.vs = a.Jd();
            return m;
        },
        Qp = function (a, b) {
            if (Ob(An, b)) return !0;
            var c = a.ga[b];
            return void 0 !== c ? ((a.ga[b] = !0), !c) : !1;
        };
    Ep.prototype.Jd = function () {
        return this.Ja ? 2 : Lp(this) ? 5 : this.Ka() ? 4 : 3;
    };
    Ep.prototype.Fd = function () {
        return this.pa ? (2e3 <= this.la().A.l ? 4 : 3) : 2;
    };
    var Up = Za(),
        Xp = function () {
            this.h = {};
            var a = z();
            Vp(this, a, document);
            var b = Wp();
            try {
                if ("1" == b) {
                    for (var c = a.parent; c != a.top; c = c.parent) Vp(this, c, c.document);
                    Vp(this, a.top, a.top.document);
                }
            } catch (d) {}
        },
        Wp = function () {
            var a = document.documentElement;
            try {
                if (!sf(z().top)) return "2";
                var b = [],
                    c = z(a.ownerDocument);
                for (a = c; a != c.top; a = a.parent)
                    if (a.frameElement) b.push(a.frameElement);
                    else break;
                return b && 0 != b.length ? "1" : "0";
            } catch (d) {
                return "2";
            }
        },
        Vp = function (a, b, c) {
            Io(
                c,
                "mousedown",
                function () {
                    return Yp(a);
                },
                301
            );
            Io(
                b,
                "scroll",
                function () {
                    return Zp(a);
                },
                302
            );
            Io(
                c,
                "touchmove",
                function () {
                    return $p(a);
                },
                303
            );
            Io(
                c,
                "mousemove",
                function () {
                    return aq(a);
                },
                304
            );
            Io(
                c,
                "keydown",
                function () {
                    return bq(a);
                },
                305
            );
        },
        Yp = function (a) {
            wd(a.h, function (b) {
                1e5 < b.l || ++b.l;
            });
        },
        Zp = function (a) {
            wd(a.h, function (b) {
                1e5 < b.h || ++b.h;
            });
        },
        $p = function (a) {
            wd(a.h, function (b) {
                1e5 < b.h || ++b.h;
            });
        },
        bq = function (a) {
            wd(a.h, function (b) {
                1e5 < b.j || ++b.j;
            });
        },
        aq = function (a) {
            wd(a.h, function (b) {
                1e5 < b.o || ++b.o;
            });
        };
    var cq = function () {
            this.h = [];
            this.j = [];
        },
        dq = function (a, b) {
            return Lb(a.h, function (c) {
                return c.ia == b;
            });
        },
        eq = function (a, b) {
            return b
                ? Lb(a.h, function (c) {
                      return c.ra.Ua == b;
                  })
                : null;
        },
        fq = function (a, b) {
            return Lb(a.j, function (c) {
                return 2 == c.ta() && c.ia == b;
            });
        },
        hq = function () {
            var a = gq;
            return 0 == a.h.length ? a.j : 0 == a.j.length ? a.h : Sb(a.j, a.h);
        };
    cq.prototype.reset = function () {
        this.h = [];
        this.j = [];
    };
    var iq = function (a, b) {
            a = 1 == b.ta() ? a.h : a.j;
            var c = Mb(a, function (d) {
                return d == b;
            });
            return -1 != c ? (a.splice(c, 1), b.ba && b.ba.Kb(), b.dispose(), !0) : !1;
        },
        jq = function (a) {
            var b = gq;
            if (iq(b, a)) {
                switch (a.ta()) {
                    case 0:
                        var c = function () {
                            return null;
                        };
                    case 2:
                        c = function () {
                            return fq(b, a.ia);
                        };
                        break;
                    case 1:
                        c = function () {
                            return dq(b, a.ia);
                        };
                }
                for (var d = c(); d; d = c()) iq(b, d);
            }
        },
        kq = function (a) {
            var b = gq;
            a = Hb(a, function (c) {
                return !eq(b, c.ra.Ua);
            });
            b.h.push.apply(b.h, ha(a));
        },
        lq = function (a) {
            var b = gq,
                c = [];
            Fb(a, function (d) {
                Kb(b.h, function (e) {
                    return e.ra.Ua === d.ra.Ua && e.ia === d.ia;
                }) || (b.h.push(d), c.push(d));
            });
        },
        gq = G(cq);
    var mq = function () {
            this.h = this.j = null;
        },
        nq = function (a, b) {
            if (null == a.j) return !1;
            var c = function (d, e) {
                b(d, e);
            };
            a.h = Lb(a.j, function (d) {
                return null != d && d.ld();
            });
            a.h && (a.h.init(c) ? Zn(a.h.h) : b(a.h.h.nb(), a.h));
            return null != a.h;
        };
    var pq = function (a) {
        a = oq(a);
        eo.call(this, a.length ? a[a.length - 1] : new Vn(N, 0));
        this.l = a;
        this.j = null;
    };
    r(pq, eo);
    l = pq.prototype;
    l.getName = function () {
        return (this.j ? this.j : this.h).getName();
    };
    l.Ra = function () {
        return (this.j ? this.j : this.h).Ra();
    };
    l.Ha = function () {
        return (this.j ? this.j : this.h).Ha();
    };
    l.init = function (a) {
        var b = !1;
        Fb(this.l, function (c) {
            c.initialize() && (b = !0);
        });
        b && ((this.o = a), Yn(this.h, this));
        return b;
    };
    l.dispose = function () {
        Fb(this.l, function (a) {
            a.dispose();
        });
        eo.prototype.dispose.call(this);
    };
    l.ld = function () {
        return Kb(this.l, function (a) {
            return a.G();
        });
    };
    l.yb = function () {
        return Kb(this.l, function (a) {
            return a.G();
        });
    };
    l.Lb = function (a, b, c) {
        return new yp(a, this.h, b, c);
    };
    l.Sa = function (a) {
        this.j = a.j;
    };
    var oq = function (a) {
        if (!a.length) return [];
        a = Hb(a, function (c) {
            return null != c && c.G();
        });
        for (var b = 1; b < a.length; b++) Yn(a[b - 1], a[b]);
        return a;
    };
    var qq = { threshold: [0, 0.3, 0.5, 0.75, 1] },
        rq = function (a, b, c, d) {
            co.call(this, a, b, c, d);
            this.J = this.H = this.A = this.B = this.l = null;
        };
    r(rq, yp);
    rq.prototype.uc = function () {
        var a = this;
        this.J || (this.J = tn());
        if (
            kn(298, function () {
                return sq(a);
            })
        )
            return !0;
        Xn(this.j, "msf");
        return !1;
    };
    rq.prototype.Kb = function () {
        if (this.l && this.element)
            try {
                this.l.unobserve(this.element), this.B ? (this.B.unobserve(this.element), (this.B = null)) : this.A && (this.A.disconnect(), (this.A = null));
            } catch (a) {}
    };
    var tq = function (a) {
            return a.l && a.l.takeRecords ? a.l.takeRecords() : [];
        },
        sq = function (a) {
            if (!a.element) return !1;
            var b = a.element,
                c = a.j.h.l,
                d = P().h.h;
            a.l = new c.IntersectionObserver(
                Um(d, function (e) {
                    return uq(a, e);
                }),
                qq
            );
            d = Um(d, function () {
                a.l.unobserve(b);
                a.l.observe(b);
                uq(a, tq(a));
            });
            c.ResizeObserver ? ((a.B = new c.ResizeObserver(d)), a.B.observe(b)) : c.MutationObserver && ((a.A = new t.MutationObserver(d)), a.A.observe(b, { attributes: !0, childList: !0, characterData: !0, subtree: !0 }));
            a.l.observe(b);
            uq(a, tq(a));
            return !0;
        },
        uq = function (a, b) {
            try {
                if (b.length) {
                    a.H || (a.H = tn());
                    var c = vq(b),
                        d = al(a.element, a.j.h.l),
                        e = d.x,
                        f = d.y;
                    a.h = new B(Math.round(f), Math.round(e) + c.boundingClientRect.width, Math.round(f) + c.boundingClientRect.height, Math.round(e));
                    var g = Kn(c.intersectionRect);
                    a.o = Jf(g, a.h.left - g.left, a.h.top - g.top);
                }
            } catch (h) {
                a.Kb(), mn(299, h);
            }
        },
        vq = function (a) {
            return Jb(
                a,
                function (b, c) {
                    return b.time > c.time ? b : c;
                },
                a[0]
            );
        };
    l = rq.prototype;
    l.Pa = function () {
        var a = tq(this);
        0 < a.length && uq(this, a);
        yp.prototype.Pa.call(this);
    };
    l.yc = function () {};
    l.Kd = function () {
        return !1;
    };
    l.ud = function () {};
    l.Ra = function () {
        var a = {};
        return Object.assign(this.j.Ra(), ((a.niot_obs = this.J), (a.niot_cbk = this.H), a));
    };
    l.getName = function () {
        return "nio";
    };
    var wq = function (a) {
        a = void 0 === a ? N : a;
        eo.call(this, new Vn(a, 2));
    };
    r(wq, eo);
    wq.prototype.getName = function () {
        return "nio";
    };
    wq.prototype.yb = function () {
        return !Q().j && null != this.h.h.l.IntersectionObserver;
    };
    wq.prototype.Lb = function (a, b, c) {
        return new rq(a, this.h, b, c);
    };
    var yq = function () {
        var a = xq();
        Vn.call(this, N.top, a, "geo");
    };
    r(yq, Vn);
    yq.prototype.W = function () {
        return Q().h;
    };
    yq.prototype.G = function () {
        var a = xq();
        this.J !== a && (this.h != this && a > this.h.J && ((this.h = this), Wn(this)), (this.J = a));
        return 2 == a;
    };
    var xq = function () {
        P();
        var a = Q();
        return a.l || a.j ? 0 : 2;
    };
    var zq = function () {};
    var Aq = function () {
            this.done = !1;
            this.h = { ye: 0, qd: 0, Nh: 0, Bd: 0, Ic: -1, Ee: 0, De: 0, Fe: 0 };
            this.A = null;
            this.B = !1;
            this.l = null;
            this.C = 0;
            this.j = new Un(this);
        },
        Dq = function () {
            var a = Bq;
            a.B ||
                ((a.B = !0),
                Cq(a, function () {
                    return a.o.apply(a, ha(Ha.apply(0, arguments)));
                }),
                a.o());
        };
    Aq.prototype.sample = function () {
        Eq(this, hq(), !1);
    };
    var Fq = function () {
            G(zq);
            var a = G(mq);
            null != a.h && a.h.h ? Zn(a.h.h) : Q().update(N);
        },
        Eq = function (a, b, c) {
            if (!a.done && (a.j.cancel(), 0 != b.length)) {
                a.l = null;
                try {
                    Fq();
                    var d = tn(),
                        e = P();
                    e.B = d;
                    if (null != G(mq).h) for (e = 0; e < b.length; e++) Vo(b[e], d, c);
                    else an(cn, "", { strategy_not_selected: 1, bin: e.j }, !0, void 0);
                    for (d = 0; d < b.length; d++) Wo(b[d]);
                    ++a.h.Bd;
                } finally {
                    c
                        ? Fb(b, function (f) {
                              f.ma.X = 0;
                          })
                        : a.j.schedule();
                }
            }
        },
        Cq = function (a, b) {
            if (!a.A) {
                b = ln(142, b);
                Nm();
                var c = yh(Fl);
                c && De(Fl, c, b, { capture: !1 }) && (a.A = b);
            }
        };
    Aq.prototype.o = function () {
        var a = Tn(),
            b = tn();
        a
            ? (sn ||
                  ((on = b),
                  Fb(gq.h, function (c) {
                      var d = c.la();
                      d.R = up(d, b, 1 != c.ea);
                  })),
              (sn = !0))
            : ((this.C = Gq(this, b)),
              (sn = !1),
              (qn = b),
              Fb(gq.h, function (c) {
                  c.Sb && (c.la().H = b);
              }));
        Eq(this, hq(), !a);
    };
    var Hq = function () {
            var a = G(mq);
            if (null != a.h) {
                var b = a.h;
                Fb(hq(), function (c) {
                    return To(c, b);
                });
            }
        },
        Gq = function (a, b) {
            a = a.C;
            sn && (a += b - on);
            return a;
        },
        Iq = function (a) {
            var b = Bq;
            a =
                void 0 === a
                    ? function () {
                          return {};
                      }
                    : a;
            gn.Zc("av-js");
            cn.h = 0.01;
            jn([
                function (c) {
                    var d = P(),
                        e = {};
                    e = ((e.bin = d.j), (e.type = "error"), e);
                    d = ym(d.featureSet);
                    if (!b.l) {
                        var f = N.document,
                            g = 0 <= pn ? tn() - pn : -1,
                            h = tn();
                        -1 == b.h.Ic && (g = h);
                        var k = Q(),
                            n = P(),
                            m = ym(n.featureSet),
                            x = hq();
                        try {
                            if (0 < x.length) {
                                var v = k.h;
                                v && (m.bs = [v.getWidth(), v.getHeight()]);
                                var A = k.o;
                                A && (m.ps = [A.width, A.height]);
                                N.screen && (m.scs = [N.screen.width, N.screen.height]);
                            } else (m.url = encodeURIComponent(N.location.href.substring(0, 512))), f.referrer && (m.referrer = encodeURIComponent(f.referrer.substring(0, 512)));
                            m.tt = g;
                            m.pt = pn;
                            m.bin = n.j;
                            void 0 !== N.google_osd_load_pub_page_exp && (m.olpp = N.google_osd_load_pub_page_exp);
                            m.deb = [1, b.h.ye, b.h.qd, b.h.Bd, b.h.Ic, 0, b.j.j, b.h.Ee, b.h.De, b.h.Fe].join("-");
                            m.tvt = Gq(b, h);
                            k.j && (m.inapp = 1);
                            if (null !== N && N != N.top) {
                                0 < x.length && (m.iframe_loc = encodeURIComponent(N.location.href.substring(0, 512)));
                                var C = k.C;
                                m.is = [C.getWidth(), C.getHeight()];
                            }
                        } catch (la) {
                            m.error = 1;
                        }
                        b.l = m;
                    }
                    A = b.l;
                    v = {};
                    for (var O in A) v[O] = A[O];
                    O = P().h;
                    1 == xm(O.l, "prf")
                        ? ((A = new Rm()),
                          (C = O.h),
                          (f = 0),
                          -1 < C.h && (f = C.l.h.h() - C.h),
                          (A = sg(A, 1, C.o + f, 0)),
                          (C = O.h),
                          (A = Ag(A, 5, -1 < C.h ? C.j + 1 : C.j)),
                          (A = Ag(A, 2, O.j.h.o())),
                          (A = Ag(A, 3, O.j.h.l())),
                          (O = Ag(A, 4, O.j.h.j())),
                          (A = {}),
                          (O = ((A.pf = Dc(Sg(O, Sm))), A)))
                        : (O = {});
                    Nd(v, O);
                    Nd(c, e, d, v, a());
                    if ((e = po())) (d = {}), Nd(c, ((d.v = encodeURIComponent(e)), d));
                },
            ]);
        },
        Bq = G(Aq);
    var Jq = null,
        Kq = "",
        Lq = !1,
        Mq = function () {
            var a = Jq || N;
            if (!a) return "";
            var b = [];
            if (!a.location || !a.location.href) return "";
            b.push("url=" + encodeURIComponent(a.location.href.substring(0, 512)));
            a.document && a.document.referrer && b.push("referrer=" + encodeURIComponent(a.document.referrer.substring(0, 512)));
            return b.join("&");
        };
    function Nq() {
        var a =
                "av.default_js_unreleased_RCxx".match(/_(\d{8})_RC\d+$/) ||
                "av.default_js_unreleased_RCxx".match(/_(\d{8})_\d+_\d+$/) ||
                "av.default_js_unreleased_RCxx".match(/_(\d{8})_\d+\.\d+$/) ||
                "av.default_js_unreleased_RCxx".match(/_(\d{8})_\d+_RC\d+$/),
            b;
        if (2 == (null == (b = a) ? void 0 : b.length)) return a[1];
        a = "av.default_js_unreleased_RCxx".match(/.*_(\d{2})\.(\d{4})\.\d+_RC\d+$/);
        var c;
        return 3 == (null == (c = a) ? void 0 : c.length) ? "20" + a[1] + a[2] : null;
    }
    var Oq = function () {
            return "ima_html5_sdk".includes("ima_html5_sdk")
                ? { Aa: "ima", Ba: null }
                : "ima_html5_sdk".includes("ima_native_sdk")
                ? { Aa: "nima", Ba: null }
                : "ima_html5_sdk".includes("admob-native-video-javascript")
                ? { Aa: "an", Ba: null }
                : "av.default_js_unreleased_RCxx".includes("cast_js_sdk")
                ? { Aa: "cast", Ba: Nq() }
                : "av.default_js_unreleased_RCxx".includes("youtube.player.web")
                ? { Aa: "yw", Ba: Nq() }
                : "av.default_js_unreleased_RCxx".includes("outstream_web_client")
                ? { Aa: "out", Ba: Nq() }
                : "av.default_js_unreleased_RCxx".includes("drx_rewarded_web")
                ? { Aa: "r", Ba: Nq() }
                : "av.default_js_unreleased_RCxx".includes("gam_native_web_video")
                ? { Aa: "n", Ba: Nq() }
                : "av.default_js_unreleased_RCxx".includes("admob_interstitial_video")
                ? { Aa: "int", Ba: Nq() }
                : { Aa: "j", Ba: null };
        },
        Pq = Oq().Aa,
        Qq = Oq().Ba;
    var Sq = function (a, b) {
            var c = { sv: "922" };
            null !== Qq && (c.v = Qq);
            c.cb = Pq;
            c.nas = gq.h.length;
            c.msg = a;
            void 0 !== b && (a = Rq(b)) && (c.e = Bn[a]);
            return c;
        },
        Tq = function (a) {
            return 0 == a.lastIndexOf("custom_metric_viewable", 0);
        },
        Rq = function (a) {
            var b = Tq(a) ? "custom_metric_viewable" : a.toLowerCase();
            return Hd(function (c) {
                return c == b;
            });
        };
    var Uq = { dg: "visible", Kf: "audible", oh: "time", qh: "timetype" },
        Vq = {
            visible: function (a) {
                return /^(100|[0-9]{1,2})$/.test(a);
            },
            audible: function (a) {
                return "0" == a || "1" == a;
            },
            timetype: function (a) {
                return "mtos" == a || "tos" == a;
            },
            time: function (a) {
                return /^(100|[0-9]{1,2})%$/.test(a) || /^([0-9])+ms$/.test(a);
            },
        },
        Wq = function () {
            this.h = void 0;
            this.j = !1;
            this.l = 0;
            this.o = -1;
            this.A = "tos";
        },
        Xq = function (a) {
            try {
                var b = a.split(",");
                return b.length > Dd(Uq).length
                    ? null
                    : Jb(
                          b,
                          function (c, d) {
                              d = d.toLowerCase().split("=");
                              if (2 != d.length || void 0 === Vq[d[0]] || !Vq[d[0]](d[1])) throw Error("Entry (" + d[0] + ", " + d[1] + ") is invalid.");
                              c[d[0]] = d[1];
                              return c;
                          },
                          {}
                      );
            } catch (c) {
                return null;
            }
        },
        Yq = function (a, b) {
            if (void 0 == a.h) return 0;
            switch (a.A) {
                case "mtos":
                    return a.j ? Bo(b.h, a.h) : Bo(b.j, a.h);
                case "tos":
                    return a.j ? zo(b.h, a.h) : zo(b.j, a.h);
            }
            return 0;
        };
    var Zq = function (a, b, c, d) {
        wp.call(this, b, d);
        this.C = a;
        this.H = c;
    };
    r(Zq, wp);
    Zq.prototype.getId = function () {
        return this.C;
    };
    Zq.prototype.B = function () {
        return !0;
    };
    Zq.prototype.h = function (a) {
        var b = a.la(),
            c = a.getDuration();
        return Kb(this.H, function (d) {
            if (void 0 != d.h) var e = Yq(d, b);
            else
                b: {
                    switch (d.A) {
                        case "mtos":
                            e = d.j ? b.A.l : b.l.h;
                            break b;
                        case "tos":
                            e = d.j ? b.A.h : b.l.h;
                            break b;
                    }
                    e = 0;
                }
            0 == e ? (d = !1) : ((d = -1 != d.l ? d.l : void 0 !== c && 0 < c ? d.o * c : -1), (d = -1 != d && e >= d));
            return d;
        });
    };
    var $q = function () {};
    var ar = function () {};
    r(ar, $q);
    ar.prototype.j = function () {
        return null;
    };
    ar.prototype.l = function () {
        return [];
    };
    var br = function () {};
    r(br, mp);
    br.prototype.h = function (a) {
        var b = new lp();
        b.h = np(a, jp);
        b.j = np(a, kp);
        return b;
    };
    var cr = function (a) {
        wp.call(this, "fully_viewable_audible_half_duration_impression", a);
    };
    r(cr, wp);
    cr.prototype.h = function (a) {
        return Lp(a);
    };
    var dr = function (a) {
        this.h = a;
    };
    r(dr, $q);
    var er = function (a, b) {
        wp.call(this, a, b);
    };
    r(er, wp);
    er.prototype.h = function (a) {
        return a.la().Ka();
    };
    var fr = function (a) {
        xp.call(this, "measurable_impression", a);
    };
    r(fr, xp);
    fr.prototype.h = function (a) {
        var b = Ob(this.C, xm(P().featureSet, "ovms"));
        return !a.Ja && (0 != a.ea || b);
    };
    var gr = function () {
        dr.apply(this, arguments);
    };
    r(gr, dr);
    gr.prototype.j = function () {
        return new fr(this.h);
    };
    gr.prototype.l = function () {
        return [new er("viewable_impression", this.h), new cr(this.h)];
    };
    var hr = function (a, b, c) {
        Ap.call(this, a, b, c);
    };
    r(hr, Ap);
    hr.prototype.l = function () {
        var a = Ma("ima.admob.getViewability"),
            b = xm(this.featureSet, "queryid");
        "function" === typeof a && b && a(b);
    };
    hr.prototype.getName = function () {
        return "gsv";
    };
    var ir = function (a) {
        a = void 0 === a ? N : a;
        eo.call(this, new Vn(a, 2));
    };
    r(ir, eo);
    ir.prototype.getName = function () {
        return "gsv";
    };
    ir.prototype.yb = function () {
        var a = Q();
        P();
        return a.j && !1;
    };
    ir.prototype.Lb = function (a, b, c) {
        return new hr(this.h, b, c);
    };
    var jr = function (a, b, c) {
        Ap.call(this, a, b, c);
    };
    r(jr, Ap);
    jr.prototype.l = function () {
        var a = this,
            b = Ma("ima.bridge.getNativeViewability"),
            c = xm(this.featureSet, "queryid");
        "function" === typeof b &&
            c &&
            b(c, function (d) {
                Jd(d) && a.A++;
                var e = d.opt_nativeViewVisibleBounds || {},
                    f = d.opt_nativeViewHidden;
                a.h = Ln(d.opt_nativeViewBounds || {});
                var g = a.j.o;
                g.h = f ? If(zp) : Ln(e);
                a.timestamp = d.opt_nativeTime || -1;
                Q().h = g.h;
                d = d.opt_nativeVolume;
                void 0 !== d && (g.volume = d);
            });
    };
    jr.prototype.getName = function () {
        return "nis";
    };
    var kr = function (a) {
        a = void 0 === a ? N : a;
        eo.call(this, new Vn(a, 2));
    };
    r(kr, eo);
    kr.prototype.getName = function () {
        return "nis";
    };
    kr.prototype.yb = function () {
        var a = Q();
        P();
        return a.j && !1;
    };
    kr.prototype.Lb = function (a, b, c) {
        return new jr(this.h, b, c);
    };
    var lr = function () {
        Vn.call(this, N, 2, "mraid");
        this.Y = 0;
        this.K = this.L = !1;
        this.H = null;
        this.j = Cn(this.l);
        this.o.h = new B(0, 0, 0, 0);
        this.Z = !1;
    };
    r(lr, Vn);
    lr.prototype.G = function () {
        return null != this.j.wa;
    };
    lr.prototype.V = function () {
        var a = {};
        this.Y && (a.mraid = this.Y);
        this.L && (a.mlc = 1);
        a.mtop = this.j.vf;
        this.H && (a.mse = this.H);
        this.Z && (a.msc = 1);
        a.mcp = this.j.compatibility;
        return a;
    };
    lr.prototype.C = function (a) {
        var b = Ha.apply(1, arguments);
        try {
            return this.j.wa[a].apply(this.j.wa, b);
        } catch (c) {
            mn(538, c, 0.01, function (d) {
                d.method = a;
            });
        }
    };
    var mr = function (a, b, c) {
        a.C("addEventListener", b, c);
    };
    lr.prototype.initialize = function () {
        var a = this;
        if (this.isInitialized) return !this.Db();
        this.isInitialized = !0;
        if (2 === this.j.compatibility) return (this.H = "ng"), Xn(this, "w"), !1;
        if (1 === this.j.compatibility) return (this.H = "mm"), Xn(this, "w"), !1;
        Q().J = !0;
        this.l.document.readyState && "complete" == this.l.document.readyState
            ? nr(this)
            : Io(
                  this.l,
                  "load",
                  function () {
                      Nm().setTimeout(
                          ln(292, function () {
                              return nr(a);
                          }),
                          100
                      );
                  },
                  292
              );
        return !0;
    };
    var nr = function (a) {
            P().A = !!a.C("isViewable");
            mr(a, "viewableChange", or);
            "loading" === a.C("getState") ? mr(a, "ready", pr) : qr(a);
        },
        qr = function (a) {
            "string" === typeof a.j.wa.AFMA_LIDAR ? ((a.L = !0), rr(a)) : ((a.j.compatibility = 3), (a.H = "nc"), Xn(a, "w"));
        },
        rr = function (a) {
            a.K = !1;
            var b = 1 == xm(P().featureSet, "rmmt"),
                c = !!a.C("isViewable");
            (b ? !c : 1) &&
                Nm().setTimeout(
                    ln(524, function () {
                        a.K || (sr(a), mn(540, Error()), (a.H = "mt"), Xn(a, "w"));
                    }),
                    500
                );
            tr(a);
            mr(a, a.j.wa.AFMA_LIDAR, ur);
        },
        tr = function (a) {
            var b = 1 == xm(P().featureSet, "sneio"),
                c = void 0 !== a.j.wa.AFMA_LIDAR_EXP_1,
                d = void 0 !== a.j.wa.AFMA_LIDAR_EXP_2;
            (b = b && d) && (a.j.wa.AFMA_LIDAR_EXP_2 = !0);
            c && (a.j.wa.AFMA_LIDAR_EXP_1 = !b);
        },
        sr = function (a) {
            a.C("removeEventListener", a.j.wa.AFMA_LIDAR, ur);
            a.L = !1;
        };
    lr.prototype.R = function () {
        var a = Q(),
            b = vr(this, "getMaxSize");
        a.h = new B(0, b.width, b.height, 0);
    };
    lr.prototype.T = function () {
        Q().A = vr(this, "getScreenSize");
    };
    var vr = function (a, b) {
        if ("loading" === a.C("getState")) return new y(-1, -1);
        b = a.C(b);
        if (!b) return new y(-1, -1);
        a = parseInt(b.width, 10);
        b = parseInt(b.height, 10);
        return isNaN(a) || isNaN(b) ? new y(-1, -1) : new y(a, b);
    };
    lr.prototype.dispose = function () {
        sr(this);
        Vn.prototype.dispose.call(this);
    };
    var pr = function () {
            try {
                var a = G(lr);
                a.C("removeEventListener", "ready", pr);
                qr(a);
            } catch (b) {
                mn(541, b);
            }
        },
        ur = function (a, b) {
            try {
                var c = G(lr);
                c.K = !0;
                var d = a ? new B(a.y, a.x + a.width, a.y + a.height, a.x) : new B(0, 0, 0, 0);
                var e = tn(),
                    f = Tn();
                var g = new vn(e, f, c);
                g.h = d;
                g.volume = b;
                c.Sa(g);
            } catch (h) {
                mn(542, h);
            }
        },
        or = function (a) {
            var b = P(),
                c = G(lr);
            a && !b.A && ((b.A = !0), (c.Z = !0), c.H && Xn(c, "w", !0));
        };
    var xr = function () {
        this.l = this.isInitialized = !1;
        this.h = this.j = null;
        var a = {};
        this.K =
            ((a.start = this.We),
            (a.firstquartile = this.Re),
            (a.midpoint = this.Te),
            (a.thirdquartile = this.Xe),
            (a.complete = this.Pe),
            (a.pause = this.Sc),
            (a.resume = this.Rd),
            (a.skip = this.Ve),
            (a.viewable_impression = this.Ca),
            (a.mute = this.tb),
            (a.unmute = this.tb),
            (a.fullscreen = this.Se),
            (a.exitfullscreen = this.Qe),
            (a.fully_viewable_audible_half_duration_impression = this.Ca),
            (a.measurable_impression = this.Ca),
            (a.abandon = this.Sc),
            (a.engagedview = this.Ca),
            (a.impression = this.Ca),
            (a.creativeview = this.Ca),
            (a.progress = this.tb),
            (a.custom_metric_viewable = this.Ca),
            (a.bufferstart = this.Sc),
            (a.bufferfinish = this.Rd),
            (a.audio_measurable = this.Ca),
            (a.audio_audible = this.Ca),
            a);
        a = {};
        this.M =
            ((a.overlay_resize = this.Ue),
            (a.abandon = this.Gc),
            (a.close = this.Gc),
            (a.collapse = this.Gc),
            (a.overlay_unmeasurable_impression = function (b) {
                return Sp(b, "overlay_unmeasurable_impression", Tn());
            }),
            (a.overlay_viewable_immediate_impression = function (b) {
                return Sp(b, "overlay_viewable_immediate_impression", Tn());
            }),
            (a.overlay_unviewable_impression = function (b) {
                return Sp(b, "overlay_unviewable_impression", Tn());
            }),
            (a.overlay_viewable_end_of_session_impression = function (b) {
                return Sp(b, "overlay_viewable_end_of_session_impression", Tn());
            }),
            a);
        P().j = 3;
        wr(this);
    };
    xr.prototype.A = function (a) {
        So(a, !1);
        jq(a);
    };
    xr.prototype.C = function () {};
    var yr = function (a, b, c, d) {
        a = a.B(null, d, !0, b);
        a.A = c;
        kq([a]);
        return a;
    };
    xr.prototype.B = function (a, b, c, d) {
        var e = this;
        b = c ? b : -1;
        c = this.zd();
        a = new Ep(N, a, b, 7, this.Ac(), c.j(), c.l());
        a.ia = d;
        vm(a.featureSet);
        wm(a.featureSet, "queryid", a.ia);
        a.Uc("");
        Xo(
            a,
            function () {
                return e.I.apply(e, ha(Ha.apply(0, arguments)));
            },
            function () {
                return e.L.apply(e, ha(Ha.apply(0, arguments)));
            }
        );
        (d = G(mq).h) && To(a, d);
        a.ra.Ua && G(zq);
        return a;
    };
    var zr = function (a, b, c) {
            dm(b);
            var d = a.h;
            Fb(b, function (e) {
                var f = Ib(e.criteria, function (g) {
                    var h = Xq(g);
                    if (null == h) g = null;
                    else if (((g = new Wq()), null != h.visible && (g.h = h.visible / 100), null != h.audible && (g.j = 1 == h.audible), null != h.time)) {
                        var k = "mtos" == h.timetype ? "mtos" : "tos",
                            n = kb(h.time, "%") ? "%" : "ms";
                        h = parseInt(h.time, 10);
                        "%" == n && (h /= 100);
                        "ms" == n ? ((g.l = h), (g.o = -1)) : ((g.l = -1), (g.o = h));
                        g.A = void 0 === k ? "tos" : k;
                    }
                    return g;
                });
                Kb(f, function (g) {
                    return null == g;
                }) || Kp(c, new Zq(e.id, e.event, f, d));
            });
        },
        Ar = function () {
            var a = [],
                b = P();
            a.push(G(yq));
            xm(b.featureSet, "mvp_lv") && a.push(G(lr));
            b = [new ir(), new kr()];
            b.push(new pq(a));
            b.push(new wq(N));
            return b;
        },
        Cr = function (a) {
            if (!a.isInitialized) {
                a.isInitialized = !0;
                try {
                    var b = tn(),
                        c = P(),
                        d = Q();
                    pn = b;
                    c.o = 79463069;
                    "o" !== a.j && (Jq = Hf(N));
                    if (Om()) {
                        Bq.h.qd = 0;
                        Bq.h.Ic = tn() - b;
                        var e = Ar(),
                            f = G(mq);
                        f.j = e;
                        nq(f, function () {
                            Br();
                        })
                            ? Bq.done || (Hq(), Yn(f.h.h, a), Dq())
                            : d.l
                            ? Br()
                            : Dq();
                    } else Lq = !0;
                } catch (g) {
                    throw (gq.reset(), g);
                }
            }
        },
        Dr = function (a) {
            Bq.j.cancel();
            Kq = a;
            Bq.done = !0;
        },
        Er = function (a) {
            if (a.j) return a.j;
            var b = G(mq).h;
            if (b)
                switch (b.getName()) {
                    case "nis":
                        a.j = "n";
                        break;
                    case "gsv":
                        a.j = "m";
                }
            a.j || (a.j = "h");
            return a.j;
        },
        Fr = function (a, b, c) {
            if (null == a.h) return (b.ub |= 4), !1;
            a = a.h.report(c, b);
            b.ub |= a;
            return 0 == a;
        };
    xr.prototype.pb = function (a) {
        switch (a.Ha()) {
            case 0:
                if ((a = G(mq).h)) (a = a.h), Pb(a.B, this), a.D && this.Da() && ao(a);
                Br();
                break;
            case 2:
                Dq();
        }
    };
    xr.prototype.Sa = function () {};
    xr.prototype.Da = function () {
        return !1;
    };
    var Br = function () {
        var a = [new wq(N)],
            b = G(mq);
        b.j = a;
        nq(b, function () {
            Dr("i");
        })
            ? Bq.done || (Hq(), Dq())
            : Dr("i");
    };
    xr.prototype.L = function (a, b) {
        a.Ja = !0;
        switch (a.ta()) {
            case 1:
                Gr(a, b);
                break;
            case 2:
                this.Xc(a);
        }
        this.Yc(a);
    };
    var Gr = function (a, b) {
        if (!a.sa) {
            var c = Sp(a, "start", Tn());
            c = a.Tc.h(c).h;
            var d = { id: "lidarv" };
            d.r = b;
            d.sv = "922";
            null !== Qq && (d.v = Qq);
            pf(c, function (e, f) {
                return (d[e] = "mtos" == e || "tos" == e ? f : encodeURIComponent(f));
            });
            b = Mq();
            pf(b, function (e, f) {
                return (d[e] = encodeURIComponent(f));
            });
            b = "//pagead2.googlesyndication.com/pagead/gen_204?" + mo(ko(new io(), d));
            qo(b);
            a.sa = !0;
        }
    };
    l = xr.prototype;
    l.We = function (a) {
        var b = a.C(a);
        b && ((b = b.volume), (a.pa = Mn(b) && 0 < b));
        Pp(a, 0);
        return Sp(a, "start", Tn());
    };
    l.tb = function (a, b, c) {
        Eq(Bq, [a], !Tn());
        return this.Ca(a, b, c);
    };
    l.Ca = function (a, b, c) {
        return Sp(a, c, Tn());
    };
    l.Re = function (a) {
        return Hr(a, "firstquartile", 1);
    };
    l.Te = function (a) {
        a.Y = !0;
        return Hr(a, "midpoint", 2);
    };
    l.Xe = function (a) {
        return Hr(a, "thirdquartile", 3);
    };
    l.Pe = function (a) {
        var b = Hr(a, "complete", 4);
        0 != a.ea && (a.ea = 3);
        return b;
    };
    var Hr = function (a, b, c) {
        Eq(Bq, [a], !Tn());
        Pp(a, c);
        4 != c && Op(a.L, c, a.Ub);
        return Sp(a, b, Tn());
    };
    l = xr.prototype;
    l.Rd = function (a, b, c) {
        b = Tn();
        2 != a.ea || b || (a.la().H = tn());
        Eq(Bq, [a], !b);
        2 == a.ea && (a.ea = 1);
        return Sp(a, c, b);
    };
    l.Ve = function (a, b) {
        b = this.tb(a, b || {}, "skip");
        0 != a.ea && (a.ea = 3);
        return b;
    };
    l.Se = function (a, b) {
        So(a, !0);
        return this.tb(a, b || {}, "fullscreen");
    };
    l.Qe = function (a, b) {
        So(a, !1);
        return this.tb(a, b || {}, "exitfullscreen");
    };
    l.Sc = function (a, b, c) {
        b = a.la();
        b.R = up(b, tn(), 1 != a.ea);
        Eq(Bq, [a], !Tn());
        1 == a.ea && (a.ea = 2);
        return Sp(a, c, Tn());
    };
    l.Ue = function (a) {
        Eq(Bq, [a], !Tn());
        return a.j();
    };
    l.Gc = function (a) {
        Eq(Bq, [a], !Tn());
        this.Pd(a);
        0 != a.ea && (a.ea = 3);
        return a.j();
    };
    var wr = function (a) {
            Iq(function () {
                var b = Ir();
                null != a.j && (b.sdk = a.j);
                var c = G(mq);
                null != c.h && (b.avms = c.h.getName());
                return b;
            });
        },
        Jr = function (a, b, c, d) {
            var e = eq(gq, c);
            null !== e && e.ia !== b && (a.A(e), (e = null));
            e || ((b = a.B(c, tn(), !1, b)), 0 == gq.j.length && (P().o = 79463069), lq([b]), (e = b), (e.A = Er(a)), d && (e.Va = d));
            return e;
        };
    xr.prototype.I = function () {};
    var Lr = function (a, b) {
        b.D = 0;
        for (var c in xn) null == a[c] && (b.D |= xn[c]);
        Kr(a, "currentTime");
        Kr(a, "duration");
    };
    l = xr.prototype;
    l.Xc = function () {};
    l.Pd = function () {};
    l.md = function () {};
    l.Yc = function () {};
    l.Bc = function () {};
    l.zd = function () {
        this.h || (this.h = this.Bc());
        return null == this.h || this.l ? new ar() : new gr(this.h);
    };
    l.Ac = function () {
        return new br();
    };
    var Kr = function (a, b) {
            var c = a[b];
            void 0 !== c && 0 < c && (a[b] = Math.floor(1e3 * c));
        },
        Ir = function () {
            var a = Q(),
                b = {},
                c = {},
                d = {};
            return Object.assign({}, ((b.sv = "922"), b), null !== Qq && ((c.v = Qq), c), ((d["if"] = a.l ? "1" : "0"), (d.nas = String(gq.h.length)), d));
        };
    var Mr = function (a) {
        this.j = a;
    };
    Mr.prototype.report = function (a, b) {
        var c = this.h(b);
        if ("function" === typeof c) {
            var d = {};
            var e = {};
            d = Object.assign({}, null !== Qq && ((d.v = Qq), d), ((e.sv = "922"), (e.cb = Pq), (e.e = Nr(a)), e));
            e = Sp(b, a, Tn());
            Nd(d, e);
            b.Wd[a] = e;
            d = 2 == b.ta() ? oo(d).join("&") : b.Tc.h(d).h;
            try {
                return c(b.ia, d, a), 0;
            } catch (f) {
                return 2;
            }
        } else return 1;
    };
    var Nr = function (a) {
        var b = Tq(a) ? "custom_metric_viewable" : a;
        a = Hd(function (c) {
            return c == b;
        });
        return Bn[a];
    };
    Mr.prototype.h = function () {
        return Ma(this.j);
    };
    var Or = function (a, b) {
        this.j = a;
        this.l = b;
    };
    r(Or, Mr);
    Or.prototype.h = function (a) {
        if (!a.Va) return Mr.prototype.h.call(this, a);
        if (this.l[a.Va]) return function () {};
        mn(393, Error());
        return null;
    };
    var Pr = function () {
        xr.call(this);
        this.J = void 0;
        this.D = null;
        this.H = !1;
        this.o = {};
        this.G = 0;
    };
    r(Pr, xr);
    Pr.prototype.C = function (a, b) {
        var c = this,
            d = G(mq);
        if (null != d.h)
            switch (d.h.getName()) {
                case "nis":
                    var e = Qr(this, a, b);
                    break;
                case "gsv":
                    e = Rr(this, a, b);
                    break;
                case "exc":
                    e = Sr(this, a);
            }
        e || (b.opt_overlayAdElement ? (e = void 0) : b.opt_adElement && (e = Jr(this, a, b.opt_adElement, b.opt_osdId)));
        e &&
            1 == e.ta() &&
            (e.C == xe &&
                (e.C = function (f) {
                    return c.md(f);
                }),
            Tr(this, e, b));
        return e;
    };
    var Tr = function (a, b, c) {
        c = c.opt_configurable_tracking_events;
        null != a.h && Array.isArray(c) && zr(a, c, b);
    };
    Pr.prototype.md = function (a) {
        a.j = 0;
        a.M = 0;
        if ("h" == a.A || "n" == a.A) {
            if (P().l) var b = Ma("ima.bridge.getVideoMetadata");
            else if (a.Va && Ur(this)) {
                var c = this.o[a.Va];
                c
                    ? (b = function (e) {
                          return Vr(c, e);
                      })
                    : null !== c && mn(379, Error());
            } else b = Ma("ima.common.getVideoMetadata");
            if ("function" === typeof b)
                try {
                    var d = b(a.ia);
                } catch (e) {
                    a.j |= 4;
                }
            else a.j |= 2;
        } else if ("b" == a.A)
            if (((b = Ma("ytads.bulleit.getVideoMetadata")), "function" === typeof b))
                try {
                    d = b(a.ia);
                } catch (e) {
                    a.j |= 4;
                }
            else a.j |= 2;
        else if ("ml" == a.A)
            if (((b = Ma("ima.common.getVideoMetadata")), "function" === typeof b))
                try {
                    d = b(a.ia);
                } catch (e) {
                    a.j |= 4;
                }
            else a.j |= 2;
        else a.j |= 1;
        a.j || (void 0 === d ? (a.j |= 8) : null === d ? (a.j |= 16) : Jd(d) ? (a.j |= 32) : null != d.errorCode && ((a.M = d.errorCode), (a.j |= 64)));
        null == d && (d = {});
        Lr(d, a);
        Mn(d.volume) && Mn(this.J) && (d.volume *= this.J);
        return d;
    };
    var Rr = function (a, b, c) {
            var d = dq(gq, b);
            d || ((d = c.opt_nativeTime || -1), (d = yr(a, b, Er(a), d)), c.opt_osdId && (d.Va = c.opt_osdId));
            return d;
        },
        Qr = function (a, b, c) {
            var d = dq(gq, b);
            d || (d = yr(a, b, "n", c.opt_nativeTime || -1));
            return d;
        },
        Sr = function (a, b) {
            var c = dq(gq, b);
            c || (c = yr(a, b, "h", -1));
            return c;
        };
    Pr.prototype.Bc = function () {
        if (Ur(this)) return new Or("ima.common.triggerExternalActivityEvent", this.o);
        var a = Wr(this);
        return null != a ? new Mr(a) : null;
    };
    var Wr = function (a) {
        var b = P();
        switch (Er(a)) {
            case "b":
                return "ytads.bulleit.triggerExternalActivityEvent";
            case "n":
                return "ima.bridge.triggerExternalActivityEvent";
            case "h":
                if (b.l) return "ima.bridge.triggerExternalActivityEvent";
            case "m":
            case "ml":
                return "ima.common.triggerExternalActivityEvent";
        }
        return null;
    };
    Pr.prototype.Xc = function (a) {
        !a.h && a.Ja && Fr(this, a, "overlay_unmeasurable_impression") && (a.h = !0);
    };
    Pr.prototype.Pd = function (a) {
        a.Sd && (a.Ka() ? Fr(this, a, "overlay_viewable_end_of_session_impression") : Fr(this, a, "overlay_unviewable_impression"), (a.Sd = !1));
    };
    var Xr = function (a, b, c, d) {
        c = void 0 === c ? {} : c;
        var e = {};
        Nd(e, { opt_adElement: void 0, opt_fullscreen: void 0 }, c);
        var f = a.C(b, c);
        c = f ? f.Tc : a.Ac();
        if (e.opt_bounds) return c.h(Sq("ol", d));
        if (void 0 !== d)
            if (void 0 !== Rq(d))
                if (Lq) a = Sq("ue", d);
                else if ((Cr(a), "i" == Kq)) (a = Sq("i", d)), (a["if"] = 0);
                else if ((b = a.C(b, e))) {
                    b: {
                        "i" == Kq && ((b.Ja = !0), a.Yc(b));
                        f = e.opt_fullscreen;
                        void 0 !== f && So(b, !!f);
                        var g;
                        if ((f = !Q().j && !Pn())) Nm(), (f = 0 === xh(Fl));
                        if ((g = f)) {
                            switch (b.ta()) {
                                case 1:
                                    Gr(b, "pv");
                                    break;
                                case 2:
                                    a.Xc(b);
                            }
                            Dr("pv");
                        }
                        f = d.toLowerCase();
                        if ((g = !g))
                            c: {
                                if (xm(P().featureSet, "ssmol") && ((g = a.l), "loaded" === f)) break c;
                                g = Ob(yn, f);
                            }
                        if (g && 0 == b.ea) {
                            "i" != Kq && (Bq.done = !1);
                            g = void 0 !== e ? e.opt_nativeTime : void 0;
                            rn = g = "number" === typeof g ? g : tn();
                            b.Sb = !0;
                            var h = Tn();
                            b.ea = 1;
                            b.ga = {};
                            b.ga.start = !1;
                            b.ga.firstquartile = !1;
                            b.ga.midpoint = !1;
                            b.ga.thirdquartile = !1;
                            b.ga.complete = !1;
                            b.ga.resume = !1;
                            b.ga.pause = !1;
                            b.ga.skip = !1;
                            b.ga.mute = !1;
                            b.ga.unmute = !1;
                            b.ga.viewable_impression = !1;
                            b.ga.measurable_impression = !1;
                            b.ga.fully_viewable_audible_half_duration_impression = !1;
                            b.ga.fullscreen = !1;
                            b.ga.exitfullscreen = !1;
                            b.Cc = 0;
                            h || (b.la().H = g);
                            Eq(Bq, [b], !h);
                        }
                        (g = b.fb[f]) && pp(b.fa, g);
                        Ob(zn, f) && b.ab && b.ab.A(b, null);
                        switch (b.ta()) {
                            case 1:
                                var k = Tq(f) ? a.K.custom_metric_viewable : a.K[f];
                                break;
                            case 2:
                                k = a.M[f];
                        }
                        if (k && ((d = k.call(a, b, e, d)), void 0 !== d)) {
                            e = Sq(void 0, f);
                            Nd(e, d);
                            d = e;
                            break b;
                        }
                        d = void 0;
                    }
                    3 == b.ea && a.A(b);
                    a = d;
                } else a = Sq("nf", d);
            else a = void 0;
        else Lq ? (a = Sq("ue")) : f ? ((a = Sq()), Nd(a, Rp(f, !0, !1, !1))) : (a = Sq("nf"));
        return "string" === typeof a ? c.h(void 0) : c.h(a);
    };
    Pr.prototype.I = function (a) {
        this.l && 1 == a.ta() && Yr(this, a);
    };
    Pr.prototype.Yc = function (a) {
        this.l && 1 == a.ta() && Yr(this, a);
    };
    var Yr = function (a, b) {
            var c;
            if (b.Va && Ur(a)) {
                var d = a.o[b.Va];
                d
                    ? (c = function (f, g) {
                          Zr(d, f, g);
                      })
                    : null !== d && mn(379, Error());
            } else c = Ma("ima.common.triggerViewabilityMeasurementUpdate");
            if ("function" === typeof c) {
                var e = Mp(b);
                e.nativeVolume = a.J;
                c(b.ia, e);
            }
        },
        $r = function (a, b, c) {
            a.o[b] = c;
        },
        Ur = function (a) {
            return P().l || ("h" != Er(a) && "m" != Er(a)) ? !1 : 0 != a.G;
        };
    Pr.prototype.B = function (a, b, c, d) {
        a = xr.prototype.B.call(this, a, b, c, d);
        this.H && ((b = this.D), null == a.o && (a.o = new $o()), (b.h[a.ia] = a.o), (a.o.A = Up));
        return a;
    };
    Pr.prototype.A = function (a) {
        a && 1 == a.ta() && this.H && delete this.D.h[a.ia];
        return xr.prototype.A.call(this, a);
    };
    Pr.prototype.zd = function () {
        this.h || (this.h = this.Bc());
        return null == this.h || this.l ? new ar() : new gr(this.h);
    };
    Pr.prototype.Ac = function () {
        return new br();
    };
    var as = function (a) {
            var b = {};
            return (b.viewability = a.h), (b.googleViewability = a.j), b;
        },
        bs = function (a, b, c) {
            c = void 0 === c ? {} : c;
            a = Xr(G(Pr), b, c, a);
            return as(a);
        },
        cs = ln(193, bs, void 0, Ir);
    u("Goog_AdSense_Lidar_sendVastEvent", cs, void 0);
    var ds = ln(194, function (a, b) {
        b = void 0 === b ? {} : b;
        a = Xr(G(Pr), a, b);
        return as(a);
    });
    u("Goog_AdSense_Lidar_getViewability", ds, void 0);
    var es = ln(195, function () {
        return Pm();
    });
    u("Goog_AdSense_Lidar_getUrlSignalsArray", es, void 0);
    var fs = ln(196, function () {
        return JSON.stringify(Pm());
    });
    u("Goog_AdSense_Lidar_getUrlSignalsList", fs, void 0);
    var hs = function (a) {
        F.call(this, a, -1, gs);
    };
    r(hs, F);
    var gs = [3];
    var js = function (a) {
        F.call(this, a, -1, is);
    };
    r(js, F);
    var ks = function (a, b) {
            return rg(a, 1, b);
        },
        ls = function (a, b) {
            return rg(a, 2, b);
        },
        ms = function (a, b) {
            return rg(a, 3, b);
        },
        ns = function (a, b) {
            rg(a, 4, b);
        },
        is = [1, 2, 3, 4];
    var os = function (a) {
        F.call(this, a);
    };
    r(os, F);
    var qs = function (a) {
        F.call(this, a, -1, ps);
    };
    r(qs, F);
    qs.prototype.getVersion = function () {
        return pg(this, 1, 0);
    };
    var rs = function (a, b) {
            return Ag(a, 1, b);
        },
        ts = function (a, b) {
            return wg(a, 2, b);
        },
        us = function (a, b) {
            return wg(a, 3, b);
        },
        vs = function (a, b) {
            return Ag(a, 4, b);
        },
        ws = function (a, b) {
            return Ag(a, 5, b);
        },
        xs = function (a, b) {
            return Ag(a, 6, b);
        },
        ys = function (a, b) {
            return sg(a, 7, b, "");
        },
        zs = function (a, b) {
            return Ag(a, 8, b);
        },
        As = function (a, b) {
            return Ag(a, 9, b);
        },
        Bs = function (a, b) {
            return sg(a, 10, b, !1);
        },
        Cs = function (a, b) {
            return sg(a, 11, b, !1);
        },
        Ds = function (a, b) {
            return rg(a, 12, b);
        },
        Es = function (a, b) {
            return rg(a, 13, b);
        },
        Fs = function (a, b) {
            return rg(a, 14, b);
        },
        Gs = function (a, b) {
            return sg(a, 15, b, !1);
        },
        Hs = function (a, b) {
            return sg(a, 16, b, "");
        },
        Is = function (a, b) {
            return rg(a, 17, b);
        },
        Js = function (a, b) {
            return rg(a, 18, b);
        },
        Ks = function (a, b) {
            return xg(a, 19, b);
        },
        ps = [12, 13, 14, 17, 18, 19];
    var Ls = function (a) {
        F.call(this, a);
    };
    r(Ls, F);
    var Ms = "a".charCodeAt(),
        Ns = Cd({ Ag: 0, zg: 1, wg: 2, rg: 3, xg: 4, sg: 5, yg: 6, ug: 7, vg: 8, qg: 9, tg: 10 }),
        Os = Cd({ Cg: 0, Dg: 1, Bg: 2 });
    var Ps = function (a) {
            if (/[^01]/.test(a)) throw Error("Input bitstring " + a + " is malformed!");
            this.j = a;
            this.h = 0;
        },
        Rs = function (a) {
            a = Qs(a, 36);
            var b = new os();
            b = Ag(b, 1, Math.floor(a / 10));
            return Ag(b, 2, (a % 10) * 1e8);
        },
        Ss = function (a) {
            return String.fromCharCode(Ms + Qs(a, 6)) + String.fromCharCode(Ms + Qs(a, 6));
        },
        Vs = function (a) {
            var b = Qs(a, 16);
            return !0 === !!Qs(a, 1)
                ? ((a = Ts(a)),
                  a.forEach(function (c) {
                      if (c > b) throw Error("ID " + c + " is past MaxVendorId " + b + "!");
                  }),
                  a)
                : Us(a, b);
        },
        Ws = function (a) {
            for (var b = [], c = Qs(a, 12); c--; ) {
                var d = Qs(a, 6),
                    e = Qs(a, 2),
                    f = Ts(a),
                    g = b,
                    h = g.push,
                    k = new hs();
                d = sg(k, 1, d, 0);
                e = sg(d, 2, e, 0);
                f = rg(e, 3, f);
                h.call(g, f);
            }
            return b;
        },
        Ts = function (a) {
            for (var b = Qs(a, 12), c = []; b--; ) {
                var d = !0 === !!Qs(a, 1),
                    e = Qs(a, 16);
                if (d) for (d = Qs(a, 16); e <= d; e++) c.push(e);
                else c.push(e);
            }
            c.sort(function (f, g) {
                return f - g;
            });
            return c;
        },
        Us = function (a, b, c) {
            for (var d = [], e = 0; e < b; e++)
                if (Qs(a, 1)) {
                    var f = e + 1;
                    if (c && -1 === c.indexOf(f)) throw Error("ID: " + f + " is outside of allowed values!");
                    d.push(f);
                }
            return d;
        },
        Qs = function (a, b) {
            if (a.h + b > a.j.length) throw Error("Requested length " + b + " is past end of string.");
            var c = a.j.substring(a.h, a.h + b);
            a.h += b;
            return parseInt(c, 2);
        };
    Ps.prototype.skip = function (a) {
        this.h += a;
    };
    var Xs = function (a) {
        try {
            var b = Fc(a)
                    .map(function (f) {
                        return f.toString(2).padStart(8, "0");
                    })
                    .join(""),
                c = new Ps(b);
            if (3 !== Qs(c, 3)) return null;
            var d = ls(ks(new js(), Us(c, 24, Ns)), Us(c, 24, Ns)),
                e = Qs(c, 6);
            0 !== e && ns(ms(d, Us(c, e)), Us(c, e));
            return d;
        } catch (f) {
            return null;
        }
    };
    var Ys = function (a) {
        try {
            var b = Fc(a)
                    .map(function (d) {
                        return d.toString(2).padStart(8, "0");
                    })
                    .join(""),
                c = new Ps(b);
            return Ks(
                Js(
                    Is(
                        Hs(
                            Gs(
                                Fs(
                                    Es(Ds(Cs(Bs(As(zs(ys(xs(ws(vs(us(ts(rs(new qs(), Qs(c, 6)), Rs(c)), Rs(c)), Qs(c, 12)), Qs(c, 12)), Qs(c, 6)), Ss(c)), Qs(c, 12)), Qs(c, 6)), !!Qs(c, 1)), !!Qs(c, 1)), Us(c, 12, Os)), Us(c, 24, Ns)),
                                    Us(c, 24, Ns)
                                ),
                                !!Qs(c, 1)
                            ),
                            Ss(c)
                        ),
                        Vs(c)
                    ),
                    Vs(c)
                ),
                Ws(c)
            );
        } catch (d) {
            return null;
        }
    };
    var $s = function (a) {
            if (!a) return null;
            var b = a.split(".");
            if (4 < b.length) return null;
            a = Ys(b[0]);
            if (!a) return null;
            var c = new Ls();
            a = wg(c, 1, a);
            b.shift();
            b = q(b);
            for (c = b.next(); !c.done; c = b.next())
                switch (((c = c.value), Zs(c))) {
                    case 1:
                    case 2:
                        break;
                    case 3:
                        c = Xs(c);
                        if (!c) return null;
                        wg(a, 2, c);
                        break;
                    default:
                        return null;
                }
            return a;
        },
        Zs = function (a) {
            try {
                var b = Fc(a)
                    .map(function (c) {
                        return c.toString(2).padStart(8, "0");
                    })
                    .join("");
                return Qs(new Ps(b), 3);
            } catch (c) {
                return -1;
            }
        };
    var at = function (a, b) {
        var c = {};
        if (Array.isArray(b) && 0 !== b.length) {
            b = q(b);
            for (var d = b.next(); !d.done; d = b.next()) (d = d.value), (c[d] = -1 !== a.indexOf(d));
        } else for (a = q(a), d = a.next(); !d.done; d = a.next()) c[d.value] = !0;
        delete c[0];
        return c;
    };
    var bt = /^((market|itms|intent|itms-appss):\/\/)/i;
    var R = function (a, b) {
        this.j = this.B = this.o = "";
        this.H = null;
        this.J = this.h = "";
        this.A = !1;
        var c;
        a instanceof R
            ? ((this.A = void 0 !== b ? b : a.A), ct(this, a.o), (this.B = a.B), (this.j = a.j), dt(this, a.H), (this.h = a.h), et(this, ft(a.l)), (this.J = a.D()))
            : a && (c = String(a).match(nf))
            ? ((this.A = !!b), ct(this, c[1] || "", !0), (this.B = gt(c[2] || "")), (this.j = gt(c[3] || "", !0)), dt(this, c[4]), (this.h = gt(c[5] || "", !0)), et(this, c[6] || "", !0), (this.J = gt(c[7] || "")))
            : ((this.A = !!b), (this.l = new ht(null, this.A)));
    };
    R.prototype.toString = function () {
        var a = [],
            b = this.o;
        b && a.push(it(b, jt, !0), ":");
        var c = this.j;
        if (c || "file" == b) a.push("//"), (b = this.B) && a.push(it(b, jt, !0), "@"), a.push(encodeURIComponent(String(c)).replace(/%25([0-9a-fA-F]{2})/g, "%$1")), (c = this.H), null != c && a.push(":", String(c));
        if ((c = this.h)) this.j && "/" != c.charAt(0) && a.push("/"), a.push(it(c, "/" == c.charAt(0) ? kt : lt, !0));
        (c = this.l.toString()) && a.push("?", c);
        (c = this.D()) && a.push("#", it(c, mt));
        return a.join("");
    };
    R.prototype.resolve = function (a) {
        var b = this.G(),
            c = !!a.o;
        c ? ct(b, a.o) : (c = !!a.B);
        c ? (b.B = a.B) : (c = !!a.j);
        c ? (b.j = a.j) : (c = null != a.H);
        var d = a.h;
        if (c) dt(b, a.H);
        else if ((c = !!a.h)) {
            if ("/" != d.charAt(0))
                if (this.j && !this.h) d = "/" + d;
                else {
                    var e = b.h.lastIndexOf("/");
                    -1 != e && (d = b.h.substr(0, e + 1) + d);
                }
            e = d;
            if (".." == e || "." == e) d = "";
            else if (-1 != e.indexOf("./") || -1 != e.indexOf("/.")) {
                d = 0 == e.lastIndexOf("/", 0);
                e = e.split("/");
                for (var f = [], g = 0; g < e.length; ) {
                    var h = e[g++];
                    "." == h ? d && g == e.length && f.push("") : ".." == h ? ((1 < f.length || (1 == f.length && "" != f[0])) && f.pop(), d && g == e.length && f.push("")) : (f.push(h), (d = !0));
                }
                d = f.join("/");
            } else d = e;
        }
        c ? (b.h = d) : (c = "" !== a.l.toString());
        c ? et(b, ft(a.l)) : (c = !!a.J);
        c && (b.J = a.D());
        return b;
    };
    R.prototype.G = function () {
        return new R(this);
    };
    var ct = function (a, b, c) {
            a.o = c ? gt(b, !0) : b;
            a.o && (a.o = a.o.replace(/:$/, ""));
        },
        dt = function (a, b) {
            if (b) {
                b = Number(b);
                if (isNaN(b) || 0 > b) throw Error("Bad port number " + b);
                a.H = b;
            } else a.H = null;
        },
        et = function (a, b, c) {
            b instanceof ht ? ((a.l = b), nt(a.l, a.A)) : (c || (b = it(b, ot)), (a.l = new ht(b, a.A)));
        },
        pt = function (a, b, c) {
            a.l.set(b, c);
            return a;
        };
    R.prototype.D = function () {
        return this.J;
    };
    var qt = function (a) {
            return a instanceof R ? a.G() : new R(a, void 0);
        },
        gt = function (a, b) {
            return a ? (b ? decodeURI(a.replace(/%25/g, "%2525")) : decodeURIComponent(a)) : "";
        },
        it = function (a, b, c) {
            return "string" === typeof a ? ((a = encodeURI(a).replace(b, rt)), c && (a = a.replace(/%25([0-9a-fA-F]{2})/g, "%$1")), a) : null;
        },
        rt = function (a) {
            a = a.charCodeAt(0);
            return "%" + ((a >> 4) & 15).toString(16) + (a & 15).toString(16);
        },
        jt = /[#\/\?@]/g,
        lt = /[#\?:]/g,
        kt = /[#\?]/g,
        ot = /[#\?@]/g,
        mt = /#/g,
        ht = function (a, b) {
            this.j = this.h = null;
            this.l = a || null;
            this.o = !!b;
        },
        tt = function (a) {
            a.h ||
                ((a.h = new Map()),
                (a.j = 0),
                a.l &&
                    pf(a.l, function (b, c) {
                        a.add(Ke(b), c);
                    }));
        };
    ht.prototype.add = function (a, b) {
        tt(this);
        this.l = null;
        a = ut(this, a);
        var c = this.h.get(a);
        c || this.h.set(a, (c = []));
        c.push(b);
        this.j += 1;
        return this;
    };
    ht.prototype.remove = function (a) {
        tt(this);
        a = ut(this, a);
        return this.h.has(a) ? ((this.l = null), (this.j -= this.h.get(a).length), this.h.delete(a)) : !1;
    };
    ht.prototype.clear = function () {
        this.h = this.l = null;
        this.j = 0;
    };
    ht.prototype.isEmpty = function () {
        tt(this);
        return 0 == this.j;
    };
    var vt = function (a, b) {
        tt(a);
        b = ut(a, b);
        return a.h.has(b);
    };
    l = ht.prototype;
    l.forEach = function (a, b) {
        tt(this);
        this.h.forEach(function (c, d) {
            c.forEach(function (e) {
                a.call(b, e, d, this);
            }, this);
        }, this);
    };
    l.Tb = function () {
        tt(this);
        for (var a = Array.from(this.h.values()), b = Array.from(this.h.keys()), c = [], d = 0; d < b.length; d++) for (var e = a[d], f = 0; f < e.length; f++) c.push(b[d]);
        return c;
    };
    l.ob = function (a) {
        tt(this);
        var b = [];
        if ("string" === typeof a) vt(this, a) && (b = b.concat(this.h.get(ut(this, a))));
        else {
            a = Array.from(this.h.values());
            for (var c = 0; c < a.length; c++) b = b.concat(a[c]);
        }
        return b;
    };
    l.set = function (a, b) {
        tt(this);
        this.l = null;
        a = ut(this, a);
        vt(this, a) && (this.j -= this.h.get(a).length);
        this.h.set(a, [b]);
        this.j += 1;
        return this;
    };
    l.get = function (a, b) {
        if (!a) return b;
        a = this.ob(a);
        return 0 < a.length ? String(a[0]) : b;
    };
    l.toString = function () {
        if (this.l) return this.l;
        if (!this.h) return "";
        for (var a = [], b = Array.from(this.h.keys()), c = 0; c < b.length; c++) {
            var d = b[c],
                e = encodeURIComponent(String(d));
            d = this.ob(d);
            for (var f = 0; f < d.length; f++) {
                var g = e;
                "" !== d[f] && (g += "=" + encodeURIComponent(String(d[f])));
                a.push(g);
            }
        }
        return (this.l = a.join("&"));
    };
    var ft = function (a) {
            var b = new ht();
            b.l = a.l;
            a.h && ((b.h = new Map(a.h)), (b.j = a.j));
            return b;
        },
        ut = function (a, b) {
            b = String(b);
            a.o && (b = b.toLowerCase());
            return b;
        },
        nt = function (a, b) {
            b &&
                !a.o &&
                (tt(a),
                (a.l = null),
                a.h.forEach(function (c, d) {
                    var e = d.toLowerCase();
                    d != e && (this.remove(d), this.remove(e), 0 < c.length && ((this.l = null), this.h.set(ut(this, e), Tb(c)), (this.j += c.length)));
                }, a));
            a.o = b;
        };
    var wt = "://secure-...imrworldwide.com/ ://cdn.imrworldwide.com/ ://aksecure.imrworldwide.com/ ://[^.]*.moatads.com ://youtube[0-9]+.moatpixel.com ://pm.adsafeprotected.com/youtube ://pm.test-adsafeprotected.com/youtube ://e[0-9]+.yt.srs.doubleverify.com www.google.com/pagead/xsul www.youtube.com/pagead/slav".split(
            " "
        ),
        xt = /\bocr\b/,
        yt = 0,
        zt = {};
    function At(a) {
        if (lb(Ne(a))) return !1;
        if (0 <= a.indexOf("://pagead2.googlesyndication.com/pagead/gen_204?id=yt3p&sr=1&")) return !0;
        try {
            var b = new R(a);
        } catch (c) {
            return (
                null !=
                Lb(wt, function (d) {
                    return 0 < a.search(d);
                })
            );
        }
        return b.D().match(xt)
            ? !0
            : null !=
                  Lb(wt, function (c) {
                      return null != a.match(c);
                  });
    }
    function Bt(a, b) {
        if (a && ((a = Ct(a)), !lb(a))) {
            var c = 'javascript:"<body><img src=\\""+' + a + '+"\\"></body>"';
            b
                ? Dt(function (d) {
                      Et(d ? c : 'javascript:"<body><object data=\\""+' + a + '+"\\" type=\\"text/html\\" width=1 height=1 style=\\"visibility:hidden;\\"></body>"');
                  })
                : Et(c);
        }
    }
    function Et(a) {
        var b = ef("IFRAME", { src: a, style: "display:none" });
        a = Ue(b).body;
        var c = Hk(function () {
            Yj(d);
            ff(b);
        }, 15e3);
        var d = Pj(b, ["load", "error"], function () {
            Hk(function () {
                t.clearTimeout(c);
                ff(b);
            }, 5e3);
        });
        a.appendChild(b);
    }
    function Dt(a) {
        var b = zt.imageLoadingEnabled;
        if (null != b) a(b);
        else {
            var c = !1;
            Ft(function (d, e) {
                delete zt[e];
                c || ((c = !0), null == zt.imageLoadingEnabled && (zt.imageLoadingEnabled = d), a(d));
            });
        }
    }
    function Ft(a) {
        var b = new Image(),
            c = "" + yt++;
        zt[c] = b;
        b.onload = function () {
            clearTimeout(d);
            a(!0, c);
        };
        var d = setTimeout(function () {
            a(!1, c);
        }, 300);
        b.src = "data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==";
    }
    function Gt(a) {
        if (a) {
            var b = cf(document, "OBJECT");
            b.data = a;
            b.width = "1";
            b.height = "1";
            b.style.visibility = "hidden";
            var c = "" + yt++;
            zt[c] = b;
            b.onload = b.onerror = function () {
                delete zt[c];
            };
            document.body.appendChild(b);
        }
    }
    function Ht(a) {
        if (a) {
            var b = new Image(),
                c = "" + yt++;
            zt[c] = b;
            b.onload = b.onerror = function () {
                delete zt[c];
            };
            b.src = a;
        }
    }
    function It(a, b) {
        a &&
            (b
                ? Dt(function (c) {
                      c ? Ht(a) : Gt(a);
                  })
                : Ht(a));
    }
    function Ct(a) {
        a instanceof Vd || ((a = "object" == typeof a && a.Ta ? a.Ga() : String(a)), Yd.test(a) ? (a = new Vd(a, Ud)) : ((a = String(a)), (a = a.replace(/(%0A|%0D)/g, "")), (a = a.match(Xd) ? new Vd(a, Ud) : null)));
        var b = Wd(a || Zd);
        if ("about:invalid#zClosurez" === b) return "";
        if (b instanceof de) a = b;
        else {
            var c = "object" == typeof b;
            a = null;
            c && b.Hc && (a = b.Dc());
            b = c && b.Ta ? b.Ga() : String(b);
            ub.test(b) &&
                (-1 != b.indexOf("&") && (b = b.replace(ob, "&amp;")),
                -1 != b.indexOf("<") && (b = b.replace(pb, "&lt;")),
                -1 != b.indexOf(">") && (b = b.replace(qb, "&gt;")),
                -1 != b.indexOf('"') && (b = b.replace(rb, "&quot;")),
                -1 != b.indexOf("'") && (b = b.replace(sb, "&#39;")),
                -1 != b.indexOf("\x00") && (b = b.replace(tb, "&#0;")));
            a = fe(b, a);
        }
        a = ee(a).toString();
        return encodeURIComponent(String(new ei(void 0).aa(a)));
    }
    var Jt = "ad_type vpos mridx pos vad_type videoad_start_delay".split(" ");
    var Kt = function (a) {
        var b = a.Oa,
            c = a.height,
            d = a.width,
            e = void 0 === a.xa ? !1 : a.xa;
        this.Xa = a.Xa;
        this.Oa = b;
        this.height = c;
        this.width = d;
        this.xa = e;
    };
    Kt.prototype.getHeight = function () {
        return this.height;
    };
    Kt.prototype.getWidth = function () {
        return this.width;
    };
    var Lt = function (a) {
        var b = a.Bf,
            c = a.Ae,
            d = a.Af,
            e = a.ze;
        Kt.call(this, { Xa: a.Xa, Oa: a.Oa, height: a.height, width: a.width, xa: void 0 === a.xa ? !1 : a.xa });
        this.o = b;
        this.j = c;
        this.l = d;
        this.h = e;
    };
    r(Lt, Kt);
    var Mt = function (a) {
        var b = a.df;
        Kt.call(this, { Xa: a.Xa, Oa: a.Oa, height: a.height, width: a.width, xa: void 0 === a.xa ? !1 : a.xa });
        this.h = b;
    };
    r(Mt, Kt);
    Mt.prototype.getMediaUrl = function () {
        return this.h;
    }; /*

Math.uuid.js (v1.4)
http://www.broofa.com
mailto:robert@broofa.com
Copyright (c) 2010 Robert Kieffer
Dual licensed under the MIT and GPL licenses.
*/
    var Nt = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz".split(""),
        Ot = function () {
            for (var a = Array(36), b = 0, c, d = 0; 36 > d; d++)
                8 == d || 13 == d || 18 == d || 23 == d ? (a[d] = "-") : 14 == d ? (a[d] = "4") : (2 >= b && (b = (33554432 + 16777216 * Math.random()) | 0), (c = b & 15), (b >>= 4), (a[d] = Nt[19 == d ? (c & 3) | 8 : c]));
            return a.join("");
        };
    function Pt(a) {
        return new (Function.prototype.bind.apply(a, [null].concat(ha(Ha.apply(1, arguments)))))();
    }
    var S = {},
        Qt =
            ((S[18] = -1),
            (S[22] = -1),
            (S[43] = 350),
            (S[44] = 350),
            (S[45] = 350),
            (S[59] = -1),
            (S[133] = 350),
            (S[134] = 350),
            (S[135] = 350),
            (S[136] = 350),
            (S[139] = 50),
            (S[140] = 50),
            (S[141] = 50),
            (S[160] = 350),
            (S[242] = 150),
            (S[243] = 150),
            (S[244] = 150),
            (S[245] = 150),
            (S[249] = 50),
            (S[250] = 50),
            (S[251] = 50),
            (S[278] = 150),
            (S[342] = -1),
            (S[343] = -1),
            (S[344] = -1),
            (S[345] = -1),
            (S[346] = -1),
            (S[347] = -1),
            (S[396] = -1),
            (S[398] = -1),
            S),
        T = {},
        Rt =
            ((T[18] = !1),
            (T[22] = !1),
            (T[43] = !0),
            (T[44] = !0),
            (T[45] = !0),
            (T[59] = !1),
            (T[133] = !0),
            (T[134] = !0),
            (T[135] = !0),
            (T[136] = !0),
            (T[139] = !0),
            (T[140] = !0),
            (T[141] = !0),
            (T[160] = !0),
            (T[242] = !0),
            (T[243] = !0),
            (T[244] = !0),
            (T[245] = !0),
            (T[249] = !0),
            (T[250] = !0),
            (T[251] = !0),
            (T[278] = !0),
            (T[342] = !1),
            (T[343] = !1),
            (T[344] = !1),
            (T[345] = !1),
            (T[346] = !1),
            (T[347] = !1),
            (T[396] = !0),
            (T[398] = !0),
            T),
        U = {},
        St =
            ((U[18] = "video/mp4"),
            (U[22] = "video/mp4"),
            (U[43] = "video/webm"),
            (U[44] = "video/webm"),
            (U[45] = "video/webm"),
            (U[59] = "video/mp4"),
            (U[133] = "video/mp4"),
            (U[134] = "video/mp4"),
            (U[135] = "video/mp4"),
            (U[136] = "video/mp4"),
            (U[139] = "audio/mp4"),
            (U[140] = "audio/mp4"),
            (U[141] = "audio/mp4"),
            (U[160] = "video/mp4"),
            (U[242] = "video/webm"),
            (U[243] = "video/webm"),
            (U[244] = "video/webm"),
            (U[245] = "video/webm"),
            (U[249] = "audio/webm"),
            (U[250] = "audio/webm"),
            (U[251] = "audio/webm"),
            (U[278] = "video/webm"),
            (U[342] = "video/mp4"),
            (U[343] = "video/mp4"),
            (U[344] = "video/mp4"),
            (U[345] = "video/mp4"),
            (U[346] = "video/mp4"),
            (U[347] = "video/mp4"),
            (U[396] = "video/mp4"),
            (U[398] = "video/mp4"),
            U),
        V = {},
        Tt =
            ((V[18] = "avc1.42001E, mp4a.40.2"),
            (V[22] = "avc1.64001F, mp4a.40.2"),
            (V[43] = "vp8, vorbis"),
            (V[44] = "vp8, vorbis"),
            (V[45] = "vp8, vorbis"),
            (V[59] = "avc1.4D001F, mp4a.40.2"),
            (V[133] = "avc1.4D401E"),
            (V[134] = "avc1.4D401E"),
            (V[135] = "avc1.4D401E"),
            (V[136] = "avc1.4D401E"),
            (V[139] = "mp4a.40.2"),
            (V[140] = "mp4a.40.2"),
            (V[141] = "mp4a.40.2"),
            (V[160] = "avc1.4D401E"),
            (V[242] = "vp9"),
            (V[243] = "vp9"),
            (V[244] = "vp9"),
            (V[245] = "vp9"),
            (V[249] = "opus"),
            (V[250] = "opus"),
            (V[251] = "opus"),
            (V[278] = "vp9"),
            (V[342] = "avc1.42E01E, mp4a.40.2"),
            (V[343] = "avc1.42E01E, mp4a.40.2"),
            (V[344] = "avc1.42E01E, mp4a.40.2"),
            (V[345] = "avc1.42E01E, mp4a.40.2"),
            (V[346] = "avc1.42E01E, mp4a.40.2"),
            (V[347] = "avc1.4D001F, mp4a.40.2"),
            (V[396] = "av01.0.05M.08"),
            (V[398] = "av01.0.05M.08"),
            V);
    var Ut = function () {};
    Ut.prototype.h = null;
    var Wt = function (a) {
        var b;
        (b = a.h) || ((b = {}), Vt(a) && ((b[0] = !0), (b[1] = !0)), (b = a.h = b));
        return b;
    };
    var Xt,
        Yt = function () {};
    $a(Yt, Ut);
    var Zt = function (a) {
            return (a = Vt(a)) ? new ActiveXObject(a) : new XMLHttpRequest();
        },
        Vt = function (a) {
            if (!a.j && "undefined" == typeof XMLHttpRequest && "undefined" != typeof ActiveXObject) {
                for (var b = ["MSXML2.XMLHTTP.6.0", "MSXML2.XMLHTTP.3.0", "MSXML2.XMLHTTP", "Microsoft.XMLHTTP"], c = 0; c < b.length; c++) {
                    var d = b[c];
                    try {
                        return new ActiveXObject(d), (a.j = d);
                    } catch (e) {}
                }
                throw Error("Could not create ActiveXObject. ActiveX might be disabled, or MSXML might not be installed");
            }
            return a.j;
        };
    Xt = new Yt();
    var $t = function (a) {
        M.call(this);
        this.headers = new Map();
        this.I = a || null;
        this.j = !1;
        this.G = this.h = null;
        this.M = "";
        this.A = 0;
        this.l = this.L = this.B = this.K = !1;
        this.D = 0;
        this.C = null;
        this.W = "";
        this.T = this.U = !1;
        this.R = null;
    };
    $a($t, M);
    var au = /^https?$/i,
        bu = ["POST", "PUT"];
    $t.prototype.setTrustToken = function (a) {
        this.R = a;
    };
    var fu = function (a, b, c, d) {
            if (a.h) throw Error("[goog.net.XhrIo] Object is active with another request=" + a.M + "; newUri=" + b);
            c = c ? c.toUpperCase() : "GET";
            a.M = b;
            a.A = 0;
            a.K = !1;
            a.j = !0;
            a.h = a.I ? Zt(a.I) : Zt(Xt);
            a.G = a.I ? Wt(a.I) : Wt(Xt);
            a.h.onreadystatechange = Xa(a.V, a);
            try {
                (a.L = !0), a.h.open(c, String(b), !0), (a.L = !1);
            } catch (g) {
                cu(a);
                return;
            }
            b = d || "";
            d = new Map(a.headers);
            var e = Array.from(d.keys()).find(function (g) {
                    return "content-type" == g.toLowerCase();
                }),
                f = t.FormData && b instanceof t.FormData;
            !Ob(bu, c) || e || f || d.set("Content-Type", "application/x-www-form-urlencoded;charset=utf-8");
            c = q(d);
            for (d = c.next(); !d.done; d = c.next()) (e = q(d.value)), (d = e.next().value), (e = e.next().value), a.h.setRequestHeader(d, e);
            a.W && (a.h.responseType = a.W);
            "withCredentials" in a.h && a.h.withCredentials !== a.U && (a.h.withCredentials = a.U);
            if ("setTrustToken" in a.h && a.R)
                try {
                    a.h.setTrustToken(a.R);
                } catch (g) {}
            try {
                du(a), 0 < a.D && ((a.T = eu(a.h)), a.T ? ((a.h.timeout = a.D), (a.h.ontimeout = Xa(a.Y, a))) : (a.C = Hk(a.Y, a.D, a))), (a.B = !0), a.h.send(b), (a.B = !1);
            } catch (g) {
                cu(a);
            }
        },
        eu = function (a) {
            return cc && tc(9) && "number" === typeof a.timeout && void 0 !== a.ontimeout;
        };
    $t.prototype.Y = function () {
        "undefined" != typeof La && this.h && ((this.A = 8), this.dispatchEvent("timeout"), this.abort(8));
    };
    var cu = function (a) {
            a.j = !1;
            a.h && ((a.l = !0), a.h.abort(), (a.l = !1));
            a.A = 5;
            gu(a);
            hu(a);
        },
        gu = function (a) {
            a.K || ((a.K = !0), a.dispatchEvent("complete"), a.dispatchEvent("error"));
        };
    $t.prototype.abort = function (a) {
        this.h && this.j && ((this.j = !1), (this.l = !0), this.h.abort(), (this.l = !1), (this.A = a || 7), this.dispatchEvent("complete"), this.dispatchEvent("abort"), hu(this));
    };
    $t.prototype.N = function () {
        this.h && (this.j && ((this.j = !1), (this.l = !0), this.h.abort(), (this.l = !1)), hu(this, !0));
        $t.ya.N.call(this);
    };
    $t.prototype.V = function () {
        this.Ia() || (this.L || this.B || this.l ? iu(this) : this.Z());
    };
    $t.prototype.Z = function () {
        iu(this);
    };
    var iu = function (a) {
            if (a.j && "undefined" != typeof La && (!a.G[1] || 4 != (a.h ? a.h.readyState : 0) || 2 != ju(a)))
                if (a.B && 4 == (a.h ? a.h.readyState : 0)) Hk(a.V, 0, a);
                else if ((a.dispatchEvent("readystatechange"), a.isComplete())) {
                    a.j = !1;
                    try {
                        var b = ju(a);
                        a: switch (b) {
                            case 200:
                            case 201:
                            case 202:
                            case 204:
                            case 206:
                            case 304:
                            case 1223:
                                var c = !0;
                                break a;
                            default:
                                c = !1;
                        }
                        var d;
                        if (!(d = c)) {
                            var e;
                            if ((e = 0 === b)) {
                                var f = String(a.M).match(nf)[1] || null;
                                if (!f && t.self && t.self.location) {
                                    var g = t.self.location.protocol;
                                    f = g.substr(0, g.length - 1);
                                }
                                e = !au.test(f ? f.toLowerCase() : "");
                            }
                            d = e;
                        }
                        d ? (a.dispatchEvent("complete"), a.dispatchEvent("success")) : ((a.A = 6), gu(a));
                    } finally {
                        hu(a);
                    }
                }
        },
        hu = function (a, b) {
            if (a.h) {
                du(a);
                var c = a.h,
                    d = a.G[0] ? Na : null;
                a.h = null;
                a.G = null;
                b || a.dispatchEvent("ready");
                try {
                    c.onreadystatechange = d;
                } catch (e) {}
            }
        },
        du = function (a) {
            a.h && a.T && (a.h.ontimeout = null);
            a.C && (t.clearTimeout(a.C), (a.C = null));
        };
    $t.prototype.isActive = function () {
        return !!this.h;
    };
    $t.prototype.isComplete = function () {
        return 4 == (this.h ? this.h.readyState : 0);
    };
    var ju = function (a) {
            try {
                return 2 < (a.h ? a.h.readyState : 0) ? a.h.status : -1;
            } catch (b) {
                return -1;
            }
        },
        ku = function (a) {
            if (a.h) {
                a: {
                    a = a.h.responseText;
                    if (t.JSON)
                        try {
                            var b = t.JSON.parse(a);
                            break a;
                        } catch (c) {}
                    b = di(a);
                }
                return b;
            }
        };
    var lu = RegExp("/itag/(\\d+)/");
    function mu(a) {
        var b = parseInt(rf(a, "itag"), 10);
        return b ? b : (a = a.match(lu)) && 2 == a.length ? parseInt(a[1], 10) : null;
    }
    function nu(a) {
        var b = St[a];
        a = Tt[a];
        b ? ((b = Ne(b).toLowerCase()), (b = a ? b + '; codecs="' + Ne(a) + '"' : b)) : (b = "");
        return b;
    }
    function ou(a, b) {
        if ("function" === typeof CustomEvent) return new CustomEvent(a, { detail: b });
        var c = document.createEvent("CustomEvent");
        c.initCustomEvent(a, !1, !0, b);
        return c;
    }
    var pu = -1;
    var qu = function () {
        this.h = Date.now();
    };
    qu.prototype.reset = function () {
        this.h = Date.now();
    };
    var ru = function (a) {
        a = a.h + 5e3 - Date.now();
        return 0 < a ? a : 0;
    };
    var tu = "ad.doubleclick.net bid.g.doubleclick.net ggpht.com google.co.uk google.com googleads.g.doubleclick.net googleads4.g.doubleclick.net googleadservices.com googlesyndication.com googleusercontent.com gstatic.com gvt1.com prod.google.com pubads.g.doubleclick.net s0.2mdn.net static.doubleclick.net surveys.g.doubleclick.net youtube.com ytimg.com".split(
            " "
        ),
        uu = ["c.googlesyndication.com"];
    function vu(a, b) {
        b = void 0 === b ? window.location.protocol : b;
        var c = !1;
        wu(a, uu) ? (c = !1) : b.includes("https") && wu(a, tu) && (c = !0);
        if (c) {
            b = new R(a);
            if ("https" == b.o) return a;
            I(J(), "htp", "1");
            ct(b, "https");
            return b.toString();
        }
        return a;
    }
    function wu(a, b) {
        return new RegExp("^https?://([a-z0-9-]{1,63}\\.)*(" + b.join("|").replace(/\./g, "\\.") + ")(:[0-9]+)?([/?#]|$)", "i").test(a);
    }
    var xu = /OS (\S+) like/,
        yu = /Android ([\d\.]+)/;
    function zu(a, b) {
        a = (a = a.exec(yb())) ? a[1] : "";
        a = a.replace(/_/g, ".");
        return 0 <= xb(a, b);
    }
    var Au = function () {
            return gc && "ontouchstart" in document.documentElement;
        },
        Bu = function (a) {
            return mc && zu(xu, a);
        },
        Cu = function (a) {
            return (a = void 0 === a ? null : a) && "function" === typeof a.getAttribute ? (a.getAttribute("playsinline") ? !0 : !1) : !1;
        };
    var Du = function (a, b) {
        var c = Error.call(this, a);
        this.message = c.message;
        "stack" in c && (this.stack = c.stack);
        this.errorCode = a;
        this.httpStatus = b;
    };
    r(Du, Error);
    var Fu = function (a) {
            Eu();
            return fe(a, null);
        },
        Eu = Na;
    var Gu = function () {
            if (!cc) return !1;
            try {
                return new ActiveXObject("MSXML2.DOMDocument"), !0;
            } catch (a) {
                return !1;
            }
        },
        Hu = cc && Gu();
    var Iu = function (a) {
        L.call(this);
        this.o = a;
        this.j = {};
    };
    $a(Iu, L);
    var Ju = [];
    Iu.prototype.P = function (a, b, c, d) {
        return Ku(this, a, b, c, d);
    };
    var Ku = function (a, b, c, d, e, f) {
        Array.isArray(c) || (c && (Ju[0] = c.toString()), (c = Ju));
        for (var g = 0; g < c.length; g++) {
            var h = Qj(b, c[g], d || a.handleEvent, e || !1, f || a.o || a);
            if (!h) break;
            a.j[h.key] = h;
        }
        return a;
    };
    Iu.prototype.Gb = function (a, b, c, d) {
        return Lu(this, a, b, c, d);
    };
    var Lu = function (a, b, c, d, e, f) {
        if (Array.isArray(c)) for (var g = 0; g < c.length; g++) Lu(a, b, c[g], d, e, f);
        else {
            b = Pj(b, c, d || a.handleEvent, e, f || a.o || a);
            if (!b) return a;
            a.j[b.key] = b;
        }
        return a;
    };
    Iu.prototype.Wa = function (a, b, c, d, e) {
        if (Array.isArray(b)) for (var f = 0; f < b.length; f++) this.Wa(a, b[f], c, d, e);
        else
            (c = c || this.handleEvent),
                (d = Qa(d) ? !!d.capture : !!d),
                (e = e || this.o || this),
                (c = Rj(c)),
                (d = !!d),
                (b = Fj(a) ? a.Cb(b, c, d, e) : a ? ((a = Tj(a)) ? a.Cb(b, c, d, e) : null) : null),
                b && (Yj(b), delete this.j[b.key]);
    };
    var Mu = function (a) {
        wd(
            a.j,
            function (b, c) {
                this.j.hasOwnProperty(c) && Yj(b);
            },
            a
        );
        a.j = {};
    };
    Iu.prototype.N = function () {
        Iu.ya.N.call(this);
        Mu(this);
    };
    Iu.prototype.handleEvent = function () {
        throw Error("EventHandler.handleEvent not implemented");
    };
    var Nu = function () {};
    Nu.prototype.get = function (a) {
        return Ou({ url: a.url, timeout: a.timeout, withCredentials: void 0 === a.withCredentials ? !0 : a.withCredentials, method: "GET", Na: void 0 === a.Na ? void 0 : a.Na });
    };
    Nu.prototype.post = function (a) {
        var b = a.timeout,
            c = void 0 === a.withCredentials ? !0 : a.withCredentials,
            d = void 0 === a.headers ? {} : a.headers,
            e = void 0 === a.content ? void 0 : a.content;
        a = new R(a.url);
        e || ((e = a.l.toString()), et(a, ""));
        return Ou({ url: a.toString(), timeout: b, withCredentials: c, method: "POST", content: e, headers: d });
    };
    var Ou = function (a) {
            var b = a.url,
                c = a.timeout,
                d = a.withCredentials,
                e = a.method,
                f = void 0 === a.content ? void 0 : a.content,
                g = void 0 === a.Na ? void 0 : a.Na,
                h = void 0 === a.headers ? {} : a.headers;
            return Pu({ url: b, timeout: c, withCredentials: d, method: e, content: f, Na: g, headers: h }).then(
                function (k) {
                    return Promise.resolve(k);
                },
                function (k) {
                    return k instanceof Error && 6 == k.message && d ? Pu({ url: b, timeout: c, withCredentials: !d, method: e, content: f, Na: g, headers: h }) : Promise.reject(k);
                }
            );
        },
        Pu = function (a) {
            var b = a.url,
                c = a.timeout,
                d = a.withCredentials,
                e = a.method,
                f = void 0 === a.content ? void 0 : a.content,
                g = void 0 === a.Na ? void 0 : a.Na;
            a = void 0 === a.headers ? {} : a.headers;
            var h = new $t();
            h.U = d;
            h.D = Math.max(0, ru(c));
            h.setTrustToken && g && h.setTrustToken(g);
            for (var k in a) h.headers.set(k, a[k]);
            var n = new Iu();
            return new Promise(function (m, x) {
                n.Gb(h, "success", function () {
                    a: {
                        if (On())
                            try {
                                ku(h);
                                var v = "application/json";
                                break a;
                            } catch (O) {
                                v = "application/xml";
                                break a;
                            }
                        h.h && h.isComplete() ? ((v = h.h.getResponseHeader("Content-Type")), (v = null === v ? void 0 : v)) : (v = void 0);
                        v = v || "";
                    }
                    if (-1 != v.indexOf("application/json")) m(ku(h) || {});
                    else {
                        try {
                            var A = h.h ? h.h.responseXML : null;
                        } catch (O) {
                            A = null;
                        }
                        if (null == A) {
                            try {
                                var C = h.h ? h.h.responseText : "";
                            } catch (O) {
                                C = "";
                            }
                            A = C;
                            if ("undefined" != typeof DOMParser) (C = new DOMParser()), (A = Fu(A)), (A = C.parseFromString(ee(A), "application/xml"));
                            else if (Hu) {
                                C = new ActiveXObject("MSXML2.DOMDocument");
                                C.resolveExternals = !1;
                                C.validateOnParse = !1;
                                try {
                                    C.setProperty("ProhibitDTD", !0), C.setProperty("MaxXMLSize", 2048), C.setProperty("MaxElementDepth", 256);
                                } catch (O) {}
                                C.loadXML(A);
                                A = C;
                            } else throw Error("Your browser does not support loading xml documents");
                        }
                        m(A);
                    }
                    n.dispose();
                    h.dispose();
                });
                n.Gb(h, ["error", "timeout"], function () {
                    x(new Du(h.A, ju(h)));
                    n.dispose();
                    h.dispose();
                });
                fu(h, vu(b), e, f);
            });
        };
    function Qu(a, b) {
        return lb(b) ? !1 : new RegExp(a).test(b);
    }
    function Ru(a) {
        var b = {};
        a.split(",").forEach(function (c) {
            var d = c.split("=");
            2 == d.length && ((c = nb(d[0])), (d = nb(d[1])), 0 < c.length && (b[c] = d));
        });
        return b;
    }
    function Su(a) {
        var b = "af am ar_eg ar_sa ar_xb ar be bg bn ca cs da de_at de_cn de el en_au en_ca en_gb en_ie en_in en_sg en_xa en_xc en_za en es_419 es_ar es_bo es_cl es_co es_cr es_do es_ec es_gt es_hn es_mx es_ni es_pa es_pe es_pr es_py es_sv es_us es_uy es_ve es et eu fa fi fil fr_ca fr_ch fr gl gsw gu he hi hr hu id in is it iw ja kn ko ln lo lt lv ml mo mr ms nb ne nl no pl pt_br pt_pt pt ro ru sk sl sr_latn sr sv sw ta te th tl tr uk ur vi zh_cn zh_hk zh_tw zh zu".split(
            " "
        );
        if (!a) return null;
        a = a.toLowerCase().replace("-", "_");
        if (b.includes(a)) return a;
        a = (a = a.match(/^\w{2,3}([-_]|$)/)) ? a[0].replace(/[_-]/g, "") : "";
        return b.includes(a) ? a : null;
    }
    var Uu = function (a) {
        R.call(this, a);
        this.C = new Map();
        a = this.h;
        var b = a.indexOf(";"),
            c = null;
        0 <= b ? ((this.h = a.substring(0, b)), (c = a.substring(b + 1))) : (this.h = a);
        Tu(this, c);
    };
    r(Uu, R);
    Uu.prototype.toString = function () {
        return Vu(this, R.prototype.toString.call(this));
    };
    Uu.prototype.D = function () {
        return "";
    };
    var Tu = function (a, b) {
            lb(Ne(b)) ||
                b.split(";").forEach(function (c) {
                    var d = c.indexOf("=");
                    if (!(0 >= d)) {
                        var e = Ke(c.substring(0, d));
                        c = Ke(c.substring(d + 1));
                        d = a.C.get(e);
                        null != d ? d.includes(c) || d.push(c) : (d = [Ne(c)]);
                        a.C.set(e, d);
                    }
                }, a);
        },
        Wu = function (a) {
            if (lb(Ne("ord"))) return null;
            a = a.C.get("ord");
            return null != a ? a : null;
        },
        Xu = function (a, b) {
            lb(Ne("ord")) || ((b = b.map(Ne)), a.C.set("ord", b));
        },
        Vu = function (a, b) {
            b = [Ne(b)];
            b.push.apply(b, ha(Yu(a)));
            return b.join(";");
        },
        Yu = function (a) {
            var b = Wu(a);
            null == b ? (b = [Ne(Date.now())]) : lb(Ne("ord")) || a.C.delete("ord");
            var c = [];
            a.C.forEach(function (d, e) {
                d.forEach(function (f) {
                    c.push(e + "=" + f);
                });
            });
            c.push("ord=" + b[0]);
            Xu(a, b);
            return c;
        };
    Uu.prototype.G = function () {
        return new Uu(this.toString());
    };
    var Zu,
        $u,
        av,
        bv = function () {
            return t.navigator ? t.navigator.userAgent : "";
        },
        cv = -1 != bv().indexOf("(iPad") || -1 != bv().indexOf("(Macintosh") || -1 != bv().indexOf("(iPod") || -1 != bv().indexOf("(iPhone");
    function dv(a, b) {
        b = null != b ? b : "";
        cc && (b = "");
        if (!lb(Ne(a))) {
            var c = a instanceof Vd || !bt.test(a) ? a : new Vd(a, Ud);
            if (c instanceof Vd) a = c;
            else {
                var d = void 0 === d ? ph : d;
                a: {
                    d = void 0 === d ? ph : d;
                    for (c = 0; c < d.length; ++c) {
                        var e = d[c];
                        if (e instanceof nh && e.isValid(a)) {
                            a = new pe(a, ge);
                            break a;
                        }
                    }
                    a = void 0;
                }
                a = a || qe;
            }
            window.open(te(a), "_blank", b);
        }
    }
    var ev = function (a) {
        M.call(this);
        this.h = a;
        this.A = this.B = !1;
        this.C = this.D = 0;
        this.j = new Gk(1e3);
        zj(this, this.j);
        Qj(this.j, "tick", this.G, !1, this);
        Qj(this.h, "pause", this.l, !1, this);
        Qj(this.h, "playing", this.l, !1, this);
        Qj(this.h, "ended", this.l, !1, this);
        Qj(this.h, "timeupdate", this.l, !1, this);
    };
    r(ev, M);
    ev.prototype.l = function (a) {
        switch (a.type) {
            case "playing":
                fv(this);
                break;
            case "pause":
            case "ended":
                this.j.enabled && this.j.stop();
                break;
            case "timeupdate":
                !this.B && 0 < this.h.currentTime && ((this.B = !0), fv(this));
        }
    };
    var fv = function (a) {
        !a.j.enabled && a.B && ((a.D = 1e3 * a.h.currentTime), (a.C = Date.now()), (a.A = !1), a.j.start());
    };
    ev.prototype.G = function () {
        var a = Date.now(),
            b = 1e3 * this.h.currentTime;
        b - this.D < 0.5 * (a - this.C) ? this.A || ((this.A = !0), this.dispatchEvent("playbackStalled")) : (this.A = !1);
        this.D = b;
        this.C = a;
    };
    var gv = new Map(),
        hv = function () {
            this.j = this.h = null;
        };
    function iv(a, b, c, d) {
        var e = dl(a);
        b.width <= e.width && b.height <= e.height
            ? (jv(d), c(e))
            : ((e = setTimeout(function () {
                  return iv(a, b, c, d);
              }, 200)),
              (d.j = e));
    }
    function kv(a, b) {
        b = void 0 === b ? new y(1, 1) : b;
        var c = new hv(),
            d = new Promise(function (e) {
                var f = dl(a);
                if (b.width <= f.width && b.height <= f.height) return e(f);
                "ResizeObserver" in window
                    ? ((f = new ResizeObserver(function (g) {
                          window.requestAnimationFrame(function () {
                              for (var h = new y(0, 0), k = q(g), n = k.next(); !n.done; n = k.next())
                                  if (
                                      ((n = n.value),
                                      n.contentBoxSize
                                          ? ((n = Array.isArray(n.contentBoxSize) ? n.contentBoxSize[0] : n.contentBoxSize), (h.width = Math.floor(n.inlineSize)), (h.height = Math.floor(n.blockSize)))
                                          : ((h.width = Math.floor(n.contentRect.width)), (h.height = Math.floor(n.contentRect.height))),
                                      b.width <= h.width && b.height <= h.height)
                                  )
                                      return jv(c), e(h);
                          });
                      })),
                      (c.h = f),
                      f.observe(a))
                    : iv(a, b, e, c);
            });
        gv.set(d, c);
        return d;
    }
    function jv(a) {
        a.j && window.clearTimeout(a.j);
        a.h && (a.h.disconnect(), (a.h = null));
    }
    var lv = {
        AUTOPLAY_DISALLOWED: "autoplayDisallowed",
        Lf: "beginFullscreen",
        Mf: "canPlay",
        Nf: "canPlayThrough",
        CLICK: "click",
        DURATION_CHANGE: "durationChange",
        $f: "end",
        ag: "endFullscreen",
        bg: "error",
        fg: "focusSkipButton",
        ie: "loadStart",
        LOADED: "loaded",
        Jg: "mediaLoadTimeout",
        Kg: "mediaPlaybackTimeout",
        vc: "pause",
        Vg: "play",
        Xg: "playing",
        gh: "seeked",
        hh: "seeking",
        ih: "skip",
        se: "skipShown",
        kh: "stalled",
        wc: "start",
        rh: "timeUpdate",
        ph: "timedMetadata",
        xe: "volumeChange",
        Dh: "waiting",
        gg: "fullyLoaded",
    };
    var nv = function (a) {
            this.h = a;
            this.j = mv(a);
        },
        mv = function (a) {
            return new Map(
                a.h.split("/").reduce(function (b, c, d) {
                    d % 2 ? b[b.length - 1].push(c) : b.push([c]);
                    return b;
                }, [])
            );
        };
    nv.prototype.getId = function () {
        return ov(this, "id");
    };
    var ov = function (a, b) {
        var c = a.h.l.get(b);
        return c ? c : (a = a.j.get(b)) ? a : null;
    };
    var pv = function () {};
    var rv = ["doubleclick.net"];
    function tv() {
        if (Db() || w("iPad") || w("iPod")) return !1;
        if (w("Android")) {
            if (void 0 === av) {
                a: {
                    if (void 0 === Zu) {
                        if (cv) {
                            var a = -1 != bv().indexOf("Safari");
                            var b = new R(window.location.href).l.ob("js");
                            b: {
                                if ((b = b.length ? b[0] : "") && 0 == b.lastIndexOf("afma-", 0)) {
                                    var c = b.lastIndexOf("v");
                                    if (-1 < c && (b = b.substr(c + 1).match(/^(\d+\.\d+\.\d+|^\d+\.\d+|^\d+)(-.*)?$/))) {
                                        b = b[1];
                                        break b;
                                    }
                                }
                                b = "0.0.0";
                            }
                            if (!a || "0.0.0" !== b) {
                                a = Zu = !0;
                                break a;
                            }
                        }
                        Zu = !1;
                    }
                    a = Zu;
                }
                a || (void 0 === $u && ($u = -1 != bv().indexOf("afma-sdk-a") ? !0 : !1), (a = $u));
                av = a;
            }
            return av ? !0 : lf() ? !1 : uv();
        }
        a = w("Macintosh") || w("Linux") || w("Windows") || w("CrOS");
        return (jj.isSelected() || hj.isSelected() || ij.isSelected()) && a && Cb() ? uv() : !1;
    }
    function uv() {
        var a = !1,
            b = new R(window.location.href).j;
        rv.forEach(function (c) {
            b.includes(c) && (a = !0);
        });
        return a;
    }
    function vv(a) {
        for (var b = 0, c = 0; c < a.length; c++) b = (Math.imul(31, b) + a.charCodeAt(c)) | 0;
        return b.toString();
    }
    var wv,
        zv = function (a, b, c) {
            if ("number" === typeof a) var d = { name: xv(a) };
            else (d = a), (a = yv(a.name));
            this.code = a;
            this.h = d;
            b = "Error " + b + ": " + this.getName();
            c && (b += ", " + c);
            bb.call(this, b);
        };
    $a(zv, bb);
    zv.prototype.getName = function () {
        return this.h.name || "";
    };
    var Av = { ue: 1, Pg: 2, NOT_FOUND_ERR: 3, ae: 4, de: 5, Qg: 6, te: 7, ABORT_ERR: 8, re: 9, th: 10, TIMEOUT_ERR: 11, qe: 12, INVALID_ACCESS_ERR: 13, INVALID_STATE_ERR: 14 },
        Bv = (t.h || t.j || Av).ue,
        Cv = (t.h || t.j || Av).NOT_FOUND_ERR,
        Dv = (t.h || t.j || Av).ae,
        Ev = (t.h || t.j || Av).de,
        Fv = (t.h || t.j || Av).te,
        Gv = (t.h || t.j || Av).ABORT_ERR,
        Hv = (t.h || t.j || Av).re,
        Iv = (t.h || t.j || Av).TIMEOUT_ERR,
        Jv = (t.h || t.j || Av).qe,
        Kv = (t.DOMException || Av).INVALID_ACCESS_ERR,
        Lv = (t.DOMException || Av).INVALID_STATE_ERR,
        yv = function (a) {
            switch (a) {
                case "UnknownError":
                    return Bv;
                case "NotFoundError":
                    return Cv;
                case "ConstraintError":
                    return Dv;
                case "DataError":
                    return Ev;
                case "TransactionInactiveError":
                    return Fv;
                case "AbortError":
                    return Gv;
                case "ReadOnlyError":
                    return Hv;
                case "TimeoutError":
                    return Iv;
                case "QuotaExceededError":
                    return Jv;
                case "InvalidAccessError":
                    return Kv;
                case "InvalidStateError":
                    return Lv;
                default:
                    return Bv;
            }
        },
        xv = function (a) {
            switch (a) {
                case Bv:
                    return "UnknownError";
                case Cv:
                    return "NotFoundError";
                case Dv:
                    return "ConstraintError";
                case Ev:
                    return "DataError";
                case Fv:
                    return "TransactionInactiveError";
                case Gv:
                    return "AbortError";
                case Hv:
                    return "ReadOnlyError";
                case Iv:
                    return "TimeoutError";
                case Jv:
                    return "QuotaExceededError";
                case Kv:
                    return "InvalidAccessError";
                case Lv:
                    return "InvalidStateError";
                default:
                    return "UnknownError";
            }
        },
        Mv = function (a, b) {
            return "error" in a ? new zv(a.error, b) : new zv({ name: "UnknownError" }, b);
        },
        Nv = function (a, b) {
            return "name" in a ? new zv(a, b + ": " + a.message) : new zv({ name: "UnknownError" }, b);
        };
    var Ov = function (a) {
            this.h = a;
        },
        Pv = t.IDBKeyRange || t.webkitIDBKeyRange;
    Ov.prototype.range = function () {
        return this.h;
    }; /*

 Copyright 2005, 2007 Bob Ippolito. All Rights Reserved.
 Copyright The Closure Library Authors.
 SPDX-License-Identifier: MIT
*/
    var Qv = function () {
        this.A = [];
        this.o = this.l = !1;
        this.j = void 0;
        this.J = this.G = this.C = !1;
        this.B = 0;
        this.h = null;
        this.H = 0;
    };
    Qv.prototype.cancel = function (a) {
        if (this.l) this.j instanceof Qv && this.j.cancel();
        else {
            if (this.h) {
                var b = this.h;
                delete this.h;
                a ? b.cancel(a) : (b.H--, 0 >= b.H && b.cancel());
            }
            this.J = !0;
            this.l || Rv(this, new Sv(this));
        }
    };
    Qv.prototype.D = function (a, b) {
        this.C = !1;
        Tv(this, a, b);
    };
    var Tv = function (a, b, c) {
            a.l = !0;
            a.j = c;
            a.o = !b;
            Uv(a);
        },
        Wv = function (a) {
            if (a.l) {
                if (!a.J) throw new Vv(a);
                a.J = !1;
            }
        };
    Qv.prototype.callback = function (a) {
        Wv(this);
        Tv(this, !0, a);
    };
    var Rv = function (a, b) {
            Wv(a);
            Tv(a, !1, b);
        },
        Yv = function (a, b) {
            return Xv(a, b, null, void 0);
        },
        Xv = function (a, b, c, d) {
            a.A.push([b, c, d]);
            a.l && Uv(a);
            return a;
        };
    Qv.prototype.then = function (a, b, c) {
        var d,
            e,
            f = new rk(function (g, h) {
                e = g;
                d = h;
            });
        Xv(this, e, function (g) {
            g instanceof Sv ? f.cancel() : d(g);
        });
        return f.then(a, b, c);
    };
    Qv.prototype.$goog_Thenable = !0;
    Qv.prototype.isError = function (a) {
        return a instanceof Error;
    };
    var Zv = function (a) {
            return Kb(a.A, function (b) {
                return "function" === typeof b[1];
            });
        },
        Uv = function (a) {
            if (a.B && a.l && Zv(a)) {
                var b = a.B,
                    c = $v[b];
                c && (t.clearTimeout(c.h), delete $v[b]);
                a.B = 0;
            }
            a.h && (a.h.H--, delete a.h);
            b = a.j;
            for (var d = (c = !1); a.A.length && !a.C; ) {
                var e = a.A.shift(),
                    f = e[0],
                    g = e[1];
                e = e[2];
                if ((f = a.o ? g : f))
                    try {
                        var h = f.call(e || null, b);
                        void 0 !== h && ((a.o = a.o && (h == b || a.isError(h))), (a.j = b = h));
                        if (pk(b) || ("function" === typeof t.Promise && b instanceof t.Promise)) (d = !0), (a.C = !0);
                    } catch (k) {
                        (b = k), (a.o = !0), Zv(a) || (c = !0);
                    }
            }
            a.j = b;
            d && ((h = Xa(a.D, a, !0)), (d = Xa(a.D, a, !1)), b instanceof Qv ? (Xv(b, h, d), (b.G = !0)) : b.then(h, d));
            c && ((b = new aw(b)), ($v[b.h] = b), (a.B = b.h));
        },
        Vv = function () {
            bb.call(this);
        };
    $a(Vv, bb);
    Vv.prototype.message = "Deferred has already fired";
    Vv.prototype.name = "AlreadyCalledError";
    var Sv = function () {
        bb.call(this);
    };
    $a(Sv, bb);
    Sv.prototype.message = "Deferred was canceled";
    Sv.prototype.name = "CanceledError";
    var aw = function (a) {
        this.h = t.setTimeout(Xa(this.l, this), 0);
        this.j = a;
    };
    aw.prototype.l = function () {
        delete $v[this.h];
        throw this.j;
    };
    var $v = {};
    var bw = function () {
        M.call(this);
    };
    $a(bw, M);
    bw.prototype.h = null;
    bw.prototype.next = function (a) {
        if (a) this.h["continue"](a);
        else this.h["continue"]();
    };
    bw.prototype.update = function (a) {
        var b = "updating via cursor with value ",
            c = new Qv();
        try {
            var d = this.h.update(a);
        } catch (e) {
            return (b += Zh(a)), Rv(c, Nv(e, b)), c;
        }
        d.onsuccess = function () {
            c.callback();
        };
        d.onerror = function (e) {
            b += Zh(a);
            Rv(c, Mv(e.target, b));
        };
        return c;
    };
    bw.prototype.remove = function () {
        var a = new Qv();
        try {
            var b = this.h["delete"]();
        } catch (c) {
            return Rv(a, Nv(c, "deleting via cursor")), a;
        }
        b.onsuccess = function () {
            a.callback();
        };
        b.onerror = function (c) {
            Rv(a, Mv(c.target, "deleting via cursor"));
        };
        return a;
    };
    var cw = function (a, b) {
        var c = new bw();
        try {
            var d = b ? b.range() : null;
            var e = a.openCursor(d);
        } catch (f) {
            throw (c.dispose(), Nv(f, a.name));
        }
        e.onsuccess = function (f) {
            c.h = f.target.result || null;
            c.h ? c.dispatchEvent("n") : c.dispatchEvent("c");
        };
        e.onerror = function () {
            c.dispatchEvent("e");
        };
        return c;
    };
    var dw = function (a) {
        this.h = a;
    };
    dw.prototype.getName = function () {
        return this.h.name;
    };
    var ew = function (a, b, c) {
        var d = new Qv();
        try {
            var e = a.h.get(c);
        } catch (f) {
            return (b += " with key " + Zh(c)), Rv(d, Nv(f, b)), d;
        }
        e.onsuccess = function (f) {
            d.callback(f.target.result);
        };
        e.onerror = function (f) {
            b += " with key " + Zh(c);
            Rv(d, Mv(f.target, b));
        };
        return d;
    };
    dw.prototype.get = function (a) {
        return ew(this, "getting from index " + this.getName(), a);
    };
    var fw = function (a, b) {
        return cw(a.h, b);
    };
    var gw = function (a) {
        this.h = a;
    };
    gw.prototype.getName = function () {
        return this.h.name;
    };
    var hw = function (a, b, c, d, e) {
            var f = new Qv();
            try {
                var g = e ? a.h[b](d, e) : a.h[b](d);
            } catch (h) {
                return (c += Zh(d)), e && (c += ", with key " + Zh(e)), Rv(f, Nv(h, c)), f;
            }
            g.onsuccess = function (h) {
                f.callback(h.target.result);
            };
            g.onerror = function (h) {
                c += Zh(d);
                e && (c += ", with key " + Zh(e));
                Rv(f, Mv(h.target, c));
            };
            return f;
        },
        iw = function (a, b) {
            return hw(a, "put", "putting into " + a.getName() + " with value", b, void 0);
        };
    gw.prototype.add = function (a, b) {
        return hw(this, "add", "adding into " + this.getName() + " with value ", a, b);
    };
    gw.prototype.remove = function (a) {
        var b = new Qv();
        try {
            var c = this.h["delete"](a instanceof Ov ? a.range() : a);
        } catch (e) {
            return (c = "removing from " + this.getName() + " with key " + Zh(a)), Rv(b, Nv(e, c)), b;
        }
        c.onsuccess = function () {
            b.callback();
        };
        var d = this;
        c.onerror = function (e) {
            var f = "removing from " + d.getName() + " with key " + Zh(a);
            Rv(b, Mv(e.target, f));
        };
        return b;
    };
    gw.prototype.get = function (a) {
        var b = new Qv();
        try {
            var c = this.h.get(a);
        } catch (e) {
            return (c = "getting from " + this.getName() + " with key " + Zh(a)), Rv(b, Nv(e, c)), b;
        }
        c.onsuccess = function (e) {
            b.callback(e.target.result);
        };
        var d = this;
        c.onerror = function (e) {
            var f = "getting from " + d.getName() + " with key " + Zh(a);
            Rv(b, Mv(e.target, f));
        };
        return b;
    };
    gw.prototype.clear = function () {
        var a = "clearing store " + this.getName(),
            b = new Qv();
        try {
            var c = this.h.clear();
        } catch (d) {
            return Rv(b, Nv(d, a)), b;
        }
        c.onsuccess = function () {
            b.callback();
        };
        c.onerror = function (d) {
            Rv(b, Mv(d.target, a));
        };
        return b;
    };
    var jw = function (a) {
        try {
            return new dw(a.h.index("timestamp"));
        } catch (b) {
            throw Nv(b, "getting index timestamp");
        }
    };
    gw.prototype.count = function (a) {
        var b = new Qv();
        try {
            var c = a ? a.range() : null,
                d = this.h.count(c);
            d.onsuccess = function (f) {
                b.callback(f.target.result);
            };
            var e = this;
            d.onerror = function (f) {
                Rv(b, Mv(f.target, e.getName()));
            };
        } catch (f) {
            Rv(b, Nv(f, this.getName()));
        }
        return b;
    };
    var kw = function (a, b) {
        M.call(this);
        this.h = a;
        this.l = b;
        this.j = new Iu(this);
        this.j.P(this.h, "complete", Xa(this.dispatchEvent, this, "complete"));
        this.j.P(this.h, "abort", Xa(this.dispatchEvent, this, "abort"));
        this.j.P(this.h, "error", this.fe);
    };
    $a(kw, M);
    l = kw.prototype;
    l.fe = function (a) {
        a.target instanceof zv ? this.dispatchEvent({ type: "error", target: a.target }) : this.dispatchEvent({ type: "error", target: Mv(a.target, "in transaction") });
    };
    l.objectStore = function (a) {
        try {
            return new gw(this.h.objectStore(a));
        } catch (b) {
            throw Nv(b, "getting object store " + a);
        }
    };
    l.commit = function (a) {
        if (this.h.commit || !a)
            try {
                this.h.commit();
            } catch (b) {
                throw Nv(b, "cannot commit the transaction");
            }
    };
    l.wait = function () {
        var a = new Qv();
        Pj(this, "complete", Xa(a.callback, a));
        var b = Pj(this, "abort", function () {
            Yj(c);
            Rv(a, new zv(Gv, "waiting for transaction to complete"));
        });
        var c = Pj(this, "error", function (e) {
            Yj(b);
            Rv(a, e.target);
        });
        var d = this.l;
        return Yv(a, function () {
            return d;
        });
    };
    l.abort = function () {
        this.h.abort();
    };
    l.N = function () {
        kw.ya.N.call(this);
        this.j.dispose();
    };
    var lw = function (a) {
        M.call(this);
        this.h = a;
        this.j = new Iu(this);
        this.j.P(this.h, "abort", Xa(this.dispatchEvent, this, "abort"));
        this.j.P(this.h, "error", this.ge);
        this.j.P(this.h, "versionchange", this.He);
        this.j.P(this.h, "close", Xa(this.dispatchEvent, this, "close"));
    };
    $a(lw, M);
    l = lw.prototype;
    l.Rc = !0;
    l.ge = function (a) {
        a = (a = a.target) && a.error;
        this.dispatchEvent({ type: "error", errorCode: a && a.severity });
    };
    l.He = function (a) {
        this.dispatchEvent(new mw(a.oldVersion, a.newVersion));
    };
    l.close = function () {
        this.Rc && (this.h.close(), (this.Rc = !1));
    };
    l.getName = function () {
        return this.h.name;
    };
    l.getVersion = function () {
        return Number(this.h.version);
    };
    var nw = function (a) {
        var b = ["MediaSourceVideoChunk"];
        try {
            var c = a.h.transaction(b, "readwrite");
            return new kw(c, a);
        } catch (d) {
            throw Nv(d, "creating transaction");
        }
    };
    lw.prototype.N = function () {
        lw.ya.N.call(this);
        this.j.dispose();
    };
    var mw = function (a, b) {
        Aj.call(this, "versionchange");
        this.oldVersion = a;
        this.newVersion = b;
    };
    $a(mw, Aj);
    var ow = function (a) {
        var b = new Qv();
        void 0 == wv && (wv = t.indexedDB || t.mozIndexedDB || t.webkitIndexedDB || t.moz_indexedDB);
        var c = wv.open("VideoChunkPersistentStorage", 5);
        c.onsuccess = function (d) {
            d = new lw(d.target.result);
            b.callback(d);
        };
        c.onerror = function (d) {
            Rv(b, Mv(d.target, "opening database VideoChunkPersistentStorage"));
        };
        c.onupgradeneeded = function (d) {
            if (a) {
                var e = new lw(d.target.result);
                a(new mw(d.oldVersion, d.newVersion), e, new kw(d.target.transaction, e));
            }
        };
        c.onblocked = function () {};
        return b;
    };
    var pw = { Bh: "videoId", Gg: "itag", jh: "source", lh: "startIndex" },
        qw = function () {
            M.call(this);
            this.h = null;
        };
    r(qw, M);
    qw.prototype.initialize = function () {
        var a = this;
        return Promise.resolve(ow(this.j)).then(
            function (b) {
                return (a.h = b);
            },
            function (b) {
                I(J(), "codf", b.message);
            }
        );
    };
    var rw = function (a) {
        return null !== a.h && a.h.Rc;
    };
    qw.prototype.close = function () {
        var a = this;
        return new Promise(function (b) {
            return tw(a, b);
        })
            .then(function () {
                return uw();
            })
            .then(function () {
                return a.h.close();
            });
    };
    var uw = function () {
            return "storage" in navigator && "estimate" in navigator.storage
                ? navigator.storage.estimate().then(function (a) {
                      I(J(), "csue", String(a.usage));
                  })
                : Promise.resolve(void 0);
        },
        yw = function (a, b) {
            b = vw(b);
            if (!b) return Promise.resolve(null);
            var c = ww(b);
            return xw(a, c, b.lmt);
        },
        Aw = function (a, b, c, d) {
            if ((c = vw(c))) {
                var e = ww(c),
                    f = c.startIndex;
                zw(a, { cacheId: e, startIndex: f, endIndex: f + b.byteLength - 1, lmt: c.lmt, timestamp: new Date(Date.now()), isLastVideoChunk: d, video: b });
            } else Promise.resolve(void 0);
        };
    qw.prototype.j = function (a, b) {
        if (b.h.objectStoreNames.contains("MediaSourceVideoChunk"))
            try {
                b.h.deleteObjectStore("MediaSourceVideoChunk");
            } catch (d) {
                throw Nv(d, "deleting object store MediaSourceVideoChunk");
            }
        a = { keyPath: "cacheId" };
        try {
            var c = new gw(b.h.createObjectStore("MediaSourceVideoChunk", a));
        } catch (d) {
            throw Nv(d, "creating object store MediaSourceVideoChunk");
        }
        b = { unique: !1 };
        try {
            c.h.createIndex("timestamp", "timestamp", b);
        } catch (d) {
            throw Nv(d, "creating new index timestamp with key path timestamp");
        }
    };
    var tw = function (a, b) {
            var c = new Date(Date.now());
            c.setDate(c.getDate() - 30);
            c = new Ov(Pv.upperBound(c, void 0));
            var d = fw(jw(nw(a.h).objectStore("MediaSourceVideoChunk")), c),
                e = d.P("n", function () {
                    d.remove();
                    d.next();
                });
            Pj(d, "c", function () {
                Yj(e);
                b();
            });
        },
        vw = function (a) {
            var b = new nv(a);
            a = b.getId();
            var c = ov(b, "itag"),
                d = ov(b, "source"),
                e = ov(b, "lmt");
            (b = b.h.l.get("range")) ? ((b = b.split("-")[0]), (b = !b || isNaN(b) ? null : parseInt(b, 10))) : (b = null);
            var f = [];
            a ? (c ? (d ? (e ? null === b && f.push("startIndex") : f.push("lmt")) : f.push("source")) : f.push("itag")) : f.push("videoId");
            return 0 < f.length ? (I(J(), "civp", f.join("-")), null) : { videoId: a, itag: c, source: d, lmt: e, startIndex: b + 0 };
        },
        ww = function (a) {
            var b = Object.keys(pw)
                .sort()
                .map(function (c) {
                    return a[pw[c]];
                })
                .join(",");
            return vv(b);
        },
        xw = function (a, b, c) {
            var d = nw(a.h).objectStore("MediaSourceVideoChunk");
            return Promise.resolve(d.get(b)).then(
                function (e) {
                    if (!e) return I(J(), "cenf", "1"), null;
                    if (e.lmt !== c)
                        return (
                            I(J(), "cdl", "1"),
                            d.remove(b).then(null, function (f) {
                                I(J(), "crdlvf", f.message);
                            }),
                            null
                        );
                    I(J(), "cefml", "1");
                    return { endIndex: e.endIndex, isLastVideoChunk: e.isLastVideoChunk, video: e.video };
                },
                function (e) {
                    I(J(), "cgvf", e.message);
                }
            );
        },
        zw = function (a, b) {
            a = nw(a.h).objectStore("MediaSourceVideoChunk");
            Promise.resolve(iw(a, b)).then(
                function () {
                    I(J(), "cavs", "1");
                },
                function (c) {
                    I(J(), "cavf", c.message);
                }
            );
        };
    var Bw = function (a) {
        M.call(this);
        var b = this;
        this.D = new R(a);
        this.G = this.h = this.l = this.j = 0;
        this.A = (this.C = tv()) ? Pt(qw) : null;
        yj(this, function () {
            xj(b.A);
        });
        this.I = this.C ? this.A.initialize() : null;
        this.B = null;
    };
    r(Bw, M);
    Bw.prototype.isComplete = function () {
        return 3 === this.h;
    };
    var Dw = function (a) {
            Ga(function (b) {
                if (1 == b.h) return 2 === a.h && (a.h = 1), ya(b, Cw(a), 4);
                var c = 3 < a.G;
                if (c && null !== a.B) {
                    var d = ou("media_source_error", {
                        code: 0 < a.l ? MediaError.MEDIA_ERR_NETWORK : MediaError.MEDIA_ERR_SRC_NOT_SUPPORTED,
                        message: 'Response code "' + a.B + '" with ' + a.j + " bytes requested and " + a.l + " bytes loaded",
                    });
                    a.dispatchEvent(d);
                }
                a.l < a.j && 3 !== a.h && !c ? (b.h = 1) : (3 !== a.h && (a.h = 0), (b.h = 0));
            });
        },
        Cw = function (a) {
            var b;
            return Ga(function (c) {
                switch (c.h) {
                    case 1:
                        b = a.l + "-" + (a.j - 1);
                        pt(a.D, "range", b);
                        if (!a.C) {
                            c.h = 2;
                            break;
                        }
                        return ya(c, a.I, 3);
                    case 3:
                        return c.return(Ew(a));
                    case 2:
                        return (c.j = 4), ya(c, Fw(a), 6);
                    case 6:
                        c.h = 0;
                        c.j = 0;
                        break;
                    case 4:
                        za(c), a.G++, (c.h = 0);
                }
            });
        },
        Ew = function (a) {
            var b;
            return Ga(function (c) {
                switch (c.h) {
                    case 1:
                        return ya(c, yw(a.A, a.D), 2);
                    case 2:
                        if ((b = c.B)) {
                            b.isLastVideoChunk && (a.h = 3);
                            Gw(a, b.video, 0);
                            c.h = 0;
                            break;
                        }
                        c.j = 4;
                        return ya(c, Fw(a), 6);
                    case 6:
                        c.h = 0;
                        c.j = 0;
                        break;
                    case 4:
                        za(c), a.G++, (c.h = 0);
                }
            });
        },
        Fw = function (a) {
            return new Promise(function (b, c) {
                var d = new XMLHttpRequest(),
                    e = 0,
                    f = a.j - a.l;
                d.addEventListener("load", function () {
                    bi("lvlcl");
                    if (400 <= d.status) return I(J(), "lvlxes", d.status.toString()), (a.B = d.status), c();
                    var g = d.response;
                    g.byteLength < f && (a.h = 3);
                    var h = Gw(a, g, e);
                    e += h;
                    a.C && 0 < g.byteLength && Aw(a.A, g, a.D, g.byteLength < f);
                    b();
                });
                d.addEventListener("timeout", function () {
                    bi("lvlct");
                    a.B = d.status;
                    c();
                });
                d.addEventListener("error", function () {
                    bi("lvlce");
                    a.B = d.status;
                    c();
                });
                d.addEventListener("progress", function () {
                    if (400 <= d.status) a.B = d.status;
                    else {
                        var g = Gw(a, d.response, e);
                        e += g;
                    }
                });
                d.responseType = "arraybuffer";
                d.open("get", a.D.toString());
                d.send(null);
            });
        },
        Gw = function (a, b, c) {
            if (null === b) return 0;
            b = b.slice(c);
            a.l += b.byteLength;
            a.dispatchEvent({ type: "progress", Ce: b });
            return b.byteLength;
        };
    Bw.prototype.N = function () {
        this.C && rw(this.A) && this.A.close();
        M.prototype.N.call(this);
    };
    function Hw() {
        return !!window.MediaSource;
    }
    function Iw(a) {
        return [43, 44, 45].includes(a) && xc ? !1 : Rt[a] ? ((a = nu(a)), !!a && Hw() && MediaSource.isTypeSupported(a)) : !1;
    }
    var Jw = function () {};
    Jw.prototype.h = function (a, b, c) {
        return 0 === c ? 1e6 : 5e3 > b - a ? 3e5 : 0;
    };
    var Kw = function (a, b, c, d) {
        this.url = a;
        this.mimeType = b;
        this.chunkSize = c;
        this.h = void 0 === d ? null : d;
    };
    var Nw = function (a) {
        M.call(this);
        var b = this;
        this.j = a;
        this.A = this.j.map(function (c) {
            return Pt(Bw, c.url);
        });
        this.$ = Pt(MediaSource);
        this.h = [];
        this.l = window.URL.createObjectURL(this.$);
        this.G = 0;
        this.D = !1;
        this.C = function () {
            return Lw(b);
        };
        this.$.addEventListener("sourceopen", this.C);
        this.$.addEventListener("sourceended", function (c) {
            return b.dispatchEvent(c);
        });
        this.I = Mw(this);
        this.B = 0;
    };
    r(Nw, M);
    var Mw = function (a) {
            for (var b = [], c = 0; c < a.j.length; ++c) b.push(new Jw());
            return b;
        },
        Lw = function (a) {
            bi("msms_oso");
            for (var b = {}, c = 0; c < a.j.length; b = { xb: b.xb, wb: b.wb }, ++c) {
                var d = a.j[c];
                I(J(), "msms_mime" + c, d.mimeType);
                I(J(), "msms_cs" + c, d.chunkSize.toString());
                b.xb = a.$.addSourceBuffer(d.mimeType);
                b.wb = a.A[c];
                b.wb.P(
                    "progress",
                    (function (e) {
                        return function (f) {
                            var g = e.wb;
                            f = f.Ce;
                            0 !== f.byteLength && e.xb.appendBuffer(f);
                            g.isComplete() && (a.G++, a.G === a.h.length && Ow(a));
                        };
                    })(b)
                );
                b.wb.P("media_source_error", function (e) {
                    a.dispatchEvent(e);
                });
                b.xb ? a.h.push(b.xb) : bi("msms_sbf" + c);
            }
            I(J(), "msms_ns", a.h.length.toString());
            a.D = !0;
            Pw(a);
        },
        Ow = function (a) {
            Promise.all(
                a.h.map(function (b) {
                    return new Promise(function (c) {
                        b.updating
                            ? b.addEventListener("updateend", function () {
                                  c();
                              })
                            : c();
                    });
                })
            ).then(function () {
                return a.$.endOfStream();
            });
        },
        Pw = function (a) {
            if (a.D)
                for (var b = 0; b < a.j.length; ++b) {
                    var c = a.A[b],
                        d = a.h[b];
                    d = 0 === d.buffered.length ? 0 : 1e3 * d.buffered.end(0);
                    d = a.I[b].h(a.B, d, c.j);
                    0 !== d && (1 === c.h ? ((c.j += d), (c.h = 2)) : 0 === c.h && ((c.j += d), (c.h = 1), Dw(c)));
                }
        };
    Nw.prototype.N = function () {
        this.l && window.URL.revokeObjectURL(this.l);
        for (var a = q(this.A), b = a.next(); !b.done; b = a.next()) b.value.dispose();
        this.$.removeEventListener("sourceopen", this.C);
        M.prototype.N.call(this);
    };
    var Qw = RegExp(
            "/pagead/conversion|/pagead/adview|/pagead/gen_204|/activeview?|csi.gstatic.com/csi|google.com/pagead/xsul|google.com/ads/measurement/l|googleads.g.doubleclick.net/pagead/ide_cookie|googleads.g.doubleclick.net/xbbe/pixel"
        ),
        Rw = RegExp("outstream.min.js"),
        Sw = RegExp("outstream.min.css"),
        Tw = RegExp("fonts.gstatic.com"),
        Uw = RegExp("googlevideo.com/videoplayback|c.2mdn.net/videoplayback|gcdn.2mdn.net/videoplayback"),
        Vw = RegExp("custom.elements.min.js");
    function Ww(a, b) {
        var c = 0,
            d = 0,
            e = 0,
            f = 0,
            g = 0,
            h = 0,
            k = 0,
            n = !1,
            m = !1;
        if ("function" === typeof Ma("performance.getEntriesByType", t) && "transferSize" in t.PerformanceResourceTiming.prototype) {
            var x = t.performance.getEntriesByType("resource");
            x = q(x);
            for (var v = x.next(); !v.done; v = x.next())
                (v = v.value),
                    Qw.test(v.name) ||
                        ((f += 1),
                        v.transferSize
                            ? ((c += v.transferSize),
                              v.encodedBodySize && v.transferSize < v.encodedBodySize && ((h += 1), (e += v.encodedBodySize), Rw.test(v.name) && (n = !0), Sw.test(v.name) && (m = !0)),
                              Uw.test(v.name) && (d += v.transferSize))
                            : 0 == v.transferSize && 0 == v.encodedBodySize
                            ? Vw.test(v.name)
                                ? (c += 6686)
                                : Tw.test(v.name) || ((k += 1), ai(J(), { event_name: "unmeasurable_asset", resource_name: v.name, encoded_body_size: v.encodedBodySize, transfer_size: v.transferSize }))
                            : ((g += 1), (e += v.encodedBodySize), Rw.test(v.name) && (n = !0), Sw.test(v.name) && (m = !0)));
            x = 0;
            if (a.duration) {
                for (v = 0; v < a.buffered.length; v++) x += a.buffered.end(v) - a.buffered.start(v);
                x = Math.min(x, a.duration);
            }
            ai(J(), {
                event_name: b,
                asset_bytes: c,
                video_bytes: d,
                cached_data_bytes: e,
                js_cached: n,
                css_cached: m,
                num_assets: f,
                num_assets_cached: g,
                num_assets_cache_validated: h,
                num_assets_unmeasurable: k,
                video_played_seconds: a.currentTime.toFixed(2),
                video_muted: a.muted,
                video_seconds_loaded: x.toFixed(2),
            });
        } else I(J(), "error", "reporting_timing_not_supported");
    }
    function Xw(a) {
        var b = J(),
            c = a.getVideoPlaybackQuality && a.getVideoPlaybackQuality();
        c ? ((a = a.currentTime), I(b, "vqdf", String(c.droppedVideoFrames)), I(b, "vqtf", String(c.totalVideoFrames)), I(b, "vqfr", String(Math.round(c.totalVideoFrames / a)))) : I(b, "vqu", "1");
    }
    var Yw = function () {};
    Yw.prototype.toString = function () {
        return "video_mute";
    };
    var Zw = new Yw();
    var $w = function (a) {
        L.call(this);
        this.B = 1;
        this.l = [];
        this.o = 0;
        this.h = [];
        this.j = {};
        this.D = !!a;
    };
    $a($w, L);
    var ax = function (a, b, c) {
            var d = Zw.toString(),
                e = a.j[d];
            e || (e = a.j[d] = []);
            var f = a.B;
            a.h[f] = d;
            a.h[f + 1] = b;
            a.h[f + 2] = c;
            a.B = f + 3;
            e.push(f);
        },
        bx = function (a, b, c) {
            var d = a.j[Zw.toString()];
            if (d) {
                var e = a.h;
                (d = d.find(function (f) {
                    return e[f + 1] == b && e[f + 2] == c;
                })) && a.A(d);
            }
        };
    $w.prototype.A = function (a) {
        var b = this.h[a];
        if (b) {
            var c = this.j[b];
            0 != this.o ? (this.l.push(a), (this.h[a + 1] = Na)) : (c && Pb(c, a), delete this.h[a], delete this.h[a + 1], delete this.h[a + 2]);
        }
        return !!b;
    };
    $w.prototype.C = function (a, b) {
        var c = this.j[a];
        if (c) {
            for (var d = Array(arguments.length - 1), e = 1, f = arguments.length; e < f; e++) d[e - 1] = arguments[e];
            if (this.D)
                for (e = 0; e < c.length; e++) {
                    var g = c[e];
                    cx(this.h[g + 1], this.h[g + 2], d);
                }
            else {
                this.o++;
                try {
                    for (e = 0, f = c.length; e < f && !this.Ia(); e++) (g = c[e]), this.h[g + 1].apply(this.h[g + 2], d);
                } finally {
                    if ((this.o--, 0 < this.l.length && 0 == this.o)) for (; (c = this.l.pop()); ) this.A(c);
                }
            }
        }
    };
    var cx = function (a, b, c) {
        nk(function () {
            a.apply(b, c);
        });
    };
    $w.prototype.clear = function (a) {
        if (a) {
            var b = this.j[a];
            b && (b.forEach(this.A, this), delete this.j[a]);
        } else (this.h.length = 0), (this.j = {});
    };
    $w.prototype.N = function () {
        $w.ya.N.call(this);
        this.clear();
        this.l.length = 0;
    };
    var dx = function (a) {
        L.call(this);
        this.h = new $w(a);
        zj(this, this.h);
    };
    $a(dx, L);
    dx.prototype.clear = function (a) {
        this.h.clear(void 0 !== a ? a.toString() : void 0);
    };
    var ex = function (a) {
        a = void 0 === a ? null : a;
        L.call(this);
        this.h = new Iu(this);
        zj(this, this.h);
        this.sb = a;
    };
    r(ex, L);
    var fx = function (a, b, c) {
        a.sb &&
            (ax(a.sb.h, b, c),
            yj(a, function () {
                bx(a.sb.h, b, c);
            }));
    };
    var gx = function (a, b) {
        ex.call(this, b);
        fx(
            this,
            function (c) {
                c ? a.show() : a.hide();
            },
            this
        );
    };
    r(gx, ex);
    var hx = function () {
        M.call(this);
        this.element = null;
        this.j = new Iu(this);
        zj(this, this.j);
    };
    r(hx, M);
    var jx = function (a, b, c) {
        c = void 0 === c ? !0 : c;
        hx.call(this);
        a.setAttribute("crossorigin", "anonymous");
        var d = ef("TRACK");
        d.setAttribute("kind", "captions");
        d.setAttribute("src", b);
        d.setAttribute("default", "");
        a.appendChild(d);
        this.h = a.textTracks[0];
        ix(this);
        c ? this.show() : this.hide();
    };
    r(jx, hx);
    var ix = function (a) {
        var b = a.h;
        b.addEventListener(
            "cuechange",
            function () {
                for (var c = b.cues, d = 0; d < c.length; d++) {
                    var e = c[d];
                    e.align = "center";
                    e.position = "auto";
                }
            },
            { once: !0 }
        );
    };
    jx.prototype.show = function () {
        this.h.mode = "showing";
    };
    jx.prototype.hide = function () {
        this.h.mode = "hidden";
    };
    function kx(a, b) {
        if ("undefined" !== typeof ReportingObserver) {
            var c = function (e) {
                    e = q(e);
                    for (var f = e.next(); !f.done; f = e.next()) (f = f.value), a(f) && b(f);
                },
                d = new ReportingObserver(c, { buffered: !0 });
            t.addEventListener("pagehide", function () {
                c(d.takeRecords(), d);
                d.disconnect();
            });
            d.observe();
        }
    }
    function lx(a) {
        a = void 0 === a ? null : a;
        kx(
            function (b) {
                return b.body && "HeavyAdIntervention" === b.body.id;
            },
            function (b) {
                var c = b.body.message,
                    d = J();
                I(d, "ham", c);
                c.includes("CPU") ? I(d, "hacpu", "true") : c.includes("network") && I(d, "habytes", "true");
                a && a(b);
            }
        );
    }
    var mx = "autoplay controls crossorigin demuxedaudiosrc demuxedvideosrc loop muted playsinline poster preload src webkit-playsinline x-webkit-airplay".split(" "),
        nx = "autoplay buffered controls crossOrigin currentSrc currentTime defaultMuted defaultPlaybackRate disableRemotePlayback duration ended loop muted networkState onerror onwaitingforkey paused played playsinline poster preload preservesPitch mozPreservesPitch webkitPreservesPitch readyState seekable videoWidth videoHeight volume textTracks canPlayType captureStream getVideoPlaybackQuality load pause play setSinkId oncanplay oncanplaythrough onload onplay onpause onended onfullscreenchange onfullscreenerror addEventListener dispatchEvent removeEventListener requestFullscreen".split(
            " "
        ),
        ox = { childList: !0 },
        px = !RegExp("^\\s*class\\s*\\{\\s*\\}\\s*$").test(function () {}.toString()),
        qx = HTMLElement;
    px &&
        ((qx = function () {
            return t.Reflect.construct(HTMLElement, [], this.__proto__.constructor);
        }),
        Object.setPrototypeOf(qx, HTMLElement),
        Object.setPrototypeOf(qx.prototype, HTMLElement.prototype));
    var rx = function (a) {
            if (null !== a) {
                a = q(a);
                for (var b = a.next(); !b.done; b = a.next()) if (((b = b.value), b.nodeName === "TRACK".toString())) return b;
            }
            return null;
        },
        sx = function (a, b) {
            this.code = a;
            this.message = void 0 === b ? "" : b;
        },
        tx = function (a) {
            sx.call(this, MediaError.MEDIA_ERR_SRC_NOT_SUPPORTED, void 0 === a ? "" : a);
        };
    r(tx, sx);
    var xx = function () {
        var a = qx.call(this) || this;
        I(J(), "ulv", "1");
        a.$ = null;
        a.Od = "";
        a.rd = null;
        a.O = ef("VIDEO");
        ux(a);
        a.sb = new dx();
        vx(a);
        a.Rb = null;
        wx(a);
        a.attachShadow({ mode: "open" });
        a.shadowRoot.appendChild(a.O);
        lx(function () {
            I(J(), "has", a.src || a.lb);
            I(J(), "hat", String(a.O.currentTime));
        });
        a.hc = !1;
        a.Qd = !1;
        a.Hb = null;
        a.Ma = null;
        return a;
    };
    r(xx, qx);
    xx.prototype.attributeChangedCallback = function (a, b, c) {
        switch (a) {
            case "src":
                yx(this, c);
                break;
            case "demuxedaudiosrc":
            case "demuxedvideosrc":
                zx(this);
                break;
            case "muted":
                this.O[a] = "" === c ? !0 : !!c;
                Ax(this, a, c);
                break;
            default:
                Ax(this, a, c);
        }
    };
    var Ax = function (a, b, c) {
            c !== a.O.getAttribute(b) && (null === c ? a.O.removeAttribute(b) : a.O.setAttribute(b, c));
        },
        Bx = function (a) {
            a.$ && (a.O.removeEventListener("timeupdate", a.Hb), a.$.dispose(), (a.$ = null));
        },
        Cx = function (a, b) {
            a.rd = b;
            a.O.dispatchEvent(new Event("error"));
        },
        ux = function (a) {
            Dx(a);
            Ex(a);
            a.O.addEventListener("loadedmetadata", function () {
                a.Ma = kv(a);
                a.Ma.then(function (b) {
                    var c = a.O.videoWidth,
                        d = a.O.videoHeight,
                        e = b.width,
                        f = b.height;
                    0 < c && 0 < d && 0 < e && 0 < f && ((b = b.width / b.height), (c /= d), 0.97 <= Math.min(c, b) / Math.max(c, b) ? Vk(a.O, { "object-fit": "cover" }) : Vk(a.O, { "object-fit": "contain" }));
                });
            });
            a.O.addEventListener("play", function () {
                a.Qd || (Ww(a.O, "first_play"), (a.Qd = !0));
            });
            a.O.addEventListener("pause", function () {
                a.hc || (Ww(a.O, "first_pause"), Xw(a.O), (a.hc = !0));
            });
            Qj(t, "pagehide", function () {
                a.hc || (Ww(a.O, "first_pause"), Xw(a.O), (a.hc = !0));
            });
            a.O.addEventListener("stalled", function () {
                I(J(), "ves", "1");
            });
            new ev(a.O).P("playbackStalled", function () {
                return I(J(), "pbs", "1");
            });
            a.O.addEventListener("media_source_error", function (b) {
                Bx(a);
                b = b.detail;
                Cx(a, new sx(b.code, b.message));
            });
            Fx(a);
        },
        wx = function (a) {
            var b = rx(a.childNodes);
            b && Gx(a, b);
            null === a.Rb && Hx(a);
        },
        Hx = function (a) {
            if (t.MutationObserver) {
                var b = new MutationObserver(function (c) {
                    c = q(c);
                    for (var d = c.next(); !d.done; d = c.next())
                        if (((d = d.value), "childList" === d.type && (d = rx(d.addedNodes)))) {
                            Gx(a, d);
                            b.disconnect();
                            break;
                        }
                });
                b.observe(a, ox);
            }
        },
        vx = function (a) {
            a.O.addEventListener("volumechange", function () {
                a.sb.h.C(Zw.toString(), a.O.muted);
            });
        },
        Gx = function (a, b) {
            if (null === a.Rb && b.hasAttribute("src")) {
                var c = b.getAttribute("src");
                a.Rb = new jx(a.O, c, b.hasAttribute("default"));
                new gx(a.Rb, a.sb);
                c.includes("kind=asr") && I(J(), "act", "1");
            }
        },
        yx = function (a, b) {
            if (b !== a.Od) {
                var c = (a.Od = b) ? mu(b) : null,
                    d = !!c && Iw(c);
                I(J(), "umsem", d ? "1" : "0");
                d
                    ? ((b = Pt(Kw, b, nu(c), 1e3 * Qt[c], null)),
                      (a.$ = Pt(Nw, [b])),
                      a.$.P("media_source_error", function (e) {
                          e = ou("media_source_error", e.detail);
                          a.O.dispatchEvent(e);
                      }),
                      a.$.P("sourceended", function () {
                          return a.O.dispatchEvent(new Event("sourceended"));
                      }),
                      (a.Hb = function () {
                          var e = a.$;
                          e.B = 1e3 * a.O.currentTime;
                          Pw(e);
                      }),
                      a.O.addEventListener("timeupdate", a.Hb),
                      (a.O.src = a.$.l))
                    : (Bx(a), (a.O.src = b));
                a.O.load();
            }
        },
        zx = function (a) {
            a.src && Cx(a, new sx(MediaError.MEDIA_ERR_ABORTED, "Setting demuxed src after src is already set."));
            if (!a.Bb && !a.lb && a.$) Bx(a), (a.O.src = "about:blank"), a.O.load();
            else if (a.Bb && a.lb) {
                var b = mu(a.Bb),
                    c = mu(a.lb);
                if (c && Iw(c))
                    if (b && Iw(b)) {
                        var d = !!c && Iw(c) && !!b && Iw(b);
                        I(J(), "umsed", d ? "1" : "0");
                        c = Pt(Kw, a.lb, nu(c), -1, null);
                        b = Pt(Kw, a.Bb, nu(b), -1, null);
                        a.$ = Pt(Nw, [c, b]);
                        a.$.P("media_source_error", function (e) {
                            e = ou("media_source_error", e.detail);
                            a.O.dispatchEvent(e);
                        });
                        a.Hb = function () {
                            var e = a.$;
                            e.B = 1e3 * a.O.currentTime;
                            Pw(e);
                        };
                        a.O.addEventListener("timeupdate", a.Hb);
                        a.O.src = a.$.l;
                        a.O.load();
                    } else Cx(a, new tx('Audio itag "' + b + '" not supported.'));
                else Cx(a, new tx('Video itag "' + c + '" not supported.'));
            }
        },
        Dx = function (a) {
            for (var b = {}, c = q(nx), d = c.next(); !d.done; b = { za: b.za, rc: b.rc }, d = c.next())
                (b.za = d.value),
                    b.za in a.O &&
                        ("function" === typeof a.O[b.za]
                            ? ((b.rc = a.O[b.za].bind(a.O)),
                              Object.defineProperty(a, b.za, {
                                  set: (function (e) {
                                      return function (f) {
                                          a.O[e.za] = f;
                                      };
                                  })(b),
                                  get: (function (e) {
                                      return function () {
                                          return e.rc;
                                      };
                                  })(b),
                              }))
                            : Object.defineProperty(a, b.za, {
                                  set: (function (e) {
                                      return function (f) {
                                          a.O[e.za] = f;
                                      };
                                  })(b),
                                  get: (function (e) {
                                      return function () {
                                          return a.O[e.za];
                                      };
                                  })(b),
                              }));
        },
        Ex = function (a) {
            Object.defineProperty(a, "error", {
                set: function () {},
                get: function () {
                    return a.O.error ? a.O.error : a.rd;
                },
            });
        },
        Fx = function (a) {
            a.O.style.width = bl();
            a.O.style.height = bl();
        };
    xx.prototype.disconnectedCallback = function () {
        if (this.Ma) {
            var a = gv.get(this.Ma);
            jv(a);
        }
        qx.prototype.disconnectedCallback && qx.prototype.disconnectedCallback.call(this);
    };
    da.Object.defineProperties(xx.prototype, {
        Bb: {
            configurable: !0,
            enumerable: !0,
            set: function (a) {
                this.setAttribute("demuxedaudiosrc", a);
            },
            get: function () {
                return this.getAttribute("demuxedaudiosrc");
            },
        },
        lb: {
            configurable: !0,
            enumerable: !0,
            set: function (a) {
                this.setAttribute("demuxedvideosrc", a);
            },
            get: function () {
                return this.getAttribute("demuxedvideosrc");
            },
        },
        src: {
            configurable: !0,
            enumerable: !0,
            set: function (a) {
                this.setAttribute("src", a);
            },
            get: function () {
                return this.getAttribute("src");
            },
        },
    });
    da.Object.defineProperties(xx, {
        observedAttributes: {
            configurable: !0,
            enumerable: !0,
            get: function () {
                return mx;
            },
        },
    });
    t.customElements && (t.customElements.get("lima-video") || t.customElements.define("lima-video", xx));
    function Ix() {
        var a = Pt(qw);
        a.initialize().then(function (b) {
            b && ((b = ou("initialized")), a.dispatchEvent(b));
        });
        return a;
    }
    var Kx = function (a, b, c, d, e) {
        L.call(this);
        this.K = a;
        this.R = new R(b.url);
        this.j = c;
        this.o = e;
        this.I = b.chunkSize;
        this.sa = d;
        (this.U = b.h) || this.R.l.remove("alr");
        I(J(), "sl_dv" + this.o, (null != this.U).toString());
        this.V = !this.U;
        this.kb = 0;
        this.h = new XMLHttpRequest();
        this.Y = this.T = this.Ob = this.D = this.l = 0;
        this.W = 0.1;
        this.C = [];
        this.M = !1;
        this.Z = this.qa = this.pa = null;
        this.Fa = !1;
        this.td = this.L = this.B = this.ib = this.gb = null;
        this.G = !1;
        if ((this.A = tv())) (this.B = Ix()), zj(this, this.B);
        Jx(this);
    };
    r(Kx, L);
    var Lx = function (a, b) {
            b = ou("media_source_error", b);
            a.K.dispatchEvent(b);
        },
        Mx = function (a, b) {
            Lx(a, { code: 1 < a.l ? MediaError.MEDIA_ERR_NETWORK : MediaError.MEDIA_ERR_SRC_NOT_SUPPORTED, message: b });
        },
        Jx = function (a) {
            a.pa = function () {
                Nx(a);
                if (a.V) {
                    var b = a.h.responseText;
                    a.M = !b || b.length < a.I;
                    a.T = 0;
                    bi("sl_cc" + a.o + "_" + a.l);
                    a.D++;
                    Ox(a);
                }
            };
            a.qa = function () {
                return Nx(a);
            };
            a.Z = function () {
                bi("sl_ec" + a.o + "_" + a.l);
                Mx(a, "Failed to load chunk " + a.l + " for stream " + a.o);
            };
            a.h.addEventListener("load", a.pa);
            a.h.addEventListener("progress", a.qa);
            a.h.addEventListener("error", a.Z);
            a.j.addEventListener("updateend", function () {
                a.j.buffered.length && ((a.Ob = a.j.buffered.end(0)), a.A ? a.G && !a.j.updating && a.l === a.D && (bi("sl_lc" + a.o), a.sa()) : a.M && !a.j.updating && a.l === a.D && (bi("sl_lc" + a.o), a.sa()));
                !a.Fa && 1 < a.K.buffered.length && (I(J(), "dbr", "1"), (a.Fa = !0));
            });
            a.j.addEventListener("update", function () {
                a.C.length && !a.j.updating && a.j.appendBuffer(a.C.shift());
            });
            a.j.addEventListener("error", function () {
                bi("msb_err" + a.o);
                Lx(a, { code: MediaError.MEDIA_ERR_DECODE, message: "Error on SourceBuffer " + a.o });
            });
            a.A
                ? (rw(a.B)
                      ? Px(a)
                      : (a.gb = Qj(a.B, "initialized", function () {
                            Px(a);
                        })),
                  (a.ib = Qj(a.B, "get_video_succeeded", function () {
                      Ox(a);
                  })))
                : Px(a);
        },
        Rx = function (a) {
            bi("sl_rc" + a.o + "-" + a.l);
            var b = Qx(a);
            a.h.open("get", b);
            a.h.overrideMimeType("text/plain; charset=x-user-defined");
            a.h.send(null);
            a.A && ((a.L = null), (a.td = b));
        },
        Nx = function (a) {
            if (400 <= a.h.status) Mx(a, 'Response code "' + a.h.status + '" on loading chunk ' + a.l + " for stream " + a.o);
            else {
                if (!a.V) {
                    var b = a.h.getResponseHeader("content-type");
                    if (b && 0 <= b.indexOf("text/plain")) {
                        a.h.readyState === XMLHttpRequest.DONE && ((a.R = new R(a.h.response)), (a.l = 0), (a.D = 0), a.kb++, Px(a));
                        return;
                    }
                    a.V = !0;
                    bi("sl_redc" + a.o);
                    I(J(), "sl_tr" + a.o, a.kb.toString());
                }
                a.R.l.remove("alr");
                if (a.h.readyState === XMLHttpRequest.LOADING || a.h.readyState === XMLHttpRequest.DONE) (b = Sx(a, a.T)), (a.T = a.h.response.length), (a.Y += b.byteLength), Tx(a, b);
                if (a.A && a.h.readyState === XMLHttpRequest.DONE && ((b = Sx(a, 0)), 0 < b.byteLength)) {
                    var c = a.h.responseText;
                    a.G = !c || c.length < a.I;
                    Aw(a.B, b, new R(a.td), a.G);
                }
            }
        },
        Tx = function (a, b) {
            0 < b.byteLength && (a.j.updating || a.C.length ? a.C.push(b) : a.j.appendBuffer(b));
        },
        Sx = function (a, b) {
            a = a.h.response;
            for (var c = new Uint8Array(a.length - b), d = 0; d < c.length; d++) c[d] = a.charCodeAt(d + b) & 255;
            return c.buffer;
        },
        Ox = function (a) {
            var b = pu;
            -1 != b && b < a.Y + a.I ? (a.K.pause(), (pu = -1), (b = !1)) : ((b = a.D === a.l && !a.j.updating && !a.C.length), (b = a.A ? !a.G && b && a.K.currentTime >= a.W : !a.M && b && a.K.currentTime >= a.W));
            b && ((a.W = a.Ob + 0.1), Px(a));
        },
        Qx = function (a) {
            var b = a.A && a.L ? a.L + 1 : a.l * a.I;
            return pt(a.R, "range", b + "-" + (b + a.I - 1)).toString();
        },
        Px = function (a) {
            if (a.A) {
                var b = new R(Qx(a));
                yw(a.B, b).then(function (c) {
                    c ? ((a.L = parseInt(c.endIndex, 10)), (a.G = c.isLastVideoChunk), Tx(a, c.video), (c = ou("get_video_succeeded")), a.B.dispatchEvent(c), a.D++) : Rx(a);
                    a.l++;
                });
            } else Rx(a), a.l++;
        };
    Kx.prototype.isComplete = function () {
        return this.A ? this.G && !this.j.updating && !this.C.length : this.M && !this.j.updating && !this.C.length;
    };
    Kx.prototype.N = function () {
        this.A && rw(this.B) && this.B.close();
        this.h.removeEventListener("load", this.pa);
        this.h.removeEventListener("progress", this.qa);
        this.h.removeEventListener("error", this.Z);
        Yj(this.gb);
        Yj(this.ib);
        L.prototype.N.call(this);
    };
    var Vx = function (a, b) {
        L.call(this);
        var c = this;
        this.o = a;
        this.D = b;
        this.$ = new MediaSource();
        this.C = [];
        this.j = [];
        this.h = this.l = null;
        this.A = !1;
        this.B = function () {
            return Ux(c);
        };
        this.$.addEventListener("sourceopen", this.B);
    };
    r(Vx, L);
    var Wx = function (a) {
            a.l && a.o.removeEventListener("timeupdate", a.l);
        },
        Ux = function (a) {
            bi("msmsw_oso");
            a.l = function () {
                if (!a.A) for (var e = q(a.j), f = e.next(); !f.done; f = e.next()) Ox(f.value);
            };
            a.o.addEventListener("timeupdate", a.l);
            for (var b = 0; b < a.D.length; b++) {
                var c = a.D[b];
                I(J(), "msmsw_mime" + b, c.mimeType);
                I(J(), "msmsw_cs" + b, c.chunkSize.toString());
                var d = a.$.addSourceBuffer(c.mimeType);
                d
                    ? (a.C.push(d),
                      (c = Pt(
                          Kx,
                          a.o,
                          c,
                          d,
                          function () {
                              a: if (!a.A) {
                                  for (var e = q(a.j), f = e.next(); !f.done; f = e.next()) if (!f.value.isComplete()) break a;
                                  a.$.endOfStream();
                                  a.A = !0;
                                  Wx(a);
                              }
                          },
                          b
                      )),
                      a.j.push(c))
                    : bi("msmsw_sbf" + b);
            }
            I(J(), "msmsw_ns", a.C.length.toString());
        };
    Vx.prototype.N = function () {
        this.h && window.URL.revokeObjectURL(this.h);
        for (var a = q(this.j), b = a.next(); !b.done; b = a.next()) b.value.dispose();
        Wx(this);
        this.$.removeEventListener("sourceopen", this.B);
        L.prototype.N.call(this);
    };
    var Xx = function () {
        throw Error("Do not instantiate directly");
    };
    Xx.prototype.h = null;
    Xx.prototype.getContent = function () {
        return this.content;
    };
    Xx.prototype.toString = function () {
        return this.content;
    };
    var Yx = function () {
        Xx.call(this);
    };
    $a(Yx, Xx);
    var Zx = (function (a) {
        function b(c) {
            this.content = c;
        }
        b.prototype = a.prototype;
        return function (c, d) {
            c = new b(String(c));
            void 0 !== d && (c.h = d);
            return c;
        };
    })(Yx); /*
 Copyright The Closure Library Authors.
 SPDX-License-Identifier: Apache-2.0
*/
    var $x = function () {
        if (window.MutationObserver) {
            var a = [];
            new MutationObserver(function () {
                a.forEach(function (b) {
                    return b();
                });
                a = [];
            }).observe(document.createTextNode(""), { characterData: !0 });
        }
    };
    ("function" === typeof Promise && -1 < String(Promise).indexOf("[native code]")) || $x();
    var ay = function (a) {
            this.h = a;
        },
        by = function (a, b) {
            return Fd(a.h, b) && ((a = a.h[b]), "boolean" === typeof a) ? a : !1;
        },
        cy = function (a) {
            if (Fd(a.h, "forceExperimentIds")) {
                a = a.h.forceExperimentIds;
                var b = [],
                    c = 0;
                Array.isArray(a) &&
                    a.forEach(function (d) {
                        "number" === typeof d && (b[c++] = d);
                    });
                return b;
            }
            return null;
        };
    var W = function () {
            this.D = "always";
            this.M = 4;
            this.B = 1;
            this.h = 0;
            this.J = !0;
            this.o = "en";
            this.L = !1;
            this.U = this.T = "";
            this.A = null;
            this.W = this.R = -1;
            this.V = this.K = this.H = "";
            this.G = !1;
            this.j = !0;
            this.C = Ot();
            this.I = {};
            try {
                this.Z = Km(void 0)[0];
            } catch (a) {}
        },
        dy = function (a) {
            a = Ne(a);
            lb(a) || (a = a.substring(0, 20));
            return a;
        };
    l = W.prototype;
    l.setCompanionBackfill = function (a) {
        this.D = a;
    };
    l.getCompanionBackfill = function () {
        return this.D;
    };
    l.setNumRedirects = function (a) {
        this.M = a;
    };
    l.getNumRedirects = function () {
        return this.M;
    };
    l.setPpid = function (a) {
        this.Y = a;
    };
    l.getPpid = function () {
        return this.Y;
    };
    l.setVpaidAllowed = function (a) {
        "boolean" === typeof a && (this.B = a ? 1 : 0);
    };
    l.setVpaidMode = function (a) {
        this.B = a;
    };
    l.getVpaidMode = function () {
        return this.B;
    };
    l.setAutoPlayAdBreaks = function (a) {
        this.J = a;
    };
    l.isAutoPlayAdBreaks = function () {
        return this.J;
    };
    l.setIsVpaidAdapter = function (a) {
        this.L = a;
    };
    l.Fb = function () {
        return this.L;
    };
    l.setLocale = function (a) {
        if ((a = Su(a))) this.o = a;
    };
    l.Le = function () {
        return this.o;
    };
    l.setPlayerType = function (a) {
        this.T = dy(a);
    };
    l.getPlayerType = function () {
        return this.T;
    };
    l.setPlayerVersion = function (a) {
        this.U = dy(a);
    };
    l.getPlayerVersion = function () {
        return this.U;
    };
    var ey = function (a) {
        if (null == a.A) {
            var b = {},
                c = new R(z().location.href).l;
            if (vt(c, "tcnfp"))
                try {
                    b = JSON.parse(c.get("tcnfp"));
                } catch (d) {}
            a.A = new ay(b);
        }
        return a.A;
    };
    l = W.prototype;
    l.setPageCorrelator = function (a) {
        this.R = a;
    };
    l.setStreamCorrelator = function (a) {
        this.W = a;
    };
    l.setDisableCustomPlaybackForIOS10Plus = function (a) {
        this.G = a;
    };
    l.getDisableCustomPlaybackForIOS10Plus = function () {
        return this.G;
    };
    l.Ze = function () {
        return this.j;
    };
    l.setCookiesEnabled = function (a) {
        null != a && (this.j = a);
    };
    l.setSessionId = function (a) {
        this.C = a;
    };
    l.setDisableFlashAds = function () {};
    l.getDisableFlashAds = function () {
        return !0;
    };
    l.setFeatureFlags = function (a) {
        this.I = a;
    };
    l.getFeatureFlags = function () {
        return this.I;
    };
    l.aa = function (a) {
        a = void 0 === a ? null : a;
        var b = {};
        null != a && (b.activeViewPushUpdates = a);
        b.activityMonitorMode = this.h;
        b.adsToken = this.H;
        b.autoPlayAdBreaks = this.isAutoPlayAdBreaks();
        b.companionBackfill = this.getCompanionBackfill();
        b.cookiesEnabled = this.j;
        b.disableCustomPlaybackForIOS10Plus = this.getDisableCustomPlaybackForIOS10Plus();
        b.engagementDetection = !0;
        b.isFunctionalTest = !1;
        b.isVpaidAdapter = this.Fb();
        b["1pJar"] = this.K;
        b.numRedirects = this.getNumRedirects();
        b.pageCorrelator = this.R;
        b.persistentStateCorrelator = Qf();
        b.playerType = this.getPlayerType();
        b.playerVersion = this.getPlayerVersion();
        b.ppid = this.getPpid();
        b.privacyControls = this.V;
        b.reportMediaRequests = !1;
        b.sessionId = this.C;
        b.streamCorrelator = this.W;
        b.testingConfig = ey(this).h;
        b.urlSignals = this.Z;
        b.vpaidMode = this.getVpaidMode();
        b.featureFlags = this.getFeatureFlags();
        return b;
    };
    W.prototype.getFeatureFlags = W.prototype.getFeatureFlags;
    W.prototype.setFeatureFlags = W.prototype.setFeatureFlags;
    W.prototype.getDisableFlashAds = W.prototype.getDisableFlashAds;
    W.prototype.setDisableFlashAds = W.prototype.setDisableFlashAds;
    W.prototype.setSessionId = W.prototype.setSessionId;
    W.prototype.setCookiesEnabled = W.prototype.setCookiesEnabled;
    W.prototype.isCookiesEnabled = W.prototype.Ze;
    W.prototype.getDisableCustomPlaybackForIOS10Plus = W.prototype.getDisableCustomPlaybackForIOS10Plus;
    W.prototype.setDisableCustomPlaybackForIOS10Plus = W.prototype.setDisableCustomPlaybackForIOS10Plus;
    W.prototype.setStreamCorrelator = W.prototype.setStreamCorrelator;
    W.prototype.setPageCorrelator = W.prototype.setPageCorrelator;
    W.prototype.getPlayerVersion = W.prototype.getPlayerVersion;
    W.prototype.setPlayerVersion = W.prototype.setPlayerVersion;
    W.prototype.getPlayerType = W.prototype.getPlayerType;
    W.prototype.setPlayerType = W.prototype.setPlayerType;
    W.prototype.getLocale = W.prototype.Le;
    W.prototype.setLocale = W.prototype.setLocale;
    W.prototype.isVpaidAdapter = W.prototype.Fb;
    W.prototype.setIsVpaidAdapter = W.prototype.setIsVpaidAdapter;
    W.prototype.isAutoPlayAdBreaks = W.prototype.isAutoPlayAdBreaks;
    W.prototype.setAutoPlayAdBreaks = W.prototype.setAutoPlayAdBreaks;
    W.prototype.getVpaidMode = W.prototype.getVpaidMode;
    W.prototype.setVpaidMode = W.prototype.setVpaidMode;
    W.prototype.setVpaidAllowed = W.prototype.setVpaidAllowed;
    W.prototype.getPpid = W.prototype.getPpid;
    W.prototype.setPpid = W.prototype.setPpid;
    W.prototype.getNumRedirects = W.prototype.getNumRedirects;
    W.prototype.setNumRedirects = W.prototype.setNumRedirects;
    W.prototype.getCompanionBackfill = W.prototype.getCompanionBackfill;
    W.prototype.setCompanionBackfill = W.prototype.setCompanionBackfill;
    var fy = new W();
    var gy = function (a) {
        F.call(this, a);
    };
    r(gy, F);
    var hy = function (a) {
            void 0 !== a.addtlConsent && "string" !== typeof a.addtlConsent && (a.addtlConsent = void 0);
            void 0 !== a.gdprApplies && "boolean" !== typeof a.gdprApplies && (a.gdprApplies = void 0);
            return (void 0 !== a.tcString && "string" !== typeof a.tcString) || (void 0 !== a.listenerId && "number" !== typeof a.listenerId) ? 2 : a.cmpStatus && "error" !== a.cmpStatus ? 0 : 3;
        },
        iy = function (a, b) {
            b = void 0 === b ? 500 : b;
            L.call(this);
            this.j = a;
            this.h = null;
            this.A = {};
            this.B = 0;
            this.o = b;
            this.l = null;
        };
    r(iy, L);
    iy.prototype.N = function () {
        this.A = {};
        this.l && (Ee(this.j, "message", this.l), delete this.l);
        delete this.A;
        delete this.j;
        delete this.h;
        L.prototype.N.call(this);
    };
    var ky = function (a) {
            return "function" === typeof a.j.__tcfapi || null != jy(a);
        },
        ny = function (a, b) {
            var c = { internalErrorState: 0 },
                d = ze(function () {
                    return b(c);
                }),
                e = 0;
            -1 !== a.o &&
                (e = setTimeout(function () {
                    e = 0;
                    c.tcString = "tcunavailable";
                    c.internalErrorState = 1;
                    d();
                }, a.o));
            ly(a, "addEventListener", function (f) {
                f && ((c = f), (c.internalErrorState = hy(c)), my(c) && (0 != c.internalErrorState && (c.tcString = "tcunavailable"), ly(a, "removeEventListener", null, c.listenerId), e && (clearTimeout(e), (e = 0)), d()));
            });
        };
    iy.prototype.addEventListener = function (a) {
        var b = {},
            c = ze(function () {
                return a(b);
            }),
            d = 0;
        -1 !== this.o &&
            (d = setTimeout(function () {
                b.tcString = "tcunavailable";
                b.internalErrorState = 1;
                c();
            }, this.o));
        var e = function (f, g) {
            clearTimeout(d);
            f ? ((b = f), (b.internalErrorState = hy(b)), (g && 0 === b.internalErrorState) || ((b.tcString = "tcunavailable"), g || (b.internalErrorState = 3))) : ((b.tcString = "tcunavailable"), (b.internalErrorState = 3));
            a(b);
        };
        try {
            ly(this, "addEventListener", e);
        } catch (f) {
            (b.tcString = "tcunavailable"), (b.internalErrorState = 3), d && (clearTimeout(d), (d = 0)), c();
        }
    };
    iy.prototype.removeEventListener = function (a) {
        a && a.listenerId && ly(this, "removeEventListener", null, a.listenerId);
    };
    var ly = function (a, b, c, d) {
            c || (c = function () {});
            if ("function" === typeof a.j.__tcfapi) (a = a.j.__tcfapi), a(b, 2, c, d);
            else if (jy(a)) {
                oy(a);
                var e = ++a.B;
                a.A[e] = c;
                a.h && ((c = {}), a.h.postMessage(((c.__tcfapiCall = { command: b, version: 2, callId: e, parameter: d }), c), "*"));
            } else c({}, !1);
        },
        jy = function (a) {
            if (a.h) return a.h;
            a.h = Df(a.j, "__tcfapiLocator");
            return a.h;
        },
        oy = function (a) {
            a.l ||
                ((a.l = function (b) {
                    try {
                        var c = ("string" === typeof b.data ? JSON.parse(b.data) : b.data).__tcfapiReturn;
                        a.A[c.callId](c.returnValue, c.success);
                    } catch (d) {}
                }),
                De(a.j, "message", a.l));
        },
        my = function (a) {
            if (!1 === a.gdprApplies) return !0;
            void 0 === a.internalErrorState && (a.internalErrorState = hy(a));
            return "error" === a.cmpStatus || 0 !== a.internalErrorState || ("loaded" === a.cmpStatus && ("tcloaded" === a.eventStatus || "useractioncomplete" === a.eventStatus)) ? !0 : !1;
        };
    function py(a) {
        var b = {};
        new R(a).l.forEach(function (c, d) {
            b[d] = c;
        });
        return b;
    }
    var qy = function (a) {
            this.Ld = a.isGdprLoader || !1;
            this.uspString = a.uspString || "";
            var b = a.gdprApplies;
            this.j = "boolean" == typeof b ? (b ? "1" : "0") : "number" != typeof b || (1 !== b && 0 !== b) ? ("string" != typeof b || ("1" !== b && "0" !== b) ? "" : "1" == b ? "1" : "0") : 1 == b ? "1" : "0";
            this.h = a.tcString || "";
            /^[\.\w_-]*$/.test(this.h) || (this.h = encodeURIComponent(this.h));
        },
        ry = function (a, b) {
            a = void 0 === a ? {} : a;
            b = void 0 === b ? {} : b;
            this.h = a;
            this.j = new qy(b);
        },
        sy = function (a, b) {
            var c = new R(a);
            var d = c.h;
            (c = kb(c.j, "googleads.g.doubleclick.net") && Qu("/pagead/(live/)?ads", d)) || ((d = new Uu(a)), (c = d.j), (d = Vu(d, d.h)), (c = !kb(c, ".g.doubleclick.net") && kb(c, "doubleclick.net") && Qu("/(ad|pfad)[x|i|j]?/", d)));
            c || ((c = new R(a)), (d = c.h), (c = kb(c.j, "doubleclick.net") && Qu("/gampad/(live/)?ads", d)));
            (c = c || "bid.g.doubleclick.net" == new R(a).j) || ((c = new R(a)), (d = c.h), (c = "ad.doubleclick.net" === c.j && Qu("/dv3/adv", d)));
            c || ((c = new R(a)), (d = c.h), "pubads.g.doubleclick.net" === c.j && (Qu("/ssai/", d) || Qu("/ondemand/", d)));
            return new ry(py(a), b);
        },
        ty = function (a, b) {
            if (a.h.hasOwnProperty(b)) return a.h[b];
        },
        uy = function (a) {
            var b, c;
            if (!(b = "1" == (null == (c = ty(a, "ltd")) ? void 0 : c.toString()))) {
                var d;
                b = null == (d = ty(a, "gdpr")) ? void 0 : d.toString();
                d = a.j.j;
                d = ("1" == d || "0" == d ? d : void 0 != b ? b : "").toLowerCase();
                if ("true" === d || "1" === d)
                    if (((d = a.j.h), (a = ty(a, "gdpr_consent")), (a = d && "tcunavailable" != d ? d : "tcunavailable" == d ? a || d : a || ""), "tcunavailable" === a)) var e = !1;
                    else {
                        if ((d = $s(a)) && a) {
                            var f = ug(d, qs, 1);
                            d = ug(d, js, 2) || new js();
                            b = pg(f, 9, 0);
                            c = pg(f, 4, 0);
                            var g = pg(f, 5, 0),
                                h = qg(f, 10),
                                k = qg(f, 11),
                                n = pg(f, 16, ""),
                                m = qg(f, 15),
                                x = { consents: at(mg(f, 13), Ns), legitimateInterests: at(mg(f, 14), Ns) },
                                v = { consents: at(mg(f, 17), void 0), legitimateInterests: at(mg(f, 18), void 0) },
                                A = at(mg(f, 12), Os),
                                C = vg(f, hs, 19);
                            f = {};
                            C = q(C);
                            for (var O = C.next(); !O.done; O = C.next()) {
                                O = O.value;
                                var la = pg(O, 1, 0);
                                f[la] = f[la] || {};
                                for (var na = q(mg(O, 3)), Ja = na.next(); !Ja.done; Ja = na.next()) f[la][Ja.value] = pg(O, 2, 0);
                            }
                            a = {
                                tcString: a,
                                tcfPolicyVersion: b,
                                gdprApplies: !0,
                                cmpId: c,
                                cmpVersion: g,
                                isServiceSpecific: h,
                                useNonStandardStacks: k,
                                publisherCC: n,
                                purposeOneTreatment: m,
                                purpose: x,
                                vendor: v,
                                specialFeatureOptins: A,
                                publisher: { restrictions: f, consents: at(mg(d, 1), Ns), legitimateInterests: at(mg(d, 2), Ns), customPurposes: { consents: at(mg(d, 3)), legitimateInterests: at(mg(d, 4)) } },
                            };
                        } else a = null;
                        if (a) {
                            var va = void 0 === va ? !1 : va;
                            if (my(a))
                                if (!1 === a.gdprApplies || "tcunavailable" === a.tcString || (void 0 === a.gdprApplies && !va) || "string" !== typeof a.tcString || !a.tcString.length) e = !0;
                                else {
                                    e = void 0 === e ? "755" : e;
                                    c: {
                                        if (a.publisher && a.publisher.restrictions && ((va = a.publisher.restrictions["1"]), void 0 !== va)) {
                                            va = va[void 0 === e ? "755" : e];
                                            break c;
                                        }
                                        va = void 0;
                                    }
                                    0 === va
                                        ? (e = !1)
                                        : a.purpose && a.vendor
                                        ? ((va = a.vendor.consents), (e = !(!va || !va[void 0 === e ? "755" : e])) && a.purposeOneTreatment && "CH" === a.publisherCC ? (e = !0) : e && ((e = a.purpose.consents), (e = !(!e || !e["1"]))))
                                        : (e = !0);
                                }
                            else e = !1;
                        } else e = !1;
                    }
                else e = !0;
                b = !e;
            }
            return b;
        };
    var wy = function (a) {
        F.call(this, a, -1, vy);
    };
    r(wy, F);
    var yy = function (a, b) {
            return E(a, 2, b);
        },
        zy = function (a, b) {
            return E(a, 3, b);
        },
        Ay = function (a, b) {
            return E(a, 4, b);
        },
        By = function (a, b) {
            return E(a, 5, b);
        },
        Cy = function (a, b) {
            return E(a, 9, b);
        },
        Dy = function (a, b) {
            return xg(a, 10, b);
        },
        Ey = function (a, b) {
            return E(a, 11, b);
        },
        Fy = function (a, b) {
            return E(a, 1, b);
        },
        Gy = function (a) {
            F.call(this, a);
        };
    r(Gy, F);
    Gy.prototype.getVersion = function () {
        return pg(this, 2, "");
    };
    var vy = [10, 6];
    var Hy = "platform platformVersion architecture model uaFullVersion bitness fullVersionList wow64".split(" ");
    function Iy(a) {
        var b;
        return null != (b = a.google_tag_data) ? b : (a.google_tag_data = {});
    }
    function Jy(a) {
        var b, c;
        if ("function" !== typeof (null == (b = a.navigator) ? void 0 : null == (c = b.userAgentData) ? void 0 : c.getHighEntropyValues)) return null;
        var d = Iy(a);
        if (d.uach_promise) return d.uach_promise;
        a = a.navigator.userAgentData.getHighEntropyValues(Hy).then(function (e) {
            null != d.uach || (d.uach = e);
            return e;
        });
        return (d.uach_promise = a);
    }
    function Ky(a) {
        var b;
        return Ey(
            Dy(
                Cy(By(Ay(zy(yy(Fy(new wy(), a.platform || ""), a.platformVersion || ""), a.architecture || ""), a.model || ""), a.uaFullVersion || ""), a.bitness || ""),
                (null == (b = a.fullVersionList)
                    ? void 0
                    : b.map(function (c) {
                          var d = new Gy();
                          d = E(d, 1, c.brand);
                          return E(d, 2, c.version);
                      })) || []
            ),
            a.wow64 || !1
        );
    }
    function Ly() {
        var a = window;
        if (jh(qd)) {
            var b, c;
            return null !=
                (c =
                    null == (b = Jy(a))
                        ? void 0
                        : b.then(function (g) {
                              return Ky(g);
                          }))
                ? c
                : null;
        }
        var d, e;
        if ("function" !== typeof (null == (d = a.navigator) ? void 0 : null == (e = d.userAgentData) ? void 0 : e.getHighEntropyValues)) return null;
        var f;
        return null !=
            (f = a.navigator.userAgentData.getHighEntropyValues(Hy).then(function (g) {
                return Ky(g);
            }))
            ? f
            : null;
    }
    var Ny = function () {
            this.adBlock = 1;
            this.appName = null;
            new ry();
            Ot();
            this.deviceId = "";
            this.h = this.referrer = null;
            My(this);
        },
        Oy = function () {
            G(Ny);
            var a = "h.3.507.1";
            fy.Fb() && (a += "/vpaid_adapter");
            return a;
        },
        My = function (a) {
            var b = Ly();
            b &&
                b.then(function (c) {
                    if (null == c) c = null;
                    else {
                        c = c.aa();
                        for (var d = [], e = 0, f = 0; f < c.length; f++) {
                            var g = c.charCodeAt(f);
                            255 < g && ((d[e++] = g & 255), (g >>= 8));
                            d[e++] = g;
                        }
                        c = Dc(d, 3);
                    }
                    a.h = c;
                });
        };
    var Py = "abort canplay canplaythrough durationchange emptied loadstart loadeddata loadedmetadata progress ratechange seeked seeking stalled suspend waiting".split(" ");
    var Ry = function (a) {
            var b = ey(fy);
            if ((b && by(b, "forceCustomPlayback")) || fy.Fb()) return !0;
            if ((jc || Au()) && a) return !1;
            a = a && (jc || Au() || Bu(10)) && fy.getDisableCustomPlaybackForIOS10Plus();
            return ((ic || kc) && !a) || (hc && (!hc || !zu(yu, 4))) || Qy() ? !0 : !1;
        },
        Sy = function (a) {
            return null == a ? !1 : fy.Fb() ? !0 : mc || jc || Au() ? (Cu(a) ? (jc || Au() || (Bu(10) && fy.getDisableCustomPlaybackForIOS10Plus()) ? !1 : !0) : !0) : (hc && (!hc || !zu(yu, 4))) || Qy() ? !0 : !1;
        },
        Ty = function () {
            var a = ey(fy);
            return a && by(a, "disableOnScreenDetection") ? !1 : !On();
        },
        Qy = function () {
            return Pn() || (G(Ny), !1);
        };
    var Uy = function (a) {
        M.call(this);
        this.sessionId = a || "goog_" + Oe++;
        this.l = [];
        this.j = !1;
    };
    r(Uy, M);
    Uy.prototype.connect = function () {
        for (this.j = !0; 0 != this.l.length; ) {
            var a = this.l.shift();
            this.sendMessage(a.name, a.type, a.data);
        }
    };
    var Vy = function (a, b, c, d) {
        a.j ? a.sendMessage(b, c, d) : a.l.push({ name: b, type: c, data: d });
    };
    Uy.prototype.sendMessage = function () {};
    var Wy = function (a, b, c, d, e) {
        e = void 0 === e ? "" : e;
        Aj.call(this, a);
        this.ha = b;
        this.ka = c;
        this.Mb = d;
        this.Nd = e;
    };
    r(Wy, Aj);
    Wy.prototype.toString = function () {
        return "";
    };
    var Xy = function () {
            this.allowCustom = !0;
            this.creativeType = this.resourceType = "All";
            this.sizeCriteria = "SelectExactMatch";
            this.nearMatchPercent = 90;
            this.adSlotIds = [];
        },
        Yy = { IMAGE: "Image", FLASH: "Flash", ALL: "All" };
    u("module$contents$ima$CompanionAdSelectionSettings_CompanionAdSelectionSettings.CreativeType", Yy, void 0);
    var Zy = { HTML: "Html", IFRAME: "IFrame", STATIC: "Static", ALL: "All" };
    u("module$contents$ima$CompanionAdSelectionSettings_CompanionAdSelectionSettings.ResourceType", Zy, void 0);
    var $y = { IGNORE: "IgnoreSize", SELECT_EXACT_MATCH: "SelectExactMatch", SELECT_NEAR_MATCH: "SelectNearMatch", SELECT_FLUID: "SelectFluid" };
    u("module$contents$ima$CompanionAdSelectionSettings_CompanionAdSelectionSettings.SizeCriteria", $y, void 0);
    var bz = function (a, b) {
            b = void 0 === b ? new Xy() : b;
            this.j = a;
            this.h = b ? b : new Xy();
            this.B = az(Zy, this.h.resourceType) ? this.h.resourceType : "All";
            this.l = az(Yy, this.h.creativeType) ? this.h.creativeType : "All";
            this.C = az($y, this.h.sizeCriteria) ? this.h.sizeCriteria : "SelectExactMatch";
            this.o = null != this.h.adSlotIds ? this.h.adSlotIds : [];
            this.A = "number" === typeof this.h.nearMatchPercent && 0 < this.h.nearMatchPercent && 100 >= this.h.nearMatchPercent ? this.h.nearMatchPercent : 90;
        },
        ez = function (a, b) {
            var c = [];
            b.forEach(function (d) {
                a.h.allowCustom &&
                    (!lb(d.getContent()) && (isNaN(d.h.sequenceNumber) || isNaN(d.h.mainAdSequenceNumber) || d.h.mainAdSequenceNumber == d.h.sequenceNumber) && cz(a, d)
                        ? c.push(d)
                        : ((d = dz(a, d)), null != d && !lb(d.getContent()) && c.push(d)));
            });
            return c;
        },
        cz = function (a, b) {
            var c;
            if ((c = "Flash" != b.getContentType())) {
                if ((c = "All" == a.B || a.B == b.h.resourceType)) (c = b.getContentType()), (c = null == c ? !0 : "All" == a.l || a.l == c);
                c && ((c = b.Ed()), (c = 0 == a.o.length ? !0 : null != c ? a.o.includes(c) : !1));
            }
            if (c)
                if (((c = b.h.size), (b = !!b.h.fluidSize) || a.j.Dd)) a = b && a.j.Dd;
                else if (((b = "IgnoreSize" == a.C) || ((b = a.j.size), (b = b == c ? !0 : b && c ? b.width == c.width && b.height == c.height : !1)), b)) a = !0;
                else {
                    if ((b = "SelectNearMatch" == a.C)) (b = c.width), (c = c.height), (b = b > a.j.size.width || c > a.j.size.height || b < (a.A / 100) * a.j.size.width || c < (a.A / 100) * a.j.size.height ? !1 : !0);
                    a = b;
                }
            else a = !1;
            return a;
        },
        dz = function (a, b) {
            b = fz(b);
            return null == b
                ? null
                : b.find(function (c) {
                      return cz(a, c);
                  }) || null;
        },
        az = function (a, b) {
            return null != b && Gd(a, b);
        };
    var X = {},
        gz =
            ((X.creativeView = "creativeview"),
            (X.start = "start"),
            (X.midpoint = "midpoint"),
            (X.firstQuartile = "firstquartile"),
            (X.thirdQuartile = "thirdquartile"),
            (X.complete = "complete"),
            (X.mute = "mute"),
            (X.unmute = "unmute"),
            (X.pause = "pause"),
            (X.rewind = "rewind"),
            (X.resume = "resume"),
            (X.fullscreen = "fullscreen"),
            (X.exitFullscreen = "exitfullscreen"),
            (X.expand = "expand"),
            (X.collapse = "collapse"),
            (X.close = "close"),
            (X.acceptInvitation = "acceptinvitation"),
            (X.userInteraction = "userInteraction"),
            (X.adCanPlay = "adCanPlay"),
            (X.adStarted = "adStarted"),
            (X.abandon = "abandon"),
            (X.acceptInvitationLinear = "acceptinvitationlinear"),
            (X.engagedView = "engagedview"),
            (X.instreamAdComplete = "instreamAdComplete"),
            (X.skipShown = "skipshown"),
            (X.skippableStateChanged = "skippableStateChanged"),
            (X.skip = "skip"),
            (X.progress = "progress"),
            (X.publisher_invoked_skip = "PUBLISHER_INVOKED_SKIP"),
            (X.annotation_start = "annotation_start"),
            (X.annotation_click = "annotation_click"),
            (X.annotation_close = "annotation_close"),
            (X.cta_annotation_shown = "cta_annotation_shown"),
            (X.cta_annotation_clicked = "cta_annotation_clicked"),
            (X.cta_annotation_closed = "cta_annotation_closed"),
            (X.replay = "replay"),
            (X.stop = "stop"),
            (X.autoplayDisallowed = "autoplayDisallowed"),
            (X.error = "error"),
            (X.mediaLoadTimeout = "mediaLoadTimeout"),
            (X.linearChanged = "linearChanged"),
            (X.click = "click"),
            (X.contentPauseRequested = "contentPauseRequested"),
            (X.contentResumeRequested = "contentResumeRequested"),
            (X.discardAdBreak = "discardAdBreak"),
            (X.updateAdsRenderingSettings = "updateAdsRenderingSettings"),
            (X.durationChange = "durationChange"),
            (X.expandedChanged = "expandedChanged"),
            (X.autoClose = "autoClose"),
            (X.userClose = "userClose"),
            (X.userRecall = "userRecall"),
            (X.prefetched = "prefetched"),
            (X.loaded = "loaded"),
            (X.init = "init"),
            (X.allAdsCompleted = "allAdsCompleted"),
            (X.adMetadata = "adMetadata"),
            (X.adBreakReady = "adBreakReady"),
            (X.adBreakFetchError = "adBreakFetchError"),
            (X.log = "log"),
            (X.volumeChange = "volumeChange"),
            (X.companionBackfill = "companionBackfill"),
            (X.companionInitialized = "companionInitialized"),
            (X.companionImpression = "companionImpression"),
            (X.companionClick = "companionClick"),
            (X.impression = "impression"),
            (X.interaction = "interaction"),
            (X.adProgress = "adProgress"),
            (X.adBuffering = "adBuffering"),
            (X.trackingUrlPinged = "trackingUrlPinged"),
            (X.measurable_impression = "measurable_impression"),
            (X.custom_metric_viewable = "custom_metric_viewable"),
            (X.viewable_impression = "viewable_impression"),
            (X.fully_viewable_audible_half_duration_impression = "fully_viewable_audible_half_duration_impression"),
            (X.overlay_resize = "overlay_resize"),
            (X.overlay_unmeasurable_impression = "overlay_unmeasurable_impression"),
            (X.overlay_unviewable_impression = "overlay_unviewable_impression"),
            (X.overlay_viewable_immediate_impression = "overlay_viewable_immediate_impression"),
            (X.overlay_viewable_end_of_session_impression = "overlay_viewable_end_of_session_impression"),
            (X.externalActivityEvent = "externalActivityEvent"),
            (X.adEvent = "adEvent"),
            (X.configure = "configure"),
            (X.remainingTime = "remainingTime"),
            (X.destroy = "destroy"),
            (X.resize = "resize"),
            (X.volume = "volume"),
            (X.authorIconClicked = "videoAuthorIconClicked"),
            (X.authorNameClicked = "videoAuthorClicked"),
            (X.videoClicked = "videoClicked"),
            (X.videoIconClicked = "videoIconClicked"),
            (X.learnMoreClicked = "videoLearnMoreClicked"),
            (X.muteClicked = "videoMuteClicked"),
            (X.titleClicked = "videoTitleClicked"),
            (X.skipShown = "SKIP_SHOWN"),
            (X.videoSkipClicked = "SKIPPED"),
            (X.unmuteClicked = "videoUnmuteClicked"),
            (X.vpaidEvent = "vpaidEvent"),
            (X.show_ad = "show_ad"),
            (X.video_card_endcap_collapse = "video_card_endcap_collapse"),
            (X.video_card_endcap_dismiss = "video_card_endcap_dismiss"),
            (X.video_card_endcap_impression = "video_card_endcap_impression"),
            (X.mediaUrlPinged = "mediaUrlPinged"),
            (X.breakStart = "breakstart"),
            (X.breakEnd = "breakend"),
            (X.omidReady = "omidReady"),
            (X.omidUnavailable = "omidUnavailable"),
            (X.omidAdSessionCompleted = "omidAdSessionCompleted"),
            (X.omidAdSessionAbandoned = "omidAdSessionAbandoned"),
            (X.verificationNotExecuted = "verificationNotExecuted"),
            (X.loadStart = "loadStart"),
            (X.seeked = "seeked"),
            (X.seeking = "seeking"),
            X);
    var hz = {
        qf: function (a, b) {
            a && (At(a) ? Bt(a, b) : It(a, b));
        },
    };
    function iz(a) {
        a = vu(a, On() ? "https" : window.location.protocol);
        if (On()) jz(a);
        else
            try {
                hz.qf(a, !0);
            } catch (b) {}
    }
    function jz(a) {
        new Nu().get({ url: a, timeout: new qu() }).then(
            function () {},
            function () {}
        );
    }
    var kz = function (a, b, c) {
        var d = Error.call(this);
        this.message = d.message;
        "stack" in d && (this.stack = d.stack);
        this.l = b;
        this.h = c;
        this.o = a;
    };
    r(kz, Error);
    l = kz.prototype;
    l.getAd = function () {
        return this.A;
    };
    l.getInnerError = function () {
        return this.j;
    };
    l.getMessage = function () {
        return this.l;
    };
    l.getErrorCode = function () {
        return this.h;
    };
    l.Id = function () {
        return 1e3 > this.h ? this.h : 900;
    };
    l.getType = function () {
        return this.o;
    };
    l.toString = function () {
        return "AdError " + this.getErrorCode() + ": " + this.getMessage() + (null != this.getInnerError() ? " Caused by: " + this.getInnerError() : "");
    };
    l.aa = function () {
        for (var a = {}, b = (a = ((a.type = this.getType()), (a.errorCode = this.getErrorCode()), (a.errorMessage = this.getMessage()), a)), c = this.getInnerError(), d = 0; 3 > d; ++d)
            if (c instanceof kz) {
                var e = {};
                e = ((e.type = c.getType()), (e.errorCode = c.getErrorCode()), (e.errorMessage = c.getMessage()), e);
                b = b.innerError = e;
                c = c.getInnerError();
            } else {
                null != c && (b.innerError = String(c));
                break;
            }
        return a;
    };
    var lz = function (a, b) {
        this.message = a;
        this.errorCode = b;
    };
    lz.prototype.getErrorCode = function () {
        return this.errorCode;
    };
    lz.prototype.getMessage = function () {
        return this.message;
    };
    var mz = new lz("Failed to initialize ad playback element before starting ad playback.", 400),
        nz = new lz("The provided {0} information: {1} is invalid.", 1101);
    function oz(a, b, c) {
        var d = void 0 === b ? null : b;
        if (!(d instanceof kz)) {
            var e = a.errorCode,
                f = a.message,
                g = Array.prototype.slice.call(arguments, 2);
            if (0 < g.length) for (var h = 0; h < g.length; h++) f = f.replace(new RegExp("\\{" + h + "\\}", "ig"), g[h]);
            e = new kz("adPlayError", f, e);
            e.j = d;
            d = e;
        }
        return d;
    }
    function pz(a, b, c, d) {
        c = void 0 === c ? null : c;
        d = void 0 === d ? {} : d;
        if (Math.random() < kh(od)) {
            var e = {};
            Uf(Object.assign({}, ((e.c = String(a)), (e.pc = String(Gf())), (e.em = c), (e.lid = b), (e.eids = G($l).h().join()), e), d), "esp");
        }
    }
    var qz = function () {
            this.cache = {};
        },
        uz = function () {
            rz || ((sz = kh(ld)), (tz = kh(kd)), (rz = new qz()));
            return rz;
        },
        vz = function (a) {
            var b = D(a, 3);
            if (!b) return 3;
            if (void 0 === D(a, 2)) return 4;
            a = Date.now();
            return a > b + 36e5 * tz ? 2 : a > b + 36e5 * sz ? 1 : 0;
        };
    qz.prototype.get = function (a, b) {
        if (this.cache[a]) return { rb: this.cache[a], success: !0 };
        var c = "";
        try {
            c = b.getItem("_GESPSK-" + a);
        } catch (g) {
            var d;
            pz(6, a, null == (d = g) ? void 0 : d.message);
            return { rb: null, success: !1 };
        }
        if (!c) return { rb: null, success: !0 };
        try {
            var e = Eg(ul, c);
            this.cache[a] = e;
            return { rb: e, success: !0 };
        } catch (g) {
            var f;
            pz(5, a, null == (f = g) ? void 0 : f.message);
            return { rb: null, success: !1 };
        }
    };
    qz.prototype.set = function (a, b) {
        var c = D(a, 1);
        try {
            b.setItem("_GESPSK-" + c, a.aa());
        } catch (e) {
            var d;
            pz(7, c, null == (d = e) ? void 0 : d.message);
            return !1;
        }
        this.cache[c] = a;
        return !0;
    };
    var rz = null,
        sz = 24,
        tz = 72;
    function wz(a) {
        var b = void 0 === b ? null : b;
        if (!a) return null;
        var c = new rl(),
            d = kh(nd),
            e = kh(md),
            f = [],
            g = RegExp("^_GESPSK-(.+)$");
        try {
            for (var h = 0; h < a.length; h++) {
                var k = (g.exec(a.key(h)) || [])[1];
                k && f.push(k);
            }
        } catch (m) {}
        f = q(f);
        for (g = f.next(); !g.done; g = f.next())
            if (((g = g.value), (h = uz().get(g, a).rb)))
                if (((k = vz(h)), 0 === k || 1 === k)) {
                    var n = !1;
                    if (b && (k = /^pub(\d+)$/.exec(g)) && !(n = b.split(",").includes(k[1]))) continue;
                    k = D(h, 2);
                    n = n ? e : d;
                    0 <= n && k && k.length > n ? ((h = {}), pz(12, g, null, ((h.sl = String(k.length)), h))) : (yg(c, 2, ul, h, void 0), pz(19, g));
                }
        return vg(c, ul, 2).length ? Dc(Sg(c, wl), 3) : null;
    }
    var xz = function () {
        L.apply(this, arguments);
        this.h = [];
        this.dependencies = [];
        this.j = [];
    };
    r(xz, L);
    xz.prototype.N = function () {
        this.h.length = 0;
        this.j.length = 0;
        this.dependencies.length = 0;
        L.prototype.N.call(this);
    };
    var yz = function () {
        var a = this;
        this.promise = new Promise(function (b, c) {
            a.resolve = b;
            a.reject = c;
        });
    };
    var zz = function (a) {
        a = Error.call(this, a);
        this.message = a.message;
        "stack" in a && (this.stack = a.stack);
        Object.setPrototypeOf(this, zz.prototype);
        this.name = "InputError";
    };
    r(zz, Error);
    var Az = function () {
            var a = this;
            this.C = this.o = null;
            this.A = -1;
            this.j = new yz();
            this.h = !1;
            this.j.promise.then(
                function () {
                    -1 !== a.A && (a.C = Ah() - a.A);
                },
                function () {}
            );
        },
        Bz = function () {
            Az.apply(this, arguments);
        };
    r(Bz, Az);
    da.Object.defineProperties(Bz.prototype, {
        promise: {
            configurable: !0,
            enumerable: !0,
            get: function () {
                return this.j.promise;
            },
        },
        resolved: {
            configurable: !0,
            enumerable: !0,
            get: function () {
                return this.h;
            },
        },
    });
    var Cz = function () {
        Bz.apply(this, arguments);
    };
    r(Cz, Bz);
    var Dz = function (a, b) {
            a.h || ((a.h = !0), (a.o = b), a.j.resolve(b));
        },
        Ez = function (a) {
            a.h || ((a.h = !0), (a.o = null), a.j.resolve(null));
        },
        Fz = function (a) {
            Az.call(this);
            this.l = a;
        };
    r(Fz, Az);
    da.Object.defineProperties(Fz.prototype, {
        error: {
            configurable: !0,
            enumerable: !0,
            get: function () {
                return this.l.B;
            },
        },
    });
    var Gz = function (a) {
        Fz.call(this, a);
        this.l = a;
    };
    r(Gz, Fz);
    da.Object.defineProperties(Gz.prototype, {
        value: {
            configurable: !0,
            enumerable: !0,
            get: function () {
                return this.l.o;
            },
        },
    });
    function Hz(a, b) {
        var c, d, e;
        return Ga(function (f) {
            if (1 == f.h)
                return (
                    (c =
                        0 < b
                            ? a.filter(function (g) {
                                  return !g.sd;
                              })
                            : a),
                    ya(
                        f,
                        Promise.all(
                            c.map(function (g) {
                                return g.dependency.promise;
                            })
                        ),
                        2
                    )
                );
            if (3 != f.h) {
                if (a.length === c.length) return f.return(0);
                d = a.filter(function (g) {
                    return g.sd;
                });
                e = Ah();
                return ya(
                    f,
                    Promise.race([
                        Promise.all(
                            d.map(function (g) {
                                return g.dependency.promise;
                            })
                        ),
                        new Promise(function (g) {
                            return void setTimeout(g, b);
                        }),
                    ]),
                    3
                );
            }
            return f.return(Ah() - e);
        });
    }
    var Iz = function (a, b) {
        b = void 0 === b ? 0 : b;
        L.call(this);
        this.id = a;
        this.timeoutMs = b;
        this.h = new xz();
        this.started = !1;
        this.D = -1;
        zj(this, this.h);
    };
    r(Iz, L);
    Iz.prototype.start = function () {
        var a = this,
            b,
            c,
            d,
            e,
            f;
        return Ga(function (g) {
            switch (g.h) {
                case 1:
                    if (a.started) return g.return();
                    a.started = !0;
                    g.j = 2;
                    b = a;
                    return ya(g, Hz(a.h.dependencies, a.timeoutMs), 4);
                case 4:
                    b.D = g.B;
                    if (a.Ia()) {
                        g.h = 5;
                        break;
                    }
                    for (var h = 0, k = q(a.h.j), n = k.next(); !n.done; n = k.next()) {
                        if (null === n.value.l.o) throw Error("missing input: " + a.id + "/" + h);
                        ++h;
                    }
                    c = q(a.h.h);
                    for (d = c.next(); !d.done; d = c.next()) (e = d.value), (e.A = Ah());
                    return ya(g, a.A(), 5);
                case 5:
                    g.h = 0;
                    g.j = 0;
                    break;
                case 2:
                    f = za(g);
                    if (a.Ia()) return g.return();
                    if (!(f instanceof zz) && f instanceof Error && (a.C(a.id, f), a.h.h.length))
                        for (h = new zz(f.message), k = q(a.h.h), n = k.next(); !n.done; n = k.next())
                            if (((n = n.value), !n.resolved)) {
                                var m = h;
                                n.h = !0;
                                n.B = m;
                                n.j.reject(m);
                            }
                    g.h = 0;
            }
        });
    };
    var Jz = function (a) {
            var b = new Cz();
            a.h.h.push(b);
            return b;
        },
        Kz = function (a, b) {
            a.h.dependencies.push({ sd: !1, dependency: b });
            return new Gz(b);
        };
    var Lz = function (a, b) {
        Iz.call(this, a);
        this.id = a;
        this.C = b;
    };
    r(Lz, Iz);
    var Mz = function (a, b, c, d) {
        Lz.call(this, 655, d);
        this.Qa = a;
        this.collectorFunction = b;
        this.storage = c;
        this.l = Jz(this);
        this.o = Jz(this);
        this.j = kh(jd);
    };
    r(Mz, Lz);
    Mz.prototype.A = function () {
        var a = uz().get(this.Qa, this.storage).rb,
            b = Date.now();
        if (a)
            if ((this.j && (null == D(a, 8) && (pz(33, this.Qa), E(a, 8, this.j)), null == D(a, 7) && (pz(34, this.Qa), E(a, 7, Math.round(Date.now() / 1e3 / 60)))), null != D(a, 3) || pz(35, this.Qa), this.j)) {
                var c = ng(a, 8),
                    d,
                    e = null != (d = D(a, 7)) ? d : b;
                c < this.j && E(a, 8, Math.min(c + Number(((this.j * (b / 1e3 / 60 - e)) / 60).toFixed(3)), this.j));
                1 > ng(a, 8) ? ((b = {}), pz(22, this.Qa, null, ((b.t = String(e)), (b.cr = String(c)), (b.cs = String(vz(a))), b)), Ez(this.l), Ez(this.o)) : (Dz(this.l, this.collectorFunction), Dz(this.o, a));
            } else Dz(this.l, this.collectorFunction), Dz(this.o, a);
        else Dz(this.l, this.collectorFunction), (a = this.o), (c = new ul()), (c = E(c, 1, this.Qa)), (c = E(c, 8, this.j)), (b = E(c, 3, b)), Dz(a, b);
    };
    function Nz(a, b, c, d) {
        pz(18, a);
        try {
            var e = Ah();
            kh(jd) && (E(b, 8, Number((ng(b, 8) - 1).toFixed(3))), E(b, 7, Math.round(e / 1e3 / 60)));
            return c()
                .then(function (f) {
                    pz(29, a, null, { delta: String(Ah() - e) });
                    E(b, 3, Date.now());
                    Oz(a, b, f, d);
                    return b;
                })
                .catch(function (f) {
                    Oz(a, b, D(b, 2), d);
                    pz(28, a, Pz(f));
                    return b;
                });
        } catch (f) {
            return Oz(a, b, D(b, 2), d), pz(1, a, Pz(f)), Promise.resolve(b);
        }
    }
    var Oz = function (a, b, c, d) {
            "string" !== typeof c ? pz(21, a) : c || pz(20, a);
            E(b, 2, c);
            uz().set(b, d) && pz(27, a);
        },
        Pz = function (a) {
            return "string" === typeof a ? a : a instanceof Error ? a.message : null;
        };
    var Qz = function (a, b, c, d) {
        Lz.call(this, 658, d);
        this.storage = c;
        this.j = Jz(this);
        this.l = Jz(this);
        this.o = Jz(this);
        this.B = Kz(this, a);
        this.G = Kz(this, b);
    };
    r(Qz, Lz);
    Qz.prototype.A = function () {
        var a = this;
        if (this.B.value) {
            var b = function (g) {
                    Dz(a.j, { id: D(g, 1), collectorGeneratedData: D(g, 2) });
                },
                c = this.B.value,
                d = this.G.value,
                e = D(d, 1),
                f = vz(d);
            switch (f) {
                case 0:
                    pz(24, e);
                    break;
                case 1:
                    pz(25, e);
                    break;
                case 2:
                    pz(26, e);
                    break;
                case 3:
                    pz(9, e);
                    break;
                case 4:
                    pz(23, e);
            }
            switch (f) {
                case 0:
                    b(d);
                    Rz(this);
                    break;
                case 1:
                    b(d);
                    Dz(this.l, c);
                    Dz(this.o, d);
                    break;
                case 3:
                case 2:
                case 4:
                    E(d, 2, null), Nz(e, d, c, this.storage).then(b), Rz(this);
            }
        } else Ez(this.j), Rz(this);
    };
    var Rz = function (a) {
        Ez(a.l);
        Ez(a.o);
    };
    function Sz() {
        var a = window;
        var b = void 0 === b ? function () {} : b;
        return new Promise(function (c) {
            var d = function () {
                c(b());
                Ee(a, "load", d);
            };
            De(a, "load", d);
        });
    }
    var Tz = function (a, b, c, d) {
        Lz.call(this, 662, d);
        this.storage = c;
        this.j = Kz(this, a);
        this.l = Kz(this, b);
    };
    r(Tz, Lz);
    Tz.prototype.A = function () {
        var a = this;
        this.l.value &&
            this.j.value &&
            Sz().then(function () {
                var b = a.l.value;
                Nz(D(b, 1), b, a.j.value, a.storage);
            });
    };
    var Uz = function () {
        L.apply(this, arguments);
        this.nodes = [];
    };
    r(Uz, L);
    Uz.prototype.empty = function () {
        return !this.nodes.length;
    };
    Uz.prototype.N = function () {
        L.prototype.N.call(this);
        this.nodes.length = 0;
    };
    function Vz(a, b, c, d) {
        var e, f, g, h, k;
        return Ga(function (n) {
            if (1 == n.h) {
                e = new Mz(a, b, c, d);
                f = new Qz(e.l, e.o, c, d);
                g = new Tz(f.l, f.o, c, d);
                h = new Uz();
                for (var m = q([e, f, g]), x = m.next(); !x.done; x = m.next()) (x = x.value), zj(h, x), h.nodes.push(x);
                if (!h.empty()) for (m = q(h.nodes), x = m.next(); !x.done; x = m.next()) x.value.start();
                return ya(n, f.j.promise, 2);
            }
            k = n.B;
            return n.return(k ? k : { id: a, collectorGeneratedData: null });
        });
    }
    var Wz = function (a, b) {
        this.storage = b;
        this.l = [];
        this.j = [];
        this.h = [];
        a = q(a);
        for (b = a.next(); !b.done; b = a.next()) this.push(b.value);
    };
    Wz.prototype.push = function (a) {
        var b = a.id;
        a = a.collectorFunction;
        if ("string" !== typeof b) pz(37, "invalid-id");
        else if ("function" !== typeof a) pz(14, b);
        else {
            b = Vz(b, a, this.storage, this.o);
            this.l.push(b);
            a = q(this.j);
            for (var c = a.next(); !c.done; c = a.next()) b.then(c.value);
        }
    };
    Wz.prototype.addOnSignalResolveCallback = function (a) {
        this.j.push(a);
        for (var b = q(this.l), c = b.next(); !c.done; c = b.next()) c.value.then(a);
    };
    Wz.prototype.addErrorHandler = function (a) {
        this.h.push(a);
    };
    Wz.prototype.o = function (a, b) {
        for (var c = q(this.h), d = c.next(); !d.done; d = c.next()) (d = d.value), d(a, b);
    };
    var Xz = function (a) {
        this.push = function (b) {
            a.push(b);
        };
        this.addOnSignalResolveCallback = function (b) {
            a.addOnSignalResolveCallback(b);
        };
        this.addErrorHandler = function (b) {
            a.addErrorHandler(b);
        };
    };
    function Yz(a, b, c, d) {
        if (b) {
            var e = vf();
            var f = window;
            f = sf(f.top) ? f.top : null;
            if (e === f || jh(id))
                if (a.encryptedSignalProviders instanceof Xz) d && a.encryptedSignalProviders.addOnSignalResolveCallback(d), a.encryptedSignalProviders.addErrorHandler(c);
                else {
                    var g;
                    e = new Wz(null != (g = a.encryptedSignalProviders) ? g : [], b);
                    a.encryptedSignalProviders = new Xz(e);
                    d && e.addOnSignalResolveCallback(d);
                    e.addErrorHandler(c);
                }
            else pz(16, "");
        } else pz(15, "");
    }
    function Zz(a, b, c, d) {
        var e = new Map();
        b = b.map(function (f) {
            var g = f.Qa;
            return new Promise(function (h) {
                e.set(g, h);
            });
        });
        Yz(a, c, d, function (f) {
            var g = f.collectorGeneratedData;
            f = f.id;
            var h;
            return void (null == (h = e.get(f)) ? void 0 : h({ collectorGeneratedData: g, id: f }));
        });
        return b;
    }
    function $z() {
        var a;
        return null != (a = t.googletag) ? a : (t.googletag = { cmd: [] });
    }
    function aA(a) {
        if (!a || uy(a)) return null;
        try {
            return window.localStorage;
        } catch (b) {
            return null;
        }
    }
    function bA(a, b) {
        a = aA(a);
        Yz($z(), a, function () {}, b);
    }
    function cA(a, b) {
        return (b = aA(b)) && 0 != a.length ? Zz($z(), a, b, function () {}) : null;
    } /*


 Copyright Mathias Bynens <http://mathiasbynens.be/>

 Permission is hereby granted, free of charge, to any person obtaining
 a copy of this software and associated documentation files (the
 "Software"), to deal in the Software without restriction, including
 without limitation the rights to use, copy, modify, merge, publish,
 distribute, sublicense, and/or sell copies of the Software, and to
 permit persons to whom the Software is furnished to do so, subject to
 the following conditions:

 The above copyright notice and this permission notice shall be
 included in all copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/
    var dA = function (a, b) {
        return 0 == a.indexOf(b) ? a.substr(b.length) : null;
    };
    var eA = function () {
        M.call(this);
        this.G = !1;
        this.h = null;
        this.B = this.D = this.L = !1;
        this.j = 0;
        this.A = [];
        this.C = !1;
        this.R = this.M = Infinity;
        this.l = 0;
        this.K = new Iu(this);
        zj(this, this.K);
        this.I = {};
    };
    r(eA, M);
    var gA = function (a, b) {
            null == b || a.G || ((a.h = b), fA(a), (a.G = !0));
        },
        iA = function (a) {
            null != a.h && a.G && (hA(a), (a.G = !1), (a.D = !1), (a.B = !1), (a.j = 0), (a.A = []), (a.C = !1));
        },
        fA = function (a) {
            hA(a);
            !(a.h instanceof M) && "ontouchstart" in document.documentElement && mc
                ? ((a.I = {
                      touchstart: function (b) {
                          a.D = !0;
                          a.j = b.touches.length;
                          a.l && (window.clearTimeout(a.l), (a.l = 0), (a.L = !0));
                          a.C = jA(a, b.touches) || 1 != b.touches.length;
                          a.C ? ((a.M = Infinity), (a.R = Infinity)) : ((a.M = b.touches[0].clientX), (a.R = b.touches[0].clientY));
                          b = b.touches;
                          a.A = [];
                          for (var c = 0; c < b.length; c++) a.A.push(b[c].identifier);
                      },
                      touchmove: function (b) {
                          a.j = b.touches.length;
                          if (!Bu(8) || Math.pow(b.changedTouches[0].clientX - a.M, 2) + Math.pow(b.changedTouches[0].clientY - a.R, 2) > Math.pow(5, 2)) a.B = !0;
                      },
                      touchend: function (b) {
                          return void kA(a, b);
                      },
                  }),
                  wd(a.I, function (b, c) {
                      a.h.addEventListener(c, b, !1);
                  }))
                : a.K.P(a.h, "click", a.T);
        },
        hA = function (a) {
            a.K.Wa(a.h, "click", a.T);
            wd(
                a.I,
                function (b, c) {
                    this.h.removeEventListener(c, b, !1);
                },
                a
            );
            a.I = {};
        },
        kA = function (a, b) {
            !a.D ||
                1 != a.j ||
                a.B ||
                a.L ||
                a.C ||
                !jA(a, b.changedTouches) ||
                (a.l = window.setTimeout(function () {
                    return void lA(a);
                }, 300));
            a.j = b.touches.length;
            0 == a.j && ((a.D = !1), (a.B = !1), (a.A = []));
            a.L = !1;
        };
    eA.prototype.T = function () {
        lA(this);
    };
    var jA = function (a, b) {
            for (var c = 0; c < b.length; c++) if (a.A.includes(b[c].identifier)) return !0;
            return !1;
        },
        lA = function (a) {
            a.l = 0;
            a.dispatchEvent(new Aj("click"));
        };
    eA.prototype.N = function () {
        iA(this);
        M.prototype.N.call(this);
    };
    var mA = function (a, b, c) {
        this.j = c;
        0 == b.length && (b = [[]]);
        this.h = b.map(function (d) {
            d = a.concat(d);
            for (var e = [], f = 0, g = 0; f < d.length; ) {
                var h = d[f++];
                if (128 > h) e[g++] = String.fromCharCode(h);
                else if (191 < h && 224 > h) {
                    var k = d[f++];
                    e[g++] = String.fromCharCode(((h & 31) << 6) | (k & 63));
                } else if (239 < h && 365 > h) {
                    k = d[f++];
                    var n = d[f++],
                        m = d[f++];
                    h = (((h & 7) << 18) | ((k & 63) << 12) | ((n & 63) << 6) | (m & 63)) - 65536;
                    e[g++] = String.fromCharCode(55296 + (h >> 10));
                    e[g++] = String.fromCharCode(56320 + (h & 1023));
                } else (k = d[f++]), (n = d[f++]), (e[g++] = String.fromCharCode(((h & 15) << 12) | ((k & 63) << 6) | (n & 63)));
            }
            return new RegExp(e.join(""));
        });
    };
    mA.prototype.match = function (a) {
        var b = this;
        return this.h.some(function (c) {
            c = a.match(c);
            return null == c ? !1 : !b.j || (1 <= c.length && "3.507.1" == c[1]) || (2 <= c.length && "3.507.1" == c[2]) ? !0 : !1;
        });
    };
    var nA = [
            104,
            116,
            116,
            112,
            115,
            63,
            58,
            47,
            47,
            105,
            109,
            97,
            115,
            100,
            107,
            92,
            46,
            103,
            111,
            111,
            103,
            108,
            101,
            97,
            112,
            105,
            115,
            92,
            46,
            99,
            111,
            109,
            47,
            106,
            115,
            47,
            40,
            115,
            100,
            107,
            108,
            111,
            97,
            100,
            101,
            114,
            124,
            99,
            111,
            114,
            101,
            41,
            47,
        ],
        oA = [104, 116, 116, 112, 115, 63, 58, 47, 47, 115, 48, 92, 46, 50, 109, 100, 110, 92, 46, 110, 101, 116, 47, 105, 110, 115, 116, 114, 101, 97, 109, 47, 104, 116, 109, 108, 53, 47],
        pA = [
            104,
            116,
            116,
            112,
            115,
            63,
            58,
            47,
            47,
            105,
            109,
            97,
            115,
            100,
            107,
            92,
            46,
            103,
            111,
            111,
            103,
            108,
            101,
            97,
            112,
            105,
            115,
            92,
            46,
            99,
            111,
            109,
            47,
            112,
            114,
            101,
            114,
            101,
            108,
            101,
            97,
            115,
            101,
            47,
            106,
            115,
            47,
            91,
            48,
            45,
            57,
            93,
            43,
            92,
            46,
            91,
            48,
            45,
            57,
            92,
            46,
            93,
            43,
            47,
        ],
        qA = [
            [105, 109, 97, 51, 92, 46, 106, 115],
            [105, 109, 97, 51, 95, 100, 101, 98, 117, 103, 92, 46, 106, 115],
            [105, 109, 97, 51, 95, 101, 97, 112, 46, 106, 115],
        ],
        rA = [
            [98, 114, 105, 100, 103, 101, 40, 91, 48, 45, 57, 93, 43, 92, 46, 91, 48, 45, 57, 92, 46, 93, 43, 41, 40, 95, 40, 91, 97, 45, 122, 48, 45, 57, 93, 41, 123, 50, 44, 51, 125, 41, 123, 48, 44, 50, 125, 92, 46, 104, 116, 109, 108],
            [
                98,
                114,
                105,
                100,
                103,
                101,
                40,
                91,
                48,
                45,
                57,
                93,
                43,
                92,
                46,
                91,
                48,
                45,
                57,
                92,
                46,
                93,
                43,
                41,
                95,
                100,
                101,
                98,
                117,
                103,
                40,
                95,
                40,
                91,
                97,
                45,
                122,
                48,
                45,
                57,
                93,
                41,
                123,
                50,
                44,
                51,
                125,
                41,
                123,
                48,
                44,
                50,
                125,
                92,
                46,
                104,
                116,
                109,
                108,
            ],
            [98, 114, 105, 100, 103, 101, 40, 95, 40, 91, 97, 45, 122, 48, 45, 57, 93, 41, 123, 50, 44, 51, 125, 41, 123, 48, 44, 50, 125, 92, 46, 104, 116, 109, 108],
        ],
        sA = [
            [111, 117, 116, 115, 116, 114, 101, 97, 109, 92, 46, 106, 115],
            [111, 117, 116, 115, 116, 114, 101, 97, 109, 95, 100, 101, 98, 117, 103, 92, 46, 106, 115],
        ],
        tA = new mA(nA, qA, !1),
        uA = new mA(nA, rA, !0),
        vA = new mA(oA, qA, !1),
        wA = new mA(oA, rA, !0),
        xA = new mA(pA, qA, !1),
        yA = new mA(
            [
                104,
                116,
                116,
                112,
                115,
                63,
                58,
                47,
                47,
                40,
                112,
                97,
                103,
                101,
                97,
                100,
                50,
                124,
                116,
                112,
                99,
                41,
                92,
                46,
                103,
                111,
                111,
                103,
                108,
                101,
                115,
                121,
                110,
                100,
                105,
                99,
                97,
                116,
                105,
                111,
                110,
                92,
                46,
                99,
                111,
                109,
                47,
                112,
                97,
                103,
                101,
                97,
                100,
                47,
                40,
                103,
                97,
                100,
                103,
                101,
                116,
                115,
                124,
                106,
                115,
                41,
                47,
            ],
            [],
            !1
        ),
        zA = new mA(
            nA,
            [
                [
                    100,
                    97,
                    105,
                    95,
                    105,
                    102,
                    114,
                    97,
                    109,
                    101,
                    40,
                    91,
                    48,
                    45,
                    57,
                    93,
                    43,
                    92,
                    46,
                    91,
                    48,
                    45,
                    57,
                    92,
                    46,
                    93,
                    43,
                    41,
                    40,
                    95,
                    40,
                    91,
                    97,
                    45,
                    122,
                    48,
                    45,
                    57,
                    93,
                    41,
                    123,
                    50,
                    44,
                    51,
                    125,
                    41,
                    123,
                    48,
                    44,
                    50,
                    125,
                    92,
                    46,
                    104,
                    116,
                    109,
                    108,
                ],
                [
                    100,
                    97,
                    105,
                    95,
                    105,
                    102,
                    114,
                    97,
                    109,
                    101,
                    40,
                    91,
                    48,
                    45,
                    57,
                    93,
                    43,
                    92,
                    46,
                    91,
                    48,
                    45,
                    57,
                    92,
                    46,
                    93,
                    43,
                    41,
                    95,
                    100,
                    101,
                    98,
                    117,
                    103,
                    40,
                    95,
                    40,
                    91,
                    97,
                    45,
                    122,
                    48,
                    45,
                    57,
                    93,
                    41,
                    123,
                    50,
                    44,
                    51,
                    125,
                    41,
                    123,
                    48,
                    44,
                    50,
                    125,
                    92,
                    46,
                    104,
                    116,
                    109,
                    108,
                ],
                [100, 97, 105, 95, 105, 102, 114, 97, 109, 101, 40, 95, 40, 91, 97, 45, 122, 48, 45, 57, 93, 41, 123, 50, 44, 51, 125, 41, 123, 48, 44, 50, 125, 92, 46, 104, 116, 109, 108],
            ],
            !0
        ),
        AA = new mA(nA, sA, !1),
        BA = new mA(pA, sA, !1),
        zd = { og: tA, mg: uA, Ig: vA, Hg: wA, pg: xA, nh: yA, ng: zA, Sg: AA, Tg: BA };
    function CA(a) {
        for (var b = null, c = 0; c < a.length; c++)
            if (
                ((b = a[c]),
                yd(function (d) {
                    return d.match(b.src);
                }))
            )
                return b;
        return null;
    }
    var DA = function () {
            var a = z(),
                b = document;
            return new R(a.parent == a ? a.location.href : b.referrer);
        },
        EA = function (a, b) {
            pt(a, "url", "");
            try {
                var c = 2083 - a.toString().length - 1;
                if (0 >= c) return a.toString();
                for (var d = b.slice(0, c), e = encodeURIComponent(d), f = c; 0 < f && e.length > c; ) (d = b.slice(0, f--)), (e = encodeURIComponent(d));
                pt(a, "url", d);
            } catch (g) {}
            return a.toString();
        };
    var FA = function () {
        this.h = 0.01 > Math.random();
        this.j = Math.floor(4503599627370496 * Math.random());
    };
    FA.prototype.report = function (a, b, c) {
        b = void 0 === b ? {} : b;
        if (null == t.G_testRunner && (this.h || (void 0 === c ? 0 : c))) {
            b.lid = a;
            b.sdkv = Oy();
            a = si().sort().join(",");
            lb(Ne(a)) || (b.e = a);
            b = GA(this, b);
            var d = new R("http://pagead2.googlesyndication.com/pagead/gen_204");
            wd(
                b,
                function (e, f) {
                    pt(d, f, null == e ? "" : "boolean" == typeof e ? (e ? "t" : "f") : "" + e);
                },
                this
            );
            b = DA();
            ct(d, b.o);
            b = d.toString();
            a = d.l.get("url");
            null != a && zb() && 2083 < b.length && (b = EA(d, a));
            iz(b);
        }
    };
    var GA = function (a, b) {
        b.id = "ima_html5";
        var c = DA();
        b.c = a.j;
        b.domain = c.j;
        return b;
    };
    FA.prototype.isLoggingEnabled = function () {
        return this.h;
    };
    var HA = function () {
        return G(FA);
    };
    var IA = function (a) {
        this.h = a;
    };
    l = IA.prototype;
    l.getTotalAds = function () {
        return this.h.totalAds;
    };
    l.getMaxDuration = function () {
        return this.h.maxDuration;
    };
    l.getAdPosition = function () {
        return this.h.adPosition;
    };
    l.getPodIndex = function () {
        return this.h.podIndex;
    };
    l.getTimeOffset = function () {
        return this.h.timeOffset;
    };
    l.getIsBumper = function () {
        return this.h.isBumper;
    };
    IA.prototype.getIsBumper = IA.prototype.getIsBumper;
    IA.prototype.getTimeOffset = IA.prototype.getTimeOffset;
    IA.prototype.getPodIndex = IA.prototype.getPodIndex;
    IA.prototype.getAdPosition = IA.prototype.getAdPosition;
    IA.prototype.getMaxDuration = IA.prototype.getMaxDuration;
    IA.prototype.getTotalAds = IA.prototype.getTotalAds;
    var JA = function (a) {
        this.h = a;
    };
    l = JA.prototype;
    l.getContent = function () {
        return this.h.content;
    };
    l.getContentType = function () {
        return this.h.contentType;
    };
    l.getWidth = function () {
        return this.h.size.width;
    };
    l.getHeight = function () {
        return this.h.size.height;
    };
    l.Ed = function () {
        return this.h.adSlotId;
    };
    var fz = function (a) {
        return (a = a.h.backupCompanions)
            ? a.map(function (b) {
                  return new JA(b);
              })
            : [];
    };
    JA.prototype.getAdSlotId = JA.prototype.Ed;
    JA.prototype.getHeight = JA.prototype.getHeight;
    JA.prototype.getWidth = JA.prototype.getWidth;
    JA.prototype.getContentType = JA.prototype.getContentType;
    JA.prototype.getContent = JA.prototype.getContent;
    var KA = function (a, b) {
        this.j = a;
        this.h = b;
    };
    KA.prototype.getAdIdValue = function () {
        return this.j;
    };
    KA.prototype.getAdIdRegistry = function () {
        return this.h;
    };
    KA.prototype.getAdIdRegistry = KA.prototype.getAdIdRegistry;
    KA.prototype.getAdIdValue = KA.prototype.getAdIdValue;
    var Y = function (a) {
        this.h = a;
    };
    Y.prototype.getAdId = function () {
        return this.h.adId;
    };
    Y.prototype.getCreativeAdId = function () {
        return this.h.creativeAdId;
    };
    Y.prototype.getCreativeId = function () {
        return this.h.creativeId;
    };
    var LA = function (a) {
        return a.h.adQueryId;
    };
    l = Y.prototype;
    l.getAdSystem = function () {
        return this.h.adSystem;
    };
    l.getAdvertiserName = function () {
        return this.h.advertiserName;
    };
    l.getApiFramework = function () {
        return this.h.apiFramework;
    };
    l.getWrapperAdIds = function () {
        return this.h.adWrapperIds;
    };
    l.getWrapperCreativeIds = function () {
        return this.h.adWrapperCreativeIds;
    };
    l.getWrapperAdSystems = function () {
        return this.h.adWrapperSystems;
    };
    l.isLinear = function () {
        return this.h.linear;
    };
    l.isSkippable = function () {
        return this.h.skippable;
    };
    l.getContentType = function () {
        return this.h.contentType;
    };
    l.Ke = function () {
        return this.h.description;
    };
    l.Me = function () {
        return this.h.title;
    };
    l.getDuration = function () {
        return this.h.duration;
    };
    l.getVastMediaWidth = function () {
        return this.h.vastMediaWidth;
    };
    l.getVastMediaHeight = function () {
        return this.h.vastMediaHeight;
    };
    l.getWidth = function () {
        return this.h.width;
    };
    l.getHeight = function () {
        return this.h.height;
    };
    l.getUiElements = function () {
        return this.h.uiElements;
    };
    l.getMinSuggestedDuration = function () {
        return this.h.minSuggestedDuration;
    };
    l.getAdPodInfo = function () {
        return new IA(this.h.adPodInfo);
    };
    l.getCompanionAds = function (a, b, c) {
        if (!this.h.companions) return [];
        var d = this.h.companions.map(function (e) {
            return new JA(e);
        });
        return ez(new bz({ size: new y(a, b), Dd: c ? "SelectFluid" == c.sizeCriteria : !1 }, c), d);
    };
    l.getTraffickingParameters = function () {
        return Ru(Ne(this.h.traffickingParameters));
    };
    l.getTraffickingParametersString = function () {
        return this.h.traffickingParameters;
    };
    l.getVastMediaBitrate = function () {
        return this.h.vastMediaBitrate;
    };
    l.getMediaUrl = function () {
        return this.h.mediaUrl;
    };
    l.getSurveyUrl = function () {
        return this.h.surveyUrl;
    };
    l.getDealId = function () {
        return this.h.dealId;
    };
    l.Ne = function () {
        return (this.h.universalAdIds || []).map(function (a) {
            return new KA(a.adIdValue, a.adIdRegistry);
        });
    };
    l.getUniversalAdIdValue = function () {
        return this.h.universalAdIdValue;
    };
    l.getUniversalAdIdRegistry = function () {
        return this.h.universalAdIdRegistry;
    };
    l.getSkipTimeOffset = function () {
        return this.h.skipTimeOffset;
    };
    l.isUiDisabled = function () {
        return this.h.disableUi;
    };
    Y.prototype.isUiDisabled = Y.prototype.isUiDisabled;
    Y.prototype.getSkipTimeOffset = Y.prototype.getSkipTimeOffset;
    Y.prototype.getUniversalAdIdRegistry = Y.prototype.getUniversalAdIdRegistry;
    Y.prototype.getUniversalAdIdValue = Y.prototype.getUniversalAdIdValue;
    Y.prototype.getUniversalAdIds = Y.prototype.Ne;
    Y.prototype.getDealId = Y.prototype.getDealId;
    Y.prototype.getSurveyUrl = Y.prototype.getSurveyUrl;
    Y.prototype.getMediaUrl = Y.prototype.getMediaUrl;
    Y.prototype.getVastMediaBitrate = Y.prototype.getVastMediaBitrate;
    Y.prototype.getTraffickingParametersString = Y.prototype.getTraffickingParametersString;
    Y.prototype.getTraffickingParameters = Y.prototype.getTraffickingParameters;
    Y.prototype.getCompanionAds = Y.prototype.getCompanionAds;
    Y.prototype.getAdPodInfo = Y.prototype.getAdPodInfo;
    Y.prototype.getMinSuggestedDuration = Y.prototype.getMinSuggestedDuration;
    Y.prototype.getUiElements = Y.prototype.getUiElements;
    Y.prototype.getHeight = Y.prototype.getHeight;
    Y.prototype.getWidth = Y.prototype.getWidth;
    Y.prototype.getVastMediaHeight = Y.prototype.getVastMediaHeight;
    Y.prototype.getVastMediaWidth = Y.prototype.getVastMediaWidth;
    Y.prototype.getDuration = Y.prototype.getDuration;
    Y.prototype.getTitle = Y.prototype.Me;
    Y.prototype.getDescription = Y.prototype.Ke;
    Y.prototype.getContentType = Y.prototype.getContentType;
    Y.prototype.isSkippable = Y.prototype.isSkippable;
    Y.prototype.isLinear = Y.prototype.isLinear;
    Y.prototype.getWrapperAdSystems = Y.prototype.getWrapperAdSystems;
    Y.prototype.getWrapperCreativeIds = Y.prototype.getWrapperCreativeIds;
    Y.prototype.getWrapperAdIds = Y.prototype.getWrapperAdIds;
    Y.prototype.getApiFramework = Y.prototype.getApiFramework;
    Y.prototype.getAdvertiserName = Y.prototype.getAdvertiserName;
    Y.prototype.getAdSystem = Y.prototype.getAdSystem;
    Y.prototype.getCreativeId = Y.prototype.getCreativeId;
    Y.prototype.getCreativeAdId = Y.prototype.getCreativeAdId;
    Y.prototype.getAdId = Y.prototype.getAdId;
    var MA = null,
        NA = function () {
            M.call(this);
            this.h = null;
            this.D = new Iu(this);
            zj(this, this.D);
            this.j = new Map();
            this.A = new Map();
            this.l = this.B = !1;
            this.C = null;
        },
        OA;
    r(NA, M);
    var PA = function () {
            null == MA && (MA = new NA());
            return MA;
        },
        Zr = function (a, b, c) {
            var d = {};
            d.queryId = b;
            d.viewabilityData = c;
            a.h && Vy(a.h, "activityMonitor", "viewabilityMeasurement", d);
        };
    NA.prototype.destroy = function () {
        this.D.Wa(this.h, "activityMonitor", this.G);
        this.l = !1;
        this.j.clear();
        this === OA && (OA = null);
    };
    NA.prototype.N = function () {
        this.destroy();
        M.prototype.N.call(this);
    };
    NA.prototype.init = function (a) {
        if (!this.l) {
            if ((this.h = a || null)) this.D.P(this.h, "activityMonitor", this.G), QA(this);
            if (!(t.ima && t.ima.video && t.ima.video.client && t.ima.video.client.tagged)) {
                u("ima.video.client.sdkTag", !0, void 0);
                var b = t.document;
                a = cf(document, "SCRIPT");
                var c = Td(ib(jb("https://s0.2mdn.net/instream/video/client.js")));
                a.src = se(c);
                ve(a);
                a.async = !0;
                a.type = "text/javascript";
                b = b.getElementsByTagName("script")[0];
                b.parentNode.insertBefore(a, b);
            }
            Am();
            G(Pr).G = fy.h;
            this.B = !0;
            G(Pr).l = !0;
            this.C = null;
            a = G(Pr);
            b = "h" == Er(a) || "b" == Er(a);
            c = !(P(), !1);
            b && c && ((a.H = !0), (a.D = new Xp()));
            this.l = !0;
        }
    };
    var SA = function (a) {
            if (null == a) return !1;
            if ((ic || kc) && null != a.webkitDisplayingFullscreen) return a.webkitDisplayingFullscreen;
            a = RA(a);
            var b = window.screen.availHeight || window.screen.height;
            return 0 >= (window.screen.availWidth || window.screen.width) - a.width && 42 >= b - a.height;
        },
        RA = function (a) {
            var b = { left: a.offsetLeft, top: a.offsetTop, width: a.offsetWidth, height: a.offsetHeight };
            try {
                "function" === typeof a.getBoundingClientRect && hf(Ue(a), a) && (b = a.getBoundingClientRect());
            } catch (c) {}
            return b;
        },
        TA = function (a, b, c, d, e) {
            e = void 0 === e ? {} : e;
            if (a.l) {
                d && null == e.opt_osdId && (e.opt_osdId = d);
                if (a.C) return a.C(b, c, e);
                if ((a = d ? a.A.get(d) : fy.l)) null == e.opt_fullscreen && (e.opt_fullscreen = SA(a)), null == e.opt_adElement && (e.opt_adElement = a);
                return am.bb(469, Ya(bs, b, c, e), void 0) || {};
            }
            return {};
        },
        UA = function (a, b) {
            var c = String(Math.floor(1e9 * Math.random()));
            a.A.set(c, b);
            if (oj.isSelected())
                try {
                    pl(
                        function (d) {
                            if (a.h) {
                                var e = {};
                                e.engagementString = d;
                                Vy(a.h, "activityMonitor", "engagementData", e);
                            }
                        },
                        function () {
                            return b;
                        }
                    );
                } catch (d) {}
            0 != fy.h && $r(G(Pr), c, a);
            return c;
        },
        VA = function (a, b, c) {
            if (c) a.j.get(c) == b && a.j.delete(c);
            else {
                var d = [];
                a.j.forEach(function (e, f) {
                    e == b && d.push(f);
                });
                d.forEach(a.j.delete, a.j);
            }
        },
        Vr = function (a, b) {
            a = a.j.get(b);
            return "function" === typeof a ? a() : {};
        },
        QA = function (a) {
            if ("function" === typeof window.Goog_AdSense_Lidar_getUrlSignalsArray) {
                var b = {};
                b.pageSignals = window.Goog_AdSense_Lidar_getUrlSignalsArray();
                Vy(a.h, "activityMonitor", "pageSignals", b);
            }
        };
    NA.prototype.G = function (a) {
        var b = a.ka,
            c = b.queryId,
            d = {},
            e = null;
        d.eventId = b.eventId;
        switch (a.ha) {
            case "getPageSignals":
                QA(this);
                break;
            case "reportVastEvent":
                e = b.vastEvent;
                a = b.osdId;
                var f = {};
                f.opt_fullscreen = b.isFullscreen;
                b.isOverlay && (f.opt_bounds = b.overlayBounds);
                d.viewabilityData = TA(this, e, c, a, f);
                Vy(this.h, "activityMonitor", "viewability", d);
                break;
            case "fetchAdTagUrl":
                (c = {}),
                    (c.eventId = b.eventId),
                    (a = b.osdId),
                    Fd(b, "isFullscreen") && (e = b.isFullscreen),
                    Fd(b, "loggingId") && ((b = b.loggingId), (c.loggingId = b), HA().report(43, { step: "beforeLookup", logid: b, time: Date.now() })),
                    (c.engagementString = WA(this, a, e)),
                    this.h && Vy(this.h, "activityMonitor", "engagement", c);
        }
    };
    var WA = function (a, b, c) {
        var d = b ? a.A.get(b) : fy.l;
        a = {};
        null != c && (a.fullscreen = c);
        c = "";
        try {
            c = ol(function () {
                return d;
            }, a);
        } catch (e) {
            c = "sdktle;" + Le(e.name, 12) + ";" + Le(e.message, 40);
        }
        return c;
    };
    u(
        "ima.common.getVideoMetadata",
        function (a) {
            return Vr(PA(), a);
        },
        void 0
    );
    u(
        "ima.common.triggerViewabilityMeasurementUpdate",
        function (a, b) {
            Zr(PA(), a, b);
        },
        void 0
    );
    var XA = cc ? Td(ib(jb('javascript:""'))) : Td(ib(jb("about:blank")));
    Sd(XA);
    var YA = cc ? Td(ib(jb('javascript:""'))) : Td(ib(jb("javascript:undefined")));
    Sd(YA);
    var ZA = function (a, b, c) {
        b = void 0 === b ? null : b;
        c = void 0 === c ? null : c;
        Aj.call(this, a);
        this.l = b;
        this.h = c;
    };
    r(ZA, Aj);
    ZA.prototype.getAd = function () {
        return this.l;
    };
    ZA.prototype.getAdData = function () {
        return this.h;
    };
    var $A = function () {
        this.loadVideoTimeout = 8e3;
        this.autoAlign = !0;
        this.bitrate = -1;
        this.uiElements = null;
        this.enablePreloading = this.disableClickThrough = !1;
        this.mimeTypes = null;
        this.useStyledNonLinearAds = this.useStyledLinearAds = this.useLearnMoreButton = this.restoreCustomPlaybackStateOnAdBreakComplete = !1;
        this.playAdsAfterTime = -1;
        this.useVideoAdUi = !0;
        this.disableUi = !1;
    };
    $A.prototype.aa = function (a) {
        var b = {};
        Object.assign(b, this);
        a && (b.disableClickThrough = !0);
        return b;
    };
    $A.prototype.append = function (a) {
        if (a) {
            this.autoAlign = a.autoAlign || this.autoAlign;
            var b = Se(a.bitrate);
            "number" === typeof b && !isNaN(b) && 0 < b && (this.bitrate = b);
            this.disableClickThrough = a.disableClickThrough || this.disableClickThrough;
            this.disableUi = a.disableUi || this.disableUi;
            this.enablePreloading = a.enablePreloading || this.enablePreloading;
            a.mimeTypes && 0 != a.mimeTypes.length && (this.mimeTypes = a.mimeTypes);
            b = Se(a.playAdsAfterTime);
            "number" === typeof b && !isNaN(b) && 0 < b && (this.playAdsAfterTime = b);
            this.restoreCustomPlaybackStateOnAdBreakComplete = a.restoreCustomPlaybackStateOnAdBreakComplete || this.restoreCustomPlaybackStateOnAdBreakComplete;
            b = Se(a.loadVideoTimeout);
            "number" === typeof b && !isNaN(b) && 0 < b && (this.loadVideoTimeout = b);
            this.uiElements = a.uiElements || this.uiElements;
            this.useLearnMoreButton = a.useLearnMoreButton || this.useLearnMoreButton;
            this.useStyledLinearAds = a.useStyledLinearAds || this.useStyledLinearAds;
            this.useStyledNonLinearAds = a.useStyledNonLinearAds || this.useStyledNonLinearAds;
            this.useVideoAdUi = !1 === a.useVideoAdUi ? !1 : this.useVideoAdUi;
        }
    };
    u("module$contents$ima$AdsRenderingSettings_AdsRenderingSettings.AUTO_SCALE", -1, void 0);
    var aB = function (a, b) {
        this.h = a[t.Symbol.iterator]();
        this.j = b;
    };
    aB.prototype[Symbol.iterator] = function () {
        return this;
    };
    aB.prototype.next = function () {
        var a = this.h.next();
        return { value: a.done ? void 0 : this.j.call(void 0, a.value), done: a.done };
    };
    var bB = function (a, b) {
        return new aB(a, b);
    };
    var fB = function (a) {
            if (a instanceof cB || a instanceof dB || a instanceof eB) return a;
            if ("function" == typeof a.next)
                return new cB(function () {
                    return a;
                });
            if ("function" == typeof a[Symbol.iterator])
                return new cB(function () {
                    return a[Symbol.iterator]();
                });
            if ("function" == typeof a.jb)
                return new cB(function () {
                    return a.jb();
                });
            throw Error("Not an iterator or iterable.");
        },
        cB = function (a) {
            this.h = a;
        };
    cB.prototype.jb = function () {
        return new dB(this.h());
    };
    cB.prototype[Symbol.iterator] = function () {
        return new eB(this.h());
    };
    cB.prototype.j = function () {
        return new eB(this.h());
    };
    var dB = function (a) {
        this.h = a;
    };
    r(dB, Yo);
    dB.prototype.next = function () {
        return this.h.next();
    };
    dB.prototype[Symbol.iterator] = function () {
        return new eB(this.h);
    };
    dB.prototype.j = function () {
        return new eB(this.h);
    };
    var eB = function (a) {
        cB.call(this, function () {
            return a;
        });
        this.l = a;
    };
    r(eB, cB);
    eB.prototype.next = function () {
        return this.l.next();
    };
    var gB = function (a, b) {
        this.j = {};
        this.h = [];
        this.l = this.size = 0;
        var c = arguments.length;
        if (1 < c) {
            if (c % 2) throw Error("Uneven number of arguments");
            for (var d = 0; d < c; d += 2) this.set(arguments[d], arguments[d + 1]);
        } else if (a)
            if (a instanceof gB) for (c = a.Tb(), d = 0; d < c.length; d++) this.set(c[d], a.get(c[d]));
            else for (d in a) this.set(d, a[d]);
    };
    l = gB.prototype;
    l.ob = function () {
        hB(this);
        for (var a = [], b = 0; b < this.h.length; b++) a.push(this.j[this.h[b]]);
        return a;
    };
    l.Tb = function () {
        hB(this);
        return this.h.concat();
    };
    l.has = function (a) {
        return iB(this.j, a);
    };
    l.isEmpty = function () {
        return 0 == this.size;
    };
    l.clear = function () {
        this.j = {};
        this.l = this.size = this.h.length = 0;
    };
    l.remove = function (a) {
        return this.delete(a);
    };
    l.delete = function (a) {
        return iB(this.j, a) ? (delete this.j[a], --this.size, this.l++, this.h.length > 2 * this.size && hB(this), !0) : !1;
    };
    var hB = function (a) {
        if (a.size != a.h.length) {
            for (var b = 0, c = 0; b < a.h.length; ) {
                var d = a.h[b];
                iB(a.j, d) && (a.h[c++] = d);
                b++;
            }
            a.h.length = c;
        }
        if (a.size != a.h.length) {
            var e = {};
            for (c = b = 0; b < a.h.length; ) (d = a.h[b]), iB(e, d) || ((a.h[c++] = d), (e[d] = 1)), b++;
            a.h.length = c;
        }
    };
    l = gB.prototype;
    l.get = function (a, b) {
        return iB(this.j, a) ? this.j[a] : b;
    };
    l.set = function (a, b) {
        iB(this.j, a) || ((this.size += 1), this.h.push(a), this.l++);
        this.j[a] = b;
    };
    l.forEach = function (a, b) {
        for (var c = this.Tb(), d = 0; d < c.length; d++) {
            var e = c[d],
                f = this.get(e);
            a.call(b, f, e, this);
        }
    };
    l.keys = function () {
        return fB(this.jb(!0)).j();
    };
    l.values = function () {
        return fB(this.jb(!1)).j();
    };
    l.entries = function () {
        var a = this;
        return bB(this.keys(), function (b) {
            return [b, a.get(b)];
        });
    };
    l.jb = function (a) {
        hB(this);
        var b = 0,
            c = this.l,
            d = this,
            e = new Yo();
        e.next = function () {
            if (c != d.l) throw Error("The map has changed since the iterator was created");
            if (b >= d.h.length) return Zo;
            var f = d.h[b++];
            return { value: a ? f : d.j[f], done: !1 };
        };
        return e;
    };
    var iB = function (a, b) {
        return Object.prototype.hasOwnProperty.call(a, b);
    };
    var jB = null,
        kB = function () {
            M.call(this);
            this.h = new gB();
            this.j = null;
            this.A = new Map();
            this.l = new Iu(this);
            zj(this, this.l);
            this.B = new Map();
            this.G = null;
            this.D = -1;
            P().l = !0;
            Ty();
        };
    r(kB, M);
    var lB = function () {
            null == jB && (jB = new kB());
            return jB;
        },
        mB = function (a, b) {
            var c = {};
            c.queryId = a;
            c.viewabilityString = b;
            lB().dispatchEvent(new ZA("measurable_impression", null, c));
        },
        nB = function (a, b) {
            var c = {};
            c.queryId = a;
            c.viewabilityString = b;
            lB().dispatchEvent(new ZA("viewable_impression", null, c));
        },
        oB = function (a, b, c) {
            var d = {};
            d.queryId = a;
            d.viewabilityString = b;
            d.eventName = c;
            lB().dispatchEvent(new ZA("externalActivityEvent", null, d));
        };
    kB.prototype.destroy = function () {
        this.l.Wa(this.j, "activityMonitor", this.C);
        this.j = null;
    };
    kB.prototype.C = function (a) {
        var b = a.ka;
        switch (a.ha) {
            case "appStateChanged":
                G(Pr);
                b = b.appState;
                a = Q();
                a.H != b && ((a.H = b), (a = G(mq).h) && $n(a.h, !b));
                break;
            case "externalActivityEvent":
                oB(b.queryId, b.viewabilityString, b.eventName);
                break;
            case "measurableImpression":
                mB(b.queryId, b.viewabilityString);
                break;
            case "viewableImpression":
                nB(b.queryId, b.viewabilityString);
                break;
            case "engagementData":
                b = b.engagementString;
                lB().G = b;
                lB().D = Za();
                break;
            case "viewability":
                a = b.queryId;
                var c = b.vastEvent;
                this.A.get(a) && "start" == c && this.A.get(a);
                a = b.eventId;
                clearTimeout(a);
                if ((c = this.h.get(a))) this.h.delete(a), c(b.viewabilityData);
                break;
            case "viewabilityMeasurement":
                G(Pr);
                P();
                break;
            case "engagement":
                a = b.eventId;
                clearTimeout(a);
                c = this.h.get(a);
                if (HA().isLoggingEnabled()) {
                    var d = -1;
                    this.I && (d = Za() - this.I);
                    var e = !1;
                    c || (e = !0);
                    Fd(b, "loggingId") && HA().report(43, { step: "receivedResponse", time: Za(), timeout: e, logid: b.loggingId, timediff: d });
                }
                c && (this.h.delete(a), c(b.engagementString));
        }
    };
    u(
        "ima.bridge.getNativeViewability",
        function (a, b) {
            lB();
            b({});
        },
        void 0
    );
    u(
        "ima.bridge.getVideoMetadata",
        function (a) {
            return (a = lB().B.get(a)) ? a() : {};
        },
        void 0
    );
    u("ima.bridge.triggerViewEvent", nB, void 0);
    u("ima.bridge.triggerMeasurableEvent", mB, void 0);
    u("ima.bridge.triggerExternalActivityEvent", oB, void 0);
    Object.entries({
        "application/dash+xml": 1,
        "application/x-javascript": 2,
        "application/x-mpegurl": 3,
        "application/javascript": 4,
        "audio/ogg": 5,
        "audio/mp4": 6,
        "audio/mpeg": 7,
        "audio/wav": 8,
        "text/javascript": 9,
        "video/m4v": 10,
        "video/ogg": 11,
        "video/x-flv": 12,
        "video/3gpp": 13,
        "video/mpt2": 14,
        "video/mp4": 15,
        "video/mpeg": 16,
        "video/quicktime": 17,
        "video/webm": 18,
    });
    function pB(a, b) {
        return b instanceof RegExp ? "__REGEXP" + b.toString() : b;
    }
    function qB(a, b) {
        return b && 0 == b.toString().indexOf("__REGEXP") ? ((a = b.split("__REGEXP")[1].match(/\/(.*)\/(.*)?/)), new RegExp(a[1], a[2] || "")) : b;
    }
    var rB = function (a, b) {
        Uy.call(this, b);
        this.A = a;
        this.h = null;
        this.B = new Iu(this);
        this.B.P(z(), "message", this.C);
    };
    r(rB, Uy);
    var sB = function (a) {
        if (null == a || "string" !== typeof a || 0 != a.lastIndexOf("ima://", 0)) return null;
        a = a.substr(6);
        try {
            return JSON.parse(a, qB);
        } catch (b) {
            return null;
        }
    };
    rB.prototype.sendMessage = function (a, b, c) {
        if (null != this.h && null != this.h.postMessage) {
            var d = this.h,
                e = d.postMessage,
                f = {};
            f.name = a;
            f.type = b;
            null != c && (f.data = c);
            f.sid = this.sessionId;
            f.channel = this.A;
            a = "ima://" + new ei(pB).aa(f);
            e.call(d, a, "*");
        }
        null != this.h && null == this.h.postMessage && HA().report(11);
    };
    rB.prototype.N = function () {
        xj(this.B);
        this.h = null;
        Uy.prototype.N.call(this);
    };
    rB.prototype.C = function (a) {
        a = a.h;
        var b = sB(a.data);
        if (tB(this, b)) {
            if (null == this.h) (this.h = a.source), this.j || this.connect();
            else if (this.h != a.source) return;
            tB(this, b) && this.dispatchEvent(new Wy(b.name, b.type, b.data || {}, b.sid, a.origin));
        }
    };
    var tB = function (a, b) {
        if (null == b) return !1;
        var c = b.channel;
        if (null == c || c != a.A) return !1;
        b = b.sid;
        return null == b || ("*" != a.sessionId && b != a.sessionId) ? !1 : !0;
    };
    var uB = {
        LOADED: "loaded",
        wc: "start",
        FIRST_QUARTILE: "firstQuartile",
        MIDPOINT: "midpoint",
        THIRD_QUARTILE: "thirdQuartile",
        COMPLETE: "complete",
        vc: "pause",
        pd: "resume",
        hd: "bufferStart",
        gd: "bufferFinish",
        SKIPPED: "skipped",
        xe: "volumeChange",
        Wg: "playerStateChange",
        If: "adUserInteraction",
    };
    Object.values({ LIMITED: "limited", DOMAIN: "domain", FULL: "full" });
    var vB = Td(ib(jb("https://pagead2.googlesyndication.com/omsdk/releases/live/omweb-v1.js")));
    function wB(a, b) {
        if (!b) throw Error("Value for " + a + " is undefined, null or blank.");
        if ("string" !== typeof b && !(b instanceof String)) throw Error("Value for " + a + " is not a string.");
        if ("" === b.trim()) throw Error("Value for " + a + " is empty string.");
    }
    function xB(a, b) {
        if (null == b) throw Error("Value for " + a + " is undefined or null");
    }
    function yB(a, b) {
        if (null == b) throw Error(a + " must not be null or undefined.");
        if ("number" !== typeof b || isNaN(b)) throw Error("Value for " + a + " is not a number");
    }
    function zB(a, b) {
        return a && (a[b] || (a[b] = {}));
    }
    function AB(a, b) {
        var c;
        if ((c = void 0 === c ? ("undefined" === typeof omidExports ? null : omidExports) : c)) (a = a.split(".")), (a.slice(0, a.length - 1).reduce(zB, c)[a[a.length - 1]] = b);
    }
    function BB() {
        return /\d+\.\d+\.\d+(-.*)?/.test("1.3.31-google_20220309");
    }
    function CB() {
        for (var a = ["1", "3", "31"], b = ["1", "0", "3"], c = 0; 3 > c; c++) {
            var d = parseInt(a[c], 10),
                e = parseInt(b[c], 10);
            if (d > e) break;
            else if (d < e) return !1;
        }
        return !0;
    }
    var DB = function (a, b, c, d) {
            this.h = a;
            this.method = b;
            this.version = c;
            this.args = d;
        },
        EB = function (a) {
            return (
                !!a &&
                void 0 !== a.omid_message_guid &&
                void 0 !== a.omid_message_method &&
                void 0 !== a.omid_message_version &&
                "string" === typeof a.omid_message_guid &&
                "string" === typeof a.omid_message_method &&
                "string" === typeof a.omid_message_version &&
                (void 0 === a.omid_message_args || void 0 !== a.omid_message_args)
            );
        },
        FB = function (a) {
            return new DB(a.omid_message_guid, a.omid_message_method, a.omid_message_version, a.omid_message_args);
        };
    DB.prototype.aa = function () {
        var a = {};
        a = ((a.omid_message_guid = this.h), (a.omid_message_method = this.method), (a.omid_message_version = this.version), a);
        void 0 !== this.args && (a.omid_message_args = this.args);
        return a;
    };
    var GB = function (a) {
        this.j = a;
    };
    GB.prototype.aa = function (a) {
        return JSON.stringify(a);
    };
    function HB() {
        return "xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx".replace(/[xy]/g, function (a) {
            var b = (16 * Math.random()) | 0;
            return "y" === a ? ((b & 3) | 8).toString(16) : b.toString(16);
        });
    }
    function IB() {
        var a = Ha.apply(0, arguments);
        JB(
            function () {
                throw new (Function.prototype.bind.apply(Error, [null, "Could not complete the test successfully - "].concat(ha(a))))();
            },
            function () {
                return console.error.apply(console, ha(a));
            }
        );
    }
    function JB(a, b) {
        "undefined" !== typeof jasmine && jasmine ? a() : "undefined" !== typeof console && console && console.error && b();
    }
    var KB = function (a) {
        try {
            return a.frames ? !!a.frames.omid_v1_present : !1;
        } catch (b) {
            return !1;
        }
    };
    var LB = function (a) {
        this.j = a;
        this.handleExportedMessage = LB.prototype.l.bind(this);
    };
    r(LB, GB);
    LB.prototype.sendMessage = function (a, b) {
        b = void 0 === b ? this.j : b;
        if (!b) throw Error("Message destination must be defined at construction time or when sending the message.");
        b.handleExportedMessage(a.aa(), this);
    };
    LB.prototype.l = function (a, b) {
        EB(a) && this.h && this.h(FB(a), b);
    };
    var MB = (function () {
        if ("undefined" !== typeof omidGlobal && omidGlobal) return omidGlobal;
        if ("undefined" !== typeof global && global) return global;
        if ("undefined" !== typeof window && window) return window;
        if ("undefined" !== typeof globalThis && globalThis) return globalThis;
        var a = Function("return this")();
        if (a) return a;
        throw Error("Could not determine global object context.");
    })();
    function NB(a) {
        return null != a && "undefined" !== typeof a.top && null != a.top;
    }
    function OB(a) {
        if (a === MB) return !1;
        try {
            if ("undefined" === typeof a.location.hostname) return !0;
        } catch (b) {
            return !0;
        }
        return !1;
    }
    var PB = function (a, b) {
        this.j = b = void 0 === b ? MB : b;
        var c = this;
        a.addEventListener("message", function (d) {
            if ("object" === typeof d.data) {
                var e = d.data;
                EB(e) && d.source && c.h && c.h(FB(e), d.source);
            }
        });
    };
    r(PB, GB);
    PB.prototype.sendMessage = function (a, b) {
        b = void 0 === b ? this.j : b;
        if (!b) throw Error("Message destination must be defined at construction time or when sending the message.");
        b.postMessage(a.aa(), "*");
    };
    var QB = ["omid", "v1_SessionServiceCommunication"];
    function RB(a) {
        return QB.reduce(function (b, c) {
            return b && b[c];
        }, a);
    }
    AB("OmidSessionClient.Partner", function (a, b) {
        wB("Partner.name", a);
        wB("Partner.version", b);
        this.name = a;
        this.version = b;
    });
    var SB = function (a, b, c, d) {
        d = void 0 === d ? "full" : d;
        wB("VerificationScriptResource.resourceUrl", a);
        this.l = a;
        this.o = b;
        this.j = c;
        this.h = d;
    };
    SB.prototype.toJSON = function () {
        return { accessMode: this.h, resourceUrl: this.l, vendorKey: this.o, verificationParameters: this.j };
    };
    AB("OmidSessionClient.VerificationScriptResource", SB);
    AB("OmidSessionClient.Context", function (a, b, c, d) {
        c = void 0 === c ? null : c;
        d = void 0 === d ? null : d;
        xB("Context.partner", a);
        this.partner = a;
        this.l = b;
        this.contentUrl = c;
        this.h = d;
        this.j = !1;
    });
    var TB = { sessionError: "reportError" },
        UB = Object.keys(uB).map(function (a) {
            return uB[a];
        }),
        VB = ["impressionOccurred"],
        WB = function () {
            var a = void 0 === a ? MB : a;
            this.h = a.omidSessionInterface;
        };
    WB.prototype.isSupported = function () {
        return null != this.h;
    };
    WB.prototype.sendMessage = function (a, b, c) {
        "registerSessionObserver" == a && (c = [b]);
        TB[a] && (a = TB[a]);
        b = this.h;
        0 <= VB.indexOf(a) && (b = b.adEvents);
        0 <= UB.indexOf(a) && (b = b.mediaEvents);
        b = b[a];
        if (!b) throw Error("Unrecognized method name: " + a + ".");
        b.apply(null, ha(c));
    };
    var ZB = function (a, b, c) {
        xB("AdSession.context", a);
        this.j = a;
        if (!b) {
            var d;
            "undefined" === typeof d && "undefined" !== typeof window && window && (d = window);
            d = NB(d) ? d : MB;
            var e = void 0 === e ? KB : e;
            a: {
                b = q([d, NB(d) ? d.top : MB]);
                for (var f = b.next(); !f.done; f = b.next()) {
                    b: {
                        var g = d;
                        f = f.value;
                        var h = e;
                        if (!OB(f))
                            try {
                                var k = RB(f);
                                if (k) {
                                    var n = new LB(k);
                                    break b;
                                }
                            } catch (m) {}
                        n = h(f) ? new PB(g, f) : null;
                    }
                    if ((g = n)) {
                        b = g;
                        break a;
                    }
                }
                b = null;
            }
        }
        this.h = b;
        this.l = c || new WB();
        this.J = this.C = this.B = !1;
        this.H = this.A = null;
        this.o = {};
        this.h && (this.h.h = this.Oe.bind(this));
        this.va("setClientInfo", "1.3.31-google_20220309", this.j.partner.name, this.j.partner.version);
        XB(this, a.l);
        (a = a.contentUrl) && this.va("setContentUrl", a);
        YB(this);
    };
    ZB.prototype.isSupported = function () {
        return !!this.h || this.l.isSupported();
    };
    var $B = function (a, b) {
        a.sendMessage("registerSessionObserver", b);
    };
    l = ZB.prototype;
    l.start = function () {
        this.va("startSession", { customReferenceData: this.j.h, underEvaluation: this.j.j });
    };
    l.error = function (a, b) {
        this.va("sessionError", a, b);
    };
    l.va = function (a) {
        this.sendMessage.apply(this, [a, null].concat(ha(Ha.apply(1, arguments))));
    };
    l.sendMessage = function (a, b) {
        var c = Ha.apply(2, arguments);
        if (this.h) {
            var d = a,
                e = b,
                f = HB();
            e && (this.o[f] = e);
            c = new DB(f, "SessionService." + d, "1.3.31-google_20220309", BB() && CB() ? c : JSON.stringify(c));
            this.h.sendMessage(c);
        } else if (this.l.isSupported())
            try {
                this.l.sendMessage(a, b, c);
            } catch (g) {
                IB("Failed to communicate with SessionInterface with error:"), IB(g);
            }
    };
    l.Oe = function (a) {
        var b = a.method,
            c = a.h;
        a = a.args;
        if ("response" === b && this.o[c]) {
            var d = BB() && CB() ? (a ? a : []) : a && "string" === typeof a ? JSON.parse(a) : [];
            this.o[c].apply(this, d);
        }
        "error" === b && window.console && IB(a);
    };
    var XB = function (a, b) {
            b &&
                ((b = b.map(function (c) {
                    return c.toJSON();
                })),
                a.va("injectVerificationScriptResources", b));
        },
        YB = function (a) {
            $B(a, function (b) {
                "sessionStart" === b.type && ((a.J = !0), (a.A = b.data.creativeType), (a.H = b.data.impressionType));
                "sessionFinish" === b.type && (a.J = !1);
            });
        };
    AB("OmidSessionClient.AdSession", ZB);
    var aC = function (a) {
        xB("AdEvents.adSession", a);
        try {
            if (a.B) throw Error("AdEvents already registered.");
            a.B = !0;
            a.va("registerAdEvents");
            this.h = a;
        } catch (b) {
            throw Error("AdSession already has an ad events instance registered");
        }
    };
    aC.prototype.loaded = function (a) {
        a = void 0 === a ? null : a;
        var b = this.h;
        if ("definedByJavaScript" === b.A) throw Error("Creative type has not been redefined");
        if ("definedByJavaScript" === b.H) throw Error("Impression type has not been redefined");
        a ? this.h.va("loaded", a.toJSON()) : this.h.va("loaded");
    };
    AB("OmidSessionClient.AdEvents", aC);
    var bC = function (a) {
        xB("MediaEvents.adSession", a);
        try {
            if (a.C) throw Error("MediaEvents already registered.");
            a.C = !0;
            a.va("registerMediaEvents");
            this.h = a;
        } catch (b) {
            throw Error("AdSession already has a media events instance registered");
        }
    };
    bC.prototype.start = function (a, b) {
        yB("MediaEvents.start.duration", a);
        yB("MediaEvents.start.mediaPlayerVolume", b);
        if (0 > b || 1 < b) throw Error("Value for MediaEvents.start.mediaPlayerVolume is outside the range [0,1]");
        this.h.va("start", a, b);
    };
    bC.prototype.pause = function () {
        this.h.va("pause");
    };
    bC.prototype.resume = function () {
        this.h.va("resume");
    };
    AB("OmidSessionClient.MediaEvents", bC);
    var eC = function (a, b) {
            cC ? (a.srcdoc = b) : (a = a.contentWindow) && dC(a.document, b);
        },
        cC = fc && "srcdoc" in cf(document, "IFRAME"),
        dC = function (a, b) {
            a.open("text/html", "replace");
            we(a, Fu(b));
            a.close();
        };
    function fC(a) {
        return (a = jf(a)) && a.omidSessionInterface ? a.omidSessionInterface : null;
    }
    function gC(a, b) {
        var c = ef("IFRAME", { sandbox: "allow-scripts allow-same-origin", style: "display: none" });
        a.appendChild(c);
        a = "<script src=" + vB.Ga() + ">\x3c/script>";
        b &&
            (a +=
                "\n      <script>\n        window.addEventListener('message', function(e) {\n          if (e.data.type === 'innerBridgeIframeLoaded') {\n            window.frameElement.parentElement\n              .querySelector('#" +
                b +
                "').contentWindow\n              .postMessage({type: 'omidIframeLoaded'}, '*');\n          }\n        });\n      \x3c/script>\n    ");
        b = new Promise(function (d, e) {
            c.addEventListener("load", function () {
                fC(c) ? d(c) : e();
            });
        });
        eC(c, a);
        return b;
    }
    var hC = function (a, b) {
        M.call(this);
        this.j = fC(a);
        this.h = b;
    };
    r(hC, M);
    var jC = function (a) {
            try {
                a.j.registerSessionObserver(function (b) {
                    "sessionStart" == b.type ? iC(a, a.h) : "sessionFinish" == b.type && jC(a);
                });
            } catch (b) {
                a.dispatchEvent(new Event("error"));
            }
        },
        iC = function (a, b) {
            b instanceof xx && (b = b.O);
            try {
                a.j.setVideoElement(b);
            } catch (c) {
                a.dispatchEvent(new Event("error"));
            }
        };
    var kC = function (a) {
        this.h = a;
    };
    kC.prototype.getCuePoints = function () {
        return this.h;
    };
    kC.prototype.getCuePoints = kC.prototype.getCuePoints;
    u("module$contents$ima$AdCuePoints_AdCuePoints.PREROLL", 0, void 0);
    u("module$contents$ima$AdCuePoints_AdCuePoints.POSTROLL", -1, void 0);
    var lC = function (a) {
            this.h = a;
            this.l = "";
            this.j = -1;
            this.o = !1;
        },
        nC = function (a, b) {
            if (0 <= a.j) {
                var c = null == b ? function () {} : b,
                    d = function () {
                        mC(a, c);
                        a.h.removeEventListener("loadedmetadata", d, !1);
                    };
                a.h.addEventListener("loadedmetadata", d, !1);
                a.h.src = a.l;
                a.h.load();
            } else null != b && b();
        },
        mC = function (a, b) {
            var c = 0 < a.h.seekable.length;
            a.o
                ? c
                    ? ((a.h.currentTime = a.j), oC(a), b())
                    : setTimeout(function () {
                          return mC(a, b);
                      }, 100)
                : (oC(a), b());
        },
        oC = function (a) {
            a.j = -1;
            a.l = "";
            a.o = !1;
        };
    var pC = new y(5, 5),
        qC = function (a) {
            M.call(this);
            this.h = a;
            this.Y = null;
            this.B = new lC(a);
            this.j = new Iu(this);
            zj(this, this.j);
            this.A = 0;
            this.K = this.D = this.M = this.W = this.I = !1;
            this.L = this.l = null;
            this.T = new y(this.h.offsetWidth, this.h.offsetHeight);
            this.Ma = null;
            this.U = SA(this.h);
            this.V = !1;
        };
    r(qC, M);
    l = qC.prototype;
    l.nd = function () {
        var a = this.B;
        a.l = a.h.currentSrc;
        a.o = 0 < a.h.seekable.length;
        a.j = a.h.ended ? -1 : a.h.currentTime;
    };
    l.Nb = function (a) {
        nC(this.B, a);
    };
    l.load = function (a, b) {
        var c = J().h;
        c.T = !0;
        Nh(c);
        bi("hvd_lc");
        rC(this);
        this.M = !1;
        if (b)
            if ((bi("hvd_ad"), b instanceof Mt)) {
                if ((bi("hvd_mad"), (c = b.getMediaUrl()))) {
                    bi("hvd_admu");
                    bi("hvd_src");
                    this.h.src = c;
                    this.h.load();
                    return;
                }
            } else if (b instanceof Lt) {
                bi("hvd_dad");
                c = b.o;
                var d = b.j,
                    e = b.l,
                    f = b.h,
                    g = b.Xa,
                    h = b.Oa;
                if (c && d && e && f && g && h && (bi("hvd_addu"), b.xa)) {
                    bi("hvd_admse");
                    b = e + '; codecs="' + g + '"';
                    f = f + '; codecs="' + h + '"';
                    if (Hw() && Hw() && MediaSource.isTypeSupported(b) && Hw() && MediaSource.isTypeSupported(f)) {
                        bi("hvd_ymse");
                        bi("hvd_mse");
                        a = !1;
                        try {
                            -1 != window.location.search.indexOf("goog_limavideo=true") && (a = !0);
                        } catch (k) {}
                        t.customElements
                            ? a
                                ? (a = !0)
                                : (pj.isSelected() && HA().report(153, { limvid: "vd" }),
                                  (a = pj.isSelected() || jj.isSelected() || nj.isSelected() || mj.isSelected() || kj.isSelected() || lj.isSelected() || hj.isSelected() || ij.isSelected() ? !0 : !1))
                            : (a = !1);
                        a && this.h instanceof xx
                            ? ((a = this.h), (a.lb = c), (a.Bb = d))
                            : ((this.Y = new Vx(this.h, [new Kw(c, b, 35e4, new pv()), new Kw(d, f, 82e3, new pv())])), zj(this, this.Y), (c = this.h), (d = this.Y), d.h || (d.h = window.URL.createObjectURL(d.$)), (d = d.h), (c.src = d));
                        this.h.load();
                        return;
                    }
                    bi("hvd_nmse");
                }
            } else bi("hvd_uad");
        a ? (bi("hvd_src"), (this.h.src = a)) : bi("hvd_vn");
        this.h.load();
    };
    l.unload = function () {
        rC(this);
        this.M = !1;
        "removeAttribute" in this.h ? this.h.removeAttribute("src") : (this.h.src = "");
        this.h.load();
    };
    l.setVolume = function (a) {
        this.h.volume = Math.max(a, 0);
        this.h.muted = 0 == a ? !0 : !1;
    };
    l.getVolume = function () {
        return this.isMuted() ? 0 : this.h.volume;
    };
    var sC = function (a) {
        a.V = !1;
        a.M || zb()
            ? ((a.K = !1),
              (a.l = a.h.play()),
              null != a.l &&
                  ((a.L = null),
                  a.l
                      .then(function () {
                          a.l = null;
                          a.Md(a.L);
                          a.L = null;
                      })
                      .catch(function (b) {
                          a.l = null;
                          var c = "";
                          null != b && null != b.name && (c = b.name);
                          "AbortError" == c || "NotAllowedError" == c ? a.dispatchEvent("autoplayDisallowed") : a.Z();
                      })))
            : (a.K = !0);
    };
    l = qC.prototype;
    l.pause = function () {
        null == this.l && ((this.V = !0), this.h.pause());
    };
    l.isMuted = function () {
        return this.h.muted;
    };
    l.getCurrentTime = function () {
        return this.h.currentTime;
    };
    l.getDuration = function () {
        return isNaN(this.h.duration) ? -1 : this.h.duration;
    };
    l.N = function () {
        if (this.Ma) {
            var a = gv.get(this.Ma);
            jv(a);
        }
        tC(this);
        M.prototype.N.call(this);
    };
    var tC = function (a) {
            null != a.C && (iA(a.C), (a.C = null));
            null != a.R && a.R.dispose();
            Mu(a.j);
            rC(a);
        },
        rC = function (a) {
            a.W = !1;
            a.D = !1;
            a.I = !1;
            a.K = !1;
            a.A = 0;
            a.l = null;
            a.L = null;
            xj(a.G);
        };
    qC.prototype.gb = function (a) {
        this.dispatchEvent(a.type);
    };
    var vC = function (a) {
        if (!a.D) {
            a.D = !0;
            a.dispatchEvent("start");
            try {
                if (pj.isSelected() && t.customElements) {
                    var b = t.customElements.get("lima-video");
                    a.h instanceof b ? HA().report(153, { limvid: "limastart" }) : HA().report(153, { limvid: "videostart" });
                }
            } catch (c) {
                HA().report(153, { limvid: "startfail" });
            }
            b = "function" === typeof a.h.getAttribute && null != a.h.getAttribute("playsinline");
            b = void 0 === b ? !1 : b;
            ((jc || Au() || Bu(10)) && (b || (G(Ny), 1))) || (G(Ny), vb(yb(), "Xbox")) || (ic || kc ? 0 : (!hc || (hc && zu(yu, 4))) && (On() ? (G(Ny), !1) : !Qy())) || !hc || (hc && zu(yu, 3)) || ((ic || kc) && !Bu(4)) || uC(a);
        }
    };
    l = qC.prototype;
    l.hf = function () {
        this.M = !0;
        this.K && sC(this);
        this.K = !1;
        wC(this);
    };
    l.jf = function () {
        this.W || ((this.W = !0), this.dispatchEvent("loaded"));
    };
    l.Md = function (a) {
        null != this.l ? (this.L = a) : (this.dispatchEvent("play"), mc || jc || Au() || yc || vC(this));
    };
    l.mf = function (a) {
        if (!this.D && (mc || jc || Au() || yc)) {
            if (0 >= this.getCurrentTime()) return;
            if (yc && this.h.ended && 1 == this.getDuration()) {
                this.Z(a);
                return;
            }
            vC(this);
        }
        if (mc || vb(yb(), "Nintendo WiiU")) {
            if (1.5 < this.getCurrentTime() - this.A) {
                this.I = !0;
                this.h.currentTime = this.A;
                return;
            }
            this.I = !1;
            this.getCurrentTime() > this.A && (this.A = this.getCurrentTime());
        }
        this.dispatchEvent("timeUpdate");
    };
    l.nf = function () {
        this.dispatchEvent("volumeChange");
    };
    l.lf = function () {
        if (this.D && mc && !this.V && (2 > xC(this) || this.I)) {
            this.G = new Gk(250);
            this.j.P(this.G, "tick", this.Fa);
            this.G.start();
            var a = !0;
        } else a = !1;
        a || this.l || this.dispatchEvent("pause");
    };
    l.gf = function () {
        var a = !0;
        if (mc || vb(yb(), "Nintendo WiiU")) a = this.A >= this.h.duration - 1.5;
        !this.I && a && this.dispatchEvent("end");
    };
    var uC = function (a) {
        a.dispatchEvent("beginFullscreen");
    };
    qC.prototype.qa = function () {
        this.dispatchEvent("endFullscreen");
    };
    qC.prototype.Z = function () {
        this.dispatchEvent("error");
    };
    qC.prototype.sa = function () {
        this.dispatchEvent("click");
    };
    var wC = function (a) {
        a.h instanceof HTMLElement &&
            ((a.Ma = kv(a.h, pC)),
            a.Ma.then(function (b) {
                a.Ia() || I(J(), "ps", b.width + "x" + b.height);
            }));
    };
    qC.prototype.ib = function () {
        var a = new y(this.h.offsetWidth, this.h.offsetHeight),
            b = SA(this.h);
        if (a.width != this.T.width || a.height != this.T.height) !this.U && b ? uC(this) : this.U && !b && this.qa(), (this.T = a), (this.U = b);
    };
    qC.prototype.Fa = function () {
        if (!this.h.ended && this.h.paused && (mc || zc ? this.h.currentTime < this.h.duration : 1)) {
            var a = this.h.duration - this.h.currentTime,
                b = xC(this);
            0 < b && (2 <= b || 2 > a) && (xj(this.G), sC(this));
        } else xj(this.G);
    };
    var xC = function (a) {
        var b;
        a: {
            for (b = a.h.buffered.length - 1; 0 <= b; ) {
                if (a.h.buffered.start(b) <= a.h.currentTime) {
                    b = a.h.buffered.end(b);
                    break a;
                }
                b--;
            }
            b = 0;
        }
        return b - a.h.currentTime;
    };
    qC.prototype.kb = function () {
        HA().report(139);
        uC(this);
    };
    var AC = function (a, b, c, d) {
        L.call(this);
        var e = this;
        this.B = a;
        this.o = b;
        this.l = c;
        this.C = d;
        this.A = !1;
        a = new Iu(this);
        zj(this, a);
        this.j = this.h = null;
        yC(this);
        sj.isSelected() &&
            a.P(this.o, "adsManager", function (f) {
                "allAdsCompleted" === f.ha && zC(e);
            });
    };
    r(AC, L);
    var CC = function (a) {
            a.A = !0;
            BC(a);
        },
        yC = function (a) {
            gC(a.B, a.C)
                .then(function (b) {
                    return void DC(a, b);
                })
                .catch(function () {
                    return void EC(a);
                });
        },
        zC = function (a) {
            if (a.h) {
                var b = a.h;
                setTimeout(function () {
                    ff(b);
                }, 3e3);
            }
            yC(a);
        },
        BC = function (a) {
            a.h && a.A && jf(a.h).postMessage({ type: "innerBridgeIframeLoaded" }, "*");
        },
        DC = function (a, b) {
            a.h = b;
            a.j = new hC(b, a.l);
            a.j.P("error", function () {
                return void EC(a);
            });
            jC(a.j);
            BC(a);
        },
        EC = function (a) {
            Vy(a.o, "omid", "iframeFailed");
            a.dispose();
        };
    AC.prototype.N = function () {
        this.h && (ff(this.h), (this.h = null));
        L.prototype.N.call(this);
    };
    var FC = function (a, b, c, d) {
        L.call(this);
        this.o = a;
        this.l = b;
        this.h = c;
        this.C = d;
        this.j = new Iu(this);
        zj(this, this.j);
        this.j.P(this.o, d, this.B);
    };
    r(FC, L);
    var GC = function (a, b) {
        var c = b.ka;
        switch (b.ha) {
            case "showVideo":
                c = a.l;
                null != c.j && c.j.show();
                break;
            case "hide":
                a.l.hide();
                break;
            case "resizeAndPositionVideo":
                a = a.l.h;
                c = c.resizeAndPositionVideo;
                a.h.style.left = String(c.left) + "px";
                a.h.style.top = String(c.top) + "px";
                a.h.style.width = String(c.width) + "px";
                a.h.style.height = String(c.height) + "px";
                break;
            case "restoreSizeAndPositionVideo":
                (c = a.l.h), (c.h.style.width = "100%"), (c.h.style.height = "100%"), (c.h.style.left = "0"), (c.h.style.right = "0");
        }
    };
    FC.prototype.B = function (a) {
        var b = a.ka;
        switch (a.ha) {
            case "activate":
                a = this.l;
                var c = this.h;
                a.h != c &&
                    a.j &&
                    a.o &&
                    a.l &&
                    (c.setVolume(a.h.getVolume()), (c = a.h), (a.h = a.l), (a.l = c), (c = a.j), (a.j = a.o), (a.o = c), a.o.hide(), null != (c = a.H.D) && ((a = a.h.B.h), (c.l = a), c.j && ((c = c.j), (c.h = a), iC(c, a))));
                break;
            case "startTracking":
                a = this.h;
                c = this.A;
                this.j.P(a, Cd(lv), c);
                this.j.P(a, Py, c);
                a = this.h;
                tC(a);
                a.j.P(a.h, Py, a.gb);
                a.j.P(a.h, "ended", a.gf);
                a.j.P(a.h, "webkitbeginfullscreen", a.kb);
                a.j.P(a.h, "webkitendfullscreen", a.qa);
                a.j.P(a.h, "loadedmetadata", a.hf);
                a.j.P(a.h, "pause", a.lf);
                a.j.P(a.h, "playing", a.Md);
                a.j.P(a.h, "timeupdate", a.mf);
                a.j.P(a.h, "volumechange", a.nf);
                a.j.P(a.h, "error", a.Z);
                a.j.P(a.h, yc || (mc && !Bu(8)) ? "loadeddata" : "canplay", a.jf);
                a.C = new eA();
                a.j.P(a.C, "click", a.sa);
                gA(a.C, a.h);
                a.R = new Gk(1e3);
                a.j.P(a.R, "tick", a.ib);
                a.R.start();
                break;
            case "stopTracking":
                a = this.h;
                c = this.A;
                this.j.Wa(a, Cd(lv), c);
                this.j.Wa(a, Py, c);
                tC(this.h);
                break;
            case "exitFullscreen":
                a = this.h;
                (ic || kc) && a.h.webkitDisplayingFullscreen && a.h.webkitExitFullscreen();
                break;
            case "play":
                sC(this.h);
                break;
            case "pause":
                this.h.pause();
                break;
            case "load":
                a = this.h;
                c = b.videoUrl;
                var d = b.muxedMediaUrl,
                    e = b.muxedMimeType,
                    f = b.muxedAudioCodec,
                    g = b.muxedVideoCodec,
                    h = b.demuxedAudioUrl,
                    k = b.demuxedVideoUrl,
                    n = b.demuxedAudioMimeType,
                    m = b.demuxedVideoMimeType,
                    x = b.demuxedAudioCodec,
                    v = b.demuxedVideoCodec;
                b = b.mseCompatible;
                var A = null;
                k && h && b && m && n && v && x && (A = new Lt({ Bf: k, Ae: h, videoItag: null, audioItag: null, Af: m, ze: n, Xa: v, Oa: x, height: null, width: null, xa: b, Oh: null, Eh: null }));
                h = null;
                d && e && g && f && (h = new Mt({ df: d, itag: null, mimeType: e, Xa: g, Oa: f, height: null, width: null, xa: b, Gh: null }));
                A ? a.load(c, A) : h ? a.load(c, h) : a.load(c, null);
                break;
            case "unload":
                this.h.unload();
                break;
            case "setCurrentTime":
                this.h.h.currentTime = b.currentTime;
                break;
            case "setVolume":
                this.h.setVolume(b.volume);
        }
    };
    FC.prototype.A = function (a) {
        var b = {};
        switch (a.type) {
            case "autoplayDisallowed":
                a = "autoplayDisallowed";
                break;
            case "beginFullscreen":
                a = "fullscreen";
                break;
            case "endFullscreen":
                a = "exitFullscreen";
                break;
            case "click":
                a = "click";
                break;
            case "end":
                a = "end";
                break;
            case "error":
                a = "error";
                break;
            case "loaded":
                a = "loaded";
                break;
            case "mediaLoadTimeout":
                a = "mediaLoadTimeout";
                break;
            case "pause":
                a = "pause";
                b.ended = this.h.h.ended;
                break;
            case "play":
                a = "play";
                break;
            case "skip":
                a = "skip";
                break;
            case "start":
                a = "start";
                b.volume = this.h.getVolume();
                break;
            case "timeUpdate":
                a = "timeupdate";
                b.currentTime = this.h.getCurrentTime();
                b.duration = this.h.getDuration();
                break;
            case "volumeChange":
                a = "volumeChange";
                b.volume = this.h.getVolume();
                break;
            case "loadedmetadata":
                a = a.type;
                b.duration = this.h.getDuration();
                break;
            case "abort":
            case "canplay":
            case "canplaythrough":
            case "durationchange":
            case "emptied":
            case "loadstart":
            case "loadeddata":
            case "progress":
            case "ratechange":
            case "seeked":
            case "seeking":
            case "stalled":
            case "suspend":
            case "waiting":
                a = a.type;
                break;
            default:
                return;
        }
        Vy(this.o, this.C, a, b);
    };
    var HC = function (a, b) {
        L.call(this);
        this.j = b;
        this.l = new FC(a, b, this.j.h, "videoDisplay1");
        zj(this, this.l);
        this.h = null;
        var c = this.j.l;
        null != c && ((this.h = new FC(a, b, c, "videoDisplay2")), zj(this, this.h));
    };
    r(HC, L);
    function IC(a) {
        var b,
            c = null == (b = Pd()) ? void 0 : b.createHTML(a);
        return new ie(null != c ? c : a, ge);
    }
    var JC = new rh(),
        KC = new Map(JC.h.o);
    KC.set("style", { na: 4 });
    JC.h = new lh(JC.h.h, JC.h.l, JC.h.j, KC);
    var LC = new Map(JC.h.o);
    LC.set("class", { na: 1 });
    JC.h = new lh(JC.h.h, JC.h.l, JC.h.j, LC);
    var MC = new Map(JC.h.o);
    MC.set("id", { na: 1 });
    JC.h = new lh(JC.h.h, JC.h.l, JC.h.j, MC);
    JC.build();
    var NC = function (a, b, c, d) {
        var e = !0;
        e = void 0 === e ? !1 : e;
        var f = Ef("IFRAME");
        f.id = b;
        f.name = b;
        f.width = String(c);
        f.height = String(d);
        f.allowTransparency = "true";
        f.scrolling = "no";
        f.marginWidth = "0";
        f.marginHeight = "0";
        f.frameBorder = "0";
        f.style.border = "0";
        f.style.verticalAlign = "bottom";
        f.src = "about:blank";
        if (jh(rd) || e) f.setAttribute("role", "region"), f.setAttribute("aria-label", "Advertisement"), (f.title = "3rd party ad content"), (f.tabIndex = 0);
        a.appendChild(f);
        return f;
    };
    function OC() {
        var a, b;
        return null == (a = z().googletag) ? void 0 : null == (b = a.companionAds) ? void 0 : b.call(a);
    }
    function PC(a) {
        var b = {};
        b.slotId = a.getSlotId().getId();
        var c = [];
        a = q(a.getSizes() || []);
        for (var d = a.next(); !d.done; d = a.next())
            if (((d = d.value), "string" !== typeof d)) {
                var e = {};
                c.push(((e.adWidth = d.getWidth()), (e.adHeight = d.getHeight()), e));
            } else "fluid" === d && ((d = {}), c.push(((d.fluidSize = !0), d)));
        return (b.adSizes = c), b;
    }
    function QC(a) {
        var b = OC();
        if (b && a && Array.isArray(a)) {
            var c = new Map(
                b.getSlots().map(function (v) {
                    return [v.getSlotId().getId(), v];
                })
            );
            a = q(a);
            for (var d = a.next(); !d.done; d = a.next()) {
                var e = d.value,
                    f = c.get(e.slotId);
                if (f && !b.isSlotAPersistentRoadblock(f)) {
                    var g = e.adContent;
                    if (g && (d = We(f.getSlotId().getDomId()))) {
                        d.style.display = "";
                        var h = e.adWidth,
                            k = e.adHeight;
                        e.fluidSize && ((k = hl(d)), (h = k.width), (k = k.height));
                        d.textContent = "";
                        if (e.friendlyIframeRendering)
                            try {
                                var n = "google_companion_" + f.getSlotId().getId(),
                                    m = NC(d, n, h, k),
                                    x = m.contentWindow ? m.contentWindow.document : m.contentDocument;
                                ec && x.open("text/html", "replace");
                                we(x, IC(g));
                                x.close();
                            } catch (v) {}
                        else ue(d, IC(g)), (d.style.width = h + "px"), (d.style.height = k + "px");
                        b.slotRenderEnded(f, h, k);
                        (e = e.onAdContentSet) && e(d);
                    }
                }
            }
        }
    }
    var RC = function (a, b, c, d, e, f) {
        Wy.call(this, a, b, c, d, e);
        this.h = f;
    };
    r(RC, Wy);
    var SC = function (a, b) {
        M.call(this);
        this.A = a;
        this.l = b;
        this.h = {};
        this.j = new Iu(this);
        zj(this, this.j);
        this.j.P(z(), "message", this.B);
    };
    r(SC, M);
    var TC = function (a, b) {
            var c = b.h;
            a.h.hasOwnProperty(c) && Vy(a.h[c], b.type, b.ha, b.ka);
        },
        UC = function (a, b, c, d) {
            a.h.hasOwnProperty(b) ||
                ((c = new rB(b, c)),
                a.j.P(c, a.A, function (e) {
                    this.dispatchEvent(new RC(e.type, e.ha, e.ka, e.Mb, e.Nd, b));
                }),
                (c.h = d),
                c.connect(),
                (a.h[b] = c));
        };
    SC.prototype.N = function () {
        for (var a in this.h) xj(this.h[a]);
        M.prototype.N.call(this);
    };
    SC.prototype.B = function (a) {
        a = a.h;
        var b = sB(a.data);
        if (null != b) {
            var c = b.channel;
            if (this.l && !this.h.hasOwnProperty(c)) {
                var d = b.sid;
                UC(this, c, d, a.source);
                this.dispatchEvent(new RC(b.name, b.type, b.data || {}, d, a.origin, c));
            }
        }
    };
    function VC() {
        return !!Ma("googletag.cmd", z());
    }
    function WC() {
        var a = Ma("googletag.console", z());
        return null != a ? a : null;
    }
    var XC = function () {
        Iu.call(this);
        this.l = new SC("gpt", !0);
        zj(this, this.l);
        this.P(this.l, "gpt", this.B);
        this.h = null;
        VC() || z().top === z() || ((this.h = new SC("gpt", !1)), zj(this, this.h), this.P(this.h, "gpt", this.A));
    };
    r(XC, Iu);
    XC.prototype.B = function (a) {
        var b = a.Nd,
            c = "//imasdk.googleapis.com".match(nf);
        b = b.match(nf);
        if (c[3] == b[3] && c[4] == b[4])
            if (null != this.h) UC(this.h, a.h, a.Mb, z().parent), null != this.h && TC(this.h, a);
            else if (((c = a.ka), null != c && void 0 !== c.scope)) {
                b = c.scope;
                c = c.args;
                var d;
                if ("proxy" == b) {
                    var e = a.ha;
                    "isGptPresent" == e ? (d = VC()) : "isConsolePresent" == e && (d = null != WC());
                } else if (VC())
                    if ("pubads" == b || "companionAds" == b) {
                        d = a.ha;
                        var f = z().googletag;
                        if (null != f && null != f[b] && ((b = f[b]()), null != b && ((d = b[d]), null != d)))
                            try {
                                e = d.apply(b, c);
                            } catch (g) {}
                        d = e;
                    } else if ("console" == b) {
                        if (((e = WC()), null != e && ((b = e[a.ha]), null != b)))
                            try {
                                b.apply(e, c);
                            } catch (g) {}
                    } else null === b && ((e = a.ha), "googleGetCompanionAdSlots" == e ? ((e = OC()) ? ((e = e.getSlots().map(PC)), (d = e.length ? e : null)) : (d = null)) : ("googleSetCompanionAdContents" == e && QC(c[0]), (d = null)));
                void 0 !== d && ((a.ka.returnValue = d), TC(this.l, a));
            }
    };
    XC.prototype.A = function (a) {
        TC(this.l, a);
    };
    var YC = function (a, b) {
        if (a.h) {
            var c = a.h;
            xj(c.h[b]);
            delete c.h[b];
        }
        a.l && ((a = a.l), xj(a.h[b]), delete a.h[b]);
    };
    G(ih);
    var ZC = [
        "A7D05fL9zBqt11RE193XmJzeo4RPtGLsV822r1Bivfn7OUM0YRLJQcJVPgMdvq7u5hLUS/KmNIpe9fz+VE/dUg4AAACJeyJvcmlnaW4iOiJodHRwczovL2ltYXNkay5nb29nbGVhcGlzLmNvbTo0NDMiLCJmZWF0dXJlIjoiQ29udmVyc2lvbk1lYXN1cmVtZW50IiwiZXhwaXJ5IjoxNjQzMTU1MTk5LCJpc1RoaXJkUGFydHkiOnRydWUsInVzYWdlIjoic3Vic2V0In0=",
    ];
    function $C() {
        var a = void 0 === a ? window.navigator.userAgent : a;
        return (a = a.match(/Chrome\/([0-9]+)/)) && 92 > Number(a[1]) ? "conversion-measurement" : "attribution-reporting";
    }
    var aD = { issuerOrigin: "https://attestation.android.com", issuancePath: "/att/i", redemptionPath: "/att/r" },
        bD = { issuerOrigin: "https://pagead2.googlesyndication.com", issuancePath: "/dtt/i", redemptionPath: "/dtt/r", getStatePath: "/dtt/s" };
    var cD = G(ih).l(),
        eD = function (a, b, c) {
            L.call(this);
            var d = this;
            this.j = a;
            this.h = [];
            b && dD() && this.h.push(aD);
            c && this.h.push(bD);
            if (document.hasTrustToken && !jh(sd)) {
                var e = new Map();
                this.h.forEach(function (f) {
                    e.set(f.issuerOrigin, { issuerOrigin: f.issuerOrigin, state: d.j ? 1 : 12, hasRedemptionRecord: !1 });
                });
                window.goog_tt_state_map = window.goog_tt_state_map && window.goog_tt_state_map instanceof Map ? new Map([].concat(ha(e), ha(window.goog_tt_state_map))) : e;
                (window.goog_tt_promise_map && window.goog_tt_promise_map instanceof Map) || (window.goog_tt_promise_map = new Map());
            }
        };
    r(eD, L);
    var dD = function () {
            var a = void 0 === a ? window : a;
            a = a.navigator.userAgent;
            var b = /Chrome/.test(a);
            return /Android/.test(a) && b;
        },
        fD = function (a, b, c) {
            var d,
                e = null == (d = window.goog_tt_state_map) ? void 0 : d.get(a);
            e && ((e.state = b), void 0 != c && (e.hasRedemptionRecord = c));
        },
        gD = function () {
            var a = "" + aD.issuerOrigin + aD.redemptionPath,
                b = { keepalive: !0, trustToken: { type: "token-redemption", issuer: aD.issuerOrigin, refreshPolicy: "none" } };
            fD(aD.issuerOrigin, 2);
            return window
                .fetch(a, b)
                .then(function (c) {
                    if (!c.ok) throw Error(c.status + ": Network response was not ok!");
                    fD(aD.issuerOrigin, 6, !0);
                })
                .catch(function (c) {
                    c && "NoModificationAllowedError" === c.name ? fD(aD.issuerOrigin, 6, !0) : fD(aD.issuerOrigin, 5);
                });
        },
        hD = function () {
            var a = "" + aD.issuerOrigin + aD.issuancePath;
            fD(aD.issuerOrigin, 8);
            return window
                .fetch(a, { keepalive: !0, trustToken: { type: "token-request" } })
                .then(function (b) {
                    if (!b.ok) throw Error(b.status + ": Network response was not ok!");
                    fD(aD.issuerOrigin, 10);
                    return gD();
                })
                .catch(function (b) {
                    if (b && "NoModificationAllowedError" === b.name) return fD(aD.issuerOrigin, 10), gD();
                    fD(aD.issuerOrigin, 9);
                });
        },
        iD = function () {
            fD(aD.issuerOrigin, 13);
            return document.hasTrustToken(aD.issuerOrigin).then(function (a) {
                return a ? gD() : hD();
            });
        },
        jD = function () {
            fD(bD.issuerOrigin, 13);
            if (window.Promise) {
                var a = document
                        .hasTrustToken(bD.issuerOrigin)
                        .then(function (e) {
                            return e;
                        })
                        .catch(function (e) {
                            return window.Promise.reject({ state: 19, error: e });
                        }),
                    b = "" + bD.issuerOrigin + bD.redemptionPath,
                    c = { keepalive: !0, trustToken: { type: "token-redemption", refreshPolicy: "none" } };
                fD(bD.issuerOrigin, 16);
                a = a
                    .then(function (e) {
                        return window
                            .fetch(b, c)
                            .then(function (f) {
                                if (!f.ok) throw Error(f.status + ": Network response was not ok!");
                                fD(bD.issuerOrigin, 18, !0);
                            })
                            .catch(function (f) {
                                if (f && "NoModificationAllowedError" === f.name) fD(bD.issuerOrigin, 18, !0);
                                else {
                                    if (e) return window.Promise.reject({ state: 17, error: f });
                                    fD(bD.issuerOrigin, 17);
                                }
                            });
                    })
                    .then(function () {
                        return document
                            .hasTrustToken(bD.issuerOrigin)
                            .then(function (e) {
                                return e;
                            })
                            .catch(function (e) {
                                return window.Promise.reject({ state: 19, error: e });
                            });
                    })
                    .then(function (e) {
                        var f = "" + bD.issuerOrigin + bD.getStatePath;
                        fD(bD.issuerOrigin, 20);
                        return window
                            .fetch(f + "?ht=" + e, { trustToken: { type: "send-redemption-record", issuers: [bD.issuerOrigin] } })
                            .then(function (g) {
                                if (!g.ok) throw Error(g.status + ": Network response was not ok!");
                                fD(bD.issuerOrigin, 22);
                                return g.text().then(function (h) {
                                    return JSON.parse(h);
                                });
                            })
                            .catch(function (g) {
                                return window.Promise.reject({ state: 21, error: g });
                            });
                    });
                var d = Gf();
                return a
                    .then(function (e) {
                        var f = "" + bD.issuerOrigin + bD.issuancePath;
                        return e && e.srqt && e.cs
                            ? (fD(bD.issuerOrigin, 23),
                              window
                                  .fetch(f + "?cs=" + e.cs + "&correlator=" + d, { keepalive: !0, trustToken: { type: "token-request" } })
                                  .then(function (g) {
                                      if (!g.ok) throw Error(g.status + ": Network response was not ok!");
                                      fD(bD.issuerOrigin, 25);
                                      return e;
                                  })
                                  .catch(function (g) {
                                      return window.Promise.reject({ state: 24, error: g });
                                  }))
                            : e;
                    })
                    .then(function (e) {
                        if (e && e.srdt && e.cs)
                            return (
                                fD(bD.issuerOrigin, 26),
                                window
                                    .fetch(b + "?cs=" + e.cs + "&correlator=" + d, { keepalive: !0, trustToken: { type: "token-redemption", refreshPolicy: "refresh" } })
                                    .then(function (f) {
                                        if (!f.ok) throw Error(f.status + ": Network response was not ok!");
                                        fD(bD.issuerOrigin, 28, !0);
                                    })
                                    .catch(function (f) {
                                        return window.Promise.reject({ state: 27, error: f });
                                    })
                            );
                    })
                    .then(function () {
                        fD(bD.issuerOrigin, 29);
                    })
                    .catch(function (e) {
                        if (e instanceof Object && e.hasOwnProperty("state") && e.hasOwnProperty("error"))
                            if ("number" === typeof e.state && e.error instanceof Error) {
                                fD(bD.issuerOrigin, e.state);
                                var f = kh(td);
                                Math.random() <= f && Uf({ state: e.state, err: e.error.toString() }, "dtt_err");
                            } else throw Error(e);
                        else throw e;
                    });
            }
        },
        kD = function (a) {
            if (document.hasTrustToken && !jh(sd) && a.j) {
                var b = window.goog_tt_promise_map;
                if (b && b instanceof Map) {
                    var c = [];
                    if (
                        a.h.some(function (e) {
                            return e.issuerOrigin === aD.issuerOrigin;
                        })
                    ) {
                        var d = b.get(aD.issuerOrigin);
                        d || ((d = iD()), b.set(aD.issuerOrigin, d));
                        c.push(d);
                    }
                    a.h.some(function (e) {
                        return e.issuerOrigin === bD.issuerOrigin;
                    }) && ((a = b.get(bD.issuerOrigin)), a || ((a = jD()), b.set(bD.issuerOrigin, a)), c.push(a));
                    if (0 < c.length && window.Promise && window.Promise.all) return window.Promise.all(c);
                }
            }
        };
    var mD = function (a, b) {
            var c = Array.prototype.slice.call(arguments),
                d = c.shift();
            if ("undefined" == typeof d) throw Error("[goog.string.format] Template required");
            return d.replace(/%([0\- \+]*)(\d+)?(\.(\d+))?([%sfdiu])/g, function (e, f, g, h, k, n, m, x) {
                if ("%" == n) return "%";
                var v = c.shift();
                if ("undefined" == typeof v) throw Error("[goog.string.format] Not enough arguments");
                arguments[0] = v;
                return lD[n].apply(null, arguments);
            });
        },
        lD = {
            s: function (a, b, c) {
                return isNaN(c) || "" == c || a.length >= Number(c) ? a : (a = -1 < b.indexOf("-", 0) ? a + Me(" ", Number(c) - a.length) : Me(" ", Number(c) - a.length) + a);
            },
            f: function (a, b, c, d, e) {
                d = a.toString();
                isNaN(e) || "" == e || (d = parseFloat(a).toFixed(e));
                var f = 0 > Number(a) ? "-" : 0 <= b.indexOf("+") ? "+" : 0 <= b.indexOf(" ") ? " " : "";
                0 <= Number(a) && (d = f + d);
                if (isNaN(c) || d.length >= Number(c)) return d;
                d = isNaN(e) ? Math.abs(Number(a)).toString() : Math.abs(Number(a)).toFixed(e);
                a = Number(c) - d.length - f.length;
                return (d = 0 <= b.indexOf("-", 0) ? f + d + Me(" ", a) : f + Me(0 <= b.indexOf("0", 0) ? "0" : " ", a) + d);
            },
            d: function (a, b, c, d, e, f, g, h) {
                return lD.f(parseInt(a, 10), b, c, d, 0, f, g, h);
            },
        };
    lD.i = lD.d;
    lD.u = lD.d;
    function nD() {
        return ["autoplay", "trust-token-redemption", $C()]
            .filter(function (a) {
                var b = document.featurePolicy;
                return void 0 !== b && "function" == typeof b.allowedFeatures && "object" == typeof b.allowedFeatures() && b.allowedFeatures().includes(a);
            })
            .join(";");
    }
    var pD = function (a, b) {
        M.call(this);
        this.l = new Iu(this);
        zj(this, this.l);
        this.L = this.K = null;
        this.I = !1;
        this.C = "goog_" + Oe++;
        this.B = new Map();
        var c = this.C,
            d = (zf() ? "https:" : "http:") + mD("//imasdk.googleapis.com/js/core/bridge3.507.1_%s.html", fy.o);
        a: {
            var e = window;
            try {
                do {
                    try {
                        if (0 == e.location.href.indexOf(d) || 0 == e.document.referrer.indexOf(d)) {
                            var f = !0;
                            break a;
                        }
                    } catch (h) {}
                    e = e.parent;
                } while (e != e.top);
            } catch (h) {}
            f = !1;
        }
        f && (d += "?f=" + c);
        var g = void 0 === g ? window.document : g;
        Ff(cD, g);
        f = {};
        HA().report(158, ((f.aot = "ob"), (f.tte = !!document.hasTrustToken), f));
        f = window.document;
        f = void 0 === f ? window.document : f;
        Ff(ZC, f);
        f = nD();
        c = ef("IFRAME", { src: d + "#" + c, allowFullscreen: !0, allow: f, id: c, style: "border:0; opacity:0; margin:0; padding:0; position:relative; color-scheme: light;" });
        this.l.Gb(c, "load", this.W);
        a.appendChild(c);
        this.h = c;
        this.A = oD(this);
        this.G = b;
        this.j = null;
        this.M = new HC(this.A, this.G);
        zj(this, this.M);
        this.G.h && this.l.P(this.A, "displayContainer", this.T);
        this.l.P(this.A, "mouse", this.U);
        this.l.P(this.A, "touch", this.V);
        c = z();
        d = Ma("google.ima.gptProxyInstance", c);
        null != d ? (c = d) : ((d = new XC()), u("google.ima.gptProxyInstance", d, c), (c = d));
        this.R = c;
        Qy() || ((this.D = new AC(a, this.A, b.h.B.h, this.C)), zj(this, this.D));
    };
    r(pD, M);
    var oD = function (a, b) {
        b = void 0 === b ? "*" : b;
        var c = a.B.get(b);
        null == c && ((c = new rB(a.C, b)), a.I && ((c.h = jf(a.h)), c.connect()), a.B.set(b, c));
        return c;
    };
    pD.prototype.N = function () {
        null !== this.j && (this.j.dispose(), (this.j = null));
        this.B.forEach(function (a) {
            xj(a);
        });
        this.B.clear();
        YC(this.R, this.C);
        ff(this.h);
        M.prototype.N.call(this);
    };
    pD.prototype.U = function (a) {
        var b = a.ka,
            c = $k(this.h),
            d = document.createEvent("MouseEvent");
        d.initMouseEvent(a.ha, !0, !0, window, b.detail, b.screenX, b.screenY, b.clientX + c.x, b.clientY + c.y, b.ctrlKey, b.altKey, b.shiftKey, b.metaKey, b.button, null);
        this.h.dispatchEvent(d);
    };
    var qD = function (a, b) {
        var c = $k(a.h),
            d = !!("TouchEvent" in window && 0 < TouchEvent.length);
        b = b.map(function (e) {
            return d
                ? new Touch({ identifier: e.identifier, target: a.h, clientX: e.clientX, clientY: e.clientY, screenX: e.screenX, screenY: e.screenY, pageX: e.pageX + c.x, pageY: e.pageY + c.y })
                : document.createTouch(window, a.h, e.identifier, e.pageX + c.x, e.pageY + c.y, e.screenX, e.screenY);
        });
        return d ? b : document.createTouchList.apply(document, b);
    };
    pD.prototype.V = function (a) {
        var b = a.ka,
            c = $k(this.h);
        if ("TouchEvent" in window && 0 < TouchEvent.length)
            (b = {
                bubbles: !0,
                cancelable: !0,
                view: window,
                detail: b.detail,
                ctrlKey: b.ctrlKey,
                altKey: b.altKey,
                shiftKey: b.shiftKey,
                metaKey: b.metaKey,
                touches: qD(this, b.touches),
                targetTouches: qD(this, b.targetTouches),
                changedTouches: qD(this, b.changedTouches),
            }),
                (a = new TouchEvent(a.ha, b)),
                this.h.dispatchEvent(a);
        else {
            var d = document.createEvent("TouchEvent");
            d.initTouchEvent(
                a.ha,
                !0,
                !0,
                window,
                b.detail,
                b.screenX,
                b.screenY,
                b.clientX + c.x,
                b.clientY + c.y,
                b.ctrlKey,
                b.altKey,
                b.shiftKey,
                b.metaKey,
                qD(this, b.touches),
                qD(this, b.targetTouches),
                qD(this, b.changedTouches),
                b.scale,
                b.rotation
            );
            this.h.dispatchEvent(d);
        }
    };
    pD.prototype.T = function (a) {
        switch (a.ha) {
            case "showVideo":
                null == this.j ? ((this.j = new eA()), this.l.P(this.j, "click", this.Y)) : iA(this.j);
                gA(this.j, rD(this.G));
                break;
            case "hide":
                null !== this.j && (this.j.dispose(), (this.j = null));
        }
        var b = this.M;
        GC(b.l, a);
        b.h && GC(b.h, a);
    };
    pD.prototype.Y = function () {
        Vy(this.A, "displayContainer", "videoClick");
    };
    pD.prototype.W = function () {
        var a = this;
        this.K = Dh();
        this.L = Ah();
        this.B.forEach(function (c) {
            c.h = jf(a.h);
            c.connect();
        });
        var b;
        null == (b = this.D) || CC(b);
        this.I = !0;
    };
    var tD = function () {
        M.call(this);
        this.buffered = new sD();
        this.seekable = new sD();
        this.j = new Iu(this);
        zj(this, this.j);
        this.src = this.l = "";
        this.A = !1;
        this.h = null;
        var a = ey(fy);
        if (a) {
            a: {
                if (Fd(a.h, "videoElementFakeDuration") && ((a = a.h.videoElementFakeDuration), "number" === typeof a)) break a;
                a = NaN;
            }
            this.duration = a;
        }
    };
    r(tD, M);
    var uD = function () {
        var a = ["video/mp4"],
            b = ["video/ogg"],
            c = new tD();
        c.canPlayType = function (d) {
            return a.includes(d) ? "probably" : b.includes(d) ? "maybe" : "";
        };
        c.width = 0;
        c.height = 0;
        c.offsetWidth = 0;
        c.offsetHeight = 0;
        return c;
    };
    l = tD.prototype;
    l.pause = function () {
        this.autoplay = !1;
        this.paused || (null.stop(), (this.paused = !0), this.dispatchEvent("timeupdate"), this.dispatchEvent("pause"));
    };
    l.load = function () {
        this.readyState = 0;
        this.paused = !0;
        this.seeking = !1;
        this.dispatchEvent("loadstart");
        var a;
        isNaN(this.duration) ? (a = 10 + 20 * Math.random()) : (a = this.duration);
        this.setProperty("duration", a);
        a = this.seekable;
        a.h.push(new vD(this.duration));
        a.length = a.h.length;
        a = this.buffered;
        a.h.push(new vD(this.duration));
        a.length = a.h.length;
        this.dispatchEvent("loadedmetadata");
        0 < this.currentTime && this.dispatchEvent("timeupdate");
        this.dispatchEvent("loadeddata");
        this.dispatchEvent("canplay");
        this.dispatchEvent("canplaythrough");
        this.dispatchEvent("progress");
        this.playbackRate = this.defaultPlaybackRate;
    };
    l.setProperty = function (a, b) {
        switch (a) {
            case "currentTime":
                a = Number(b);
                this.seeking = !0;
                this.dispatchEvent("seeking");
                this.seeking = !1;
                this.currentTime = a;
                this.dispatchEvent("seeked");
                a = Za() - this.Lc;
                b = this.currentTime + a / 1e3;
                this.Lc += a;
                2 < this.readyState && (this.currentTime = Math.min(b, this.duration));
                this.dispatchEvent("timeupdate");
                this.currentTime == this.duration && ((this.ended = this.paused = !0), null.stop(), this.dispatchEvent("ended"));
                break;
            case "duration":
                this.duration = Number(b);
                this.dispatchEvent("durationchange");
                break;
            case "volume":
                this.volume = Number(b);
                this.dispatchEvent("volumechange");
                break;
            default:
                throw "Property setter not implemented";
        }
    };
    l.setAttribute = function (a, b) {
        null != a && wD.set(a, b);
    };
    l.getAttribute = function (a) {
        return wD.get(a);
    };
    l.he = function (a) {
        var b = null,
            c = null;
        switch (a.type) {
            case "loadeddata":
                b = "Loaded";
                break;
            case "playing":
                b = "Playing";
                c = "#00f";
                break;
            case "pause":
                b = "Paused";
                break;
            case "ended":
                (b = "Ended"), (c = "#000");
        }
        b && this.lc && (this.lc.innerText = b);
        c && this.Ab && (this.Ab.style.backgroundColor = c);
    };
    var wD = new gB(),
        vD = function (a) {
            this.startTime = 0;
            this.endTime = a;
        },
        sD = function () {
            this.length = 0;
            this.h = [];
        };
    sD.prototype.start = function (a) {
        return this.h[a].startTime;
    };
    sD.prototype.end = function (a) {
        return this.h[a].endTime;
    };
    l = tD.prototype;
    l.readyState = 0;
    l.seeking = !1;
    l.currentTime = 0;
    l.initialTime = void 0;
    l.duration = NaN;
    l.paused = !0;
    l.ended = !1;
    l.autoplay = !1;
    l.loop = !1;
    l.volume = 1;
    l.muted = !1;
    Object.defineProperty(tD.prototype, "src", {
        get: function () {
            return tD.prototype.l;
        },
        set: function (a) {
            var b = tD.prototype;
            b.A && null != b.h ? (b.h.reject(), (b.h = null)) : (b.l = a);
        },
    });
    l = tD.prototype;
    l.currentSrc = "";
    l.defaultPlaybackRate = 1;
    l.playbackRate = 0;
    l.Lc = 0;
    l.Ab = null;
    l.lc = null;
    var zD = function (a, b) {
        L.call(this);
        this.o = a;
        this.l = this.h = null;
        this.j = xD();
        yD(this, b);
        lx(function () {
            I(J(), "haob", "1");
        });
    };
    r(zD, L);
    zD.prototype.initialize = function () {
        this.j && this.j.load();
    };
    zD.prototype.N = function () {
        ff(this.h);
        L.prototype.N.call(this);
    };
    var yD = function (a, b) {
            a.h = ef("DIV", { style: "display:none;" });
            a.o.appendChild(a.h);
            a.h.appendChild(a.j);
            b && ((a.l = ef("DIV", { style: "position:absolute;width:100%;height:100%;left:0px;top:0px" })), a.h.appendChild(a.l));
        },
        xD = function () {
            var a = ey(fy);
            if (by(a, "useVideoElementFake")) {
                a = uD();
                var b = ef("DIV", { style: "position:absolute;width:100%;height:100%;top:0px;left:0px;" });
                Object.assign(b, a);
                a.Ab = ef("DIV", { style: "position:absolute;width:100%;height:100%;top:0px;left:0px;background-color:#000" });
                a.lc = ef("P", { style: "position:absolute;top:25%;margin-left:10px;font-size:24px;color:#fff;" });
                a.Ab.appendChild(a.lc);
                b.appendChild(a.Ab);
                a.j.P(a, ["loadeddata", "playing", "pause", "ended"], a.he);
                a = b;
            } else {
                a = !1;
                try {
                    -1 != window.location.search.indexOf("goog_limavideo=true") && (a = !0);
                } catch (c) {}
                t.customElements ? (a ? (b = !0) : (pj.isSelected() && HA().report(153, { limvid: "vw" }), (b = jj.isSelected() || pj.isSelected() || hj.isSelected() || ij.isSelected() ? !0 : !1))) : (b = !1);
                if (b) {
                    a && console.log("force lima video in wrapper");
                    a = null;
                    try {
                        a = new xx();
                    } catch (c) {
                        (a = ef("lima-video")), pj.isSelected() && HA().report(153, { limvid: "firefail" });
                    }
                    a.style.backgroundColor = "#000";
                    a.style.height = "100%";
                    a.style.width = "100%";
                    a.style.position = "absolute";
                    a.style.left = "0";
                    a.style.top = "0";
                } else a = ef("VIDEO", { style: "background-color:#000;position:absolute;width:100%;height:100%;left:0;top:0;", title: Zx("Advertisement").toString() });
            }
            a.setAttribute("webkit-playsinline", !0);
            a.setAttribute("playsinline", !0);
            return a;
        };
    zD.prototype.show = function () {
        var a = this.h;
        null != a && (a.style.display = "block");
    };
    zD.prototype.hide = function () {
        var a = this.h;
        null != a && (a.style.display = "none");
    };
    var CD = function (a, b, c) {
        var d = a && a.getRootNode ? a.getRootNode({ composed: !0 }) : a;
        if (null == a || !hf(Ue(d), d)) throw oz(nz, null, "containerElement", "element");
        this.B = b;
        this.W = Sy(this.B || null);
        this.V = Cu(this.B || null);
        this.U = String(Math.floor(1e9 * Math.random()));
        this.L = !1;
        this.G = a;
        this.T = null != b;
        fy.h = 2;
        this.D = AD(b ? b : null);
        d = ef("DIV", { style: "position:absolute" });
        a.insertBefore(d, a.firstChild);
        this.A = d;
        this.j = null;
        BD(this) && b ? (a = new qC(b)) : ((this.j = new zD(this.A, !0)), (a = new qC(this.j.j)));
        this.h = a;
        this.l = this.o = null;
        if ((a = this.j && fy.isAutoPlayAdBreaks())) a = !(BD(this) || ic || kc || Pn() || (hc && (!hc || !zu(yu, 4))));
        a && ((this.o = new zD(this.A, !0)), (this.l = new qC(this.o.j)));
        this.C = c || null;
        this.R = null != this.C;
        BD(this) && b ? ("function" !== typeof b.getBoundingClientRect ? ((c = this.A), (fy.l = c)) : (c = b)) : (c = this.A);
        this.J = c;
        this.H = new pD(this.A, this);
        this.M = new y(0, 0);
        this.I = "";
        b && ((b = qt(b.src || b.currentSrc)), 200 > b.toString().length ? (this.I = b.toString()) : 200 > b.j.length && (this.I = b.j));
        this.K = new Map();
        this.K.set("videoDisplay1", this.h);
        this.l && this.K.set("videoDisplay2", this.l);
    };
    CD.prototype.initialize = function () {
        this.L = !0;
        null != this.j && this.j.initialize();
        null != this.o && this.o.initialize();
    };
    CD.prototype.isInitialized = function () {
        return this.L;
    };
    CD.prototype.destroy = function () {
        var a = this;
        this.B = null;
        xj(this.j);
        xj(this.o);
        xj(this.H);
        this.h.Nb(function () {
            return xj(a.h);
        });
        null != this.l &&
            this.l.Nb(function () {
                return xj(a.l);
            });
        ff(this.A);
    };
    CD.prototype.hide = function () {
        null != this.j && this.j.hide();
    };
    var rD = function (a) {
            return a.R && a.C ? a.C : null != a.j ? a.j.l : null;
        },
        BD = function (a) {
            return Ry(a.D) && a.T;
        },
        AD = function (a) {
            return null != a && "function" === typeof a.getAttribute && null != a.getAttribute("playsinline") ? !0 : !1;
        };
    CD.prototype.destroy = CD.prototype.destroy;
    CD.prototype.initialize = CD.prototype.initialize;
    var DD = function (a) {
        var b = Error.call(this);
        this.message = b.message;
        "stack" in b && (this.stack = b.stack);
        this.h = a;
    };
    r(DD, Error);
    l = DD.prototype;
    l.getInnerError = function () {
        var a = this.h.innerError;
        return a instanceof Object ? new DD(a) : null != a ? Error(a) : null;
    };
    l.getMessage = function () {
        return this.h.errorMessage;
    };
    l.getErrorCode = function () {
        return this.h.errorCode;
    };
    l.Id = function () {
        var a = this.getErrorCode();
        return 1e3 > a ? a : 900;
    };
    l.getType = function () {
        return this.h.type;
    };
    l.toString = function () {
        return "AdError " + this.getErrorCode() + ": " + this.getMessage() + (null != this.getInnerError() ? " Caused by: " + this.getInnerError() : "");
    };
    DD.prototype.getType = DD.prototype.getType;
    DD.prototype.getVastErrorCode = DD.prototype.Id;
    DD.prototype.getErrorCode = DD.prototype.getErrorCode;
    DD.prototype.getMessage = DD.prototype.getMessage;
    DD.prototype.getInnerError = DD.prototype.getInnerError;
    var ED = { AD_LOAD: "adLoadError", AD_PLAY: "adPlayError" };
    u("module$contents$ima$AdError_AdError.Type", ED, void 0);
    var FD = function (a, b) {
        b = void 0 === b ? null : b;
        Aj.call(this, "adError");
        this.h = a;
        this.l = b;
    };
    r(FD, Aj);
    FD.prototype.getError = function () {
        return this.h;
    };
    FD.prototype.getUserRequestContext = function () {
        return this.l;
    };
    FD.prototype.getUserRequestContext = FD.prototype.getUserRequestContext;
    FD.prototype.getError = FD.prototype.getError;
    var GD = { AD_ERROR: "adError" };
    u("module$contents$ima$AdErrorEvent_AdErrorEvent.Type", GD, void 0);
    var HD = function (a, b, c) {
        b = void 0 === b ? null : b;
        c = void 0 === c ? null : c;
        Aj.call(this, a);
        this.l = b;
        this.h = c;
    };
    r(HD, Aj);
    HD.prototype.getAd = function () {
        return this.l;
    };
    HD.prototype.getAdData = function () {
        return this.h;
    };
    HD.prototype.getAdData = HD.prototype.getAdData;
    HD.prototype.getAd = HD.prototype.getAd;
    var ID = {
        AD_CAN_PLAY: "adCanPlay",
        Hf: "adStarted",
        CONTENT_PAUSE_REQUESTED: "contentPauseRequested",
        CONTENT_RESUME_REQUESTED: "contentResumeRequested",
        CLICK: "click",
        VIDEO_CLICKED: "videoClicked",
        VIDEO_ICON_CLICKED: "videoIconClicked",
        jd: "engagedView",
        EXPANDED_CHANGED: "expandedChanged",
        STARTED: "start",
        AD_PROGRESS: "adProgress",
        AD_BUFFERING: "adBuffering",
        IMPRESSION: "impression",
        od: "measurable_impression",
        VIEWABLE_IMPRESSION: "viewable_impression",
        kd: "fully_viewable_audible_half_duration_impression",
        le: "overlay_resize",
        me: "overlay_unmeasurable_impression",
        ne: "overlay_unviewable_impression",
        pe: "overlay_viewable_immediate_impression",
        oe: "overlay_viewable_end_of_session_impression",
        eg: "externalActivityEvent",
        PAUSED: "pause",
        RESUMED: "resume",
        FIRST_QUARTILE: "firstQuartile",
        MIDPOINT: "midpoint",
        THIRD_QUARTILE: "thirdQuartile",
        COMPLETE: "complete",
        DURATION_CHANGE: "durationChange",
        USER_CLOSE: "userClose",
        vh: "userRecall",
        Zg: "prefetched",
        LOADED: "loaded",
        ALL_ADS_COMPLETED: "allAdsCompleted",
        SKIPPED: "skip",
        se: "skipShown",
        LINEAR_CHANGED: "linearChanged",
        SKIPPABLE_STATE_CHANGED: "skippableStateChanged",
        AD_METADATA: "adMetadata",
        Gf: "adBreakFetchError",
        AD_BREAK_READY: "adBreakReady",
        LOG: "log",
        VOLUME_CHANGED: "volumeChange",
        VOLUME_MUTED: "mute",
        INTERACTION: "interaction",
        Rf: "companionBackfill",
        sh: "trackingUrlPinged",
        yh: "video_card_endcap_collapse",
        zh: "video_card_endcap_dismiss",
        Ah: "video_card_endcap_impression",
        Uf: "companionInitialized",
        Tf: "companionImpression",
        Sf: "companionClick",
        Lg: "mediaUrlPinged",
        ie: "loadStart",
        Og: "navigationRequested",
    };
    u("module$contents$ima$AdEvent_AdEvent.Type", ID, void 0);
    var JD = function (a, b) {
        b = void 0 === b ? null : b;
        HD.call(this, "adMetadata", a);
        this.o = b;
    };
    r(JD, HD);
    JD.prototype.Je = function () {
        return this.o;
    };
    JD.prototype.getAdCuePoints = JD.prototype.Je;
    var KD = function (a) {
        this.adBreakDuration = a.adBreakDuration;
        this.adPosition = a.adPosition;
        this.currentTime = a.currentTime;
        this.duration = a.duration;
        this.totalAds = a.totalAds;
    };
    var LD = function (a, b) {
        M.call(this);
        this.l = a;
        this.B = b;
        this.j = this.l.currentTime;
        this.h = new Gk(250);
        zj(this, this.h);
        this.A = new Iu(this);
        zj(this, this.A);
        Ku(this.A, this.h, "tick", this.C, !1, this);
    };
    r(LD, M);
    LD.prototype.Za = function () {
        return this.j;
    };
    LD.prototype.start = function () {
        MD(this);
        this.h.start();
    };
    LD.prototype.stop = function () {
        this.h.stop();
    };
    LD.prototype.C = function () {
        var a = this.l.currentTime;
        a != this.Za() && ((this.j = a), MD(this));
    };
    var MD = function (a) {
        var b = {};
        b.currentTime = a.Za();
        Vy(a.B, "contentTimeUpdate", "contentTimeUpdate", b);
    };
    var ND = {
            rgb: !0,
            rgba: !0,
            alpha: !0,
            rect: !0,
            image: !0,
            "linear-gradient": !0,
            "radial-gradient": !0,
            "repeating-linear-gradient": !0,
            "repeating-radial-gradient": !0,
            "cubic-bezier": !0,
            matrix: !0,
            perspective: !0,
            rotate: !0,
            rotate3d: !0,
            rotatex: !0,
            rotatey: !0,
            steps: !0,
            rotatez: !0,
            scale: !0,
            scale3d: !0,
            scalex: !0,
            scaley: !0,
            scalez: !0,
            skew: !0,
            skewx: !0,
            skewy: !0,
            translate: !0,
            translate3d: !0,
            translatex: !0,
            translatey: !0,
            translatez: !0,
        },
        OD = function (a) {
            a = nb(a);
            if ("" == a) return null;
            var b = String(a.substr(0, 4)).toLowerCase();
            if (0 == ("url(" < b ? -1 : "url(" == b ? 0 : 1)) return null;
            if (0 < a.indexOf("(")) {
                if (/"|'/.test(a)) return null;
                b = /([\-\w]+)\(/g;
                for (var c; (c = b.exec(a)); ) if (!(c[1].toLowerCase() in ND)) return null;
            }
            return a;
        };
    function PD(a, b) {
        a = t[a];
        return a && a.prototype ? ((b = Object.getOwnPropertyDescriptor(a.prototype, b)) && b.get) || null : null;
    }
    function QD(a) {
        var b = t.CSSStyleDeclaration;
        return (b && b.prototype && b.prototype[a]) || null;
    }
    PD("Element", "attributes") || PD("Node", "attributes");
    PD("Element", "innerHTML") || PD("HTMLElement", "innerHTML");
    PD("Node", "nodeName");
    PD("Node", "nodeType");
    PD("Node", "parentNode");
    PD("Node", "childNodes");
    PD("HTMLElement", "style") || PD("Element", "style");
    PD("HTMLStyleElement", "sheet");
    var RD = QD("getPropertyValue"),
        SD = QD("setProperty");
    PD("Element", "namespaceURI") || PD("Node", "namespaceURI");
    function TD(a, b, c, d) {
        if (a) return a.apply(b, d);
        if (cc && 10 > document.documentMode) {
            if (!b[c].call) throw Error("IE Clobbering detected");
        } else if ("function" != typeof b[c]) throw Error("Clobbering detected");
        return b[c].apply(b, d);
    }
    var UD = { "-webkit-border-horizontal-spacing": !0, "-webkit-border-vertical-spacing": !0 },
        WD = function (a) {
            if (!a) return be;
            var b = document.createElement("div").style;
            VD(a).forEach(function (c) {
                var d = fc && c in UD ? c : c.replace(/^-(?:apple|css|epub|khtml|moz|mso?|o|rim|wap|webkit|xv)-(?=[a-z])/i, "");
                0 != d.lastIndexOf("--", 0) &&
                    0 != d.lastIndexOf("var", 0) &&
                    ((c = TD(RD, a, a.getPropertyValue ? "getPropertyValue" : "getAttribute", [c]) || ""), (c = OD(c)), null != c && TD(SD, b, b.setProperty ? "setProperty" : "setAttribute", [d, c]));
            });
            return new ae(b.cssText || "", $d);
        },
        VD = function (a) {
            Pa(a) ? (a = Tb(a)) : ((a = Dd(a)), Pb(a, "cssText"));
            return a;
        };
    var XD = function (a, b, c) {
        M.call(this);
        this.j = a;
        this.h = null;
        this.I = "";
        this.K = be;
        this.L = 0;
        this.C = this.l = null;
        this.A = b;
        this.B = null;
        this.D = "";
        this.G = c;
    };
    r(XD, M);
    XD.prototype.init = function (a) {
        this.D = a;
        a = "about:blank";
        cc && (a = "");
        this.l = ef("IFRAME", { src: a, allowtransparency: !0, background: "transparent" });
        Vk(this.l, { display: "none", width: "0", height: "0" });
        a = this.j.G;
        a.appendChild(this.l);
        a = a.ownerDocument;
        a = a.defaultView || a.parentWindow;
        null == this.B && (this.B = new Iu(this));
        this.B.P(a, "message", this.M);
        a = '<body><script src="//imasdk.googleapis.com/js/sdkloader/loader.js">\x3c/script><script>loader = new VPAIDLoader(false, "' + (this.D + '");\x3c/script></body>');
        if (zc || xc || dc) {
            var b = this.l.contentWindow;
            b && dC(b.document, a);
        } else eC(this.l, a);
    };
    XD.prototype.M = function (a) {
        try {
            var b = a.h.data;
            try {
                var c = JSON.parse(b);
            } catch (la) {
                return;
            }
            var d = c.session;
            if (null != d && this.D == d)
                switch (c.type) {
                    case "friendlyReady":
                        var e = YD(this);
                        if (null != e) {
                            this.h = e;
                            this.I = e.currentSrc;
                            var f = e.style.cssText;
                            if (cc && 10 > document.documentMode) var g = be;
                            else {
                                var h = document;
                                "function" === typeof HTMLTemplateElement && (h = cf(document, "TEMPLATE").content.ownerDocument);
                                var k = h.implementation.createHTMLDocument("").createElement("DIV");
                                k.style.cssText = f;
                                g = WD(k.style);
                            }
                            this.K = g;
                            this.L = e.currentTime;
                        } else {
                            var n = this.j.G,
                                m = this.j.M;
                            var x = "border: 0; margin: 0; padding: 0; position: absolute; width:" + (m.width + "px; ");
                            x += "height:" + m.height + "px;";
                            this.h = ef("VIDEO", { style: x, autoplay: !0 });
                            n.appendChild(this.h);
                        }
                        var v = this.j.G;
                        e = "border: 0; margin: 0; padding: 0;position: absolute; ";
                        var A = dl(this.h);
                        e += "width:" + A.width + "px; ";
                        e += "height:" + A.height + "px;";
                        this.C = ef("DIV", { style: e });
                        v.appendChild(this.C);
                        try {
                            this.l.contentWindow.loader.initFriendly(this.h, this.C);
                        } catch (la) {
                            ZD(this);
                        }
                        Vy(this.A, "vpaid", "", b);
                        break;
                    case "becameLinear":
                        this.h && !mf() && !lf() && Vk(this.h, { visibility: "visible" });
                        Vy(this.A, "vpaid", "", b);
                        break;
                    case "becameNonlinear":
                        $D(this);
                        Vy(this.A, "vpaid", "", b);
                        break;
                    case "startAd":
                        v = {};
                        if (this.h) {
                            h = this.h.paused;
                            var C = 0 < this.h.currentTime;
                            v.apl = C && !h ? "1" : "0";
                            v.ip = h ? "1" : "0";
                            v.iavp = C ? "1" : "0";
                        } else v.apl = "n";
                        HA().report(99, v);
                        Vy(this.A, "vpaid", "", b);
                        if (null != YD(this)) {
                            var O = this.j;
                            null != O.j && O.j.show();
                        }
                        break;
                    default:
                        Vy(this.A, "vpaid", "", b);
                }
        } catch (la) {
            ZD(this);
        }
    };
    var ZD = function (a) {
            var b = { type: "error" };
            b.session = a.D;
            a = new ei(void 0).aa(b);
            window.postMessage(a, "*");
        },
        YD = function (a) {
            return ("videoDisplayUnknown" == a.G ? a.j.h : a.j.K.get(a.G)).B.h;
        },
        $D = function (a) {
            a.h && !mf() && !lf() && Vk(a.h, { visibility: "hidden" });
        };
    XD.prototype.N = function () {
        L.prototype.N.call(this);
        xj(this.B);
        this.B = null;
        ff(this.C);
        this.C = null;
        ff(this.l);
        this.l = null;
        var a = YD(this);
        if (null != a) {
            var b = this.K;
            a.style.cssText = b instanceof ae && b.constructor === ae ? b.h : "type_error:SafeStyle";
            mf() || lf() ? ((a.src = this.I), (a.currentTime = this.L)) : (a.removeAttribute("src"), this.j.hide());
        } else ff(this.h), (this.h = null);
    };
    var aE = function (a, b) {
        L.call(this);
        this.j = a;
        this.l = b;
        this.h = new Map();
    };
    r(aE, L);
    var bE = function (a, b) {
        try {
            var c = b.ka,
                d = c.session;
            switch (c.vpaidEventType) {
                case "createFriendlyIframe":
                    b = "videoDisplayUnknown";
                    c.videoDisplayName && (b = c.videoDisplayName);
                    var e = c.session,
                        f = new XD(a.j, a.l, b);
                    a.h.set(e, f);
                    f.init(e);
                    break;
                case "vpaidNonLinear":
                    var g = a.h.get(d);
                    g && $D(g);
                    break;
                case "destroyFriendlyIframe":
                    var h = a.h.get(d);
                    h && (h.dispose(), a.h.delete(d));
            }
        } catch (k) {
            HA().report(125, { msg: k.message });
        }
    };
    aE.prototype.N = function () {
        this.h.forEach(function (a) {
            return a.dispose();
        });
    };
    var cE = function () {
        this.h = [];
        this.j = [];
    };
    cE.prototype.isEmpty = function () {
        return 0 === this.h.length && 0 === this.j.length;
    };
    cE.prototype.clear = function () {
        this.h = [];
        this.j = [];
    };
    cE.prototype.remove = function (a) {
        var b = this.h;
        b: {
            var c = b.length - 1;
            0 > c && (c = Math.max(0, b.length + c));
            if ("string" === typeof b) c = "string" !== typeof a || 1 != a.length ? -1 : b.lastIndexOf(a, c);
            else {
                for (; 0 <= c; c--) if (c in b && b[c] === a) break b;
                c = -1;
            }
        }
        0 <= c ? (Qb(b, c), (b = !0)) : (b = !1);
        return b || Pb(this.j, a);
    };
    cE.prototype.ob = function () {
        for (var a = [], b = this.h.length - 1; 0 <= b; --b) a.push(this.h[b]);
        var c = this.j.length;
        for (b = 0; b < c; ++b) a.push(this.j[b]);
        return a;
    };
    var Z = function (a, b, c, d, e, f, g) {
        M.call(this);
        var h = this;
        this.I = a;
        this.h = b;
        this.L = c;
        this.gb = e;
        this.A = new $A();
        this.C = g;
        this.M = !1;
        this.T = 1;
        this.ib = d;
        this.Z = -1;
        this.l = this.j = null;
        this.G = new LD({ currentTime: 0 }, this.C);
        this.D = new cE();
        this.qa = this.V = !1;
        this.W = new Map();
        this.Y = this.sa = !1;
        this.Fa = new aE(b, g);
        zj(this, this.Fa);
        this.K = f && null != this.h.C;
        this.R = function () {
            var k = h.h.h,
                n = k.getCurrentTime();
            k = k.getDuration();
            return { currentTime: n, duration: k, isPlaying: !0, volume: h.T };
        };
        this.U = new Iu(this);
        this.U.P(this.C, "adsManager", this.kb);
    };
    r(Z, M);
    Z.prototype.kb = function (a) {
        var b = this,
            c = a.ha,
            d = a.ka;
        switch (c) {
            case "error":
                dE(this);
                eE(this, d);
                break;
            case "contentPauseRequested":
                HA().report(130);
                fE(this);
                gE(this, c, d);
                break;
            case "contentResumeRequested":
                hE(this, function () {
                    return gE(b, c, d);
                });
                break;
            case "remainingTime":
                this.Z = d.remainingTime;
                break;
            case "skip":
                gE(this, c, d);
                break;
            case "log":
                gE(this, c, d, d.logData);
                break;
            case "companionBackfill":
                a = Ma("window.google_show_companion_ad");
                null != a && a();
                break;
            case "skipShown":
                this.M = !0;
                gE(this, c, d);
                break;
            case "interaction":
                gE(this, c, d, d.interactionData);
                break;
            case "vpaidEvent":
                bE(this.Fa, a);
                break;
            case "skippableStateChanged":
                a = d.adData;
                null != a.skippable && (this.M = a.skippable);
                gE(this, c, d);
                break;
            case "volumeChange":
                a = d.adData;
                null != a && "number" === typeof a.volume && (this.T = a.volume);
                gE(this, c, d);
                break;
            case "firstQuartile":
                gE(this, gz.firstQuartile, d);
                gE(this, c, d);
                break;
            case "thirdQuartile":
                gE(this, gz.thirdQuartile, d);
                gE(this, c, d);
                break;
            default:
                gE(this, c, d);
        }
    };
    var gE = function (a, b, c, d) {
            if (null == c.companions) {
                var e = a.W.get(c.adId);
                c.companions = null != e ? e : [];
            }
            var f = c.adData;
            if ((e = null == f ? null : new Y(f))) a.j = e;
            switch (b) {
                case "adBreakReady":
                case "mediaUrlPinged":
                    b = new HD(b, null, c);
                    break;
                case "adMetadata":
                    b = null;
                    null != c.adCuePoints && (b = new kC(c.adCuePoints));
                    b = new JD(e, b);
                    break;
                case "allAdsCompleted":
                    a.j = null;
                    a.sa = !0;
                    b = new HD(b, e);
                    break;
                case "contentPauseRequested":
                    a.Y = !1;
                    b = new HD(b, e);
                    break;
                case "contentResumeRequested":
                    a.j = null;
                    a.Y = !0;
                    b = new HD(b, e);
                    break;
                case "loaded":
                    a.Z = e.getDuration();
                    a.M = !1;
                    Ty() && ((d = a.I), (c = a.gb), d.j.set(LA(e), a.R), (0 != fy.h ? G(Pr).l : d.B) && TA(d, "loaded", LA(e), c));
                    b = new HD(b, e, f);
                    break;
                case "start":
                    a.W.set(c.adId, c.companions);
                    null != rD(a.h) && (null == a.l ? ((a.l = new eA()), a.U.P(a.l, "click", a.kf)) : iA(a.l), gA(a.l, rD(a.h)));
                    b = new HD(b, e);
                    break;
                case "complete":
                    null != a.l && iA(a.l);
                    Ty() && VA(a.I, a.R, LA(e));
                    a.j = null;
                    a.W.delete(c.adId);
                    b = new HD(b, e);
                    break;
                case "log":
                    c = null;
                    null != d && null != d.type ? ((f = d.type), (f = "adLoadError" == f || "adPlayError" == f)) : (f = !1);
                    f && (c = { adError: new DD(d) });
                    b = new HD(b, e, c);
                    break;
                case "interaction":
                    b = new HD(b, e, d);
                    break;
                case "adProgress":
                    b = new HD(b, e, new KD(c));
                    break;
                default:
                    b = new HD(b, e);
            }
            a.dispatchEvent(b);
            a.sa && a.Y && a.destroy();
        },
        eE = function (a, b) {
            var c = new FD(new DD(b));
            a.V ? (a.dispatchEvent(c), Ty() && a.j && VA(a.I, a.R, LA(a.j)), (a.j = null)) : a.D.j.push(c);
            a = { error: b.errorCode, vis: xh(document) };
            HA().report(7, a);
        },
        iE = function (a, b, c) {
            Vy(a.C, "adsManager", b, c);
        },
        hE = function (a, b) {
            HA().report(131);
            dE(a, b);
        },
        fE = function (a) {
            var b = a.h.h;
            BD(a.h) && a.A.restoreCustomPlaybackStateOnAdBreakComplete && null != b.nd && b.nd();
        },
        dE = function (a, b) {
            var c = a.h.h;
            BD(a.h) && a.A.restoreCustomPlaybackStateOnAdBreakComplete && null != c.Nb ? c.Nb(b) : b && b();
        };
    l = Z.prototype;
    l.init = function (a, b, c, d) {
        if (this.D.isEmpty()) {
            var e = this.h,
                f = null;
            e.B && null == d && (f = { vd: "setnull" });
            e.B && e.B === d && (f = { vd: "match" });
            if (e.B && e.B !== d) {
                f = Sy(d || null);
                var g = Cu(d || null);
                f = { vd: "diff", oc: e.W, nc: f, oi: e.V, ni: g };
            }
            !e.B && d && (f = { vd: "new" });
            f && ((f.custVid = e.U), HA().report(93, f));
            null != d &&
                ((e.D = AD(d)),
                Ry(e.D) &&
                    ((e.T = !0),
                    xj(e.j),
                    xj(e.o),
                    xj(e.l),
                    (e.j = null),
                    (e.o = null),
                    (e.l = null),
                    xj(e.h),
                    (e.h = new qC(d)),
                    "function" !== typeof d.getBoundingClientRect ? ((e.J = e.A), (fy.l = e.J)) : (e.J = d),
                    null != (d = e.H.D) && ((e = e.h.B.h), (d.l = e), d.j && ((d = d.j), (d.h = e), iC(d, e)))));
            this.V = !0;
            this.resize(a, b, c);
            e = this.A.aa(this.K);
            iE(this, "init", { adsRenderingSettings: e, width: a, height: b, viewMode: c });
        } else {
            for (; !this.D.isEmpty(); ) (b = a = this.D), 0 === b.h.length && ((b.h = b.j), b.h.reverse(), (b.j = [])), (a = a.h.pop()), this.dispatchEvent(a);
            this.dispose();
        }
    };
    l.af = function () {
        return BD(this.h);
    };
    l.$e = function () {
        return this.K;
    };
    l.getRemainingTime = function () {
        return this.Z;
    };
    l.getAdSkippableState = function () {
        return this.M;
    };
    l.Ge = function () {
        iE(this, "discardAdBreak");
    };
    l.updateAdsRenderingSettings = function (a) {
        if (null != a) {
            a = jE(a);
            var b = this.A.bitrate,
                c = a.bitrate;
            HA().report(96, { init: this.V ? "1" : "0", start: this.qa ? "1" : "0", old: b, new: c, changed: b != c ? "1" : "0" });
            this.A = a;
            a = this.A.aa(this.K);
            iE(this, "updateAdsRenderingSettings", { adsRenderingSettings: a });
        }
    };
    l.skip = function () {
        iE(this, "skip");
    };
    l.start = function () {
        if (this.L) {
            (ic || kc) && HA().report(50, { customPlayback: BD(this.h) });
            this.h.isInitialized() || HA().report(26, { adtagurl: this.L, customPlayback: BD(this.h) });
            Jn(this.h.A) && HA().report(30, { adtagurl: this.L, customPlayback: BD(this.h) });
            var a = this.h.C,
                b = this.h.A,
                c;
            if ((c = a && b && !Jn(a)))
                (a = RA(a)), (b = RA(b)), (c = 0 < a.width && 0 < a.height && 0 < b.width && 0 < b.height && a.left <= b.left + b.width && b.left <= a.left + a.width && a.top <= b.top + b.height && b.top <= a.top + a.height);
            c && HA().report(31, { adtagurl: this.L, customPlayback: BD(this.h) });
        }
        if (!this.h.isInitialized() && !BD(this.h)) throw oz(mz);
        b = this.h;
        b.R = this.K && null != b.C;
        this.h.H.h.style.opacity = 1;
        null != this.B && 1 == this.getVolume() && ("boolean" === typeof this.B.muted && this.B.muted ? this.setVolume(0) : "number" === typeof this.B.volume && ((b = this.B.volume), 0 <= b && 1 >= b && this.setVolume(this.B.volume)));
        this.qa = !0;
        iE(this, "start");
    };
    l.kf = function () {
        if (!this.A.disableClickThrough && null != this.j) {
            var a = this.j.h.clickThroughUrl;
            null != a && dv(a, this.j.h.attributionParams);
        }
    };
    l.resize = function (a, b, c) {
        var d = this.h,
            e = d.A;
        null != e && (-1 == a ? ((e.style.right = "0"), (e.style.left = "0")) : (e.style.width = a + "px"), -1 == b ? ((e.style.bottom = "0"), (e.style.top = "0")) : (e.style.height = b + "px"));
        e = d.H;
        e.h.width = -1 == a ? "100%" : a;
        e.h.height = -1 == b ? "100%" : b;
        try {
            e.h.offsetTop = e.h.offsetTop;
        } catch (f) {}
        d.M = new y(a, b);
        iE(this, "resize", { width: a, height: b, viewMode: c });
    };
    l.stop = function () {
        iE(this, "stop");
    };
    l.expand = function () {
        iE(this, "expand");
    };
    l.collapse = function () {
        iE(this, "collapse");
    };
    l.getVolume = function () {
        return this.T;
    };
    l.setVolume = function (a) {
        this.T = a;
        this.h.h.setVolume(a);
        iE(this, "volume", { volume: a });
    };
    l.pause = function () {
        iE(this, "pause");
    };
    l.resume = function () {
        iE(this, "resume");
    };
    l.destroy = function () {
        this.dispose();
    };
    l.getCuePoints = function () {
        return this.ib;
    };
    l.getCurrentAd = function () {
        return this.j;
    };
    l.N = function () {
        iE(this, "destroy");
        null != this.l && this.l.dispose();
        this.U.dispose();
        this.D.clear();
        this.G && (this.G.stop(), this.G.dispose());
        Ty() && VA(this.I, this.R);
        M.prototype.N.call(this);
    };
    l.clicked = function () {
        HA().report(124, { api: "clicked" });
        var a = this.j && this.j.h.clickThroughUrl;
        a && this.j.isUiDisabled() && dv(a, this.j.h.attributionParams);
        iE(this, "click");
    };
    l.focus = function () {
        Vy(this.C, "userInteraction", "focusUiElement");
    };
    Z.prototype.clicked = Z.prototype.clicked;
    Z.prototype.getCurrentAd = Z.prototype.getCurrentAd;
    Z.prototype.getCuePoints = Z.prototype.getCuePoints;
    Z.prototype.destroy = Z.prototype.destroy;
    Z.prototype.resume = Z.prototype.resume;
    Z.prototype.pause = Z.prototype.pause;
    Z.prototype.setVolume = Z.prototype.setVolume;
    Z.prototype.getVolume = Z.prototype.getVolume;
    Z.prototype.collapse = Z.prototype.collapse;
    Z.prototype.expand = Z.prototype.expand;
    Z.prototype.stop = Z.prototype.stop;
    Z.prototype.resize = Z.prototype.resize;
    Z.prototype.start = Z.prototype.start;
    Z.prototype.skip = Z.prototype.skip;
    Z.prototype.updateAdsRenderingSettings = Z.prototype.updateAdsRenderingSettings;
    Z.prototype.discardAdBreak = Z.prototype.Ge;
    Z.prototype.getAdSkippableState = Z.prototype.getAdSkippableState;
    Z.prototype.getRemainingTime = Z.prototype.getRemainingTime;
    Z.prototype.isCustomClickTrackingUsed = Z.prototype.$e;
    Z.prototype.isCustomPlaybackUsed = Z.prototype.af;
    Z.prototype.init = Z.prototype.init;
    function jE(a) {
        if (a instanceof $A) return a;
        var b = new $A();
        b.append(a);
        return b;
    }
    var kE = function (a, b) {
        Aj.call(this, "adsManagerLoaded");
        this.h = a;
        this.l = b;
    };
    r(kE, Aj);
    kE.prototype.getAdsManager = function (a, b) {
        a = a || { currentTime: null };
        var c = this.h;
        c.B = a;
        null != a.currentTime && ((c.G = new LD(a, c.C)), c.G.start());
        null != b && (c.A = jE(b));
        return this.h;
    };
    kE.prototype.getUserRequestContext = function () {
        return this.l;
    };
    kE.prototype.getUserRequestContext = kE.prototype.getUserRequestContext;
    kE.prototype.getAdsManager = kE.prototype.getAdsManager;
    var lE = { ADS_MANAGER_LOADED: "adsManagerLoaded" };
    u("module$contents$ima$AdsManagerLoadedEvent_AdsManagerLoadedEvent.Type", lE, void 0);
    var mE = function () {
        this.videoPlayMuted = this.videoPlayActivation = "unknown";
        this.videoContinuousPlay = "0";
        this.nonLinearAdSlotHeight = this.nonLinearAdSlotWidth = this.linearAdSlotHeight = this.linearAdSlotWidth = this.liveStreamPrefetchSeconds = 0;
        this.forceNonLinearFullSlot = !1;
        this.contentTitle = this.contentKeywords = this.contentDuration = null;
        this.vastLoadTimeout = 5e3;
        this.omidAccessModeRules = {};
        this.pageUrl = null;
    };
    mE.prototype.aa = function () {
        var a = {};
        a.adsResponse = this.adsResponse;
        a.videoPlayActivation = this.videoPlayActivation;
        a.videoPlayMuted = this.videoPlayMuted;
        a.videoContinuousPlay = this.videoContinuousPlay;
        a.adTagUrl = this.adTagUrl;
        a.contentDuration = this.contentDuration;
        a.contentKeywords = this.contentKeywords;
        a.contentTitle = this.contentTitle;
        a.linearAdSlotWidth = this.linearAdSlotWidth;
        a.linearAdSlotHeight = this.linearAdSlotHeight;
        a.nonLinearAdSlotWidth = this.nonLinearAdSlotWidth;
        a.nonLinearAdSlotHeight = this.nonLinearAdSlotHeight;
        a.forceNonLinearFullSlot = this.forceNonLinearFullSlot;
        a.liveStreamPrefetchSeconds = this.liveStreamPrefetchSeconds;
        a.vastLoadTimeout = this.vastLoadTimeout;
        a.omidAccessModeRules = this.omidAccessModeRules;
        a.pageUrl = this.pageUrl;
        return a;
    };
    mE.prototype.setAdWillAutoPlay = function (a) {
        this.videoPlayActivation = a ? "auto" : "click";
    };
    mE.prototype.setAdWillPlayMuted = function (a) {
        this.videoPlayMuted = a ? "muted" : "unmuted";
    };
    mE.prototype.setContinuousPlayback = function (a) {
        this.videoContinuousPlay = a ? "2" : "1";
    };
    mE.prototype.setContinuousPlayback = mE.prototype.setContinuousPlayback;
    mE.prototype.setAdWillPlayMuted = mE.prototype.setAdWillPlayMuted;
    mE.prototype.setAdWillAutoPlay = mE.prototype.setAdWillAutoPlay;
    var nE = function (a) {
        this.h = a || { cookie: "" };
    };
    l = nE.prototype;
    l.isEnabled = function () {
        if (!t.navigator.cookieEnabled) return !1;
        if (!this.isEmpty()) return !0;
        this.set("TESTCOOKIESENABLED", "1", { Nc: 60 });
        if ("1" !== this.get("TESTCOOKIESENABLED")) return !1;
        this.remove("TESTCOOKIESENABLED");
        return !0;
    };
    l.set = function (a, b, c) {
        var d = !1;
        if ("object" === typeof c) {
            var e = c.Mh;
            d = c.uf || !1;
            var f = c.domain || void 0;
            var g = c.path || void 0;
            var h = c.Nc;
        }
        if (/[;=\s]/.test(a)) throw Error('Invalid cookie name "' + a + '"');
        if (/[;\r\n]/.test(b)) throw Error('Invalid cookie value "' + b + '"');
        void 0 === h && (h = -1);
        this.h.cookie =
            a +
            "=" +
            b +
            (f ? ";domain=" + f : "") +
            (g ? ";path=" + g : "") +
            (0 > h ? "" : 0 == h ? ";expires=" + new Date(1970, 1, 1).toUTCString() : ";expires=" + new Date(Date.now() + 1e3 * h).toUTCString()) +
            (d ? ";secure" : "") +
            (null != e ? ";samesite=" + e : "");
    };
    l.get = function (a, b) {
        for (var c = a + "=", d = (this.h.cookie || "").split(";"), e = 0, f; e < d.length; e++) {
            f = nb(d[e]);
            if (0 == f.lastIndexOf(c, 0)) return f.substr(c.length);
            if (f == a) return "";
        }
        return b;
    };
    l.remove = function (a, b, c) {
        var d = void 0 !== this.get(a);
        this.set(a, "", { Nc: 0, path: b, domain: c });
        return d;
    };
    l.Tb = function () {
        return oE(this).keys;
    };
    l.ob = function () {
        return oE(this).values;
    };
    l.isEmpty = function () {
        return !this.h.cookie;
    };
    l.clear = function () {
        for (var a = oE(this).keys, b = a.length - 1; 0 <= b; b--) this.remove(a[b]);
    };
    var oE = function (a) {
        a = (a.h.cookie || "").split(";");
        for (var b = [], c = [], d, e, f = 0; f < a.length; f++) (e = nb(a[f])), (d = e.indexOf("=")), -1 == d ? (b.push(""), c.push(e)) : (b.push(e.substring(0, d)), c.push(e.substring(d + 1)));
        return { keys: b, values: c };
    };
    function pE(a, b, c) {
        b = og(b, 5) && "null" !== c.origin ? c.document.cookie : null;
        return null === b ? null : new nE({ cookie: b }).get(a) || "";
    }
    var qE = function () {
        this.h = window;
        this.j = 0;
    };
    qE.prototype.isSupported = function (a) {
        if (0 === this.j) {
            if (a && pE("__gads", a, this.h)) a = !0;
            else {
                var b = this.h;
                og(a, 5) && "null" !== b.origin && new nE(b.document).set("GoogleAdServingTest", "Good", void 0);
                if ((b = "Good" === pE("GoogleAdServingTest", a, this.h))) {
                    var c = this.h;
                    og(a, 5) && "null" !== c.origin && new nE(c.document).remove("GoogleAdServingTest", void 0, void 0);
                }
                a = b;
            }
            this.j = a ? 2 : 1;
        }
        return 2 === this.j;
    };
    var rE = function (a, b, c, d) {
        if (d) {
            var e = { Nc: D(c, 2) - Date.now() / 1e3, path: D(c, 3), domain: D(c, 4), uf: !1 };
            a = a.h;
            og(d, 5) && "null" !== a.origin && new nE(a.document).set(b, D(c, 1), e);
        }
    };
    var sE = p(["https://adservice.google.com/adsid/integrator.", ""]),
        tE = p(["https://adservice.google.ad/adsid/integrator.", ""]),
        uE = p(["https://adservice.google.ae/adsid/integrator.", ""]),
        vE = p(["https://adservice.google.com.af/adsid/integrator.", ""]),
        wE = p(["https://adservice.google.com.ag/adsid/integrator.", ""]),
        xE = p(["https://adservice.google.com.ai/adsid/integrator.", ""]),
        yE = p(["https://adservice.google.al/adsid/integrator.", ""]),
        zE = p(["https://adservice.google.co.ao/adsid/integrator.", ""]),
        AE = p(["https://adservice.google.com.ar/adsid/integrator.", ""]),
        BE = p(["https://adservice.google.as/adsid/integrator.", ""]),
        CE = p(["https://adservice.google.at/adsid/integrator.", ""]),
        DE = p(["https://adservice.google.com.au/adsid/integrator.", ""]),
        EE = p(["https://adservice.google.az/adsid/integrator.", ""]),
        FE = p(["https://adservice.google.com.bd/adsid/integrator.", ""]),
        GE = p(["https://adservice.google.be/adsid/integrator.", ""]),
        HE = p(["https://adservice.google.bf/adsid/integrator.", ""]),
        IE = p(["https://adservice.google.bg/adsid/integrator.", ""]),
        JE = p(["https://adservice.google.com.bh/adsid/integrator.", ""]),
        KE = p(["https://adservice.google.bi/adsid/integrator.", ""]),
        LE = p(["https://adservice.google.bj/adsid/integrator.", ""]),
        ME = p(["https://adservice.google.com.bn/adsid/integrator.", ""]),
        NE = p(["https://adservice.google.com.bo/adsid/integrator.", ""]),
        OE = p(["https://adservice.google.com.br/adsid/integrator.", ""]),
        PE = p(["https://adservice.google.bs/adsid/integrator.", ""]),
        QE = p(["https://adservice.google.bt/adsid/integrator.", ""]),
        RE = p(["https://adservice.google.co.bw/adsid/integrator.", ""]),
        SE = p(["https://adservice.google.com.bz/adsid/integrator.", ""]),
        TE = p(["https://adservice.google.ca/adsid/integrator.", ""]),
        UE = p(["https://adservice.google.cd/adsid/integrator.", ""]),
        VE = p(["https://adservice.google.cf/adsid/integrator.", ""]),
        WE = p(["https://adservice.google.cg/adsid/integrator.", ""]),
        XE = p(["https://adservice.google.ch/adsid/integrator.", ""]),
        YE = p(["https://adservice.google.ci/adsid/integrator.", ""]),
        ZE = p(["https://adservice.google.co.ck/adsid/integrator.", ""]),
        $E = p(["https://adservice.google.cl/adsid/integrator.", ""]),
        aF = p(["https://adservice.google.cm/adsid/integrator.", ""]),
        bF = p(["https://adservice.google.com.co/adsid/integrator.", ""]),
        cF = p(["https://adservice.google.co.cr/adsid/integrator.", ""]),
        dF = p(["https://adservice.google.com.cu/adsid/integrator.", ""]),
        eF = p(["https://adservice.google.cv/adsid/integrator.", ""]),
        fF = p(["https://adservice.google.com.cy/adsid/integrator.", ""]),
        gF = p(["https://adservice.google.cz/adsid/integrator.", ""]),
        hF = p(["https://adservice.google.de/adsid/integrator.", ""]),
        iF = p(["https://adservice.google.dj/adsid/integrator.", ""]),
        jF = p(["https://adservice.google.dk/adsid/integrator.", ""]),
        kF = p(["https://adservice.google.dm/adsid/integrator.", ""]),
        lF = p(["https://adservice.google.dz/adsid/integrator.", ""]),
        mF = p(["https://adservice.google.com.ec/adsid/integrator.", ""]),
        nF = p(["https://adservice.google.ee/adsid/integrator.", ""]),
        oF = p(["https://adservice.google.com.eg/adsid/integrator.", ""]),
        pF = p(["https://adservice.google.es/adsid/integrator.", ""]),
        qF = p(["https://adservice.google.com.et/adsid/integrator.", ""]),
        rF = p(["https://adservice.google.fi/adsid/integrator.", ""]),
        sF = p(["https://adservice.google.com.fj/adsid/integrator.", ""]),
        tF = p(["https://adservice.google.fm/adsid/integrator.", ""]),
        uF = p(["https://adservice.google.fr/adsid/integrator.", ""]),
        vF = p(["https://adservice.google.ga/adsid/integrator.", ""]),
        wF = p(["https://adservice.google.ge/adsid/integrator.", ""]),
        xF = p(["https://adservice.google.gg/adsid/integrator.", ""]),
        yF = p(["https://adservice.google.com.gh/adsid/integrator.", ""]),
        zF = p(["https://adservice.google.com.gi/adsid/integrator.", ""]),
        AF = p(["https://adservice.google.gl/adsid/integrator.", ""]),
        BF = p(["https://adservice.google.gm/adsid/integrator.", ""]),
        CF = p(["https://adservice.google.gr/adsid/integrator.", ""]),
        DF = p(["https://adservice.google.com.gt/adsid/integrator.", ""]),
        EF = p(["https://adservice.google.gy/adsid/integrator.", ""]),
        FF = p(["https://adservice.google.com.hk/adsid/integrator.", ""]),
        GF = p(["https://adservice.google.hn/adsid/integrator.", ""]),
        HF = p(["https://adservice.google.hr/adsid/integrator.", ""]),
        IF = p(["https://adservice.google.ht/adsid/integrator.", ""]),
        JF = p(["https://adservice.google.hu/adsid/integrator.", ""]),
        KF = p(["https://adservice.google.co.id/adsid/integrator.", ""]),
        LF = p(["https://adservice.google.ie/adsid/integrator.", ""]),
        MF = p(["https://adservice.google.co.il/adsid/integrator.", ""]),
        NF = p(["https://adservice.google.im/adsid/integrator.", ""]),
        OF = p(["https://adservice.google.co.in/adsid/integrator.", ""]),
        PF = p(["https://adservice.google.iq/adsid/integrator.", ""]),
        QF = p(["https://adservice.google.is/adsid/integrator.", ""]),
        RF = p(["https://adservice.google.it/adsid/integrator.", ""]),
        SF = p(["https://adservice.google.je/adsid/integrator.", ""]),
        TF = p(["https://adservice.google.com.jm/adsid/integrator.", ""]),
        UF = p(["https://adservice.google.jo/adsid/integrator.", ""]),
        VF = p(["https://adservice.google.co.jp/adsid/integrator.", ""]),
        WF = p(["https://adservice.google.co.ke/adsid/integrator.", ""]),
        XF = p(["https://adservice.google.com.kh/adsid/integrator.", ""]),
        YF = p(["https://adservice.google.ki/adsid/integrator.", ""]),
        ZF = p(["https://adservice.google.kg/adsid/integrator.", ""]),
        $F = p(["https://adservice.google.co.kr/adsid/integrator.", ""]),
        aG = p(["https://adservice.google.com.kw/adsid/integrator.", ""]),
        bG = p(["https://adservice.google.kz/adsid/integrator.", ""]),
        cG = p(["https://adservice.google.la/adsid/integrator.", ""]),
        dG = p(["https://adservice.google.com.lb/adsid/integrator.", ""]),
        eG = p(["https://adservice.google.li/adsid/integrator.", ""]),
        fG = p(["https://adservice.google.lk/adsid/integrator.", ""]),
        gG = p(["https://adservice.google.co.ls/adsid/integrator.", ""]),
        hG = p(["https://adservice.google.lt/adsid/integrator.", ""]),
        iG = p(["https://adservice.google.lu/adsid/integrator.", ""]),
        jG = p(["https://adservice.google.lv/adsid/integrator.", ""]),
        kG = p(["https://adservice.google.com.ly/adsid/integrator.", ""]),
        lG = p(["https://adservice.google.md/adsid/integrator.", ""]),
        mG = p(["https://adservice.google.me/adsid/integrator.", ""]),
        nG = p(["https://adservice.google.mg/adsid/integrator.", ""]),
        oG = p(["https://adservice.google.mk/adsid/integrator.", ""]),
        pG = p(["https://adservice.google.ml/adsid/integrator.", ""]),
        qG = p(["https://adservice.google.com.mm/adsid/integrator.", ""]),
        rG = p(["https://adservice.google.mn/adsid/integrator.", ""]),
        sG = p(["https://adservice.google.ms/adsid/integrator.", ""]),
        tG = p(["https://adservice.google.com.mt/adsid/integrator.", ""]),
        uG = p(["https://adservice.google.mu/adsid/integrator.", ""]),
        vG = p(["https://adservice.google.mv/adsid/integrator.", ""]),
        wG = p(["https://adservice.google.mw/adsid/integrator.", ""]),
        xG = p(["https://adservice.google.com.mx/adsid/integrator.", ""]),
        yG = p(["https://adservice.google.com.my/adsid/integrator.", ""]),
        zG = p(["https://adservice.google.co.mz/adsid/integrator.", ""]),
        AG = p(["https://adservice.google.com.na/adsid/integrator.", ""]),
        BG = p(["https://adservice.google.com.ng/adsid/integrator.", ""]),
        CG = p(["https://adservice.google.com.ni/adsid/integrator.", ""]),
        DG = p(["https://adservice.google.ne/adsid/integrator.", ""]),
        EG = p(["https://adservice.google.nl/adsid/integrator.", ""]),
        FG = p(["https://adservice.google.no/adsid/integrator.", ""]),
        GG = p(["https://adservice.google.com.np/adsid/integrator.", ""]),
        HG = p(["https://adservice.google.nr/adsid/integrator.", ""]),
        IG = p(["https://adservice.google.nu/adsid/integrator.", ""]),
        JG = p(["https://adservice.google.co.nz/adsid/integrator.", ""]),
        KG = p(["https://adservice.google.com.om/adsid/integrator.", ""]),
        LG = p(["https://adservice.google.com.pa/adsid/integrator.", ""]),
        MG = p(["https://adservice.google.com.pe/adsid/integrator.", ""]),
        NG = p(["https://adservice.google.com.pg/adsid/integrator.", ""]),
        OG = p(["https://adservice.google.com.ph/adsid/integrator.", ""]),
        PG = p(["https://adservice.google.com.pk/adsid/integrator.", ""]),
        QG = p(["https://adservice.google.pl/adsid/integrator.", ""]),
        RG = p(["https://adservice.google.pn/adsid/integrator.", ""]),
        SG = p(["https://adservice.google.com.pr/adsid/integrator.", ""]),
        TG = p(["https://adservice.google.ps/adsid/integrator.", ""]),
        UG = p(["https://adservice.google.pt/adsid/integrator.", ""]),
        VG = p(["https://adservice.google.com.py/adsid/integrator.", ""]),
        WG = p(["https://adservice.google.com.qa/adsid/integrator.", ""]),
        XG = p(["https://adservice.google.ro/adsid/integrator.", ""]),
        YG = p(["https://adservice.google.ru/adsid/integrator.", ""]),
        ZG = p(["https://adservice.google.rw/adsid/integrator.", ""]),
        $G = p(["https://adservice.google.com.sa/adsid/integrator.", ""]),
        aH = p(["https://adservice.google.com.sb/adsid/integrator.", ""]),
        bH = p(["https://adservice.google.sc/adsid/integrator.", ""]),
        cH = p(["https://adservice.google.se/adsid/integrator.", ""]),
        dH = p(["https://adservice.google.com.sg/adsid/integrator.", ""]),
        eH = p(["https://adservice.google.sh/adsid/integrator.", ""]),
        fH = p(["https://adservice.google.si/adsid/integrator.", ""]),
        gH = p(["https://adservice.google.sk/adsid/integrator.", ""]),
        hH = p(["https://adservice.google.sn/adsid/integrator.", ""]),
        iH = p(["https://adservice.google.so/adsid/integrator.", ""]),
        jH = p(["https://adservice.google.sm/adsid/integrator.", ""]),
        kH = p(["https://adservice.google.sr/adsid/integrator.", ""]),
        lH = p(["https://adservice.google.st/adsid/integrator.", ""]),
        mH = p(["https://adservice.google.com.sv/adsid/integrator.", ""]),
        nH = p(["https://adservice.google.td/adsid/integrator.", ""]),
        oH = p(["https://adservice.google.tg/adsid/integrator.", ""]),
        pH = p(["https://adservice.google.co.th/adsid/integrator.", ""]),
        qH = p(["https://adservice.google.com.tj/adsid/integrator.", ""]),
        rH = p(["https://adservice.google.tl/adsid/integrator.", ""]),
        sH = p(["https://adservice.google.tm/adsid/integrator.", ""]),
        tH = p(["https://adservice.google.tn/adsid/integrator.", ""]),
        uH = p(["https://adservice.google.to/adsid/integrator.", ""]),
        vH = p(["https://adservice.google.com.tr/adsid/integrator.", ""]),
        wH = p(["https://adservice.google.tt/adsid/integrator.", ""]),
        xH = p(["https://adservice.google.com.tw/adsid/integrator.", ""]),
        yH = p(["https://adservice.google.co.tz/adsid/integrator.", ""]),
        zH = p(["https://adservice.google.com.ua/adsid/integrator.", ""]),
        AH = p(["https://adservice.google.co.ug/adsid/integrator.", ""]),
        BH = p(["https://adservice.google.co.uk/adsid/integrator.", ""]),
        CH = p(["https://adservice.google.com.uy/adsid/integrator.", ""]),
        DH = p(["https://adservice.google.co.uz/adsid/integrator.", ""]),
        EH = p(["https://adservice.google.com.vc/adsid/integrator.", ""]),
        FH = p(["https://adservice.google.co.ve/adsid/integrator.", ""]),
        GH = p(["https://adservice.google.vg/adsid/integrator.", ""]),
        HH = p(["https://adservice.google.co.vi/adsid/integrator.", ""]),
        IH = p(["https://adservice.google.com.vn/adsid/integrator.", ""]),
        JH = p(["https://adservice.google.vu/adsid/integrator.", ""]),
        KH = p(["https://adservice.google.ws/adsid/integrator.", ""]),
        LH = p(["https://adservice.google.rs/adsid/integrator.", ""]),
        MH = p(["https://adservice.google.co.za/adsid/integrator.", ""]),
        NH = p(["https://adservice.google.co.zm/adsid/integrator.", ""]),
        OH = p(["https://adservice.google.co.zw/adsid/integrator.", ""]),
        PH = p(["https://adservice.google.cat/adsid/integrator.", ""]),
        QH = new Map(
            [
                [
                    ".google.com",
                    function (a) {
                        return H(sE, a);
                    },
                ],
                [
                    ".google.ad",
                    function (a) {
                        return H(tE, a);
                    },
                ],
                [
                    ".google.ae",
                    function (a) {
                        return H(uE, a);
                    },
                ],
                [
                    ".google.com.af",
                    function (a) {
                        return H(vE, a);
                    },
                ],
                [
                    ".google.com.ag",
                    function (a) {
                        return H(wE, a);
                    },
                ],
                [
                    ".google.com.ai",
                    function (a) {
                        return H(xE, a);
                    },
                ],
                [
                    ".google.al",
                    function (a) {
                        return H(yE, a);
                    },
                ],
                [
                    ".google.co.ao",
                    function (a) {
                        return H(zE, a);
                    },
                ],
                [
                    ".google.com.ar",
                    function (a) {
                        return H(AE, a);
                    },
                ],
                [
                    ".google.as",
                    function (a) {
                        return H(BE, a);
                    },
                ],
                [
                    ".google.at",
                    function (a) {
                        return H(CE, a);
                    },
                ],
                [
                    ".google.com.au",
                    function (a) {
                        return H(DE, a);
                    },
                ],
                [
                    ".google.az",
                    function (a) {
                        return H(EE, a);
                    },
                ],
                [
                    ".google.com.bd",
                    function (a) {
                        return H(FE, a);
                    },
                ],
                [
                    ".google.be",
                    function (a) {
                        return H(GE, a);
                    },
                ],
                [
                    ".google.bf",
                    function (a) {
                        return H(HE, a);
                    },
                ],
                [
                    ".google.bg",
                    function (a) {
                        return H(IE, a);
                    },
                ],
                [
                    ".google.com.bh",
                    function (a) {
                        return H(JE, a);
                    },
                ],
                [
                    ".google.bi",
                    function (a) {
                        return H(KE, a);
                    },
                ],
                [
                    ".google.bj",
                    function (a) {
                        return H(LE, a);
                    },
                ],
                [
                    ".google.com.bn",
                    function (a) {
                        return H(ME, a);
                    },
                ],
                [
                    ".google.com.bo",
                    function (a) {
                        return H(NE, a);
                    },
                ],
                [
                    ".google.com.br",
                    function (a) {
                        return H(OE, a);
                    },
                ],
                [
                    ".google.bs",
                    function (a) {
                        return H(PE, a);
                    },
                ],
                [
                    ".google.bt",
                    function (a) {
                        return H(QE, a);
                    },
                ],
                [
                    ".google.co.bw",
                    function (a) {
                        return H(RE, a);
                    },
                ],
                [
                    ".google.com.bz",
                    function (a) {
                        return H(SE, a);
                    },
                ],
                [
                    ".google.ca",
                    function (a) {
                        return H(TE, a);
                    },
                ],
                [
                    ".google.cd",
                    function (a) {
                        return H(UE, a);
                    },
                ],
                [
                    ".google.cf",
                    function (a) {
                        return H(VE, a);
                    },
                ],
                [
                    ".google.cg",
                    function (a) {
                        return H(WE, a);
                    },
                ],
                [
                    ".google.ch",
                    function (a) {
                        return H(XE, a);
                    },
                ],
                [
                    ".google.ci",
                    function (a) {
                        return H(YE, a);
                    },
                ],
                [
                    ".google.co.ck",
                    function (a) {
                        return H(ZE, a);
                    },
                ],
                [
                    ".google.cl",
                    function (a) {
                        return H($E, a);
                    },
                ],
                [
                    ".google.cm",
                    function (a) {
                        return H(aF, a);
                    },
                ],
                [
                    ".google.com.co",
                    function (a) {
                        return H(bF, a);
                    },
                ],
                [
                    ".google.co.cr",
                    function (a) {
                        return H(cF, a);
                    },
                ],
                [
                    ".google.com.cu",
                    function (a) {
                        return H(dF, a);
                    },
                ],
                [
                    ".google.cv",
                    function (a) {
                        return H(eF, a);
                    },
                ],
                [
                    ".google.com.cy",
                    function (a) {
                        return H(fF, a);
                    },
                ],
                [
                    ".google.cz",
                    function (a) {
                        return H(gF, a);
                    },
                ],
                [
                    ".google.de",
                    function (a) {
                        return H(hF, a);
                    },
                ],
                [
                    ".google.dj",
                    function (a) {
                        return H(iF, a);
                    },
                ],
                [
                    ".google.dk",
                    function (a) {
                        return H(jF, a);
                    },
                ],
                [
                    ".google.dm",
                    function (a) {
                        return H(kF, a);
                    },
                ],
                [
                    ".google.dz",
                    function (a) {
                        return H(lF, a);
                    },
                ],
                [
                    ".google.com.ec",
                    function (a) {
                        return H(mF, a);
                    },
                ],
                [
                    ".google.ee",
                    function (a) {
                        return H(nF, a);
                    },
                ],
                [
                    ".google.com.eg",
                    function (a) {
                        return H(oF, a);
                    },
                ],
                [
                    ".google.es",
                    function (a) {
                        return H(pF, a);
                    },
                ],
                [
                    ".google.com.et",
                    function (a) {
                        return H(qF, a);
                    },
                ],
                [
                    ".google.fi",
                    function (a) {
                        return H(rF, a);
                    },
                ],
                [
                    ".google.com.fj",
                    function (a) {
                        return H(sF, a);
                    },
                ],
                [
                    ".google.fm",
                    function (a) {
                        return H(tF, a);
                    },
                ],
                [
                    ".google.fr",
                    function (a) {
                        return H(uF, a);
                    },
                ],
                [
                    ".google.ga",
                    function (a) {
                        return H(vF, a);
                    },
                ],
                [
                    ".google.ge",
                    function (a) {
                        return H(wF, a);
                    },
                ],
                [
                    ".google.gg",
                    function (a) {
                        return H(xF, a);
                    },
                ],
                [
                    ".google.com.gh",
                    function (a) {
                        return H(yF, a);
                    },
                ],
                [
                    ".google.com.gi",
                    function (a) {
                        return H(zF, a);
                    },
                ],
                [
                    ".google.gl",
                    function (a) {
                        return H(AF, a);
                    },
                ],
                [
                    ".google.gm",
                    function (a) {
                        return H(BF, a);
                    },
                ],
                [
                    ".google.gr",
                    function (a) {
                        return H(CF, a);
                    },
                ],
                [
                    ".google.com.gt",
                    function (a) {
                        return H(DF, a);
                    },
                ],
                [
                    ".google.gy",
                    function (a) {
                        return H(EF, a);
                    },
                ],
                [
                    ".google.com.hk",
                    function (a) {
                        return H(FF, a);
                    },
                ],
                [
                    ".google.hn",
                    function (a) {
                        return H(GF, a);
                    },
                ],
                [
                    ".google.hr",
                    function (a) {
                        return H(HF, a);
                    },
                ],
                [
                    ".google.ht",
                    function (a) {
                        return H(IF, a);
                    },
                ],
                [
                    ".google.hu",
                    function (a) {
                        return H(JF, a);
                    },
                ],
                [
                    ".google.co.id",
                    function (a) {
                        return H(KF, a);
                    },
                ],
                [
                    ".google.ie",
                    function (a) {
                        return H(LF, a);
                    },
                ],
                [
                    ".google.co.il",
                    function (a) {
                        return H(MF, a);
                    },
                ],
                [
                    ".google.im",
                    function (a) {
                        return H(NF, a);
                    },
                ],
                [
                    ".google.co.in",
                    function (a) {
                        return H(OF, a);
                    },
                ],
                [
                    ".google.iq",
                    function (a) {
                        return H(PF, a);
                    },
                ],
                [
                    ".google.is",
                    function (a) {
                        return H(QF, a);
                    },
                ],
                [
                    ".google.it",
                    function (a) {
                        return H(RF, a);
                    },
                ],
                [
                    ".google.je",
                    function (a) {
                        return H(SF, a);
                    },
                ],
                [
                    ".google.com.jm",
                    function (a) {
                        return H(TF, a);
                    },
                ],
                [
                    ".google.jo",
                    function (a) {
                        return H(UF, a);
                    },
                ],
                [
                    ".google.co.jp",
                    function (a) {
                        return H(VF, a);
                    },
                ],
                [
                    ".google.co.ke",
                    function (a) {
                        return H(WF, a);
                    },
                ],
                [
                    ".google.com.kh",
                    function (a) {
                        return H(XF, a);
                    },
                ],
                [
                    ".google.ki",
                    function (a) {
                        return H(YF, a);
                    },
                ],
                [
                    ".google.kg",
                    function (a) {
                        return H(ZF, a);
                    },
                ],
                [
                    ".google.co.kr",
                    function (a) {
                        return H($F, a);
                    },
                ],
                [
                    ".google.com.kw",
                    function (a) {
                        return H(aG, a);
                    },
                ],
                [
                    ".google.kz",
                    function (a) {
                        return H(bG, a);
                    },
                ],
                [
                    ".google.la",
                    function (a) {
                        return H(cG, a);
                    },
                ],
                [
                    ".google.com.lb",
                    function (a) {
                        return H(dG, a);
                    },
                ],
                [
                    ".google.li",
                    function (a) {
                        return H(eG, a);
                    },
                ],
                [
                    ".google.lk",
                    function (a) {
                        return H(fG, a);
                    },
                ],
                [
                    ".google.co.ls",
                    function (a) {
                        return H(gG, a);
                    },
                ],
                [
                    ".google.lt",
                    function (a) {
                        return H(hG, a);
                    },
                ],
                [
                    ".google.lu",
                    function (a) {
                        return H(iG, a);
                    },
                ],
                [
                    ".google.lv",
                    function (a) {
                        return H(jG, a);
                    },
                ],
                [
                    ".google.com.ly",
                    function (a) {
                        return H(kG, a);
                    },
                ],
                [
                    ".google.md",
                    function (a) {
                        return H(lG, a);
                    },
                ],
                [
                    ".google.me",
                    function (a) {
                        return H(mG, a);
                    },
                ],
                [
                    ".google.mg",
                    function (a) {
                        return H(nG, a);
                    },
                ],
                [
                    ".google.mk",
                    function (a) {
                        return H(oG, a);
                    },
                ],
                [
                    ".google.ml",
                    function (a) {
                        return H(pG, a);
                    },
                ],
                [
                    ".google.com.mm",
                    function (a) {
                        return H(qG, a);
                    },
                ],
                [
                    ".google.mn",
                    function (a) {
                        return H(rG, a);
                    },
                ],
                [
                    ".google.ms",
                    function (a) {
                        return H(sG, a);
                    },
                ],
                [
                    ".google.com.mt",
                    function (a) {
                        return H(tG, a);
                    },
                ],
                [
                    ".google.mu",
                    function (a) {
                        return H(uG, a);
                    },
                ],
                [
                    ".google.mv",
                    function (a) {
                        return H(vG, a);
                    },
                ],
                [
                    ".google.mw",
                    function (a) {
                        return H(wG, a);
                    },
                ],
                [
                    ".google.com.mx",
                    function (a) {
                        return H(xG, a);
                    },
                ],
                [
                    ".google.com.my",
                    function (a) {
                        return H(yG, a);
                    },
                ],
                [
                    ".google.co.mz",
                    function (a) {
                        return H(zG, a);
                    },
                ],
                [
                    ".google.com.na",
                    function (a) {
                        return H(AG, a);
                    },
                ],
                [
                    ".google.com.ng",
                    function (a) {
                        return H(BG, a);
                    },
                ],
                [
                    ".google.com.ni",
                    function (a) {
                        return H(CG, a);
                    },
                ],
                [
                    ".google.ne",
                    function (a) {
                        return H(DG, a);
                    },
                ],
                [
                    ".google.nl",
                    function (a) {
                        return H(EG, a);
                    },
                ],
                [
                    ".google.no",
                    function (a) {
                        return H(FG, a);
                    },
                ],
                [
                    ".google.com.np",
                    function (a) {
                        return H(GG, a);
                    },
                ],
                [
                    ".google.nr",
                    function (a) {
                        return H(HG, a);
                    },
                ],
                [
                    ".google.nu",
                    function (a) {
                        return H(IG, a);
                    },
                ],
                [
                    ".google.co.nz",
                    function (a) {
                        return H(JG, a);
                    },
                ],
                [
                    ".google.com.om",
                    function (a) {
                        return H(KG, a);
                    },
                ],
                [
                    ".google.com.pa",
                    function (a) {
                        return H(LG, a);
                    },
                ],
                [
                    ".google.com.pe",
                    function (a) {
                        return H(MG, a);
                    },
                ],
                [
                    ".google.com.pg",
                    function (a) {
                        return H(NG, a);
                    },
                ],
                [
                    ".google.com.ph",
                    function (a) {
                        return H(OG, a);
                    },
                ],
                [
                    ".google.com.pk",
                    function (a) {
                        return H(PG, a);
                    },
                ],
                [
                    ".google.pl",
                    function (a) {
                        return H(QG, a);
                    },
                ],
                [
                    ".google.pn",
                    function (a) {
                        return H(RG, a);
                    },
                ],
                [
                    ".google.com.pr",
                    function (a) {
                        return H(SG, a);
                    },
                ],
                [
                    ".google.ps",
                    function (a) {
                        return H(TG, a);
                    },
                ],
                [
                    ".google.pt",
                    function (a) {
                        return H(UG, a);
                    },
                ],
                [
                    ".google.com.py",
                    function (a) {
                        return H(VG, a);
                    },
                ],
                [
                    ".google.com.qa",
                    function (a) {
                        return H(WG, a);
                    },
                ],
                [
                    ".google.ro",
                    function (a) {
                        return H(XG, a);
                    },
                ],
                [
                    ".google.ru",
                    function (a) {
                        return H(YG, a);
                    },
                ],
                [
                    ".google.rw",
                    function (a) {
                        return H(ZG, a);
                    },
                ],
                [
                    ".google.com.sa",
                    function (a) {
                        return H($G, a);
                    },
                ],
                [
                    ".google.com.sb",
                    function (a) {
                        return H(aH, a);
                    },
                ],
                [
                    ".google.sc",
                    function (a) {
                        return H(bH, a);
                    },
                ],
                [
                    ".google.se",
                    function (a) {
                        return H(cH, a);
                    },
                ],
                [
                    ".google.com.sg",
                    function (a) {
                        return H(dH, a);
                    },
                ],
                [
                    ".google.sh",
                    function (a) {
                        return H(eH, a);
                    },
                ],
                [
                    ".google.si",
                    function (a) {
                        return H(fH, a);
                    },
                ],
                [
                    ".google.sk",
                    function (a) {
                        return H(gH, a);
                    },
                ],
                [
                    ".google.sn",
                    function (a) {
                        return H(hH, a);
                    },
                ],
                [
                    ".google.so",
                    function (a) {
                        return H(iH, a);
                    },
                ],
                [
                    ".google.sm",
                    function (a) {
                        return H(jH, a);
                    },
                ],
                [
                    ".google.sr",
                    function (a) {
                        return H(kH, a);
                    },
                ],
                [
                    ".google.st",
                    function (a) {
                        return H(lH, a);
                    },
                ],
                [
                    ".google.com.sv",
                    function (a) {
                        return H(mH, a);
                    },
                ],
                [
                    ".google.td",
                    function (a) {
                        return H(nH, a);
                    },
                ],
                [
                    ".google.tg",
                    function (a) {
                        return H(oH, a);
                    },
                ],
                [
                    ".google.co.th",
                    function (a) {
                        return H(pH, a);
                    },
                ],
                [
                    ".google.com.tj",
                    function (a) {
                        return H(qH, a);
                    },
                ],
                [
                    ".google.tl",
                    function (a) {
                        return H(rH, a);
                    },
                ],
                [
                    ".google.tm",
                    function (a) {
                        return H(sH, a);
                    },
                ],
                [
                    ".google.tn",
                    function (a) {
                        return H(tH, a);
                    },
                ],
                [
                    ".google.to",
                    function (a) {
                        return H(uH, a);
                    },
                ],
                [
                    ".google.com.tr",
                    function (a) {
                        return H(vH, a);
                    },
                ],
                [
                    ".google.tt",
                    function (a) {
                        return H(wH, a);
                    },
                ],
                [
                    ".google.com.tw",
                    function (a) {
                        return H(xH, a);
                    },
                ],
                [
                    ".google.co.tz",
                    function (a) {
                        return H(yH, a);
                    },
                ],
                [
                    ".google.com.ua",
                    function (a) {
                        return H(zH, a);
                    },
                ],
                [
                    ".google.co.ug",
                    function (a) {
                        return H(AH, a);
                    },
                ],
                [
                    ".google.co.uk",
                    function (a) {
                        return H(BH, a);
                    },
                ],
                [
                    ".google.com.uy",
                    function (a) {
                        return H(CH, a);
                    },
                ],
                [
                    ".google.co.uz",
                    function (a) {
                        return H(DH, a);
                    },
                ],
                [
                    ".google.com.vc",
                    function (a) {
                        return H(EH, a);
                    },
                ],
                [
                    ".google.co.ve",
                    function (a) {
                        return H(FH, a);
                    },
                ],
                [
                    ".google.vg",
                    function (a) {
                        return H(GH, a);
                    },
                ],
                [
                    ".google.co.vi",
                    function (a) {
                        return H(HH, a);
                    },
                ],
                [
                    ".google.com.vn",
                    function (a) {
                        return H(IH, a);
                    },
                ],
                [
                    ".google.vu",
                    function (a) {
                        return H(JH, a);
                    },
                ],
                [
                    ".google.ws",
                    function (a) {
                        return H(KH, a);
                    },
                ],
                [
                    ".google.rs",
                    function (a) {
                        return H(LH, a);
                    },
                ],
                [
                    ".google.co.za",
                    function (a) {
                        return H(MH, a);
                    },
                ],
                [
                    ".google.co.zm",
                    function (a) {
                        return H(NH, a);
                    },
                ],
                [
                    ".google.co.zw",
                    function (a) {
                        return H(OH, a);
                    },
                ],
                [
                    ".google.cat",
                    function (a) {
                        return H(PH, a);
                    },
                ],
            ].map(function (a) {
                var b = q(a);
                a = b.next().value;
                b = b.next().value;
                var c = {};
                return [a, ((c.json = b("json")), (c.js = b("js")), (c["sync.js"] = b("sync.js")), c)];
            })
        );
    var RH = function (a, b, c) {
        var d = "script";
        d = void 0 === d ? "" : d;
        var e = Ef("LINK", a);
        try {
            if (((e.rel = "preload"), vb("preload", "stylesheet"))) {
                e.href = Sd(b).toString();
                var f = Ie('style[nonce],link[rel="stylesheet"][nonce]', e.ownerDocument && e.ownerDocument.defaultView);
                f && e.setAttribute("nonce", f);
            } else {
                if (b instanceof Rd) var g = Sd(b).toString();
                else {
                    if (b instanceof Vd) var h = Wd(b);
                    else {
                        if (b instanceof Vd) var k = b;
                        else (b = "object" == typeof b && b.Ta ? b.Ga() : String(b)), Yd.test(b) || (b = "about:invalid#zClosurez"), (k = new Vd(b, Ud));
                        h = Wd(k);
                    }
                    g = h;
                }
                e.href = g;
            }
        } catch (n) {
            return;
        }
        d && (e.as = d);
        c && e.setAttribute("nonce", c);
        if ((a = a.getElementsByTagName("head")[0]))
            try {
                a.appendChild(e);
            } catch (n) {}
    };
    var SH = t,
        UH = function (a) {
            var b = new Map([["domain", t.location.hostname]]);
            TH[3] >= Za() && b.set("adsid", TH[1]);
            return sh(QH.get(a).js, b);
        },
        TH,
        VH,
        WH = function () {
            SH = t;
            TH = SH.googleToken = SH.googleToken || {};
            var a = Za();
            (TH[1] && TH[3] > a && 0 < TH[2]) || ((TH[1] = ""), (TH[2] = -1), (TH[3] = -1), (TH[4] = ""), (TH[6] = ""));
            VH = SH.googleIMState = SH.googleIMState || {};
            QH.has(VH[1]) || (VH[1] = ".google.com");
            Array.isArray(VH[5]) || (VH[5] = []);
            "boolean" !== typeof VH[6] && (VH[6] = !1);
            Array.isArray(VH[7]) || (VH[7] = []);
            "number" !== typeof VH[8] && (VH[8] = 0);
        },
        XH = {
            Ec: function () {
                return 0 < VH[8];
            },
            rf: function () {
                VH[8]++;
            },
            sf: function () {
                0 < VH[8] && VH[8]--;
            },
            tf: function () {
                VH[8] = 0;
            },
            shouldRetry: function () {
                return !1;
            },
            Gd: function () {
                return VH[5];
            },
            xd: function (a) {
                try {
                    a();
                } catch (b) {
                    t.setTimeout(function () {
                        throw b;
                    }, 0);
                }
            },
            Wc: function () {
                if (!XH.Ec()) {
                    var a = t.document,
                        b = function (e) {
                            e = UH(e);
                            a: {
                                try {
                                    var f = Ie("script[nonce]", void 0);
                                    break a;
                                } catch (g) {}
                                f = void 0;
                            }
                            RH(a, e.toString(), f);
                            f = Ef("SCRIPT", a);
                            f.type = "text/javascript";
                            f.onerror = function () {
                                return t.processGoogleToken({}, 2);
                            };
                            f.src = se(e);
                            ve(f);
                            try {
                                (a.head || a.body || a.documentElement).appendChild(f), XH.rf();
                            } catch (g) {}
                        },
                        c = VH[1];
                    b(c);
                    ".google.com" != c && b(".google.com");
                    b = {};
                    var d = ((b.newToken = "FBT"), b);
                    t.setTimeout(function () {
                        return t.processGoogleToken(d, 1);
                    }, 1e3);
                }
            },
        };
    function YH(a) {
        WH();
        var b = SH.googleToken[5] || 0;
        a && (0 != b || TH[3] >= Za() ? XH.xd(a) : (XH.Gd().push(a), XH.Wc()));
        (TH[3] >= Za() && TH[2] >= Za()) || XH.Wc();
    }
    var ZH = function (a) {
        t.processGoogleToken =
            t.processGoogleToken ||
            function (b, c) {
                var d = b;
                d = void 0 === d ? {} : d;
                c = void 0 === c ? 0 : c;
                b = d.newToken || "";
                var e = "NT" == b,
                    f = parseInt(d.freshLifetimeSecs || "", 10),
                    g = parseInt(d.validLifetimeSecs || "", 10),
                    h = d["1p_jar"] || "";
                d = d.pucrd || "";
                WH();
                1 == c ? XH.tf() : XH.sf();
                if (!b && XH.shouldRetry()) QH.has(".google.com") && (VH[1] = ".google.com"), XH.Wc();
                else {
                    var k = (SH.googleToken = SH.googleToken || {}),
                        n = 0 == c && b && "string" === typeof b && !e && "number" === typeof f && 0 < f && "number" === typeof g && 0 < g && "string" === typeof h;
                    e = e && !XH.Ec() && (!(TH[3] >= Za()) || "NT" == TH[1]);
                    var m = !(TH[3] >= Za()) && 0 != c;
                    if (n || e || m)
                        (e = Za()),
                            (f = e + 1e3 * f),
                            (g = e + 1e3 * g),
                            1e-5 > Math.random() && Sf(t, "https://pagead2.googlesyndication.com/pagead/gen_204?id=imerr&err=" + c),
                            (k[5] = c),
                            (k[1] = b),
                            (k[2] = f),
                            (k[3] = g),
                            (k[4] = h),
                            (k[6] = d),
                            WH();
                    if (n || !XH.Ec()) {
                        c = XH.Gd();
                        for (b = 0; b < c.length; b++) XH.xd(c[b]);
                        c.length = 0;
                    }
                }
            };
        YH(a);
    };
    var $H = function (a, b) {
        b = void 0 === b ? 500 : b;
        L.call(this);
        this.j = a;
        this.timeoutMs = b;
        this.h = null;
        this.o = {};
        this.A = 0;
        this.l = null;
    };
    r($H, L);
    $H.prototype.N = function () {
        this.o = {};
        this.l && (Ee(this.j, "message", this.l), delete this.l);
        delete this.o;
        delete this.j;
        delete this.h;
        L.prototype.N.call(this);
    };
    var bI = function (a) {
            var b;
            return "function" === typeof (null == (b = a.j) ? void 0 : b.__uspapi) || null != aI(a);
        },
        dI = function (a, b) {
            var c = {};
            if (bI(a)) {
                var d = ze(function () {
                    return b(c);
                });
                cI(a, function (e, f) {
                    f && (c = e);
                    d();
                });
                setTimeout(d, a.timeoutMs);
            } else b(c);
        },
        cI = function (a, b) {
            var c;
            "function" === typeof (null == (c = a.j) ? void 0 : c.__uspapi)
                ? ((a = a.j.__uspapi), a("getUSPData", 1, b))
                : aI(a) && (eI(a), (c = ++a.A), (a.o[c] = b), a.h && ((b = {}), a.h.postMessage(((b.__uspapiCall = { command: "getUSPData", version: 1, callId: c }), b), "*")));
        },
        aI = function (a) {
            if (a.h) return a.h;
            a.h = Df(a.j, "__uspapiLocator");
            return a.h;
        },
        eI = function (a) {
            a.l ||
                ((a.l = function (b) {
                    try {
                        var c = {};
                        "string" === typeof b.data ? (c = JSON.parse(b.data)) : (c = b.data);
                        var d = c.__uspapiReturn;
                        var e;
                        null == (e = a.o) || e[d.callId](d.returnValue, d.success);
                    } catch (f) {}
                }),
                De(a.j, "message", a.l));
        };
    var fI = function (a) {
        M.call(this);
        var b = this,
            c = cy(ey(this.getSettings()));
        c && 0 < c.length && (ri.reset(), ti(new Vi(c)));
        this.B = new qE();
        this.h = a;
        this.D = new Map();
        this.A = this.h.H;
        this.K = new Iu(this);
        zj(this, this.K);
        this.L = new iy(window);
        this.M = new $H(window);
        this.j = null;
        this.G = {};
        this.I = [];
        0 != fy.h ? ((this.l = new NA()), zj(this, this.l)) : (this.l = PA());
        Ty() &&
            (this.l.init(oD(this.A)),
            (this.C = UA(this.l, this.h.J)),
            yj(this, function () {
                var d = b.C;
                b.l.A.delete(d);
                0 != fy.h && (G(Pr).o[d] = null);
            }));
    };
    r(fI, M);
    fI.prototype.destroy = function () {
        this.dispose();
    };
    fI.prototype.getVersion = function () {
        return "h.3.507.1";
    };
    fI.prototype.requestAds = function (a, b) {
        var c = this,
            d = [],
            e = null;
        ky(this.L) &&
            d.push(
                new Promise(function (g) {
                    ny(c.L, function (h) {
                        e = h;
                        g();
                    });
                })
            );
        var f = null;
        bI(this.M) &&
            d.push(
                new Promise(function (g) {
                    dI(c.M, function (h) {
                        f = h;
                        g();
                    });
                })
            );
        Promise.all(d).then(function () {
            gI(c, a, b, { cd: e, fd: f });
        });
    };
    var gI = function (a, b, c, d) {
        var e = b.adTagUrl;
        e && HA().report(8, { adtagurl: e, customPlayback: BD(a.h), customClick: null != a.h.C });
        var f = "goog_" + Oe++;
        a.D.set(f, c || null);
        var g = hI({ adTagUrl: e, Ld: !1, cd: d.cd, fd: d.fd });
        a.j = sy(e, g || {});
        bA(a.j, function () {
            iI(a);
        });
        c = [jI(a)];
        kI(uy(a.j)) && c.push(lI());
        Promise.all(c).then(function () {
            var h = {};
            h = ((h.limaExperimentIds = si().sort().join(",")), h);
            var k = a.getSettings().aa(0 != fy.h ? G(Pr).l : a.l.B),
                n = b.adTagUrl;
            var m = {};
            m.contentMediaUrl = a.h.I;
            m.customClickTrackingProvided = null != a.h.C;
            a: {
                var x = Hl();
                x = q(x);
                for (var v = x.next(); !v.done; v = x.next())
                    if (((v = v.value), v.url && v.url.includes("amp=1"))) {
                        x = !0;
                        break a;
                    }
                x = null != window.context ? 0 < parseInt(window.context.ampcontextVersion, 10) : null != Kl().l;
            }
            m.isAmp = x;
            a: {
                try {
                    var A = window.top.location.href;
                } catch (qv) {
                    A = 2;
                    break a;
                }
                A = null == A ? 2 : A == window.document.location.href ? 0 : 1;
            }
            m.iframeState = A;
            m.imaHostingDomain = window.document.domain;
            m.imaHostingPageUrl = window.document.URL;
            if (On()) var C = window.location.href;
            else {
                v = Kl();
                A = v.j;
                x = v.h;
                v = v.l;
                var O = null;
                if (v)
                    try {
                        C = qt(v.url);
                        var la = C.h,
                            na = dA(la, "/v/");
                        na || (na = dA(la, "/a/"));
                        if (!na) throw Error("Can not extract standalone amp url.");
                        var Ja = dA("/" + na, "/s/"),
                            va = ft(C.l);
                        va.remove("amp_js_v");
                        va.remove("amp_lite");
                        var lc = Ja ? qt("https://" + Ja) : qt("http://" + na);
                        et(lc, va);
                        O = lc.toString();
                    } catch (qv) {
                        O = null;
                    }
                C = O ? O : A && A.url ? A.url : x && x.url ? x.url : "";
            }
            m.topAccessiblePageUrl = C;
            m.referrer = window.document.referrer;
            m.domLoadTime = a.A.K;
            m.sdkImplLoadTime = a.A.L;
            m.supportsResizing = !BD(a.h);
            C = z().location.ancestorOrigins;
            m.topOrigin = C ? (0 < C.length && 200 > C[C.length - 1].length ? C[C.length - 1] : "") : null;
            m.osdId = a.C;
            m.usesCustomVideoPlayback = BD(a.h);
            m.usesInlinePlayback = a.h.D;
            la = a.h.G;
            C = [];
            Ja = na = "";
            if (null != la) {
                na = la;
                Ja = !0;
                Ja = void 0 === Ja ? !1 : Ja;
                va = [];
                for (lc = 0; na && 25 > lc; ++lc) {
                    A = "";
                    (void 0 !== Ja && Ja) || (A = (A = 9 !== na.nodeType && na.id) ? "/" + A : "");
                    a: {
                        if (na && na.nodeName && na.parentElement) {
                            x = na.nodeName.toString().toLowerCase();
                            v = na.parentElement.childNodes;
                            for (var Ym = (O = 0); Ym < v.length; ++Ym) {
                                var Zm = v[Ym];
                                if (Zm.nodeName && Zm.nodeName.toString().toLowerCase() === x) {
                                    if (na === Zm) {
                                        x = "." + O;
                                        break a;
                                    }
                                    ++O;
                                }
                            }
                        }
                        x = "";
                    }
                    va.push((na.nodeName && na.nodeName.toString().toLowerCase()) + A + x);
                    na = na.parentElement;
                }
                na = va.join();
                if (la) {
                    la = ((la = la.ownerDocument) && (la.defaultView || la.parentWindow)) || null;
                    Ja = [];
                    if (la)
                        try {
                            var db = la.parent;
                            for (va = 0; db && db !== la && 25 > va; ++va) {
                                var Gc = db.frames;
                                for (lc = 0; lc < Gc.length; ++lc)
                                    if (la === Gc[lc]) {
                                        Ja.push(lc);
                                        break;
                                    }
                                la = db;
                                db = la.parent;
                            }
                        } catch (qv) {}
                    Ja = Ja.join();
                } else Ja = "";
            }
            C.push(na, Ja);
            if (null != n) {
                for (db = 0; db < Jt.length - 1; ++db) C.push(rf(n, Jt[db]) || "");
                n = rf(n, "videoad_start_delay");
                db = "";
                n && ((n = parseInt(n, 10)), (db = 0 > n ? "postroll" : 0 == n ? "preroll" : "midroll"));
                C.push(db);
            } else for (n = 0; n < Jt.length; ++n) C.push("");
            n = C.join(":");
            db = n.length;
            if (0 == db) n = 0;
            else {
                Gc = 305419896;
                for (C = 0; C < db; C++) Gc ^= ((Gc << 5) + (Gc >> 2) + n.charCodeAt(C)) & 4294967295;
                n = 0 < Gc ? Gc : 4294967296 + Gc;
            }
            m = ((m.videoAdKey = n.toString()), m);
            n = {};
            h = ((n.consentSettings = g), (n.imalibExperiments = h), (n.settings = k), (n.videoEnvironment = m), n);
            Object.assign(h, b.aa());
            a.j &&
                fy.j &&
                ((m = a.j),
                (k = new gy()),
                (m = !uy(m)),
                E(k, 5, m),
                (h.isBrowserCookieEnabled = a.B.isSupported(k)),
                (m = k ? pE("__gads", k, a.B.h) : null),
                null !== m && (h.gfpCookieValue = m),
                rj.isSelected() && ((m = k ? pE("__gpi", k, a.B.h) : null), null !== m && (h.gfpCookieV2Id = m), (k = k ? pE("__gpi_opt_out", k, a.B.h) : null), null !== k && (h.gfpCookieV2OptOut = k)));
            h.trustTokenStatuses = a.I;
            if ((k = wz(aA(a.j)))) (a.G.espSignals = k), (h.espSignals = k);
            h.isEapLoader = !1;
            k = oD(a.A, f);
            a.K.P(k, "adsLoader", a.R);
            Vy(k, "adsLoader", "requestAds", h);
        });
    };
    fI.prototype.getSettings = function () {
        return fy;
    };
    fI.prototype.contentComplete = function () {
        Vy(oD(this.A), "adsLoader", "contentComplete");
    };
    var kI = function (a) {
        return a ? !1 : !Qy();
    };
    fI.prototype.R = function (a) {
        var b = a.ha;
        switch (b) {
            case "adsLoaded":
                b = a.ka;
                a = a.Mb;
                b = new Z(this.l, this.h, b.adTagUrl || "", b.adCuePoints, this.C, b.isCustomClickTrackingAllowed, oD(this.A, a));
                this.dispatchEvent(new kE(b, mI(this, a)));
                break;
            case "error":
                b = a.ka;
                this.dispatchEvent(new FD(new DD(b), mI(this, a.Mb)));
                a = { error: b.errorCode, vis: xh(document) };
                HA().report(7, a);
                break;
            case "cookieUpdate":
                a = a.ka;
                if (null == a) break;
                if (fy.j) {
                    b = new gy();
                    E(b, 5, !0);
                    var c = a.gfpCookie;
                    c && rE(this.B, "__gads", Eg(Dl, c), b);
                    rj.isSelected() && (c = a.gfpCookieV2) && rE(this.B, "__gpi", Eg(Dl, c), b);
                }
                nI(this, a.encryptedSignalBidderIds || []);
                break;
            case "trackingUrlPinged":
                this.dispatchEvent(new HD(b, null, a.ka));
        }
    };
    var nI = function (a, b) {
            0 != b.length &&
                (b = cA(
                    b.map(function (c) {
                        return { Qa: c };
                    }),
                    a.j
                )) &&
                b.forEach(function (c) {
                    return c.then(function (d) {
                        d && iI(a);
                    });
                });
        },
        iI = function (a) {
            var b = wz(aA(a.j));
            b && ((a.G.espSignals = b), Vy(oD(a.A), "adsLoader", "signalsRefresh", a.G));
        },
        mI = function (a, b) {
            var c = a.D.get(b);
            a.D.delete(b);
            return c;
        },
        hI = function (a) {
            var b,
                c = (b = void 0 === b ? t : b);
            c = void 0 === c ? t : c;
            (c = !!c.googlefc) || ((c = b.top), (c = void 0 === c ? t.top : c), (c = Cf(c, "googlefcPresent")));
            var d = void 0 === d ? t : d;
            d = Cf(d.top, "googlefcLoaded");
            b = a.cd;
            var e = a.fd,
                f = {};
            return (
                (f.gfcPresent = !1),
                (f.gfcLoaded = d),
                (f.gfcUserConsent = c ? 4 : 0),
                (f.isGdprLoader = a.Ld),
                (f.addtlConsent = b ? b.addtlConsent : null),
                (f.gdprApplies = b ? b.gdprApplies : null),
                (f.tcString = b ? b.tcString : null),
                (f.uspString = e ? e.uspString : null),
                f
            );
        },
        lI = function () {
            return new Promise(function (a) {
                ZH(function () {
                    WH();
                    fy.H = TH[1] || "";
                    WH();
                    fy.V = TH[6] || "";
                    WH();
                    fy.K = TH[4] || "";
                    a();
                });
            });
        },
        jI = function (a) {
            if (!qj.isSelected()) return Promise.resolve();
            var b = {};
            HA().report(158, ((b.tte = !!document.hasTrustToken), b));
            var c;
            b = null != (c = kD(new eD(!uy(a.j), !1, !0))) ? c : Promise.resolve();
            return Promise.race([b, Ik()]).then(function (d) {
                var e = window.goog_tt_state_map,
                    f,
                    g = [];
                (f = null == e ? void 0 : e.get(bD.issuerOrigin)) && g.push(f);
                a.I = g;
                HA().report(158, { timedOut: "timed out" == d, status: JSON.stringify(a.I) });
            });
        };
    fI.prototype.contentComplete = fI.prototype.contentComplete;
    fI.prototype.getSettings = fI.prototype.getSettings;
    fI.prototype.requestAds = fI.prototype.requestAds;
    fI.prototype.getVersion = fI.prototype.getVersion;
    fI.prototype.destroy = fI.prototype.destroy;
    u("google.ima.AdCuePoints", kC, window);
    u("google.ima.AdDisplayContainer", CD, window);
    u(
        "google.ima.AdError.ErrorCode",
        {
            DEPRECATED_ERROR_CODE: -1,
            VAST_MALFORMED_RESPONSE: 100,
            VAST_SCHEMA_VALIDATION_ERROR: 101,
            VAST_UNSUPPORTED_VERSION: 102,
            VAST_TRAFFICKING_ERROR: 200,
            VAST_UNEXPECTED_LINEARITY: 201,
            VAST_UNEXPECTED_DURATION_ERROR: 202,
            VAST_WRAPPER_ERROR: 300,
            VAST_LOAD_TIMEOUT: 301,
            VAST_TOO_MANY_REDIRECTS: 302,
            VAST_NO_ADS_AFTER_WRAPPER: 303,
            VIDEO_PLAY_ERROR: 400,
            VAST_MEDIA_LOAD_TIMEOUT: 402,
            VAST_LINEAR_ASSET_MISMATCH: 403,
            VAST_PROBLEM_DISPLAYING_MEDIA_FILE: 405,
            OVERLAY_AD_PLAYING_FAILED: 500,
            NONLINEAR_DIMENSIONS_ERROR: 501,
            Ug: 502,
            wh: 503,
            Vf: 602,
            Qf: 603,
            UNKNOWN_ERROR: 900,
            VPAID_ERROR: 901,
            FAILED_TO_REQUEST_ADS: 1005,
            VAST_ASSET_NOT_FOUND: 1007,
            VAST_EMPTY_RESPONSE: 1009,
            UNKNOWN_AD_RESPONSE: 1010,
            UNSUPPORTED_LOCALE: 1011,
            ADS_REQUEST_NETWORK_ERROR: 1012,
            INVALID_AD_TAG: 1013,
            STREAM_INITIALIZATION_FAILED: 1020,
            ASSET_FALLBACK_FAILED: 1021,
            INVALID_ARGUMENTS: 1101,
            Ng: 1204,
            AUTOPLAY_DISALLOWED: 1205,
            CONSENT_MANAGEMENT_PROVIDER_NOT_READY: 1300,
            mh: 2002,
        },
        window
    );
    u("google.ima.AdError.ErrorCode.VIDEO_ELEMENT_USED", -1, window);
    u("google.ima.AdError.ErrorCode.VIDEO_ELEMENT_REQUIRED", -1, window);
    u("google.ima.AdError.ErrorCode.VAST_MEDIA_ERROR", -1, window);
    u("google.ima.AdError.ErrorCode.ADSLOT_NOT_VISIBLE", -1, window);
    u("google.ima.AdError.ErrorCode.OVERLAY_AD_LOADING_FAILED", -1, window);
    u("google.ima.AdError.ErrorCode.VAST_MALFORMED_RESPONSE", -1, window);
    u("google.ima.AdError.ErrorCode.COMPANION_AD_LOADING_FAILED", -1, window);
    u("google.ima.AdError.Type", ED, window);
    u("google.ima.AdErrorEvent.Type", GD, window);
    u("google.ima.AdEvent.Type", ID, window);
    u("google.ima.AdsLoader", fI, window);
    u("google.ima.AdsManagerLoadedEvent.Type", lE, window);
    u("google.ima.CompanionAdSelectionSettings", Xy, window);
    u("google.ima.CompanionAdSelectionSettings.CreativeType", Yy, void 0);
    u("google.ima.CompanionAdSelectionSettings.ResourceType", Zy, void 0);
    u("google.ima.CompanionAdSelectionSettings.SizeCriteria", $y, void 0);
    u("google.ima.CustomContentLoadedEvent.Type.CUSTOM_CONTENT_LOADED", "deprecated-event", window);
    u("ima.ImaSdkSettings", W, window);
    u("google.ima.settings", fy, window);
    u("google.ima.ImaSdkSettings.CompanionBackfillMode", { ALWAYS: "always", ON_MASTER_AD: "on_master_ad" }, void 0);
    u("google.ima.ImaSdkSettings.VpaidMode", { DISABLED: 0, ENABLED: 1, INSECURE: 2 }, void 0);
    u("google.ima.AdsRenderingSettings", $A, window);
    u("google.ima.AdsRenderingSettings.AUTO_SCALE", -1, window);
    u("google.ima.AdsRequest", mE, window);
    u("google.ima.VERSION", "3.507.1", void 0);
    u("google.ima.OmidAccessMode", { LIMITED: "limited", DOMAIN: "domain", FULL: "full" }, void 0);
    u("google.ima.OmidVerificationVendor", { OTHER: 1, MOAT: 2, DOUBLEVERIFY: 3, INTEGRAL_AD_SCIENCE: 4, PIXELATE: 4, NIELSEN: 6, COMSCORE: 7, MEETRICS: 8, GOOGLE: 9 }, void 0);
    u("google.ima.UiElements", { AD_ATTRIBUTION: "adAttribution", COUNTDOWN: "countdown" }, void 0);
    u("google.ima.ViewMode", { NORMAL: "normal", FULLSCREEN: "fullscreen" }, void 0);
})();
