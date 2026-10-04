<?php

namespace CalCom\Integrations;

defined('ABSPATH') || exit;

class Integrations
{
    const OPTION_KEY = 'cal_com_integrations';

    public function get_integrations()
    {
        return [
            'woocommerce' => [
                'name'        => __('WooCommerce', 'cal-com'),
                'description' => __('Show a Cal.com scheduling prompt on WooCommerce order confirmation pages.', 'cal-com'),
                'icon'        => 'dashicons dashicons-cart',
            ],
            'learnpress' => [
                'name'        => __('LearnPress', 'cal-com'),
                'description' => __('Show an optional Cal.com booking option on LearnPress course pages.', 'cal-com'),
                'icon'        => 'dashicons dashicons-book',
            ],
            'tutor-lms' => [
                'name'        => __('Tutor LMS', 'cal-com'),
                'description' => __('Show an optional Cal.com booking option on Tutor LMS course pages.', 'cal-com'),
                'icon'        => 'dashicons dashicons-book',
            ],
            'users-wp' => [
                'name'        => __('UsersWP', 'cal-com'),
                'description' => __('Show a Cal.com booking option on UsersWP user profile pages using per-user booking URLs stored in the WordPress user profile.', 'cal-com'),
                'icon'        => 'dashicons dashicons-groups',
            ],
            'ultimate-member' => [
                'name'        => __('Ultimate Member', 'cal-com'),
                'description' => __('Show a Cal.com booking option on Ultimate Member member profiles using per-member booking URLs stored in the WordPress user profile.', 'cal-com'),
                'icon'        => 'dashicons dashicons-groups',
            ],
        ];
    }

    public function get_keys()
    {
        return array_keys($this->get_integrations());
    }

    public function get_enabled()
    {
        $enabled = get_option(self::OPTION_KEY, []);

        if (! is_array($enabled)) {
            return [];
        }

        return array_values(array_filter($enabled, [$this, 'is_registered']));
    }

    public function is_registered($key)
    {
        return array_key_exists($key, $this->get_integrations());
    }

    public function is_enabled($key)
    {
        if (! $this->is_registered($key)) {
            return false;
        }

        return in_array($key, $this->get_enabled(), true);
    }

    public function save($keys)
    {
        $valid_keys = array_filter(
            array_map('sanitize_key', (array) $keys),
            [$this, 'is_registered']
        );

        $enabled = array_values($valid_keys);

        update_option(self::OPTION_KEY, $enabled);
    }

    public function toggle($key, $state)
    {
        if (! $this->is_registered($key)) {
            return false;
        }

        $enabled = $this->get_enabled();

        if ((bool) $state) {
            // Add
            if (in_array($key, $enabled, true)) {
                return false;
            }
            $enabled[] = $key;
        } else {
            // Remove
            $enabled = array_values(array_filter(
                $enabled,
                function ($enabled_key) use ($key) {
                    return $enabled_key !== $key;
                }
            ));
        }

        $this->save($enabled);

        return true;
    }
}
