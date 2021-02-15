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
                            <strong>{{ $t('admin.category.form.faq') }}</strong>
                        </label>

                        <model-list-select :list="faqs"
                                           v-model="model.faq_id"
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

                    <div class="form-group">
                        <strong>
                            <h1>{{ $t('admin.category.form.steps') }}</h1>
                        </strong>
                        <div class="form-group">
                            <div class="change-blocks-wrapper__item" v-for="(item, index) in model.steps">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-right">
                                            <a href="javascript:void(0)" class="text-danger" v-on:click="deleteStep(index)">
                                                {{ $t('common.word.remove') }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>
                                                        <strong>{{ $t('admin.category.form.select_step') }}</strong>
                                                    </label>
                                                    <model-list-select :list="steps"
                                                                       v-model="model.steps[index]"
                                                                       option-value="id"
                                                                       :custom-text="name"
                                                                       placeholder="select item">
                                                    </model-list-select>
                                                    <div class="mt-3">
                                                        <p>Selected:</p> <a :href="$r('admin.step.edit', { step: model.steps[index].id })">{{ model.steps[index].name }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button
                                    v-on:click="addStep"
                                    class="btn btn-primary margin-top-10"
                                >{{ $t('common.word.add') }}</button>
                            </div>
                        </div>
                    </div>
                    <div v-for="(error, i) in errors.items"
                         :key="`items__error__${i}`"
                         class="text-danger error"
                    >
                        {{ error }}
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
    import { ModelListSelect } from 'vue-search-select';

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
            steps: {
                type: Array,
                required: false,
            },
            faqs: {
                type: Array,
                required: false,
            },
            errors: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        components: {
            ModelListSelect,
        },

        data() {
            return {
                categoryPreviewImage: null,
                selectedSteps: [],
            };
        },

        watch: {
            selectedSteps: function () {
                this.model.steps = this.selectedSteps;
            }
        },

        methods: {
            submit() {
                this.$emit('submit', this.model);
            },

            addStep() {
                this.selectedSteps.push({
                    id: null,
                });

                this.$forceUpdate();
            },

            deleteStep(index) {
                this.selectedSteps.splice(index, 1);

                this.$forceUpdate();
            },

            name (item) {
                return `${item.name}`
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
        },

        created() {
            if (this.model.id) {
                this.model.is_hidden = Number(this.model.is_hidden);
            }

            this.categoryPreviewImage = this.model.image;

            this.selectedSteps = this.model.steps;
        },
    };
</script>
