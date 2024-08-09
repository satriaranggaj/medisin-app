<template>
  <q-page class="flex flex-center">
    <q-card>
      <q-card-section>
        <img
          class="logo"
          src="../../public/img/medisin-logo.png"
          alt="logo"
          width="50%"
        />
      </q-card-section>

      <q-card-section>
        <q-form @submit.prevent="login">
          <q-input borderless v-model="email" label="Email" type="email" />
          <q-input
            borderless
            v-model="password"
            label="Password"
            type="password"
          />
          <q-btn
            type="submit"
            label="Login"
            color="primary"
            :loading="loading"
          />
        </q-form>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script>
export default {
  data() {
    return {
      email: "",
      password: "",
      loading: false,
    };
  },
  methods: {
    async login() {
      if (!this.email || !this.password) {
        this.$q.notify({
          color: "negative",
          position: "top",
          message: "Mohon masukan data terlebih dulu.",
        });
        return;
      }

      this.loading = true;

      try {
        const response = await this.$api.post("/login", {
          email: this.email,
          password: this.password,
        });

        localStorage.setItem("token", response.data.token);
        this.$q.notify({
          color: "positive",
          position: "top",
          message: "Anda Berhasil Login",
        });

        this.$router.push("/patients");
      } catch (error) {
        let errorMessage =
          "An unexpected error occurred. Please try again later.";

        if (error.response) {
          if (error.response.status === 401) {
            errorMessage =
              error.response.data.message ||
              "Invalid credentials, please check and try again.";
          } else {
            errorMessage = "Login Gagal, Mohon di coba kembali.";
          }
        }

        this.storeNotification({
          color: "negative",
          message: errorMessage,
        });
      } finally {
        this.loading = false;
      }
    },

    storeNotification(notification) {
      const notifications =
        JSON.parse(localStorage.getItem("notifications")) || [];
      notifications.push(notification);
      localStorage.setItem("notifications", JSON.stringify(notifications));
    },

    showStoredNotifications() {
      const notifications =
        JSON.parse(localStorage.getItem("notifications")) || [];
      notifications.forEach((notification) => {
        this.$q.notify({
          color: notification.color,
          position: "top",
          message: notification.message,
        });
      });
      localStorage.removeItem("notifications");
    },
  },

  mounted() {
    this.showStoredNotifications();
  },
};
</script>

<style scoped>
.q-page {
  background-color: #0461cd;
}
.q-card {
  width: 500px;
  max-width: 90%;
  border-radius: 25px;
  padding: 10px 25px;
}
.logo {
  margin: 0 25%;
}
.q-input {
  background-color: lightgrey;
  height: 55px;
  border-radius: 55px;
  margin-bottom: 20px;
  padding: 0 15px 4px 25px;
}
.q-btn {
  width: 80%;
  margin: 0px 10% 25px 10%;
  padding: 10px 0;
  border-radius: 50px;
}
</style>
