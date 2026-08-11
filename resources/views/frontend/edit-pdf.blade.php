<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Edit PDF — CurrentSign</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @php
    $rawPath = $signature->pdf_path ?? $signature->signature ?? '';
    $rawPath = ltrim((string) $rawPath, '/');
    if ($rawPath !== '' && str_starts_with($rawPath, 'public/')) {
        $rawPath = substr($rawPath, strlen('public/'));
    }
    $pdfUrl = $rawPath !== '' ? asset($rawPath) : '';
  @endphp
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"/>
  <style>
    body { margin: 0; font-family: sans-serif; background: #f0f0f0; }

    #toolbar {
      position: sticky;
      top: 0;
      width: 100%;
      background: #333;
      color: #fff;
      padding: 8px;
      z-index: 999;
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
      justify-content: center;
    }
    #toolbar button,
    #toolbar label {
      background: #555;
      border: none;
      padding: 6px 10px;
      border-radius: 4px;
      color: #fff;
      font-size: 13px;
      cursor: pointer;
      flex: 1 1 auto;
      text-align: center;
      min-width: 90px;
    }
    #toolbar input[type="file"] { display: none; }

    #page-nav {
      position: sticky;
      top: 52px;
      z-index: 998;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      flex-wrap: wrap;
      padding: 8px;
      background: #eee;
      border-bottom: 1px solid #ccc;
    }
    #page-nav button,
    #page-nav select {
      padding: 6px 10px;
      border-radius: 4px;
      border: 1px solid #bbb;
      background: #fff;
      cursor: pointer;
    }
    #page-nav button:disabled { opacity: 0.45; cursor: not-allowed; }

    #pdf-container {
      padding: 20px 12px 40px;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }
    .page-wrapper {
      position: relative;
      width: 100%;
      max-width: 900px;
      display: none;
      background: #fff;
    }
    .page-wrapper.is-active { display: block; }
    .page-wrapper.is-exporting { display: block !important; }

    canvas {
      display: block;
      width: 100% !important;
      height: auto !important;
      border: 1px solid #ccc;
    }

    .overlay-item {
      position: absolute;
      z-index: 20;
      cursor: grab;
      touch-action: none;
      user-select: none;
      max-width: 90%;
    }
    .overlay-item.is-dragging { cursor: grabbing; z-index: 40; opacity: 0.95; }
    .overlay-item .overlay-body { display: block; max-width: 100%; }
    .overlay-remove {
      position: absolute; top: -10px; right: -10px; width: 22px; height: 22px;
      border: none; border-radius: 50%; background: red; color: #fff;
      font-size: 14px; cursor: pointer; z-index: 21; display: none;
    }
    .overlay-item:hover > .overlay-remove,
    .overlay-item:focus-within > .overlay-remove { display: grid; place-items: center; }
    .overlay-text {
      background: transparent;
      border: 1px dashed transparent;
      padding: 2px 4px;
      font-size: 14px;
      min-width: 40px;
      outline: none;
      white-space: pre-wrap;
      cursor: text;
    }
    .overlay-item:hover .overlay-text,
    .overlay-text:focus {
      border-color: #2563eb;
      background: rgba(255,255,255,0.15);
    }
    .overlay-img { display:block; max-width: 150px; max-height: 150px; background: transparent; pointer-events: none; }
    .overlay-note {
      background: #fef9c3;
      border: 1px solid #eab308;
      border-radius: 4px;
      padding: 8px;
      min-width: 120px;
      min-height: 70px;
      max-width: 220px;
      font-size: 13px;
      outline: none;
      white-space: pre-wrap;
    }
    .overlay-checkbox,
    .overlay-radio {
      width: 22px;
      height: 22px;
      border: 2px solid #333;
      background: rgba(255,255,255,0.9);
      display: grid;
      place-items: center;
      font-weight: bold;
    }
    .overlay-checkbox { border-radius: 3px; }
    .overlay-radio { border-radius: 50%; }
    .overlay-checkbox.is-checked::after { content: '✓'; }
    .overlay-radio.is-checked::after {
      content: '';
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #333;
    }
    .overlay-redact {
      background: #000;
      min-width: 60px;
      min-height: 24px;
      width: 140px;
      height: 28px;
      resize: both;
      overflow: hidden;
    }
    .draw-canvas {
      position: absolute;
      top: 0; left: 0;
      width: 100% !important;
      height: 100% !important;
      z-index: 5;
      pointer-events: none;
      touch-action: none;
    }

    .signature-box {
      background: white;
      border: 2px solid #333;
      padding: 5px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.2);
      z-index: 20;
    }
    .signature-box .top-bar {
      cursor: move;
      background: #eee;
      padding: 5px;
      font-weight: bold;
      display: flex;
      justify-content: space-between;
      gap: 6px;
    }
    .signature-box canvas {
      border: 1px solid #ccc;
      touch-action: none;
      width: 200px !important;
      height: 100px !important;
    }
    .delete-btn {
      background: red;
      color: white;
      border: none;
      padding: 3px 6px;
      border-radius: 4px;
      cursor: pointer;
    }

    @media (max-width: 768px) {
      #toolbar button, #toolbar label { font-size: 12px; min-width: 70px; }
      #page-nav { top: 88px; }
    }
  </style>
