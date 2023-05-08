<div class="container-fluid page-body-wrapper full-page-wrapper">
  <!-- partial:partials/_sidebar.html -->
	<?php require_once __DIR__ . "/../views/profile-sidebar_view.php"?>
  <!-- partial -->
  <div class="content-wrapper d-flex align-items-center auth">
    <div class="row flex-grow">
      <div class="col-lg-6 mx-auto">
        <div class="auth-form-light text-left p-5">
          <h4>Смена пароля</h4>
          <h6 class="font-weight-light">Введите новый пароль</h6>
          <form class="pt-3" method="POST" action="/reset/process">
            <div class="form-group">
              <input type="password" name="new_password" class="form-control form-control-lg" placeholder="Пароль">
              <span class="help-block text-danger"><?php echo isset($errors['new_password_err']) ? $errors['new_password_err'] : ''; ?></span>
            </div>
            <div class="form-group">
              <input type="password" name="confirm_password" class="form-control form-control-lg" placeholder="Подтверждение">
              <span class="help-block text-danger"><?php echo isset($errors['confirm_password_err']) ? $errors['confirm_password_err'] : ''; ?></span>
            </div>
            <div class="mt-3">
              <button type="submit" class="btn btn-block btn-gradient-primary btn-lg font-weight-medium auth-form-btn">Сменить пароль</button>
            </div>
            <div class="text-center mt-4 font-weight-light"> Передумали? <a href="/profile/settings" class="text-primary">В Профиль</a>
            </div>
            <input name="date" value="" type="hidden">
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- content-wrapper ends -->
</div>