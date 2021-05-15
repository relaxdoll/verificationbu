<template>
    <div class="card" :style="'background-color:' +background+ ';'">
        <div class="card-header">
            <ul class="nav nav-tabs nav-tabs-primary" role="tablist">
                <li v-for="(tab, index) in tabs" :key="index" class="nav-item">
                    <a @click="selectTab(tab, index)" class="nav-link" :class="{'active':tab.isActive}" data-toggle="tab" href="#link1" role="tablist">
                        <i :class="tab.icon" style="top: -1px;"></i> {{tab.name}}
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <!-- Tab panes -->
            <div class="tab-content tab-space">
                <slot></slot>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: {
            background: {default: '#27293d'},
        },

        data() {
            return {
                tabs: [],
                activeTabIndex: 0,
            };

        },

        created() {
        },

        mounted() {
            let index = 0;
            this.$children.forEach((child) => {
                if (child.$options.name === 'tab') {
                    child.index = index++;
                    this.tabs.push(child);

                }
            });
            this.tabs[0].isActive = true;
        },

        methods: {
            selectTab(selectedTab, index) {
                this.activeTabIndex = index;
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.href == selectedTab.href);
                });
            },
        }
    };
</script>
