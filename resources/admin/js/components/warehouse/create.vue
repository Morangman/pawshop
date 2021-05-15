<template>
    <warehouse-form
        :model.sync="model"
        :errors.sync="errors"
        @submit="store"
    >
    </warehouse-form>
</template>

<script>
    import WarehouseFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            WarehouseForm: WarehouseFormComponent,
        },

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    category_id: null,
                    order_id: null,
                    status: null,
                    product_name: null,
                    imei: null,
                    serial_number: null,
                    price: null,
                    clear_price: null,
                    is_locked: null,
                    delivery_price: null,
                    repair_price: null,
                    sell_price: null,
                    steps: null,
                    images: [],
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
                    Router.route('admin.warehouse.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.warehouse.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
