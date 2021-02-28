<template>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <strong v-if="suspect && suspect.length > 0" class="text-danger date-centered">
                        {{ $t('admin.order.form.suspect_ip') + ' ' + suspect.length + ' ' + $t('common.word.times') }}
                    </strong>
                    <div class="form-group" v-if="model.user_id">
                        <label>
                            <strong>{{ $t('admin.order.form.user') }}</strong>
                        </label>
                        <a :href="$r('admin.user.edit', {user: model.user_id})">{{ model.user_id }}</a>
                        <div v-for="(error, i) in errors.user"
                             :key="`user__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="form-group col-md-6">
                            <label>
                                <strong>{{ $t('admin.order.form.name') }}</strong>
                            </label>
                            <input
                                name="name"
                                type="text"
                                v-model="model.address.name"
                                class="form-control"
                                :class="{ 'border-danger': errors['address.name'] }"
                            >
                            <div v-for="(error, i) in errors['address.name']"
                                 :key="`address__error__${i}`"
                                 class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>
                                <strong>{{ $t('admin.order.form.phone') }}</strong>
                            </label>
                            <input
                                name="phone"
                                type="text"
                                v-model="model.address.phone"
                                class="form-control"
                                :class="{ 'border-danger': errors['address.phone'] }"
                            >
                            <div v-for="(error, i) in errors['address.phone']"
                                 :key="`address__error__${i}`"
                                 class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group col-md-6">
                            <label>
                                <strong>{{ $t('admin.order.form.email') }}</strong>
                            </label>
                            <input
                                name="email"
                                type="text"
                                v-model="model.user_email"
                                class="form-control"
                                :class="{ 'border-danger': errors.address }"
                            >
                            <div v-for="(error, i) in errors.address"
                                 :key="`address__error__${i}`"
                                 class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>
                                <strong>{{ $t('admin.order.form.city') }}</strong>
                            </label>
                            <input
                                name="city"
                                type="text"
                                v-model="model.address.city"
                                class="form-control"
                                :class="{ 'border-danger': errors['address.city'] }"
                            >
                            <div v-for="(error, i) in errors['address.city']"
                                 :key="`address__error__${i}`"
                                 class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <div class="form-group col-md-6">
                            <label>
                                <strong>{{ $t('admin.order.form.state') }}</strong>
                            </label>
                            <input
                                name="state"
                                type="text"
                                v-model="model.address.state"
                                class="form-control"
                                :class="{ 'border-danger': errors['address.state'] }"
                            >
                            <div v-for="(error, i) in errors['address.state']"
                                 :key="`address__error__${i}`"
                                 class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>
                                <strong>{{ $t('admin.order.form.postal_code') }}</strong>
                            </label>
                            <input
                                name="postal_code"
                                type="text"
                                v-model="model.address.postal_code"
                                class="form-control"
                                :class="{ 'border-danger': errors['address.postal_code'] }"
                            >
                            <div v-for="(error, i) in errors['address.postal_code']"
                                 :key="`address__error__${i}`"
                                 class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="form-group col-md-6">
                            <label>
                                <strong>{{ $t('admin.order.form.address_1') }}</strong>
                            </label>
                            <input
                                name="address1"
                                type="text"
                                v-model="model.address.address1"
                                class="form-control"
                                :class="{ 'border-danger': errors['address.address1'] }"
                            >
                            <div v-for="(error, i) in errors['address.address1']"
                                 :key="`address__error__${i}`"
                                 class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label>
                                <strong>{{ $t('admin.order.form.address_2') }}</strong>
                            </label>
                            <input
                                name="address2"
                                type="text"
                                v-model="model.address.address2"
                                class="form-control"
                                :class="{ 'border-danger':  errors['address.address2'] }"
                            >
                            <div v-for="(error, i) in errors['address.address2']"
                                 :key="`address__error__${i}`"
                                 class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.order.form.notes') }}</strong>
                        </label>
                        <textarea
                            name="notes"
                            type="text"
                            rows="7"
                            v-model="model.notes"
                            class="form-control"
                            :class="{ 'border-danger': errors.notes }"
                        ></textarea>
                        <div v-for="(error, i) in errors.notes"
                             :key="`notes__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.order.form.ordered_product') }}: <span v-if="model.id">{{ model.orders.length }}</span></strong>
                        </label>
                        <div class="change-blocks-wrapper__item" v-for="(product, index) in model.orders.order">
                                <div class="text-right mb-2">
                                    <a href="#" class="text-danger" v-on:click="deleteOrderedProduct(index)" :title="$t('common.word.remove')">
                                        <i class="icon-bin"></i>
                                    </a>
                                </div>
                                <div class="form-group">
                                    <select class="form-control selectpicker" v-model="product.id" required>
                                        <option v-for="(item, index) in products" :value="item.id">{{ item.name }}</option>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-md-6">
                                        <input
                                            name="ordered_product-quantity"
                                            type="text"
                                            :placeholder="$t('admin.order.form.ordered_products.quantity')"
                                            v-model="product.ctn"
                                            class="form-control"
                                        >
                                    </div>
                                    <div class="form-group col-md-6">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control price-summ product-order-list__item"
                                                name="ordered_product-price"
                                                :placeholder="$t('admin.order.form.ordered_products.price')"
                                                v-model="product.total"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">$</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div v-for="(error, i) in errors.orders"
                             :key="`orders_product__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                        <label>
                            <strong>{{ $t('admin.order.form.summ') }}: <span v-if="model.id">{{ parseFloat(model.total_summ).toFixed(2) }}</span></strong>
                        </label>
                        <div class="text-right">
                            <button
                                v-on:click="addOrderedProduct"
                                class="btn btn-primary margin-top-10"
                            >{{ $t('common.word.add') }}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.order.form.ordered_status') }}</strong>
                        </label>
                        <select class="form-control" name="ordered_status" v-model="model.ordered_status" required :class="{ 'border-danger': errors.ordered_status }">
                            <option v-for="(status, i) in $t('admin.order.order_statuses')" :value="i">{{ status }}</option>
                        </select>
                        <div v-for="(error, i) in errors.ordered_status"
                             :key="`ordered_status_product__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="text-center date-centered">
                        <table class="table table-bordered table-t5px-b10px">
                            <tbody><tr class="w-50percent text-333 small">
                                <th class="p-all-5px">{{ $t('admin.order.form.order_number') }}:</th>
                                <td class="text-666">{{ model.id }}</td>
                            </tr>
                            <tr class="w-50percent text-333 small">
                                <th class="p-all-5px">{{ $t('admin.order.form.created_at') }}:</th>
                                <td class="text-666">{{ normalizeDate(this.model.created_at) }}</td>
                            </tr>
                            <tr class="w-50percent text-333 small">
                                <th class="p-all-5px">{{ $t('admin.order.form.updated_at') }}:</th>
                                <td class="text-666">{{ normalizeDate(this.model.updated_at) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <h4 class="mt-2">{{ $t('admin.order.form.reminder.name') }}</h4>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.order.form.reminder.title') }}</strong>
                        </label>
                        <input
                            name="reminder-title"
                            type="text"
                            v-model="reminder.title"
                            class="form-control"
                        >
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.order.form.reminder.text') }}</strong>
                        </label>
                        <textarea
                            name="reminder-text"
                            type="text"
                            rows="4"
                            v-model="reminder.text"
                            class="form-control"
                        ></textarea>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.order.form.reminder.date') }}</strong>
                        </label>
                        <datetime
                            id="reminder-date"
                            :placeholder="$t('admin.order.form.reminder.date')"
                            input-class="form-control"
                            type="date"
                            v-model="reminder.date"
                        ></datetime>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <template v-if="model.id">
                    <button
                        v-if="model.ip_address"
                        type="submit"
                        class="btn btn-danger"
                        @click.prevent="addIpToSuspect"
                    >
                        {{ $t('admin.order.form.add_suspect_ip') }}
                    </button>
                    <button
                        type="submit"
                        class="btn btn-danger"
                        @click.prevent="deleteOrder"
                    >
                        {{ $t('common.word.delete') }}
                    </button>
                </template>
                <button
                    type="submit"
                    class="btn btn-primary"
                    @click.prevent="submit"
                >
                    {{ $t('common.word.save') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import FormHelper from '../../mixins/form_helper';
    import moment from 'moment';

    export default {
        props: {
            model: {
                type: Object,
                required: true,
            },
            products: {
                type: Array,
                required: true,
            },
            suspect: {
                type: Array,
                required: false,
            },
            errors: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                selectedOrderedProduct: [],
                selectedOrderedProductColor: [],
                summOrdered: null,
                reminder: {
                    title: null,
                    text: null,
                    date: null,
                },
            };
        },

        watch: {
            model: {
                handler() {
                    this.summOrderedProducts();
                },
                deep: true,
            },
        },

        methods: {
            submit() {
                _.assign(this.model, { reminder: this.reminder });

                this.$emit('submit', this.model);
            },

            deleteOrder() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },

            addIpToSuspect() {
                _.assign(this.model, { suspect_ip: this.model.ip_address });

                this.$emit('submit', this.model);
            },

            addOrderedProduct() {
                this.model.ordered_product.push({
                    product_title: null,
                    product_color: null,
                    quantity: null,
                    price: null,
                    product: {},
                    variation: {},
                });
            },

            deleteOrderedProduct(index) {
                this.model.ordered_product.splice(index, 1);
                this.selectedOrderedProduct.splice(index, 1);
                this.selectedOrderedProductColor.splice(index, 1);

                if (this.selectedOrderedProduct.length < 1) {
                    this.model.ordered_product = [];
                }

                this.summOrderedProducts();
            },

            selectOrderedProduct(index) {
                this.model.ordered_product[index].product_title = this.selectedOrderedProduct[index].title;
                this.model.ordered_product[index].product = this.selectedOrderedProduct[index];
            },

            selectOrderedProductColor(index) {
                this.model.ordered_product[index].product_color = this.selectedOrderedProductColor[index].color_name;
                this.model.ordered_product[index].quantity = 1;
                this.model.ordered_product[index].price = this.selectedOrderedProductColor[index].price;
                this.model.ordered_product[index].variation = this.selectedOrderedProductColor[index];

                this.summOrderedProducts();
            },

            summOrderedProducts() {
                this.summOrdered = null;

                _.each(this.model.ordered_product, (key, value) => {
                    this.summOrdered += Number(key.price) * Number(key.quantity);
                });
            },

            normalizeDate(date) {
                return moment(date).format("DD.MM.YYYY hh:mm");
            },
        }
    };
</script>
