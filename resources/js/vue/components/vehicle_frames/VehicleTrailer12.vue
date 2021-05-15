<template>

    <div>
        <trailer-upper-body></trailer-upper-body>

        <div class="center-flex">

            <trailer-bar height="20"></trailer-bar>
            <trailer-sub-bar></trailer-sub-bar>
            <trailer-bar height="20"></trailer-bar>
            <trailer-sub-bar></trailer-sub-bar>
            <trailer-bar height="10"></trailer-bar>
            <div style="display: flex;">
                <wheel :placement="1"></wheel>
                <wheel margin="1" style="margin-right: 1px;" :placement="2"></wheel>
                <div style="height: 80px; width: 12px; background-color: #777777;"></div>
                <div style="margin-top: 36px; height: 8px; width: 38px; border:#777777 2px solid; border-left: none; border-right: none;"></div>
                <div style="height: 80px; width: 12px; background-color: #777777;"></div>
                <wheel margin="1" :placement="3"></wheel>
                <wheel margin="1" :placement="4"></wheel>
            </div>
            <trailer-bar height="10"></trailer-bar>
            <div style="display: flex;">
                <wheel :placement="5"></wheel>
                <wheel margin="1" style="margin-right: 1px;" :placement="6"></wheel>
                <div style="height: 80px; width: 12px; background-color: #777777;"></div>
                <div style="margin-top: 36px; height: 8px; width: 38px; border:#777777 2px solid; border-left: none; border-right: none;"></div>
                <div style="height: 80px; width: 12px; background-color: #777777;"></div>
                <wheel margin="1" :placement="7"></wheel>
                <wheel margin="1" :placement="8"></wheel>
            </div>
            <trailer-bar height="10"></trailer-bar>
            <div style="display: flex;">
                <wheel :placement="9"></wheel>
                <wheel margin="1" style="margin-right: 1px;" :placement="10"></wheel>
                <div style="height: 80px; width: 12px; background-color: #777777;"></div>
                <div style="margin-top: 36px; height: 8px; width: 38px; border:#777777 2px solid; border-left: none; border-right: none;"></div>
                <div style="height: 80px; width: 12px; background-color: #777777;"></div>
                <wheel margin="1" :placement="11"></wheel>
                <wheel margin="1" :placement="12"></wheel>
            </div>
            <trailer-bar height="10"></trailer-bar>
            <trailer-sub-bar></trailer-sub-bar>
            <trailer-bar height="20"></trailer-bar>
            <div class="joint"></div>

            <wheel placement="13" is-spare style="margin-top: 20px;"></wheel>
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
