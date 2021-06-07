<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.coupon.form.category_id') }}</strong>
                        </label>
                        <model-list-select :list="categories"
                                        v-model="model.category_id"
                                        option-value="id"
                                        :custom-text="name"
                                        placeholder="select item">
                        </model-list-select>
                        <div v-for="(error, i) in errors.category_id"
                             :key="`category_id__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.coupon.form.name') }}</strong>
                        </label>
                        <input
                            name="name"
                            type="text"
                            v-model="model.name"
                            class="form-control"
                            :class="{ 'border-danger': errors.name }"
                        >
                        <div v-for="(error, i) in errors.name"
                             :key="`name__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.coupon.form.code') }}</strong>
                        </label>
                        <input
                            name="code"
                            type="text"
                            v-model="model.code"
                            class="form-control"
                            :class="{ 'border-danger': errors.code }"
                        >
                        <div v-for="(error, i) in errors.code"
                             :key="`code__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.coupon.form.percent_value') }}</strong>
                        </label>
                        <input
                            name="percent_value"
                            type="text"
                            v-model="model.percent_value"
                            class="form-control"
                            :class="{ 'border-danger': errors.percent_value }"
                        >
                        <div v-for="(error, i) in errors.percent_value"
                             :key="`percent_value__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.coupon.form.text') }}</strong>
                        </label>
                        <input
                            name="text"
                            type="text"
                            v-model="model.text"
                            class="form-control"
                            :class="{ 'border-danger': errors.text }"
                        >
                        <div v-for="(error, i) in errors.text"
                             :key="`text__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.coupon.form.is_hidden') }}</strong>
                        </label>
                        <b-form-checkbox
                            v-model="model.is_hidden"
                            value="1"
                            unchecked-value="0"
                        >
                            {{ $t('admin.category.form.hidden') }}
                        </b-form-checkbox>
                        <div v-for="(error, i) in errors.is_hidden"
                             :key="`is_hidden__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>

                </div>
            </div>
            <div class="text-right">
                <template v-if="model.id">
                    <button
                        type="submit"
                        class="btn btn-danger"
                        @click.prevent="deleteCoupon"
                    >
                        {{ $t('common.word.delete') }}
                    </button>
                </template>
                <button
                    type="submit"
                    class="btn btn-primary"
                    @click.prevent="submit"
                >
                    {{ $t('common.word.save') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    import FormHelper from '../../mixins/form_helper';
    import { ModelListSelect } from 'vue-search-select';

    export default {
        props: {
            model: {
                type: Object,
                required: true,
            },
            categories: {
                type: Array,
                required: true,
            },
            errors: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        components: {
            ModelListSelect,
        },

        methods: {
            name (item) {
                return `${item.name}`
            },

            submit() {
                this.$emit('submit', this.model);
            },

            deleteCoupon() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },
        },


        created() {
            if (this.model.id) {
                this.model.is_hidden = Number(this.model.is_hidden);
            }
        },
    };
</script>
