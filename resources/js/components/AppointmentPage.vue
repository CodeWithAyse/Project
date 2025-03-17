<template>
    <button class="button red" @click="confirmLogout">Çıkış Yap</button>

    <LogoutModal :visible="showLogoutConfirm" @confirm="logout" @cancel="cancelLogout" />

    <div class="arkaplan-container">
        <div v-if="step === 1" class="ilksayfa-container">
            <div v-if="controlMessage" class="control-message">
                {{ controlMessage }}
            </div>
            <label for="polyclinic" class="label">Poliklinik Seçiniz</label>
            <select v-model="selectedPolyclinic" class="dropdown">
                <option value="" disabled>Poliklinik Seçiniz</option>
                <option v-for="polyclinic in polyclinics" :key="polyclinic.id" :value="polyclinic.id">
                    {{ polyclinic.name }}
                </option>
            </select>

            <label for="doctor" class="label">Hekim Seçiniz</label>
            <select v-model="selectedDoctor" class="dropdown" :disabled="!selectedPolyclinic">
                <option value="" disabled>Hekim Seçiniz</option>
                <option v-for="doctor in FilteredDoctors" :key="doctor.id" :value="doctor.id">
                    {{ doctor.name }} {{ doctor.surname }}
                </option>
            </select>
        </div>

        <div v-if="step === 2" class="ilksayfa-container">
            <div v-if="controlMessage" class="control-message">
                {{ controlMessage }}
            </div>
            <label for="appointment-date" class="label">Tarih Seçiniz</label>
            <Datepicker class="date" v-model="selectedDate" type="date" format="dd.MM.yyyy" locale="tr-TR"
                :format-locale="tr" :disabled="!selectedDoctor" :minDate="minDate" :enableTimePicker="false"
                :disabled-week-days="[6, 0]" />

            <div class="saat-container">
                <div v-for="(timeslot, index) in timeslots" :key="index" class="timeslot">
                    <div @click="toggleTime(index)" class="timeslot-header">
                        <span>{{ timeslot.hour }}</span>
                        <span>
                            <ChevronDown v-if="openIndex === index" class="w-5 h-5 text-gray-600" />
                            <ChevronRight v-else class="w-5 h-5 text-gray-600" />
                        </span>
                    </div>

                    <div v-if="openIndex === index" class="timeslot-body">
                        <button v-for="(time, i) in timeslot.slots" :key="i" class="time-button" :class="{
                            'selected-time': selectedTime === time,
                            'disabled-time': checkAppointmentConflict(time),
                        }" :disabled="checkAppointmentConflict(time)" @click="selectTime(time)">
                            {{ time }}
                        </button>
                    </div>
                </div>

                <button class="btn-onayla" @click="onayla()">Onayla</button>
                <ConfirmModal :visible="showConfirmScreen" @confirm="confirmYes" @cancel="confirmNo" />
            </div>
        </div>

        <div v-if="step === 3" class="ilksayfa-container">
            <div v-if="controlMessage" class="control-message">
                {{ controlMessage }}
            </div>
            <h2>Seçilen Randevu Bilgileri</h2>
            <p><strong>Poliklinik:</strong> {{ selectedPolyclinicName }}</p>
            <p><strong>Hekim:</strong> {{ selectedDoctorName }}</p>
            <p><strong>Tarih:</strong> {{ formattedDate }}</p>
            <p><strong>Saat:</strong> {{ selectedTime || "Seçilmedi" }}</p>
        </div>

        <SquareArrowLeft v-if="step > 1" @click="prevStep" class="clickable-icon-left" />
        <SquareArrowRight v-if="step < 2" @click="nextStep" class="clickable-icon-right" />

    </div>
</template>

<script>
import axios from "axios";
import LogoutModal from "./LogoutModal.vue";
import ConfirmModal from "./ConfirmModal.vue";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";
import { DateTime } from "luxon";


