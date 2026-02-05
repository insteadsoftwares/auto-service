<template>
  <div>
    <div class="header-top">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-12 auto-info">
            <div class="contact-info">
              <i class="las la-envelope"></i> auto.service@gmail.com |
              <i class="las la-phone"></i> +216 58 476 718
            </div>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-12 text-end">
            <div class="site-info">
				<div v-if="!isLoggedIn">
					<span @click="$bvModal.show('login-modal')" style="cursor:pointer;">Connexion</span> |
					<span @click="$bvModal.show('register-modal')" style="cursor:pointer;">Inscription</span>
				</div>
				<div v-else>
					<span class="notif-wrapper" @click="toggleNotif">
						<i class="las la-bell"></i>
						<span v-if="notificationsNb > 0" class="notif-badge">{{ notificationsNb }}</span>
						<div v-if="showNotif" class="notif-dropdown">
							<div v-if="notifications.length">
								<div v-for="(notif, index) in formattedNotifications" :key="index" class="notif-item">
									<strong>{{ notif.title }}</strong>
									<p>{{ notif.message }}</p>
								</div>
							</div>
							<div v-else class="notif-empty">
								Aucune notification
							</div>
						</div>
					</span>
					<span>{{ userName }}</span> |
                	<span @click="logout" style="cursor:pointer;">Déconnexion</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import store from '@/store'

const NOTIFICATION_TRANSLATIONS = {
  'new appointment': {
    title: 'Nouveau rendez-vous',
    message: (n, role) => {
	  const date = new Intl.DateTimeFormat('fr-FR').format(new Date(n.appointment.appointment_date))
      if (role === 'technician') {
        return `Nouveau rendez-vous demandé par ${n.appointment.client.first_name} ${n.appointment.client.last_name} pour le service ${n.appointment.service.name} le ${date}.`
      }
      return `Votre demande de rendez-vous pour le service ${n.appointment.service.name} le ${date} a été envoyée.`
    }
  },
  'appointment confirmed': {
    title: 'Rendez-vous confirmé',
    message: (n, role) => {
	  const date = new Intl.DateTimeFormat('fr-FR').format(new Date(n.appointment.appointment_date))
      if (role === 'client') {
        return `Votre rendez-vous pour le service ${n.appointment.service.name} le ${date} a été confirmé.`
      }
      return `Vous avez confirmé le rendez-vous de ${n.appointment.client.first_name} ${n.appointment.client.last_name} pour le ${date}.`
    }
  },
  'appointment cancelled': {
    title: 'Rendez-vous annulé',
    message: (n, role) => {
	  const date = new Intl.DateTimeFormat('fr-FR').format(new Date(n.appointment.appointment_date))
      if (role === 'client') {
        return `Votre rendez-vous du ${date} pour le service ${n.appointment.service.name} a été annulé.`
      }
      return `Le rendez-vous de ${n.appointment.client.first_name} ${n.appointment.client.last_name} prévu le ${date} a été annulé.`
    }
  },
  'appointment completed': {
    title: 'Rendez-vous terminé',
    message: (n, role) => {
	  const date = new Intl.DateTimeFormat('fr-FR').format(new Date(n.appointment.appointment_date))
      if (role === 'client') {
        return `Votre rendez-vous pour le service ${n.appointment.service.name} du ${date} est terminé.`
      }
      return `Le rendez-vous de ${n.appointment.client.first_name} ${n.appointment.client.last_name} du ${date} a été marqué comme terminé.`
    }
  }
}

export default {
  name: "HeaderTop",
  data() {
    return {
      notifications: [],
	  notificationsNb: null,
	  showNotif: false
    }
  },
  computed: {
    ...mapState('auth-module', {
      isLoggedIn: state => !!state.currentUser,
      userName: state => state.currentUser ? `${state.currentUser.first_name} ${state.currentUser.last_name}` : '',
      userRole: state => state.currentUser.role.name,
    }),
	formattedNotifications() {
		return this.notifications.map(n => {
			const key = n.title?.toLowerCase()
			const translation = NOTIFICATION_TRANSLATIONS[key]

			if (!translation) {
				return {
				...n,
				title: n.title,
				message: n.message
				}
			}

			return {
				...n,
				title: translation.title,
				message: translation.message(n, this.userRole)
			}
		})
	}
  },
  methods: {
    async logout() {
      await this.$store.dispatch('auth-module/logout')
      this.$router.push({ name: 'HomePage' }).catch(() => {})
    },
	toggleNotif() {
		this.showNotif = !this.showNotif
		if (this.showNotif && this.notificationsNb > 0) {
			this.notifications.forEach(notif => {
				if (!notif.is_read) {
					this.$store.dispatch('notification-module/markAsRead', notif.id)
				}
			})
			this.notificationsNb = 0
		}
	},
  },
  mounted() {
    this.$store.dispatch('auth-module/checkUser')
  },
  created() {
	if(this.userRole == 'client'){
		store.dispatch('notification-module/getClientNotifications', { active: 1 }).then(() => {
			this.notifications = store.getters['notification-module/clientNotifications']
			this.notificationsNb = this.notifications.filter(n => !n.is_read).length
		})
	}
	else{
		store.dispatch('notification-module/getTechnicianNotifications').then(() => {
			this.notifications = store.getters['notification-module/technicianNotifications']
			this.notificationsNb = this.notifications.filter(n => !n.is_read).length
		})
	}
  },
}
</script>
<style scoped>
.notif-badge {
  position: absolute;
  top: -6px;
  right: -6px;
  background: #ff3b3b;
  color: #fff;
  border-radius: 50%;
  min-width: 16px;
  height: 16px;
  font-size: 11px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 2px;
} 

.notif-wrapper {
  position: relative;
  cursor: pointer;
  margin-right: 10px;
}

.notif-wrapper i{
  font-size: 18px;
}

.notif-dropdown {
  position: absolute;
  top: 28px;
  right: -150px;
  width: 280px;
  background: #fff;
  border-radius: 6px;
  box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  z-index: 1000;
}

.notif-item {
  padding: 10px 12px;
  border-bottom: 1px solid #eee;
}

.notif-item:last-child {
  border-bottom: none;
}

.notif-item p {
  margin: 4px 0 0;
  font-size: 13px;
  color: #666;
}

.notif-item:hover {
  background: #f7f7f7;
}

.notif-empty {
  padding: 12px;
  text-align: center;
  color: #999;
  font-size: 13px;
}

</style>