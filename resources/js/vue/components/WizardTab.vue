<template>

    <div :class="(isActive)?'tab-pane active show':'tab-pane'">
        <slot></slot>
    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: 'wizard-tab',
        props: {
            name: {required: true},
            icon: {required: false},
            selected: {default: false}
        },
        data() {
            return {
                isActive: false,
                isChecked: false,
                validated: false,
                forms: [],
                index: null,
            };
        },
        created() {
        },
        watch: {
            isActive: function (value) {
                if (value === true) {
                    this.isChecked = true;
                }
            },
            mode: function (value){
                if (value === 'edit'){
                    this.isChecked = true;
                }
            }
        },
        methods: {
            validateTab() {
                let temp = true;
                this.forms.forEach((form) => {
                    temp = temp && form.validateForm();
                });
                return this.validated = temp;
            },
            redirectError() {
                this.$parent.changeTab(this, this.index)
            },
            reset() {
                this.isChecked = false;
                this.validated = false;
                this.$children.forEach((child) => {
                    if (child.$options.name === 'vForm') {
                        child.reset();
                    }
                });
            }
        },

        mounted() {
            this.isActive = this.selected;

            this.$children.forEach((child) => {
                if (child.$options.name === 'vForm') {
                    this.forms.push(child)
                }
            });
        },
        computed: {
            href() {
                return '#' + this.name.toLowerCase().replace(/ /g, '-');
            },
            ...mapState([
                'theme',
                'mode'
            ])
        }
    };
</script>

