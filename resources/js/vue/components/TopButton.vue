<template>

    <div class="col-5 text-right" style="padding: 5px 15px;">
        <a :href="link" style="font-size: 12px;" class="btn btn-sm btn-primary">{{ text }}</a>
    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        props: {
            text: {required: true},
            link: {required: true},
        },
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
                'theme'
            ])
        }
    };
</script>

