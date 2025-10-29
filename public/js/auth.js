const body = document.querySelector("body");
const modal = document.querySelector(".modal");
const modalButton = document.querySelector(".modal-button");
const closeButton = document.querySelector(".close-button");
const scrollDown = document.querySelector(".scroll-down");
let isOpened = false;

const openModal = () => {
    modal.classList.add("is-open");
    body.style.overflow = "hidden";
};

const closeModal = () => {
    modal.classList.remove("is-open");
    body.style.overflow = "initial";
};

// Abrir modal al hacer scroll
window.addEventListener("scroll", () => {
    if (window.scrollY > window.innerHeight / 3 && !isOpened) {
        isOpened = true;
        scrollDown.style.display = "none";
        openModal();
    }
});

// Abrir modal al hacer clic en el botón
modalButton.addEventListener("click", openModal);

// Cerrar modal al hacer clic en el botón de cerrar
closeButton.addEventListener("click", closeModal);

// Cerrar modal con la tecla ESC
document.onkeydown = evt => {
    evt = evt || window.event;
    evt.keyCode === 27 ? closeModal() : false;
};

// Si hay errores en el formulario, abrir el modal automáticamente
window.addEventListener('load', () => {
    const hasErrors = document.querySelector('.alert.error');
    const hasSuccess = document.querySelector('.alert.success');
    
    if (hasErrors || hasSuccess) {
        isOpened = true;
        scrollDown.style.display = "none";
        openModal();
    }
});