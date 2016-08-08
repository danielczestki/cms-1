<?php 

namespace Thinmartian\Cms\App\Services\Resource;

use Illuminate\Support\HtmlString;

class Grid {
    
    /**
     * Top level rneder method that delegates to the correct column type
     * 
     * @param  Model $record The record contaion the data
     * @param  array $column The array column data
     * @return string
     */
    public function render($record, $column)
    {
        $value = $record->$column["name"];
        $type = $column["type"];
        return $this->$type($value);
    }
    
    /**
     * Output a number type
     * 
     * @param  string $value
     * @return string
     */
    protected function number($value)
    {
        return new HtmlString('<div class="List__column List__column--center"><span class="List__type List__type--number">'. $value .'</span></div>');
    }
    
    /**
     * Output a boolean type
     * 
     * @param  string $value
     * @return string
     */
    protected function boolean($value)
    {
        return new HtmlString('<div class="List__column List__column--center"><i class="fa fa-check-circle List__type List__type--boolean List__type--boolean-'. $value .'"></i></div>');
    }
    
    /**
     * Output a date type
     * 
     * @param  string $value
     * @return string
     */
    protected function date($value)
    {
        return $this->_date($value, "jS M Y");
    }
    
    /**
     * Output a datetime type
     * 
     * @param  string $value
     * @return string
     */
    protected function datetime($value)
    {
        return $this->_date($value);
    }
    
    /**
     * Render a data/datetime type
     * 
     * @param  string $value 
     * @param  string $format The date format we want
     * @return string
     */
    private function _date($value, $format = "jS M Y H:i")
    {
        if ($value instanceof \Carbon\Carbon) {
            return new HtmlString('<div class="List__column"><span class="List__type List__type--date">'. $value->format($format) .'</span></div>');
        } else {
            $carbon = new \Carbon\Carbon;
            return new HtmlString('<div class="List__column"><span class="List__type List__type--date">'. $carbon->parse($value)->format($format) .'</span></div>');
        }
    }
    
    /**
     * Output a text type (capture all)
     * 
     * @param  string $value
     * @return string
     */
    public function __call($name, $args)
    {
        return new HtmlString('<div class="List__column"><span class="List__type List__type--'. $name .'">'. head($args) .'</span></div>');
    }
    
}