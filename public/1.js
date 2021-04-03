(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[1],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/SellDevice.vue?vue&type=script&lang=js&":
/*!*********************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/SellDevice.vue?vue&type=script&lang=js& ***!
  \*********************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
/* harmony default export */ __webpack_exports__["default"] = ({
  props: {
    category: {
      type: Object,
      required: false
    },
    steps: {
      type: Array,
      required: false
    },
    categories: {
      type: Array,
      required: false
    },
    faqs: {
      type: Object,
      required: false
    }
  },
  data: function data() {
    return {
      name: '',
      searchDevices: {},
      faqsIsset: false,
      priceError: false,
      priceLoaded: false,
      stepSelected: false,
      selectedStep: null,
      selectedSteps: [],
      selectedAccesories: [],
      stepIndex: 0,
      options: [],
      summ: parseFloat(this.category.custom_text)
    };
  },
  methods: {
    searchDevice: function searchDevice() {
      var _this = this;

      this.searchDevices = {};
      axios.get(Router.route('header-search', {
        name: this.name
      })).then(function (data) {
        _this.searchDevices = data.data;
      })["catch"](function (_ref) {
        var errors = _ref.response.data.errors;
        console.log(errors);
      });
    },
    backStep: function backStep() {
      var _this2 = this;

      if (this.stepIndex > 0) {
        var isNew = false;

        _.each(this.selectedSteps, function (value, key) {
          if (value) {
            if (value.value === "Brand New") {
              isNew = true;
              _this2.selectedAccesories = [];
            }
          }
        });

        if (this.selectedStep) {
          if (this.selectedStep.is_checkbox) {
            this.selectedAccesories = [];
          } else {
            this.selectedSteps[this.steps[this.stepIndex].items[0].name_id] = null;
          }
        }

        this.stepIndex--;
        var selectedStepValue = this.steps[this.stepIndex];

        if (selectedStepValue) {
          if (isNew && selectedStepValue.is_checkbox) {
            this.stepIndex--;
            this.selectedStep = this.steps[this.stepIndex];
          } else {
            this.selectedStep = selectedStepValue;
          }
        } else {
          this.selectedStep = null;
        }
      }

      this.valuate();
      this.stepSelected = true;
      this.$forceUpdate();
    },
    nextStep: function nextStep() {
      var _this3 = this;

      var isNew = false;

      _.each(this.selectedSteps, function (value, key) {
        if (value) {
          if (value.value === "Brand New") {
            isNew = true;
            _this3.selectedAccesories = [];
          }
        }
      });

      this.stepIndex++;
      var selectedStepValue = this.steps[this.stepIndex];

      if (selectedStepValue) {
        if (isNew && selectedStepValue.is_checkbox) {
          this.stepIndex++;
          this.selectedStep = this.steps[this.stepIndex];
        } else {
          this.selectedStep = selectedStepValue;
        }
      } else {
        this.selectedStep = null;
      }

      this.valuate();
      this.stepSelected = false;

      if (this.selectedStep) {
        _.each(this.selectedStep['items'], function (value, key) {
          if (_this3.selectedSteps[value.name_id]) {
            _this3.stepSelected = true;
          }
        });
      }

      var offset = 125; // sticky nav height

      var el = document.querySelector('#step-title'); // element you are scrolling to

      window.scroll({
        top: el.offsetTop - offset,
        left: 0,
        behavior: 'smooth'
      });
    },
    valuate: function valuate() {
      var _this4 = this;

      this.priceLoaded = false;
      this.summ = parseFloat(this.category.custom_text);

      if (!this.selectedStep) {
        var steps = [];

        _.each(this.selectedSteps, function (value, key) {
          if (value) {
            value.value = value.value.replace('&', '');
            steps.push(value);
          }
        });

        _.each(this.selectedAccesories, function (value, key) {
          if (value) {
            value.value = value.value.replace('&', '');
            steps.push(value);
          }
        });

        axios.post(Router.route('get-price', {
          steps: JSON.stringify(steps),
          category_id: this.category.id
        })).then(function (data) {
          _this4.summ = data.data.price;
          setTimeout(function () {
            return _this4.priceLoaded = true;
          }, 1000);
        })["catch"](function (_ref2) {
          var errors = _ref2.response.data.errors;
          _this4.priceError = true;
        });
      }

      _.each(this.selectedSteps, function (key, value) {
        if (key) {
          if (key.price_plus) {
            _this4.summ += parseFloat(key.price_plus);
          }

          if (key.price_percent) {
            var percent = _this4.summ * parseFloat(key.price_percent) / 100;
            _this4.summ += percent;
          }
        }
      });
    },
    selectOption: function selectOption(option, index) {
      _.set(this.selectedStep.items[index], 'checked', true);

      this.stepSelected = true;
      this.$forceUpdate();
    },
    addToBox: function addToBox() {
      var orders = {
        order: []
      };
      var localValue = localStorage.getItem("orders");
      var storedNames = JSON.parse(localStorage.getItem("orders"));

      if (localValue) {
        //Добавляем или изменяем значение:
        storedNames.order.push({
          id: this.category.id,
          device: this.category,
          steps: this.selectedSteps.concat(this.selectedAccesories),
          summ: this.summ,
          total: this.summ,
          ctn: 1
        });
        localStorage.setItem("orders", JSON.stringify(storedNames));
      } else {
        orders.order.push({
          id: this.category.id,
          device: this.category,
          steps: this.selectedSteps.concat(this.selectedAccesories),
          summ: this.summ,
          total: this.summ,
          ctn: 1
        });
        localStorage.setItem("orders", JSON.stringify(orders));
      }

      location.href = Router.route('cart');
    }
  },
  created: function created() {
    this.faqsIsset = _.isEmpty(this.faqs);

    if (this.steps.length) {
      this.selectedStep = _.head(this.steps);
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/SellDevice.vue?vue&type=template&id=5e78461a&":
/*!*************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/SellDevice.vue?vue&type=template&id=5e78461a& ***!
  \*************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", [
    _c("h1", [_vm._v("Start Selling")]),
    _vm._v(" "),
    _c("div", { staticClass: "description" }, [
      _vm._v("Find the product you'd like to trade-in for cash")
    ]),
    _vm._v(" "),
    _vm.steps.length
      ? _c(
          "ul",
          { staticClass: "order-steps-list", attrs: { id: "scrolled" } },
          [
            _vm._l(_vm.steps, function(step, index) {
              return _c(
                "li",
                { class: _vm.stepIndex === index ? "active-step" : "" },
                [
                  _c("a", { attrs: { href: "javascript:void(0)" } }, [
                    _c("i", [_vm._v(_vm._s(index + 1))]),
                    _vm._v(" "),
                    _c("span", [_vm._v(_vm._s(index + 1))])
                  ])
                ]
              )
            }),
            _vm._v(" "),
            _c("li", [
              _c("a", { attrs: { href: "javascript:void(0)" } }, [
                _c("i", [_vm._v(_vm._s(_vm.steps.length + 1))]),
                _vm._v(" "),
                _c("span", [_vm._v("Finish ")])
              ])
            ])
          ],
          2
        )
      : _vm._e(),
    _vm._v(" "),
    _vm.steps.length
      ? _c("div", { staticClass: "order-options-content" }, [
          _c("div", { staticClass: "order-options-product" }, [
            _c("div", { staticClass: "product-image" }, [
              _c("img", { attrs: { src: _vm.category.image, alt: "" } }),
              _vm._v(" "),
              _c("div", { staticClass: "name" }, [
                _c("span", [_vm._v(_vm._s(_vm.category.name))])
              ])
            ]),
            _vm._v(" "),
            _vm.selectedSteps.length
              ? _c(
                  "ul",
                  { staticClass: "selected-list", attrs: { id: "options" } },
                  [
                    _vm._l(_vm.selectedSteps, function(option) {
                      return option
                        ? _c("li", [_vm._v(_vm._s(option ? option.value : ""))])
                        : _vm._e()
                    }),
                    _vm._v(" "),
                    _vm._l(_vm.selectedAccesories, function(option) {
                      return option && _vm.selectedAccesories.length
                        ? _c("li", [_vm._v(_vm._s(option ? option.value : ""))])
                        : _vm._e()
                    })
                  ],
                  2
                )
              : _vm._e()
          ]),
          _vm._v(" "),
          _c("div", { staticClass: "order-options-detail" }, [
            _vm.selectedStep
              ? _c(
                  "div",
                  { staticClass: "order-options-block", attrs: { id: "step" } },
                  [
                    _c("h4", { attrs: { id: "step-title" } }, [
                      _vm._v(_vm._s(_vm.selectedStep.title))
                    ]),
                    _vm._v(" "),
                    _vm.selectedStep.tip
                      ? _c("div", { staticClass: "helping-block" }, [
                          _c(
                            "a",
                            {
                              staticClass: "popup-open",
                              attrs: {
                                href: "#helping-popup",
                                "data-effect": "mfp-zoom-in"
                              }
                            },
                            [
                              _c("img", {
                                attrs: {
                                  src: __webpack_require__(/*! ../../client/images/icon_help.svg */ "./resources/client/images/icon_help.svg"),
                                  alt: ""
                                }
                              }),
                              _vm._v(" "),
                              _c("span", [
                                _vm._v(
                                  _vm._s(
                                    _vm.selectedStep.tip
                                      ? _vm.selectedStep.tip.name
                                      : ""
                                  )
                                )
                              ])
                            ]
                          )
                        ])
                      : _vm._e(),
                    _vm._v(" "),
                    _c(
                      "div",
                      { staticClass: "order-options-radios" },
                      [
                        _vm._l(_vm.selectedStep.items, function(option, index) {
                          return _c(
                            "div",
                            {
                              key: "step" + option.id + "_" + index,
                              staticClass: "options-radio"
                            },
                            [
                              !_vm.selectedStep.is_checkbox
                                ? _c(
                                    "label",
                                    {
                                      staticClass: "radiobox-block",
                                      class: _vm.selectedSteps[
                                        option.step_name.id
                                      ]
                                        ? _vm.selectedSteps[option.step_name.id]
                                            .id === option.id
                                          ? "radio-bordered"
                                          : ""
                                        : ""
                                    },
                                    [
                                      _c("input", {
                                        directives: [
                                          {
                                            name: "model",
                                            rawName: "v-model",
                                            value:
                                              _vm.selectedSteps[
                                                option.step_name.id
                                              ],
                                            expression:
                                              "selectedSteps[option.step_name.id]"
                                          }
                                        ],
                                        attrs: {
                                          type: "radio",
                                          name:
                                            "step-" +
                                            _vm.selectedStep.id +
                                            "-radios"
                                        },
                                        domProps: {
                                          checked: !!_vm.selectedStep.items[
                                            index
                                          ].checked,
                                          value: option,
                                          checked: _vm._q(
                                            _vm.selectedSteps[
                                              option.step_name.id
                                            ],
                                            option
                                          )
                                        },
                                        on: {
                                          click: function($event) {
                                            return _vm.selectOption(
                                              option,
                                              index
                                            )
                                          },
                                          change: function($event) {
                                            return _vm.$set(
                                              _vm.selectedSteps,
                                              option.step_name.id,
                                              option
                                            )
                                          }
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("i"),
                                      _vm._v(" "),
                                      _c("span", [
                                        _c("strong", [
                                          _vm._v(_vm._s(option.value))
                                        ]),
                                        _vm._v(" "),
                                        _c("small", [
                                          _vm._v(_vm._s(option.decryption))
                                        ])
                                      ])
                                    ]
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.selectedStep.is_checkbox
                                ? _c(
                                    "label",
                                    { staticClass: "checkbox-block" },
                                    [
                                      _c("input", {
                                        directives: [
                                          {
                                            name: "model",
                                            rawName: "v-model",
                                            value: _vm.selectedAccesories,
                                            expression: "selectedAccesories"
                                          }
                                        ],
                                        attrs: {
                                          type: "checkbox",
                                          name:
                                            "step-" +
                                            _vm.selectedStep.id +
                                            "-checkbox"
                                        },
                                        domProps: {
                                          value: option,
                                          checked: Array.isArray(
                                            _vm.selectedAccesories
                                          )
                                            ? _vm._i(
                                                _vm.selectedAccesories,
                                                option
                                              ) > -1
                                            : _vm.selectedAccesories
                                        },
                                        on: {
                                          change: function($event) {
                                            var $$a = _vm.selectedAccesories,
                                              $$el = $event.target,
                                              $$c = $$el.checked ? true : false
                                            if (Array.isArray($$a)) {
                                              var $$v = option,
                                                $$i = _vm._i($$a, $$v)
                                              if ($$el.checked) {
                                                $$i < 0 &&
                                                  (_vm.selectedAccesories = $$a.concat(
                                                    [$$v]
                                                  ))
                                              } else {
                                                $$i > -1 &&
                                                  (_vm.selectedAccesories = $$a
                                                    .slice(0, $$i)
                                                    .concat($$a.slice($$i + 1)))
                                              }
                                            } else {
                                              _vm.selectedAccesories = $$c
                                            }
                                          }
                                        }
                                      }),
                                      _vm._v(" "),
                                      _c("i"),
                                      _vm._v(" "),
                                      _c("span", [
                                        _c("strong", [
                                          _vm._v(_vm._s(option.value))
                                        ]),
                                        _vm._v(" "),
                                        _c("small", [
                                          _vm._v(_vm._s(option.decryption))
                                        ])
                                      ])
                                    ]
                                  )
                                : _vm._e()
                            ]
                          )
                        }),
                        _vm._v(" "),
                        _vm.selectedStep.is_condition
                          ? _c("div", { staticClass: "options-radio-detail" }, [
                              _c("h5", [
                                _vm._v(
                                  "For a device to be in this condition. The following must also be true."
                                )
                              ]),
                              _vm._v(" "),
                              _vm._m(0),
                              _vm._v(" "),
                              _vm._m(1)
                            ])
                          : _vm._e()
                      ],
                      2
                    ),
                    _vm._v(" "),
                    _c("div", { staticClass: "order-options-links" }, [
                      _c("div", [
                        _c(
                          "button",
                          {
                            staticClass: "btn gray-btn step-button",
                            on: { click: _vm.backStep }
                          },
                          [_vm._v("Back")]
                        )
                      ]),
                      _vm._v(" "),
                      _c("div", [
                        _vm.stepSelected || _vm.selectedStep.is_checkbox
                          ? _c(
                              "button",
                              {
                                staticClass: "btn red-btn step-button",
                                on: { click: _vm.nextStep }
                              },
                              [_vm._v("Next step")]
                            )
                          : _vm._e()
                      ])
                    ]),
                    _vm._v(" "),
                    _vm.selectedStep.tip
                      ? _c(
                          "div",
                          {
                            staticClass: "popup-modal mfp-hide mfp-with-anim",
                            attrs: { id: "helping-popup" }
                          },
                          [
                            _c("div", { staticClass: "popup-content" }, [
                              _c(
                                "div",
                                { staticClass: "helping-popup-content" },
                                [
                                  _c("span", {
                                    domProps: {
                                      innerHTML: _vm._s(
                                        _vm.selectedStep.tip
                                          ? _vm.selectedStep.tip.text
                                          : ""
                                      )
                                    }
                                  })
                                ]
                              ),
                              _vm._v(" "),
                              _vm._m(2)
                            ])
                          ]
                        )
                      : _vm._e()
                  ]
                )
              : _vm._e(),
            _vm._v(" "),
            !_vm.selectedStep
              ? _c("div", { staticClass: "order-options-block" }, [
                  _c("h4", [_vm._v("Your device is valued at")]),
                  _vm._v(" "),
                  !_vm.priceLoaded
                    ? _c("img", {
                        staticStyle: {
                          display: "block",
                          "margin-left": "auto",
                          "margin-right": "auto",
                          width: "50%"
                        },
                        attrs: {
                          src: __webpack_require__(/*! ../../client/images/spinner.gif */ "./resources/client/images/spinner.gif")
                        }
                      })
                    : _vm._e(),
                  _vm._v(" "),
                  _vm.priceLoaded
                    ? _c("div", { staticClass: "order-total-block" }, [
                        _c("div", { staticClass: "price" }, [
                          _vm._v("$" + _vm._s(_vm.summ))
                        ]),
                        _vm._v(" "),
                        _c("div", { staticClass: "order-options-links" }, [
                          _c(
                            "button",
                            {
                              staticClass: "btn gray-btn",
                              on: { click: _vm.backStep }
                            },
                            [_vm._v("Back")]
                          ),
                          _vm._v(" "),
                          _c(
                            "button",
                            {
                              staticClass: "btn red-btn",
                              on: { click: _vm.addToBox }
                            },
                            [_vm._v("Add to box")]
                          )
                        ])
                      ])
                    : _vm._e(),
                  _vm._v(" "),
                  _vm._m(3)
                ])
              : _vm._e()
          ])
        ])
      : _vm._e(),
    _vm._v(" "),
    !_vm.steps.length
      ? _c("div", { staticClass: "order-search-outer" }, [
          _c("h5", [_vm._v("Search the device:")]),
          _vm._v(" "),
          _c("div", { staticClass: "order-search" }, [
            _c("div", { staticClass: "order-search-form" }, [
              _c("input", {
                directives: [
                  {
                    name: "model",
                    rawName: "v-model",
                    value: _vm.name,
                    expression: "name"
                  }
                ],
                attrs: { type: "text", placeholder: "Write text for search" },
                domProps: { value: _vm.name },
                on: {
                  keyup: _vm.searchDevice,
                  input: function($event) {
                    if ($event.target.composing) {
                      return
                    }
                    _vm.name = $event.target.value
                  }
                }
              }),
              _vm._v(" "),
              _c(
                "a",
                {
                  staticClass: "btn red-btn",
                  attrs: { href: "javascript:void(0)" },
                  on: { click: _vm.searchDevice }
                },
                [_vm._v("Search")]
              )
            ]),
            _vm._v(" "),
            _vm.searchDevices.length
              ? _c("div", { staticClass: "order-search-popup" }, [
                  _c(
                    "ul",
                    { staticClass: "order-search-popup-list" },
                    _vm._l(_vm.searchDevices, function(device, index) {
                      return _c("li", { key: "device_" + index }, [
                        _c(
                          "a",
                          {
                            staticClass: "link",
                            attrs: {
                              href: _vm.$r("get-category", {
                                slug: device.slug
                              })
                            }
                          },
                          [
                            _c("div", { staticClass: "image" }, [
                              _c("img", {
                                attrs: { src: device.image, alt: "" }
                              })
                            ]),
                            _vm._v(" "),
                            _c("span", { staticClass: "name" }, [
                              _vm._v(_vm._s(device.name))
                            ])
                          ]
                        ),
                        _vm._v(" "),
                        _c("div", { staticClass: "price" }, [
                          _vm._v("up to "),
                          _c("strong", [_vm._v(_vm._s(device.custom_text))])
                        ])
                      ])
                    }),
                    0
                  )
                ])
              : _vm._e()
          ])
        ])
      : _vm._e(),
    _vm._v(" "),
    !_vm.steps.length
      ? _c("div", { staticClass: "order-content" }, [
          _c("h4", [_vm._v("Or choose the device for sell:")]),
          _vm._v(" "),
          _c(
            "ul",
            { staticClass: "order-list" },
            _vm._l(_vm.categories, function(category, index) {
              return _c("li", { key: "device_" + index }, [
                _c(
                  "a",
                  {
                    attrs: {
                      href: _vm.$r("get-category", { slug: category.slug })
                    }
                  },
                  [
                    _c("div", { staticClass: "image" }, [
                      _c("img", { attrs: { src: category.image, alt: "" } })
                    ]),
                    _vm._v(" "),
                    _c("h5", [_vm._v(_vm._s(category.name))]),
                    _vm._v(" "),
                    category.custom_text
                      ? _c("div", { staticClass: "price" }, [
                          _vm._v(
                            "Cash in up to $" + _vm._s(category.custom_text)
                          )
                        ])
                      : _vm._e()
                  ]
                )
              ])
            }),
            0
          )
        ])
      : _vm._e(),
    _vm._v(" "),
    !_vm.faqsIsset
      ? _c(
          "div",
          { staticClass: "faqs-content" },
          [
            _c("h2", [_vm._v("FAQs")]),
            _vm._v(" "),
            _vm._l(_vm.faqs.data, function(faq, index) {
              return _c(
                "div",
                { key: "faq_" + index, staticClass: "faqs-block" },
                [
                  _c("div", { staticClass: "faqs-question" }, [
                    _vm._v(_vm._s(faq.title))
                  ]),
                  _vm._v(" "),
                  _c("div", {
                    staticClass: "faqs-answer",
                    domProps: { innerHTML: _vm._s(faq.text) }
                  })
                ]
              )
            })
          ],
          2
        )
      : _vm._e(),
    _vm._v(" "),
    _vm._m(4)
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("ol", [
      _c("li", [
        _vm._v("Zero scratches, scuffs or other marks. Looks like new.")
      ]),
      _vm._v(" "),
      _c("li", [
        _vm._v(
          "Display is free of defects such as dead pixels, white spots, or burn-in."
        )
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("div", { staticClass: "links" }, [
      _c(
        "a",
        {
          staticClass: "btn popup-open",
          attrs: { href: "#condition-popup", "data-effect": "mfp-zoom-in" }
        },
        [_vm._v("Condition Examples")]
      )
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "button",
      {
        staticClass: "mfp-close",
        attrs: { type: "button", title: "Close (Esc)" }
      },
      [
        _c("img", {
          attrs: { src: __webpack_require__(/*! ../../client/images/close.png */ "./resources/client/images/close.png"), alt: "" }
        }),
        _vm._v(" "),
        _c("img", {
          staticClass: "sm-only",
          attrs: {
            src: __webpack_require__(/*! ../../client/images/close_popup.png */ "./resources/client/images/close_popup.png"),
            alt: ""
          }
        })
      ]
    )
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("ul", { staticClass: "order-advantages-list" }, [
      _c("li", [
        _c("div", { staticClass: "pic" }, [
          _c("img", {
            attrs: {
              src: __webpack_require__(/*! ../../client/images/order_advantage_1.svg */ "./resources/client/images/order_advantage_1.svg"),
              alt: ""
            }
          })
        ]),
        _vm._v(" "),
        _c("div", [
          _c("p", [_vm._v("No selling fees")]),
          _vm._v(" "),
          _c("div", { staticClass: "desc" }, [
            _vm._v("Save up to 15% on marketplace selling fees.")
          ])
        ])
      ]),
      _vm._v(" "),
      _c("li", [
        _c("div", { staticClass: "pic" }, [
          _c("img", {
            attrs: {
              src: __webpack_require__(/*! ../../client/images/order_advantage_2.svg */ "./resources/client/images/order_advantage_2.svg"),
              alt: ""
            }
          })
        ]),
        _vm._v(" "),
        _c("div", [
          _c("p", [_vm._v("Zero fraud risk")]),
          _vm._v(" "),
          _c("div", { staticClass: "desc" }, [
            _vm._v("We handle the bad guys.")
          ])
        ])
      ]),
      _vm._v(" "),
      _c("li", [
        _c("div", { staticClass: "pic" }, [
          _c("img", {
            attrs: {
              src: __webpack_require__(/*! ../../client/images/order_advantage_3.svg */ "./resources/client/images/order_advantage_3.svg"),
              alt: ""
            }
          })
        ]),
        _vm._v(" "),
        _c("div", [
          _c("p", [_vm._v("Free and Convenient shipping via FedEx or UPS")]),
          _vm._v(" "),
          _c("div", { staticClass: "desc" }, [
            _vm._v("There's a drop-off around the corner.")
          ])
        ])
      ]),
      _vm._v(" "),
      _c("li", [
        _c("div", { staticClass: "pic" }, [
          _c("img", {
            attrs: {
              src: __webpack_require__(/*! ../../client/images/order_advantage_4.svg */ "./resources/client/images/order_advantage_4.svg"),
              alt: ""
            }
          })
        ]),
        _vm._v(" "),
        _c("div", [
          _c("p", [_vm._v("Optional 2-Day shipping and 24-Hour processing")]),
          _vm._v(" "),
          _c("div", { staticClass: "desc" }, [
            _vm._v("We get it. Sometimes you just can't wait!")
          ])
        ])
      ])
    ])
  },
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c(
      "div",
      {
        staticClass: "popup-modal mfp-hide mfp-with-anim",
        attrs: { id: "condition-popup" }
      },
      [
        _c("div", { staticClass: "popup-content" }, [
          _c("div", { staticClass: "condition-popup-content" }, [
            _c("h4", [_vm._v("Condition Examples")]),
            _vm._v(" "),
            _c("ul", { staticClass: "condition-popup-tabs" }, [
              _c("li", [
                _c(
                  "a",
                  {
                    staticClass: "active-tab",
                    attrs: { href: "#condition-1" }
                  },
                  [_vm._v("Flawless")]
                )
              ]),
              _vm._v(" "),
              _c("li", [
                _c("a", { attrs: { href: "#condition-2" } }, [_vm._v("Good")])
              ]),
              _vm._v(" "),
              _c("li", [
                _c("a", { attrs: { href: "#condition-3" } }, [_vm._v("Fair")])
              ]),
              _vm._v(" "),
              _c("li", [
                _c("a", { attrs: { href: "#condition-4" } }, [_vm._v("Broken")])
              ])
            ]),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass: "condition-tabs-content visible",
                attrs: { id: "condition-1" }
              },
              [
                _c("div", { staticClass: "photo-flex" }, [
                  _c("div", { staticClass: "image" }, [
                    _c("img", {
                      attrs: {
                        src: __webpack_require__(/*! ../../client/images/conditions/condition_1_1.jpg */ "./resources/client/images/conditions/condition_1_1.jpg"),
                        alt: ""
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "image" }, [
                    _c("img", {
                      attrs: {
                        src: __webpack_require__(/*! ../../client/images/conditions/condition_1_2.jpg */ "./resources/client/images/conditions/condition_1_2.jpg"),
                        alt: ""
                      }
                    })
                  ])
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass: "condition-tabs-content",
                attrs: { id: "condition-2" }
              },
              [
                _c("div", { staticClass: "photo-flex" }, [
                  _c("div", { staticClass: "image" }, [
                    _c("img", {
                      attrs: {
                        src: __webpack_require__(/*! ../../client/images/conditions/condition_2_1.jpg */ "./resources/client/images/conditions/condition_2_1.jpg"),
                        alt: ""
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "image" }, [
                    _c("img", {
                      attrs: {
                        src: __webpack_require__(/*! ../../client/images/conditions/condition_2_2.jpg */ "./resources/client/images/conditions/condition_2_2.jpg"),
                        alt: ""
                      }
                    })
                  ])
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass: "condition-tabs-content",
                attrs: { id: "condition-3" }
              },
              [
                _c("div", { staticClass: "photo-flex" }, [
                  _c("div", { staticClass: "image" }, [
                    _c("img", {
                      attrs: {
                        src: __webpack_require__(/*! ../../client/images/conditions/condition_3_1.jpg */ "./resources/client/images/conditions/condition_3_1.jpg"),
                        alt: ""
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "image" }, [
                    _c("img", {
                      attrs: {
                        src: __webpack_require__(/*! ../../client/images/conditions/condition_3_2.jpg */ "./resources/client/images/conditions/condition_3_2.jpg"),
                        alt: ""
                      }
                    })
                  ])
                ])
              ]
            ),
            _vm._v(" "),
            _c(
              "div",
              {
                staticClass: "condition-tabs-content",
                attrs: { id: "condition-4" }
              },
              [
                _c("div", { staticClass: "photo-flex" }, [
                  _c("div", { staticClass: "image" }, [
                    _c("img", {
                      attrs: {
                        src: __webpack_require__(/*! ../../client/images/conditions/condition_4_1.jpg */ "./resources/client/images/conditions/condition_4_1.jpg"),
                        alt: ""
                      }
                    })
                  ]),
                  _vm._v(" "),
                  _c("div", { staticClass: "image" }, [
                    _c("img", {
                      attrs: {
                        src: __webpack_require__(/*! ../../client/images/conditions/condition_4_2.jpg */ "./resources/client/images/conditions/condition_4_2.jpg"),
                        alt: ""
                      }
                    })
                  ])
                ])
              ]
            ),
            _vm._v(" "),
            _c("div", { staticClass: "note" }, [
              _vm._v("*Scratches may be enhanced to show detail")
            ])
          ]),
          _vm._v(" "),
          _c(
            "button",
            {
              staticClass: "mfp-close",
              attrs: { type: "button", title: "Close (Esc)" }
            },
            [
              _c("img", {
                attrs: {
                  src: __webpack_require__(/*! ../../client/images/close.png */ "./resources/client/images/close.png"),
                  alt: ""
                }
              }),
              _vm._v(" "),
              _c("img", {
                staticClass: "sm-only",
                attrs: {
                  src: __webpack_require__(/*! ../../client/images/close_popup.png */ "./resources/client/images/close_popup.png"),
                  alt: ""
                }
              })
            ]
          )
        ])
      ]
    )
  }
]
render._withStripped = true



/***/ }),

/***/ "./resources/client/images/close.png":
/*!*******************************************!*\
  !*** ./resources/client/images/close.png ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/close.png?3365377fd075715b06b6510224785880";

/***/ }),

/***/ "./resources/client/images/close_popup.png":
/*!*************************************************!*\
  !*** ./resources/client/images/close_popup.png ***!
  \*************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/close_popup.png?c75e43c6a14689556029439fe821d637";

/***/ }),

/***/ "./resources/client/images/conditions/condition_1_1.jpg":
/*!**************************************************************!*\
  !*** ./resources/client/images/conditions/condition_1_1.jpg ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/condition_1_1.jpg?0baa74b26cd962c6c53b911c1879726e";

/***/ }),

/***/ "./resources/client/images/conditions/condition_1_2.jpg":
/*!**************************************************************!*\
  !*** ./resources/client/images/conditions/condition_1_2.jpg ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/condition_1_2.jpg?8b29da92c64ef3dc16d4a27483ec87ea";

/***/ }),

/***/ "./resources/client/images/conditions/condition_2_1.jpg":
/*!**************************************************************!*\
  !*** ./resources/client/images/conditions/condition_2_1.jpg ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/condition_2_1.jpg?9f252bdccec09c322a1ce1c01cf4739b";

/***/ }),

/***/ "./resources/client/images/conditions/condition_2_2.jpg":
/*!**************************************************************!*\
  !*** ./resources/client/images/conditions/condition_2_2.jpg ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/condition_2_2.jpg?0549824381bc29cfa7be646b4f730a65";

/***/ }),

