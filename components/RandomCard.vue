<template>
  <div class="card">
    <h3> Números Aleatórios</h3>
    <form @submit.prevent="fetchRandom">
      <label>Min: <input v-model.number="min" type="number" required /></label>
      <label>Max: <input v-model.number="max" type="number" required /></label>
      <label>Qtd: <input v-model.number="count" type="number" min="1" max="100" required /></label>
      <button class="btn" type="submit">Gerar</button>
    </form>
    <div v-if="result" class="result">
      <strong>Resultado:</strong>
      <pre>{{ result }}</pre>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const min = ref(1);
const max = ref(100);
const count = ref(1);
const result = ref(null);
const error = ref('');

async function fetchRandom() {
  error.value = '';
  result.value = null;
  try {
    const res = await fetch(`/api/random?min=${min.value}&max=${max.value}&count=${count.value}`);
    const data = await res.json();
    if (data.status === 'SUCCESS') {
      result.value = JSON.stringify(data.data.numbers, null, 2);
    } else {
      error.value = data.message || 'Erro ao buscar número aleatório';
    }
  } catch (e) {
    error.value = 'Erro de conexão';
  }
}
</script>

<style scoped>
.card {
  display: flex;
  flex-direction: column;
}
form {
  display: flex;
  gap: 8px;
  flex-wrap: wrap;
  margin-bottom: 10px;
}
input {
  width: 60px;
  padding: 4px 6px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
.btn {
  margin-left: auto;
}
.result {
  background: #e8f5e8;
  border: 1px solid #4CAF50;
  border-radius: 5px;
  padding: 10px;
  margin-top: 10px;
  font-size: 1.1em;
}
.error {
  color: #c00;
  margin-top: 8px;
}
</style> 