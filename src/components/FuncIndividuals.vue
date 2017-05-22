<template>
  <div class="container">
    <h1>Hai {{ userName }},</h1>
    <h2>pilih beberapa kriteria laptop yang sesuai dengan minatmu</h2>
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
        <table class="table table-striped">
          <tbody>
            <tr v-for="(individual, index) in funcIndividuals" :key="index">
              <td><input type="checkbox" id="checkbox" v-model="checkedInds" :value="individual"></td>
              <td>{{ index+1 }}</td>
              <td>{{ individual }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-sm-6 col-sm-offset-3">
        <h3>Selected Requirements:</h3>
        <ol>
          <li v-for="ind in checkedInds">{{ ind }}</li>
        </ol>
      </div>
    </div>

    <div class="row">
      <div class="col-sm-6">
        <button class="btn btn-lg btn-success pull-left" @click="onPrevClicked">&larr; Prev</button>
      </div>
      <div class="col-sm-6">
        <button class="btn btn-lg btn-success pull-right" @click="onNextClicked" :disabled="!isDataValid">Next &rarr;</button>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapMutations } from 'vuex'

export default {
  name: 'funcIndividuals',
  data () {
    return {
      checkedInds: []
    }
  },
  computed: {
    isDataValid () {
      return this.checkedInds.length
    },
    ...mapState(['funcIndividuals', 'userName'])
  },
  methods: {
    onPrevClicked () {
      this.$router.go(-1)
    },
    onNextClicked () {
      if (this.isDataValid) {
        console.log('SUKSES')
        /* this.$http.post(`${this.api}/functional-requirement`, { checkedReqs: this.checkedReqs }).then(res => {
          this.setUserName(this.yourName)
          this.setUserFunctionalReqs(this.checkedReqs)
        }) */
      }
    },
    ...mapMutations(['setUserName', 'setUserFunctionalReqs'])
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}

a {
  color: #42b983;
}
</style>