/***/ "./resources/client/images/conditions/condition_3_1.jpg":
/*!**************************************************************!*\
  !*** ./resources/client/images/conditions/condition_3_1.jpg ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/condition_3_1.jpg?ba25f104310446b819392ccd8875492f";

/***/ }),

/***/ "./resources/client/images/conditions/condition_3_2.jpg":
/*!**************************************************************!*\
  !*** ./resources/client/images/conditions/condition_3_2.jpg ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/condition_3_2.jpg?6b4937333032e660d6227d71690127de";

/***/ }),

/***/ "./resources/client/images/conditions/condition_4_1.jpg":
/*!**************************************************************!*\
  !*** ./resources/client/images/conditions/condition_4_1.jpg ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/condition_4_1.jpg?d3e9ff468e118e15e687725a91a9a6a6";

/***/ }),

/***/ "./resources/client/images/conditions/condition_4_2.jpg":
/*!**************************************************************!*\
  !*** ./resources/client/images/conditions/condition_4_2.jpg ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/condition_4_2.jpg?52ddd9e70c94e8d34ef3b9b66aef9779";

/***/ }),

/***/ "./resources/client/images/icon_help.svg":
/*!***********************************************!*\
  !*** ./resources/client/images/icon_help.svg ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/icon_help.svg?5d7116130c04db6bf7e693fa8e2535d6";

/***/ }),

