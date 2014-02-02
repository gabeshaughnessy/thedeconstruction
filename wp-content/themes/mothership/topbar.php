<?php /* The Top bar template -- for a zurb foundation top bar*/ ?>
<div class="top-bar-container contain-to-grid">
    <nav class="top-bar">
        <ul  class="title-area" >
            <li class="name">
                <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
            </li>          
             <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
        </ul>
        <section class="top-bar-section">
            <?php foundation_top_bar_l(); ?>

            <?php foundation_top_bar_r(); ?>
        </section>
    </nav>
</div>