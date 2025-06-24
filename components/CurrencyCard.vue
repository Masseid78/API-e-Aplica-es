<template>
  <div class="card">
    <h3> Conversão de Moeda</h3>
    <form @submit.prevent="fetchCurrency">
      <input v-model.number="amount" type="number" min="0.01" step="0.01" placeholder="Valor" required />
      <select v-model="from">
        <option value="USD">USD</option>
        <option value="BRL">BRL</option>
        <option value="EUR">EUR</option>
      </select>
      <span style="align-self:center;">→</span>
      <select v-model="to">
        <option value="BRL">BRL</option>
        <option value="USD">USD</option>
        <option value="EUR">EUR</option>
      </select>
      <button class="btn" type="submit">Converter</button>
    </form>
    <div v-if="result" class="result">
      <div><strong>{{ result.from.amount }} {{ result.from.currency }}</strong> = <strong>{{ result.to.amount }} {{ result.to.currency }}</strong></div>
      <div><strong>Taxa:</strong> {{ result.rate }}</div>
      <div><strong>Data:</strong> {{ result.date }}</div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const amount = ref(1);
const from = ref('USD');
const to = ref('BRL');
const result = ref(null);
const error = ref('');

async function fetchCurrency() {
  error.value = '';
  result.value = null;
  try {
    const res = await fetch(`/api/currency?amount=${amount.value}&from=${from.value}&to=${to.value}`);
    const data = await res.json();
    if (data.status === 'SUCCESS') {
      result.value = data.data;
    } else {
      error.value = data.message || 'Erro ao converter moeda';
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
  align-items: center;
}
input, select {
  padding: 4px 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
input {
  width: 80px;
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