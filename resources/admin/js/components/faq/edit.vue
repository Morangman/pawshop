<template>
    <faq-form
        v-if="model"
        :model.sync="model"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteFaq"
    >
    </faq-form>
</template>

<script>
    import FaqFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            FaqForm: FaqFormComponent,
        },

        props: {
            faq: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.faq,
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
                    Router.route('admin.faq.update', { faq: this.faq.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.faq.edit', { faq: this.faq.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteFaq() {
                axios.delete(
                    Router.route('admin.faq.delete', { faq: this.faq.id }),
                ).then(() => {
                    location.href = Router.route('admin.faq.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
