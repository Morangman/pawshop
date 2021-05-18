<template>
    <warehouse-status-form
        :model.sync="model"
        :errors.sync="errors"
        @submit="store"
    >
    </warehouse-status-form>
</template>

<script>
    import WarehouseStatusFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            WarehouseStatusForm: WarehouseStatusFormComponent,
        },

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    name: null,
                    color: null,
                    order: null,
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
                    Router.route('admin.warehouse-status.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.warehouse-status.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
