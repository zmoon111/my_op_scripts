<?php

namespace pointybeard\ShellArgs\Lib;

class ArgumentIterator implements \Iterator, \Countable
{
    private $args = [];
    private $keys = [];
    private $position = 0;

    public function __construct($ignoreFirst = true, array $args = null)
    {
        // Constructor can accept an array of arguments,
        // however if it's null, try to use ARGV instead
        // Need to assign to a new variable, since this function
        // would be destructive to $argv otherwise.
        if (is_null($args)) {
            global $argv;
            $args = $argv;
        }

        $start = 0;
        if ($ignoreFirst == true) {
            array_shift($args);
        }

        // Reconstruct the args string
        $string = implode($args, ' ');
        $matches = [];

        /**
         * Credit to "Jonathan Leffler" from Stack Overflow
         * for this regex (http://stackoverflow.com/a/13141314)
         */
        preg_match_all(
            '@(?:-{1,2}|/)(?<name>\w+)(?:[=:]?|\s+)(?<value>[^-\s"][^"]*?|"[^"]*")?(?=\s+[-/]|$)@i',
            $string,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $arg) {
            $this->args[] = new Argument($arg['name'], (isset($arg['value']) ? $arg['value'] : true));
            $this->keys[] = $arg['name'];
        }

        return true;
    }
    /**
     * Look through the args array for a particular value. If $name is an array, it
     * will look through until it finds a match and return the first one.
     *
     * @param  string|array $name
     * @return array
     */
    public function find($names)
    {
        if (!is_array($names)) {
            $names = [$names];
        }

        foreach ($names as $name) {
            if (in_array($name, $this->keys)) {
                return $this->args[array_search($name, $this->keys)];
            };
        }

        return false;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->args[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->args[$this->position]);
    }
    
    public function count(){
        return count($this->keys);
    }
}
