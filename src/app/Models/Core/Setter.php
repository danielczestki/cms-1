<?php

namespace Thinmartian\Cms\App\Models\Core;

use Symfony\Component\Yaml\Parser;
use Thinmartian\Cms\App\Services\Cms;

trait Setter
{
    
    /**
     * Read the YAML and build the fillable fields
     */
    protected function setCmsFillable()
    {
        if (property_exists($this, "fillable") and $this->fillable) {
            return false;
        }
        $arr = [];
        $fields = $this->getYamlFields();
        foreach ($fields as $idx => $data) {
            if ($this->allowedColumn($data)) {
                $arr[] = $idx;
            }
        }
        $this->fillable($arr);
    }
    
    /**
     * Is this column allowed in the fillable
     *
     * @param  array $data Array of data from yaml
     * @return boolean
     */
    private function allowedColumn($data)
    {
        $cms = new Cms;
        if (array_key_exists("persist", $data) and ! $data["persist"]) {
            return false;
        }
        if (! array_key_exists("type", $data)) {
            return true;
        }
        if (in_array($data["type"], $cms->getIgnoredFieldTypes())) {
            return false;
        }
        return true;
    }
    
    /**
     * Read the YAML and build the date fields
     */
    protected function setCmsDates()
    {
        if (property_exists($this, "dates") and $this->dates) {
            return false;
        }
        $arr = [];
        $fields = $this->getYamlFields();
        foreach ($fields as $idx => $data) {
            if (array_key_exists("type", $data) and in_array($data["type"], ["date", "datetime"])) {
                $arr[] = $idx;
            }
        }
        $this->dates = $arr;
    }
    
    /**
     * Return the fields array from the YAML
     *
     * @return array
     */
    private function getYamlFields()
    {
        $parser = new Parser;
        $yaml = $parser->parse(file_get_contents(app_path("Cms/Definitions/{$this->yaml}.yaml")));
        return $yaml["fields"];
    }
}
