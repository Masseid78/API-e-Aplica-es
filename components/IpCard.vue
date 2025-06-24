<template>
  <div class="card">
    <h3> Informações de IP</h3>
    <form @submit.prevent="fetchIp">
      <input v-model="ip" type="text" placeholder="Digite o IP (ou deixe em branco para o seu)" />
      <button class="btn" type="submit">Consultar</button>
    </form>
    <div v-if="result" class="result">
      <div><strong>IP:</strong> {{ result.ip }}</div>
      <div><strong>País:</strong> {{ result.country }}</div>
      <div><strong>Cidade:</strong> {{ result.city }}</div>
      <div><strong>Região:</strong> {{ result.region }}</div>
      <div><strong>Fuso:</strong> {{ result.timezone }}</div>
      <div><strong>ISP:</strong> {{ result.isp }}</div>
      <div><strong>Coordenadas:</strong> {{ result.coordinates.lat }}, {{ result.coordinates.lon }}</div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const ip = ref('');
const result = ref(null);
const error = ref('');

async function fetchIp() {
  error.value = '';
  result.value = null;
  try {
    const url = ip.value ? `/api/ip?ip=${ip.value}` : '/api/ip';
    const res = await fetch(url);
    const data = await res.json();
    if (data.status === 'SUCCESS') {
      result.value = data.data;
    } else {
      error.value = data.message || 'Erro ao buscar IP';
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