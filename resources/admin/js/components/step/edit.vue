<template>
    <step-form
        v-if="model"
        :model.sync="model"
        :steps.sync="steps"
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
            steps: {
                type: Array,
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
                    Router.route('admin.step.update', { stepName: this.step.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.step.edit', { stepName: this.step.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteStep() {
                axios.delete(
                    Router.route('admin.step.delete', { stepName: this.step.id }),
                ).then(() => {
                    location.href = Router.route('admin.step.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
