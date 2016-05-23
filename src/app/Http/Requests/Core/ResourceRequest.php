<?php

namespace Thinmartian\Cms\App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use CmsYaml;

class ResourceRequest extends FormRequest
{
    
    /**
     * @var string
     */
    protected $name;
    
    /**
     * constructor, set the name using the request, we KNOW it's there!
     */
    public function __construct()
    {
        $this->name = request()->get("_name");
        CmsYaml::setFile($this->name);
    }
    
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->buildRules();
    }
    
    /**
     * Build the validation
     * 
     * @return array
     */
    private function buildRules()
    {
        $arr = [];
        $type = $this->getRuleType();
        foreach (CmsYaml::getFields() as $key => $data) {
            if (array_key_exists($type, $data)) $arr[$key] = $data[$type];
        }
        return $arr;
    }
    
    /**
     * Determine what rules we want based on create, edit and delete
     * 
     * @return string
     */
    private function getRuleType()
    {
        switch ($this->method) {
            case "DELETE":
                return "validationOnDelete";
            break;
            case "POST":
                return "validationOnCreate";
            break;
            case "PUT":
            case "PATCH":
                return "validationOnUpdate";
            break;
        }
    }
    
}