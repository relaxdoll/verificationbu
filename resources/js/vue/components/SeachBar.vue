<template>
    <div class="form-group" style="padding-top:5px; height: 56px;" :class="{'mini': ($store.state.forceNavMini || $store.state.navBarMini)}">
        <div class="input-group">
            <span class="input-group-prepend">
                <div class="input-group-text" style="background-color: #27293d;">
                    <i class="eec-icons icon-magnifier" style="font-size:14px;"></i>
                </div>
            </span>
            <input ref="searchbar" v-model="search" class="search-bar" type="text" placeholder="Search">
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        data() {
            return {}
        },
        created() {
        },
        computed: {
            localComputed() {
                return true
            },
            ...mapState([
                'loading',
                'theme',
            ]),
            search: {
                get() {
                    return this.$store.state.searchBar
                },
                set(value) {
                    this.$store.commit('searchInput', value);
                }
            },
        }
    };
</script>
<style scoped>
    @media only screen and (max-width: 990px) {
        .form-group {
            display:none;
        }
    }
    .search-bar {
        height: 46px;
        width: 250px;
        background-color: #27293d;
        border-top-right-radius: 23px;
        border-bottom-right-radius: 23px;
        margin-left: -3px;
        border-width: 0;
        border-image-width: 0;
        color: white;
        transition: 0.1s;
    }

    .search-bar:focus {
        outline: none;
        width: 380px;
        border-top-right-radius: 23px !important;
        border-bottom-right-radius: 23px !important;
    }

    .input-group-focus {
        outline: none;
        background-color: #27293d;
    }

    .input-group-text {
        height: 46px;
        width: 42px;
        background-color: #27293d;
        color: #ffffffb3;
        border-top-left-radius: 23px;
        border-bottom-left-radius: 23px;
        line-height: 21px;
        font-size: 14px;
        padding: 10px 12px 10px 16px !important;
        border-width: 0;
    }

    ::placeholder {
        color: #ffffffb3;
        opacity: 1;
    }

    .form-group {
        margin: 0;
    }

    .mini {
        margin-left: 40px;
    }
</style>
