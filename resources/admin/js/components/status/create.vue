<template>
    <order-status-form
        :model.sync="model"
        :errors.sync="errors"
        @submit="store"
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

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    name: null,
                    color: null,
                    order: null,
                    fedex_status: null,
                },
                errors: {},
                formData: null,
            };
        },

        methods: {
            store(data) {
                this.errors = {};
                this.formData = new FormData();
                this.collectFormData(data);

                axios.post(
                    Router.route('admin.order-status.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.order-status.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
