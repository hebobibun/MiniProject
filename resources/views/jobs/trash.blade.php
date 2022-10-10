@extends('layouts.main')

@section('title', 'Trash News')

@section('page_title', 'Trash News')

@section('breadcrumb')

    <li class="breadcrumb-item"><a href="/">Home</a></li>
    <li class="breadcrumb-item active"><a>Trash - News</a></li>

@endsection

@section('content')

<div class="row">
    <div class="col-md-12 mt-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">List Data</h3>
            </div>
            <div class="card-body table-responsive">
                <table id="dataTable" class="table table-bordered text-nowrap">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Job Role</th>
                        <th>Job Field</th>
                        <th>Company</th>
                        <th>Location</th>
                        <th>Created at</th>
                        <th>Action</th>
                    </tr>
                </thead>

                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
          <form action="#" class="form-create" method="POST">
              @csrf
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter title ..." required>
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="id_category" class="form-control">
                            @foreach($jobfield as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Enter description ..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save</button>
                </div>
           </form>
      </div>
    </div>
  </div>

  {{-- <!-- Modal -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
          <form action="#" class="form-edit" method="POST">
              @csrf
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Create Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" placeholder="Enter title ..." required>
                        <input type="hidden" name="id">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="id_category" class="form-control" >
                            @foreach ($category as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Enter description ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update</button>
                </div>
           </form>
      </div>
    </div>
  </div> --}}

@endsection

@push('custom-script')

    <script>


        $(function() {

            loadData()
        });

        function loadData() {
            $.ajax({
            url: "/jobs/getDataTrash",
            type: "GET",
            data: {}
            }).done(function(result) {

                $('#dataTable').DataTable({
                    "paging": true,
                    "searching": true,
                    "ordering": true,
                    "responsive": true,
                    "destroy": true,
                    "data": result.data,
                    "columns": [
                        {"data": "no"},
                        {"data": "name"},
                        {"data": "jobfield.name"},
                        {"data": "company"},
                        {"data": "location"},
                        {"data": "created_date"},
                        {"data": "id"}
                    ],
                    "columnDefs": [
                        {
                            "targets": 6,
                            "data": "id",
                            "render": function(data, type, row) {
                                return '<div class="btn-group">'+
                                        '<button type="button" class="btn btn-default">Action</button>'+
                                        '<button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">'+
                                        '<span class="sr-only">Toggle Dropdown</span>'+
                                        '</button>'+
                                        '<div class="dropdown-menu" role="menu">'+
                                            '<a class="dropdown-item btn-restore" data-id="'+row.id+'" href="#">Restore Data</a>'+
                                            '<input type="submit" class="dropdown-item btn-delete" data-id="'+row.id+'"  value="Delete" href="#">'+
                                        '</div>'+
                                    '</div>';
                            }
                        }
                    ]

                });

            }).fail(function(xhr, error) {
                console.log('xhr', xhr.status)
                console.log('error', error)
            })
        }

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault()

            if(confirm('Are you sure to delete data permanently? Your data cannot be restored anymore forever.')) {
                var inputToken = $("input[name=_token]")

                $.ajax({
                    url: "/jobs/deletePermanent/"+$(this).data('id'),
                    type: "POST",
                    data: {
                        _token: inputToken.val()
                    }
                }).done(function(result) {

                    inputToken.val(result.newToken)
                    if (result.status) {
                        loadData()
                    } else {
                        alert(result.message)
                    }

                }).fail(function(xhr, error) {
                    console.log('xhr', xhr.status)
                    console.log('error', error)
                })
            }


        })

        $(document).on('click', '.btn-restore', function(e) {
            e.preventDefault()

            if(confirm('Are you sure to restore data ?')) {
                var inputToken = $("input[name=_token]")

                $.ajax({
                    url: "/jobs/restoreData/"+$(this).data('id'),
                    type: "GET",
                    data: {
                        _token: inputToken.val()
                    }
                }).done(function(result) {

                    inputToken.val(result.newToken)
                    if (result.status) {
                        loadData()
                    } else {
                        alert(result.message)
                    }

                }).fail(function(xhr, error) {
                    console.log('xhr', xhr.status)
                    console.log('error', error)
                })
            }


        })

    </script>

@endpush
