This plugin will create a list of upcoming events from your EventBrite account.

Set your EventBrite App Key and organizer ID in the Settings tab.
Your App Key is in your EventBrite account under App Management.  
If you don't have one, you will need to create one.
Your Organizer ID is on the EventBrite page "Organizer Profile".
It's the number at the end of your "Organizer Page URL"

This plugin will show all events that EventBrite returns.
However, if you check the "Don't show past events?" option all events before today are masked.
If you would like the picture returned from EventBrite to show in the listing, check the "Display Picture?" option.

Use the shortcode "upcoming_events_for_eventbrite" to render the events.
[upcoming_events_for_eventbrite]

Optionally, you can specify the maximum number of events to display using the max_count. Default is 3.
[upcoming_events_for_eventbrite max_count="6"]

The plugin is hosted in the wordpress plugin library here:  
https://wordpress.org/plugins/upcoming-events-for-eventbrite/

This was created for a non-profit at Cleveland GiveCamp 2015.
