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
                            <strong>{{ $t('admin.task.form.videos') }}</strong>
                        </label>
                        <div class="form-group overflow-box">
                            <div
                                v-if="model.id"
                                v-for="(media, i) in model.task_videos"
                                :key="`task_file__${i}`"
                                class="row"
                            >
                                <div class="col-8">
                                    <a :href="media.url" target="_blank">{{ media.url }}</a>
                                </div>
                            </div>
                            <b-form-file
                                v-model="model.task_videos"
                                class="mt-1"
                                multiple
                                @change="showTaskFiles"
                            ></b-form-file>
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

        data() {
            return {
                taskFiles: [],
            };
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

            showTaskFiles(e) {
                this.taskFiles = [];

                const files = e.target.files;

                _.each(files, (value) => {
                    this.taskFiles.push(URL.createObjectURL(value));
                });
            },

            handleDocumentDeleted(media) {
                this.model.task_vieos = _.filter(
                    this.model.task_vieos,
                    (file) => {
                        return file.id !== media.id;
                    });

                notify.success(
                    'File success deleted!'
                );
            },
        },
    };
</script>
