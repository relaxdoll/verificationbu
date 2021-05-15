import {getField, updateField} from 'vuex-map-fields';

export default {

    state: {
        loading: false,
        uploading: false,
        processing: false,
        theme: 'blue',
        forms: {},
        originalData: {},
        validateError: {},
        forceResetWizard: false,
        property: null,
        mode: '',
        showModal: false,
        modalName: null,
        page_name: null,

        show_drawer: false,
        drawer_name: null,

        driver: null,
        driver_id: null,
        profile: {},

        active_tires: {},
        has_requests: {},
        has_updated: {},
        wheel_text: 'placement',
        has_banner: false,
        placement: null,

        uploadPercentage: 0,
    },

    getters: {
        getNavbarState(state) {
            if (state.navBarMini) {
                return 'sidebar-mini';
            }
            return '';
        },

        getLoadingFromGetters(state) { //take parameter state

            return state.loading;
        },
        getFieldValue: (state) => (param) => {
            if (state.forms[param.form]) {
                if (param.subfield) {
                    return state.forms[param.form][param.field][param.subfield];
                } else {
                    return state.forms[param.form][param.field];
                }
            }
            return null;
        },
        getFieldError: (state) => (field) => {
            if (state.validateError[field]) {
                return state.validateError[field]
            }
            return null;
        },
        getField,
    },

    actions: {
        liffInit({commit}) {
            return liff.init({
                liffId: '1653575237-yDkDbm1r'
            }).then(function (response) {
                commit('getProfile')
            }.bind(this))
        },
        getProfile({commit}) {
            if (liff.isLoggedIn()) {
                liff.getProfile()
                    .then(function (profile) {
                        commit('setProfile', profile);
                        // this.lineId = profile.userId;
                    }.bind(this));
            } else {
                commit('getDriverId', 'U3094f16f5d2775edcaebca950e013091');
                // commit('getDriverId', 'U03851075bfe338576ba62957198a96b0');
            }
        },
        getDriverId({commit}, lineId) {
            return new Promise((resolve, reject) => {
                axios.get('/api/driver/crud/getDriverIdByLineId/' + lineId, {})
                    .then(response => {
                        commit('saveDriver', response.data.data);
                        console.log(response.data);
                        resolve(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error.response);
                    });
            });
        },
        updateAvatar({commit, state}) {
            return new Promise((resolve, reject) => {
                axios.patch('/api/driver/crud/updateAvatar/' + state.driver_id, {'avatar': state.profile.pictureUrl})
                    .then(response => {
                        console.log(response.data);
                        resolve(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error.response);
                    });
            });
        },
        populateForm({commit}, data) {

            commit('setProperty', data.property);

            commit("originalData", {'name': data.form, 'field': data.field});

            commit('addForm', data.form);

            for (let field in data.field) {
                // this[field] = fields[field];
                commit("addField", {'name': data.form, 'field': {'property': field, 'value': data.field[field]}});
            }

        },
        populateEditForm({commit, state}, data) {

            commit('loading', true);
            commit('editMode');

            return new Promise((resolve, reject) => {
                axios.get('/api/' + state.property + '/' + data.id + '/edit')
                    .then(response => {
                        let fields = response.data.data;
                        for (let field in fields) {

                            commit('updateForm', {'form': data.form, 'field': field, 'value': fields[field]});
                            // console.log(field, data[field]);
                        }
                        commit('loading', false);
                        console.log(response.data.data);
                        resolve(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error.response);
                    });
            });
        },
        submit({commit, dispatch, state}, params) {

            commit("loading", true);

            // console.log(dispatch('data'));
            return new Promise((resolve, reject) => {
                axios.post(params.url, state.forms[params.form])
                    .then(response => {
                        if (params.reset) {
                            commit('resetWizard', true);
                        }
                        commit("loading", false);
                        // console.log(response);

                        resolve(response.data);
                    })
                    .catch(error => {
                        commit("loading", false);
                        dispatch('recordError', {'form': params.form, 'error': error});
                        // console.log(error);
                        // console.log(error.response.data);
                        reject(error.response.data);
                    });
            });
        },
        submitFile({commit, dispatch, state}, params) {

            commit("loading", true);

            let data = new FormData();

            for (let property in state.forms[params.form]) {
                if (state.forms[params.form][property]) {
                    data.append(property, state.forms[params.form][property]);
                }
            }

            return new Promise((resolve, reject) => {
                axios.post(params.url, data, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                    onUploadProgress: function (progressEvent) {
                        state.uploadPercentage = parseInt(Math.round((progressEvent.loaded / progressEvent.total) * 100));
                    }.bind(this)
                })
                    .then(response => {
                        if (params.reset) {
                            commit('resetWizard', true);
                        }
                        commit("loading", false);
                        // console.log(response);

                        resolve(response.data);
                    })
                    .catch(error => {
                        commit("loading", false);
                        dispatch('recordError', {'form': params.form, 'error': error});
                        // console.log(error.response.data);
                        reject(error.response.data);
                    });
            });
        },
        submitWithoutLoading({commit, dispatch, state}, params) {

            // console.log(dispatch('data'));
            return new Promise((resolve, reject) => {
                axios.post(params.url, state.forms[params.form])
                    .then(response => {
                        if (params.reset) {
                            commit('resetWizard', true);
                        }
                        // console.log(response);

                        resolve(response.data);
                    })
                    .catch(error => {
                        commit("loading", false);
                        dispatch('recordError', {'form': params.form, 'error': error});
                        // console.log(error);
                        // console.log(error.response.data);
                        reject(error.response.data);
                    });
            });
        },
        update({commit, dispatch, state}, params) {

            commit("loading", true);

            // console.log(dispatch('data'));
            return new Promise((resolve, reject) => {
                axios.patch(params.url, state.forms[params.form])
                    .then(response => {
                        if (params.reset) {
                            commit('resetWizard', true);
                        }
                        commit("loading", false);
                        console.log(response);

                        resolve(response.data);
                    })
                    .catch(error => {
                        commit("loading", false);
                        dispatch('recordError', {'form': params.form, 'error': error});
                        // console.log(error);
                        // console.log(error.response.data);
                        reject(error.response.data);
                    });
            });
        },
        recordError({commit}, error) {
            switch (error.error.response.data.message) {
                case 'Validation Error.':
                    commit('validateError', error.error.response.data.data);
                    break;
            }
        },
    },

    mutations: {
        updateField,
        getProfile() {
            this.dispatch('getProfile');
        },
        getDriverId(state, lineId) {
            this.dispatch('getDriverId', lineId);
        },
        saveDriver(state, driver){
            state.driver_id = driver.id;
            state.driver = driver;
            if (state.profile.pictureUrl) {
                if (driver.avatar !== state.profile.pictureUrl) {
                    this.commit('updateAvatar');
                }
            }
        },
        updateAvatar() {
            this.dispatch('updateAvatar');
        },
        setProfile(state, profile) {
            this.commit('getDriverId', profile.userId);
            return state.profile = profile;
        },
        editMode(state) {
            return state.mode = 'edit';
        },
        setProperty(state, data) {
            return state.property = data;
        },
        loading(state, data) {
            return state.loading = data
        },
        uploading(state, data) {
            if (data) {
                state.uploadPercentage = 0;
            }
            return state.uploading = data
        },
        processing(state, data) {
            if (data) {
                state.uploadPercentage = 0;
            }
            return state.processing = data
        },
        originalData(state, data) {
            let temp = {};
            temp[data.name] = data.field;
            return state.originalData = {...state.originalData, ...temp}
        },
        addForm(state, data) {
            let temp = {};
            temp[data] = {};
            return state.forms = {...state.forms, ...temp}
        },
        addField(state, data) {
            let temp = {};
            temp[data.field.property] = data.field.value;
            return state.forms[data.name] = {...state.forms[data.name], ...temp}
        },
        validateError(state, error) {
            return state.validateError = error;
        },
        updateForm(state, data) {
            if (data.subfield) {
                return state.forms[data.form][data.field][data.subfield] = data.value;
            } else {
                return state.forms[data.form][data.field] = data.value;
            }
        },
        clearError(state, data) {
            return Vue.delete(state.validateError, data);
        },
        resetWizard(state, reset) {
            return state.forceResetWizard = reset;
        },
        navBarToggle(state) {
            axios.post('/api/user/post/miniNav', {'user_id': state.user.id, miniNav: !state.navBarMini});
            return state.navBarMini = !state.navBarMini;
        },
        showModal(state, modal) {
            state.showModal = true;
            return state.modalName = modal;
        },
        setPageName(state, name) {
            return state.page_name = name;
        },
        hideModal(state) {
            state.showModal = false;
            return state.modalName = null;
        },
        uploadPercentage(state, data) {
            return state.uploadPercentage = data;
        },
        showDrawer(state) {
            return state.show_drawer = true;
        },
        hideDrawer(state) {
            return state.show_drawer = false;
        },
        resetForm(state, form){

            state.forms[form] = {};

            return state.forms[form] = {...state.originalData[form]}

        },
        setActiveTires(state, tires){
            return state.active_tires = tires;
        },
        setHasRequests(state, requests){
            state.has_requests = {};

            return state.has_requests = { ...requests};
        },
        setUpdateTire(state, data){
            return state.has_updated = data;
        },
        setWheelText(state, text){
            return state.wheel_text = text;
        },
        hasBanner(state){
            return state.has_banner = true;
        },
        setPlacement(state, placement){
            return state.placement = placement;
        },
        setMode(state, mode){
            return state.mode = mode;
        },
    }
}
