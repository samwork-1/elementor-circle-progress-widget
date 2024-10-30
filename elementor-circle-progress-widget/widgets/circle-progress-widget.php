<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Circle_Progress_Widget extends Widget_Base {

    public function get_name() {
        return 'circle_progress';
    }

    public function get_title() {
        return __('Circle Progress', 'text-domain');
    }

    public function get_icon() {
        return 'eicon-skill-bar';
    }

    public function get_categories() {
        return ['general'];
    }

protected function _register_controls() {
    $this->start_controls_section(
        'section_progress',
        [
            'label' => __('Progress Settings', 'text-domain'),
        ]
    );

    $this->add_control(
        'progress_value',
        [
            'label' => __('Progress Value (%)', 'text-domain'),
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 75],
            'range' => ['px' => ['min' => 0, 'max' => 100]],
        ]
    );

    $this->add_control(
        'progress_color',
        [
            'label' => __('Progress Bar Color', 'text-domain'),
            'type' => Controls_Manager::COLOR,
            'default' => '#4caf50',
        ]
    );

    $this->add_control(
        'progress_size',
        [
            'label' => __('Progress Bar Size', 'text-domain'),
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 150],
            'range' => ['px' => ['min' => 50, 'max' => 300]],
        ]
    );

    $this->add_control(
        'stroke_width',
        [
            'label' => __('Stroke Width', 'text-domain'),
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 10],
            'range' => ['px' => ['min' => 1, 'max' => 20]],
        ]
    );

    $this->add_control(
        'text_color',
        [
            'label' => __('Text Color', 'text-domain'),
            'type' => Controls_Manager::COLOR,
            'default' => '#000000',
        ]
    );

    $this->add_control(
        'text_size',
        [
            'label' => __('Text Size', 'text-domain'),
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 20],
            'range' => ['px' => ['min' => 10, 'max' => 50]],
        ]
    );

    $this->add_control(
        'progress_text',
        [
            'label' => __('Center Text', 'text-domain'),
            'type' => Controls_Manager::TEXT,
            'default' => __('75%', 'text-domain'),
        ]
    );
      $this->add_control(
        'dot_position',
        [
            'label' => __('Dot Position (%)', 'text-domain'),
            'type' => Controls_Manager::SLIDER,
            'default' => ['size' => 75], // Default dot position at 75%
            'range' => ['px' => ['min' => 0, 'max' => 100]],
        ]
    );

    $this->end_controls_section();
}

protected function render() {
    $widget_id = $this->get_id(); // Get the unique ID for the current widget instance
    $settings = $this->get_settings_for_display();
    $progress_value = $settings['progress_value']['size'];
    $progress_color = $settings['progress_color'];
    $progress_size = $settings['progress_size']['size'];
    $stroke_width = $settings['stroke_width']['size'];
    $text_color = $settings['text_color'];
    $text_size = $settings['text_size']['size'];
    
    // New dot position based on user input
    $dot_position_value = $settings['dot_position']['size'];

    // Calculate position for the end dot
    $radius = (75 - $stroke_width / 2);
    $circumference = 2 * pi() * $radius;
    $offset = $circumference * (1 - $progress_value / 100);
    
    // Calculate the position of the dot based on user-defined dot position
    $angle = (360 * ($dot_position_value / 100));
    $dot_x = 75 + $radius * cos(deg2rad($angle - 90));
    $dot_y = 75 + $radius * sin(deg2rad($angle - 90));

    ?>
    <div class="circle-progress-container" style="position: relative; width: <?php echo esc_attr($progress_size); ?>px; height: <?php echo esc_attr($progress_size); ?>px;">
        <svg class="circle-progress-svg" width="<?php echo esc_attr($progress_size); ?>" height="<?php echo esc_attr($progress_size); ?>" viewBox="0 0 150 150" style="cursor: pointer;">
            <!-- Background Circle -->
            <circle class="circle-bg" cx="75" cy="75" r="<?php echo esc_attr($radius); ?>" stroke="#e0e0e0" stroke-width="<?php echo esc_attr($stroke_width); ?>" fill="none"></circle>
            
            <!-- Progress Circle -->
            <circle class="circle-progress-<?php echo esc_attr($widget_id); ?>" cx="75" cy="75" r="<?php echo esc_attr($radius); ?>" stroke="<?php echo esc_attr($progress_color); ?>" stroke-width="<?php echo esc_attr($stroke_width); ?>" fill="none"
                stroke-linecap="round" style="stroke-dasharray: <?php echo esc_attr($circumference); ?>; stroke-dashoffset: <?php echo esc_attr($offset); ?>; transition: stroke-dashoffset 1s ease;"></circle>
            
            <!-- Dot at the Specified Position -->
            <circle class="progress-dot-<?php echo esc_attr($widget_id); ?>" cx="<?php echo esc_attr($dot_x); ?>" cy="<?php echo esc_attr($dot_y); ?>" r="5" fill="#ffff" class="draggable-dot"></circle>
        </svg>
        
        <!-- Centered Text -->
        <div class="circle-progress-text-<?php echo esc_attr($widget_id); ?>" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: <?php echo esc_attr($text_color); ?>; font-size: <?php echo esc_attr($text_size); ?>px; font-weight: bold;">
            <?php echo esc_html($settings['progress_text']); ?>
        </div>
    </div>
    <script type="text/javascript">
           (function($) {
        $(document).ready(function() {
            var widgetID = '<?php echo esc_js($widget_id); ?>'; // Get the widget ID
            var progressValue = <?php echo esc_js($progress_value); ?>;
            var radius = 65; // radius of the circle
            var circumference = 2 * Math.PI * radius;

            // Use unique selectors based on the widget ID
            var dot = document.querySelector('.progress-dot-' + widgetID);
            var progressCircle = document.querySelector('.circle-progress-' + widgetID);
            
            // Initial offset for 0% progress
            var initialOffset = circumference;
            progressCircle.style.strokeDashoffset = initialOffset;

            // Set timeout to start the animation after DOM is ready
            setTimeout(function() {
                var offset = circumference * (1 - progressValue / 100);
                progressCircle.style.strokeDashoffset = offset;

                // Update the text in the center
                document.querySelector('.circle-progress-text-' + widgetID).textContent = progressValue + '%';

                // Dot position animation
                var dotPositionValue = <?php echo esc_js($dot_position_value); ?>;
                var angle = (360 * (dotPositionValue / 100));
                var dotX = 75 + radius * Math.cos((angle - 0) * (Math.PI / 180));
                var dotY = 75 + radius * Math.sin((angle - 0) * (Math.PI / 180));
                dot.setAttribute('cx', dotX);
                dot.setAttribute('cy', dotY);
            }, 100); // Start animation after 100 milliseconds
        });
    })(jQuery);
    </script>
    <?php
}


}