<template>
    <div>
        <!--    <div class="fileinput">-->
        <!--        <label :for="field" class="image-uploader" :class="{ 'img-circle': type === 'avatar' }"-->
        <!--               style="border-bottom: #DDDDDD 1px solid; border-top: #DDDDDD 1px solid; width: 100vw; height: 200px;">-->
        <!--            <img src="/icon/photo.svg" alt="preview" style="width: 20%; margin: 75px 0 20px 0;"/>-->
        <!--        </label>-->
        <input accept="image/*" @change="handlePreview" type="file" :name="(subfield)?subfield:field" :id="(subfield)?subfield:field" class="valid" :multiple="false"
               aria-invalid="false"
               style="display: none;"/>
        <!--        <slot name="error" v-if="error && (showError || forceShowError)">-->
        <!--            <label class="error">{{ error }}</label>-->
        <!--        </slot>-->
        <!--    </div>-->
        <label :for="(subfield)?subfield:field" class="input-theme" :class="{'bottom-border':islast || !isgrouped || error, 'top-border': !isgrouped}">
            <div class="text-wrapper" style="display:flex;">
                <div style="width: 100px; display:flex; height: 90px; padding:10px 15px;" :class="{'disable':disable}">
                    <div v-if="(!imagePreview)" style="border: #dfdfdf 1px solid; border-radius: 10px; background-color: #dfdfdf; width: 70px; height: 70px;">
                        <i style="color: #ffffff; font-size: 36px; margin:17px;" class="icon icon-image-add-2"></i>
                    </div>
                    <div v-else style="border: #fefefe 1px solid; border-radius: 10px; background-color: #fefefe; width: 70px; height: 70px;">
                        <img style="width: 70px; height: 70px; border-radius: 10px; object-fit: cover;" :src="image" alt="preview"/>

                    </div>
                    <!--                <img src="/icon/photo.svg" alt="preview" style="height: 64px; width: 64px"/>-->
                </div>
                <div style="width:100%; display:flex" :class="{'bottom-border':!islast && isgrouped && !error}">
                    <div style="width:100%; font-size: 16px; font-weight: 300; padding:17px 0 0 0; display:flex; height: 70px; line-height:16px; color:black;"
                         :class="{'disable':disable}">
                        <div style="position:relative; width: 100%;">
                            <div style="float: left;">{{label}}</div>
                            <div class="progress" style="width: 100%; margin-top: 50px;" v-show="imagePreview">
                                <div class="progress-bar" role="progressbar"
                                     style="width: 100%;">
                                    <span class="progress-value">100%</span>
                                    <span class="progress-value" style="left: 0; right: auto; color: #888888;">Successfully uploaded</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div style="padding:32px 10px 32px 0; display:flex; height: 70px; line-height:26px; margin-left:auto;">
                        <span class="next">></span>
                    </div>
                </div>
            </div>
        </label>
        <label v-show="error" style="font-size: 11px; color: red; margin: 0 0 0 15px;">{{ error }}</label>
    </div>
</template>
<script>
    export default {
        name: 'liff-image-upload',
        props: {
            type: {
                type: String,
                default: '',
                description: 'Image upload type (""|avatar)'
            },
            src: {
                type: String,
                default: '',
                description: 'Initial image to display'
            },
            selectText: {
                type: String,
                default: 'Select image'
            },
            changeText: {
                type: String,
                default: 'Change'
            },
            removeText: {
                type: String,
                default: 'Remove'
            },
            require: {required: false},
            field: {required: false},
            subfield: {required: false},
            label: {required: false},
            showError: {default: false},
            disable: {default: false},
        },
        data() {
            let avatarPlaceholder = '/img/placeholder.jpg';
            let imgPlaceholder = '/img/image_placeholder.jpg';
            return {
                placeholder: this.type === 'avatar' ? avatarPlaceholder : imgPlaceholder,
                imagePreview: null,
                form: null,
                isInvalid: false,
                forceShowError: false,
                focused: false,
                touched: false,
                error: null,
                isgrouped: false,
                islast: false,
            };
        },
        mounted() {

            this.getForm(this);

            if (this.$parent.$options.name == 'liffInputGroup') {
                this.isgrouped = true;

                let indexId = [];

                this.$parent.$children.forEach(child => {
                    indexId.push(child._uid)
                });

                this.islast = this._uid === indexId.slice(-1)[0];
            }
        },
        computed: {
            value() {
                return this.$store.getters.getFieldValue({'form': this.form, 'field': this.field, 'subfield': this.subfield});
            },
            fileExists() {
                return this.imagePreview !== null;
            },
            image() {
                return this.imagePreview || this.src || this.placeholder;
            },
            formError() {
                return this.$store.getters.getFieldError(this.field);
            }
        },
        methods: {
            getForm(component) {
                if (component.$parent.$options.name == 'LiffForm') {
                    return this.form = component.$parent.name;
                }
                this.getForm(component.$parent);
            },
            handlePreview(event) {
                let file = event.target.files[0];
                this.error = '';
                this.imagePreview = URL.createObjectURL(file);
                this.$store.commit('clearError', this.field);
                this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'subfield': this.subfield, 'value': file});
                this.$emit('change', file);
            },
            removeFile() {
                this.imagePreview = null;
                this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'subfield': this.subfield, 'value': null});
                this.$emit('change', null);
            },
            checkValidateError() {
                this.error = '';
                if (this.require) {
                    if (!this.value) {
                        this.isInvalid = true;
                        return this.error = 'Please upload an image.';
                    } else {
                        if (this.formError) {
                            this.isInvalid = true;
                            this.error = this.formError[0];
                        } else {
                            this.isInvalid = false;
                            this.error = null;
                        }
                    }
                } else {

                    if (this.formError) {
                        this.isInvalid = true;
                        this.error = this.formError[0];
                    } else {
                        this.isInvalid = false;
                        this.error = null;
                    }
                }
            },
            reset() {
                this.touched = false;
                this.error = null;
                this.isInvalid = false;
                this.forceShowError = false;
                this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'value': null});
            }
        }
    };
</script>
<style scoped>

    .image-uploader {
        margin: auto;
        width: 100vw;
        height: 300px;
        border: #DDDDDD 1px dashed;
        background-color: white;
        text-align: center;
        vertical-align: middle;
    }

    .input-theme {
        /*display: table;*/
        /*padding: 0 20px;*/
        margin: 0;
        height: 90px;
        width: 100vw;
        background-color: white;
    }

    .next {
        text-align: left;
        padding-left: 10px;
        color: #DDDDDD;
        font-size: 26px;
        font-family: 'BenchNine', sans-serif;
    }

    .disable {
        opacity: 0.3;
    }

    .bottom-border {
        border-bottom: #DDDDDD 1px solid;
    }

    .top-border {
        border-top: #DDDDDD 1px solid;
    }

    .progress {
        margin-bottom: 10px;
        height: 5px;
    }

    .progress-bar {
        border-radius: 0.875rem;
        box-shadow: none;
        background: rgba(0, 112, 255, 0.6);
    }

    .progress-value {
        position: absolute;
        top: 2px;
        margin-top: 30px;
        right: 0;
        color: black;
        font-size: 10px;
    }
</style>
