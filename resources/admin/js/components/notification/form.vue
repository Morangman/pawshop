<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ model.data.title }}</strong>
                        </label>
                    </div>
                    <div class="form-group" v-if="model.data.text">
                        <label>
                            <strong>{{ $t('admin.notification.form.text') }}</strong>
                        </label>
                        <p>{{ model.data.text }}</p>
                    </div>
                    <div class="form-group" v-if="model.data.order_id">
                        <label>
                            <strong>{{ $t('admin.notification.form.order_id') }}</strong>
                        </label>
                        <a :href="$r('admin.order.edit', { order: model.data.order_id })">{{ model.data.order_id }}</a>
                    </div>
                    <div class="form-group" v-if="model.data.comment_id">
                        <label>
                            <strong>{{ $t('admin.notification.form.comment_id') }}</strong>
                        </label>
                        <a :href="$r('admin.comment.edit', { comment: model.data.comment_id })">{{ model.data.comment_id }}</a>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.notification.form.created_at') }}</strong>
                        </label>
                        <p>{{ model.created_at }}</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <template v-if="model.id">
                    <button
                            type="submit"
                            class="btn btn-danger"
                            @click.prevent="deleteNotification"
                    >
                        {{ $t('common.word.delete') }}
                    </button>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            model: {
                type: Object,
                required: true,
            },
        },

        methods: {
            deleteNotification() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },
        }
    };
</script>
