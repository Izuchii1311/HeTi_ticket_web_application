@extends('templates.main')

@section('content')
    <div class="page-content">
        <div class="container-fluid">
            <div class="panel-main panel-lighter">

                {{-- Header --}}
                <div class="main-header">
                    <div class="page-header">
                        <h3 class="page-name">Tiket Baru</h3>
                        <p class="page-maps">
                            <a href="{{ route('ticket-create') }}">Tiket</a> / <span class="current">Buat tiket baru</span>
                        </p>
                    </div>
                </div>

                <!-- body -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">

                                <form action="{{ route('ticket-store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                                    @csrf

                                    {{-- Subject --}}
                                    <div class="form-group my-2">
                                        <label>
                                            Subjek<sup class="text-danger"><b>*</b></sup>
                                        </label>
                                        <input type="text" class="form-control" id="subject" name="subject" autocomplete="off">
                                        @error('subject')
                                            <div class="text-danger mt-2"><i>{{ $message }}</i></div>
                                        @enderror
                                    </div>

                                    {{-- Name --}}
                                    {{-- <div class="form-group my-2">
                                        <label>
                                            Nama<sup class="text-danger"><b>*</b></sup>
                                        </label>
                                        <input type="text" class="form-control" id="name" name="name" autocomplete="off">
                                        @error('name')
                                            <div class="text-danger mt-2"><i>{{ $message }}</i></div>
                                        @enderror
                                    </div> --}}

                                    {{-- Type --}}
                                    <div class="form-group my-2">
                                        <label>
                                            Tipe<sup class="text-danger"><b>*</b></sup>
                                        </label>
                                        <select class="form-control select2-hidden-accessible" name="type" id="type"
                                         aria-hidden="true">
                                            <option value="">- Pilih -</option>
                                            <option value="Request">Request</option>
                                            <option value="Question">Question</option>
                                            <option value="Incident">Incident</option>
                                            <option value="Problem">Problem</option>
                                            <option value="Feature request">Feature request</option>
                                            <option value="Lead">Lead</option>
                                            <option value="Bug application">Bug application</option>
                                        </select>
                                        @error('type')
                                            <div class="text-danger mt-2"><i>{{ $message }}</i></div>
                                        @enderror
                                    </div>

                                    {{-- Team --}}
                                    <div class="form-group my-2">
                                        <label>Tim<sup class="text-danger"><b>*</b></label>
                                        <select class="form-control select2-hidden-accessible" name="team" id="team"
                                            aria-hidden="true">
                                            <option value="">- Pilih -</option>
                                            @forelse ($employees as $employee)
                                                @if ($employee->is_active == true)
                                                    <option value="{{ $employee->name }}">
                                                        {{ $employee->name }}
                                                    </option>
                                                @endif
                                            @empty
                                                <option value="">Akun belum dibuat oleh admin</option>
                                            @endforelse
                                        </select>
                                        @error('team')
                                            <div class="text-danger mt-2"><i>{{ $message }}</i></div>
                                        @enderror
                                    </div>

                                    {{-- Priority --}}
                                    <div class="form-group my-2">
                                        <label>Prioritas Tiket<sup class="text-danger"><b>*</b></label>
                                        <select class="form-control select2-hidden-accessible" name="priority" id="priority"
                                            aria-hidden="true">
                                            <option value="">- Pilih -</option>
                                            <option value="LOW">LOW</option>
                                            <option value="MEDIUM">MEDIUM</option>
                                            <option value="HIGH">HIGH</option>
                                        </select>
                                        @error('priority')
                                            <div class="text-danger mt-2"><i>{{ $message }}</i></div>
                                        @enderror
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="form-group my-2 mt-3">
                                        <label for="description">Deskripsi:</label>
                                        <div id="toolbar-container">
                                            <span class="ql-formats">
                                                <select class="ql-font"></select>
                                                <select class="ql-size"></select>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-bold"></button>
                                                <button class="ql-italic"></button>
                                                <button class="ql-underline"></button>
                                                <button class="ql-strike"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <select class="ql-color"></select>
                                                <select class="ql-background"></select>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-script" value="sub"></button>
                                                <button class="ql-script" value="super"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-header" value="1"></button>
                                                <button class="ql-header" value="2"></button>
                                                <button class="ql-blockquote"></button>
                                                <button class="ql-code-block"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-list" value="ordered"></button>
                                                <button class="ql-list" value="bullet"></button>
                                                <button class="ql-indent" value="-1"></button>
                                                <button class="ql-indent" value="+1"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-direction" value="rtl"></button>
                                                <select class="ql-align"></select>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-link"></button>
                                                {{-- <button class="ql-image"></button> --}}
                                                <button class="ql-video"></button>
                                                <button class="ql-formula"></button>
                                            </span>
                                            <span class="ql-formats">
                                                <button class="ql-clean"></button>
                                            </span>
                                        </div>
                                        <div id="editor"></div>
                                        <!-- Hidden Input to Store Quill.js Content -->
                                        <input type="hidden" name="description" id="description">
                                    </div>

                                    <div class="form-group my-2">
                                        <label>Lampiran</label>
                                        <input type="file" class="form-control" name="attachments[]" id="attachments"
                                            multiple>
                                    </div>

                                    <button type="button" class="btn btn-dark waves-effect waves-light"
                                        data-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary waves-effect waves-light"
                                        id="btn-save">Simpan</button>

                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            // Initialize Quill.js
            const quill = new Quill('#editor', {
                modules: {
                    syntax: true,
                    toolbar: '#toolbar-container',
                },
                placeholder: 'Berikan deskripsi tiket (Opsional)',
                theme: 'snow',
            });

            // Store Quill.js Content in Hidden Input and log to console
            const quillEditor = document.getElementById('description');
            quill.on('text-change', function() {
                quillEditor.value = quill.root.innerHTML;
                // console.log('Editor Content:', quillEditor.value);
            });

            // Custom Font Configuration
            const Font = Quill.import('formats/font');
            Font.whitelist = ['arial', 'comic-sans', 'courier-new', 'georgia', 'helvetica', 'lucida'];
            Quill.register(Font, true);
        </script>
    @endpush

@endsection
