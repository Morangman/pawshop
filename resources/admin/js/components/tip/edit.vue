<template>
    <tip-form
        v-if="model"
        :model.sync="model"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteStep"
    >
    </tip-form>
</template>

<script>
    import TipFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            TipForm: TipFormComponent,
        },

        props: {
            tip: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.tip,
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
                    Router.route('admin.tip.update', { tip: this.tip.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.tip.edit', { tip: this.tip.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteStep() {
                axios.delete(
                    Router.route('admin.tip.delete', { tip: this.tip.id }),
                ).then(() => {
                    location.href = Router.route('admin.tip.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
