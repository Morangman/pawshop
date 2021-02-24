<template>
    <task-form
        v-if="model"
        :model.sync="model"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteTask"
    >
    </task-form>
</template>

<script>
    import TaskFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            TaskForm: TaskFormComponent,
        },

        props: {
            task: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.task,
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
                    Router.route('admin.task.update', { task: this.task.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.task.edit', { task: this.task.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteTask() {
                axios.delete(
                    Router.route('admin.task.delete', { task: this.task.id }),
                ).then(() => {
                    location.href = Router.route('admin.task.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
