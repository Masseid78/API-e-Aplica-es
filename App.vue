<template>
  <div>
    <div class="animated-bg"></div>
    <div class="container">
      <header>
        <h1>OmniAPI Navigator</h1>
        <p>Teste todos os endpoints da sua API de forma visual e moderna!</p>
      </header>
      <section class="grid">
        <RandomCard />
        <WeatherCard />
        <CryptoCard />
        <QuoteCard />
        <CpfCard />
        <CepCard />
        <EmailCard />
        <TimeCard />
        <IpCard />
        <TranslateCard />
        <CurrencyCard />
      </section>
      <footer>
        <p style="text-align:center; color:#888; margin-top:40px;"></p>
      </footer>
      <OmniAPINavigator />
    </div>
  </div>
</template>

<script setup>
import RandomCard from './components/RandomCard.vue';
import WeatherCard from './components/WeatherCard.vue';
import CryptoCard from './components/CryptoCard.vue';
import QuoteCard from './components/QuoteCard.vue';
import CpfCard from './components/CpfCard.vue';
import CepCard from './components/CepCard.vue';
import EmailCard from './components/EmailCard.vue';
import TimeCard from './components/TimeCard.vue';
import IpCard from './components/IpCard.vue';
import TranslateCard from './components/TranslateCard.vue';
import CurrencyCard from './components/CurrencyCard.vue';
import OmniAPINavigator from './components/Cursor.vue';
</script>

<style>
body {
  margin: 0;
  font-family: 'Segoe UI', Arial, sans-serif;
  background: transparent;
}
.animated-bg {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 0;
  pointer-events: none;
  background: linear-gradient(270deg, #9cf7f7, #50d2f3, #9900ff, #97e4ee, #00f7ff);
  background-size: 200% 200%;
  animation: moveBg 10s ease-in-out infinite;
  filter: blur(8px) brightness(1.08) saturate(1.2);
}
@keyframes moveBg {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}
.container {
  position: relative;
  z-index: 1;
  max-width: 1200px;
  margin: 0 auto;
  padding: 30px 10px;
}
header {
  text-align: center;
  margin-bottom: 30px;
}
header h1 {
  color: #333;
  margin-bottom: 10px;
}
header p {
  color: #666;
}
.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 20px;
}
.card {
  background: white;
  border-radius: 15px;
  padding: 25px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.07);
  transition: transform 0.3s ease;
}
.card:hover {
  transform: translateY(-5px);
}
.card h3 {
  color: #333;
  margin-bottom: 15px;
  font-size: 1.3rem;
}
.card p {
  color: #666;
  margin-bottom: 20px;
  line-height: 1.6;
}
.btn {
  background: linear-gradient(45deg, #667eea, #764ba2);
  color: white;
  padding: 12px 25px;
  border: none;
  border-radius: 25px;
  cursor: pointer;
  text-decoration: none;
  display: inline-block;
  transition: all 0.3s ease;
}
.btn:hover {
  transform: scale(1.05);
  box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}
.result {
  background: #e8f5e8;
  border: 1px solid #4CAF50;
  border-radius: 5px;
  padding: 15px;
  margin-top: 15px;
  word-break: break-all;
}
</style> 