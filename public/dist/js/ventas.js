/* Chart.js Charts */
// Sales chart
var salesChartCanvas = document.getElementById('revenue-chart-canvas').getContext('2d');
//$('#revenue-chart').get(0).getContext('2d');

if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp = new XMLHttpRequest();

} else {  // code for IE6, IE5
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
}

xmlhttp.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
        if (this.responseText != "") {
            var myObj = JSON.parse(this.responseText);
            var datap = myObj.productos;
            var datas = myObj.servicios;

            var salesChartData = {
                labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                datasets: [
                    {
                        label: 'Productos',
                        backgroundColor: 'rgba(60,141,188,0.9)',
                        borderColor: 'rgba(60,141,188,0.8)',
                        pointRadius: true,
                        pointColor: '#3b8bba',
                        pointStrokeColor: 'rgba(60,141,188,1)',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(60,141,188,1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: [
                            getNumberFun(datap[0][0]),
                            getNumberFun(datap[1][0]),
                            getNumberFun(datap[2][0]),
                            getNumberFun(datap[3][0]),
                            getNumberFun(datap[4][0]),
                            getNumberFun(datap[5][0]),
                            getNumberFun(datap[6][0]),
                            getNumberFun(datap[7][0]),
                            getNumberFun(datap[8][0]),
                            getNumberFun(datap[9][0]),
                            getNumberFun(datap[10][0]),
                            getNumberFun(datap[11][0])
                        ]
                    },
                    {
                        label: 'Servicios',
                        backgroundColor: 'rgba(210, 214, 222, 1)',
                        borderColor: 'rgba(210, 214, 222, 1)',
                        pointRadius: true,
                        pointColor: 'rgba(210, 214, 222, 1)',
                        pointStrokeColor: '#c1c7d1',
                        pointHighlightFill: '#fff',
                        pointHighlightStroke: 'rgba(220,220,220,1)',
                        pointHitRadius: 10,
                        pointBorderWidth: 2,
                        data: [
                            getNumberFun(datas[0][0]),
                            getNumberFun(datas[1][0]),
                            getNumberFun(datas[2][0]),
                            getNumberFun(datas[3][0]),
                            getNumberFun(datas[4][0]),
                            getNumberFun(datas[5][0]),
                            getNumberFun(datas[6][0]),
                            getNumberFun(datas[7][0]),
                            getNumberFun(datas[8][0]),
                            getNumberFun(datas[9][0]),
                            getNumberFun(datas[10][0]),
                            getNumberFun(datas[11][0])
                        ]
                    },
                ]
            }
        }
        var salesChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            datasetFill: false,
            legend: {
                display: true
            },
            scales: {
                xAxes: [{
                    stacked: true,
                    gridLines: {
                        display: false,
                    }
                }],
                yAxes: [{
                    stacked: true,
                    ticks: {
                        beginAtZero: true,
                        maxTicksLimit: 10,
                    },
                    gridLines: {
                        display: true,
                    }
                }]
            },
            tooltips: {
                backgroundColor: "rgb(255,255,255)",
                bodyFontColor: "#858796",
                titleMarginBottom: 10,
                titleFontColor: '#6e707e',
                titleFontSize: 14,
                borderColor: '#dddfeb',
                borderWidth: 1,
                xPadding: 15,
                yPadding: 15,
                displayColors: false,
                intersect: false,
                mode: 'index',
                caretPadding: 2,
            }
        }

// This will get the first returned node in the jQuery collection.
        var salesChart = new Chart(salesChartCanvas, {
                type: 'bar',
                data: salesChartData,
                options: salesChartOptions
            }
        )
    }
};

xmlhttp.open("GET", "home/sales", true);
xmlhttp.send();

function getNumberFun(number) {
    if (isNaN(number)) {
        return 0;
    }
    return number;
}