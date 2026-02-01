<?php

namespace Wpxero\Marqueex\Integrations\Elementor\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Icons_Manager;
use Wpxero\Marqueex\Traits\AnimatedHeading\TitleControls;
use Wpxero\Marqueex\Traits\AnimatedHeading\AnimationControls;
use Wpxero\Marqueex\Traits\AnimatedHeading\TextStylesControls;
use Wpxero\Marqueex\Traits\AnimatedHeading\AnimatedTextEffectControls;

if (!defined('ABSPATH')) {
    exit;
}

/**
 * MarqueeX Animated Heading Widget for Elementor
 */
class AnimatedHeading extends Widget_Base {

    use TitleControls;
    use AnimationControls;
    use TextStylesControls;
    use AnimatedTextEffectControls;

    /**
     * Get widget name
     */
    public function get_name() {
        return 'marqueex-animated-heading';
    }

    /**
     * Get widget title
     */
    public function get_title() {
        return __('Animated Heading', 'marqueex');
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
            'marqueex-animated-heading'
        ];
    }

    public function get_style_depends() {
        return ['marqueex-animated-heading-style'];
    }

    /**
     * Get widget keywords
     */
    public function get_keywords() {
        return ['animation', 'animated', 'heading', 'head', 'header', 'marquee'];
    }

    /**
     * Register widget controls
     */
    protected function register_controls() {
        $this->register_title_section_controls();
        $this->register_animation_section_controls();
        $this->register_text_styles_section_controls();
        $this->register_animated_text_effect_section_controls();
    }

    /**
     * Render widget output on the frontend
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        $tag = $settings['marqueex_heading_tag'] ?? 'h2';
        $before = $settings['marqueex_before_text'] ?? '';
        $after = $settings['marqueex_after_text'] ?? '';
        $texts = $settings['marqueex_animated_texts'] ?? [];
        $animation = $settings['marqueex_animation_type'] ?? '';
        $animationSpeed = $settings['marqueex_animation_speed'] ?? '';
        $isPauseOnHover = $settings['marqueex_animation_pause_on_hover'] ?? '';
        $textEffectType = $settings['marqueex_animated_text_effect_type'] ?? '';
        $pauseBetweenWords = $settings['marqueex_pause_between_words'] ?? '';
        $pauseAfterTyped = $settings['marqueex_pause_after_typed'] ?? '';
        $delayPerWord = $settings['marqueex_delay_per_word'] ?? '';
        $lineType = $settings['marqueex_line_type'] ?? '';
        $delayBeforeErase = $settings['marqueex_delay_before_erase'] ?? '';

        // Check if MarqueeX Pro is active
        $is_pro_active = class_exists('Wpxero\MarqueexPro\Plugin');

        // Pro animation types
        $pro_animations = ['rotation-3d', 'construct', 'twist', 'line', 'wave', 'swing', 'tilt', 'lean'];
        $pro_effects = ['marqueex-gradient-text', 'marqueex-masked-text'];

        // Fallback to basic animation if Pro feature is used without Pro plugin
        if (!$is_pro_active && in_array($animation, $pro_animations)) {
            $animation = 'slide';
        }

        // Fallback to basic effect if Pro feature is used without Pro plugin
        if (!$is_pro_active && in_array($textEffectType, $pro_effects)) {
            $textEffectType = 'fill';
        }

        $slideDirection = '';
        if ($animation === 'slide') {
            $slideDirection = $settings['marqueex_slide_vertical_direction'] ?? '';
        } elseif ($animation === 'slide-horizontal') {
            $slideDirection = $settings['marqueex_slide_horizontal_direction'] ?? '';
        }
?>
        <div class="marqueex-animated-heading marqueex-animation-on">
            <<?php echo esc_html($tag); ?> class="marqueex-heading <?php echo esc_attr($animation); ?><?php echo ($animation === 'line') ? ' ' . esc_attr($lineType) : ''; ?>">
                <?php if ($before): ?>
                    <span class="marqueex-before-text"><?php echo esc_html($before); ?></span>
                <?php endif; ?>

                <?php if (in_array($animation, ['slide', 'slide-horizontal', 'rotation-3d'])): ?>
                    <div class="marqueex-animated-text-container">
                    <?php endif; ?>

                    <?php if (!empty($texts)): ?>
                        <span class="marqueex-texts-wrapper <?php echo esc_attr($textEffectType); ?>"
                            data-animation="<?php echo esc_attr($animation); ?>"
                            data-animation-speed="<?php echo esc_attr($animationSpeed); ?>"
                            data-is-pause-on-hover="<?php echo esc_attr($isPauseOnHover); ?>"
                            <?php if ($animation === 'construct'): ?>
                            data-pause-between-words="<?php echo esc_attr($pauseBetweenWords); ?>"
                            <?php endif; ?>
                            <?php if ($animation === 'typing'): ?>
                            data-pause-after-typed="<?php echo esc_attr($pauseAfterTyped); ?>"
                            <?php endif; ?>
                            <?php if (in_array($animation, ['slide', 'slide-horizontal'])): ?>
                            data-delay-per-word="<?php echo esc_attr($delayPerWord); ?>"
                            data-slide-direction="<?php echo esc_attr($slideDirection); ?>"
                            <?php endif; ?>
                            <?php if ($animation === 'line'): ?>
                            data-line-type="<?php echo esc_attr($lineType); ?>"
                            data-delay-before-erase="<?php echo esc_attr($delayBeforeErase); ?>"
                            <?php endif; ?>>
                            <?php foreach ($texts as $item): ?>
                                <span class="marqueex-animated-text"><?php echo esc_html($item['marqueex_animated_text'] ?? ''); ?></span>
                            <?php endforeach; ?>

                            <?php if ($animation === 'line'): ?>
                                <svg
                                    height="100%"
                                    width="100%"
                                    class="marqueex-animated-lines"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 500 200"
                                    preserveAspectRatio="none"></svg>
                            <?php endif; ?>
                        </span>
                    <?php endif; ?>

                    <?php if (in_array($animation, ['slide', 'slide-horizontal', 'rotation-3d'])): ?>
                    </div>
                <?php endif; ?>

                <?php if ($after): ?>
                    <span class="marqueex-after-text"><?php echo esc_html($after); ?></span>
                <?php endif; ?>
            </<?php echo esc_html($tag); ?>>
        </div>
<?php
    }
}
