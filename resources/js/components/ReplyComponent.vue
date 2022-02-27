<template>
<div :id="'reply-'+id" class="mt-3 mb-3">
    <div class="card">
        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/'+data.owner.name" 
                       v-text="data.owner.name">
                    </a>
                    said
                    <span v-text="ago"></span>
                    
                </h5>

                <div v-if="signedIn">
                    <favourite :reply="data"></favourite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>

                <button class="btn btn-xs btn-primary" @click="update">Update</button>
                <button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
            </div>
            <div v-else v-text="body">
            </div>
        </div>

        <!-- @can('update', $reply) -->
        <div class="card-footer level" v-if="canUpdate">
            <button class="btn btn-warning btn-xs mr-1" @click="editing = true">Edit</button>

            <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>
        </div>
        <!-- @endcan -->
    </div>
</div>

</template>

<script>

    import Favourite from './FavouriteComponent.vue';
    import moment from 'moment'; 
    
    export default {
        components: { Favourite },

        props: ['data'],

        data() {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body
            }
        },

        computed: {
            ago() {
                return moment(this.data.created_at).fromNow() + '...'; 
            },
            signedIn() {
                return window.Larav.signedIn;
            },
            canUpdate() {
                return this.authorize(user => this.data.user_id == user.id);
                // return this.data.user_id == window.Larav.user.id
            }
        },

        methods: {
            update() {
                axios.patch(
                    '/replies/' + this.data.id, {
                    body: this.body
                })
                .then(() => {
                    this.editing = false;
                    flash("Updated");
                })
                .catch(error => {
                    flash(error.response.data, 'danger');
                });
            },

            destroy() {
                axios.delete('/replies/' + this.data.id);

                this.$emit('removed')

                this.$emit('deleted', this.data.id);
                    
                // $(this.$el).fadeOut(300, () => {
                //     flash('Your reply has been deleted');
                // })
            }
        }
    }
</script>
