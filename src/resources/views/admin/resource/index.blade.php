@extends("cms::admin.layouts.default")

@section("body_class", "Listing")
@section("title", $title)

@section("content")
    
    <!-- Titles -->
    <div class="Title Utility--clearfix">
        <h1 class="h1">{{ $title }}</h1>
        <div class="Title__buttons">
            <a href="{{ cmsaction($controller . "@create", true, $filters) }}" class="Button Button--icon Button--small Button--blue">
                <i class="Button__icon fa fa-plus-circle"></i>
                Create New
            </a>
        </div>
    </div>
    
    <!-- Filters -->
    <aside class="Filters Box Utility--clearfix">
        <div class="Filters__filter Filters__filter--totals">
            @if ($listing->total())
                Showing results <strong>{{ intval($listing->firstItem()) }}</strong> to <strong>{{ intval($listing->lastItem()) }}</strong> of <strong>{{ $listing->total() }}</strong> | 
            @endif
            @unless (! $listing->total() and ! request()->has("search"))
                <a href="{{ cmsaction($controller . "@index", true) }}">Reset</a>
            @endunless
        </div>
        @unless (! $listing->total() and ! request()->has("search"))
            {{ Form::open(["method" => "GET", "url" => url()->current(), "class" => "Filters__filter Filters__filter--form"]) }}
                {{ Form::hidden("sort", request()->get("sort")) }}
                <label for="search" class="Filters__label">Search</label>
                {{ Form::text("search", request()->get("search"), ["id" => "search", "placeholder" => "Filter results...", "class" => "Form__input Form__input--small Filters__search"]) }}
                <label for="records_per_page" class="Filters__label">Per page</label>
                {{ Form::select(
                    "records_per_page",
                    array_combine(config("cms.cms.records_per_page_options"), config("cms.cms.records_per_page_options")),
                    $perpage,
                    ["id" => "records_per_page", "class" => "Form__select Form__select--small Filters__perpage"]
                )}}
                <button type="submit" class="Form__button Form__button--blue Form__button--small">GO</button>
            {{ Form::close() }}
        @endunless
    </aside>
    
    <!-- Status messages -->
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}
    
    <!-- Listing -->
    <div class="Box">
                
        @if ($listing->total())
            {{ Form::open(["method" => "DELETE", "url" => url()->current() . "/destroy?" . http_build_query($filters)]) }}
                <table class="List">
                    <thead>
                        <tr>
                            <td class="List__buttons" colspan="{{ count($columns) + 2 }}">
                                <button type="submit" class="Button Button--icon Button--tiny Button--red">
                                    <i class="Button__icon fa fa-trash"></i>
                                    Delete selected
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th>{{ Form::checkbox("bla", 1) }}</th>
                            <?php $i = 0; ?>
                            @foreach($columns as $idx => $column)
                                <?php $i++; ?>
                                <th class="{{ $i == 2 ? 'List__main' : null }}">
                                    @if (isset($column["sortable"]) and $column["sortable"])
                                        <a href="{{ cmsaction($controller . "@index", true, array_merge($filters, CmsForm::sortString($idx))) }}">
                                    @endif
                                    {{ $column["label"] }}
                                    {{ CmsForm::sorted($idx) }}
                                    @if (isset($column["sortable"]) and $column["sortable"])
                                        </a>
                                    @endif
                                </th>
                            @endforeach
                            <th>&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($listing as $idx => $record)
                            <tr>
                                <td>{{ Form::checkbox("ids[]", $record->id) }}</td>
                                <?php $i = 0; ?>
                                @foreach($columns as $idx => $column)
                                    <?php $i++; ?>
                                    <td class="{{ $i == 2 ? 'List__main' : null }}">
                                        @if ($i == 2)
                                            <a href="{{ cmsaction($controller . '@edit', true, array_merge(['id' => $record->id], $filters)) }}">
                                        @endif
                                        {{ $record->$idx }}
                                        @if ($i == 2)
                                            </a>
                                        @endif
                                    </td>
                                @endforeach
                                <td><a href="{{ cmsaction($controller . '@edit', true, array_merge(['id' => $record->id], $filters)) }}" class="List__action List__action--edit"><i class="fa fa-pencil-square-o"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            {{ Form::close() }}
        @else
            <div class="Noresults">
                <p class="Noresults__title">Sorry, I got nothing to show you!</p>
                @if (request()->has("search"))
                    <p class="Noresults__link"><a href="{{ cmsaction($controller . "@index", true) }}">Show me all results</a></p>
                @endif
            </div>
        @endif
        
    </div>
    
    <!-- Paging -->
    {!! $listing->appends($filters)->links() !!}
    
    <?php /* ?>
    
    
        
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}
    
    @if ($listing->total())
        {{ Form::open(["method" => "DELETE", "url" => url()->current() . "/destroy?" . http_build_query($filters)]) }}
            <aside>{{ Form::button("delete selected", ["type" => "submit"]) }}</aside>
            <table width="100%">
                <tr>
                    <td></td>
                    @foreach($columns as $idx => $column)
                        <th align="left">
                            @if (isset($column["sortable"]) and $column["sortable"])
                                <a href="{{ cmsaction($controller . "@index", true, array_merge($filters, CmsForm::sortString($idx))) }}">
                            @endif
                            {{ $column["label"] }}
                            {{ CmsForm::sorted($idx) }}
                            @if (isset($column["sortable"]) and $column["sortable"])
                                </a>
                            @endif
                        </th>
                    @endforeach
                        <td colspan="2"></td>
                </tr>
                @foreach($listing as $record)
                    <tr>
                        <td>{{ Form::checkbox("ids[]", $record->id) }}</td>
                        @foreach($columns as $idx => $column)
                            <td>{{ $record->$idx }}</td>
                        @endforeach
                        <td><a href="{{ cmsaction($controller . '@edit', true, array_merge(['id' => $record->id], $filters))}} ">Edit</a></td>
                    </tr>
                @endforeach
            </table>
        {{ Form::close() }}
    @else
        <p>No records to show you</p>
        @if (request()->has("search"))
            <p><a href="{{ cmsaction($controller . "@index", true) }}">Show all results</a></p>
        @endif
    @endif
    
    {!! $listing->appends($filters)->links() !!}
    
    
    <?php */ ?>
    
@endsection