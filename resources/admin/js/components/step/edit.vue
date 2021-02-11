<template>
    <step-form
        v-if="model"
        :model.sync="model"
        :tips.sync="tips"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteStep"
    >
    </step-form>
</template>

<script>
    import StepFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            StepForm: StepFormComponent,
        },

        props: {
            step: {
                type: Object,
                required: true,
            },
            tips: {
                type: Array,
                required: false,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.step,
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
                    Router.route('admin.step.update', { step: this.step.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.step.edit', { step: this.step.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteStep() {
                axios.delete(
                    Router.route('admin.step.delete', { step: this.step.id }),
                ).then(() => {
                    location.href = Router.route('admin.step.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
