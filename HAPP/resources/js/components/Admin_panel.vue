<template>


    <div id="main" class="text-primary relative min-h-screen ">
        <div id="menu">
            <div @click="selector(0)" class="menu_item">
                <button> users</button>
            </div>
            <div @click="selector(1)" class="menu_item">
                <button> regions</button>
            </div>
            <div @click="selector(2)" class="menu_item">
                <button> districts</button>
            </div>
            <div @click="selector(3)" class="menu_item">
                <button> categories</button>
            </div>
            <div @click="selector(4)" class="menu_item">
                <button> activities</button>
            </div>
        </div>

        <div class="window">
            <div class="window-title "> {{ window_title }}</div>
            <hr>
            <div>
                <label for="Search"> find </label>
                <input class="mb-3" id="Search" @input="search(window_title,this.find)" v-model="this.find">
                <div class="dropdownSearch mt-3 ml-4">
                    <div> sort by <i class="arrow down"></i> <span class="text-danger ml-5"> {{ sorting_by }} </span>
                    </div>
                    <div class="dropdown-content">
                        <button @click="sort('AZ')"> name A-Z</button>
                        <button @click="sort('ZA')"> name Z-A</button>
                        <button @click="sort('NO')"> date new-old</button>
                        <button @click="sort('ON')"> date old-new</button>
                    </div>
                </div>
            </div>
            <hr>

            <div class="window_item" v-if="visible === 0">
                <div v-for="user in users">
                    <div v-show="user.active === true">
                        <div class="item_name">
                            {{ user.id }} {{ user.name }}
                            <button class="btn btn-outline-danger btn-sm float-end" type="button"
                                    @click="deleteUser(user.id)"> delete
                            </button>

                            <div class="editDropdown">
                                <button class="btn btn-outline-primary btn-sm"> edit</button>
                                <div class="dropdown-content">
                                    <button @click="this.renameId=user.id, this.newName=user.name"> rename</button>
                                </div>
                            </div>

                            <form v-if="this.renameId === user.id" @submit.prevent="updateUserName">
                                <label for="user"> new name </label>
                                <input id="user" v-model="this.newName">
                                <button class="btn btn-outline-warning btn-sm" type="submit"> rename</button>
                            </form>
                        </div>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="window_item" v-if="visible === 1">
                <form @submit.prevent="addRegion">
                    <label for="addRegion"> new region name </label>
                    <input id="addRegion" v-model="this.newItem">
                    <button class="btn btn-outline-success" type="submit"> add region</button>
                </form>
                <hr>
                <div v-for="region in regions">
                    <div v-show="region.active === true">
                        <div class="item_name">
                            {{ region.id }} {{ region.name }}
                            <button class="btn btn-outline-danger btn-sm float-end" type="button"
                                    @click="deleteRegion(region.id)"> delete
                            </button>
                            <div class="editDropdown">
                                <button class="btn btn-outline-primary btn-sm"> edit</button>
                                <div class="dropdown-content">
                                    <button @click="this.renameId = region.id, this.newName = region.name"> rename
                                    </button>
                                    <button @click="this.addDistrictToId = region.id, this.newName = ''">
                                        add district
                                    </button>
                                </div>
                            </div>

                            <form v-if="this.renameId === region.id" @submit.prevent="updateRegionName">
                                <label for="regionN"> new name </label>
                                <input id="regionN" v-model="this.newName">
                                <button class="btn btn-outline-warning btn-sm" type="submit"> rename</button>
                            </form>

                            <form v-if="this.addDistrictToId === region.id" @submit.prevent="addDistrictToRegion">
                                <label for="regionD"> district name </label>
                                <input id="regionD" v-model="this.newName">
                                <button class="btn btn-outline-success btn-sm" type="submit"> add</button>
                            </form>

                        </div>
                        <hr>
                    </div>
                </div>
            </div>

            <div class="window_item" v-if="visible == 2">
                <form @submit.prevent="addDistrict">
                    select a region where the district will be added
                   <Dropdown_search class="ml-20" :input_array="'regions'"
                                     @return-item="setNameOfParent"></Dropdown_search>
                    <label for="addActivity"> district name </label>
                    <input id="addActivity" v-model="this.newItem">
                    <button class="btn btn-outline-success" type="submit"> add district</button>
                </form>
                <div v-for="district in districts">
                    <hr>
                    <div v-show="district.active === true">
                        <div class="item_name">
                            {{ district.id }}{{ district.name }}
                            <button class="btn btn-outline-danger btn-sm float-end" type="button"
                                    @click="deleteDistrict(district.id)"> delete
                            </button>

                            <div class="editDropdown">
                                <button class="btn btn-outline-primary btn-sm"> edit</button>
                                <div class="dropdown-content">
                                    <button @click="this.renameId=district.id, this.newName=district.name"> rename
                                    </button>
                                </div>
                            </div>

                            <form v-if="this.renameId === district.id" @submit.prevent="updateDistrictName">
                                <label for="districtN"> new name </label>
                                <input id="districtN" v-model="this.newName">
                                <button class="btn btn-outline-warning btn-sm" type="submit"> rename</button>
                            </form>

                        </div>
                    </div>
                </div>
            </div>

            <div class="window_item" v-if="visible === 3">
                <form @submit.prevent="addCategory">
                    <label for="addCategory"> name </label>
                    <input id="addCategory" v-model="this.newItem">
                    <button class="btn btn-outline-success" type="submit"> add category</button>
                </form>
                <hr>
                <div v-for="category in categories">
                    <div v-show="category.active === true">
                        <div class="item_name">
                            {{ category.id }}{{ category.name }}
                            <button class="btn btn-outline-danger btn-sm float-end" type="button"
                                    @click="deleteCategory(category.id)"> delete
                            </button>
                            <div class="editDropdown">
                                <button class="btn btn-outline-primary btn-sm"> edit</button>
                                <div class="dropdown-content">
                                    <button @click="this.renameId=category.id, this.newName=category.name"> rename
                                    </button>
                                </div>
                            </div>

                            <form v-if="this.renameId == category.id" @submit.prevent="updateCategoryName">
                                <label for="categoryN"> new name </label>
                                <input id="categoryN" v-model="this.newName">
                                <button class="btn btn-outline-warning btn-sm" type="submit"> rename</button>
                            </form>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>

            <div class="window_item" v-if="visible == 4">

                <form @submit.prevent="addActivity">

                    select a category where the activity will be added
                    <Dropdown_search class="ml-20" :input_array="'categories'"
                                     @return-item="setNameOfParent"></Dropdown_search>
                    <label for="addActivity"> district name </label>
                    <input id="addActivity" v-model="this.newItem">
                    <button class="btn btn-outline-success" type="submit"> add activity</button>

                </form>
                <hr>
                <div v-for="activity in activities">
                    <div v-show="activity.active == true">
                        <div class="item_name">
                            {{ activity.id }}{{ activity.name }}
                            <button class="btn btn-outline-danger btn-sm float-end" type="button"
                                    @click="deleteActivity(activity.id)"> delete
                            </button>
                            <div class="editDropdown">
                                <button class="btn btn-outline-primary btn-sm"> edit</button>
                                <div class="dropdown-content">
                                    <button @click="this.renameId = activity.id, this.newName = activity.name"> rename
                                    </button>
                                </div>
                            </div>

                            <form v-if="this.renameId === activity.id" @submit.prevent="updateActvityName">
                                <label for="activityN"> new name </label>
                                <input id="activityN" v-model="this.newName">
                                <button class="btn btn-outline-warning btn-sm" type="submit"> rename</button>
                            </form>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>
        </div>

        <div v-show="this.warn_window" id="warning"
             class=" h-28 w-32  border-solid border-amber-400 border-4 bg-amber-400 ">
            <div> are you sure to delete this item?</div>
            <button id="ok" class=" mt-4 mr-1.5 bg-red-400 p-1 " @click="this.delete_confirmed = 1"> yes</button>
            <button id="goback" class="bg-gray-400 mt-4 p-1"> go back</button>
        </div>
    </div>
