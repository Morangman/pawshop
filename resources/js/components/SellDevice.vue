<template>
<div>
    <h1 class="center-text">Start Selling</h1>
    <div class="description center-text">Find the product you'd like to trade-in for cash</div>
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
                <img :src="category.image" alt="">
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
                        <img src="../../client/images/icon_help.svg" alt="">
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
                                                <img src="/images/close.png?3365377fd075715b06b6510224785880" alt="">
                                                <img src="/images/close_popup.png?c75e43c6a14689556029439fe821d637" alt="" class="sm-only">
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
                            <a href="#condition-popup" class="btn popup-open" data-effect="mfp-zoom-in">Condition Examples</a>
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
                <div v-if="priceLoaded" class="order-total-block">
                    <div class="price">${{ summ }}</div>
                    <div class="order-options-links">
                        <button class="btn gray-btn" v-on:click="backStep">Back</button>
                        <button v-on:click="addToBox" class="btn red-btn">Add to box</button>
                    </div>
                </div>
                <ul class="order-advantages-list">
                    <li>
                        <div class="pic"><img src="../../client/images/order_advantage_1.svg" alt=""></div>
                        <div>
                            <p>No selling fees</p>
                            <div class="desc">Save up to 15% on marketplace selling fees.</div>
                        </div>
                    </li>
                    <li>
                        <div class="pic"><img src="../../client/images/order_advantage_2.svg" alt=""></div>
                        <div>
                            <p>Zero fraud risk</p>
                            <div class="desc">We handle the bad guys.</div>
                        </div>
                    </li>
                    <li>
                        <div class="pic"><img src="../../client/images/order_advantage_3.svg" alt=""></div>
                        <div>
                            <p>Free and Convenient shipping via FedEx or UPS</p>
                            <div class="desc">There's a drop-off around the corner.</div>
                        </div>
                    </li>
                    <li>
                        <div class="pic"><img src="../../client/images/order_advantage_4.svg" alt=""></div>
                        <div>
                            <p>Optional 2-Day shipping and 24-Hour processing</p>
                            <div class="desc">We get it. Sometimes you just can't wait!</div>
                        </div>
                    </li>
                </ul>
            </div>

        </div>
    </div>

    <div class="order-search-outer" v-if="!steps.length">
        <h5>Search the device:</h5>
        <div class="order-search">
            <div class="order-search-form">
                <input v-on:keyup="searchDevice" v-model="name" type="text" placeholder="Write text for search">
                <a href="javascript:void(0)" v-on:click="searchDevice" class="btn red-btn">Search</a>
            </div>
            <div class="order-search-popup" v-if="searchDevices.length">
                <ul class="order-search-popup-list">
                    <li v-for="(device, index) in searchDevices" :key="`device_${index}`">
                        <a :href="$r('get-category', { slug: device.slug })" class="link">
                            <div class="image"><img :src="device.image" alt=""></div>
                            <span class="name">{{ device.name }}</span>
                        </a>
                        <div class="price">up to <strong>{{ device.custom_text }}</strong></div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="order-content" v-if="!steps.length">
        <h4>Or choose the device for sell:</h4>
        <ul class="order-list">
            <li v-for="(category, index) in categories" :key="`device_${index}`">
                <a :href="$r('get-category', { slug: category.slug })">
                    <div class="image"><img :src="category.image" alt="" /></div>
                    <h5>{{ category.name }}</h5>
                    <div v-if="category.custom_text" class="price">Cash in up to ${{ category.custom_text }}</div>
                </a>
            </li>
        </ul>
    </div>

    <div class="faqs-content" v-if="!faqsIsset">
        <h2>FAQs</h2>
        <div class="faqs-block" v-for="(faq, index) in faqs.data" :key="`faq_${index}`">
            <div class="faqs-question">{{ faq.title }}</div>
            <div class="faqs-answer" v-html="faq.text"></div>
        </div>
    </div>

    <!--Condition -->
    <div id="condition-popup" class="popup-modal mfp-hide mfp-with-anim">
        <div class="popup-content">
            <div class="condition-popup-content">
                <h4>Condition Examples</h4>
                <ul class="condition-popup-tabs">
                    <li><a href="#condition-1" class="active-tab">Flawless</a></li>
                    <li><a href="#condition-2">Good</a></li>
                    <li><a href="#condition-3">Fair</a></li>
                    <li><a href="#condition-4">Broken</a></li>
                </ul>
                <div id="condition-1" class="condition-tabs-content visible">
                    <div class="image"><img src="../../client/images/phone_icons.png" alt=""></div>
                </div>
                <div id="condition-2" class="condition-tabs-content">
                    <div class="image"><img src="../../client/images/phone_icons-28.png" alt=""></div>
                </div>
                <div id="condition-3" class="condition-tabs-content">
                    <div class="image"><img src="../../client/images/phone_icons-26.png" alt=""></div>
                </div>
                <div id="condition-4" class="condition-tabs-content">
                    <div class="image"><img src="../../client/images/phone_icons-25.png" alt=""></div>
                </div>
                <div class="note">*Scratches may be enhanced to show detail</div>
            </div>
            <button class="mfp-close" type="button" title="Close (Esc)">
                <img src="../../client/images/close.png" alt="" />
                <img class="sm-only" src="../../client/images/close_popup.png" alt="" />
            </button>
        </div>
    </div>
</div>
</template>

<script>
    import FormHelper from '../../admin/js/mixins/form_helper';

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
            categories: {
                type: Array,
                required: false,
            },
            faqs: {
                type: Object,
                required: false,
            },
        },

        mixins: [FormHelper],

        data() {
            return {
                name: '',
                searchDevices: {},
                faqsIsset: false,
                priceError: false,
                priceLoaded: false,
                stepSelected: false,
                selectedStep: null,
                selectedSteps: [],
                selectedAccesories: [],
                stepIndex: 0,
                options: [],
                summ: parseFloat(this.category.custom_text),
                helpingPopup: false,
            };
        },

        methods: {
            searchDevice() {
                this.searchDevices = {};

                axios.get(
                    Router.route('header-search', { name: this.name }),
                ).then((data) => {
                    this.searchDevices = data.data;
                }).catch(({ response: { data: { errors } } }) => {
                    console.log(errors);
                });
            },

            backStep(){
                if (this.stepIndex > 0) {
                    let isNew = false;

                    _.each(this.selectedSteps, (value, key) => {
                        if (value) {
                            if (value.value === "Brand New") {
                                isNew = true;

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
                }

                this.valuate();

                this.stepSelected = true;

                this.$forceUpdate();
            },

            nextStep() {
                let isNew = false;

                _.each(this.selectedSteps, (value, key) => {
                    if (value) {
                        if (value.value === "Brand New") {
                            isNew = true;

                            this.selectedAccesories = [];
                        }
                    }
                });

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

                this.valuate();

                this.stepSelected = false;

                if (this.selectedStep) {
                    _.each(this.selectedStep['items'], (value, key) => {
                        if (this.selectedSteps[value.name_id]) {
                            this.stepSelected = true;
                        }
                    });
                }

                let offset = 125; // sticky nav height
                let el = document.querySelector('#step-title'); // element you are scrolling to
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
                        this.summ = data.data.price;

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
            }
        }
    }
</script>
