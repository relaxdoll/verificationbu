<template>

    <div :class="(show)?'dropdown show': 'dropdown'">

        <div>
            <button v-click-outside="hide" @click="toggle" type="button" class="btn btn-default dropdown-toggle btn-link btn-icon" data-toggle="dropdown"
                    :aria-expanded="show">
                <!--            <button @click="show = !show" type="button" class="btn btn-default dropdown-toggle btn-link btn-icon">-->
                <i class="tim-icons icon-settings-gear-63"></i>
            </button>
        </div>
        <div class="dropdown-menu dropdown-menu-right">
            <slot></slot>
        </div>
    </div>

</template>

<script>
    import {mapState} from 'vuex'

    import ClickOutside from 'vue-click-outside'

    export default {
        directives: {
            ClickOutside
        },
        props: {},
        data() {
            return {
                show: false,
            }
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
        },
        methods: {
            toggle() {
                this.show = true
            },

            hide() {
                this.show = false
            }

        },
    };
</script>

