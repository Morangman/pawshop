<template>
    <section class="checkout-section">
        <div class="container">
            <h1>Checkout</h1>
            <div id="to-top" class="checkout-flex">
                <div class="checkout-content">
                    <ul class="order-steps-list">
                        <li :class="stepIndex === 1 ? 'active-step' : ''">
                            <a href="javascript:0"><i>1.</i> <span>Account</span></a>
                        </li>
                        <li :class="stepIndex === 2 ? 'active-step' : ''">
                            <a href="javascript:0"><i>2.</i> <span>Payment</span></a>
                        </li>
                        <li :class="stepIndex === 3 ? 'active-step' : ''">
                            <a href="javascript:0"><i>3.</i> <span>Shipping</span></a>
                        </li>
                        <li :class="stepIndex === 4 ? 'active-step' : ''">
                            <a href="javascript:0"><i>4.</i> <span>Options & Terms</span></a>
                        </li>
                    </ul>

                    <!-- Step 1 -->
                    <div class="checkout-content-step" v-if="stepIndex === 1 && !this.user.id">
                        <h2>Account</h2>
                        <div class="checkout-account">
                            <a :href="$r('web.register')">Create an Account</a>
                        </div>
                        <div class="checkout-login">
                            <div class="inner-block">
                                <div class="checkout-customer">
                                    <h4>Customer login</h4>
                                    <div class="input-block">
                                        <input v-model="email" :class="loginErrors.email ? 'input-error' : ''" type="email" placeholder="E-mail">
                                        <div v-if="loginErrors.email" v-for="error in loginErrors.email" class="invalid-feedback">
                                            <strong>{{ error }}</strong>
                                        </div>
                                    </div>
                                    <div class="input-block">
                                        <input v-model="password" :class="loginErrors.password ? 'input-error' : ''" type="password" placeholder="Password">
                                        <div v-if="loginErrors.password" v-for="error in loginErrors.password" class="invalid-feedback">
                                            <strong>{{ error }}</strong>
                                        </div>
                                    </div>
                                    <div class="password-forget">
                                        <a :href="$r('web.password.request')">Forgot your password?</a>
                                    </div>
                                    <div class="bottom-links">
                                        <button v-on:click="login" class="btn red-btn">Login</button>
                                        <div class="divider">or</div>
                                        <ul class="socials">
                                            <li>
                                                <a :href="$r('redirect-google')"><img src="../../client/images/google.svg" alt="google-svg"></a>
                                            </li>
                                            <li>
                                                <a :href="$r('redirect-facebook')"><img src="../../client/images/facebook.svg" alt="facebook-svg"></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="inner-block">
                                <div class="checkout-guest">
                                    <h4>Guest Checkout</h4>
                                    <div class="input-block">
                                        <input v-model="orderData.user_email" :class="registerErrors.email || emailError ? 'input-error' : ''" type="email" placeholder="E-mail">
                                        <div v-if="registerErrors.email" v-for="error in registerErrors.email" class="invalid-feedback">
                                            <strong>{{ error }}</strong>
                                        </div>
                                        <div v-if="emailError" class="invalid-feedback">
                                            <strong>{{ emailError }}</strong>
                                        </div>
                                    </div>
                                    <a href="javascript:0" v-if="orderData.user_email" v-on:click="validateEmail" class="btn red-btn">Continue as Guest</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="checkout-content-step" v-if="stepIndex === 2">
                        <h2>Payment</h2>
                        <div class="checkout-payment">
                            <h4>How would you like to be paid?</h4>
                            <div class="checkout-payment-list">
                                <label v-on:click="selectPaymentCheck()" class="payment-radiobox">
                                    <input type="radio" name="payment-radios">
                                    <span>
                                        <img src="../../client/images/pay_check.svg" alt="pay_check">
                                        <small>Check</small>
                                    </span>
                                </label>
                                <!-- <label v-on:click="selectPaymentPayPal()" class="payment-radiobox">
                                    <input type="radio" name="payment-radios">
                                    <span>
                                        <img src="../../client/images/pay_paypal.svg" alt="pay_paypal">
                                        <small>PayPal</small>
                                    </span>
                                </label> -->
                                <label v-on:click="selectPaymentZelle()" class="payment-radiobox">
                                    <input type="radio" name="payment-radios">
                                    <span>
                                        <img src="../../client/images/pay_zelle.png" alt="pay_zelle">
                                        <small>Zelle</small>
                                    </span>
                                </label>
                                <label v-on:click="selectPaymentVenmo()" class="payment-radiobox">
                                    <input type="radio" name="payment-radios">
                                    <span>
                                        <img src="../../client/images/pay_venmo.png" alt="pay_venmo">
                                        <small>Venmo</small>
                                    </span>
                                </label>
                                <div v-if="paymentError" class="invalid-feedback">
                                    <strong>You must select the type of payment and confirm the mail</strong>
                                </div>
                                <div v-if="paymentZelleError" class="invalid-feedback">
                                    <strong>Minimum payout amount from $500 for Zelle</strong>
                                </div>
                            </div>
                        </div>
                        <div class="checkout-payment-content" v-if="orderData.payment.type === 1 && !paymentZelleError">
                            <p>We'll mail you a check once your item(s) have been received and verified by our team.</p>
                        </div>
                        <div class="checkout-payment-content" v-if="orderData.payment.type === 2 && !paymentZelleError">
                            <p>We'll credit your PayPal Email Address once your item(s) have been verified by our staff. Please note that PayPal charges a fee ($0.30 + 2.9%) to receive funds using their service.</p>
                            <div class="inputs-flex">
                                <div class="input-block width-50">
                                    <input v-model="orderData.payment.email" :class="paymentError ? 'input-error' : ''" type="email" placeholder="PayPal E-mail Adress">
                                </div>
                                <div class="input-block width-50">
                                    <input v-model="checkEmail" :class="paymentError ? 'input-error' : ''" type="email" placeholder="Confirm PayPal E-mail Adress">
                                </div>
                            </div>
                        </div>
                        <div class="checkout-payment-content" v-if="orderData.payment.type === 3 && !paymentZelleError">
                            <p>We'll credit your Zelle® account once your item(s) have been verified by our staff. Email address provided must be associated and linked with your Zelle® account to avoid any delays.</p>
                            <div class="inputs-flex">
                                <div class="input-block width-50">
                                    <input v-model="orderData.payment.email" :class="paymentError ? 'input-error' : ''" type="email" placeholder="Zelle E-mail Adress">
                                </div>
                                <div class="input-block width-50">
                                    <input v-model="checkEmail" :class="paymentError ? 'input-error' : ''" type="email" placeholder="Confirm Zelle E-mail Adress">
                                </div>
                            </div>
                        </div>
                        <div class="checkout-payment-content" v-if="orderData.payment.type === 4 && !paymentZelleError">
                            <p>We'll credit your Venmo® account once your item(s) have been verified by our staff. Email address provided must be associated and linked with your Venmo® account to avoid any delays.</p>
                            <div class="inputs-flex">
                                <div class="input-block width-50">
                                    <input v-model="orderData.payment.email" :class="paymentError ? 'input-error' : ''" type="email" required placeholder="Venmo E-mail Adress">
                                </div>
                                <div class="input-block width-50">
                                    <input v-model="checkEmail" :class="paymentError ? 'input-error' : ''" type="email" required placeholder="Confirm Venmo E-mail Adress">
                                </div>
                            </div>
                        </div>
                        <div class="order-options-links">
                            <a href="javascript:0" v-if="!this.user.id" v-on:click="backStep" class="btn gray-btn">Back</a>
                            <a href="javascript:0" v-on:click="validatePayment" class="btn red-btn">Next step</a>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="checkout-content-step" v-if="stepIndex === 3">
                        <h2>Shipping</h2>
                        <div class="checkout-simple-block">
                            <h4>Your adress</h4>
                            <select
                                id="addresses"
                                class="form-control form-control-sm d-inline-block"
                                style="width: auto;"
                                v-model="orderData.address"
                                v-if="user.id && user.addresses && user.addresses.length"
                            >
                                <option :value="orderData.address">Select address</option>
                                <option v-for="(address, key) in user.addresses" :value="address">{{ address.name + ' ' + address.phone + ' ' + address.address1 }}</option>
                            </select>
                            <br>
                            <br>
                            <form autocomplete="on" class="inputs-flex">
                                <div class="input-block width-50">
                                    <input v-model="orderData.address.name" :class="addressError.name ? 'input-error' : ''" name="name" autocomplete="on" type="text" placeholder="First and Last Name*">
                                    <div v-if="addressError.name" class="invalid-feedback">
                                        <strong>Please provide a first and last name.</strong>
                                    </div>
                                </div>
                                <div class="input-block width-50">
                                    <input v-model="orderData.address.phone" @input="acceptNumber" :class="addressError.phone ? 'input-error' : ''" name="tel" autocomplete="on" type="tel" placeholder="Phone*">
                                    <div v-if="addressError.phone" class="invalid-feedback">
                                        <strong>Please provide a valid phone number.</strong>
                                    </div>
                                </div>
                                <div class="input-block width-50">
                                    <input v-model="orderData.address.address1" :class="addressError.address1 ? 'input-error' : ''" name="address-line1" autocomplete="on" type="text" placeholder="Adress 1*">
                                    <div v-if="addressError.address1" class="invalid-feedback">
                                        <strong>Please provide a street address.</strong>
                                    </div>
                                </div>
                                <div class="input-block width-50">
                                    <input v-model="orderData.address.address2" name="address-line2" autocomplete="on" type="text" placeholder="Adress 2">
                                </div>
                                <div class="input-block width-50">
                                    <input v-model="orderData.address.city" :class="addressError.city ? 'input-error' : ''" name="city" autocomplete="on" type="text" placeholder="City*">
                                    <div v-if="addressError.city" class="invalid-feedback">
                                        <strong>Please provide a city.</strong>
                                    </div>
                                </div>
                                <div class="input-block width-25">
                                    <input name="state" v-model="orderData.address.state" autocomplete="on" style="width: 0; height: 0; border: 0;">
                                    <CustomSelect
                                        :options="states"
                                        :default="'States*'"
                                        :error="addressError.state"
                                        :value="orderData.address.state"
                                        class="select"
                                        v-model="orderData.address.state"
                                    />
                                    <div v-if="addressError.state" class="invalid-feedback">
                                        <strong>Please select a state.</strong>
                                    </div>
                                </div>
                                <div class="input-block width-25">
                                    <input v-model="orderData.address.postal_code"  @input="acceptZip" :class="addressError.postal_code ? 'input-error' : ''" name="postal-code" autocomplete="on" type="text" placeholder="Postal Code*">
                                    <div v-if="addressError.postal_code" class="invalid-feedback">
                                        <strong>Please provide a valid 5 digit zip code.</strong>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="checkout-simple-block" v-if="totalSumm > 100">
                            <h4>Get paid faster <small>(optional)</small></h4>
                            <ul class="checkbox-list">
                                <li>
                                    <label class="checkbox-block">
                                        <input v-model="orderData.exp_service" v-on:change="valuate" type="checkbox" name="paid-checkbox">
                                        <i></i>
                                        <span>Expedited Service <strong>($20.00)</strong></span>
                                    </label>
                                </li>
                            </ul>
                            <div class="timing-note">
                                <h5>2-Day Shipping and 24 Hour Processing Time</h5>
                                <p>(This amount will be deducted from the final offer amount)</p>
                            </div>
                        </div>
                        <div class="order-options-links">
                            <a href="javascript:0" v-on:click="backStep" class="btn gray-btn">Back</a>
                            <a href="javascript:0" v-on:click="validateAddress" class="btn red-btn">Next step</a>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="checkout-content-step" v-if="stepIndex === 4">
                        <h2>Options & Terms</h2>
                        <div class="checkout-simple-block">
                            <h4>Shipping insurance</h4>
                            <div class="desc">
                                <p>All packages are insured up to $100 for free. If you'd like, you can purchase additional shipping insurance to cover the full value of your offer for just 1% of the cost.</p>
                            </div>
                            <div class="radio-list">
                                <div class="options-radio">
                                    <label class="radiobox-block">
                                        <input v-on:click="addInsurance" type="radio" name="step-4-radios" :checked="orderData.insurance === true">
                                        <i></i>
                                        <span>
                                            <strong>Yes, Add Insurance</strong>
                                        </span>
                                    </label>
                                </div>
                                <div class="options-radio">
                                    <label class="radiobox-block">
                                        <input v-on:click="deleteInsurance" type="radio" name="step-4-radios" :checked="orderData.insurance === false">
                                        <i></i>
                                        <span>
                                            <strong>No Thanks</strong>
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <div class="desc red-note">
                                <p>* Coverage only applies to damages incurred during transit or lost packages. It does NOT cover packages received with missing or incorrect items.</p>
                            </div>
                        </div>
                        <div class="checkout-simple-block">
                            <h4>Order comment</h4>
                            <textarea style="border-radius: 9px; margin-bottom: 20px;" v-model="orderData.comment"></textarea>
                        </div>
                        <div class="checkout-simple-block" style="display: none;">
                            <h4>Terms & Conditions</h4>
                            <div class="desc">
                                <p>Please carefully read our terms and conditions before placing your offer.</p>
                            </div>
                            <ul class="checkbox-list">
                                <li>
                                    <label class="checkbox-block">
                                        <input v-model="accept_terms" :checked="accept_terms === true" type="checkbox" name="Options-checkbox">
                                        <i></i>
                                        <span>I accept the <a :href="$r('terms')" target="_blank" style="text-decoration: revert; color: #0000FF;">terms and conditions</a></span>
                                    </label>
                                </li>
                                <div v-if="termsError" class="invalid-feedback">
                                    <strong>You must be accept the terms and conditions.</strong>
                                </div>
                            </ul>
                        </div>
                        <div class="order-options-links">
                            <a href="javascript:0" v-on:click="backStep" class="btn gray-btn">Back</a>
                            <a href="javascript:0" v-if="showCheckout" v-on:click="validateTerms" class="btn red-btn">Checkout</a>
                        </div>
                    </div>

                </div>
                <div class="cart-total checkout-summary">
                    <h5>Trade-in summary</h5>
                    <div class="cart-total-product" v-for="(order, index) in orders['order']" :key="`device_${index}`">
                        <a href="javascript:0" class="image"><img :src="order.device.image" :alt="order.device.name"></a>
                        <div class="inner">
                            <div class="name"><a :href="$r('get-category', { slug:  order.device.slug })">{{ order.device.name }}</a></div>
                            <div class="price">${{ order.total }}</div>
                            <br>
                            <span>×{{ order.ctn }}</span>
                        </div>
                    </div>
                    <ul class="cart-total-list">
                        <li>
                            <span>Total Payout</span>
                            <div class="price">${{ number_format(totalSumm, 2) }}</div>
                        </li>
                        <li v-if="orderData.exp_service">
                            <span>Expedited Service</span>
                            <div class="price free">-$20</div>
                        </li>
                        <li v-if="orderData.insurance">
                            <span>Shipping insurance</span>
                            <div class="price free">-${{ insurancePrice }}</div>
                        </li>
                        <li>
                            <span>Delivery</span>
                            <div class="price free">Free</div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import FormHelper from "../../admin/js/mixins/form_helper";
    import CustomSelect from "./CustomSelect.vue";

    export default {
        mixins: [FormHelper],

        components: {
            CustomSelect,
        },

        props: {
            user: {
                type: Object,
                required: false,
            },
            states: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
                orders: JSON.parse(localStorage.getItem("orders")),
                totalSumm: 0,
                stepIndex: 1,
                email: null,
                checkEmail: null,
                password: null,
                emailError: null,
                paymentError: false,
                paymentZelleError: false,
                showCheckout: true,
                addressError: {
                    name: false,
                    phone: false,
                    address1: false,
                    address2: false,
                    city: false,
                    state: false,
                    postal_code: false,
                },
                termsError: false,
                orderData: {
                    orders: [],
                    user_id: null,
                    comment: null,
                    total_summ: 0,
                    user_email: null,
                    payment: {
                        name: null,
                        type: null,
                        email: null,
                    },
                    address: {
                        name: null,
                        phone: null,
                        address1: null,
                        address2: null,
                        city: null,
                        state: null,
                        postal_code: null,
                    },
                    exp_service: false,
                    insurance: false,
                },
                insurancePrice: 0,
                registerErrors: [],
                loginErrors: [],
                accept_terms: true,
            };
        },

        methods: {
            valuate(){
                this.totalSumm = 0;

                if (this.orders && this.orders['order'].length) {
                    _.each(this.orders['order'], (key, value) => {
                        if(key) {
                            this.totalSumm += parseFloat(key.total);
                        }
                    });
                }

                if (this.orderData.exp_service) {
                    this.totalSumm -= 20;
                }

                if (this.orderData.insurance) {
                    let insuranceValue = (this.totalSumm * 1)/100;

                    this.insurancePrice = this.number_format(insuranceValue, 2);

                    this.totalSumm = this.totalSumm - insuranceValue;
                } else {
                    this.insurancePrice = 0;
                }
            },

            backStep(){
                if (this.stepIndex !== 1) {
                    this.stepIndex--;
                }
            },

            nextStep() {
                this.stepIndex++;

                let offset = 160; // sticky nav height
                let el = document.querySelector('#to-top');
                window.scroll({ top: (el.offsetTop - offset), left: 0, behavior: 'smooth' });
            },

            login(){
                this.loginErrors = [];

                axios.post(
                    Router.route('web.login.post', { email: this.email, password: this.password }),
                ).then((data) => {
                    location.reload();
                }).catch(({ response: { data: { errors } } }) => {
                    this.loginErrors = errors;
                });
            },

            selectPaymentCheck() {
                this.paymentError = false;
                this.paymentZelleError = false;

                this.orderData.payment.email = this.user.id ? this.user.email : this.orderData.user_email;
                this.checkEmail = this.user.id ? this.user.email : this.orderData.user_email;
                this.orderData.payment.name = 'Check';
                this.orderData.payment.type = 1;
            },

            selectPaymentPayPal() {
                this.paymentError = false;
                this.paymentZelleError = false;

                this.orderData.payment.email = '';
                this.checkEmail = '';
                this.orderData.payment.name = 'PayPal';
                this.orderData.payment.type = 2;
            },

            selectPaymentZelle() {
                this.paymentError = false;
                this.paymentZelleError = false;

                if (this.totalSumm < 500) {
                    this.paymentZelleError = true;
                } else {
                    this.orderData.payment.email = '';
                    this.checkEmail = '';
                    this.orderData.payment.name = 'Zelle';
                    this.orderData.payment.type = 3;
                }
            },

            selectPaymentVenmo() {
                this.paymentError = false;
                this.paymentZelleError = false;

                this.orderData.payment.email = '';
                this.checkEmail = '';
                this.orderData.payment.name = 'Venmo';
                this.orderData.payment.type = 4;
            },

            validateEmail()
            {
                this.emailError = null;

                if (/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(this.orderData.user_email))
                {
                    this.registerErrors = [];

                    axios.post(
                        Router.route('web.register.store', {
                            name: this.orderData.user_email,
                            email: this.orderData.user_email,
                            phone: '1234567890',
                            password: 'pass123',
                            password_confirmation: 'pass123',
                            is_guest: !!this.user.id
                        }),
                    ).then((data) => {
                        location.reload();
                    }).catch(({ response: { data: { errors } } }) => {
                        this.registerErrors = errors;
                    });
                } else {
                    this.emailError = 'Please provide a valid email address.';
                }
            },

            validatePayment(){
                this.paymentError = false;

                if (!this.paymentZelleError)

                if (
                    /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(this.orderData.payment.email) &&
                    this.orderData.payment.type &&
                    this.orderData.payment.email &&
                    this.orderData.payment.email === this.checkEmail
                )
                {
                    this.stepIndex++;

                    let offset = 160; // sticky nav height
                    let el = document.querySelector('#to-top');
                    window.scroll({ top: (el.offsetTop - offset), left: 0, behavior: 'smooth' });
                } else {
                    this.paymentError = true;
                }
            },

            acceptNumber() {
                var x = this.orderData.address.phone.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
                this.orderData.address.phone = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
            },

            acceptZip() {
                this.orderData.address.postal_code = this.orderData.address.postal_code.replace(/\D/g, '');

                if (this.orderData.address.postal_code.replace(/[^0-9]/g,"").length > 5) {
                    this.orderData.address.postal_code = this.orderData.address.postal_code.slice(0, -1);
                }
            },

            validateAddress(){
                this.addressError.name = false;
                this.addressError.phone = false;
                this.addressError.postal_code = false;
                this.addressError.address1 = false;
                this.addressError.city = false;
                this.addressError.state = false;

                !this.orderData.address.name ? this.addressError.name = true : this.addressError.name = false;

                !this.orderData.address.phone || this.orderData.address.phone.replace(/[^0-9]/g,"").length < 10 ? this.addressError.phone = true : this.addressError.phone = false;

                !this.orderData.address.postal_code || this.orderData.address.postal_code.replace(/[^0-9]/g,"").length < 5 ? this.addressError.postal_code = true : this.addressError.postal_code = false;

                !this.orderData.address.address1 ? this.addressError.address1 = true : this.addressError.address1 = false;

                !this.orderData.address.city ? this.addressError.city = true : this.addressError.city = false;

                !this.orderData.address.state ? this.addressError.state = true : this.addressError.state = false;

                if (
                    !this.addressError.name &&
                    !this.addressError.phone &&
                    !this.addressError.postal_code &&
                    !this.addressError.address1 &&
                    !this.addressError.city &&
                    !this.addressError.state
                )
                {
                    this.stepIndex++;

                    let offset = 160; // sticky nav height
                    let el = document.querySelector('#to-top');
                    window.scroll({ top: (el.offsetTop - offset), left: 0, behavior: 'smooth' });
                }
            },

            addInsurance(){
                this.orderData.insurance = true;

                this.valuate();
            },

            deleteInsurance(){
                this.orderData.insurance = false;

                this.valuate();
            },

            validateTerms() {
                this.termsError = false;

                this.showCheckout = false;

                if (this.accept_terms)
                {
                    this.orderData.total_summ = this.totalSumm;

                    this.orderData.orders = this.orders;

                    this.orderData.user_id = this.user ? this.user.id : null;

                    this.formData = new FormData();
                    this.formData.set('_method', 'POST');
                    this.collectFormData(this.orderData);

                    axios.post(
                        Router.route('order'),
                        this.formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            },
                        },
                    ).then((data) => {
                        let orders = {
                            order: []
                        };

                        localStorage.setItem("orders", JSON.stringify(orders));

                        location.href = Router.route('thanks', {order_uuid: data.data.order_uuid});
                    }).catch(({ response: { data: { errors } } }) => {
                        this.errors = errors;
                    });
                } else {
                    this.termsError = true;

                    this.showCheckout = true;
                }
            },

            number_format(number, decimals) {
                return Number(Math.round(number+"e"+decimals)+"e-"+decimals); 
            }
        },

        created(){
            this.valuate();

            if (this.user.id) {
                this.stepIndex = 2;

                _.each(this.user.addresses, (value, key) => {
                    if (value.is_default === "1") {
                        this.orderData.address = this.user.addresses[key];
                    }
                });
            } else {
                this.stepIndex = 1;
            }

            if (!this.orders || this.orders['order'] === [] || this.orders['order'].length === 0) {
                location.href = Router.route('home');
            }
        }
    }
</script>
