<template>
    <comment-form
        :model.sync="model"
        :errors.sync="errors"
        @submit="store"
    >
    </comment-form>
</template>

<script>
    import CommentFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            CommentForm: CommentFormComponent,
        },

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    name: null,
                    email: null,
                    text: null,
                    value: null,
                    is_hidden: null,
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
                    Router.route('admin.comment.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.comment.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
