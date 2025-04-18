@extends('cms.parent')

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <!-- left column -->
                <div class="col-md-12">
                    <!-- general form elements -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Create Category</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        {{-- <form action="{{ route('categories.store') }}" method="POST"> --}}
                        <form>
                            @csrf
                            <div class="card-body">
                                {{-- @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert"
                                            aria-hidden="true">×</button>
                                        <h5><i class="icon fas fa-ban"></i> Errors!</h5>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif --}}
                                <div class="form-group">
                                    <label>User</label>
                                    <select class="form-control" id="user_id">
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="category_title">Title</label>
                                    <input type="text" class="form-control" id="category_title"
                                        placeholder="Enter title">
                                </div>
                                <div class="form-group">
                                    <label for="category_info">Info</label>
                                    <input type="text" class="form-control" id="category_info" placeholder="Enter info">
                                </div>
                                <div class="form-group">
                                    <div
                                        class="custom-control custom-switch custom-switch-off-danger custom-switch-on-success">
                                        <input type="checkbox" class="custom-control-input" id="active" name="active">
                                        <label class="custom-control-label" for="active">Active</label>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer">
                                {{-- <button type="submit" class="btn btn-primary">Submit</button> --}}
                                <button type="button" onclick="saveCategoryAxios()" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    <script>
        function saveCategory() {
            //fetch
            const token = document.getElementsByName('_token')[0].value
            fetch('/cms/admin/categories', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        _token: token,
                        title: document.getElementById('category_title').value,
                        info: document.getElementById('category_info').value,
                        user_id: document.getElementById('user_id').value,
                        active: document.getElementById('active').checked,
                    })
                })
                .then((response) => {
                    //
                    console.log(response);
                    return response.json();
                })
                .then((response) => {
                    //
                    console.log(response);
                    Swal.fire({
                        position: 'center',
                        icon: response.icon,
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    })
                    return response;
                })
                .catch((error) => {
                    //
                    console.log('ERROR');
                })
            //axios
        }

        function saveCategoryAxios() {
            axios.post('/cms/admin/categories', {
                    title: document.getElementById('category_title').value,
                    info: document.getElementById('category_info').value,
                    user_id: document.getElementById('user_id').value,
                    active: document.getElementById('active').checked,
                }).then(function(response) {
                    console.log(response);
                    showMessage(response.data.icon, response.data.message);
                })
                .catch(function(error) {
                    console.log(error);
                    showMessage(error.response.data.icon, error.response.data.message);
                });
        }

        function showMessage(icon, message) {
            Swal.fire({
                position: 'center',
                icon: icon,
                title: message,
                showConfirmButton: false,
                timer: 1500
            })
        }
    </script>
@endsection
