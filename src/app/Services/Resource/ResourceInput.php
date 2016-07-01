<?php

namespace Thinmartian\Cms\App\Services\Resource;

class ResourceInput {
    
    /**
     * @var array
     */
    protected $input = [];
    
    /**
     * Ignore this params in submit of the form
     * 
     * @var array
     */
    protected $ignore = ["_token", "_name", "_method"];
    
    /**
     * constructor
     */
    public function __construct()
    {
        $this->input = $this->cleanInput();
    }
    
    /**
     * Add a new field to the input
     * 
     * @param string $name  The name of the input field
     * @param mixed  $value The value of the input field
     * @return void
     */
    public function add($name, $value)
    {
        $this->input[$name] = $value;
    }
    
    /**
     * Edit a field in the input
     * 
     * @param string $name  The name of the input field to edit
     * @param mixed  $value The new value of the input field
     * @return void
     */
    public function edit(...$params)
    {
        $this->add(...$params);
    }
    
    /**
     * Remove a field from the input
     * 
     * @param  string $name The name of the field to remove
     * @return void
     */
    public function remove($name)
    {
        unset($this->input[$name]);
    }
    
    /**
     * Get the input fields
     * 
     * @return array
     */
    public function getInput()
    {
        return $this->input;
    }
    
    /**
     * Clean the input by removing fields we don't need to send to resources
     * 
     * @return array
     */
    private function cleanInput()
    {
        return array_except(request()->all(), $this->ignore);
    }
    
    /**
     * Pull the param from the input if there is no prop
     * 
     * @param  string $name
     * @return mixed
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->input)) {
            return $this->input[$name];
        }
    }
    
}