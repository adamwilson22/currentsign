<!DOCTYPE html>
<html>
<head>
  <title>PDF Signature Placement</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    canvas { border: 1px solid #000; touch-action: none; }
    #pdf-wrapper { position: relative; display: inline-block; width: 100%; max-width: 800px; }
    #pdf-canvas { cursor: crosshair; width: 100%; height: auto; }

    #signature-preview, .text-preview {
      position: absolute;
      display: none;
      background: rgba(255,255,255,0.8);
      padding: 2px 6px;
      border: 1px dashed #000;
      font-weight: bold;
      cursor: move;
      z-index: 10;
    }
  </style>
</head>
<body class="p-4">

<!-- Action Buttons -->
<div class="mb-3">
  <button class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#signatureModal">Add Signature</button>
  <button class="btn btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#nameModal">Add Name</button>
  <button class="btn btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#titleModal">Add Title</button>
  <button class="btn btn-outline-primary mb-2" data-bs-toggle="modal" data-bs-target="#dateModal">Add Date</button>
</div>
  @if (session('success'))
    <div style="padding: 10px; background-color: #d4edda; color: #155724; margin-bottom: 15px; border: 1px solid #c3e6cb;">
      {{ session('success') }}
    </div>
  @endif
<!-- PDF Viewer & Overlay -->
<h5>Step: Drag to place elements</h5>
<input type="range" id="signature-scale" min="0.5" max="3" step="0.1" value="1">
<div id="pdf-wrapper">
  <canvas id="pdf-canvas"></canvas>
  <img id="signature-preview" />
  <div id="name-preview" class="text-preview">Name</div>
  <div id="title-preview" class="text-preview">Title</div>
  <div id="date-preview" class="text-preview">Date</div>
</div>

<!-- Submit Form -->
<form method="POST" action="{{ url('user/signacturesubmit/' . $signature->id) }}">
  @csrf
  <input type="hidden" name="signature_data" id="signature-data">
  <input type="hidden" name="x" id="x">
  <input type="hidden" name="y" id="y">
  <input type="hidden" name="scale" id="scale">
  <input type="hidden" name="canvas_height" id="canvas_height">
  <input type="hidden" name="page" value="1">

  <input type="hidden" name="name_x" id="name_x">
  <input type="hidden" name="name_y" id="name_y">
  <input type="hidden" name="name" id="name">

  <input type="hidden" name="title_x" id="title_x">
  <input type="hidden" name="title_y" id="title_y">
  <input type="hidden" name="title" id="title">

  <input type="hidden" name="date_x" id="date_x">
  <input type="hidden" name="date_y" id="date_y">
  <input type="hidden" name="date" id="date">

  <button class="btn btn-success w-100 mt-3" type="submit">Submit</button>
</form>

<!-- Signature Modal -->
<div class="modal fade" id="signatureModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Draw Signature</h5></div>
      <div class="modal-body text-center">
        <canvas id="signature-pad" width="300" height="150" style="border:1px solid #000;"></canvas><br>
        <button id="clear-signature" class="btn btn-danger btn-sm mt-2">Clear</button>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="done-signature" data-bs-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>

<!-- Name Modal -->
<div class="modal fade" id="nameModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Enter Name</h5></div>
      <div class="modal-body">
        <input type="text" id="name-input" class="form-control" placeholder="Your name">
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="addText('name')" data-bs-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>

<!-- Title Modal -->
<div class="modal fade" id="titleModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Enter Title</h5></div>
      <div class="modal-body">
        <input type="text" id="title-input" class="form-control" placeholder="Your title">
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="addText('title')" data-bs-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>

