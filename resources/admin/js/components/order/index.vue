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
                <div class="form-group col-md-auto" v-if="!ordersstatus">
                    <label for="orderStatus" class="d-inline-block">{{ $t('admin.order.index.table.headers.status') }} :</label>
                    <select
                        id="orderStatus"
                        class="form-control form-control-sm d-inline-block"
                        style="width: auto;"
                        v-model="filters.order_status"
                        required
                    >
                        <option :value="0">{{ $t('admin.order.index.search.all') }}</option>
                        <option v-for="(status, key) in statuses" :value="status.id">{{ status.name }}</option>
                    </select>
                </div>
                <div class="col col-md-3">
                    <input
                        id="searchOrder"
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
                                {{ $t('admin.order.index.table.headers.image') }}
                            </th>
                            <th>
                                {{ $t('admin.order.index.table.headers.name') }}
                            </th>
                            <th>{{ $t('admin.order.index.table.headers.contacts') }}</th>
                            <th>{{ $t('admin.order.index.table.headers.status') }}</th>
                            <th v-if="isnew">{{ $t('admin.order.index.table.headers.label_status') }}</th>
                            <th v-if="istransit">
                                {{ $t('admin.order.index.table.headers.estimate_date') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'estimate_date' && filters.dir === 'desc'"
                                            @click.prevent="sort('estimate_date', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'estimate_date' && filters.dir === 'asc'"
                                            @click.prevent="sort('estimate_date', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'estimate_date'" @click.prevent="sort('estimate_date', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th v-if="isdelivered">
                                {{ $t('admin.order.index.table.headers.delivered_date') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'delivered_date' && filters.dir === 'desc'"
                                            @click.prevent="sort('delivered_date', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'delivered_date' && filters.dir === 'asc'"
                                            @click.prevent="sort('delivered_date', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'delivered_date'" @click.prevent="sort('delivered_date', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th v-if="ispayed">
                                {{ $t('admin.order.index.table.headers.payed_date') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'paid_date' && filters.dir === 'desc'"
                                            @click.prevent="sort('paid_date', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'paid_date' && filters.dir === 'asc'"
                                            @click.prevent="sort('paid_date', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'paid_date'" @click.prevent="sort('paid_date', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th v-if="isreceived">
                                {{ $t('admin.order.index.table.headers.received_date') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'received_date' && filters.dir === 'desc'"
                                            @click.prevent="sort('received_date', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'received_date' && filters.dir === 'asc'"
                                            @click.prevent="sort('received_date', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'received_date'" @click.prevent="sort('received_date', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th v-if="iscancelled">
                                {{ $t('admin.order.index.table.headers.cancelled_date') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'cancelled_date' && filters.dir === 'desc'"
                                            @click.prevent="sort('cancelled_date', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'cancelled_date' && filters.dir === 'asc'"
                                            @click.prevent="sort('cancelled_date', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'cancelled_date'" @click.prevent="sort('cancelled_date', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
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
                            <tr v-for="(order, i) in orders" :key="`order${i}`" class="order-table-item">
                                    <td class="order-table-hovered">
                                        <a :href="$r('admin.order.edit', { order: order.id })">
                                            <img width="auto"
                                                height="100"
                                                :src="order.orders.order[0].device.image"
                                            >
                                        </a>
                                        <a v-if="order.orders.order.length > 1" 
                                            :href="$r('admin.order.edit', { order: order.id })" 
                                            class="badge badge-primary" style="color: rgb(255, 255, 255);">
                                            + {{ order.orders.order.length - 1 }}
                                        </a>
                                    </td>
                                    <td class="order-table-hovered" v-on:click="editOrder(order.id)" v-html="highlightSearchResult(order.address.name, filters.search)"></td>
                                    <td class="order-table-hovered" v-on:click="editOrder(order.id)">{{ order.address.phone }}</td>
                                    <td class="order-table-hovered" v-on:click="editOrder(order.id)">
                                        <b :style="'color:' + order.order_status.color">{{ order.order_status.name }}</b>
                                    </td>
                                    <td v-if="isnew" class="order-table-hovered" v-on:click="editOrder(order.id)">
                                        <span v-if="order.tracking_number" class="badge badge-primary">Label created</span>
                                        <span v-if="order.is_label_trouble" class="badge badge-danger">Label trouble</span>
                                    </td>
                                    <td v-if="istransit" v-on:click="editOrder(order.id)" class="order-table-hovered"><span v-if="order.estimate_date">{{ normalizeDate(order.estimate_date) }}</span></td>
                                    <td v-if="isdelivered" v-on:click="editOrder(order.id)" class="order-table-hovered"><span v-if="order.delivered_date">{{ normalizeDate(order.delivered_date) }}</span></td>
                                    <td v-if="ispayed" v-on:click="editOrder(order.id)" class="order-table-hovered"><span v-if="order.paid_date">{{ normalizeDate(order.paid_date) }}</span></td>
                                    <td v-if="isreceived" v-on:click="editOrder(order.id)" class="order-table-hovered"><span v-if="order.received_date">{{ normalizeDate(order.received_date) }}</span></td>
                                    <td v-if="iscancelled" v-on:click="editOrder(order.id)" class="order-table-hovered"><span v-if="order.cancelled_date">{{ normalizeDate(order.cancelled_date) }}</span></td>
                                    <td v-on:click="editOrder(order.id)" class="order-table-hovered">{{ normalizeDate(order.created_at) }}</td>
                                    <td>
                                        <a :href="$r('admin.order.edit', { order: order.id })">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        <a href="javascript:0" v-if="!iscancelled" v-on:click="cancelOrder(order.id)" title="Cancel order" class="text-danger"><i class="icon-cancel-circle2"></i></a>
                                        <a href="javascript:0" v-if="iscancelled" v-on:click="restoreOrder(order.id)" title="Restore order" class="text-info"><i class="icon-reset"></i></a>
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
        props: {
            searched: {
                type: Object,
                required: false,
            },
            statuses: {
                type: Array,
                required: false,
            },
            isnew: {
                type: Boolean,
                required: false,
            },
            isdelivered: {
                type: Boolean,
                required: false,
            },
            ispayed: {
                type: Boolean,
                required: false,
            },
            isreceived: {
                type: Boolean,
                required: false,
            },
            iscancelled: {
                type: Boolean,
                required: false,
            },
            istransit: {
                type: Boolean,
                required: false,
            },
            ordersstatus: {
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
                    order_status: this.ordersstatus,
                    fedex_status: null,
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

            editOrder(order) {
                location.href = Router.route('admin.order.edit', { order: order });
            },

            cancelOrder(id) {
                window.swal({
                    title: this.$t('common.phrase.confirm.title'),
                    text: 'The order will be canceled',
                    icon: 'warning',
                    buttons: [this.$t('common.word.cancel'), this.$t('common.word.confirm')],
                }).then((result) => {
                    if (!result) {
                        return
                    }

                    axios.get(
                        Router.route('admin.order.set-cancel-status', { order: id }),
                    ).then(() => {
                        location.reload();
                    }).catch(({ response: { data: { errors } } }) => {
                        console.log(errors);
                    });
                });
            },

            restoreOrder(id) {
                window.swal({
                    title: this.$t('common.phrase.confirm.title'),
                    text: 'The order will be restored',
                    icon: 'warning',
                    buttons: [this.$t('common.word.cancel'), this.$t('common.word.confirm')],
                }).then((result) => {
                    if (!result) {
                        return
                    }

                    axios.get(
                        Router.route('admin.order.restore-order', { order: id }),
                    ).then(() => {
                        location.reload();
                    }).catch(({ response: { data: { errors } } }) => {
                        console.log(errors);
                    });
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
            if (this.searched) {
                this.orders = this.searched.data;

                this.total = this.searched.total;

                this.isLoading = false;
            } else {
                this.getOrders();
            }

            this.debouncedGetOrders =_.debounce(this.getOrders, 500);
        },
    };
</script>
