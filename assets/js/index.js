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
                title:     'Orders',
                parameter: 'orders',
            },
            revenuesChartBlock:  {
                title:     'Revenues',
                parameter: 'revenues',
            },
            customersChartBlock: {
                title:     'Customers',
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