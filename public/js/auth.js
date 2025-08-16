// public/js/auth.js

// Modulo de autenticación simple usando localStorage
window.Auth = {
  get token() { return localStorage.getItem('token'); },
  set token(t) { if (t) localStorage.setItem('token', t); },
  clear() { localStorage.removeItem('token'); },
  isAuth() { return !!localStorage.getItem('token'); },
};
 
// Manejo de la interfaz de usuario
document.addEventListener('DOMContentLoaded', () => {
  const needAuth  = document.querySelectorAll('[data-auth="required"]');
  const needGuest = document.querySelectorAll('[data-auth="guest"]');

  if (Auth.isAuth()) {
    needAuth.forEach(el => el.classList.remove('hidden'));
    needGuest.forEach(el => el.classList.add('hidden'));
  } else {
    needAuth.forEach(el => el.classList.add('hidden'));
    needGuest.forEach(el => el.classList.remove('hidden'));
  }

  // Cerrar sesión
  const logoutBtn = document.querySelector('[data-action="logout"]');
  if (logoutBtn) {
    logoutBtn.addEventListener('click', (e) => {
      e.preventDefault();
      Auth.clear();
      window.location.href = '/login';
    });
  }

  // Redirección automática si intenta abrir páginas protegidas
  const protectedPrefixes = ['/proyectos-tech-solutions'];
  const mustProtect = protectedPrefixes.some(p => location.pathname.startsWith(p));
  if (mustProtect && !Auth.isAuth()) {
    window.location.replace('/login');
  }
});
