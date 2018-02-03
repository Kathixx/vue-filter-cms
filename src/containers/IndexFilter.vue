<template>
<main class="ifMain box section container">
 <div class="logIn"><a class="button is-success" href="/login.php">Login</a></div>
    <h1 class="title section align-center">Projectfilter</h1>
    <section class="filterSize section">
      <img :style="'width:'+imageSize.width+'; height:'+imageSize.height" src="http://mpcoleman.co.uk/wp-content/uploads/2013/07/placeholder.jpg" alt="Placeholder" class="image filterSizeImg">
    </section>
    <section class="filterOptions section">
      <ul class="section filterOptionsList">
        <li v-for="(filtername, filterIndex) in filters" :key="filterIndex">
          <label>
            <input class="checkbox" type="checkbox" :data-filter="filtername" @click="updateActiveFilters(filterIndex)">
            {{filtername}}
          </label>
        </li>
      </ul>
    </section>
    <section class="filterResult align-center section">
      <a :disabled="selectedEntries > 0 ? false: true" class="button is-primary is-medium" href="">{{displayCounted}}</a>
    </section>
  </main>       
</template>

<script>
export default {
  data(){
    return {
      data: null,
      entries: [],
      filters: [],
      activeFilters: [],
      selectedEntries: 0,
    }
  },
  computed: {
    displayCounted(){
      if(this.selectedEntries==1){
        return this.selectedEntries + " Entry";
      }else{
        return this.selectedEntries + " Entries";
      }
    },
    imageSize(){
      return this.selectedEntries < 1 ? {width: "300px", height: "150px"}: {width: "400px", height: "250px"};
    }
  },
  methods: {
    JSONFallback(){
      this.data = window.data;
      // console.log(window.data);
      if(!this.data){
        this.save();
        window.location.reload();
      }else{
        this.entries = this.data.entries;
        this.filters = this.data.filters;
      }
    },
    updateActiveFilters(filterIndex){
      let el = this.filters[filterIndex];
      // console.log(filterIndex, el)
      if(this.activeFilters.indexOf(el)==-1){
        this.activeFilters.push(el);
      }else {
        let indexOfFilter = this.activeFilters.indexOf(el);
        this.activeFilters.splice(indexOfFilter, 1);
      }
      this.updateSelectedEntries();
    },
    updateSelectedEntries(){
      const quantity = this.entries.map((entry) =>  {
        return entry.filters.map((filter) =>  {
          return this.activeFilters.includes(filter) ? true:false;
        });
      }).reduce((currentValue, newValue) => (newValue.includes(true) ? 1 : 0) + currentValue, 0);

      this.selectedEntries = quantity ? quantity : 0;
    }
  },
  mounted() {
    this.JSONFallback();
  }

}
</script>

<style lang="scss" scoped>
  .ifMain{
    margin-top: 25px;
    .align-center{
      text-align: center;
    }
    .button{
      min-width: 110px;
      font-size: 16px;
    }
    .logIn{
      position: absolute; 
      right: 10px; 
      top: 10px;
    }
    .filterSize{
      max-width: 100%;
      display: flex;
      justify-content: center;
      height: 400px;
      align-items: center;
      .filterSizeImg{
        max-width: 800px;
        width: 300px;
        height: 150px;
        transition: all .4s ease-in-out;
      }
    }
    .filterOptions{
      margin-top: 50px;
      .filterOptionsList{
        border-top: 2px solid whitesmoke;
        border-bottom: 2px solid whitesmoke;
        display: flex;
        justify-content: space-around;
        li{

        }
      }
    }
  }
</style>
