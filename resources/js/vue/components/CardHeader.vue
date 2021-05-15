<template>

    <div class="card-header">
        <div class="tools float-right">
            <slot></slot>
        </div>
        <h5 v-if="count" class="card-category"> {{ 'Total: ' + compCount}} </h5>
        <h5 v-else class="card-category"> {{ description }} </h5>
        <h3 class="card-title"> {{ title}} </h3>
    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        props: {
            'title': {required: false},
            'description': {required: false},
            'count': {required: false},
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
                'theme',
                'dataCount'
            ]),
            compCount() {
                return this.dataCount[this.count] ? this.dataCount[this.count] : 0;
            }
        },
        watch: {},
    };
</script>

