<?php
namespace pointybeard\ShellArgs\Lib;

class Argument
{
    private $name;
    private $value;

    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }
    /**
     * Getter magic method for the $name and $value properties
     * DO NOT USE. THIS IS DEPRECATED AND WILL BE REMOVE IN FUTURE VERSIONS
     * Use $this->name() and $this->value() instead.
     *
     * @param  string       $name
     * @return string|false
     */
    public function __get($name)
    {
        trigger_error('Magic method __get() will no longer be used as getter for $name and $value. Please use the name() and value()  methods instead.', E_USER_DEPRECATED);

        if ($name != "name" && $name != "value") {
            return false;
        }

        return $this->$name;
    }

    /**
     * Returns the $name property
     *
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * Returns the $value property
     *
     * @return string
     */
    public function value()
    {
        return $this->value;
    }
}
