<?php

namespace Wpxero\Marqueex\Integrations\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Icons_Manager;
use Wpxero\Marqueex\Traits\AnimatedWordRoller\ContentControls;
use Wpxero\Marqueex\Traits\AnimatedWordRoller\AdditionalOptionsControls;
use Wpxero\Marqueex\Traits\AnimatedWordRoller\StyleControls;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * MarqueeX Animated Word Roller Widget for Elementor
 */
class AnimatedWordRoller extends Widget_Base {

    use ContentControls;
    use AdditionalOptionsControls;
    use StyleControls;

    /**
     * Get widget name
     */
    public function get_name() {
        return 'marqueex-animated-word-roller';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Animated Word Roller', 'marqueex');
    }

    /**
     * Get widget icon
     */
    public function get_icon() {
        return 'eicon-animated-headline eicon-marqueex';
    }

    /**
     * Get widget categories
     */
    public function get_categories() {
        return ['marqueex'];
    }

    /**
     * Enqueue widget scripts and styles
     */
    public function get_script_depends() {
        return [
            'marqueex-animated-word-roller'
        ];
    }

    public function get_style_depends() {
        return ['marqueex-animated-word-roller-style'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['animated word roller', 'animated word', 'animated', 'word', 'smooth', 'roller', 'rotation', 'scroll', 'marquee'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        $this->register_content_section_controls();
        $this->register_additional_options_section_controls();
        $this->register_style_section_controls();
    }

    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $total_words = !empty($settings['marqueex_word_roller_words']) ? count($settings['marqueex_word_roller_words']) : 0;
        $rotation_delay = !empty($settings['marqueex_word_roller_delay']) ? $settings['marqueex_word_roller_delay'] : 1;
        $visible_words = !empty($settings['marqueex_word_roller_visible_words']) ? $settings['marqueex_word_roller_visible_words'] : 5;
        $html_tag = isset($settings['marqueex_word_roller_html_tag']) ? $settings['marqueex_word_roller_html_tag'] : 'h2';
        $animation_type = $settings['marqueex_word_roller_animation_type'] ?? 'vertical';
        $pause_on_hover = $settings['marqueex_word_roller_pause_on_hover'] ?? 'no';

        // Check if MarqueeX Pro is active
        $is_pro_active = class_exists('Wpxero\MarqueexPro\Plugin');

        // Pro animation types
        $pro_animations = ['horizontal', 'fade', 'slide', 'zoom'];

        // Fallback to basic animation if Pro feature is used without Pro plugin
        if (!$is_pro_active && in_array($animation_type, $pro_animations)) {
            $animation_type = 'vertical';
        }
?>
        <div class="marqueex-word-roller-main-wrapper">
            <<?php echo esc_attr($html_tag); ?> class="marqueex-word-roller-container">
                <?php if (!empty($settings['marqueex_word_roller_title'])): ?>
                    <span class="marqueex-fixed-text marqueex-word-roller-heading">
                        <?php echo esc_html($settings['marqueex_word_roller_title']); ?>
                    </span>
                <?php endif; ?>

                <div class="marqueex-text-rotator-container">
                    <div style="opacity: 0;" class="marqueex-vertical-scroll-track"
                        data-total-words="<?php echo esc_attr($total_words); ?>"
                        data-rotation-delay="<?php echo esc_attr($rotation_delay); ?>"
                        data-visible-words="<?php echo esc_attr($visible_words); ?>"
                        data-animation-type="<?php echo esc_attr($animation_type); ?>"
                        data-pause-on-hover="<?php echo esc_attr($pause_on_hover); ?>">

                        <?php
                        if (!empty($settings['marqueex_word_roller_words'])):
                            foreach ($settings['marqueex_word_roller_words'] as $item):
                        ?>
                                <div class="marqueex-rotate-text">
                                    <div class="marqueex-rotating-word">
                                        <?php echo esc_html($item['marqueex_word_roller_text']); ?>
                                    </div>
                                    <?php
                                    if (!empty($item['marqueex_word_roller_icon']['value'])) {
                                        Icons_Manager::render_icon(
                                            $item['marqueex_word_roller_icon'],
                                            ['aria-hidden' => 'true']
                                        );
                                    }
                                    ?>
                                </div>
                        <?php
                            endforeach;
                        endif;
                        ?>

                    </div>
                </div>
            </<?php echo esc_attr($html_tag); ?>>
        </div>
<?php
    }
}
