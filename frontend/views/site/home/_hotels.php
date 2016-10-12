<div class="row">
	<?php for ($i = 1; $i <= 4; $i++): ?>
	<div class="col-lg-3 col-md-6">
		<div class="card-box room shadow-2 shadow-3-hover">
			<div class="header image hover-scale">
				<img src="content/images/room-small.jpg" alt="title">
			</div>
			<div class="content">
				<div class="box">
					<div class="row thin">
						<div class="col-xs-7">
							<h3 class="title"><a href="#">The title</a></h3>
							<p class="description">Киев</p>
						</div>
						<div class="col-xs-5">
							<div class="price text-right">
								<span class="currency">UAH</span><span class="value">800</span>
								<p class="description">сутки</p>
							</div>
						</div>
					</div>

					<div class="rating">
						<div class="row thin">
							<div class="col-xs-6">
								<i class="fa fa-star active"></i>
								<i class="fa fa-star active"></i>
								<i class="fa fa-star active"></i>
								<i class="fa fa-star active"></i>
								<i class="fa fa-star"></i>
							</div>
							<div class="col-xs-6 text-right reviews">
								5 отзывов
							</div>
						</div>
					</div>
					
					<p class="grey">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt</p>
				</div>
				<div class="actions">
					<a href="javascript:void(0)" class="btn btn-flat btn-success">Заказать</a>
				</div>
			</div>
		</div>
	</div>
	<?php endfor ?>
</div>