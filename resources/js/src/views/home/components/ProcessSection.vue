<template>
  <div class="process-area section-padding pad-bot-60">
    <div class="container">

      <div class="row">
        <div class="offset-lg-2 col-lg-8 text-center">
          <div class="section-title">
            <h2>Votre parcours en 4 étapes</h2>
          </div>
        </div>
      </div>

      <div class="row process-item-wrap">
        <div
          class="col-lg-3 col-md-6 col-12"
          v-for="pr in process.slice(0, 4)"
          :key="pr.id"
        >
          <div class="single-process-item">
            <div class="process-num">
              <p>{{ pr.number }}</p>
            </div>
            <div class="process-icon">
              <img :src="'/homePage/img/icon/' + pr.icon" alt="icon" />
            </div>
            <div class="process-content">
              <h5>{{ pr.title }}</h5>
              <p>{{ pr.content }}</p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "ProcessComponent",

  data() {
    return {
      process: []
    };
  },
  mounted() {
    this.loadProcessData();
  },
  methods: {
    async loadProcessData() {
      const fetchedProcess = [
        {
          id: "1", 
          number: "01",
          icon: "customer-support.png",
          title: "Choisissez un service",
          content: "Sélectionnez le type de service dont votre voiture a besoin, comme révision, vidange, ou réparation."
        },
        {
          id: "2",
          number: "02",
          icon: "schedule.png",
          title: "Réservez un rendez-vous",
          content: "Choisissez le garage partenaire, la date et l’heure qui vous conviennent."
        },
        {
          id: "3",
          number: "03",
          icon: "confirmation.png",
          title: "Confirmation et préparation",
          content: "Recevez la confirmation du rendez-vous et les instructions pour votre arrivée."
        },
        {
          id: "4",
          number: "04",
          icon: "mechanic.png",
          title: "Service réalisé",
          content: "Votre voiture est prise en charge par un professionnel, et vous recevez un compte-rendu du service effectué."
        }
      ];

      this.process = fetchedProcess;
    }
  }
};
</script>
