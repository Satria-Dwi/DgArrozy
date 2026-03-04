require('./bootstrap');
import Alpine from 'alpinejs'
import collapse from '@alpinejs/collapse'  // ← import plugin

Alpine.plugin(collapse)  // ← daftarkan plugin
window.Alpine = Alpine
Alpine.start()
