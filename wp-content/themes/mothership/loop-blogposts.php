<?php if(have_posts()) : while(have_posts()) : the_post(); ?>
<article>
	<div class="post-title">
		<h2><a href="<?php the_permalink(); ?>" title="View Post"><?php the_title(); ?></a></h2>
			<div class="row"><div class="team-image large-1 columns"><?php echo get_avatar(get_the_author_meta('ID'), 'thumbnail');  ?></div><p class="author large-11 columns end"><em>posted by: </em><?php the_author_posts_link(); ?> on <?php echo the_date('F j, Y', '<em>','</em>', true); ?></p></div>
	</div>
	<div class="post-content">
		<?php the_content('Read More &rArr;'); ?>
	</div>
	<div class="share-section">
		<p class="share-text">Share:</p>
		<?php get_template_part('accordion', 'social'); ?>
	</div>
	<hr>
</article>
<?php endwhile; ?>
<?php endif; 
?>
