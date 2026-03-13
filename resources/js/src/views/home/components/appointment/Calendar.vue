<template>
  <FullCalendar id="calendar" :key="calendarKey" :options="calendarOptions" />
</template>

<script>
import FullCalendar from '@fullcalendar/vue'
import dayGridPlugin from '@fullcalendar/daygrid'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import store from '@/store'

export default {
  components: { FullCalendar },
  props: {
    garage: {
		type: Object,
		default: null,
	},
  },
  data() {
    return {
      calendarKey: 0,
      upcomingAppointments: [],
	  localLeaves: [],
      calendarOptions: {
        plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',

        headerToolbar: {
          left: 'prev,next today',
          center: 'title',
          right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },

		height: 'auto',

        selectable: true,
		selectMirror: true,
		selectMinDistance: 0,
		selectLongPressDelay: 0,
		selectOverlap: true,

        // slotMinTime: '08:00:00',
        // slotMaxTime: '18:00:00',

        // businessHours: [
        //   { daysOfWeek: [1,2,3,4,5,6], startTime: '08:00', endTime: '12:00' },
        //   { daysOfWeek: [1,2,3,4,5,6], startTime: '14:00', endTime: '18:00' }
        // ],

        timeZone: 'local',

        // events: [],

        eventDidMount: (info) => {
          info.el.style.backgroundColor = info.event.backgroundColor
          info.el.style.borderColor = info.event.backgroundColor
          info.el.style.whiteSpace = 'normal'
        },
		eventContent: (arg) => {
			if (arg.event.title === 'Congé') {
				return { html: `<div style="color: white; font-weight: bold;">${arg.event.title}</div>` }
			}
			return {
				html: `
				<div style="white-space:normal; padding:4px; font-size:12px;">
					<div style="font-weight:600;">${arg.timeText}</div>
					<div>${arg.event.title}</div>
				</div>
				`
			}
		}
      }
    }
  },
  methods: {
	formatLocalDate(date) {
		const year = date.getFullYear()
		const month = String(date.getMonth() + 1).padStart(2, '0')
		const day = String(date.getDate()).padStart(2, '0')
		return `${year}-${month}-${day}`
	},
	addOneDay(dateString) {
		const date = new Date(dateString)
		date.setDate(date.getDate() + 1)

		const year = date.getFullYear()
		const month = String(date.getMonth() + 1).padStart(2, '0')
		const day = String(date.getDate()).padStart(2, '0')

		return `${year}-${month}-${day}`
	},
	async handleDateSelect(info) {
		const start = info.start
		const end = info.end

		const overlappingLeave = this.localLeaves.find(leave => {
			const leaveStart = this.formatLocalDate(new Date(leave.start_date))
			const leaveEnd = this.formatLocalDate(new Date(leave.end_date))

			const selectedStart = this.formatLocalDate(info.start)

			const endDate = new Date(info.end)
			endDate.setDate(endDate.getDate() - 1)
			const selectedEnd = this.formatLocalDate(endDate)

			return !(leaveEnd < selectedStart || leaveStart > selectedEnd)
		})

		if (overlappingLeave) {
			this.$bvModal.msgBoxConfirm(`Un congé existe du ${start.toLocaleDateString()} au ${new Date(end - 1).toLocaleDateString()}. Voulez-vous le supprimer ?`,
				{
					title: 'Suppression congé',
					size: 'md',
					buttonSize: 'md',
					okVariant: 'danger',
					okTitle: 'Oui',
					cancelTitle: 'Non',
					footerClass: 'p-2',
					hideHeaderClose: false,
					centered: true,
				}
			)
			.then(value => {
				if (value === true) {
					store.dispatch('garage-leave-module/deleteGarageLeave', { 'start_date': this.formatLocalDate(start), 'end_date': this.formatLocalDate(new Date(end - 1)) }).then(() => {
						this.$toast.success('Le congé a été supprimé avec succès')
						store.dispatch('garage-leave-module/getGarageLeaves').then(() => {
							this.localLeaves = store.getters['garage-leave-module/garageLeaves']
							this.updateCalendarEvents()
						})
					})
					.catch(() => {
						this.$toast.error('Une erreur est survenue')
					})
				}
			})
			.catch(err => {
				console.log(err)
			})
		}
		else{
			this.$bvModal.msgBoxConfirm(
				`Souhaitez-vous bloquer la période du ${start.toLocaleDateString()} au ${new Date(end - 1).toLocaleDateString()} pour congé ? 
				Aucun rendez-vous ne pourra être pris durant cette période.`,
				{
					title: 'Confirmation congé',
					size: 'md',
					buttonSize: 'md',
					okVariant: 'danger',
					okTitle: 'Oui',
					cancelTitle: 'Non',
					footerClass: 'p-2',
					hideHeaderClose: false,
					centered: true,
				}
			)
			.then(value => {
				if (value === true) {
					store.dispatch('garage-leave-module/addGarageLeaves', { 'start_date': this.formatLocalDate(start), 'end_date': this.formatLocalDate(new Date(end - 1)) }).then(() => {
						this.$toast.success('Le Congé on été ajouter avec succès')
						const leaves = this.$store.getters['garage-leave-module/addLeaves']
						if (this.garage && Array.isArray(this.localLeaves)) {
							this.localLeaves.push(leaves)
						}

						this.getUpcomingAppointments()
					}).catch((error) => {
						this.$toast.error('An unexpected error occured! Please retry')
					})
				}
			})
			.catch(err => {
				console.log(err)
			})
		}
	},
	convertAppointmentsToEvents() {
      	return this.upcomingAppointments.map(rdv => {
			const serviceName = rdv.service ? rdv.service.name : ''
			const baseDate = new Date(rdv.appointment_date)
			const [hour, minute] = rdv.appointment_time.split(':')

			const startDate = new Date(
				baseDate.getFullYear(),
				baseDate.getMonth(),
				baseDate.getDate(),
				parseInt(hour),
				parseInt(minute),
				59
			)

			const endDate = new Date(startDate)
			endDate.setHours(endDate.getHours() + 1)

			return {
				id: rdv.id,
				title: serviceName,
				start: startDate,
				end: endDate,
				backgroundColor: this.getBgColor(rdv.status),
				textColor: this.getTextColor(rdv.status)
			}
      	})
    },
    getBgColor(status) {
      const colors = {
        pending: '#fff3cd',
        confirmed: '#d4edda',
        cancelled: 'red',
        completed: '#cff6fc'
      }
      return colors[status]
    },
    getTextColor(status) {
      const colors = {
        pending: '#856404',
        confirmed: '#155724',
        cancelled: '#fbe5e5',
        completed: '#0dcaf0'
      }
      return colors[status]
    },
	convertLeavesToEvents() {
		if (!this.garage || !Array.isArray(this.localLeaves)) return []
		return this.localLeaves.map(leave => ({
			id: 'leave-' + leave.id,
			// title: 'Congé',
			start: leave.start_date,
			end: this.addOneDay(leave.end_date),
			display: 'background',
			// backgroundColor: '#ff0000',
			// textColor: '#ffffff',
			// overlap: false,
			classNames: ['leave-event'],
			allDay: true,
		}))
	},
	updateCalendarEvents() {
		const rdvEvents = this.convertAppointmentsToEvents()
		const leaveEvents = this.convertLeavesToEvents()
		const slotTimes = this.getCalendarSlotTimes()
		this.calendarOptions.events = [...rdvEvents, ...leaveEvents]
		this.calendarOptions.businessHours = this.generateBusinessHours()
		this.calendarOptions.slotMinTime = slotTimes.min
		this.calendarOptions.slotMaxTime = slotTimes.max
		this.calendarKey++
	},
	getUpcomingAppointments(){
		this.$store.dispatch('appointment-module/getUpcomingTechnicianAppointments').then(() => {
			this.upcomingAppointments = store.getters['appointment-module/upcomingTechnicianAppointments']
			this.updateCalendarEvents()
		})
	},
	handleDeleteleave(info) {
		if (!info.event.id.startsWith('leave-')) return
		const leaveId = info.event.id.replace('leave-', '')
		this.$bvModal.msgBoxConfirm(`Souhaitez-vous vraiment supprimer ce congé ?`,
			{
				title: 'Suppression congé',
				size: 'md',
				buttonSize: 'md',
				okVariant: 'danger',
				okTitle: 'Oui',
				cancelTitle: 'Non',
				footerClass: 'p-2',
				hideHeaderClose: false,
				centered: true,
			}
		)
		.then(value => {
			if (value === true) {
				store.dispatch('garage-leave-module/deleteGarageLeave', { 'id': leaveId, 'date': clickedDate }).then(() => {
					this.$toast.success('Le congé a été supprimé avec succès')
					if (this.garage && Array.isArray(this.localLeaves)) {
						this.localLeaves = this.localLeaves.filter(l => l.id != leaveId)
					}
					this.updateCalendarEvents()
				})
				.catch(() => {
					this.$toast.error('Une erreur est survenue')
				})
			}
		})
		.catch(err => {
			console.log(err)
		})
	},
	isDateInLeave(date) {
		if (!this.garage || !Array.isArray(this.localLeaves)) return null

		const selected = this.formatLocalDate(date)

		return this.localLeaves.find(leave => {
			return selected >= leave.start_date && selected <= leave.end_date
		}) || null
	},
	generateBusinessHours() {
		if (!this.garage || !this.garage.garage_working_days) return []
		const businessHours = []

		this.garage.garage_working_days.forEach(day => {
			if (!day.is_open) return
			day.garage_working_hours.forEach(range => {
				businessHours.push({
					daysOfWeek: [day.day_of_week],
					startTime: range.start_time.slice(0,5),
					endTime: range.end_time.slice(0,5)
				})
			})
		})
		return businessHours
	},
	getCalendarSlotTimes() {
		if (!this.garage || !this.garage.garage_working_days) {
			return { min: '08:00:00', max: '18:00:00' }
		}

		let minTime = '23:59:59'
		let maxTime = '00:00:00'

		this.garage.garage_working_days.forEach(day => {
			if (!day.is_open) return
			day.garage_working_hours.forEach(range => {
				if (range.start_time < minTime) {
					minTime = range.start_time
				}

				if (range.end_time > maxTime) {
					maxTime = range.end_time
				}
			})
		})

		return {
			min: minTime,
			max: maxTime
		}
	},
  },
  watch: {
	garage: {
		immediate: true,
		handler(newVal) {
			if (newVal && Array.isArray(newVal.garage_leaves)) {
				this.localLeaves = [...newVal.garage_leaves]
			}
			this.updateCalendarEvents()
		}
	}
  },
  mounted() {
    this.calendarOptions.select = this.handleDateSelect
  },
  created() {
    this.getUpcomingAppointments()
  }
}
</script>

<style>
@media (max-width: 768px) {

  .fc-header-toolbar {
    flex-direction: column;
    align-items: center;
    gap: 6px;
  }

  .fc-toolbar-chunk {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
  }

  .fc .fc-button {
    font-size: 12px;
    padding: 4px 6px;
  }

  .fc-toolbar-title {
    font-size: 16px;
  }

}

.leave-event {
  background: rgba(220, 53, 69, 0.35) !important;
}

.fc-bg-event {
  display: flex;
  align-items: center;
  justify-content: center;
}

.fc-bg-event::after {
  content: "Congé";
  color: red;
  font-weight: bold;
  font-size: 14px;
}
</style>