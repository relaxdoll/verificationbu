'use strict';

module.exports = class Form {
    /**
     * Create a new Form instance.
     *
     * @param {object} data
     */
    constructor(data) {
        this.originalData = data;

        for (let field in data) {
            this[field] = data[field];
        }

        this.errors = new Errors();
    }


    /**
     * Fetch all relevant data for the form.
     */
    data() {
        let data = {};

        for (let property in this.originalData) {
            data[property] = this[property];
        }

        return data;
    }


    imageData() {

        let data = new FormData();

        for (let property in this.originalData) {
            if (this[property]) {
                data.append(property, this[property]);
            }
        }

        return data;
    }

    /**
     * Reset the form fields.
     */
    reset() {
        for (let field in this.originalData) {
            this[field] = '';
        }

        this.errors.clear();
    }


    /**
     * Send a POST request to the given URL.
     * .
     * @param {string} url
     * @param reset
     */
    post(url, reset = true, image = false) {
        return this.submit('post', url, reset, image);
    }

    postImageArray(url, reset = true) {

        let data = this.imageData();

        let image_array = new FormData();

        for (let property in this.image_array) {
            if (this.image_array[property]) {
                data.append(property, this.image_array[property]);
            }
        }

        return new Promise((resolve, reject) => {
            axios['post'](url, data, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            })
                .then(response => {

                    console.log(response.data);

                    this.onSuccess(response.data, reset);

                    resolve(response.data);
                })
                .catch(error => {
                    this.onFail(error.response.data);
                    // console.log(error.response.data);
                    reject(error.response.data);
                });
        });
    }

    /**
     * Send a PUT request to the given URL.
     * .
     * @param {string} url
     */
    put(url) {
        return this.submit('put', url);
    }


    /**
     * Send a PATCH request to the given URL.
     * .
     * @param {string} url
     */
    patch(url) {
        return this.submit('patch', url);
    }


    /**
     * Send a DELETE request to the given URL.
     * .
     * @param {string} url
     */
    delete(url) {
        return this.submit('delete', url);
    }


    /**
     * Submit the form.
     *
     * @param {string} requestType
     * @param {string} url
     * @param reset
     */
    submit(requestType, url, reset, image) {
        if (image) {
            return new Promise((resolve, reject) => {
                axios[requestType](url, this.imageData(), {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then(response => {
                        this.onSuccess(response.data, reset);

                        resolve(response.data);
                    })
                    .catch(error => {
                        this.onFail(error.response.data);
                        // console.log(error.response.data);
                        reject(error.response.data);
                    });
            });
        } else {
            return new Promise((resolve, reject) => {
                axios[requestType](url, this.data())
                    .then(response => {
                        this.onSuccess(response.data, reset);

                        resolve(response.data);
                    })
                    .catch(error => {
                        this.onFail(error.response.data);
                        // console.log(error.response.data);
                        reject(error.response.data);
                    });
            });
        }
    }


    /**
     * Handle a successful form submission.
     *
     * @param {object} data
     * @param reset
     */
    onSuccess(data, reset) {

        if (reset) {
            this.reset();
        }
    }


    /**
     * Handle a failed form submission.
     *
     * @param {object} errors
     */
    onFail(errors) {
        this.errors.record(errors);
    }
}

class Errors {
    /**
     * Create a new Errors instance.
     */
    constructor() {
        this.errors = {};
        this.reason = null;
    }


    /**
     * Determine if an errors exists for the given field.
     *
     * @param {string} field
     */
    has(field) {
        return this.errors.hasOwnProperty(field);
    }


    /**
     * Determine if we have any errors.
     */
    any() {
        return Object.keys(this.errors).length > 0;
    }


    /**
     * Retrieve the error message for a field.
     *
     * @param {string} field
     */
    get(field) {
        if (this.errors[field]) {
            return this.errors[field][0];
        }
    }


    /**
     * Record the new errors.
     *
     * @param {object} errors
     */
    record(errors) {
        this.errors = errors.data;
        this.reason = errors.message;
    }

    add(field, message) {
        let errors = {};
        errors[field] = [];
        errors[field].push(message);

        this.errors = errors;
    }


    /**
     * Clear one or all error fields.
     *
     * @param {string|null} field
     */
    clear(field) {
        if (field) {
            delete this.errors[field];

            return;
        }

        this.errors = {};
    }
}
