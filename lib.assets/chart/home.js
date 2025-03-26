Chart.defaults.pointHitDetectionRadius = 1;
Chart.defaults.plugins.tooltip.enabled = false;
Chart.defaults.plugins.tooltip.mode = 'index';
Chart.defaults.plugins.tooltip.position = 'nearest';
Chart.defaults.plugins.tooltip.external = coreui.ChartJS.customTooltips;
Chart.defaults.defaultFontColor = coreui.Utils.getStyle('--cui-body-color');
document.documentElement.addEventListener('ColorSchemeChange', () => {
    cardChart1.data.datasets[0].pointBackgroundColor = coreui.Utils.getStyle('--cui-primary');
    cardChart2.data.datasets[0].pointBackgroundColor = coreui.Utils.getStyle('--cui-info');
    mainChart.options.scales.x.grid.color = coreui.Utils.getStyle('--cui-border-color-translucent');
    mainChart.options.scales.x.ticks.color = coreui.Utils.getStyle('--cui-body-color');
    mainChart.options.scales.y.border.color = coreui.Utils.getStyle('--cui-border-color-translucent');
    mainChart.options.scales.y.grid.color = coreui.Utils.getStyle('--cui-border-color-translucent');
    mainChart.options.scales.y.ticks.color = coreui.Utils.getStyle('--cui-body-color');
    cardChart1.update();
    cardChart2.update();
    mainChart.update();
});
const random = (min, max) =>
    Math.floor(Math.random() * (max - min + 1) + min);


document.addEventListener('DOMContentLoaded', function () {
    Chart.register({
        id: 'moment',
        beforeInit: function (chart) {
            chart.data.labels = chart.data.labels.map(function (label) {
                return moment(label).format('YYYY-MM-DD HH:mm:ss');
            });
        }
    });
    document.querySelector('#proyek_id').addEventListener('change', function (e) {
        if (e.target.value != '') {
            fetch(urlBoq + '?proyek_id=' + e.target.value, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    createChart(data);
                    document.querySelector('#min-date').innerHTML = data.minDate;
                    document.querySelector('#max-date').innerHTML = data.maxDate;
                })
                .catch(error => {
                    console.error('There has been a problem with your fetch operation:', error);
                });
        }
    });
    let proyeks = [];
    $('.progres-proyek').each(function () {
        proyeks.push('proyeks[]=' + $(this).attr('data-proyek-id'));
    });

    fetch(urlProgres + '?' + proyeks.join('&'), {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            let cardChart = {};
            for (let i in data) {
                let config2 = data[i];
                config2.options.plugins.tooltip = {
                    callbacks: {
                        label: function (tooltipItem) {
                            let label = tooltipItem.dataset.label;
                            let value = tooltipItem.parsed.y.toFixed(2);
                            return ` ${label}: ${value}%`;
                        }
                    }
                };

                if (chart) {
                    chart.destroy();
                }
                cardChart[i] = new Chart(document.getElementById('card-chart-' + i), config2);
            }
        })
        .catch(error => {
            console.error('There has been a problem with your fetch operation:', error);
        });
});

var chart;
var ctx;
function createChart(config) {
    ctx = document.getElementById('main-chart2').getContext('2d');

    config.options.plugins.tooltip = {
        callbacks: {
            label: function (tooltipItem) {
                let label = tooltipItem.dataset.label;
                let value = tooltipItem.raw.y.toFixed(2);
                return ` ${label}: ${value}%`;
            }
        }
    };

    config.options.plugins.legend = {
        labels: {
            generateLabels: function (chart) {
                const labels = Chart.defaults.plugins.legend.labels.generateLabels(chart);
                labels.forEach(label => {
                    if (label.text.length > 12) {
                        label.text = label.text.substring(0, 12) + '...';
                    }
                });
                return labels;
            },
            boxWidth: 10,
            boxHeight: 8
        }
    };


    if (chart) {
        chart.destroy();
    }
    chart = new Chart(ctx, config);
}
