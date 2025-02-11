<template>


    <div class="settingsMenu">

        <div @click="selector(0)" class="settingsMenu_item">
            <button> filter matches</button>
        </div>
        <div @click="selector(1)" class="settingsMenu_item">
            <button> personal information</button>
        </div>
        <div @click="selector(2)" class="settingsMenu_item">
            <button> filters and preferencies</button>
        </div>
    </div>


    <div class="settingsWindow">

        <div v-if="visible === 0">
            <Matches></Matches>
        </div>
        <div v-if="visible === 1">
            <div class="window-title"> personal information</div>
            <hr>

            <form @submit.prevent="savePersonal">
                <label for="pers_age"> your age</label>
                <input id="pers_age" :disabled="!editPersonal" v-model="personal_form.age">
                <hr>
                <br>
                your gender: {{ personal_form.gender }}
                <br>
                <div v-show="editPersonal == true">
                    <label for="female"> female </label>
                    <input type="radio" id="female" value="female" v-model="personal_form.gender">
                    <label for="male"> male </label>
                    <input type="radio" id="male" value="male" v-model="personal_form.gender">
                </div>
                <hr>
                <button v-show="editPersonal" class="btn btn-outline-warning"> save</button>
                <button type="button" class="btn btn-outline-primary" @click="editPersonal = !editPersonal"> edit
                </button>
            </form>

        </div>
        <div v-if="visible === 2">

            <div class="window-title"> your filters</div>
            <hr>
            <div class="min-h-10">
                <button class="float-end btn btn-outline-success " type="button" @click="setFilterMode('add')">
                    add filter
                </button>
            </div>
            <hr>
            <div v-if="this.listOfFilters.length == 0 "> no filters yet</div>
            <div v-else>
                <div v-for="filter in listOfFilters">

                    <button @click="this.getFilter(filter.id)"> {{ filter.name }}</button>
                    <button class="ml-1.5 btn btn-outline-danger" @click="deleteFilter(filter.id)"> delete</button>

                </div>

            </div>
            <hr>
            <hr>

            <form v-show="filterMode === 'show' || filterMode === 'add'  " @input="validateFilter(0)"
                  @change="validateFilter(0)" @submit.prevent="postHandler">
                <label for="fName"> name </label>
                <input id="fName" :disabled="disableEdit" v-model="filter.name">
                <div class="error" v-if="nameError"> please enter a valid name</div>
                <hr>

                <div>
                    <p> choose categories </p>

                    <label for="anyCategory"> any category</label>
                    <input id="anyCategory" :disabled="disableEdit" name="category" type="radio" value="any"
                           v-model="anyOrChooseCategory">

                    <label for="chooseCategory"> choose category </label>
                    <input id="chooseCategory" :disabled="disableEdit" name="category" type="radio"
                           value="chooseCategory" v-model="anyOrChooseCategory">
                    <div class="error" v-if="anyOrChooseCategoryRadioError"> please select one option</div>
                    <div v-if="anyOrChooseCategory=='chooseCategory'">

                        <div class="ml-36" v-if="!disableEdit">
                            <Dropdown_search :input_array="'categories'"
                                             @return-item="addCategoryToFilter"></Dropdown_search>

                            <div v-if="duplicateCategory" class=text-danger> this category is already added</div>
                            <div class="error" v-if="categoriesListError"> please select at least one category</div>
                        </div>
                        <div class="ml-36" v-if="filter.categoriesList == null || filter.categoriesList.length === 0">
                            no categories yet
                        </div>
                        <div v-else>
                            <div class="mt-1" v-for="(category,index) in this.filter.categoriesList">

                                <div class="ml-36"> {{ category.name }}
                                    <button type="button" class="btn btn-outline-danger btn-sm" :disabled="disableEdit"
                                            @click="removeCategoryFromFilter(category)">
                                        remove
                                    </button>
                                </div>
                                <div class="ml-40">
                                    <label for="wholeCategory"> whole category </label>
                                    <input :disabled="disableEdit" type="radio"
                                           value="wholeCategory"
                                           v-model="filter.categoriesList[index].wholeOrActivityRadio">

                                    <label for="chooseActivity"> choose activity </label>
                                    <input :disabled="disableEdit" type="radio"
                                           value="chooseActivity"
                                           v-model="filter.categoriesList[index].wholeOrActivityRadio">

                                    <div class="error" v-if="filter.categoriesList[index].wholeOrActivityError"> please
                                        select one option
                                    </div>
                                </div>

                                <div v-if="filter.categoriesList[index].wholeOrActivityRadio ==='chooseActivity'">

                                    <div class="ml-56" v-if="!disableEdit">
                                        <Dropdown_search :input_array="'activities'" :where="category.name"
                                                         @return-item="addActivityToFilter"></Dropdown_search>

                                        <span v-if="duplicateActivity"
                                              class=text-danger> this activity is already added </span>
                                        <div class="error" v-if="filter.categoriesList[index].activitiesListError">
                                            please select at least one activity
                                        </div>
                                    </div>
                                    <div class="ml-56" v-for="activity in this.filter.activitiesList">

                                        <div v-show="activity.category === category.name">
                                            {{ activity.name }}
                                            <button class="btn btn-outline-danger btn-sm" type="button"
                                                    :disabled="disableEdit"
                                                    @click="removeActivityFromFilter(activity.name)"> remove
                                            </button>
                                        </div>

                                    </div>

                                </div>
                            </div>


                        </div>

                    </div>
                </div>
                <hr>

                choose preferred age of your potential friends
                <br>
                <label for="anyAge"> any age </label>
                <input id="anyAge" :disabled="disableEdit" name="age" type="radio" value="any" v-model="chooseAge">

                <label for="chooseAge"> choose age </label>
                <input id="chooseAge" :disabled="disableEdit" name="age" type="radio" value="choose"
                       v-model="chooseAge">
                <div class="error" v-if="ageRadioError"> please select one option</div>

                <div v-if="chooseAge =='choose'">
                    <label for="ageFrom"> age from </label>
                    <input id="ageFrom" :disabled="disableEdit" v-model="filter.age_from">
                    <label for="ageTo"> age to </label>
                    <input id="ageTo" :disabled="disableEdit" v-model="filter.age_to">
                    <div class="error" v-if="ageRangeError"> please set the age range</div>
                    <div class="error" v-if="ageIncorrectError"> please set the age range correctly</div>
                </div>

                <br>
                <hr>

                are you looking for a group ?
                <br>

                <label for="group"> group </label>
                <input id="group" :disabled="disableEdit" name="group" type="radio" value="group"
                       v-model="filter.group">

                <label for="individual"> individual </label>
                <input id="individual" :disabled="disableEdit" name="group" type="radio" value="individual"
                       v-model="filter.group">

                <label for="anyNumber"> does not matter </label>
                <input id="anyNumber" :disabled="disableEdit" name="group" type="radio" value="any"
                       v-model="filter.group">
                <div class="error" v-if="groupRadioError"> please select one option</div>
                <br>
                <hr>
                you are looking for
                <br>

                <label for="male"> male </label>
                <input id="male" :disabled="disableEdit" name="gender" type="radio" value="male"
                       v-model="filter.gender">

                <label for="female"> female </label>
                <input id="female" :disabled="disableEdit" name="gender" type="radio" value="female"
                       v-model="filter.gender">

                <label for="anyGender"> does not matter </label>
                <input id="anyGender" :disabled="disableEdit" name="gender" type="radio" value="any"
                       v-model="filter.gender">
                <div class="error" v-if="genderRadioError"> please select one option</div>

                <br>
                <hr>
                choose where you would like to go
                <br>

                <label for="wholeSlovakia"> whole Slovakia</label>
                <input id="wholeSlovakia" :disabled="disableEdit" name="region" type="radio" value="any"
                       v-model="anyOrChooseRegion">
                <label for="chooseRegions"> choose regions</label>
                <input id="chooseRegions" :disabled="disableEdit" name="region" type="radio" value="chooseRegion"
                       v-model="anyOrChooseRegion">
                <div class="error" v-if="anyOrChooseRegionRadioError"> please select one option</div>
                <div v-if="anyOrChooseRegion === 'chooseRegion'">
                    <div v-if="!disableEdit">
                        <Dropdown_search class="ml-36" :input_array="'regions'"
                                         @returnItem="addRegionToFilter"></Dropdown_search>

                        <span v-if="duplicateRegion" class=text-danger> this region is already added </span>
                        <div class="error" v-if="regionsListError"> please select at least one region</div>
                    </div>

                    <div class="ml-36" v-if="filter.regionsList == null || filter.regionsList.length === 0"> no regions
                        yet
                    </div>
                    <div v-else>

                        <div class="mt-1" v-for="(region,index) in this.filter.regionsList">

                            <div class="ml-36">
                                {{ region.name }}
                                <button class="btn btn-outline-danger btn-sm" type="button"
                                        @click="removeRegionFromFilter(region)"> remove
                                </button>

                            </div>

                            <div class="ml-40">
                                <label for="wholeRegion"> whole region </label>
                                <input id="wholeRegion" :disabled="disableEdit" type="radio" value="wholeRegion"
                                       v-model="filter.regionsList[index].wholeOrDistrictRadio">

                                <label for="chooseDistricts"> choose districts </label>
                                <input id="chooseDistricts" :disabled="disableEdit"
                                       type="radio"
                                       value="chooseDistrict"
                                       v-model="filter.regionsList[index].wholeOrDistrictRadio">
                                <div class="error" v-if="filter.regionsList[index].wholeOrDistrictError"> please select
                                    one option
                                </div>
                            </div>
                            <div v-if="filter.regionsList[index].wholeOrDistrictRadio == 'chooseDistrict'">
                                <div class="ml-56" v-if="!disableEdit">

                                    <Dropdown_search :input_array="'districts'" :where="region.name"

                                                     @return-item="addDistrictToFilter"></Dropdown_search>

                                    <span v-if="duplicateDistrict"
                                          class=text-danger> this district is already added </span>
                                    <div class="error" v-if="filter.regionsList[index].districtsListError"> please
                                        select at
                                        least one district
                                    </div>
                                </div>
                                <div class="ml-56" v-for="district in this.filter.districtsList">

                                    <div v-if="district.region==region.name">{{ district.name }}
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                @click="removeDistrictFromFilter(district.name)"
                                        > remove
                                        </button>

                                    </div>

                                </div>
                            </div>

                        </div>


                    </div>

                </div>
                <br>
                <hr>
                <div>
                    choose date
                    <br>

                    <label for="anyDate"> any date </label>
                    <input id="anyDate" :disabled="disableEdit" type="radio" value="any" v-model="chooseDate">

                    <label for="chooseDate"> choose date </label>
                    <input id="chooseDate" :disabled="disableEdit" type="radio" value="chooseDate" v-model="chooseDate">
                    <div class="error" v-if="dateRadioError"> please select one option</div>

                    <div v-if="chooseDate=='chooseDate'">

                        <label for="date_from"> date from </label>
                        <input id="date_from" :disabled="disableEdit" type="date" v-model="filter.date_from">

                        <label for="date_to"> date to </label>
                        <input id="date_to" :disabled="disableEdit" type="date" v-model="filter.date_to">
                        <div class="error" v-if="dateIncorrectError"> please set the date correctly</div>

                    </div>

                </div>
                <br>
                <hr>

                <button v-show="filterMode==='add'" class="btn btn-outline-warning" type="submit"> save filter</button>
                <button v-show="!disableEdit && filterMode==='add'" class="btn btn-outline-danger" type="button"
                        @click="filterMode = 'list'">
                    cancel
                </button>
                <button v-show="filterMode==='show' && !disableEdit" class="btn btn-outline-warning" type="submit">
                    update
                    filter
                </button>
                <button v-show="disableEdit && filterMode === 'show'" class="btn btn-outline-primary" type="button"
                        @click="disableEdit = !disableEdit">
                    edit
                </button>
                <button v-show="!disableEdit && filterMode === 'show'" class="btn btn-outline-danger" type="button"
                        @click="disableEdit = !disableEdit, filterMode = 'list'">
                    cancel
                </button>

            </form>

        </div>

    </div>

