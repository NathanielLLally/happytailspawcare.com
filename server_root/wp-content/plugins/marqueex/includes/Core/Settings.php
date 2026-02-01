<?php

namespace Wpxero\Marqueex\Core;

use Wpxero\Marqueex\Traits\Singleton;

if (! defined('ABSPATH')) {
    exit;
}

// Import WordPress classes and functions
use WP_REST_Controller;
use WP_REST_Request;
use WP_REST_Response;
use WP_Error;

/**
 * Class Settings
 */
class Settings extends \WP_REST_Controller {
    use Singleton;
    /**
     * Namespace.
     *
     * @var string
     */
    protected $namespace = 'wpxero/marqueex/v';

    /**
     * Version.
     *
     * @var string
     */
    protected $version = '1';

    /**
     * Settings constructor.
     */
    public function __construct() {
        \add_action('rest_api_init', [$this, 'register_routes']);
        \add_action('init', [$this, 'init_default_settings']);
    }

    /**
     * Initialize default settings
     */
    public function init_default_settings() {
        $current_settings = \get_option('marqueex_settings', []);
        $default_settings = $this->get_default_settings();

        // Merge with existing settings, keeping existing values
        $merged_settings = array_merge($default_settings, $current_settings);

        if ($current_settings !== $merged_settings) {
            \update_option('marqueex_settings', $merged_settings);
        }
    }

    /**
     * Get default settings
     *
     * @return array
     */
    public function get_default_settings() {
        return [
            'builder_support' => [
                'gutenberg' => [
                    'enabled' => true,
                    'auto_detect' => true,
                    'elements' => [
                        'infinite_slider' => true,
                    ],
                ],
                'elementor' => [
                    // 'enabled' =>  $this->is_elementor_active(),
                    'enabled' => true,
                    'auto_detect' => true,
                    'elements' => [
                        'text_marquee' => true,
                        'news_ticker' => true,
                        'infinite_slider' => true,
                    ],
                ],
                'bricks' => [
                    'enabled' => false,
                    'auto_detect' => true,
                    'elements' => [
                        'text_marquee' => true,
                        'news_ticker' => true,
                        'infinite_slider' => true,
                    ],
                ],
                'shortcode' => [
                    'enabled' => true,
                    'auto_detect' => true,
                    'elements' => [
                        'text_marquee' => true,
                        'news_ticker' => true,
                        'infinite_slider' => true,
                    ],
                ],
            ],
            'performance' => [
                'lazy_loading' => true,
                'minify_css' => true,
                'cache_styles' => true,
            ],
            'compatibility' => [
                'theme_compatibility' => true,
                'plugin_compatibility' => true,
                'responsive_breakpoints' => [
                    'mobile' => 480,
                    'tablet' => 1024,
                    'desktop' => 1200,
                ],
            ],
        ];
    }

    /**
     * Register rest routes.
     */
    public function register_routes() {
        $namespace = $this->namespace . $this->version;

        // Update Settings.
        \register_rest_route(
            $namespace,
            '/update_settings/',
            [
                'methods'             => ['POST'],
                'callback'            => [$this, 'update_settings'],
                'permission_callback' => [$this, 'update_settings_permission'],
            ]
        );

        // Get Settings.
        \register_rest_route(
            $namespace,
            '/get_settings/',
            [
                'methods'             => ['GET'],
                'callback'            => [$this, 'get_settings'],
                'permission_callback' => [$this, 'get_settings_permission'],
            ]
        );

        // Test Builder Integration.
        \register_rest_route(
            $namespace,
            '/test_builder/',
            [
                'methods'             => ['POST'],
                'callback'            => [$this, 'test_builder_integration'],
                'permission_callback' => [$this, 'update_settings_permission'],
            ]
        );
    }

    /**
     * Get edit options permissions.
     *
     * @return bool
     */
    public function update_settings_permission() {
        if (! \current_user_can('manage_options')) {
            return $this->error('user_dont_have_permission', \__('User don\'t have permissions to change options.', 'marqueex'), true);
        }

        return true;
    }

