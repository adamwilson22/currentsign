<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Sign document — CurrentSign</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
  @php
    $rawPath = $signature->pdf_path ?? '';
    $rawPath = ltrim((string) $rawPath, '/');
    if ($rawPath !== '' && str_starts_with($rawPath, 'public/')) {
        $rawPath = substr($rawPath, strlen('public/'));
    }
    $pdfUrl = $rawPath !== '' ? asset($rawPath) : '';
  @endphp
  <style>
    :root {
      --bg: #eef2f7;
      --surface: #ffffff;
      --ink: #0f172a;
      --muted: #64748b;
      --border: #e2e8f0;
      --accent: #2563eb;
      --accent-dim: #dbeafe;
      --danger: #dc2626;
      --radius: 14px;
      --shadow: 0 12px 40px rgba(15, 23, 42, 0.08);
      --shadow-sm: 0 1px 3px rgba(15, 23, 42, 0.06);
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: 'DM Sans', system-ui, sans-serif;
      background: var(--bg);
      color: var(--ink);
      min-height: 100vh;
    }

    .sign-shell {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .sign-top {
      background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%);
      color: #f8fafc;
      padding: 1rem 1.25rem 1.25rem;
      box-shadow: var(--shadow-sm);
    }

    .sign-top-inner {
      max-width: 1100px;
      margin: 0 auto;
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      gap: 0.75rem;
    }

    .sign-brand {
      display: flex;
      align-items: center;
      gap: 0.65rem;
    }

    .sign-brand-mark {
      width: 40px;
      height: 40px;
      border-radius: 10px;
      background: rgba(255,255,255,0.15);
      display: grid;
      place-items: center;
      font-size: 1.1rem;
    }

    .sign-brand h1 {
      margin: 0;
      font-size: 1.125rem;
      font-weight: 700;
      letter-spacing: -0.02em;
    }

    .sign-brand p {
      margin: 0.15rem 0 0;
      font-size: 0.8rem;
      opacity: 0.85;
    }

    .sign-meta {
      font-size: 0.8rem;
      opacity: 0.9;
      background: rgba(255,255,255,0.1);
      padding: 0.35rem 0.65rem;
      border-radius: 999px;
    }

    #toolbar {
      position: sticky;
      top: 0;
      z-index: 100;
      background: var(--surface);
      border-bottom: 1px solid var(--border);
      padding: 0.65rem 1rem;
      display: flex;
      flex-wrap: wrap;
      gap: 0.5rem;
      justify-content: center;
      align-items: center;
      box-shadow: var(--shadow-sm);
    }

    #toolbar button,
    #toolbar label.cs-tool {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.45rem;
      background: var(--surface);
      border: 1px solid var(--border);
      padding: 0.5rem 0.75rem;
      border-radius: 10px;
      color: var(--ink);
      font-size: 0.8rem;
      font-weight: 500;
      font-family: inherit;
      cursor: pointer;
      transition: background 0.15s, border-color 0.15s, color 0.15s;
      min-width: 0;
    }

    #toolbar button:hover,
    #toolbar label.cs-tool:hover {
      background: var(--accent-dim);
      border-color: #93c5fd;
      color: #1e40af;
    }

    #toolbar button.primary {
      background: var(--accent);
      border-color: var(--accent);
      color: #fff;
    }

    #toolbar button.primary:hover {
      background: #1d4ed8;
      border-color: #1d4ed8;
      color: #fff;
    }

    #toolbar input[type="file"] { display: none; }

    #page-nav {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
      flex-wrap: wrap;
      padding: 0.65rem 1rem;
      background: #f8fafc;
      border-bottom: 1px solid var(--border);
      position: sticky;
      top: 52px;
      z-index: 99;
    }

    #page-nav button {
      border: 1px solid var(--border);
      background: #fff;
      border-radius: 8px;
      padding: 0.4rem 0.75rem;
      cursor: pointer;
      font-family: inherit;
      font-size: 0.85rem;
    }

    #page-nav button:disabled {
      opacity: 0.45;
      cursor: not-allowed;
    }

    #page-nav select,
    #page-nav input[type="number"] {
      border: 1px solid var(--border);
      border-radius: 8px;
      padding: 0.4rem 0.5rem;
      font-family: inherit;
      font-size: 0.85rem;
      min-width: 4.5rem;
    }

    .page-nav-label {
      font-size: 0.85rem;
      color: var(--muted);
    }

    .sign-main {
      flex: 1;
      padding: 1.25rem 1rem 2.5rem;
    }

    .sign-main-inner {
      max-width: 960px;
      margin: 0 auto;
    }

    .doc-card {
      background: var(--surface);
      border-radius: var(--radius);
      box-shadow: var(--shadow);
      border: 1px solid var(--border);
      padding: 1rem;
      min-height: 200px;
    }

    #pdf-status {
      text-align: center;
      padding: 2.5rem 1rem;
      color: var(--muted);
      font-size: 0.95rem;
    }

    #pdf-status.error,
    #pdf-error {
      color: var(--danger);
      background: #fef2f2;
      border-radius: 10px;
      border: 1px solid #fecaca;
      padding: 1rem;
      text-align: center;
    }

    #pdf-status.loading i {
      font-size: 1.75rem;
      margin-bottom: 0.75rem;
      color: var(--accent);
    }

    #pdf-container {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 1.25rem;
    }

    .page-wrapper {
      position: relative;
      width: 100%;
      max-width: 900px;
      border-radius: 8px;
      overflow: visible;
      box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
      border: 1px solid var(--border);
      background: #fff;
      display: none;
    }

    .page-wrapper > canvas:first-child {
      border-radius: 8px;
    }

    .page-wrapper.is-active {
      display: block;
    }

    .page-wrapper.is-exporting {
      display: block !important;
    }

    canvas {
      display: block;
      width: 100% !important;
      height: auto !important;
    }

    .overlay-item {
      position: absolute;
      z-index: 20;
      cursor: grab;
      touch-action: none;
      user-select: none;
      max-width: 90%;
    }

    .overlay-item.is-dragging {
      cursor: grabbing;
      z-index: 40;
      opacity: 0.95;
    }

    .overlay-item .overlay-body {
      display: block;
      max-width: 100%;
    }

    .overlay-remove {
      position: absolute;
      top: -10px;
      right: -10px;
      width: 22px;
      height: 22px;
      border: none;
      border-radius: 50%;
      background: var(--danger);
      color: #fff;
      font-size: 14px;
      line-height: 1;
      cursor: pointer;
      z-index: 21;
      display: none;
    }

    .overlay-item:hover > .overlay-remove,
    .overlay-item:focus-within > .overlay-remove {
      display: grid;
      place-items: center;
    }

    .overlay-text {
      background: transparent;
      border: 1px dashed transparent;
      padding: 2px 4px;
      font-size: 14px;
      min-width: 40px;
      color: #0f172a;
      outline: none;
      white-space: pre-wrap;
      line-height: 1.25;
      cursor: text;
    }

    .overlay-item:hover .overlay-text,
    .overlay-text:focus {
      border-color: rgba(37, 99, 235, 0.7);
      background: rgba(255, 255, 255, 0.15);
    }

    .overlay-img {
      display: block;
      max-width: 180px;
      max-height: 180px;
      background: transparent;
      pointer-events: none;
    }

    .overlay-note {
      background: #fef9c3;
      border: 1px solid #eab308;
      border-radius: 6px;
      padding: 8px 10px;
      min-width: 120px;
      min-height: 70px;
      max-width: 220px;
      font-size: 13px;
      color: #713f12;
      box-shadow: var(--shadow-sm);
      white-space: pre-wrap;
      outline: none;
    }

    .overlay-checkbox,
    .overlay-radio {
      width: 22px;
      height: 22px;
      border: 2px solid #0f172a;
      background: rgba(255,255,255,0.85);
      display: grid;
      place-items: center;
      font-size: 14px;
      font-weight: 700;
      color: #0f172a;
    }

    .overlay-checkbox { border-radius: 4px; }
    .overlay-radio { border-radius: 50%; }
    .overlay-checkbox.is-checked::after { content: '✓'; }
    .overlay-radio.is-checked::after { content: ''; width: 10px; height: 10px; border-radius: 50%; background: #0f172a; }

    .overlay-redact {
      background: #000;
      border: 1px solid #111;
      min-width: 60px;
      min-height: 24px;
      width: 140px;
      height: 28px;
      resize: both;
      overflow: hidden;
    }

    .draw-canvas {
      position: absolute;
      top: 0;
      left: 0;
      width: 100% !important;
      height: 100% !important;
      z-index: 5;
      pointer-events: none;
      touch-action: none;
      cursor: crosshair;
    }

    .signature-box {
      background: var(--surface);
      border: 2px solid var(--ink);
      padding: 6px;
      border-radius: 10px;
      box-shadow: var(--shadow);
      z-index: 20;
    }

    .signature-box .top-bar {
      cursor: move;
      background: var(--bg);
      padding: 6px 8px;
      font-weight: 600;
      font-size: 0.8rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      border-radius: 6px;
      margin-bottom: 6px;
      gap: 0.5rem;
    }

    .signature-box canvas {
      border: 1px solid var(--border);
      border-radius: 4px;
      touch-action: none;
      width: 220px !important;
      height: 110px !important;
    }

    .delete-btn {
      background: var(--danger);
      color: white;
      border: none;
      padding: 4px 10px;
      border-radius: 6px;
      cursor: pointer;
      font-size: 0.75rem;
      font-weight: 600;
    }

    .sign-hint {
      text-align: center;
      font-size: 0.8rem;
      color: var(--muted);
      margin-top: 1rem;
      max-width: 640px;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.45;
    }

    @media (max-width: 768px) {
      #toolbar button,
      #toolbar label.cs-tool {
        flex: 1 1 calc(33% - 0.5rem);
        font-size: 0.75rem;
        padding: 0.45rem 0.4rem;
      }
      #page-nav { top: 96px; }
      .sign-brand h1 { font-size: 1rem; }
    }
  </style>
