<template>
    <div class="tab-pane" :class="{'active':isActive}">
        <slot></slot>
    </div>
</template>
<script>
    import {mapState} from 'vuex'

    export default {
        name: 'tab',
        props: {
            name: {required: true},
            icon: {required: false},
            selected: {default: false}
        },

        data() {
            return {
                isActive: false
            };
        },

        computed: {
            href() {
                return '#' + this.name.toLowerCase().replace(/ /g, '-');
            }
        },

        mounted() {
        },
    };
</script>
