import './bootstrap';
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';
import { confirmDelete } from './confirm_delete';
import Chart from 'chart.js/auto';
window.Chart = Chart; 
window.Alpine = Alpine;
window.Swal = Swal;
window.confirmDelete = confirmDelete;

Alpine.start();


