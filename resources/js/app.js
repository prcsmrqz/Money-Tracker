import './bootstrap';
import Swal from 'sweetalert2';
import Alpine from 'alpinejs';
import { confirmDelete } from './confirm_delete';
import Chart from 'chart.js/auto';
import ChartDataLabels from 'chartjs-plugin-datalabels';
import AOS from "aos";
import "aos/dist/aos.css";

Chart.register(ChartDataLabels);

AOS.init({
  duration: 1500,
  once: false, 
});


window.Chart = Chart; 
window.Alpine = Alpine;
window.Swal = Swal;
window.confirmDelete = confirmDelete;


Alpine.start();


