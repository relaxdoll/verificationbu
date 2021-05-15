<template>
    <div @click="isClicked" :class="getClass()">
        <div class="text-wrapper">
            <div class="row">
                <div :class="(disabled)? 'col-10 text-option disabled': 'col-10 text-option'">
                    {{ text }}
                </div>
                <div class="col-2 checked">
                    {{ (selected)? "&#x2713":'' }}
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            text: {required: true},
            selected: {default: false},
            disabled: {default: false},
        },

        data() {
            return {
                hasParent: this.$parent.children,
            };
        },

        mounted() {
            // console.log(this.$parent.children);
            // console.log(this._uid);
        },

        methods: {
            isClicked() {
                if (!this.disabled) {
                    this.$emit('click', true);
                }
            },
            getClass() {

                if (this.hasParent) {
                    if (this.$parent.children[0]._uid === this._uid) {
                        return 'select-option-first'
                    } else {
                        return 'select-option'
                    }
                }else{
                    return 'select-option-single'
                }
            }
        },

    };
</script>
<style scoped>

    .select-option {
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
    }

    .select-option-single {
        margin-top: 20px;
        padding-left: 20px;
        display: table;
        height: 50px;
        width: 100vw;
        background-color: white;
        border-top: #DDDDDD 0.8px solid;
        border-bottom: #DDDDDD 0.8px solid;
    }

    .select-option-first {
        margin-left: 20px;
        display: table;
        height: 50px;
        width: calc(100vw - 20px);
        background-color: white;
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
</style>
