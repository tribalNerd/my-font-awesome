<?php
if( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }

/**
 * Plugin Admin Sidebar
 */
?>
<div class="postbox">
    <h3><span><?php echo $this->menu_name;?></span></h3>
<div class="inside" style="clear:both;padding-top:1px;"><div class="para">

    <ul>
        <li>&bull; <a href="https://github.com/tribalNerd/my-font-awesome" target="_blank"><?php _e( 'Plugin Home Page', 'my-font-awesome' );?></a></li>
        <li>&bull; <a href="https://github.com/tribalNerd/my-font-awesome/issues" target="_blank"><?php _e( 'Bugs & Feature Requests', 'my-font-awesome' );?></a></li>
        <li>&bull; <a href="http://technerdia.com/help/" target="_blank"><?php _e( 'Contact Support', 'my-font-awesome' );?></a></li>
        <li>&bull; <a href="http://technerdia.com/feedback/" target="_blank"><?php _e( 'Submit Feedback', 'my-font-awesome' );?></a></li>
        <li>&bull; <a href="http://technerdia.com/projects/" target="_blank"><?php _e( 'More Plugins!', 'my-font-awesome' );?></a></li>
    </ul>

</div></div> <!-- end inside-pad & inside -->
</div> <!-- end postbox -->
