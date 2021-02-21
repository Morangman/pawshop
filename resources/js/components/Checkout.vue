<template>
    <section class="checkout-section">
        <div class="container">
            <h1>Checkout</h1>
            <div class="checkout-flex">
                <div class="checkout-content">
                    <ul class="order-steps-list">
                        <li>
                            <a href=""><i>1.</i> <span>Account</span></a>
                        </li>
                        <li class="active-step">
                            <a href=""><i>2.</i> <span>Payment</span></a>
                        </li>
                        <li>
                            <a href=""><i>3.</i> <span>Shipping</span></a>
                        </li>
                        <li>
                            <a href=""><i>4.</i> <span>Options & Terms</span></a>
                        </li>
                    </ul>

                    <!-- Step 1 -->
                    <div class="checkout-content-step">
                        <h2>Account</h2>
                        <div class="checkout-login">
                            <div class="inner-block">
                                <div class="checkout-customer">
                                    <h4>Customer login</h4>
                                    <div class="input-block">
                                        <input type="text" placeholder="E-mail">
                                    </div>
                                    <div class="input-block">
                                        <input type="password" placeholder="Password">
                                    </div>
                                    <div class="password-forget">
                                        <a href="">Forgot your password?</a>
                                    </div>
                                    <div class="bottom-links">
                                        <button class="btn red-btn">Login</button>
                                        <div class="divider">or</div>
                                        <ul class="socials">
                                            <li>
                                                <a href=""><img src="../../client/images/google.svg" alt=""></a>
                                            </li>
                                            <li>
                                                <a href=""><img src="../../client/images/facebook.svg" alt=""></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="inner-block">
                                <div class="checkout-guest">
                                    <h4>Guest Checkout</h4>
                                    <div class="input-block">
                                        <input type="text" placeholder="E-mail">
                                    </div>
                                    <button class="btn red-btn">Continue as Guest</button>
                                </div>
                                <div class="checkout-account">
                                    <a href="">Create an Account</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2 -->
                    <div class="checkout-content-step">
                        <h2>Payment</h2>
                        <div class="checkout-payment">
                            <h4>How would you like to be paid?</h4>
                            <div class="checkout-payment-list">
                                <label class="payment-radiobox" data-tab="#payment-1">
                                    <input type="radio" name="payment-radios">
                                    <span>
												<img src="../../client/images/pay_check.svg" alt="">
												<small>Check</small>
											</span>
                                </label>
                                <label class="payment-radiobox" data-tab="#payment-2">
                                    <input type="radio" name="payment-radios">
                                    <span>
												<img src="../../client/images/pay_paypal.svg" alt="">
												<small>PayPal</small>
											</span>
                                </label>
                                <label class="payment-radiobox" data-tab="#payment-3">
                                    <input type="radio" name="payment-radios">
                                    <span>
												<img src="../../client/images/pay_zelle.png" alt="">
												<small>Zelle</small>
											</span>
                                </label>
                                <label class="payment-radiobox" data-tab="#payment-4">
                                    <input type="radio" name="payment-radios">
                                    <span>
												<img src="../../client/images/pay_venmo.png" alt="">
												<small>Venmo</small>
											</span>
                                </label>
                            </div>
                        </div>
                        <div id="payment-1" class="checkout-payment-content">
                            <p>We'll mail you a check once your item(s) have been received and verified by our team.</p>
                        </div>
                        <div id="payment-2" class="checkout-payment-content">
                            <p>We'll credit your PayPal Email Address once your item(s) have been verified by our staff. Please note that PayPal charges a fee ($0.30 + 2.9%) to receive funds using their service.</p>
                            <div class="inputs-flex">
                                <div class="input-block width-50">
                                    <input type="text" placeholder="PayPal E-mail Adress">
                                </div>
                                <div class="input-block width-50">
                                    <input type="text" placeholder="Confirm PayPal E-mail Adress">
                                </div>
                            </div>
                        </div>
                        <div id="payment-3" class="checkout-payment-content">
                            <p>We'll credit your Zelle® account once your item(s) have been verified by our staff. Email address provided must be associated and linked with your Zelle® account to avoid any delays.</p>
                            <div class="inputs-flex">
                                <div class="input-block width-50">
                                    <input type="text" placeholder="Zelle E-mail Adress">
                                </div>
                                <div class="input-block width-50">
                                    <input type="text" placeholder="Confirm Zelle E-mail Adress">
                                </div>
                            </div>
                        </div>
                        <div id="payment-4" class="checkout-payment-content">
                            <p>We'll credit your Venmo® account once your item(s) have been verified by our staff. Email address provided must be associated and linked with your Venmo® account to avoid any delays.</p>
                            <div class="inputs-flex">
                                <div class="input-block width-50">
                                    <input type="text" placeholder="Venmo E-mail Adress">
                                </div>
                                <div class="input-block width-50">
                                    <input type="text" placeholder="Confirm Venmo E-mail Adress">
                                </div>
                            </div>
                        </div>
                        <div class="order-options-links">
                            <button class="btn gray-btn">Back</button>
                            <button class="btn red-btn">Next step</button>
                        </div>
                    </div>

                    <!-- Step 3 -->
                    <div class="checkout-content-step">
                        <h2>Shipping</h2>
                        <div class="checkout-simple-block">
                            <h4>Your adress</h4>
                            <div class="inputs-flex">
                                <div class="input-block width-50">
                                    <input type="text" placeholder="First and Last Name">
                                </div>
                                <div class="input-block width-50">
                                    <input type="text" placeholder="Phone">
                                </div>
                                <div class="input-block width-50">
                                    <input type="text" placeholder="Adress 1">
                                </div>
                                <div class="input-block width-50">
                                    <input type="text" placeholder="Adress 2">
                                </div>
                                <div class="input-block width-50">
                                    <input type="text" placeholder="City">
                                </div>
                                <div class="input-block width-25">
                                    <input type="text" placeholder="State">
                                </div>
                                <div class="input-block width-25">
                                    <input type="text" placeholder="Postal Code">
                                </div>
                            </div>
                        </div>
                        <div class="checkout-simple-block">
                            <h4>Get paid faster <small>(optional)</small></h4>
                            <ul class="checkbox-list">
                                <li>
                                    <label class="checkbox-block">
                                        <input type="checkbox" name="paid-checkbox">
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
                            <button class="btn gray-btn">Back</button>
                            <button class="btn red-btn">Next step</button>
                        </div>
                    </div>

                    <!-- Step 4 -->
                    <div class="checkout-content-step">
                        <h2>Options & Terms</h2>
                        <div class="checkout-simple-block">
                            <h4>Shipping insurance</h4>
                            <div class="desc">
                                <p>All packages are insured up to $100 for free. If you'd like, you can purchase additional shipping insurance to cover the full value of your offer for just $4.00.</p>
                            </div>
                            <div class="radio-list">
                                <div class="options-radio">
                                    <label class="radiobox-block">
                                        <input type="radio" name="step-4-radios" checked="">
                                        <i></i>
                                        <span>
													<strong>Yes, Add Insurance</strong>
												</span>
                                    </label>
                                </div>
                                <div class="options-radio">
                                    <label class="radiobox-block">
                                        <input type="radio" name="step-4-radios">
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
                            <h4>Terms & Conditions</h4>
                            <div class="desc">
                                <p>Please carefully read our terms and conditions before placing your offer.</p>
                            </div>
                            <ul class="checkbox-list">
                                <li>
                                    <label class="checkbox-block">
                                        <input type="checkbox" name="Options-checkbox">
                                        <i></i>
                                        <span>I accept the terms and conditions</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="checkbox-block">
                                        <input type="checkbox" name="Options-checkbox">
                                        <i></i>
                                        <span>Send me occasional special offers</span>
                                    </label>
                                </li>
                                <li>
                                    <label class="checkbox-block">
                                        <input type="checkbox" name="Options-checkbox">
                                        <i></i>
                                        <span>Send me important SMS notifications to <strong>+1 234 567 89 22</strong></span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                        <div class="order-options-links">
                            <button class="btn gray-btn">Back</button>
                            <button class="btn red-btn">Checkout</button>
                        </div>
                    </div>

                </div>
                <div class="cart-total checkout-summary">
                    <h5>Trade-in summary</h5>
                    <div class="cart-total-product" v-for="(order, index) in orders['order']" :key="`device_${index}`">
                        <a href="" class="image"><img :src="order.device.image" alt=""></a>
                        <div class="inner">
                            <div class="name"><a :href="$r('get-category', { category:  order.device.id })">{{ order.device.name }}</a></div>
                            <div class="price">${{ order.total }}</div>
                            <br>
                            <span>×{{ order.ctn }}</span>
                        </div>
                    </div>
                    <ul class="cart-total-list">
                        <li>
                            <span>Total Payout</span>
                            <div class="price">${{ totalSumm }}</div>
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
    export default {
        data() {
            return {
                orders: JSON.parse(localStorage.getItem("orders")),
                totalSumm: 0
            };
        },

        methods: {
            valuate(){
                this.totalSumm = 0;

                if (this.orders['order'].length) {
                    _.each(this.orders['order'], (key, value) => {
                        if(key) {
                            this.totalSumm += key.total;
                        }
                    });
                }
            },
        },

        created(){
            this.valuate();
        }
    }
</script>
