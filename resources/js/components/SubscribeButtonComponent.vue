<template>
    <button :class="classes" @click="subscribe">Subscribe</button>
</template>

<script>

export default {

    props: ['active'],

    data() {
        return {
            status: this.active
        }
    },

    computed: {
        classes() {
            return ['btn', this.status ? 'btn-danger' : 'btn-primary'];
        }
    },

    methods: {
        subscribe() {

            let requestType = this.status ? 'delete' : 'post';

            axios[requestType](location.pathname + '/subscriptions');

            this.status = ! this.status;

        }
    }
}
</script>
