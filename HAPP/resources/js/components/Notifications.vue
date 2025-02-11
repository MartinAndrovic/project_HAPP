<template>

    <div id="notifications "  @click="notificationDdActive = !notificationDdActive " >

        <img class=" notificationsDd w-6 h-6 "  v-if="notifications.length == 0"  src="/icons/bell.svg">
        <img class=" notificationsDd w-6 h-6 "  v-if="notifications.length != 0"  src="/icons/bellN.svg">
        <div class="notif-content dropdown-menu" :class="{ showDd: notificationDdActive }">

            <div class="text-center fw-bold "> notifications </div>
            <hr>
            <div class="whitespace-nowrap notif-item" v-for="notification in notifications">
                <a  v-if="notification.type === 'NotificationToNewUser'" href="/home/settings/first"> {{ notification.data.message }} </a>
                <a  v-if="notification.type === 'NewMatchNotification'" href="/home/settings/show"> {{ notification.data.message }} </a>
                <br>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    data() {
        return {
            notificationDdActive: false,
            notifications: []
        }
    },
    mounted() {
        this.getNotifications()
    },
    methods: {
        getNotifications() {
            axios.get('/home/notifications')
                .then(response => {
                    this.notifications = response.data;
                })
                .catch(error => console.log(error))
        },
    }
}


</script>

<style>

.notificationsDd {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    margin-right: 15%;
}

#notifications {
    position: relative;
    z-index: 1;
}

.notif-content {
    display: none;
    position: absolute;
    font-size: 0.8rem;
    min-height: 50px;
    transform: translate(-50%,50%);
    background-color: lightgrey;
    width: fit-content;
    z-index: 1;
}

.showDd {
    display: inline-block;
}

.notif-item:hover, .notif-item:focus {
    color: var(--bs-dropdown-link-hover-color);
    background-color: #e9ecef;
}

</style>
