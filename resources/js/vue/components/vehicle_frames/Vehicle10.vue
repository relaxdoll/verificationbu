<template>

    <div class="center-flex">
        <div class="truck-head">
            <wheel placement="1"></wheel>
            <wheel margin="98" placement="2"></wheel>
        </div>
        <div class="joint"></div>
        <div class="truck-upper-body">
            <wheel placement="3"></wheel>
            <wheel margin="1" placement="4"></wheel>

            <wheel margin="72" placement="5"></wheel>
            <wheel margin="1" placement="6"></wheel>
        </div>

        <div class="truck-lower-body">
            <wheel placement="7"></wheel>
            <wheel margin="1" placement="8"></wheel>

            <wheel margin="72" placement="9"></wheel>
            <wheel margin="1" placement="10"></wheel>
        </div>

        <wheel placement="11" is-spare style="margin-top: 20px;"></wheel>
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
