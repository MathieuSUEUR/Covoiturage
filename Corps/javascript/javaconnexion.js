document.addEventListener('DOMContentLoaded', function () {
  const imageVue = document.getElementById('imagevue');
  const passwordInput = document.getElementById('password');

  
  imageVue.addEventListener('click', function () {
    const type = passwordInput.type === 'password';
    passwordInput.type = type ? 'text' : 'password';
  });
});