</head>
<body>

<div class="sign-shell">
  <header class="sign-top">
    <div class="sign-top-inner">
      <div class="sign-brand">
        <div class="sign-brand-mark" aria-hidden="true"><i class="fa-solid fa-file-signature"></i></div>
        <div>
          <h1>Sign document</h1>
          <p>Work on any page — place tools, drag to position, then download or submit.</p>
        </div>
      </div>
      <span class="sign-meta">Document #{{ $signature->id }}</span>
    </div>
  </header>

  <nav id="toolbar">
    <button type="button" onclick="addText()"><i class="fa-solid fa-font"></i> Text</button>
    <button type="button" onclick="addNote()"><i class="fa-solid fa-sticky-note"></i> Note</button>
    <button type="button" onclick="addCheckbox()"><i class="fa-regular fa-square-check"></i> Checkbox</button>
    <button type="button" onclick="addRadio()"><i class="fa-regular fa-circle-dot"></i> Radio</button>
    <button type="button" onclick="addRedact()"><i class="fa-solid fa-eraser"></i> Redact</button>
    <label class="cs-tool">
      <i class="fa-regular fa-image"></i> Image
      <input type="file" id="imgInput" accept="image/*" />
    </label>
    <button type="button" onclick="addSignatureBox()"><i class="fa-solid fa-pen-nib"></i> Signature</button>
    <button type="button" onclick="location.reload()"><i class="fa-solid fa-rotate-right"></i> Reload</button>
    <button type="button" class="primary" onclick="downloadPDF()"><i class="fa-solid fa-paper-plane"></i> Download &amp; send</button>
  </nav>

  <div id="page-nav" hidden>
    <button type="button" id="prevPageBtn" aria-label="Previous page"><i class="fa-solid fa-chevron-left"></i> Prev</button>
    <span class="page-nav-label">Page</span>
    <select id="pageSelect" aria-label="Select page"></select>
    <span class="page-nav-label" id="pageCountLabel">of 1</span>
    <button type="button" id="nextPageBtn" aria-label="Next page">Next <i class="fa-solid fa-chevron-right"></i></button>
  </div>

  <main class="sign-main">
    <div class="sign-main-inner">
      <div class="doc-card">
        <div id="pdf-status" class="loading" role="status">
          <div><i class="fa-solid fa-spinner fa-spin"></i></div>
          Loading PDF…
        </div>
        <div id="pdf-error" style="display:none;"></div>
        <div id="pdf-container"></div>
      </div>
      <p class="sign-hint">
        Use the page selector to jump to any page. Tools are placed on the <strong>current page</strong> and stay there when you switch pages.
        Drag to reposition. Text has a transparent background.
      </p>
    </div>
  </main>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
