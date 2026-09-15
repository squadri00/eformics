<?php
$page = [
  'title'       => 'Meccora — Service Reminder Software for Auto Repair Shops | Eformics',
  'description' => 'Meccora helps independent auto repair shops keep customers coming back with automated reminders, vehicle history and deferred-work recovery. A product by Eformics Systems.',
  'canonical'   => '/products/meccora.php',
  'section'     => 'products',
];
require __DIR__ . '/../partials/head.php';
?>

<?php $ph = page_hero('meccora'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / <a href="<?= url('/') ?>#products">Products</a> / Meccora</div>
      <span class="pill">For independent auto repair shops</span>
      <h1 style="margin-top:16px;max-width:16ch;">Your customers don't forget your shop. They forget their next service.</h1>
      <p class="lede">Meccora keeps every customer connected to your shop with automated email and SMS reminders, complete vehicle history, and follow-ups for the work they put off &mdash; so more of them come back instead of going to the shop down the road.</p>
      <div class="hero-cta">
        <a href="https://app.meccora.com/signup.php" class="btn btn-primary">Start your 14-day free trial</a>
        <a href="https://meccora.com" class="btn btn-outline">Visit meccora.com</a>
      </div>
      <p style="margin-top:16px;font-size:.86rem;color:var(--ink-soft);">No credit card required &middot; No contract, cancel anytime &middot; Plans from $60/mo CAD</p>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">The problem</span>
      <h2>Follow-up is the first thing to slip on a busy day.</h2>
      <p class="lede">Most independent shops are focused on today's cars. There's rarely time to call customers when their next oil change, brake service or inspection is due &mdash; so they drift, forget, and eventually book somewhere else.</p>
    </div>
    <div class="grid-3">
      <div class="card reveal"><h3>Every service schedules its own reminders</h3><p>30 days before, 15 days before, and on the due date &mdash; automatically, for every logged service.</p></div>
      <div class="card reveal"><h3>Declined work doesn't disappear</h3><p>Flag it once and Meccora keeps it on your dashboard until someone follows up.</p></div>
      <div class="card reveal"><h3>Lapsed customers resurface</h3><p>See who hasn't been in for 6&ndash;12 months, before they're gone for good.</p></div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">What you get</span>
      <h2>Everything a small shop needs to keep customers &mdash; and nothing it doesn't.</h2>
    </div>
    <div class="grid-3">
      <div class="card reveal"><h3>Automated reminders</h3><p>Email on every plan, SMS on higher tiers. Three touches per service, in English or French, with real-time delivery status.</p></div>
      <div class="card reveal"><h3>Deferred-work recovery</h3><p>When a customer declines recommended work, staff flag it with an estimated value so it stays visible as real money to win back.</p></div>
      <div class="card reveal"><h3>Customer &amp; vehicle history</h3><p>Every customer, vehicle, VIN and past service in one place &mdash; pulled up in seconds at the counter or on the phone.</p></div>
      <div class="card reveal"><h3>Revenue &amp; pipeline dashboard</h3><p>Customers, vehicles, services this month, billed revenue and projected income at a glance.</p></div>
      <div class="card reveal"><h3>Invoices</h3><p>Generate, print or email an invoice from any service record. You still take payment on your own terminal; Meccora keeps the record.</p></div>
      <div class="card reveal"><h3>QR self-registration</h3><p>Print one QR code for the counter. Customers scan it and enter themselves and their vehicle &mdash; no staff data entry.</p></div>
    </div>
  </div>
</section>

<section>
  <div class="container two-col">
    <div class="reveal">
      <span class="eyebrow">How it works</span>
      <h2>Up and running the same day.</h2>
      <p class="lede">No installation, no integration project. Meccora works alongside whatever you use now.</p>
      <div class="steps" style="grid-template-columns:1fr;gap:22px;">
        <div class="step"><h3>Add your shop</h3><p>Set your business details, services and reminder timing. Import existing customers and vehicles from a CSV.</p></div>
        <div class="step"><h3>Log a service</h3><p>Record the work, the cost, and the next service date. Flag anything the customer declined.</p></div>
        <div class="step"><h3>Meccora follows up</h3><p>Reminders go out automatically at 30 days, 15 days and on the due date, in the customer's language.</p></div>
        <div class="step"><h3>They come back</h3><p>Watch bookings, deferred work and pipeline revenue on your dashboard.</p></div>
      </div>
    </div>
    <div class="mock-panel reveal">
      <h3 style="margin-bottom:16px;">Dashboard snapshot</h3>
      <div class="mock-row"><span>Customers</span><strong>1,240</strong></div>
      <div class="mock-row"><span>Registered vehicles</span><strong>1,610</strong></div>
      <div class="mock-row"><span>Reminders due this week</span><strong>38</strong></div>
      <div class="mock-row"><span>Deferred work value</span><strong>$6,420</strong></div>
      <div class="mock-row"><span>Projected pipeline revenue</span><strong>$18,900</strong></div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container two-col">
    <div class="mock-panel reveal" style="order:2;">
      <h3 style="margin-bottom:14px;">Documented consent, built in</h3>
      <ul class="check-list">
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Verbal, web form or QR registration &mdash; each with method, timestamp and staff member.</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>STOP / START opt-out by reply, or logged in-person by staff.</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Reminders sent in English or French, per customer.</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Designed for Canadian privacy law (PIPEDA-aligned).</li>
      </ul>
    </div>
    <div class="reveal" style="order:1;">
      <span class="eyebrow">Messaging done right</span>
      <h2>Texting your customers, without the compliance headache.</h2>
      <p class="lede">Meccora's SMS is built for transactional service notifications &mdash; not mass marketing &mdash; with the consent records and opt-out handling regulators expect.</p>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head center reveal">
      <span class="eyebrow">FAQ</span>
      <h2>Good to know before you start</h2>
    </div>
    <div style="max-width:760px;margin:0 auto;">
      <div class="faq-item"><button>Can I try it before paying? <span class="plus"></span></button><div class="faq-panel"><p>Yes &mdash; a 14-day free trial with full access and no credit card. Every account is seeded with demo data so you can explore safely, then a one-click cleanup wipes it when you're ready to go live.</p></div></div>
      <div class="faq-item"><button>Is there a contract? <span class="plus"></span></button><div class="faq-panel"><p>No. Cancel anytime from the billing portal &mdash; you keep access until the end of your current billing period.</p></div></div>
      <div class="faq-item"><button>Can I bring my existing customer list? <span class="plus"></span></button><div class="faq-panel"><p>Yes. Import your current customers and their vehicles from a CSV file &mdash; no manual re-typing.</p></div></div>
      <div class="faq-item"><button>Does it replace my existing system? <span class="plus"></span></button><div class="faq-panel"><p>No &mdash; Meccora works alongside whatever you use now, as the layer that handles customer records and follow-up.</p></div></div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="cta-band reveal">
      <h2>Bring more customers back &mdash; automatically.</h2>
      <p class="lede">Start a 14-day free trial. No credit card, no contract, cancel anytime. Plans from $60/mo CAD.</p>
      <div class="hero-cta"><a href="https://app.meccora.com/signup.php" class="btn btn-primary">Start free trial</a></div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
