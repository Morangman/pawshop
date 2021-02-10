<template>
    <product-form
        :model.sync="model"
        :categories.sync="categories"
        :recommended.sync="recommended"
        :errors.sync="errors"
        @submit="store"
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
                model: {
                    title: null,
                    category_id: null,
                    general_info: null,
                    price: null,
                    old_price: null,
                    image: null,
                    variations: [],
                    recommended_products: [],
                    recommended_images: null,
                    seo: {
                        title: null,
                        meta: null,
                        keywords: null,
                        image: null,
                    },
                    is_hidden: null,
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
                    Router.route('admin.product.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.product.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