(function () {
  const pdfUrl = @json($pdfUrl);
  const PDF_WORKER = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

  const container = document.getElementById('pdf-container');
  const statusEl = document.getElementById('pdf-status');
  const errorEl = document.getElementById('pdf-error');
  const pageNav = document.getElementById('page-nav');
  const pageSelect = document.getElementById('pageSelect');
  const pageCountLabel = document.getElementById('pageCountLabel');
  const prevPageBtn = document.getElementById('prevPageBtn');
  const nextPageBtn = document.getElementById('nextPageBtn');

  let pdfDoc = null;
  let currentPage = 1;
  let totalPages = 0;
  const pageWrappers = new Map();

  function showError(msg) {
    statusEl.style.display = 'none';
    errorEl.textContent = msg;
    errorEl.style.display = 'block';
  }

  function hideStatus() {
    statusEl.style.display = 'none';
  }

  function getActivePage() {
    return pageWrappers.get(currentPage) || document.querySelector('.page-wrapper.is-active');
  }

  function updatePageNavUi() {
    pageSelect.value = String(currentPage);
    prevPageBtn.disabled = currentPage <= 1;
    nextPageBtn.disabled = currentPage >= totalPages;
  }

  function setActivePage(pageNumber, scrollIntoView) {
    const n = Math.max(1, Math.min(totalPages, Number(pageNumber) || 1));
    currentPage = n;
    pageWrappers.forEach((wrapper, num) => {
      wrapper.classList.toggle('is-active', num === n);
    });
    updatePageNavUi();
    if (scrollIntoView !== false) {
      const active = getActivePage();
      if (active) active.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
  }

  function makeDraggable(el, options) {
    const opts = options || {};
    const ignoreSelector = opts.ignoreSelector || 'button, canvas, input, textarea, a, .overlay-remove';
    let pointerId = null;
    let dragging = false;
    let pending = false;
    let moved = false;
    let startX = 0;
    let startY = 0;
    let grabX = 0;
    let grabY = 0;
    const THRESHOLD = 3;

    const point = (e) => ({ x: e.clientX, y: e.clientY });

    const onDown = (e) => {
      if (e.button != null && e.button !== 0) return;
      if (e.target.closest(ignoreSelector)) return;
      if (opts.allowEdit && e.target.isContentEditable && document.activeElement === e.target) return;
      pending = true;
      moved = false;
      dragging = false;
      pointerId = e.pointerId;
      const p = point(e);
      startX = p.x;
      startY = p.y;
      const rect = el.getBoundingClientRect();
      grabX = p.x - rect.left;
      grabY = p.y - rect.top;
      try { el.setPointerCapture(e.pointerId); } catch (_) {}
    };

    const onMove = (e) => {
      if (!pending && !dragging) return;
      if (pointerId != null && e.pointerId !== pointerId) return;
      const p = point(e);
      if (pending && !dragging) {
        if (Math.abs(p.x - startX) < THRESHOLD && Math.abs(p.y - startY) < THRESHOLD) return;
        dragging = true;
        moved = true;
        pending = false;
        el.classList.add('is-dragging');
        const ae = document.activeElement;
        if (ae && el.contains(ae) && ae.blur) ae.blur();
      }
      if (!dragging) return;
      const parent = el.parentElement;
      if (!parent) return;
      const parentRect = parent.getBoundingClientRect();
      const elRect = el.getBoundingClientRect();
      let left = p.x - parentRect.left - grabX;
      let top = p.y - parentRect.top - grabY;
      left = Math.min(Math.max(0, left), Math.max(0, parentRect.width - elRect.width));
      top = Math.min(Math.max(0, top), Math.max(0, parentRect.height - elRect.height));
      el.style.left = left + 'px';
      el.style.top = top + 'px';
      e.preventDefault();
    };

    const onUp = (e) => {
      if (pointerId != null && e.pointerId !== pointerId) return;
      el.dataset.wasDragged = moved ? '1' : '0';
      el.classList.remove('is-dragging');
      if (!moved && opts.allowEdit && e.target && e.target.isContentEditable) {
        e.target.focus();
      }
      pending = false;
      dragging = false;
      pointerId = null;
      try { el.releasePointerCapture(e.pointerId); } catch (_) {}
    };

    el.addEventListener('pointerdown', onDown);
    el.addEventListener('pointermove', onMove);
    el.addEventListener('pointerup', onUp);
    el.addEventListener('pointercancel', onUp);
  }

  function createOverlay(contentEl, left, top, pageNumber) {
    const page = pageNumber != null
      ? (pageWrappers.get(Number(pageNumber)) || getActivePage())
      : getActivePage();
    if (!page) return null;

    const shell = document.createElement('div');
    shell.className = 'overlay-item';
    shell.dataset.page = String(page.dataset.page || currentPage);
    shell.style.left = (left != null ? left : 40) + 'px';
    shell.style.top = (top != null ? top : 40) + 'px';

    contentEl.classList.add('overlay-body');
    shell.appendChild(contentEl);

    const removeBtn = document.createElement('button');
    removeBtn.type = 'button';
    removeBtn.className = 'overlay-remove';
    removeBtn.title = 'Remove';
    removeBtn.setAttribute('aria-label', 'Remove');
    removeBtn.textContent = '×';
    removeBtn.addEventListener('pointerdown', (e) => e.stopPropagation());
    removeBtn.addEventListener('click', (e) => {
      e.stopPropagation();
      shell.remove();
    });
    shell.appendChild(removeBtn);

    page.appendChild(shell);
    makeDraggable(shell, { allowEdit: contentEl.isContentEditable });
    return shell;
  }

  async function renderPage(pageNumber) {
    const page = await pdfDoc.getPage(pageNumber);
    const scale = window.innerWidth > 768 ? 1.5 : 1;
    const viewport = page.getViewport({ scale });

    const wrapper = document.createElement('div');
    wrapper.className = 'page-wrapper';
    wrapper.dataset.page = String(pageNumber);

    const canvas = document.createElement('canvas');
    const ctx = canvas.getContext('2d');
    canvas.width = viewport.width;
    canvas.height = viewport.height;
    await page.render({ canvasContext: ctx, viewport }).promise;

    const drawCanvas = document.createElement('canvas');
    drawCanvas.className = 'draw-canvas';
    drawCanvas.width = viewport.width;
    drawCanvas.height = viewport.height;

    wrapper.append(canvas, drawCanvas);
    wrapper.addEventListener('click', () => {
      if (currentPage !== pageNumber) setActivePage(pageNumber, false);
    });

    container.appendChild(wrapper);
    pageWrappers.set(pageNumber, wrapper);
  }

  function buildPageNav() {
    pageSelect.innerHTML = '';
    for (let i = 1; i <= totalPages; i++) {
      const opt = document.createElement('option');
      opt.value = String(i);
      opt.textContent = String(i);
      pageSelect.appendChild(opt);
    }
    pageCountLabel.textContent = 'of ' + totalPages;
    pageNav.hidden = totalPages < 1;
  }

  if (!pdfUrl) {
    showError('No PDF file is linked to this document. The sender may need to upload it again.');
  } else if (typeof pdfjsLib !== 'undefined') {
    pdfjsLib.GlobalWorkerOptions.workerSrc = PDF_WORKER;
  }

  if (pdfUrl && typeof pdfjsLib !== 'undefined') {
    pdfjsLib.getDocument({ url: pdfUrl }).promise
      .then(async (pdf) => {
        pdfDoc = pdf;
        totalPages = pdf.numPages;
        hideStatus();
        buildPageNav();
        for (let i = 1; i <= pdf.numPages; i++) {
          await renderPage(i);
        }
        setActivePage(1, false);
      })
      .catch((err) => {
        console.error(err);
        showError('Could not open this PDF. The file may be missing or the link is invalid.');
      });
  }

  prevPageBtn.addEventListener('click', () => setActivePage(currentPage - 1));
  nextPageBtn.addEventListener('click', () => setActivePage(currentPage + 1));
  pageSelect.addEventListener('change', () => setActivePage(pageSelect.value));

  window.addText = function addText() {
    const txt = document.createElement('div');
    txt.className = 'overlay-text';
    txt.contentEditable = 'true';
    txt.spellcheck = false;
    txt.textContent = 'Text';
    const shell = createOverlay(txt, 48, 48);
    if (!shell) return;
    setTimeout(() => {
      txt.focus();
      const range = document.createRange();
      range.selectNodeContents(txt);
      const sel = window.getSelection();
      sel.removeAllRanges();
      sel.addRange(range);
    }, 0);
  };

  window.addNote = function addNote() {
    const note = document.createElement('div');
    note.className = 'overlay-note';
    note.contentEditable = 'true';
    note.textContent = 'Note';
    const shell = createOverlay(note, 60, 80);
    if (!shell) return;
    setTimeout(() => note.focus(), 0);
  };

  window.addCheckbox = function addCheckbox() {
    const box = document.createElement('div');
    box.className = 'overlay-checkbox';
    box.title = 'Click to toggle';
    const shell = createOverlay(box, 56, 56);
    if (!shell) return;
    box.addEventListener('click', (e) => {
      e.stopPropagation();
      if (shell.dataset.wasDragged === '1') return;
      box.classList.toggle('is-checked');
    });
  };

  window.addRadio = function addRadio() {
    const radio = document.createElement('div');
    radio.className = 'overlay-radio';
    radio.title = 'Click to select';
    const shell = createOverlay(radio, 56, 90);
    if (!shell) return;
    radio.addEventListener('click', (e) => {
      e.stopPropagation();
      if (shell.dataset.wasDragged === '1') return;
      const page = getActivePage();
      if (page) {
        page.querySelectorAll('.overlay-radio').forEach((r) => r.classList.remove('is-checked'));
      }
      radio.classList.add('is-checked');
    });
  };

  window.addRedact = function addRedact() {
    const block = document.createElement('div');
    block.className = 'overlay-redact';
    createOverlay(block, 70, 120);
  };

  document.getElementById('imgInput').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (evt) => {
      const img = document.createElement('img');
      img.src = evt.target.result;
      img.className = 'overlay-img';
      img.alt = 'Uploaded image';
      createOverlay(img, 40, 40);
    };
    reader.readAsDataURL(file);
    e.target.value = '';
  });

  window.addSignatureBox = function addSignatureBox() {
    const page = getActivePage();
    if (!page) return;

    const box = document.createElement('div');
    box.className = 'signature-box overlay-item';
    box.dataset.page = String(currentPage);
    box.style.left = '50px';
    box.style.top = '100px';

    box.innerHTML = `
      <div class="top-bar">
        <span>Draw signature — drag this bar to move</span>
        <button type="button" class="delete-btn" data-cancel>Remove</button>
      </div>
      <canvas width="220" height="110"></canvas>
      <div style="margin-top:6px;display:flex;gap:6px;justify-content:flex-end;">
        <button type="button" class="delete-btn" style="background:#2563eb;" data-place>Place on page</button>
      </div>
    `;

    page.appendChild(box);
    makeDraggable(box, { ignoreSelector: 'button, canvas, input, textarea, a, .overlay-remove' });

    const canvas = box.querySelector('canvas');
    const ctx = canvas.getContext('2d');
    let drawing = false;
    let hasDrawn = false;

    const coords = (e) => {
      const rect = canvas.getBoundingClientRect();
      const clientX = e.clientX != null ? e.clientX : (e.touches && e.touches[0].clientX);
      const clientY = e.clientY != null ? e.clientY : (e.touches && e.touches[0].clientY);
      return {
        x: (clientX - rect.left) * (canvas.width / rect.width),
        y: (clientY - rect.top) * (canvas.height / rect.height)
      };
    };

    const startDraw = (e) => {
      drawing = true;
      const { x, y } = coords(e);
      ctx.beginPath();
      ctx.moveTo(x, y);
      e.preventDefault();
      e.stopPropagation();
    };

    const draw = (e) => {
      if (!drawing) return;
      const { x, y } = coords(e);
      ctx.lineTo(x, y);
      ctx.strokeStyle = '#0f172a';
      ctx.lineWidth = 2;
      ctx.lineCap = 'round';
      ctx.stroke();
      hasDrawn = true;
      e.preventDefault();
      e.stopPropagation();
    };

    const endDraw = () => { drawing = false; };

    canvas.addEventListener('pointerdown', startDraw);
    canvas.addEventListener('pointermove', draw);
    canvas.addEventListener('pointerup', endDraw);
    canvas.addEventListener('pointerleave', endDraw);
    canvas.addEventListener('pointercancel', endDraw);

    function placeSignature() {
      if (!hasDrawn) {
        alert('Draw your signature first, then click Place on page.');
        return;
      }
      const left = parseFloat(box.style.left) || 50;
      const top = parseFloat(box.style.top) || 100;
      const pageNo = Number(box.dataset.page || currentPage);
      const img = document.createElement('img');
      img.src = canvas.toDataURL('image/png');
      img.className = 'overlay-img';
      img.alt = 'Signature';
      createOverlay(img, left, top, pageNo);
      box.remove();
    }

    box.querySelector('[data-place]').addEventListener('click', (e) => {
      e.stopPropagation();
      placeSignature();
    });
    box.querySelector('[data-cancel]').addEventListener('click', (e) => {
      e.stopPropagation();
      box.remove();
    });
  };

  window.downloadPDF = async function downloadPDF() {
    const pages = Array.from(pageWrappers.values());
    if (!pages.length) {
      alert('Nothing to export yet. Wait for the PDF to finish loading.');
      return;
    }

    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF();

    for (let i = 0; i < pages.length; i++) {
      if (i > 0) pdf.addPage();
      pages[i].classList.add('is-exporting');
      const snapshot = await html2canvas(pages[i], { backgroundColor: '#ffffff', scale: 2, logging: false });
      pages[i].classList.remove('is-exporting');
      const img = snapshot.toDataURL('image/jpeg', 1.0);
      const prop = pdf.getImageProperties(img);
      const w = pdf.internal.pageSize.getWidth();
      const h = (prop.height * w) / prop.width;
      pdf.addImage(img, 'JPEG', 0, 0, w, h);
    }

    setActivePage(currentPage, false);
    pdf.save('edited.pdf');

    const pdfBlob = pdf.output('blob');
    const formData = new FormData();
    formData.append('pdf_file', pdfBlob, 'edited.pdf');
    formData.append('id', '{{ $signature->id }}');

    try {
      const response = await fetch("{{ url('/upload-file') }}", {
        method: 'POST',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
          'Accept': 'application/json',
        },
        body: formData,
      });

      const result = await response.json().catch(() => ({}));

      if (!response.ok) {
        throw new Error(result.message || 'Upload failed');
      }

      alert(result.message || 'PDF submitted successfully!');
      if (result.path) {
        window.location.href = result.path;
      }
    } catch (error) {
      console.error(error);
      alert('Could not submit the PDF to the server. Your download should still be available locally.');
    }
  };
})();
</script>
</body>
</html>
