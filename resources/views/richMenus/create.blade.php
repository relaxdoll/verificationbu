@extends('layouts.app', ['activePage' => 'createReplaceTire', 'titlePage' => __('Create Rich Menu')])

@section('style')
    <style>
        .slide-fade-enter-active, .slide-fade-long-enter-active, .slide-fade-reverse-long-enter-active {
            transition: all .3s ease;
        }

        .slide-fade-leave-active {
            transition: all .3s cubic-bezier(0.5, 0.5, 0.8, 1.0);
        }

        .slide-fade-enter, .slide-fade-leave-to
            /* .slide-fade-leave-active below version 2.1.8 */
        {
            transform: translateX(-20px);
        }

        .slide-fade-long-enter
            /* .slide-fade-leave-active below version 2.1.8 */
        {
            transform: translateY(-50px);
        }

        .slide-fade-reverse-long-enter
            /* .slide-fade-leave-active below version 2.1.8 */
        {
            transform: translateX(200px);
        }

        .list-enter-active, .list-leave-active {
            transition: all 0.5s;
        }

        .list-enter, .list-leave-to {
            opacity: 0;
            transform: translateX(30px);
        }

        .list-move {
            transition: transform 0.5s;
        }

        .tr-active {
            background-color: #e3f2fd;
            border: 2px solid #9acffa;
        }

    </style>
