<template>
    <div class="setting-form">
        <b-card no-body>
            <b-tabs card>
                <b-tab :title="$t('admin.setting.form.contacts.title')" active>
                    <div class="row">
                    <div class="col-md-6">
                        <label>
                            <h4>{{ $t('admin.setting.form.contacts.title') }}</h4>
                        </label>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>{{ $t('admin.setting.form.contacts.phone') }}</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.phone"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>
                            <h4>{{ $t('admin.setting.form.seo.title') }}</h4>
                        </label>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>{{ $t('admin.setting.form.seo.seo_title') }}</strong>
                                    </label>
                                    <input
                                        name="seo-title"
                                        type="text"
                                        v-model="model.general_settings.seo_title"
                                        class="form-control"
                                    >
                                </div>
                                <div class="form-group">
                                    <label>
                                        <strong>{{ $t('admin.setting.form.seo.seo_meta') }}</strong>
                                    </label>
                                    <input
                                        name="seo-meta"
                                        type="text"
                                        v-model="model.general_settings.seo_meta"
                                        class="form-control"
                                    >
                                </div>
                                <div class="form-group">
                                    <label>
                                        <strong>{{ $t('admin.setting.form.seo.seo_keywords') }}</strong>
                                    </label>
                                    <input
                                        name="seo-keywords"
                                        type="text"
                                        v-model="model.general_settings.seo_keywords"
                                        class="form-control"
                                    >
                                </div>
                                <div class="form-group">
                                    <label>
                                        <strong>{{ $t('admin.setting.form.seo.seo_image') }}</strong>
                                    </label>
                                    <b-form-file
                                        class="mt-1"
                                        accept=".png,.jpg,.jpeg,.gif"
                                        @change="showSeoPreviewImage($event)"
                                    ></b-form-file>
                                    <img width="auto"
                                         height="100"
                                         class="center-image"
                                         v-if="seoPreviewImage"
                                         :src="seoPreviewImage"
                                         v-on:click="deleteSeoImage"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                </b-tab>
                <b-tab title="Endpoints for parser">
                    <div class="col-md-12">
                        <label>
                            <h4>Endpoints for parser</h4>
                        </label>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Base path</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.base_path"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>IPhones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.iphones_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Samsung phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.samsung_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>HTC phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.htc_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Motorola phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.motorola_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>LG phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.lg_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>OnePlus phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.onePlus_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Google phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.google_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Sony phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.sony_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>BlackBerry phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.blackBerry_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Huawei phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.huawei_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Kyocera phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.kyocera_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>ZTE phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.zte_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Xiaomi phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.xiaomi_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Razer phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.razer_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Nokia phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.nokia_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Asus phones</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.asus_phones"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>iPads</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.ipads_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Samsung tablets</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.samsung_tablets_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Microsoft tablets</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.microsoft_tablets_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>IPods</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.iPods_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>GoPro</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.gopro_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Xbox</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.xbox_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Playstation</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.playstation_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Nintendo Switch</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.switch_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Nintendo DS</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.ds_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>
                                        <strong>Apple Watch</strong>
                                    </label>
                                    <input
                                        name="phone"
                                        type="text"
                                        v-model="model.general_settings.appleWatch_point"
                                        class="form-control"
                                    >
                                </div>
                            </div>
                        </div>

                    </div>
                </b-tab>
                <b-tab title="Terms and Conditions">
                    <label>
                        <h4>Terms and Conditions</h4>
                    </label>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <vue-editor v-model="model.terms"></vue-editor>
                            </div>
                        </div>
                    </div>
                </b-tab>
                <b-tab :title="$t('admin.setting.form.code_insert.title')">
                    <label>
                        <h4>{{ $t('admin.setting.form.code_insert.title') }}</h4>
                    </label>
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <editor
                                    v-model="model.code_insert"
                                    lang="javascript"
                                    theme="chrome"
                                    width="auto"
                                    height="500"
                                    class="form-control"
                                ></editor>
                            </div>
                        </div>
                    </div>
                </b-tab>
            </b-tabs>
        </b-card>
        <div v-for="(error, i) in errors.general_settings"
             :key="`general_settings__error__${i}`"
             class="text-danger error"
        >
            {{ error }}
        </div>
        <div v-for="(error, i) in errors.code_insert"
             :key="`code_insert__error__${i}`"
             class="text-danger error"
        >
            {{ error }}
        </div>
        <div class="text-right">
            <template v-if="!settings.id">
                <button
                    type="submit"
                    class="btn btn-primary"
                    @click.prevent="save"
                >
                    {{ $t('common.word.save') }}
                </button>
            </template>
            <template v-if="settings.id">
            <button
                type="submit"
                class="btn btn-primary"
                @click.prevent="update"
            >
                {{ $t('common.word.update') }}
            </button>
            </template>
        </div>
    </div>
</template>

<script>
    import FormHelper from "../../mixins/form_helper";
    import { VueEditor } from "vue2-editor";

    export default {
        mixins: [FormHelper],

        components: {
            editor: require('vue2-ace-editor'),
            VueEditor
        },

        props: {
            settings: {
                type: Object,
                required: false,
            },
        },

        data() {
            return {
                errors: {},
                formData: null,
                seoPreviewImage: null,
                model: {
                    general_settings: {
                        phone: null,
                        seo_title: null,
                        seo_meta: null,
                        seo_keywords: null,
                        seo_image: null,
                        base_path: null,
                        iphones_point: null,
                        samsung_point: null,
                        htc_phones: null,
                        motorola_phones: null,
                        lg_phones: null,
                        onePlus_phones: null,
                        google_phones: null,
                        sony_phones: null,
                        blackBerry_phones: null,
                        huawei_phones: null,
                        kyocera_phones: null,
                        zte_phones: null,
                        xiaomi_phones: null,
                        razer_phones: null,
                        nokia_phones: null,
                        asus_phones: null,
                        ipads_point: null,
                        samsung_tablets_point: null,
                        microsoft_tablets_point: null,
                        iPods_point: null,
                        gopro_point: null,
                        xbox_point: null,
                        playstation_point: null,
                        switch_point: null,
                        ds_point: null,
                        appleWatch_point: null,
                    },
                    terms: null,
                    code_insert: null,
                    is_seo_image_deleted: false,
                },
            };
        },

        methods: {
            showSeoPreviewImage(event) {
                const file = event.target.files[0];

                this.model.general_settings.seo_image = file;

                this.seoPreviewImage = URL.createObjectURL(file);

                this.$forceUpdate();
            },

            deleteSeoImage() {
                this.model.general_settings.seo_image = null;
                this.seoPreviewImage = null;

                this.model.is_seo_image_deleted = true;

                notify.success(
                    this.$t('admin.setting.messages.seo_image_delete')
                );
            },

            save() {
                this.errors = {};
                this.formData = new FormData();
                this.collectFormData(this.model);

                axios.post(
                    Router.route('admin.setting.store'),
                    this.formData,
                ).then(() => {
                    location.href = Router.route('admin.setting.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },

            update() {
                this.errors = {};
                this.formData = new FormData();
                this.formData.set('_method', 'PATCH');
                this.collectFormData(this.model);

                axios.post(
                    Router.route('admin.setting.update', { setting: this.model.id }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then(() => {
                    location.href = Router.route('admin.setting.index');
                }).catch(({ response: { data: { errors } } }) => {
                    this.errors = errors;
                    this.scrollToError();
                });
            },
        },

        created() {
            if (this.settings.id) {
                this.model = this.settings;

                this.seoPreviewImage = this.model.general_settings.seo_image;

                this.model.general_settings.seo_image = null;
            }
        },
    };
</script>
