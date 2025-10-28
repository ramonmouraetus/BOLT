<script setup>
import { ref } from 'vue';

defineProps({
  msg: String,
});

const count = ref(0);

const iter = () => {
  console.log('Botão clicado!');
  count.value++; 
  
};
</script>

<template>
  <h1>{{ msg }}</h1>
  <div class="card">
    <button type="button" @click="iter">count is {{ count }}</button>
  </div>
</template>
