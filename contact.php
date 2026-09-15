<?php
require __DIR__ . '/partials/contact-handler.php'; // handles POST then exits; no-op on GET

$page = [
  'title'       => 'Contact Us | Eformics Systems',
  'description' => 'Get in touch with Eformics Systems about Meccora, Quotaire, Chantley, custom development or website design projects.',
  'canonical'   => '/contact.php',
  'section'     => 'contact',
];
require __DIR__ . '/partials/head.php';
?>

<?php $ph = page_hero('contact'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / Contact</div>
      <span class="pill">Let's talk</span>
      <h1 style="margin-top:16px;max-width:18ch;">Tell us about your project.</h1>
      <p class="lede">Whether it's a question about Meccora, Quotaire or Chantley, or a custom development / website project &mdash; send us a message and we'll get back to you.</p>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>

<section id="contact-form">
  <div class="container two-col">
    <form id="contactForm" class="reveal" action="<?= url('/contact.php') ?>" method="post" style="display:grid;gap:16px;">
      <div class="hp" aria-hidden="true">
        <input type="text" name="cf_extra" tabindex="-1" autocomplete="off" value="">
      </div>
      <div class="cf-row" style="display:grid;gap:16px;">
        <div>
          <label for="cf-name" style="display:block;font-size:.86rem;font-weight:600;margin-bottom:6px;">Name</label>
          <input id="cf-name" name="name" type="text" required maxlength="120" autocomplete="name" style="width:100%;padding:13px 14px;border:1.5px solid var(--line);border-radius:10px;font:inherit;">
        </div>
        <div>
          <label for="cf-email" style="display:block;font-size:.86rem;font-weight:600;margin-bottom:6px;">Email</label>
          <input id="cf-email" name="email" type="email" required autocomplete="email" style="width:100%;padding:13px 14px;border:1.5px solid var(--line);border-radius:10px;font:inherit;">
        </div>
      </div>
      <div>
        <label for="cf-interest" style="display:block;font-size:.86rem;font-weight:600;margin-bottom:6px;">I'm interested in</label>
        <select id="cf-interest" name="interest" style="width:100%;padding:13px 14px;border:1.5px solid var(--line);border-radius:10px;font:inherit;background:var(--surface);color:var(--ink);">
          <option>Meccora</option>
          <option>Quotaire</option>
          <option>Chantley</option>
          <option>Custom Development</option>
          <option>Website Designing</option>
          <option>Progressive Web App</option>
          <option>Something else</option>
        </select>
      </div>
      <div>
        <label for="cf-message" style="display:block;font-size:.86rem;font-weight:600;margin-bottom:6px;">Message</label>
        <textarea id="cf-message" name="message" rows="5" required maxlength="5000" style="width:100%;padding:13px 14px;border:1.5px solid var(--line);border-radius:10px;font:inherit;resize:vertical;"></textarea>
      </div>
      <button type="submit" class="btn btn-primary" style="justify-self:start;">Send message</button>
      <div id="formStatus" class="form-status" role="status" aria-live="polite" hidden></div>
      <p style="font-size:.82rem;color:var(--ink-soft);">We'll only use your details to reply to your enquiry. See our <a href="<?= url('/privacy-policy.php') ?>" style="color:var(--violet);text-decoration:underline;">Privacy Policy</a>.</p>
    </form>

    <div class="mock-panel reveal">
      <h3 style="margin-bottom:16px;">Direct contact</h3>
      <div class="mock-row"><span>Phone</span><strong><a href="<?= SITE_PHONE_HREF ?>"><?= SITE_PHONE ?></a></strong></div>
      <div class="mock-row"><span>Email</span><strong><a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></strong></div>
      <div class="mock-row"><span>Location</span><strong><?= SITE_LOCATION ?></strong></div>
      <div class="mock-row"><span>Product support</span><strong><a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></strong></div>
      <?php
      $exploreProducts = array_filter([
        'products/meccora'       => ['/products/meccora.php', 'Meccora'],
        'products/quotaire'      => ['/products/quotaire.php', 'Quotaire'],
        'products/chantley'      => ['/products/chantley.php', 'Chantley'],
        'products/smart-qr-menu' => ['/products/smart-qr-menu.php', 'Smart QR Menu'],
        'products/recodik'      => ['/products/recodik.php', 'Recodik'],
      ], 'page_is_published', ARRAY_FILTER_USE_KEY);
      ?>
      <?php if ($exploreProducts): ?>
      <h3 style="margin:24px 0 12px;">Prefer to explore a product first?</h3>
      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <?php foreach ($exploreProducts as [$path, $label]): ?>
        <a href="<?= url($path) ?>" class="btn btn-outline btn-sm"><?= e($label) ?></a>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
