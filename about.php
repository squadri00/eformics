<?php
$page = [
  'title'       => 'Who We Are | Eformics Systems',
  'description' => 'Eformics Systems has built software and websites for businesses since 2009, and now builds and runs its own SaaS products: Meccora, Quotaire and Chantley.',
  'canonical'   => '/about.php',
  'section'     => 'about',
];
require __DIR__ . '/partials/head.php';
?>

<?php $ph = page_hero('about'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / Who We Are</div>
      <span class="pill">Since 2009 &middot; Mississauga, Ontario</span>
      <h1 style="margin-top:16px;max-width:20ch;">We build software we'd actually want to use.</h1>
      <p class="lede">Eformics Systems started as a web design and development shop and has grown into a team that builds and runs its own SaaS products, alongside custom software for clients.</p>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>

<section>
  <div class="container two-col">
    <div class="reveal">
      <h2>From client projects to our own products.</h2>
      <p>For over 16 years, we've partnered with businesses to build websites and software that simplify daily operations. Along the way, we started building tools for problems we kept seeing across clients &mdash; and turned them into standalone products.</p>
      <p>Today that means Meccora, Quotaire and Chantley run as their own SaaS businesses, while our development and design team continues to build custom applications and websites directly for clients.</p>
    </div>
    <div class="stats-band reveal">
      <div class="stats-grid">
        <div><strong>2009</strong><span>Founded</span></div>
        <div><strong>3</strong><span>Products in market</span></div>
        <div><strong>2</strong><span>Core services</span></div>
        <div><strong>ON</strong><span>Mississauga, Canada</span></div>
      </div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">What we do</span>
      <h2>Two sides of the same team.</h2>
    </div>
    <div class="grid-2">
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v16H4z"/><path d="M4 9h16"/></svg></div>
        <h3>Our products</h3>
        <p>Meccora, Quotaire and Chantley are built, hosted and supported by Eformics &mdash; each solving one specific business problem, run as an independent SaaS product.</p>
        <a href="<?= url('/') ?>#products" class="plink">See our products <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M8 4 4 8l4 4M16 4l4 4-4 4M14 4l-4 16"/></svg></div>
        <h3>Our services</h3>
        <p>Custom development and website design for clients who need something built specifically for how their business works.</p>
        <a href="<?= url('/services/development.php') ?>" class="plink">See our services <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="cta-band reveal">
      <h2>Want to work with us?</h2>
      <p class="lede">Whether it's a product question or a custom project, we'd like to hear about it.</p>
      <div class="hero-cta"><a href="<?= url('/contact.php') ?>" class="btn btn-primary">Contact us</a></div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
