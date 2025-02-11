import { ref, onMounted, onUnmounted } from 'vue';


export function getDataFromDB(table,where) {

        const promise = axios.get('/AP/'+table, {params: { where: where}})
        const dataPromise = promise.then((response) => response.data)
        promise.catch((error) => { console.error(error);
    });
        return dataPromise
}






