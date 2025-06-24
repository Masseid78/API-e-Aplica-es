<template>
  <div class="card">
    <h3> Previsão do Tempo</h3>
    <form @submit.prevent="fetchWeather">
      <input v-model="city" type="text" placeholder="Cidade" required />
      <button class="btn" type="submit">Consultar</button>
    </form>
    <div v-if="result" class="result">
      <strong>{{ result.city }}</strong><br>
      Temperatura: {{ result.temperature }}°C<br>
      Condição: {{ result.condition }}<br>
      Umidade: {{ result.humidity }}%<br>
      Vento: {{ result.wind_speed }} km/h
      <div style="margin-top:8px;">
        <strong>Previsão:</strong>
        <ul>
          <li>Hoje: {{ result.forecast.today.temp }}°C, {{ result.forecast.today.condition }}</li>
          <li>Amanhã: {{ result.forecast.tomorrow.temp }}°C, {{ result.forecast.tomorrow.condition }}</li>
          <li>Depois: {{ result.forecast.day_after.temp }}°C, {{ result.forecast.day_after.condition }}</li>
        </ul>
      </div>
    </div>
    <div v-if="error" class="error">{{ error }}</div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
const city = ref('São Paulo');
const result = ref(null);
const error = ref('');

async function fetchWeather() {
  error.value = '';
  result.value = null;
  try {
    const res = await fetch(`/api/weather?city=${encodeURIComponent(city.value)}`);
    const data = await res.json();
    if (data.status === 'SUCCESS') {
      result.value = data.data;
    } else {
      error.value = data.message || 'Erro ao buscar previsão';
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
ul {
  margin: 0;
  padding-left: 18px;
}
</style> 