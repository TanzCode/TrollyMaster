document.addEventListener("DOMContentLoaded", function() {
    const toggle = document.querySelector('.header_toggle');
    const navbar = document.querySelector('.l-navbar');
    const bodyPd = document.querySelector('body'); // To adjust body padding when nav is shown/hidden
  
    // Toggle the 'show' class to expand/collapse the navbar
    toggle.addEventListener('click', () => {
      navbar.classList.toggle('show');
      bodyPd.classList.toggle('body-pd'); // Adjust body padding when nav is visible/hidden
    });
  });
  