@extends("cms::admin.layouts.default")

@section("body_class", "Listing")
@section("title", $title)

@inject("grid", "Thinmartian\Cms\App\Services\Resource\Grid")

@section("content")

    <!-- Title -->
    <div class="Title Utility--clearfix">
        <div class="Title__titles">
            <h1 class="h1">{{ $title }}</h1>
        </div>
        <div class="Title__buttons">
            <a href="{{ cmsaction($controller . "@create", false, $filters) }}" class="Button Button--icon Button--small Button--blue">
                <i class="Button__icon fa fa-plus-circle"></i>
                Create New
            </a>
        </div>
    </div>

    <!-- Filters -->
    @unless (! $listing->count() and ! request()->has("search"))
        <aside class="Filters Box Utility--clearfix">
            <div class="Filters__filter Filters__filter--totals">
                {{--@if ($listing->count())
                    Showing results <strong>{{ intval($listing->firstItem()) }}</strong> to <strong>{{ intval($listing->lastItem()) }}</strong> of <strong>{{ $listing->count() }}</strong> |
                @endif--}}
                @unless (! $listing->count() and ! request()->has("search"))
                    <a href="{{ cmsaction($controller . "@index", false) }}">Reset</a>
                @endunless
            </div>
            @unless (! $listing->count() and ! request()->has("search"))
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
    @endunless

    <!-- Status messages -->
    {{ CmsForm::error() }}
    {{ CmsForm::success() }}

    <!-- Listing -->
    <div class="Box">

        @if ($listing->count())
            {{ Form::open(["method" => "DELETE", "url" => url()->current() . "/destroy?" . http_build_query($filters)]) }}
                <table class="List">
                    <thead>
                        <tr>
                            <td class="List__keep List__buttons" colspan="{{ count($columns) + 2 }}">
                                <button type="submit" class="Button Button--icon Button--tiny Button--red">
                                    <i class="Button__icon fa fa-trash"></i>
                                    Delete selected
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <th class="List__keep">{{ Form::checkbox("bla", 1) }}</th>
                            <?php $i = 0; ?>
                            @foreach($columns as $idx => $column)
                                <?php $i++; ?>
                                <th class="{{ $i == 2 ? 'List__keep List__main' : null }}">
                                    @if (isset($column["sortable"]) and $column["sortable"])
                                        <a href="{{ cmsaction($controller . "@index", false, array_merge($filters, CmsForm::sortString($idx))) }}">
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
                                <td class="List__keep List__check">{{ Form::checkbox("ids[]", $record->id) }}</td>
                                <?php $i = 0; ?>
                                @foreach($columns as $idx => $column)
                                    <?php $i++; ?>
                                    <td class="{{ $i == 2 ? 'List__keep List__main' : null }}">
                                        @if ($i == 2)
                                            <a href="{{ cmsaction($controller . '@edit', false, array_merge(['id' => $record->id], $filters)) }}">
                                        @endif
                                        {{ $grid->render($record, $column) }}
                                        @if ($i == 2)
                                            </a>
                                        @endif
                                    </td>
                                @endforeach
                                <td><a href="{{ cmsaction($controller . '@edit', false, array_merge(['id' => $record->id], $filters)) }}" class="List__action List__action--edit"><i class="fa fa-pencil-square-o"></i></a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            {{ Form::close() }}
        @else
            <div class="Noresults">
                <p class="Noresults__title">No results founds</p>
                @if (request()->has("search"))
                    <p class="Noresults__link"><a href="{{ cmsaction($controller . "@index", false) }}">Show me all results</a></p>
                @endif
            </div>
        @endif

    </div>

    <!-- Paging -->
    {{--
    @if ($listing->hasPages())
        <div class="Box Paging">
            {!! $listing->appends($filters)->links() !!}
        </div>
    @endif
    --}}

@endsection
