<template>
  <q-page class="q-pa-md">
    <q-card>
      <q-card-section>
        <img
          class="logo"
          src="../../public/img/medisin-logo.png"
          alt="logo"
          width="20%"
        />
      </q-card-section>
      <q-table
        :rows="patients"
        :columns="columns"
        row-key="medical_record_number"
      >
        <template v-slot:top>
          <q-toolbar>
            <q-toolbar-title>Daftar Pasien</q-toolbar-title>
            <q-btn
              label="TAMBAH PASIEN"
              color="primary"
              @click="showAddPatientModal = true"
            ></q-btn>
            <q-btn
              label="BUAT KUNJUNGAN"
              color="green"
              @click="showCreateVisitModal = true"
            ></q-btn>
            <q-btn
              label="LOGOUT"
              color="negative"
              @click="logout"
              icon="exit_to_app"
            ></q-btn>
          </q-toolbar>
        </template>
        <template v-slot:body-cell-number="props">
          <q-td :props="props">
            {{ props.rowIndex + 1 }}
          </q-td>
        </template>
      </q-table>
    </q-card>

    <!-- Modal Tambah Pasien -->
    <q-dialog v-model="showAddPatientModal">
      <q-card class="form-modal">
        <q-card-section>
          <h4 class="title-form">TAMBAH PASIEN</h4>
        </q-card-section>
        <q-card-section>
          <q-form @submit.prevent="addPatient">
            <q-input
              v-model="newPatient.medical_record_number"
              label="No. Rekam Medik"
              readonly
            />
            <q-input
              v-model="newPatient.name"
              label="Nama Pasien"
              :rules="[
                (val) =>
                  /^[a-zA-Z\s]+$/.test(val) ||
                  'Nama pasien tidak boleh mengandung angka.',
              ]"
            />
            <q-input
              v-model="newPatient.id_number"
              label="NIK"
              :rules="[
                (val) =>
                  /^\d{16}$/.test(val) ||
                  'NIK harus terdiri dari 16 digit angka.',
              ]"
            />
            <q-input
              v-model="newPatient.address"
              label="Alamat Pasien"
              :rules="[
                (val) =>
                  val.length >= 5 ||
                  'Alamat pasien harus memiliki minimal 5 karakter.',
              ]"
            />
            <q-btn
              class="btn-modal"
              type="submit"
              label="Simpan"
              color="primary"
            />
            <q-btn
              class="btn-modal"
              @click="showAddPatientModal = false"
              label="Batal"
              color="secondary"
            />
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>

    <!-- Modal Buat Kunjungan -->
    <q-dialog v-model="showCreateVisitModal">
      <q-card class="form-modal">
        <q-card-section>
          <h4 class="title-form">BUAT KUNJUNGAN</h4>
        </q-card-section>
        <q-card-section>
          <q-form>
            <q-input
              v-model="visitForm.medical_record_number"
              label="No. Rekam Medik"
            />
            <q-input v-model="visitForm.name" label="Nama Pasien" readonly />
            <q-input v-model="visitForm.id_number" label="NIK" readonly />
            <q-input
              v-model="visitForm.address"
              label="Alamat Pasien"
              readonly
            />

            <q-btn
              class="btn-modal"
              @click="isVisitFormValid ? createVisit() : fetchPatientData()"
              :label="isVisitFormValid ? 'Simpan' : 'Cek'"
              color="primary"
            />

            <q-btn
              class="btn-modal"
              @click="showCreateVisitModal = false"
              label="Batal"
              color="secondary"
            />
          </q-form>
        </q-card-section>
      </q-card>
    </q-dialog>
  </q-page>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useQuasar } from "quasar";
import { useRouter } from "vue-router";
import { api } from "src/boot/axios";

const $q = useQuasar();
const router = useRouter();

const patients = ref([]);
const showAddPatientModal = ref(false);
const showCreateVisitModal = ref(false);
const newPatient = ref({
  medical_record_number: "",
  name: "",
  id_number: "",
  address: "",
});
const visitForm = ref({
  medical_record_number: "",
  name: "",
  id_number: "",
  address: "",
});
const isVisitFormValid = ref(false);

