<div class="one_full">
	<section class="title">
		<h4><?php echo lang('news:posts_title') ?></h4>
	</section>

	<section class="item">
		<div class="content">
			<?php if ($news) : ?>
				<?php echo $this->load->view('admin/partials/filters') ?>
	
				<?php echo form_open('admin/news/action') ?>
					<div id="filter-stage">
						<?php echo $this->load->view('admin/tables/posts') ?>
					</div>
				<?php echo form_close() ?>
			<?php else : ?>
				<div class="no_data"><?php echo lang('news:currently_no_posts') ?></div>
			<?php endif ?>
		</div>
	</section>
</div>
