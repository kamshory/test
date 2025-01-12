var labels = [];
var data = [];

Chart.defaults.pointHitDetectionRadius = 1;
Chart.defaults.plugins.tooltip.enabled = false;
Chart.defaults.plugins.tooltip.mode = 'index';
Chart.defaults.plugins.tooltip.position = 'nearest';
Chart.defaults.plugins.tooltip.external = coreui.ChartJS.customTooltips;
Chart.defaults.defaultFontColor = 'rgba(75, 192, 192, 1)';

let chartCanvas;
let ctx;
let minDate;
let maxDate;
let sChart;
let draggingPoint = null;
let editorLocked = false;
let snapY = 0.25;
let chartConfig = {};
let elemMinDate = null;
let elemMaxDate = null;
let clbkFunc = function(){
};

function initChart(elem, elemStart, elemEnd, enableEdit, clbk) {
    clbkFunc = clbk;
    elemMinDate = elemStart;
    elemMaxDate = elemEnd;
    chartCanvas = document.querySelector(elem, elemStart, elemEnd);
    ctx = chartCanvas.getContext('2d');
    let v1 = document.querySelector(elemStart).value;
    let v2 = document.querySelector(elemEnd).value;

    if (v1 == '') {
        v1 = convertTimestampToDate((new Date()).getTime());
        document.querySelector(elemStart).value = v1;
        setTimeout(function () {
            document.querySelector(elemStart).value = v1;
        }, 10
        );
    }

    if (v2 == '') {
        v2 = convertTimestampToDate((new Date()).getTime() + (29 * 86400000));
        setTimeout(function () {
            document.querySelector(elemEnd).value = v2;
        }, 10
        );
    }

    minDate = new Date(v1 + 'T00:00:00').getTime();
    maxDate = new Date(v2 + 'T23:59:59').getTime();


    if(enableEdit)
    {
        chartCanvas.addEventListener('click', function (event) {
            if (!editorLocked) {
                let rect = chartCanvas.getBoundingClientRect();
                let x = event.clientX - rect.left;
                let y = event.clientY - rect.top;
                let xScale = sChart.scales.x;
                let xValue = xScale.getValueForPixel(x);

                let lastLabel = labels.length > 0 ? labels[labels.length - 1] : 0;
                // hilangkan jam untuk keseragaman data
                xValue = fixHour(xValue);

                let yScale = sChart.scales.y;
                let yValue = yScale.getValueForPixel(y);

                if (xValue > lastLabel && yValue <= chartConfig.options.scales.y.max) {
                    yValue = snapValue(yValue, snapY);
                    labels.push(xValue);
                    data.push(yValue);
                    sChart.update();
                    clbkFunc(labels, data);
                }
            }
        });

        chartCanvas.addEventListener('contextmenu', function (event) {
            event.preventDefault();
            // Menghapus data terakhir
            if (labels.length > 0 && data.length > 0) {
                labels.pop();
                data.pop();
                sChart.update();
                clbkFunc(labels, data);
            }
        });

        chartCanvas.addEventListener('mousedown', function (event) {
            let points = sChart.getElementsAtEventForMode(event, 'nearest', { intersect: true }, false);
            if (points.length) {
                draggingPoint = points[0];
            }
        });

        chartCanvas.addEventListener('mousemove', function (event) {
            if (draggingPoint) {
                editorLocked = true;
                let yScale = sChart.scales.y;
                let canvasPosition = Chart.helpers.getRelativePosition(event, sChart);
                let yValue = yScale.getValueForPixel(canvasPosition.y);
                yValue = snapValue(yValue, snapY);
                // Update point value
                sChart.data.datasets[draggingPoint.datasetIndex].data[draggingPoint.index] = Math.max(0, Math.min(100, yValue));
                sChart.update();
                clbkFunc(labels, data);
            }
        });

        chartCanvas.addEventListener('mouseup', function () {
            draggingPoint = null;
            setTimeout(function () {
                editorLocked = false;
            }, 2);
        });

        chartCanvas.addEventListener('mouseout', function () {
            draggingPoint = null;
        });

        $(document).on('blur', elemMinDate, function(){
            let e = $(this);
            setTimeout(function () {
                let val = e.val();
                minDate = new Date(val + 'T00:00:00').getTime();
                createChart();
            }, 10);
        });
        $(document).on('blur', elemMaxDate, function(){
            let e = $(this);
            setTimeout(function () {
                let val = e.val();
                maxDate = new Date(val + 'T23:59:59').getTime();
                createChart();
            }, 10);
        });
    }

}


