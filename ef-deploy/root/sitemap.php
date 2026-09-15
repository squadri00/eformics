<?php
$page = [
  'title'       => 'Sitemap | Eformics Systems',
  'description' => 'Sitemap for Eformics Systems.',
  'canonical'   => '/sitemap.php',
];
require __DIR__ . '/partials/head.php';
?>

<?php $ph = page_hero('sitemap'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <h1>Sitemap</h1>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>
<section><div class="container" style="max-width:640px;">
  <?php
  $links = [
    'home'                 => ['/', 'Home'],
    'products/meccora'      => ['/products/meccora.php', 'Meccora'],
    'products/quotaire'     => ['/products/quotaire.php', 'Quotaire'],
    'products/chantley'     => ['/products/chantley.php', 'Chantley'],
    'products/smart-qr-menu' => ['/products/smart-qr-menu.php', 'Smart QR Menu'],
    'services/development' => ['/services/development.php', 'Custom Development'],
    'services/web-design'  => ['/services/web-design.php', 'Website Designing'],
    'services/pwa'         => ['/services/pwa.php', 'Progressive Web Apps'],
    'services/visual-enhancement' => ['/services/visual-enhancement.php', 'Visual Enhancement'],
    'portfolio'            => ['/portfolio.php', 'Portfolio'],
    'about'                => ['/about.php', 'Who We Are'],
    'contact'             => ['/contact.php', 'Contact'],
    'privacy-policy'       => ['/privacy-policy.php', 'Privacy Policy'],
    'terms'                => ['/terms.php', 'Terms of Use'],
  ];
  ?>
  <ul class="check-list" style="display:grid;gap:10px;">
    <?php foreach ($links as $key => [$path, $label]): ?>
      <?php if (page_is_published($key)): ?>
      <li><a href="<?= e(url($path)) ?>"><?= e($label) ?></a></li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div></section>

<?php require __DIR__ . '/partials/footer.php'; ?>
