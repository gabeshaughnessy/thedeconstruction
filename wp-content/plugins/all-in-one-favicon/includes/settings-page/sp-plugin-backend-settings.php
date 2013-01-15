<?php
/**
 * @package Techotronic
 * @subpackage All in one Favicon
 *
 * @since 4.1
 * @author Arne Franken
 *
 * Backend Favicon Settings
 */
?>
<div id="aio-favicon-backend-settings" class="postbox">
    <h3 id="backend-settings"><?php _e('Backend', AIOFAVICON_TEXTDOMAIN); echo " "; _e('Settings', AIOFAVICON_TEXTDOMAIN); ?></h3>

    <div class="inside">
        <table class="form-table">
          <?php
          // The icons that we can set.
          $faviconMap = array(
            'backendICO' => 'ICO',
            'backendPNG' => 'PNG',
            'backendGIF' => 'GIF',
            'backendApple' => 'Apple Touch Icon');

          // Loop over this list of icons.
          foreach ($faviconMap as $iconName => $iconType) { ?>
              <tr>
                  <th scope="row">
                      <label for="<?php echo AIOFAVICON_SETTINGSNAME .'-'. $iconName ?>"><?php printf(__('%1$s '.$iconType, AIOFAVICON_TEXTDOMAIN), __('Backend', AIOFAVICON_TEXTDOMAIN)); ?>:</label>
                  </th>
                  <td width="32">
                      <div id="<?php echo $iconName ?>-favicon"></div>
                  </td>
                  <td>
                      <input id="<?php echo AIOFAVICON_SETTINGSNAME .'-'. $iconName ?>" type="file" name="<?php echo $iconName ?>" size="50" maxlength="100000" accept="image/*" value="<?php echo $this->aioFaviconSettings[$iconName] ?>" style="display:none;"/>
                      <input id="<?php echo AIOFAVICON_SETTINGSNAME .'-'. $iconName ?>-text" type="text" name="<?php echo $iconName ?>-text" size="60" maxlength="100000" value="<?php echo $this->aioFaviconSettings[$iconName] ?>"/>
                      <input id="<?php echo AIOFAVICON_SETTINGSNAME .'-'. $iconName ?>-button" type="button" name="<?php echo $iconName ?>-button" class="button-secondary" value="<?php _e('Upload') ?>" />
                      <br />
                      <input type="checkbox" name="delete-<?php echo $iconName ?>"/><?php _e('Check box to delete favicon.',AIOFAVICON_TEXTDOMAIN) ?>
                  </td>
              </tr>
            <?php } ?>
        </table>
        <p class="submit">
            <input type="hidden" name="action" value="aioFaviconUpdateSettings"/>
            <input type="submit" name="aioFaviconUpdateSettings" class="button-primary" value="<?php _e('Save Changes') ?>"/>
        </p>
    </div>
</div>