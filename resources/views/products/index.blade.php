@extends('products.layout.app')

@section('title')
index
@endsection

@section('content')

<div class="container mt-4">
    <h1>Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-success">Create New Product</a>

    <table id="productTable" class="table table-bordered">
        <thead>
            <tr>
                <th><input type="checkbox" id="select-all"></th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
        </thead>
    </table>
</div>

@push('script')
<script>
    $(document).ready(function() {
        $('#productTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('products.index') }}",
            columns: [
                {
                    data: 'checkbox',
                    name: 'checkbox',
                    orderable: false,
                    searchable: false
                },
                { data: 'name',name:'name' },
                { data: 'description', name:'description' },
                { data: 'price',name: 'price' },
                { data: 'action', orderable: false, searchable: false }
            ]
        });
    });

</script>

<script>
    // sweet alert delete data
    $(function() {
    $(document).on('click', '.delete-form button', function(e) {
        e.preventDefault();
        let deleteId = $('#deleteId').val();
        var form = $(this).closest('form');
        var url = form.attr('action');

        Swal.fire({
            title: 'Are you sure?',
            text: "Delete This Data? id="+deleteId,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _method: 'DELETE',
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
                        // Refresh DataTable
                        $('#productTable').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', 'Unable to delete data.', 'error');
                    }
                });
            }
        });
    });
});

</script>
@endpush
@endsection
