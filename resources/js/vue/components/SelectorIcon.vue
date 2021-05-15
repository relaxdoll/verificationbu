<template>
    <div @click="isClicked" :class="(darkmode)? 'input-theme-dark' : 'input-theme'">
        <div class="text-wrapper" style="display:flex;" :class="{'bottom-border':islast || !isgrouped || error, 'top-border': !isgrouped}">
            <div style="width: 54px; display:flex; height: 50px; padding:13px 15px;" :class="{'disable':disable}">
                <div class="left-icon" :style="'background-color: '+iconcolor+';'">
                    <i style="font-size:14px;" :class="icon"></i>
                </div>
            </div>
            <div style="width:100%; display:flex" :class="{'bottom-border':!islast && isgrouped && !error}">
                <div style="padding:17px 0; display:flex; height: 50px; line-height:16px; color:black;" :class="{'disable':disable}">
                    {{ label }}
                </div>
                <div style="color:#727272; padding:12px 10px 12px 0; display:flex; height: 50px; line-height:26px; margin-left:auto;">
                    {{ getValueText() }} <span class="next">></span>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        props: {
            label: {required: true},
            currentoption: {default: null},
            darkmode: {default: false},
            col: {type: Number, default: 6},
            icon: {required: true},
            iconcolor: {required: true},
            disable: {default: false},
            isgrouped: {required: true},
            islast: {required: true},
            form: {required: false},
            field: {required: false},
            valuetext: {required: false},
            error: {required: false},

        },

        data() {
            return {};
        },

        mounted() {
        },

        computed: {
            ...mapState([
                'theme'
            ]),
            value: {
                get() {
                    return this.$store.getters.getFieldValue({'form': this.form, 'field': this.field});
                },
            },
        },
        methods: {
            isClicked() {
                this.$emit('click', true);
            },
            getLabelCol() {
                return 'col-' + this.col;
            },
            getOptionCol() {
                let col2 = 10 - this.col;
                return 'col-' + col2.toString();
            },
            getIconColor() {
                let color = '';
                color += this.iconcolor;
                if (this.disable) {
                    color += '50';
                }
                return color;
            },
            getValueText(){
                if (this.valuetext){
                    return this.valuetext;
                }else{
                    return this.value;
                }
            }
        },
    };
</script>
<style scoped>

    .input-label {
        font-weight: 400;
        color: black;
    }

    .input-label-dark {
        font-weight: 400;
        color: #FFD25A;
    }

    .input-theme {
        /*display: table;*/
        /*padding: 0 20px;*/
        height: 50px;
        width: 100vw;
        background-color: white;
    }

    .input-theme-dark {
        display: table;
        padding: 0 20px;
        height: 60px;
        width: 100vw;
        background-color: #3B3736;
    }

    .current-option {
        text-align: right;
        font-weight: 300;
        color: #727272;
        padding-right: 0;
    }

    .current-option-dark {
        text-align: right;
        font-weight: 300;
        color: #BBBBBB;
        padding-right: 0;
    }

    .text-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .next {
        text-align: left;
        padding-left: 10px;
        color: #DDDDDD;
        font-size: 26px;
        font-family: 'BenchNine', sans-serif;
    }

    .next-dark {
        text-align: left;
        padding-left: 10px;
        color: #777777;
        font-size: 25px;
        font-family: 'BenchNine', sans-serif;
    }

    .left-icon {
        width: 24px;
        height: 24px;
        padding-top: 2px;
        text-align: center;
        font-size: 14px;
        border-radius: 5px;
        color: white;
    }

    .disable {
        opacity: 0.3;
    }

    .bottom-border {
        border-bottom: #DDDDDD 1px solid;
    }

    .top-border {
        border-top: #DDDDDD 1px solid;
    }
</style>
