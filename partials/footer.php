<?php
$fLink = static function (string $key, string $path, string $label): string {
    return page_is_published($key)
        ? '<li><a href="' . e(url($path)) . '">' . e($label) . '</a></li>'
        : '';
};
?>
<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="<?= url('/') ?>" class="brand brand-logo" style="margin-bottom:14px;display:inline-flex;"><img src="<?= url('/assets/img/eformics-logo-white.webp') ?>" alt="<?= e(SITE_NAME) ?>" class="logo-white"></a>
        <p>Since 2009, we've built practical software, websites and digital products that help businesses run better &mdash; from our own SaaS products to custom applications built for clients.</p>
      </div>
      <div>
        <h4>Products</h4>
        <ul>
          <?= $fLink('products/meccora', '/products/meccora.php', 'Meccora') ?>
          <?= $fLink('products/quotaire', '/products/quotaire.php', 'Quotaire') ?>
          <?= $fLink('products/chantley', '/products/chantley.php', 'Chantley') ?>
          <?= $fLink('products/smart-qr-menu', '/products/smart-qr-menu.php', 'Smart QR Menu') ?>
          <?= $fLink('products/recodik', '/products/recodik.php', 'Recodik') ?>
        </ul>
      </div>
      <div>
        <h4>Services</h4>
        <ul>
          <?= $fLink('services/development', '/services/development.php', 'Custom Development') ?>
          <?= $fLink('services/web-design', '/services/web-design.php', 'Website Designing') ?>
          <?= $fLink('services/pwa', '/services/pwa.php', 'Progressive Web Apps') ?>
          <?= $fLink('services/visual-enhancement', '/services/visual-enhancement.php', 'Visual Enhancement') ?>
          <?= $fLink('portfolio', '/portfolio.php', 'Portfolio') ?>
          <?= $fLink('about', '/about.php', 'Who We Are') ?>
          <?= $fLink('contact', '/contact.php', 'Contact') ?>
        </ul>
      </div>
      <div>
        <h4>Contact</h4>
        <ul>
          <li><a href="<?= SITE_PHONE_HREF ?>"><?= SITE_PHONE ?></a></li>
          <li><a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></li>
          <li><?= SITE_LOCATION ?></li>
        </ul>
        <?php $socialLinks = social_links_enabled(); ?>
        <?php if ($socialLinks): ?>
        <div class="footer-social">
          <?php foreach ($socialLinks as $s): ?>
          <a href="<?= e($s['url']) ?>" target="_blank" rel="noopener" aria-label="Eformics Systems on <?= e($s['label']) ?>"><?= social_icon_svg($s['icon']) ?></a>
          <?php endforeach; ?>
        </div>
        <?php endif; ?>
      </div>
    </div>
    <div class="footer-bottom">
      <span>&copy; <?= e(SITE_NAME) ?>, 2009&ndash;<?= date('Y') ?>. All rights reserved.</span>
      <span>
        <?php
        $legal = [];
        if (page_is_published('privacy-policy')) $legal[] = '<a href="' . e(url('/privacy-policy.php')) . '">Privacy Policy</a>';
        if (page_is_published('terms'))          $legal[] = '<a href="' . e(url('/terms.php')) . '">Terms of Use</a>';
        if (page_is_published('sitemap'))        $legal[] = '<a href="' . e(url('/sitemap.php')) . '">Sitemap</a>';
        echo implode(' &middot; ', $legal);
        ?>
      </span>
    </div>
  </div>
</footer>
<button class="to-top" type="button" aria-label="Back to top">
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg>
</button>
<script src="<?= asset('/assets/js/theme.js') ?>"></script>
<script src="<?= asset('/assets/js/main.js') ?>"></script>
<?= seo_body() ?>
</body>
</html>
