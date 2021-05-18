<template>
    <warehouse-form
        v-if="model"
        :model.sync="model"
        :categories.sync="categories"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteWarehouse"
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

        props: {
            warehouse: {
                type: Object,
                required: true,
            },
            categories: {
                type: Array,
                required: true,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.warehouse,
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
                    Router.route('admin.warehouse.update', { warehouse: this.warehouse.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.warehouse.edit', { warehouse: this.warehouse.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteWarehouse() {
                axios.delete(
                    Router.route('admin.warehouse.delete', { warehouse: this.warehouse.id }),
                ).then(() => {
                    location.href = Router.route('admin.warehouse.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
