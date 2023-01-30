<div class="card-body p-0">
    <div class="table-responsive">
        <table class="table" id="items-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td  style="width: 120px">
                        {!! Form::open([
                                'route' => ['items.destroy', '#id'],
                                'method' => 'delete',
                                'class' => 'deleteForm'
                            ])
                        !!}
                        <div class='btn-group'>
                            <a class="showBtn" href="{{ route('items.show', ['#id']) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </a>
                            <a class="editBtn" href="{{ route('items.edit', ['#id']) }}"
                               class='btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </a>
                            {!! Form::button('<i class="far fa-trash-alt"></i>', ['type' => 'submit', 'class' => 'btn btn-danger btn-xs', 'onclick' => "return confirm('Are you sure?')"]) !!}
                        </div>
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="card-footer clearfix">
        <div class="float-right">
            @include('adminlte-templates::common.paginate', ['records' => $items])
        </div>
    </div>
</div>
@push('page_scripts')
    <script src="{{ mix('js/views/items.js') }}"></script>
@endpush
