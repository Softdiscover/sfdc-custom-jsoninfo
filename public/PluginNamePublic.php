<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://github.com/Softdiscover
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 */
declare(strict_types=1);
 
namespace sfdc\wpJsonPlaceholder\front;

use \sfdc\wpJsonPlaceholder\core\LogicCore;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/public
 * @author     Your Name <email@example.com>
 */
class PluginNamePublic
{
    
    
    /**
     * Static instance
     *
     * @var LogicCore
     */
    private static $lcInstance;
    
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $pluginName    The ID of this plugin.
     */
    private $pluginName;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $pluginName       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct(string $pluginName, string $version)
    {
        $this->plugin_name = $pluginName;
        $this->version = $version;
        
        //adding LogicCore Instance
        if (empty(self::$lcInstance)) {
            self::$lcInstance = new LogicCore();
        }
        
        //modify title
        add_filter('pre_get_document_title', [ self::$lcInstance, 'changeTitle' ]);
         
        // create custom slug
        add_filter('template_include', [ self::$lcInstance, 'createCustomSlug' ], 99);
        
        // list users
        add_action('wp_ajax_sfdc_showListUsers', [ self::$lcInstance, 'listUsers' ]);
        add_action('wp_ajax_nopriv_sfdc_showListUsers', [ self::$lcInstance, 'listUsers' ]);
        
        // user detail
        add_action('wp_ajax_sfdc_UserDetail', [ self::$lcInstance, 'userDetail' ]);
        add_action('wp_ajax_nopriv_sfdc_UserDetail', [ self::$lcInstance, 'userDetail' ]);
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueStyles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/plugin-name-public.css',
            [],
            $this->version,
            'all'
        );
        
        
        wp_enqueue_style(
            'bootstrapcdn',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css',
            [],
            $this->version,
            'all'
        );
        
        wp_enqueue_style(
            'fontawesome',
            'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.13.1/css/all.min.css',
            [],
            (PLUGIN_NAME_DEBUG)? date('Ymdgis'): $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueueScripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Plugin_Name_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Plugin_Name_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'js/plugin-name-public.js',
            [ 'jquery', 'wp-i18n' ],
            (PLUGIN_NAME_DEBUG)? date('Ymdgis'): $this->version,
            false
        );
        
        $variables=[];
        $variables['ajaxurl']=site_url('wp-admin/admin-ajax.php');
        $variables['ajax_nonce'] = wp_create_nonce('sfdc_ajax_nonce');
        wp_localize_script($this->plugin_name, 'sfdc_vars', $variables);
        
        wp_enqueue_script(
            'bootstrapcdn',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js',
            [ 'jquery' ],
            $this->version,
            false
        );
    }
}
