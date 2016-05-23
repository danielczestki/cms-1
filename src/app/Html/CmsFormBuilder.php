<?php

namespace Thinmartian\Cms\App\Html;

use Illuminate\Support\HtmlString;

class CmsFormBuilder {
    
    /**
     * The $data keys we want REMOVED from the FormBuilder attributes array.
     * This will allow for custom data-* for example
     * 
     * @var array
     */
    protected $attributeSchema = ["name", "type", "label", "value", "validationOnCreate", "validationOnUpdate", "validationOnDelete", "info"];
    
    //
    // FORM
    // 
    
    /**
     * Render a <form>
     * 
     * @param  array  $data The element attributes
     * @return string
     */ 
    public function open($data = [])
    {
       return $this->render(view("cms::html.form.open", $data));         
    }
    
    /**
     * Render a </form>
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function close()
    {
        return $this->render(view("cms::html.form.close")); 
    }
    
    //
    // INPUT
    // 
    
    /**
     * Render a input[type=text]
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function text($data = [])
    {
        $data["type"] = "text";
        return $this->input($data);
    }
    
    /**
     * Render a input[type=email]
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function email($data = [])
    {
        $data["type"] = "email";
        return $this->input($data);
    }
    
    /**
     * Render a input[type=password]
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function password($data = [])
    {
        $data["type"] = "password";
        return $this->input($data);
    }
    
    /**
     * Render a input[type=hidden]
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function hidden($data = [])
    {
       return $this->render(view("cms::html.form.hidden", $data)); 
    }
    
    /**
     * Renders most input[type=$type]
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    private function input($data)
    {
        return $this->render(view("cms::html.form.input", $this->buildData($data))); 
    }
    
    //
    // CHECKBOX AND RADIOS
    // 
    
    /**
     * Render a input[type=checkbox]
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function checkbox($data = [])
    {
       return $this->render(view("cms::html.form.checkbox", $this->buildData($data))); 
    }
    
    //
    // BUTTONS
    // 
    
    /**
     * Render a button[type=submit]
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function submit($data = [])
    {
       return $this->render(view("cms::html.form.submit", $data)); 
    }
    
    //
    // TITLES AND STRINGS
    // 
    
    /**
     * Return the subtitle for the page
     * 
     * @param  string $type create or edit
     * @param  string $name the name of the resource or record
     * @return string
     */
    public function subtitle($type, $name)
    {
        return $type == "create" ? "Create a new " . strtolower(str_singular($name)) : "Edit $name";
    }
    
    /**
     * Return the label for the submit button
     * 
     * @param  string $type create or edit
     * @param  string $name the name of the resource or record
     * @return string
     */
    public function submitlabel($type, $name)
    {
        return ($type == "create" ? "Create " : "Edit ") . strtolower(str_singular($name));
    }
    
    
    //
    // UTILS AND PRIVATE
    // 
    
    /**
     * Build the data array so they can add custom attrs (e.g. data-*)
     * 
     * @param  array  $data The element attributes
     * @return array
     */
    private function buildData($data = [])
    {
        $data["additional"] = array_except($data, $this->attributeSchema);
        return $data;
    }
    
    /**
     * Render the HTML back to the view, this allows for {{}} or {!!!!}
     * 
     * @param  View     $view   The viewe instance, it will be render()'d'
     * @return string
     */
    private function render($view)
    {
        return new HtmlString($view->render());
    }
    
}