import './bootstrap';
import '../css/app.css';

// Handle filter buttons
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Toggle active state
            if (this.classList.contains('inactive')) {
                this.classList.remove('inactive');
                this.classList.add('active');
            } else {
                this.classList.remove('active');
                this.classList.add('inactive');
            }
        });
    });
});
