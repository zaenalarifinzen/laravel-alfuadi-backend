export function initQuestionList({ config, onSelectQuestion }) {
    const btnToggle = document.getElementById("btn-toggle-sidebar");
    const toggleText = document.getElementById("toggle-sidebar-text");
    const sidebar = document.getElementById("exercise-sidebar");
    const mainContent = document.getElementById("exercise-main-content");
    const questionListHeaderEl = document.getElementById("question-list-header");
    const questionListEl = document.getElementById("question-list");
    const countBadgeEl = document.getElementById("question-count-badge");

    let isSidebarVisible = localStorage.getItem("exercise_sidebar_visible") !== "false";
    let currentQuestions = [];
    let activeExerciseId = null;
    let lastLoadedKey = null;
    let currentLevelName = null;

    function updateQuestionListHeader(level) {
        currentLevelName = level?.name || level?.slug || null;

        if (questionListHeaderEl && currentLevelName) {
            questionListHeaderEl.textContent = `Daftar Soal ${currentLevelName}`;
        }
    }

    function applySidebarState() {
        if (!sidebar || !mainContent || !btnToggle) return;

        if (isSidebarVisible) {
            sidebar.classList.remove("d-none");
            mainContent.classList.remove("col-12");
            mainContent.classList.add("col-lg-9", "col-md-12");
            btnToggle.classList.add("active");
            if (toggleText) toggleText.textContent = "Sembunyikan";
        } else {
            sidebar.classList.add("d-none");
            mainContent.classList.remove("col-lg-9", "col-md-12");
            mainContent.classList.add("col-12");
            btnToggle.classList.remove("active");
            if (toggleText) toggleText.textContent = "Tampilkan";
        }
    }

    if (btnToggle) {
        btnToggle.addEventListener("click", () => {
            isSidebarVisible = !isSidebarVisible;
            localStorage.setItem("exercise_sidebar_visible", isSidebarVisible);
            applySidebarState();
        });
    }

    applySidebarState();

    function fetchQuestions(levelSlug, surahId = null, force = false, onLoaded = null) {
        if (!questionListEl || !config.exerciseListUrl) return;

        const currentKey = `${levelSlug}_${surahId || ""}`;
        if (!force && lastLoadedKey === currentKey && currentQuestions.length > 0) {
            if (currentLevelName && questionListHeaderEl) {
                questionListHeaderEl.textContent = `Daftar Soal ${currentLevelName}`;
            }
            if (activeExerciseId !== null) {
                setActiveQuestion(activeExerciseId);
            }
            if (onLoaded) onLoaded(currentQuestions);
            return;
        }

        lastLoadedKey = currentKey;

        let url = config.exerciseListUrl.replace(":level", levelSlug);
        if (levelSlug === "alquran" && surahId) {
            url += `?surah_id=${surahId}`;
        }

        questionListEl.innerHTML = `
            <div class="p-4 text-center text-muted spinner-container">
                <div class="spinner-border spinner-border-sm text-primary mr-2" role="status"></div>
                <span>Memuat daftar soal...</span>
            </div>
        `;

        $.ajax({
            url: url,
            type: "GET",
            success: (response) => {
                const exercises = response?.data?.exercises;

                if (response.success && Array.isArray(exercises)) {
                    updateQuestionListHeader(response.data.level);
                    currentQuestions = exercises;
                    renderQuestions(currentQuestions);
                    if (activeExerciseId !== null) {
                        setActiveQuestion(activeExerciseId);
                    }
                    if (onLoaded) onLoaded(currentQuestions);
                } else {
                    renderEmpty();
                }
            },
            error: () => {
                renderEmpty();
            },
        });
    }

    function renderEmpty() {
        if (!questionListEl) return;
        questionListEl.innerHTML = `
            <div class="p-4 text-center text-muted">
                <i class="fas fa-exclamation-circle mb-2 d-block" style="font-size: 1.5rem;"></i>
                Tidak ada daftar soal
            </div>
        `;
        if (countBadgeEl) countBadgeEl.textContent = "0 Soal";
    }

    function renderQuestions(questions) {
        if (!questionListEl) return;
        if (countBadgeEl) countBadgeEl.textContent = `${questions.length} Soal`;

        if (questions.length === 0) {
            renderEmpty();
            return;
        }

        const html = questions
            .map((q, idx) => {
                const isActive = q.id == activeExerciseId;
                const isPassed = q.passed;
                const activeClass = isActive ? "active" : "";
                const passedClass = isPassed ? "passed" : "";

                const icon = isPassed
                    ? `<i class="fas fa-check"></i>`
                    : `${idx + 1}`;

                return `
                    <div class="question-item ${activeClass} ${passedClass}" data-exercise-id="${q.id}">
                        <div class="question-number">${icon}</div>
                        <div class="question-info">
                            <div class="question-title">${q.title}</div>
                        </div>
                    </div>
                `;
            })
            .join("");

        questionListEl.innerHTML = html;

        questionListEl.querySelectorAll(".question-item").forEach((item) => {
            item.addEventListener("click", () => {
                const exerciseId = item.getAttribute("data-exercise-id");
                if (onSelectQuestion) {
                    onSelectQuestion(exerciseId);
                }
            });
        });
    }

    function setActiveQuestion(exerciseId) {
        activeExerciseId = exerciseId;
        if (questionListEl) {
            questionListEl.querySelectorAll(".question-item").forEach((item) => {
                const itemExerciseId = item.getAttribute("data-exercise-id");
                if (itemExerciseId == exerciseId) {
                    item.classList.add("active");
                    item.scrollIntoView({ behavior: "smooth", block: "nearest" });
                } else {
                    item.classList.remove("active");
                }
            });
        }
    }

    function markQuestionPassed(exerciseId) {
        const q = currentQuestions.find((item) => item.id == exerciseId);
        if (q) {
            q.passed = true;
            renderQuestions(currentQuestions);
        }
    }

    return {
        fetchQuestions,
        setActiveQuestion,
        markQuestionPassed,
    };
}
