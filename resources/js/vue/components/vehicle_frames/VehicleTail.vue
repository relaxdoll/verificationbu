<template>

    <div class="center-flex">
        <div class="joint"></div>
        <div class="truck-upper-body">
            <wheel placement="1"></wheel>
            <wheel margin="1" placement="2"></wheel>

            <wheel margin="72" placement="3"></wheel>
            <wheel margin="1" placement="4"></wheel>
        </div>

        <div class="truck-middle-body">
            <wheel placement="5"></wheel>
            <wheel margin="1" placement="6"></wheel>

            <wheel margin="72" placement="7"></wheel>
            <wheel margin="1" placement="8"></wheel>
        </div>

        <div class="truck-lower-body">
            <wheel placement="9"></wheel>
            <wheel margin="1" placement="10"></wheel>

            <wheel margin="72" placement="11"></wheel>
            <wheel margin="1" placement="12"></wheel>
        </div>

        <wheel placement="13" is-spare style="margin-top: 20px;"></wheel>
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
