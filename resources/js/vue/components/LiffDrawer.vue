<template>
    <div>
<!--        <transition name="fade" mode="out-in">-->
<!--            <div :style="indexCls()" @click="onMask" v-if="$slots.default" :class="{ mask }" >click </div>-->
<!--        </transition>-->
        <transition :enter-active-class="alignInCls" :leave-active-class="alignOutCls">
            <div key="content" :class="{ closeable, [align.toLowerCase()]: true }" v-if="$slots.default" class="vue-simple-drawer cover" :style="indexCls()">

                <div v-if="(header)" class="header" :style="customstyle">
                    <div style="display: table-cell; vertical-align: middle;">
                        <div class="row">
                            <div @click.stop="close" class="col-1"
                                 style="text-align: right; padding-right: 0;  color: #555555; font-size: 40px; font-family: 'BenchNine', sans-serif;">
                                <
                            </div>
                            <div @click.stop="close" class="col-2" style="text-align: left; color: #BBBBBB; padding-left: 0;">
                                {{ currentview }}
                            </div>
                            <div class="col-6" style="text-align:center; font-weight: 500; color: #FFD25A;">
                                {{ label }}
                            </div>
                        </div>
                    </div>
                </div>
                <slot></slot>
            </div>
        </transition>
    </div>
</template>
<script>
    export default {
        props: {
            customstyle: {required: false},
            header: {default: true},
            align: {
                type: String,
                default: "right",
                validator: function (value) {
                    // The value must match one of these strings
                    return ["left", "up", "right", "down"].indexOf(value) !== -1;
                }
            },
            closeable: {
                type: Boolean,
                default: true
            },
            mask: {
                type: Boolean,
                default: true
            },
            maskclosable: {
                type: Boolean,
                default: false
            },
            zindex: {
                type: Number,
                default() {
                    return this.simpleDrawerIndex;
                }
            },
            label: {default:'label'},
            currentview: {default:'Back'}
        },
        provide() {
            return {
                simpleDrawerIndex: this.computedIndex + 1
            };
        },
        inject: {
            simpleDrawerIndex: {default: 450}
        },
        methods: {
            close() {
                this.$emit("close");
            },
            onMask() {
                if (this.maskclosable) {
                    this.close();
                }
            },
            indexCls(offset = 0) {
                return {
                    zindex: this.computedIndex + offset
                };
            }
        },
        computed: {
            alignInCls() {
                return `animated bounceIn${this.align.toLowerCase()}`;
            },
            alignOutCls() {
                return `animated bounceOut${this.align.toLowerCase()}`;
            },
            alighCloseCls() {
                return `close-${this.align.toLowerCase()}`;
            },
            computedIndex() {
                return this.zindex || this.simpleDrawerIndex;
            }
        }
    };
</script>
<style lang="scss">
    @import "../../../sass/drawer/liffIndex";

</style>
<style scoped>
    .header {
        display: table;
        padding: 0 20px;
        height: 60px;
        width: 100vw;
        background-color: #3B3736;
        /*z-index: 99999;*/
    }
</style>
