<template>

    <div>

        <v-form v-show="mq === 'sm' || (collapseonselect && rowSelected)" name="pill" style="margin-top: -15px;">
            <select-box field="pill" placeholder="Fleet" type="dropdown" :forceoption="pill_data" @input="select($event)"
                        optiontext="text" optionvalue="value" :addon-left-icon="icon" :vparam="[]"
            ></select-box>
        </v-form>

        <div v-show="!(mq == 'sm' || (collapseonselect && rowSelected))" ref="pillcontainer" style="overflow-x: auto;">
            <div class="nav nav-pills" :class="'nav-pills-'+color">
                <div v-if="(datum.text !== 'Ex')" v-for="(datum, index) in pill_data" :key="index" class="nav-item" ref="lists" style="display:flex;">
                    <a style="cursor: pointer;height:43px;" :class="(active === datum.value)? 'active nav-link' : 'nav-link'" @click="select(datum.value)">
                        {{ datum.text }}
                    </a>
                </div>
            </div>
        </div>

    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        props: {
            'data': {required: true},
            'default': {required: false},
            'color': {default: 'primary'},
            'icon': {default: 'tim-icons icon-delivery-fast'},
            collapseonselect: Boolean,
        },
        data() {
            return {
                'active': null,
                // 'pill_data': null,
            }
        },
        created() {
            // this.pill_data = this.data;
            this.active = this.default;

            this.$store.dispatch('populateForm', {
                'property': 'pill',
                'form': 'pill',
                'field': {
                    pill: this.default,
                }
            });
            // this.pill_data = _.orderBy(this.pill_data, 'text', 'asc');
        },

        computed: {
            ...mapState([
                'mq',
                'theme',
                'rowSelected',
            ]),
            pill_data: function () {
                return _.orderBy(this.data, 'text', 'asc');
            },

        },
        methods: {
            select(value) {
                this.active = value;
                this.$emit('select', value);
            },
        },
    };
</script>

<style>
    @import '../../../css/syncfusion/syncfusion.css';
</style>
