<template>
    <section class="cart-section">
        <div class="container">
            <div class="cart-section-title">
                <h1>Your box</h1>
                <a href="javascript:0" v-if="orders && orders['order'].length" v-on:click="clearCart" class="empty-cart-link">Empty your box</a>
                <a href="/#sell-device-section" v-if="!orders || !orders['order'].length" class="empty-cart-link">Sell Your Device</a>
            </div>

            <form class="cart-form" v-if="orders">
                <div class="cart-table-outer">
                    <table class="cart-table">
                        <thead>
                        <tr>
                            <th>Items</th>
                            <th>Quantity</th>
                            <th>Item value</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(order, index) in orders['order']" :key="`device_${index}`">
                            <td>
                                <div class="product-name">
                                    <a class="image" href=""><img :alt="order.device.name" :src="order.device.image"></a>
                                    <div class="inner">
                                        <div class="name"><a :href="$r('get-category', { slug:  order.device.slug })">{{ order.device.name }}</a></div>
                                        <div class="chars" v-if="order.steps">
                                            <span v-for="(option, key) in order.steps" v-if="option">
                                                {{ option ? option.value === 'Yes' || option.value === 'No' ? '' : key == Object.keys(order.steps).pop() ? option.step_name.name + ': ' + option.value : option.step_name.name + ': ' + option.value + ', ' : '' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="product-amount">
                                    <span v-on:click="minusQtn(index)" class="qtyminus">−</span>
                                    <input type="text" class="qty" v-model="orders.order[index].ctn" v-on:keyup="onChangeQtn(index)">
                                    <span v-on:click="plusQtn(index)" data-max="100" class="qtyplus">+</span>
                                </div>
                            </td>
                            <td>
                                <div class="price">${{ order.total }}</div>
                            </td>
                            <td>
                                <a href="javascript:0" v-on:click="removeFromCart(index)" class="remove"><img src="../../client/images/remove_product.svg" alt="remove_product"></a>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="cart-total" v-if="totalSumm > 0">
                    <h5>Trade-in summary</h5>
                    <ul class="cart-total-list">
                        <li>
                            <span>Total Payout</span>
                            <div class="price">${{ parseFloat(totalSumm).toFixed(2) }}</div>
                        </li>
                        <li>
                            <span>Delivery</span>
                            <div class="price free">Free</div>
                        </li>
                    </ul>
                    <a :href="$r('checkout')" class="btn red-btn">Checkout</a>
                </div>
            </form>
        </div>
    </section>
</template>

<script>
    import FormHelper from '../../admin/js/mixins/form_helper';

    export default {
        mixins: [FormHelper],

        data() {
            return {
                orders: JSON.parse(localStorage.getItem("orders")),
                totalSumm: 0,
            };
        },

        methods: {
            removeFromCart(index) {
                this.orders.order.splice(index,1);

                localStorage.setItem("orders", JSON.stringify(this.orders));

                this.valuate();

                this.updateDBCart();

                location.reload();
            },

            clearCart() {
                let orders = {
                    order: []
                };

                localStorage.setItem("orders", JSON.stringify(orders));

                this.totalSumm = 0;

                axios.post(
                    Router.route('delete-cart'),
                ).then((data) => {
                    location.reload();
                }).catch(({ response: { data: { errors } } }) => {
                    // notify.success(
                    //     errors
                    // );
                });
            },

            plusQtn(index) {
                this.orders['order'][index].ctn =  parseInt(this.orders['order'][index].ctn) + 1;

                this.orders.order[index].total = this.orders.order[index].summ * this.orders.order[index].ctn;

                localStorage.setItem("orders", JSON.stringify(this.orders));

                this.valuate();

                this.updateDBCart();

                location.reload();
            },

            minusQtn(index) {
                if (parseInt(this.orders.order[index].ctn) !== 1) {
                    this.orders['order'][index].ctn =  parseInt(this.orders['order'][index].ctn) - 1;
                }

                this.orders.order[index].total = this.orders.order[index].summ * this.orders.order[index].ctn;

                localStorage.setItem("orders", JSON.stringify(this.orders));

                this.valuate();

                this.updateDBCart();

                location.reload();
            },

            onChangeQtn(index) {
                this.orders.order[index].total = this.orders.order[index].summ * parseInt(this.orders.order[index].ctn);

                localStorage.setItem("orders", JSON.stringify(this.orders));

                this.valuate();

                this.updateDBCart();

                location.reload();
            },

            updateDBCart() {
                if (this.orders && this.orders['order'].length) {
                    this.formData = new FormData();
                    this.formData.set('_method', 'POST');

                    this.collectFormData({
                        orders: this.orders['order']
                    });

                    axios.post(
                        Router.route('update-cart'),
                        this.formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            },
                        },
                    ).then((data) => {
                        //console.log(data);
                    }).catch(({ response: { data: { errors } } }) => {
                        // notify.success(
                        //     errors
                        // );
                    });
                } else {
                    axios.post(
                        Router.route('delete-cart'),
                    ).then((data) => {
                        //console.log(data);
                    }).catch(({ response: { data: { errors } } }) => {
                        // notify.success(
                        //     errors
                        // );
                    });
                }
            },

            valuate(){
                this.totalSumm = 0;

                if (this.orders && this.orders['order'].length) {
                    _.each(this.orders['order'], (key, value) => {
                        if(key) {
                            this.totalSumm += parseFloat(key.total);
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
