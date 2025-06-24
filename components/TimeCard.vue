<template>
  <div class="card">
    <h3> Horário Atual</h3>
    <form @submit.prevent="fetchTime">
      <input v-model="timezone" type="text" placeholder="Fuso horário (ex: America/Sao_Paulo)" required />
      <button class="btn" type="submit">Consultar</button>
    </form>
    <div v-if="result" class="result">
      <div><strong>Data/Hora:</strong> {{ result.datetime }}</div>
      <div><strong>Timestamp:</strong> {{ result.timestamp }}</div>
      <div><strong>Fuso:</strong> {{ result.timezone }}</div>
      <div><strong>Formatado:</strong> {{ result.formatted.date }} {{ result.formatted.time }} ({{ result.formatted.day }}, {{ result.formatted.month }})</div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const timezone = ref('America/Sao_Paulo');
const result = ref(null);
const error = ref('');

async function fetchTime() {
  error.value = '';
  result.value = null;
  try {
    const res = await fetch(`/api/time?timezone=${timezone.value}`);
    const data = await res.json();
    if (data.status === 'SUCCESS') {
      result.value = data.data;
    } else {
      error.value = data.message || 'Erro ao buscar horário';
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
  margin-bottom: 10px;
}
input {
  flex: 1;
  padding: 4px 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
.result {
  background: #e3f0ff;
  border: 1px solid #2196F3;
  border-radius: 5px;
  padding: 10px;
  margin-top: 10px;
}
.error {
  color: #c00;
  margin-top: 8px;
}
</style> 