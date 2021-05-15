<template>
    <div class="fileinput text-center">
        <div
            class="fileinput-new thumbnail"
            :class="{ 'img-circle': type === 'avatar' }"
        >
            <img :src="image" alt="preview"/>
        </div>
        <div>
      <span class="btn btn-primary btn-file">
        <span class="fileinput-new">{{
          fileExists ? changeText : selectText
        }}</span>
        <input type="hidden" value="" name=""/>
        <input
            accept="image/*"
            @change="handlePreview"
            type="file"
            name="..."
            class="valid"
            :multiple="false"
            aria-invalid="false"
        />
      </span>
            <base-button v-if="fileExists" @click="removeFile" round type="danger">
                <i class="fas fa-times"></i> {{ removeText }}
            </base-button>
        </div>
        <slot name="error" v-if="error && (showError || forceShowError)">
            <label class="error">{{ error }}</label>
        </slot>
    </div>
</template>
<script>
    export default {
        name: 'image-upload',
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
            showError: {default: false},
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
            };
        },
        computed: {
            value() {
                return this.$store.getters.getFieldValue({'form': this.form, 'field': this.field});
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
        mounted() {

            this.getForm(this);

        },
        methods: {
            getForm(component) {
                if (component.$parent.$options.name == 'vForm') {
                    return this.form = component.$parent.name;
                }
                this.getForm(component.$parent);
            },
            handlePreview(event) {
                let file = event.target.files[0];
                this.error = '';
                this.imagePreview = URL.createObjectURL(file);
                this.$store.commit('clearError', this.field);
                this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'value': file});
                this.$emit('change', file);
            },
            removeFile() {
                this.imagePreview = null;
                this.$store.commit('updateForm', {'form': this.form, 'field': this.field, 'value': null});
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
<style></style>
