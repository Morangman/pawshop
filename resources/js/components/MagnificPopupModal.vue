<template>

  <div class="Modal mfp-hide" ref="modal">
    <div class="Modal__inner">
      <slot></slot>
    </div>
  </div>

</template>


<script>
  import _ from 'underscore'
  import $ from 'jquery'
  import 'magnific-popup'

  export default {
    name: 'magnific-popup-modal',

    props: {
      show: {
        type: Boolean,
        default: false
      },
      config: {
        type: Object,
        default: function () {
          return {
            // magnific defaults
            disableOn: null,
            mainClass: '',
            preloader: true,
            focus: '',
            closeOnContentClick: false,
            closeOnBgClick: false,
            closeBtnInside: true,
            showCloseBtn: false,
            enableEscapeKey: true,
            modal: false,
            alignTop: false,
            index: null,
            fixedContentPos: 'auto',
            fixedBgPos: 'auto',
            overflowY: 'auto',
            removalDelay: 0,
            // closeMarkup: '',
            // prependTo: document.body,
            autoFocusLast: true
          }
        }
      }
    },

    data () {
      return {
        visible: this.show
      }
    },

    mounted () {
      this[this.visible ? 'open' : 'close']()
    },

    methods: {

      /**
       * Opens the modal
       * @param {object} options Last chance to define options on this call to Magnific Popup's open() method
       */
      open: function (options) {
        if (!!$.magnificPopup.instance.isOpen && this.visible) {
          return
        }

        let root = this

        let config = _.extend(
          this.config,
          {
            items: {
              src: $(this.$refs.modal),
              type: 'inline'
            },
            callbacks: {
              open: function () {
                root.visible = true
                root.$emit('open')
              },
              close: root.close
            }
          },
          options || {})

        $.magnificPopup.open(config)
      },

      /**
       * Closes the modal
       */
      close: function () {
        if (!$.magnificPopup.instance.isOpen && !this.visible) {
          return
        }

        this.visible = false
        $.magnificPopup.close()
        this.$emit('close')
      },

      /**
       * Toggles modal open/closed
       */
      toggle: function () {
        this[this.visible ? 'close' : 'open']()
      }
    }
  }

</script>


<style lang="scss">

  @import '../../../node_modules/magnific-popup/src/css/main.scss';

  .mfp-content {
    text-align: center;
  }

  .mfp-content {
    text-align: center;
  }

  .modal-cmp-content-wrapper {
    display: inline-block;
    position: relative;
  }
  .modal-cmp-content {
    display: inline-block;
    text-align: left;
  }
  .modal-cmp-content-wrapper .mfp-close {
    position: absolute;
    top: auto;
    bottom: 100%;
    color: #fff;
  }

  .Modal {
    margin: 40px auto;
  }

  .Modal__inner {
    width: 740px;
  }

  $module: '.Modal';
  #{$module} {
    display: inline-block;
    position: relative;

    &__inner {
      display: inline-block;
      text-align: left;
    }

    .mfp-close {
        right: -28px;
        height: auto;
        width: auto;
        line-height: 1;
        font-size: 1px;
        top: -28px;
        transition: all .3s ease-out;
        opacity: 1;
    }
  }

    @media (max-width: 740px) {
        .Modal__inner {
            width: auto;
        }
    }

    @media (max-width: 767px) {
        .Modal .mfp-close {
            top: 10px;
            right: 10px;
        }
        .Modal .sm-only {
            display: block!important;
        }
        .Modal .mfp-close .big-only {
            display: none!important;
        }
    }

    .Modal .mfp-close .sm-only {
        display: none;
    }
    .Modal .mfp-close .big-only {
        display: block;
    }

</style>