<?php

namespace Coalition;

use \ArrayAccess;

class ConfigRepository implements ArrayAccess
{
    private $configValues = null;

    /**
     * ConfigRepository Constructor
     */
    public function __construct($values = null)
    {
        if( is_array($values) ){
            foreach ($values as $key => $value) {
                $this->configValues[$key] = $value;                
            }
        }
        return $this->configValues;
    }

    /**
     * Determine whether the config array contains the given key
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        if (array_key_exists($key, $this->configValues)) {
            return true;
        }
        return false;
    }

    /**
     * Set a value on the config array
     *
     * @param string $key
     * @param mixed  $value
     * @return \Coalition\ConfigRepository
     */
    public function set($key = null, $value = null)
    {
        if( ! is_null($key) ){
                $this->configValues[$key] = $value;
        }
        return $this;
    }

    /**
     * Get an item from the config array
     *
     * If the key does not exist the default
     * value should be returned
     *
     * @param string     $key
     * @param null|mixed $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if ( !$default && array_key_exists($key, $this->configValues)) {
            return $this->configValues[$key];
        }
        return $default;        

    }

    /**
     * Remove an item from the config array
     *
     * @param string $key
     * @return \Coalition\ConfigRepository
     */
    public function remove($key)
    {
        if (array_key_exists($key, $this->configValues)) {
            unset($this->configValues[$key]);
        }
        return $this;      

    }

    /**
     * Load config items from a file or an array of files
     *
     * The file name should be the config key and the value
     * should be the return value from the file
     * 
     * @param array|string The full path to the files $files
     * @return void
     */
    public function load($files = null)
    {
        if( is_string($files) ){
            $key = basename("$files", ".php");        
            $fileContent = include $files;
            $this->configValues[$key] = $fileContent;
            return true;
        }elseif( is_array($files) ){
            foreach($files as $file) {
                $key = basename("$file", ".php");                
                $fileContent = include $file;
                $this->configValues[$key] = $fileContent;
            }
            return true;
        }
        return false;        
    }

    /* ---------------------------------------------------- */
    /* ---- ArrayAccess (abstract public methods) --------- */
    /* ---------------------------------------------------- */    

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            $this->configValues[] = $value;
        } else {
            $this->configValues[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset($this->configValues[$offset]);
    }

    public function offsetUnset($offset) {
        unset($this->configValues[$offset]);
    }

    public function offsetGet($offset) {
        return isset($this->configValues[$offset]) 
                        ? $this->configValues[$offset] : null;
    }    

}