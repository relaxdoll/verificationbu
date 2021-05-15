@extends('layouts.app', ['activePage' => 'indexLineRichMenu', 'titlePage' => __('Add Menu')])

@section('style')

    <link href="/css/syncfusion.css" rel="stylesheet"/>
@endsection

@section('content')

    <div class="row">

        <breadcrumb :data="[{'text':'Home','href':'/home'}, {'text':'LINE','href':'#'}, {'text':'Rich Menu','href':'./'}]" active="Create"></breadcrumb>

        <topbutton text="Back" link="./"></topbutton>

        <wizard @submit="submit" title="Create Line Rich Menu" description="Follow the process to add a new LINE Rich Menu to our system.">

            <wizard-tab name="info" icon="eec-icons icon-restaurant-menu">

                <v-form name="form" class="row justify-content-center mt-5">
                    <div class="col-sm-5">
                        <base-input placeholder="Menu Name" field="name" addon-left-icon="eec-icons icon-restaurant-menu"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <base-input placeholder="Chat Bar Text" field="chatBarText" addon-left-icon="tim-icons icon-caps-small"
                                    :vparam="['required']">
                        </base-input>
                    </div>
                    <div class="col-sm-5">
                        <select-box field="rawSize" placeholder="Size" type="select" @input="sizeSelected($event)"
                                    optiontext="text" optionvalue="text" addon-left-icon="tim-icons icon-app"
                                    :forceoption="[{'text':'Full'},{'text':'Half'}]" :vparam="['required']"></select-box>
                    </div>
                    <div class="col-sm-5"></div>
                    <div class="col-sm-5">
                        <base-radio-input field="selected" label="Make Default" :vparam="['required']"
                                          :forceoption="[{'text':'Yes', 'value': true},{'text':'No', 'value': false}]"></base-radio-input>
                    </div>
                    <div class="col-sm-5"></div>
                </v-form>

            </wizard-tab>
            <wizard-tab name="upload" icon="tim-icons icon-image-02">
                <h5 class="info-text"> Upload a menu.</h5>

                <v-form name="image" class="row justify-content-center mt-5">
                    <div class="col-lg-10 text-center" style="margin-top: 10px;">
                        <image-upload ref="imageUpload" field="image" :require="true"></image-upload>
                    </div>
                </v-form>
            </wizard-tab>
            <wizard-tab name="Build" icon="tim-icons icon-image-02">

                <div :style="canvasStyle">
                    <v-stage ref="stage" :config="configKonva" style="text-align: center;" @mousedown="handleStageMouseDown">
                        <v-layer ref="layer">

                            <v-rect v-for="item in this.areas" :key="item.id" :config="item" @dragend="updateAreaPos" @transformend="updateAreaPos"></v-rect>

                            <v-transformer ref="transformer" :config="{rotateEnabled:false, keepRatio:false}"></v-transformer>
                        </v-layer>
                    </v-stage>
                </div>
                <v-form name="action">
                    <div class="row justify-content-center">
                        <div class="col-sm-5 mb-2">
                            <base-button type="info" @click="addArea()" class="btn-previous">
                                Add Area
                            </base-button>
                        </div>
                        <div class="col-sm-5"></div>

                        <div class="col-sm-10 row" v-for="(item, index) in this.forms.action" :key="index">
                            <div class="col-sm-4 row">
                                <div class="col-sm-2" style="padding-top: 11px;">
                                    <div :style="'width:15px; height:15px; border-radius:7.5px; background-color: '+item.color"></div>
                                </div>
                                <select-box class="col-sm-10" :field="index" subfield="type" placeholder="Type" type="select"
                                            optiontext="text" optionvalue="value" addon-left-icon="eec-icons icon-code"
                                            :forceoption="[{'text':'Postback', 'value':'postback'},{'text':'URI','value':'uri'}]" :vparam="['required']"></select-box>
                            </div>
                            <div class="col-sm-8">
                                <base-input v-if="forms.action[index].type === 'uri'" placeholder="URI" :field="index" subfield="uri"
                                            addon-left-icon="eec-icons icon-hash-mark"
                                            :vparam="['required']">
                                </base-input>
                                <base-input v-if="forms.action[index].type === 'postback'" placeholder="DATA" :field="index" subfield="data"
                                            addon-left-icon="eec-icons icon-hash-mark"
                                            :vparam="['required']">
                                </base-input>
                            </div>
                        </div>
                    </div>
                </v-form>
            </wizard-tab>
        </wizard>
    </div>


@endsection

