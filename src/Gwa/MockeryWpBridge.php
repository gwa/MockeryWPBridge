<?php
namespace Gwa\Wordpress\MockeryWpBridge;

use Gwa\Wordpress\MockeryWpBridge\Contracts\WpBridgeInterface;
use Mockery;

class MockeryWpBridge implements WpBridgeInterface
{
    /**
     * Mockery instance.
     *
     * @var \Mockery
     */
    private $mock;

    /**
     * All shortcodes
     *
     * @var array
     */
    private $shortcodes = [];

    /* -------- */

    /**
     * Add a shortcode.
     *
     * @param string $tag
     *
     * @param mixed $func
     */
    public function addShortcode($tag, $func)
    {
        $this->shortcodes[$tag] = $func;
    }

    /**
     * Check if shortcode exist.
     *
     * @param string $tag
     *
     * @return boolean
     */
    public function hasShortcode($tag)
    {
        return isset($this->shortcodes[$tag]);
    }

    /**
     * Get a shortcode callback.
     *
     * @param string $tag
     *
     * @return mixed
     */
    public function getShortcodeCallback($tag)
    {
        return isset($this->shortcodes[$tag]) ? $this->shortcodes[$tag] : null;
    }

    /**
     * Combines shortcode attributes with known attributes and fills in defaults when needed.
     *
     * @param array       $pairs
     * @param array       $atts
     * @param string|null $shortcode
     *
     * @return array
     */
    public function shortcodeAtts($pairs, $atts, $shortcode = null)
    {
        return array_merge($pairs, $atts);
    }

    /* -------- */

    /**
     * Wordpress mock on __() func.
     *
     * @param  string $text
     * @param  string $domain
     *
     * @return string
     */
    public function __($text, $domain)
    {
        return $text;
    }

    /* -------- */

    public function __call($function, $args)
    {
        return call_user_func_array([$this->mock, $function], $args);
    }

    public function mock()
    {
        if (!isset($this->mock)) {
            $this->mock = Mockery::mock('WpBridge');
        }

        return $this->mock;
    }
}
