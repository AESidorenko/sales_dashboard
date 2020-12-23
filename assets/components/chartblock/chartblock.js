import Chart from 'chart.js';

export default class ChartBlock
{
    constructor(options)
    {
        this.chart              = null;
        this.options            = options;
        this.template           = document.createElement('template');
        this.template.innerHTML = `
            <div id="${options.id}" class="card mb-3">
                <div class="card-header">${options.title}</div>
                <div class="card-body">
                    <div id="${options.id}-in-progress" class="loading-status">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="card-text pl-1">Loading chart data...</span>
                    </div>
                    <div id="${options.id}-error" class="loading-error d-none">Error getting data</div>
                    <div id="${options.id}-chart-container" class="chart d-none">
                        <canvas id="${options.id}-chart"></canvas>
                    </div>
                </div>
            </div>
        `;
        document.getElementById(options.container).appendChild(this.template.content);
    }

    showData(data, startDate, endDate)
    {
        const ctx = document.getElementById(`${this.options.id}-chart`).getContext('2d');

        this.chart ? this.chart.destroy() : true;
        this.chart = new Chart(ctx, {
            type:    'bar',
            data:    {
                datasets: [
                    {
                        label:           data.labels[0],
                        data:            data.datasets[0],
                        yAxisID:         'A',
                        borderColor:     'rgb(15,24,62)',
                        backgroundColor: 'rgba(65, 92, 200, 0.75)',
                        order:           1,
                    },
                    {
                        label:       data.labels[1],
                        data:        data.datasets[1],
                        yAxisID:     'B',
                        type:        'line',
                        fill:        false,
                        borderColor: 'rgba(182, 53, 76, 1)',
                        order:       2,
                    },
                ],
            },
            options: {
                aspectRatio: 4,
                scales:      {
                    xAxes: [
                        {
                            type:  'time',
                            time:  {
                                unit: 'day',
                            },
                            ticks: {
                                min: startDate,
                                max: endDate,
                            },
                        },
                    ],
                    yAxes: [
                        {
                            id:       'A',
                            type:     'linear',
                            position: 'left',
                            ticks:    {
                                min: 0,
                            },
                        },
                        {
                            id:        'B',
                            type:      'linear',
                            position:  'right',
                            ticks:     {
                                min: 0,
                            },
                            gridLines: {
                                display: false,
                            },
                        },
                    ],
                },
            },
        });

        $(`#${this.options.id}-in-progress`).addClass('d-none');
        $(`#${this.options.id}-error`).addClass('d-none');
        $(`#${this.options.id}-chart-container`).removeClass('d-none');
    }

    showError()
    {
        $(`#${this.options.id}-in-progress`).addClass('d-none');
        $(`#${this.options.id}-error`).removeClass('d-none');
        $(`#${this.options.id}-chart-container`).addClass('d-none');
    }
}