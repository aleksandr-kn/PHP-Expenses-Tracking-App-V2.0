$(document).ready(() => {

    const createSuggestedElement = (data) => {
        const {symbol, name, region, currency} = data;
        return `
        <div class="investments__suggested-ticker">
            <h6 class="investments__suggested-ticker-title">Название: ${name}</h6>
            <p class="investments__suggested-ticker-symbol">Тикер: ${symbol}</p>
            <p class="investments__suggested-ticker-currency">Регион: ${region}</p>
            <p class="investments__suggested-ticker-region">Валюта: ${currency}</p>
        </div>
       `;
    }

    const populateSuggestemItems = (data) => {
        let result = '';
        data.map(item => {
            result += createSuggestedElement(item);
        })
        $('.investments__suggested-tickers').html(result);
    }

    let fetching = false;

    $(".investments__input").on('input', function() {
        if (this.value.length < 4 || fetching) {
            return;
        }

        const requestUrl = `/investments/get_suggested_tickers?company_name=${this.value}`;

        fetching = true;
        $.get(requestUrl)
        .done(function(data) {
            const result = $.parseJSON(data);
            populateSuggestemItems(result);
        })
        .fail(function() {
            // use default alert popup?
        })
        .always(function() {
            fetching = false;
        });
    });
})