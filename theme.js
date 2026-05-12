const root = document.documentElement;
const btn  = document.getElementById('theme-toggle');

const saved  = localStorage.getItem('theme');
const system = window.matchMedia('(prefers-color-scheme: dark)').matches
               ? 'dark' : 'light';
const theme  = saved ?? system;

root.setAttribute('data-theme', theme);
btn.textContent = theme === 'dark' ? '☀️' : '🌙';

btn.addEventListener('click', () => {
  const current = root.getAttribute('data-theme');
  const next = current === 'dark' ? 'light' : 'dark';
  root.setAttribute('data-theme', next);
  localStorage.setItem('theme', next);
  btn.textContent = next === 'dark' ? '☀️' : '🌙';
});
