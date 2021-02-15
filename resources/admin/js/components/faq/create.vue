<template>
    <faq-form
        :model.sync="model"
        :errors.sync="errors"
        @submit="store"
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

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    name: null,
                    category_id: null,
                    data: [],
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
                    Router.route('admin.faq.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.faq.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
