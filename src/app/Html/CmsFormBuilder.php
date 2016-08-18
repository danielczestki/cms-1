<?php

namespace Thinmartian\Cms\App\Html;

use Illuminate\Support\HtmlString;
use CmsImage, CmsVideo, CmsDocument, CmsEmbed, CmsYaml;

class CmsFormBuilder {
    
    /**
     * The $data keys we want REMOVED from the FormBuilder attributes array.
     * This will allow for custom data-* for example
     * 
     * @var array
     */
    protected $attributeSchema = ["name", "type", "label", "persist", "value", "validationOnCreate", "validationOnUpdate", "info", "infoUpdate", "options", "prefix", "suffix", "limit", "allowed"];
    
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
     * Render a model form
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function model($data)
    {
        $cmsAppAction = (isset($data["cmsAppAction"]) && $data["cmsAppAction"]) || !isset($data["cmsAppAction"]) ? true : false;
        $filters = $data["type"] == "edit" ? array_merge(["id" => $data["model"]->id], $data["filters"]) : $data["filters"];
        $data["url"] = cmsaction($data["controller"] . ($data["type"] == "edit" ? "@update" : "@store"), $cmsAppAction, $filters);
        $data["method"] = $data["type"] == "edit" ? "PUT" : "POST";
        return $this->render(view("cms::html.form.model", $data)); 
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
     * Render a input[type=number]
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function number($data = [])
    {
        $data["type"] = "number";
        $data["min"] = 0;
        return $this->input($data, "number");
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
        $data["autocomplete"] = "off";
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
     * Render a input[type=file]
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function file($data = [])
    {
        $data["type"] = "file";
        if (isset($data["mediatype"])) {
            if ($accepted = CmsImage::getMediaTypes($data["mediatype"] . ".accepted")) {
                array_walk($accepted, function(&$item, $key) {
                    $item = "." . $item;
                });
                $data["accept"] = implode(",", $accepted);
            }
        }
        return $this->input($data, "file");
    }
    
    /**
     * Renders most input[type=$type]
     * 
     * @param  array  $data The element attributes
     * @param  string $type the elemnt type to be sent down (optional)
     * @return string
     */
    private function input($data, $type = null)
    {
        $data["class"] = @$data["class"] . " Form__input";
        return $this->render(view("cms::html.form.input", $this->buildData($data, $type))); 
    }
    
    //
    // SELECT
    // 
    
    /**
     * Render a select
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function select($data = [])
    {
        $data["class"] = @$data["class"] . " Form__select";
        $data["options"] = ["" => "Please Select..."] + $data["options"];
        return $this->render(view("cms::html.form.select", $this->buildData($data))); 
    }
    
    /**
     * Render a access level select
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function access_level($data = [])
    {
        $arr = [];
        $data["class"] = "Form__select";
        $data["name"] = "access_level";
        $data["label"] = "Access level";
        $data["options"] = ["Admin" => "Administrator", "Standard" => "Standard user"];
        $data["info"] = "Only administrators can change access levels and permissions.";
        
        return $this->render(view("cms::html.form.select", $this->buildData($data))); 
    }
    
    /**
     * Render a permissions select
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function permissions($data = [])
    {
        $arr = [];
        $data["class"] = @$data["class"] . " Form__select";
        $data["name"] .= "[]";
        $data["topper"] = true;
        
        // Fetch the modules
        $defs = CmsYaml::getAllYamls();
        foreach ($defs as $def) {
            $yaml = CmsYaml::parseYaml($def->getRealpath());
            $value = @CmsYaml::getFilename($def);
            $label = @$yaml["meta"]["title"];
            $show = @$yaml["meta"]["show_in_nav"];
            if ($label and $value and $show) {
                $arr[$value] = $label;
            }
        }
        $size = count($defs) + 1;
        $data["options"] = ["" => "-- All --"] + $arr;
        $data["multiple"] = true;
        $data["size"] = min(6, $size);
        $data["style"] = "height:auto;" . (isset($data["style"]) ? $data["style"] : null);
        return $this->render(view("cms::html.form.select", $this->buildData($data))); 
    }
    
    //
    // TEXTAREA AND WYSIWYG
    // 
    
    /**
     * Render a textarea
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function textarea($data = [])
    {
        $data["class"] = @$data["class"] . " Form__textarea";
        $data["rows"] = isset($data["rows"]) ? $data["rows"] : 3;
       return $this->render(view("cms::html.form.textarea", $this->buildData($data))); 
    }
    
    /**
     * Render a wysiwyg
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function wysiwyg($data = [])
    {
        $data["class"] = @$data["class"] . " Form__textarea Form__wysiwyg";
        $data["rows"] = isset($data["rows"]) ? $data["rows"] : 27;
       return $this->render(view("cms::html.form.wysiwyg", $this->buildData($data))); 
    }
    
    //
    // CHECKBOX AND RADIOS
    // 
    
    /**
     * Render a boolean (radios)
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function boolean($data = [])
    {
        $data["class"] = @$data["class"] . " Form__radio";
        return $this->render(view("cms::html.form.boolean", $this->buildData($data))); 
    }
    
    /**
     * Render a input[type=checkbox]
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function checkbox($data = [])
    {
        $data["class"] = @$data["class"] . " Form__checkbox";
        return $this->render(view("cms::html.form.checkbox", $this->buildData($data))); 
    }
    
    /**
     * Render a input[type=radio]
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function radio($data = [])
    {
        $data["class"] = @$data["class"] . " Form__radio";
        return $this->render(view("cms::html.form.radio", $this->buildData($data))); 
    }
    
    //
    // DATES
    // 
    
    /**
     * Render a datetime picker
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function datetime($data = [])
    {
        return $this->_date($data, true);
    }
    
    /**
     * Render a date picker
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function date($data = [])
    {
        return $this->_date($data, false);
    }
    
    /**
     * Return the date/time picker
     */
    private function _date($data, $time = false)
    {
        $data["data-time"] = $time;
        $data["readonly"] = "readonly";
        $data["prefix"] = "<i class='fa fa-calendar-o'></i>";
        $data["class"] = @$data["class"] . " Form__input Form__input--date";
        return $this->render(view("cms::html.form.datetime", $this->buildData($data))); 
    }
    
    //
    // MEDIA
    // 
    
    /**
     * Render a media picker field
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function media($data, $resource = null)
    {
        $data["resource"] = $resource;
        $data["existing"] = $this->existingMedia($data, $resource);
        
        $_info = isset($data["info"]) ? $data["info"] : "";
        if (isset($data["limit"])) {
            $data["info"] = "Maximum of {$data['limit']} allowed.";
        }
        if (isset($data["allowed"])) {
            $data["info"] .= " Accepted types: " . implode(", ", $data["allowed"]) .".";
        }
        if (isset($data["info"])) {
            $data["info"] .= " " . $_info;
        }
        
        // Return the form field
        return $this->render(view("cms::html.form.media", $this->buildData($data)));
    }
    
    /**
     * Builds a clean array of all thedata needed for the vue component
     * 
     * @param  array  $data
     * @param  model  $resource 
     * @return array
     */
    private function existingMedia($data, $resource = null)
    {
        $result = [];
        // No resource means a creeate form, so just return an empty array
        //if (! $resource) return $result;
        // Fetch the media (if any) and continue...
        if ($old = request()->old("cmsmedia.{$data['name']}")) {
            // Any in the old input?
            $collection = \App\Cms\CmsMedium::whereIn("id", $old)->get();
        } else if ($resource) {
            // Any saved to it already?
            $collection = $resource->media($data["name"])->get(); 
        } else {
            // None, just return empty
            return $result;
        }
        if (! $collection->count()) return $result;
        // Loop the media and build the array      
        foreach ($collection as $media) {
            $result[] = $this->mediaArray($media);
        }
        // Return the new array
        return $result;
    }
    
    public function mediaArray($media)
    {
        // Add default/global data
        $result = [
            "cms_medium_id" => $media->id,
            "type" => $media->type,
            "title" => $media->title,
            "icon" => CmsImage::getMediaTypes($media->type . ".icon"), 
            "filename" => $media->filename,
            "extension" => $media->extension,
            "original_name" => $media->original_name,
            "removed" => false, // for vue
        ];
        // Image data (if applicable)
        if ($media->type == "image") {
            $result["image"] = $media->image->toArray() + ["thumbnail" => CmsImage::get($media->id, 600, 600)];
        }
        // video data (if applicable)
        if ($media->type == "video") {
            $result["video"] = $media->video->toArray() + ["thumbnail" => CmsVideo::thumbnail($media->id)];
        }
        // document data (if applicable)
        if ($media->type == "document") {
            $result["document"] = $media->document->toArray() + ["fileicon" => CmsDocument::icon($media)];
        }
        // embed data (if applicable)
        if ($media->type == "embed") {
            $result["embed"] = $media->embed->toArray() + ["domain" => CmsEmbed::domain($media)];
        }
        return $result;
    }
    
    
    //
    // BUTTONS
    // 
    
    /**
     * Render the surround for the generic button collection
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function buttons($data = [])
    {
       return $this->render(view("cms::html.form.buttons", $data)); 
    }
    
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
     * Static field (no inputs)
     * 
     * @param  array  $data The element attributes
     * @return string
     */
    public function content($data)
    {
        return $this->render(view("cms::html.form.content", $data)); 
    }
    
    /**
     * Return the subtitle for the page
     * 
     * @param  string $type create or edit
     * @param  string $name the name of the resource or record
     * @return string
     */
    public function subtitle($type, $name)
    {
        $title = strtolower(str_singular($name));
        return $type == "create" ? "Create a new " . $title : "Edit $title";
    }
    
    /**
     * Return the success message
     * 
     * @return string
     */
    public function success()
    {
        return $this->render(view("cms::html.form.success")); 
    }
    
    /**
     * Return the global error message
     * 
     * @return string
     */
    public function error()
    {
        return $this->render(view("cms::html.form.error")); 
    }
    
    /**
     * Return the array to build the sort query string
     * 
     * @param  string $idx The column we want to sort
     * @return array
     */
    public function sortString($idx)
    {
        $direction = (request()->get("sort") == $idx and request()->get("sort_dir") == "asc") ? "desc" : "asc";
        return ["sort" => $idx, "sort_dir" => $direction];
    }
    
    /**
     * Show the correct icon on listing to declare if this field is sorted by or not
     * 
     * @param  string $idx
     * @return string
     */
    public function sorted($idx)
    {
        if ($this->getSort() == $idx) {
            return $this->getSortDirection() == "desc" ? new HtmlString('<i class="fa fa-caret-up"></i>') : new HtmlString('<i class="fa fa-caret-down"></i>');
        }
        return null;
    }
     
    //
    // RELATIONSHIPS
    //
    
    /**
     * Show a form field for the relation
     *
     * @param  array  $data The element attributes
     * @return string
     */
    public function relation($data = []) {
        $relations = [
            'belongsTo' => 'select',
            'belongsToMany' => 'selectMultiple',
        ];
        if (isset($data['relationType']) && isset($relations[$data['relationType']])) {
            // we are dealing with a select
            if ($relations[$data['relationType']] == 'select' || $relations[$data['relationType']] == 'selectMultiple') {
                // many-to-many - use a multiselect
                if ($relations[$data['relationType']] == 'selectMultiple') {
                    $data['multiple'] = 'multiple';
                }
                // use the name of the item as default
                $className = $data['name'];
                // if a classname was provided, use it instead
                if (isset($data['className']) && strlen($data['className'])) {
                    $className = $data['className'];
                }
                // generate class string
                $class = '\Thinmartian\Cms\App\Models\Core\Cms' . ucwords($className);
                // does class exist?
                if (class_exists($class)) {
                    // grab options
                    $data['options'] = $class::get()->pluck('name', 'id')->toArray();
                    // return options
                    return $this->{$relations['belongsTo']}($data);
                }
            }
        }
        return null;
    }
    
    //
    // UTILS AND PRIVATE
    // 
    
    /**
     * Build the data array so they can add custom attrs (e.g. data-*)
     * 
     * @param  array  $data The element attributes
     * @param  string $type the elemnt type to be sent down (optional)
     * @return array
     */
    private function buildData($data = [], $type = null)
    {
        $data["class"] = @$data["class"] . " Form__control";
        $data["additional"] = array_except($data, $this->attributeSchema);
        $data["additional"]["maxlength"] = (isset($data["maxlength"]) and $data["maxlength"]) ? $data["maxlength"] : $this->getMaxLength($data);
        $data["required"] = (isset($data["required"]) and $data["required"]) ? true : $this->isRequired($data);
        $data["additional"]["id"] = "f-{$data['name']}";
        if ($type == "number") {
            unset($data["additional"]["maxlength"]);
            $data["additional"]["max"] = $this->getMaxLength($data);
        }
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
     * Get the sorted value
     * 
     * @return string
     */
    private function getSort()
    {
        return request()->get("sort") ?: config("cms.cms.default_sort_column");
    }
    
    /**
     * Get the sorted direction value
     * 
     * @return string
     */
    private function getSortDirection()
    {
        return request()->get("sort_dir") ?: config("cms.cms.default_sort_direction");
    }
    
    /**
     * Get the rules key based on the method
     * 
     * @return string
     */
    private function getRulesKey()
    {
        return str_contains(request()->route()->getAction()["controller"], "@edit") ? "validationOnUpdate" : "validationOnCreate";
    }
    
    /**
     * Render the HTML back to the view, this allows for {{}} or {!!!!}
     * 
     * @param  View     $view   The viewe instance, it will be render()'d'
     * @return string
     */
    private function render($view)
    {
        return new HtmlString($view);
    }
    
}