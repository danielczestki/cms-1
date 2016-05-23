<?php

namespace Thinmartian\Cms\App\Services\Resource;

class ResourceInput {
    
    /**
     * @var array
     */
    protected $input = [];
    
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
        return array_except(request()->all(), ["_token", "_name"]);
    }
}