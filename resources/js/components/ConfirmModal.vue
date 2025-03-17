<template>

    <div v-if="visible" class="confirm-modal">
        <div class="confirm-modal-content">
            <p>Onaylamak İstediğinizden Emin Misiniz?</p>
            <button @click="confirm" class="button green">Evet</button>
            <button @click="cancel" class="button blue">Hayır</button>
    </div>
    </div>
</template>
<script>
export default{
    props:['visible'],
    methods:{
        confirm(){
            this.$emit('confirm');
        },
        cancel() {
        this.$emit('cancel')
        }
    }
};
</script>
<style>
.confirm-modal{
  position: fixed;
  left:40%;
  top:15%;
  align-items: center;
  background-color: rgba(0, 0, 0, 0.7);
  padding: 20px;
  border-radius: 10px;
  /* z-index: 1000; */
  width: auto;
  max-width: 300px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
}
.confirm-modal-content {
  color: white;
  text-align: center;
}

.button {
  margin: 10px;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  color: #fff;
  font-size: 14px;
}

.green {
  background-color: #26b99a;
}

.blue {
  background-color: #0275d8;
}
</style>