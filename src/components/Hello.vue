<template>
  <div class="container">
    <!--<nav aria-label="...">
      <ul class="pager">
        <li class="next"><a href="#">Next <span aria-hidden="true">&rarr;</span></a></li>
      </ul>
    </nav>-->
    <h1>Hai <input type="text" id="your-name" placeholder="Nama Kamu..." v-model="yourName" :size="nameLength || 13">,</h1>
    <h1>Laptop Kamu akan Digunakan untuk Apa?</h1>
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
        <table class="table table-striped">
          <tbody>
            <tr v-for="(req, index) in funcReq" :key="index">
              <td><input type="checkbox" id="checkbox" v-model="checkedReqs" :value="req"></td>
              <td>{{ index+1 }}</td>
              <td>{{ req }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-sm-6 col-sm-offset-3">
        <h3>Selected Requirements:</h3>
        <ol>
          <li v-for="req in checkedReqs">{{ req }}</li>
        </ol>
      </div>
    </div>

    <button class="btn btn-lg btn-success pull-right" @click="onNextClicked" :disabled="!isDataValid">Next &rarr;</button>
  </div>
</template>

<script>
export default {
  name: 'hello',
  created () {
    this.fetchData()
  },
  data () {
    return {
      yourName: '',
      funcReq: [],
      checkedReqs: [],
      msg: 'Welcome to Your Vue.js App'
    }
  },
  computed: {
    nameLength () {
      return this.yourName.trim().length
    },
    isDataValid () {
      return this.nameLength && this.checkedReqs.length
    }
  },
  methods: {
    fetchData () {
      this.$http.get('http://localhost:6969/functional-requirement').then(res => {
        this.funcReq = res.body
      })
    },

    onNextClicked () {
      //
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}

#your-name {
  border: none;
  border-bottom: 2px dotted #aaa;
  outline: none;
  padding-right: 0;
}

a {
  color: #42b983;
}
</style>
