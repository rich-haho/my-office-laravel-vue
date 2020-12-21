<template>
  <el-form label-position="top">
    <el-row :gutter="20">
      <el-col :span="24">
        <el-form-item
          label="Domaine ou adresse email"
          required
          :error="errorMessage(saveDomainError, 'domain')"
        >
          <el-input v-model="form.domain" placeholder="Domaine"> </el-input>
        </el-form-item>
      </el-col>
    </el-row>
    <el-row>
      <el-col :span="12">
        <template #default="scope"></template>
        <el-button type="text" @click="onClose">
          Annuler
        </el-button>
      </el-col>
      <el-col :span="12">
        <el-button
          :loading="saveDomainLoading"
          type="success"
          @click="onSubmit"
        >
          Enregistrer
        </el-button>
      </el-col>
    </el-row>
  </el-form>
</template>

<script>
import { errorMessage } from '@/services/api'
import { mapActions, mapState } from 'vuex'

export default {
  name: 'SaveDomain',
  props: {
    client: {
      type: Object,
      default: () => {},
      required: true
    },
    domain: {
      type: Object,
      default: null
    },
    onClose: {
      type: Function,
      default: () => {}
    }
  },
  data() {
    return {
      form: {
        domain: this.domain ? this.domain.domain : null
      }
    }
  },
  computed: {
    ...mapState('clients', ['saveDomainError', 'saveDomainLoading'])
  },
  mounted: function() {},
  methods: {
    ...mapActions('clients', ['saveDomain', 'getClient']),
    errorMessage,
    async onSubmit() {
      // Add or update a domain to the given client
      await this.saveDomain({
        id: this.domain ? this.domain.id : null,
        client_id: this.client.id,
        domain: this.form.domain
      })
      if (!this.saveDomainError) {
        if (!this.domain) {
          this.form = {}
        }
        this.onClose()
        this.getClient({ id: this.client.id })
      }
    }
  }
}
</script>
