@extends("cms::admin.layouts.default")

@section("title", "LISTING")

@section("content")
    
    <h1>{{ $title }}</h1>
    
    @if ($listing->total())
        Showing results {{intval($listing->firstItem())}} to {{ intval($listing->lastItem()) }} of {{ $listing->total() }} | 
    @endif
     
    @unless (! $listing->total() and ! request()->has("search"))
        <a href="{{ cmsaction($controller . "@index", true) }}">Reset</a>
        <div style="float: right">
            {{ Form::open(["method" => "GET", "url" => url()->current(), "style" => "display: inline", "id" => "listing-form"]) }}
                {{ Form::hidden("sort", request()->get("sort")) }}
                Filter {{ Form::text("search", request()->get("search")) }}
                Per page: {{ Form::select("records_per_page", array_combine(config("cms.cms.records_per_page_options"), config("cms.cms.records_per_page_options")), $perpage, ["onchange" => "document.getElementById('listing-form').submit()"]) }}
                <button type="submit">GO</button>
            {{ Form::close() }}
            &nbsp; <a href="{{ cmsaction($controller . "@create", true, $filters) }}">Create New</a>
        </div>
    @endunless
    
    <hr>
        
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
    
@endsection