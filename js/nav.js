const navbar2 = document.querySelector('.navbar2');

// Show navbar on hover
navbar2.addEventListener('mouseenter', () => {
    navbar2.classList.add('active'); // Slide down
});

// Hide navbar on mouse leave
navbar2.addEventListener('mouseleave', () => {
    navbar2.classList.remove('active'); // Slide back up
});
