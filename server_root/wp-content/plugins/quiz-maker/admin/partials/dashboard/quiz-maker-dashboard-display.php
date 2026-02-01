<?php

$course_page_url = sprintf('?page=%s', 'quiz-maker');
// $course_add_new_url = sprintf('?page=%s&action=%s', 'quiz-maker', 'add');
$lessons_page_url = admin_url( 'edit.php?post_type=flessons' );
// $new_lessons_page_url = admin_url( 'post-new.php?post_type=flessons' );
// $general_settings_page_url = sprintf('?page=%s&fox_lms_tab=tab4', 'quiz-maker-settings');

$quiz_page_url = sprintf('?page=%s', 'quiz-maker');
// $add_new_url = sprintf('?page=%s&action=%s', 'quiz-maker', 'add');
$questions_page_url = sprintf('?page=%s', 'quiz-maker-questions');
$new_questions_page_url = sprintf('?page=%s&action=%s', 'quiz-maker-questions', 'add');

?>
<div class="wrap">
    <!-- Hero Section -->
    <section class="quiz-maker-hero">
        <div class="quiz-maker-hero-container">
            <svg width="80" height="80" viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M35.8471 0.17603C26.7357 1.06007 17.1198 5.85914 10.8458 12.5841C3.68906 20.2563 0.000342535 29.6019 0.000342535 40.0841C-0.031185 48.4509 2.11269 55.4285 6.84182 62.5323C12.7059 71.3096 22.1642 77.4979 32.9466 79.5501C36.0994 80.15 42.8463 80.15 46.598 79.5501C61.7312 77.0874 74.3423 65.7843 78.5039 50.8504C82.5079 36.5479 78.3463 21.4561 67.5638 11.1318C61.1322 4.9751 52.5252 0.996924 44.1704 0.302322C39.3467 -0.0765521 38.59 -0.0765521 35.8471 0.17603ZM45.8098 12.7736C55.205 14.8574 62.4564 21.0457 66.019 29.9808C68.0052 34.9693 68.4781 39.9578 67.4693 45.2304C66.8702 48.2614 64.6003 49.9664 61.6051 49.6191C59.9657 49.4296 58.7677 48.6719 57.9164 47.188C57.412 46.3355 57.3174 45.6725 57.412 43.7465C57.6642 38.2844 57.2228 35.0956 55.8041 32.2856C54.6691 30.0123 51.8631 26.7919 49.8454 25.4659C46.7872 23.4136 44.864 22.8137 40.7654 22.6243C37.4866 22.498 36.9821 22.5612 34.523 23.3505C27.2086 25.75 22.4164 32.2856 22.4164 39.9262C22.4164 45.1357 23.9928 48.8613 27.7446 52.5553C31.8431 56.5651 34.9013 57.6701 41.8058 57.5438C46.7872 57.4807 47.1655 57.607 48.5843 59.5645C50.2237 61.8377 49.6562 65.1213 47.3862 66.6368C45.2108 68.0892 38.0225 68.4049 33.2934 67.2683C25.6007 65.3739 18.507 59.7539 15.039 52.7132C12.769 48.1351 12.17 45.3883 12.2015 39.7683C12.2015 35.5692 12.2961 34.7167 13.0527 32.254C16.7415 20.2248 27.3347 12.2368 39.7566 12.1737C41.932 12.1737 44.0128 12.3631 45.8098 12.7736ZM50.4444 44.1885C51.2641 44.378 52.5883 45.4514 55.9302 48.735C62.2672 54.9549 64.0643 57.0387 64.348 58.6173C64.6948 60.3538 64.2534 61.6167 62.8662 63.0375C60.9746 64.995 58.6415 65.216 56.4661 63.7005C56.0248 63.3848 53.0927 60.5117 49.9715 57.2913C45.4 52.6185 44.1704 51.1977 43.8867 50.1874C43.4453 48.6087 43.8867 46.8722 45.0532 45.5777C45.936 44.599 48.0483 43.6518 48.8995 43.8728C49.1518 43.936 49.8454 44.0938 50.4444 44.1885Z" fill="#E85011"/>
            </svg>


            <h2 class="quiz-maker-hero-title"><?php echo esc_html__("Welcome to Quiz Maker", 'quiz-maker'); ?></h2>
            <p class="quiz-maker-hero-subtitle"><?php echo esc_html__("Easily create, customize, and manage engaging quizzes for your WordPress website", 'quiz-maker'); ?></p>
            <div class="quiz-maker-hero-buttons">
                <a class="quiz-maker-btn quiz-maker-btn-secondary" href="<?php echo esc_url($questions_page_url); ?>">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g clip-path="url(#clip0_158_47)">
                            <path d="M10.0003 18.3334C14.6027 18.3334 18.3337 14.6024 18.3337 10C18.3337 5.39765 14.6027 1.66669 10.0003 1.66669C5.39795 1.66669 1.66699 5.39765 1.66699 10C1.66699 14.6024 5.39795 18.3334 10.0003 18.3334Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M7.5752 7.49999C7.77112 6.94304 8.15782 6.47341 8.66682 6.17426C9.17583 5.87512 9.77427 5.76577 10.3562 5.86558C10.9381 5.96539 11.4659 6.26792 11.8461 6.71959C12.2263 7.17126 12.4344 7.74292 12.4335 8.33332C12.4335 9.99999 9.93353 10.8333 9.93353 10.8333" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M10 14.1667H10.0088" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </g>
                        <defs>
                            <clipPath id="clip0_158_47">
                                <rect width="20" height="20" fill="white" />
                            </clipPath>
                        </defs>
                    </svg>
                    <?php echo esc_html__("Create Question", 'quiz-maker'); ?>
                </a>
                <a class="quiz-maker-btn quiz-maker-btn-primary" href="<?php echo esc_url($quiz_page_url); ?>">
                    <svg width="16" height="20" viewBox="0 0 16 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M13.9495 7.91656V5.66656C13.9495 4.26644 13.9495 3.56636 13.6812 3.03159C13.4452 2.56119 13.0687 2.17874 12.6054 1.93905C12.0789 1.66656 11.3896 1.66656 10.011 1.66656H4.75975C3.38116 1.66656 2.69186 1.66656 2.16531 1.93905C1.70215 2.17874 1.32558 2.56119 1.08958 3.03159C0.821289 3.56636 0.821289 4.26644 0.821289 5.66656V14.3333C0.821289 15.7334 0.821289 16.4334 1.08958 16.9683C1.32558 17.4386 1.70215 17.8211 2.16531 18.0608C2.69186 18.3333 3.38116 18.3333 4.75975 18.3333H9.02642M9.02642 9.16656H4.10334M5.74436 12.4999H4.10334M10.6674 5.83324H4.10334M11.0777 12.5018C11.2223 12.0844 11.5076 11.7324 11.8832 11.5082C12.2588 11.284 12.7004 11.2021 13.1299 11.2769C13.5593 11.3517 13.9487 11.5784 14.2293 11.9169C14.5098 12.2554 14.6634 12.6839 14.6628 13.1263C14.6628 14.3754 12.818 14.9999 12.818 14.9999M12.8417 17.4999H12.85" stroke="white" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>

                    <?php echo esc_html__("Create Quiz", 'quiz-maker'); ?>
                </a>
            </div>
        </div>
    </section>

    <!-- Quick Start Steps Section -->
    <section class="quiz-maker-steps">
        <div class="quiz-maker-steps-container">
            <h2 class="quiz-maker-steps-title"><?php echo esc_html__("Quick Setup Guide", 'quiz-maker'); ?></h2>
            <div class="quiz-maker-steps-content">
                <div class="quiz-maker-steps-list">
                    <h3 class="quiz-maker-steps-sub-title"><?php echo esc_html__("4 Simple Steps", 'quiz-maker'); ?></h3>
                    <ol class="quiz-maker-ordered-list">
                        <li class="quiz-maker-step-item">
                            <div class="quiz-maker-step-number">1</div>
                            <div class="quiz-maker-step-text">
                                <p class="quiz-maker-step-title"><?php echo esc_html__("Create questions", 'quiz-maker'); ?></p>
                            </div>
                        </li>
                        <li class="quiz-maker-step-item">
                            <div class="quiz-maker-step-number">2</div>
                            <div class="quiz-maker-step-text">
                                <p class="quiz-maker-step-title"><?php echo esc_html__("Create quiz", 'quiz-maker'); ?></p>
                            </div>
                        </li>
                        <li class="quiz-maker-step-item">
                            <div class="quiz-maker-step-number">3</div>
                            <div class="quiz-maker-step-text">
                                <p class="quiz-maker-step-title"><?php echo esc_html__("Insert questions", 'quiz-maker'); ?></p>
                            </div>
                        </li>
                        <li class="quiz-maker-step-item">
                            <div class="quiz-maker-step-number">4</div>
                            <div class="quiz-maker-step-text">
                                <p class="quiz-maker-step-title"><?php echo esc_html__("Copy/paste shortcode", 'quiz-maker'); ?></p>
                            </div>
                        </li>
                    </ol>
                </div>
                <div class="quiz-maker-video-container">
                    <div class="quiz-maker-video-wrapper">
                        <div class="quiz-maker-create-course-youtube-video">
                            <div class="ays-quiz-youtube-placeholder quiz-maker-youtube-placeholder" data-video-id="AUHZrVcBrMU">
                                <img src="<?php echo esc_url(AYS_QUIZ_ADMIN_URL .'/images/youtube/create-quiz-on-wordpress-480.webp'); ?>" width="480" height="265">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Resources Section -->
    <section class="quiz-maker-video-resources">
        <div class="quiz-maker-video-resources-container">
            <div class="quiz-maker-video-resources-header">
                <h2 class="quiz-maker-video-resources-title"><?php echo esc_html__("Learn with Video", 'quiz-maker'); ?></h2>
                <p class="quiz-maker-video-resources-subtitle"><?php echo esc_html__("Watch our comprehensive video tutorials to master Quiz Maker quickly", 'quiz-maker'); ?></p>
            </div>
            <div class="quiz-maker-video-cards">
                <div class="quiz-maker-video-row">
                    <a href="https://youtu.be/FnTVnwnqXBU" target="_blank" class="quiz-maker-video-card">
                        <div class="quiz-maker-video-card-content">
                            <div class="quiz-maker-video-card-icon">
                                <svg class="quiz-maker-play-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="6 3 20 12 6 21 6 3"></polygon>
                                </svg>
                            </div>
                            <div class="quiz-maker-video-card-text">
                                <div class="quiz-maker-video-card-header">
                                    <h3 class="quiz-maker-video-card-title"><?php echo esc_html__("How to Create a WordPress Quiz", 'quiz-maker'); ?></h3>
                                    <svg class="quiz-maker-external-link" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                                <p class="quiz-maker-video-card-description"><?php echo esc_html__("Learn how to create your first WordPress quiz and get acquainted with all available features", 'quiz-maker'); ?></p>
                                <span class="quiz-maker-video-duration"><?php echo esc_html__("5 min", 'quiz-maker'); ?></span>
                            </div>
                        </div>
                    </a>

                    <a href="https://youtu.be/3YFZyy85Kjg" target="_blank" class="quiz-maker-video-card">
                        <div class="quiz-maker-video-card-content">
                            <div class="quiz-maker-video-card-icon">
                                <svg class="quiz-maker-play-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="6 3 20 12 6 21 6 3"></polygon>
                                </svg>
                            </div>
                            <div class="quiz-maker-video-card-text">
                                <div class="quiz-maker-video-card-header">
                                    <h3 class="quiz-maker-video-card-title"><?php echo esc_html__("WordPress Quiz Plugin Overview", 'quiz-maker'); ?></h3>
                                    <svg class="quiz-maker-external-link" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                                <p class="quiz-maker-video-card-description"><?php echo esc_html__("Get general overview for the WordPress Quiz Maker plugin", 'quiz-maker'); ?></p>
                                <span class="quiz-maker-video-duration"><?php echo esc_html__("16 min", 'quiz-maker'); ?></span>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="quiz-maker-video-row">
                    <a href="https://youtu.be/ok6f59iV_R0" target="_blank" class="quiz-maker-video-card">
                        <div class="quiz-maker-video-card-content">
                            <div class="quiz-maker-video-card-icon">
                                <svg class="quiz-maker-play-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="6 3 20 12 6 21 6 3"></polygon>
                                </svg>
                            </div>
                            <div class="quiz-maker-video-card-text">
                                <div class="quiz-maker-video-card-header">
                                    <h3 class="quiz-maker-video-card-title"><?php echo esc_html__("All Question Types Explained", 'quiz-maker'); ?></h3>
                                    <svg class="quiz-maker-external-link" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                                <p class="quiz-maker-video-card-description"><?php echo esc_html__("Learn about all the available question types in the Quiz Maker plugin", 'quiz-maker'); ?></p>
                                <span class="quiz-maker-video-duration"><?php echo esc_html__("19 min", 'quiz-maker'); ?></span>
                            </div>
                        </div>
                    </a>
                    <a href="https://youtu.be/AUHZrVcBrMU" target="_blank" class="quiz-maker-video-card">
                        <div class="quiz-maker-video-card-content">
                            <div class="quiz-maker-video-card-icon">
                                <svg class="quiz-maker-play-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polygon points="6 3 20 12 6 21 6 3"></polygon>
                                </svg>
                            </div>
                            <div class="quiz-maker-video-card-text">
                                <div class="quiz-maker-video-card-header">
                                    <h3 class="quiz-maker-video-card-title"><?php echo esc_html__("Create Quiz in One Minute", 'quiz-maker'); ?></h3>
                                    <svg class="quiz-maker-external-link" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15 3h6v6"></path>
                                        <path d="M10 14 21 3"></path>
                                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    </svg>
                                </div>
                                <p class="quiz-maker-video-card-description"><?php echo esc_html__("Set up your first quiz in one minute with an easy guide", 'quiz-maker'); ?></p>
                                <span class="quiz-maker-video-duration"><?php echo esc_html__("1 min", 'quiz-maker'); ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- What's New Section -->
    <section id="quiz-maker-what-is-new" class="quiz-maker-what-is-new-section">
        <div class="quiz-maker-what-is-new-container">
            <a href="https://wordpress.org/plugins/quiz-maker/#developers" class="quiz-maker-what-is-new-see-more-btn" target="_blank">
                <?php echo esc_html__('Show More', 'quiz-maker'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="quiz-maker-what-is-new-btn-icon">
                    <path d="M15 3h6v6"></path>
                    <path d="M10 14 21 3"></path>
                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                </svg>
            </a>
            
            <div class="quiz-maker-what-is-new-header">
                <h2 class="quiz-maker-what-is-new-title"><?php echo esc_html__('What’s New', 'quiz-maker'); ?></h2>
                <p class="quiz-maker-what-is-new-subtitle"><?php echo esc_html__('New Features Added in Free Version', 'quiz-maker'); ?></p>
            </div>
            
            <div class="quiz-maker-what-is-new-content">
                <div class="quiz-maker-what-is-new-version-badge"><?php echo esc_html__('New', 'quiz-maker'); ?></div>
                
                <ul class="quiz-maker-what-is-new-features-list">
                    <li class="quiz-maker-what-is-new-feature-item"><?php echo esc_html__('Answer image functionality', 'quiz-maker'); ?></li>
                    <li class="quiz-maker-what-is-new-feature-item"><?php echo esc_html__('Question report functionality', 'quiz-maker'); ?></li>
                    <li class="quiz-maker-what-is-new-feature-item"><?php echo esc_html__('Question Report Statistics in the Dashboard Widget', 'quiz-maker'); ?></li>
                    <li class="quiz-maker-what-is-new-feature-item"><?php echo esc_html__('Keyboard navigation option', 'quiz-maker'); ?></li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Help Section -->
    <section id="quiz-maker-help-types" class="quiz-maker-help-demos-section">
        <div class="quiz-maker-help-container">
            <div class="quiz-maker-help-max-width">
                <div class="quiz-maker-help-header">
                    <h2 class="quiz-maker-help-title"><?php echo esc_html__("Support & Resources", 'quiz-maker'); ?></h2>
                    <!-- <p class="quiz-maker-help-description">Explore different types of quizzes and interactive features</p> -->
                </div>
                <div class="quiz-maker-help-grid">
                    <div class="quiz-maker-help-card">
                        <div class="quiz-maker-help-card-header">
                            <div class="quiz-maker-help-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="quiz-maker-help-icon">
                                    <path d="M12 7v14"></path>
                                    <path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"></path>
                                </svg>
                            </div>
                            <h3 class="quiz-maker-help-card-title"><?php echo esc_html__("Documentation", 'quiz-maker'); ?></h3>
                        </div>
                        <div class="quiz-maker-help-card-content">
                            <p class="quiz-maker-help-card-description"><?php echo esc_html__("Access comprehensive guides and tutorials to master Quiz Maker.", 'quiz-maker'); ?></p>
                            <a href="https://quiz-plugin.com/docs/" class="quiz-maker-help-button" target="_blank">
                                <?php echo esc_html__("View Docs", 'quiz-maker'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="quiz-maker-help-button-icon">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14 21 3"></path>
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="quiz-maker-help-card">
                        <div class="quiz-maker-help-card-header">
                            <div class="quiz-maker-help-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="quiz-maker-help-icon">
                                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="9" cy="7" r="4"></circle>
                                    <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                </svg>
                            </div>
                            <h3 class="quiz-maker-help-card-title"><?php echo esc_html__("Community Forum", 'quiz-maker'); ?></h3>
                        </div>
                        <div class="quiz-maker-help-card-content">
                            <p class="quiz-maker-help-card-description"><?php echo esc_html__("Join discussions with other educators and get help from the community.", 'quiz-maker'); ?></p>
                            <a href="https://wordpress.org/support/plugin/quiz-maker/" class="quiz-maker-help-button" target="_blank">
                                <?php echo esc_html__("Join Forum", 'quiz-maker'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="quiz-maker-help-button-icon">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14 21 3"></path>
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="quiz-maker-help-card">
                        <div class="quiz-maker-help-card-header">
                            <div class="quiz-maker-help-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="quiz-maker-help-icon">
                                    <path d="M7.9 20A9 9 0 1 0 4 16.1L2 22Z"></path>
                                </svg>
                            </div>
                            <h3 class="quiz-maker-help-card-title"><?php echo esc_html__("Contact Support", 'quiz-maker'); ?></h3>
                        </div>
                        <div class="quiz-maker-help-card-content">
                            <p class="quiz-maker-help-card-description"><?php echo esc_html__("Get direct help from our support team for technical issues.", 'quiz-maker'); ?></p>
                            <a href="https://ays-pro.com/contact" class="quiz-maker-help-button" target="_blank">
                                <?php echo esc_html__("Get Help", 'quiz-maker'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="quiz-maker-help-button-icon">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14 21 3"></path>
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="quiz-maker-help-card">
                        <div class="quiz-maker-help-card-header">
                            <div class="quiz-maker-help-icon-container">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="quiz-maker-help-icon">
                                    <rect width="20" height="14" x="2" y="3" rx="2"></rect>
                                    <line x1="8" x2="16" y1="21" y2="21"></line>
                                    <line x1="12" x2="12" y1="17" y2="21"></line>
                                </svg>
                            </div>
                            <h3 class="quiz-maker-help-card-title"><?php echo esc_html__("Demo", 'quiz-maker'); ?></h3>
                        </div>
                        <div class="quiz-maker-help-card-content">
                            <p class="quiz-maker-help-card-description"><?php echo esc_html__("See Quiz Maker in action with our interactive demo and examples.", 'quiz-maker'); ?></p>
                            <a href="https://quiz-plugin.com/wordpress-quiz-plugin-free-demo/" class="quiz-maker-help-button" target="_blank">
                                <?php echo esc_html__("Try Demo", 'quiz-maker'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="quiz-maker-help-button-icon">
                                    <path d="M15 3h6v6"></path>
                                    <path d="M10 14 21 3"></path>
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="quiz-maker-review-settings" class="quiz-maker-review-settings-section">
        <div class="quiz-maker-review-settings-container">
            <p style="font-size:13px;text-align:center;font-style:italic;">
                <!-- <span style="margin-left:0px;margin-right:10px;" class="ays_heart_beat"><i class="ays_fa ays_fa_heart_o animated"></i></span> -->
                <span><?php echo esc_html__( "If you love our plugin, please do big favor and rate us on WordPress.org", 'quiz-maker'); ?></span> 
                <a target="_blank" class="ays-rated-link" href='https://wordpress.org/support/plugin/quiz-maker/reviews/'>
                    <span class="ays-dashicons ays-dashicons-star-empty"></span>
                    <span class="ays-dashicons ays-dashicons-star-empty"></span>
                    <span class="ays-dashicons ays-dashicons-star-empty"></span>
                    <span class="ays-dashicons ays-dashicons-star-empty"></span>
                    <span class="ays-dashicons ays-dashicons-star-empty"></span>
                </a>
                <!-- <span class="ays_heart_beat"><i class="ays_fa ays_fa_heart_o animated"></i></span> -->
            </p>
        </div>
    </section>
</div>

