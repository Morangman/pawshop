<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.task.form.name') }}</strong>
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
                            <strong>{{ $t('admin.task.form.text') }}</strong>
                        </label>
                        <vue-editor v-model="model.text"></vue-editor>

                        <div v-for="(error, i) in errors.text"
                             :key="`text__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.task.form.notes') }}</strong>
                        </label>
                        <vue-editor v-model="model.notes"></vue-editor>

                        <div v-for="(error, i) in errors.notes"
                             :key="`notes__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.task.form.task_status') }}</strong>
                        </label>
                        <select class="form-control" name="ordered_status" v-model="model.task_status" required :class="{ 'border-danger': errors.task_status }">
                            <option v-for="(status, i) in $t('admin.task.task_statuses')" :value="i">{{ status }}</option>
                        </select>
                        <div v-for="(error, i) in errors.task_status"
                             :key="`task_status__error__${i}`"
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

            deleteFaq() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },
        },
    };
</script>
