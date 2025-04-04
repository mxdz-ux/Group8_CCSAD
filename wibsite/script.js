let currentIndex = 0;
const slides = document.querySelectorAll('.slider-item');
const dots = document.querySelectorAll('.dot');

function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.style.display = i === index ? 'block' : 'none';
    });


    dots.forEach((dot, i) => {
        dot.classList.toggle('active', i === index);
    });
}


function nextSlide() {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
}


function prevSlide() {
    currentIndex = (currentIndex - 1 + slides.length) % slides.length;
    showSlide(currentIndex);
}

showSlide(currentIndex);

setInterval(nextSlide, 5000);

    const hamburger = document.querySelector('.Hamburger');
    const navLinks = document.querySelector('.nav-links');

    hamburger.addEventListener('click', () => {
        navLinks.classList.toggle('show');
    });

    gsap.utils.toArray(".book").forEach((book, index) => {
        gsap.fromTo(book, 
            { opacity: 0, y: 50 }, 
            { opacity: 1, y: 0, duration: 0.6, delay: index * 0.1, ease: "power2.out",
              scrollTrigger: {
                  trigger: book,
                  start: "top 80%",
                  toggleActions: "play reverse play reverse"
              }
            }
        );
    });
    
    document.addEventListener("DOMContentLoaded", function() {
        gsap.utils.toArray(".news-card").forEach((card, index) => {
            gsap.fromTo(card, 
                { opacity: 0, y: 50 }, 
                { opacity: 1, y: 0, duration: 0.6, delay: index * 0.1, ease: "power2.out",
                  scrollTrigger: {
                      trigger: card,
                      start: "top 80%",
                      toggleActions: "play reverse play reverse"
                  }
                }
            );
        });
    });
    
    document.querySelector('.Hamburger').addEventListener('click', function() {
        document.querySelector('.nav-links').classList.toggle('show');
    });

    // Add to your page JavaScript
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        console.log("Submitting book ID:", 
            this.querySelector('[name="book_id"]').value);
    });
});