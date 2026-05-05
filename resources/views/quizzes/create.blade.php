<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Quiz Creator</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #f5f4f0;
            --surface: #ffffff;
            --surface2: #f0efe9;
            --border: #e2e0d8;
            --border-strong: #c8c6bc;
            --text: #1a1916;
            --text-muted: #6b6960;
            --text-light: #9b9990;
            --accent: #2d5a27;
            --accent-light: #e8f0e6;
            --accent-border: #b8d4b3;
            --danger: #c0392b;
            --danger-light: #fdf0ef;
            --info: #1a4a7a;
            --info-light: #e8f0f8;
            --info-border: #b3cce0;
            --radius: 10px;
            --radius-sm: 6px;
            --shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.08);
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 0;
        }

       
        .header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 60px;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .header-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 15px;
            font-weight: 600;
            color: var(--text);
            text-decoration: none;
        }
        .header-brand .dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--accent);
        }
        .header-actions { display: flex; gap: 8px; }

        
        .container {
            max-width: 760px;
            margin: 0 auto;
            padding: 2.5rem 1.5rem 4rem;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
            color: var(--text);
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }
        .page-subtitle {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 2rem;
        }

        
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: var(--shadow);
        }
        .card-dashed {
            border-style: dashed;
            border-color: var(--border-strong);
            background: var(--surface2);
            box-shadow: none;
        }

        
        .field { margin-bottom: 1.1rem; }
        .field:last-child { margin-bottom: 0; }
        .field label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }
        input[type=text], input[type=number], input[type=url], textarea, select {
            width: 100%;
            padding: 9px 12px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            color: var(--text);
            transition: border-color 0.15s, box-shadow 0.15s;
            outline: none;
        }
        input:focus, textarea:focus, select:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px rgba(45,90,39,0.1);
        }
        textarea { resize: vertical; min-height: 72px; line-height: 1.6; }

        .row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }

        
        .type-grid {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 1rem;
        }
        .type-pill {
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 13px;
            font-weight: 500;
            border: 1px solid var(--border-strong);
            background: var(--surface);
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s;
        }
        .type-pill:hover { border-color: var(--accent); color: var(--accent); }
        .type-pill.active {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }

        
        .question-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 1.25rem 1.5rem;
            margin-bottom: 10px;
            box-shadow: var(--shadow);
            animation: slideIn 0.2s ease;
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .q-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 1rem;
        }
        .q-num {
            width: 28px; height: 28px;
            border-radius: 50%;
            background: var(--surface2);
            border: 1px solid var(--border);
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 600;
            color: var(--text-muted);
            flex-shrink: 0;
        }
        .q-type-badge {
            font-size: 11px;
            font-weight: 500;
            padding: 3px 10px;
            border-radius: 99px;
            background: var(--info-light);
            color: var(--info);
            border: 1px solid var(--info-border);
        }
        .q-remove {
            margin-left: auto;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--text-light);
            font-size: 18px;
            line-height: 1;
            padding: 2px 6px;
            border-radius: var(--radius-sm);
            transition: all 0.15s;
        }
        .q-remove:hover { background: var(--danger-light); color: var(--danger); }

        
        .options-list { display: flex; flex-direction: column; gap: 8px; margin-top: 8px; }
        .option-row { display: flex; align-items: center; gap: 8px; }
        .option-row input[type=text] { flex: 1; }
        .correct-label {
            display: flex; align-items: center; gap: 5px;
            font-size: 12px; color: var(--text-muted);
            cursor: pointer; white-space: nowrap; flex-shrink: 0;
        }
        .correct-label input { width: auto; cursor: pointer; accent-color: var(--accent); }
        .opt-remove {
            background: none; border: none; cursor: pointer;
            color: var(--text-light); font-size: 15px; padding: 2px 5px;
            border-radius: var(--radius-sm); flex-shrink: 0;
            transition: all 0.15s;
        }
        .opt-remove:hover { color: var(--danger); background: var(--danger-light); }
        .add-option-btn {
            margin-top: 8px;
            width: 100%;
            padding: 7px;
            font-size: 13px;
            font-family: 'DM Sans', sans-serif;
            border: 1px dashed var(--border-strong);
            border-radius: var(--radius-sm);
            background: transparent;
            color: var(--text-muted);
            cursor: pointer;
            transition: all 0.15s;
        }
        .add-option-btn:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-light); }

        
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 9px 18px;
            font-size: 14px; font-weight: 500;
            font-family: 'DM Sans', sans-serif;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-strong);
            background: var(--surface);
            color: var(--text);
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }
        .btn:hover { background: var(--surface2); border-color: var(--border-strong); }
        .btn:active { transform: scale(0.98); }
        .btn-primary {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }
        .btn-primary:hover { background: #234d1e; border-color: #234d1e; }
        .btn-add-question {
            width: 100%;
            justify-content: center;
            border-style: dashed;
            color: var(--text-muted);
            font-size: 14px;
        }
        .btn-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 1.5rem; }

        
        .section-label {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.07em;
            color: var(--text-light);
            margin-bottom: 10px;
        }
        .divider { border: none; border-top: 1px solid var(--border); margin: 1.5rem 0; }

        
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            margin-top: 1rem;
            display: none;
        }
        .alert-success {
            background: var(--accent-light);
            border: 1px solid var(--accent-border);
            color: var(--accent);
        }
        .alert-error {
            background: var(--danger-light);
            border: 1px solid #f5c6c2;
            color: var(--danger);
        }
        .alert-title { font-weight: 600; margin-bottom: 4px; }
        .alert pre {
            font-family: 'DM Mono', monospace;
            font-size: 12px;
            margin-top: 8px;
            background: rgba(0,0,0,0.04);
            padding: 10px;
            border-radius: var(--radius-sm);
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-all;
            max-height: 200px;
            overflow-y: auto;
            color: var(--text);
        }

        
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--text-light);
            font-size: 14px;
        }

        
        .spinner {
            width: 14px; height: 14px;
            border: 2px solid rgba(255,255,255,0.4);
            border-top-color: #fff;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
            display: none;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        
        .hint { font-size: 11px; color: var(--text-light); margin-top: 4px; }
    </style>
</head>
<body>

<header class="header">
    <a href="{{ url('/') }}" class="header-brand">
        <span class="dot"></span>
        Quiz System
    </a>
    <div class="header-actions">
        <a href="{{ url('/') }}" class="btn">← All quizzes</a>
    </div>
</header>

<div class="container">
    <h1 class="page-title">Create a quiz</h1>
    <p class="page-subtitle">Fill in the details below, add your questions, then submit.</p>

    {{-- Quiz meta --}}
    <div class="card">
        <div class="field">
            <label>Quiz title *</label>
            <input type="text" id="quizTitle" placeholder="e.g. General Knowledge Round 1">
        </div>
        <div class="field">
            <label>Description (optional)</label>
            <textarea id="quizDesc" placeholder="Brief description of this quiz…"></textarea>
        </div>
    </div>

    {{-- Questions list --}}
    <div class="section-label" style="margin-top:1.5rem">Questions</div>
    <div id="questionsList">
        <div class="empty-state">No questions yet — add one below.</div>
    </div>

    {{-- Add question --}}
    <div class="card card-dashed" style="margin-top:10px">
        <div class="section-label">Choose type then add</div>
        <div class="type-grid" id="typeGrid">
            <button class="type-pill active" data-type="single_choice">Single choice</button>
            <button class="type-pill" data-type="multiple_choice">Multiple choice</button>
            <button class="type-pill" data-type="binary">Yes / No</button>
            <button class="type-pill" data-type="number">Number</button>
            <button class="type-pill" data-type="text">Text</button>
        </div>
        <button class="btn btn-add-question" id="addQBtn">+ Add question</button>
    </div>

    <hr class="divider">

    {{-- Submit --}}
    <div class="btn-row">
        <button class="btn btn-primary" id="submitBtn">
            <span class="spinner" id="spinner"></span>
            <span id="submitLabel">Submit quiz</span>
        </button>
        <button class="btn" id="previewBtn">Preview JSON</button>
        <button class="btn" id="clearBtn">Clear all</button>
    </div>

    <div class="alert alert-success" id="successBox">
        <div class="alert-title" id="successTitle">Quiz created!</div>
        <pre id="successPre"></pre>
        <div style="margin-top:10px">
            <a id="viewQuizLink" href="#" class="btn btn-primary" style="font-size:13px">Start this quiz →</a>
        </div>
    </div>
    <div class="alert alert-error" id="errorBox">
        <div class="alert-title">Something went wrong</div>
        <div id="errorMsg"></div>
    </div>
</div>

<script>
let questions = [];
let selectedType = 'single_choice';
let qCounter = 0;

const typeLabels = {
    single_choice:   'Single choice',
    multiple_choice: 'Multiple choice',
    binary:          'Yes / No',
    number:          'Number',
    text:            'Text',
};

// ── Type pill toggle ──────────────────────────────────────────────────────────
document.getElementById('typeGrid').addEventListener('click', e => {
    const btn = e.target.closest('.type-pill');
    if (!btn) return;
    document.querySelectorAll('.type-pill').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    selectedType = btn.dataset.type;
});

// ── Add question ──────────────────────────────────────────────────────────────
document.getElementById('addQBtn').addEventListener('click', () => {
    qCounter++;
    const q = {
        id: qCounter,
        type: selectedType,
        question_text: '',
        marks: 1,
        video_url: '',
        options: [],
        correct_answer: '',
    };

    if (q.type === 'binary') {
        q.options = [{ text: 'Yes', is_correct: true }, { text: 'No', is_correct: false }];
    } else if (q.type === 'single_choice' || q.type === 'multiple_choice') {
        q.options = [
            { text: '', is_correct: false },
            { text: '', is_correct: false },
        ];
    }

    questions.push(q);
    renderAll();
    setTimeout(() => {
        const cards = document.querySelectorAll('.question-card');
        if (cards.length) cards[cards.length - 1].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 60);
});

// ── Render ────────────────────────────────────────────────────────────────────
function renderAll() {
    const list = document.getElementById('questionsList');
    if (!questions.length) {
        list.innerHTML = '<div class="empty-state">No questions yet — add one below.</div>';
        return;
    }

    list.innerHTML = questions.map((q, idx) => `
        <div class="question-card" data-idx="${idx}">
            <div class="q-header">
                <div class="q-num">${idx + 1}</div>
                <span class="q-type-badge">${typeLabels[q.type]}</span>
                <button class="q-remove" data-action="remove-q" data-idx="${idx}" title="Remove question">×</button>
            </div>

            <div class="field">
                <label>Question text *</label>
                <textarea data-action="q-text" data-idx="${idx}" placeholder="Type your question here…" rows="2">${escHtml(q.question_text)}</textarea>
            </div>

            <div class="row-2 field">
                <div>
                    <label>Marks</label>
                    <input type="number" data-action="q-marks" data-idx="${idx}" value="${q.marks}" min="1">
                </div>
                <div>
                    <label>YouTube URL (optional)</label>
                    <input type="url" data-action="q-video" data-idx="${idx}" value="${escHtml(q.video_url || '')}" placeholder="https://youtube.com/watch?v=…">
                </div>
            </div>

            ${renderAnswers(q, idx)}
        </div>
    `).join('');

    // bind events
    list.querySelectorAll('[data-action="remove-q"]').forEach(b =>
        b.addEventListener('click', e => {
            questions.splice(+e.currentTarget.dataset.idx, 1);
            renderAll();
        })
    );
    list.querySelectorAll('[data-action="q-text"]').forEach(el =>
        el.addEventListener('input', e => { questions[+e.target.dataset.idx].question_text = e.target.value; })
    );
    list.querySelectorAll('[data-action="q-marks"]').forEach(el =>
        el.addEventListener('input', e => { questions[+e.target.dataset.idx].marks = +e.target.value || 1; })
    );
    list.querySelectorAll('[data-action="q-video"]').forEach(el =>
        el.addEventListener('input', e => { questions[+e.target.dataset.idx].video_url = e.target.value; })
    );
    list.querySelectorAll('[data-action="opt-text"]').forEach(el =>
        el.addEventListener('input', e => {
            questions[+e.target.dataset.qi].options[+e.target.dataset.oi].text = e.target.value;
        })
    );
    list.querySelectorAll('[data-action="opt-correct"]').forEach(el =>
        el.addEventListener('change', e => {
            const qi = +e.target.dataset.qi, oi = +e.target.dataset.oi;
            if (questions[qi].type === 'multiple_choice') {
                questions[qi].options[oi].is_correct = e.target.checked;
            } else {
                questions[qi].options.forEach((o, i) => o.is_correct = (i === oi));
            }
        })
    );
    list.querySelectorAll('[data-action="remove-opt"]').forEach(b =>
        b.addEventListener('click', e => {
            const qi = +e.currentTarget.dataset.qi, oi = +e.currentTarget.dataset.oi;
            if (questions[qi].options.length > 2) { questions[qi].options.splice(oi, 1); renderAll(); }
        })
    );
    list.querySelectorAll('[data-action="add-opt"]').forEach(b =>
        b.addEventListener('click', e => {
            questions[+e.currentTarget.dataset.qi].options.push({ text: '', is_correct: false });
            renderAll();
        })
    );
    list.querySelectorAll('[data-action="correct-answer"]').forEach(el =>
        el.addEventListener('input', e => { questions[+e.target.dataset.qi].correct_answer = e.target.value; })
    );
}

function renderAnswers(q, idx) {
    if (q.type === 'binary' || q.type === 'single_choice' || q.type === 'multiple_choice') {
        const inputType = q.type === 'multiple_choice' ? 'checkbox' : 'radio';
        const correctLabel = q.type === 'multiple_choice' ? 'Correct answers (tick all that apply)' : 'Options — tick the correct one';
        return `
            <div class="field">
                <label>${correctLabel}</label>
                <div class="options-list">
                    ${q.options.map((opt, oi) => `
                        <div class="option-row">
                            <input type="text" data-action="opt-text" data-qi="${idx}" data-oi="${oi}"
                                value="${escHtml(opt.text)}" placeholder="Option ${oi + 1}">
                            <label class="correct-label">
                                <input type="${inputType}" data-action="opt-correct"
                                    name="correct_${idx}" data-qi="${idx}" data-oi="${oi}"
                                    ${opt.is_correct ? 'checked' : ''}>
                                Correct
                            </label>
                            ${q.options.length > 2
                                ? `<button class="opt-remove" data-action="remove-opt" data-qi="${idx}" data-oi="${oi}" title="Remove">×</button>`
                                : ''}
                        </div>
                    `).join('')}
                </div>
                ${q.type !== 'binary' ? `<button class="add-option-btn" data-action="add-opt" data-qi="${idx}">+ Add option</button>` : ''}
            </div>`;
    }

    const placeholder = q.type === 'number' ? 'e.g. 42' : 'e.g. Paris';
    const inputType   = q.type === 'number' ? 'number' : 'text';
    return `
        <div class="field">
            <label>Correct answer</label>
            <input type="${inputType}" data-action="correct-answer" data-qi="${idx}"
                value="${escHtml(q.correct_answer)}" placeholder="${placeholder}" style="max-width:300px">
            <p class="hint">The user's answer will be compared to this (case-insensitive)</p>
        </div>`;
}

function escHtml(str) {
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Build payload ─────────────────────────────────────────────────────────────
function buildPayload() {
    return {
        title: document.getElementById('quizTitle').value.trim(),
        description: document.getElementById('quizDesc').value.trim(),
        questions: questions.map(q => {
            const out = { type: q.type, question_text: q.question_text, marks: q.marks };
            if (q.video_url) out.video_url = q.video_url;
            if (['single_choice', 'multiple_choice', 'binary'].includes(q.type)) {
                out.options = q.options.map(o => ({ text: o.text, is_correct: o.is_correct }));
            } else {
                out.correct_answer = q.correct_answer;
            }
            return out;
        })
    };
}

// ── Preview JSON ──────────────────────────────────────────────────────────────
document.getElementById('previewBtn').addEventListener('click', () => {
    const box = document.getElementById('successBox');
    document.getElementById('successTitle').textContent = 'JSON Preview (not submitted)';
    document.getElementById('successPre').textContent = JSON.stringify(buildPayload(), null, 2);
    document.getElementById('viewQuizLink').style.display = 'none';
    box.style.display = 'block';
    document.getElementById('errorBox').style.display = 'none';
    box.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
});

// ── Submit ────────────────────────────────────────────────────────────────────
document.getElementById('submitBtn').addEventListener('click', async () => {
    const payload = buildPayload();
    const errBox  = document.getElementById('errorBox');
    const okBox   = document.getElementById('successBox');
    errBox.style.display = 'none';
    okBox.style.display  = 'none';

    if (!payload.title) {
        showError('Please enter a quiz title.');
        return;
    }
    if (!payload.questions.length) {
        showError('Please add at least one question.');
        return;
    }
    for (let i = 0; i < payload.questions.length; i++) {
        if (!payload.questions[i].question_text.trim()) {
            showError(`Question ${i + 1} has no text.`);
            return;
        }
    }

    setLoading(true);

    try {
        const res = await fetch('/api/quiz', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify(payload),
        });

        const data = await res.json();
        if (!res.ok) throw new Error(data.message || JSON.stringify(data.errors || data));

        document.getElementById('successTitle').textContent = `Quiz created! ID: ${data.id}`;
        document.getElementById('successPre').textContent = JSON.stringify(data, null, 2);
        const link = document.getElementById('viewQuizLink');
        link.href = `/quiz/${data.id}`;
        link.style.display = 'inline-flex';
        okBox.style.display = 'block';
        okBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });

        // reset
        questions = [];
        qCounter  = 0;
        renderAll();
        document.getElementById('quizTitle').value = '';
        document.getElementById('quizDesc').value  = '';

    } catch (err) {
        showError(err.message);
    } finally {
        setLoading(false);
    }
});

// ── Clear ─────────────────────────────────────────────────────────────────────
document.getElementById('clearBtn').addEventListener('click', () => {
    if (!confirm('Clear everything?')) return;
    questions = []; qCounter = 0; renderAll();
    document.getElementById('quizTitle').value = '';
    document.getElementById('quizDesc').value  = '';
    document.getElementById('successBox').style.display = 'none';
    document.getElementById('errorBox').style.display   = 'none';
});

// ── Helpers ───────────────────────────────────────────────────────────────────
function showError(msg) {
    document.getElementById('errorMsg').textContent = msg;
    document.getElementById('errorBox').style.display = 'block';
    document.getElementById('errorBox').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}
function setLoading(on) {
    document.getElementById('spinner').style.display  = on ? 'block' : 'none';
    document.getElementById('submitLabel').textContent = on ? 'Submitting…' : 'Submit quiz';
    document.getElementById('submitBtn').disabled = on;
}

renderAll();
</script>
</body>
</html>