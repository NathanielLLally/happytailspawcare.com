<?php


namespace Wpxero\Marqueex\Core;

use Wpxero\Marqueex\Traits\Singleton;   

defined('ABSPATH') || exit;

/**
 * Converts styles to CSS.
 */
class Property {
    use Singleton;


    /**
     * Raw name of the property without device or psuedo-class.
     *
     * @var string
     */
    public string $name;

    /**
     * Device type.
     *
     * @var string
     */
    public string $device = 'desktop';

    /**
     * Hover psuedo-class or not.
     *
     * @var bool
     */
    public bool $hover = false;

    /**
     * Constructor
     */
    public function __construct() {
        // Singleton constructor - no parameters needed
    }

    /**
     * Set property name and parse it
     *
     * @param string $property
     * @return void
     */
    public function set_property(string $property): void {
        $this->name = $property;
        $this->parse();
    }

    /**
     * Parse the fullname.
     *
     * @return void
     */
    private function parse(): void {
        if (str_starts_with($this->name, 'tablet')) {
            $this->name = lcfirst(substr($this->name, 6));
            $this->device = 'tablet';
        } elseif (str_starts_with($this->name, 'mobile')) {
            $this->name = lcfirst(substr($this->name, 6));
            $this->device = 'mobile';
        }

        if (str_ends_with($this->name, 'Hover')) {
            $this->name = substr($this->name, 0, strlen($this->name) - 5);
            $this->hover = true;
        }
    }
}
