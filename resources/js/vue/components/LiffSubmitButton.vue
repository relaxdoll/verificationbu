<template>

    <div class="at-bottom">
        <div style="text-align: center;">
            <p @click="submit()" class="liff-btn">{{label}}</p>
            <div v-if="windowHeight > 736" class="filler"></div>
        </div>

    </div>

</template>
<script>
    export default {
        props: {
            label: {default: 'ส่ง'}
        },

        data() {
            return {
                windowHeight: window.innerHeight,
            };
        },

        methods: {
            submit() {
                this.$emit('submit', true);
            },
        },

    };
</script>
<style scoped>
    .liff-btn {
        background-color: #1d2a44;
        color: white;
        font-weight: 500;
    }

    .at-bottom {
        position: fixed;
        bottom: 0;
    }

    .filler {
        width: 100vw;
        height: 30px;
        background-color: #1d2a44;
    }
</style>
