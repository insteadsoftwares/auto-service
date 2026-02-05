<template>
  <div class="feature-area section-padding">
    <div class="container">
      <div class="row">
        <div class="col-lg-6 col-md-12 col-sm-12">
          <div class="section-title">
            <h2>Ce Qui Nous Distingue</h2>
          </div>
          <div
            v-for="feature in features.slice(0,3)"
            :key="feature.id"
            class="single-feature-item"
          >
            <div class="feature-icon">
              <i :class="feature.icon" />
            </div>
            <div class="feature-content">
              <h5>{{ feature.title }}</h5>
              <p>{{ feature.content }}</p>
            </div>
          </div> 					
        </div>
        <div class="col-lg-6 col-md-12 col-sm-12">
          <div class="feature-image">
            <img
              src="/homePage/img/feature/feature.jpg"
              alt=""
            >
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "FeatureArea",
  data() {
    return {
      features: []
    };
  },
  mounted() {
    // Si tu veux récupérer depuis un JSON externe
    // axios.get("/data/features.json").then(res => {
    //   this.features = res.data;
    // });

    // Exemple de données statiques
    this.features = [
      {
        id: "1",
        icon: "flaticon-plumber",
        title: "Service Automobile de Qualité",
        content: "Nous offrons des services d'entretien et de réparation haut de gamme pour tous types de véhicules.",
      },
      {
        id: "2",
        icon: "flaticon-engineer",
        title: "Expertise Reconnu",
        content: "Notre équipe de mécaniciens certifiés garantit des interventions fiables et rapides.",
      },
      {
        id: "3",
        icon: "flaticon-process",
        title: "Technologie de Pointe",
        content: "Nous utilisons les dernières technologies pour des diagnostics précis et des réparations efficaces.",
      }
    ];
  }
};
</script>