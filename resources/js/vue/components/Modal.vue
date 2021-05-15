<template>
    <div id="modal-container" :class="{'one': cShowModal, 'out': isHiding}" v-show="cShowModal" @click.self="$store.commit('hideModal')">
        <div style="position: fixed; right: 20px; top: 20px; z-index:10002;">
            <a @click="$store.commit('hideModal')">
                <i class="tim-icons icon-simple-remove" style="color: white;"></i>
            </a>
        </div>
        <div id="modal-content" class="mt-5 row justify-content-center mobile-container" @click.self="$store.commit('hideModal')">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        props: {},
        data() {
            return {
                'cShowModal': false,
                'isHiding': false,
            }
        },
        created() {
        },
        computed: {
            localComputed() {
                return true
            },
            ...mapState([
                'theme',
                'showModal'
            ])
        },
        watch: {
            showModal(val) {
                if (val) {
                    this.cShowModal = val;
                    this.toggleBodyClass('addClass', 'modal-open');

                } else {
                    this.isHiding = true;
                    this.toggleBodyClass('removeClass', 'modal-open');
                    setTimeout(function () {
                        this.cShowModal = this.$store.state.showModal;
                        this.isHiding = false;
                    }.bind(this), 700);
                }
            }
        },
        methods: {
            toggleBodyClass(addRemoveClass, className) {
                const el = document.body;

                if (addRemoveClass === 'addClass') {
                    el.classList.add(className);
                } else {
                    el.classList.remove(className);
                }
            },
        },
    };
</script>
<style lang="scss">
    /*https://codepen.io/designcouch/pen/obvKxm*/

    #modal-container {
        position: fixed;
        width: 100%;
        height: 100%;
        z-index: 10001;
        background: rgba(0, 0, 0, 0.7);
        margin: 0 !important;
        padding-top: 0;
        overflow-y: auto;
        left: 0;
        top: 0;
        transform: scale(0);

        &.one {
            transform: scaleY(.01) scaleX(0);
            animation: unfoldIn 0.5s cubic-bezier(0.165, 0.840, 0.440, 1.000) forwards;

            #modal-content {
                transform: scale(0);
                animation: zoomIn .2s .5s cubic-bezier(0.165, 0.840, 0.440, 1.000) forwards;
            }

            &.out {
                transform: scale(1);
                animation: unfoldOut .4s .2s cubic-bezier(0.165, 0.840, 0.440, 1.000) forwards;

                #modal-content {
                    animation: zoomOut .2s cubic-bezier(0.165, 0.840, 0.440, 1.000) forwards;
                }
            }
        }

    }


    @keyframes unfoldIn {
        0% {
            transform: scaleY(.005) scaleX(0);
        }
        50% {
            transform: scaleY(.005) scaleX(1);
        }
        100% {
            transform: scaleY(1) scaleX(1);
        }
    }

    @keyframes unfoldOut {
        0% {
            transform: scaleY(1) scaleX(1);
        }
        50% {
            transform: scaleY(.005) scaleX(1);
        }
        100% {
            transform: scaleY(.005) scaleX(0);
        }
    }

    @keyframes zoomIn {
        0% {
            transform: scale(0);
        }
        100% {
            transform: scale(1);
        }
    }

    @keyframes zoomOut {
        0% {
            transform: scale(1);
        }
        100% {
            transform: scale(0);
        }
    }

    @media screen and (max-width: 400px) {
        .mobile-container {
            margin: 7px -7px !important;
        }
    }
</style>
