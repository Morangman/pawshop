<template>
    <product-form
        v-if="model"
        :model.sync="model"
        :categories.sync="categories"
        :recommended.sync="recommended"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteProduct"
    >
    </product-form>
</template>

<script>
    import ProductFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            ProductForm: ProductFormComponent,
        },

        props: {
            product: {
                type: Object,
                required: true,
            },
            categories: {
                type: Array,
                required: true,
            },
            recommended: {
                type: Array,
                required: true,
            }
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.product,
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
                    Router.route('admin.product.update', { product: this.product.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.product.edit', { product: this.product.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteProduct() {
                axios.delete(
                    Router.route('admin.product.delete', { product: this.product.id }),
                ).then(() => {
                    location.href = Router.route('admin.product.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
