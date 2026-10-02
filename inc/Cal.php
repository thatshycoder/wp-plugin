<?php

namespace CalCom;

use CalCom\Admin\Customizer;
use CalCom\Admin\ApiSettings;

defined('ABSPATH') || exit;

class Cal
{
    private static $instance;

    private function __construct()
    {
        $this->hooks();

        (new Embed())->hooks();
        (new Customizer())->hooks();
        (new ApiSettings())->hooks();
        (new CustomEmbed())->hooks();

    }

    private function hooks()
    {
        add_action('admin_enqueue_scripts', [$this, 'register_scripts']);
        add_action('wp_enqueue_scripts', [$this, 'register_scripts']);
    }

    public function register_scripts()
    {
        $ver = file_exists(CALCOM_ASSETS_PATH . 'js/embed.min.js')
            ? filemtime(CALCOM_ASSETS_PATH . 'js/embed.min.js')
            : false;

        wp_register_script(
            'calcom-loader-js',
            CALCOM_ASSETS_URL . 'js/cal-loader.min.js',
            [],
            $ver,
            true
        );

        wp_register_script(
            'calcom-embed-js',
            CALCOM_ASSETS_URL . 'js/embed.min.js',
            ['calcom-loader-js'],
            $ver,
            true
        );

        wp_register_script(
            'calcom-custom-embed-js',
            CALCOM_ASSETS_URL . 'js/custom-embed.min.js',
            ['calcom-embed-js'],
            $ver,
            true
        );

        wp_register_script(
            'calcom-customizer-js',
            CALCOM_ASSETS_URL . 'js/admin-customizer.min.js',
            ['calcom-custom-embed-js'],
            $ver,
            true
        );

        wp_register_script(
            'calcom-customizer-extra-js',
            CALCOM_ASSETS_URL . 'js/admin-customizer-extra.js',
            ['calcom-customizer-js'],
            $ver,
            true
        );

        wp_register_style(
            'calcom-customizer-css',
            CALCOM_ASSETS_URL . 'css/admin-customizer.min.css',
            [],
            $ver
        );

        wp_register_style(
            'calcom-embed-css',
            CALCOM_ASSETS_URL . 'css/style.min.css',
            [],
            $ver
        );
    }

    public static function get_instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
