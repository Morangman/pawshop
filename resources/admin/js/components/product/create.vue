<template>
   <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <template v-if="stepCount === 0">
                        <div class="form-group">
                            <label>
                                <strong>{{ $t('admin.product.form.name') }}</strong>
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
                                <strong>Production year</strong>
                            </label>
                            <input
                                name="prod_year"
                                type="text"
                                v-model="model.prod_year"
                                class="form-control"
                                :class="{ 'border-danger': errors.prod_year }"
                            >
                            <div v-for="(error, i) in errors.prod_year"
                                :key="`prod_year__error__${i}`"
                                class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                <strong>{{ $t('admin.product.form.price_for_broken') }}</strong>
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
                                <strong>{{ $t('admin.product.form.status') }}</strong>
                            </label>
                            <b-form-checkbox
                                v-model="model.is_hidden"
                                value="1"
                                unchecked-value="0"
                            >
                                {{ $t('admin.product.form.hidden') }}
                            </b-form-checkbox>
                            <div v-for="(error, i) in errors.is_hidden"
                                :key="`is_hidden__error__${i}`"
                                class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>

                        <div class="text-right">
                            <button
                                v-on:click="nextStep()"
                                class="btn btn-primary margin-top-10"
                            >Next -></button>
                        </div>
                    </template>

                    <template v-if="stepCount === 1">
                        <div class="form-group">
                            <label>
                                <strong>{{ $t('admin.product.form.image') }}</strong>
                            </label>
                            <b-form-file
                                class="mt-1"
                                style="margin-bottom: 20px;"
                                accept=".png,.jpg,.jpeg,.gif"
                                @change="showCategoryPreviewImage($event)"
                            ></b-form-file>
                            <input
                                name="image"
                                type="text"
                                v-model="model.image_url"
                                placeholder="Image URL"
                                class="form-control"
                                :class="{ 'border-danger': errors.image }"
                            >
                            <img width="auto"
                                height="100"
                                class="center-image"
                                v-if="categoryPreviewImage"
                                :src="categoryPreviewImage"
                                v-on:click="deleteCategoryImage"
                            >
                        </div>

                        <div class="text-right">
                            <button
                                v-on:click="backStep()"
                                class="btn btn-primary margin-top-10"
                            > <- Back</button>
                            <button
                                v-on:click="nextStep()"
                                class="btn btn-primary margin-top-10"
                            >Next -></button>
                        </div>
                    </template>

                    <template v-if="stepCount === 2">
                        <div class="form-group">
                            <label>
                                <strong>{{ $t('admin.product.form.subcategory') }}</strong>
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
                                <strong>{{ $t('admin.product.form.faq') }}</strong>
                            </label>

                            <model-list-select :list="faqs"
                                            v-model="model.faq_id"
                                            option-value="id"
                                            :custom-text="name"
                                            placeholder="select item">
                            </model-list-select>
                        </div>

                        <div class="text-right">
                            <button
                                v-on:click="backStep()"
                                class="btn btn-primary margin-top-10"
                            > <- Back</button>
                            <button
                                v-on:click="nextStep()"
                                class="btn btn-primary margin-top-10"
                            >Next -></button>
                        </div>
                    </template>

                    <template v-if="stepCount === 3">
                        <div class="form-group">
                            <strong>
                                <h1>{{ $t('admin.product.form.steps') }}</h1>
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
                                                            <strong>{{ $t('admin.product.form.select_step') }}</strong>
                                                        </label>
                                                        <model-list-select :list="steps"
                                                                        v-model="stepsByCategory[index]"
                                                                        v-on:input="selectStepEvent(index)"
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
                                                            v-on:input="selectStepItemEvent(index)"
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

                        <div class="text-right">
                            <button
                                v-on:click="backStep()"
                                class="btn btn-primary margin-top-10"
                            > <- Back</button>
                            <button
                                v-on:click="generatePrices()"
                                class="btn btn-primary margin-top-10"
                            >Next -></button>
                        </div>
                    </template>

                    <template v-if="stepCount === 4">
                        <div class="form-group">
                            <strong>
                                <h1>
                                    Price variations
                                    <a :href="'https://www.ebay.com/sch/i.html?_nkw=' + model.name" target="_blank">
                                        <img style="height:30px; width: auto;" :src="'../../../../client/images/ebay.png'" />
                                    </a>
                                </h1>
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
                                <th scope="col" v-for="(step, index) in priceVariations.length ? priceVariations[0].steps : []" :key="`step_table__${index}`">{{ step.step_name.name }}</th>
                                <th scope="col">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(price, index) in priceVariations" :key="`variation__${index}`">
                                    <th scope="row"> {{ price.id }} </th>
                                    <td v-for="(step, index) in price.steps" :key="`step_price__${index}`">
                                        <div class="flex flex-row steps-row">
                                            <p>{{ step.value }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <input
                                            name="parsed_price"
                                            type="text"
                                            v-model="price.custom_price"
                                            class="form-control"
                                            v-on:keyup="updatePrice(price)"
                                        >
                                    </td>
                                </tr>
                            </tbody>
                            </table>
                        </div>

                        <div class="text-right">
                            <button
                                v-on:click="backStep()"
                                class="btn btn-primary margin-top-10"
                            > <- Back</button>
                            <template v-if="model.id">
                                <button
                                    type="submit"
                                    class="btn btn-danger"
                                    @click.prevent="deleteCategory"
                                >
                                    {{ $t('common.word.cancel') }}
                                </button>
                            </template>
                            <button
                                v-on:click="nextStep()"
                                class="btn btn-primary margin-top-10"
                            >Save</button>
                        </div>
                    </template>

                    <template v-if="stepCount > 4">
                        <h1>Processing..</h1>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import CategoryFormComponent from './form';
    import FormHelper from '../../mixins/form_helper';
    import { ModelListSelect } from 'vue-search-select';

    export default {
        components: {
            CategoryForm: CategoryFormComponent,
        },

        props: {
            category: {
                type: Object,
                required: false,
            },
            faqs: {
                type: Array,
                required: true,
            },
            steps: {
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
            categories: {
                type: Array,
                required: false,
            },
            subcategory: {
                type: Number,
                required: false,
            },
        },

        mixins: [FormHelper],

        components: {
            ModelListSelect,
        },

        data() {
            return {
                model: {
                    name: null,
                    slug: null,
                    image: null,
                    image_url: null,
                    subcategory_id: null,
                    faq_id: null,
                    custom_text: null,
                    premium_price: null,
                    price_for_broken: 5,
                    steps: [],
                },
                stepCount: 0,
                categoryPreviewImage: null,
                stepsByCategory: [],
                priceVariations: [],
                premiumPrices: [],
                pricesData: {},
                searchText: null,
                errors: {},
                formData: null,
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
            nextStep() {
                this.stepCount++;

                if (this.stepCount > 4) {
                    this.store();
                }
            },

            selectStepEvent(index) {
                this.stepsByCategory[index]['items'] = [];

                if (this.stepsByCategory[index].items_variations.length <= 5) {
                    _.each(this.stepsByCategory[index].items_variations, (step, i) => {
                        this.stepsByCategory[index]['items'].push(step);
                    });
                }

                this.$forceUpdate();
            },

            backStep() {
                if (this.stepCount > 0) {
                    this.stepCount--;
                }
            },

            deleteCategory() {
                confirmation.delete(() => {
                    axios.delete(
                        Router.route('admin.product.delete', { category: this.model.id }),
                    ).then(() => {
                        location.href = Router.route('admin.product.index');
                    }).catch(({ response: { data: { errors } } }) => {
                        notify.error(_.head(errors));
                    });
                });
            },

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
                    Router.route('admin.product.update-price', { category: this.model.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                }).catch(({ response: { data: { errors } } }) => {
                    notify.success(
                        errors
                    );
                });
            },

            generatePrices() {
                this.errors = {};
                this.formData = new FormData();
                this.collectFormData(this.model);

                axios.post(
                    Router.route('admin.product.generate'),
                    this.formData,
                ).then((data) => {
                    this.priceVariations = data.data.prices;

                    this.prices = data.data.prices;

                    this.model.id = data.data.product;

                    this.stepCount++;
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;

                    let html = [];

                    _.each(errors, (error, key) => {
                        html.push(error[0]);
                    });

                    notify.error(html.join('<br>'));

                    this.scrollToError();
                });
            },

            debouncedRefresh() {
                this.model.steps = this.stepsByCategory;
                this.$forceUpdate();
            },

            addStep() {
                // this.stepsByCategory[1] = {
                //     id: null,
                // };

                this.stepsByCategory.splice(1, 0, {id: null});
                this.$forceUpdate();
            },

            deleteStep(index) {
                this.stepsByCategory.splice(index, 1);

                this.$forceUpdate();
            },

            name (item) {
                return `${item.name}`
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

            store() {
                this.errors = {};
                this.formData = new FormData();
                this.collectFormData(this.model);

                axios.post(
                    Router.route('admin.product.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.product.edit', { category: this.model.id });
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;

                    let html = [];

                    _.each(errors, (error, key) => {
                        html.push(error[0]);
                    });

                    notify.error(html.join('<br>'));

                    this.scrollToError();
                });
            },
        },

        created() {
            this.stepsByCategory = [this.steps[0], this.steps[8]];

            if (this.subcategory) {
                this.model.subcategory_id = this.subcategory;
            }
        },
    };
</script>
