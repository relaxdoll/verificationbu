<template>
    <li :class="(isActive)?'nav-item active':'nav-item'">
        <a :class="(isActive)?'nav-link':'nav-link collapse'" data-toggle="collapse"
           :href="'#'+title"
           :aria-expanded="(isActive)? 'true': 'false'">
            <i :class="icon"></i>
            <p>{{ title }}
                <b class="caret"></b>
            </p>
        </a>
        <div :class="(isActive)? 'collapse show':'collapse'" :id="title">
            <ul class="nav">
                <slot></slot>
            </ul>
        </div>
    </li>

</template>
<script>
    export default {
        props: {
            title: {required: false},
            icon: {required: false},
            expand: {default: false}
        },

        data() {
            return {
                sides: [],
                isActive: false
            };
        },

        created() {
            this.sides = this.$children;
            this.isActive = this.expand;
        },

        mounted() {
            this.sides.forEach(side => {
                this.isActive = this.isActive || side.isActive;
            });
        },
    };
</script>
