import './bootstrap';
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';
import { confirmDelete } from './confirm_delete';
import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';


Chart.register(ChartDataLabels);

window.Chart = Chart; 
window.Alpine = Alpine;
window.Swal = Swal;
window.confirmDelete = confirmDelete;


Alpine.start();


