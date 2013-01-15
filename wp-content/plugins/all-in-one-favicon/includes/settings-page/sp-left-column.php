<?php
/**
 * @package Techotronic
 * @subpackage All in one Favicon
 *
 * @since 4.0
 * @author Arne Franken
 *
 * Left column for settings page
 */
?>
<div class="postbox-container" style="width: 69%;">
    <form id="aio-favicon-settings-update" name="aio-favicon-settings-update" enctype="multipart/form-data" method="post" action="admin-post.php">
      <?php if (function_exists('wp_nonce_field') === true) wp_nonce_field('aio-favicon-settings-form'); ?>

    <div id="poststuff">
<?php
      require_once 'sp-plugin-frontend-settings.php';
      require_once 'sp-plugin-backend-settings.php';
      require_once 'sp-plugin-meta-settings.php';
    ?>
    </div>
  </form>

<?php
    require_once 'sp-tipps-box.php';
    require_once 'sp-delete-settings.php';
  ?>
</div>