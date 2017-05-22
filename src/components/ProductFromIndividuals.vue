<template>
  <div class="container">
    <h1>Hai {{ userName }},</h1>
    <h2>pilih beberapa laptop yang sesuai dengan minatmu</h2>
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
        <table class="table table-striped">
          <tbody>
            <tr v-for="(product, index) in productsFromFuncIndividuals" :key="index">
              <td><input type="checkbox" id="checkbox" v-model="checkedProducts" :value="product"></td>
              <td>{{ index+1 }}</td>
              <td>{{ product }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="col-sm-6 col-sm-offset-3">
        <h3>Selected Laptops:</h3>
        <ol>
          <li v-for="product in checkedProducts">{{ product }}</li>
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
  name: 'productFromIndividuals',
  data () {
    return {
      checkedProducts: []
    }
  },
  computed: {
    isDataValid () {
      return this.checkedProducts.length
    },
    ...mapState(['productsFromFuncIndividuals', 'userName'])
  },
  methods: {
    onPrevClicked () {
      this.$router.go(-1)
    },
    onNextClicked () {
      if (this.isDataValid) {
        // this.$http.post(`${this.api}/product-individuals`, { checkedInds: this.checkedInds }).then(res => {
        //   if (res.body.next) {
        //     this.setProductsFromFuncIndividuals(res.body.individuals)

        //     this.$router.push('prod-individuals')
        //   }
        // })
      }
    },
    ...mapMutations(['setProductsFromFuncIndividuals'])
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
