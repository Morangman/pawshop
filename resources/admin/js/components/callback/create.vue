<template>
    <callback-form
        :model.sync="model"
        :errors.sync="errors"
        @submit="store"
    >
    </callback-form>
</template>

<script>
    import CallbackFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            CallbackForm: CallbackFormComponent,
        },

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    name: null,
                    email: null,
                    phone: null,
                    text: null,
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
                    Router.route('admin.callback.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.callback.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
