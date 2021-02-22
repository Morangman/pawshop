<template>
    <section class="account-section">
        <div class="container">
            <h1>My Account</h1>
            <b-card no-body>
                <b-tabs card>
                    <b-tab title="Account Info" active>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>
                                                <strong>First and Last Name</strong>
                                            </label>
                                            <input
                                                name="name"
                                                type="text"
                                                v-model="model.name"
                                                class="form-control"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>
                                    <h4>dfgd</h4>
                                </label>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>
                                                <strong>Email</strong>
                                            </label>
                                            <input
                                                name="email"
                                                type="mail"
                                                v-model="model.email"
                                                class="form-control"
                                            >
                                        </div>
                                        <div class="form-group">
                                            <label>
                                                <strong>Phone</strong>
                                            </label>
                                            <input
                                                name="phone"
                                                type="tel"
                                                v-model="model.phone"
                                                class="form-control"
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                </b-tabs>
            </b-card>
        </div>
    </section>
</template>

<script>
    export default {
        props: {
            user: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
                model: this.user,
            };
        },

        methods: {
            update() {
                this.errors = {};
                this.formData = new FormData();
                this.formData.set('_method', 'PATCH');
                this.collectFormData(this.model);

                axios.post(
                    Router.route('update-account', { user: this.user.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('account');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        }
    }
</script>
