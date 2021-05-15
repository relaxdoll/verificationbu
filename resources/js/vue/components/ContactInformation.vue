<template>
    <div style="margin-top: 20px;">

        <selector @click="getThaiQRProfile(url)" :label="label" :currentoption="currentOption"></selector>

        <a v-show="false" href="line://home/public/profile?id=thaiqr" ref="getProfile"></a>
    </div>
</template>

<script>
    export default {
        props: {
            label: {required: true},
            currentview: {default: 'Back'},
            isselect: {required: false},
        },

        data() {
            return {
                lineId: 'U26e7d44f7f32a4997e6fe6d3c62f8f83',
                currentOption: null,
                selected: false,
                url: 'line://home/public/profile?id=thaiqr',
            };
        },

        mounted() {},

        methods: {
            getThaiQRProfile(url) {
                return this.$refs.getProfile.click();
            },
        },

        computed: {}
    }
</script>

<style scoped>
    body {
        color: #3B3736;
        font-size: 16px;
        font-family: "Roboto", "Helvetica", "Arial", sans-serif;
        font-weight: 400 !important;
    }

    .stuff {
        border-top: #DDDDDD 0.8px solid;
        border-bottom: #DDDDDD 0.8px solid;
    }

    .remaining-header {
        line-height: 50px;
        border-bottom: #DDDDDD 0.8px solid;
        margin-left: 20px;
    }

    .remaining-header-last {
        line-height: 50px;
        margin-left: 20px;
    }

    .remaining-content {
        text-align: right;
        padding: 0 40px 0 0;
    }

</style>
