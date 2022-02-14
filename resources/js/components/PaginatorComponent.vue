<template>
    <ul class="pagination" v-if="shouldPaginate">
    <li class="page-item" v-show="preUrl">
      <a class="page-link" href="#" aria-label="Previous" rel="prev" @click.prevent="page--">
        <span aria-hidden="true">&laquo; Previous</span>
        <span class="sr-only">Previous</span>
      </a>
    </li>
    <!-- <li class="page-item"><a class="page-link" href="#">1</a></li>
    <li class="page-item"><a class="page-link" href="#">2</a></li>
    <li class="page-item"><a class="page-link" href="#">3</a></li> -->
    <li class="page-item" v-show="nextUrl">
      <a class="page-link" href="#" aria-label="Next" rel="next" @click.prevent="page++">
        <span aria-hidden="true">Next &raquo;</span>
        <span class="sr-only">Next</span>
      </a>
    </li>
  </ul>

</template>

<script>
export default {
    props: ['dataSet'],

    data() {
        return {
            page: 1,
            preUrl: false,
            nextUrl: false
        }
    },

    watch: {
        dataSet() {
            this.page = this.dataSet.current_page;
            this.preUrl = this.dataSet.prev_page_url;
            this.nextUrl = this.dataSet.next_page_url;
        },

        page() {
            this.broadcast();
            this.updateUrl();
        }
    },

    computed: {
        shouldPaginate() {
            return !! this.preUrl || !! this.nextUrl;
        }
    },

    methods: {
        broadcast() {
           return this.$emit('changed', this.page);
        },

        updateUrl() {
            history.pushState(null, null, '?page=' + this.page);
        }
    }
}
</script>