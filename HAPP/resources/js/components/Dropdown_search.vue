<template>

        <div class="xdropdownSearch mt-3">
            <input @input="xsearch(inputValue)" v-model="inputValue">
            <button v-show="inputValue!= ''" class="btn btn-outline-success btn-sm" type="button"
                    @click="$emit('returnItem',{item: inputValue, parent: this.where})">
                    add
            </button>
            <div  class="xdropdown-content">
                <div class="dd-item" v-for="item in array">
                    <button  class="btn  w-100"  v-if="item.active == true" @click="this.inputValue = item.name" type="button">
                        {{ item.name}}
                    </button>
                </div>

            </div>

        </div>
        <br>

</template>

<script>

import {getDataFromDB} from './getData.js';

export default {
    emits: ['returnItem'],
    props: {
        input_array: String,
        where: String
    },

    data() {
        return {
            array: [],
            inputValue: '',
        }
    },
    mounted() {
        this.getData()
    },
    methods: {
        getData() {
            getDataFromDB(this.input_array, this.where)
                .then(data => {
                    this.array = data
                    this.setActive(this.array)
                });

        },

        setActive(object) {
            for (let i = 0; i < object.length; i++) {
                object[i].active = true;
            }
        },

        xsearch(key) {

            let object = this.array;
            if (key === "") {
                for (let i = 0; i < object.length; i++) {
                    object[i].active = true;
                }
            }
            let reg = new RegExp(key, "i")
            for (let i = 0; i < object.length; i++) {
                if (!reg.test(object[i].name)) {
                    object[i].active = false
                }
            }

        },
    }
}

</script>

<style>

.xdropdownSearch {
    display: inline-block;
    margin-right: 20px;
}

.xdropdown-content {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    min-width: 150px;
    z-index: 1;
}

.xdropdownSearch:hover .xdropdown-content {
    display: block;
}

.dd-item:hover {
    background-color: #e9ecef;
}
</style>
