<template>
  <div class="card">
    <h3> Criptomoedas</h3>
    <form @submit.prevent="fetchCrypto">
      <select v-model="currency">
        <option value="USD">USD</option>
        <option value="BRL">BRL</option>
        <option value="EUR">EUR</option>
      </select>
      <button class="btn" type="submit">Consultar</button>
    </form>
    <div v-if="result" class="result">
      <table>
        <thead>
          <tr><th>Moeda</th><th>Preço</th><th>Variação 24h</th></tr>
        </thead>
        <tbody>
          <tr v-for="(c, key) in result.prices" :key="key">
            <td>{{ c.name }} ({{ c.symbol }})</td>
            <td>{{ c.price }} {{ result.currency }}</td>
            <td :style="{color: c.change_24h >= 0 ? '#4CAF50' : '#c00'}">
              {{ c.change_24h }}%
            </td>
          </tr>
        </tbody>
      </table>
      <div style="font-size:0.9em; color:#888; margin-top:6px;">Atualizado: {{ result.last_updated }}</div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const currency = ref('USD');
const result = ref(null);
const error = ref('');

async function fetchCrypto() {
  error.value = '';
  result.value = null;
  try {
    const res = await fetch(`/api/crypto?currency=${currency.value}`);
    const data = await res.json();
    if (data.status === 'SUCCESS') {
      result.value = data.data;
    } else {
      error.value = data.message || 'Erro ao buscar criptomoedas';
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
select {
  padding: 4px 8px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 8px;
}
th, td {
  padding: 4px 8px;
  border-bottom: 1px solid #eee;
  text-align: left;
}
th {
  background: #f5f5f5;
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