</template>


<script>
import {getDataFromDB} from './getData.js';
import Dropdown_search from "@/components/Dropdown_search.vue";


export default {
    components: {
        Dropdown_search
    },
    data() {
        return {
            users: [],
            regions: [],
            districts: [],
            categories: [],
            activities: [],
            visible: '',
            sorting_by: 'date new-old',
            window_title: '',
            warn_window: '',
            delete_confirmed: '',
            nameOfParent: '',
            newItem: '',
            newName: '',
            renameId: '',
            addDistrictToId: '',
            find: '',
            ready: false
        }

    },
    mounted() {
        this.selector(0)
    },
    methods: {

        selector(sel) {
            switch (sel) {
                case 0:
                    this.getUsers();
                    this.visible = sel;
                    this.window_title = 'Users';
                    break;

                case 1:
                    this.getRegions();
                    this.visible = sel;
                    this.window_title = 'Regions';
                    break;

                case 2:
                    this.getDistricts();
                    this.getRegions();
                    this.visible = sel;
                    this.window_title = 'Districts';
                    break;

                case 3:
                    this.getCategories();
                    this.visible = sel;
                    this.window_title = 'Categories';
                    break;

                default:
                    this.getActivities();
                    this.getCategories();
                    this.visible = 4;
                    this.window_title = 'Activities';
                    break;
            }
        },

        setActive(object) {
            for (let i = 0; i < object.length; i++) {
                object[i].active = true;
            }
        },

        getUsers() {
            axios.get('AP/users')
                .then(response => {
                    this.users = response.data
                    this.setActive(this.users)
                    this.newItem = '';
                })
                .catch(error => console.log(error))

        },

        getRegions() {
            getDataFromDB('regions')
                .then(data => {
                    this.regions = data
                    this.setActive(this.regions)
                });
        },

        getDistricts() {
            getDataFromDB('districts')
                .then(data => {
                    this.districts = data
                    this.setActive(this.districts)
                });
        },

        getCategories() {
            getDataFromDB('categories')
                .then(data => {
                    this.categories = data
                    this.setActive(this.categories)
                });
        },

        getActivities() {
            getDataFromDB('activities')
                .then(data => {
                    this.activities = data
                    this.setActive(this.activities)
                });
        },

        addRegion() {
            axios.post("AP/addRegion", {
                name: this.newItem
            })
                .then(() => {
                    this.getRegions()
                    this.newItem = ''
                })
                .catch(error => console.log(error))
        },

        addDistrict() {
            alert(this.nameOfParent)
            axios.post("AP/addDistrict", {
                parentRegion: this.nameOfParent, name: this.newItem
            })
                .then(() => {
                    this.getDistricts()
                    this.newItem = ''
                })
                .catch(error => console.log(error))
        },

        addCategory() {
            axios.post("AP/addCategory", {
                name: this.newItem
            })
                .then(() => {
                    this.getCategories()
                    this.newItem = ''
                })
                .catch(error => console.log(error))
        },

        addActivity() {
            axios.post("AP/addActivity", {
                parentCategory: this.nameOfParent, name: this.newItem
            })
                .then(() => {
                    this.getActivities()
                })
                .catch(error => console.log(error))
        },

        updateUserName() {
            axios.post("AP/updateUserName",
                {name: this.newName, id: this.renameId})
                .then(() => this.getUsers(), this.renameId = 0, this.newName = '')
                .catch(error => console.log(error))
        },

        updateRegionName() {
            axios.post("AP/updateRegionName",
                {name: this.newName, id: this.renameId})
                .then(() => this.getRegions(), this.renameId = 0, this.newName = '')
                .catch(error => console.log(error))
        },

        updateDistrictName() {
            axios.post("AP/updateDistrictName",
                {name: this.newName, id: this.renameId})
                .then(() => this.getDistricts(), this.renameId = 0, this.newName = '')
                .catch(error => console.log(error))
        },

        updateCategoryName() {
            axios.post("AP/updateCategoryName",
                {name: this.newName, id: this.renameId})
                .then(() => this.getCategories(), this.renameId = 0, this.newName = '')
                .catch(error => console.log(error))
        },

        updateActvityName() {
            axios.post("AP/updateActivityName",
                {name: this.newName, id: this.renameId})
                .then(() => this.getActivities(), this.renameId = 0, this.newName = '')
                .catch(error => console.log(error))
        },

        setNameOfParent(parent) {
            this.nameOfParent = parent.item;
        },

        confirmDeleting() {

            const button1 = document.getElementById('ok')
            const button2 = document.getElementById('goback')

            return new Promise((resolve) => {
                const listener = () => {
                    button1.removeEventListener("click", listener);
                    button2.removeEventListener("click", listener);
                    resolve();
                }
                button1.addEventListener("click", listener);
                button2.addEventListener("click", listener);
            })
        },

        async deleteUser(id) {

            this.warn_window = true;
            this.delete_confirmed = 0;
            await this.confirmDeleting()
            this.warn_window = false;

            if (this.delete_confirmed === 1) {
                axios.delete(`AP/deleteUser/${id}`)
                    .then(() => {
                        let deletedIndex = this.users.findIndex(p => p.id === id);
                        this.users.splice(deletedIndex, 1);
                    })
                    .catch(error => console.log(error))
            }
        },

        async deleteRegion(id) {

            this.warn_window = true;
            this.delete_confirmed = 0;
            await this.confirmDeleting()
            this.warn_window = false;
            if (this.delete_confirmed === 1) {
                axios.delete(`AP/deleteRegions/${id}`)
                    .then(() => {
                        let deletedIndex = this.regions.findIndex(p => p.id === id);
                        this.regions.splice(deletedIndex, 1);
                    })
                    .catch(error => console.log(error))
            }
        },

        async deleteDistrict(id) {

            this.warn_window = true;
            this.delete_confirmed = 0;
            await this.confirmDeleting()
            this.warn_window = false;
            if (this.delete_confirmed === 1) {
                axios.delete(`AP/deleteDistrict/${id}`)
                    .then(() => {
                        let deletedIndex = this.districts.findIndex(p => p.id === id);
                        this.districts.splice(deletedIndex, 1);
                    })
                    .catch(error => console.log(error))
            }
        },

        async deleteCategory(id) {

            this.warn_window = true;
            this.delete_confirmed = '';
            await this.confirmDeleting()
            this.warn_window = false;

            if (this.delete_confirmed === 1) {
                axios.delete(`AP/deleteCategory/${id}`)
                    .then(() => {
                        let deletedIndex = this.categories.findIndex(p => p.id === id);
                        this.categories.splice(deletedIndex, 1);
                    })
                    .catch(error => console.log(error))
            }
        },

        async deleteActivity(id) {

            this.warn_window = true;
            this.delete_confirmed = 0;
            await this.confirmDeleting()
            this.warn_window = false;
            if (this.delete_confirmed === 1) {
                axios.delete(`AP/deleteActivity/${id}`)
                    .then(() => {
                        const deletedId = this.activities.findIndex(p => p.id === id);
                        this.activities.splice(deletedId, 1);
                    })
                    .catch(error => console.log(error))
            }
        },

        chooseArray() {

            switch (this.visible) {
                case 0:
                    return this.users;
                    break;
                case 1:
                    return this.regions;
                    break;
                case 2:
                    return this.districts;
                    break;
                case 3:
                    return this.categories;
                    break;
                default:
                    return this.activities;
            }
        },

        search(array, key) {

            let object;
            if (array === 'Users') {
                object = this.users
            }
            if (array === 'Regions') {
                object = this.regions;
            }
            if (array === 'Districts') {
                object = this.districts;
            }
            if (array === 'Categories') {
                object = this.categories;
            }
            if (array === 'Activities') {
                object = this.activities;
            }
            if (key === "") {
                for (let i = 0; i < object.length; i++) {
                    object[i].active = true;
                }
            }
            let reg = new RegExp(key, "i")
            for (let i = 0; i < object.length; i++) {
                if (!reg.test(object[i].name)) {
                    object[i].active = false;
                }
            }
        },

        addDistrictToRegion(id) {

            axios.post("AP/addDistrictToRegion",
                {name: this.newName, id: this.addDistrictToId})
                .then(() => this.addDistrictToId = 0
                )
                .catch(error => console.log(error))

        },

        sort(sort) {

            let object = this.chooseArray();
            switch (sort) {
                case 'AZ':
                    this.sorting_by = 'name A-Z'
                    object.sort(function (a, b) {
                        if (a.name < b.name) {
                            return -1;
                        }
                        if (a.name > b.name) {
                            return 1;
                        }
                        return 0;
                    })
                    break;

                case 'ZA':
                    this.sorting_by = 'name Z-A'
                    object.sort(function (a, b) {
                        if (a.name > b.name) {
                            return -1;
                        }
                        if (a.name < b.name) {
                            return 1;
                        }
                        return 0;
                    })
                    break;
                case 'NO':
                    this.sorting_by = 'date new-old'
                    object.sort(function (a, b) {
                        if (a.created_at < b.created_at) {
                            return -1;
                        }
                        if (a.created_at > b.created_at) {
                            return 1;
                        }
                        return 0;
                    })
                    break;

                default:
                    this.sorting_by = 'date old-new'
                    object.sort(function (a, b) {
                        if (a.created_at > b.created_at) {
                            return -1;
                        }
                        if (a.created_at < b.created_at) {
                            return 1;
                        }
                        return 0;
                    })
                    break;
            }
        }
    }
}
</script>

