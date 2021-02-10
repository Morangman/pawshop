<template>
    <notification-form
            v-if="model"
            :model.sync="model"
            @delete="deleteNotification"
    >
    </notification-form>
</template>

<script>
    import NotificationFormComponent from './form';

    export default {
        components: {
            NotificationForm: NotificationFormComponent,
        },

        props: {
            notification: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
                model: this.notification,
            };
        },

        methods: {
            deleteNotification() {
                axios.delete(
                    Router.route('admin.notification.delete', { notification: this.notification.id }),
                ).then(() => {
                    location.href = Router.route('admin.notification.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                });
            },
        },
    };
</script>
