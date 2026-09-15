<?php
$page = [
  'title'       => 'Eformics Systems — Software, Websites & Digital Products',
  'description' => 'Eformics Systems builds SaaS products (Meccora, Quotaire, Chantley) and custom software & websites for businesses. Based in Mississauga, Ontario, since 2009.',
  'canonical'   => '/',
  'section'     => 'home',
];
require __DIR__ . '/partials/head.php';
?>

<section class="hero">
  <div class="container hero-center">
    <span class="eyebrow">Since 2009 &middot; Mississauga, Ontario</span>
    <h1>Software, websites, and&nbsp;products<br>that help businesses run better.</h1>
    <p class="lede">Eformics Systems builds two kinds of things: SaaS products used by businesses every day, and custom software &amp; websites built around how our clients actually work.</p>
    <div class="hero-badges">
      <span>3 products in market</span>
      <span>Custom development</span>
      <span>Website design</span>
      <span>16 years building software</span>
    </div>
    <div class="hero-cta">
      <a href="<?= url('/contact.php') ?>" class="btn btn-primary">Talk to us about your project</a>
      <a href="#products" class="btn btn-ghost">See our products</a>
    </div>

    <?php $homeVideo = homepage_video_html(); ?>
    <div class="hero-video reveal<?= $homeVideo !== '' ? ' has-media' : '' ?>">
      <?php if ($homeVideo !== ''): ?>
        <?= $homeVideo ?>
      <?php else: ?>
        <button class="play-btn" type="button" aria-label="Play video">
          <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
        </button>
        <span class="hero-video-label"><?= e(setting('home_video_label', 'See Eformics in 90 seconds')) ?></span>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php
$pubDev    = page_is_published('services/development');
$pubWebDes = page_is_published('services/web-design');

/* Homepage product cards — data-driven. Add / remove a product here (or
   toggle it in Admin -> SEO) and the grid reflows automatically. Column
   count follows how many are published:
     1 -> single card   2 -> 2-up   3 -> 3-up   4 -> 2 + 2   5+ -> 3-up  */
$productCards = array_filter([
  'products/meccora' => [
    'label' => 'For auto repair shops',
    'name'  => 'Meccora',
    'href'  => '/products/meccora.php',
    'desc'  => 'Automated service reminders, full vehicle history and deferred-work recovery, so more customers come back instead of drifting to another shop.',
    'stats' => [['$60/mo', 'Starting price'], ['3', 'Reminder touches per service']],
  ],
  'products/quotaire' => [
    'label' => 'For any business that quotes',
    'name'  => 'Quotaire',
    'href'  => '/products/quotaire.php',
    'desc'  => 'Turn a price list into a live quote calculator customers can use on your website &mdash; or your team can use internally &mdash; in four simple steps.',
    'stats' => [['DIY / DFY', 'Build it or we build it'], ['4 steps', 'Price list to live calculator']],
  ],
  'products/chantley' => [
    'label' => 'For small business websites',
    'name'  => 'Chantley',
    'href'  => '/products/chantley.php',
    'desc'  => 'Turns your website content and documents into an AI chatbot that answers customer questions 24/7 &mdash; no coding, live the same day.',
    'stats' => [['$29/mo', 'Starting price'], ['3 steps', 'Content to live chatbot']],
  ],
  'products/smart-qr-menu' => [
    'label' => 'For restaurants &amp; caf&eacute;s',
    'name'  => 'Smart QR Menu',
    'href'  => '/products/smart-qr-menu.php',
    'desc'  => 'A fully managed digital menu &mdash; guests scan, your menu opens instantly with photos and prices, and our team keeps it updated for you. No printing, no apps.',
    'stats' => [['3&ndash;5 days', 'To go live'], ['Unlimited', 'Updates all year']],
  ],
], 'page_is_published', ARRAY_FILTER_USE_KEY);
$pcount = count($productCards);
?>
<?php if ($pcount): ?>
<section id="products">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Our products</span>
      <h2>Products built around real business problems.</h2>
      <p class="lede">Built by Eformics, run as standalone SaaS &mdash; each one solves one job well instead of trying to do everything.</p>
    </div>

    <div class="products-grid" data-count="<?= $pcount ?>">
      <?php foreach ($productCards as $p): ?>
      <div class="product-card reveal">
        <span class="plabel"><?= $p['label'] ?></span>
        <h3><?= e($p['name']) ?></h3>
        <p><?= $p['desc'] ?></p>
        <div class="pstats">
          <?php foreach ($p['stats'] as [$big, $small]): ?>
          <div><strong><?= $big ?></strong><?= e($small) ?></div>
          <?php endforeach; ?>
        </div>
        <a href="<?= url($p['href']) ?>" class="plink">See <?= e($p['name']) ?> <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php if ($pubDev || $pubWebDes): ?>
<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Our services</span>
      <h2>When off-the-shelf isn't the answer, we build it around you.</h2>
      <p class="lede">Alongside our own products, our team designs and builds custom software and websites for clients directly.</p>
    </div>
    <div class="grid-2">
      <?php if ($pubDev): ?>
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 4 4 8l4 4M16 4l4 4-4 4M14 4l-4 16"/></svg></div>
        <h3>Custom Development</h3>
        <p>Business applications built to automate a specific workflow &mdash; customer portals, internal tools and management systems designed around your process, not a generic template.</p>
        <a href="<?= url('/services/development.php') ?>" class="plink">Learn more <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>
      <?php endif; ?>
      <?php if ($pubWebDes): ?>
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 5h18M3 12h18M3 19h11"/></svg></div>
        <h3>Website Designing</h3>
        <p>Modern, fast-loading websites that combine clean design with layouts built to convert visitors into leads &mdash; not just look good in a portfolio.</p>
        <a href="<?= url('/services/web-design.php') ?>" class="plink">Learn more <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<section>
  <div class="container">
    <div class="stats-band reveal">
      <div class="stats-grid">
        <div><strong>2009</strong><span>Founded</span></div>
        <div><strong>3</strong><span>Products in market</span></div>
        <div><strong>2</strong><span>Core services</span></div>
        <div><strong>16+</strong><span>Years building software</span></div>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Why Eformics</span>
      <h2>We build our own products &mdash; so we know what shipping software actually takes.</h2>
    </div>
    <div class="grid-3">
      <div class="card reveal">
        <h3>We use what we build</h3>
        <p>Meccora, Quotaire and Chantley are our own products, run day to day. That means client work is done by a team that ships and maintains real software, not just design mockups.</p>
      </div>
      <div class="card reveal">
        <h3>Practical over flashy</h3>
        <p>We design around how a business actually operates day to day &mdash; solutions built to be used, not just demoed once and forgotten.</p>
      </div>
      <div class="card reveal">
        <h3>One team, start to finish</h3>
        <p>From the first conversation to design, build and ongoing support &mdash; the same team stays with your project.</p>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="cta-band reveal">
      <h2>Let's build something great together.</h2>
      <p class="lede">Whether you need a customer portal, a business management system or a completely custom web application, we're ready to help turn your idea into reliable software.</p>
      <div class="hero-cta">
        <a href="<?= url('/contact.php') ?>" class="btn btn-primary">Get started</a>
        <a href="<?= url('/about.php') ?>" class="btn btn-ghost">Who we are</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
