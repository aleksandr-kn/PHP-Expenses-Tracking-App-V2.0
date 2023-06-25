$(document).ready(function () {

    let tickerDates = [];
    let tickerValues = [];
    for (const [key, value] of Object.entries(window.tickerHistoricalData).reverse().slice(0, 10)) {
        tickerValues.push(value['close']);
        tickerDates.push(key);
    }

    console.log(window.tickerHistoricalData);
    tickerValues.reverse();
    tickerDates.reverse();

    //Создаем график тикера за последние несколько дней
    new Chart(
        document.querySelector('#ticker-chart-canvas'),
        {
            type: 'line',
            data: {
                labels: tickerDates,
                datasets: [
                    {
                        label: 'Цена последней закрытой сделки',
                        data: tickerValues,
                        borderColor: '#36A2EB',
                        backgroundColor: '#9BD0F5',
                    }
                ]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: 'График на бирже'
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            display: false //this will remove only the label
                        }
                    }]
                }
            }
        }
    );
})