    /**
     * Get settings permissions.
     *
     * @return bool
     */
    public function get_settings_permission() {
        if (! \current_user_can('manage_options')) {
            return $this->error('user_dont_have_permission', \__('User don\'t have permissions to view options.', 'marqueex'), true);
        }

        return true;
    }

    /**
     * Update Settings.
     *
     * @param WP_REST_Request $req  request object.
     *
     * @return mixed
     */
    public function update_settings(WP_REST_Request $req) {
        $new_settings = $req->get_param('settings');

        if (is_array($new_settings)) {
            $current_settings = get_option('marqueex_settings', []);
            $updated_settings = array_merge($current_settings, $new_settings);

            // Validate settings before saving
            $validated_settings = $this->validate_settings($updated_settings);

            update_option('marqueex_settings', $validated_settings);

            // Clear any cached data
            $this->clear_cache();

            return $this->success([
                'message' => __('Settings updated successfully.', 'marqueex'),
                'settings' => $validated_settings
            ]);
        }

        return $this->error('invalid_settings', __('Invalid settings data provided.', 'marqueex'));
    }

    /**
     * Get Settings.
     *
     * @param WP_REST_Request $req  request object.
     *
     * @return mixed
     */
    public function get_settings(WP_REST_Request $req) {
        $settings = get_option('marqueex_settings', []);
        $default_settings = $this->get_default_settings();

        // Merge with defaults to ensure all settings exist
        $complete_settings = array_merge($default_settings, $settings);

        return $this->success($complete_settings);
    }

    /**
     * Test Builder Integration.
     *
     * @param WP_REST_Request $req  request object.
     *
     * @return mixed
     */
    public function test_builder_integration(WP_REST_Request $req) {
        $builder = $req->get_param('builder');
        $test_results = [];

        switch ($builder) {
            case 'elementor':
                $test_results = $this->test_elementor_integration();
                break;
            case 'bricks':
                $test_results = $this->test_bricks_integration();
                break;
            case 'gutenberg':
                $test_results = $this->test_gutenberg_integration();
                break;
            case 'shortcode':
                $test_results = $this->test_shortcode_integration();
                break;
            default:
                return $this->error('invalid_builder', __('Invalid builder specified.', 'marqueex'));
        }

        return $this->success($test_results);
    }