/***/ "./resources/client/images/order_advantage_1.svg":
/*!*******************************************************!*\
  !*** ./resources/client/images/order_advantage_1.svg ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/order_advantage_1.svg?c8dc52aef20ec24eee931d38f04653a4";

/***/ }),

/***/ "./resources/client/images/order_advantage_2.svg":
/*!*******************************************************!*\
  !*** ./resources/client/images/order_advantage_2.svg ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/order_advantage_2.svg?3bfa288e782fea12f58f02d6b07949ce";

/***/ }),

/***/ "./resources/client/images/order_advantage_3.svg":
/*!*******************************************************!*\
  !*** ./resources/client/images/order_advantage_3.svg ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/order_advantage_3.svg?e9948fef1392e02525b6c9c8f5b81d23";

/***/ }),

/***/ "./resources/client/images/order_advantage_4.svg":
/*!*******************************************************!*\
  !*** ./resources/client/images/order_advantage_4.svg ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/order_advantage_4.svg?d79e33b5a602827430218f18e89c54ab";

/***/ }),

/***/ "./resources/client/images/spinner.gif":
/*!*********************************************!*\
  !*** ./resources/client/images/spinner.gif ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = "/images/spinner.gif?26ef8bb418031b9bb4f44e1aeea71186";

/***/ }),

