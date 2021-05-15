<template>

    <div class="center-flex">

        <div class="truck-head" style="height: 200px;">
            <wheel placement="1"></wheel>
            <wheel margin="98" placement="2"></wheel>
        </div>
        <div class="joint"></div>
        <differential></differential>
        <trailer-bar height="20"></trailer-bar>
        <div style="display: flex;">
            <div style="height: 5px; width: 12px; background-color: #777777;"></div>
            <div style="height: 5px; width: 38px; border:#777777 2px solid;"></div>
            <div style="height: 5px; width: 12px; background-color: #777777;"></div>
        </div>
        <trailer-bar height="20"></trailer-bar>
        <div style="display: flex;">
            <wheel placement="3"></wheel>
            <wheel margin="1" style="margin-right: 1px;" placement="4"></wheel>
            <div style="height: 80px; width: 12px; background-color: #777777;"></div>
            <div style="margin-top: 36px; height: 8px; width: 38px; border:#777777 2px solid; border-left: none; border-right: none;"></div>
            <div style="height: 80px; width: 12px; background-color: #777777;"></div>
            <wheel margin="1" placement="5"></wheel>
            <wheel margin="1" placement="6"></wheel>
        </div>
        <trailer-bar height="10"></trailer-bar>
        <div style="display: flex;">
            <wheel placement="7"></wheel>
            <wheel margin="1" style="margin-right: 1px;" placement="8"></wheel>
            <div style="height: 80px; width: 12px; background-color: #777777;"></div>
            <div style="margin-top: 36px; height: 8px; width: 38px; border:#777777 2px solid; border-left: none; border-right: none;"></div>
            <div style="height: 80px; width: 12px; background-color: #777777;"></div>
            <wheel margin="1" placement="9"></wheel>
            <wheel margin="1" placement="10"></wheel>
        </div>
        <trailer-bar height="10"></trailer-bar>
        <div style="display: flex;">
            <div style="height: 8px; width: 30px; border:#777777 2px solid; border-right: none;"></div>
            <div style="height: 8px; width: 12px; background-color: #777777;"></div>
            <div style="height: 8px; width: 38px; border:#777777 2px solid; border-left: none; border-right: none;"></div>
            <div style="height: 8px; width: 12px; background-color: #777777;"></div>
            <div style="height: 8px; width: 30px; border:#777777 2px solid; border-left: none;"></div>
        </div>
<!--        <liff-trailer-bar height="2"></liff-trailer-bar>-->
        <div class="joint"></div>

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
