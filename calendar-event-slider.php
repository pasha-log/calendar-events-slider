<?php
/*
Plugin Name: Calendar Event Slider
Description: A plugin that creates a carousel of events using glide.js and The Events Calendar plugin.
Version: 1.0
Author: Pasha Loguinov
*/

function calendar_event_slider() {
	global $wpdb;

	// Query for events
	$events = $wpdb->get_results("
    	SELECT p.ID, p.post_title, p.post_name, o.start_date
    	FROM " . $wpdb->prefix . "tec_occurrences o
    	INNER JOIN " . $wpdb->prefix . "posts p ON o.post_id = p.ID
    	WHERE p.post_name NOT LIKE '%__trashed'
    	ORDER BY o.start_date DESC
	");

    // Start the carousel HTML
    $html = '<div class="glide"><div class="glide__track" data-glide-el="track"><ul class="glide__slides">';

    // Loop through the events
    foreach ($events as $event) {
        // Get the data points
        $permalink = home_url('/event/' . $event->post_name);
        $img_url = get_the_post_thumbnail_url($event->ID);
        $date = date('j M', strtotime($event->start_date));
        $title = $event->post_title;

        // Add the slide HTML
        $html .= '<li class="glide__slide"><a href="' . $permalink . '"><img src="' . $img_url . '" alt="' . $title . '"><div class="date-tag"><span class="day">' . explode(' ', $date)[0] . '</span><span class="month">' . explode(' ', $date)[1] . '</span></div><div class="slide-text">' . $title . '</div></a></li>';
    }

    // Add the arrow buttons
    $html .= '</ul></div>
                    <div class="glide__arrows" data-glide-el="controls">
                        <button class="glide__arrow glide__arrow--left" data-glide-dir="<">
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve" fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <line style="fill:none;stroke:#fff;stroke-width:2;stroke-miterlimit:10;" x1="6" y1="16" x2="28" y2="16"></line>
                                    <polyline style="fill:none;stroke:#fff;stroke-width:2;stroke-miterlimit:10;" points="11.515,22 5.515,16 11.515,10 "></polyline>
                                </g>
                            </svg>
                        </button>
                        <button class="glide__arrow glide__arrow--right" data-glide-dir=">">                
                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" xml:space="preserve" fill="#000000">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <line style="fill:none;stroke:#fff;stroke-width:2;stroke-miterlimit:10;" x1="26" y1="16" x2="4" y2="16"></line>
                                    <polyline style="fill:none;stroke:#fff;stroke-width:2;stroke-miterlimit:10;" points="20.485,10 26.485,16 20.485,22 "></polyline>
                                </g>
                            </svg>
                        </button>
                    </div>';

    // End the carousel HTML
    $html .= '</div>';

    // Return the HTML
    return $html;
}

// Create a shortcode for the carousel
add_shortcode('calendar_event_slider', 'calendar_event_slider');

// Enqueue the CSS
function calendar_event_slider_enqueue_styles() {
    wp_enqueue_style('calendar_event_slider_styles', plugin_dir_url(__FILE__) . 'css/style.css');
}
add_action('wp_enqueue_scripts', 'calendar_event_slider_enqueue_styles');
?>