<div class="container-fluid page-body-wrapper">
    <!-- partial:partials/_sidebar.html -->

    <?php require_once __DIR__ . "/../views/profile-sidebar_view.php"?>
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <a href="/investments#added-tickers-list" class="investment__back-link">
                        <span class="page-title-icon bg-gradient-primary text-white mr-2">
                            <i class="mdi mdi-arrow-left"></i>
                        </span>
                        К списку
                    </a>
                </h3>
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">
                            <span>Информация об инвестиции</span>
                            <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle pl-1"></i>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Шапка инвестиции -->
            <div class="col-12 grid-margin no-x-padding mb-4">
                <div class="card card-statistics">
                    <div class="row m-0">
                        <div class="investment__card card-col col-xl-4 col-lg-4 col-md-4 col-6 border-right">
                            <div class="card-body h-100">
                                <div class="h-100 d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                    <i class="mdi mdi-checkbook text-primary me-0 me-sm-4 icon-lg"></i>
                                    <div class="wrapper text-center text-sm-left">
                                        <div class="fluid-container">
                                            <h2 class="mb-0 font-weight-medium text-center"><?=$investment['name']?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="investment__card card-col col-xl-4 col-lg-4 col-md-4 col-6 border-right">
                            <div class="h-100 card-body">
                                <div class="h-100 d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                    <i class="mdi mdi-finance text-primary me-0 me-sm-4 icon-lg"></i>
                                    <div class="wrapper text-center text-sm-left">
                                        <p class="card-text mb-1 text-center">Тикер на бирже</p>
                                        <div class="fluid-container">
                                            <h3 class="mb-0 font-weight-medium text-center"><?=$investment['ticker']?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="investment__card card-col col-xl-4 col-lg-4 col-md-4 col-6 border-right">
                            <div class="h-100 card-body">
                                <div class="h-100 d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                    <i class="mdi mdi-currency-usd text-primary me-0 me-sm-4 icon-lg"></i>
                                    <div class="wrapper text-center text-sm-left">
                                        <p class="card-text mb-1 text-center">Текущий доход</p>
                                        <div class="fluid-container">
                                            <h3 class="mb-0 font-weight-medium text-center"><?=$income?> USD</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- График свечей на бирже -->
            <div class="col-12 grid-margin no-x-padding mb-4">
                <div class="card card-ticker-chart w-100">
                    <canvas id="ticker-chart-canvas"></canvas>
                </div>
            </div>

            <!-- Список инвестиций по данному тикеру -->
            <?php if (isset($data['investments']) && count($data['investments']) > 0): ?>
                <div class="row investments-added-list" id="added-tickers-list">
                    <?php foreach ($data['investments'] as $investment): ?>
                        <div class="col-12 col-xl-6 stretch-card mb-4 no-x-padding">
                            <div class="card h-100 bg-gradient-dark card-img-holder">
                                <a class="investments-added-card__link" href="/investments/investment/<?=$investment['id']?>">
                                    <div class="investments-added-card card-body text-white">
                                        <h6 class="investments-added-card__title"><?=$investment['name']?></h6>
                                        <p class="investments-added-card__text">Тикер: <?=$investment['ticker']?></p>
                                        <p class="investments-added-card__text">Цена при покупке: <?=$investment['start_price']?> USD</p>
                                        <p class="investments-added-card__text">Кол-во: <?=$investment['quantity']?></p>
                                        <p class="investments-added-card__text">Дата покупки: <?=$investment['date']?></p>
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


<!-- Пишем глобально, так пока быстрее чем делать из js-а запросы -->
<script>
    window.tickerHistoricalData = <?= json_encode($history) ?>;
</script>