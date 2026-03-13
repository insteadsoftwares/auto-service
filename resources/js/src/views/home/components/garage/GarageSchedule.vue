<template>
  <div> 
    <h3>Horaires du garage</h3>
	<div class="table-wrapper">
		<table>
			<thead>
				<tr>
					<th>Jour</th>
					<th>Ouvert / Fermé</th>
					<th>Horaires</th>
				</tr>
			</thead>
			<tbody>
				<tr v-for="workingDay in workingDays" :key="workingDay.id">
					<td>
						{{ getDayName(workingDay.day_of_week) }}
					</td>
					<td>
						<b-form-checkbox v-model="workingDay.is_open" switch>
							{{ workingDay.is_open ? 'Ouvert' : 'Fermé' }}
						</b-form-checkbox>
					</td>
					<td>
						<div v-for="(hour, index) in workingDay.garage_working_hours" :key="index" class="d-flex align-items-center mb-1">
							<b-form-input type="time" v-model="hour.start_time" :disabled="!workingDay.is_open" class="input-mr" @blur="validateHour(workingDay, index)"/>
							<b-form-input type="time" v-model="hour.end_time" :disabled="!workingDay.is_open" class="input-mr" @blur="validateHour(workingDay, index)"/>

							<b-button 
								v-if="workingDay.garage_working_hours.length > 1" 
								size="sm" 
								variant="danger" 
								@click="removeHour(workingDay, index)"
								class="btn-delete"
							>
								✕
							</b-button>
						</div>
						<b-button size="sm" variant="success" @click="addHour(workingDay)">+ Ajouter horaire</b-button>
					</td>
				</tr>
			</tbody>
		</table>
	</div>

    <b-button variant="primary" class="mt-3" @click="saveSchedule">
      Enregistrer les horaires
    </b-button>
  </div>
</template>

<script>
import { BTable, BFormInput, BFormCheckbox, BButton } from 'bootstrap-vue'
import store from '@/store'

export default {
  components: {
	BTable,
	BFormInput,
	BFormCheckbox,
	BButton,
  },
  props: {
    garage: {
      type: Object,
      required: true
    }
  },
  data() {
    return {
      fields: [
        { key: 'day_of_week', label: 'Jour' },
        { key: 'is_open', label: 'Ouvert / Fermé' },
        { key: 'garage_working_hours', label: 'Horaires' },
      ],
      workingDays: []
    }
  },
  watch: {
    garage: {
      immediate: true,
      handler(newVal) {
        if (newVal && Array.isArray(newVal.garage_working_days)) {
          this.workingDays = newVal.garage_working_days.map(day => ({
            ...day,
            is_open: day.is_open,
            garage_working_hours: day.garage_working_hours.map(hour => ({ ...hour }))
          }))
        }
      }
    }
  },
  methods: {
    getDayName(day) {
      const days = ['Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']
      return days[day] || 'Inconnu'
    },
	addHour(day) {
		if (!day.garage_working_hours) day.garage_working_hours = []

		let newStart = '08:00'
		let newEnd = '18:00'

		if (day.garage_working_hours.length > 0) {
			const lastHour = day.garage_working_hours[day.garage_working_hours.length - 1]
			
			const [lastH, lastM] = lastHour.end_time.split(':').map(Number)
			let nextH = lastH + 1
			if (nextH > 23) nextH = 23
			newStart = `${String(nextH).padStart(2, '0')}:${String(lastM).padStart(2, '0')}`
			newEnd = `${String(nextH + 1 <= 23 ? nextH + 1 : 23).padStart(2, '0')}:${String(lastM).padStart(2, '0')}`
		}

		day.garage_working_hours.push({ start_time: newStart, end_time: newEnd })
	},
    saveSchedule() {
		const error = this.validateAllWorkingDays()
		if (error) {
			this.$toast.error(error)
			return
		}

	  	store.dispatch('garage-working-days-module/editGarageWorkingDays', { 'garage_working_hours': this.workingDays }).then(() => {
			this.$toast.success('Horaires modifiés avec succès')
		})
		.catch(() => {
			this.$toast.error('Une erreur est survenue')
		})
    },
	validateAllWorkingDays() {
		for (const day of this.workingDays) {
			if (!day.garage_working_hours || day.garage_working_hours.length === 0) continue
			for (let i = 0; i < day.garage_working_hours.length; i++) {
				const current = day.garage_working_hours[i]
				if (current.end_time <= current.start_time) {
					return `La fin de la plage ${i + 1} doit être après le début pour le jour ${this.getDayName(day.day_of_week)}.`
				}

				const next = day.garage_working_hours[i + 1]
				if (next && next.start_time < current.end_time) {
					return `La plage ${i + 2} commence avant la fin de la plage ${i + 1} pour le jour ${this.getDayName(day.day_of_week)}.`
				}
			}
		}

		return null
	},
	removeHour(day, index) {
		day.garage_working_hours.splice(index, 1)
	},
	validateHour(day, index) {
		const hour = day.garage_working_hours[index]

		if (!hour.start_time || !hour.end_time) return

		if (hour.end_time <= hour.start_time) {
			this.$toast.error("L'heure de fin doit être supérieure à l'heure de début");
			return;
		}

		const nextHour = day.garage_working_hours[index + 1];
		if (nextHour && hour.end_time >= nextHour.start_time) {
			this.$toast.error("L'heure de fin doit être inférieure au début de la plage suivante");
			return;
		}

		const prevHour = day.garage_working_hours[index - 1];
		if (prevHour && hour.start_time <= prevHour.end_time) {
			this.$toast.error("L'heure de début doit être supérieure à la fin de la plage précédente");
			return;
		}
	}
  }
}
</script>

<style scoped>
.btn-delete{
	position: relative;
	top: -11px;
	right: -8px;
}

.input-mr{
	margin-right: 10px;
}
</style>