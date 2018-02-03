<template>
  <main class="feMain box section container">
    <div class="logOut"><a class="button is-danger" href="/logout.php">Logout</a></div>
    <h1 class="title section align-center">Projectfilter</h1>
    <table :class="actions.entriesEditMode ? 'activeInput':''" class="table feTable">
      <thead>
        <tr>
          <th class="feColumn">Entry:</th>
          <th class="feColumn">Filter:</th>
          <th class="feColumn">Actions:</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(entry, entryIndex) in entries" :key="entryIndex">
          <td><input type="text" v-model="entry.name" @change="checkEntryName(entry.name, entryIndex)" :disabled="!actions.entriesEditMode"></td>
          <td>
            <uL>
              <li class="inputFilters" v-for="(filtername, entryFilterIndex) in filters" :key="entryFilterIndex">
                <label :class="actions.entriesEditMode ? '':'disabled'">
                  <input type="checkbox" :data-filtername="filtername" @click="updateEntryFilters(entry, filtername)" :checked="checkFilter(filtername, entry)" :disabled="!actions.entriesEditMode">
                  {{filtername}}
                </label>
              </li>
              <li v-show="!filters.length">
                <span class="warning">{{error.noFilter}}</span>
              </li>
              <li v-show="filters.length && !entry.filters.length">
                <span class="warning">{{error.noFilterSelected}}</span>
              </li>
            </ul>
          </td>
          <td>
            <ul>
              <li data-action="entry.name" @click="deleteEntry(entry)"><button class="button is-danger" :disabled="actions.entriesEditMode">Delete</button></li>
            </ul>
          </td>
        </tr>
        <tr v-show="filters.length">
          <td class="newEntry"><input type="text" @keyup.enter="addEntry" v-model="newEntry.name" placeholder="New entry"></td>
          <td>
            <ul>
              <li class="inputFilters" @keyup.enter="addEntry" v-for="(filtername, newEntryFilterIndex) in filters" :key="newEntryFilterIndex">
                <label>
                  <input type="checkbox" :checked="setNewEntryFilter(filtername)" @click="updateNewEntryFilters(filtername)" :data-filtername="filtername">
                  {{filtername}}
                </label>
              </li>
            </ul>
          </td>
          <td>
            <ul>
              <li><button @click="addEntry" class="button is-primary" :disabled="!newEntry.name.length || !newEntry.filters.length">Add</button></li>
            </ul>
          </td>
        </tr>
        <tr>
          <td><span v-show="countEntries">{{countEntries}}</span></td>
          <td></td>
          <td>
            <ul>
              <li v-show="!actions.entriesEditMode && entries.length" data-action="entry.name" @click="actions.entriesEditMode = true"><button class="button is-light">Edit</button></li>
              <li v-show="actions.entriesEditMode && saveStatusEntries" data-action="entry.name" @click="actions.entriesEditMode = false; save();"><button class="button is-success">Save</button></li>
            </ul>
          </td>
        </tr>
      </tbody>
    </table>

  <table class="table feTable section">
      <thead>
        <tr>
          <th class="feColum">Filter:</th>
          <th class="feColumn"></th>
          <th class="feColumn">Actions:</th>
        </tr>
      </thead>
      <tbody>
          <tr  v-for="(filtername, filterIndex) in filters" :key="filterIndex">
            <td>
              <span :data-filtername="filtername" v-text="filters[filterIndex]"></span>
            </td>
            <td></td>
            <td>
              <ul>
                <li data-action="entry.name" @click="deleteFilter(filtername)"><button class="button is-danger">Delete</button></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td class="newEntry"><input type="text" @keyup.enter="addFilter" v-model="newFilter" placeholder="New filter"></td>
            <td>
            </td>
            <td>
              <ul>
                <li><button @click="addFilter" class="button is-primary" :disabled="!newFilter">Add</button></li>
              </ul>
            </td>
          </tr>
          <tr>
            <td><span v-show="countFilters">{{countFilters}}</span></td>
          </tr>
        </tbody>
  </table>      
  </main>       
</template>

