<?php
$page = [
  'title'       => 'Restaurant Visual Enhancement — Food Photo & Video Editing | Eformics',
  'description' => 'We turn ordinary restaurant food photos into premium, menu-ready images and short animated videos for websites, delivery apps and social media.',
  'canonical'   => '/services/visual-enhancement.php',
  'section'     => 'services',
];
require __DIR__ . '/../partials/head.php';

$baItems  = va_ba_public();
$vidItems = va_vid_public();
$PER_PAGE = 4; // 2 columns x 2 rows
?>

<?php $ph = page_hero('visual-enhancement'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / <a href="<?= url('/') ?>#services">Services</a> / Visual Enhancement</div>
      <span class="pill">Restaurant photo &amp; video enhancement</span>
      <h1 style="margin-top:16px;max-width:22ch;">Your food already tastes amazing. Now make it look unforgettable.</h1>
      <p class="lede">We take your existing food photos and turn them into rich, menu-ready visuals &mdash; sharper light, deeper colour, cleaner composition &mdash; then bring them to life with subtle motion for digital menus, websites and social feeds.</p>
      <div class="hero-cta">
        <a href="<?= url('/contact.php') ?>#contact-form" class="btn btn-primary">Get a free sample</a>
        <a href="#before-after" class="btn btn-outline">See the difference</a>
      </div>
      <div class="hero-badges" style="margin-top:24px;">
        <span style="border-color:var(--line);color:var(--ink-soft);">2&ndash;3 day turnaround</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">Stills + short animated clips</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">1 free revision</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">Menus, apps &amp; social</span>
      </div>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Why it matters</span>
      <h2>People eat with their eyes first.</h2>
      <p class="lede">Listings with strong photography get noticeably more online orders. Whether you&rsquo;re a local caf&eacute; or a fine-dining room, better visuals make your dishes look irresistible &mdash; and turn casual scrollers into hungry customers.</p>
    </div>
    <div class="grid-3">
      <div class="card reveal"><h3>Enhanced stills</h3><p>Lighting, colour, sharpness and composition corrected so every dish looks its best &mdash; without looking fake or over-filtered.</p></div>
      <div class="card reveal"><h3>Subtle motion</h3><p>Rising steam, gentle highlights and soft background movement that make a dish pop in a crowded feed.</p></div>
      <div class="card reveal"><h3>On-brand</h3><p>Every image and clip is tuned to your restaurant&rsquo;s style &mdash; fine dining, casual or fast food.</p></div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">How it works</span>
      <h2>From your phone photo to menu-ready in three steps.</h2>
    </div>
    <div class="steps" style="grid-template-columns:repeat(3,1fr);">
      <div class="step reveal"><span class="num">01</span><h3>Send your photos</h3><p>Email us the dish photos you already have. No studio, no reshoot &mdash; your existing pictures are enough.</p></div>
      <div class="step reveal"><span class="num">02</span><h3>We enhance them</h3><p>Our team refines light, colour and texture, cleans up the frame, and adds gentle motion where it helps.</p></div>
      <div class="step reveal"><span class="num">03</span><h3>You get the files</h3><p>Menu-ready images and short animated clips, delivered in 2&ndash;3 business days with one free revision.</p></div>
    </div>
  </div>
</section>

<!-- BEFORE / AFTER GALLERY -->
<?php if ($baItems): ?>
<section id="before-after">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Before &amp; after</span>
      <h2>Same dish. Same photo. Different result.</h2>
    </div>

    <div class="va-grid" id="baGrid" data-per-page="<?= $PER_PAGE ?>">
      <?php foreach ($baItems as $p): ?>
      <figure class="va-card reveal">
        <div class="va-baslider" aria-label="<?= e($p['title'] !== '' ? $p['title'] . ' — drag to compare' : 'Before and after — drag to compare') ?>">
          <img class="va-ba-img" src="<?= e($p['after_path']) ?>" alt="<?= e($p['after_alt'] !== '' ? $p['after_alt'] : ('After: ' . $p['title'])) ?>" loading="lazy" draggable="false">
          <div class="va-ba-before"><img class="va-ba-img" src="<?= e($p['before_path']) ?>" alt="<?= e($p['before_alt'] !== '' ? $p['before_alt'] : ('Before: ' . $p['title'])) ?>" loading="lazy" draggable="false"></div>
          <span class="va-ba-tag va-ba-tag--before">Before</span>
          <span class="va-ba-tag va-ba-tag--after">After</span>
          <button type="button" class="va-ba-handle" role="slider" aria-label="Reveal the before image"
                  aria-valuemin="0" aria-valuemax="100" aria-valuenow="50"><span class="va-ba-grip" aria-hidden="true"></span></button>
        </div>
        <?php if ($p['title'] !== ''): ?><figcaption class="va-title"><?= e($p['title']) ?></figcaption><?php endif; ?>
      </figure>
      <?php endforeach; ?>
    </div>
    <nav class="va-pager" data-for="baGrid" hidden></nav>
  </div>
</section>
<?php endif; ?>

<!-- VIDEO GALLERY -->
<?php if ($vidItems): ?>
<section style="background:var(--lavender);" id="video-portfolio">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">In motion</span>
      <h2>Short clips that stop the scroll.</h2>
      <p class="lede">Muted, looping animations built for digital menus, delivery apps and social. Tap any clip for sound.</p>
    </div>

    <div class="va-grid" id="vidGrid" data-per-page="<?= $PER_PAGE ?>">
      <?php foreach ($vidItems as $p): $emb = va_video_embed($p); if ($emb['type'] === '') { continue; } ?>
      <figure class="va-card va-card--video reveal">
        <div class="va-video">
          <?php if ($emb['type'] === 'file'): ?>
            <video src="<?= e($emb['src']) ?>" muted loop playsinline preload="metadata"
                   <?= $emb['poster'] !== '' ? 'poster="' . e($emb['poster']) . '"' : '' ?>
                   data-va-video></video>
            <button type="button" class="va-sound" aria-label="Toggle sound">🔊</button>
          <?php else: ?>
            <iframe data-src="<?= e($emb['src']) ?>" title="<?= e($p['title']) ?>" loading="lazy"
                    allow="autoplay; encrypted-media; picture-in-picture" allowfullscreen data-va-iframe></iframe>
          <?php endif; ?>
        </div>
        <?php if ($p['title'] !== ''): ?><figcaption class="va-title"><?= e($p['title']) ?></figcaption><?php endif; ?>
      </figure>
      <?php endforeach; ?>
    </div>
    <nav class="va-pager" data-for="vidGrid" hidden></nav>
  </div>
</section>
<?php endif; ?>

<!-- FREE TRIAL -->
<section id="free-sample">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Try it free</span>
      <h2>Send two dish photos. We&rsquo;ll transform them &mdash; no charge.</h2>
      <p class="lede">Pick one or two photos from your current menu. We&rsquo;ll enhance them and turn one into a short animated clip so you can see the difference on your own dishes, with no obligation.</p>
    </div>
    <div class="cta-band reveal">
      <h2>Get your free sample</h2>
      <p class="lede">Tell us about your restaurant and attach a couple of photos &mdash; we&rsquo;ll send the enhanced versions back within a few days.</p>
      <div class="hero-cta"><a href="<?= url('/contact.php') ?>#contact-form" class="btn btn-primary">Request my free sample</a></div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section style="background:var(--lavender);">
  <div class="container" style="max-width:820px;">
    <div class="section-head reveal">
      <span class="eyebrow">FAQ</span>
      <h2>Questions restaurant owners ask.</h2>
    </div>
    <div class="va-faq">
      <details open><summary>Can you improve my existing food photos?</summary><p>Yes. We enhance your current images by adjusting lighting, sharpness and colour tones and adding subtle cinematic effects to make them more appetising &mdash; without a reshoot.</p></details>
      <details><summary>Will my food look fake or over-edited?</summary><p>No. We preserve the real textures, lighting and colours of your dishes. The goal is that what customers see is what they get &mdash; just presented at its best.</p></details>
      <details><summary>What proof is there that this helps?</summary><p>Listings with high-quality images consistently get more online orders and engagement, especially on delivery apps and Instagram. No service can guarantee sales, but strong visuals make your brand look more trustworthy and appetising.</p></details>
      <details><summary>Do you offer revisions?</summary><p>Every project includes one free revision so you&rsquo;re happy with the result. We&rsquo;ll also advise on how to capture better source photos if needed.</p></details>
      <details><summary>How long does it take?</summary><p>Enhanced images and short clips are typically delivered within 2&ndash;3 business days, depending on how many items you submit.</p></details>
      <details><summary>Will the visuals match my brand?</summary><p>Yes. We tailor every image and clip to your restaurant&rsquo;s theme, colours and atmosphere &mdash; whether that&rsquo;s fine dining, casual or fast food.</p></details>
      <details><summary>Where can I use the final files?</summary><p>Your website, Instagram, Facebook, Uber Eats, DoorDash, printed menus and digital ads &mdash; anywhere you want to grab attention.</p></details>
      <details><summary>What do you need from me to start?</summary><p>Just one or two existing photos of the dishes you want enhanced, and a note about your restaurant&rsquo;s style.</p></details>
      <details><summary>What does it cost?</summary><p>Pricing depends on how many items you need done. Send us your list and we&rsquo;ll come back with a quote &mdash; and your free sample first.</p></details>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="cta-band reveal">
      <h2>Make every dish look worth ordering.</h2>
      <p class="lede">Start with two free enhanced photos of your own food &mdash; then decide.</p>
      <div class="hero-cta"><a href="<?= url('/contact.php') ?>#contact-form" class="btn btn-primary">Get a free sample</a></div>
    </div>
  </div>
</section>

<script>
(function () {
  /* Simple client-side pager: chunks a .va-grid into pages of data-per-page. */
  function pager(grid) {
    var per  = parseInt(grid.getAttribute('data-per-page'), 10) || 4;
    var nav  = document.querySelector('.va-pager[data-for="' + grid.id + '"]');
    var cards = Array.prototype.slice.call(grid.children);
    if (cards.length <= per) { activate(cards); return; }

    var pages = Math.ceil(cards.length / per);
    var cur = 0;

    function stop(card) {
      card.querySelectorAll('video[data-va-video]').forEach(function (v) { try { v.pause(); } catch (e) {} });
      card.querySelectorAll('iframe[data-va-iframe]').forEach(function (f) { if (f.src) f.removeAttribute('src'); });
    }
    function play(card) {
      card.querySelectorAll('video[data-va-video]').forEach(function (v) { var p = v.play(); if (p && p.catch) p.catch(function () {}); });
      card.querySelectorAll('iframe[data-va-iframe]').forEach(function (f) { if (!f.src) f.src = f.getAttribute('data-src'); });
    }
    function activate(list) { list.forEach(play); }

    function show(n) {
      cur = Math.max(0, Math.min(pages - 1, n));
      cards.forEach(function (c, i) {
        var on = i >= cur * per && i < (cur + 1) * per;
        c.hidden = !on;
        on ? play(c) : stop(c);
      });
      build();
      grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
    function btn(label, target, opts) {
      var b = document.createElement('button');
      b.type = 'button';
      b.textContent = label;
      if (opts && opts.current) b.setAttribute('aria-current', 'page');
      if (opts && opts.disabled) b.disabled = true;
      b.addEventListener('click', function () { show(target); });
      return b;
    }
    function build() {
      nav.innerHTML = '';
      nav.appendChild(btn('‹ Prev', cur - 1, { disabled: cur === 0 }));
      for (var i = 0; i < pages; i++) nav.appendChild(btn(String(i + 1), i, { current: i === cur }));
      nav.appendChild(btn('Next ›', cur + 1, { disabled: cur === pages - 1 }));
      nav.hidden = false;
    }
    show(0);
  }

  document.querySelectorAll('.va-grid').forEach(pager);

  /* Before / after drag slider. Position is kept as a 0–1 fraction, so it
     stays correct through pagination and window resizes. */
  function initBaSlider(el) {
    var before = el.querySelector('.va-ba-before');
    var handle = el.querySelector('.va-ba-handle');
    if (!before || !handle) return;
    var pos = 0.5, dragging = false;

    function apply() {
      var pct = (pos * 100).toFixed(2);
      before.style.clipPath = 'inset(0 ' + (100 - pct) + '% 0 0)';
      handle.style.left = pct + '%';
      handle.setAttribute('aria-valuenow', Math.round(pos * 100));
    }
    function fromX(clientX) {
      var r = el.getBoundingClientRect();
      if (!r.width) return;
      pos = Math.min(1, Math.max(0, (clientX - r.left) / r.width));
      apply();
    }
    el.addEventListener('pointerdown', function (e) {
      dragging = true;
      try { el.setPointerCapture(e.pointerId); } catch (x) {}
      fromX(e.clientX);
      e.preventDefault();
    });
    el.addEventListener('pointermove', function (e) { if (dragging) fromX(e.clientX); });
    el.addEventListener('pointerup', function () { dragging = false; });
    el.addEventListener('pointercancel', function () { dragging = false; });
    handle.addEventListener('keydown', function (e) {
      var step = e.shiftKey ? 0.1 : 0.02;
      if (e.key === 'ArrowLeft')  { pos = Math.max(0, pos - step); apply(); e.preventDefault(); }
      if (e.key === 'ArrowRight') { pos = Math.min(1, pos + step); apply(); e.preventDefault(); }
      if (e.key === 'Home')       { pos = 0; apply(); e.preventDefault(); }
      if (e.key === 'End')        { pos = 1; apply(); e.preventDefault(); }
    });
    apply();
  }
  document.querySelectorAll('.va-baslider').forEach(initBaSlider);

  /* Per-video unmute toggle for uploaded clips. */
  document.querySelectorAll('.va-sound').forEach(function (b) {
    b.addEventListener('click', function () {
      var v = b.parentElement.querySelector('video[data-va-video]');
      if (!v) return;
      v.muted = !v.muted;
      b.textContent = v.muted ? '🔊' : '🔈';
      if (!v.muted) { var p = v.play(); if (p && p.catch) p.catch(function () {}); }
    });
  });
})();
</script>

<?php require __DIR__ . '/../partials/footer.php'; ?>
