<?php
/**
 * TEMPLATE NAME: Modal Page 
 *
 *
 * @package WordPress
 * @subpackage 1H1H
 */
get_header(); 

global $post;
 if ( have_posts() ) : ?>

			

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
				
		
				  <h2 class="modal-title"><?php the_title(); ?></h2>
				  <!--<p class="lead"></p>-->
				  <p><?php the_content(); ?></p>  
		
			  
							

				<?php endwhile; 
				wp_reset_postdata();
				?>

				

			<?php else : ?>
			<p>Sorry, Nothing Here</p>
<?php endif; ?>