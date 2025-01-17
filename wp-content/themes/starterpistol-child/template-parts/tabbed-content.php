<?php
$preselect_tab = (isset($_GET['selected']) && !empty( $_GET['selected'] ))?$_GET['selected']:$_SERVER['REQUEST_URI'];
$tab_1 = $tab_2 = $tab_3 = $tab_4 = "";
switch ($preselect_tab) {
    case "#tab-1":
        $tab_1 = "current";
        break;
    case "#tab-2":
        $tab_2 = "current";
        break;
    case "#tab-3":
        $tab_3 = "current";
        break;
    case "#tab-4":
        $tab_4 = "current";
        break;
    default:
		 $tab_1 = "current";
        break;
}
?>

<div class="tabbed-content is-desktop">	
    <div class="tabs__wrapper">
		<ul class="tabs columns columns--grid-fourths">
			<?php if(get_field('tab_title_one')) : ?>
			<li class="tab__main-title <?php echo $tab_1; ?>" data-tab="tab-1">
				<div class="inner">
					<h3><?php the_field('tab_title_one'); ?></h3>
				</div>
			</li>
			<?php endif; ?>
			<?php if(get_field('tab_title_two')) : ?>
			<li class="tab__main-title <?php echo $tab_2; ?>" data-tab="tab-2">
				<div class="inner">
					<h3><?php the_field('tab_title_two'); ?></h3>
				</div>
			</li>
			<?php endif; ?>
			<?php if(get_field('tab_title_three')) : ?>
			<li class="tab__main-title <?php echo $tab_3; ?>" data-tab="tab-3">
				<div class="inner">
					<h3><?php the_field('tab_title_three'); ?></h3>
				</div>
			</li>
			<?php endif; ?>
			<?php if(get_field('tab_title_four')) : ?>
			<li class="tab__main-title <?php echo $tab_4; ?>" data-tab="tab-4">
				<div class="inner">
					<h3><?php the_field('tab_title_four'); ?></h3>
				</div>
			</li>
			<?php endif; ?>
		</ul>
	</div>
	
	<div class="page__content">
		<div class="l-constrain">
			<?php if(get_field('tab_content_type_one') == 'has-accordion') : ?>
			<div id="tab-1" class="tab-item <?php echo $tab_1; ?>">
				<div class="tab__content">
					<div class="l-constrain">
						<div class="inner">
							<div class="tab__body">
								<?php if( have_rows('tab_accordion_content_one') ): ?>
								<div class="accordion">

								  <?php while( have_rows('tab_accordion_content_one') ): the_row(); ?>

								  <div class="accordion-item">
									<div class="accordion__title"><?php the_sub_field('accordion_title'); ?></div>
										<div class="accordion__content">
											<?php the_sub_field('accordion_content'); ?>
										</div>
									</div>

								  <?php endwhile; ?>

								</div>
								<?php endif; ?>
							</div>
						</div>   
					</div>
				</div>
			</div>
			<?php else : ?>
			<div id="tab-1" class="tab-item <?php echo $tab_1; ?>">
				<div class="tab__content">
					<div class="l-constrain">
						<div class="inner">
							<div class="tab__body">
								<?php the_field('tab_standard_content_one'); ?>
							</div>
						</div>   
					</div>
				</div>
			</div>						
			<?php endif; ?>
			<!--- Start Tab 2 -->
			<?php if(get_field('tab_content_type_two') == 'has-accordion') : ?>
			<div id="tab-2" class="tab-item <?php echo $tab_2; ?>">
				<div class="tab__content">
					<div class="l-constrain">
						<div class="inner">
							<div class="tab__body">
								<?php if( have_rows('tab_accordion_content_two') ): ?>
								<div class="accordion">

								  <?php while( have_rows('tab_accordion_content_two') ): the_row(); ?>

								  <div class="accordion-item">
									<div class="accordion__title"><?php the_sub_field('accordion_title'); ?></div>
										<div class="accordion__content">
											<?php the_sub_field('accordion_content'); ?>
										</div>
									</div>

								  <?php endwhile; ?>

								</div>
								<?php endif; ?>
							</div>
						</div>   
					</div>
				</div>
			</div>
			<?php else : ?>
			<div id="tab-2" class="tab-item <?php echo $tab_2; ?>">
				<div class="tab__content">
					<div class="l-constrain">
						<div class="inner">
							<div class="tab__body">
								<?php the_field('tab_standard_content_two'); ?>
							</div>
						</div>   
					</div>
				</div>
			</div>						
			<?php endif; ?>
			<!-- Start Tab 3 -->
			<?php if(get_field('tab_content_type_three') == 'has-accordion') : ?>
			<div id="tab-3" class="tab-item <?php echo $tab_3; ?>">
				<div class="tab__content">
					<div class="l-constrain">
						<div class="inner">
							<div class="tab__body">
								<?php if( have_rows('tab_accordion_content_three') ): ?>
								<div class="accordion">

								  <?php while( have_rows('tab_accordion_content_three') ): the_row(); ?>

								  <div class="accordion-item">
									<div class="accordion__title"><?php the_sub_field('accordion_title'); ?></div>
										<div class="accordion__content">
											<?php the_sub_field('accordion_content'); ?>
										</div>
									</div>

								  <?php endwhile; ?>

								</div>
								<?php endif; ?>
							</div>
						</div>   
					</div>
				</div>
			</div>
			<?php else : ?>
			<div id="tab-3" class="tab-item <?php echo $tab_3; ?>">
				<div class="tab__content">
					<div class="l-constrain">
						<div class="inner">
							<div class="tab__body">
								<?php the_field('tab_standard_content_three'); ?>
							</div>
						</div>   
					</div>
				</div>
			</div>						
			<?php endif; ?>
			
			<!-- Start Tab 4 -->
			<?php if(get_field('tab_content_type_four') == 'has-accordion') : ?>
			<div id="tab-4" class="tab-item <?php echo $tab_4; ?>">
				<div class="tab__content">
					<div class="l-constrain">
						<div class="inner">
							<div class="tab__body">
								<?php if( have_rows('tab_accordion_content_four') ): ?>
								<div class="accordion">

								  <?php while( have_rows('tab_accordion_content_four') ): the_row(); ?>

								  <div class="accordion-item">
									<div class="accordion__title"><?php the_sub_field('accordion_title'); ?></div>
										<div class="accordion__content">
											<?php the_sub_field('accordion_content'); ?>
										</div>
									</div>

								  <?php endwhile; ?>

								</div>
								<?php endif; ?>
							</div>
						</div>   
					</div>
				</div>
			</div>
			<?php else : ?>
			<div id="tab-4" class="tab-item <?php echo $tab_4; ?>">
				<div class="tab__content">
					<div class="l-constrain">
						<div class="inner">
							<div class="tab__body">
								<?php the_field('tab_standard_content_four'); ?>
							</div>
						</div>   
					</div>
				</div>
			</div>						
			<?php endif; ?>
		</div>
	</div>
</div>