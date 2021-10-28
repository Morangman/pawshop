<template>
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col col-md-2">
                        <datetime type="date"
                            placeholder="Yesterday"
                            v-model="filters.date"
                            input-class="form-control"
                            :format="{ year: 'numeric', month: 'long', day: 'numeric' }">
                        </datetime>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="bookings">
                    <thead>
                        <tr class="bg-blue">
                            <th>
                                {{ $t('admin.statistics.form.image') }}
                            </th>
                            <th>
                                {{ $t('admin.statistics.form.title') }}
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
                                ({{ views }}) {{ $t('admin.statistics.form.view_count') }}
                                <span>
                                    <i
                                            v-if="filters.by === 'steps_view_count' && filters.dir === 'desc'"
                                            @click.prevent="sort('steps_view_count', 'asc')"
                                            class="icon-arrow-down8 cursor-pointer"
                                    ></i>
                                    <i
                                            v-if="filters.by === 'steps_view_count' && filters.dir === 'asc'"
                                            @click.prevent="sort('steps_view_count', 'desc')"
                                            class="icon-arrow-up8 cursor-pointer"
                                    ></i>
                                    <span v-if="filters.by !== 'steps_view_count'" @click.prevent="sort('steps_view_count', 'asc')">
                                        <i class="icon-arrow-up8 cursor-pointer"></i>
                                        <i class="icon-arrow-down8 cursor-pointer"></i>
                                    </span>
                                </span>
                            </th>
                            <th>
                                {{ $t('admin.statistics.form.coefficient') }}
                            </th>
                            <th>
                                Price
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="!isLoading">
                            <tr v-for="(product, i) in products" :key="`status_${i}`">
                                    <td><img style="width: 100px; height: auto;" :src="product.image"/></td>
                                    <td><a :href="$r('admin.product.edit', { category: product.category_id })">{{ product.title }}</a></td>
                                    <td>{{ product.steps_view_count }}</td>
                                    <td>{{ product.steps_coefficient }}</td>
                                    <td>{{ product.price }}</td>
                                </tr>
                        </template>
                    </tbody>
                </table>
                <spinner :is-loading="isLoading" class="m-4"></spinner>
            </div>
            <b-pagination
                v-model='filters.page'
                :total-rows='total'
                aria-controls='all_statistics'
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
                    date: null,
                    by: 'steps_view_count',
                    dir: 'desc',
                },
                products: [],
                total: null,
                views: 0,
                isLoading: true,
            };
        },

        watch: {
            filters: {
                handler() {
                    this.debouncedGetAllProducts();
                },
                deep: true,
            },
        },

        methods: {
            getProducts() {
                this.isLoading = true;

                axios.get(
                    Router.route(
                        'admin.all-daily-statistics.all',
                        _.pickBy(this.filters, _.identity)
                    ),
                ).then(({ data }) => {
                    this.products = data.data.data;
                    this.total = data.data.total;
                    this.views = data.viewsCount;
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
            //this.getProducts();

            this.debouncedGetAllProducts =_.debounce(this.getProducts, 500);
        },
    };
</script>
