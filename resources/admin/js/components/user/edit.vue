<template>
    <user-form
        v-if="model"
        :model.sync="model"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteUser"
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

        props: {
            user: {
                type: Object,
                required: true,
            },
            role: {
                type: Array,
                required: true,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.user,
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
                    Router.route('admin.user.update', { user: this.user.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.user.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteUser() {
                axios.delete(
                    Router.route('admin.user.delete', { user: this.user.id }),
                ).then(() => {
                    location.href = Router.route('admin.user.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },

        created() {
            this.model.role = _.head(this.role).name;
        },
    };
</script>