</template>


<script>
import Dropdown_search from './Dropdown_search.vue';
import Matches from './Matches.vue';

export default {
    components: {
        Dropdown_search, Matches
    },
    props: ['type'],
    data() {
        return {
            listOfFilters: [],
            disableEdit: false,
            filter: {
                name: '',
                age_from: '',
                age_to: '',
                group: '',
                gender: '',
                date_from: '',
                date_to: '',
                regionsList: [],
                districtsList: [],
                categoriesList: [],
                activitiesList: [],
            },
            anyOrChooseRegion: '',
            anyOrChooseCategory: '',
            chooseAge: '',
            chooseDate: '',
            visible: 0,
            filterMode: 'list',
            duplicateRegion: false,
            duplicateDistrict: false,
            duplicateCategory: false,
            duplicateActivity: false,
            nameError: false,
            anyOrChooseCategoryRadioError: false,
            categoriesListError: false,
            ageRadioError: false,
            ageRangeError: false,
            ageIncorrectError: false,
            groupRadioError: false,
            genderRadioError: false,
            anyOrChooseRegionRadioError: false,
            regionsListError: false,
            districtRadioError: false,
            districtsListError: false,
            dateRadioError: false,
            dateIncorrectError: false,
            filterValidationCount: 0,
            editPersonal: false,
            personal_form: {
                age: '',
                gender: ''
            },
        }
    },
    mounted() {
        this.getPersonal();
        if (this.type === 'first') {
            this.selector(1)
            this.editPersonal = true
        }
    },
    methods: {

        addRegionToFilter(input) {

            if (this.filter.regionsList.some(element => element.name === input.item)) {
                this.duplicateRegion = true
            } else {
                this.filter.regionsList.push({
                    'name': input.item,
                    'wholeOrDistrictRadio': '',
                    'wholeOrDistrictError': '',
                    'districtsListError': ''
                })
                this.duplicateRegion = false
                this.validateFilter(0)
            }
        },
        addDistrictToFilter(input) {

            if (this.filter.districtsList.some(element => element.name === input.item && element.region === input.parent)) {
                this.duplicateDistrict = true
            } else {
                this.filter.districtsList.push({'name': input.item, 'region': input.parent})
                this.duplicateDistrict = false
                this.validateFilter(0)
            }
        },
        addCategoryToFilter(input) {

            if (this.filter.categoriesList.some(element => element.name === input.item)) {
                this.duplicateCategory = true
            } else {
                this.filter.categoriesList.push({
                    'name': input.item,
                    'wholeOrActivityRadio': '',
                    'wholeOrActivityError': '',
                    'activitiesListError': ''
                })
                this.duplicateCategory = false
                this.validateFilter(0)
            }
        },
        addActivityToFilter(input) {

            if (this.filter.activitiesList.some(element => element.name === input.item && element.category === input.parent)) {
                this.duplicateActivity = true
            } else {
                this.filter.activitiesList.push({'name': input.item, 'category': input.parent})
                this.duplicateActivity = false
                this.validateFilter(0)
            }

        },
        removeRegionFromFilter(region) {

            let i = 0;
            while (i < this.filter.regionsList.length) {
                if (this.filter.regionsList[i] == region) {
                    this.filter.regionsList.splice(i, 1)
                }
                i++
            }
            i = 0;
            while (i < this.filter.districtsList.length) {
                if (!this.filter.regionsList.some(e => e.name === this.filter.districtsList[i].region)) {
                    this.filter.districtsList.splice(i, 1)
                }
                i++
            }
            this.validateFilter(0)
        },
        removeDistrictFromFilter(district) {

            let i = 0;
            while (i < this.filter.districtsList.length) {
                if (this.filter.districtsList[i].name == district) {
                    this.filter.districtsList.splice(i, 1)
                }
                i++
            }
            this.validateFilter(0)
        },
        removeCategoryFromFilter(category) {

            let i = 0;
            while (i < this.filter.categoriesList.length) {
                if (this.filter.categoriesList[i] == category) {
                    this.filter.categoriesList.splice(i, 1)
                }
                i++
            }
            i = 0;
            while (i < this.filter.activitiesList.length) {
                if (!this.filter.categoriesList.some(e => e.name === this.filter.activitiesList[i].category)) {
                    this.filter.categoriesList.splice(i, 1)
                }
                i++
            }
            this.validateFilter(0)
        },
        removeActivityFromFilter(activity) {

            let i = 0;
            while (i < this.filter.activitiesList.length) {
                if (this.filter.activitiesList[i].name == activity) {
                    this.filter.activitiesList.splice(i, 1)
                }
                i++
            }
            this.validateFilter(0);
        },
        postHandler() {

            if (this.validateFilter(1) === 0) {
                if (this.filterMode === 'add') {
                    this.prepareFilterBeforePost()
                    this.postFilter('saveFilter')
                } else {
                    this.prepareFilterBeforePost()
                    this.postFilter('updateFilter')
                }
            }
        },
        validateFilter(i) {

            this.filterValidationCount += i
            this.duplicateCategory = false
            this.duplicateActivity = false
            this.duplicateRegion = false
            this.duplicateDistrict = false
            if (this.filterValidationCount !== 0) {
                let errors = 0
                this.nameError = false
                this.anyOrChooseCategoryRadioError = false
                this.categoriesListError = false
                if (this.filter.categoriesList !== null && this.filter.categoriesList.length !== 0) {
                    for (let i = 0; i < this.filter.categoriesList.length; i++) {
                        this.filter.categoriesList[i].wholeOrActivityError = false
                        this.filter.categoriesList[i].activitiesListError = false
                    }
                }
                this.ageRadioError = false
                this.ageRangeError = false
                this.ageIncorrectError = false
                this.groupRadioError = false
                this.genderRadioError = false
                this.anyOrChooseRegionRadioError = false
                this.regionsListError = false
                if (this.filter.regionsList != null && this.filter.regionsList.length !== 0) {
                    for (let i = 0; i < this.filter.regionsList.length; i++) {
                        this.filter.regionsList[i].wholeOrDistrictError = false
                        this.filter.regionsList[i].districtsListError = false
                    }
                }
                this.dateRadioError = false
                this.dateIncorrectError = false
                let regName = /^([A-Za-z0-9]{2,})$/
                let regWord = /^([A-Za-z]{2,})$/
                let regAge = /^[1-9]?[0-9]{1}$|^100$/

                if (!regName.test(this.filter.name)) {
                    this.nameError = true;
                    errors++
                }
                if (!regWord.test(this.anyOrChooseCategory)) {
                    this.anyOrChooseCategoryRadioError = true;
                    errors++
                }
                if (this.anyOrChooseCategory === 'chooseCategory' && this.filter.categoriesList.length === 0) {
                    this.categoriesListError = true;
                    errors++
                }

                let i = 0;
                while (i < this.filter.categoriesList.length) {
                    if (this.anyOrChooseCategory === 'chooseCategory' && !regWord.test(this.filter.categoriesList[i].wholeOrActivityRadio)) {
                        this.filter.categoriesList[i].wholeOrActivityError = true;
                        errors++
                    }
                    if (this.filter.categoriesList[i].wholeOrActivityRadio === 'chooseActivity' && !this.filter.activitiesList.some(a => a.category === this.filter.categoriesList[i].name)) {
                        this.filter.categoriesList[i].activitiesListError = true
                        errors++
                    }
                    i++;
                }

                if (!regWord.test(this.anyOrChooseRegion)) {
                    this.anyOrChooseRegionRadioError = true;
                    errors++
                }

                if (this.anyOrChooseRegion === 'chooseRegion' && this.filter.regionsList.length === 0) {
                    this.regionsListError = true;
                    errors++
                }

                if (this.filter.regionsList != null) {
                    i = 0;
                    while (i < this.filter.regionsList.length) {
                        if (this.anyOrChooseRegion === 'chooseRegion' && !regWord.test(this.filter.regionsList[i].wholeOrDistrictRadio)) {
                            this.filter.regionsList[i].wholeOrDistrictError = true;
                            errors++
                        }
                        if (this.filter.regionsList[i].wholeOrDistrictRadio === 'chooseDistrict' && !this.filter.districtsList.some(a => a.region === this.filter.regionsList[i].name)) {
                            this.filter.regionsList[i].districtsListError = true
                            errors++
                        }
                        i++;
                    }
                }
                if (!regWord.test(this.chooseAge)) {
                    this.ageRadioError = true;
                    errors++
                }
                if ((+this.filter.age_from > +this.filter.age_to || !regAge.test(this.filter.age_from) || !regAge.test(this.filter.age_to)) || (this.chooseAge === 'choose' && this.filter.age_from === '' || this.filter.age_to === '')) {
                    if (this.chooseAge === 'choose' && this.filter.age_from != null && this.filter.age_to != null) {
                        this.ageIncorrectError = true;
                        errors++;
                    }
                }
                if (!regWord.test(this.filter.group)) {
                    this.groupRadioError = true
                    errors++
                }
                if (!regWord.test(this.filter.gender)) {
                    this.genderRadioError = true
                    errors++
                }
                if (!regWord.test(this.anyOrChooseRegion)) {
                    this.anyOrChooseRegionRadioError = true;
                    errors++
                }
                if ((this.filter.districts === 'chooseDistrict') && (!regWord.test(this.filter.districts))) {
                    this.districtRadioError = true;
                    errors++
                }
                if (this.anyOrChooseRegion === 'chooseRegion' && this.filter.regionsList.length === 0) {
                    this.regionsListError = true;
                    errors++
                }
                if (this.filter.districts === 'chooseDistrict' && this.filter.districtsList.length === 0) {
                    this.districtsListError = true;
                    errors++
                }
                if (!regWord.test(this.chooseDate)) {
                    this.dateRadioError = true
                    errors++
                }
                if (this.chooseDate === 'chooseDate' && (this.filter.date_from === '' || this.filter.date_to === '')) {
                    this.dateIncorrectError = true;
                    errors++
                }
                if (this.filter.date_from > this.filter.date_to) {
                    this.dateIncorrectError = true;
                    errors++
                }
                return errors
            }
        },

        prepareFilterBeforePost() {

            if (this.anyOrChooseCategory === 'any' && this.filter.categoriesList.length !== 0) {
                this.filter.categoriesList.length = 0
                this.filter.activitiesList.length = 0
            }
            let i = 0
            while (i < this.filter.categoriesList.length) {
                if (this.filter.categoriesList[i].wholeOrActivityRadio === 'wholeCategory' && this.filter.activitiesList.some(a => a.category === this.filter.categoriesList[i].name)) {
                    this.filter.activitiesList = this.filter.activitiesList.filter(a => a.category !== this.filter.categoriesList[i].name)
                }
                i++
            }

            if (this.anyOrChooseRegion === 'any' && this.filter.regionsList != null) {
                this.filter.regionsList.length = 0
                this.filter.districtsList.length = 0
            }
            if (this.filter.regionsList != null) {
                i = 0;
                while (i < this.filter.regionsList.length) {
                    if (this.filter.regionsList[i].wholeOrDistrictRadio === 'wholeRegion' && this.filter.districtsList.some(d => d.region === this.filter.regionsList[i].name)) {
                        this.filter.districtsList = this.filter.districtsList.filter(d => d.region !== this.filter.regionsList[i].name)
                    }
                    i++
                }
            }
            this.filter.categoriesList = this.filter.categoriesList.map(a => a.name)
            if (this.filter.regionsList != null) {
                this.filter.regionsList = this.filter.regionsList.map(a => a.name)
            }

            if(this.chooseDate === 'any' && this.filter.date_from != null || this.filter.date_to != null){
                this.filter.date_to = null
                this.filter.date_from = null
            }

            if(this.chooseAge === 'any' && (this.filter.age_from != null || this.filter.age_to != null)){
                this.filter.age_to = null
                this.filter.age_from = null
            }

        },

        prepareFilterBeforeShow(data) {

            this.filter = data.filter;
            if (this.filter.categoriesList.length === 0) {
                this.anyOrChooseCategory = 'any'
            } else {
                this.anyOrChooseCategory = 'chooseCategory'
                this.filter.categoriesList = this.filter.categoriesList.map(
                    function (item) {
                        return {
                            'name': item, 'wholeOrActivityRadio': '',
                            'wholeOrActivityError ': '', 'activitiesListError': ''
                        }
                    })

                let i = 0
                while (i < this.filter.categoriesList.length) {
                    if (this.filter.activitiesList !== null) {
                        if (this.filter.activitiesList.some(c => c.category === this.filter.categoriesList[i].name)) {
                            this.filter.categoriesList[i].wholeOrActivityRadio = 'chooseActivity'
                        } else {
                            this.filter.categoriesList[i].wholeOrActivityRadio = 'wholeCategory'
                        }
                    } else {
                    }
                    i++
                }

            }
            /*------------------REGIONS--------Regions--------REGIONS------------------------------------------*/
            if (this.filter.regionsList.length === 0) {
                this.anyOrChooseRegion = 'any'
            } else {
                this.anyOrChooseRegion = 'chooseRegion'
                this.filter.regionsList = this.filter.regionsList.map(
                    function (item) {
                        return {
                            'name': item, 'wholeOrDistrictRadio': '',
                            'wholeOrDistrictError ': '', 'districtsListError': ''
                        }
                    })

                let i = 0
                while (i < this.filter.regionsList.length) {
                    if (this.filter.districtsList !== null) {
                        if (this.filter.districtsList.some(c => c.region === this.filter.regionsList[i].name)) {
                            this.filter.regionsList[i].wholeOrDistrictRadio = 'chooseDistrict'
                        } else {
                            this.filter.regionsList[i].wholeOrDistrictRadio = 'wholeRegion'
                        }
                    } else {
                    }
                    i++
                }
            }
            if (this.filter.age_from === null) {
                this.chooseAge = 'any'
            } else {
                this.chooseAge = 'choose'
            }
            if (this.filter.date_from === null) {
                this.chooseDate = 'any'
            } else {
                this.chooseDate = 'chooseDate'
            }
            this.disableEdit = true
            this.filterMode = 'show'
        },

        postFilter(type) {
            axios.post(`/home/settings/${type}`, this.filter)
                .then(() => {
                    this.getListOfFilters()
                    this.nullFilter()
                    this.filterMode = 'close'
                })
                .catch(error => console.log(error))
        },
        nullFilter() {
            this.chooseAge = null
            this.chooseDate = null
            this.anyOrChooseCategory = null
            this.anyOrChooseRegion = null
            if (this.filter.regionsList != null) {
                this.filter.regionsList.length = 0
            }
            if (this.filter.districtsList != null) {
                this.filter.districtsList.length = 0
            }
            this.filter.categoriesList.length = 0
            this.filter.activitiesList.length = 0
            this.filterValidationCount = 0

            for (let i = 0; i < Object.keys(this.filter).length; i++) {
                if (i < Object.keys(this.filter).length - 4) {
                    this.filter[Object.keys(this.filter)[i]] = null
                }
            }
        },
        getRegions() {
            axios.get('/AP/regions')
                .then(response => this.region = response.data)
        },
        selector(sel) {
            switch (sel) {
                case 0:
                    this.visible = sel;
                    break;
                case 1:
                    this.visible = sel;
                    break;
                case 2:
                    this.visible = sel;
                    this.getListOfFilters();
                    break;
                default:
            }
        },

        setFilterMode(mode) {
            if (mode === 'add') {
                this.nullFilter()
                this.filterMode = 'add'
                this.disableEdit = false;
            }
        },
        getPersonal() {
            axios.get('/home/settings/get/personal')
                .then(response => [this.personal_form.age = response.data.age,
                    this.personal_form.gender = response.data.gender])
                .catch(error => console.log(error))
        },
        getListOfFilters() {
            axios.get('/home/settings/get/filters')
                .then(response => this.listOfFilters = response.data)
                .catch(error => console.log(error))
        },
        getFilter(id) {
            axios.get(`/home/settings/get/filter/${id}`)
                .then(response => {
                    this.prepareFilterBeforeShow(response.data)
                })
                .catch(error => console.log(error))
        },
        deleteFilter(id) {
            axios.delete(`/home/settings/deleteFilter/${id}`)
                .then(response => {
                    const deletedId = this.listOfFilters.findIndex(p => p.id === id);
                    this.listOfFilters.splice(deletedId, 1);
                })
                .catch(error => console.log(error))
        },
        savePersonal() {                                                           // +validacia
            axios.post('/home/settings/savePersonal', this.personal_form)
                .then(this.editPersonal = false)
                .catch(error => console.log(error))
        }
    }
}
</script>

<style>

.settingsMenu {
    position: fixed;
}

.settingsMenu_item {
    border: solid;
    border-color: red;
    padding: 0.12em;
    text-align: center;
}

.settingsWindow {
    position: relative;
    top: 1.5rem;
    margin: 0 auto;
    min-width: 600px;
    max-width: 30rem;
    min-height: 100vh;
    border: solid;
}

.error {
    color: crimson;
}

.window-title {
    text-align: center;
    font-weight: bold;
}

label {
    margin-left: 0.375rem;
}
</style>
