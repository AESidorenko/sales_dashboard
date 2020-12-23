import $ from 'jquery';
import ChartBlock from '../components/chartblock/chartblock';

export default class App
{
    #currentStartDate   = null;
    #currentEndDate     = null;
    #allowedChartParams = ['orders', 'revenues', 'customers'];
    #collectionUrlMap   = {
        'revenues':  '/api/v1/statistics/revenues',
        'customers': '/api/v1/statistics/customers',
    };

    constructor(options)
    {
        this.chartsContainer = $(`#${options.chartsContainer}`);
        this.charts          = [];

        for (const chart in options.charts) {
            this.charts.push({
                parameter: options.charts[chart].parameter,
                chart:     new ChartBlock({
                    id:        chart,
                    title:     options.charts[chart].title,
                    container: options.chartsContainer,
                }),
            });
        }
    }

    bindPicker(picker)
    {
        this.picker = picker;
        this.picker.on('apply.daterangepicker', this.dateRangeSelected.bind(this));
    }

    run()
    {
        this.picker.trigger('apply.daterangepicker');
    }

    dateRangeSelected()
    {
        const startDate = this.picker.data('daterangepicker').startDate.format('YYYY-MM-DD'),
              endDate   = this.picker.data('daterangepicker').endDate.format('YYYY-MM-DD');

        if (startDate === this.#currentStartDate && endDate === this.#currentEndDate) {
            return;
        }

        this.charts.forEach(function(element) {
            const url = this.apiMapCollectionToUrl(element.parameter);
            if (url === null) {
                return;
            }

            fetch(`${url}?startDate=${startDate}&endDate=${endDate}`, {
                method:  'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                },
            })
                .then(response => response.json())
                .then(function(data) {
                    element.chart.showData(data, this.picker.data('daterangepicker').startDate, this.picker.data('daterangepicker').endDate);
                }.bind(this))
                .catch(function() {
                    element.chart.showError();
                }.bind(this))
            ;
        }, this);

        $('#total-customers').text(`Customers: -`);
        $('#total-orders').text(`Orders: -`);
        $('#total-revenue').text(`Revenue: -`);

        fetch(`/api/v1/statistics/summary?startDate=${startDate}&endDate=${endDate}`, {
            method:  'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        })
            .then(response => response.json())
            .then(function(data) {
                $('#total-customers').text(`Customers: ${data.summary.totalCustomers}`);
                $('#total-orders').text(`Orders: ${data.summary.totalOrders}`);
                $('#total-revenue').text(`Revenue: ${data.summary.totalRevenue}`);
            }.bind(this));
    }

    apiMapCollectionToUrl(collectionName)
    {
        if (!this.#allowedChartParams.includes(collectionName) || !this.#collectionUrlMap.hasOwnProperty(collectionName)) {
            return null;
        }

        return this.#collectionUrlMap[collectionName];
    }
}