<template>

    <div class="flex-center" style="display: flex;">
        <div v-if="isLoading" class="loader"></div>
        <img v-show="isLoaded" @load="loaded" :src="src" alt="image"
             style="height:100%;">
    </div>

</template>
<script>
    export default {

        props: {
            src: {required: true},
        },

        data() {
            return {
                isLoaded: false,
                isLoading: false
            };
        },

        watch: {
            src: function (value) {
                this.isLoaded = false;
                this.isLoading = true;
            }
        },

        created() {
        },

        mounted() {
            this.isLoading = true;
        },

        methods: {
            loaded() {
                this.isLoaded = true;
                this.isLoading = false;
            },
        }
    };
</script>
<style scoped>

    .loader,
    .loader:after {
        border-radius: 50%;
        width: 5em;
        height: 5em;
    }

    .loader {
        margin: auto;
        font-size: 10px;
        position: relative;
        text-indent: -9999em;
        border-top: 0.5em solid rgba(255, 255, 255, 0.2);
        border-right: 0.5em solid rgba(255, 255, 255, 0.2);
        border-bottom: 0.5em solid rgba(255, 255, 255, 0.2);
        border-left: 0.5em solid #e14eca;
        -webkit-transform: translateZ(0);
        -ms-transform: translateZ(0);
        transform: translateZ(0);
        -webkit-animation: load8 0.5s infinite linear;
        animation: load8 0.5s infinite linear;
    }

    @-webkit-keyframes load8 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

    @keyframes load8 {
        0% {
            -webkit-transform: rotate(0deg);
            transform: rotate(0deg);
        }
        100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
    }

</style>
