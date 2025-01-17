<div class="hamburger"><i class="fas fa-bars"></i></div>

<div class="site-menu site-menu--mobile">
	<div class="site-menu__close"><i class="fas fa-times-circle"></i></div>
	<nav id="site-navigation" class="mobile-menu">
		<button class="menu-toggle" aria-controls="mobile-menu" aria-expanded="false"><?php esc_html_e( 'Mobile Menu', 'starterpistol' ); ?></button>
		<?php
			wp_nav_menu( array(
				'theme_location' => 'mobile-menu',
				'menu_id'        => 'mobile-menu',
			) );
		?>
	</nav><!-- #site-navigation -->
</div>