<template>
    <div class="card">
        <div class="card-header" v-if="title || category">
            <h5 class="card-category" v-if="category">{{category}}</h5>
            <h3 class="card-title" v-if="title">{{title}}</h3>
        </div>
        <div id="accordion" role="tablist" aria-multiselectable="true" class="card-collapse">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Panels",
        props: {
            category: {required: false},
            title: {required: false},
        },
    }
</script>

<style scoped>

</style>
