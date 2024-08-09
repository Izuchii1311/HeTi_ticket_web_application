@extends('templates.main')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="panel-main panel-lighter">

                <div class="main-header">
                    <div class="page-header">
                        <h3 class="page-name">Daftar Tiket</h3>
                        <p class="page-name">Kumpulan data tiket yang sudah dibuat</p>
                    </div>
                </div>

                <!-- body -->
                <div class="row">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">

                                {{-- Display success message --}}
                                @if (Session::has('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif

                                @forelse ($templates as $template)
                                    <div class="card shadow-lg mb-3">
                                        <div class="card-body d-flex justify-content-between align-items-center">
                                            <div>
                                                <h4><span class="badge bg-primary">{{ $template->tag }}</span></h4>
                                                <p>{{ $template->description }}</p>
                                            </div>
                                            <div class="d-flex">
                                                <button 
                                                    class="btn btn-info btn-sm edit-template mx-2" 
                                                    data-id="{{ $template->id }}"
                                                    data-tag="{{ $template->tag }}"
                                                    data-description="{{ $template->description }}">
                                                    Edit
                                                </button>
                                                <button 
                                                    class="btn btn-danger btn-sm delete-template" 
                                                    data-id="{{ $template->id }}">
                                                    Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center">Belum ada template balasan dibuat.</p>
                                @endforelse

                            </div>
                        </div>
                    </div>

                    <!-- Form untuk Create Data -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h4>Create New Template Comment</h4>
                                <form id="templateCommentForm">
                                    @csrf
                                    <div class="form-group">
                                        <label for="tag">Tag</label>
                                        <input type="text" class="form-control" id="tag" name="tag" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-2">Submit</button>
                                </form>
                                <div id="responseMessage" class="mt-3"></div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    @push('js')
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#templateCommentForm').on('submit', function(event) {
                event.preventDefault(); // Prevent the default form submission

                $.ajax({
                    url: "{{ route('template-comments.store') }}", // Route URL
                    method: 'POST',
                    data: $(this).serialize(), // Serialize form data
                    success: function(response) {
                        $('#responseMessage').html('<div class="alert alert-success">Template komen berhasil dibuat!</div>');
                        $('#templateCommentForm')[0].reset(); // Reset form after submission
                        location.reload(); // Refresh the page after successful submission
                    },
                    error: function(xhr) {
                        $('#responseMessage').html('<div class="alert alert-danger">An error occurred: ' + xhr.responseText + '</div>');
                    }
                });
            });

            // Delete template using AJAX
            $(document).on('click', '.delete-template', function() {
                if (confirm('Apa kamu yakin template ini dihapus?')) {
                    var templateId = $(this).data('id');
                    var button = $(this);

                    $.ajax({
                        url: "{{ route('template-comments.destroy', ':id') }}".replace(':id', templateId), // Correct URL for DELETE request
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}' // Include CSRF token
                        },
                        success: function(response) {
                            alert('Template komen berhasil dihapus');
                            button.closest('.card').remove(); // Remove the card from the UI
                        },
                        error: function(xhr) {
                            $('#responseMessage').html('<div class="alert alert-danger">An error occurred: ' + xhr.responseText + '</div>');
                        }
                    });

                }
            });

            // Fill the form with template data on Edit button click
            $(document).on('click', '.edit-template', function() {
                var tag = $(this).data('tag');
                var description = $(this).data('description');

                $('#tag').val(tag); // Set tag value to input field
                $('#description').val(description); // Set description value to textarea
            });
        });
    </script>
    @endpush
@endsection