function createChart() {
    if (sChart) {
        sChart.destroy();
    }
    chartConfig = {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Rencana',
                data: data,
                borderColor: 'rgba(75, 192, 192, 1)',
                fill: false,
                tension: 0.2
            }]
        },
        options: {
            animation: false,
            scales: {
                x: {
                    type: 'time',
                    time: {
                        unit: 'day',
                        tooltipFormat: 'dd MMM yyyy'
                    },
                    title: {
                        display: true,
                        text: 'Tanggal'
                    },
                    min: minDate,
                    max: maxDate
                },
                y: {
                    beginAtZero: true,
                    min: 0,
                    max: 105,
                    title: {
                        display: true,
                        text: 'Rencana (%)'
                    }
                }
            },

            plugins: {
                tooltip: {
                    backgroundColor: 'white',
                    borderColor: 'black',
                    borderWidth: 1,
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += (context.parsed.y.toFixed(2) + '%');
                            }
                            return label;
                        }
                    }
                }
            },
            maintainAspectRatio: false,
        }
    };
    sChart = new Chart(ctx, chartConfig);
}

function convertTimestampToDatetime(timestamp) {
    const date = new Date(timestamp); // Konversi ke milidetik
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0, jadi ditambah 1
    const day = String(date.getDate()).padStart(2, '0');
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');

    return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
}

function convertTimestampToDate(timestamp) {
    const date = new Date(timestamp); // Konversi ke milidetik
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0, jadi ditambah 1
    const day = String(date.getDate()).padStart(2, '0');


    return `${year}-${month}-${day}`;
}

function fixHour(unixTimestamp) {
    let date = new Date(unixTimestamp); // Konversi ke milidetik
    // Setel waktu ke tengah malam
    date.setHours(0, 0, 0, 0);
    let unixTimestamp2 = date.getTime();
    if (unixTimestamp - unixTimestamp2 > (12 * 3600000)) {
        unixTimestamp2 += (24 * 3600000);
    }
    return unixTimestamp2;
}

function snapValue(yValue, snap) {
    if (yValue > 100) {
        yValue = 100;
    }
    if (yValue < 0) {
        yValue = 0;
    }
    return Math.round(yValue / snap) * snap;
}

function moveLeft() {
    for (let i in labels) {
        labels[i] = labels[i] - 86400000;
    }
    sChart.update();
    clbkFunc(labels, data);
}

function moveRight() {
    for (let i in labels) {
        labels[i] = labels[i] + 86400000;
    }
    sChart.update();
    clbkFunc(labels, data);
}

function moveUp() {
    let max = 0;
    for (let i in data) {
        if (data[i] > max) {
            max = data[i];
        }
    }
    if (max <= 99) {
        for (let i in data) {
            data[i] = data[i] + 1;
        }
        sChart.update();
        clbkFunc(labels, data);
    }

}

function moveDown() {
    let min = 100;
    for (let i in data) {
        if (data[i] < min) {
            min = data[i];
        }
    }
    if (min >= 1) {
        for (let i in data) {
            data[i] = data[i] - 1;
        }
        sChart.update();
        clbkFunc(labels, data);
    }

}

function save() {
    let json = JSON.stringify({ labels: labels, data: data });
    console.log(json)
}