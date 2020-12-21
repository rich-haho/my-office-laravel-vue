<template>
  <el-card v-loading="getClientLoading" class="card">
    <div slot="header">
      <span>
        {{ needUpdate ? 'Modifier le client' : 'Créer un client' }}
      </span>
    </div>
    <el-form @submit.native.prevent>
      <el-form-item
        label="Nom"
        required
        :error="errorMessage(saveError, 'name')"
      >
        <el-input v-model="item.name" placeholder="Nom"> </el-input>
      </el-form-item>

      <el-form-item
        v-if="needUpdate"
        label="Domaines et adresses email du client"
      >
        <el-col :span="24">
          <el-tag
            v-for="domain in item.domains"
            :key="domain.id"
            class="m-5"
            closable
            type="info"
            @close="showDeleteModal(domain)"
          >
            {{ domain.domain }}
            <el-link
              icon="el-icon-edit"
              @click="saveDomainDialog(domain, item)"
            />
          </el-tag>
        </el-col>
        <el-col :span="12" :offset="12">
          <el-button
            type="default"
            plain
            icon="el-icon-circle-plus"
            class="mb-20 add-domain-btn"
            @click="saveDomainDialog(null, item)"
          >
            Ajouter un domaine ou une adresse email autorisé
          </el-button>
        </el-col>
      </el-form-item>

      <el-row>
        <el-col :span="12">
          <el-button type="text" @click="cancel">
            Annuler
          </el-button>
        </el-col>
        <el-col :span="12">
          <el-button :loading="saveLoading" type="success" @click="submit">
            {{ needUpdate ? 'Modifier' : 'Enregistrer' }}
          </el-button>
        </el-col>
      </el-row>
      <removal
        :on-delete="deleteDomain"
        :show="confirmDomainDeletion"
        :on-close="
          () => {
            confirmDomainDeletion = false
          }
        "
        :delete-loading="deleteDomainLoading"
      ></removal>
      <el-dialog
        title="Ajouter un domaine ou une adresse email autorisé"
        :visible.sync="saveDomainVisible"
      >
        <save-domain
          :key="domainToUpdate ? domainToUpdate.id : 0"
          :domain="domainToUpdate ? domainToUpdate : null"
          :client="item"
          :on-close="
            () => {
              clearErrorState()
              saveDomainVisible = false
            }
          "
        />
      </el-dialog>
    </el-form>
  </el-card>
</template>

<script>
import { mapState, mapActions } from 'vuex'
import { errorMessage } from '@/services/api'
import router from '@/router'
import Removal from '@/components/Removal'
import SaveDomain from './SaveDomain'
export default {
  name: 'ClientSave',
  components: { Removal, SaveDomain },
  data() {
    return {
      confirmDomainDeletion: false,
      domainToRemove: null,
      saveDomainVisible: false,
      domainToUpdate: null,
      domainToUpdateClient: null
    }
  },
  computed: {
    ...mapState('clients', [
      'item',
      'saveLoading',
      'saveError',
      'getClientLoading',
      'deleteDomainLoading'
    ]),
    needUpdate() {
      return (
        this.$route.params.id !== undefined && this.$route.params.id !== null
      )
    }
  },
  mounted: function() {
    if (this.needUpdate) {
      this.getClient({ id: this.$route.params.id })
    } else {
      this.clearClient()
    }
  },
  methods: {
    ...mapActions('clients', [
      'save',
      'getClient',
      'clearClient',
      'destroyDomain',
      'clearErrorState'
    ]),
    errorMessage,
    async submit() {
      await this.save({
        ...this.item
      })
      if (this.saveError === null) {
        return router.push({
          name: 'client-list'
        })
      }
    },
    cancel() {
      return router.push({
        name: 'client-list'
      })
    },
    showDeleteModal(domain) {
      this.domainToRemove = domain
      this.confirmDomainDeletion = true
    },
    async deleteDomain() {
      await this.destroyDomain({
        id: this.domainToRemove.id,
        client_id: this.item.id
      })
      this.item.domains.splice(
        this.item.domains.indexOf(this.domainToRemove),
        1
      )
      this.confirmDomainDeletion = false
    },
    addDomain() {
      this.saveDomainVisible = true
    },
    saveDomainDialog(domain, client) {
      this.domainToUpdate = domain
      this.domainToUpdateClient = client
      this.clearErrorState()
      this.saveDomainVisible = true
    }
  }
}
</script>

<style lang="scss" scoped>
.add-domain-btn {
  white-space: normal;
}
</style>
