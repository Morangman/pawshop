<template>
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col col-md-4">
                    <button class="btn btn-primary" @click.prevent="readNotifications">
                        Read
                    </button>
                    <button class="btn btn-primary" @click.prevent="readAllNotifications">
                        Read All
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="bookings">
                    <thead>
                    <tr class="bg-blue">
                        <th>
                            <input type="checkbox" v-model="allSelected" v-on:change="selectAll"/>
                        </th>
                        <th>{{ $t('admin.notification.index.table.headers.title') }}</th>
                        <th>
                            {{ $t('admin.notification.index.table.headers.created_at') }}
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
                        <th>{{ $t('admin.notification.index.table.headers.updated_at') }}</th>
                        <th>{{ $t('common.word.actions') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-if="!isLoading">
                        <tr v-for="(notification, i) in notifications" :key="`notification_${i}`">
                            <td>
                                <input type="checkbox" v-model="selected.ids" :value="notification.id"/>
                            </td>
                            <td>
                                {{ notification.data.title }}
                                <span class="badge badge-pill badge-success" v-if="!notification.read_at">
                                    {{ $t('admin.notification.index.table.headers.common.new') }}
                                </span>
                            </td>
                            <td>{{ notification.created_at }}</td>
                            <td>{{ notification.updated_at }}</td>
                            <td>
                                <a :href="$r('admin.notification.view', { notification: notification.id })">
                                    <i class="icon-eye"></i>
                                </a>
                                <delete-confirmation
                                        :route-path="$r('admin.notification.delete', { notification: notification.id })"
                                        :redirect-path="$r('admin.notification.index')"
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
                    aria-controls='notifications'
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
        components: {
            InfiniteLoading,
        },

        mixins: [IndexPageHelper, FormHelper],

        data() {
            return {
                filters: {
                    page: 1,
                    by: 'created_at',
                    dir: 'desc',
                },
                notifications: [],
                selected: {
                    ids: [],
                },
                formData: null,
                total: null,
                isLoading: true,
                errors: {},
                allSelected: false,
            };
        },

        watch: {
            filters: {
                handler() {
                    this.debouncedGetNotifications();
                },
                deep: true,
            },
        },

        methods: {
            getNotifications() {
                this.isLoading = true;

                axios.get(
                    Router.route(
                        'admin.notification.all',
                        _.pickBy(this.filters, _.identity)
                    ),
                ).then(({ data }) => {
                    this.notifications = data.data;
                    this.total = data.total;
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                }).finally(() => {
                    this.isLoading = false;
                })
            },

            readNotifications() {
                this.formData = new FormData();
                this.formData.set('_method', 'POST');
                this.collectFormData(this.selected);
                
                axios.post(
                    Router.route(
                        'admin.notification.read'
                    ),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(({ data }) => {
                    location.reload();
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },

            readAllNotifications() {
                axios.post(
                    Router.route('admin.notification.read-all')
                ).then(({ data }) => {
                    location.reload();
                }).catch(({ response: { data: { errors } } }) => {
                    notify.error(_.head(errors));
                });
            },

            selectAll() {
                if (this.allSelected) {
                    _.each(this.notifications, (value, key) => {
                        this.selected.ids.push(value.id);
                    });
                } else {
                    this.selected.ids = [];
                }
            },

            sort(field, direction) {
                this.filters.by = field;
                this.filters.dir = direction;
            },
        },

        created() {
            this.getNotifications();

            this.debouncedGetNotifications = _.debounce(this.getNotifications, 500);
        },
    };
</script>