/***/ "./resources/js/components/SellDevice.vue":
/*!************************************************!*\
  !*** ./resources/js/components/SellDevice.vue ***!
  \************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _SellDevice_vue_vue_type_template_id_5e78461a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./SellDevice.vue?vue&type=template&id=5e78461a& */ "./resources/js/components/SellDevice.vue?vue&type=template&id=5e78461a&");
/* harmony import */ var _SellDevice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./SellDevice.vue?vue&type=script&lang=js& */ "./resources/js/components/SellDevice.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _SellDevice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _SellDevice_vue_vue_type_template_id_5e78461a___WEBPACK_IMPORTED_MODULE_0__["render"],
  _SellDevice_vue_vue_type_template_id_5e78461a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/SellDevice.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/SellDevice.vue?vue&type=script&lang=js&":
/*!*************************************************************************!*\
  !*** ./resources/js/components/SellDevice.vue?vue&type=script&lang=js& ***!
  \*************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SellDevice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/babel-loader/lib??ref--4-0!../../../node_modules/vue-loader/lib??vue-loader-options!./SellDevice.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/SellDevice.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_SellDevice_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/SellDevice.vue?vue&type=template&id=5e78461a&":
/*!*******************************************************************************!*\
  !*** ./resources/js/components/SellDevice.vue?vue&type=template&id=5e78461a& ***!
  \*******************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SellDevice_vue_vue_type_template_id_5e78461a___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../node_modules/vue-loader/lib??vue-loader-options!./SellDevice.vue?vue&type=template&id=5e78461a& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/SellDevice.vue?vue&type=template&id=5e78461a&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SellDevice_vue_vue_type_template_id_5e78461a___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_SellDevice_vue_vue_type_template_id_5e78461a___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);