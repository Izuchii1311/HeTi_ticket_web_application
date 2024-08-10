@extends("templates.main")

@section("content")
    <div class="page-content">
        <div class="container-fluid">

            <div class="row">

                @if (Session::has("success"))
                    <div class="alert alert-success">
                        {{ Session::get("success") }}
                    </div>
                @endif

                <div class="col-lg-8">

                    {{-- Card content --}}
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/images/users/' . ($imageAuthor->profile_picture ?? 'assets/img/default_image.jpg')) }}" class="rounded-circle me-3" width="50" height="50" alt="user-pict">
                                <div>
                                    <h5 class="card-title">
                                        {{-- {{ $ticket->name }} -  --}}
                                        {{ $ticket->subject }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">{{ $ticket->created_at->diffForHumans() }} <br>
                                        Dibuat sejak: {{ $ticket->created_at }}</h6>
                                </div>

                                @if ($ticket->priority == "HIGH")
                                    <h6 class="ms-auto">
                                        <span class="badge bg-danger">{{ $ticket->priority }}</span>
                                    </h6>
                                @elseif ($ticket->priority == "MEDIUM")
                                    <h6 class="ms-auto">
                                        <span class="badge bg-warning">{{ $ticket->priority }}</span>
                                    </h6>
                                @else
                                    <h6 class="ms-auto">
                                        <span class="badge bg-primary">{{ $ticket->priority }}</span></h6>
                                @endif
                            </div>

                            <p class="mt-3"><i class="text-grey">#{{ $ticket->no_ticket }} - {{ $ticket->team }}</i></p>
                            <p class="mt-2">
                                <b>Tipe tiket : </b>{{ $ticket->type }} <br>
                                <b>Status tiket : </b>{{ $ticket->status }} <br>
                                <b>Dibuat oleh : </b>{{ $user->name }} <br>
                            </p>

                            <div>
                                <hr><h6><b>Deskripsi Tiket :</b></h6>
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td>{!! $ticket->description !!}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Card Attachment & Percakapan --}}
                    <div class="card">
                        <ul class="nav nav-tabs mt-3" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <a class="nav-link active" id="percakapan-tab" data-bs-toggle="tab" href="#percakapan" role="tab" aria-controls="percakapan" aria-selected="true">Percakapan</a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link" id="lampiran-tab" data-bs-toggle="tab" href="#lampiran" role="tab" aria-controls="lampiran" aria-selected="false">Lampiran</a>
                            </li>
                        </ul>

                        {{-- Card comment --}}
                        <div class="card-body">
                            <div class="d-flex flex-column">
                                <div class="tab-content flex-grow-1">
                                    <div class="tab-pane fade show active" id="percakapan" role="tabpanel" aria-labelledby="percakapan-tab">
                                        @foreach ($comments as $comment)
                                            <div class="card mb-2">
                                                <div class="card-header bg-danger text-white">
                                                    <div class="d-flex justify-content-between align-items-center">

                                                        @if ($user->id == $comment->user_id)
                                                            <p class="m-0"><b>{{ $comment->user->name }}</b> memberikan komentar</p>
                                                        @else
                                                            <p class="m-0"><b>{{ $comment->user->name }}</b> membalas komentar</p>
                                                        @endif


                                                        <small>{{ $comment->created_at->diffForHumans() }} ({{ $comment->created_at->format("d/m/Y H:i") }})</small>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <p>{{ $comment->message }}</p>
                                                    @if ($comment->attachments)
                                                        @php
                                                            $attachments = json_decode($comment->attachments, true);
                                                        @endphp
                                                        @foreach ($attachments as $attachment)
                                                            @php
                                                                $ext = pathinfo($attachment, PATHINFO_EXTENSION);
                                                                $attachmentPath = 'storage/' . $attachment;
                                                                $fileName = basename($attachment);
                                                            @endphp
                                                            <a href="#" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#fileModal"
                                                            data-file="{{ asset($attachmentPath) }}" data-type="{{ $ext }}">{{ $fileName }}</a>
                                                            <br>
                                                        @endforeach
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="tab-pane fade" id="lampiran" role="tabpanel" aria-labelledby="lampiran-tab">
                                        @if ($attachments && count($attachments) > 0)
                                            <div class="attachments">
                                                <h2>Lampiran file</h2>

                                                @foreach ($attachments as $attachment)
                                                    <div class="attachment mb-2">
                                                        <a href="#" data-bs-toggle="modal" data-bs-target="#modal-{{ $loop->index }}">
                                                            {{ $attachment }}
                                                        </a>

                                                        <!-- Modal -->
                                                        <div class="modal fade" id="modal-{{ $loop->index }}" tabindex="-1" aria-labelledby="modalLabel-{{ $loop->index }}" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="modalLabel-{{ $loop->index }}">{{ $attachment }}</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @if (in_array(pathinfo($attachment, PATHINFO_EXTENSION), ["jpg", "jpeg", "png"]))
                                                                            <img src="{{ asset("storage/images/attachment/" . $attachment) }}" alt="{{ $attachment }}" class="img-fluid">
                                                                        @elseif (pathinfo($attachment, PATHINFO_EXTENSION) === "pdf")
                                                                            <iframe src="{{ asset("storage/images/attachment/" . $attachment) }}" type="application/pdf" width="100%" height="600px">
                                                                                This browser does not support PDFs. Please download the PDF to view it: <a href="{{ asset("storage/images/attachment/" . $attachment) }}">Download PDF</a>.
                                                                            </iframe>
                                                                        @else
                                                                            <p>File type not supported for preview.</p>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach

                                            </div>
                                        @else
                                            <p>No attachments found.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <button id="replyButton" class="btn btn-secondary mt-3" data-bs-toggle="modal" data-bs-target="#replyModal">
                                <i class="bx bx-reply"></i> Balas
                            </button>
                        </div>
                    </div>

                </div>

                <div class="col-lg-4">

                    <div class="card">
                        <div class="card-body">
                            <h6>Diproses oleh:</h6>
                            <div class="d-flex align-items-center">
                                <img src="https://www.unipulse.tokyo/en/wp-content/plugins/all-in-one-seo-pack/images/default-user-image.png"
                                    class="rounded img-thumbnail mr-3" width="50" alt="user-pict">
                                <div class="ms-2">
                                    @if($ticket->status == "Open")
                                        <h6 class=" text-danger"><i>Belum diproses oleh {{ $ticket->team }}</i></h6>
                                        <p class="mb-0"><i>Menunggu...</i></p>
                                    @else
                                        <h6 class="card-title">{{ $employee->name }}</h6>
                                        <p class="mb-0">{{ $employeeEmail->email }}</p>
                                    @endif
                                </div>
                            </div>
                            <h6 class="mt-3"><b>Histori :</b></h6>
                            <div class="d-flex">
                                <ul>
                                    @php
                                        $lastIndex = count($statusHistory) - 1;
                                    @endphp

                                    @forelse ($statusHistory as $index => $history)
                                        <li class="{{ $index === $lastIndex ? "main-text mt-2" : "second-text" }}">
                                            <!-- Tampilkan informasi status history di sini -->
                                            {{ $history->text }}
                                            <br>
                                            {{ $history->created_at_indo }}
                                        </li>
                                    @empty
                                        <li>Tidak ada status history</li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            @if (Auth::user()->role == "agent" || Auth::user()->role == "admin")
                                @if ($ticket->status != "Open")
                                    <h6 class="card-title">Perbarui Status Tiket</h6>
                                    <hr>
                                    <form action="{{ route("update-status-ticket", $ticket->id) }}" method="POST">
                                        @csrf
                                        @method("PUT")

                                        {{-- Status --}}
                                        <div class="form-group my-2">
                                            <label for="update_status">Status</label>
                                            <select class="form-control" name="status" id="update_status">
                                                <option value="">- Pilih -</option>
                                                <option value="Pending">Pending</option>
                                                <option value="Resolved">Resolved</option>
                                                <option value="Waiting">Waiting</option>
                                                <option value="Processed">Processed</option>
                                            </select>
                                            @error("status")
                                                <div class="text-danger mt-2"><i>{{ $message }}</i></div>
                                            @enderror
                                        </div>

                                        {{-- Priority --}}
                                        {{-- <div class="form-group my-2">
                                            <label for="priority">Prioritas Tiket</label>
                                            <select class="form-control" name="priority" id="priority">
                                                <option value="">- Pilih -</option>
                                                <option value="LOW">LOW</option>
                                                <option value="MEDIUM">MEDIUM</option>
                                                <option value="HIGH">HIGH</option>
                                            </select>
                                            @error("priority")
                                                <div class="text-danger mt-2"><i>{{ $message }}</i></div>
                                            @enderror
                                        </div> --}}

                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-dark me-2" id="btn-cancel" data-dismiss="modal">Reset</button>
                                            <button type="submit" class="btn btn-primary" id="btn-save">Simpan <i class="bx bx-check"></i></button>
                                        </div>
                                    </form>
                                @else
                                    <h6 class="card-title">Aksi</h6>
                                    <hr>
                                    <p><i>Menunggu ticket di pickup...</i></p>
                                @endif
                            @endif

                            @if (Auth::user()->role == "admin" || Auth::user()->role == "customer-service")
                                {{-- @if(>role) --}}
                                <div class="d-flex justify-content-end mt-2">
                                    <form action="{{ route("ticket.delete", $ticket->id) }}" method="POST" onsubmit="return confirm("Are you sure you want to delete this ticket?");">
                                        @csrf
                                        @method("delete")
                                        <button type="submit" class="btn btn-danger">Hapus Tiket <i class="bx bx-trash"></i></button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>


                </div>

            </div>
        </div>
    </div>

    <!-- Modal Comment -->
    <div class="modal fade" id="replyModal" tabindex="-1" aria-labelledby="replyModalLabel" aria-hidden="true">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="replyModalLabel">Komentar</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route("sendReply") }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="ticket_id" value="{{ $ticket->id }}">
                                <div class="mb-3">
                                    <textarea class="form-control" id="text" name="text" rows="3"
                                        placeholder="Tulis balasan Anda..."></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="attachments">Lampiran Gambar</label>
                                    <input type="file" class="form-control" id="attachments" name="attachments[]" multiple>
                                </div>
                                @if(Auth::user()->role == "admin" || Auth::user()->role == "agent")
                                    <div class="mb-3 mt-2">
                                        <h5>Template Balasan</h5>
                                        <hr>
                                        <div class="d-flex flex-wrap">
                                            @forelse ($templates as $template)
                                                <h5 class="me-2">
                                                    <span class="badge bg-primary template-badge"
                                                        data-bs-toggle="tooltip"
                                                        title="{{ $template->description }}"
                                                        data-description="{{ $template->description }}">
                                                        {{ $template->tag }}
                                                    </span>
                                                </h5>
                                            @empty
                                            <p><i>Belum ada template balasan</i></p>
                                            @endforelse
                                        </div>
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-primary">Kirim Balasan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fileModalLabel">File Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="fileModalBody">
                    {{-- Tampilkan gambar disini --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    @push('style')
        <style>
            .template-badge {
                cursor: pointer; /* Pastikan kursor berubah saat hover */
                pointer-events: auto; /* Pastikan elemen dapat menerima event klik */
            }

            .main-text {
                color: black;
                border-top: 3px solid salmon;
                padding-top: 10px;
            }
            .second-text {
                color: gray;
                font-style: italic;
                font-size: 12px;
            }

            .card-header {
                background-color: #dc3545;
                /* Warna latar belakang card header */
            }

            .text-white {
                color: #ffffff;
                /* Warna teks putih */
            }

            .modal-dialog {
                max-width: 90%;
                /* Ubah ukuran modal sesuai kebutuhan */
            }

            .modal-content img {
                max-width: 100%;
                height: auto;
            }

            .card-new-badge {
                background-color: #5bc0de;
                color: white;
                font-size: 0.75em;
                padding: 0.25em 0.5em;
                border-radius: 0.25rem;
                font-weight: bold;
            }

            .nav-tabs .nav-link.active {
                color: #d9534f;
                font-weight: bold;
                border-color: #d9534f;
            }
        </style>
    @endpush

    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var cancelButton = document.querySelector('#btn-cancel');
                var dropdownStatus = document.getElementById('update_status');
                // var dropdownPriority = document.getElementById('priority');

                // console.log("Cancel Button:", cancelButton);
                // console.log("Dropdown Status:", dropdownStatus);
                // console.log("Dropdown Priority:", dropdownPriority);

                if (cancelButton) {
                    cancelButton.addEventListener('click', function() {
                        if (dropdownStatus) dropdownStatus.selectedIndex = 0;
                        if (dropdownPriority) dropdownPriority.selectedIndex = 0;
                    });
                }

                // Template balasan
                var templateBadges = document.querySelectorAll('.template-badge');
                var textArea = document.getElementById('text');

                console.log("Text Area:", textArea);
                console.log("Template Badges:", templateBadges);

                if (textArea) {
                    templateBadges.forEach(function(badge) {
                        badge.addEventListener('click', function() {
                            var description = this.getAttribute('data-description');
                            console.log("Badge clicked:", description);
                            textArea.value = description;
                        });
                    });
                }

                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                });


                // Image attachment
                var fileModal = document.getElementById('fileModal');
                fileModal.addEventListener('show.bs.modal', function(event) {
                    var button = event.relatedTarget;
                    var file = button.getAttribute('data-file');
                    var fileType = button.getAttribute('data-type');

                    var modalBody = fileModal.querySelector('#fileModalBody');

                    if (['jpg', 'jpeg', 'png'].includes(fileType)) {
                        modalBody.innerHTML = '<img src="' + file + '" alt="Attachment" class="img-fluid">';
                    } else if (fileType === 'pdf') {
                        modalBody.innerHTML = '<iframe src="' + file + '" type="application/pdf" width="100%" height="600px">This browser does not support PDFs. Please download the PDF to view it: <a href="' + file + '">Download PDF</a>.</iframe>';
                    } else {
                        modalBody.innerHTML = '<p>File type not supported for preview.</p>';
                    }
                });
            });

        </script>
    @endpush


@endsection
