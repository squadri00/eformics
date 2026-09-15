<?php
$page = [
  'title'       => 'Quotaire — Build Your Own Quote Calculator | Eformics',
  'description' => 'Quotaire lets businesses build custom online quote calculators from their own price lists, in four simple steps. A product by Eformics Systems.',
  'canonical'   => '/products/quotaire.php',
  'section'     => 'products',
];
require __DIR__ . '/../partials/head.php';
?>

<?php $ph = page_hero('quotaire'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / <a href="<?= url('/') ?>#products">Products</a> / Quotaire</div>
      <span class="pill">For businesses that quote by products, options &amp; rules</span>
      <h1 style="margin-top:16px;max-width:18ch;">Build your own quote calculator.</h1>
      <p class="lede">Create custom quote calculators for your business. Set your products, options, pricing and rules, then use them internally or give customers a simple way to get a quote online.</p>
      <div class="hero-cta">
        <a href="https://www.quotaire.com/pricing" class="btn btn-primary">Join for free</a>
        <a href="https://www.quotaire.com/demo" class="btn btn-outline">Try a live demo</a>
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
      <h2>Manual quoting is costing you business.</h2>
      <p class="lede">Slow estimates, spreadsheet errors, and hidden pricing all push potential customers toward a competitor who answers faster.</p>
    </div>
    <div class="grid-3">
      <div class="card reveal"><h3>Slow quotes lose sales</h3><p>Buyers want instant answers. Waiting 24&ndash;48 hours for a manual estimate gives leads time to buy elsewhere.</p></div>
      <div class="card reveal"><h3>Spreadsheets break</h3><p>One incorrect formula, missing row, or outdated rate sheet ruins your profit margins.</p></div>
      <div class="card reveal"><h3>Hidden pricing kills conversions</h3><p>Modern buyers skip businesses that require a form or sales call just to get a baseline price.</p></div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">How it works</span>
      <h2>From price list to live calculator in 4 simple steps.</h2>
    </div>
    <div class="steps">
      <div class="step reveal"><span class="num">01</span><h3>Add your products</h3><p>Enter your core products, services, materials, or billable options.</p></div>
      <div class="step reveal"><span class="num">02</span><h3>Set prices &amp; rules</h3><p>Define base rates, conditional logic, variable quantities, and percentage add-ons.</p></div>
      <div class="step reveal"><span class="num">03</span><h3>Test &amp; preview</h3><p>Test your calculator in real-time to ensure every rule calculates perfectly.</p></div>
      <div class="step reveal"><span class="num">04</span><h3>Embed anywhere</h3><p>Paste a lightweight code snippet into your website, or use it internally.</p></div>
    </div>
  </div>
</section>

<section>
  <div class="container two-col">
    <div class="mock-panel reveal">
      <h3 style="margin-bottom:14px;">Dashboard snapshot</h3>
      <div class="mock-row"><span>Total quotes</span><strong>248</strong></div>
      <div class="mock-row"><span>Pending</span><strong>37</strong></div>
      <div class="mock-row"><span>Approved</span><strong>126</strong></div>
      <div class="mock-row"><span>Quote value</span><strong>$184K</strong></div>
    </div>
    <div class="reveal">
      <span class="eyebrow">Internal business tool</span>
      <h2>One place to manage your quoting process.</h2>
      <p class="lede">Give your team one place to manage products, pricing, options and quotes &mdash; so everyone works from the same information.</p>
      <ul class="check-list">
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Put your calculator on your website for customers to use anytime.</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Customers see estimated pricing and submit a quote request without calling.</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Ready-made templates for printing, HVAC, cabinets, furniture, manufacturing and more.</li>
      </ul>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">Two ways to get started</span>
      <h2>Build it yourself, or let us build it for you.</h2>
    </div>
    <div class="grid-2">
      <div class="card reveal">
        <span class="plabel">DIY</span>
        <h3>Build it yourself</h3>
        <p>You know your business best. Set up your products, pricing and rules yourself, make changes whenever you need, and keep complete control of your quote system.</p>
        <a href="https://www.quotaire.com/pricing" class="plink">Start building <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>
      <div class="card reveal">
        <span class="plabel">DFY</span>
        <h3>We build it for you</h3>
        <p>Tell us what you sell and how you price it. Our team will build and configure your quote calculator, so you can start using it without spending time on setup.</p>
        <a href="https://www.quotaire.com/contact" class="plink">Get a setup quote <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg></a>
      </div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="cta-band reveal">
      <h2>Start creating quotes today.</h2>
      <p class="lede">Why start from a blank page? Pick a template close to your business and customize every product, question and price to fit exactly how you quote.</p>
      <div class="hero-cta"><a href="https://www.quotaire.com/pricing" class="btn btn-primary">Join for free</a><a href="https://www.quotaire.com" class="btn btn-ghost">Visit quotaire.com</a></div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
