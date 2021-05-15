<template>

    <div class="header-box">
        <div class="header-wrapper">
            <div class="col-12 header">
                {{title}}
            </div>
        </div>
    </div>

</template>
<script>
    export default {
        props: {
            title: {required: true}
        },

        data() {
            return {};
        },

    };
</script>
<style scoped>
    .header-box {
        display: table;
        padding: 0 20px;
        height: 60px;
        width: 100vw;
        background-color: #1d2a44;
        z-index: 99999;
    }

    .header-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .header {
        text-align: center;
        font-weight: 500;
        color: white;
    }
</style>
