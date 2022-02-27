<template>
    <div>
        <div v-if="signedIn">
            <div class="form-group mt-2">
                <textarea name="body" 
                            id="body" 
                            class="form-control" 
                            cols="70" 
                            rows="5" 
                            placeholder="Have something to say?" 
                            required
                            v-model="body"></textarea>
            </div>

            <button type="submit" 
                    class="btn btn-default"
                    @click="addReply">Post</button>
        </div>

        <p class="text-center" v-else>
            Please <a href="/login">sign in</a> to participate in this discussion
        </p>

    </div>
</template>

<script>

export default {

   data() {
       return {
           body: ''
       }
   },

   computed:  {
       signedIn() {
            return window.Larav.signedIn;
        },
   },

   methods: {
       addReply() {
           axios.post( location.pathname + '/replies', { body: this.body} )
                .catch(error => {
                    flash(error.response.data, 'danger');
                })
                .then(({data}) => {
                    this.body = '';

                    flash('Your reply has been posted.');

                    this.$emit('created', data);
                });
       }
   }
}
</script>
