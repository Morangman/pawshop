<template>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <strong v-if="suspect && suspect.length > 0" class="text-danger date-centered">
                        {{ $t('admin.order.form.suspect_ip') + ' ' + suspect.length + ' ' + $t('common.word.times') }}
                    </strong>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.order.form.name') }}</strong>
                        </label>
                        <input
                            name="name"
                            type="text"
                            v-model="model.name"
                            class="form-control"
                            :class="{ 'border-danger': errors.name }"
                        >
                        <div v-for="(error, i) in errors.name"
                             :key="`name__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.order.form.phone') }}</strong>
                        </label>
                        <input
                            name="phone"
                            type="text"
                            v-model="model.phone"
                            class="form-control"
                            :class="{ 'border-danger': errors.phone }"
                        >
                        <div v-for="(error, i) in errors.phone"
                             :key="`phone__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.order.form.email') }}</strong>
                        </label>
                        <input
                            name="email"
                            type="text"
                            v-model="model.email"
                            class="form-control"
                            :class="{ 'border-danger': errors.email }"
                        >
                        <div v-for="(error, i) in errors.email"
                             :key="`email__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
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
                            <strong>{{ $t('admin.order.form.ordered_product') }}: <span v-if="model.id">{{ model.ordered_product.length }}</span></strong>
                        </label>
                        <div class="change-blocks-wrapper__item" v-for="(product, index) in model.ordered_product">
                                <div class="text-right mb-2">
                                    <a href="#" class="text-danger" v-on:click="deleteOrderedProduct(index)" :title="$t('common.word.remove')">
                                        <i class="icon-bin"></i>
                                    </a>
                                </div>
                                <div class="form-group">
                                    <select class="form-control selectpicker" v-model="selectedOrderedProduct[index]" v-on:change="selectOrderedProduct(index)" required>
                                        <optgroup v-for="(category, index) in products" :label="index">
                                            <option v-for="(product, index) in category" :value="product">{{ product.title }}</option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <div class="form-group col-md-4" v-if="selectedOrderedProduct[index]">
                                        <select class="form-control" name="ordered_product-product_color" v-model="selectedOrderedProductColor[index]" v-on:change="selectOrderedProductColor(index)" required>
                                            <option v-for="(variation, i) in selectedOrderedProduct[index].variations" :value="variation">{{ variation.color_name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <input
                                            name="ordered_product-quantity"
                                            type="text"
                                            :placeholder="$t('admin.order.form.ordered_products.quantity')"
                                            v-model="product.quantity"
                                            class="form-control"
                                        >
                                    </div>
                                    <div class="form-group col-md-5">
                                        <div class="input-group">
                                            <input
                                                type="text"
                                                class="form-control price-summ product-order-list__item"
                                                name="ordered_product-price"
                                                :placeholder="$t('admin.order.form.ordered_products.price')"
                                                v-model="product.price"
                                            >
                                            <div class="input-group-append">
                                                <span class="input-group-text" id="basic-addon2">грн.</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div v-for="(error, i) in errors.ordered_product"
                             :key="`ordered_product__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                        <label>
                            <strong>{{ $t('admin.order.form.summ') }}: <span v-if="summOrdered">{{ summOrdered }}</span></strong>
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
                type: Object,
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
        },

        created() {
            if (this.model.id) {
                _.each(this.model.ordered_product, (key, value) => {
                    this.selectedOrderedProduct.push({
                        id: key.product.id,
                        title: key.product.title,
                        variations: key.product.variations,
                    });
                    this.selectedOrderedProductColor.push(key.variation);
                });
            } else {
                this.model.ordered_product.push({
                    product_title: null,
                    product_color: null,
                    quantity: null,
                    price: null,
                    product: {},
                    variation: {},
                });
            }

            this.summOrderedProducts();
        }
    };
</script>
