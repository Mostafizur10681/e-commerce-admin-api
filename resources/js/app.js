import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

// Global theme helper
window.toggleDarkMode = function (theme) {
  if (theme === 'dark' || (theme === 'system' && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
    document.documentElement.classList.add('dark');
  } else {
    document.documentElement.classList.remove('dark');
  }
  localStorage.setItem('theme', theme);
};

// Auto apply theme on load
const savedTheme = localStorage.getItem('theme') || 'light';
window.toggleDarkMode(savedTheme);

Alpine.start();
