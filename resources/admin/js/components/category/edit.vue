<template>
    <category-form
        v-if="model"
        :model.sync="model"
        :categories.sync="categories"
        :errors.sync="errors"
        @submit="update"
        @delete="deleteCategory"
    >
    </category-form>
</template>

<script>
    import CategoryFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';

    export default {
        components: {
            CategoryForm: CategoryFormComponent,
        },

        props: {
            category: {
                type: Object,
                required: true,
            },
            categories: {
                type: Array,
                required: false,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                model: this.category,
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
                    Router.route('admin.category.update', { category: this.category.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.category.edit', { category: this.category.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteCategory() {
                axios.delete(
                    Router.route('admin.category.delete', { category: this.category.id }),
                ).then(() => {
                    location.href = Router.route('admin.category.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },
        },
    };
</script>
