<template>

    <div class="option-wrapper">

        <slot></slot>

    </div>

</template>
<script>
    export default {
        name: 'liffInputGroup',

        data() {
            return {
            };
        },

    };
</script>
<style scoped>

    .option-wrapper {
        border-top: #DDDDDD 0.8px solid !important;
    }

</style>
