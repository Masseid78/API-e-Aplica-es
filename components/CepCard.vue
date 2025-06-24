<template>
  <div class="card">
    <h3>Consulta de CEP</h3>
    <form @submit.prevent="fetchCep">
      <input v-model="cep" type="text" placeholder="Digite o CEP" maxlength="9" required />
      <button class="btn" type="submit">Consultar</button>
    </form>
    <div v-if="result" class="result">
      <div><strong>CEP:</strong> {{ result.cep }}</div>
      <div><strong>Logradouro:</strong> {{ result.logradouro }}</div>
      <div><strong>Bairro:</strong> {{ result.bairro }}</div>
      <div><strong>Cidade:</strong> {{ result.cidade }}</div>
      <div><strong>Estado:</strong> {{ result.estado }}</div>
      <div><strong>IBGE:</strong> {{ result.ibge }}</div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const cep = ref('');
const result = ref(null);
const error = ref('');

async function fetchCep() {
  error.value = '';
  result.value = null;
  try {
    const res = await fetch(`/api/cep?cep=${cep.value}`);
    const data = await res.json();
    if (data.status === 'SUCCESS') {
      result.value = data.data;
    } else {
      error.value = data.message || 'Erro ao consultar CEP';
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
  background: #fffbe8;
  border: 1px solid #ffc107;
  border-radius: 5px;
  padding: 10px;
  margin-top: 10px;
}
.error {
  color: #c00;
  margin-top: 8px;
}
</style> 