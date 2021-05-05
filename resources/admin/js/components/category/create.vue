<template>
    <category-form
        :model.sync="model"
        :categories.sync="categories"
        :faqs.sync="faqs"
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
                    Router.route('admin.category.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.category.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
