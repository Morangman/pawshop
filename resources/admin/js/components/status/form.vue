<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.order-status.form.name') }}</strong>
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
                            <strong>{{ $t('admin.order-status.form.color') }}</strong>
                        </label>
                        <v-swatches
                            v-model="model.color"
                            popover-x="left"
                            swatches="text-advanced"
                            show-fallback
                            fallback-input-type="color"
                        ></v-swatches>
                        <div v-for="(error, i) in errors.color"
                             :key="`color__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.order-status.form.order') }}</strong>
                        </label>
                        <input
                            name="order"
                            type="text"
                            v-model="model.order"
                            class="form-control"
                            :class="{ 'border-danger': errors.order }"
                        >
                        <div v-for="(error, i) in errors.order"
                             :key="`order__error__${i}`"
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
                        @click.prevent="deleteOrderStatus"
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

        data() {
            return {
                color: '#000000',
            };
        },

        methods: {
            submit() {
                this.$emit('submit', this.model);
            },

            deleteOrderStatus() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },
        },

        created() {
            if (!this.model.id) {
                this.model.color = '#000000';
            }
        },
    };
</script>
