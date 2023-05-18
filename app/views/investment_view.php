<div class="container-fluid page-body-wrapper">
    <!-- partial:partials/_sidebar.html -->
    <?php require_once __DIR__ . "/../views/profile-sidebar_view.php"?>
    <!-- partial -->
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="page-header">
                <h3 class="page-title">
                    <span class="page-title-icon bg-gradient-primary text-white mr-2">
                        <i class="mdi mdi-home"></i>
                    </span>
                    Управление инвестициями
                </h3>
                <nav aria-label="breadcrumb">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">
                            <span>Категории</span>
                            <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle pl-1"></i>
                        </li>
                    </ul>
                </nav>
            </div>

            <div class="col-12 grid-margin">
                <div class="card card-statistics">
                    <div class="row">
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6 border-right">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                    <i class="mdi mdi-checkbook text-primary me-0 me-sm-4 icon-lg"></i>
                                    <div class="wrapper text-center text-sm-left ml-3">
                                        <div class="fluid-container">
                                            <h2 class="mb-0 font-weight-medium text-center"><?=$investment['name']?></h2>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6 border-right">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                    <i class="mdi mdi-finance text-primary me-0 me-sm-4 icon-lg"></i>
                                    <div class="wrapper text-center text-sm-left ml-3">
                                        <p class="card-text mb-1 text-center">Тикер на бирже</p>
                                        <div class="fluid-container">
                                            <h3 class="mb-0 font-weight-medium text-center"><?=$investment['ticker']?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6 border-right">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                    <i class="mdi mdi-currency-usd text-primary me-0 me-sm-4 icon-lg"></i>
                                    <div class="wrapper text-center text-sm-left ml-3">
                                        <p class="card-text mb-1 text-center">Цена при покупке</p>
                                        <div class="fluid-container">
                                            <h3 class="mb-0 font-weight-medium text-center"><?=$investment['start_price']?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-col col-xl-3 col-lg-3 col-md-3 col-6">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center flex-column flex-sm-row">
                                    <i class="mdi mdi-calendar-range text-primary me-0 me-sm-4 icon-lg"></i>
                                    <div class="wrapper text-sm-left ml-3">
                                        <p class="card-text mb-1 text-center">Дата покупки</p>
                                        <div class="fluid-container">
                                            <h3 class="mb-0 font-weight-medium text-center"><?=$investment['date']?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>