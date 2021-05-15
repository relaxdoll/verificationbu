<template>

    <div class="loader loader-20" v-if="loading">
        <div>
            <div>
                <span></span>
            </div>
        </div>
    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            return {}
        },
        created() {
        },
        computed: {
            localComputed() {
                return true
            },
            ...mapState([
                'loading',
                'theme'
            ])
        }
    };
</script>

<style>
    .loader-20 > div {
        width: 100%;
    }

    .loader-20 div > div span {
        position: fixed;
        display: inline-block;
        height: 2px;
        width: 100%;
        border-radius: 50px;
        background-color: #0889FB;
        overflow: hidden;
        z-index: 10002;
    }

    .loader-20 > div > div span:before {
        content: '';
        position: absolute;
        top: 0;
        width: 40%;
        height: 100%;
        background-image: linear-gradient(to right, transparent, #333, transparent);
        animation: 0.7s moving_03 ease-in-out infinite;
    }

    @keyframes moving_03 {
        0% {
            left: -30%;
        }
        100% {
            left: 100%;
        }
    }
</style>
