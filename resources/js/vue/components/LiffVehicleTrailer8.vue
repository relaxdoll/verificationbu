<template>
    <div>
        <liff-trailer-upper-body></liff-trailer-upper-body>

        <div class="center-flex">

            <liff-trailer-bar height="20"></liff-trailer-bar>
            <liff-trailer-sub-bar></liff-trailer-sub-bar>
            <liff-trailer-bar height="20"></liff-trailer-bar>
            <liff-trailer-sub-bar></liff-trailer-sub-bar>
            <liff-trailer-bar height="10"></liff-trailer-bar>
            <div style="display: flex;">
                <liff-wheel placement="1"></liff-wheel>
                <liff-wheel margin="1" style="margin-right: 1px;" placement="2"></liff-wheel>
                <div style="height: 80px; width: 12px; background-color: #777777;"></div>
                <div style="margin-top: 36px; height: 8px; width: 38px; border:#777777 2px solid; border-left: none; border-right: none;"></div>
                <div style="height: 80px; width: 12px; background-color: #777777;"></div>
                <liff-wheel margin="1" placement="3"></liff-wheel>
                <liff-wheel margin="1" placement="4"></liff-wheel>
            </div>
            <liff-trailer-bar height="10"></liff-trailer-bar>
            <div style="display: flex;">
                <liff-wheel placement="5"></liff-wheel>
                <liff-wheel margin="1" style="margin-right: 1px;" placement="6"></liff-wheel>
                <div style="height: 80px; width: 12px; background-color: #777777;"></div>
                <div style="margin-top: 36px; height: 8px; width: 38px; border:#777777 2px solid; border-left: none; border-right: none;"></div>
                <div style="height: 80px; width: 12px; background-color: #777777;"></div>
                <liff-wheel margin="1" placement="7"></liff-wheel>
                <liff-wheel margin="1" placement="8"></liff-wheel>
            </div>
            <liff-trailer-bar height="10"></liff-trailer-bar>
            <liff-trailer-sub-bar></liff-trailer-sub-bar>
            <liff-trailer-bar height="20"></liff-trailer-bar>
            <div class="joint"></div>

            <liff-wheel placement="9" is-spare style="margin-top: 20px;"></liff-wheel>
        </div>
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
