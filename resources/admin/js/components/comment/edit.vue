<template>
    <comment-form
        v-if="model"
        :model.sync="model"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteComment"
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

        props: {
            comment: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.comment,
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
                    Router.route('admin.comment.update', { comment: this.comment.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.comment.edit', { comment: this.comment.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteComment() {
                axios.delete(
                    Router.route('admin.comment.delete', { comment: this.comment.id }),
                ).then(() => {
                    location.href = Router.route('admin.comment.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
