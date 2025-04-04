let slideContainer = document.querySelector('.slides');
let autoSlide;

function nextSlide() {
    let items = document.querySelectorAll('.items');
    slideContainer.appendChild(items[0]); 
}

function previousSlide() {
    let items = document.querySelectorAll('.items');
    slideContainer.insertBefore(items[items.length - 1], items[0]);
}

function startAutoSlide() {
    autoSlide = setInterval(nextSlide, 5000);
}

function stopAutoSlide() {
    clearInterval(autoSlide);
}

document.addEventListener('keydown', (event) => {
    if (event.key === "ArrowRight") {
        stopAutoSlide();
        nextSlide();
        startAutoSlide();
    } else if (event.key === "ArrowLeft") {
        stopAutoSlide();
        previousSlide();
        startAutoSlide();
    }
});

let touchStartX = 0;
let touchEndX = 0;

slideContainer.addEventListener('touchstart', (event) => {
    touchStartX = event.changedTouches[0].screenX;
});

slideContainer.addEventListener('touchend', (event) => {
    touchEndX = event.changedTouches[0].screenX;
    if (touchStartX > touchEndX + 50) { 
        stopAutoSlide();
        nextSlide();
        startAutoSlide();
    } else if (touchStartX < touchEndX - 50) {
        stopAutoSlide();
        previousSlide();
        startAutoSlide();
    }
});

startAutoSlide();