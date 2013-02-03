<?php
//newsfeed section heading
?>
<div class="news-wrapper">
<!-- ><h3>Deconstruction News:</h3> -->
<?php
//check for newsfeed transient, if it exists, assign variable newfeed to it
//if it doesn't exist:
//query the posts for

$args = array(
'posts_per_page' => '3'
);

$newsfeed = new WP_Query( $args );
if($newsfeed->have_posts()) : while($newsfeed->have_posts()) : $newsfeed->the_post();
?>
<div class="news-item">

<h4 class="post-title"><?php the_title(); ?></h4>
<div class="post-image four columns">
	<?php the_post_thumbnail(); ?>
</div>
<div class="post-excerpt"><?php the_excerpt(); ?></div>
<a class="twelve columns read more" href="<?php the_permalink(); ?>" title="Read More">Read More &rArr;</a>
</div>
<?php

endwhile;
endif;
//assign the query to variable newsfeed
//foreach loop through newsfeed posts
//display the post title, date and excerpt with a readmore link
//end the loop

//set newsfeed to the transient

//hook into save posts loop - clear transients for posts.
?>
<?php
//newsfeed section footer
?>
</div>