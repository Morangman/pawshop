<template>
    <section class="account-section">
        <div class="container">
            <h1>My Account</h1>
            <b-card no-body>
                <b-tabs card>
                    <b-tab title="Account Info" active>
                        <div class="row checkout-content-step">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>
                                                <p>First and Last Name</p>
                                            </label>
                                            <input
                                                name="name"
                                                type="text"
                                                v-model="model.name"
                                                class="form-control"
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
                                                <p>Email</p>
                                            </label>
                                            <input
                                                name="email"
                                                type="email"
                                                v-model="model.email"
                                                class="form-control"
                                            >
                                            <div v-for="(error, i) in errors.email"
                                                 :key="`email__error__${i}`"
                                                 class="text-danger error"
                                            >
                                                {{ error }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>
                                                <p>Phone</p>
                                            </label>
                                            <input
                                                name="phone"
                                                type="tel"
                                                v-model="model.phone"
                                                class="form-control"
                                            >
                                            <div v-for="(error, i) in errors.phone"
                                                 :key="`phone__error__${i}`"
                                                 class="text-danger error"
                                            >
                                                {{ error }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </b-tab>
                    <b-tab title="Addresses">
                        <div class="row checkout-content-step">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="form-group">
                                            <label>
                                                <p>Phone</p>
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
            <div class="bottom-links">
                <button v-on:click="update" type="submit" class="btn red-btn">
                    Save Account Details
                </button>
            </div>
        </div>
    </section>
</template>

<script>
    import FormHelper from "../../admin/js/mixins/form_helper";

    export default {
        mixins: [FormHelper],

        props: {
            user: {
                type: Object,
                required: true,
            },
        },

        data() {
            return {
                model: this.user,
                errors: {},
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
                });
            },
        }
    }
</script>