export default {
    components: {
        LogoutModal,
        ConfirmModal,
        Datepicker,
    },
    data() {
        return {
            controlMessage: "",
            step: 1, //the variable whose pages we will follow
            doctors: [],
            polyclinics: [],
            takenAppointments: [], //we will keep previously made appointments
            selectedPolyclinic: null,
            selectedDoctor: null,
            selectedDate: null,
            selectedTime: null,
            showConfirmScreen: false,
            showLogoutConfirm: false,
            minDate: new Date().toISOString().split("T")[0], // prevents the user from selecting dates before today
            openIndex: null, // Holds the index of the open row
            timeslots: [
                {
                    hour: "09:00",
                    slots: [
                        "09:00",
                        "09:10",
                        "09:20",
                        "09:30",
                        "09:40",
                        "09:50",
                    ],
                },
                {
                    hour: "10:00",
                    slots: [
                        "10:00",
                        "10:10",
                        "10:20",
                        "10:30",
                        "10:40",
                        "10:50",
                    ],
                },
                {
                    hour: "11:00",
                    slots: [
                        "11:00",
                        "11:10",
                        "11:20",
                        "11:30",
                        "11:40",
                        "11:50",
                    ],
                },
                {
                    hour: "13:00",
                    slots: [
                        "13:00",
                        "13:10",
                        "13:20",
                        "13:30",
                        "13:40",
                        "13:50",
                    ],
                },
                {
                    hour: "14:00",
                    slots: [
                        "14:00",
                        "14:10",
                        "14:20",
                        "14:30",
                        "14:40",
                        "14:50",
                    ],
                },
                {
                    hour: "15:00",
                    slots: [
                        "15:00",
                        "15:10",
                        "15:20",
                        "15:30",
                        "15:40",
                        "15:50",
                    ],
                },
                {
                    hour: "16:00",
                    slots: [
                        "16:00",
                        "16:10",
                        "16:20",
                        "16:30",
                        "16:40",
                        "16:50",
                    ],
                },
            ],
        };
    },
    computed: {
        userId() {
            return this.$store.state.userId;
        },
        FilteredDoctors() {
            if (!this.selectedPolyclinic) return [];
            return this.doctors.filter(
                (doctor) => doctor.polyclinicId == this.selectedPolyclinic
            );
        },
        selectedPolyclinicName() {
            let polyclinic = this.polyclinics.find(
                (p) => p.id == this.selectedPolyclinic
            );
            if (this.selectedPolyclinic == "")
                this.controlMessage = "Lütfen Seçim Yapınız";
            return polyclinic.name;
        },
        selectedDoctorName() {
            let doctor = this.doctors.find((d) => d.id == this.selectedDoctor);
            return doctor ? `${doctor.name} ${doctor.surname}` : "Seçilmedi";
        },
        formattedDate() {
            return this.selectedDate
                ? DateTime.fromJSDate(new Date(this.selectedDate)).toFormat(
                    "dd.MM.yyyy"
                )
                : "Seçilmedi";
        },
    },
    methods: {
        async fetchAvailableTimes() {
            if (!this.selectedDoctor || !this.selectedDate) return;

            try {
                const response = await axios.get(
                    "http://localhost:8000/appointments/available-times",
                    {
                        params: {
                            doctorId: this.selectedDoctor,
                            appointmentDate: this.selectedDate,
                        },
                    }
                );

                this.takenAppointments = response.data.takenAppointments.map(
                    (appointment) => {
                        return appointment.slice(0, 5); // "HH:mm:ss" -> "HH:mm" 
                    }
                );

                console.log("Dolu Saatler:", this.takenAppointments);
            } catch (error) {
                console.error("Randevu saatleri alınırken hata oluştu:", error);
            }
        },

        checkAppointmentConflict(time) {
            return this.takenAppointments.includes(time); 
        },
        selectTime(time) {
            if (!this.checkAppointmentConflict(time)) {
                this.selectedTime = time;
            }
        },
        logout() {
            this.$router.push("/");
        },
        confirmLogout() {
            this.showLogoutConfirm = true;
        },
        cancelLogout() {
            this.showLogoutConfirm = false;
        },
        logout() {
            this.showLogoutConfirm = false;
            this.$router.push("/");
        },

        nextStep() {
            if (this.step === 1) {
                if (!this.selectedPolyclinic) {
                    this.controlMessage = "Lütfen bir poliklinik seçiniz.";
                    return;
                }
                if (!this.selectedDoctor) {
                    this.controlMessage = "Lütfen bir hekim seçiniz.";
                    return;
                }
            }

            this.controlMessage = ""; 
            this.step++;
        },
        prevStep() {
            if (this.step > 1) this.step--;
        },
        toggleTime(index) {
            this.openIndex = this.openIndex === index ? null : index;
        },
        selectTime(time) {
            this.selectedTime = time;
        },
        confirmYes() {
            this.showConfirmScreen = false;
            this.step++;
            console.log("Seçilen Poliklinik ID:", this.selectedPolyclinic);
            console.log("Seçilen Hekim ID:", this.selectedDoctor);
            console.log("Seçilen tarih ", this.selectedDate);
            console.log("Seçilen saat", this.selectedTime);
            console.log(typeof this.selectedDate);

            const formattedDate = DateTime.fromJSDate(
                new Date(this.selectedDate)
            ).toFormat("yyyy-MM-dd");

            const appointmentData = {
                userId: this.userId, 
                polyclinicId: this.selectedPolyclinic, 
                doctorId: this.selectedDoctor, 
                appointmentDate: formattedDate,

                appointmentTime: this.selectedTime, 
            };
            axios
                .post("http://localhost:8000/appointments", appointmentData)
                .then((response) => {
                    console.log(response.data.message); 
                })
                .catch((error) => {
                    console.error("Randevu kaydedilirken hata oluştu:", error);
                });
        },
        confirmNo() {
            this.showConfirmScreen = false;
        },
        onayla() {
            if (!this.selectedDate) {
                this.controlMessage = "Lütfen bir tarih seçiniz.";
                return;
            }
            this.controlMessage = "";
            if (!this.selectedTime) {
                this.controlMessage = "Lütfen bir saat seçiniz.";
                return;
            }
            this.controlMessage = "";
            this.showConfirmScreen = true;
        },
    },
    watch: {
        selectedDoctor() {
            this.fetchAvailableTimes();
        },
        selectedDate() {
            this.fetchAvailableTimes();
        },
    },
    mounted() {
        axios
            .get("http://localhost:8000/doctor")
            .then((response) => {
                this.doctors = response.data;
                console.log("Doktorlar:", this.doctors);
            })
            .catch((error) => {
                console.error("Doktorlar alınırken hata oluştu:", error);
            });

        axios
            .get("http://localhost:8000/polyclinic")
            .then((response) => {
                this.polyclinics = response.data;
                console.log("Poliklinikler:", this.polyclinics);
            })
            .catch((error) => {
                console.error("Poliklinik alınırken hata oluştu:", error);
            });

        console.log("Kullanıcı ID:", this.userId); 
    },
};
</script>

