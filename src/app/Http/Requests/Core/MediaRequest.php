<?php

namespace Thinmartian\Cms\App\Http\Requests\Core;

use CmsImage, CmsVideo, CmsDocument, CmsEmbed;
use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
{
    
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
        $method = in_array($this->method, ["PUT", "PATCH"]) ? "validationOnUpdate" : "validationOnCreate";
        switch (request()->get("type")) {
            case "image" :
                return CmsImage::$method();
            break;
            case "video" :
                return CmsVideo::$method();
            break;
            case "document" :
                return CmsDocument::$method();
            break;
            case "embed" :
                return CmsEmbed::$method();
            break;
        }
    }
    
}