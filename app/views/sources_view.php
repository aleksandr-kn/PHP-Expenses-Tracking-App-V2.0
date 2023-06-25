<div class="container-fluid page-body-wrapper">
  <!-- partial:partials/_sidebar.html -->
  <?php require_once __DIR__ . "/../views/profile-sidebar_view.php"?>
  <!-- partial -->
  <div class="main-panel">
    <div class="content-wrapper">
      <div class="page-header">
        <h3 class="page-title">
          Управление Источниками Расходов
        </h3>
      </div>
     
      <div class="row align-items-start">
        <div class="col-md-4 stretch-card grid-margin add-source-card">
          <div class="card bg-gradient-dark card-img-holder text-white">
            <div class="card-body">
              <h2 class="mb-5 add-source-card__title">Новый источник</h2>
              <div class="add-source">
                <p class="add-source__input-title">
                  Введите название
                </p>
                <div class="add-source__input-wrap">
                  <input class="add-source__input" id="new-source-name" type="text" placeholder="Название" required="required">
                </div>
                <p class="add-source__input-title add-source__input-title-description">
                  Введите описание
                </p>
                <div class="add-source__input-wrap">
                  <input class="add-source__input" type="text" id="new-source-description" class="form-control">
                </div>
                <button type="button" class="btn btn-outline-secondary btn-fw" id="add-new-source">Добавить</button>
              </div>
              <span class="source-error"></span>
            </div>
          </div>
        </div>

        <?php if (!empty($user_data["sources"])) foreach ($user_data["sources"] as $source) : ?>
          <div class="col-md-4 stretch-card grid-margin">
            <div class="card bg-gradient-danger card-img-holder text-white">
              <div class="card-body">
                <img src="/assets/images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image">
                <h2 class="mb-3"><?= $source['name'] ?></h2>
                <h4 class="font-weight-normal"><?= $source['description'] ?> <i class="mdi mdi-chart-line mdi-24px float-right"></i>
                </h4>
                <h6 class="card-text">Сумарные расходы 25%</h6>
                <button data-source-id="<?=$source['id']  ?>"  type="button" class="delete-source btn btn-outline-light text-black btn-fw mt-5" style="color: #fff;">Удалить</button>
              </div>
            </div>
          </div>
        <?php endforeach ?>

      </div>


    </div>

  </div>
  <!-- main-panel ends -->
</div>