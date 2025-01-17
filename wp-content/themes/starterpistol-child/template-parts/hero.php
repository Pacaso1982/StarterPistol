<!-- Desktop Hero -->
<div class="hero is-desktop">
    <div class="glide" id="carousel-hero-desktop">
        <div class="glide__track" data-glide-el="track">
            <ul class="glide__slides">
                <?php
                $desktop_images = array(); // Array to store desktop images
                while (have_rows('slides')) : the_row();
                    $slide_image = get_sub_field('slide_image');
                    if ($slide_image && !in_array($slide_image, $desktop_images)) :
                        ?>
                        <li class="glide__slide" style="background-image: url('<?php echo esc_url($slide_image); ?>');">
                            <div class="inner">
								<div class="l-constrain">
									<div class="columns columns--vertical-center">
										<div class="content">
											<?php if (get_sub_field('slide_heading')) : ?>
												<h1><?php the_sub_field('slide_heading') ?></h1>
											<?php endif; ?>
											
											<?php if (get_sub_field('slide_content')) : ?>
												<?php the_sub_field('slide_content') ?>
											<?php endif; ?>

											<?php if (get_sub_field('slide_button_text')) : ?>
												<a href="<?php the_sub_field('slide_button_link') ?>" class="button button--accent-color"><?php the_sub_field('slide_button_text') ?></a>
											<?php endif; ?>
										</div>
									</div>
								</div>
                            </div>
                        </li>
                        <?php
                        $desktop_images[] = $slide_image; // Add the image URL to the desktop images array
                    endif;
                endwhile;
                ?>
            </ul>
        </div>
		<div class="glide__arrows" data-glide-el="controls">
            <button class="glide__arrow glide__arrow--left" data-glide-dir="<"></button>
            <button class="glide__arrow glide__arrow--right" data-glide-dir=">"></button>
        </div>
    </div>
</div>

<!-- Mobile Hero -->
<div class="hero is-mobile">
    <div class="glide" id="carousel-hero-mobile">
        <div class="glide__track" data-glide-el="track">
            <ul class="glide__slides">
                <?php
                $mobile_images = array(); // Array to store mobile images
                while (have_rows('slides')) : the_row();
                    $slide_image_mobile = get_sub_field('slide_image_mobile');
                    if ($slide_image_mobile && !in_array($slide_image_mobile, $mobile_images)) :
                        ?>
                        <li class="glide__slide" style="background-image: url('<?php echo $slide_image_mobile; ?>');">
                            <div class="inner">
                                <div class="content">
                                    <?php if (get_sub_field('slide_heading')) : ?>
										<h1><?php the_sub_field('slide_heading') ?></h1>
									<?php endif; ?>

									<?php if (get_sub_field('slide_content')) : ?>
										<?php the_sub_field('slide_content') ?>
									<?php endif; ?>
									
                                    <?php if (get_sub_field('slide_button_text')) : ?>
                                        <a href="<?php the_sub_field('slide_button_link'); ?>" class="button button--primary-color"><?php the_sub_field('slide_button_text'); ?></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </li>
                        <?php
                        $mobile_images[] = $slide_image_mobile; // Add the image URL to the mobile images array
                    endif;
                endwhile;
                ?>
            </ul>
        </div>
		<div class="glide__arrows" data-glide-el="controls">
            <button class="glide__arrow glide__arrow--left" data-glide-dir="<"></button>
            <button class="glide__arrow glide__arrow--right" data-glide-dir=">"></button>
        </div>
    </div>
</div>