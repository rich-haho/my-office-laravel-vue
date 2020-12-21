<template>
  <el-card v-loading="getBookingLoading" class="card">
    <div slot="header">
      <span>
        {{ 'Modifier la réservation' }}
      </span>
    </div>
    <el-form v-if="item.user" @submit.native.prevent>
      <el-row :gutter="10">
        <el-col :span="12">
          <el-form-item label="Utilisateur">
            <el-input v-model="item.user.name" :disabled="true" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Client">
            <el-input v-model="item.user.client.name" :disabled="true" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="10">
        <el-col :span="12">
          <el-form-item label="Partenaire">
            <el-input
              v-if="item.partner"
              v-model="item.partner.name"
              :disabled="true"
            />
            <el-input v-else :disabled="true" />
          </el-form-item>
        </el-col>

        <el-col :span="12">
          <el-form-item label="Date de réservation">
            <el-input :value="item.date | datetime" :disabled="true" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="10">
        <el-col :span="12">
          <el-form-item label="Produit">
            <el-input v-model="item.product.name" :disabled="true" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Quantité">
            <el-input v-model="item.quantity" :disabled="true" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="10">
        <el-col :span="6">
          <el-form-item label="Image">
            <el-image :src="item.product.image" class="image">
              <div slot="error" class="image-slot">
                <div class="image-slot-error">
                  <i class="el-icon-picture-outline"></i>
                </div>
              </div>
            </el-image>
          </el-form-item>
        </el-col>
        <el-col :span="18">
          <el-form-item label="Description">
            <el-input
              v-model="item.product.description"
              type="textarea"
              :rows="3"
              :disabled="true"
            />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="10">
        <el-col :span="12">
          <el-form-item label="Prix">
            <el-input v-model="item.product.price" :disabled="true" />
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Devise">
            <el-input v-model="item.product.currency" :disabled="true" />
          </el-form-item>
        </el-col>
      </el-row>
      <el-row :gutter="10">
        <el-col :span="12">
          <el-form-item
            label="Statut"
            required
            :error="errorMessage(saveError, 'status')"
          >
            <el-select
              v-model="item.status"
              placeholder="Statut"
              class="el-col-24"
            >
              <el-option
                v-for="st in statuses"
                :key="st"
                :label="st.toUpperCase()"
                :value="st"
              >
              </el-option>
            </el-select>
          </el-form-item>
        </el-col>
        <el-col :span="12">
          <el-form-item label="Capture du paiement">
            <el-input
              :v-model="item.manual_product"
              :placeholder="item.manual_product ? 'Manuel' : 'Automatique'"
              :disabled="true"
            />
          </el-form-item>
        </el-col>
      </el-row>

      <el-row>
        <el-col :span="12">
          <el-button type="text" @click="cancel">
            Annuler
          </el-button>
        </el-col>
        <el-col :span="12">
          <el-button :loading="saveLoading" type="success" @click="submit">
            {{ 'Modifier' }}
          </el-button>
        </el-col>
      </el-row>
    </el-form>
  </el-card>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { errorMessage } from '@/services/api'
import router from '@/router'

export default {
  name: 'BookingsSave',
  data() {
    return {}
  },
  computed: {
    ...mapState('bookings', [
      'item',
      'saveLoading',
      'saveError',
      'getBookingLoading',
      'statuses'
    ])
  },
  mounted: function() {
    this.getBooking({ url: '/admin/bookings/', id: this.$route.params.id })
    this.getBookingStatuses()
  },
  methods: {
    ...mapActions('bookings', ['save', 'getBooking', 'getBookingStatuses']),
    errorMessage,
    async submit() {
      await this.save({
        ...this.item
      })
      if (this.saveError === null) {
        return router.push({
          name: 'booking-list'
        })
      }
    },
    cancel() {
      return router.push({
        name: 'booking-list'
      })
    }
  }
}
</script>
