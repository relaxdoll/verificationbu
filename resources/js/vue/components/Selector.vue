<template>
    <div @click="isClicked" :class="(darkmode)? 'input-theme-dark' : 'input-theme'">
        <div class="text-wrapper">
            <div class="row">
                <div :class="getLabelClass()">
                    {{ label }}
                </div>
                <div :class="getOptionClass()">
                    {{ currentoption }}
                </div>
                <div :class="(darkmode)? 'col-1 next-dark':'col-1 next'">
                    >
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            label: {required: true},
            currentoption: {default: null},
            darkmode: {default: false},
            col: {type: Number, default: 6}

        },

        data() {
            return {};
        },

        mounted() {
        },

        methods: {
            isClicked() {
                this.$emit('click', true);
            },
            getLabelClass() {
                if (this.darkmode) {
                    return 'col-' + this.col + ' input-label-dark';
                } else {
                    return 'col-' + this.col + ' input-label';
                }
            },
            getOptionClass() {
                let col2 = 11 - this.col;
                if (this.darkmode) {
                    return 'col-' + col2.toString() + ' current-option-dark';
                } else {
                    return 'col-' + col2.toString() + ' current-option';
                }
            }
        },
    };
</script>
<style scoped>

    .input-label {
        font-weight: 400;
        color: #5b5553;
    }

    .input-label-dark {
        font-weight: 400;
        color: #FFD25A;
    }

    .input-theme {
        display: table;
        padding: 0 20px;
        height: 50px;
        width: 100vw;
        background-color: white;
        border: #DDDDDD 0.8px solid;
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
        color: #929292;
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
        font-size: 25px;
        font-family: 'BenchNine', sans-serif;
    }

    .next-dark {
        text-align: left;
        padding-left: 10px;
        color: #777777;
        font-size: 25px;
        font-family: 'BenchNine', sans-serif;
    }
</style>
