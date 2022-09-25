<template>
  <div id="root-page">
    <div class="custom-shape-divider-top-1646529674">
      <img v-bind:src="images.wave">
    </div>
    
    <div class="container main text-center">
      <img v-bind:src="images.logo">

      <div class="my-4 text-white" id="greetings">
        <h1 class="font-weight-300"> Selamat Datang Di <br> E-Office DPC SPPI BERSATU </h1>
        <p class="lead">
          PT. Pelabuhan Tanjung Priok
        </p>

        <a href="/login" class="btn btn-white my-4"> Login </a>
      </div>

      <img v-bind:src="images.vector" class="img-vector">
    </div>

    <section class="container text-center">
      <h1 class="font-weight-300"> Visi dan Misi </h1>

      <div class="cards my-4">
        <div class="row">
          <div class="col"></div>
          <div class="col">
            <div class="card">
              <div class="card-body">
                <img v-bind:src="images.visi" class="my-4">
                <h5 class="card-title"> Visi </h5>
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto, veritatis.
              </div>
            </div>
          </div>
          <div class="col">
            <div class="card">
              <div class="card-body">
                <img v-bind:src="images.misi" class="my-4">
                <h5 class="card-title"> Misi </h5>
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure tenetur quasi eos architecto fuga recusandae quod et hic.
              </div>
            </div>
          </div>
          <div class="col"></div>
        </div>
      </div>
    </section>

    <section class="text-white" id="news">
      <div class="container text-center" >
        <h1 class="font-weight-300"> Berita </h1>

        <div class="row text-align-left my-4">
          <div class="col">
            <img src="https://images.unsplash.com/photo-1645193601259-63ad87c0b4a9?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80" class="img-thumbnail">
            <h5 class="mt-2"> Office | 27 August 2022 </h5>
            <p class="lead">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus, expedita?
            </p>
          </div>
          <div class="col">
            <img src="https://images.unsplash.com/photo-1645193601259-63ad87c0b4a9?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80" class="img-thumbnail">
            <h5 class="mt-2"> Office | 27 August 2022 </h5>
            <p class="lead">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus, expedita?
            </p>
          </div>
          <div class="col">
            <img src="https://images.unsplash.com/photo-1645193601259-63ad87c0b4a9?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=870&q=80" class="img-thumbnail">
            <h5 class="mt-2"> Office | 27 August 2022 </h5>
            <p class="lead">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Delectus, expedita?
            </p>
          </div>
        </div>
      </div>
    </section>

    <section id="stuktur-organisasi">
      <div class="container text-center">
        <img v-bind:src="images.struktur_organisasi" class="img-fluid">
      </div>
    </section>

    <section id="aspiration">
      <div class="container my-4">
        <div class="row">
          <div class="col-8">
            <h5> Get In Touch </h5>

            <form action="">
              <div class="row">
                <div class="col">
                  <label for="fullname"> Fullname </label>
                  <input type="text" id="fullname" class="form-control my-2">
                </div>
                <div class="col">
                  <label for="email"> Email Address </label>
                  <input type="email" id="email" class="form-control my-2">
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <label for="message"> Message </label>
                  <textarea name="message" class="form-control my-2" id="message" cols="30" rows="5"></textarea>
                </div>
              </div>
              <div class="row">
                <div class="col">
                  <button class="btn btn-primary btn-lg mt-4"> Submit </button>
                </div>
              </div>
            </form>
          </div>
          <div class="col"></div>
        </div>
      </div>
    </section>

    <footer>
      <div class="container">
        <div class="row">
          <div class="col">
            <div class="d-flex">
              <a href=""> <i class="fa fa-facebook"></i> </a>
              <a href=""> <i class="fa fa-twitter"></i> </a>
              <a href=""> <i class="fa fa-youtube"></i> </a>
              <a href=""> <i class="fa fa-instagram"></i> </a>
            </div>
          </div>
          <div class="col">
            <img v-bind:src="images.logo">
          </div>
          <div class="col copy">
            2022 Copyrights & Protected
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<style scoped>
  .main {
    position: relative;
    margin-top: 4em;
  }

  .text-align-left {
    text-align: left;
  }

  #news {
    height: 165vh;
    background-image: url('~@/assets/particles/wave-news-landing.svg');
    background-repeat: no-repeat;
    padding: 12em 0;
  }

  section {
    margin-top: 6em;
    margin-bottom: 6em;
  }

  .font-weight-300 {
    font-weight: 300;
  }

  .card { 
    min-height: 300px;
  }

  .custom-shape-divider-top-1646529674 {
    position: absolute;
    top: -6em;
    left: 0;
    width: 100%;
    overflow: hidden;
    line-height: 0;
  }

  .custom-shape-divider-top-1646529674 svg {
    position: relative;
    display: block;
    width: calc(126% + 1.3px);
    height: 175px;
  }

  .custom-shape-divider-top-1646529674 .shape-fill {
    fill: #FFFFFF;
  }

  .custom-shape-divider-top-2 {
    position: absolute;
    width: 100%;
    overflow: hidden;
    line-height: 0;
  }

  .custom-shape-divider-top-2 svg {
    position: relative;
    display: block;
    width: calc(126% + 1.3px);
    height: 175px;
  }

  .custom-shape-divider-top-2 .shape-fill {
    fill: #FFFFFF;
  }

  /** For mobile devices **/
  @media (max-width: 767px) {
    .custom-shape-divider-top-2 svg {
      width: calc(138% + 1.3px);
      height: 175px;
    }
  }

  .btn-white {
    color: #686FA8;
    background-color: #fff;
    border-radius: 40;
    font-size: 23px;
    padding: 8px 2em;
  }

  .img-vector {
    width: 50%;
  }

  #root-page {
    background-image: url('~@/assets/background.svg');
  }

  footer {
    color: #fff;
    background: linear-gradient(298.86deg, #651CE9 31.29%, #414179 68.72%);
    text-align: center;
    padding: 4em 0;
  }

  footer a {
    color: #fff;
    padding: 0 .2em;
  }

  footer .copy {
    text-align: right;
  }
</style>

<script>
// @ is an alias to /src
export default {
  name: 'HomeView',
  components: {

  },
  data: () => {
    return {
      images: {
        "logo": require('../assets/logo-white.svg'),
        "visi": require('../assets/visi.svg'),
        "misi": require('../assets/misi.svg'),
        "vector": require('../assets/particles/vector-landing.svg'),
        "wave": require('../assets/particles/wave-landing.svg'),
        "wave_news": require('../assets/particles/wave-news-landing.svg'),
        "struktur_organisasi": require('../assets/struktur_og.svg'),
      }
    }
  }
}
</script>
