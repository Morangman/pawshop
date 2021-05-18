<template>
    <warehouse-status-form
        v-if="model"
        :model.sync="model"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteWarehouseStatus"
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
                    Router.route('admin.warehouse-status.update', { warehouse_status: this.status.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.warehouse-status.edit', { warehouse_status: this.status.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteWarehouseStatus() {
                axios.delete(
                    Router.route('admin.warehouse-status.delete', { warehouse_status: this.status.id }),
                ).then(() => {
                    location.href = Router.route('admin.warehouse-status.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
