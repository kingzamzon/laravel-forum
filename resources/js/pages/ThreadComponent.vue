<template>
    
</template>

<script>
import Replies from '../components/RepliesComponent'
import SubscribeButton from '../components/SubscribeButtonComponent'

export default {
    props: ['initialRepliesCount'],

    components: { Replies, SubscribeButton },

    data() {
        return {
            repliesCount: this.initialRepliesCount
        }
    }
}
</script>