<style scoped>
.arkaplan-container {
    width: 100%;
}

.ilksayfa-container {
    width: 70%;
    position: fixed;
    left: 15%;
    bottom: 18%;
    background-color: #e8e8e8;
    height: 530px;
    border-radius: 25px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 40px;
    margin: 1px auto;
}

.nav-button {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    top: 42%;
    width: 5%;
    height: 7%;
    background-color: #cfcfcf;
    color: black;
    font-size: 50px;
    cursor: pointer;
}

.left-button {
    left: 16%;
}

.right-button {
    right: 16%;
}

.label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.dropdown {
    width: 30%;
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ccc;
    border-radius: 4px;
}

.date {
    width: 30%;
}

.saat-container {
    padding-left: 20%;
    width: 95%;
    margin: 20px auto;
    font-family: Arial, sans-serif;
}

.timeslot {
    width: 70%;
    border: 1px solid #ccc;
    border-radius: 8px;
    margin-bottom: 10px;
    overflow: hidden;
}

.timeslot-header {
    background: #f7f7f7;
    padding: 3px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    font-weight: bold;
}

.timeslot-header:hover {
    background: #e2e2e2;
}

.timeslot-body {
    padding: 10px;
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    background: #fff;
}

.time-button {
    background: #007bff;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
}

.time-button:hover {
    background: #0056b3;
}

.selected-time {
    background-color: #28a745 !important;
    color: white;
}

.btn-onayla {
    background-color: red;
    color: white;
    position: absolute;
    top: 90%;
    left: 45%;
}

.button {
    width: 10%;
    position: fixed;
    left: 75%;
    margin: 10px;
    padding: 10px;
    border: none;
    border-radius: 5px;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}

.red {
    background-color: #d9534f;
}
.control-message {
    position: absolute;
    color: red;
    font-size: 14px;
    top: 85%;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    width: 100%;
}

.disabled-time {
    background-color: #e8e8e8;
    cursor: not-allowed;
    color: white;
}

.selected-time {
    background-color: #28a745 !important;
    color: white;
}

.clickable-icon-right {
    cursor: pointer;
    position: fixed;
    left: 80%;
    top: 45%;
    width: 30px;
    height: 30px;
}

.clickable-icon-left {
    cursor: pointer;
    position: fixed;
    left: 17%;
    top: 45%;
    width: 30px;
    height: 30px;
}
</style>
