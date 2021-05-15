<template>
    <div style="margin-top: 20px;">

        <selector :col="col" @click="selected = true" :label="label" :currentoption="currentOption"></selector>

        <liffdrawer @close="selected = false" align="right" :maskclosable="true" :label="label" :currentview="currentview"
                    :closeable="true">

            <div v-if="selected">

                <lifftopinput v-if="searchbar" placeholder="Search" :value.sync="searchText"
                              @input="searchText = $event"/>

                <liffgroup style="margin-bottom:60px;">
                    <liffoption @click="select(option, index)" v-for="(option, index) in setoptions" :key="index" :value="option"
                                :selected="(currentOption === option)"></liffoption>
                </liffgroup>

                <liffgroup v-if="disabled" style="margin-top:-40px;">
                    <liffoption v-for="(option, index) in disabled" :key="index" :value="option+' (Coming Soon)'" :disabled="true"></liffoption>
                </liffgroup>

            </div>

        </liffdrawer>
    </div>
</template>
<script>
    export default {
        props: {
            label: {required: true},
            options: {requited: true},
            currentview: {default: 'Back'},
            isselect: {required: false},
            selectedoption: {require: false},
            disabled: {required: false},
            col: {type: Number, default: 6},
            closeonselect: {default: true},
            searchbar: {default: false},
        },

        data() {
            return {
                currentOption: null,
                selected: false,
                searchText: null,
                setoptions: [],
            };
        },

        watch: {
            searchText: function (newVal, oldVal) {
                this.setoptions = this.options.filter(item => item.toLowerCase().includes(newVal.toLowerCase()));
            },
            selected: function (newVal, oldVal) {
                this.setoptions = this.options;
            },
        },


        mounted() {
            this.currentOption = this.selectedoption;
            this.setoptions = this.options;
            this.selected = this.isselect;
        },
        methods: {
            select(option, index) {
                this.currentOption = option;

                    this.$emit('input', option);
                    this.$emit('index', index);

                if (this.closeonselect) {
                    new Promise(function (resolve, reject) {
                        setTimeout(function () {
                            resolve('foo');
                        }, 100);
                    })
                        .then(response => {
                            this.selected = false;
                        })
                }
                // this.selected = false;
            }
        },
        computed: {}
    };
</script>
<style>

</style>
