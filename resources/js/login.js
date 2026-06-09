// switch tab Login Register
function switchTab(tab) {
    const formLogin    = document.getElementById('form-login');
    const formRegister = document.getElementById('form-register');
    const tabLogin     = document.getElementById('tab-login');
    const tabRegister  = document.getElementById('tab-register');

    if (tab === 'login') {
        formLogin.style.display    = 'flex';
        formRegister.style.display = 'none';
        tabLogin.classList.add('active');
        tabRegister.classList.remove('active');
    } else {
        formLogin.style.display    = 'none';
        formRegister.style.display = 'flex';
        tabRegister.classList.add('active');
        tabLogin.classList.remove('active');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    if (params.get('tab') === 'register') {
        switchTab('register');
    }
});