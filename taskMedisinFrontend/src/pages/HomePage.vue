<template>
  <q-page class="flex flex-center">
    <q-card>
      <q-card-section>
        <h2 class="title">Welcome To</h2>
        <img
          class="logo"
          src="../../public/img/medisin-logo.png"
          alt="logo"
          width="80%"
        />
        <q-btn
            to="/login"
            label="start"
            color="primary"
            :loading="loading"
          />
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>

</script>

<style scoped>
.q-page {
  background-color: #0461cd;
}
.q-card {
  width: 1000px;
  max-width: 90%;
  border-radius: 25px;
  padding: 10px 25px;
}
.title{
  font-weight: bold;
  color: #0461cd;
  text-align: center;
}
.logo {
  margin: 0px 10%;
}
.q-input {
  background-color: lightgrey;
  height: 55px;
  border-radius: 55px;
  margin-bottom: 20px;
  padding: 0 15px 4px 25px;
}
.q-btn {
  width: 40%;
  margin: 0px 30% 25px 30%;
  padding: 10px 0;
  border-radius: 50px;
  margin-top: 50px;
}
</style>
