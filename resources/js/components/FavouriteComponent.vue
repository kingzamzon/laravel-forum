<template>
    <button type="submit" :class="classes" @click="toggle">
        <span class="glyphicon glyphicon-heart"></span>
        <span v-text="favouritesCount"></span>
    </button>
</template>

<script>
    export default {
        props: ['reply'],

        data() {
            return {
                favouritesCount: this.reply.favouritesCount,
                isFavourited: true
            }
        }, 

        computed: { 
            classes() {
                return ['btn', this.isFavourited ? 'btn-primary' : 'btn-danger']
            }
        },

        methods: {
            toggle() {
                if (this.isFavourited) {
                    axios.delete('/replies/' + this.reply.id + '/favourites');
                    
                    this.isFavourited = false;
                    this.favouritesCount--;
                } else {
                    axios.post('/replies/' + this.reply.id + '/favourites');

                }
            }
        }
    }
</script>
