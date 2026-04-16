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
