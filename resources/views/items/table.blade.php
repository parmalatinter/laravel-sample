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

                        <div class='btn-group'>
                            <button class='showBtn btn btn-default btn-xs'>
                                <i class="far fa-eye"></i>
                            </button>
                            <button class='editBtn btn btn-default btn-xs'>
                                <i class="far fa-edit"></i>
                            </button>
                            <button class='destroyBtn btn btn-danger btn-xs'>
                                <i class="far fa-trash-alt"></i>
                            </button>
                        </div>
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

<!-- form modal -->
<div class="modal" tabindex="-1" role="dialog" id="form-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Form</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="error-div"></div>
                <form>
                    <input type="hidden" name="update_id" id="update_id">
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name">
                    </div>
                    <button type="submit" class="btn btn-outline-primary mt-3" id="saveBtn">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- view modal -->
<div class="modal " tabindex="-1" role="dialog" id="view-modal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Information</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <b>Name:</b>
                <p id="name-info"></p>
            </div>
        </div>
    </div>
</div>
