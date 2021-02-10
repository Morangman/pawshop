<template>
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col col-auto">
                    <a :href="$r('admin.comment.create')" class="btn btn-labeled btn-labeled-right bg-blue heading-btn">
                        <b><i class="icon-add"></i></b>
                        {{ $t('admin.comment.index.header_btn') }}
                    </a>
                </div>
                <div class="col col-md-5">
                    <input
                        v-model="filters.search"
                        name="search"
                        type="text"
                        class="form-control"
                        :placeholder="$t('admin.comment.index.filters.search')"
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
                                {{ $t('admin.comment.index.table.headers.id') }}
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
                                {{ $t('admin.comment.index.table.headers.name') }}
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
                            <th>{{ $t('admin.comment.index.table.headers.text') }}</th>
                            <th>{{ $t('admin.comment.index.table.headers.is_hidden') }}</th>
                            <th>
                                {{ $t('admin.comment.index.table.headers.created_at') }}
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
                            <tr v-for="(comment, i) in comments" :key="`comment_${i}`">
                                    <td><a :href="$r('admin.comment.edit', { comment: comment.id })">{{ comment.id }}</a></td>
                                    <td v-html="highlightSearchResult(comment.name, filters.search)"></td>
                                    <td>{{ comment.text }}</td>
                                    <td>{{ comment.is_hidden ? $t('common.word.yes') : $t('common.word.no') }}</td>
                                    <td>{{ comment.created_at }}</td>
                                    <td>
                                        <a :href="$r('admin.comment.edit', { comment: comment.id })">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        <delete-confirmation
                                            :route-path="$r('admin.comment.delete', { comment: comment.id })"
                                            :redirect-path="$r('admin.comment.index')"
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
                aria-controls='comments'
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
                comments: [],
                total: null,
                isLoading: true,
            };
        },

        watch: {
            filters: {
                handler() {
                    this.debouncedGetComments();
                },
                deep: true,
            },
        },

        methods: {
            getComments() {
                this.isLoading = true;

                axios.get(
                    Router.route(
                        'admin.comment.all',
                        _.pickBy(this.filters, _.identity)
                    ),
                ).then(({ data }) => {
                    this.comments = data.data;
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
            this.getComments();

            this.debouncedGetComments =_.debounce(this.getComments, 500);
        },
    };
</script>
