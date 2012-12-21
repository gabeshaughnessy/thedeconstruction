<?php
/**
 * TEMPLATE NAME: Modal Page 
 *
 *
 * @package WordPress
 * @subpackage 1H1H
 */
global $post;
 if ( have_posts() ) : ?>

			

				<?php /* Start the Loop */ ?>
				<?php while ( have_posts() ) : the_post(); ?>
				
				<div class="modal-header">
				<h2 id="modal-title"><?php the_title(); ?></h2>
				</div>
				
				<div class="left modal-inner">
				<div class="three columns first">
				<?php 
				$image_attr = array(
							
							'class'	=> "left",
							
						);
				the_post_thumbnail('post-thumbnail', $image_attr); ?>
				</div>
				<?php the_content(); ?>
				</div>
				
				<div class="modal-footer">
				<div class="navigation"><p class="modal-link three columns first"></p><div class="hathand hand three columns"><a href=
				<?php if(is_user_logged_in()){ echo '"'.get_edit_post_link($post).'"'; } 
				 else { echo '"#" class="no-link"'; ?> onclick="return noLink()" <?php } ?>></a></div><p class="modal-link three columns last"></p></div>
				</div>
				
				

				<?php endwhile; 
				wp_reset_postdata();
				?>

				

			<?php else : ?>
			<p>Sorry, Nothing Here</p>
<?php endif; ?>