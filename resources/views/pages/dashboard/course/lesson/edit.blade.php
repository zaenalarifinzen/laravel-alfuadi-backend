@extends('layouts.dashboard')

@section('title', 'Edit Materi')

@push('style')
    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('library/selectric/public/selectric.css') }}">
    <link rel="stylesheet" href="{{ asset('library/summernote/dist/summernote-bs4.css') }}">
    <style>
        .quiz-card {
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            border: 1px solid #e4e6fc;
            border-radius: 6px;
        }
        .quiz-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            border-color: #6777ef;
        }
    </style>
@endpush

@section('main')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Materi</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item active"><a href="{{ route('home') }}">Dashboard</a></div>
                    <div class="breadcrumb-item active"><a href="{{ route('dashboard.courses.index') }}">Kelas</a></div>
                    <div class="breadcrumb-item active"><a
                            href="{{ route('dashboard.courses.index') }}">{{ $course->title }}</a></div>
                    <div class="breadcrumb-item active"><a
                            href="{{ route('dashboard.lessons.index', $course->id) }}">Materi</a></div>
                    <div class="breadcrumb-item">Edit</div>
                </div>
            </div>

            <div class="section-body">
                <h2 class="section-title">{{ $course->title }}</h2>
                <p class="section-lead">
                    Edit materi pembelajaran.
                </p>

                <div class="row">
                    <div class="col-12">
                        @include('layouts.alert')
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 col-md-6 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ $course->title }}</h4>
                            </div>
                            <div class="card-body">

                                <form action="{{ route('dashboard.lessons.update', [$course->id, $lesson->id]) }}"
                                    method="POST" novalidate>
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label>Judul Materi</label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            name="title" value="{{ $lesson->title }}" required>
                                        @error('title')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Isi Materi</label>
                                        <textarea class="summernote @error('content_markdown') is-invalid @enderror" name="content_markdown">{{ $lesson->content_markdown }}</textarea>
                                        @error('content_markdown')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Video URL</label>
                                        <input type="text" class="form-control @error('video_url') is-invalid @enderror"
                                            name="video_url" value="{{ $lesson->video_url }}" required>
                                        @error('video_url')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Urutan</label>
                                        <input type="number"
                                            class="form-control @error('display_order') is-invalid @enderror"
                                            name="display_order" value="{{ $lesson->display_order }}" required>
                                        @error('display_order')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary px-4">Update</button>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Quiz</h4>
                                <span class="badge badge-primary">{{ $quizzes->count() }}</span>
                            </div>
                            <div class="card-body">
                                @if ($quizzes->count() > 0)
                                    <div class="quiz-list mb-3">
                                        @foreach ($quizzes as $index => $quiz)
                                            <div class="card mb-2 quiz-card quiz-clickable"
                                                data-quiz="{{ json_encode($quiz) }}"
                                                title="Klik untuk mengedit quiz ini">
                                                <div class="card-body p-3">
                                                    <div class="d-flex justify-content-between align-items-start">
                                                        <div class="text-left pr-2" style="flex: 1;">
                                                            <div class="d-flex align-items-center mb-1">
                                                                <span class="badge badge-light mr-2 font-weight-bold">#{{ $index + 1 }}</span>
                                                                <span class="badge badge-success text-uppercase">Kunci: {{ $quiz->correct_answer }}</span>
                                                            </div>
                                                            <p class="mb-0 text-dark font-weight-500" style="white-space: pre-wrap; word-break: break-word;">{{ $quiz->question }}</p>
                                                        </div>
                                                        <div class="d-flex align-items-center">
                                                            <button type="button" class="btn btn-sm btn-outline-primary btn-icon mr-1" title="Edit Quiz">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <form action="{{ route('dashboard.quizzes.destroy', [$course->id, $lesson->id, $quiz->id]) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus quiz ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-sm btn-outline-danger btn-icon btn-delete-quiz" title="Hapus Quiz">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-3">
                                        <div class="empty-state-icon mb-2 text-muted" style="font-size: 2rem;">
                                            <i class="fas fa-question-circle"></i>
                                        </div>
                                        <p class="text-muted mb-3">Belum ada quiz pada materi ini.</p>
                                    </div>
                                @endif
                                <div class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-block" id="btn-add-quiz">
                                        <i class="fas fa-plus mr-1"></i> Tambah Quiz
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Add/Edit Quiz-->
    <div class="modal fade" id="modal-quiz" tabindex="-1" role="dialog" aria-labelledby="modalQuizLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="form-quiz-label">Tambah Quiz</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('dashboard.quizzes.store', [$course->id, $lesson->id]) }}" method="POST"
                    id="form-quiz">
                    @csrf
                    <div id="form-quiz-method"></div>
                    <div class="modal-body">
                        <input type="hidden" id="quiz_id">
                        <div class="form-row">
                            <div class="form-group col-12">
                                <label for="input-question">Pertanyaan <span class="text-danger">*</span></label>
                                <textarea class="form-control" name="question" id="input-question" style="min-height: 100px" required
                                    placeholder="Masukkan pertanyaan quiz..."></textarea>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-12">
                                <label for="option_a">Pilihan A <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-left" name="option_a" id="option_a"
                                    required placeholder="Jawaban A">
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="option_b">Pilihan B <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-left" name="option_b" id="option_b"
                                    required placeholder="Jawaban B">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-12">
                                <label for="option_c">Pilihan C <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-left" name="option_c" id="option_c"
                                    required placeholder="Jawaban C">
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="option_d">Pilihan D <span class="text-danger">*</span></label>
                                <input type="text" class="form-control text-left" name="option_d" id="option_d"
                                    required placeholder="Jawaban D">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-12">
                                <label for="correct_answer">Kunci Jawaban <span class="text-danger">*</span></label>
                                <select name="correct_answer" id="correct_answer" required
                                    class="form-control text-left">
                                    <option value="a">A</option>
                                    <option value="b">B</option>
                                    <option value="c">C</option>
                                    <option value="d">D</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btn-submit-quiz">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('library/summernote/dist/summernote-bs4.js') }}"></script>

    <!-- Page Specific JS File -->
    <script>
        $(document).ready(function() {
            var storeUrl = "{{ route('dashboard.quizzes.store', [$course->id, $lesson->id]) }}";
            var updateUrlTemplate = "{{ route('dashboard.quizzes.update', [$course->id, $lesson->id, ':quiz_id']) }}";

            // Tambah Quiz
            $("#btn-add-quiz").on("click", function() {
                $("#form-quiz")[0].reset();
                $("#form-quiz").attr("action", storeUrl);
                $("#form-quiz-method").empty();
                $("#form-quiz-label").text("Tambah Quiz");
                $("#btn-submit-quiz").text("Simpan");

                $("#modal-quiz").modal({
                    backdrop: "static"
                }).modal("show");
            });

            // Klik Quiz untuk Edit
            $(document).on("click", ".quiz-clickable", function(e) {
                // Abaikan jika klik tombol hapus atau form hapus
                if ($(e.target).closest(".btn-delete-quiz, form").length > 0) {
                    return;
                }

                var quiz = $(this).data("quiz");
                if (!quiz) return;

                $("#form-quiz")[0].reset();

                // Set Action URL & Method PUT
                var updateUrl = updateUrlTemplate.replace(':quiz_id', quiz.id);
                $("#form-quiz").attr("action", updateUrl);
                $("#form-quiz-method").html('<input type="hidden" name="_method" value="PUT">');

                // Isi Form Fields
                $("#quiz_id").val(quiz.id);
                $("#input-question").val(quiz.question);
                $("#option_a").val(quiz.option_a);
                $("#option_b").val(quiz.option_b);
                $("#option_c").val(quiz.option_c);
                $("#option_d").val(quiz.option_d);
                $("#correct_answer").val(quiz.correct_answer);

                $("#form-quiz-label").text("Edit Quiz");
                $("#btn-submit-quiz").text("Update");

                $("#modal-quiz").modal({
                    backdrop: "static"
                }).modal("show");
            });
        });
    </script>
@endpush
