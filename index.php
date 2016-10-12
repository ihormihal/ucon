<!DOCTYPE html>
<html lang="en">
	<head>
	<?php include 'components/head.php'; ?>
	<?php include 'components/ng-head.php'; ?>
	</head>
	<body ng-app="app">
		<header>
			<?php include 'components/navbar.php'; ?>
		</header>
		<main ng-controller="mainController">

			<?php include 'components/site/slider-home.php'; ?>

			<section class="hotels pt2 pb5">
				<div class="container wide">

					<div class="pt1 pb3 text-center">
						<h2>Популярные отели</h2>
					</div>
					
					<?php include 'components/site/hotels-row.php'; ?>
				</div>
			</section>

<!-- 			<section class="pt4 pb4 light-bg">
			<div class="container">
				<h2 class="text-center up">Sample section #1</h2>
				<p class="text-center grey mt1">Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
				<div class="icon-items mt4">
					<div class="row tile">
						<?php for ($i = 1; $i <= 3; $i++): ?>
						<div class="col-md-4">
							<div class="item text-center">
								<div class="image">
								<img src="design/images/abstract-logo.png" alt="">
								</div>
								<h3 class="title teal mt2">Lorem ipsum dolor</h3>
								<div class="description grey mt1">Lorem ipsum dolor sit amet, consectetur adipiscing elit</div>
							</div>
						</div>
						<?php endfor ?>
					</div>
				</div>
			</div>
			</section> -->


			<section class="hotels pt2 pb5">
				<div class="container wide">

					<div class="pt1 pb3 text-center">
						<h2>Популярные отели</h2>
					</div>
					
					<?php include 'components/site/hotels-row.php'; ?>
				</div>
			</section>
			
			

		</main>
		<footer>
			<?php include 'components/footer.php'; ?>
		</footer>
	</body>
</html>