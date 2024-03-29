$(document).ready(() => {

    // Простой debounce чтобы не подключать lodash
    function debounce(func, timeout = 300){
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => { func.apply(this, args); }, timeout);
        };
    }

    // Слушаем клик для выбора айтема
    $('.investments__suggested-tickers').click(function (e) {
        e.stopPropagation();

        // Клик по крестику
        if (e.target.classList.contains('investments__close-icon')) {
            emptySuggestedItems();
            $('.investments__company-input').val('');
            return;
        };

        if ($(e.target).closest('.investments__suggested-ticker')) {
            $(e.target.closest('.investments__suggested-ticker')).addClass('active');
            $('.investments__suggested-ticker').not('.active').remove();
        }
    })

    //Слушаем клики для удаления инвестиций
    $('.investments-added-list').click(function (e) {
        e.stopPropagation();
        if (e.target.classList.contains('investments-added-card__close-icon')) {
            e.preventDefault();
            const id = $(e.target).closest('.investments-added-card').data('investment-id');

            $.post('/investments/delete_investment', {
                    id: id,
                },
            ).done(function () {
                UI.showAlert(
                    "Успешно удалено",
                    "bg-gradient-success"
                );
                setTimeout(() => {
                    window.location.reload();
                }, 350);
            }).fail(function () {
                UI.showAlert(
                    "Произошла ошибка",
                    "bg-gradient-danger"
                );
            })
        };
    })

    const createSuggestedElement = (data) => {
        const {symbol, name, region, currency} = data;
        return `
        <div class="investments__suggested-ticker" data-ticker="${symbol}">
            <h6 class="investments__suggested-ticker-title">${name}</h6>
            <p class="investments__suggested-ticker-symbol">Тикер: ${symbol}</p>
            <p class="investments__suggested-ticker-currency">Биржа: ${region}</p>
            <p class="investments__suggested-ticker-region">Валюта: ${currency}</p>
            <span class="investments__close-icon mdi mdi-close"></span>
        </div>
       `;
    }

    const populateSuggestemItems = (data) => {
        $('.investments__suggested-tickers').css('display', 'flex');
        let result = '';
        data.map(item => {
            result += createSuggestedElement(item);
        })
        $('.investments__suggested-tickers').html(result);
    }

    const emptySuggestedItems = () => {
        $('.investments__suggested-tickers')
            .empty()
            .css("display", "none");
    }

    // Обработка инпута тикера с задержкой
    const handleTickerInputDebounce = debounce((context) => {
        if (context.value.length < 3) {
            return;
        }

        const requestUrl = `/investments/get_suggested_tickers?company_name=${context.value}`;

        fetching = true;
        $.get(requestUrl)
            .done(function(data) {
                emptySuggestedItems();

                let result = $.parseJSON(data);
                if (result.length) {
                    result = result.filter(item => item.symbol.indexOf('.') === -1);
                    populateSuggestemItems(result);
                }
            })
            .fail(function() {
                emptySuggestedItems();
            })
            .always(function() {
                fetching = false;
            });
    });

    $(".investments__company-input").on('input', function () {
        if ($('.investments__suggested-ticker.active').length) {
            return;
        }
        if (this.value.length < 3) {
            emptySuggestedItems();
            return;
        }
        handleTickerInputDebounce(this);
    });

    $('.investments__submit').click(function () {
        const date = $('.investments__input-date').val();
        const amount = $('.investments__amount-input').val(); // Сумма
        const ticker = $('.investments__suggested-ticker.active').eq(0).data('ticker');
        const name = $('.investments__company-input').val();
        const quantity = $('.investments__quantity-input').val(); // Количество

        if ((!date || !amount || !ticker || !name || !quantity)) {
            UI.showAlert(
                "Пожалуйста заполните все поля",
                "bg-gradient-danger"
            );
            return;
        }

        $.post('/investments/add_investment', {
                date: date,
                amount: amount,
                ticker: ticker,
                name: name,
                quantity: quantity,
            },
        ).done(function () {
            UI.showAlert(
                "Успешно добавлено",
                "bg-gradient-success"
            );
            window.location.reload();
        }).fail(function () {
            UI.showAlert(
                "Произошла ошибка",
                "bg-gradient-danger"
            );
        })

    })
})