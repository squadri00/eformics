<?php
$page = [
  'title'       => 'Recodik — Free, Self-Hosted Record Keeper | Eformics Systems',
  'description' => 'Recodik is a free, self-hosted, no-code record keeper you fully customize — build your own categories and fields for customers, passwords, domains, subscriptions, anything. No license, no subscription, ever.',
  'canonical'   => '/products/recodik.php',
  'section'     => 'products',
];
require __DIR__ . '/../partials/head.php';
require_once __DIR__ . '/../partials/recodik.php';

$countries = recodik_countries();
?>

<?php $ph = page_hero('products/recodik'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / <a href="<?= url('/') ?>#products">Products</a> / Recodik</div>
      <span class="pill">100% free &middot; no license &middot; self-hosted</span>
      <h1 style="margin-top:16px;max-width:20ch;">A record keeper you build yourself &mdash; free, forever.</h1>
      <p class="lede">Recodik is a no-code app for keeping anything organized &mdash; customers, passwords, domains, subscriptions, whatever you need &mdash; with categories and fields <em>you</em> define. It runs on your own server or your own PC. No subscription, no license fee, no catch.</p>
      <div class="hero-cta">
        <a href="#download" class="btn btn-primary">Get your free download</a>
        <a href="<?= url('/contact.php') ?>#contact-form" class="btn btn-outline">Ask us a question</a>
      </div>
      <div class="hero-badges" style="margin-top:24px;">
        <span style="border-color:var(--line);color:var(--ink-soft);">Free to download &amp; use</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">No license required</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">Self-hosted &mdash; your data stays yours</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">No telemetry, ever</span>
      </div>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">What it is</span>
      <h2>Most record-keeping apps assume they know what you want to store. Recodik doesn't.</h2>
      <p class="lede">You decide what a "record" is. Create a category &mdash; Customers, Domains, Passwords, Equipment, anything &mdash; then add exactly the fields it needs: text, numbers, dates, encrypted passwords, file attachments, links to other records, and more. Nothing about it is hardcoded.</p>
    </div>
    <div class="grid-3">
      <div class="card reveal"><h3>Build your own structure</h3><p>14 field types &mdash; text, numbers, dates, dropdowns, linked records and more &mdash; combine into whatever shape your data actually needs.</p></div>
      <div class="card reveal"><h3>Sensitive fields, encrypted</h3><p>Mark any field as a password. It's encrypted at rest and only ever decrypted for a moment when you click Reveal, with your own master password.</p></div>
      <div class="card reveal"><h3>Self-hosted &amp; private</h3><p>Runs on your own server or your own PC. No outbound calls, no analytics, no telemetry &mdash; your data never leaves your control.</p></div>
      <div class="card reveal"><h3>Connect your data</h3><p>Link records across categories (a Project to its Customer), or tag anything, in any category, with a free-form label to group it your way.</p></div>
      <div class="card reveal"><h3>Reminders &amp; a calendar</h3><p>Any date field can warn you as it approaches &mdash; a domain renewal, an invoice due date &mdash; and every date across every category shows up on one calendar.</p></div>
      <div class="card reveal"><h3>Search, sort, filter, backup</h3><p>Full-text search across everything you've stored, sortable/filterable lists, one-click backup and restore, and a full activity log of who changed what.</p></div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">How it works</span>
      <h2>Verify your email, download, and you're building in minutes.</h2>
    </div>
    <div class="steps" style="grid-template-columns:repeat(3,1fr);">
      <div class="step reveal"><span class="num">01</span><h3>Verify your email</h3><p>Enter your email and country below &mdash; we send a 6-digit code so we know the download went to a real inbox. That's it, no account, no card.</p></div>
      <div class="step reveal"><span class="num">02</span><h3>Download &amp; run it</h3><p>A single Windows program &mdash; double-click it and it opens in your browser. (Prefer Docker or Linux? <a href="<?= url('/contact.php') ?>#contact-form">ask us</a> for the self-hosted server build.)</p></div>
      <div class="step reveal"><span class="num">03</span><h3>Build your first category</h3><p>Create a category, add a few fields, add your first record. That's the whole idea &mdash; everything else is built on top of that.</p></div>
    </div>
  </div>
</section>

<section id="download">
  <div class="container" style="max-width:640px;">
    <div class="section-head reveal" style="text-align:center;">
      <span class="eyebrow">Free download</span>
      <h2>Get Recodik &mdash; free, no license.</h2>
      <p class="lede">We just need an email to send your download link's verification code to, and your country so we know where Recodik is being used. We won't use it for anything else — see our <a href="<?= url('/privacy-policy.php') ?>">Privacy Policy</a>.</p>
    </div>

    <div class="card reveal" style="padding:32px;">
      <form id="recodikRequestForm" style="display:grid;gap:16px;">
        <div class="hp" aria-hidden="true">
          <input type="text" name="website" tabindex="-1" autocomplete="off" value="">
        </div>
        <div>
          <label for="rk-email" style="display:block;font-size:.86rem;font-weight:600;margin-bottom:6px;">Email address</label>
          <input id="rk-email" name="email" type="email" required autocomplete="email" style="width:100%;padding:13px 14px;border:1.5px solid var(--line);border-radius:10px;font:inherit;">
        </div>
        <div>
          <label for="rk-country" style="display:block;font-size:.86rem;font-weight:600;margin-bottom:6px;">Country</label>
          <select id="rk-country" name="country" required style="width:100%;padding:13px 14px;border:1.5px solid var(--line);border-radius:10px;font:inherit;background:var(--surface);color:var(--ink);">
            <option value="" disabled selected>Choose your country&hellip;</option>
            <?php foreach ($countries as $c): ?>
              <option value="<?= e($c) ?>"><?= e($c) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary" id="recodikSendBtn">Email me a code</button>
        <div id="recodikRequestStatus" class="form-status" role="status" aria-live="polite" hidden></div>
      </form>

      <form id="recodikVerifyForm" style="display:none;gap:16px;margin-top:8px;" hidden>
        <p style="font-size:.9rem;color:var(--ink-soft);margin:0 0 4px;">Enter the 6-digit code we emailed to <strong id="recodikEmailLabel"></strong>.</p>
        <div>
          <label for="rk-code" style="display:block;font-size:.86rem;font-weight:600;margin-bottom:6px;">Verification code</label>
          <input id="rk-code" name="code" type="text" inputmode="numeric" pattern="\d{6}" maxlength="6" autocomplete="one-time-code" required style="width:100%;padding:13px 14px;border:1.5px solid var(--line);border-radius:10px;font:inherit;letter-spacing:.3em;text-align:center;font-size:1.2rem;">
        </div>
        <button type="submit" class="btn btn-primary" id="recodikVerifyBtn">Verify &amp; download</button>
        <button type="button" class="btn btn-ghost btn-sm" id="recodikResendBtn" style="justify-self:start;">Send a new code</button>
        <div id="recodikVerifyStatus" class="form-status" role="status" aria-live="polite" hidden></div>
      </form>

      <div id="recodikDownloadReady" style="display:none;text-align:center;padding:12px 0;">
        <p style="font-weight:600;margin-bottom:14px;">You're verified &mdash; your download should start automatically.</p>
        <a href="#" id="recodikDownloadLink" class="btn btn-primary">Download Recodik.exe again</a>
      </div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container" style="max-width:760px;">
    <div class="section-head reveal">
      <span class="eyebrow">Good to know</span>
      <h2>A few honest answers.</h2>
    </div>
    <div class="grid-2">
      <div class="card reveal"><h3>Why is it free?</h3><p>Recodik started as a tool we built for our own use. We're sharing it as-is, free, with no license — no catch, no upsell inside the app.</p></div>
      <div class="card reveal"><h3>Is my data private?</h3><p>Completely. Recodik runs on your own server or PC and makes no outbound calls of any kind — no analytics, no telemetry. Nothing you store in it is ever sent to us or anyone else.</p></div>
      <div class="card reveal"><h3>Why verify my email first?</h3><p>Just to confirm the download reaches a real inbox and to keep a rough count of where Recodik is being used. We don't require an account, a password, or any payment details.</p></div>
      <div class="card reveal"><h3>What if I need help?</h3><p><a href="<?= url('/contact.php') ?>#contact-form">Contact us</a> any time — and the app itself has a full, plain-English Help guide built in once you're set up.</p></div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="cta-band reveal">
      <h2>Free to download. Free to keep.</h2>
      <p class="lede">No license, no subscription, no expiry date.</p>
      <div class="hero-cta">
        <a href="#download" class="btn btn-primary">Get your free download</a>
      </div>
    </div>
  </div>
</section>

<script>
(function () {
  var reqForm   = document.getElementById('recodikRequestForm');
  var verForm   = document.getElementById('recodikVerifyForm');
  var readyBox  = document.getElementById('recodikDownloadReady');
  var reqStatus = document.getElementById('recodikRequestStatus');
  var verStatus = document.getElementById('recodikVerifyStatus');
  var emailLbl  = document.getElementById('recodikEmailLabel');
  var resendBtn = document.getElementById('recodikResendBtn');
  var dlLink    = document.getElementById('recodikDownloadLink');
  if (!reqForm || !verForm) return;

  var ACTION_URL = '<?= url('/partials/recodik-otp-handler.php') ?>';
  var currentEmail = '';

  function showStatus(el, msg, ok) {
    if (!el) return;
    el.textContent = msg;
    el.className = 'form-status ' + (ok ? 'is-ok' : 'is-err');
    el.hidden = false;
  }

  function post(action, fields) {
    var fd = new FormData();
    fd.append('action', action);
    Object.keys(fields).forEach(function (k) { fd.append(k, fields[k]); });
    return fetch(ACTION_URL, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
      .then(function (res) { return res.json().catch(function () { return {}; }).then(function (data) { return { ok: res.ok, data: data }; }); });
  }

  reqForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var email   = reqForm.email.value.trim();
    var country = reqForm.country.value;
    var website = reqForm.website.value; // honeypot
    var btn = document.getElementById('recodikSendBtn');
    btn.disabled = true; var label = btn.textContent; btn.textContent = 'Sending…';

    post('request', { email: email, country: country, website: website })
      .then(function (r) {
        if (r.ok && r.data.ok) {
          currentEmail = email;
          emailLbl.textContent = email;
          showStatus(reqStatus, r.data.message || 'Code sent.', true);
          reqForm.style.display = 'none';
          verForm.hidden = false;
          verForm.style.display = 'grid';
          document.getElementById('rk-code').focus();
        } else {
          showStatus(reqStatus, (r.data && r.data.error) || 'Something went wrong. Please try again.', false);
        }
      })
      .catch(function () { showStatus(reqStatus, 'Network error — please try again.', false); })
      .finally(function () { btn.disabled = false; btn.textContent = label; });
  });

  verForm.addEventListener('submit', function (e) {
    e.preventDefault();
    var code = verForm.code.value.trim();
    var btn = document.getElementById('recodikVerifyBtn');
    btn.disabled = true; var label = btn.textContent; btn.textContent = 'Verifying…';

    post('verify', { email: currentEmail, code: code })
      .then(function (r) {
        if (r.ok && r.data.ok) {
          verForm.style.display = 'none';
          readyBox.style.display = 'block';
          dlLink.href = r.data.download_url;
          window.location.href = r.data.download_url;
        } else {
          showStatus(verStatus, (r.data && r.data.error) || 'That code did not work. Please try again.', false);
        }
      })
      .catch(function () { showStatus(verStatus, 'Network error — please try again.', false); })
      .finally(function () { btn.disabled = false; btn.textContent = label; });
  });

  resendBtn.addEventListener('click', function () {
    resendBtn.disabled = true;
    post('request', { email: currentEmail, country: reqForm.country.value, website: '' })
      .then(function (r) {
        showStatus(verStatus, r.ok && r.data.ok ? 'A new code is on its way.' : ((r.data && r.data.error) || 'Could not resend right now.'), !!(r.ok && r.data.ok));
      })
      .finally(function () { setTimeout(function () { resendBtn.disabled = false; }, 3000); });
  });
})();
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