    /**
     * Test Elementor Integration
     *
     * @return array
     */
    private function test_elementor_integration() {
        $results = [
            'status' => 'unknown',
            'message' => '',
            'details' => []
        ];

        // Check if Elementor is installed
        if (!$this->is_elementor_installed()) {
            $results['status'] = 'not_installed';
            $results['message'] = __('Elementor is not installed. Please install Elementor to use this integration.', 'marqueex');
            return $results;
        }

        // Check if Elementor is activated
        if (!$this->is_elementor_active()) {
            $results['status'] = 'not_active';
            $results['message'] = __('Elementor is installed but not activated. Please activate Elementor to use this integration.', 'marqueex');
            return $results;
        }

        // Check Elementor version
        $elementor_version = defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : 'unknown';
        $results['details']['version'] = $elementor_version;

        // Check minimum version requirement
        if (version_compare($elementor_version, '3.0.0', '<')) {
            $results['status'] = 'version_incompatible';
            $results['message'] = sprintf(__('Elementor version %s is too old. Please update to version 3.0.0 or higher.', 'marqueex'), $elementor_version);
            return $results;
        }

        // Check if we can register widgets
        try {
            $results['status'] = 'compatible';
            $results['message'] = sprintf(__('Elementor integration test successful. Version: %s', 'marqueex'), $elementor_version);
            $results['details']['widgets_available'] = true;
            $results['details']['pro_available'] = $this->is_elementor_pro_available();
        } catch (Exception $e) {
            $results['status'] = 'error';
            $results['message'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Check if Elementor is installed
     *
     * @return bool
     */
    private function is_elementor_installed() {
        return file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php');
    }

    /**
     * Check if Elementor is active
     *
     * @return bool
     */
    private function is_elementor_active() {
        return class_exists('\Elementor\Plugin');
    }

    /**
     * Check if Elementor Pro is available
     *
     * @return bool
     */
    private function is_elementor_pro_available() {
        return class_exists('\ElementorPro\Plugin');
    }

    /**
     * Test Bricks Integration
     *
     * @return array
     */
    private function test_bricks_integration() {
        $results = [
            'status' => 'unknown',
            'message' => '',
            'details' => []
        ];

        // Check if Bricks is active
        if (!class_exists('Bricks\Database')) {
            $results['status'] = 'not_installed';
            $results['message'] = __('Bricks is not installed or activated.', 'marqueex');
            return $results;
        }

        // Check Bricks version
        $bricks_version = defined('BRICKS_VERSION') ? BRICKS_VERSION : 'unknown';
        $results['details']['version'] = $bricks_version;

        try {
            $results['status'] = 'compatible';
            $results['message'] = __('Bricks integration test successful.', 'marqueex');
        } catch (Exception $e) {
            $results['status'] = 'error';
            $results['message'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Test Gutenberg Integration
     *
     * @return array
     */
    private function test_gutenberg_integration() {
        $results = [
            'status' => 'compatible',
            'message' => __('Gutenberg integration is always available.', 'marqueex'),
            'details' => [
                'wp_version' => get_bloginfo('version'),
                'gutenberg_available' => function_exists('register_block_type')
            ]
        ];

        return $results;
    }

    /**
     * Test Shortcode Integration
     *
     * @return array
     */
    private function test_shortcode_integration() {
        $results = [
            'status' => 'compatible',
            'message' => __('Shortcode integration is always available.', 'marqueex'),
            'details' => [
                'shortcode_functions_available' => function_exists('add_shortcode')
            ]
        ];

        return $results;
    }

    /**
     * Validate settings before saving
     *
     * @param array $settings
     * @return array
     */
    private function validate_settings($settings) {
        $default_settings = $this->get_default_settings();

        // Ensure all required keys exist
        foreach ($default_settings as $key => $default_value) {
            if (!isset($settings[$key])) {
                $settings[$key] = $default_value;
            }
        }

        // Validate builder support settings
        if (isset($settings['builder_support'])) {
            foreach ($settings['builder_support'] as $builder => $config) {
                if (isset($config['enabled']) && !is_bool($config['enabled'])) {
                    $settings['builder_support'][$builder]['enabled'] = (bool) $config['enabled'];
                }
                if (isset($config['auto_detect']) && !is_bool($config['auto_detect'])) {
                    $settings['builder_support'][$builder]['auto_detect'] = (bool) $config['auto_detect'];
                }
            }
        }

        // Validate performance settings
        if (isset($settings['performance'])) {
            foreach ($settings['performance'] as $key => $value) {
                if (!is_bool($value)) {
                    $settings['performance'][$key] = (bool) $value;
                }
            }
        }

        // Validate compatibility settings
        if (isset($settings['compatibility'])) {
            if (isset($settings['compatibility']['responsive_breakpoints'])) {
                foreach ($settings['compatibility']['responsive_breakpoints'] as $device => $value) {
                    if (!is_numeric($value) || $value < 0) {
                        $settings['compatibility']['responsive_breakpoints'][$device] = $default_settings['compatibility']['responsive_breakpoints'][$device];
                    }
                }
            }
        }

        return $settings;
    }

    /**
     * Clear cache when settings are updated
     */
    private function clear_cache() {
        // Clear any cached CSS
        if (function_exists('wp_cache_flush')) {
            wp_cache_flush();
        }

        // Clear any transients
        delete_transient('marqueex_builder_cache');
    }

    /**
     * Success rest.
     *
     * @param mixed $response response data.
     * @return mixed
     */
    public function success($response) {
        return new WP_REST_Response(
            [
                'success'  => true,
                'response' => $response,
            ],
            200
        );
    }

    /**
     * Error rest.
     *
     * @param mixed   $code       error code.
     * @param mixed   $response   response data.
     * @param boolean $true_error use true error response to stop the code processing.
     * @return mixed
     */
    public function error($code, $response, $true_error = false) {
        if ($true_error) {
            return new WP_Error($code, $response, ['status' => 401]);
        }

        return new WP_REST_Response(
            [
                'error'      => true,
                'success'    => false,
                'error_code' => $code,
                'response'   => $response,
            ],
            401
        );
    }
}
