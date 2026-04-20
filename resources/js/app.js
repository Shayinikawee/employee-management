import './bootstrap';

// Import Chart.js
import Chart from 'chart.js/auto';
window.Chart = Chart;

// CSRF Token Setup for AJAX
const token = document.querySelector('meta[name="csrf-token"]');
if (token) {
    window.csrfToken = token.getAttribute('content');
}

// Auto-dismiss flash messages after 5 seconds
document.addEventListener('DOMContentLoaded', function () {
    setTimeout(() => {
        const flashSuccess = document.getElementById('flash-success');
        const flashError = document.getElementById('flash-error');
        if (flashSuccess) flashSuccess.style.display = 'none';
        if (flashError) flashError.style.display = 'none';
    }, 5000);
});
