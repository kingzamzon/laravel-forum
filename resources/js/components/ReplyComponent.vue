<template>

</template>

<script>
    import Favourite from './FavouriteComponent.vue';
    
    export default {
        components: { Favourite },

        props: ['attributes'],

        data() {
            return {
                editing: false,
                body: this.attributes.body
            }
        },

        methods: {
            update() {
                axios.patch('/replies/' + this.attributes.id, {
                    body: this.body
                });

                this.editing = false;

                flash("Updated")
            }
        }
    }
</script>
