<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <h1 id="ays-quiz-intro-title"><?php echo esc_html__('Please feel free to use our other awesome plugins!', 'quiz-maker'); ?></h1>
    <?php do_action('ays_quiz_sale_banner'); ?>
    <?php $this->output_about_addons(); ?>
    <div class="ays-quiz-see-all">
        <a href="https://ays-pro.com/wordpress" target="_blank" class="ays-quiz-all-btn"><?php echo esc_html__('See All Plugins', 'quiz-maker'); ?></a>
    </div>

    <!-- <p class="text-center coming-soon">And more and more is <span>Coming Soon</span></p> -->
</div>