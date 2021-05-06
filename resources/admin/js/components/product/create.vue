<template>
    <category-form
        :model.sync="model"
        :categories.sync="categories"
        :prices.sync="prices"
        :premiumprices.sync="premiumprices"
        :faqs.sync="faqs"
        :steps.sync="steps"
        :categorysteps.sync="categorysteps"
        :errors.sync="errors"
        @submit="store"
    >
    </category-form>
</template>

<script>
    import CategoryFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            CategoryForm: CategoryFormComponent,
        },

        props: {
            category: {
                type: Object,
                required: false,
            },
            faqs: {
                type: Array,
                required: true,
            },
            steps: {
                type: Array,
                required: false,
            },
            prices: {
                type: Array,
                required: false,
            },
            premiumprices: {
                type: Array,
                required: false,
            },
            categorysteps: {
                type: Array,
                required: false,
            },
            categories: {
                type: Array,
                required: false,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    name: null,
                    slug: null,
                    image: null,
                    image_url: null,
                    subcategory_id: null,
                    faq_id: null,
                    custom_text: null,
                    premium_price: null,
                    price_for_broken: null,
                    steps: [],
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
