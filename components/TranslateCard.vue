<template>
  <div class="card">
    <h3> Tradução</h3>
    <form @submit.prevent="fetchTranslate">
      <input v-model="text" type="text" placeholder="Texto para traduzir" required />
      <select v-model="from">
        <option value="auto">Detectar</option>
        <option value="pt">Português</option>
        <option value="en">Inglês</option>
        <option value="es">Espanhol</option>
      </select>
      <select v-model="to">
        <option value="en">Inglês</option>
        <option value="pt">Português</option>
        <option value="es">Espanhol</option>
      </select>
      <button class="btn" type="submit">Traduzir</button>
    </form>
    <div v-if="result" class="result">
      <div><strong>Original:</strong> {{ result.original }}</div>
      <div><strong>Traduzido:</strong> {{ result.translated }}</div>
      <div><strong>De:</strong> {{ result.from }} <strong>Para:</strong> {{ result.to }}</div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const text = ref('Hello world');
const from = ref('auto');
const to = ref('pt');
const result = ref(null);
const error = ref('');

async function fetchTranslate() {
  error.value = '';
  result.value = null;
  try {
    const res = await fetch(`/api/translate?text=${encodeURIComponent(text.value)}&from=${from.value}&to=${to.value}`);
    const data = await res.json();
    if (data.status === 'SUCCESS') {
      result.value = data.data;
    } else {
      error.value = data.message || 'Erro ao traduzir';
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
input, select {
  padding: 4px 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
input {
  flex: 1;
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