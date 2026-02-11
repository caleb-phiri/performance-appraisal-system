import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
import '../css/app.css';
// Your JavaScript code here
console.log('MOIC Performance Appraisal System loaded');

// You can also move the mobile menu script here
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileUserMenu');
    const userDropdown = document.getElementById('userDropdown');
    
    if (mobileMenuBtn && userDropdown) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
            userDropdown.classList.toggle('mobile-menu-enter');
        });
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }
    
    // Auto-hide success/error messages
    setTimeout(() => {
        const successMsg = document.getElementById('successMessage');
        if (successMsg) successMsg.style.display = 'none';
    }, 5000);
    
    setTimeout(() => {
        const errorMsg = document.getElementById('errorMessage');
        if (errorMsg) errorMsg.style.display = 'none';
    }, 8000);
});

// Mobile menu functionality
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuBtn = document.getElementById('mobileUserMenu');
    const userDropdown = document.getElementById('userDropdown');
    
    if (mobileMenuBtn && userDropdown) {
        mobileMenuBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('hidden');
            userDropdown.classList.toggle('mobile-menu-enter');
        });
        
        document.addEventListener('click', function(e) {
            if (!mobileMenuBtn.contains(e.target) && !userDropdown.contains(e.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    }
});