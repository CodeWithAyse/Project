<template>
    <div class="container">
      <!-- Sol Taraftaki Form -->
      <div class="left-container">
        <!-- Logo ve Başlık -->
        <div class="header">
          <img src="../assets/online_randevu.jpeg" alt="Logo" class="logo" />
          <h1>
            <span class="highlight">Randevu Sistemi</span>
          </h1>
        </div>
        <!-- Form -->
        <div class="form">
          <input type="text" placeholder="T.C. Kimlik No" v-model="TcNo" class="input-field" maxlength="11"/>
          <input :type="showPassword ? 'text' : 'password'" placeholder="Parola" v-model="Password" class="input-field"/>
          <span class="toggle-password" @click="togglePasswordVisibility">
          <img :src="showPassword ? eyeOpenIcon : eyeClosedIcon" alt="Toggle Password" class="password-icon"/></span>

          <a href="#" class="forgot-password">Parolamı Unuttum</a>
          
          <div v-if="errorMessage" class="error-message">
            {{ errorMessage }}
          </div>

          <div>
          <button class="button green" @click="login">Giriş</button>
          <button class="button blue" @click="signup">Üye Ol</button>
          </div>

        </div>
      </div>
  
      <!-- Sağ Taraftaki Resim -->
      <div class="right-container">
        <img src="../assets/randevu.png" alt="Randevu Resmi" class="background-image" />
      </div>
    </div>
  </template>

  <script>
import axios from 'axios';
import eyeOpenIcon from '../assets/eye-open-icon.png';
import eyeClosedIcon from '../assets/eye-closed-icon.png';
export default {
  data() {
    return {
      TcNo: '',          // Kullanıcının TC kimlik numarasını tutacak
      Password: '',      // Kullanıcının şifresini tutacak
      errorMessage: '' ,
      showPassword: false,  // Hata mesajını tutacak
      eyeOpenIcon,   // Göz açık simgesini değişkene bağla
      eyeClosedIcon,
    };
  },
  methods: {
    togglePasswordVisibility() {
      this.showPassword = !this.showPassword; // Şifre görünürlüğünü değiştir
    },
    async login() {
      if (!this.TcNo || !this.Password) {
      this.errorMessage = 'Lütfen ilgili alanları doldurduğunuzdan emin olun';
      return; // Eğer eksik alan varsa işlemi durdur
    }
    if (this.Password.length < 6) {
      this.errorMessage = 'Şifreniz en az 6 karakter uzunluğunda olmalıdır';
      return;
    }
    if (this.TcNo.length != 11) {
      this.errorMessage = 'TC Kimlik Numaranızı Kontrol Ediniz!';
      return;
    }
    try {
        const response = await axios.post('http://localhost:8000/login', {
            tc_no: this.TcNo,
            password: this.Password,
        });
       
        // alert(response.data.message);
        // console.log('Giriş yapan kullanıcı ID: ', response.data);
        if (response.data && response.data.userId) {  // user_id
          this.$store.dispatch('setUserId', response.data.userId);  // Vuex'e kaydet
        this.$router.push('/RandevuSayfasi');}
        
    } catch (error) {
      if (error.response) {
        // 401 Unauthorized hatası durumunda
        if (error.response.status===401) {
          // Backend tarafından döndürülen hata mesajını kullan
          this.errorMessage = 'TC kimlik numaranız veya şifreniz hatalı';
        } else {
          // Diğer hatalar için genel mesaj
          this.errorMessage = 'Bir hata oluştu, lütfen tekrar deneyin';
        }
      } 
    }
},
    signup() {
      this.$router.push('/UyeKayıt'); // Üye ol sayfasına yönlendir
    },
    
  }
};
  </script>
  <style>
  /* Ana konteyneri iki sütuna böl */
  .container {
  display: flex;
  justify-content: space-between; /* İki tarafı ayır */
  align-items: center; /* Dikey hizalama */
  width: 100%;
  height: auto; /* Sayfa yüksekliği */
  }
  
  /* Sol taraf: Form alanı */
  .left-container {
  flex: 0.5; /* Sol taraf genişliği */
  padding: 100px;
  text-align: center;
  }
  
  /* Sağ taraf: Resim alanı */
  .right-container {
  flex: 1; /* Sağ taraf genişliği */
  display: flex;
  justify-content: center; /* Resmi ortala */
  align-items: center;
  }
  
  .background-image {
  max-width: 90%; /* Resmin boyutunu ayarla */
  height: 600px;
  }
  
  /* Logo */
  .logo {
  width: 100px;
  margin-bottom: 10px;
  position: relative;
  top:60px;
  }
  
  /* Başlık */
  h1 {
  font-size: 24px;
  margin-bottom: 20px;
  color: red;
  }
  
  /* Form input stilleri */
  .input-field {
  width: 100%;
  padding: 10px;
  margin: 10px 0;
  border: 1px solid #ccc;
  border-radius: 5px;
  }
  
  .forgot-password {
  display: block;
  margin: 10px 0;
  color: blue;
  text-decoration: none;
  }
  
  .button {
  /* display: block; */
  width: 50%;
  padding: 10px;
  margin: 10px ;
  border: none;
  border-radius: 5px;
  color: #fff;
  font-size: 16px;
  cursor: pointer;
  }
  
  .green {
  background-color: #26b99a;
  }
  
  .blue {
  background-color: #0275d8;
  }

  .error-message {
  color: red;           /* Hata mesajını kırmızı yap */
  font-size: 14px;      /* Hata mesajının boyutunu küçük yap */
  margin-top: 2%;     /* Giriş butonundan biraz mesafe bırak */
  display: block;       /* Hata mesajını blok seviyesinde göster */
}
.toggle-password {
  position: relative;
  cursor: pointer;
  top: -38px; /* Simgeyi şifre alanına hizalayacak şekilde ayar */
  right: -95%; /* Konumlandırmayı düzenleyin */
}

.password-icon {
  width: 20px;
  height: 20px;
}
  </style>