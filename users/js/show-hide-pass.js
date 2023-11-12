const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');
togglePassword.addEventListener('click', () => {
    if (password.type === 'password') {
        password.type = 'text';
        togglePassword.innerHTML = '<i class="bi bi-eye"></i>';
    } else {
        password.type = 'password';
        togglePassword.innerHTML = '<i class="bi bi-eye-slash"></i>';
    }
});

const toggleCPassword = document.querySelector('#toggleCPassword');
const cpassword = document.querySelector('#cpassword');
toggleCPassword.addEventListener('click', () => {
    if (cpassword.type === 'password') {
        cpassword.type = 'text';
        toggleCPassword.innerHTML = '<i class="bi bi-eye"></i>';
    } else {
        cpassword.type = 'password';
        toggleCPassword.innerHTML = '<i class="bi bi-eye-slash"></i>';
    }
});
