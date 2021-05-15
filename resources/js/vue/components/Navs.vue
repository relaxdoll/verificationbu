<template>
    <div>
        <div class="card">
            <div class="card-header card-header-tabs card-header-primary">
                <div class="nav-tabs-navigation">
                    <div class="nav-tabs-wrapper">
                        <span class="nav-tabs-title">{{ title }}:</span>
                        <ul class="nav nav-tabs">
                            <li v-for="tab in tabs" class="nav-item">
                                <a :href="tab.href" @click="selectTab(tab)" :class="(tab.isActive)? 'active nav-link' : 'nav-link'">
                                    <i class="material-icons">{{ tab.icon }}</i> {{ tab.name }}
                                    <div class="ripple-container"></div>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <slot></slot>
                </div>
            </div>
        </div>
            <li class="nav-item {{ ($activePage == 'createReplaceTire' || $activePage == 'indexReplaceTire') ? ' active' : '' }}">
                <a class="nav-link {{ ($activePage == 'createReplaceTire' || $activePage == 'indexReplaceTire') ? '' : 'collapse' }}" data-toggle="collapse" href="#MaintenanceNav"
                   aria-expanded="{{ ($activePage == 'createReplaceTire' || $activePage == 'indexReplaceTire') ? 'true' : 'false' }}">
                    <i class="material-icons">build</i>
                    <p>{{ __('Maintenance') }}
                        <b class="caret"></b>
                    </p>
                </a>
                <div class="collapse {{ ($activePage == 'createReplaceTire' || $activePage == 'indexReplaceTire') ? 'show' : '' }}" id="MaintenanceNav">
                    <ul class="nav">

                        <li class="nav-item{{ $activePage == 'indexReplaceTire' ? ' active' : '' }}">
                            <a class="nav-link" href="#">
                                <i style="margin-top: 2px;" class="material-icons">assignment</i>
                                <span class="sidebar-normal">{{ __('Summary') }} </span>
                            </a>
                        </li>

                        <li class="nav-item{{ $activePage == 'createReplaceTire' ? ' active' : '' }}">
                            <a class="nav-link" href="{{ route('replacetires.create') }}">
                                <i style="margin-top: 2px;" class="material-icons">cached</i>
                                <span class="sidebar-normal">{{ __('Replace Tire') }} </span>
                            </a>
                        </li>

                    </ul>
                </div>
            </li>

    </div>
</template>
<script>
    export default {
        props: {
            title: {required: false},
        },

        data() {
            return {navs: []};
        },

        created() {
            this.navs = this.$children;
        },

        methods: {
        }
    };
</script>
