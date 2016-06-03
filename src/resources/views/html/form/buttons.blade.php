</div></div><div class="Box Utility--clearfix">
    <div class="Form__buttons">
        {{ CmsForm::submit(["label" => "Save", "icon" => "check", "name" => "save", "color" => "blue"]) }}
        {{ CmsForm::submit(["label" => "Save and exit", "icon" => "bars", "name" => "saveexit", "color" => "green"]) }}
    </div>
    <div class="Form__cancel">
        {{ CmsForm::cancel(["url" => cmsaction($controller . '@index', true, $filters)]) }}
    </div>
</div>