// script.js

// DOM Elements
const navLinks = document.querySelectorAll('nav a');
const sections = document.querySelectorAll('section');
const contactForm = document.getElementById('contactForm');
const galleryImages = document.querySelectorAll('.gallery img');

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    // Set active section on page load
    setActiveSection('home');
    
    // Check for contact form success message
    if (window.location.hash === '#contact' && window.location.search.includes('contact=success')) {
        alert('Thank you for your message! I will get back to you soon.');
    }
    
    // Add event listeners
    setupNavigation();
    setupGallery();
    if (contactForm) {
        contactForm.addEventListener('submit', handleContactSubmit);
    }
});

// Navigation functionality
function setupNavigation() {
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const sectionId = this.getAttribute('href').substring(1);
            setActiveSection(sectionId);
            
            // Smooth scroll to section
            document.getElementById(sectionId).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
}

function setActiveSection(sectionId) {
    // Hide all sections
    sections.forEach(section => {
        section.style.display = 'none';
    });
    
    // Show the selected section
    const activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.style.display = 'block';
    }
    
    // Update active nav link
    navLinks.forEach(link => {
        link.classList.remove('active');
        if (link.getAttribute('href') === `#${sectionId}`) {
            link.classList.add('active');
        }
    });
}

// Gallery functionality
function setupGallery() {
    galleryImages.forEach(img => {
        // Add click event for potential lightbox functionality
        img.addEventListener('click', function() {
            // For future implementation: open image in lightbox
            console.log('Image clicked:', this.src);
        });

        // Lazy loading for better performance
        img.setAttribute('loading', 'lazy');
    });
}

// Contact form functionality
function handleContactSubmit(e) {
    e.preventDefault();
    
    // Get form values
    const formData = {
        name: this.querySelector('#name').value,
        email: this.querySelector('#email').value,
        subject: this.querySelector('#subject').value || 'No Subject',
        message: this.querySelector('#message').value
    };
    
    // Simple validation
    if (!formData.name || !formData.email || !formData.message) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Submit the form (will be handled by PHP)
    this.submit();
}

// Additional utility functions
function isElementInViewport(el) {
    const rect = el.getBoundingClientRect();
    return (
        rect.top >= 0 &&
        rect.left >= 0 &&
        rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
        rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
}

// Add active class to nav links when scrolling
window.addEventListener('scroll', function() {
    sections.forEach(section => {
        if (isElementInViewport(section)) {
            const id = section.getAttribute('id');
            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === `#${id}`) {
                    link.classList.add('active');
                }
            });
        }
    });
});