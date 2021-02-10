<template>
    <order-form
        v-if="model"
        :model.sync="model"
        :products.sync="products"
        :suspect.sync="suspect"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteOrder"
    >
    </order-form>
</template>

<script>
    import OrderFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            OrderForm: OrderFormComponent,
        },

        props: {
            order: {
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
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.order,
                errors: {},
                formData: null,
            };
        },

        methods: {
            update(data) {
                this.errors = {};
                this.formData = new FormData();
                this.formData.set('_method', 'PATCH');
                this.collectFormData(data);

                axios.post(
                    Router.route('admin.order.update', { order: this.order.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.order.edit', { order: this.order.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteOrder() {
                axios.delete(
                    Router.route('admin.order.delete', { order: this.order.id }),
                ).then(() => {
                    location.href = Router.route('admin.order.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
