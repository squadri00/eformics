/* Eformics Admin — shared behaviour */
(function () {
  'use strict';

  var meta = document.querySelector('meta[name="csrf-token"]');
  var CSRF = meta ? meta.getAttribute('content') : '';
  var UPLOAD_URL = (document.body.getAttribute('data-admin-base') || '') + 'upload.php';

  /* ---- "Upload" buttons next to URL fields ---- */
  document.querySelectorAll('.upfield').forEach(function (wrap) {
    var text   = wrap.querySelector('input[type="text"]');
    var btn    = wrap.querySelector('.upfield-btn');
    var file   = wrap.querySelector('.upfield-file');
    var status = wrap.querySelector('.upfield-status');
    var kind   = wrap.getAttribute('data-kind') || 'image';
    if (!text || !btn || !file) return;

    btn.addEventListener('click', function () { file.click(); });

    file.addEventListener('change', function () {
      if (!file.files || !file.files[0]) return;
      var f = file.files[0];
      status.textContent = 'Uploading ' + f.name + '…';
      status.className = 'upfield-status';
      btn.disabled = true;

      var fd = new FormData();
      fd.append('file', f);
      fd.append('kind', kind);
      fd.append('csrf', CSRF);

      fetch(UPLOAD_URL, { method: 'POST', body: fd })
        .then(function (r) { return r.json().catch(function () { return { ok: false, error: 'Server error.' }; }); })
        .then(function (data) {
          if (data.ok && data.url) {
            text.value = data.url;
            text.dispatchEvent(new Event('input', { bubbles: true }));
            status.textContent = 'Uploaded ✓';
            status.className = 'upfield-status ok';
          } else {
            status.textContent = data.error || 'Upload failed.';
            status.className = 'upfield-status err';
          }
        })
        .catch(function () {
          status.textContent = 'Upload failed — network error.';
          status.className = 'upfield-status err';
        })
        .finally(function () {
          btn.disabled = false;
          file.value = '';
        });
    });
  });
})();
