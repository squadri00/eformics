<?php
$page = [
  'title'       => 'Chantley — AI Chatbot for Small Business Websites | Eformics',
  'description' => 'Chantley turns your website content into an AI chatbot that answers customer questions 24/7. No coding, live in minutes. A product by Eformics Systems.',
  'canonical'   => '/products/chantley.php',
  'section'     => 'products',
];
require __DIR__ . '/../partials/head.php';
?>

<?php $ph = page_hero('chantley'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / <a href="<?= url('/') ?>#products">Products</a> / Chantley</div>
      <span class="pill">AI chatbot for small business websites</span>
      <h1 style="margin-top:16px;max-width:18ch;">Your website already has the answers. Chantley just speaks them.</h1>
      <p class="lede">Turn your website content and documents into a smart AI chatbot &mdash; no technical setup, no expensive integrations. Just upload, and start answering customer questions in minutes.</p>
      <div class="hero-cta">
        <a href="https://www.chantley.com/pricing" class="btn btn-primary">Get started</a>
        <a href="https://www.chantley.com/how-it-works" class="btn btn-outline">See how it works</a>
      </div>
      <div class="hero-badges" style="margin-top:24px;">
        <span style="border-color:var(--line);color:var(--ink-soft);">Minutes to go live</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">Zero coding required</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">Plans from $29/mo</span>
        <span style="border-color:var(--line);color:var(--ink-soft);">24/7 always answering</span>
      </div>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">How it works</span>
      <h2>From content to conversation in 3 simple steps.</h2>
      <p class="lede">You don't need to be technical to set up Chantley. If you can upload a file or copy a link, you can have a working AI chatbot today.</p>
    </div>
    <div class="steps" style="grid-template-columns:repeat(3,1fr);">
      <div class="step reveal"><span class="num">01</span><h3>Add your content</h3><p>Enter your website address or upload documents &mdash; product lists, service details, FAQs, anything.</p></div>
      <div class="step reveal"><span class="num">02</span><h3>We organize it</h3><p>Chantley automatically reads and organizes your content, ready to answer questions the moment a visitor asks.</p></div>
      <div class="step reveal"><span class="num">03</span><h3>Chatbot goes live</h3><p>Add one code snippet to your website, and your AI assistant starts helping customers immediately.</p></div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Features</span>
      <h2>What Chantley does for your business.</h2>
    </div>
    <div class="grid-2">
      <div class="card reveal"><h3>Smart answers</h3><p>Understands real, open-ended questions &mdash; not just keyword matching.</p></div>
      <div class="card reveal"><h3>One-line embed</h3><p>Add one snippet to your website. No developer needed.</p></div>
      <div class="card reveal"><h3>Conversation history</h3><p>See exactly what your customers are asking, anytime.</p></div>
      <div class="card reveal"><h3>Order lookup</h3><p>Optional order status lookup &mdash; no live integration required.</p></div>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Who it's for</span>
      <h2>If your business has a website or documents customers ask about, Chantley can help.</h2>
    </div>
    <div class="grid-3">
      <div class="card reveal"><h3>Retail &amp; e-commerce</h3><p>Answer product questions, sizing, availability, and gift suggestions based on your catalog.</p></div>
      <div class="card reveal"><h3>Local services</h3><p>Let customers ask about hours, pricing, service areas and offerings without calling in.</p></div>
      <div class="card reveal"><h3>Content-heavy sites</h3><p>Turn catalogs, guides and reviews into instant answers for visitors.</p></div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Simple, honest pricing</span>
      <h2>Built for the small business owner, not the enterprise dev team.</h2>
      <p class="lede">Chantley talks about AI questions per month, not tokens or confusing credit math &mdash; so you always know what you're paying for.</p>
    </div>
    <div class="table-wrap reveal">
      <table class="table-compare">
        <thead><tr><th>What you get</th><th>Chantley</th><th>Typical competitor</th></tr></thead>
        <tbody>
          <tr><td>Starting paid price</td><td><strong>$29/mo</strong></td><td>$40&ndash;$124/mo</td></tr>
          <tr><td>Usage measurement</td><td><strong>Simple AI questions/mo</strong></td><td>Credits that vary by AI model</td></tr>
          <tr><td>Choosing an AI model</td><td><strong>Not required &mdash; handled for you</strong></td><td>Often required</td></tr>
          <tr><td>Website chatbot</td><td>Yes</td><td>Yes</td></tr>
        </tbody>
      </table>
    </div>
    <p style="margin-top:16px;font-size:.85rem;color:var(--ink-soft);">Comparison is a general snapshot; competitor pricing and features change often.</p>
  </div>
</section>

<section>
  <div class="container">
    <div class="cta-band reveal">
      <h2>Give every visitor an instant, helpful answer.</h2>
      <p class="lede">Most businesses are live the same day. No coding, no IT team required.</p>
      <div class="hero-cta"><a href="https://www.chantley.com/pricing" class="btn btn-primary">Get started</a><a href="https://www.chantley.com" class="btn btn-ghost">Visit chantley.com</a></div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
