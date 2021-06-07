<template>
    <coupon-form
        v-if="model"
        :model.sync="model"
        :categories.sync="categories"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteCoupon"
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
            coupon: {
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
                model: this.coupon,
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
                    Router.route('admin.coupon.update', { coupon: this.coupon.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.coupon.edit', { coupon: this.coupon.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteCoupon() {
                axios.delete(
                    Router.route('admin.coupon.delete', { coupon: this.coupon.id }),
                ).then(() => {
                    location.href = Router.route('admin.coupon.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
