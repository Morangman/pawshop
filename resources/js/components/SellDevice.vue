<template>
<div>
    <ul id="scrolled" class="order-steps-list" v-if="steps.length">
        <li v-for="(step, index) in steps" :class="stepIndex === index ? 'active-step' : ''">
            <a href="javascript:void(0)"><i>{{ index + 1 }}</i> <span>{{ index + 1 }}</span></a>
        </li>
        <li>
            <a href="javascript:void(0)"><i>{{ steps.length + 1 }}</i> <span>Finish </span></a>
        </li>
    </ul>

    <div class="order-options-content" v-if="steps.length">
        <div class="order-options-product">
            <div class="product-image">
                <img height="400" :src="category.image" :alt="category.name">
                <div class="name"><span>{{ category.name }}</span></div>
            </div>
            <ul id="options" class="selected-list" v-if="selectedSteps.length">
                <li v-for="option in selectedSteps" v-if="option">{{ option ? option.step_name.name + ': ' + option.value : '' }}</li>
                <li v-for="option in selectedAccesories" v-if="option && selectedAccesories.length">{{ option ? option.value : '' }}</li>
            </ul>
        </div>
        <div class="order-options-detail">

            <!-- Step 1 -->
            <div id="step" class="order-options-block" v-if="selectedStep">
                <h4 id="step-title">{{ selectedStep.title }}</h4>
                <div class="helping-block" v-if="selectedStep.tip">
                    <a href="javascript:void(0)" v-on:click="helpingPopup = true" data-effect="mfp-zoom-in">
                        <img src="../../client/images/icon_help.svg" alt="icon_help">
                        <span>{{ selectedStep.tip ? selectedStep.tip.name : '' }}</span>
                    </a>
                    
                    <div v-if="helpingPopup">
                        <div class="mfp-bg mfp-zoom-in mfp-ready"></div>
                        <div v-on:click="helpingPopup = false" class="mfp-wrap mfp-close-btn-in mfp-auto-cursor mfp-zoom-in mfp-ready" tabindex="-1" style="overflow: hidden auto;">
                            <div class="mfp-container mfp-s-ready mfp-inline-holder">
                                <div class="mfp-content">
                                    <div id="helping-popup-1" class="popup-modal mfp-with-anim">
                                        <div class="popup-content">
                                            <div class="helping-popup-content">
                                                <h4>{{ selectedStep.tip.name }}</h4>
                                                <p v-html="selectedStep.tip ? selectedStep.tip.text : ''"></p>
                                            </div>
                                            <button v-on:click="helpingPopup = false" type="button" title="Close (Esc)" class="mfp-close">
                                                <img src="/images/close.png?3365377fd075715b06b6510224785880" alt="close-png">
                                                <img src="/images/close_popup.png?c75e43c6a14689556029439fe821d637" alt="close-popup" class="sm-only">
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-options-radios">
                    <div class="options-radio" v-for="(option, index) in selectedStep.items" :key="`step${option.id}_${index}`">
                        <label class="radiobox-block" :class="selectedSteps[option.step_name.id] ? selectedSteps[option.step_name.id].id === option.id ? 'radio-bordered' : '' : ''" v-if="!selectedStep.is_checkbox">
                            <input v-model="selectedSteps[option.step_name.id]" v-on:click="selectOption(option, index)" :checked="!!selectedStep.items[index].checked" :value="option" type="radio" :name="`step-${selectedStep.id}-radios`">
                            <i></i>
                            <span>
                                <strong>{{ option.value }}</strong>
                                <small>{{ option.decryption }}</small>
                            </span>
                        </label>
                        <label class="checkbox-block" v-if="selectedStep.is_checkbox">
                            <input type="checkbox" v-model="selectedAccesories" v-bind:value="option"  :name="`step-${selectedStep.id}-checkbox`">
                            <i></i>
                            <span>
                                <strong>{{ option.value }}</strong>
                                <small>{{ option.decryption }}</small>
                            </span>
                        </label>
                    </div>
                    <div class="options-radio-detail" v-if="selectedStep.is_condition">
                        <h5>For a device to be in this condition. The following must also be true.</h5>
                        <ol>
                            <li>Zero scratches, scuffs or other marks. Looks like new.</li>
                            <li>Display is free of defects such as dead pixels, white spots, or burn-in.</li>
                        </ol>
                        <div class="links">
                            <button  class="btn" @click="openModal">Condition Examples</button>
                            <!-- <a href="/" class="play-link popup-youtube"><img src="../../client/images/play.svg" alt=""></a> -->
                        </div>
                    </div>
                </div>
                <div class="order-options-links">
                    <div><button v-on:click="backStep" v-if="stepIndex > 0" class="btn gray-btn step-button">Back</button></div>
                    <div><button v-on:click="nextStep" v-if="stepSelected || selectedStep.is_checkbox" class="btn red-btn step-button">Next step</button></div>
                </div>
            </div>

            <div class="order-options-block" v-if="!selectedStep">
                <h4>Your device is valued at</h4>
                <img v-if="!priceLoaded" style="display: block; margin-left: auto; margin-right: auto; width: 50%;" src="../../client/images/spinner.gif">
                <span v-if="priceLoaded">
                    <div class="order-total-block">
                        <div class="price">${{ summ }}</div>
                        <div class="order-options-links">
                            <button class="btn gray-btn" v-on:click="backStep">Back</button>
                            <button v-on:click="addToBox" class="btn red-btn">Add to box</button>
                        </div>
                    </div>
                    <div class="order-total-block" v-if="category.coupon && summ > 5 && !couponEntered">
                        <div class="coupon-input-block">
                            <input v-model="coupon" class="coupon-input" type="code" placeholder="Coupon code">
                        </div>
                        <div class="order-options-links">
                            <button v-on:click="enterCoupon" class="btn red-btn">Enter</button>
                        </div>
                    </div>
                    <div class="order-total-block" style="justify-content: center;" v-if="category.coupon && couponEntered">
                        <h4>Coupon activated!</h4>
                    </div>
                </span>

                <ul class="order-advantages-list">
                    <li>
                        <div class="pic"><img src="../../client/images/order_advantage_1.svg" alt="order-advantage-1"></div>
                        <div>
                            <p>No selling fees</p>
                            <div class="desc">Save up to 15% on marketplace selling fees.</div>
                        </div>
                    </li>
                    <li>
                        <div class="pic"><img src="../../client/images/order_advantage_2.svg" alt="order-advantage-2"></div>
                        <div>
                            <p>Zero fraud risk</p>
                            <div class="desc">We handle the bad guys.</div>
                        </div>
                    </li>
                    <li>
                        <div class="pic"><img src="../../client/images/order_advantage_3.svg" alt="order-advantage-3"></div>
                        <div>
                            <p>Free and Convenient shipping via FedEx or UPS</p>
                            <div class="desc">There's a drop-off around the corner.</div>
                        </div>
                    </li>
                    <li>
                        <div class="pic"><img src="../../client/images/order_advantage_4.svg" alt="order-advantage-4"></div>
                        <div>
                            <p>Optional 2-Day shipping and 24-Hour processing</p>
                            <div class="desc">We get it. Sometimes you just can't wait!</div>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="faqs-content" v-if="!faqsIsset">
        <h2>FAQs</h2>
        <div class="faqs-block" v-for="(faq, index) in faqs.data" :key="`faq_${index}`">
            <div class="faqs-question">{{ faq.title }}</div>
            <div class="faqs-answer" v-html="faq.text"></div>
        </div>
    </div>

    <magnific-popup-modal :show="false" :config="{closeOnBgClick:true}" ref="modal">

        <!-- Put whatever content you want in here -->
        <div class="condition-popup-content" style="background: white;">
            <h4>Condition Examples</h4>
            <button class="mfp-close" v-on:click="closeModal" type="button" title="Close (Esc)">
                <img class="big-only" src="../../client/images/close.png" alt="close" />
                <img class="sm-only" src="../../client/images/close_popup.png" alt="close-popup" />
            </button>
            <ul class="condition-popup-tabs">
                <li><a href="#condition-1" class="active-tab">Flawless</a></li>
                <li><a href="#condition-2">Good</a></li>
                <li><a href="#condition-3">Fair</a></li>
                <li><a href="#condition-4">Broken</a></li>
            </ul>
            <div id="condition-1" class="condition-tabs-content visible">
                <div class="image"><img src="../../client/images/phone_icons-01.webp" alt="phone_icons-01"></div>
            </div>
            <div id="condition-2" class="condition-tabs-content">
                <div class="image"><img src="../../client/images/phone_icons-04.webp" alt="phone_icons-04"></div>
            </div>
            <div id="condition-3" class="condition-tabs-content">
                <div class="image"><img src="../../client/images/phone_icons-03.webp" alt="phone_icons-03"></div>
            </div>
            <div id="condition-4" class="condition-tabs-content">
                <div class="image"><img src="../../client/images/phone_icons-02.webp" alt="phone_icons-02"></div>
            </div>
            <div class="note">*Scratches may be enhanced to show detail</div>
        </div>

    </magnific-popup-modal>
