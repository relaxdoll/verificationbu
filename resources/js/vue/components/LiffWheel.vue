<template>
    <div v-if="mode === 'แจ้งเปลี่ยนยาง'" @click="wheelSelected()" :style="'display: flex; margin-left: '+margin+'px;'">
        <div class="left-sidewall" :class="{'wheel-active':active, 'wheel-has-request': has_requests[placement]}"></div>
        <div class="wheel" :class="{'wheel-active':active, 'wheel-has-request': has_requests[placement]}">{{text2}}</div>
        <div class="right-sidewall" :class="{'wheel-active':active, 'wheel-has-request': has_requests[placement]}"></div>
    </div>

    <div v-else-if="mode === 'เช็คลม/วัดดอก'" @click="wheelSelected()" :style="'display: flex; margin-left: '+margin+'px;'">
        <div class="left-sidewall" :class="{'wheel-active':active, 'wheel-has-no-update': !has_updated[placement]}"></div>
        <div class="wheel" :class="{'wheel-active':active, 'wheel-has-no-update': !has_updated[placement], 'wheel-smaller-text': isPressure()}">{{text}}</div>
        <div class="right-sidewall" :class="{'wheel-active':active, 'wheel-has-no-update': !has_updated[placement]}"></div>
    </div>

    <div v-else @click="wheelSelected()" :style="'display: flex; margin-left: '+margin+'px;'">
        <div class="left-sidewall" :class="{'wheel-active':active, 'wheel-highlight': active_tires[placement]}"></div>
        <div class="wheel" :class="{'wheel-active':active, 'wheel-highlight': active_tires[placement]}">{{placement}}</div>
        <div class="right-sidewall" :class="{'wheel-active':active, 'wheel-highlight': active_tires[placement]}"></div>
    </div>

</template>
<script>
    import {mapState} from 'vuex'

    export default {
        props: {
            placement: {required: false},
            margin: {default: 0},
            isSpare: Boolean,
        },

        data() {
            return {
                active: false,
                form: null,
                field: null,
                text: null,
                text2: null,
                isUpdated: null,
            };
        },

        mounted() {

            this.getForm(this);

            if (this.$store.state.active_tires[this.placement]) {
                if (this.$store.state.active_tires[this.placement].end_tread_depth) {
                    this.text2 = this.$store.state.active_tires[this.placement].end_tread_depth;
                } else {
                    this.text2 = this.$store.state.active_tires[this.placement].start_tread_depth;
                }

            }

        },

        computed: {
            ...mapState([
                'active_tires',
                'has_requests',
                'has_updated',
                'wheel_text',
                'mode'
            ]),
        },

        watch: {
            has_updated: function (val) {
                this.changeText();
            },
            wheel_text: function (val) {
                this.changeText();
            },
        },


        methods: {
            getForm(component) {
                if (component.$parent.$options.name == 'LiffForm') {
                    this.form = component.$parent.name;
                    return this.field = component.$parent.field;
                }
                this.getForm(component.$parent);
            },
            wheelSelected() {
                this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'value': this.placement});
                this.$store.commit('updateForm', {'form': this.form, 'field': 'is_spare', 'value': this.isSpare});
                this.$store.commit('setPlacement', this.placement);
                this.active = true;
                this.$store.state.drawer_name = 'Wheel ' + this.placement;
                new Promise(function (resolve, reject) {
                    setTimeout(function () {
                        this.$store.commit('showDrawer');
                        resolve('foo');
                    }.bind(this), 150);
                }.bind(this))
                    .then(response => {
                        setTimeout(function () {
                            this.active = false;
                        }.bind(this), 300);
                    });
            },
            isPressure() {
                let isPressure = false;
                if (this.wheel_text === 'pressure') {
                    isPressure = true;
                }
                return isPressure
            },
            changeText() {
                switch (this.wheel_text) {
                    case 'placement':
                        this.text = this.placement;
                        break;
                    case 'tread':
                        if (this.$store.state.has_updated[this.placement]) {
                            this.text = this.$store.state.has_updated[this.placement].tread_depth;
                        } else {
                            this.text = null
                        }
                        break;
                    case 'pressure':
                        if (this.$store.state.has_updated[this.placement]) {
                            this.text = this.$store.state.has_updated[this.placement].pressure;
                        } else {
                            this.text = null
                        }
                        break;
                }
            }
        },
    };
</script>
<style scoped>
    .left-sidewall {
        height: 80px;
        width: 6px;
        border: #777777 3px solid;
        border-right: none;
        border-radius: 16px 0 0 16px;
    }

    .right-sidewall {
        height: 80px;
        width: 6px;
        border: #777777 3px solid;
        border-left: none;
        border-radius: 0 6px 6px 0;
    }

    .wheel {
        height: 80px;
        width: 25px;
        border-color: #777777;
        border-style: solid;
        border-width: 3px 2px;
        text-align: center;
        font-size: 14px;
        padding-top: 24px;
    }

    .wheel-highlight {
        background-color: #dddddd;
    }

    .wheel-active {
        background-color: #c6e4ee;
    }

    .wheel-has-request {
        border-color: #c2000b90 !important;
        color: #c2000b !important;
    }

    .wheel-has-no-update {
        border-color: #c2000b90 !important;
        color: #c2000b !important;
    }

    .wheel-smaller-text {
        font-size: 12px !important;
    }
</style>
