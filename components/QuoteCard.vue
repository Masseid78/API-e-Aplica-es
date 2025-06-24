<template>
  <div class="card">
    <h3> Citação Inspiradora</h3>
    <button class="btn" @click="fetchQuote">Obter Citação</button>
    <div v-if="result" class="result">
      <blockquote>“{{ result.text }}”</blockquote>
      <div style="text-align:right; color:#888;">— {{ result.author }}</div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const result = ref(null);
const error = ref('');

async function fetchQuote() {
  error.value = '';
  result.value = null;
  try {
    const res = await fetch('/api/quote');
    const data = await res.json();
    if (data.status === 'SUCCESS') {
      result.value = data.data;
    } else {
      error.value = data.message || 'Erro ao buscar citação';
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
.btn {
  margin-bottom: 10px;
}
.result {
  background: #f3e8ff;
  border: 1px solid #9c27b0;
  border-radius: 5px;
  padding: 12px;
  margin-top: 10px;
}
blockquote {
  margin: 0 0 8px 0;
  font-style: italic;
  color: #5e35b1;
}
.error {
  color: #c00;
  margin-top: 8px;
}
</style> 