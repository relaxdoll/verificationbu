<template>

    <div class="card-body">
        <slot></slot>
    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        props: {
            'title': {required: false},
            'description': {required: false},
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