<!-- Date Modal -->
<div class="modal fade" id="dateModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Enter Date</h5></div>
      <div class="modal-body">
        <input type="date" id="date-input" class="form-control">
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" onclick="addText('date')" data-bs-dismiss="modal">Done</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
  const canvas = document.getElementById('pdf-canvas');
  const ctx = canvas.getContext('2d');
  const sigPreview = document.getElementById('signature-preview');
  const signaturePad = document.getElementById('signature-pad');
  const signatureCtx = signaturePad.getContext('2d');
  const scaleInput = document.getElementById('signature-scale');
  let drawing = false, currentScale = 1;

  const dragItems = {
    'signature-preview': { xId: 'x', yId: 'y' },
    'name-preview': { xId: 'name_x', yId: 'name_y' },
    'title-preview': { xId: 'title_x', yId: 'title_y' },
    'date-preview': { xId: 'date_x', yId: 'date_y' }
  };

  const pdfUrl = "{{ asset('public/' . $signature->pdf_path) }}";
  const pdfjsLib = window['pdfjs-dist/build/pdf'];
  pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

  let pdfHeight = 0;
  pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
    pdf.getPage(1).then(page => {
      const viewport = page.getViewport({ scale: 1.5 });
      canvas.width = viewport.width;
      canvas.height = viewport.height;
      pdfHeight = viewport.height;

      // Send height to backend
      document.getElementById('canvas_height').value = canvas.height;

      page.render({ canvasContext: ctx, viewport });
    });
  });

  // Signature drawing (mouse/touch)
  signaturePad.addEventListener('mousedown', () => { drawing = true; signatureCtx.beginPath(); });
  signaturePad.addEventListener('mouseup', () => drawing = false);
  signaturePad.addEventListener('mousemove', e => {
    if (!drawing) return;
    const rect = signaturePad.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    signatureCtx.lineTo(x, y);
    signatureCtx.stroke();
  });

  signaturePad.addEventListener("touchstart", e => { drawing = true; signatureCtx.beginPath(); });
  signaturePad.addEventListener("touchend", () => drawing = false);
  signaturePad.addEventListener("touchmove", e => {
    if (!drawing) return;
    const rect = signaturePad.getBoundingClientRect();
    const touch = e.touches[0];
    const x = touch.clientX - rect.left;
    const y = touch.clientY - rect.top;
    signatureCtx.lineTo(x, y);
    signatureCtx.stroke();
  });

  document.getElementById('clear-signature').onclick = () => {
    signatureCtx.clearRect(0, 0, signaturePad.width, signaturePad.height);
    sigPreview.style.display = 'none';
  };

  document.getElementById('done-signature').onclick = () => {
    const sigData = signaturePad.toDataURL("image/png");
    document.getElementById('signature-data').value = sigData;
    sigPreview.src = sigData;
    sigPreview.style.left = '50px';
    sigPreview.style.top = '50px';
    sigPreview.style.display = 'block';
    scaleInput.dispatchEvent(new Event('input'));
  };

  scaleInput.oninput = function () {
    currentScale = parseFloat(this.value);
    sigPreview.style.transform = `scale(${currentScale})`;
    sigPreview.style.transformOrigin = 'top left';
    document.getElementById('scale').value = currentScale;
  };

  function addText(field) {
    const val = document.getElementById(`${field}-input`).value;
    const preview = document.getElementById(`${field}-preview`);
    preview.textContent = val;
    preview.style.left = '100px';
    preview.style.top = `${100 + Object.keys(dragItems).length * 40}px`;
    preview.style.display = 'block';
    document.getElementById(field).value = val;
  }

  let activeDrag = null, offsetX = 0, offsetY = 0;

  // Desktop
  document.addEventListener('mousedown', e => {
    for (let id in dragItems) {
      const el = document.getElementById(id);
      if (e.target === el) {
        activeDrag = el;
        const rect = el.getBoundingClientRect();
        offsetX = e.clientX - rect.left;
        offsetY = e.clientY - rect.top;
      }
    }
  });

  document.addEventListener('mousemove', e => {
    if (!activeDrag) return;
    const wrapper = document.getElementById('pdf-wrapper').getBoundingClientRect();
    const x = e.clientX - wrapper.left - offsetX;
    const y = e.clientY - wrapper.top - offsetY;
    activeDrag.style.left = `${x}px`;
    activeDrag.style.top = `${y}px`;
  });

  document.addEventListener('mouseup', () => {
    if (!activeDrag) return;
    const id = activeDrag.id;
    const field = dragItems[id];
    const x = parseFloat(activeDrag.style.left);
    const y = parseFloat(activeDrag.style.top);
    document.getElementById(field.xId).value = x;
    document.getElementById(field.yId).value = y;
    activeDrag = null;
  });

  // Mobile
  document.addEventListener('touchstart', function (e) {
    for (let id in dragItems) {
      const el = document.getElementById(id);
      if (e.target === el) {
        activeDrag = el;
        const rect = el.getBoundingClientRect();
        const touch = e.touches[0];
        offsetX = touch.clientX - rect.left;
        offsetY = touch.clientY - rect.top;
      }
    }
  });

  document.addEventListener('touchmove', function (e) {
    if (!activeDrag) return;
    e.preventDefault();
    const wrapper = document.getElementById('pdf-wrapper').getBoundingClientRect();
    const touch = e.touches[0];
    const x = touch.clientX - wrapper.left - offsetX;
    const y = touch.clientY - wrapper.top - offsetY;
    activeDrag.style.left = `${x}px`;
    activeDrag.style.top = `${y}px`;
  }, { passive: false });

  document.addEventListener('touchend', function () {
    if (!activeDrag) return;
    const id = activeDrag.id;
    const field = dragItems[id];
    const x = parseFloat(activeDrag.style.left);
    const y = parseFloat(activeDrag.style.top);
    document.getElementById(field.xId).value = x;
    document.getElementById(field.yId).value = y;
    activeDrag = null;
  });
</script>

</body>
</html>
