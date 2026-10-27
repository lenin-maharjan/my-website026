const loginForm = document.getElementById('loginForm');
const usernameInput = document.getElementById('username');
const rememberedUsername = document.cookie
    .split('; ')
    .find((cookie) => cookie.startsWith('remember_username='));

if (rememberedUsername && usernameInput) {
    usernameInput.value = decodeURIComponent(rememberedUsername.split('=')[1]);
}

loginForm.addEventListener('submit', (event) => {
    const username = document.getElementById('username').value.trim();
    const password = document.getElementById('password').value;

    const captcha = document.getElementById('captcha').value;
    if (username === '' || password === '' || captcha === '') {
        event.preventDefault();
        alert('Please fill in both fields.');
    }
});
