<template>
    <div class="card card-plain" >
        <div class="card-header" style="cursor: pointer; padding: 12px 15px;" @click="doClickAction()">
            <a data-toggle="collapse" :aria-expanded="body ? 'true' : 'false'">
                {{header}}
                <i class="tim-icons icon-minimal-down"></i>
            </a>
        </div>
        <div :class="{collapse:body}">
            <div class="card-body">
                <slot></slot>
            </div>
        </div>
    </div>

</template>

<script>
    export default {
        name: "Panel",
        props: {
            header: {required: true},
        },
        data() {
            return {
                body: true,
            }
        },
        methods: {
            doClickAction() {
                this.body = !this.body; // Open Panel and Close Panel
                this.$emit('click');
            },
        },

    }
</script>

<style scoped>

</style>
