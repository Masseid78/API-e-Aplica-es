<template>
  <div class="card">
    <h3> Validação de CPF</h3>
    <form @submit.prevent="fetchCpf">
      <input v-model="cpf" type="text" placeholder="Digite o CPF" maxlength="14" required />
      <button class="btn" type="submit">Validar</button>
    </form>
    <div v-if="result" class="result">
      <div><strong>CPF:</strong> {{ result.cpf }}</div>
      <div><strong>Válido:</strong> <span :style="{color: result.is_valid ? '#4CAF50' : '#c00'}">{{ result.is_valid ? 'Sim' : 'Não' }}</span></div>
      <div v-if="result.formatted"><strong>Formatado:</strong> {{ result.formatted }}</div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const cpf = ref('');
const result = ref(null);
const error = ref('');

async function fetchCpf() {
  error.value = '';
  result.value = null;
  try {
    const res = await fetch(`/api/validate-cpf?cpf=${cpf.value}`);
    const data = await res.json();
    if (data.status === 'SUCCESS') {
      result.value = data.data;
    } else {
      error.value = data.message || 'Erro ao validar CPF';
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
  background: #e8f5e8;
  border: 1px solid #4CAF50;
  border-radius: 5px;
  padding: 10px;
  margin-top: 10px;
}
.error {
  color: #c00;
  margin-top: 8px;
}
</style> 