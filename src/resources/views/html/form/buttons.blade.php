</div></div><div class="Box Utility--clearfix">
    <div class="Form__buttons">
        @if (! isset($hide_save))
            {{ CmsForm::submit(["label" => isset($save_label) ? $save_label : "Save", "icon" => isset($save_icon) ? $save_icon : "check", "name" => "save", "color" => "blue"]) }}
        @endif
        @if (! isset($hide_save_exit))
            {{ CmsForm::submit(["label" => isset($save_exit_label) ? $save_exit_label : "Save and exit", "icon" => isset($save_exit_icon) ? $save_exit_icon : "bars", "name" => "saveexit", "color" => "green"]) }}
        @endif
    </div>
    @if (! isset($hide_cancel))
        <div class="Form__cancel">
            {{ CmsForm::cancel(["url" => cmsaction($controller . '@index', true, $filters)]) }}
        </div>
    @endif
</div>