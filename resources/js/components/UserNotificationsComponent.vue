<template>
    <li class="nav-item dropdown" v-if="notifications.length">
        <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">
            Notifications
        </a>


        <ul class="dropdown-menu dropdown-menu-left">
            <li :key="notification.id" v-for="notification in notifications">
                <a class="dropdown-item"  :href="notification.data.link" @click="markAsRead(notification)">{{notification.data.message}}</a>
            </li>
        </ul>
    </li>
</template>

<script>
export default {
    data() {
        return { notifications: false}
    },

    created() {
        axios.get("/profile/" + window.Larav.user.name + "/notifications")
        .then(response => this.notifications = response.data);
    },

    methods: {
        markAsRead(notification) {
            axios.delete("/profile/" + window.Larav.user.name + "/notifications" + notification.id);
        }
    }
}
</script>