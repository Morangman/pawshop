<template>
    <div class="custom-select" :tabindex="tabindex" @blur="open = false">
        <div class="selected" :class="{ open: open, 'input-error': error }" @click="open = !open">
            <p v-if="!selected && !value">State*</p>
            <p>{{ value ? options[value] : selected }}</p>
        </div>
        <div class="items" :class="{ selectHide: !open }">
            <div v-for="(option, i) of options"
            :key="i"
            @click="selected = option; open = false; $emit('input', i);">
                <p>{{ option }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            options: {
                type: Object,
                required: true,
            },
            default: {
                type: String,
                required: false,
                default: null,
            },
            value: {
                type: String,
                required: false,
                default: null,
            },
            tabindex: {
                type: Number,
                required: false,
                default: 0,
            },
            error: {
                type: Boolean,
                required: false,
                default: false,
            },
        },
        data() {
            return {
                selected: this.default
                    ? this.value ? this.value : null
                    : this.options.length > 0
                        ? this.options[0]
                        : null,
                open: false,
            };
        },
        mounted() {
            this.$emit("input", this.selected);
        },
    };
</script>

<style scoped>

    .custom-select {
        position: relative;
        border-radius: 90px;
        width: 100%;
        text-align: left;
        outline: none;
        color: #636363;
        font-weight: 300;
        font-size: 15px;
        font-family: 'GothamPro', Arial, Tahoma, sans-serif;
    }

    .custom-select .selected {
        position: relative;
        z-index: 2;
        background: #fff;
        border: 1px solid #DADADA;
        padding-left: 1em;
        cursor: pointer;
        user-select: none;
        border-radius: 99px;
        height: 38px;
        transition: all 0.3s ease-out;
    }

    .custom-select .selected p{
        position: absolute;
        top: 12px;
        left: 22px;
    }

    .custom-select .selected:after {
        position: absolute;
        content: url("../../client/images/select_arrow.svg");
        top: 11px;
        right: 25px;
        width: 0;
        height: 0;
    }

    .custom-select .items {
        position: absolute;
        z-index: 1;
        top: 20px;
        width: 100%;
        max-height: 300px;
        overflow: auto;
        border: 1px solid #ededed;
        background: #fff;
        padding: 10px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.06);
        border-radius: 0px 0px 20px 20px;
    }

    .custom-select .items div {
        font-size: 15px;
        font-weight: 400;
        border-radius: 0;
        background: none;
    }

    .custom-select .items div:first-child{
        margin-top: 17px;
    }

    .custom-select .items div p:hover {
        color: #e13d55;
        cursor: pointer;
    }

    .custom-select .items div p {
        padding: 7px 10px;
        font-size: 15px;
        font-weight: 400;
        border-radius: 0;
        background: none;
    }

    .selectHide {
        display: none;
    }

    @media (max-width: 480px) {
        .custom-select {
            font-size: 13px;
        }
    }
</style>
