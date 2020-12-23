import $ from 'jquery';

import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.css';

import '../css/style.css';

import '../components/daterangepicker/daterangepicker.js';
import '../components/daterangepicker/daterangepicker.css';

import App from './app';

$(function() {
    const app = new App({
        chartsContainer: 'charts',
        charts:          {
            ordersChartBlock:   {
                title:      'Customers vs Orders',
                parameter:  'customers',
            },
            revenuesChartBlock: {
                title:      'Revenues vs Orders',
                parameter:  'revenues',
            }
        },
    });

    $('#dateRangePicker').daterangepicker({
        opens:  'left',
        locale: {
            format: 'DD/MM/YYYY',
        },
    });

    app.bindPicker($('#dateRangePicker'));
    app.run();
})
;