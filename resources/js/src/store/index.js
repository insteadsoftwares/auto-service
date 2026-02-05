import Vue from 'vue'
import Vuex from 'vuex'

// Modules
import app from './app'
import appConfig from './app-config'
import verticalMenu from './vertical-menu'
import authModule from './modules/auth'
import userModule from './modules/user'
import serviceModule from './modules/service'
import VehicleTypeModule from './modules/vehicleType'
import VehicleBrandModule from './modules/vehicleBrand'
import VehicleModeleModule from './modules/vehicleModele'
import GarageModule from './modules/garage'
import AppointmentModule from './modules/appointment'
import ClientVehicleModule from './modules/clientVehicle'
import NotificationModule from './modules/notification'
import ContactModule from './modules/contact'
import StatisticModule from './modules/statistic'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    app,
    appConfig,
    verticalMenu,
	'auth-module': authModule,
	'user-module': userModule,
	'service-module': serviceModule,
	'vehicle-type-module': VehicleTypeModule,
	'vehicle-brand-module': VehicleBrandModule,
	'vehicle-modele-module': VehicleModeleModule,
	'garage-module': GarageModule,
	'appointment-module': AppointmentModule,
	'client-vehicle-module': ClientVehicleModule,
	'notification-module': NotificationModule,
	'contact-module': ContactModule,
	'statistic-module': StatisticModule,
  },
  strict: process.env.DEV,
})
