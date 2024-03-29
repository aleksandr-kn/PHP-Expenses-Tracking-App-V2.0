<div class="container-fluid page-body-wrapper">
    <!-- partial:partials/_sidebar.html -->
    <?php require_once __DIR__ . "/../views/profile-sidebar_view.php"?>
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    Управление инвестициями
                </h3>
            </div>

            <!-- Форма добавления новых инвестиций -->
            <div class="row">
                <div class="col-12 col-xl-6 stretch-card grid-margin">
                    <div class="card h-100 bg-gradient-primary card-img-holder text-white">
                        <div class="investments-form card-body">
                            <h3 class="font-weight-normal mb-3 spendings__title">
                                Добавить новую инвестицию
                                <i class="mdi mdi-chart-line mdi-24px float-right spendings-icon"></i>
                            </h3>

                            <div class="investments__inner">
                                <div class="investments__input-wrapper">
                                    <p class="investments__input-title">
                                        Название ценной бумаги
                                    </p>
                                    <input class="investments__input investments__company-input" type="text"/>
                                </div>
                                <div class="investments__suggested-tickers"></div>
                                <div class="investments__input-wrapper">
                                    <p class="investments__input-title">
                                        Цена при покупке
                                    </p>
                                    <input class="investments__input investments__amount-input" type="number" placeholder="0.00"/>
                                </div>
                                <div class="investments__input-wrapper">
                                    <p class="investments__input-title">
                                        Количество
                                    </p>
                                    <input class="investments__input investments__quantity-input" type="number" value="1"/>
                                </div>
                                <p class="investments__input-title">
                                    Дата покупки
                                </p>
                                <input class="investments__input-date form-control mb-3" type="date">
                            </div>
                            <button class="investments__submit btn btn-light btn-lg btn-block"
                                type="button">
                                Добавить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($data['investments']) && count($data['investments']) > 0): ?>
                <div class="row investments-added-list" id="added-tickers-list">

                <?php foreach ($data['investments'] as $investment): ?>
                    <div class="col-12 col-xl-6 stretch-card mb-4">
                        <div class="card h-100 bg-gradient-dark card-img-holder">
                            <a class="investments-added-card__link" href="/investments/investment/<?=$investment['id']?>">
                                <div class="investments-added-card card-body text-white" data-investment-id="<?=$investment['id']?>">
                                    <h6 class="investments-added-card__title"><?=$investment['name']?></h6>
                                    <p class="investments-added-card__text">Тикер: <?=$investment['ticker']?></p>
                                    <p class="investments-added-card__text">Цена при покупке: <?=$investment['start_price']?> USD</p>
                                    <p class="investments-added-card__text">Кол-во: <?=$investment['quantity']?></p>
                                    <p class="investments-added-card__text">Дата покупки: <?=$investment['date']?></p>
                                    <span class="investments-added-card__close-icon mdi mdi-close"></span>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>