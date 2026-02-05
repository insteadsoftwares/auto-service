<template>
  <div id="about-3" class="about-area gray-bg section-padding">
    <div class="container">
      <div class="row">
        
        <div class="col-lg-6 col-md-12 col-sm-12">
          <div class="info-content-area">
            <div class="section-title">
              <h6>Let's to Know Us</h6>
              <h2>We build <b>dream</b> of <b>construction site</b></h2>
            </div>
            <p>
              We are the leaders in the construction industries and factories.
              We’re world wide reknown for our dignity. We provide high quality
              service all time.
            </p>
          </div>

          <div class="row">
            <div class="col-lg-11">
              <div class="progress-bar-area">
                
                <div class="single-bar-item">
                  <h4>Industrial</h4>
                  <div id="bar1" class="barfiller">
                    <span class="tip">90%</span>
                    <span class="fill" data-percentage="90"></span>
                  </div>
                </div>

                <div class="single-bar-item">
                  <h4>Construction</h4>
                  <div id="bar3" class="barfiller">
                    <span class="tip">80%</span>
                    <span class="fill" data-percentage="80"></span>
                  </div>
                </div>

              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 col-md-6 col-12">
              <div class="choose-certify">
                <i class="la la-clipboard-check"></i>
                <h5>Certified Company</h5>
                <p>Behold Lorem ipsum dolor sit, amet consectetur adipisicing elit.</p>
              </div>
            </div>

            <div class="col-lg-6 col-md-6 col-12">
              <div class="choose-certify">
                <i class="la la-medal"></i>
                <h5>Experience Employee</h5>
                <p>Behold Lorem ipsum dolor sit, amet consectetur adipisicing elit.</p>
              </div>
            </div>
          </div>

        </div>

        <div class="col-lg-6 col-md-12 col-sm-12 text-lg-end">
          <div class="about-bg">
            <img src="/homePage/img/info/about-3.jpg" alt="">
          </div>
        </div>

      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "AboutSection",

  mounted() {
    $("#bar1").barfiller({
      barColor: "#FFD857",
      duration: 5000,
    });

    $("#bar2").barfiller({
      barColor: "#FFD857",
      duration: 6000,
    });

    $("#bar3").barfiller({
      barColor: "#FFD857",
      duration: 7000,
    });

    $("#bar4").barfiller({
      barColor: "#FFD857",
      duration: 5000,
    });

    $("#bar5").barfiller({
      barColor: "#FFD857",
      duration: 6000,
    });

    $("#bar6").barfiller({
      barColor: "#FFD857",
      duration: 7000,
    });
  }
}
</script>
