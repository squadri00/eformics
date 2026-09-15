<?php
$page = [
  'title'       => 'Smart QR Menu — Live Digital Menus for Restaurants | Eformics',
  'description' => 'A fully managed digital menu for restaurants. Customers scan, your menu opens instantly with photos and prices, and our team keeps it updated for you — no printing, no apps, one flat yearly price.',
  'canonical'   => '/products/smart-qr-menu.php',
  'section'     => 'products',
];
require __DIR__ . '/../partials/head.php';

/* Live demo menu — change this to your real demo URL. */
$demoUrl = 'https://eformics.com/web/ef2026/';
?>

<?php $ph = page_hero('smart-qr-menu'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / <a href="<?= url('/') ?>#products">Products</a> / Smart QR Menu</div>
      <span class="pill">Fully managed digital menus for restaurants</span>
      <h1 style="margin-top:16px;max-width:20ch;">Your menu, live in minutes &mdash; and always up to date.</h1>
      <p class="lede">Customers scan one code and see your real menu: photos, descriptions, current prices. You email us a change; it&rsquo;s live within hours. No reprints, no apps, no clumsy PDFs.</p>
      <div class="hero-cta">
        <a href="<?= url('/contact.php') ?>#contact-form" class="btn btn-primary">Get a quote for my restaurant</a>
        <a href="<?= e($demoUrl) ?>" target="_blank" rel="noopener" class="btn btn-outline">Scan a live demo</a>
      </div>
      <div class="hero-badges" style="margin-top:24px;">
        <span style="border-color:var(--line);color:var(--ink-soft);">Ready in 3&ndash;5 business days</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">Unlimited updates all year</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">One flat yearly price</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">No app to install</span>
      </div>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">The problem</span>
      <h2>Paper menus and PDF QR codes are quietly costing you.</h2>
      <p class="lede">Every price change means a reprint or a menu that&rsquo;s simply wrong. And a PDF behind a QR code loads slowly, needs pinch-and-zoom, and still sends guests back to the server with questions.</p>
    </div>
    <div class="grid-3">
      <div class="card reveal"><h3>Reprints add up</h3><p>New dish, new price, an 86&rsquo;d item &mdash; each change is a design fee and a print run, or a menu customers can&rsquo;t trust.</p></div>
      <div class="card reveal"><h3>PDFs frustrate guests</h3><p>Heavy file, tiny text, endless zooming. Half of guests give up and ask a server instead.</p></div>
      <div class="card reveal"><h3>You look behind the times</h3><p>Sticky laminated cards and blurry PDFs set the wrong tone before the food even arrives.</p></div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">The solution</span>
      <h2>A real digital menu &mdash; and a team that keeps it perfect.</h2>
      <p class="lede">The Smart QR Menu isn&rsquo;t a file behind a QR code. It&rsquo;s a fast, mobile-first menu we design around your food and maintain for you all year.</p>
    </div>
    <div class="grid-3">
      <div class="card reveal"><h3>Instant updates, done for you</h3><p>Email or call in a change &mdash; price, special, sold-out item &mdash; and we make it live, usually within 24&ndash;48 hours. Unlimited, all year.</p></div>
      <div class="card reveal"><h3>Built to sell</h3><p>Appetising photos, clear descriptions and tidy categories that make higher-margin dishes and add-ons easy to say yes to.</p></div>
      <div class="card reveal"><h3>Effortless for guests</h3><p>Scan with the phone camera and the menu opens instantly in the browser. No app, no sign-up, no pinch-to-zoom.</p></div>
      <div class="card reveal"><h3>One menu, every channel</h3><p>The same live menu works for dine-in, the takeaway counter, and customers checking you out before they visit.</p></div>
      <div class="card reveal"><h3>Cleaner tables</h3><p>Nothing to wipe down or pass hand to hand. Every guest gets their own, always-current copy.</p></div>
      <div class="card reveal"><h3>Managed &amp; hosted</h3><p>Secure cloud hosting, backups and uptime are on us. Only your public menu is ever visible &mdash; no business data exposed.</p></div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">How it works</span>
      <h2>From menu file to scannable code in under a week.</h2>
      <p class="lede">If you can email us a list of dishes, you can have a live digital menu. We handle the rest.</p>
    </div>
    <div class="steps" style="grid-template-columns:repeat(3,1fr);">
      <div class="step reveal"><span class="num">01</span><h3>Send us your menu</h3><p>Items, categories, descriptions, prices and any food photos you have. That&rsquo;s all we need to start.</p></div>
      <div class="step reveal"><span class="num">02</span><h3>We design &amp; build it</h3><p>Our team lays out a clean, on-brand menu and sets up your unique QR code. Ready in 3&ndash;5 business days.</p></div>
      <div class="step reveal"><span class="num">03</span><h3>Put it on the table &mdash; and change it anytime</h3><p>Add the code to table talkers, windows and takeaway bags. Message us with updates all year; your menu stays right.</p></div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Why switch</span>
      <h2>Smart QR Menu vs. the old way.</h2>
    </div>
    <div class="table-wrap reveal">
      <table class="table-compare">
        <thead><tr><th>&nbsp;</th><th>Printed menu</th><th>PDF behind a QR</th><th>Smart QR Menu</th></tr></thead>
        <tbody>
          <tr><td>Update a price</td><td>Reprint</td><td>Re-export &amp; re-upload</td><td><strong>Email us &mdash; live in hours</strong></td></tr>
          <tr><td>Loads fast on phones</td><td>&mdash;</td><td>Often slow</td><td><strong>Instantly</strong></td></tr>
          <tr><td>Readable without zooming</td><td>Yes</td><td>Rarely</td><td><strong>Always</strong></td></tr>
          <tr><td>Dish photos &amp; upsells</td><td>Costly</td><td>Static</td><td><strong>Built in</strong></td></tr>
          <tr><td>Hygiene</td><td>Shared &amp; handled</td><td>Touch-free</td><td><strong>Touch-free</strong></td></tr>
          <tr><td>Ongoing cost</td><td>Every reprint</td><td>Your time, every change</td><td><strong>One flat yearly fee</strong></td></tr>
          <tr><td>Who maintains it</td><td>You + a printer</td><td>You</td><td><strong>Our team</strong></td></tr>
        </tbody>
      </table>
    </div>
    <p style="margin-top:16px;font-size:.85rem;color:var(--ink-soft);">One annual fee, paid upfront &mdash; setup, design, unlimited updates and hosting for the full year. Contact us with your menu size for an exact price.</p>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Who it&rsquo;s for</span>
      <h2>If guests read a menu before they order, this is for you.</h2>
    </div>
    <div class="grid-3">
      <div class="card reveal"><h3>Restaurants &amp; bistros</h3><p>Seasonal changes, weekend specials and supply gaps handled without a reprint &mdash; the menu keeps up on its own.</p></div>
      <div class="card reveal"><h3>Caf&eacute;s &amp; bars</h3><p>Fast-moving specials, daily bakes and drinks lists that change often &mdash; update them as many times as you like.</p></div>
      <div class="card reveal"><h3>Takeaway &amp; delivery</h3><p>One live menu customers can open anytime, anywhere &mdash; before they visit, at the counter, or from the sofa.</p></div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="cta-band reveal">
      <h2>Give guests a menu that&rsquo;s always right.</h2>
      <p class="lede">Send your details and we&rsquo;ll come back with a price and a sample of how your menu will look. No obligation.</p>
      <div class="hero-cta">
        <a href="<?= url('/contact.php') ?>#contact-form" class="btn btn-primary">Get a quote</a>
        <a href="<?= e($demoUrl) ?>" target="_blank" rel="noopener" class="btn btn-ghost">Scan the live demo</a>
      </div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
