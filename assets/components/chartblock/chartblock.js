import Chart from 'chart.js';

export default class ChartBlock
{
    constructor(options)
    {
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
                    <div id="${options.id}-error" class="loading-error d-none"></div>
                    <div id="${options.id}-chart-container" class="chart d-none">
                        <canvas id="${options.id}-chart"></canvas>
                    </div>
                </div>
            </div>
        `;
        document.getElementById(options.container).appendChild(this.template.content);
    }

    showData(dataPoints)
    {
        console.log(this.options.id, dataPoints);

        const ctx = document.getElementById(`${this.options.id}-chart`).getContext('2d');

        const chart = new Chart(ctx, {
            type:    'bar',
            data:    {
                datasets: [
                    {
                        data:     dataPoints,
                        spanGaps: false,
                    },
                ],
            },
            options: {
                aspectRatio: 4,
                scales:      {
                    xAxes: [
                        {
                            type: 'time',
                            time: {
                                unit: 'day',
                            },
                        }],
                },
            },
        });

        $(`#${this.options.id}-in-progress`).addClass('d-none');
        $(`#${this.options.id}-error`).addClass('d-none');
        $(`#${this.options.id}-chart-container`).removeClass('d-none');
    }
}