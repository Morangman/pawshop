<template>
    <a :class="classes" class="text-danger" href="#" @click.prevent="process()">
        <slot name="icon">
            <i class="icon-trash"></i>
        </slot>
        <slot></slot>
    </a>
</template>

<script>
    export default {
        props: {
            classes: {
                type: String,
            },
            routePath: {
                type: String,
                required: true,
            },

            redirectPath: {
                type: String,
                default: null,
            },

            removeElement: {
                type: String,
                default: null,
            },

            callback: {
                type: Function,
                default: () => {},
            },
        },

        methods: {
            process () {
                window.swal({
                    title: this.$t('common.phrase.confirm.title'),
                    text: this.$t('common.phrase.confirm.body'),
                    icon: 'warning',
                    buttons: [this.$t('common.word.cancel'), this.$t('common.word.confirm')],
                }).then((result) => {
                    if (!result) {
                        return
                    }

                    axios.delete(this.routePath)
                        .then(({data: {message}}) => {
                            this.callback(message)

                            this.$emit('item-deleted')

                            if (this.redirectPath) {
                                location.href = this.redirectPath
                            }

                            if (this.removeElement) {
                                document.getElementById(this.removeElement).remove()
                            }
                        })
                        .catch((response) => {
                            console.log(response);
                        });
                });
            },
        },
    }
</script>
