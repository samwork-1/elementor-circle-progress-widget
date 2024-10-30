<?php
/**
 * Plugin Name: Elementor Circle Progress Widget
 * Description: Adds a custom Elementor widget with a circular progress bar and centered text.
 * Version: 1.0
 * Author: Sameer Kazmi
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

// Include the widget file
function register_circle_progress_widget($widgets_manager) {
    require_once(__DIR__ . '/widgets/circle-progress-widget.php');
    $widgets_manager->register(new \Circle_Progress_Widget());
}

add_action('elementor/widgets/widgets_registered', 'register_circle_progress_widget');

// Enqueue CSS for the widget
function circle_progress_widget_styles() {
    wp_enqueue_style('circle-progress-style', plugins_url('/css/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'circle_progress_widget_styles');
