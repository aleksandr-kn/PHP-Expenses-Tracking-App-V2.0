<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Moneysaver</title>

	<!-- Стили для бутстаповской темы -->
	<link rel="stylesheet" href="/assets/vendors/mdi/css/materialdesignicons.min.css">
	<link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
	<link rel="stylesheet" href="/assets/css/style.css">
	<!-- Стили для бутстаповской темы END -->

  	<!-- Стили текущего layout-а -->
	<link rel="stylesheet" href="/assets/css/template.css">

	<!-- Стили текущего View файла -->
	<?php $styles = load_styles($content_view_name);
	if ($styles) : ?>
		<?php foreach ($styles as $style) : ?>
			<link rel="stylesheet" href="/assets/css/<?= $content_view_name ?>/<?= $style ?>">
		<? endforeach ?>
	<?php endif; ?>
	<!-- End layout styles -->

	<link rel="shortcut icon" href="/assets/images/favicon.ico" />

	<!-- скрипты для бутстраповской темы -->
	<script src="/assets/vendors/js/vendor.bundle.base.js"></script>
	<!-- endinject -->
	<!-- Plugin js for this page -->
	<script src="/assets/vendors/chart.js/Chart.min.js"></script>
	<!-- End plugin js for this page -->

	<script src="/assets/js/off-canvas.js"></script>
	<script src="/assets/js/hoverable-collapse.js"></script>
	<script src="/assets/js/misc.js"></script>
	<!-- Custom js for this page -->
	<script src="/assets/js/dashboard.js"></script>
	<script src="/assets/js/todolist.js"></script>

	<!-- bootstrap slider -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/css/bootstrap-slider.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/11.0.2/bootstrap-slider.min.js"></script>

	<!-- Общие для всех страниц скрипты -->
	<script src="/assets/js/common.js"></script>


	<!-- Скрипты текущей страницы -->
	<?php $scripts = load_scripts($content_view_name);
	if ($scripts) : ?>
		<?php foreach ($scripts as $script) : ?>
			<script src="/assets/js/<?= $content_view_name ?>/<?= $script ?>"></script>
		<? endforeach ?>
	<?php endif; ?>


</head>

<body>

	<div class="container-scroller">
    <!-- popup -->
    <div class="alert-popup">
      <div class="container">
        <div class="alert-popup__inner d-flex justify-content-center align-items-center"></div>
      </div>
    </div>
		<!-- partial:partials/_navbar.html -->
		<?php if (Session::is_logged_in()) : ?>
			<nav class="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
				<div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-start">
					<button class="navbar__desktop-toggler navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
						<span class="mdi mdi-menu"></span>
					</button>
					<!-- <a class="navbar-brand brand-logo" href="/profile/"><img src="/public/assets/images/logo.svg" alt="logo" /></a> -->
					<!-- <a class="navbar-brand brand-logo-mini" href="/profile/"><img src="/public/assets/images/logo-mini.svg" alt="logo" /></a> -->
				</div>
				<div class="navbar-menu-wrapper d-flex align-items-stretch">

					<ul class="navbar-nav navbar-nav-right">
						<li class="nav-item nav-profile dropdown">
							<a class="nav-link dropdown-toggle" id="profileDropdown" href="/profile" data-toggle="dropdown" aria-expanded="false">
								<div class="nav-profile-img">
									<img src="/assets/images/icons/user_icon.png" alt="image">
									<span class="availability-status online"></span>
								</div>
								<div class="nav-profile-text">
									<p class="mb-1 text-black"><?= $_SESSION["username"] ?></p>
								</div>
							</a>
							<div class="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
								<a class="dropdown-item" href="/profile/">
									<i class="mdi mdi-account mr-2 text-success"></i> Главная страница </a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="/login/logout">
									<i class="mdi mdi-logout mr-2 text-primary"></i> Выйти </a>
							</div>
						</li>
						<li class="nav-item d-none d-lg-block full-screen-link">
							<a class="nav-link">
								<i class="mdi mdi-fullscreen" id="fullscreen-button"></i>
							</a>
						</li>

						<li class="nav-item nav-logout d-none d-lg-block">
							<a class="nav-link" href="/login/logout/">
								<i class="mdi mdi-power"></i>
							</a>
						</li>
					</ul>
					<button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
						<span class="mdi mdi-menu"></span>
					</button>
				</div>
			</nav>
		<?php endif; ?>

		<!-- Require Content View  -->
		<?php require_once __DIR__ . "/../views/" . $content_view; ?>
		<!-- partial -->

		<!-- page-body-wrapper ends -->
	</div>
	<!-- container-scroller -->
	<!-- plugins:js -->


	<!-- End custom js for this page -->

</body>

</html>