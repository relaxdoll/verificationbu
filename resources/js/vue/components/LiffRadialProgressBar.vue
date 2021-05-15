<template>
    <radial-progress-bar style="margin: auto;" :diameter="70" start-color="rgba(0, 112, 255, 0.6)" stop-color="rgba(0, 112, 255, 0.5)" innerStrokeColor="#fafafa"
                         :animate-speed="speed"
                         :completed-steps="uploadPercentage" :stroke-width="2" :inner-stroke-width="2"
                         :total-steps="totalSteps">
        <p style="font-size:12px; color: #ffffff; margin-block-start: 0; margin-block-end: 0;">{{ uploadPercentage+"%" }}</p>
        <!--        <p style="color: #727272; line-height: 7px;">Uploading</p>-->
    </radial-progress-bar>
</template>

<script>
    // https://github.com/wyzantinc/vue-radial-progress/blob/master/README.md
    import RadialProgressBar from 'vue-radial-progress'
    import {mapState} from 'vuex'

    export default {
        props: {
            'speed': {default: 100},
        },

        data() {
            return {
                completedSteps: 0,
                totalSteps: 100
            }
        },

        computed: {
            ...mapState([
                'uploadPercentage',
            ]),
        },

        components: {
            RadialProgressBar
        }
    }
</script>
