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
            },
        },

        data() {
            return {
                data: null,
            };
        },

        methods: {

        },

        created(){

        }
    }
</script>
