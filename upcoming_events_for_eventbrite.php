<?php
/*
Plugin Name: Upcoming Events for EventBrite
Plugin URI:  http://www.github.com/kmckee/upcoming_events_for_eventbrite_wordpress
Description: This plugin adds a widget that displays a list of upcoming events from eventbrite.
Version:     0.3 
Author:      Kyle McKee 
Author URI:  http://aptobits.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'Ah ah ah, you didn\'t say the magic word' );

add_option('uee_app_key');
add_option('uee_organizer_id');

add_action( 'admin_menu', 'uee_upcoming_events_plugin_menu' );
function uee_upcoming_events_plugin_menu() {
    add_options_page( 'Upcoming Events Options', 'Upcoming Events for EventBrite', 'manage_options', 'upcoming_events_eventbrite', 'uee_upcoming_events_options' );
}
function uee_upcoming_events_options() {
    if (!current_user_can('manage_options'))
    {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    $hidden_field_name = 'uee_submit_hidden';
    $app_key_id = 'uee_app_key';
    $organizer_id = 'uee_organizer_id';

    $app_key_val = get_option($app_key_id);
    $organizer_val = get_option($organizer_id);

    if(isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        $app_key_val = sanitize_text_field($_POST[$app_key_id]);
        $organizer_val = sanitize_text_field($_POST[$organizer_id]);
        update_option( $app_key_id, $app_key_val );
        update_option( $organizer_id, $organizer_val );
        ?>
            <div class="updated"><p><strong><?php _e('Settings saved.', 'menu-test' ); ?></strong></p></div>
        <?php
    }

    echo '<div class="wrap">';
    echo "<h2>" . __( 'Upcoming Events For EventBrite Settings', 'menu-test' ) . "</h2>";
?>

<form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("App Key ID:", 'menu-test' ); ?> 
<input type="text" name="<?php echo $app_key_id; ?>" value="<?php echo $app_key_val; ?>" size="20">
</p><hr />

<p><?php _e("Organizer ID:", 'menu-test' ); ?> 
<input type="text" name="<?php echo $organizer_id; ?>" value="<?php echo $organizer_val; ?>" size="20">
</p><hr />

<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>

<?php

}

add_shortcode('upcoming_events_for_eventbrite', 'uee_inject_upcoming_events');
function uee_inject_upcoming_events($atts) {
    $a = shortcode_atts( array(
        'max_count' => '3'
    ), $atts );   
    $max_count = intval($a['max_count']);
    $app_key = get_option('uee_app_key');
    $organizer_id = get_option('uee_organizer_id');
    $url = "https://www.eventbrite.com/json/organizer_list_events?app_key=".$app_key."&id=".$organizer_id;
    $json = file_get_contents($url);
    $event_data = json_decode($json);
    echo '<div class="uee_events">';
    $i = 0;
    foreach ($event_data->events as $event) {
        $i += 1;
        if ($i >$max_count) {
            break;
        };
        echo '<div class="uee_event">';
        echo '<a href="'.$event->event->url.'"class="uee_event_title">'.$event->event->title.'</a> ';
        $parsed_date = date_create_from_format('Y-m-d H:i:s', $event->event->start_date);
        echo '<span class="uee_event_date">'.$parsed_date->format('m-d-Y h:ia')."</span>";
        echo '</div>';
    }
    echo '</div>';
}
