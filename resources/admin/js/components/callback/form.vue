<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-header header-elements-inline">
                    <h5 v-if="!model.id" class="card-title"></h5>
                    <h5 v-if="model.id" class="card-title chat-title">
                        <p>Conversation with</p>
                        <a class="chat-name" v-if="user" :href="$r('admin.user.edit', {user: user.id})">{{ model.name }}</a>
                        <p class="chat-name" v-if="!user">{{ model.name }}</p>
                        <p class="chat-name">({{ model.email }})</p>
                    </h5>
                    <div class="header-elements">
                        <div class="list-icons">
                            <a class="list-icons-item" v-on:click="reload()" data-action="reload"></a>
                            <delete-confirmation
                                :route-path="$r('admin.callback.delete', { callback: model.id })"
                                :redirect-path="$r('admin.callback.index')"
                                :title="$t('common.word.delete')"
                            />
                        </div>
                    </div>
                </div>

                <div v-if="!model.id" class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>Email*</strong>
                        </label>
                        <input
                            name="email"
                            type="text"
                            v-model="message.email"
                            placeholder="Email*"
                            class="form-control col-md-5"
                        >
                    </div>
                </div>

                <div class="card-body">
                    <ul ref="container" style="max-height: 300px;" class="media-list media-chat media-chat-scrollable mb-3">
                        <li class="media content-divider justify-content-center text-muted mx-0">{{ model.created_at }}</li>
                
                        <li v-for="(message, i) in model.messages" :key="`message_${i}`" class="media" :class="message.sender === 2 ? 'media-chat-item-reverse' : ''">
                            <div class="media-body">
                                <div class="media-chat-item">
                                    <span v-html="message.simple_text ? message.simple_text : message.text"></span>
                                    <div class="files">
                                        <a v-for="(file, i) in message.files" target="_blank" :key="`file_${i}`" :href="file.url">
                                            <img :src="file.url" width="100" height="100" :alt="file.type">
                                        </a>
                                    </div>
                                </div>
                                <div class="font-size-sm text-muted mt-2">
                                    {{ message.time ? message.time : message.created_at }}
                                    <a href="javascript:0" v-if="message.simple_text && !message.simple_text_copy" v-on:click="showMore(i)">Show more</a>
                                    <a href="javascript:0" v-if="message.simple_text && message.simple_text_copy" v-on:click="hide(i)">Hide</a>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <textarea v-model="message.text" name="enter-message" class="form-control mb-3" rows="3" cols="1" placeholder="Enter your message..."></textarea>

                    <div class="text-right">
                        <button
                            class="btn btn-primary"
                            @click.prevent="blockEmail()"
                        >
                            <span v-if="!model.is_blocked">Block</span>
                            <span v-if="model.is_blocked">Deblock</span>
                            this Email
                        </button>
                        <button v-on:click="sendMessage" type="button" class="btn bg-teal-400 btn-labeled btn-labeled-right ml-auto"><b><i class="icon-paperplane"></i></b> Send</button>
                    </div>
                </div>
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
            user: {
                type: Object,
                required: false,
            },
            errors: {
                type: Object,
                required: true,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                message: {
                    email: this.model.email,
                    name: this.model.name,
                    text: null,
                },
            };
        },

        methods: {
            submit() {
                this.$emit('submit', this.model);
            },

            reload() {
                location.reload();
            },

            showMore(i) {
                this.model.messages[i].simple_text_copy = this.model.messages[i].simple_text;
                
                this.model.messages[i].simple_text = this.model.messages[i].text;
            },

            hide(i) {
                this.model.messages[i].simple_text = this.model.messages[i].simple_text_copy;

                this.model.messages[i].simple_text_copy = null;
            },

            sendMessage() {
                this.errors = {};
                this.formData = new FormData();
                this.collectFormData(this.message);

                axios.post(
                    Router.route('admin.callback.send-message'),
                    this.formData,
                ).then(() => {
                    location.reload();
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            blockEmail() {
                this.errors = {};
                this.formData = new FormData();
                this.collectFormData(this.message);

                axios.post(
                    Router.route('admin.callback.block', { callback: this.model.id }),
                    this.formData,
                ).then(() => {
                    location.reload();
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            deleteComment() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },

            scrollToEnd () {
                let content = this.$refs.container;
                content.scrollTop = content.scrollHeight;
            }
        },

        mounted() {
            this.scrollToEnd();
        }
    };
</script>
