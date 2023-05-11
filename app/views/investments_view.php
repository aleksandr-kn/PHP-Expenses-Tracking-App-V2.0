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

            <!-- Форма добавления новых инвестиций -->
            <div class="row">
                <div class="col-12 col-xxl-6 stretch-card grid-margin">
                    <div class="card h-100 bg-gradient-primary card-img-holder text-white">
                        <div class="investments card-body">
                            <h3 class="font-weight-normal mb-3 spendings__title">
                                Добавить новую инвестицию
                                <i class="mdi mdi-chart-line mdi-24px float-right spendings-icon"></i>
                            </h3>

                            <div class="investments__inner">
                                <div class="investments__input-wrapper">
                                    <input class="investments__input" type="text" placeholder="Введите название ценной бумаги"/>
                                </div>
                                <div class="investments__suggested-tickers"></div>
                            </div>


                            <button class="btn btn-light btn-lg btn-block"
                                type="button">
                                Добавить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>