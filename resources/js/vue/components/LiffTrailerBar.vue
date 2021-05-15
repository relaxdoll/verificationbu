<template>

    <div style="display: flex;">
        <div :style="'height: '+height+'px; width: 12px; background-color: #777777;'"></div>
        <div :style="'height: 0; width: 38px;'"></div>
        <div :style="'height: '+height+'px; width: 12px; background-color: #777777;'"></div>
    </div>

</template>
<script>
    export default {
        props: {
            height: {default: 0}
        },

        data() {
            return {};
        },

    };
</script>
