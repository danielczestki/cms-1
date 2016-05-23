@extends("cms::admin.layouts.default")

@section("title", "LISTING")

@section("content")
    
    <h1>{{ $title }}</h1>
    
    Showing results {{$listing->firstItem()}} to {{ $listing->lastItem() }} of {{ $listing->total() }} | <a href="{{ url()->current() }}">Reset</a>
    
    <div style="float: right">
        {{ Form::open(["method" => "GET", "url" => url()->current(), "style" => "display: inline", "id" => "listing-form"]) }}
            Per page: {{ Form::select("records_per_page", array_combine(config("cms.cms.records_per_page_options"), config("cms.cms.records_per_page_options")), $perpage, ["onchange" => "document.getElementById('listing-form').submit()"]) }}
        {{ Form::close() }}
        &nbsp; <a href="{{ cmsaction($controller . "@create", true, $filters) }}">Create New</a>
    </div>
    
    <hr>
        
    {{ CmsForm::success() }}
    
    @if ($listing->total())
        {{ Form::open(["method" => "DELETE", "url" => url()->current() . "/destroy"]) }}
            <aside>{{ Form::button("delete selected", ["type" => "submit"]) }}</aside>
            <table width="100%">
                <tr>
                    <td></td>
                    @foreach($columns as $column)
                        <th align="left">{{ $column }}</th>
                    @endforeach
                        <td colspan="2"></td>
                </tr>
                @foreach($listing as $record)
                    <tr>
                        <td>{{ Form::checkbox("ids[]", $record->id) }}</td>
                        @foreach($columns as $idx => $column)
                            <td>{{ $record->$idx }}</td>
                        @endforeach
                        <td><a href="{{ url()->current() }}/{{ $record->id }}/edit">Edit</a></td>
                    </tr>
                @endforeach
            </table>
        {{ Form::close() }}
    @else
        <p>No records to show you</p>
    @endif
    
    {!! $listing->appends(["records_per_page" => $perpage])->links() !!}
    
@endsection