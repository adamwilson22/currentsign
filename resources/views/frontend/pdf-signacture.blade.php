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
      --toolbar: #0f172a;
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
      padding: 0.5rem 0.85rem;
      border-radius: 10px;
      color: var(--ink);
      font-size: 0.875rem;
      font-weight: 500;
      font-family: inherit;
      cursor: pointer;
      transition: background 0.15s, border-color 0.15s, color 0.15s;
      min-width: 7rem;
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

    #toolbar input[type="file"] {
      display: none;
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

    #pdf-status.error {
      color: var(--danger);
      background: #fef2f2;
      border-radius: 10px;
      border: 1px solid #fecaca;
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
      overflow: hidden;
      box-shadow: 0 4px 20px rgba(15, 23, 42, 0.06);
      border: 1px solid var(--border);
      background: #fff;
    }

    canvas {
      display: block;
      width: 100% !important;
      height: auto !important;
    }

    .overlay-text, .overlay-img, .signature-box {
      position: absolute;
      z-index: 10;
      cursor: move;
      touch-action: none;
    }

    .overlay-text {
      background: rgba(255,255,255,0.92);
      border: 1px dashed var(--accent);
      padding: 6px 10px;
      font-size: 14px;
      min-width: 60px;
      border-radius: 6px;
      box-shadow: var(--shadow-sm);
    }

    .overlay-img {
      max-width: 150px;
      max-height: 150px;
      border-radius: 6px;
      box-shadow: var(--shadow-sm);
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
    }

    .signature-box canvas {
      border: 1px solid var(--border);
      border-radius: 4px;
      touch-action: none;
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
      max-width: 560px;
      margin-left: auto;
      margin-right: auto;
      line-height: 1.45;
    }

    @media (max-width: 768px) {
      #toolbar button,
      #toolbar label.cs-tool {
        flex: 1 1 calc(50% - 0.5rem);
        min-width: 0;
        font-size: 0.8rem;
      }
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
          <p>Add text, images, or your signature, then download or submit.</p>
        </div>
      </div>
      <span class="sign-meta">Document #{{ $signature->id }}</span>
    </div>
  </header>

  <nav id="toolbar">
    <button type="button" onclick="addText()">
      <i class="fa-solid fa-font"></i> Add text
    </button>
    <label class="cs-tool">
      <i class="fa-regular fa-image"></i> Image
      <input type="file" id="imgInput" accept="image/*" />
    </label>
    <button type="button" onclick="addSignatureBox()">
      <i class="fa-solid fa-pen-nib"></i> Signature
    </button>
    <button type="button" onclick="location.reload()">
      <i class="fa-solid fa-rotate-right"></i> Reload
    </button>
    <button type="button" class="primary" onclick="downloadPDF()">
      <i class="fa-solid fa-paper-plane"></i> Download &amp; send
    </button>
  </nav>

  <main class="sign-main">
    <div class="sign-main-inner">
      <div class="doc-card">
        <div id="pdf-status" class="loading" role="status">
          <div><i class="fa-solid fa-spinner fa-spin"></i></div>
          Loading PDF…
        </div>
        <div id="pdf-error" class="error" style="display:none;"></div>
        <div id="pdf-container"></div>
      </div>
      <p class="sign-hint">Drag overlays to position them on the page. Use <strong>Signature</strong> to draw, then click outside the box to place it on the document.</p>
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

  let pdfDoc;

  function showError(msg) {
    statusEl.style.display = 'none';
    errorEl.textContent = msg;
    errorEl.style.display = 'block';
    errorEl.classList.add('error');
  }

  function hideStatus() {
    statusEl.style.display = 'none';
  }

  if (!pdfUrl) {
    showError('No PDF file is linked to this document. The sender may need to upload it again.');
  } else if (typeof pdfjsLib !== 'undefined') {
    pdfjsLib.GlobalWorkerOptions.workerSrc = PDF_WORKER;
  }

  async function renderPage(pageNumber) {
    const page = await pdfDoc.getPage(pageNumber);
    const scale = window.innerWidth > 768 ? 1.5 : 1;
    const viewport = page.getViewport({ scale });

    const wrapper = document.createElement('div');
    wrapper.className = 'page-wrapper';

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
  }

  if (pdfUrl && typeof pdfjsLib !== 'undefined') {
    pdfjsLib.getDocument({ url: pdfUrl }).promise
      .then(async (pdf) => {
        pdfDoc = pdf;
        hideStatus();
        for (let i = 1; i <= pdf.numPages; i++) {
          await renderPage(i);
        }
      })
      .catch((err) => {
        console.error(err);
        showError('Could not open this PDF. The file may be missing or the link is invalid. If you just uploaded it, ask the sender to resend the signing link.');
      });
  }

  window.addText = function addText() {
    const page = document.querySelector('.page-wrapper');
    if (!page) return;
    const txt = document.createElement('div');
    txt.className = 'overlay-text';
    txt.contentEditable = 'true';
    txt.textContent = '';
    txt.style.left = '20px';
    txt.style.top = '20px';
    makeDraggable(txt);
    page.appendChild(txt);
    txt.focus();
  };

  document.getElementById('imgInput').addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (evt) => {
      const page = document.querySelector('.page-wrapper');
      if (!page) return;
      const img = document.createElement('img');
      img.src = evt.target.result;
      img.className = 'overlay-img';
      img.style.left = '20px';
      img.style.top = '20px';
      makeDraggable(img);
      page.appendChild(img);
    };
    reader.readAsDataURL(file);
    e.target.value = '';
  });

  function makeDraggable(el) {
    let dragging = false;
    let offsetX;
    let offsetY;
    const start = (e) => {
      dragging = true;
      const evt = e.touches ? e.touches[0] : e;
      const rect = el.getBoundingClientRect();
      offsetX = evt.clientX - rect.left;
      offsetY = evt.clientY - rect.top;
      e.preventDefault();
    };
    const move = (e) => {
      if (!dragging) return;
      const evt = e.touches ? e.touches[0] : e;
      const parentRect = el.parentElement.getBoundingClientRect();
      el.style.left = `${evt.clientX - parentRect.left - offsetX}px`;
      el.style.top = `${evt.clientY - parentRect.top - offsetY}px`;
    };
    const end = () => { dragging = false; };

    el.addEventListener('mousedown', start);
    el.addEventListener('touchstart', start, { passive: false });
    document.addEventListener('mousemove', move);
    document.addEventListener('touchmove', move, { passive: false });
    document.addEventListener('mouseup', end);
    document.addEventListener('touchend', end);
  }

  window.addSignatureBox = function addSignatureBox() {
    const page = document.querySelector('.page-wrapper');
    if (!page) return;

    const box = document.createElement('div');
    box.className = 'signature-box';
    box.style.left = '50px';
    box.style.top = '100px';
    box.style.position = 'absolute';

    box.innerHTML = `
      <div class="top-bar">
        <span>Draw signature</span>
        <button type="button" class="delete-btn">Remove</button>
      </div>
      <canvas width="220" height="110"></canvas>
    `;

    page.appendChild(box);

    const canvas = box.querySelector('canvas');
    const ctx = canvas.getContext('2d');
    let drawing = false;
    let hasDrawn = false;

    const coords = (e) => {
      const rect = canvas.getBoundingClientRect();
      const evt = e.touches ? e.touches[0] : e;
      return { x: evt.clientX - rect.left, y: evt.clientY - rect.top };
    };

    const startDraw = (e) => {
      drawing = true;
      const { x, y } = coords(e);
      ctx.beginPath();
      ctx.moveTo(x, y);
      e.preventDefault();
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
    };

    const endDraw = () => { drawing = false; };

    canvas.addEventListener('mousedown', startDraw);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', endDraw);
    canvas.addEventListener('mouseleave', endDraw);

    canvas.addEventListener('touchstart', startDraw, { passive: false });
    canvas.addEventListener('touchmove', draw, { passive: false });
    canvas.addEventListener('touchend', endDraw);

    setTimeout(() => {
      document.addEventListener('click', function handler(e) {
        if (!box.contains(e.target) && hasDrawn) {
          const image = new Image();
          image.src = canvas.toDataURL('image/png');
          image.className = 'overlay-img';
          image.style.left = box.style.left;
          image.style.top = box.style.top;
          page.appendChild(image);
          makeDraggable(image);
          box.remove();
          document.removeEventListener('click', handler);
        }
      });
    }, 100);

    box.querySelector('.delete-btn').onclick = () => box.remove();
  };

  window.downloadPDF = async function downloadPDF() {
    const pages = document.querySelectorAll('.page-wrapper');
    if (!pages.length) {
      alert('Nothing to export yet. Wait for the PDF to finish loading.');
      return;
    }

    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF();

    for (let i = 0; i < pages.length; i++) {
      if (i > 0) pdf.addPage();
      const snapshot = await html2canvas(pages[i], { backgroundColor: '#ffffff', scale: 2, logging: false });
      const img = snapshot.toDataURL('image/jpeg', 1.0);
      const prop = pdf.getImageProperties(img);
      const w = pdf.internal.pageSize.getWidth();
      const h = (prop.height * w) / prop.width;
      pdf.addImage(img, 'JPEG', 0, 0, w, h);
    }

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