@endsection
@section('content')
    <div id="asset" class="content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-xxl-8 col-xl-10 col-md-12">
                    <div class="card">
                        <div class="card-header card-header-tabs card-header-primary">
                            <div class="nav-tabs-navigation">
                                <div class="nav-tabs-wrapper">
                                    <span class="nav-tabs-title">Size:</span>
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a :class="(menu.property.size == 'full')? 'active nav-link' : 'nav-link'" @click="menu.set('size', 'full')">
                                                <i class="material-icons">crop_3_2</i> Full
                                                <div class="ripple-container"></div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a :class="(menu.property.size == 'half')? 'active nav-link' : 'nav-link'" @click="menu.set('size', 'half')">
                                                <i class="material-icons">crop_7_5</i> Half
                                                <div class="ripple-container"></div>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane active">

                                    <div class="text-right">
                                        <transition name="slide-fade-long" mode="out-in">
                                            {{--<a @click="menu.addArea()" href="#" class="btn btn-primary btn-sm" v-show="menu.property.image"--}}
                                            <a @click="menu.addArea()" href="#" class="btn btn-primary btn-sm"
                                               key="0">{{ __('Add Action') }}</a>
                                        </transition>
                                    </div>

                                    <div style="padding-bottom: 15px;">

                                        <inputbox :value.sync="menu.property.name" label="Name" @input="menu.set('name', $event)" type="string"
                                                  :error="menu.property.errors.has('name')" :errorvalue="menu.property.errors.get('name')"></inputbox>

                                        <inputbox :value.sync="menu.property.image" label="Image" @input="setImage($event)" type="image"
                                                  :error="menu.property.errors.has('image')" :errorvalue="menu.property.errors.get('image')"></inputbox>

                                        <inputbox :value.sync="menu.property.chatBarTitle" label="Chat Bar Title" @input="menu.set('chatBarTitle', $event)" type="string"
                                                  :error="menu.property.errors.has('chatBarTitle')" :errorvalue="menu.property.errors.get('chatBarTitle')"></inputbox>
                                    </div>

                                    {{--<div :style="canvasStyle" v-if="menu.property.image">--}}
                                    <div :style="canvasStyle">
                                        <v-stage ref="stage" :config="configKonva" style="text-align: center;" @mousedown="handleStageMouseDown">
                                            <v-layer ref="layer">
                                                {{--<v-image v-for="(image, index) in menu.images" :key="index" :config="image" @mousedown="menu.images[index].draggable = false"></v-image>--}}
                                                {{--<v-image :config="menu.images[index]" @mousedown="handleDragStart"></v-image>--}}
                                                {{--<v-rect :config="configCircle" @dragend="handleDragEnd"></v-rect>--}}

                                                <v-rect v-for="item in this.menu.areas" :key="item.id" :config="item" @transformend="handleDragEnd"></v-rect>

                                                {{--<v-rect :config="configCircle" @dragend="handleDragEnd"></v-rect>--}}
                                                <v-transformer ref="transformer" :config="{rotateEnabled:false, keepRatio:false}"></v-transformer>
                                            </v-layer>
                                        </v-stage>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('js')
    <script src="/js/vue/richMenu.js"></script>
    <script src="/js/form/form.js"></script>
    <script src="/js/class/richMenu.js"></script>


    <script>

        new Vue({
            el: '#asset',

            data: {
                searchfield: null,

                menu: new richMenu(),

                configCircle: {
                    x: 100,
                    y: 100,
                    width: 100,
                    height: 100,
                    fill: 'transparent',
                    stroke: 'black',
                    strokeWidth: 1,
                    draggable: true,
                    dragBoundFunc: function (pos) {
                        let newY = pos.y < 0 ? 0 : pos.y > 337.2 ? 337.2 : pos.y;
                        let newX = pos.x < 0 ? 0 : pos.x > 500 ? 500 : pos.x;
                        return {
                            x: newX,
                            y: newY
                        };
                    }
                },
                image: null,

                selectedShapeName: '',


            },

            watch: {
                searchfield: function (newVal, oldVal) {
                    this.inventory.filter(newVal);
                }
            },


            mounted() {
            },

            computed: {
                canvasStyle() {
                    return 'background-image: url("' + this.image + '"); width: ' + this.menu.size.width * this.menu.sizeMultiplier + 'px; background-size:  ' + this.menu.size.width * this.menu.sizeMultiplier + 'px; background-repeat: no-repeat; margin:auto;'
                },

                configKonva() {
                    return {
                        width: this.menu.size.width * this.menu.sizeMultiplier,
                        height: this.menu.size.height * this.menu.sizeMultiplier
                    }
                },
            },

            methods: {
                handleDragEnd(arg) {
                    console.log(arg);
                },

                handleDragStart(arg) {
                    this.configImg.draggable = false;
                },
                //
                setImage(arg) {
                    this.menu.set('image', arg);

                    let reader = new FileReader();
                    reader.onload = (e) => {
                        this.image = e.target.result;
                    };
                    if (arg) {
                        reader.readAsDataURL(arg);
                    }

                },
                handleStageMouseDown(e) {
                    // clicked on stage - cler selection
                    if (e.target === e.target.getStage()) {
                        this.selectedShapeName = '';
                        this.updateTransformer();
                        return;
                    }

                    // clicked on transformer - do nothing
                    const clickedOnTransformer =
                        e.target.getParent().className === 'Transformer';
                    if (clickedOnTransformer) {
                        return;
                    }

                    // find clicked rect by its name
                    const name = e.target.name();
                    const rect = this.menu.areas.find(r => r.name === name);
                    if (rect) {
                        this.selectedShapeName = name;
                    } else {
                        this.selectedShapeName = '';
                    }
                    this.updateTransformer();
                },
                updateTransformer() {
                    // here we need to manually attach or detach Transformer node
                    const transformerNode = this.$refs.transformer.getStage();
                    const stage = transformerNode.getStage();
                    const {selectedShapeName} = this;

                    const selectedNode = stage.findOne('.' + selectedShapeName);
                    // do nothing if selected node is already attached
                    if (selectedNode === transformerNode.node()) {
                        return;
                    }

                    if (selectedNode) {
                        // attach to another node
                        transformerNode.attachTo(selectedNode);
                    } else {
                        // remove transformer
                        transformerNode.detach();
                    }
                    transformerNode.getLayer().batchDraw();
                },

            },
        });
    </script>
@endpush
