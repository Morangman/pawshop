<template>
    <coupon-form
        :model.sync="model"
        :categories.sync="categories"
        :errors.sync="errors"
        @submit="store"
    >
    </coupon-form>
</template>

<script>
    import CouponFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            CouponForm: CouponFormComponent,
        },

        props: {
            categories: {
                type: Array,
                required: true,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    category_id: null,
                    name: null,
                    code: null,
                    percent_value: null,
                    text: null,
                    is_hidden: null,
                    start_date: null,
                    end_date: null,
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
                    Router.route('admin.coupon.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.coupon.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
