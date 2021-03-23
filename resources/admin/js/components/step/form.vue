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
                            <strong>{{ $t('admin.step.form.title') }}</strong>
                        </label>
                        <input
                            name="name"
                            type="text"
                            v-model="model.title"
                            class="form-control"
                            :class="{ 'border-danger': errors.title }"
                        >
                        <div v-for="(error, i) in errors.title"
                             :key="`title_error__${i}`"
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
                            <strong>{{ $t('admin.step.form.is_functional') }}</strong>
                        </label>
                        <b-form-checkbox
                            v-model="model.is_functional"
                            value="1"
                            unchecked-value="0"
                        >
                        </b-form-checkbox>
                        <div v-for="(error, i) in errors.is_functional"
                             :key="`is_functional__error__${i}`"
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
                            v-model="model.is_checkbox"
                            value="1"
                            unchecked-value="0"
                        >
                        </b-form-checkbox>
                        <div v-for="(error, i) in errors.is_checkbox"
                             :key="`is_checkbox__error__${i}`"
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
                            <div class="change-blocks-wrapper__item" v-for="(item, index) in model.steps">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-right">
                                            <a href="javascript:void(0)" class="text-danger" v-on:click="deleteStepItem(item, index)">
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
                                                        v-model="item.value"
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
                                                        v-model="item.decryption"
                                                        type="text"
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
                        <div v-for="(error, i) in errors.steps"
                             :key="`steps__error__${i}`"
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
            steps: {
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
                this.model.steps.push({
                    value: null,
                    decryption: null,
                });

                this.$forceUpdate();
            },

            deleteStepItem(item, index) {
                if (item.id) {
                    window.swal({
                        title: this.$t('common.phrase.confirm.title'),
                        text: this.$t('common.phrase.confirm.body'),
                        icon: 'warning',
                        buttons: [this.$t('common.word.cancel'), this.$t('common.word.confirm')],
                    }).then((result) => {
                        if (!result) {
                            return
                        }

                        axios.delete(Router.route('admin.step-item.delete-item', {step: item.id}))
                            .then(() => {
                                location.href = Router.route('admin.step.edit', {stepName: this.model.id});
                            })
                            .catch(({response: {data: {errors}}}) => {
                                notify.error(_.head(errors));
                            });
                    });
                } else {
                    this.model.steps.splice(index, 1);
                }

                this.$forceUpdate();
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
                this.model.is_checkbox = Number(this.model.is_checkbox);
                this.model.is_functional = Number(this.model.is_functional);

                this.model.steps = this.steps;
            }
        },
    };
</script>
