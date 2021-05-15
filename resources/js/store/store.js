import {getField, updateField} from "vuex-map-fields";

function getMQ() {
    switch (true) {
        case (screen.width < 450):
            return 'sm';
        case (1250 <= screen.width && screen.width >= 450):
            return 'md';
        case (screen.width > 1250):
            return 'lg';
    }
}

export default {

    state: {

        // ---------------------User--------------------
        user,


        // ---------------------Utility--------------------
        loading: false,
        navBarMini: miniNav,
        forceNavMini: false,
        searchBar: null,
        showSearchBar: false,
        theme: 'blue',
        showModal: false,
        modalName: null,

        mq: getMQ(),
        showIndex: true,


        // ---------------------Index--------------------
        tables: {},
        dataCount: {},
        sortSetting: {},
        filterSetting: {},
        datum: {},
        rowSelected: false,
        model: null,
        rowId: null,
        refreshTable: false,


        // ---------------------Create--------------------
        forms: {},
        originalData: {},
        validateError: {},
        forceResetWizard: false,
        mode: 'create',


        // ---------------------Wheel--------------------
        wheel_text: 'tread',
        wheel_select: null,
        wheel_swap: null,
        wheel_select_id: null,
        wheel_swap_id: null,
        swap_mode: false,
        swap_vehicle_2: false,
        datum_2: {},
    },

    getters: {

        // ---------------------Utility--------------------
        getLoadingFromGetters(state) { //take parameter state

            return state.loading;
        },


        // ---------------------Create--------------------
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

        // ---------------------Index--------------------
        getTableData({commit}, param) {

            commit("loading", true);

            let endpoint = param.property;

            if (param.is_group) {
                endpoint += '/crud/group';
            }

            if (param.url) {
                endpoint = param.url;
            }

            return new Promise((resolve, reject) => {
                axios.get('/api/' + endpoint, {
                    params: param.params,
                })
                    .then(response => {
                        commit("loading", false);
                        this.backUp = response.data.data;
                        console.log(response.data);
                        commit("addTable", param.property);
                        commit("addTableData", {'table_name': param.property, 'table_data': response.data.data});
                        resolve(response.data);
                    })
                    .catch(error => {
                        commit("loading", false);
                        console.log(error);
                        reject(error.response);
                    });
            });
        },
        rowSelect({commit, state, dispatch}, id) {
            if (state.rowId === id) {
                commit('clearRowId');
                commit('rowDeSelected');
            } else {
                commit('setCurrentRowId', id);
                commit('rowSelected');
                dispatch('getRowData', id);
            }
        },
        getRowData({commit, state}, id) {
            commit("loading", true);
            return new Promise((resolve, reject) => {
                axios.get('/api/' + state.model + '/' + id)
                    .then(response => {
                        commit('setDatum', response.data.data);

                        setTimeout(function () {
                            commit("loading", false);
                        }, 500);

                        console.log(response.data.data);
                        resolve(response.data);
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error.response);
                    });
            });
        },
        initializeSortGroup({state}, data) {

            if (!state.sortSetting[data.property]) {
                state.sortSetting[data.property] = {};
                for (let group in state.tables[data.property]) {
                    state.sortSetting[data.property][group] = {};
                }
            } else {
                for (let group in state.tables[data.property]) {
                    state.sortSetting[data.property][group] = {};
                }
            }

        },
        initializeSort({state}, data) {

            if (!state.sortSetting[data.property]) {
                state.sortSetting[data.property] = {};
            }

        },
        sortGroup({commit, state}, data) {
            if (state.sortSetting[data.property][data.group]['key'] !== data.key) {
                state.sortSetting[data.property][data.group]['key'] = data.key;
                state.sortSetting[data.property][data.group]['order'] = null;
            }
            let result = null;
            switch (state.sortSetting[data.property][data.group]['order']) {
                case 'asc':
                    result = _.orderBy(state.tables[data.property][data.group], data.key, 'desc');
                    state.sortSetting[data.property][data.group]['order'] = 'desc';
                    commit('updateTableGroup', {'property': data.property, 'data': result, 'group': data.group});
                    break;
                default:
                    result = _.orderBy(state.tables[data.property][data.group], data.key, 'asc');
                    state.sortSetting[data.property][data.group]['order'] = 'asc';
                    commit('updateTableGroup', {'property': data.property, 'data': result, 'group': data.group});
                    break;
            }
        },
        sort({commit, state}, data) {
            if (state.sortSetting[data.property]['key'] !== data.key) {
                state.sortSetting[data.property]['key'] = data.key;
                state.sortSetting[data.property]['order'] = null;
            }
            let result = null;
            switch (state.sortSetting[data.property]['order']) {
                case 'asc':
                    result = _.orderBy(state.tables[data.property], data.key, 'desc');
                    state.sortSetting[data.property]['order'] = 'desc';
                    commit('updateTable', {'property': data.property, 'data': result});
                    break;
                default:
                    result = _.orderBy(state.tables[data.property], data.key, 'asc');
                    state.sortSetting[data.property]['order'] = 'asc';
                    commit('updateTable', {'property': data.property, 'data': result});
                    break;
            }
        },
        filter({commit, state}, property) {
            if (!state.filterSetting[property]) {
                commit('setFilterBackup', {'property': property, 'backup': state.tables[property]});
            }
            let result = this.state.filterSetting[property].filter(function (o) {
                return Object.keys(o).some(function (k) {
                    return String(o[k]).toLowerCase().indexOf(state.searchBar.toLowerCase()) !== -1;
                });
            });

            commit('dataCountSet', {'property': property, 'count': result.length});
            commit('updateTable', {'property': property, 'data': result});
        },
        filterGroup({commit, state}, data) {
            if (!state.filterSetting[data.property]) {
                commit('setFilterBackup', {'property': data.property, 'backup': state.tables[data.property]});
            }

            let result = {};

            for (let group in state.filterSetting[data.property]) {
                result[group] = this.state.filterSetting[data.property][group].filter(function (o) {
                    return Object.keys(o).some(function (k) {
                        return String(o[k]).toLowerCase().indexOf(state.searchBar.toLowerCase()) !== -1;
                    });
                });
            }

            commit('dataCountSet', {'property': data.property, 'count': result[data.select].length});
            commit('updateTable', {'property': data.property, 'data': result,});
        },


        // ---------------------Index--------------------
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
                            commit('resetForm', params.form);
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

            for (let property in state.originalData[params.form]) {
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
                        console.log(parseInt(Math.round((progressEvent.loaded / progressEvent.total) * 100)));
                    }
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
        deleteForm({commit, dispatch, state}, params) {

            commit("loading", true);

            // console.log(dispatch('data'));
            return new Promise((resolve, reject) => {
                axios.delete(params.url)
                    .then(response => {
                        commit("loading", false);
                        console.log(response);

                        resolve(response.data);
                    })
                    .catch(error => {
                        commit("loading", false);
                        console.log(error);
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
        // ---------------------Index--------------------
        updateTable(state, data) {
            return state.tables[data.property] = data.data;
        },
        updateTableGroup(state, data) {
            return state.tables[data.property][data.group] = data.data;
        },
        filter(state, property) {
            return this.dispatch('filter', property);
        },
        filterGroup(state, property) {
            return this.dispatch('filterGroup', property);
        },
        setFilterBackup(state, data) {
            let temp = {};
            temp[data.property] = data.backup;
            return state.filterSetting = {...state.filterSetting, ...temp};
        },
        loading(state, data) {
            return state.loading = data;
        },
        addTable(state, data) {
            let temp = {};
            temp[data] = {};
            return state.tables = {...state.tables, ...temp}
        },
        tableData(state, data) {
            return state.tableData = data;
        },
        addTableData(state, data) {
            let temp = {};
            state.dataCount[data.table_name] = 0;
            return state.tables[data.table_name] = data.table_data;
        },
        dataCountSet(state, data) {
            let temp = {};
            temp[data.property] = data.count;
            return state.dataCount = {...state.dataCount, ...temp}
        },
        navBarToggle(state) {
            axios.post('/api/user/post/miniNav', {'user_id': state.user.id, miniNav: !state.navBarMini});
            return state.navBarMini = !state.navBarMini;
        },
        forceNavMini(state, data) {
            return state.forceNavMini = data;
        },
        clearRowId(state) {
            return state.rowId = null;
        },
        setCurrentRowId(state, id) {
            return state.rowId = id;
        },
        setDatum(state, datum) {
            return state.datum = datum;
        },
        rowSelected(state) {
            return state.rowSelected = true;
        },
        rowDeSelected(state) {
            state.rowId = null;
            return state.rowSelected = false;
        },
        setModel(state, model) {
            return state.model = model;
        },
        showSearchBar(state) {
            return state.showSearchBar = true;
        },
        searchInput(state, input) {
            return state.searchBar = input;
        },
        showIndex(state, input) {
            return state.showIndex = input;
        },


        // ---------------------Index--------------------
        updateField,
        editMode(state) {
            return state.mode = 'edit';
        },
        setProperty(state, data) {
            return state.property = data;
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
        showModal(state, modal) {
            state.showModal = true;
            return state.modalName = modal;
        },
        hideModal(state) {
            this.commit('rowDeSelected');
            state.modalName = null;
            return state.showModal = false;
        },
        resetForm(state, form) {

            state.forms[form] = {};

            return state.forms[form] = {...state.originalData[form]}

        },
        swapMode(state, boolean) {
            state.swap_mode = boolean;
        },
    }
}
