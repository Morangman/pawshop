<template>
    <div class="row">
        <div class="col-xl-8 col-lg-8 col-md-10 col-sm-10 mx-auto form p-1">
            <div class="card">
                <div class="card-body">
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.user.form.name') }}</strong>
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
                            <strong>{{ $t('admin.user.form.email') }}</strong>
                        </label>
                        <input
                            name="email"
                            type="text"
                            v-model="model.email"
                            class="form-control"
                            :class="{ 'border-danger': errors.email }"
                        >
                        <div v-if="model.register_code" class="text-danger error">
                            Email not confirmed
                        </div>
                        <div v-if="!model.register_code" class="text-success error">
                            Email confirmed
                        </div>
                        <div v-for="(error, i) in errors.email"
                             :key="`email__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.user.form.phone') }}</strong>
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
                            <strong>{{ $t('admin.user.form.role') }}</strong>
                        </label>
                        <select class="form-control" id="role" name="role" v-model="model.role" :class="{ 'border-danger': errors.role }">
                            <option v-for="(role, i) in $t('admin.user.roles')" :key="`sex_${i}`" :value="i">{{ i }}</option>
                        </select>
                        <div v-for="(error, i) in errors.role"
                             :key="`role_error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.user.form.password') }}</strong>
                        </label>
                        <input
                            name="password"
                            type="password"
                            v-model="model.password"
                            class="form-control"
                            :class="{ 'border-danger': errors.password }"
                        >
                        <div v-for="(error, i) in errors.password"
                             :key="`password__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>{{ $t('admin.user.form.password_confirmation') }}</strong>
                        </label>
                        <input
                            name="password_confirmation"
                            type="password"
                            v-model="model.password_confirmation"
                            class="form-control"
                            :class="{ 'border-danger': errors.password_confirmation }"
                        >
                        <div v-for="(error, i) in errors.password_confirmation"
                             :key="`password_confirmation__error__${i}`"
                             class="text-danger error"
                        >
                            {{ error }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label>
                            <strong>Subscribed</strong>
                        </label>
                        <p>{{ model.mail_subscription ? 'Yes' : 'No' }}</p>
                    </div>
                </div>
            </div>
            <div class="text-right">
                <template v-if="model.id">
                    <button
                        type="submit"
                        class="btn btn-danger"
                        @click.prevent="deleteUser"
                    >
                        {{ $t('common.word.delete') }}
                    </button>
                    <button
                        v-if="model.register_code"
                        type="submit"
                        class="btn btn-primary"
                        @click.prevent="sendVerificationEmail()"
                    >
                        Send Verification Mail
                    </button>
                    <button
                        type="submit"
                        class="btn btn-primary"
                        @click.prevent="blockEmail()"
                    >
                        <span v-if="!model.is_blocked">Block</span>
                        <span v-if="model.is_blocked">Deblock</span>
                        this Email
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

            sendVerificationEmail() {
                this.$emit('verify', this.model);
            },

            blockEmail() {
                this.$emit('block', this.model);
            },

            deleteUser() {
                confirmation.delete(() => {
                    this.$emit('delete');
                });
            },
        },
    };
</script>
