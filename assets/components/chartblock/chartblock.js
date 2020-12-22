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
                    <div class="loading-status">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span class="card-text pl-1">Loading chart data...</span>
                    </div>
                    <div class="loading-result d-none"></div>
                </div>
            </div>
        `;
        document.getElementById(options.container).appendChild(this.template.content);
    }
}