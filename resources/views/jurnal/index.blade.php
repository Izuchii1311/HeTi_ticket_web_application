@extends('templates.main')

@section('content')
    <div class="container mt-5 pt-5"> <!-- Tambahkan kelas mt-5 di sini -->
        <h1>Buat Jurnal</h1>

        <!-- Tampilkan pesan error jika ada -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (auth()->check())
            @if ($hasFilledPresensis)
                <form action="{{ route('jurnal.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="judul">Judul Jurnal:</label>
                        <input type="text" name="judul" id="judul" class="form-control snow-theme" required placeholder="masukan judul"  >
                    </div>
                    <div class="form-group mt-3">
                        <label for="deskripsi">Deskripsi:</label>
                        <!-- Quill Editor -->
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
                                <button class="ql-image"></button>
                                <button class="ql-video"></button>
                                <button class="ql-formula"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-clean"></button>
                            </span>
                        </div>
                        <div id="editor"></div>
                        <!-- Hidden Input to Store Quill.js Content -->
                        <input type="hidden" name="deskripsi" id="deskripsi">
                    </div>
                    <button type="submit" class="btn btn-primary mt-3">Tambah Jurnal</button>
                </form>
            @else
                <div class="alert alert-warning">Anda harus mengisi presensi terlebih dahulu sebelum dapat mengisi jurnal.</div>
            @endif
        @else
            <div class="alert alert-danger">Anda tidak memiliki izin untuk mengisi jurnal untuk pengguna ini.</div>
        @endif

    </div>

    <div class="card mt-5 container mt-5 pt-5"> <!-- Tambahkan kelas mt-5 di sini -->
        <div class="card-body mb-5" >
            <h4 class="card-title mb-5" >Activity</h4>
            <ul class="verti-timeline list-unstyled">
                @php $count = 0; @endphp
                @foreach ($data as $jurnal)
                    @if ($count >= 5)
                        @break
                    @endif

                    <li class="event-list " >
                        <div class="event-timeline-dot mt-4">
                            <i class="bx bx-right-arrow-circle font-size-18"></i>
                        </div>
                        <div class="d-flex">
                            <div class="flex-shrink-0 mt-4 me-3">
                                <h5 class="font-size-14"> {{ $jurnal->created_at->format('d M Y H:i:s') }} <i
                                        class="bx bx-right-arrow-alt font-size-16 text-primary align-middle ms-2"></i>
                                </h5>
                            </div>
                            <div class="container p-4 bg-light rounded-border p-4 shadow">
                                <div class="bg-secondar-light rounded shadow">
                                    <h3><strong>Judul Jurnal : {!! $jurnal->judul !!}</strong></h3>
                                </div>
                                <p><strong>Deskripsi :</strong></p>
                                <div class="border border-dark rounded- p-4 bg-white mt-3" id="fullDescription{{ $jurnal->id }}">

                                    <p>{!! $jurnal->deskripsi !!}</p>
                                </div>
                            </div>
                        </div>
                    </li>
                    @php $count++; @endphp
                @endforeach
            </ul>
        </div>
    </div>

    @push('js')
    <!-- Include Quill.js and Highlight.js Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script>
        // Initialize Quill.js
        const quill = new Quill('#editor', {
            modules: {
                syntax: true,
                toolbar: '#toolbar-container',
            },
            placeholder: 'masukan jurnal',
            theme: 'snow',
        });

        // Store Quill.js Content in Hidden Input
        const quillEditor = document.getElementById('deskripsi');
        quill.on('text-change', function() {
            quillEditor.value = quill.root.innerHTML;
        });

        quillEditor.addEventListener('input', function() {
            quill.root.innerHTML = quillEditor.value;
        });

        // Custom Font Configuration
        const Font = Quill.import('formats/font');
        Font.whitelist = ['arial', 'comic-sans', 'courier-new', 'georgia', 'helvetica', 'lucida'];
        Quill.register(Font, true);
    </script>
@endpush

@push('style')
    <!-- Include Quill.js, Highlight.js, and KaTeX CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">

    <!-- Custom Style -->

    <style>
        .rounded-border {
            border-radius: 15px; /* Memberikan efek melengkung pada border */
        }
    </style>
    <style>
        .form-group {
            margin-bottom: 20px;
        }

        #editor {
            height: 200px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 10px;
            padding: 10px;
            overflow-y: auto;
        }

        #editor .ql-editor {
            height: 100%;
        }

        .ql-font-arial {
            font-family: Arial, sans-serif;
        }
        .ql-font-comic-sans {
            font-family: 'Comic Sans MS', sans-serif;
        }
        .ql-font-courier-new {
            font-family: 'Courier New', monospace;
        }
        .ql-font-georgia {
            font-family: Georgia, serif;
        }
        .ql-font-helvetica {
            font-family: Helvetica, sans-serif;
        }
        .ql-font-lucida {
            font-family: 'Lucida Sans', sans-serif;
        }

        .full-description {
            margin-top: 10px;
        }

        .read-more-btn,
        .read-less-btn {
            cursor: pointer;
            color: blue;
            text-decoration: underline;
        }

        .hide {
            display: none;
        }

    </style>
@endpush

@endsection
