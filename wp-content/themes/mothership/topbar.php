<?php /* The Top bar template -- for a zurb foundation top bar*/ ?>
<div class="top-bar-container fixed contain-to-grid">
    <nav class="top-bar">
        <ul>
            <li class="name">
                <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>
            </li>          
            <li class="toggle-topbar"><a href="#"></a></li>
        </ul>
        <section>
            <?php foundation_top_bar_l(); ?>

            <?php foundation_top_bar_r(); ?>
        </section>
    </nav>
</div>