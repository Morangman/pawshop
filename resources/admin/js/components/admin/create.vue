<template>
    <user-form
        :model.sync="model"
        :errors.sync="errors"
        @submit="store"
    >
    </user-form>
</template>

<script>
    import UserFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            UserForm: UserFormComponent,
        },

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    name: null,
                    phone: null,
                    email: null,
                    role: null,
                    password: null,
                    password_confirmation: null,
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
                    Router.route('admin.admin.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.admin.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
