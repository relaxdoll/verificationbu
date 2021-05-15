<template>
    <div style="position: sticky; z-index: 2; top: 33px;">
        <div style="background-color: #3b3736; padding: 5px 20px 10px 20px; position: relative;">

            <input :style="customstyle" class="inputbox-single" type="text" :placeholder="placeholder" v-model="inputValue"/>

            <span class="searchbutton" ><i style="color: #BBBBBB; font-size:20px;" class="material-icons">search</i></span>
            <span v-show="inputValue" class="liff-close-icon">&#x00d7;</span>
            <span v-show="inputValue" @click="inputValue = ''" class="liff-close-icon-field"/>

        </div>
    </div>
</template>
<script>
    export default {
        props: {
            placeholder: {required: false},
            value: {required: true},
            type: {default: 'text'},
            customstyle: {required: false},
            mask: {required: false},
            maxlength: {required: false},
            data: {required: false},
            title: {required: false},
            delimiter: {required: false},


        },

        watch: {
            inputValue(newValue) {
                this.$emit('input', newValue);
            },
        },

        data() {
            return {
                inputValue: this.value,
                hasParent: this.$parent.children,
                pickerValue: null,
            };
        },

        methods: {
            pickerInput(input) {
                this.$emit('input', input);
                this.pickerValue = input[0] + ' ' + this.delimiter + ' ' + input[1];
                // console.log(input);
            },
            getClass() {

                if (this.hasParent) {
                    if (this.$parent.children[0]._uid === this._uid) {
                        return 'inputbox-first'
                    } else {
                        return 'inputbox'
                    }
                } else {
                    return 'inputbox-single'
                }
            }
        },
    };
</script>

<style scoped>

    .inputbox-single {
        position: relative;
        border: none;
        height: 28px;
        width: calc(100vw - 40px);
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        /*line-height: 15px;*/
        font-size: 15px;
        color: #e0e0e0;
        /*margin-top: 20px;*/
        padding: 0 40px 0 30px;
        background-color: #4f4a49;
        border-radius: 10px;
    }

    .inputbox {
        position: relative;
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        background-color: #3b3736;
        border: none;
        color: #929292;
        border-top: #DDDDDD 0.8px solid;
        padding: 0 50px 0 0;
    }

    .inputbox-first {
        position: relative;
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        border: none;
        background-color: #3b3736;
        color: #929292;
        padding: 0 50px 0 0;
    }

    .text-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .text-option {
        padding-left: 15px;
        text-align: left;
        font-weight: 400;
        color: #5b5553;
    }

    .checked {
        text-align: left;
        padding-left: 20px;
        color: #2D7AFF;
    }

    .disabled {
        color: #BBBBBB;
    }

    .searchbutton {
        display: block;
        position: absolute;
        background-color: transparent;
        z-index: 2;
        left: 25px;
        top: 10px;
        bottom: 0;
        cursor: pointer;
    }

    .liff-close-icon-field {
        display: block;
        width: 50px;
        height: 28px;
        position: absolute;
        background-color: transparent;
        z-index: 2;
        right: 10px;
        top: 0;
        bottom: 0;
        cursor: pointer;
    }

    .liff-close-icon {
        display: block;
        width: 15px;
        height: 15px;
        position: absolute;
        background-color: #a6a6a6;
        z-index: 1;
        right: 35px;
        top: 0;
        bottom: 0;
        margin-top: 11px;
        line-height: 16px;
        border-radius: 50%;
        text-align: center;
        color: #4f4a49;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
    }

</style>
<style>
    .vux-popup-header-right {
        color: #ffd25a !important;
        font-weight: 500 !important;
    }

    .vux-popup-header-left {
        color: #bbbbbb !important;
        font-weight: 400 !important;
    }

    .vux-popup-header {
        height: 55px !important;
        line-height: 55px !important;
        background-color: #3b3736 !important;
    }

    .scroller-mask {
        border-left: #DDDDDD 1px solid;
    }
</style>
