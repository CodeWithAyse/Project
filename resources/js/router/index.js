import {createRouter,createWebHistory} from 'vue-router';
import LoginPage from '../components/LoginPage.vue';
import AppointmentPage from '../components/AppointmentPage.vue';
import SignUp from '../components/SignUp.vue';
const routes=[
    {
        path:'/',
        name:'Login',
        component:LoginPage
    },
    {
        path: '/RandevuSayfasi',
        name: 'AppointmentPage',
        component: AppointmentPage, // Üye Ol sayfası
      },
      {
        path: '/UyeKayıt',
        name: 'Signup',
        component: SignUp, // Üye Ol sayfası
      },
];
const router=createRouter({
    history:createWebHistory(import.meta.env.BASE_URL),
    routes,
});
    export default router;