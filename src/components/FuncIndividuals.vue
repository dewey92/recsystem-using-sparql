<template>
  <div class="container">
    <h1>Hai {{ userName }}!</h1>
    <h2>Produk yang kami rekomendasikan lebih dari 10 produk,</h2>
    <h2>silahkan pilih kebutuhan fungsional yang Anda perlukan</h2>
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

    <div class="row" v-if="noLaptop">
      <div class="col-sm-6 col-sm-offset-3">
        <h3>Oops, maaf laptop yang kamu cari tidak ada. Coba ubah harga dan merek kamu</h3>

        <form class="form-horizontal">
          <div class="form-group form-group-lg has-feedback">
            <label for="inputHarga" class="col-sm-3 control-label">Harga &lt;</label>
            <div class="col-sm-9">
              <div class="input-group">
                <span class="input-group-addon">&dollar;</span>
                <input v-model="updatedPrice" class="form-control" id="inputHarga" placeholder="Preferensi Harga..">
              </div>
            </div>
          </div>
          <div class="form-group form-group-lg">
            <label for="inputMerek" class="col-sm-3 control-label">Merk</label>
            <div class="col-sm-9">
              <input v-model="updatedBrand" class="form-control" id="inputMerek" placeholder="Preferensi Merk..">
            </div>
          </div>
        </form>
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
      checkedInds: [],
      noLaptop: false
    }
  },
  computed: {
    isDataValid () {
      return this.checkedInds.length && this.updatedPrice > 0 && this.updatedBrand
    },
    updatedPrice: {
      get () { return this.userPreferences.price },
      set (price) { this.setUserPreferences(Object.assign({}, this.userPreferences, { price })) }
    },
    updatedBrand: {
      get () { return this.userPreferences.brand },
      set (brand) { this.setUserPreferences(Object.assign({}, this.userPreferences, { brand })) }
    },
    ...mapState(['funcIndividuals', 'userName', 'api', 'userPreferences'])
  },
  methods: {
    onPrevClicked () {
      this.$router.go(-1)
    },
    onNextClicked () {
      if (this.isDataValid) {
        const data = {
          checkedInds: this.checkedInds,
          price: this.userPreferences.price,
          brand: this.userPreferences.brand
        }

        this.$http.post(`${this.api}/product-individuals`, data).then(res => {
          if (res.body.next) {
            this.setProductsFromFuncIndividuals(res.body.products)

            this.$router.push('prod-individuals')
          } else {
            this.noLaptop = true
          }
        })
      }
    },
    ...mapMutations(['setProductsFromFuncIndividuals', 'setUserPreferences'])
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
