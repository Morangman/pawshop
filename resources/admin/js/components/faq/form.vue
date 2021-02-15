<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.faq.form.name') }}</strong>
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
                        <strong>
                            <h1>{{ $t('admin.faq.index.title') }}</h1>
                        </strong>
                        <div class="form-group">
                            <div class="change-blocks-wrapper__item" v-for="(item, index) in model.data" :key="`faq_${index}`">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="text-right">
                                            <a href="javascript:void(0)" class="text-danger" v-on:click="deleteFaqItem(index)">
                                                {{ $t('common.word.remove') }}
                                            </a>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>
                                                        <strong>{{ $t('admin.faq.form.faq_name') }}</strong>
                                                    </label>
                                                    <input
                                                        name="title"
                                                        type="text"
                                                        v-model="item.title"
                                                        class="form-control"
                                                    >
                                                </div>
                                                <div class="form-group">
                                                    <label>
                                                        <strong>{{ $t('admin.faq.form.faq_text') }}</strong>
                                                    </label>
                                                    <vue-editor v-model="item.text"></vue-editor>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <button
                                    v-on:click="addFaqItem"
                                    class="btn btn-primary margin-top-10"
                                >{{ $t('common.word.add') }}</button>
                            </div>
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
            <div class="text-right">
                <template v-if="model.id">
                    <button
                        type="submit"
                        class="btn btn-danger"
                        @click.prevent="deleteFaq"
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
    import { VueEditor } from "vue2-editor";

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

        components: {
            VueEditor
        },

        methods: {
            submit() {
                this.$emit('submit', this.model);
            },

            addFaqItem() {
                this.model.data.push({
                    title: null,
                    text: null,
                });

                this.$forceUpdate();
            },

            deleteFaqItem(index) {
                this.model.data.splice(index, 1);

                this.$forceUpdate();
            },

            name (item) {
                return `${item.name}`
            },

            deleteFaq() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },
        },
    };
</script>
