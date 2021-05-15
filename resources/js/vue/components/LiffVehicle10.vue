<template>

    <div class="center-flex">
        <div class="truck-head">
            <liff-wheel placement="1"></liff-wheel>
            <liff-wheel margin="98" placement="2"></liff-wheel>
        </div>
        <div class="joint"></div>
        <div class="truck-upper-body">
            <liff-wheel placement="3"></liff-wheel>
            <liff-wheel margin="1" placement="4"></liff-wheel>

            <liff-wheel margin="72" placement="5"></liff-wheel>
            <liff-wheel margin="1" placement="6"></liff-wheel>
        </div>

        <div class="truck-lower-body">
            <liff-wheel placement="7"></liff-wheel>
            <liff-wheel margin="1" placement="8"></liff-wheel>

            <liff-wheel margin="72" placement="9"></liff-wheel>
            <liff-wheel margin="1" placement="10"></liff-wheel>
        </div>

        <liff-wheel placement="11" is-spare style="margin-top: 20px;"></liff-wheel>
    </div>


</template>
<script>
    export default {

        props: {},

        data() {
            return {};
        },

    };
</script>
