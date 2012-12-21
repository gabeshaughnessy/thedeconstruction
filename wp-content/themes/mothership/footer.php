<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content after
 *
 * @package WordPress
 * @subpackage mothership
 * @since The Deconstruction Mothership 1.0
 */
?>
<?php if(!is_page_template('page-modal.php')){ // no containers for the modals
?>
<footer class="row">
<?php wp_footer(); ?>
</footer>
<?php get_template_part('modal'); ?>
</body>
</html>
<?php }
 else { ?>
</body>
</html>
<?php } ?>