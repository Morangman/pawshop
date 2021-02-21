<template>
<div>
    <h1>Start Selling</h1>
    <div class="description">Find the product you'd like to trade-in for cash</div>
    <ul class="order-steps-list" v-if="steps.length">
        <li v-for="(step, index) in steps" :class="stepIndex === index ? 'active-step' : ''">
            <a href="javascript:void(0)" v-on:click="setStep(index)"><i>{{ index + 1 }}</i> <span>{{ index + 1 }}</span></a>
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
            <ul class="selected-list" v-if="selectedSteps.length">
                <li v-for="option in selectedSteps" v-if="option">{{ option ? option.name : '' }}</li>
            </ul>
        </div>
        <div class="order-options-detail">

            <!-- Step 1 -->
            <div class="order-options-block" v-if="selectedStep">
                <h4>{{ selectedStep.name }}</h4>
                <div class="helping-block" v-if="selectedStep.tip">
                    <a href="#helping-popup" class="popup-open" data-effect="mfp-zoom-in">
                        <img src="../../client/images/icon_help.svg" alt="">
                        <span>{{ selectedStep.tip ? selectedStep.tip.name : '' }}</span>
                    </a>
                </div>
                <div class="order-options-radios">
                    <div class="options-radio" v-for="(option, index) in selectedStep.items" :key="`step${option.id}_${index}`">
                        <label class="radiobox-block" v-if="!selectedStep.is_checkboxes">
                            <input v-model="selectedSteps[selectedStep.id]" v-on:click="selectOption(option, index)" :checked="!!selectedStep.items[index].checked" :value="option" type="radio" :name="`step-${selectedStep.id}-radios`">
                            <i></i>
                            <span>
                                <strong>{{ option.name }}</strong>
                                <small>{{ option.text }}</small>
                            </span>
                        </label>
                        <label class="checkbox-block" v-if="selectedStep.is_checkboxes">
                            <input type="checkbox" v-model="selectedSteps" v-bind:value="option"  :name="`step-${selectedStep.id}-checkbox`">
                            <i></i>
                            <span>
                                <strong>{{ option.name }}</strong>
                                <small>{{ option.text }}</small>
                            </span>
                        </label>
                        <div class="options-radio-detail" v-if="selectedStep.is_condition">
                            <h5>For a device to be in this condition. The following must also be true.</h5>
                            <ol>
                                <li>Zero scratches, scuffs or other marks. Looks like new.</li>
                                <li>Display is free of defects such as dead pixels, white spots, or burn-in.</li>
                            </ol>
                            <div class="links">
                                <a href="#condition-popup" class="btn popup-open" data-effect="mfp-zoom-in">Condition Examples</a>
                                <a href="https://www.youtube.com/watch?v=N2EPWemOuuE" class="play-link popup-youtube"><img src="../../client/images/play.svg" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="order-options-links">
                    <button v-on:click="backStep" class="btn gray-btn">Back</button>
                    <button v-on:click="nextStep" class="btn red-btn">Next step</button>
                </div>

                <div v-if="selectedStep.tip" id="helping-popup" class="popup-modal mfp-hide mfp-with-anim">
                    <div class="popup-content">
                        <div class="helping-popup-content">
                            <span v-html="selectedStep.tip ? selectedStep.tip.text : ''"></span>
                        </div>
                        <button class="mfp-close" type="button" title="Close (Esc)">
                            <img src="../../client/images/close.png" alt="" />
                            <img class="sm-only" src="../../client/images/close_popup.png" alt="" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="order-options-block" v-if="!selectedStep">
                <h4>Your device is valued at</h4>
                <div class="order-total-block">
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
                        <a :href="$r('get-category', { category: device.id })" class="link">
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
                <a :href="$r('get-category', { category: category.id })">
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
                    <div class="photo-flex">
                        <div class="image"><img src="../../client/images/conditions/condition_1_1.jpg" alt=""></div>
                        <div class="image"><img src="../../client/images/conditions/condition_1_2.jpg" alt=""></div>
                    </div>
                </div>
                <div id="condition-2" class="condition-tabs-content">
                    <div class="photo-flex">
                        <div class="image"><img src="../../client/images/conditions/condition_2_1.jpg" alt=""></div>
                        <div class="image"><img src="../../client/images/conditions/condition_2_2.jpg" alt=""></div>
                    </div>
                </div>
                <div id="condition-3" class="condition-tabs-content">
                    <div class="photo-flex">
                        <div class="image"><img src="../../client/images/conditions/condition_3_1.jpg" alt=""></div>
                        <div class="image"><img src="../../client/images/conditions/condition_3_2.jpg" alt=""></div>
                    </div>
                </div>
                <div id="condition-4" class="condition-tabs-content">
                    <div class="photo-flex">
                        <div class="image"><img src="../../client/images/conditions/condition_4_1.jpg" alt=""></div>
                        <div class="image"><img src="../../client/images/conditions/condition_4_2.jpg" alt=""></div>
                    </div>
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
                type: Array,
                required: false,
            },
        },

        data() {
            return {
                name: '',
                searchDevices: {},
                faqsIsset: false,
                selectedStep: null,
                selectedSteps: [],
                stepIndex: 0,
                options: [],
                summ: parseFloat(this.category.custom_text)
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
                    this.stepIndex--;

                    this.selectedStep = this.steps[this.stepIndex];
                }

                this.valuate();

                this.$forceUpdate();
            },

            nextStep() {
                this.stepIndex++;

                this.selectedStep = this.steps[this.stepIndex];

                this.valuate();
            },

            setStep(index) {
                this.stepIndex = index;

                this.selectedStep = this.steps[index];

                this.valuate();

                this.$forceUpdate();
            },

            valuate(){
                this.summ = parseFloat(this.category.custom_text);

                _.each(this.selectedSteps, (key, value) => {
                    if(key) {
                        this.summ += parseFloat(key.price_plus);
                    }
                });
            },

            selectOption(option, index) {
                _.set(this.selectedStep.items[index], 'checked',true);

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
                        steps: this.selectedSteps,
                        summ: this.summ,
                        total: this.summ,
                        ctn: 1
                    });
                    localStorage.setItem("orders", JSON.stringify(storedNames));
                }else{
                    orders.order.push({
                        id: this.category.id,
                        device: this.category,
                        steps: this.selectedSteps,
                        summ: this.summ,
                        total: this.summ,
                        ctn: 1
                    });
                    localStorage.setItem("orders", JSON.stringify(orders));
                }

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
