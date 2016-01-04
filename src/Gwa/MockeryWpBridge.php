<?php
namespace Gwa\Wordpress\WpBridge;

use Gwa\Wordpress\WpBridge\Contracts\WpBridgeInterface;
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
     * All shortcodes.
     *
     * @var array
     */
    private $shortcodes = [];

    /**
     * All filters.
     *
     * @var array
     */
    private $filters = [];

    /**
     * All actions.
     *
     * @var array
     */
    private $actions = [];

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

        return $this;
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

    /**
     * Wordpress mock on add_filter func.
     *
     * @param string   $filterName
     * @param callback $filterCall
     * @param int      $prio
     * @param int      $numVars
     *
     * @return self
     */
    public function addFilter($filterName, $filterCall, $prio, $numVars)
    {
        $this->filters[] = $this->add($filterName, $filterCall, $prio, $numVars);

        return $this;
    }

    /**
     * Get all added filters.
     *
     * @return array
     */
    public function getAddedFilters()
    {
        return $this->filters;
    }

    /**
     * Wordpress mock on add_action func.
     *
     * @param string   $filterName
     * @param callback $filterCall
     * @param int      $prio
     * @param int      $numVars
     *
     * @return self
     */
    public function addAction($filterName, $filterCall, $prio, $numVars)
    {
        $this->actions[] = $this->add($filterName, $filterCall, $prio, $numVars);

        return $this;
    }

    /**
     * Get added actions
     *
     * @return array
     */
    public function getAddedActions()
    {
        return $this->actions;
    }

    /**
     * add
     *
     * @param string   $filterName
     * @param callback $filterCall
     * @param int      $prio
     * @param int      $numVars
     *
     * @return \stdClass
     */
    private function add($filterName, $filterCall, $prio, $numVars)
    {
        $data             = new \stdClass();
        $data->filtername = $filterName;
        $data->callback   = $filterCall;
        $data->prio       = $prio;
        $data->numvars    = $numVars;

        return $data;
    }

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
