<template>

    <div class="center-flex">

        <div class="truck-head" style="height: 200px;">
            <liff-wheel placement="1"></liff-wheel>
            <liff-wheel margin="98" placement="2"></liff-wheel>
        </div>
        <div class="joint"></div>
        <liff-differential></liff-differential>
        <liff-trailer-bar height="20"></liff-trailer-bar>
        <div style="display: flex;">
            <div style="height: 5px; width: 12px; background-color: #777777;"></div>
            <div style="height: 5px; width: 38px; border:#777777 2px solid;"></div>
            <div style="height: 5px; width: 12px; background-color: #777777;"></div>
        </div>
        <liff-trailer-bar height="20"></liff-trailer-bar>
        <div style="display: flex;">
            <liff-wheel placement="3"></liff-wheel>
            <liff-wheel margin="1" style="margin-right: 1px;" placement="4"></liff-wheel>
            <div style="height: 80px; width: 12px; background-color: #777777;"></div>
            <div style="margin-top: 36px; height: 8px; width: 38px; border:#777777 2px solid; border-left: none; border-right: none;"></div>
            <div style="height: 80px; width: 12px; background-color: #777777;"></div>
            <liff-wheel margin="1" placement="5"></liff-wheel>
            <liff-wheel margin="1" placement="6"></liff-wheel>
        </div>
        <liff-trailer-bar height="10"></liff-trailer-bar>
        <div style="display: flex;">
            <liff-wheel placement="7"></liff-wheel>
            <liff-wheel margin="1" style="margin-right: 1px;" placement="8"></liff-wheel>
            <div style="height: 80px; width: 12px; background-color: #777777;"></div>
            <div style="margin-top: 36px; height: 8px; width: 38px; border:#777777 2px solid; border-left: none; border-right: none;"></div>
            <div style="height: 80px; width: 12px; background-color: #777777;"></div>
            <liff-wheel margin="1" placement="9"></liff-wheel>
            <liff-wheel margin="1" placement="10"></liff-wheel>
        </div>
        <liff-trailer-bar height="10"></liff-trailer-bar>
        <div style="display: flex;">
            <div style="height: 8px; width: 30px; border:#777777 2px solid; border-right: none;"></div>
            <div style="height: 8px; width: 12px; background-color: #777777;"></div>
            <div style="height: 8px; width: 38px; border:#777777 2px solid; border-left: none; border-right: none;"></div>
            <div style="height: 8px; width: 12px; background-color: #777777;"></div>
            <div style="height: 8px; width: 30px; border:#777777 2px solid; border-left: none;"></div>
        </div>
<!--        <liff-trailer-bar height="2"></liff-trailer-bar>-->
        <div class="joint"></div>

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
