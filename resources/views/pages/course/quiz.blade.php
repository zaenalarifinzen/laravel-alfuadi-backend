<!DOCTYPE html>
<html lang="id">

<head>
    @include('components.theme-script')
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>Exercise: {{ $lesson->title }} &verbar; {{ $course->title }} &verbar; Al-Fuadi</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('library/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fira+Code:wght@400;500;600&family=Amiri+Quran&family=Amiri:wght@400;700&display=swap">

    <style>
        :root {
            --quiz-bg: #ececec;
            --quiz-card-bg: #f8fafc;
            --quiz-card-hover: #e5f0f0;
            --quiz-card-active: #cde9ee;
            --quiz-card-border: rgba(255, 255, 255, 0.12);
            --quiz-text-main: #000305;
            --quiz-text-muted: #778292;
            --quiz-green: #04aa6d;
            --quiz-green-hover: #038f5b;
            --quiz-green-light: rgba(4, 170, 109, 0.22);
            --quiz-option-text: #000305;
            --quiz-red: #ef4444;
            --quiz-red-light: rgba(239, 68, 68, 0.22);
        }

        html[data-theme="dark"],
        html.dark-mode {
            --quiz-bg: #283445;
            --quiz-card-bg: #38475c;
            --quiz-card-hover: #43556d;
            --quiz-card-active: #334d6e;
            --quiz-card-border: rgba(255, 255, 255, 0.12);
            --quiz-text-main: #f8fafc;
            --quiz-text-muted: #94a3b8;
            --quiz-green: #04aa6d;
            --quiz-green-hover: #038f5b;
            --quiz-green-light: rgba(4, 170, 109, 0.22);
            --quiz-option-text: #f8fafc;
            --quiz-red: #ef4444;
            --quiz-red-light: rgba(239, 68, 68, 0.22);
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--quiz-bg);
            color: var(--quiz-text-main);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        /* Top Bar with Close Button */
        .quiz-topbar {
            width: 100%;
            height: 60px;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            /* background: linear-gradient(180deg, rgba(26, 36, 49, 0.95) 0%, rgba(26, 36, 49, 0) 100%); */
        }

        .quiz-brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--quiz-text-muted);
            font-size: 0.85rem;
            font-weight: 600;
            text-decoration: none;
            padding: 0.35rem 0.75rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.06);
            backdrop-filter: blur(8px);
            transition: all 0.2s ease;
        }

        .quiz-brand-badge:hover {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.12);
            text-decoration: none;
        }

        .quiz-brand-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--quiz-green);
        }

        /* Close Button X */
        .quiz-close-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.15);
            color: #ffffff;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
            cursor: pointer;
            transition: all 0.2s ease;
            outline: none !important;
        }

        .quiz-close-btn:hover {
            background: rgba(239, 68, 68, 0.85);
            border-color: rgba(239, 68, 68, 1);
            color: #ffffff;
            transform: scale(1.05);
        }

        .quiz-close-btn:active {
            transform: scale(0.95);
        }

        /* Main Center Container */
        .quiz-main-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 5rem 1.25rem 3rem;
            width: 100%;
            max-width: 780px;
            margin: 0 auto;
        }

        /* Exercise Header */
        .quiz-header {
            text-align: center;
            margin-bottom: 2rem;
            width: 100%;
        }


        /* Segmented Progress Bar */
        .quiz-progress-segments {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            max-width: 320px;
            margin: 0 auto 0.75rem;
        }

        .quiz-progress-pill {
            flex: 1;
            height: 6px;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.18);
            transition: all 0.3s ease;
        }

        .quiz-progress-pill.active {
            background: var(--quiz-green);
            box-shadow: 0 0 10px rgba(4, 170, 109, 0.6);
        }

        .quiz-progress-pill.completed {
            background: var(--quiz-green);
        }

        .quiz-progress-pill.incorrect {
            background: var(--quiz-red);
        }

        .quiz-progress-text {
            font-size: 0.85rem;
            color: var(--quiz-text-muted);
            font-weight: 600;
        }

        /* Question Box */
        .quiz-question-box {
            width: 100%;
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .quiz-question-text {
            font-size: 1.15rem;
            font-weight: 600;
            color: #ffffff;
            line-height: 1.6;
            margin: 0;
        }

        .is-arabic {
            font-family: 'Amiri Quran', 'Amiri', serif !important;
            direction: rtl;
            font-size: 1.5rem !important;
            line-height: 2 !important;
        }

        /* Options List */
        .quiz-options-list {
            width: 100%;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
            margin-bottom: 2rem;
        }

        .quiz-option-card {
            background: var(--quiz-card-bg);
            border: 1px solid var(--quiz-card-border);
            border-radius: 8px;
            padding: 0.85rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            cursor: pointer;
            transition: all 0.18s ease;
            user-select: none;
            position: relative;
        }

        .quiz-option-card:hover:not(.locked) {
            background: var(--quiz-card-hover);
            border-color: rgba(255, 255, 255, 0.3);
            transform: translateY(-1px);
        }

        .quiz-option-card.selected {
            background: var(--quiz-card-active);
            border-color: #38bdf8;
            box-shadow: 0 0 0 1px #38bdf8;
        }

        .quiz-option-card.correct {
            background: var(--quiz-green-light) !important;
            border-color: var(--quiz-green) !important;
            box-shadow: 0 0 0 1px var(--quiz-green) !important;
            color: #ffffff;
        }

        .quiz-option-card.wrong {
            background: var(--quiz-red-light) !important;
            border-color: var(--quiz-red) !important;
            box-shadow: 0 0 0 1px var(--quiz-red) !important;
        }

        /* Dot Indicator */
        .quiz-option-dot {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.85);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            transition: all 0.2s ease;
        }

        .quiz-option-card.selected .quiz-option-dot {
            background: #38bdf8;
            box-shadow: 0 0 8px rgba(56, 189, 248, 0.8);
        }

        .quiz-option-card.correct .quiz-option-dot {
            background: var(--quiz-green);
            box-shadow: 0 0 8px rgba(4, 170, 109, 0.8);
        }

        .quiz-option-card.wrong .quiz-option-dot {
            background: var(--quiz-red);
            box-shadow: 0 0 8px rgba(239, 68, 68, 0.8);
        }

        .quiz-option-text {
            font-family: 'Fira Code', 'Plus Jakarta Sans', monospace;
            font-size: 0.975rem;
            color: var(--quiz-option-text);
            line-height: 1.4;
            flex: 1;
        }

        .quiz-option-key {
            font-size: 0.72rem;
            color: var(--quiz-text-muted);
            background: rgba(255, 255, 255, 0.08);
            padding: 0.15rem 0.45rem;
            border-radius: 4px;
            font-family: 'Fira Code', monospace;
        }

        /* Action Buttons Area */
        .quiz-action-area {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1rem;
            width: 100%;
            margin-bottom: 2.5rem;
        }

        /* Submit Button */
        .quiz-submit-btn {
            background-color: var(--quiz-green);
            color: #ffffff;
            border: none;
            border-radius: 6px;
            padding: 0.75rem 2rem;
            font-size: 1.05rem;
            font-weight: 700;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            transition: all 0.2s ease;
            box-shadow: 0 4px 14px rgba(4, 170, 109, 0.35);
            outline: none !important;
        }

        .quiz-submit-btn:hover:not(:disabled) {
            background-color: var(--quiz-green-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(4, 170, 109, 0.45);
            color: #ffffff;
            text-decoration: none;
        }

        .quiz-submit-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        /* Feedback Message */
        .quiz-feedback-box {
            display: none;
            padding: 0.85rem 1.5rem;
            border-radius: 8px;
            font-size: 0.95rem;
            font-weight: 600;
            width: 100%;
            text-align: center;
            animation: fadeIn 0.25s ease;
        }

        .quiz-feedback-box.correct {
            display: block;
            background: var(--quiz-green-light);
            border: 1px solid var(--quiz-green);
            color: #34d399;
        }

        .quiz-feedback-box.wrong {
            display: block;
            background: var(--quiz-red-light);
            border: 1px solid var(--quiz-red);
            color: #f87171;
        }

        .quiz-secondary-btns {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .quiz-retry-btn {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 6px;
            padding: 0.7rem 1.5rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quiz-retry-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }

        /* Results / Finish Screen */
        .quiz-results-card {
            display: none;
            background: #323f52;
            border: 1px solid rgba(255, 255, 255, 0.12);
            border-radius: 14px;
            padding: 2.5rem 2rem;
            width: 100%;
            text-align: center;
            box-shadow: 0 12px 36px rgba(0, 0, 0, 0.35);
            animation: fadeIn 0.3s ease;
        }

        .quiz-score-badge {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(4, 170, 109, 0.2), rgba(4, 170, 109, 0.4));
            border: 2px solid var(--quiz-green);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.25rem;
            margin: 0 auto 1.5rem;
            box-shadow: 0 0 24px rgba(4, 170, 109, 0.4);
        }

        .quiz-results-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 0.5rem;
        }

        .quiz-results-score {
            font-size: 2.25rem;
            font-weight: 800;
            color: var(--quiz-green);
            margin-bottom: 0.5rem;
        }

        .quiz-results-desc {
            font-size: 0.95rem;
            color: var(--quiz-text-muted);
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .quiz-summary-list {
            text-align: left;
            margin-bottom: 2rem;
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .quiz-summary-item {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            padding: 0.75rem 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            font-size: 0.9rem;
        }

        .quiz-summary-item.is-correct {
            border-left: 4px solid var(--quiz-green);
        }

        .quiz-summary-item.is-wrong {
            border-left: 4px solid var(--quiz-red);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(6px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media (max-width: 576px) {
            .quiz-title {
                font-size: 1.45rem;
            }

            .quiz-question-text {
                font-size: 1rem;
            }

            .quiz-option-card {
                padding: 0.75rem 1rem;
            }

            .quiz-main-wrapper {
                padding-top: 4.5rem;
            }
        }
    </style>
</head>

<body>
    <!-- Top Navigation Bar -->
    <header class="quiz-topbar">
        <a href="javascript:void(0)" class="quiz-brand-badge" onclick="confirmExit()">
            <span class="quiz-brand-dot"></span>
            <span>{{ $course->title }}</span>
        </a>

        <!-- Close Button (X in top right) -->
        <button type="button" class="quiz-close-btn" id="btn-close-tab" onclick="closeQuizTab()"
            title="Tutup Tab (Esc)" aria-label="Tutup Halaman Quiz">
            <i class="fas fa-times"></i>
        </button>
    </header>

    <!-- Main Container -->
    <main class="quiz-main-wrapper">
        <div id="quiz-exercise-container" style="width: 100%;">
            <div class="quiz-header">
                <h1 class="quiz-title">Quiz: {{ $lesson->title }}</h1>

                <div class="quiz-progress-segments" id="progress-segments">
                    @foreach ($quizzes as $index => $item)
                        <div class="quiz-progress-pill {{ $index === 0 ? 'active' : '' }}"
                            id="segment-{{ $index }}"></div>
                    @endforeach
                </div>

                <div class="quiz-progress-text" id="progress-text">
                    Pertanyaan <span id="current-question-num">1</span> dari {{ $quizzes->count() }}
                </div>
            </div>

            <!-- Question -->
            <div class="quiz-question-box">
                <p class="quiz-question-text" id="question-text"></p>
            </div>

            <!-- Options List -->
            <div class="quiz-options-list" id="options-list">
                <!-- Dynamically populated via JS -->
            </div>

            <!-- Action Area -->
            <div class="quiz-action-area">
                <!-- Feedback Toast -->
                <div class="quiz-feedback-box" id="feedback-box"></div>

                <!-- Submit / Next Button -->
                <button type="button" class="quiz-submit-btn" id="btn-submit-answer" disabled>
                    <span id="submit-btn-text">Submit Answer</span>
                    <i class="fas fa-chevron-right ml-1" id="submit-btn-icon"></i>
                </button>

                <!-- Secondary Actions (e.g. Try Again) -->
                <div class="quiz-secondary-btns" id="secondary-btns" style="display: none;">
                    <button type="button" class="quiz-retry-btn" id="btn-retry">
                        <i class="fas fa-redo-alt mr-1"></i> Coba Lagi
                    </button>
                    <button type="button" class="quiz-retry-btn" id="btn-skip">
                        Lewati Soal <i class="fas fa-forward ml-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Completion / Results Screen -->
        <div class="quiz-results-card" id="quiz-results-container">
            <div class="quiz-score-badge">
                <i class="fas fa-trophy"></i>
            </div>
            <h2 class="quiz-results-title">Latihan Selesai!</h2>
            <div class="quiz-results-score" id="final-score-display">0 / 0</div>
            <p class="quiz-results-desc" id="final-feedback-text">
                KAnda telah menyelesaikan seluruh quiz untuk materi ini.
            </p>

            <div class="quiz-summary-list" id="quiz-summary-list">
                <!-- Populated via JS -->
            </div>

            <div class="d-flex align-items-center justify-content-center gap-3">
                <button type="button" class="quiz-retry-btn px-4 py-2" id="btn-restart-quiz">
                    <i class="fas fa-redo-alt mr-2"></i> Ulangi Quiz
                </button>
                <button type="button" class="quiz-submit-btn ml-2" onclick="closeQuizTab()">
                    <i class="fas fa-check-circle mr-2"></i> Tutup Quiz
                </button>
            </div>
        </div>
    </main>

    <!-- General JS Scripts -->
    <script src="{{ asset('library/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('library/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>

    <script>
        // Quiz Data passed safely from Blade
        const quizData = @json($quizzes);
        const submitRoute = "{{ route('quiz.submit', [$course->slug, $lesson->id]) }}";
        const totalQuestions = quizData.length;

        let currentIndex = 0;
        let selectedOption = null;
        let isSubmitted = false;
        let userAnswers = {}; // { questionId: selectedChoice }
        let questionResults = {}; // { questionId: { correct: bool, selected: 'a', correctAnswer: 'a' } }

        // Function to close tab cleanly
        function closeQuizTab() {
            // Attempt window.close()
            window.close();

            // Fallback if browser blocks window.close() (e.g. tab wasn't opened via script or direct URL)
            setTimeout(function() {
                if (!window.closed) {
                    const confirmLeave = confirm(
                        "Tab ini tidak dapat ditutup otomatis oleh browser karena kebijakan privasi. Apakah Anda ingin kembali ke halaman materi?"
                    );
                    if (confirmLeave) {
                        window.location.href =
                            "{{ route('learn', ['course' => $course->slug, 'lesson' => $lesson->id]) }}";
                    }
                }
            }, 300);
        }

        function confirmExit() {
            if (confirm("Tutup quiz dan kembali ke materi?")) {
                closeQuizTab();
            }
        }

        // Check if text has Arabic characters
        function hasArabic(text) {
            const arabicPattern = /[\u0600-\u06FF\u0750-\u077F\u08A0-\u08FF]/;
            return arabicPattern.test(text);
        }

        // Render current question
        function renderCurrentQuestion() {
            if (currentIndex >= totalQuestions) {
                showResults();
                return;
            }

            const currentQuiz = quizData[currentIndex];
            selectedOption = null;
            isSubmitted = false;

            // Update Progress Header
            $("#current-question-num").text(currentIndex + 1);
            $(".quiz-progress-pill").removeClass("active");
            $(`#segment-${currentIndex}`).addClass("active");

            // Update Question Text
            const $qText = $("#question-text");
            $qText.text(currentQuiz.question);
            if (hasArabic(currentQuiz.question)) {
                $qText.addClass("is-arabic");
            } else {
                $qText.removeClass("is-arabic");
            }

            // Options mapping
            const options = [{
                    key: 'a',
                    label: 'A',
                    text: currentQuiz.option_a
                },
                {
                    key: 'b',
                    label: 'B',
                    text: currentQuiz.option_b
                },
                {
                    key: 'c',
                    label: 'C',
                    text: currentQuiz.option_c
                },
                {
                    key: 'd',
                    label: 'D',
                    text: currentQuiz.option_d
                }
            ];

            // Render Options Cards
            const $optionsList = $("#options-list");
            $optionsList.empty();

            options.forEach(opt => {
                const isOptArabic = hasArabic(opt.text);
                const arabicClass = isOptArabic ? 'is-arabic' : '';

                const optHtml = `
                    <div class="quiz-option-card" data-key="${opt.key}" tabindex="0" role="button" aria-pressed="false">
                        <span class="quiz-option-dot"></span>
                        <span class="quiz-option-text ${arabicClass}">
                            <span class="font-weight-bold mr-1">${opt.label}.</span> ${escapeHtml(opt.text)}
                        </span>
                        <span class="quiz-option-key ml-auto">${opt.label}</span>
                    </div>
                `;
                $optionsList.append(optHtml);
            });

            // Reset UI States
            $("#feedback-box").removeClass("correct wrong").hide().text("");
            $("#secondary-btns").hide();
            $("#btn-submit-answer").prop("disabled", true);
            $("#submit-btn-text").text("Submit Answer");
            $("#submit-btn-icon").attr("class", "fas fa-chevron-right ml-1");
        }

        // Select an Option
        $(document).on("click", ".quiz-option-card:not(.locked)", function() {
            if (isSubmitted) return;

            $(".quiz-option-card").removeClass("selected").attr("aria-pressed", "false");
            $(this).addClass("selected").attr("aria-pressed", "true");

            selectedOption = $(this).data("key");
            $("#btn-submit-answer").prop("disabled", false);
        });

        // Submit or Next Handler
        $("#btn-submit-answer").on("click", function() {
            if (!isSubmitted) {
                handleSubmitAnswer();
            } else {
                // Next Exercise
                currentIndex++;
                renderCurrentQuestion();
            }
        });

        function handleSubmitAnswer() {
            if (!selectedOption) return;

            const currentQuiz = quizData[currentIndex];
            const isCorrect = (selectedOption.toLowerCase() === currentQuiz.correct_answer.toLowerCase());

            isSubmitted = true;
            userAnswers[currentQuiz.id] = selectedOption;
            questionResults[currentQuiz.id] = {
                correct: isCorrect,
                userAnswer: selectedOption,
                correctAnswer: currentQuiz.correct_answer,
                question: currentQuiz.question
            };

            // Lock options & mark visual status
            $(".quiz-option-card").addClass("locked");

            const $selectedCard = $(`.quiz-option-card[data-key="${selectedOption}"]`);
            const $correctCard = $(`.quiz-option-card[data-key="${currentQuiz.correct_answer}"]`);

            const $segment = $(`#segment-${currentIndex}`);

            if (isCorrect) {
                // Correct styling
                $selectedCard.addClass("correct");
                $segment.removeClass("active").addClass("completed");

                $("#feedback-box")
                    .addClass("correct")
                    .html('<i class="fas fa-check-circle mr-2"></i> Jawaban Benar!')
                    .fadeIn();

                // Button becomes Next Exercise
                $("#btn-submit-answer").prop("disabled", false);
                if (currentIndex < totalQuestions - 1) {
                    $("#submit-btn-text").text("Selanjutnya");
                    $("#submit-btn-icon").attr("class", "fas fa-arrow-right ml-1");
                } else {
                    $("#submit-btn-text").text("Lihat Hasil");
                    $("#submit-btn-icon").attr("class", "fas fa-trophy ml-1");
                }
            } else {
                // Incorrect styling
                $selectedCard.addClass("wrong");
                $correctCard.addClass("correct");
                $segment.removeClass("active").addClass("incorrect");

                $("#feedback-box")
                    .addClass("wrong")
                    .html(
                        `<i class="fas fa-times-circle mr-2"></i> Jawaban kurang tepat. Kunci jawaban: <strong>${currentQuiz.correct_answer.toUpperCase()}</strong>`
                    )
                    .fadeIn();

                // Show retry or skip
                $("#btn-submit-answer").hide();
                $("#secondary-btns").fadeIn();
            }
        }

        // Retry current question
        $("#btn-retry").on("click", function() {
            isSubmitted = false;
            $(".quiz-option-card").removeClass("locked correct wrong selected");
            $(`#segment-${currentIndex}`).removeClass("incorrect").addClass("active");
            $("#feedback-box").removeClass("wrong").hide();
            $("#secondary-btns").hide();
            $("#btn-submit-answer").show().prop("disabled", true);
            $("#submit-btn-text").text("Submit Answer");
            selectedOption = null;
        });

        // Skip to next question
        $("#btn-skip").on("click", function() {
            $("#secondary-btns").hide();
            $("#btn-submit-answer").show();
            currentIndex++;
            renderCurrentQuestion();
        });

        // Show Final Results
        function showResults() {
            $("#quiz-exercise-container").hide();
            $("#quiz-results-container").fadeIn();

            let score = 0;
            const $summaryList = $("#quiz-summary-list");
            $summaryList.empty();

            quizData.forEach((q, idx) => {
                const res = questionResults[q.id];
                const isCorrect = res && res.correct;
                if (isCorrect) score++;

                const statusClass = isCorrect ? 'is-correct' : 'is-wrong';
                const statusBadge = isCorrect ?
                    '<span class="badge badge-success"><i class="fas fa-check mr-1"></i> Benar</span>' :
                    '<span class="badge badge-danger"><i class="fas fa-times mr-1"></i> Salah</span>';

                const itemHtml = `
                    <div class="quiz-summary-item ${statusClass}">
                        <div class="text-left">
                            <span class="text-muted mr-2 font-weight-bold">#${idx + 1}</span>
                            <span>${escapeHtml(q.question.substring(0, 70))}${q.question.length > 70 ? '...' : ''}</span>
                        </div>
                        <div>${statusBadge}</div>
                    </div>
                `;
                $summaryList.append(itemHtml);
            });

            $("#final-score-display").text(`${score} / ${totalQuestions}`);

            const percentage = Math.round((score / totalQuestions) * 100);
            let feedback = "Bagus sekali!";
            if (percentage === 100) {
                feedback = "Sempurna! Anda memahami materi ini dengan sangat baik.";
            } else if (percentage >= 70) {
                feedback = "Hebat! Pemahaman Anda terhadap materi ini sudah baik.";
            } else {
                feedback = "Tetap semangat! Anda dapat mempelajari materi kembali dan mencoba lagi.";
            }
            $("#final-feedback-text").text(feedback);

            // Send results to backend via AJAX (non-blocking)
            saveResultsToBackend();
        }

        // Save Results to Backend via AJAX
        function saveResultsToBackend() {
            $.ajax({
                url: submitRoute,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: {
                    answers: userAnswers
                },
                success: function(response) {
                    console.log("Quiz progress saved successfully.", response);
                },
                error: function(err) {
                    console.warn("Could not sync quiz progress.", err);
                }
            });
        }

        // Restart Quiz
        $("#btn-restart-quiz").on("click", function() {
            currentIndex = 0;
            selectedOption = null;
            isSubmitted = false;
            userAnswers = {};
            questionResults = {};
            $(".quiz-progress-pill").removeClass("completed incorrect active");
            $("#quiz-results-container").hide();
            $("#quiz-exercise-container").fadeIn();
            renderCurrentQuestion();
        });

        // Helper to escape HTML characters
        function escapeHtml(text) {
            if (!text) return '';
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // Keyboard Shortcuts
        $(document).on("keydown", function(e) {
            // Esc closes tab
            if (e.key === "Escape") {
                closeQuizTab();
                return;
            }

            // If on results screen, Enter triggers close
            if ($("#quiz-results-container").is(":visible")) {
                if (e.key === "Enter") {
                    closeQuizTab();
                }
                return;
            }

            // 1, 2, 3, 4 or A, B, C, D to pick option
            const keyMap = {
                '1': 'a',
                'a': 'a',
                'A': 'a',
                '2': 'b',
                'b': 'b',
                'B': 'b',
                '3': 'c',
                'c': 'c',
                'C': 'c',
                '4': 'd',
                'd': 'd',
                'D': 'd'
            };

            if (!isSubmitted && keyMap[e.key]) {
                const choice = keyMap[e.key];
                $(`.quiz-option-card[data-key="${choice}"]`).trigger("click");
            }

            // Enter key triggers Submit or Next
            if (e.key === "Enter") {
                if ($("#btn-submit-answer").is(":visible") && !$("#btn-submit-answer").prop("disabled")) {
                    $("#btn-submit-answer").trigger("click");
                } else if ($("#btn-retry").is(":visible")) {
                    $("#btn-retry").trigger("click");
                }
            }
        });

        // Initialize First Question on Load
        $(document).ready(function() {
            renderCurrentQuestion();
        });
    </script>
</body>

</html>
