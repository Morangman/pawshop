<template>
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col col-auto">
                    <a :href="$r('admin.product.create')" class="btn btn-labeled btn-labeled-right bg-blue heading-btn">
                        <b><i class="icon-add"></i></b>
                        {{ $t('admin.product.index.header_btn') }}
                    </a>
                </div>
                <div class="col col-md-2">
                    <a :href="$r('admin.product.addpercent')" class="btn btn-primary" style="color: #fff;">
                        +20%
                    </a>
                    <a :href="$r('admin.product.minuspercent')" class="btn btn-primary" style="color: #fff;">
                        -20%
                    </a>
                </div>
                <div class="col col-md-5">
                    <input
                        v-model="filters.search"
                        name="search"
                        type="text"
                        class="form-control"
                        :placeholder="$t('admin.product.index.filters.search')"
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
                                {{ $t('admin.product.index.table.headers.name') }}
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
                            <th>
                                {{ $t('admin.product.form.view_count') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'view_count' && filters.dir === 'desc'"
                                            @click.prevent="sort('view_count', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'view_count' && filters.dir === 'asc'"
                                            @click.prevent="sort('view_count', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'view_count'" @click.prevent="sort('view_count', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th>
                                {{ $t('admin.product.form.cart_count') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'box_count' && filters.dir === 'desc'"
                                            @click.prevent="sort('box_count', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'box_count' && filters.dir === 'asc'"
                                            @click.prevent="sort('box_count', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'box_count'" @click.prevent="sort('box_count', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th>
                                {{ $t('admin.product.index.table.headers.created_at') }}
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
                            <tr v-for="(category, i) in categories" :key="`category_${i}`">
                                    <td>
                                        <a :href="$r('admin.product.edit', { category: category.id })">
                                            <span v-html="highlightSearchResult(category.name, filters.search)"></span>
                                        </a>
                                    </td>
                                    <td>{{ category.view_count }}</td>
                                    <td>{{ category.box_count }}</td>
                                    <td>{{ category.created_at }}</td>
                                    <td>
                                        <a :href="$r('admin.product.edit', { category: category.id })">
                                            <i class="icon-pencil"></i>
                                        </a>
                                        <delete-confirmation
                                            :route-path="$r('admin.product.delete', { category: category.id })"
                                            :redirect-path="$r('admin.product.index')"
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
                aria-controls='categories'
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
                categories: [],
                total: null,
                isLoading: true,
            };
        },

        watch: {
            filters: {
                handler() {
                    this.debouncedGetCategories();
                },
                deep: true,
            },
        },

        methods: {
            getCategories() {
                this.isLoading = true;

                axios.get(
                    Router.route(
                        'admin.product.all',
                        _.pickBy(this.filters, _.identity)
                    ),
                ).then(({ data }) => {
                    this.categories = data.data;
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
            this.getCategories();

            this.debouncedGetCategories =_.debounce(this.getCategories, 500);
        },
    };
</script>
