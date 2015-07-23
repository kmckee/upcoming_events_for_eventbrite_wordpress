<?php
/*
Plugin Name: Eventbrite Upcoming Events 
Plugin URI:  http://www.github.com/kmckee/eventbrite_upcoming_events
Description: This plugin adds a widget that displays a list of upcoming events from eventbrite.
Version:     0.1 
Author:      Kyle McKee 
Author URI:  http://aptobits.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die( 'Ah ah ah, you didn\'t say the magic word' );

$app_key_id = 'eue_app_key';
$organizer_id = 'eue_organizer_id';

add_option($app_key_id);
add_option($organizer_id);

add_action( 'admin_menu', 'eventbrite_upcoming_plugin_menu' );
function eventbrite_upcoming_plugin_menu() {
    add_options_page( 'EventBrite Upcoming Events Options', 'Eventbrite Upcoming Events', 'manage_options', 'eventbrite_upcoming_events', 'eventbrite_upcoming_options' );
}
function eventbrite_upcoming_options() {
    if (!current_user_can('manage_options'))
    {
        wp_die( __('You do not have sufficient permissions to access this page.') );
    }

    $hidden_field_name = 'eue_submit_hidden';
    $app_key_id = 'eue_app_key';
    $organizer_id = 'eue_organizer_id';

    $app_key_val = get_option($app_key_id);
    $organizer_val = get_option($organizer_id);

    if(isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
        $app_key_val = $_POST[$app_key_id ];
        $organizer_val = $_POST[ $organizer_id ];
        update_option( $app_key_id, $app_key_val );
        update_option( $organizer_id, $organizer_val );
        ?>
            <div class="updated"><p><strong><?php _e('Settings saved.', 'menu-test' ); ?></strong></p></div>
        <?php
    }

    echo '<div class="wrap">';
    echo "<h2>" . __( 'Eventbrite Upcoming Events Plugin Settings', 'menu-test' ) . "</h2>";
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

add_shortcode('eventbrite_upcoming_events', 'inject_eventbrite_events');
function inject_eventbrite_events() {
    $app_key = get_option('eue_app_key');
    $organizer_id = get_option('eue_organizer_id');
    $url = "https://www.eventbrite.com/json/organizer_list_events?app_key=".$app_key."&id=".$organizer_id;
    $json = file_get_contents($url);
    $event_data = json_decode($json);
    echo '<div class="eue_events">';
    foreach ($event_data->events as $event) {
        echo '<div class="eue_event">';
        echo '<a href="'.$event->event->url.'"class="eue_event_title">'.$event->event->title.'</a> ';
        $parsed_date = date_create_from_format('Y-m-d H:i:s', $event->event->start_date);
        echo '<span class="eue_event_date">'.$parsed_date->format('m-d-Y h:ia')."</span>";
        echo '</div>';
    }
    echo '</div>';
}
