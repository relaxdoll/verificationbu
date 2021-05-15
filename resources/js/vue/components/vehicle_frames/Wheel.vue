<template>

    <div @click="wheelSelected()" :style="'display: flex; margin-left: '+margin+'px; cursor: pointer;'">
        <div :class="getClass()" class="left-sidewall"></div>
        <div :class="getClass()" class="wheel">{{getText()}}</div>
        <div :class="getClass()" class="right-sidewall"></div>
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
                info: null,
                info_2: null,
                request: null,
                request_2: null,
                active: false,
                form: null,
                field: null,
                isUpdated: null,
            };
        },

        mounted() {
            this.getForm(this);
            if(this.datum){
                this.info = this.datum.activeTire[this.placement];
                this.request = this.datum.request[this.placement];
            }
        },

        computed: {
            ...mapState([
                'datum',
                'wheel_text',
                'wheel_select',
                'wheel_swap',
                'wheel_select_id',
                'wheel_swap_id',
                'swap_mode',
                'datum_2',
            ]),
            show_vehicle_2: function () {
                return this.swap_mode && this.datum_2.frame;
            }
        },

        watch: {
            datum(value) {
                this.info = this.datum.activeTire[this.placement];
                this.request = this.datum.request[this.placement];
            },
            datum_2(value) {
                if (value.vehicle){
                    this.info_2 = this.datum_2.activeTire[this.placement];
                    this.request_2 = this.datum_2.request[this.placement];
                }else{
                    this.info_2 = null;
                }
            },
        },


        methods: {
            getForm(component) {
                if (component.$parent.$options.name == 'vForm') {
                    this.form = component.$parent.name;
                    return this.field = component.$parent.field;
                }
                this.getForm(component.$parent);
            },
            getText() {
                switch (this.wheel_text) {
                    case 'placement':
                        return this.placement;
                    case 'tread':
                        if (this.show_vehicle_2) {
                            if (this.info_2) {
                                if (this.info_2.end_tread_depth) {
                                    return this.info_2.end_tread_depth;
                                }
                                return this.info_2.start_tread_depth;
                            } else {
                                return '';
                            }
                        } else {
                            if (this.info) {
                                if (this.info.end_tread_depth) {
                                    return this.info.end_tread_depth;
                                }
                                return this.info.start_tread_depth;
                            } else {
                                return '';
                            }
                        }
                }
            },
            wheelSelected() {
                if (!this.swap_mode) {
                    if (this.$store.state.wheel_swap !== this.placement && (this.info || !this.$store.state.wheel_swap)) {
                        this.$store.state.wheel_select = this.placement;
                        this.$store.commit('updateForm', {'form': 'replace', 'field': 'placement_id', 'value': null});
                        this.$store.state.wheel_select_id = null;

                        if (this.info) {
                            this.$store.state.wheel_select_id = this.info.id;
                            this.$store.commit('updateForm', {'form': 'replace', 'field': 'placement_id', 'value': this.info.id});
                            this.$store.commit('updateForm', {'form': 'swap', 'field': 'placement_id_1', 'value': this.info.id});
                            this.$store.commit('updateForm', {'form': 'swap', 'field': 'is_spare_1', 'value': this.isSpare});
                        }

                        this.$store.commit('updateForm', {'form': 'replace', 'field': 'placement', 'value': this.placement});
                        this.$store.commit('updateForm', {'form': 'replace', 'field': 'is_spare', 'value': this.isSpare});
                    }
                } else {

                    if (this.show_vehicle_2) {
                        if (this.info_2) {
                            this.$store.state.wheel_swap = this.placement;
                            this.$store.state.wheel_swap_id = this.info_2.id;
                            this.$store.commit('updateForm', {'form': 'swap', 'field': 'placement_id_2', 'value': this.info_2.id});
                            this.$store.commit('updateForm', {'form': 'swap', 'field': 'is_spare_2', 'value': this.isSpare});
                            this.$store.state.swap_vehicle_2 = true;

                            setTimeout(function () {
                                this.$store.commit('swapMode', false)
                            }.bind(this), 500);
                        }
                    } else {
                        if (this.info && this.$store.state.wheel_select !== this.placement) {
                            this.$store.state.wheel_swap = this.placement;
                            this.$store.state.wheel_swap_id = this.info.id;
                            this.$store.commit('updateForm', {'form': 'swap', 'field': 'placement_id_2', 'value': this.info.id});
                            this.$store.commit('updateForm', {'form': 'swap', 'field': 'is_spare_2', 'value': this.isSpare});
                            this.$store.commit('swapMode', false);
                            this.$store.state.swap_vehicle_2 = false;
                        }

                    }
                }

            },
            getClass() {

                if (this.show_vehicle_2) {
                    if (!this.info_2) {
                        return {};
                    } else {
                        if (this.wheel_swap_id === this.info_2.id) {
                            return {'wheel-swap': true};
                        }
                    }
                } else {
                    if (!this.info) {
                        if(!this.swap_mode && this.wheel_select === this.placement){
                            return {'wheel-selected': true};
                        }
                        return {};
                    } else {
                        if (this.wheel_swap_id === this.info.id) {
                            return {'wheel-swap': true};
                        }

                        if (this.wheel_select_id === this.info.id) {
                            return {'wheel-selected': true};
                        }

                        if (this.request) {
                            return {'has-request': true};
                        }
                    }
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

    .wheel-selected {
        border-color: #1d8cf8 !important;
        color: #1d8cf8 !important;
    }

    .wheel-swap {
        border-color: #c2000b !important;
        color: #c2000b !important;
    }

    .wheel-active {
        background-color: #c6e4ee;
    }

    .has-request {
        border-color: #c2000b90;
        color: #c2000b;
    }

    .wheel-has-no-update {
        border-color: #c2000b90 !important;
        color: #c2000b !important;
    }

    .wheel-smaller-text {
        font-size: 12px !important;
    }
</style>
