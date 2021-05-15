<template>
    <div :style="(hasParent)? 'position: relative;':'position: relative; margin-top:20px;'">

        <input v-if="(type === 'text')" :style="customstyle" :class="getClass()" type="text" :placeholder="placeholder" v-model="inputValue"/>

        <input v-if="(type === 'number')" :style="customstyle" :class="getClass()" type="number" pattern="[0-9]*" inputmode="decimal" :placeholder="placeholder" v-model="inputValue"/>

        <input v-if="(type === 'textUpperCase')" :style="customstyle" :class="getClass()" type="text" :placeholder="placeholder" v-model="inputValue" onkeyup="this.value = this.value.toUpperCase()"/>

        <the-mask v-if="(type === 'mask')"  :style="customstyle":class="getClass()" type="tel" :maxlength="maxlength" :placeholder="placeholder" :mask="mask" v-model="inputValue"/>

        <popup-picker v-if="(type === 'picker2')" :class="getClass()" title="Test" :data="data" cancelText="Cancel" confirmText="Confirm" @input="pickerInput($event)">
            <input style="padding-left: 0; margin-left:0;"  :style="customstyle":class="getClass()" type="text" :placeholder="placeholder" v-model="pickerValue" readonly/>
        </popup-picker>

        <span v-if="(type !== 'picker2')" v-show="inputValue" class="liff-close-icon">&#x00d7;</span>
        <span v-if="(type !== 'picker2')" v-show="inputValue" @click="inputValue = null" class="liff-close-icon-field"/>

        <span v-if="(type === 'picker2')" v-show="pickerValue" class="liff-close-icon">&#x00d7;</span>
        <span v-if="(type === 'picker2')" v-show="pickerValue" @click="pickerValue = null" class="liff-close-icon-field"/>
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
                pickerValue:null,
            };
        },

        methods: {
            pickerInput(input){
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
        border: #DDDDDD 0.8px solid;
        height: 50px;
        border-radius: 0;
        width: 100vw;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        line-height: 16px;
        color: #929292;
        /*margin-top: 20px;*/
        padding: 0 50px 0 20px;
    }

    .inputbox {
        position: relative;
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        background-color: white;
        border: none;
        color: #929292;
        border-top: #DDDDDD 0.8px solid;
        padding: 0 50px 0 0;
        line-height: 16px;
    }

    .inputbox-first {
        position: relative;
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        border: none;
        background-color: white;
        color: #929292;
        padding: 0 50px 0 0;
        line-height: 16px;
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

    .liff-close-icon-field {
        display: block;
        width: 50px;
        height: 50px;
        position: absolute;
        background-color: transparent;
        z-index: 2;
        right: 0;
        top: 0;
        bottom: 0;
        cursor: pointer;
    }

    .liff-close-icon {
        display: block;
        width: 15px;
        height: 15px;
        position: absolute;
        background-color: #CBCCCB;
        z-index: 1;
        right: 25px;
        top: 0;
        bottom: 0;
        margin-top: 17.5px;
        line-height: 16px;
        border-radius: 50%;
        text-align: center;
        color: white;
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
