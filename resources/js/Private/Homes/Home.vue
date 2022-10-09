<template>
  <div class="card">
    <div class="card-body">
      <div class="col-12 px-0 pb-2">
        <h1 class="title">{{ __("Tweets") }} </h1>
      </div>

      <div class="d-flex align-items-center justify-content-between mb-3 header-list">

          <btn
            as="inertia-link"
            href="/private/home/tweets"
            >{{ __("Obtener tweets") }}</btn
          >

      </div>

      <div class="card-table">

        <ListTable v-if="tweets.data" :data="tweets.data" :labels="data.labels" />

        <Pagination class="mt-6" v-if="tweets.links" :links="tweets.links" />

      </div>

    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { ListTable, Pagination } from "../../components/common";

export default {
  name: "Tweets",
  components: {
    ListTable,
    Pagination,
  },
  props: {
    tweets: Object,
  },
  setup( props ) {

    const data = ref({
                labels: ['tweet_id', 'text', 'publish_date'],
              });

    return {
      data,
    };
  },
};
</script>