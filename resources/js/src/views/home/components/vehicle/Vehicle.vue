<template>
	<div>
		<h3>Véhicules</h3>
		<div class="table-wrapper">
			<table>
				<thead>
					<tr>
						<th>Matricule</th>
						<th>Année</th>
						<th>Type</th>
						<th>Marque</th>
						<th>Modèle</th>
						<th>Description</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<tr v-if="vehicles.length === 0">
						<td colspan="7" class="empty-row">
							Aucun véhicule trouvé.
						</td>
					</tr>
					<tr v-else v-for="vehicle in vehicles" :key="vehicle.id">
						<td>
							<span v-if="vehicle.registration_number">{{ (vehicle.registration_number) }}</span>
							<span v-else>-</span>
						</td>
						<td>
							<span v-if="vehicle.year">{{ vehicle.year }}</span>
							<span v-else>-</span>
						</td>
						<td>{{ vehicle.vehicle_type.type }}</td>
						<td>
							<span v-if="vehicle.vehicle_brand">{{ vehicle.vehicle_brand.name }}</span>
							<span v-else>-</span>
						</td>
						<td>
							<span v-if="vehicle.vehicle_modele">{{ vehicle.vehicle_modele.modele }}</span>
							<span v-else>-</span>
						</td>
						<td>
							<span v-if="vehicle.description">{{ vehicle.description }}</span>
							<span v-else>-</span>
						</td>
						<td>
							<i class="la la-edit" @click="editVehicle(vehicle)" title="Modifier"></i>
							<i class="la la-trash" @click="deleteVehicle(vehicle.id)" title="Supprimer"></i>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</template>

<script>
import store from '@/store'
import AddVehicle from "./AddVehicle.vue"
import { BRow, BCol } from 'bootstrap-vue'

export default {
  name: "vehicles",
  components: {
    AddVehicle,
	BRow, 
	BCol
  },
  data() {
    return {
      vehicles: [],
	  selectedVehicle: null,
    };
  },
  methods: {
	refreshVehicles() {
		this.selectedVehicle = null
		this.$store.dispatch('client-vehicle-module/getClientVehicles').then(() => {
			this.vehicles = store.getters['client-vehicle-module/clientVehicles']
		})
	},
	editVehicle(vehicle) {
		// this.selectedVehicle = vehicle
		// this.$bvModal.show('add-vehicle-modal')
		this.$emit('edit-vehicle', vehicle);
	},
	deleteVehicle(vehicleId) {
        this.$bvModal.msgBoxConfirm('Êtes-vous sûr de vouloir supprimer ce véhicule?', {
          title: 'Veuillez confirmer',
          centered: true,
          okVariant: 'danger',
          okTitle: 'Confirmer',
          cancelTitle: 'Annuler'
        })
		.then(value => {
            if (value) {
              store.dispatch('client-vehicle-module/deleteClientVehicle', vehicleId).then(() => {
				this.$toast.success('Le véhicule a été supprimée avec succès!')
                this.refreshVehicles()
              }).catch(() => {
				this.$toast.error('An unexpected error occured! Please retry!')
              })
            }
          })
		.catch(err => {
            console.log(err)
            // An error occurred
		})
    },
  },
  created() {
	this.refreshVehicles()
  }
};
</script>
<style>
.vehicle-item {
    background: #f7f7f7;
    padding: 25px;
	margin-bottom: 25px;
	position: relative;
}

.icon-position{
	position: absolute;
	right: 25px;
  	top: 25px;
	width: auto;
}

.icon-position i {
  cursor: pointer;
}
</style>