<template>
    <div style="position: relative;">
        <input style="position: relative;" class="card_inputbox" :type="type" name="customer_url" :placeholder="placeholder" :maxlength="maxlength" v-model="inputValue" data/>
        <span v-show="inputValue" class="liff-close-icon">&#x00d7;</span>
        <span v-show="inputValue" @click="inputValue = null" class="liff-close-icon-field"></span>
    </div>
</template>

<script>
    export default {
        props: {
            placeholder: {required:false},
            value: {required:true},
            maxlength: {required: false},
            type:{required:true},
        },

        watch: {
            inputValue(newValue) {
                this.$emit('input', newValue);
            },
        },

        data() {
            return {
                inputValue: this.value,
            };
        },

    };
</script>

<style scoped>
    .card_inputbox{
        font-size: 12px;
        border: none;
        border-top: 0.5px solid #f1f1f1;
        height: 50px;
        border-radius: 0;
        width: 100%;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        padding: 0 20px;
        line-height: 10px;
        color: #929292;
    }
    ::placeholder {
        color: #BBBBBB;
        font-size: 12px;
    }
</style>
