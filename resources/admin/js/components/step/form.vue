<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.step.form.name') }}</strong>
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
                            <strong>{{ $t('admin.step.form.tip') }}</strong>
                        </label>

                        <model-list-select :list="tips"
                                           v-model="model.tip_id"
                                           option-value="id"
                                           :custom-text="name"
                                           placeholder="select tip">
                        </model-list-select>
                    </div>

                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.step.form.is_condition') }}</strong>
                        </label>
                        <b-form-checkbox
                            v-model="model.is_condition"
                            value="1"
                            unchecked-value="0"
                        >
                        </b-form-checkbox>
                        <div v-for="(error, i) in errors.is_condition"
                             :key="`is_condition__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.step.form.is_checkboxes') }}</strong>
                        </label>
                        <b-form-checkbox
                            v-model="model.is_checkboxes"
                            value="1"
                            unchecked-value="0"
                        >
                        </b-form-checkbox>
                        <div v-for="(error, i) in errors.is_checkboxes"
                             :key="`is_checkboxes__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>

                    <div class="form-group">
                        <strong>
                            <h1>{{ $t('admin.step.form.items') }}</h1>
                        </strong>
                        <div class="form-group">
                            <div class="change-blocks-wrapper__item" v-for="(item, index) in model.items">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-right">
                                            <a v-if="!item.id" href="javascript:void(0)" class="text-danger" v-on:click="deleteStepItem(index)">
                                                {{ $t('common.word.remove') }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>
                                                        <strong>{{ $t('admin.step.form.name') }}</strong>
                                                    </label>
                                                    <input
                                                        name="text"
                                                        v-model="item.name"
                                                        type="text"
                                                        class="form-control"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>
                                                        <strong>{{ $t('admin.step.form.item_text') }}</strong>
                                                    </label>
                                                    <input
                                                        name="text"
                                                        v-model="item.text"
                                                        type="text"
                                                        class="form-control"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>
                                                        <strong>{{ $t('admin.step.form.price_minus') }}</strong>
                                                    </label>
                                                    <input
                                                        name="price_minus"
                                                        v-model="item.price_minus"
                                                        type="number"
                                                        class="form-control"
                                                    >
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        <strong>{{ $t('admin.step.form.price_plus') }}</strong>
                                                    </label>
                                                    <input
                                                        name="price_plus"
                                                        v-model="item.price_plus"
                                                        type="number"
                                                        class="form-control"
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button
                                    v-on:click="addStepItem"
                                    class="btn btn-primary margin-top-10"
                                >{{ $t('common.word.add') }}</button>
                            </div>
                        </div>
                        <div v-for="(error, i) in errors.items"
                             :key="`items__error__${i}`"
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
                        @click.prevent="deleteStep"
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
            tips: {
                type: Array,
                required: false,
            },
            errors: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        components: {
            ModelListSelect
        },

        methods: {
            submit() {
                this.$emit('submit', this.model);
            },

            addStepItem() {
                this.model.items.push({
                    name: null,
                    text: null,
                    price_plus: null,
                    price_minus: null,
                });
            },

            deleteStepItem(index) {
                this.model.items.splice(index, 1);
            },

            deleteStep() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },

            name (item) {
                return `${item.name}`
            },
        },

        created() {
            if (this.model.id) {
                this.model.is_condition = Number(this.model.is_condition);
                this.model.is_checkboxes = Number(this.model.is_checkboxes);
            }
        },
    };
</script>
