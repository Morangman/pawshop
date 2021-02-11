<template>
    <tip-form
        :model.sync="model"
        :errors.sync="errors"
        @submit="store"
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

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    name: null,
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
                    Router.route('admin.tip.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.tip.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
