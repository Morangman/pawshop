<template>
    <div>
        <div class="checkbox-form">
            <div class="answers">
                <label class="item">
                    <span :for="id">{{ name }}</span>
                    <input
                        :id="id"
                        type="checkbox"
                        :value="option"
                        :checked="option === '1'"
                        @change="onChange"
                    >
                    <span class="checkmark" />
                </label>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            id: {
                type: String,
                required: false,
            },
            name: {
                type: String,
                required: false,
            },
            option: {
                type: String,
                required: false,
                default: () => '0'
            }
        },
        methods: {
            onChange() {
                if (this.option === '0') {
                    this.option = '1';
                } else {
                    this.option = '0';
                }
                this.$emit('input', this.option);
            }
        }
    };
</script>

<style scoped>
    .checkbox-form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .checkbox-form .answers {
        display: flex;
        flex-direction: column;
        align-items: left;
        width: 100%;
    }

    .checkbox-form .item {
        display: block;
        position: relative;
        padding-left: 35px;
        margin-bottom: 12px;
        cursor: pointer;
        font-size: 1em;
        height: 25px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        display: flex;
        align-items: center;
    }

    .checkbox-form .item input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 0;
        width: 0;
    }

    .checkbox-form .checkmark {
        position: absolute;
        top: 0;
        left: 0;
        height: 25px;
        width: 25px;
        background-color: #c2c2c2;
        border-radius: 5px;
    }

    .checkbox-form .item:hover input ~ .checkmark {
        background-color: #949494;
    }

    .checkbox-form .item input:checked ~ .checkmark {
        background-color: #2d5ca7;
    }

    .checkbox-form .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .checkbox-form .item input:checked ~ .checkmark:after {
        display: block;
    }

    .checkbox-form .item .checkmark:after {
        left: 9px;
        top: 5px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 3px 3px 0;
        -webkit-transform: rotate(45deg);
        -ms-transform: rotate(45deg);
        transform: rotate(45deg);
    }
</style>
