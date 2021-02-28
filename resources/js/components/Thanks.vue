<template>
    <section class="checkout-section shipping">
        <div class="container">
            <h1>Thank You</h1>
            <p>for selling with us!</p>
            <br>
            <p>Your offer number is {{ order.id }}.</p>
            <br>
            <br>
            <p>A copy of this information has been sent to {{ order.user_email }}</p>
            <div class="checkout-flex">
                <div class="checkout-content">
                    <div class="shipping-top">
                        <div class="shipping-title">SHIPPING INSTRUCTIONS</div>
                    </div>
                    <div class="shipping-card">
                        <div class="shipping-card-top">
                            <h1 class="shipping-card-title">Okay, I sold! Now what?</h1>
                            <p class="shipping-card-subtitle">Shipping your device to us is fast, free, and easy!</p>
                        </div>
                        <div class="shipping-card-item">
                            <img src="../../client/images/smartphone.svg" class="shipping-card-item-image">
                            <div class="shipping-card-item_row">
                                <h2>Device Preparation</h2>
                                <p class="shipping-card-item_row-text">There are a few steps you need to take before your phone is ready to ship. Be sure you have indicated to your carrier that you are deactivating your phone. Turn off the “find my iPhone” option on Apple devices or the google activation lock on Android devices. Remove any SIM or SD cards with data as well. Don’t forget to back up any data you want to keep! Reset your device and make sure it is fully charged.</p>
                            </div>
                        </div>
                        <div class="shipping-card-item">
                            <img src="../../client/images/package.svg" class="shipping-card-item-image">
                            <div class="shipping-card-item_row">
                                <h2>Packaging</h2>
                                <p class="shipping-card-item_row-text">Please be sure to pack your item securely. The better condition your phone arrives in, the better the value of your phone! Some packaging options we recommend are Styrofoam peanuts, air-filled pillows, or crumpled paper. Once you have your phone comfortably set, be sure to firmly tape your box shut! Now you are ready to prepare for shipment.</p>
                            </div>
                        </div>
                        <div class="shipping-card-item">
                            <img src="../../client/images/information.svg" class="shipping-card-item-image">
                            <div class="shipping-card-item_row">
                                <h2>Shipping Information</h2>
                                <p class="shipping-card-item_row-text">Once your phone is packed nicely you can add the correct shipping label. Be sure the barcode is flat and legible to ensure your package is not delayed. You can drop off your package at the chosen shipping carrier. Your trade-in offer is guaranteed for 14 days, so be sure to ship within that time frame.</p>
                                <div class="shipping-card-item_buttons">
                                    <a  v-if="!fedexPdfUrl" href="javascript:void(0)" v-on:click="getFedexLable" class="fedex-button">
                                        <img src="../../client/images/fedex-logo.svg" class="shipping-card-item-image_fedex">
                                        <p>Print Label</p>
                                    </a>
                                    <a v-if="fedexPdfUrl" class="btn btn-info fedex-pdf-label" :href="fedexPdfUrl" target="_blank">Open PDF</a>
                                    <a href="#" class="ups-button">
                                        <img src="../../client/images/ups-logo.svg" class="shipping-card-item-image_ups">
                                        <p>Print Label</p>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="shipping-card-item">
                            <img src="../../client/images/clipboard.svg" class="shipping-card-item-image">
                            <div class="shipping-card-item_row">
                                <h2>Final Checklist</h2>
                                <p class="shipping-card-item_row-text">It is important you follow all of the instructions to be sure your phone is delivered safely and securely. Use the checklist below to be sure that you have followed all the steps!</p>
                            </div>
                        </div>
                    </div>
                    <div class="ready-section">
                        <h1>Am I Ready?</h1>
                        <div class="ready-section-row">
                            <div class="ready-section-item">
                                <h2>Device Preparation</h2>
                                <p>Required: I have reset my device or I have turned off “Find My iPhone” or “Android Activation Lock“</p>
                                <p>Optional: Removed any SIM or SD cards</p>
                            </div>
                            <div class="ready-section-item_arrow">
                                <img src="../../client/images/down-arrow.svg" class="ready-section-image_arrow-top">
                                <img src="../../client/images/down-arrow.svg" class="ready-section-image_arrow-bottom">
                            </div>
                            <div class="ready-section-item">
                                <h2>Packaging</h2>
                                <p>I have a box to ship my device in</p>
                                <p>I have used safe packaging materials to secure my phone</p>
                                <p>I have taped the box shut</p>
                                <p>The shipping label is flat and the barcode is visible</p>
                            </div>
                        </div>
                        <div class="ready-section-check">
                            <img src="../../client/images/checked.svg" class="ready-section-check-image">
                            <h2>You're ready! Ship it out!</h2>
                        </div>
                    </div>
                    <div class="order-info_row">
                        <div class="offer-section">
                            <p class="offer-section_title">OFFER #{{ order.id }}</p>
                            <div class="offer-info_card">
                                <p>{{ order.created_at }}</p>
                                <p>Payout Preference:	<span>{{ order.payment.name }}</span></p>
                                <p>Total Payout:	<span style="color: #8aa621;">${{ order.total_summ }}</span></p>
                                <hr>
                                <p>We're waiting for this offer to be shipped to our warehouse.</p>
                            </div>
                        </div>
                        <div class="contact-section">
                            <p class="offer-section_title">CONTACT INFORMATION</p>
                            <div class="contact-section_card">
                                <p><span>{{ order.address.name }},</span></p>
                                <p>{{ order.address.address1 }},</p>
                                <p v-if="order.address.address2">{{ order.address.address2 }},</p>
                                <p>{{ order.address.postal_code }},</p>
                                <p>{{ order.address.state }},</p>
                                <p>{{ order.address.city }},</p>
                                <p><span>{{ order.address.phone }},</span></p>
                                <p><span>{{ order.user_email }}</span></p>
                            </div>
                        </div>
                    </div>
                    <div class="search-section">
                        <p class="offer-section_title">MODIFY YOUR TRADE-IN OFFER</p>
                        <div class="offer-info_card">
                            <p class="thanks-search-title">You may remove or make changes to items in this trade-in offer up until we've received them at our warehouse. To add new items to this offer, start by searching below:</p>
                            <div class="order-search">
                                <div class="order-search-form">
                                    <input v-on:keyup="searchDevice" v-model="name" type="text" placeholder="Write text for search">
                                    <a href="javascript:void(0)" v-on:click="searchDevice" class="btn red-btn">Search</a>
                                </div>
                                <div class="order-search-popup" v-if="searchDevices.length">
                                    <ul class="order-search-popup-list">
                                        <li v-for="(device, index) in searchDevices" :key="`device_${index}`">
                                            <a :href="$r('get-category', { category: device.id })" class="link">
                                                <div class="image"><img :src="device.image" alt=""></div>
                                                <span class="name">{{ device.name }}</span>
                                            </a>
                                            <div class="price">up to <strong>{{ device.custom_text }}</strong></div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="orders-section">
                        <p class="offer-section_title">OFFER ITEMS</p>
                        <div class="offer-info_card">
                            <div class="checkout-content-step account-tab">
                                <div class="cart-table-outer">
                                    <table class="cart-table orders-table">
                                        <thead>
                                        <tr>
                                            <th>Items</th>
                                            <th>Quantity</th>
                                            <th>Status</th>
                                            <th>Item value</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(product, index) in order.orders['order']" :key="`product_${index}`">
                                            <td>
                                                <div class="product-name">
                                                    <a class="image" href=""><img alt="" :src="product.device.image"></a>
                                                    <div class="inner">
                                                        <div class="name"><a :href="$r('get-category', { category:  product.device.id })">{{ product.device.name }}</a></div>
                                                        <div class="chars" v-if="product.steps">
                                                            <span v-for="(option, key) in product.steps" v-if="option">{{  option ? option.name === 'Yes' || option.name === 'No' ? '' : key === Object.keys(product.steps).pop() ? option.name :  option.name + ', ' : '' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="product-amount">
                                                    <p type="text" class="qty" style="padding-top: 15px;">{{ product.ctn }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <p>{{ statuses[order.ordered_status] }}</p>
                                            </td>
                                            <td>
                                                <div class="price">${{ product.total }}</div>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="fedexError && isPopupOpen" id="popup1" class="overlay-address" style="display: block!important;">
            <div class="popup-address">
                <div class="popup-data">
                    <h2>Address Error</h2>
                    <p>Whoops, it appears there was an issue generating your free shipping label. Try double checking your shipping address; if your address is correct and a label won't generate, contact our support team and we'll send you a label right away!</p>
                    <a v-on:click="fedexError = false" class="close" href="#">&times;</a>
                    <div class="content">
                        <form name="contactform" class="simple-form popup-form" autocomplete="on">
                            <div class="input-block width-50">
                                <input v-model="order.address.name" name="name" autocomplete="on" type="text" placeholder="First and Last Name*">
                            </div>
                            <div class="input-block width-50">
                                <input v-model="order.address.phone" name="tel" autocomplete="on" type="tel" placeholder="Phone*">
                            </div>
                            <div class="input-block width-50">
                                <input v-model="order.address.address1" name="address-line1" autocomplete="on" type="text" placeholder="Adress 1*">
                            </div>
                            <div class="input-block width-50">
                                <input v-model="order.address.address2" name="address-line2" autocomplete="on" type="text" placeholder="Adress 2">
                            </div>
                            <div class="input-block width-50">
                                <input v-model="order.address.city" name="country-name" autocomplete="on" type="text" placeholder="City*">
                            </div>
                            <div class="input-block width-25">
                                <select name="state" autocomplete="on" type="text" v-model="order.address.state">
                                    <option :value="null">State*</option>
                                    <option v-for="(state, i) in states" :key="`state_${i}`" :value="i">{{ state }}</option>
                                </select>
                            </div>
                            <div class="input-block width-25">
                                <input v-model="order.address.postal_code" name="postal-code" autocomplete="on" type="text" placeholder="Postal Code*">
                            </div>
                            <br>
                            <a href="javascript:void(0)" v-on:click="tryAgainFedex" class="btn red-btn popup-open">Try Again</a>
                            <br>
                            <br>
                            <span v-if="addressError" class="address-error">
                                <p>Please fill all required fields</p>
                            </span>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
    import FormHelper from "../../admin/js/mixins/form_helper";

    export default {
        mixins: [FormHelper],

        props: {
            order: {
                type: Object,
                required: true,
                statuses: [],
            },
            statuses: {
                type: Object,
                required: true,
            },
            states: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
                name: null,
                searchDevices: {},
                fedexError: false,
                addressError: false,
                isPopupOpen: false,
                fedexPdfUrl: null,
            };
        },

        methods: {
            searchDevice() {
                this.searchDevices = {};

                axios.get(
                    Router.route('header-search', { name: this.name }),
                ).then((data) => {
                    this.searchDevices = data.data;
                }).catch(({ response: { data: { errors } } }) => {
                    console.log(errors);
                });
            },

            getFedexLable() {
                this.addressError = false;
                this.isPopupOpen = false;

                axios.get(
                    Router.route('fedex-label', { order: this.order.id }),
                ).then((data) => {
                    window.open(data.data.url, '_blank');

                    this.fedexPdfUrl = data.data.url;

                    this.fedexError = false;
                    this.addressError = false;
                }).catch(({ response: { data: { errors } } }) => {
                    this.fedexError = true;
                    this.isPopupOpen = true;
                });
            },

            tryAgainFedex(){
                this.addressError = false;

                    this.formData = new FormData();
                    this.formData.set('_method', 'PATCH');
                    this.collectFormData(this.order);

                    axios.post(
                        Router.route('update-order', { order: this.order.id }),
                        this.formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            },
                        },
                    ).then((data) => {
                        window.open(data.data.url, '_blank');

                        this.fedexPdfUrl = data.data.url;

                        this.fedexError = false;
                        this.addressError = false;
                    }).catch(({ response: { data: { errors } } }) => {
                        this.fedexError = true;
                        this.isPopupOpen = true;
                    });
            }
        },

        created(){

        }
    }
</script>
