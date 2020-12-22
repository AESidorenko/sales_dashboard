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
            ordersChartBlock:    {
                title:     'Total number of orders',
                parameter: 'orders',
            },
            revenuesChartBlock:  {
                title:     'Total number of revenue',
                parameter: 'revenues',
            },
            customersChartBlock: {
                title:     'Total number of customers',
                parameter: 'customers',
            },
        },
    });

    $('#dateRangePicker').daterangepicker({
        opens: 'left',
    });

    app.bindPicker($('#dateRangePicker'));
    app.run();
})
;