<template>
    <div >

        <liffdrawer @close="selected = false" align="right" :maskclosable="true" :label="label" :currentview="currentview"
                    :closeable="true" :header="false">

            <div v-if="selected">

                <div class="header">
                    <div style="display: table-cell; vertical-align: bottom;">
                        <div class="row">
                            <div  class="col-12" style="text-align: center; color: white; font-weight: 400;">
                                Move and Scale
                            </div>
                        </div>
                    </div>
                </div>

                <vue-croppie
                    ref="croppieRef"
                    :enableOrientation="true"
                    :boundary="{ width: '100vw', height: '100vh'}"
                    :enableResize="false"
                    :viewport="{width: viewportSize, height:viewportSize, type: 'square'}"
                    :show-zoomer="false"
                    customClass="croppie"
                    @result="result"
                    @update="update">
                </vue-croppie>

                <div class="footer">
                    <div style="display: table-cell; vertical-align: middle;">
                        <div class="row">
                            <div @click.stop="cancel()" class="col-5" style="text-align: left; color: white; font-weight: 400;">
                                Cancel
                            </div>
                            <div @click.stop="rotate(90)" class="col-2" style="text-align: center; color: white;">
                                <i class="material-icons" > rotate_left </i>
                            </div>
                            <div @click.stop="crop()" class="col-5" style="text-align: right; color: white; font-weight: 400;">
                                Choose
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </liffdrawer>

        <!-- Note that 'ref' is important here (value can be anything). read the description about `ref` below. -->

        <!-- the result -->
<!--        <img v-bind:src="cropped">-->

        <!--        <button @click="bind()">Bind</button>-->
        <!-- Rotate angle is Number -->
        <!--        <button @click="rotate(-90)">Rotate Left</button>-->
        <!--        <button @click="rotate(90)">Rotate Right</button>-->
<!--        <button @click="crop()">Crop Via Callback</button>-->
<!--        <button @click="cropViaEvent()">Crop Via Event</button>-->
    </div>
</template>

<script>
    export default {
        created() {

            this.viewportSize = screen.width - 40;
            this.containerHeight = screen.height - 150;
        },
        mounted() {

            this.$refs.croppieRef.bind({
                url: this.imagedata,
            });


        },
        props: {
            label: {required: true},
            currentview: {default: 'Back'},
            selected: {default: false},
            imagedata: {required: true},
        },
        data() {
            return {
                qr: new QR(),
                cropped: null,
                viewportSize: null,
                containerHeight: null,
            }
        },
        methods: {
            cancel(){
                this.selected = false;
                this.$emit('cancel', true);
            },
            // CALBACK USAGE
            crop() {
                // Here we are getting the result via callback function
                // and set the result to this.cropped which is being
                // used to display the result above.
                let options = {
                    circle: false,
                    quality: 1,
                    size: 'original'
                };
                this.$refs.croppieRef.result(options, (output) => {
                    // this.cropped = output;
                    this.$emit('crop', output);
                    this.selected = false;
                });
            },
            // EVENT USAGE
            cropViaEvent() {
                this.$refs.croppieRef.result(options);
            },
            result(output) {
                this.cropped = output;
            },
            update(val) {
                // console.log(val);
            },
            rotate(rotationAngle) {
                // Rotates the image
                this.$refs.croppieRef.rotate(rotationAngle);
            }
        }
    }
</script>
<style>
    .cr-viewport {
        border: none !important;
    }
    .cr-vp-square {
        border: none !important;
    }
</style>
<style scoped>
    .croppie {
        background-color: black;
        position: fixed;
        top: 0;
    }
    .cr-viewport {
        border: none !important;
    }
    .cr-vp-square {
        border: none !important;
    }
    .header {
        position: sticky;
        top: 0;
        display: table;
        padding: 0 20px;
        height: 90px;
        width: 100vw;
        background-color: transparent;
        z-index: 99999;
    }
    .footer {
        position: fixed;
        bottom: 0;
        display: table;
        padding: 0 20px;
        height: 90px;
        width: 100vw;
        background-color: transparent;
        z-index: 99999;
    }
</style>
