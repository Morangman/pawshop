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
                            <strong>Slug</strong>
                        </label>
                        <input
                            name="slug"
                            type="text"
                            v-model="model.slug"
                            class="form-control"
                            :class="{ 'border-danger': errors.slug }"
                        >
                        <div v-for="(error, i) in errors.slug"
                             :key="`slug__error__${i}`"
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
                            <strong>{{ $t('admin.category.form.cart_count') }}</strong>
                        </label>
                        <p>{{ model.box_count }}</p>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.category.form.view_count') }}</strong>
                        </label>
                        <p>{{ model.view_count }}</p>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.category.form.premium_price') }}</strong>
                        </label>
                        <input
                            name="premium_price"
                            type="text"
                            v-model="model.premium_price"
                            class="form-control"
                            :class="{ 'border-danger': errors.premium_price }"
                        >
                        <div v-for="(error, i) in errors.premium_price"
                             :key="`premium_price__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.category.form.price_for_broken') }}</strong>
                        </label>
                        <input
                            name="price_for_broken"
                            type="text"
                            v-model="model.price_for_broken"
                            class="form-control"
                            :class="{ 'border-danger': errors.price_for_broken }"
                        >
                        <div v-for="(error, i) in errors.price_for_broken"
                             :key="`price_for_broken__error__${i}`"
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
                            <div class="change-blocks-wrapper__item" v-for="(item, index) in stepsByCategory" :key="`step__${index}`">
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
                                                                       v-model="stepsByCategory[index]"
                                                                       option-value="id"
                                                                       :custom-text="name"
                                                                        placeholder="select item">
                                                    </model-list-select>

                                                    <div class="mt-3">
                                                        <p>Selected:</p> <a :href="$r('admin.step.edit', { stepName: item.id })">{{ item.title }}</a>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <multiselect
                                                        v-model="item.items"
                                                        :options="item.items_variations ? item.items_variations : []"
                                                        :multiple="true"
                                                        class="multiselect1"
                                                        :close-on-select="true"
                                                        placeholder="Select step"
                                                        label="value" track-by="value">
                                                    </multiselect>
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

                    <div class="form-group">
                        <strong>
                            <h1>Price variations</h1>
                        </strong>
                        <input
                            name="price_for_broken"
                            type="text"
                            class="col-md-6 form-control"
                            placeholder="Search by step name"
                            v-model="searchText"
                            v-on:input="serachPriceByStepName"
                        >
                        <table class="table">
                        <thead>
                            <tr>
                            <th scope="col">#</th>
                            <th scope="col">Steps</th>
                            <th scope="col">Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(price, index) in priceVariations" :key="`variation__${index}`">
                                <th scope="row"> {{ price.id }} </th>
                                <td>
                                    <div class="flex flex-row steps-row">
                                        <p v-for="(step, index) in price.steps" :key="`step_price__${index}`">{{ step.value }}</p>
                                    </div>
                                </td>
                                <td>
                                    <input
                                        name="slug"
                                        type="text"
                                        v-model="price.price"
                                        class="form-control"
                                        v-on:keyup.enter="updatePrice(price)"
                                    >
                                </td>
                            </tr>
                        </tbody>
                        </table>
                    </div>

                    <div class="form-group" v-if="premiumPrices.length">
                        <strong>
                            <h1>Premium prices</h1>
                        </strong>
                        <div class="change-blocks-wrapper__item" v-for="(price, index) in premiumPrices" :key="`premium_price__${index}`">
                            <div class="form-group row flex flex-row steps-row">
                                <p>{{ price.step_name }}</p>
                            </div>
                            <div class="form-group row">
                                <label>
                                    <strong>Price plus</strong>
                                </label>
                                <input
                                    name="price_plus"
                                    type="text"
                                    v-model="price.price_plus"
                                    class="form-control"
                                >
                                <label>
                                    <strong>Price percent</strong>
                                </label>
                                <input
                                    name="price_percent"
                                    type="text"
                                    v-model="price.price_percent"
                                    class="form-control"
                                >
                            </div>
                            <div class="form-group row">
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    v-on:click="updatePremiumPrice(price)"
                                >
                                    Update premium price
                                </button>
                            </div>
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
                <template v-if="model.id">
                    <button
                        type="submit"
                        class="btn btn-primary"
                        @click.prevent="generatePrices"
                    >
                        Generate prices variations
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
            prices: {
                type: Array,
                required: false,
            },
            premiumprices: {
                type: Array,
                required: false,
            },
            categorysteps: {
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
                stepsByCategory: [],
                priceVariations: [],
                premiumPrices: [],
                pricesData: {},
                searchText: null,
            };
        },

        watch: {
            stepsByCategory: {
                handler() {
                    this.debouncedRefresh();
                },
                deep: true,
            },
        },

        methods: {
            serachPriceByStepName() {
                if (this.searchText) {
                    this.priceVariations = [];

                    _.each(this.prices, (price, key) => {
                        _.each(price.steps, (step, i) => {
                            if (step.value.includes(this.searchText)) {
                                this.priceVariations.push(price);
                            }
                        });
                    });
                } else {
                    this.priceVariations = this.prices;
                }
            },

            updatePrice(data) {
                this.formData = new FormData();
                this.formData.set('_method', 'POST');

                this.collectFormData(data);

                axios.post(
                    Router.route('admin.category.update-price', { slug: this.model.slug }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    notify.success(
                        'Price was be updated'
                    );
                }).catch(({ response: { data: { errors } } }) => {
                    notify.success(
                        errors
                    );
                });
            },

            updatePremiumPrice(data) {
                this.formData = new FormData();
                this.formData.set('_method', 'POST');

                this.collectFormData(data);

                axios.post(
                    Router.route('admin.category.update-premium', { slug: this.model.slug }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    notify.success(
                        'Premium price was be updated'
                    );
                }).catch(({ response: { data: { errors } } }) => {
                    notify.success(
                        errors
                    );
                });
            },

            generatePrices() {
                axios.post(
                    Router.route('admin.category.generate-prices', { slug: this.model.slug }),
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.category.edit', { slug: this.model.slug });
                }).catch(({ response: { data: { errors } } }) => {
                    notify.success(
                        errors
                    );
                });
            },

            debouncedRefresh() {
                this.model.steps = this.stepsByCategory;
                this.$forceUpdate();
            },

            submit() {
                this.$emit('submit', this.model);
            },

            addStep() {
                this.stepsByCategory.push({
                    id: null,
                });

                this.$forceUpdate();
            },

            deleteStep(index) {
                this.stepsByCategory.splice(index, 1);

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

                this.stepsByCategory = this.categorysteps;

                this.premiumPrices = this.premiumprices;
            }

            this.categoryPreviewImage = this.model.image;

            this.priceVariations = this.prices;
        },
    };
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
