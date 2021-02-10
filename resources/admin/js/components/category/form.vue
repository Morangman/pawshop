<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.category.form.name') }}</strong>
                        </label>
                        <input
                            name="name"
                            type="text"
                            v-model="model.name"
                            class="form-control"
                            :class="{ 'border-danger': errors.name }"
                        >
                        <div v-for="(error, i) in errors.name"
                             :key="`name__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.category.form.image') }}</strong>
                        </label>
                        <b-form-file
                            class="mt-1"
                            accept=".png,.jpg,.jpeg,.gif"
                            @change="showCategoryPreviewImage($event)"
                        ></b-form-file>
                        <img width="auto"
                             height="100"
                             class="center-image"
                             v-if="categoryPreviewImage"
                             :src="categoryPreviewImage"
                             v-on:click="deleteCategoryImage"
                        >
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.category.form.subcategory') }}</strong>
                        </label>

                        <model-list-select :list="categories"
                                           v-model="model.subcategory_id"
                                           option-value="id"
                                           :custom-text="name"
                                           placeholder="select item">
                        </model-list-select>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.category.form.text') }}</strong>
                        </label>
                        <input
                            name="custom_text"
                            type="text"
                            v-model="model.custom_text"
                            class="form-control"
                            :class="{ 'border-danger': errors.custom_text }"
                        >
                        <div v-for="(error, i) in errors.custom_text"
                             :key="`custom_text__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.category.form.status') }}</strong>
                        </label>
                        <b-form-checkbox
                            v-model="model.is_hidden"
                            value="1"
                            unchecked-value="0"
                        >
                            {{ $t('admin.category.form.hidden') }}
                        </b-form-checkbox>
                        <div v-for="(error, i) in errors.is_hidden"
                             :key="`is_hidden__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <template v-if="model.id">
                    <button
                        type="submit"
                        class="btn btn-danger"
                        @click.prevent="deleteCategory"
                    >
                        {{ $t('common.word.delete') }}
                    </button>
                </template>
                <button
                    type="submit"
                    class="btn btn-primary"
                    @click.prevent="submit"
                >
                    {{ $t('common.word.save') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import FormHelper from '../../mixins/form_helper';
    import { ModelListSelect } from 'vue-search-select'

    export default {
        props: {
            model: {
                type: Object,
                required: true,
            },
            categories: {
                type: Array,
                required: false,
            },
            errors: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
                categoryPreviewImage: null,
            };
        },

        mixins: [FormHelper],

        components: {
            ModelListSelect
        },

        methods: {
            submit() {
                this.$emit('submit', this.model);
            },

            deleteCategory() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },

            showCategoryPreviewImage(event) {
                const file = event.target.files[0];

                this.model.image = file;

                this.categoryPreviewImage = URL.createObjectURL(file);

                this.$forceUpdate();
            },

            deleteCategoryImage() {
                this.model.image = null;
                this.categoryPreviewImage = null;

                notify.success(
                    this.$t('admin.category.messages.image_delete')
                );
            },

            name (item) {
                return `${item.name}`
            },
        },

        created() {
            if (this.model.id) {
                this.model.is_hidden = Number(this.model.is_hidden);
            }

            this.categoryPreviewImage = this.model.image;
        },
    };
</script>
