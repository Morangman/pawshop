<template>
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col col-auto">
                    <a :href="$r('admin.order.create')" class="btn btn-labeled btn-labeled-right bg-blue heading-btn">
                        <b><i class="icon-add"></i></b>
                        {{ $t('admin.order.index.header_btn') }}
                    </a>
                </div>
                <div class="form-group col-md-auto">
                    <label for="orderStatus" class="d-inline-block">{{ $t('admin.order.index.table.headers.status') }} :</label>
                    <select
                        id="orderStatus"
                        class="form-control form-control-sm d-inline-block"
                        style="width: auto;"
                        v-model="filters.order_status"
                        required
                    >
                        <option :value="null">{{ $t('admin.order.index.search.all') }}</option>
                        <option v-for="(status, key) in $t('admin.order.order_statuses')" :value="key">{{ status }}</option>
                    </select>
                </div>
                <div class="col col-md-5">
                    <input
                        v-model="filters.search"
                        name="search"
                        type="text"
                        class="form-control"
                        :placeholder="$t('admin.order.index.filters.search')"
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
                                {{ $t('admin.order.index.table.headers.id') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'id' && filters.dir === 'desc'"
                                            @click.prevent="sort('id', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'id' && filters.dir === 'asc'"
                                            @click.prevent="sort('id', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'id'" @click.prevent="sort('id', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th>
                                {{ $t('admin.order.index.table.headers.name') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'name' && filters.dir === 'desc'"
                                            @click.prevent="sort('name', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'name' && filters.dir === 'asc'"
                                            @click.prevent="sort('name', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'name'" @click.prevent="sort('name', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th>{{ $t('admin.order.index.table.headers.contacts') }}</th>
                            <th>{{ $t('admin.order.index.table.headers.status') }}</th>
                            <th>
                                {{ $t('admin.order.index.table.headers.created_at') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'created_at' && filters.dir === 'desc'"
                                            @click.prevent="sort('created_at', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'created_at' && filters.dir === 'asc'"
                                            @click.prevent="sort('created_at', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'created_at'" @click.prevent="sort('created_at', 'asc')">
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
                            <tr v-for="(order, i) in orders" :key="`order${i}`">
                                    <td><a :href="$r('admin.order.edit', { order: order.id })">{{ order.id }}</a></td>
                                    <td v-html="highlightSearchResult(order.name, filters.search)"></td>
                                    <td>{{ order.phone }} <br> {{ order.email }}</td>
                                    <td>
                                        {{ $t('admin.order.order_statuses.' + order.ordered_status) }}
                                    </td>
                                    <td>{{ normalizeDate(order.created_at) }}</td>
                                    <td>
                                        <a :href="$r('admin.order.edit', { order: order.id })">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        <delete-confirmation
                                            :route-path="$r('admin.order.delete', { order: order.id })"
                                            :redirect-path="$r('admin.order.index')"
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
                aria-controls='orders'
                class="mt-2"
            ></b-pagination>
        </div>
    </div>
</template>

<script>
    import IndexPageHelper from '../../mixins/index_page_helper';
    import InfiniteLoading from 'vue-infinite-loading';
    import moment from 'moment';

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
                    order_status: null,
                    by: 'id',
                    dir: 'desc',
                },
                orders: [],
                total: null,
                isLoading: true,
            };
        },

        watch: {
            filters: {
                handler() {
                    this.debouncedGetOrders();
                },
                deep: true,
            },
        },

        methods: {
            getOrders() {
                this.isLoading = true;

                axios.get(
                    Router.route(
                        'admin.order.all',
                        _.pickBy(this.filters, _.identity)
                    ),
                ).then(({ data }) => {
                    this.orders = data.data;
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

            normalizeDate(date) {
                return moment(date).format("DD.MM.YYYY hh:mm");
            },
        },

        created() {
            this.getOrders();

            this.debouncedGetOrders =_.debounce(this.getOrders, 500);
        },
    };
</script>
