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
                <div class="form-group col-md-auto" v-if="!status">
                    <label for="pStatus" class="d-inline-block">{{ $t('admin.warehouse.index.table.headers.status') }} :</label>
                    <select
                            id="pStatus"
                            class="form-control form-control-sm d-inline-block"
                            style="width: auto;"
                            v-model="filters.status"
                            required
                    >
                        <option :value="0">{{ $t('admin.order.index.search.all') }}</option>
                        <option v-for="(status, key) in $t('admin.warehouse.product_statuses')" :value="key">{{ status }}</option>
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
                            <tr v-for="(product, i) in products" :key="`product_${i}`">
                                    <td>
                                        <a :href="$r('admin.warehouse.edit', { warehouse: product.id })">
                                            <img width="auto"
                                                height="100"
                                                class="center-image"
                                                :src="product.category.image"
                                            >
                                        </a>
                                    </td>
                                    <td><a :href="$r('admin.warehouse.edit', { warehouse: product.id })">{{ product.product_name }}</a></td>
                                    <td>{{ product.warehouse_status.name }}</td>
                                    <td v-html="highlightSearchResult(product.imei ? product.imei : '', filters.search)"></td>
                                    <td v-html="highlightSearchResult(product.serial_number ? product.serial_number : '', filters.search)"></td>
                                    <td>{{ product.clear_price }}</td>
                                    <td>{{ product.delivery_price }}</td>
                                    <td>{{ product.repair_price }}</td>
                                    <td>{{ product.sell_price }}</td>
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

        mixins: [IndexPageHelper],

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
