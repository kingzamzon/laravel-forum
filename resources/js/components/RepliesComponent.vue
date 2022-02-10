<template>
    <div>
        <div :key="reply.id" v-for="(reply, index)  in items">
            <Reply :data="reply" @deleted="remove(index)"></Reply>
        </div>
    </div>
</template>

<script>
    import Reply from './ReplyComponent';
    
    export default {
        props: ['data'],

        components: {Reply},


        data() {
            return {
                items: this.data
            }
        },

        methods: {
            remove(index) {
                this.items.splice(index, 1);

                flash('Reply was deleted')
            }
        }
    }
</script>
