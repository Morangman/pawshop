<template>
    <task-form
        :model.sync="model"
        :errors.sync="errors"
        @submit="store"
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

        mixins: [FormHelper],

        data() {
            return {
                model: {
                    name: null,
                    category_id: null,
                    data: [],
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
                    Router.route('admin.task.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.task.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },
    };
</script>
