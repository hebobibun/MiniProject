@extends('layouts.main')

@section('page_title','welcome')

@section('title', 'Inlokari - Jobs Data')

@section('breadcrumb')

        <li class="breadcrumb-item"><a href="#">Home</a></li>
        <li class="breadcrumb-item active">Job List</li>

@endsection

@section('content')

<h1>JOB LIST</h1>

<div class="row">
    <div class="col-md-10"></div>
    <div class="col-md-2">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalCreate">
            Create New Job
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

    <!-- Modal Create-->
  <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <form action="#" class="form-create" method="POST">
            @csrf
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLongTitle">Create New Job</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <div class="form-group">
                <label>Job Role</label>
                <input type="text" name="name" class="form-control" placeholder="Enter Job Role">
            </div>
            <div class="form-group">
                <label>Job Field</label>
                <select name="id_jobfield" class="form-control">
                @foreach($joblist as $d)
                <option value="{{ $d->id }}">{{  $d->name }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Company</label>
                <input name="company" rows="3" class="form-control" placeholder="Enter company name">
            </div>
            <div class="form-group">
                <label>Location</label>
                <input name="location" class="form-control" placeholder="Enter location">
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
            <h5 class="modal-title" id="exampleModalLongTitle">Edit Job</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            </div>
            <div class="modal-body">
            <input type="hidden" name="id">
            <div class="form-group">
                <label>Job Role</label>
                <input type="text" name="name" class="form-control" placeholder="Enter Job Role">
            </div>
            <div class="form-group">
                <label>Job Field</label>
                <select name="id_jobfield" class="form-control">
                @foreach($joblist as $d)
                <option value="{{ $d->id }}">{{  $d->name }}</option>
                @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Company Name</label>
                <input name="company" class="form-control" placeholder="Enter company name">
            </div>
            <div class="form-group">
                <label>Company Name</label>
                <input name="location" class="form-control" placeholder="Enter location">
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
            url: "/jobs/getData",
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

        $(document).on('click', '.btn-delete', function(e) {
            e.preventDefault()

            if(confirm('Are you sure to delete the data?')) {

                var inputToken = $("input[name=_token]")

                $.ajax({
                    url: "/jobs/deleteData/"+$(this).data('id'),
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

        $(document).on('submit', '.form-create', function(e) {
            e.preventDefault()

            var form = $(this)
            var inputToken = form.find("input[name=_token]")

            $.ajax({
                url: "jobs/createData",
                type: "POST",
                data: {
                    _token : inputToken.val(),
                    name: form.find("input[name=name]").val(),
                    id_jobfield: form.find("select[name=id_jobfield]").val(),
                    company: form.find("input[name=company]").val(),
                    location: form.find("input[name=location]").val(),
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
                url: "jobs/getData",
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
                    form.find('select[name=id_jobfield]').val(data.id_jobfield)
                    form.find('input[name=company]').val(data.company)
                    form.find('input[name=location]').val(data.location)

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
                url: "jobs/updateData/"+form.find("input[name=id]").val(),
                type: "POST",
                data: {
                    _token : inputToken.val(),
                    name: form.find("input[name=name]").val(),
                    company: form.find("input[name=company]").val(),
                    id_jobfield: form.find("select[name=id_jobfield]").val(),
                    location: form.find("input[name=location]").val()
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



</script>



@endpush
