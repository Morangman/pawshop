<template>
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col col-auto">
                    <a :href="$r('admin.warehouse.create')" class="btn btn-labeled btn-labeled-right bg-blue heading-btn">
                        <b><i class="icon-add"></i></b>
                        {{ $t('admin.warehouse.index.header_btn') }}
                    </a>
                </div>
                <div class="col col-auto">
                    <div class="csv-file file btn btn-lg btn-primary">
                        <div v-if="!uploadingFedexPrices">
                            XML <i class="icon-file-plus2"></i>
                            <input id="csv-file" type="file" name="file" @change="importFedexPrices"/>
                        </div>
                        <div class="d-flex justify-content-center" v-if="uploadingFedexPrices">
                            <b-spinner
                                    style="width: 16px; height: 16px;"
                                    variant="info"
                            ></b-spinner>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-auto" v-if="!status">
                    <label for="pStatus" class="d-inline-block">{{ $t('admin.warehouse.index.table.headers.status') }} :</label>
                    <select id="pStatus" class="form-control form-control-sm d-inline-block" style="width: auto;" name="ordered_status" v-model="filters.status" required>
                        <option :value="0">{{ $t('admin.order.index.search.all') }}</option>
                        <option v-for="(status, i) in statuses" :key="`warehouse_status__${i}`" :value="status.id">{{ status.name }}</option>
                    </select>
                </div>
                <div class="col col-md-5">
                    <input
                        v-model="filters.search"
                        name="search"
                        type="text"
                        class="form-control"
                        :placeholder="$t('admin.warehouse.index.filters.search')"
                    >
                </div>
                <div class="col col-md-2">
                    <button class="btn btn-primary" @click.prevent="filters.search = null">
                        {{ $t('common.word.cancel') }}
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="bookings">
                    <thead>
                        <tr class="bg-blue">
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.photo') }}
                            </th>
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.product_name') }}
                            </th>
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.status') }}
                            </th>
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.imei') }}
                            </th>
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.serial_number') }}
                            </th>
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.clear_price') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'clear_price' && filters.dir === 'desc'"
                                            @click.prevent="sort('clear_price', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'clear_price' && filters.dir === 'asc'"
                                            @click.prevent="sort('clear_price', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'clear_price'" @click.prevent="sort('clear_price', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.delivery_price') }}
                                 <span>
                                    <i
                                            v-if="filters.by === 'delivery_price' && filters.dir === 'desc'"
                                            @click.prevent="sort('delivery_price', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'delivery_price' && filters.dir === 'asc'"
                                            @click.prevent="sort('delivery_price', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'delivery_price'" @click.prevent="sort('delivery_price', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.repair_price') }}
                                 <span>
                                    <i
                                            v-if="filters.by === 'repair_price' && filters.dir === 'desc'"
                                            @click.prevent="sort('repair_price', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'repair_price' && filters.dir === 'asc'"
                                            @click.prevent="sort('repair_price', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'repair_price'" @click.prevent="sort('repair_price', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.sell_price') }}
                                 <span>
                                    <i
                                            v-if="filters.by === 'sell_price' && filters.dir === 'desc'"
                                            @click.prevent="sort('sell_price', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'sell_price' && filters.dir === 'asc'"
                                            @click.prevent="sort('sell_price', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'sell_price'" @click.prevent="sort('sell_price', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th>{{ $t('common.word.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="!isLoading">
                            <tr v-for="(product, i) in products" :key="`product_${i}`" class="order-table-item">
                                    <td class="order-table-hovered">
                                        <a :href="$r('admin.warehouse.edit', { warehouse: product.id })">
                                            <img width="auto"
                                                height="100"
                                                class="center-image"
                                                :src="product.category.image"
                                            >
                                        </a>
                                    </td>
                                    <td>
                                        <a :href="$r('admin.warehouse.edit', { warehouse: product.id })" title="Edit">
                                            <span v-html="highlightSearchResult(product.product_name, filters.search)"></span>
                                        </a>
                                    </td>
                                    <td v-on:click="editDevice(product.id)">{{ product.warehouse_status.name }}</td>
                                    <td v-on:click="editDevice(product.id)" v-html="highlightSearchResult(product.imei ? product.imei : '', filters.search)"></td>
                                    <td v-on:click="editDevice(product.id)" v-html="highlightSearchResult(product.serial_number ? product.serial_number : '', filters.search)"></td>
                                    <td v-on:click="editDevice(product.id)">{{ product.clear_price }}</td>
                                    <td v-on:click="editDevice(product.id)">{{ product.delivery_price }}</td>
                                    <td v-on:click="editDevice(product.id)">{{ product.repair_price }}</td>
                                    <td v-on:click="editDevice(product.id)">{{ product.sell_price }}</td>
                                    <td>
                                        <a :href="$r('admin.warehouse.edit', { warehouse: product.id })">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        <delete-confirmation
                                            :route-path="$r('admin.warehouse.delete', { warehouse: product.id })"
                                            :redirect-path="$r('admin.warehouse.index')"
                                            :title="$t('common.word.delete')"
                                        />
                                    </td>
                                </tr>
                        </template>
                    </tbody>
                </table>
                <spinner :is-loading="isLoading" class="m-4"></spinner>
            </div>
            <b-pagination
                v-model='filters.page'
                :total-rows='total'
                aria-controls='warehouse'
                class="mt-2"
            ></b-pagination>
        </div>
    </div>
</template>

<script>
    import IndexPageHelper from '../../mixins/index_page_helper';
    import InfiniteLoading from 'vue-infinite-loading';
    import FormHelper from '../../mixins/form_helper';

    export default {
        props: {
            searched: {
                type: Object,
                required: false,
            },
            statuses: {
                type: Array,
                required: false,
            },
            status: {
                type: Number,
                required: false,
            },
        },

        components: {
            InfiniteLoading,
        },

        mixins: [IndexPageHelper, FormHelper],

        data() {
            return {
                filters: {
                    page: 1,
                    search: null,
                    status: this.status,
                    by: 'id',
                    dir: 'desc',
                },
                products: [],
                total: null,
                isLoading: true,
                uploadingFedexPrices: false,
            };
        },

        watch: {
            filters: {
                handler() {
                    this.debouncedGetProducts();
                },
                deep: true,
            },
        },

        methods: {
            getProducts() {
                this.isLoading = true;

                axios.get(
                    Router.route(
                        'admin.warehouse.all',
                        _.pickBy(this.filters, _.identity)
                    ),
                ).then(({ data }) => {
                    this.products = data.data;
                    this.total = data.total;
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                }).finally(() => {
                    this.isLoading = false;
                });
            },

            editDevice(device) {
                location.href = Router.route('admin.warehouse.edit', { warehouse: device });
            },

            importFedexPrices(e) {
                this.uploadingFedexPrices = true;

                this.file = e.target.files[0];

                let data = new FormData();

                data.append('file', this.file);

                axios.post(
                    Router.route('admin.warehouse.import'),
                    data
                ).then(({ data }) => {
                    this.uploadingFedexPrices = false;

                    location.href = Router.route('admin.warehouse.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.uploadingFedexPrices = false;

                    notify.error(_.head(errors));
                }).finally(() => {
                    this.uploadingFedexPrices = false;
                });
            },

            sort(field, direction) {
                this.filters.by = field;
                this.filters.dir = direction;
            },
        },

        created() {
            if (this.searched) {
                this.products = this.searched.data;

                this.total = this.searched.total;

                this.isLoading = false;
            } else {
                this.getProducts();
            }

            this.debouncedGetProducts =_.debounce(this.getProducts, 500);
        },
    };
</script>
