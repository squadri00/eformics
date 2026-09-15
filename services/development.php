<?php
$page = [
  'title'       => 'Custom Software Development Services | Eformics Systems',
  'description' => 'Eformics Systems designs and builds custom web applications, customer portals and business management systems built around how your business actually runs.',
  'canonical'   => '/services/development.php',
  'section'     => 'services',
];
require __DIR__ . '/../partials/head.php';
?>

<?php $ph = page_hero('development'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / <a href="<?= url('/') ?>">Services</a> / Custom Development</div>
      <span class="pill">Web Development</span>
      <h1 style="margin-top:16px;max-width:18ch;">Custom software built around your business.</h1>
      <p class="lede">Every business has unique processes. We design and develop custom web applications that automate workflows, improve efficiency and support long-term growth &mdash; the same way we build Meccora, Quotaire and Chantley for ourselves.</p>
      <div class="hero-cta">
        <a href="<?= url('/contact.php') ?>" class="btn btn-primary">Contact now</a>
        <a href="#work" class="btn btn-outline">What we build</a>
      </div>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>

<section id="work">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">What we build</span>
      <h2>Practical applications that solve real problems.</h2>
    </div>
    <div class="grid-3">
      <div class="card reveal"><h3>Customer portals</h3><p>Self-service portals where your customers can view records, submit requests or manage their account &mdash; without calling you.</p></div>
      <div class="card reveal"><h3>Business management systems</h3><p>Internal tools that replace spreadsheets and manual tracking with one system your whole team works from.</p></div>
      <div class="card reveal"><h3>Workflow automation</h3><p>Applications that remove repetitive manual steps &mdash; reminders, approvals, reporting &mdash; from your team's day.</p></div>
      <div class="card reveal"><h3>SaaS &amp; multi-tenant products</h3><p>We build and run our own SaaS products, so we bring that same production experience to client platforms.</p></div>
      <div class="card reveal"><h3>Integrations</h3><p>Connecting the tools you already use so data moves automatically instead of being re-typed.</p></div>
      <div class="card reveal"><h3>Ongoing support</h3><p>Software is never really "done" &mdash; we stay on to maintain, extend and support what we build.</p></div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container two-col">
    <div class="reveal">
      <span class="eyebrow">How we work</span>
      <h2>We start with your business, not a template.</h2>
      <p class="lede">Every successful software project starts with understanding how your business actually runs. Our team works closely with clients to design, develop and maintain solutions that are practical, scalable and built for everyday use.</p>
      <ul class="check-list">
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Discovery &mdash; understanding your process before writing a line of code.</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Design &amp; build &mdash; a working application, not just a mockup.</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Launch &amp; support &mdash; the same team stays with your project after go-live.</li>
      </ul>
    </div>
    <div class="mock-panel reveal">
      <h3 style="margin-bottom:14px;">A note on how we build</h3>
      <p>We run three of our own SaaS products in production &mdash; Meccora, Quotaire and Chantley. Custom development work is handled by the same team that ships and supports that software, day in and day out.</p>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="cta-band reveal">
      <h2>Have a process that needs its own software?</h2>
      <p class="lede">Tell us how it works today, and we'll help you figure out what it should look like.</p>
      <div class="hero-cta"><a href="<?= url('/contact.php') ?>" class="btn btn-primary">Get started</a></div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