</head>
<body>

  <div id="toolbar">
    <button type="button" onclick="addText()"><i class="fa fa-font"></i> Text</button>
    <button type="button" onclick="addNote()"><i class="fa fa-sticky-note"></i> Note</button>
    <button type="button" onclick="addCheckbox()"><i class="fa fa-check-square-o"></i> Checkbox</button>
    <button type="button" onclick="addRadio()"><i class="fa fa-dot-circle-o"></i> Radio</button>
    <button type="button" onclick="addRedact()"><i class="fa fa-ban"></i> Redact</button>
    <label>
      <i class="fa fa-image"></i> Image
      <input type="file" id="imgInput" accept="image/*" />
    </label>
    <button type="button" onclick="addSignatureBox()"><i class="fa fa-pencil"></i> Signature</button>
    <button type="button" onclick="location.reload()"><i class="fa fa-refresh"></i> Refresh</button>
    <button type="button" onclick="downloadPDF()"><i class="fa fa-download"></i> Download</button>
  </div>

  <div id="page-nav" hidden>
    <button type="button" id="prevPageBtn">Prev</button>
    <span>Page</span>
    <select id="pageSelect" aria-label="Select page"></select>
    <span id="pageCountLabel">of 1</span>
    <button type="button" id="nextPageBtn">Next</button>
  </div>

  <div id="pdf-container"></div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script>
