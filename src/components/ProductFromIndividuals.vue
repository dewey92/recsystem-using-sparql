<template>
  <div class="container">
    <h1>Hai {{ userName }}!</h1>
    <h2>Pilih beberapa laptop yang sesuai dengan minatmu.</h2>
    <h2><small>Klik untuk melihat detail spesifikasi laptop</small></h2>
    <div class="row">
      <div class="col-sm-8 col-sm-offset-2">
        <table class="table table-striped">
          <tbody>
            <tr v-for="(product, index) in productsFromFuncIndividuals" :key="index">
              <td><input type="checkbox" id="checkbox" v-model="checkedProducts" :value="product.name"></td>
              <td>{{ index+1 }}</td>
              <td>
                <a href="#" @click.prevent="onIndividualLaptopClicked(product, index)">{{ product.name }}</a>
                <div v-show="product.shown">
                  <table class="table">
                    <tr v-for="(val, key) in product.details">
                      <td align="right"><strong>{{ key }}</strong></td>
                      <td style="padding: 0 5px;">:</td>
                      <td align="left">{{ val }}</td>
                    </tr>
                  </table>
                </div>
              </td>
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
    ...mapState(['productsFromFuncIndividuals', 'userName', 'api'])
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
    onIndividualLaptopClicked (product, index) {
      if (!product.clicked) {
        this.$http.post(`${this.api}/product-details`, { name: product.name }).then(res => {
          const details = res.body.details.reduce((acc, val, i, arr) => {
            if (i % 2 === 0) {
              const currKey = arr[i].replace(/^has/, '')
              const currVal = arr[i + 1]

              acc[currKey] = acc[currKey] ? (acc[currKey] + ' ' + currVal) : currVal
            }

            return acc
          }, {})

          this.updateProducts(product, index, { details, clicked: true })
        })
      } else {
        this.updateProducts(product, index, {})
      }
    },
    updateProducts (product, index, newObj) {
      const updatedProduct = Object.assign({}, product, newObj, { shown: !product.shown })
      this.$set(this.productsFromFuncIndividuals, index, updatedProduct)
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
