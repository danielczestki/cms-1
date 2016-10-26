<?php

namespace Thinmartian\Cms\App\Http\Requests\Core;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Routing\Router;
use CmsYaml;

class ResourceRequest extends FormRequest
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var Illuminate\Routing\Router
     */
    protected $route;

    /**
     * constructor, set the name using the request, we KNOW it's there!
     */
    public function __construct(Router $route)
    {
        $this->route = $route;
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
     * Set custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return $this->buildAttributes();
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
            if (array_key_exists($type, $data)) {
                $string = $data[$type];
                foreach ($this->route->current()->parameters() as $idx => $value) {
                    $string = str_replace('{'.$idx.'}', $value, $string);
                }
                $arr[$key] = $string;
            }
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
        switch ($this->method()) {
            case "POST":
                return "validationOnCreate";
            break;
            case "PUT":
            case "PATCH":
                return "validationOnUpdate";
            break;
        }
    }

    /**
     * Build the attributes
     *
     * @return array
     */
    private function buildAttributes()
    {
        $arr = [];
        foreach (CmsYaml::getFields() as $key => $data) {
            $arr[$key] = strtolower($data["label"]);
        }
        return $arr;
    }
}
