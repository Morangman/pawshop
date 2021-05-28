<template>
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col col-auto">
                    <a :href="$r('admin.callback.create')" class="btn btn-labeled btn-labeled-right bg-blue heading-btn">
                        <b><i class="icon-add"></i></b>
                        {{ $t('admin.callback.index.header_btn') }}
                    </a>
                </div>
                <div class="col col-md-5">
                    <input
                        v-model="filters.search"
                        name="search"
                        type="text"
                        class="form-control"
                        :placeholder="$t('admin.callback.index.filters.search')"
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
                                {{ $t('admin.callback.index.table.headers.name') }}
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
                            <th>Email</th>
                            <th>{{ $t('admin.callback.index.table.headers.text') }}</th>
                            <th>
                                {{ $t('admin.callback.index.table.headers.created_at') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'updated_at' && filters.dir === 'desc'"
                                            @click.prevent="sort('updated_at', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'updated_at' && filters.dir === 'asc'"
                                            @click.prevent="sort('updated_at', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'updated_at'" @click.prevent="sort('updated_at', 'asc')">
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
                            <tr v-for="(callback, i) in callbacks" :key="`callback_${i}`">
                                    <td>
                                        <a :href="$r('admin.callback.edit', { callback: callback.id })">
                                            <span v-html="highlightSearchResult(callback.name, filters.search)"></span>
                                            <span class="badge badge-pill badge-success" v-if="callback.new_messages.length">
                                                +{{ callback.new_messages.length }}
                                            </span>
                                        </a>
                                    </td>
                                    <td>{{ callback.email }}</td>
                                    <td><span v-html="callback.text ? callback.text.substring(0,30)+'..' : ''"></span></td>
                                    <td>{{ callback.updated_at }}</td>
                                    <td>
                                        <a :href="$r('admin.callback.edit', { callback: callback.id })">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        <delete-confirmation
                                            :route-path="$r('admin.callback.delete', { callback: callback.id })"
                                            :redirect-path="$r('admin.callback.index')"
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
                aria-controls='callbacks'
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
                    by: 'updated_at',
                    dir: 'desc',
                },
                callbacks: [],
                total: null,
                isLoading: true,
            };
        },

        watch: {
            filters: {
                handler() {
                    this.debouncedGetCallbacks();
                },
                deep: true,
            },
        },

        methods: {
            getCallbacks() {
                this.isLoading = true;

                axios.get(
                    Router.route(
                        'admin.callback.all',
                        _.pickBy(this.filters, _.identity)
                    ),
                ).then(({ data }) => {
                    this.callbacks = data.data;
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
            this.getCallbacks();

            this.debouncedGetCallbacks =_.debounce(this.getCallbacks, 500);
        },
    };
</script>
