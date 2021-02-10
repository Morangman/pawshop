<template>
    <div>
        <div v-for="(product, category) in products" :key="`category_${category}`">
            <h1 class="text-center" :class="category.replace(/\s+/g, '_')">{{ category }}</h1>
            <section v-for="(product, i) in product" :key="`product_${i}`" class="phone-container container-fliud" :id="category.replace(/\s+/g, '_')">
                <div class="mti_template">
                    <p>{{ product.general_info }}</p>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="col-md-5 hidden-opacity"></div>
                        <div class="col-md-7 hidden-opacity">
                            <div class="phone-container-title hidden-opacity" :id="category.replace(/\s+/g, '-')">
                                <span>
                                  <a :href="$r('product', { product: product.id })">{{ product.title }} {{ selectedColors[product.id] ? selectedColors[product.id].color_name : '' }}</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="carousel slide" data-interval="false" data-ride="carousel" id="">
                    <div class="carousel-inner" role="listbox">
                        <div class="item active">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-5 phone-preview">
                                        <img class="hidden-opacity" :src="selectedColors[product.id] ? selectedColors[product.id].image : product.image">
                                        <div class="clear"></div>
                                        <div class="clear"></div>
                                        <div class="preview-buttons">
                                            <button v-if="!accessories" class="delivery-kit fancybox-2 link_modal" href="#package-popup">Комплект поставки</button>
                                            <button class="characteristics fancybox-2 hidden-xs" href="#characteristics-popup">Характеристики</button>
                                            <div style="display: none">
                                                <p class="text-center">
                                                    {{ product.general_info }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-7 hidden-opacity">
                                        <form class="variations_form cart" enctype="multipart/form-data" method="post">
                                            <div class="check-color">Выберите цвет:</div>
                                            <div class="row col colorflex">
                                                <div class="col-xs-2 col-md-2 col col-md-2 col-md-offset-0 col" v-for="(color, i) in product.variations" :key="`variation_${i}`">
                                                    <div class="color-div" :id="color.color_name + i" v-on:click="selectColor(product, color)">
                                                        <div class="color-img color-img-white" :style="'background: ' + color.color"></div>
                                                        <p>{{ color.color_name }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <table class="variations">
                                                <tbody>
                                                <tr style="display: none">
                                                    <td class="label">
                                                        <label for="pa_color">цвет</label>
                                                    </td>
                                                    <td class="value">
                                                        <select class="" data-attribute_name="attribute_pa_color" id="pa_color" name="attribute_pa_color">
                                                            <option value="">Выбрать опцию</option>
                                                            <option selected="selected" value="black">black</option>
                                                            <option value="white">white</option>
                                                        </select>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <div class="row price price_30">
                                                <span>Стоимость со скидкой:</span>
                                                <span class="price-phone">
                                      <div class="woocommerce-variation single_variation" style="">
                                        <div class="woocommerce-variation-description"></div>
                                        <div class="woocommerce-variation-price">
                                          <span class="price">
                                            <del>
                                              <!-- цена x -->
                                              <span class="amount main_p">
                                                {{ selectedColors[product.id] ? selectedColors[product.id].old_price : product.old_price }} грн.</span>
                                            </del>
                                            <ins>
                                              <span class="amount sale_p">{{ selectedColors[product.id] ? selectedColors[product.id].price : product.price }} грн.</span>
                                            </ins>
                                          </span>
                                        </div>
                                        <div class="woocommerce-variation-availability"></div>
                                      </div>
                                    </span>
                                            </div>
                                            <div class="row hr">
                                                <p><img class="hr" src="client/uploads/punkt.png">Магазин</p>
                                                <p><img class="hr" src="client/uploads/dost.png">Отправка в день заказа</p>
                                                <p><img class="hr" src="client/uploads/orig.png">Оригинальная техника</p>
                                                <p><img class="hr" src="client/uploads/gar.png">Гарантия 1 год</p>
                                                <p><img class="hr" src="client/uploads/test.png">Тест драйв 30 дней</p>
                                            </div>
                                            <div class="single_variation_wrap">
                                                <div class="woocommerce-variation-add-to-cart variations_button buy-buttons hidden-opacity">
                                                    <button class="buy-in-click buy-in-click_act fancybox" href="#buy-in-click-popup">Заказать сейчас</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <div class="popup container" id="buy-in-click-popup">
            <div class="buy-in-click-popup-content">
                <div class="modal_top_wp clearfix">
                    <div class="mt_img">
                        <img alt="" id="ip_img" src="client/5s.png">
                    </div>
                    <div class="mt_info">
                        <p class="mti_title">
                            <span id="ip_model">iPhone SE</span>
                            <span id="ip_color"></span>
                            <span id="ip_memory">32Gb</span>
                        </p>
                        <p class="mti_price">
                            <span id="ip_oldprice">17 000 грн.</span>
                            <span id="ip_newprice">13 990 грн.</span>
                        </p>
                        <div class="modal_forma">
                            <form id="form1" v-if="!orderSuccess">
                                <input name="name" placeholder="Имя" type="text" v-model="name" required>
                                <input-mask v-model="phone" :class="{ 'danger-input': isValidPhoneText }" v-on:input="validatePhone" mask="+38\ 999 999 99 99" placeholder="+38 999 999 99 99" maskChar="" required></input-mask>
                                <p class="text-danger" v-if="isValidPhoneText">Введите корректный номер</p>
                                <input type="text" name="address" v-model="email" placeholder="Почта" required>
                                <span style="font-size: 0.8em;" class="oft"><input class="oftt2" checked="" type="checkbox"> Я согласен с <a target="_blank" :href="$r('guarantee')">публичной офертой</a></span>
                                <button v-if="!validateButton" class="b_o_c modal_btn" type="submit" v-on:click="makeOrder">Купить</button><br><br>
                            </form>
                            <div class="isa_warning" v-if="colorError">
                                <i class="icon-notification2"></i>
                                Сначала выберите цвет
                            </div>
                            <div class="isa_success" v-if="orderSuccess">
                                <i class="icon-checkmark-circle"></i>
                                Спасибо за Ваш заказ!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import InputMask from 'vue-input-mask';
    import FormHelper from "../../admin/js/mixins/form_helper";

    export default {
        mixins: [FormHelper],

        components: {
            InputMask: InputMask,
        },

        props: {
            products: {
                type: Object,
                required: true,
            },
            accessories: {
                type: Boolean,
                required: false,
            },
        },

        data() {
            return {
                selectedColors: [],
                orderData: null,
                email: '',
                name: '',
                phone: '',
                errors: {},
                colorError: false,
                orderSuccess: false,
                ordered_product: [],
                isValidPhone: false,
                isValidPhoneText: false,
                validateButton: false,
            };
        },

        methods: {
            selectColor(product, color) {
                this.colorError = false;

                this.selectedColors[product.id] = color;

                this.ordered_product = [{
                    price: color.price,
                    product: product,
                    product_color: color.color_name,
                    product_title: product.title,
                    quantity: 1,
                    variation: color,
                }];

                this.$forceUpdate();
            },

            validatePhone() {
                if (this.phone.length < 17) {
                    this.isValidPhone = false;
                } else {
                    this.isValidPhone = true;
                }
            },

            makeOrder() {
                this.colorError = false;
                this.isValidPhoneText = false;
                this.validateButton = false;

                if (this.isValidPhone) {
                    this.validateButton = true;

                    this.orderData = {
                        name: this.name,
                        email: this.email,
                        phone: this.phone,
                        ordered_product: this.ordered_product,
                    };

                    this.errors = {};
                    this.formData = new FormData();
                    this.collectFormData(this.orderData);

                    axios.post(
                        Router.route('order'),
                        this.orderData,
                    ).then(() => {
                        this.orderSuccess = true;
                        this.validateButton = false;

                        setTimeout(() => location.href = Router.route('home'), 2000);
                    }).catch(({ response: { data: { errors } } }) => {
                        this.errors = errors;

                        this.validateButton = false;

                        if (this.errors.ordered_product) {
                            this.colorError = true;
                        } else {
                            this.colorError = false;
                        }
                    });
                } else {
                    this.isValidPhoneText = true;
                }
            },
        },
    }
</script>
