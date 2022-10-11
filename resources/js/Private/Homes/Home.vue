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

      <!-- Tabla de datos -->
      <div class="card-table">

        <ListTable v-if="tweets.data" :data="tweets.data" :labels="data.labels" />

        <Pagination class="mt-6" v-if="tweets.links" :links="tweets.links" />

      </div>

      <!-- Nube de tags -->
      <div class="col-12 px-0 pb-2">
        <h1 class="title">{{ __("Nube de tags") }} </h1>
      </div>
      <ul class="cloud my-5" role="navigation" aria-label="Webdev tag cloud">
        <li v-for="word in words" :key="word.value">
          <a href="#" :style="`font-size: min(${word.value}vw, 20vw);`">{{word.text}}</a>
        </li>
      </ul>

      <!-- Gráfica Chart.js -->
      <div class="col-12 px-0 pb-2">
        <h1 class="title">{{ __("Gráfica") }} </h1>
      </div>

        <Line :chart-data="chartData" />

    </div>
  </div>
</template>

<script>
import { ref } from 'vue';
import { ListTable, Pagination } from "../../components/common";
import { Line } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, CategoryScale, PointElement, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, LineElement, CategoryScale, PointElement, LinearScale);

export default {
  name: "Tweets",
  components: {
    ListTable,
    Pagination,
    Line,
  },
  props: {
    tweets: Object,
    allTweets: Object,
    dateTweets: Object,
  },
  setup( props ) {

    const data = ref({labels: ['tweet_id', 'text', 'publish_date']});
    const words = ref([]);

    //Genera un array de jsons para la nube de etiquetas.
    props.allTweets.forEach(item => {//Iteración por registro.
      item.hashtags.forEach(hashtag => {//Iteración dentro del array de hashtags de cada registro.
        
        if(words.value.length){ 
           const objIndex = words.value.findIndex(e => e.text == hashtag.toLowerCase());
           (objIndex > -1)? words.value[objIndex].value++ : words.value.push({text: hashtag.toLowerCase(), value: 1},);
        } else {
         words.value.push({text: hashtag.toLowerCase(), value: 1},);
        }
          
      });
    });

    //Genera los labels y el dataset para la gráfica.
    const chartData = {
        labels: [],
        datasets: [
          {
            label: 'Nº Tweets',
            backgroundColor: '#f87979',
            data: []
          }
        ]
      }

    props.dateTweets.forEach(item => {
      chartData.labels.push(item.date);
      chartData.datasets[0].data.push(item.total);
    });

    return {
      data,
      words,
      chartData
    };
  },
};
</script>
<style lang="scss" scoped>
  .cloud{
    list-style: none;
    padding-left: 0;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    line-height: 2.5rem;
    li{
      &:nth-child(2n+1){
        a{
          --color: #181;
        }
      }
      &:nth-child(3n+1){
        a{
          --color: #33a;
        }
      }
      &:nth-child(4n+1){
        a{
          --color: #c38;
        }
      }
      a{
        display: block;
        padding: 0.125rem 0.25rem;
        position: relative;
        text-decoration: none;
        color: var(--color);
        &::after{
          content: "";
          font-size: 1px;
        }
      }
    }
  }
</style>