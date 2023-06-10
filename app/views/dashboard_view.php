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
          Расходы
        </h3>
        <nav aria-label="breadcrumb">
          <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
              <span class="page-header__description">Здесь добавляются новые расходы</span>
              <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle pl-1"></i>
            </li>
          </ul>
        </nav>
      </div>
      <div class="row">
        <!-- Новая форма -->
        <div class="col-12 stretch-card grid-margin">
          <div class="card h-100 bg-gradient-danger card-img-holder text-white">
            <div class="card-body add-spendings">
              <h3 class="font-weight-normal mb-3 add-spendings__title">
                Добавить расход
                <i class="mdi mdi-chart-line mdi-24px float-right add-spendings-icon"></i>
              </h3>
              <div class="add-spendings__inner">
                <div class="add-spendings__inner-left">
                  <p class="add-spendings__input-title">
                    Сколько вы потратили? 
                  </p>
                  <div class="add-spendings__input-wrap">
                    <input class="add-spendings__input" id="new_spending_amount" type="text" id="new-spending-amount" placeholder="0.00" required="required">
                  </div>
                  <p class="add-spendings__input-title add-spendings__input-title-name">
                    Введите заметку
                  </p>
                  <div class="add-spendings__input-wrap">
                    <input class="add-spendings__input" type="text" id="new_spending_name" class="form-control">
                  </div>
                </div>
                      
                <div class="add-spendings__inner-right">
                    <div class="add-spendings__category-slide">
                        <?php if (isset($user_data["categories"])) foreach ($user_data["categories"] as $category) : ?>
                        <?php unset($category['subcategories']['status']); ?>
                        <div class="add-spendings__category-item" data-id=<?=$category['id']?> 
                            data-subcategories='<?= json_encode($category['subcategories']) ?>'>
                            <div class="add-spendings__category-item-image">
                                <?= mb_substr($category['name'],0,2); ?>
                            </div>
                            <div class="add-spendings__category-item-title">
                                <?= $category["name"]; ?>
                            </div>
                        </div>
                        <?php endforeach ?>
                    </div>
                </div>
              </div>
              
              <div class="form-group source-select w-100">
                <div class="col-12 source-select__wrap">
                  <div class="input-group">
                    <div id="radioBtn" class="btn-group">
                      <?php if (isset($user_data["sources"])) foreach ($user_data["sources"] as $source) : ?>
                        <a data-source-id="<?=$source['id']?>" class="source-select__btn btn btn-inverse-dark btn-sm <?= $source == $user_data["sources"][0] ? 'active' : 'notActive' ?>" data-toggle="fun"><?=$source['name']?></a>
                      <?php endforeach ?>
                    </div>
                    <input type="hidden" name="fun" id="fun">
                  </div>
                </div>
              </div>
              <button type="button" id="add-new-spending" class="btn btn-inverse-dark btn-lg btn-block">Добавить новый расход</button>
            </div>
          </div>
        </div>

        <!-- Статистика за неделю -->
        <div class="chart col-12 col-md-5 grid-margin stretch-card">
          <div class="card h-100">
            <div class="card-body">
              <div class="chartjs-size-monitor">
                <div class="chartjs-size-monitor-expand">
                  <div class=""></div>
                </div>
                <div class="chartjs-size-monitor-shrink">
                  <div class=""></div>
                </div>
              </div>
              <h4 class="card-title">Расходы за неделю</h4>
              <canvas id="traffic-chart" width="320" height="160" style="display: block; width: 320px; height: 160px;" class="chartjs-render-monitor"></canvas>
              <div id="traffic-chart-legend" class="rounded-legend legend-vertical legend-bottom-left pt-4">
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-4 stretch-card grid-margin">
          <div class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
              <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
              <h4 class="font-weight-normal mb-3">
                Недельные расходы
                <i class="mdi mdi-chart-line mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-5"><?= $user_data['this_week_spendings_sum'] ?> Р.</h2>
              <?php if ($user_data["show_percentage_difference_amount"]): ?>
                  <h6 class="card-text"><?= $user_data["percentage_difference_amount_status"] ?> на <?= $user_data["percentage_difference_amount"] ?> %</h6>    
              <?php endif; ?>  
            </div>
          </div>
        </div>

        <div class="col-md-4 stretch-card grid-margin">
          <div class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
              <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
              <h4 class="font-weight-normal mb-3">
                Количество покупок за неделю
                <i class="mdi mdi-bookmark-outline mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-5"><?= $user_data["this_week_spendings_quantity"] ?></h2>
              <?php if ($user_data["show_percentage_difference_quantity"]): ?>
                  <h6 class="card-text"><?= $user_data["percentage_difference_quantity_status"] ?> на <?= $user_data["percentage_difference_quantity"] ?> %</h6>
              <?php endif; ?>
            </div>
          </div>
        </div>

        <div class="col-md-4 stretch-card grid-margin">
          <div class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
              <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
              <h4 class="font-weight-normal mb-3">
                Средний чек на этой неделе
                <i class="mdi mdi-diamond mdi-24px float-right"></i>
              </h4>
              <h2 class="mb-5"><?= $user_data["this_week_average_sum"] ?> Р.</h2>
              <?php if ($user_data["show_percentage_difference_average_sum"]): ?>
                  <h6 class="card-text">Изменился на <?= $user_data["percentage_difference_average_sum"] ?> %</h6>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      
                    
        <div class="row desktop-hidden">
            <div class="col-12 grid-margin">
                <div class="spendings-list">
                    <h5 class="spendings-list__title">
                        Последние расходы
                    </h5>
                    <div class="spendings-list__items">
                        <?php if (!empty($user_data['spendings'])) foreach ($user_data['spendings'] as $spending) : ?>
                        <div class="spendings-list__item" data-spending-id="<?= $spending["id"]; ?>">
                            <div class="spendings-list__item-left">
                                <div class="spendings-list__item-image">
                                    <?= mb_substr($spending['category_name'],0,2); ?>
                                </div>
                                <div class="spendings-list__item-data">
                                    <div class="spendings-list__item-category">
                                        <?= $spending['category_name'] ?>
                                    </div>
                                    <div class="spendings-list__item-source">
                                        <?= $spending['source_name'] ?>
                                    </div>
                                    <div class="spendings-list__item-date">
                                        <?= $spending['spending_date'] ?>
                                    </div>
                                    <div class="spendings-list__item-description">
                                        <?= $spending['name'] ?>
                                    </div>
                                </div>
                            </div>
                            <div class="spendings-list__item-right">
                                <div class="spendings-list__item-sum">
                                    <?= $spending['sum'] ?> Р.
                                </div>
                                <div class="spendings-list__item-delete">
                                    Удалить
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

      <div class="row mobile-hidden">
        <div class="col-12 grid-margin">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Последние Расходы</h4>
              <small id="spendings-error"><?= isset($errors["spending_error"]) ? $errors["spending_error"] : "" ?></small>
              <div class="table-responsive ">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Сумма</th>
                      <th>Описание</th>
                      <th>Источник</th>
                      <th>Дата</th>
                      <th>Категория</th>
                      <th>Доп. Категория</th>
                      <th>Управление</th>
                    </tr>
                  </thead>
                  <tbody class="all-spendings-list">
                    <?php if (!empty($user_data['spendings'])) foreach ($user_data['spendings'] as $spending) : ?>
                      <tr class="spending-item" data-spending-id="<?= $spending["id"]; ?>">
                        <td>
                          <?= $spending['sum'] ?>
                        </td>
                        <td>
                          <?= $spending['name'] ?>
                        </td>
                        <td>
                          <!-- <label class="badge badge-gradient-success">Выполнена</label> -->
                          <?= $spending['source_name'] ?>
                        </td>
                        <td>
                          <?= $spending['spending_date'] ?>
                        </td>
                        <td>
                          <?= $spending['category_name'] ?>
                        </td>
                        <td>
                          <?= $spending['subcategory_name'] ?>
                        </td>
                        <td>
                          <button data-spending-id="<?= $spending["id"]; ?>" type="button" class="btn btn-outline-secondary btn-fw delete-spending">Удалить</button>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row mobile-hidden">
        <div class="col-12 d-flex justify-content-center grid-margin pagination">
          <div class="btn-group " role="group" aria-label="Basic example">
            <button type="button" class="pagination-prev btn btn-outline-dark <?= $user_data["current_page"] === 1 ? "disabled" : ""?>" data-direction="prev">Предыдущая</button>
            <button type="button" class="pagination-current btn btn-outline-dark disabled">1</button>
            <button type="button" class="pagination-next btn btn-outline-dark" data-direction="next">Следующая</button>
          </div>
        </div>
      </div>
    </div>
    <!-- content-wrapper ends -->
  </div>
  <!-- main-panel ends -->
</div>
