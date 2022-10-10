@extends('layouts.main')

@section('page_title','welcome')

@section('title', 'Inlokari - Job Fields')

@section('breadcrumb')

        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Job Fields</li>

@endsection

@section('content')

<div class="row">
    <div class="col-md-10"></div>
    <div class="col-md-2">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalCreate">
            Create New Job Field
        </button>
    </div>

    <div class="col-md-12">
        <div class="card-header">
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered text-nowrap" id="dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Field Name</th>
                            <th>Desc</th>
                            <th>Created at</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

</div>

    <!-- Modal Create-->
  <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="#" class="form-create" method="POST">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Create New Job Field</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <div class="form-group">
                <label>Job Field Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter Job Field Name">
            </div>
            <div class="form-group">
                <label>Description</label>
                <input name="description" class="form-control" placeholder="Enter Description">
            </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Discard</button>
            <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Edit-->
  <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="#" class="form-edit" method="POST">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Edit Job Field</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="id">
            <div class="form-group">
                <label>Job Field Name</label>
                <input type="text" name="name" class="form-control" placeholder="Enter Job Field Name">
            </div>
            <div class="form-group">
                <label>Description</label>
                <input name="description" class="form-control" placeholder="Enter Description">
            </div>

            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Discard</button>
            <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
      </div>
    </div>
  </div>

@endsection

@push('custom-script')


<script>

    $(function() {

            loadData()

        });

        function loadData() {

            $.ajax ({
            url: "/jobfield/getData",
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
                    {"data": "description"},
                    {"data": "created_date"},
                    {"data": "id"}
                ],
                "columnDefs": [
                    {
                        "targets": 4,
                        "data": "id",
                        "render": function(data, type, row) {
                            // console.log(row);

                            return '<div class="btn-group">'+
                                        '<button type="button" class="btn btn-default">Edit or Delete</button>'+
                                        '<button type="button" class="btn btn-default dropdown-toggle dropdown-icon" data-toggle="dropdown">'+
                                            '<span class="sr-only">Toggle Dropdown</span>'+
                                        '</button>'+
                                        '<div class="dropdown-menu" role="menu">'+
                                            '<a class="dropdown-item btn-edit" data-id="'+row.id+'" href="#">Edit</a>'+
                                            '<input type="submit" class="dropdown-item btn-delete" data-id="'+row.id+'" value="delete" href="#">'+
                                        '</div>'+
                                    '</div>';
                        }
                    }

                ],
            });

            }).fail(function(xhr, error) {
                console.log('xhr', xhr.status)
                console.log('error', error)
            })

        }

        $(document).on('submit', '.form-create', function(e) {
            e.preventDefault()

            var form = $(this)
            var inputToken = form.find("input[name=_token]")

            $.ajax({
                url: "jobfield/createData",
                type: "POST",
                data: {
                    _token : inputToken.val(),
                    name: form.find("input[name=name]").val(),
                    description: form.find("input[name=description]").val(),
                }
            }).done(function(result) {

                inputToken.val(result.newToken)

                if(result.status) {
                    $('#modalCreate').modal('hide');
                    alert(result.message)
                    loadData()
                } else {
                    alert(result.message)
                }

            }).fail(function(xhr, error) {

            })

        })

        $(document).on('click', '.btn-edit', function(e) {
            e.preventDefault();

            $.ajax({
                url: "jobfield/getData",
                type: "GET",
                data: {
                    id: $(this).data('id')
                }
            }).done(function(result) {

                if (result.data) {

                    var form = $('.form-edit')
                    var data = result.data

                    form.find('input[name=id]').val(data.id)
                    form.find('input[name=name]').val(data.name)
                    form.find('input[name=description]').val(data.description)

                    $('#modalEdit').modal('show')

                } else {

                    alert('Data not found')

                }

            }).fail(function(xhr, error) {

                console.log('xhr', xhr.status)
                console.log('error', error)
            })
        })

        $(document).on('submit', '.form-edit', function(e) {
            e.preventDefault()

            var form = $(this)
            var inputToken = form.find("input[name=_token]")

            $.ajax({
                url: "jobfield/updateData/"+form.find("input[name=id]").val(),
                type: "POST",
                data: {
                    _token : inputToken.val(),
                    name: form.find("input[name=name]").val(),
                    description: form.find("input[name=description]").val(),
                }
            }).done(function(result) {

                inputToken.val(result.newToken)

                if(result.status) {
                    $('#modalEdit').modal('hide')
                    alert(result.message)
                    loadData()
                } else {
                    alert(result.message)
                }

            }).fail(function(xhr, error) {

            })

        })

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault()

            if(confirm('Are you sure to delete the data?')) {

                var inputToken = $("input[name=_token]")

                $.ajax({
                    url: "/jobfield/deleteData/"+$(this).data('id'),
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

</script>

@endpush