<script>
import Axios from "axios";
export default {
  data() {
    return {
      data: null,
      entries: [],
      filters: [],
      newEntry: {
        name: "",
        filters: []
      },
      newFilter: "",
      actions: {
        entriesEditMode: false,
        filtersEditMode: false
      },
      error: {
        notFilled: "Please type a name and select at least one filter to save.",
        noFilter: "No filters are available!",
        noFilterSelected: "No filter selected!"
      },
      saveStatusEntries: true,
    };
  },
  computed: {
    countEntries(){
      if(this.entries.length==1){
        return this.entries.length + " entry";
      }else{
        return this.entries.length + " entries";
      }
    },
    countFilters(){
      if(this.filters.length==1){
        return this.filters.length + " entry";
      }else{
        return this.filters.length + " entries";
      }
    }
  },
  methods: {
    checkFilter(filtername, entry) {
      return entry.filters.indexOf(filtername) != -1;
    },
    updateNewEntryFilters(filtername) {
      let indexOfFilter = this.newEntry.filters.indexOf(filtername);
      // console.log(filtername, indexOfFilter);
      if (indexOfFilter == -1) {
        this.newEntry.filters.push(filtername);
      } else {
        this.newEntry.filters.splice(indexOfFilter, 1);
      }
    },
    checkEntryName(filename, entryIndex){
      if(filename.length < 3){
        alert(this.error.notFilled);
        this.save=false;
      }
    },
    setNewEntryFilter(filtername) {
      // console.log(this.newEntry.filters.indexOf(filtername)==-1 ? "unchecked": "checked");
      return this.newEntry.filters.indexOf(filtername) == -1 ? "" : "checked";
    },
    save() {
      this.deleteNotExistingFilters();
      let newJSON = { entries: this.entries, filters: this.filters };
      let params = new URLSearchParams();
      params.append("action", "save");
      params.append("data", JSON.stringify(newJSON));

      Axios.post("./actions.php", params)
        .then(response => {
          console.log("Action has been handled ", response.data);
        })
        .catch(error => {
          console.log("An error has occurred. \n" + error);
        });
    },
    deleteEntry(entry) {
      // console.log(this.entries.length);
      this.entries.splice(this.entries.indexOf(entry), 1);
      this.save();
    },
    deleteFilter(filtername) {
      // console.log(this.entries.length);
      this.filters.splice(this.filters.indexOf(filtername), 1);
      this.save();
    },
    updateEntryFilters(entry, filtername) {
      let indexOfEntry = this.entries.indexOf(entry);
      // console.log(this.entries[indexOfEntry].filters.length == 1);
      if (!this.checkFilter(filtername, entry)) {
        this.entries[indexOfEntry].filters.push(filtername);
        }else if(this.entries[indexOfEntry].filters.length == 1){
          alert(this.error.notFilled);
          }else {
          let indexOfFilter = this.entries[indexOfEntry].filters.indexOf(filtername);
          this.entries[indexOfEntry].filters.splice(indexOfFilter, 1);
      }
    },
    addEntry() {
      if (
        !this.newEntry.name ||
        (!this.newEntry.filters.length && this.filters)
      ) {
        alert(this.error.notFilled);
      } else {
        if (!this.entries) {
          this.entries = [];
        }
        if (!this.filters) {
          this.filters = [];
        }

        this.entries.push(Object.assign({}, this.newEntry));
        this.save();
        this.clearNewEntry();
      }
    },
    addFilter(){
      if(this.newFilter.length){
        this.filters.push(this.newFilter);
        this.save();
        this.newFilter = "";
      }
    },
    clearNewEntry() {
      this.newEntry.name = "";
      this.newEntry.filters = [];
    },
    deleteNotExistingFilters(){
      this.entries.filter((entry) => {
        let thatEntry = entry;
        entry.filters.filter((filtername, entry) => {
          let indexOfFilter = this.filters.indexOf(filtername);
          if(indexOfFilter == -1){
            thatEntry.filters.splice(indexOfFilter, 1);
          }
        });
      });
    },
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
    }
  },
  mounted() {
    this.JSONFallback();
  }
};
</script>

<style lang="scss" scoped>
.align-right{
  text-align: right;
}
.align-left{
  text-align:left;
}
.align-center{
  text-align: center;
}
.warning{
  color: #ff3860;
}
.feMain {
  display: flex;
  flex-direction: column;
  margin-top: 25px;
  .button{
   min-width: 110px;
  }
  .logOut{
    position: absolute; 
    right: 10px; 
    top: 10px;
  }
  .feColum, .feColumn, .feColumn{
    width: 33%;
  }
  .feTable {
    &.activeInput tr td input, .newEntry input{
    border-bottom: 3px solid #00d1b2 !important;
    &:hover, &:focus {
      border-color: #209cee !important;
      outline: none;
      }
    }
    input[type="text"]:disabled, .disabled {
      color: #aaa;
    }
    width: 100%;
    align-self: center;
    tr {
      width: 25%;
      height: 100px;
      text-align: center;
      td, th {
        vertical-align: middle;
        text-align:center;
        input[type=text]{
          border: none;
          border-bottom: 3px solid #ddd;
          height: 30px;
          transition: border-color .4s ease-in-out;
          text-align: center;
          font-size: 16px;
          width: 210px;
        }
        ul li {
          margin: 10px;
          &.inputFilters{
            display: flex;
            justify-content: center;
            align-items: center;
            input {
              margin: 5px;
            }
          }
        }
      }
    }
  }
}
</style>
