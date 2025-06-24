<template>
  <div class="omni-api-navigator">
    <div class="cursor-dot" :style="{ left: x + 'px', top: y + 'px' }"></div>
    <div class="cursor-halo" :style="{ left: x + 'px', top: y + 'px' }"></div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const x = ref(0);
const y = ref(0);

function updateCursor(e) {
  x.value = e.clientX;
  y.value = e.clientY;
}

onMounted(() => {
  document.addEventListener('mousemove', updateCursor);
});

onUnmounted(() => {
  document.removeEventListener('mousemove', updateCursor);
});
</script>

<style scoped>
.omni-api-navigator {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 9999;
}

.cursor-dot {
  position: absolute;
  width: 8px;
  height: 8px;
  background: #667eea;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  transition: all 0.1s ease;
}

.cursor-halo {
  position: absolute;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  transform: translate(-50%, -50%);
  animation: rgbHalo 2.5s linear infinite;
  filter: blur(2px);
}

@keyframes rgbHalo {
  0% {
    background: radial-gradient(circle, rgba(255,0,102,0.3) 0%, rgba(0,255,204,0.2) 50%, transparent 70%);
  }
  25% {
    background: radial-gradient(circle, rgba(0,255,204,0.3) 0%, rgba(0,102,255,0.2) 50%, transparent 70%);
  }
  50% {
    background: radial-gradient(circle, rgba(0,102,255,0.3) 0%, rgba(255,255,0,0.2) 50%, transparent 70%);
  }
  75% {
    background: radial-gradient(circle, rgba(255,255,0,0.3) 0%, rgba(255,0,102,0.2) 50%, transparent 70%);
  }
  100% {
    background: radial-gradient(circle, rgba(255,0,102,0.3) 0%, rgba(0,255,204,0.2) 50%, transparent 70%);
  }
}
</style> 