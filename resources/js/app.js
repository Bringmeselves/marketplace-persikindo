import './bootstrap';
import Alpine from 'alpinejs';
import { createIcons } from 'lucide';

// Pasang Alpine ke global
window.Alpine = Alpine;
Alpine.start();

// Jalankan Lucide icons setelah DOM siap
document.addEventListener('DOMContentLoaded', () => {
    createIcons();
});
