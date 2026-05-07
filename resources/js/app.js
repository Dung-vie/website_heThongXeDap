import "./bootstrap";

const blinkText = document.querySelectorAll(".blink-text");
const observer = new IntersectionObserver((entries) => {
    entries.forEach((e) => {
        if (e.isIntersecting) {
            e.target.classList.add("blink-text-animation");
            return;
        }
        e.target.classList.remove("blink-text-animation");
    });
});

blinkText.forEach((element) => {
    let characters = element.textContent.split("");

    characters = characters.map((e, i) => {
        return `<span style="animation-delay: ${0.05 * i}s;">${e}</span>`;
    });

    element.innerHTML = characters.join("");

    observer.observe(element);
});

document.addEventListener('DOMContentLoaded', () => {
    const name = document.getElementById('name');
    const phone = document.getElementById('phone');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const passwordConfirmation = document.getElementById('password_confirmation');

    const nameRegex =
        /^(?!.*\s{2,})[A-Za-zÀ-ỹ]+(?:\s[A-Za-zÀ-ỹ]+)*$/u;

    const phoneRegex =
        /^(0\d{9}|84\d{9}|\+84\d{9})$/;

    const emailRegex =
        /^(?!\.)(?!.*\.\.)[a-z0-9.]{6,30}(?<!\.)@gmail\.com$/;

    const passwordRegex =
        /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{8,}$/;

    function createError(input) {
        let error = input.parentElement.querySelector('.custom-error');
        if (!error) {
            error = document.createElement('div');
            error.className = 'text-danger small mt-1 custom-error';
            input.parentElement.appendChild(error);
        }
        return error;
    }

    function showError(input, message) {
        input.classList.add('is-invalid');
        const error = createError(input);
        error.innerText = message;
    }

    function clearError(input) {
        input.classList.remove('is-invalid');
        const error = input.parentElement.querySelector('.custom-error');
        if (error) {
            error.innerText = '';
        }
    }

    name.addEventListener('input', () => {

        if (name.value.trim().length < 3) {
            showError(name, 'Họ tên tối thiểu 3 ký tự');
            return;
        }
        if (!nameRegex.test(name.value.trim())) {
            showError(name, 'Họ tên không hợp lệ');
            return;
        }
        clearError(name);
    });

    phone.addEventListener('input', () => {
        if (!phoneRegex.test(phone.value.trim())) {
            showError(
                phone,
                'SĐT phải có dạng 0xxxxxxxxx, 84xxxxxxxxx hoặc +84xxxxxxxxx'
            );
            return;
        }
        clearError(phone);
    });

    email.addEventListener('input', () => {
        if (!emailRegex.test(email.value.trim())) {
            showError(email, 'Email gmail không hợp lệ');
            return;
        }
        clearError(email);
    });

    password.addEventListener('input', () => {
        if (!passwordRegex.test(password.value)) {
            showError(
                password,
                'Mật khẩu >=8 ký tự, gồm hoa, thường, số và ký tự đặc biệt'
            );
            return;
        }
        clearError(password);
    });

    passwordConfirmation.addEventListener('input', () => {
        if (passwordConfirmation.value !== password.value) {
            showError(
                passwordConfirmation,
                'Mật khẩu nhập lại không khớp'
            );
            return;
        }
        clearError(passwordConfirmation);
    });
});
