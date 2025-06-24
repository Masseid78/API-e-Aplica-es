<template>
  <div class="card">
    <h3> Validação de Email</h3>
    <form @submit.prevent="fetchEmail">
      <input v-model="email" type="email" placeholder="Digite o email" required />
      <button class="btn" type="submit">Validar</button>
    </form>
    <div v-if="result" class="result">
      <div><strong>Email:</strong> {{ result.email }}</div>
      <div><strong>Válido:</strong> <span :style="{color: result.is_valid ? '#4CAF50' : '#c00'}">{{ result.is_valid ? 'Sim' : 'Não' }}</span></div>
      <div v-if="result.suggestions && result.suggestions.length">
        <strong>Sugestões:</strong>
        <ul>
          <li v-for="(s, i) in result.suggestions" :key="i">{{ s }}</li>
        </ul>
      </div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const email = ref('');
const result = ref(null);
const error = ref('');

async function fetchEmail() {
  error.value = '';
  result.value = null;
  try {
    const res = await fetch(`/api/validate-email?email=${encodeURIComponent(email.value)}`);
    const data = await res.json();
    if (data.status === 'SUCCESS') {
      result.value = data.data;
    } else {
      error.value = data.message || 'Erro ao validar email';
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
ul {
  margin: 0;
  padding-left: 18px;
}
.error {
  color: #c00;
  margin-top: 8px;
}
</style> 