<template>
    <section class="account-section">
        <div class="container">
            <h1>My Account</h1>
            <b-card no-body>
                <b-tabs card>
                    <b-tab title="Account Info" :active="tab === 'account'">
                        <div class="row checkout-content-step account-tab">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>
                                                <p>First and Last Name</p>
                                            </label>
                                            <input
                                                name="name"
                                                type="text"
                                                v-model="model.name"
                                                class="form-control"
                                                :class="errors.name ? 'input-error' : ''"
                                            >
                                            <div v-for="(error, i) in errors.name"
                                                 :key="`name__error__${i}`"
                                                 class="invalid-feedback"
                                            >
                                                <strong>{{ error }}</strong>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>
                                                <p>Email</p>
                                            </label>
                                            <input
                                                name="email"
                                                type="email"
                                                v-model="model.email"
                                                class="form-control"
                                                :class="errors.email ? 'input-error' : ''"
                                            >
                                            <div v-for="(error, i) in errors.email"
                                                 :key="`email__error__${i}`"
                                                 class="invalid-feedback"
                                            >
                                                <strong>{{ error }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>
                                                <p>Phone</p>
                                            </label>
                                            <input
                                                name="phone"
                                                type="tel"
                                                v-model="model.phone"
                                                class="form-control"
                                                :class="errors.phone ? 'input-error' : ''"
                                            >
                                            <div v-for="(error, i) in errors.phone"
                                                 :key="`phone__error__${i}`"
                                                 class="invalid-feedback"
                                            >
                                                <strong>{{ error }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="custom-row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>
                                                    <p>Password</p>
                                                </label>
                                                <input
                                                    name="password"
                                                    type="text"
                                                    v-model="model.password"
                                                    class="form-control"
                                                    :class="errors.password ? 'input-error' : ''"
                                                >
                                                <div v-for="(error, i) in errors.password"
                                                     :key="`password__error__${i}`"
                                                     class="invalid-feedback"
                                                >
                                                    <strong>{{ error }}</strong>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <custom-checkbox
                                                    style="margin-top: 20px;"
                                                    :name="'Send me important mail notifications about my trade-ins'"
                                                    :option="model.mail_subscription ? '1' : '0'"
                                                    v-model="model.mail_subscription"
                                                ></custom-checkbox>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="form-group">
                                                <label>
                                                    <p>Old password</p>
                                                </label>
                                                <input
                                                    name="old_password"
                                                    type="email"
                                                    v-model="model.old_password"
                                                    class="form-control"
                                                    :class="errors.old_password ? 'input-error' : ''"
                                                >
                                                <div v-for="(error, i) in errors.old_password"
                                                     :key="`old_password__error__${i}`"
                                                     class="invalid-feedback"
                                                >
                                                    <strong>{{ error }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <b-tab title="Addresses" :active="tab === 'address'">
                        <div class="checkout-content-step account-tab">
                            <div class="card">
                                <div class="card-body">
                                    <div class="form-group">
                                        <div class="change-blocks-wrapper__item" v-for="(item, index) in model.addresses">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="text-right">
                                                        <a href="javascript:void(0)" v-on:click="deleteAddress(index)" class="remove"><img src="../../client/images/remove_product.svg?e0784a02269348e05e66f882b885e9f2" alt="remove_product"></a>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <form autocomplete="on" class="inputs-flex">
                                                                    <div class="input-block width-50">
                                                                        <input v-model="model.addresses[index].name" :class="{'input-error': dataErrors[index].name }" name="name" autocomplete="on" type="text" placeholder="First and Last Name*">
                                                                    </div>
                                                                    <div class="input-block width-50">
                                                                        <input v-model="model.addresses[index].phone" :class="{'input-error': dataErrors[index].phone }" name="tel" autocomplete="on" type="tel" placeholder="Phone*">
                                                                    </div>
                                                                    <div class="input-block width-50">
                                                                        <input v-model="model.addresses[index].address1" :class="{'input-error': dataErrors[index].address1 }" name="address-line1" autocomplete="on" type="text" placeholder="Adress 1*">
                                                                    </div>
                                                                    <div class="input-block width-50">
                                                                        <input v-model="model.addresses[index].address2" name="address-line2" autocomplete="on" type="text" placeholder="Adress 2">
                                                                    </div>
                                                                    <div class="input-block width-50">
                                                                        <input v-model="model.addresses[index].city" :class="{'input-error': dataErrors[index].city }" name="country-name" autocomplete="on" type="text" placeholder="City*">
                                                                    </div>
                                                                    <div class="input-block width-25">
                                                                        <select name="state" autocomplete="on" type="text" :class="{'input-error': dataErrors[index].state }" v-model="model.addresses[index].state">
                                                                            <option :value="null">State*</option>
                                                                            <option v-for="(state, i) in states" :key="`state_${i}`" :value="i">{{ state }}</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="input-block width-25">
                                                                        <input v-model="model.addresses[index].postal_code" :class="{'input-error': dataErrors[index].postal_code }" name="postal-code" autocomplete="on" type="text" placeholder="Postal Code*">
                                                                    </div>
                                                                    <div class="input-block">
                                                                        <custom-checkbox
                                                                            :id="`checkbox_${index}`"
                                                                            :name="'Default Address'"
                                                                            :option="model.addresses[index].is_default ? model.addresses[index].is_default : '0'"
                                                                            v-model="model.addresses[index].is_default"
                                                                        ></custom-checkbox>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <div v-if="addressError" class="invalid-feedback">
                                        <strong>Please fill all required fields.</strong>
                                    </div>
                                    <div class="add-address-btn">
                                        <button
                                            v-on:click="addAddress"
                                            class="btn btn-primary margin-top-10"
                                        >Add</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <b-tab title="Trade-Ins" :active="tab === 'trade'">
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
                                    <tbody v-for="(userOrder, key) in orders">
                                        <tr v-for="(order, index) in userOrder.orders['order']" :key="`device_${index}`">
                                            <td>
                                                <div class="product-name">
                                                    <a class="image" href=""><img :alt="order.device.name" :src="order.device.image"></a>
                                                    <div class="inner">
                                                        <div class="name"><a :href="$r('get-category', { slug:  order.device.slug })">{{ order.device.name }}</a></div>
                                                        <div class="chars" v-if="order.steps">
                                                            <span v-for="(option, key) in order.steps" v-if="option">{{  option ? option.value === 'Yes' || option.value === 'No' ? '' : key === Object.keys(order.steps).pop() ? option.value :  option.value + ', ' : '' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="product-amount">
                                                    <p type="text" class="qty" style="padding-top: 15px;">{{ orders[key].orders.order[index].ctn }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <p :style="'color:' + userOrder.order_status.color">{{ userOrder.order_status.name }}</p>
                                            </td>
                                            <td>
                                                <div class="price">${{ parseFloat(userOrder.total_summ).toFixed(2) }}</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </b-tab>
                </b-tabs>
            </b-card>
            <div class="bottom-links">
                <button v-on:click="update" type="submit" class="btn red-btn">
                    Save Account Details
                </button>
            </div>
        </div>
    </section>
</template>

<script>
    import FormHelper from "../../admin/js/mixins/form_helper";
    import CustomCheckbox from "./CustomCheckbox.vue";

    export default {
        mixins: [FormHelper],

        components: {
            CustomCheckbox,
        },

        props: {
            user: {
                type: Object,
                required: true,
            },
            states: {
                type: Object,
                required: true,
            },
            statuses: {
                type: Object,
                required: true,
            },
            orders: {
                type: Array,
                required: false,
            },
            tab: {
                type: String,
                required: false,
            }
        },

        data() {
            return {
                model: this.user,
                errors: {},
                dataErrors: [],
                addressError: false,
            };
        },

        methods: {
            addAddress() {
                this.model.addresses.push({
                    name: null,
                    phone: null,
                    address1: null,
                    address2: null,
                    city: null,
                    state: null,
                    postal_code: null,
                    is_default: 0,
                });

                _.each(this.model.addresses, (data, index) => {
                    this.dataErrors[index] = {
                        name: false,
                        phone: false,
                        address1: false,
                        address2: false,
                        city: false,
                        state: false,
                        postal_code: false,
                    };
                });
            },

            deleteAddress(index) {
                this.model.addresses.splice(index, 1);
            },

            update() {
                this.addressError = false;

                _.each(this.model.addresses, (data, index) => {
                    this.dataErrors[index] = {
                        name: false,
                        phone: false,
                        address1: false,
                        address2: false,
                        city: false,
                        state: false,
                        postal_code: false,
                    };

                    if (!data.name) {
                        this.addressError = true;
                        this.dataErrors[index].name = true;
                    }
                    if (!data.phone) {
                        this.addressError = true;
                        this.dataErrors[index].phone = true;
                    }
                    if (!data.address1) {
                        this.addressError = true;
                        this.dataErrors[index].address1 = true;
                    }
                    if (!data.city) {
                        this.addressError = true;
                        this.dataErrors[index].city = true;
                    }
                    if (!data.state) {
                        this.addressError = true;
                        this.dataErrors[index].state = true;
                    }
                    if (!data.postal_code) {
                        this.addressError = true;
                        this.dataErrors[index].postal_code = true;
                    }
                });

                if (!this.addressError) {
                    this.errors = {};
                    this.formData = new FormData();
                    this.formData.set('_method', 'PATCH');
                    this.collectFormData(this.model);

                    axios.post(
                        Router.route('update-account', { user: this.user.id }),
                        this.formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            },
                        },
                    ).then(() => {
                        location.href = Router.route('account');
                    }).catch(({ response: { data: { errors } } }) => {
                        this.errors = errors;
                    });
                }
            },
        },

        created() {
            if (!this.model.addresses) {
                this.model.addresses = [];
            } else {
                _.each(this.model.addresses, (data, index) => {
                    this.dataErrors[index] = {
                        name: false,
                        phone: false,
                        address1: false,
                        address2: false,
                        city: false,
                        state: false,
                        postal_code: false,
                    };
                });
            }
        }
    }
</script>
