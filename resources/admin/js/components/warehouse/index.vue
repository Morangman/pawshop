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
                            </th>
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.delivery_price') }}
                            </th>
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.repair_price') }}
                            </th>
                            <th>
                                {{ $t('admin.warehouse.index.table.headers.sell_price') }}
                            </th>
                            <th>{{ $t('common.word.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="!isLoading">
                            <tr v-for="(product, i) in products" :key="`faq_${i}`">
                                    <td>{{ model.images }}</td>
                                    <td><a :href="$r('admin.warehouse.edit', { warehouse: product.id })">{{ product.product_name }}</a></td>
                                    <td>{{ product.status }}</td>
                                    <td v-html="highlightSearchResult(product.imei, filters.search)"></td>
                                    <td v-html="highlightSearchResult(product.serial_number, filters.search)"></td>
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
        components: {
            InfiniteLoading,
        },

        mixins: [IndexPageHelper],

        data() {
            return {
                filters: {
                    page: 1,
                    search: null,
                    by: 'id',
                    dir: 'asc',
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
            this.getProducts();

            this.debouncedGetProducts =_.debounce(this.getProducts, 500);
        },
    };
</script>
