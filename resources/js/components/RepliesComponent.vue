<template>
    <div>
        <div :key="reply.id" v-for="(reply, index)  in items">
            <Reply :data="reply" @deleted="remove(index)"></Reply>
        </div>

        <paginator :dataSet="dataSet" @changed="fetch"></paginator>

        <new-reply @created="add"></new-reply>
    </div>
</template>

<script>
    import Reply from './ReplyComponent';
    import NewReply from './NewReplyComponent';
    import Collection from "../mixins/Collection";
    
    export default {
        

        components: {Reply, NewReply},

        mixins: [Collection],


        data() {
            return {
                dataSet: false
            }
        },

        created() {
            this.fetch();
        },

        methods: {
            fetch(page) {
                axios.get(this.url(page))
                    .then(this.refresh);
            },

            url(page) {
                if (! page) {
                    let query = location.search.match("/page=(\d+)");
                    page = query ? query[1] : 1;
                }

                return location.pathname + '/replies?page=' + page;
            },

            refresh({data}) {
                this.dataSet = data;
                this.items = data.data;

                window.scrollTo(0, 0);
            },


            
        }
    }
</script>
