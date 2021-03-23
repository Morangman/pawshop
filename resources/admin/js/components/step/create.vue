<template>
    <step-form
        :model.sync="model"
        :tips.sync="tips"
        :errors.sync="errors"
        @submit="store"
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
            tips: {
                type: Array,
                required: false,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    name: null,
                    tip_id: null,
                    steps: [],
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
                    Router.route('admin.step.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.step.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
