<template>

    <div class="option-wrapper">

        <slot></slot>

    </div>

</template>
<script>
    export default {

        data() {
            return {
                children: [],
                isActive: false
            };
        },

        created() {
            this.children = this.$children;
            // this.active = this.expand;
        },

        mounted() {
            // this.sides.forEach(side => {
            //     this.isActive = this.isActive || side.isActive;
            // });
        },
    };
</script>
<style scoped>

    .option-wrapper {
        margin-top: 20px;
        background-color: white;
        border-top: #DDDDDD 0.8px solid !important;
        border-bottom: #DDDDDD 0.8px solid !important;
    }

</style>
