import $ from 'jquery';

import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.css';

import '../css/style.css';

import '../components/daterangepicker/daterangepicker.js';
import '../components/daterangepicker/daterangepicker.css';

$(function() {
    $('input[name="daterange"]').daterangepicker({
        opens: 'left',
    }, function(start, end, label) {
        console.log('A new date selection was made: ' + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
    });
});