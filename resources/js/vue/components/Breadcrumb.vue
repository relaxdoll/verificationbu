<template>

    <ol class="breadcrumb bg-transparent col-7">
        <li v-for="(datum, index) in data" :key="index" class="breadcrumb-item">
            <a :href="datum.href">{{ datum.text }}</a>
        </li>
        <li class="breadcrumb-item active">{{active}}</li>
    </ol>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        props: {
            data: {required: true},
            active: {required: true},
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

