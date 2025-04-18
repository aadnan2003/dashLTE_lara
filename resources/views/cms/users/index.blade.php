@extends('cms.parent')

@section('style')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Responsive Hover Table</h3>

                            <div class="card-tools">
                                <div class="input-group input-group-sm" style="width: 150px;">
                                    <input type="text" name="table_search" class="form-control float-right"
                                        placeholder="Search">

                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-default">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Address</th>
                                        <th>Mobile</th>
                                        <th>Categories</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>
                                                <img class="direct-chat-img" src="{{ Storage::url($user->image) }}"
                                                    alt="message user image">
                                            </td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                @if (!is_null($user->address))
                                                    <span style="font-weight: bold">{{ $user->address }}</span>
                                                @else
                                                    <span style="color: red">No Address</span>
                                                @endif
                                            </td>
                                            <td>
                                                {{-- if(condition){} else {}  --}}
                                                {{-- condition ? "TRUE" : "FALSE"  --}}
                                                <span
                                                    class="{{ $user->mobile == '' ? 'no-data' : 'with-data' }}">{{ $user->fullMobile }}</span>
                                            </td>
                                            <td>{{ $user->categories_count }}</td>
                                            <td>{{ $user->created_at ?? '--' }}</td>
                                            {{-- <td><span class="tag tag-success">Approved</span></td> --}}
                                            <td class="options">
                                                <a href="{{ route('users.edit', $user->id) }}">Edit</a>
                                                {{-- <a href="{{ route('users.destroy', $user->id) }}" style="color: red">Delete</a> --}}

                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST">
                                                    @method('DELETE')
                                                    @csrf
                                                    <button type="submit" class="delete-btn">Delete</button>
                                                </form>

                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
@endsection
