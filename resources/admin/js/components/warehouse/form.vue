<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.warehouse.form.category_id') }}</strong>
                        </label>
                        <input
                            name="category_id"
                            type="text"
                            v-model="model.category_id"
                            class="form-control"
                            :class="{ 'border-danger': errors.category_id }"
                        >
                        <div v-for="(error, i) in errors.category_id"
                             :key="`category_id__error__${i}`"
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
                        @click.prevent="deleteWarehouse"
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

    export default {
        props: {
            model: {
                type: Object,
                required: true,
            },
            errors: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        methods: {
            submit() {
                this.$emit('submit', this.model);
            },

            deleteWarehouse() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },
        },
    };
</script>
