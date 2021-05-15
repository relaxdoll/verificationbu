<template>

    <div :class="{'col-md-12': !rowSelected || !collapseonselect, 'col-md-3': (rowSelected && collapseonselect)}" style="transition: 0.2s;">
        <div class="card">
            <slot></slot>
        </div>
    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        props: {
            collapseonselect: Boolean,
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
                'rowSelected',
            ])
        }
    };
</script>

