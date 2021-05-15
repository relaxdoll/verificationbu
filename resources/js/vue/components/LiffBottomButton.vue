<template>
    <div>
        <div v-if="(type === 'color')" style="position: fixed; bottom: 0;">
            <div style="text-align: center;">
                <p @click="click()" href="#" :class="getClass()">{{ label }}</p>
            </div>
            <div style="width: 100vw; height: 30px; background-color: #3b3736;"></div>
        </div>


        <div v-if="(type === 'slide')">
            <transition name="slide">
                <div v-if="(enable)" style="position: fixed; bottom: 0;">
                    <div style="text-align: center;">
                        <p @click="click()" href="#" class="liff-btn enable">{{ label }}</p>
                    </div>
                    <div style="width: 100vw; height: 30px; background-color: #3b3736;"></div>
                </div>
            </transition>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            label: {required: false},
            enable: {default: true},
            type: {default: 'color'}
        },

        data() {
            return {
                isActive: false
            };
        },

        mounted() {
        },

        methods: {
            click() {
                this.$emit("confirm");
            },
            getClass() {
                if (this.enable) {
                    return 'liff-btn enable';
                } else {
                    return 'liff-btn disable';
                }

            }
        }

    };
</script>

<style scoped>
    .enable {
        background-color: #3B3736;
        color: #FFD25A;
        font-weight: 500;
    }

    .disable {
        background-color: #3B3736;
        color: #777777;
        font-weight: 500;
    }

    .slide-enter-active, .slide-leave-active {
        transition: margin-bottom .1s ease-out;
    }

    .slide-enter, .slide-leave-to {
        margin-bottom: -90px;
    }

    .slide-enter-to, .slide-leave {
        margin-bottom: 0px;
    }
</style>
