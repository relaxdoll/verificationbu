<template>

    <div class="col-md-11 mr-auto ml-auto">
        <!--      Wizard container        -->
        <div class="wizard-container">
            <div class="card card-wizard active">
                <form>
                    <!--        You can switch " data-color="primary" "  with one of the next bright colors: "green", "orange", "red", "blue"       -->
                    <div class="card-header text-center">
                        <h3 class="card-title">
                            {{title}}
                        </h3>
                        <h5 class="description">
                            {{ description}}
                        </h5>
                        <div class="wizard-navigation">
                            <div class="progress-with-circle">
                                <div class="progress-bar" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="3" :style="progressWidth"></div>
                            </div>
                            <ul class="nav nav-pills">
                                <li v-for="(tab, index) in tabs" :key="index" class="nav-item" :style="navWidth">
                                    <!--                                    <a class="" href="#about">-->
                                    <a @click="selectTab(tab, index)" :class="getPillClass(tab)" href="#about">
                                        <i :class="tab.icon"></i>
                                        <p>{{tab.name}}</p>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <slot></slot>
                        </div>
                    </div>
                    <div v-if="mode === 'edit'" class="card-footer">
                        <div style="text-align: center;">
                            <base-button wide type="info" @click.native="update" class="btn-previous">
                                Update
                            </base-button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div v-else class="card-footer">
                        <div class="pull-right">
                            <base-button v-if="activeTabIndex < tabCount - 1" type="primary" wide @click.native="nextTab" class="btn-next">
                                Next
                            </base-button>
                            <base-button v-else-if="allowsubmit" wide @click.native="submit">
                                Create
                            </base-button>
                            <input type="button" class="btn btn-finish btn-fill btn-primary btn-wd" name="finish" value="Finish" style="display: none;">
                        </div>
                        <div class="pull-left">
                            <base-button v-if="activeTabIndex > 0" wide type="primary" @click.native="prevTab" class="btn-previous">
                                Previous
                            </base-button>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>

        </div>
        <!-- wizard container -->
    </div>

</template>

<script>
    import {mapState} from 'vuex'

    export default {
        props: {
            title: {required: true},
            description: {required: false},
            allowsubmit: {default: true},
        },
        data() {
            return {
                tabs: [],
                activeTabIndex: 0,
            };
        },
        created() {
        },
        computed: {
            localComputed() {
                return true
            },
            tabCount() {
                return this.tabs.length;
            },
            progressWidth() {
                let blockWidth = 100 / this.tabs.length;
                let halfBlockWidth = blockWidth / 2;

                let width = (this.activeTabIndex * blockWidth) + halfBlockWidth;

                return 'width: ' + width + '%;';
            },
            navWidth() {
                return 'width: ' + 100 / this.tabs.length + '%;';
            },
            ...mapState([
                'theme',
                'mode',
                'forceResetWizard'
            ])
        },
        mounted() {
            let index = 0;
            this.$children.forEach((child) => {
                if (child.$options.name === 'wizard-tab') {
                    child.index = index++;
                    this.tabs.push(child);

                }
            });
            this.tabs[0].isActive = true;
        },
        watch: {

            forceResetWizard: function (value) {
                if (value) {
                    this.reset();
                    this.$store.commit('resetWizard', false);
                }
            }
        },

        methods: {
            nextTab() {
                if (this.tabs[this.activeTabIndex].validateTab()) {
                    this.changeTab(this.tabs[this.activeTabIndex + 1], this.activeTabIndex + 1);
                }
            },
            submit() {
                if (this.tabs[this.activeTabIndex].validateTab()) {
                    this.$emit('submit', true);
                }
            },
            update() {
                if (this.tabs[this.activeTabIndex].validateTab()) {
                    this.$emit('update', true);

                }
            },
            prevTab() {
                // if (this.tabs[this.activeTabIndex].validateTab()) {
                this.changeTab(this.tabs[this.activeTabIndex - 1], this.activeTabIndex - 1);
                // }
            },
            getPillClass(tab) {
                let pillClass = 'nav-link';
                if (tab.isChecked) {
                    pillClass += ' checked';
                }
                if (tab.isActive) {
                    pillClass += ' active';
                }
                // return 'nav-link checked active show';
                return pillClass;
            },
            selectTab(selectedTab, index) {
                if (selectedTab.isChecked) {
                    if (this.tabs[this.activeTabIndex].validateTab()) {
                        this.changeTab(selectedTab, index);
                    }
                }
            },
            changeTab(selectedTab, index) {
                this.activeTabIndex = index;
                this.tabs.forEach(tab => {
                    tab.isActive = (tab.href == selectedTab.href);
                });
            },
            reset() {
                this.changeTab(this.tabs[0], 0);
                this.activeTabIndex = 0;

                this.$children.forEach((child) => {
                    if (child.$options.name === 'wizard-tab') {
                        child.reset();

                    }
                });
            }
        },
    };
</script>

