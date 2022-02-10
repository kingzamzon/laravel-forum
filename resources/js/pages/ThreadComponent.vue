<template>
    
</template>

<script>
import Replies from '../components/RepliesComponent'

export default {
    props: ['initialRepliesCount'],

    components: { Replies },

    data() {
        return {
            repliesCount: this.initialRepliesCount
        }
    }
}
</script>
