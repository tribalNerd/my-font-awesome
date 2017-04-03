<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( count( get_included_files() ) == 1 ){ exit(); }?>

<b><?php _e( 'Modify the settings below to adjust the display of Font Awesome.', 'my-font-awesome' );?></b>

<br />

<form enctype="multipart/form-data" method="post" action="">
<?php wp_nonce_field( $this->option_name . 'action', $this->option_name . 'nonce' );?>
<input type="hidden" name="type" value="update" />

    <h3><?php _e( 'Global Display', 'my-font-awesome' );?></h3>
    <?php _e( 'The two settings below will override all other settings.', 'my-font-awesome' );?>

    <table class="form-table">
    <tbody>
    <tr>
        <th scope="row"><?php _e( 'Website Only', 'my-font-awesome' );?></th>
        <td><input type="checkbox" name="website" value="1" id="website" <?php checked( parent::option( 'website' ), 1 );?>/> <label class="description" for="website"><?php _e( 'Globally load on the frontend of the Website only.', 'my-font-awesome' );?></label></td>
    </tr><tr>
        <th scope="row"><?php _e( 'Admin Area Only', 'my-font-awesome' );?></th>
        <td><input type="checkbox" name="admin" value="1" id="admin" <?php checked( parent::option( 'admin' ), 1 );?>/> <label class="description" for="admin"><?php _e( 'Globally load within the WP Admin Area only.', 'my-font-awesome' );?></label></td>
    </tr>
    </tbody>
    </table>

    <h3><?php _e( 'Content Display', 'my-font-awesome' );?></h3>

    <table class="form-table">
    <tbody><tr>
        <th scope="row"><?php _e( 'Post Types', 'my-font-awesome' );?></th>
        <td><p class="description"><?php _e( 'Load based on selected post types.', 'my-font-awesome' );?></p>
            <?php echo parent::posttypes();?></td>
    </tr><tr>
        <th scope="row"><?php _e( 'Theme Templates', 'my-font-awesome' );?></th>
        <td><p class="description"><?php _e( 'Load based on the theme template being displayed.', 'my-font-awesome' );?></p>
            <?php echo parent::templates();?></td>
    </tr><tr>
        <th scope="row"><?php _e( 'Post IDs', 'my-font-awesome' );?></th>
        <td><input type="text" name="post_ids" value="<?php echo parent::option( 'post_ids' );?>" /><p class="description"><?php _e( 'Enter the IDs for published content on Posts. Numeric values only, comma seperated, no spaces.', 'my-font-awesome' );?></p></td>
    </tr><tr>
        <th scope="row"><?php _e( 'Page IDs', 'my-font-awesome' );?></th>
        <td><input type="text" name="page_ids" value="<?php echo parent::option( 'page_ids' );?>" /><p class="description"><?php _e( 'Enter the IDs for published content on Pages. Numeric values only, comma seperated, no spaces.', 'my-font-awesome' );?></p></td>
    </tr><tr>
        <th scope="row"><?php _e( 'Post Type IDs', 'my-font-awesome' );?></th>
        <td><input type="text" name="pt_ids" value="<?php echo parent::option( 'pt_ids' );?>" /><p class="description"><?php _e( 'Enter the IDs for published content on Post Types. Numeric values only, comma seperated, no spaces.', 'my-font-awesome' );?></p></td>
    </tr>
    </tbody>
    </table>

    <h3><?php _e( 'Homepage Display', 'my-font-awesome' );?></h3>
    <span class="description"><?php _e( 'You can deselect radio items by clicking the selected item.', 'my-font-awesome' );?></span>

    <table class="form-table">
    <tbody>
    <tr>
        <th scope="row"><?php _e( 'Default Homepage', 'my-font-awesome' );?></th>
        <td><input type="radio" name="homepage" value="home" id="home" <?php checked( parent::option( 'homepage' ), 'home' );?>/> <label class="description" for="home"><?php _e( 'On the default homepage of the website.', 'my-font-awesome' );?></label></td>
    </tr><tr>
        <th scope="row"><?php _e( 'Static Homepage', 'my-font-awesome' );?></th>
        <td><input type="radio" name="homepage" value="static" id="static" <?php checked( parent::option( 'homepage' ), 'static' );?>/> <label class="description" for="static"><?php _e( 'On the static homepage of the website.', 'my-font-awesome' );?></label></td>
    </tr><tr>
        <th scope="row"><?php _e( 'Blog Homepage', 'my-font-awesome' );?></th>
        <td><input type="radio" name="homepage" value="blog" id="blog" <?php checked( parent::option( 'homepage' ), 'blog' );?>/> <label class="description" for="blog"><?php _e( 'On the blog homepage of the website.', 'my-font-awesome' );?></label></td>
    </tr>
    </tbody>
    </table>

    <h3><?php _e( 'User Role Display', 'my-font-awesome' );?></h3>

    <table class="form-table">
    <tbody>
    <tr>
        <th scope="row"><?php _e( 'Based On User Roles', 'my-font-awesome' );?></th>
        <td><p class="description"><?php _e( 'Based on selected user roles. User must be logged in for this feature to work.', 'my-font-awesome' );?></p>
            <?php echo $this->roles();?></td>
    </tr>
    </tbody>
    </table>

    <h3><?php _e( 'Login Status Display', 'my-font-awesome' );?></h3>
    <span class="description"><?php _e( 'You can deselect radio items by clicking the selected item.', 'my-font-awesome' );?></span>

    <table class="form-table">
    <tbody>
    <tr>
        <th scope="row"><?php _e( 'Logged In Users', 'my-font-awesome' );?></th>
        <td><input type="radio" name="access" value="logged_in" id="access" <?php checked( parent::option( 'access' ), 'logged_in' );?>/> <label class="description" for="access"><?php _e( 'All logged in users.', 'my-font-awesome' );?></label></td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Logged In - Frontend Only', 'my-font-awesome' );?></th>
        <td><input type="radio" name="access" value="logged_in_front" id="access" <?php checked( parent::option( 'access' ), 'logged_in_front' );?>/> <label class="description" for="access"><?php _e( 'All logged in users on the frontend of the website.', 'my-font-awesome' );?></label></td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Logged In - Back End Only', 'my-font-awesome' );?></th>
        <td><input type="radio" name="access" value="logged_in_back" id="access" <?php checked( parent::option( 'access' ), 'logged_in_back' );?>/> <label class="description" for="access"><?php _e( 'All logged in users within the backend of the website.', 'my-font-awesome' );?></label></td>
    </tr>
    <tr>
        <th scope="row"><?php _e( 'Logged Out Users', 'my-font-awesome' );?></th>
        <td><input type="radio" name="access" value="logged_out" id="access" <?php checked( parent::option( 'access' ), 'logged_out' );?>/> <label class="description" for="access"><?php _e( 'All logged out users.', 'my-font-awesome' );?></label></td>
    </tr>
    </tbody>
    </table>

    <div class="textcenter"><?php submit_button( __( 'update settings', 'my-font-awesome' ) );?></div>

</form>

<br /><hr /><br />

<form enctype="multipart/form-data" method="post" action="">
<?php wp_nonce_field( $this->option_name . 'action', $this->option_name . 'nonce' );?>

    <table class="form-table">
    <tr>
        <td class="textright"><label><?php _e( 'WARNING: Delete all saved settings for My Font Awesome.', 'my-font-awesome' );?></label> <input type="radio" name="type" value="delete" /></td>
    </tr>
    </table>

    <p class="textright"><input type="submit" name="submit" value=" submit " onclick="return confirm( 'Are You Sure?' );" /></p>

</form>
