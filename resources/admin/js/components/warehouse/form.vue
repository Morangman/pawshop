<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.warehouse.form.category_id') }}</strong>
                        </label>
                        <model-list-select :list="categories"
                                           v-model="model.category_id"
                                           option-value="id"
                                           :custom-text="name"
                                           placeholder="select item">
                        </model-list-select>
                        <div v-for="(error, i) in errors.category_id"
                             :key="`category_id__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.warehouse.form.order_id') }}</strong>
                        </label>
                        <a :href="$r('admin.order.edit', { order: model.order_id })">{{ model.order_id }}</a>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.warehouse.form.status') }}</strong>
                        </label>
                        <select class="form-control" name="ordered_status" v-model="model.status" required :class="{ 'border-danger': errors.status }">
                            <option v-for="(status, i) in statuses" :key="`order_status__${i}`" :value="status.id">{{ status.name }}</option>
                        </select>
                        <div v-for="(error, i) in errors.status"
                             :key="`status_product__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.warehouse.form.clear_price') }}</strong>
                        </label>
                        <input
                            name="clear_price"
                            type="text"
                            v-model="model.clear_price"
                            class="form-control"
                            :class="{ 'border-danger': errors.clear_price }"
                        >
                        <div v-for="(error, i) in errors.clear_price"
                            :key="`clear_price__error__${i}`"
                            class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.warehouse.form.price') }}</strong>
                        </label>
                        <input
                            name="price"
                            type="text"
                            v-model="model.price"
                            class="form-control"
                            :class="{ 'border-danger': errors.price }"
                        >
                        <div v-for="(error, i) in errors.price"
                            :key="`price__error__${i}`"
                            class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.warehouse.form.images') }}</strong>
                        </label>
                        <div class="form-group overflow-box">
                            <div class="row">
                                <div
                                    v-if="model.id"
                                    v-for="(image, i) in model.warehouse_images"
                                    :key="`product_file__${i}`"
                                    class="col-sm-6 product-images"
                                >
                                    <a :href="image.url" target="_blank" style="margin-bottom: 20px;">
                                        <img width="auto"
                                            height="100"
                                            class="center-image"
                                            v-if="image"
                                            :src="image.url"
                                        >
                                    </a>
                                    <a href="#" v-on:click="deleteProductImage(image.id)" class="text-danger" title="Delete"><i class="icon-trash"></i> </a>
                                </div>
                            </div>

                            <div class="row">
                                <div
                                    v-if="images"
                                    v-for="(media, i) in images"
                                    :key="`task_file__${i}`"
                                    class="col-sm-6 product-images"
                                >
                                    <a href="javascript:0" style="margin-bottom: 20px;">
                                        <img width="auto"
                                            style="margin-bottom: 20px;"
                                            height="100"
                                            class="center-image"
                                            v-if="images"
                                            :src="media"
                                        >
                                    </a>
                                    <a href="#" v-on:click="deleteLocalProductImage(i)" class="text-danger" title="Delete"><i class="icon-trash"></i> </a>
                                </div>
                            </div>
                                <b-form-file
                                    v-model="model.media"
                                    class="mt-1"
                                    multiple
                                    @change="showImages"
                                    placeholder="Choose a file or drop it here..."
                                ></b-form-file>
                        </div>
                        <div class="form-group">
                            <label>
                                <strong>{{ $t('admin.warehouse.form.imei') }}</strong>
                            </label>
                            <input
                                name="imei"
                                type="text"
                                v-model="model.imei"
                                class="form-control"
                                :class="{ 'border-danger': errors.imei }"
                            >
                            <div v-for="(error, i) in errors.imei"
                                :key="`imei__error__${i}`"
                                class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                <strong>{{ $t('admin.warehouse.form.serial_number') }}</strong>
                            </label>
                            <input
                                name="serial_number"
                                type="text"
                                v-model="model.serial_number"
                                class="form-control"
                                :class="{ 'border-danger': errors.serial_number }"
                            >
                            <div v-for="(error, i) in errors.serial_number"
                                :key="`serial_number__error__${i}`"
                                class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group">
                            <b-form-checkbox
                                v-model="model.is_locked"
                                value="1"
                                unchecked-value="0"
                            >
                            <label>
                                <strong>{{ $t('admin.warehouse.form.is_locked') }}</strong>
                            </label>
                            </b-form-checkbox>
                            <div v-for="(error, i) in errors.is_locked"
                                :key="`is_locked__error__${i}`"
                                class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                <strong>{{ $t('admin.warehouse.form.delivery_price') }}</strong>
                            </label>
                            <input
                                name="delivery_price"
                                type="text"
                                v-model="model.delivery_price"
                                class="form-control"
                                :class="{ 'border-danger': errors.delivery_price }"
                            >
                            <div v-for="(error, i) in errors.delivery_price"
                                :key="`delivery_price__error__${i}`"
                                class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                <strong>{{ $t('admin.warehouse.form.repair_price') }}</strong>
                            </label>
                            <input
                                name="repair_price"
                                type="text"
                                v-model="model.repair_price"
                                class="form-control"
                                :class="{ 'border-danger': errors.repair_price }"
                            >
                            <div v-for="(error, i) in errors.repair_price"
                                :key="`repair_price__error__${i}`"
                                class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>
                                <strong>{{ $t('admin.warehouse.form.sell_price') }}</strong>
                            </label>
                            <input
                                name="sell_price"
                                type="text"
                                v-model="model.sell_price"
                                class="form-control"
                                :class="{ 'border-danger': errors.sell_price }"
                            >
                            <div v-for="(error, i) in errors.sell_price"
                                :key="`sell_price__error__${i}`"
                                class="text-danger error"
                            >
                                {{ error }}
                            </div>
                        </div>
                        <div class="form-group" v-if="model.steps">
                            <div class="row">
                                <div v-for="(step, stepKey) in model.steps" class="col-sm-6" :key="`product_step__${stepKey}`">
                                    <div v-if="step">
                                        <label>
                                            <strong>{{ step.step_name ? step.step_name.name + ': ' : '' }}</strong>
                                        </label>
                                        <p>{{ step.value }}</p>
                                    </div>
                                </div>
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
                        @click.prevent="deleteWarehouse"
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
            statuses: {
                type: Array,
                required: true,
            },
            errors: {
                type: Object,
                required: true,
            },
            categories: {
                type: Array,
                required: true,
            },
        },

        mixins: [FormHelper],

        components: {
            ModelListSelect,
        },

        data() {
            return {
                images: [],
            };
        },

        methods: {
            name (item) {
                return `${item.name}`
            },

            showImages(e) {
                this.images = [];

                const files = e.target.files;

                _.each(files, (value) => {
                    this.images.push(URL.createObjectURL(value));
                });
            },

            deleteProductImage(media) {
                this.model.warehouse_images = _.filter(
                    this.model.warehouse_images,
                    (file) => {
                        return file.id !== media;
                    });

                axios.delete(
                    Router.route('admin.warehouse.media.delete', { warehouse: this.model.id, media: media }),
                ).then(() => {
                    //location.href = Router.route('admin.warehouse.index');
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
                    
                notify.success(
                    this.$t('admin.category.messages.image_delete')
                );
            },

            deleteLocalProductImage(index) {
                this.model.media.splice(index, 1);

                this.images.splice(index, 1);
                    
                notify.success(
                    this.$t('admin.category.messages.image_delete')
                );
            },

            submit() {
                this.$emit('submit', this.model);
            },

            deleteWarehouse() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },
        },

        created() {
            this.model.media = [];

            if (this.model.id) {
                this.model.is_locked = Number(this.model.is_locked);
            }
        }
    };
</script>