(function () {
  const pdfUrl = @json($pdfUrl);
  const PDF_WORKER = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';
  const container = document.getElementById('pdf-container');
  const pageNav = document.getElementById('page-nav');
  const pageSelect = document.getElementById('pageSelect');
  const pageCountLabel = document.getElementById('pageCountLabel');
  const prevPageBtn = document.getElementById('prevPageBtn');
  const nextPageBtn = document.getElementById('nextPageBtn');

  let pdfDoc = null;
  let currentPage = 1;
  let totalPages = 0;
  const pageWrappers = new Map();

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

    const onDown = (e) => {
      if (e.button != null && e.button !== 0) return;
      if (e.target.closest(ignoreSelector)) return;
      if (opts.allowEdit && e.target.isContentEditable && document.activeElement === e.target) return;
      pending = true;
      moved = false;
      dragging = false;
      pointerId = e.pointerId;
      startX = e.clientX;
      startY = e.clientY;
      const rect = el.getBoundingClientRect();
      grabX = e.clientX - rect.left;
      grabY = e.clientY - rect.top;
      try { el.setPointerCapture(e.pointerId); } catch (_) {}
    };

    const onMove = (e) => {
      if (!pending && !dragging) return;
      if (pointerId != null && e.pointerId !== pointerId) return;
      if (pending && !dragging) {
        if (Math.abs(e.clientX - startX) < THRESHOLD && Math.abs(e.clientY - startY) < THRESHOLD) return;
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
      let left = e.clientX - parentRect.left - grabX;
      let top = e.clientY - parentRect.top - grabY;
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
      if (!moved && opts.allowEdit && e.target && e.target.isContentEditable) e.target.focus();
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
    removeBtn.textContent = '×';
    removeBtn.addEventListener('pointerdown', (e) => e.stopPropagation());
    removeBtn.addEventListener('click', (e) => { e.stopPropagation(); shell.remove(); });
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

  if (pdfUrl && typeof pdfjsLib !== 'undefined') {
    pdfjsLib.GlobalWorkerOptions.workerSrc = PDF_WORKER;
  }

  if (!pdfUrl) {
    container.innerHTML = '<p style="padding:2rem;text-align:center;color:#64748b;">No PDF path configured for this record.</p>';
  } else {
    pdfjsLib.getDocument({ url: pdfUrl }).promise.then(async (pdf) => {
      pdfDoc = pdf;
      totalPages = pdf.numPages;
      buildPageNav();
      for (let i = 1; i <= pdf.numPages; i++) {
        await renderPage(i);
      }
      setActivePage(1, false);
    }).catch((err) => {
      console.error(err);
      container.innerHTML = '<p style="padding:2rem;text-align:center;color:#b91c1c;">Could not load this PDF.</p>';
    });
  }

  prevPageBtn.addEventListener('click', () => setActivePage(currentPage - 1));
  nextPageBtn.addEventListener('click', () => setActivePage(currentPage + 1));
  pageSelect.addEventListener('change', () => setActivePage(pageSelect.value));

  window.addText = function addText() {
    const txt = document.createElement('div');
    txt.className = 'overlay-text';
    txt.contentEditable = 'true';
    txt.textContent = 'Text';
    const shell = createOverlay(txt, 48, 48);
    if (shell) setTimeout(() => txt.focus(), 0);
  };

  window.addNote = function addNote() {
    const note = document.createElement('div');
    note.className = 'overlay-note';
    note.contentEditable = 'true';
    note.textContent = 'Note';
    const shell = createOverlay(note, 60, 80);
    if (shell) setTimeout(() => note.focus(), 0);
  };

  window.addCheckbox = function addCheckbox() {
    const box = document.createElement('div');
    box.className = 'overlay-checkbox';
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
    const shell = createOverlay(radio, 56, 90);
    if (!shell) return;
    radio.addEventListener('click', (e) => {
      e.stopPropagation();
      if (shell.dataset.wasDragged === '1') return;
      const page = getActivePage();
      if (page) page.querySelectorAll('.overlay-radio').forEach((r) => r.classList.remove('is-checked'));
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
        <span>Draw signature — drag to move</span>
        <button type="button" class="delete-btn" data-cancel>X</button>
      </div>
      <canvas width="200" height="100"></canvas>
      <div style="margin-top:6px;text-align:right;">
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
      return {
        x: (e.clientX - rect.left) * (canvas.width / rect.width),
        y: (e.clientY - rect.top) * (canvas.height / rect.height)
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
      ctx.strokeStyle = '#000';
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

    box.querySelector('[data-place]').addEventListener('click', (e) => {
      e.stopPropagation();
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
    });
    box.querySelector('[data-cancel]').addEventListener('click', (e) => {
      e.stopPropagation();
      box.remove();
    });
  };

  window.downloadPDF = async function downloadPDF() {
    const pages = Array.from(pageWrappers.values());
    if (!pages.length) {
      alert('Nothing to export yet.');
      return;
    }
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF();
    for (let i = 0; i < pages.length; i++) {
      if (i > 0) pdf.addPage();
      pages[i].classList.add('is-exporting');
      const canvas = await html2canvas(pages[i], { backgroundColor: '#fff', scale: 2 });
      pages[i].classList.remove('is-exporting');
      const img = canvas.toDataURL('image/jpeg', 1.0);
      const prop = pdf.getImageProperties(img);
      const w = pdf.internal.pageSize.getWidth();
      const h = (prop.height * w) / prop.width;
      pdf.addImage(img, 'JPEG', 0, 0, w, h);
    }
    setActivePage(currentPage, false);
    pdf.save('edited.pdf');
  };
})();
  </script>
</body>
</html>
