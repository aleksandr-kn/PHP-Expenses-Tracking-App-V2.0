$(document).ready(function () {

    let tickerDates = [];
    let tickerValues = [];
    for (const [key, value] of Object.entries(window.tickerHistoricalData)) {
        tickerValues.push(value['4. close']);
        tickerDates.push(key);
    }

    tickerValues.reverse();
    tickerDates.reverse();

    //Создаем график тикера за последние несколько дней
    new Chart(
        document.querySelector('#ticker-chart-canvas'),
        {
            type: 'bar',
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
                }
            }
        }
    );
})