</div>
</template>

<script>
    import FormHelper from '../../admin/js/mixins/form_helper';
    import MagnificPopupModal from './MagnificPopupModal';

    export default {
        props: {
            category: {
                type: Object,
                required: false,
            },
            steps: {
                type: Array,
                required: false,
            },
            faqs: {
                type: Object,
                required: false,
            },
        },

        name: 'profile',
        components: {MagnificPopupModal},

        mixins: [FormHelper],

        data() {
            return {
                name: '',
                faqsIsset: false,
                priceError: false,
                priceLoaded: false,
                stepSelected: false,
                couponEntered: false,
                selectedStep: null,
                selectedSteps: [],
                selectedAccesories: [],
                stepIndex: 0,
                coupon: null,
                options: [],
                summ: parseFloat(this.category.custom_text),
                couponPrice: 0,
                helpingPopup: false,
            };
        },

        methods: {
            openModal () {
                this.$refs.modal.open()
            },

            closeModal () {
                this.$refs.modal.close()
            },

            enterCoupon() {
                if (this.coupon === this.category.coupon.code) {
                    this.summ = this.couponPrice;

                    this.couponEntered = true;
                }
            },

            backStep(){
                if (this.stepIndex > 0) {
                    let isNew = false;
                    let isRecycle = false;

                    _.each(this.selectedSteps, (value, key) => {
                        if (value) {
                            if (value.value === "Brand New") {
                                isNew = true;

                                this.selectedAccesories = [];
                            }
                            if (value.id === 1111) {
                                isRecycle = true;

                                this.selectedAccesories = [];
                            }
                        }
                    });

                    if (this.selectedStep) {
                        if (this.selectedStep.is_checkbox) {
                            this.selectedAccesories = [];
                        } else {
                            this.selectedSteps[this.steps[this.stepIndex].items[0].name_id] = null;
                        }
                    }

                    this.stepIndex--;

                    let selectedStepValue = this.steps[this.stepIndex];

                    if (selectedStepValue) {
                        if (isNew && selectedStepValue.is_checkbox) {
                            this.stepIndex--;

                            this.selectedStep = this.steps[this.stepIndex];
                        } else {
                            this.selectedStep = selectedStepValue;
                        }
                    } else {
                        this.selectedStep = null;
                    }

                    if (isRecycle) {
                        this.stepIndex = 0;

                        this.selectedStep = this.steps[this.stepIndex];
                    }
                }
                
                this.valuate();

                this.stepSelected = true;

                this.$forceUpdate();
            },

            nextStep() {
                let isNew = false;
                let isRecycle = false;

                if (this.category.is_parsed) {
                    _.each(this.selectedSteps, (value, key) => {
                        if (value) {
                            if (value.value === "Brand New") {
                                isNew = true;

                                this.selectedAccesories = [];
                            }
                            if (value.id === 1111) {
                                isRecycle = true;

                                this.selectedAccesories = [];
                            }
                        }
                    });
                }

                this.stepIndex++;

                let selectedStepValue = this.steps[this.stepIndex];

                if (selectedStepValue) {
                    if (isNew && selectedStepValue.is_checkbox) {
                        this.stepIndex++;

                        this.selectedStep = this.steps[this.stepIndex];
                    } else {
                        this.selectedStep = selectedStepValue;
                    }
                } else {
                    this.selectedStep = null;
                }

                if (isRecycle) {
                    this.stepIndex = this.steps.length;

                    this.selectedStep = null;
                }

                this.valuate();

                this.stepSelected = false;

                if (this.selectedStep) {
                    _.each(this.selectedStep['items'], (value, key) => {
                        if (this.selectedSteps[value.name_id]) {
                            this.stepSelected = true;
                        }
                    });
                }

                let offset = 250; // sticky nav height
                let el = document.querySelector('#step-title');
                window.scroll({ top: (el.offsetTop - offset), left: 0, behavior: 'smooth' });
            },

            valuate(){
                this.priceLoaded = false;

                this.summ = parseFloat(this.category.custom_text);

                if (!this.selectedStep) {
                    let steps = [];
                    _.each(this.selectedSteps, (value, key) => {
                        if(value) {
                            value.value = value.value.replace('&', '');

                            steps.push(value);
                        }
                    });

                    _.each(this.selectedAccesories, (value, key) => {
                        if(value) {
                            value.value = value.value.replace('&', '');

                            steps.push(value);
                        }
                    });

                    axios.post(
                        Router.route('get-price', {steps: JSON.stringify(steps), category_id: this.category.id}),
                    ).then((data) => {
                        this.couponEntered = false;

                        this.summ = data.data.price;

                        this.couponPrice = data.data.couponPrice;

                        setTimeout(() => this.priceLoaded = true, 1000);
                    }).catch(({ response: { data: { errors } } }) => {
                        this.priceError = true;
                    });
                }

                _.each(this.selectedSteps, (key, value) => {
                    if(key) {
                        if (key.price_plus) {
                            this.summ += parseFloat(key.price_plus);
                        }
                        if (key.price_percent){
                            let percent = (this.summ * parseFloat(key.price_percent)) / 100;
                            this.summ += percent;
                        }
                    }
                });
            },

            selectOption(option, index) {
                _.set(this.selectedStep.items[index], 'checked',true);

                this.stepSelected = true;

                this.$forceUpdate();
            },

            addToBox(){
                let orders = {
                    order: []
                };

                let localValue = localStorage.getItem("orders");
                let storedNames = JSON.parse(localStorage.getItem("orders"));
                if(localValue){
                    //Добавляем или изменяем значение:
                    storedNames.order.push({
                        id: this.category.id,
                        device: this.category,
                        steps: this.selectedSteps.concat(this.selectedAccesories),
                        summ: this.summ,
                        total: this.summ,
                        ctn: 1
                    });
                    localStorage.setItem("orders", JSON.stringify(storedNames));
                }else{
                    orders.order.push({
                        id: this.category.id,
                        device: this.category,
                        steps: this.selectedSteps.concat(this.selectedAccesories),
                        summ: this.summ,
                        total: this.summ,
                        ctn: 1
                    });
                    localStorage.setItem("orders", JSON.stringify(orders));
                }

                this.formData = new FormData();
                this.formData.set('_method', 'POST');

                this.collectFormData({
                    steps: this.selectedSteps,
                    price: this.summ,
                    orders: JSON.parse(localStorage.getItem("orders"))
                });

                axios.post(
                    Router.route('add-to-box', { slug: this.category.slug }),
                    this.formData,
                    {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        },
                    },
                ).then((data) => {
                    //console.log(data);
                }).catch(({ response: { data: { errors } } }) => {
                    // notify.success(
                    //     errors
                    // );
                });

                location.href = Router.route('cart');
            }
        },

        created(){
            this.faqsIsset = _.isEmpty(this.faqs);

            if(this.steps.length) {
                this.selectedStep = _.head(this.steps);

                if (this.category.is_recycle) {
                    this.selectedStep.items.push({
                        id: 1111,
                        name_id: 1,
                        value: 'For recycle',
                        step_name: {
                            id: 1,
                            name: 'Condition'
                        }
                    });
                }

                if (this.category.icloud_locked) {
                    this.steps.push({
                        is_checkbox: false,
                        is_condition: false,
                        is_functional: false,
                        title: "Is the iCloud locked?",
                        items: [
                            {
                                id: 2222,
                                name_id: 2222,
                                value: 'Yes',
                                is_device_locked: true,
                                step_name: {
                                    id: 2222,
                                    name: 'iCloud locked'
                                }
                            },
                            {
                                id: 2223,
                                name_id: 2222,
                                value: 'No',
                                step_name: {
                                    id: 2222,
                                    name: 'iCloud locked'
                                }
                            },
                        ]
                    });
                }

                if (this.category.google_locked) {
                    this.steps.push({
                        is_checkbox: false,
                        is_condition: false,
                        is_functional: false,
                        title: "Is the Google account locked?",
                        items: [
                            {
                                id: 2222,
                                name_id: 2222,
                                value: 'Yes',
                                is_device_locked: true,
                                step_name: {
                                    id: 2222,
                                    name: 'Google account locked'
                                }
                            },
                            {
                                id: 2223,
                                name_id: 2222,
                                value: 'No',
                                step_name: {
                                    id: 2222,
                                    name: 'Google account locked'
                                }
                            },
                        ]
                    });
                }
            }
        }
    }
</script>
