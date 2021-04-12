<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.comment.form.name') }}</strong>
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
                            <strong>{{ $t('admin.comment.form.email') }}</strong>
                        </label>
                        <input
                            name="email"
                            type="text"
                            v-model="model.email"
                            class="form-control"
                            :class="{ 'border-danger': errors.email }"
                        >
                        <div v-for="(error, i) in errors.email"
                             :key="`email__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.callback.form.phone') }}</strong>
                        </label>
                        <input
                            name="phone"
                            type="text"
                            v-model="model.phone"
                            class="form-control"
                            :class="{ 'border-danger': errors.phone }"
                        >
                        <div v-for="(error, i) in errors.phone"
                             :key="`phone__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.comment.form.text') }}</strong>
                        </label>
                        <textarea
                            name="text"
                            type="text"
                            rows="4"
                            v-model="model.text"
                            class="form-control"
                        ></textarea>
                        <div v-for="(error, i) in errors.text"
                             :key="`text__error__${i}`"
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
                        @click.prevent="deleteComment"
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

            deleteComment() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },
        },
    };
</script>
