<template>
  <div class="container">
    <!--<nav aria-label="...">
      <ul class="pager">
        <li class="next"><a href="#">Next <span aria-hidden="true">&rarr;</span></a></li>
      </ul>
    </nav>-->
    <h1>Hai <input type="text" id="your-name" class="input--borderless" placeholder="Nama Kamu..." v-model="yourName" :size="nameLength || 13">,</h1>
    <h1>Silakan isi preferensi kamu sebelum memilih laptop</h1>
    <div class="row">
      <div class="col-sm-6 col-sm-offset-3">
        <form class="form-horizontal">
          <div class="form-group form-group-lg has-feedback">
            <label for="inputHarga" class="col-sm-3 control-label">Harga &lt;</label>
            <div class="col-sm-9">
              <div class="input-group">
                <span class="input-group-addon">&dollar;</span>
                <input v-model="userPreferences.price" class="form-control" id="inputHarga" placeholder="Preferensi Harga..">
              </div>
            </div>
          </div>
          <div class="form-group form-group-lg">
            <label for="inputMerek" class="col-sm-3 control-label">Merk</label>
            <div class="col-sm-9">
              <input v-model="userPreferences.brand" class="form-control" id="inputMerek" placeholder="Preferensi Merk..">
            </div>
          </div>
        </form>
      </div>
    </div>

    <button class="btn btn-lg btn-success pull-right" @click="onNextClicked" :disabled="!isDataValid">Next &rarr;</button>
  </div>
</template>

<script>
import { mapMutations } from 'vuex'

export default {
  name: 'hello',
  data () {
    return {
      yourName: '',
      userPreferences: {
        price: 450,
        brand: 'Asus'
      }
    }
  },
  computed: {
    nameLength () {
      return this.yourName.trim().length
    },
    isDataValid () {
      return this.nameLength && this.userPreferences.price > 0 && this.userPreferences.brand
    }
  },
  methods: {
    onNextClicked () {
      if (this.isDataValid) {
        this.setUserName(this.yourName)
        this.setUserPreferences(this.userPreferences)

        this.$router.push('func-level-one')
      }
    },
    ...mapMutations(['setUserName', 'setUserPreferences'])
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}

.input--borderless {
  border: none;
  border-bottom: 2px dotted #aaa;
  outline: none;
  padding-right: 0;
}

a {
  color: #42b983;
}
</style>
