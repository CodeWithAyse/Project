<template>
    <div class="signup-container">
        <div class="signup-left-container">
      <h2>Üye Ol</h2>
      <form @submit.prevent="submitForm">
        <input type="text" v-model="tcNo" placeholder="T.C. Kimlik No" required maxlength="11" @input="tcNo = tcNo.replace(/\D/g, '')" />
        <input type="text" v-model="ad" placeholder="Ad" required />
        <input type="text" v-model="soyad" placeholder="Soyad" required />
        <input type="password" v-model="password" placeholder="Şifre" required />
        <select v-model="cinsiyet" required>
          <option disabled value="">Cinsiyet</option>
          <option>Erkek</option>
          <option>Kadın</option>
          <option>Diğer</option>
        </select>
        <input type="date" v-model="dogumTarihi" placeholder="Doğum Tarihi" required />
        <input type="tel" v-model="telefon" placeholder="Telefon" required maxlength="10"  />
        <input type="email" v-model="mail" placeholder="E-mail" required />
        <div>
          <input type="checkbox" v-model="politikaOkudum" required />
          <label>Üyelik ve Gizlilik Politikası Okudum, kabul ediyorum.</label>
        </div>
        <button type="submit" :disabled="!politikaOkudum">Kaydol</button>
      </form>
    </div>
      <div class="signup-right-container">
        <img src="../assets/randevu.png" alt="Randevu Resmi" class="background-image" />
      </div>
    </div>
    <Notification ref="notification" />

  </template>
  
  <script>

  export default {
    data() {
      return {
        tcNo: '',
        ad: '',
        soyad: '',
        cinsiyet: '',
        dogumTarihi: '',
        telefon: '',
        mail: '',
        password: '',
        politikaOkudum: false
      };
    },
    methods: {
      async submitForm() {
    try {
        const response = await axios.post('http://localhost:8000/register', {
            tc_no: this.tcNo,
            name: this.ad,
            surname: this.soyad,
            gender: this.cinsiyet,
            birth_date: this.dogumTarihi,
            phone: this.telefon,
            email: this.mail,
            password: this.password,
        });
        // alert(response.data.message);
        this.$router.push('/');

    } catch (error) {
        alert(error.response.data.message || 'Üyelik işlemi başarısız.');
    }
},
    }
  };
  </script>
  
  <style>
  .signup-container {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    width: 100%;
    height: 100px auto;
    margin:50px auto;
    padding: 20px;
    border: 0px solid #ccc;
    border-radius: 5px;
    text-align: center;
  }
  .signup-left-container{
    flex: 0.5; /* Sol taraf genişliği */
    padding: 5px;
    text-align: center;
  }
  .signup-left-container h2 {
  margin-bottom: 10px;
  font-size: 24px;
  font-weight: bold;
}.signup-left-container label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
  color: #333;
}.signup-left-container select {
  background: white;
  border: 1px solid #ccc;
  border-radius: 4px;
  padding: 10px;
  font-size: 14px;
  color: #333;
  cursor: pointer;

}

.signup-left-container select:focus {
  outline: none;
  border-color: #0275d8;
  box-shadow: 0 0 3px rgba(2, 117, 216, 0.5);
}
  .signup-right-container {
  
  flex: 1; /* Sağ taraf genişliği */
  display: flex;
  justify-content: center; /* Resmi ortala */
  align-items: center;
  }
  input {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
  }
  
  select {
    width: 100%;
    padding: 10px;
    margin: 10px 0;
    border-radius: 5px;
  }
  
  button {
    padding: 10px 20px;
    background-color: #0275d8;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }
  
  button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
  }
  
  </style>
  