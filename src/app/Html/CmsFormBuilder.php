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
    
    /**
     * Render a cancel button
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function cancel($data = [])
    {
        return $this->render(view("cms::html.form.cancel", $data)); 
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
    
    public function success()
    {
        return $this->render(view("cms::html.form.success")); 
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
        $data["additional"]["maxlength"] = $this->getMaxLength($data);
        $data["required"] = $this->isRequired($data);
        return $data;
    }
    
    /**
     * Traverse the validation rules and determine if this field is required or not
     * 
     * @param  array   $data Array of form data
     * @return boolean
     */
    private function isRequired($data = [])
    {
        $rules = $this->getRulesKey();
        if (! array_key_exists($rules, $data)) return false;
        return in_array("required", explode("|", $data[$rules]));
    }
    
    
    /**
     * Traverse the validation rules and determine if this field has a
     * maxlength and if so, return it so we can add to the field
     * 
     * @param  array   $data Array of form data
     * @return mixed
     */
    private function getMaxLength($data = [])
    {
        $rules = $this->getRulesKey();
        if (! array_key_exists($rules, $data)) return null;
        foreach (explode("|", $data[$rules]) as $rule) {
            if (starts_with($rule, "max")) {
                return intval(str_ireplace("max:", "", $rule));
            }
        }
        return null;
    }
    
    /**
     * Get the rules key based on the method
     * 
     * @return string
     */
    private function getRulesKey()
    {
        return in_array(request()->method(), ["PUT", "PATCH"]) ? "validationOnUpdate" : "validationOnCreate";
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