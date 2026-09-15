<?php
$page = [
  'title'       => 'Website Designing Services | Eformics Systems',
  'description' => 'Eformics Systems designs modern, responsive, conversion-focused websites for businesses that want more than a template.',
  'canonical'   => '/services/web-design.php',
  'section'     => 'services',
];
require __DIR__ . '/../partials/head.php';
?>

<?php $ph = page_hero('web-design'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / Services / Website Designing</div>
      <span class="pill">Web Design</span>
      <h1 style="margin-top:16px;max-width:18ch;">Designing impressions, crafting experiences.</h1>
      <p class="lede">Our team of designers is dedicated to crafting visually appealing websites that leave a lasting impression &mdash; combining artistic design with user-friendly layouts, so your site looks fantastic and functions seamlessly.</p>
      <div class="hero-cta">
        <a href="<?= url('/contact.php') ?>" class="btn btn-primary">Get started</a>
        <?php if (page_is_published('portfolio')): ?><a href="<?= url('/portfolio.php') ?>" class="btn btn-outline">See our work</a><?php endif; ?>
        <a href="#included" class="btn btn-outline">What's included</a>
      </div>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>

<?php
/* Featured-websites slider — managed in the admin (Portfolio) */
$wdSliderOn = setting('wd_slider_enabled', '1') === '1';
$wdFeatured = $wdSliderOn ? portfolio_featured() : [];
if ($wdFeatured):
  $wdSpv      = max(1, min(3, (int) setting('wd_slider_per_view', '3')));
  $wdAuto     = setting('wd_slider_autoplay', '1') === '1';
  $wdInterval = max(2, min(30, (int) setting('wd_slider_interval', '4')));
  $wdHeading  = setting('wd_slider_heading', 'Recent work');
  $wdIntro    = setting('wd_slider_intro', '');
?>
<section class="wd-slider-section">
  <div class="container">
    <?php if (trim($wdHeading) !== '' || trim($wdIntro) !== ''): ?>
    <div class="section-head reveal" style="margin-bottom:30px;">
      <span class="eyebrow">Portfolio</span>
      <?php if (trim($wdHeading) !== ''): ?><h2><?= e($wdHeading) ?></h2><?php endif; ?>
      <?php if (trim($wdIntro) !== ''): ?><p class="lede"><?= e($wdIntro) ?></p><?php endif; ?>
    </div>
    <?php endif; ?>

    <div class="wd-slider reveal" data-autoplay="<?= $wdAuto ? '1' : '0' ?>" data-interval="<?= $wdInterval * 1000 ?>" style="--wd-spv:<?= $wdSpv ?>;">
      <button class="wd-arrow wd-prev" type="button" aria-label="Previous">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m15 18-6-6 6-6"/></svg>
      </button>
      <div class="wd-viewport">
        <div class="wd-track">
          <?php foreach ($wdFeatured as $p): ?>
            <?php $link = trim($p['website_url']) !== ''; $alt = $p['image_alt'] !== '' ? $p['image_alt'] : $p['title']; ?>
            <?php if ($link): ?><a class="wd-slide" href="<?= e($p['website_url']) ?>" target="_blank" rel="noopener"><?php else: ?><div class="wd-slide"><?php endif; ?>
              <div class="wd-shot">
                <?php if ($p['image_path'] !== ''): ?><img src="<?= e($p['image_path']) ?>" alt="<?= e($alt) ?>" loading="lazy"><?php endif; ?>
              </div>
              <div class="wd-cap">
                <strong><?= e($p['title']) ?></strong>
                <?php if ($p['client_name'] !== ''): ?><span><?= e($p['client_name']) ?></span><?php endif; ?>
                <?php if ($link): ?><span class="wd-visit">Visit site
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
                </span><?php endif; ?>
              </div>
            <?= $link ? '</a>' : '</div>' ?>
          <?php endforeach; ?>
        </div>
      </div>
      <button class="wd-arrow wd-next" type="button" aria-label="Next">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>
      </button>
    </div>
  </div>
</section>

<script>
(function () {
  var root = document.querySelector('.wd-slider');
  if (!root) return;
  var viewport = root.querySelector('.wd-viewport');
  var track    = root.querySelector('.wd-track');
  var slides   = Array.prototype.slice.call(track.children);
  var N        = slides.length;
  var GAP      = 22;
  var autoplay = root.getAttribute('data-autoplay') === '1';
  var interval = parseInt(root.getAttribute('data-interval'), 10) || 4000;

  function stepPx() { return track.children[0].getBoundingClientRect().width + GAP; }
  function perView() { return Math.max(1, Math.round((viewport.clientWidth + GAP) / stepPx())); }

  if (N <= perView()) {
    root.querySelectorAll('.wd-arrow').forEach(function (a) { a.hidden = true; });
    return;
  }

  var cloneCount = Math.min(3, N);
  for (var i = 0; i < cloneCount; i++) {
    var c = slides[i].cloneNode(true);
    c.setAttribute('aria-hidden', 'true');
    c.setAttribute('tabindex', '-1');
    track.appendChild(c);
  }

  var index = 0, animating = false, safety = null;

  function done() {
    animating = false;
    clearTimeout(safety);
    if (index >= N) { index = 0; apply(false); }
    else if (index < 0) { index = N - 1; apply(false); }
  }

  function apply(anim) {
    track.style.transition = anim ? 'transform .45s cubic-bezier(.22,.68,.36,1)' : 'none';
    track.style.transform  = 'translateX(' + (-index * stepPx()) + 'px)';
    if (anim) { clearTimeout(safety); safety = setTimeout(done, 600); }
  }
  apply(false);

  function next() {
    if (animating) return;
    animating = true; index++; apply(true);
  }
  function prev() {
    if (animating) return;
    animating = true;
    if (index === 0) { index = N; apply(false); void track.offsetWidth; index = N - 1; apply(true); }
    else { index--; apply(true); }
  }

  track.addEventListener('transitionend', function (e) {
    if (e.target !== track || e.propertyName !== 'transform') return;
    done();
  });

  var timer = null;
  function play() { if (autoplay && !timer && !document.hidden) timer = setInterval(next, interval); }
  function stop() { if (timer) { clearInterval(timer); timer = null; } }

  root.querySelector('.wd-next').addEventListener('click', function () { stop(); next(); play(); });
  root.querySelector('.wd-prev').addEventListener('click', function () { stop(); prev(); play(); });
  root.addEventListener('mouseenter', stop);
  root.addEventListener('mouseleave', play);
  root.addEventListener('focusin', stop);
  root.addEventListener('focusout', play);
  document.addEventListener('visibilitychange', function () { document.hidden ? stop() : play(); });

  var rt;
  window.addEventListener('resize', function () {
    clearTimeout(rt);
    rt = setTimeout(function () { apply(false); }, 150);
  });

  play();
})();
</script>
<?php endif; ?>

<section id="included">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">What's included</span>
      <h2>A website built to be found, and built to convert.</h2>
    </div>
    <div class="grid-3">
      <div class="card reveal"><h3>Custom design</h3><p>A layout and visual identity designed for your business, not a recycled template.</p></div>
      <div class="card reveal"><h3>Mobile-first &amp; responsive</h3><p>Built to work cleanly on phones, tablets and desktops from day one.</p></div>
      <div class="card reveal"><h3>SEO foundations</h3><p>Clean structure, fast load times and on-page SEO basics done right from the start.</p></div>
      <div class="card reveal"><h3>Conversion-focused layout</h3><p>Clear calls to action and page flow designed to turn visitors into leads.</p></div>
      <div class="card reveal"><h3>Content &amp; copy support</h3><p>Help shaping your messaging, not just the visuals around it.</p></div>
      <div class="card reveal"><h3>Ongoing support</h3><p>Updates, maintenance and improvements after launch, from the same team that built it.</p></div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="cta-band">
      <h2>Let's design something that fits your business.</h2>
      <p class="lede">Tell us about your business and who you're trying to reach &mdash; we'll take it from there. Or browse a few sites we've already built.</p>
      <div class="hero-cta">
        <a href="<?= url('/contact.php') ?>" class="btn btn-primary">Get started</a>
        <?php if (page_is_published('portfolio')): ?><a href="<?= url('/portfolio.php') ?>" class="btn btn-ghost">See our portfolio</a><?php endif; ?>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
