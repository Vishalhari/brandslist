@extends('layouts.app')
@section('content')
    @push('styles')
    @endpush
    <div class="container">
        <h2 class="mb-4">Brands List</h2>
        @if (Session::has('message'))
            <div class="alert alert-success fade in alert-dismissible" style="margin-top:18px;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                <strong>Success!</strong> {{ Session::get('message') }}
            </div>
        @endif

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="alert alert-danger fade in alert-dismissible" role="alert">
                    <strong>Error</strong> {{ $error }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endforeach
        @endif
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add</button>

        <table id="myTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Brand Name</th>
                    <th>Logo</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>


    <div class="modal fade" id="createModal" role="dialog">
        <div class="modal-dialog">
            <form method="post" action="{{ url('admin/brands') }}" enctype='multipart/form-data'>
                @csrf


                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add Brands</h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email">Brand Name:</label>
                            <input type="text" class="form-control" id="brandname" name="brandname">
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="brandlogo">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
            </form>

        </div>

    </div>
    </div>



    <div class="modal fade" id="editModal" role="dialog">
        <div class="modal-dialog">
            <form method="post" action="" enctype='multipart/form-data'>
                @csrf
                {{ method_field('PATCH') }}


                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Edit Brands</h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email">Brand Name:</label>
                            <input type="text" class="form-control brandname" id="brandname" name="brandname">
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="customFile" name="brandlogo">
                            <label class="custom-file-label" for="customFile">Choose file</label>
                            <div class="imgblk"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
            </form>

        </div>

    </div>
    </div>


    <div class="modal fade" id="deleteModal" role="dialog">
        <div class="modal-dialog">
            <form method="POST" action="">
                @csrf
                {{ method_field('DELETE') }}


                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Delete Confirmation</h4>
                    </div>

                    <div class="modal-body">
                        <p>Are you sure you want to Delete?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
            </form>

        </div>

    </div>
    </div>



@endsection
@push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(function() {
            var table = $('#myTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('brands.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'brandname',
                        name: 'brandname'
                    },
                    {
                        data: 'logo',
                        name: 'logo',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });



        });
        // Add the following code if you want the name of the file appear on select
        $(".custom-file-input").on("change", function() {
            var fileName = $(this).val().split("\\").pop();
            $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
        });


        $(document).on('click', '.deletebrand', function() {
            let id = $(this).data('id');
            var action = "{{ url('admin/brands/') }}" + '/' + id;
            $('#deleteModal form').attr('action', action);
        });

        $(document).on('click', '.editbrand', function() {
            let id = $(this).data('id');
            var showurl = "{{ url('admin/brands/') }}" + '/' + id;

            $.ajax({
                url: showurl,
                dataType: "json",
                type: "get",
                success: function(res) {
                    console.log(res);

                    $('#editModal .brandname').val(res.brandname);
                    if (res.logo != '') {
                        var url = "{{ asset('storage/brands/') }}" + '/' + res.logo;
                        var img = '<img src="' + url + '" alt="Image" width="50" height="50">';
                        $('#editModal .imgblk').html(img);
                    }
                }
            });

            var action = "{{ url('admin/brands/') }}" + '/' + id;
            $('#editModal form').attr('action', action);

        });
    </script>
@endpush
