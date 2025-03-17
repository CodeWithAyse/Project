import { createStore } from 'vuex';

export default createStore({
  state: {
    userId: null,  // Kullanıcı bilgisi başlangıçta null
  },
  mutations: {
    setUserId(state, userId) {
      state.userId = userId;  // Kullanıcı bilgisini güncelle
    },
  },
  actions: {
    setUserId({ commit }, userId) {
      commit('setUserId', userId);  // Mutasyonu çağır
    },
  },
  // getters: {
  //   getUser: (state) => state.saveUserId,  // Kullanıcı bilgisini getir
  // },
});