const columns = [
  {
    name: "number",
    label: "No.",
    align: "left",
    field: (row) => row.number,
    sortable: false,
  },
  {
    name: "medicalRecordNumber",
    label: "Nomor Rekam Medik",
    align: "left",
    field: "medical_record_number",
    sortable: true,
  },
  {
    name: "name",
    label: "Nama Pasien",
    align: "left",
    field: "name",
    sortable: true,
  },
  {
    name: "id_number",
    label: "NIK",
    align: "left",
    field: "id_number",
    sortable: true,
  },
  {
    name: "address",
    label: "Alamat Pasien",
    align: "left",
    field: "address",
    sortable: true,
  },
  {
    name: "visits",
    label: "Jumlah Kunjungan",
    align: "left",
    field: "visit",
    sortable: true,
  },
];

const fetchPatients = async () => {
  try {
    const response = await api.get("patients");
    patients.value = Array.isArray(response.data.data)
      ? response.data.data
      : [];
  } catch (error) {
    console.error("Error fetching patients:", error);
    $q.notify({
      color: "negative",
      position: "top",
      message: "Failed to fetch patient data.",
    });
  }
};

const addPatient = async () => {
  const nameRegex = /^[a-zA-Z\s]+$/;
  if (!nameRegex.test(newPatient.value.name)) {
    $q.notify({
      color: "negative",
      position: "top",
      message: "Nama pasien tidak boleh mengandung angka.",
    });
    return;
  }

  const nikRegex = /^\d{16}$/;
  if (!nikRegex.test(newPatient.value.id_number)) {
    $q.notify({
      color: "negative",
      position: "top",
      message: "NIK harus terdiri dari 16 digit angka.",
    });
    return;
  }

  if (newPatient.value.address.length < 5) {
    $q.notify({
      color: "negative",
      position: "top",
      message: "Alamat pasien harus memiliki minimal 5 karakter.",
    });
    return;
  }

  try {
    const response = await api.post("patients", newPatient.value);
    const addedPatient = response.data.data;

    $q.notify({
      color: "positive",
      position: "top",
      message: "Data Pasien Berhasil Ditambahkan.",
    });

    patients.value.push(addedPatient);

    showAddPatientModal.value = false;
  } catch (error) {
    console.error("Error adding patient:", error);
    $q.notify({
      color: "negative",
      position: "top",
      message: "Gagal Menambahkan Data Pasien.",
    });
  }
};

const fetchPatientData = async () => {
  try {
    if (!visitForm.value.medical_record_number) {
      $q.notify({
        color: "negative",
        position: "top",
        message: "Nomor Rekam Medik harus diisi.",
      });
      isVisitFormValid.value = false;
      return;
    }

    const response = await api.get(
      `patients/${visitForm.value.medical_record_number}`
    );
    const patient = response.data.data;

    if (patient) {
      visitForm.value.name = patient.name;
      visitForm.value.id_number = patient.id_number;
      visitForm.value.address = patient.address;
      isVisitFormValid.value = true;
    }
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: "Pasien Tidak Ditemukan.",
    });
    isVisitFormValid.value = false;
  }
};

const createVisit = async () => {
  try {
    if (!visitForm.value.medical_record_number) {
      $q.notify({
        color: "negative",
        position: "top",
        message: "Nomor Rekam Medik harus diisi.",
      });
      return;
    }

    if (isVisitFormValid.value) {
      await api.post("visits", {
        medical_record_number: visitForm.value.medical_record_number,
      });
      $q.notify({
        color: "positive",
        position: "top",
        message: "Kunjungan Berhasil Dibuat",
      });
      showCreateVisitModal.value = false;
      fetchPatients();

      visitForm.value = {
        medical_record_number: "",
        name: "",
        id_number: "",
        address: "",
      };
      isVisitFormValid.value = false;
    }
  } catch (error) {
    console.error("Error creating visit:", error);
    $q.notify({
      color: "negative",
      position: "top",
      message: "Kunjungan Gagal Dibuat.",
    });
  }
};
const logout = async () => {
  try {
    await api.post("/logout");
    localStorage.removeItem("token");

    $q.notify({
      color: "positive",
      position: "top",
      message: "Logout Berhasil.",
    });

    router.push("/login");
  } catch (error) {
    $q.notify({
      color: "negative",
      position: "top",
      message: "Logout Gagal. Silakan coba lagi.",
    });
  }
};

onMounted(() => {
  fetchPatients();
});
</script>
<style scoped>
.q-page {
  background-color: #0461cd;
}
.q-card {
  margin: 50px auto;
  border-radius: 25px;
  padding: 10px 25px;
}
.form-modal {
  width: 500px;
  padding: 25px;
}
.title-form {
  font-weight: bold;
  text-align: center;
  margin: 0;
}
.q-btn {
  border-radius: 50px;
  margin: 25px 5px 0 5px;
}
.logo {
  margin: 25px 40%;
}
</style>
