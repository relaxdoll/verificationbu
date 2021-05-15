<template>
    <li :class="(isActive === true)? 'nav-item active': 'nav-item '">
        <a class="nav-link" :href="href">
            <!--            <i style="margin-top: 2px;" class="material-icons">{{icon}}</i>-->
            <div v-if="icon">
                <i :class="icon"></i>
                <p>{{ title }}</p>
            </div>

            <div v-else>
                <span class="sidebar-mini-icon">{{ mini }}</span>
                <span class="sidebar-normal">{{ title }} </span>
            </div>
        </a>
    </li>
</template>
<script>
    export default {
        props: {
            title: {required: false},
            icon: {required: false},
            mini: {required: false},
            activepage: {required: false},
            href: {default: '#'},
            page: {required: false},
        },

        data() {
            return {
                isActive: false
            };
        },

        mounted() {
            this.isActive = (this.activepage === this.page);
        },

    };
</script>
