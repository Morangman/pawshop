<template>
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <strong v-if="suspect && suspect.length > 0" class="text-danger date-centered">
                        {{ $t('admin.order.form.suspect_ip') + ' ' + suspect.length + ' ' + $t('common.word.times') }}
                    </strong>
                    <div class="form-group">
                        <a
                            type="submit"
                            class="btn btn-primary"
                            :href="$r('admin.order.barcode', {order: model.id})"
                            target="_blank"
                        >
                            Print barcode
                        </a>
                        <a class="btn btn-primary" :href="$r('thanks', {order_uuid: model.uuid})" target="_blank">Show Thank Page</a>
                    </div>
                    <div class="form-group" v-if="model.user_id">
                        <label>
                            <strong>{{ $t('admin.order.form.user') }}</strong>
                        </label>
                        <a :href="$r('admin.user.edit', {user: model.user_id})">{{ model.user_id }}</a>
                    </div>
                    <div class="form-group" v-if="model.payment.fedexLabel">
                        <label>
                            <strong>{{ $t('admin.order.form.fedex_label') }}</strong>
                        </label>
                        <p><a :href="model.payment.fedexLabel">{{ model.payment.fedexLabel }}</a></p>
                    </div>
                    <div class="form-group" v-if="model.tracking_number">
                        <label>
                            <strong>Fedex tracking number</strong>
                        </label>
                        <br>
                        <a :href="'https://www.fedex.com/fedextrack/?trknbr=' + model.tracking_number" target="_blank">{{ model.tracking_number }}</a>
                    </div>
                    <div class="form-group" v-if="model.estimate_date && !model.delivered_date">
                        <label>
                            <strong>Estimate date of delivery</strong>
                        </label>
                        <br>
                        <p>{{ normalizeDate(model.estimate_date) }}</p>
                    </div>
                    <div class="form-group" v-if="model.delivered_date">
                        <label>
                            <strong>Delivered date</strong>
                        </label>
                        <br>
                        <p>{{ normalizeDate(model.delivered_date) }}</p>
                    </div>
                    <div class="form-group" v-if="model.paid_date">
                        <label>
                            <strong>Pay day</strong>
                        </label>
                        <br>
                        <p>{{ normalizeDate(model.paid_date) }}</p>
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
                            <select name="state" autocomplete="on" type="text" class="form-control" :class="{ 'border-danger': errors['address.state'] }" v-model="model.address.state">
                                <option :value="null">State*</option>
                                <option v-for="(state, i) in states" :key="`state_${i}`" :value="i">{{ state }}</option>
                            </select>
                            <input v-if="!states[model.address.state]" class="form-control" v-model="model.address.state"/>
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

                    <div v-if="model.comment" class="form-group">
                        <label>
                            <strong>Order comment</strong>
                        </label>
                        <p>{{ model.comment }}</p>
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
                        <div class="change-blocks-wrapper__item" v-for="(product, index) in model.orders.order" :key="`product__${index}`">
                            <div class="text-right mb-2">
                                <a href="javascript:void(0)" class="text-danger" v-on:click="deleteOrderedProduct(index)" :title="$t('common.word.remove')">
                                    <i class="icon-bin"></i>
                                </a>
                            </div>
                            <div class="form-group">
                                <select class="form-control selectpicker" v-model="product.id" v-on:change="selectProduct(index, product.id)" required>
                                    <option v-for="(item, index) in products" :value="item.id" :key="`product_item__${index}`">{{ item.name }}</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <div class="form-group col-md-6">
                                    <input
                                        name="ordered_product-imei"
                                        type="text"
                                        placeholder="IMEI"
                                        v-model="product.imei"
                                        class="form-control"
                                    >
                                </div>
                                <div class="form-group col-md-6">
                                    <input
                                        name="ordered_product-serial"
                                        type="text"
                                        placeholder="Serial Number"
                                        v-model="product.serial"
                                        class="form-control"
                                    >
                                </div>
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

                            <div class="form-group row">
                                <div class="form-group col-md-6">
                                    Is device locked:
                                    <input type="checkbox" id="scales" name="scales" v-model="product.is_locked">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="form-group col-md-6">
                                    <label>
                                        <strong>Delivey Price</strong>
                                    </label>
                                    <input
                                        name="delivery_price"
                                        type="text"
                                        placeholder="Delivey Price"
                                        v-model="product.delivery_price"
                                        class="form-control"
                                    >
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        <strong>Repairs Price</strong>
                                    </label>
                                    <input
                                        name="repairs_price"
                                        type="text"
                                        placeholder="Repairs Price"
                                        v-model="product.repairs_price"
                                        class="form-control"
                                    >
                                </div>
                                <div class="form-group col-md-6">
                                    <label>
                                        <strong>Sell Price</strong>
                                    </label>
                                    <input
                                        name="sell_price"
                                        type="text"
                                        placeholder="Sell Price"
                                        v-model="product.sell_price"
                                        class="form-control"
                                    >
                                </div>
                            </div>

                            <hr>
                            
                            <div class="form-group" v-if="product.steps && !stepsData[index+product.id]">
                                <div v-for="(step, stepKey) in product.steps" class="form-group" :key="`product_step__${stepKey}`">
                                    <div v-if="step" class="lex-row">
                                        <label>
                                            <strong>{{ step.step_name ? step.step_name.name + ': ' : '' }}</strong>
                                        </label>
                                        <div style="display: flex; align-items: center;">
                                            <select class="form-control flex-input" v-model="step.id" required>
                                                <option v-for="(item, index) in step.variations" :value="item.id" :key="`sorted_product_step__${index}`">{{ item.value }}</option>
                                            </select>
                                            <a v-if="step.step_name ? step.step_name.is_checkbox : false" style="margin-left: 10px;" class="text-danger"  href="javascript:0" v-on:click="deleteProductStep(index, stepKey)"><i class="icon-bin"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group" v-if="stepsData[index+product.id]">
                                <div class="admin-steps">
                                    <p>{{ selectedStep[index].title }}</p>
                                    <div class="options">
                                        <div class="options-radio" v-for="(option, key) in selectedStep[index].items" :key="`step${option.id}_${key}`">
                                            <label class="radiobox-block" v-if="!selectedStep[index].is_checkbox">
                                                <input v-model="selectedSteps[option.step_name.id]" :checked="!!selectedStep[index].items[key].checked" :value="option" type="radio" :name="`step-${selectedStep[index].title}-radios`">
                                                <i></i>
                                                <span>
                                                    <strong>{{ option.value }}</strong>
                                                    <small>{{ option.decryption }}</small>
                                                </span>
                                            </label>
                                            <label class="checkbox-block" v-if="selectedStep[index].is_checkbox">
                                                <input v-model="selectedSteps" type="checkbox" v-bind:value="option"  :name="`step-${selectedStep[index].title}-checkbox`">
                                                <i></i>
                                                <span>
                                                    <strong>{{ option.value }}</strong>
                                                    <small>{{ option.decryption }}</small>
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button v-on:click="backStep(index, product.id)" class="btn btn-info">Back</button>
                                    <button v-on:click="nextStep(index, product.id)" class="btn btn-info">Next step</button>
                                </div>
                            </div>
                            <div class="text-right mb-2" v-if="product.device.name">
                                <a :href="'https://www.ebay.com/sch/i.html?_nkw=' + product.device.name" target="_blank">
                                    <img style="height:30px; width: auto;" :src="'../../../../client/images/ebay.png'" />
                                </a>
                                <button
                                    v-on:click="updateOrderProduct(index)"
                                    class="btn btn-primary margin-top-10"
                                >{{ $t('common.word.update') }}</button>
                            </div>
                        </div>
                        <div class="text-right">
                            <button
                                v-on:click="addOrderItem"
                                class="btn btn-primary margin-top-10"
                            >{{ $t('common.word.add') }}</button>
                        </div>
                        <div v-for="(error, i) in errors.orders"
                             :key="`orders_product__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                        <div class="flex-row">
                            <label>
                                <strong>{{ $t('admin.order.form.summ') }}: </strong>
                            </label>
                            <input
                                v-if="model.id"
                                name="total_summ"
                                type="text"
                                v-model="model.total_summ"
                                class="form-control flex-input"
                            >
                        </div>
                        <div class="flex-row">
                            <label>
                                <strong>{{ $t('admin.order.form.custom_summ') }}: </strong>
                            </label>
                            <input
                                v-if="model.id"
                                name="custom_summ"
                                type="text"
                                v-model="model.custom_summ"
                                class="form-control flex-input"
                            >
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Payment method:</strong>
                        </label>
                        <p>{{ model.payment.name }}</p>
                        <p>{{ model.payment.email }}</p>
                    </div>
                    <div class="form-group" v-if="model.exp_service">
                        <label>
                            <strong>Expedited Service (-20$):</strong>
                        </label>
                        <p>{{ model.exp_service }}</p>
                    </div>
                    <div class="form-group" v-if="model.insurance">
                        <label>
                            <strong>Shipping insurance (-1%):</strong>
                        </label>
                        <p>{{ model.insurance }}</p>
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
                            <option v-for="(status, i) in statuses" :key="`order_status__${i}`" :value="status.id">{{ status.name }}</option>
                        </select>
                        <div v-for="(error, i) in errors.ordered_status"
                             :key="`ordered_status_product__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="flex-row" v-if="model.orders.changed">
                        <label>
                            <strong>Approval of changes:</strong>
                        </label>
                        <a v-if="parseInt(model.orders.confirmed) === 2"><p style="color:green;">{{ $t('admin.order.confirm_statuses.' + model.orders.confirmed) }} <i class="icon-checkmark"></i></p></a>
                        <a v-if="parseInt(model.orders.confirmed) === 1"><p style="color:red;">{{ $t('admin.order.confirm_statuses.' + model.orders.confirmed) }} <i class="icon-hour-glass"></i></p></a>
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
            bcode: {
                type: String,
                required: true,
            },
            products: {
                type: Array,
                required: true,
            },
            steps: {
                type: Object,
                required: true,
            },
            states: {
                type: Object,
                required: true,
            },
            statuses: {
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
                selectedStep: [],
                selectedSteps: [],
                stepsData: [],
                stepIndex: 0,
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
                delete this.model.orders;

                console.log(this.model);

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

            nextStep(key, id) {
                this.stepIndex++;

                let dataSteps = this.stepsData[key+id][this.stepIndex];

                if (dataSteps) {
                    this.selectedStep[key] = dataSteps;
                } else {
                    this.model.orders.order[key].steps = this.selectedSteps;

                    this.updateOrder({order: this.model.orders.order[key]});
                }

                this.$forceUpdate();
            },

            backStep(key, id) {
                if (this.stepIndex > 0) {
                    this.stepIndex--;

                    let dataSteps = this.stepsData[key+id][this.stepIndex];

                    if (dataSteps) {
                        this.selectedStep[key] = dataSteps;
                    }
                }

                this.$forceUpdate();
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
                window.swal({
                    title: this.$t('common.phrase.confirm.title'),
                    text: this.$t('common.phrase.confirm.body'),
                    icon: 'warning',
                    buttons: [this.$t('common.word.cancel'), this.$t('common.word.confirm')],
                }).then((result) => {
                    if (!result) {
                        return
                    }

                    let productData = {key: index};

                    this.errors = {};
                    this.formData = new FormData();
                    this.formData.set('_method', 'PATCH');
                    this.collectFormData(productData);

                    axios.post(
                        Router.route('admin.order.delete-order-product', { order: this.model.id }),
                        this.formData,
                        {
                            headers: {
                                'Content-Type': 'multipart/form-data',
                            },
                        },
                    ).then(() => {
                        location.href = Router.route('admin.order.edit', { order: this.model.id });
                    }).catch(({ response: { data: { errors } } }) => {
                        this.errors = errors;
                        this.scrollToError();
                    });
                });
            },

            deleteProductStep(order, step) {
                this.model.orders.order[order].steps[step] = null;

                this.summOrderedProducts();
            },

            summOrderedProducts() {
                this.summOrdered = null;

                _.each(this.model.ordered_product, (key, value) => {
                    this.summOrdered += Number(key.price) * Number(key.quantity);
                });
            },

            addOrderItem() {
                this.model.orders.order.push({
                    ctn: 1,
                    device: [],
                    id: null,
                    steps: [],
                    summ: 0,
                    total: 0,
                });
            },

            updateOrder(data) {
                this.errors = {};
                this.formData = new FormData();
                this.formData.set('_method', 'PATCH');
                this.collectFormData(data);

                axios.post(
                    Router.route('admin.order.update-order', { order: this.model.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.order.edit', { order: this.model.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            updateOrderProduct(key) {
                let productData = {
                    order: this.model.orders.order[key],
                    key: key,
                };

                this.errors = {};
                this.formData = new FormData();
                this.formData.set('_method', 'PATCH');
                this.collectFormData(productData);

                axios.post(
                    Router.route('admin.order.update-order-product', { order: this.model.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.order.edit', { order: this.model.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },


            selectProduct(key, id) {
                this.selectedSteps = [];
                this.stepsData = [];
                this.selectedStep = [];

                axios.get(
                    Router.route('admin.order.get-product', { product: id }),
                ).then((data) => {
                    this.stepsData[key+id] = data.data.steps;
                    this.model.orders.order[key].device = data.data.device;

                    this.selectedStep[key] = data.data.steps[0];

                    this.$forceUpdate();
                }).catch((error) => {
                    //console.log(error);
                });
            },

            normalizeDate(date) {
                return moment(date).format("DD.MM.YYYY hh:mm");
            },
        }
    };
</script>
