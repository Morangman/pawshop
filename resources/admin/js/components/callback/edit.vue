<template>
    <callback-form
        v-if="model"
        :model.sync="model"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteCallback"
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

        props: {
            callback: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.callback,
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
                    Router.route('admin.callback.update', { callback: this.callback.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.callback.edit', { callback: this.callback.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteCallback() {
                axios.delete(
                    Router.route('admin.callback.delete', { callback: this.callback.id }),
                ).then(() => {
                    location.href = Router.route('admin.callback.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