<style>

#menu {
    position: fixed;
}

.menu_item {
    border: solid;
    border-color: #718096;
    padding: 0.12em;
    text-align: center;
}

.window {
    min-width: 600px;
    min-height: 100vh;
    border: solid;
    position: relative;
    top: 1.5rem;
    margin: 0 auto;
    max-width: 30rem;
}

.window_item {
    color: #1a202c;
}

#warning {
    margin: 0;
    position: fixed;
    height: fit-content;
    top: 40%;
    left: 50%;
    -ms-transform: translate(-50%, -50%);
    transform: translate(-50%, -50%);
    text-align: center;
}

.item_name {
    min-height: 50px;
    height: fit-content;
}

.dropdownSearch {
    display: inline-block;
    margin-right: 20px;
}

.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    width: 100px;
    z-index: 1;
}

.dropdownSearch:hover .dropdown-content {
    display: inline-block;
}

.editDropdown:hover .dropdown-content {
    display: block;
}

.editDropdown {
    float: right;
    display: inline-block;
    margin-right: 20px;
}

.arrow {
    border: solid black;
    border-width: 0 3px 3px 0;
    display: inline-block;
    padding: 3px;
}

.down {
    transform: rotate(45deg);
    -webkit-transform: rotate(45deg);
}

input {
    margin-left: 0.375rem;
}
</style>
