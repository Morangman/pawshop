<template>
    <order-status-form
        v-if="model"
        :model.sync="model"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteOrderStatus"
    >
    </order-status-form>
</template>

<script>
    import OrderStatusFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            OrderStatusForm: OrderStatusFormComponent,
        },

        props: {
            status: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.status,
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
                    Router.route('admin.order-status.update', { status: this.status.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.order-status.edit', { status: this.status.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteOrderStatus() {
                axios.delete(
                    Router.route('admin.order-status.delete', { status: this.status.id }),
                ).then(() => {
                    location.href = Router.route('admin.order-status.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