@push('js')
    <script src=" {{ mix('/js/vue/create.js') }}"></script>

    <script>

        new Vue({
            el: '#asset',

            store,

            data: {
                fleetData: [{'text': 'Mapkha', 'value': 1}, {'text': 'Laem Chabang', 'value': 3}, {'text': 'Suksawat', 'value': 2}],
                showSending: false,
                showConfirming: false,
                initScrap: false,
                options: [],
                showSelect: false,
                reference: null,
                allowSubmit: false,
                sizeMultiplier: 0.2,
                areas: [],
                count: 0,
                selectedShapeName: '',
            },

            created() {
                this.$store.dispatch('populateForm', {
                    'form': 'form',
                    'property': 'menu',
                    'field': {
                        rawSize: null,
                        size: {'height': null, 'width': null},
                        name: null,
                        selected: null,
                        chatBarText: null,
                        areas: [],
                    }
                });
                this.$store.dispatch('populateForm', {
                    'form': 'action',
                    'property': 'menu',
                    'field': {}
                });
                this.$store.dispatch('populateForm', {
                    'form': 'image',
                    'property': 'menu',
                    'field': {
                        image: null,
                        id: null,
                    }
                });
            },

            computed: {
                ...mapState([
                    'theme',
                    'forms'
                ]),
                canvasStyle() {
                    if (this.forms.image.image) {
                        return 'background-image: url("' + URL.createObjectURL(this.forms.image.image) + '"); width: ' + this.forms.form.size.width * this.sizeMultiplier + 'px; background-size:  ' + this.forms.form.size.width * this.sizeMultiplier + 'px; background-repeat: no-repeat; margin:auto;'
                    }
                },

                configKonva() {
                    return {
                        width: this.forms.form.size.width * this.sizeMultiplier,
                        height: this.forms.form.size.height * this.sizeMultiplier,
                    }
                },
            },

            methods: {
                sizeSelected(size) {
                    switch (size) {
                        case 'Full':
                            this.$store.commit('updateForm', {'form': 'form', 'field': 'size', 'subfield': 'height', 'value': 1686});
                            this.$store.commit('updateForm', {'form': 'form', 'field': 'size', 'subfield': 'width', 'value': 2500});

                            break;
                        case 'Half':
                            this.$store.commit('updateForm', {'form': 'form', 'field': 'size', 'subfield': 'height', 'value': 843});
                            this.$store.commit('updateForm', {'form': 'form', 'field': 'size', 'subfield': 'width', 'value': 2500});
                            break;
                    }
                },
                addArea() {
                    let height = this.forms.form.size.height * this.sizeMultiplier;
                    let width = this.forms.form.size.width * this.sizeMultiplier;
                    let color = this.getRandomColor();

                    this.$store.state.forms.form.areas.push({
                        'bounds': {
                            x: 0,
                            y: 0,
                            width: 100,
                            height: 100,
                        },
                    });

                    this.$store.commit('addField', {
                        'name': 'action',
                        'field': {
                            'property': 'action' + this.count,
                            'value': {
                                'type': null,
                                'data': null,
                                'color': color,
                                'uri': null,
                            }
                        }
                    });

                    this.areas.push({
                        id: this.areas.length,
                        x: 0,
                        y: 0,
                        width: 100,
                        height: 100,
                        fill: 'transparent',
                        name: 'action' + this.count,
                        stroke: color,
                        strokeWidth: 2,
                        draggable: true,
                        dragBoundFunc: function (pos) {
                            // console.log(this.attrs);
                            let newY = pos.y < 0 ? 0 : (pos.y + this.attrs.height * this.attrs.scaleY) > height ? height - this.attrs.height * this.attrs.scaleY : pos.y;
                            let newX = pos.x < 0 ? 0 : (pos.x + this.attrs.width * this.attrs.scaleX) > width ? width - this.attrs.width * this.attrs.scaleX : pos.x;
                            return {
                                x: newX,
                                y: newY
                            };
                        },
                    });
                    this.count++;
                },
                updateAreaPos(arg) {
                    let attr = arg.target.attrs;
                    console.log(attr);
                    this.$store.state.forms.form.areas[attr.id].bounds.x = Math.floor(attr.x / this.sizeMultiplier);
                    this.$store.state.forms.form.areas[attr.id].bounds.y = Math.floor(attr.y / this.sizeMultiplier);
                    this.$store.state.forms.form.areas[attr.id].bounds.width = Math.floor(attr.width * attr.scaleX / this.sizeMultiplier);
                    this.$store.state.forms.form.areas[attr.id].bounds.height = Math.floor(attr.height * attr.scaleY / this.sizeMultiplier);
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
                    const rect = this.areas.find(r => r.name === name);
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

                getRandomColor() {
                    let letters = '0123456789ABCDEF';
                    let color = '#';
                    for (let i = 0; i < 6; i++) {
                        color += letters[Math.floor(Math.random() * 16)];
                    }
                    return color;
                },

                submit() {
                    this.$store.commit('addField', {
                        'name': 'form',
                        'field': {
                            'property': 'action',
                            'value': this.forms.action
                        }
                    });
                    this.$store.dispatch('submit', {'form': 'form', 'url': '/api/line/richmenu'})
                        .then(response => {
                            console.log(response);
                            this.$store.commit('updateForm', {'form': 'image', 'field': 'id', 'value': response.data.id});
                            this.$store.dispatch('submitFile', {'form': 'image', 'url': '/api/line/richmenu/crud/uploadRichmenu'})
                                .then(response => {
                                    console.log(response);
                                    Swal.fire({
                                        title: 'Complete!',
                                        text: "Line Group has been successfully created.",
                                        icon: 'success',
                                        confirmButtonText: 'OK'
                                    }).then((result) => {
                                        if (result) {
                                            // window.location.href = "./"
                                        }
                                    });
                                });
                        });
                }
            },
        });

    </script>
@endpush
