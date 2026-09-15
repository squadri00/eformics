<?php
$page = [
  'title'       => 'Portfolio | Eformics Systems',
  'description' => 'A selection of websites Eformics Systems has designed and built for businesses.',
  'canonical'   => '/portfolio.php',
  'section'     => 'portfolio',
];
require __DIR__ . '/partials/head.php';

$items   = portfolio_public();
$cols    = portfolio_columns();
$ph      = page_hero('portfolio');
$heading = setting('portfolio_heading', 'Our work');
$intro   = setting('portfolio_intro', '');
?>

<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / Portfolio</div>
      <h1 style="margin-top:14px;"><?= e($heading) ?></h1>
      <?php if (trim($intro) !== ''): ?><p class="lede"><?= e($intro) ?></p><?php endif; ?>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>

<section>
  <div class="container">
    <?php if (!$items): ?>
      <p class="lede">Projects are on their way &mdash; check back soon.</p>
    <?php else: ?>
    <div class="folio-grid" style="--folio-cols:<?= (int) $cols ?>;">
      <?php foreach ($items as $p): ?>
        <?php
          $hasLink = trim($p['website_url']) !== '';
          $alt     = $p['image_alt'] !== '' ? $p['image_alt'] : $p['title'];
        ?>
        <?php if ($hasLink): ?>
        <a class="folio-card<?= $p['is_featured'] ? ' is-featured' : '' ?>" href="<?= e($p['website_url']) ?>" target="_blank" rel="noopener">
        <?php else: ?>
        <div class="folio-card<?= $p['is_featured'] ? ' is-featured' : '' ?>">
        <?php endif; ?>
          <div class="folio-shot">
            <?php if ($p['image_path'] !== ''): ?>
              <img src="<?= e($p['image_path']) ?>" alt="<?= e($alt) ?>" loading="lazy">
            <?php endif; ?>
            <?php if ($hasLink): ?>
              <span class="folio-visit">Visit site
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M8 7h9v9"/></svg>
              </span>
            <?php endif; ?>
          </div>
          <div class="folio-meta">
            <?php if ($p['is_featured']): ?><span class="folio-badge">Featured</span><?php endif; ?>
            <h3><?= e($p['title']) ?></h3>
            <?php if ($p['client_name'] !== ''): ?><p class="folio-client"><?= e($p['client_name']) ?></p><?php endif; ?>
            <?php if (trim((string) $p['info']) !== ''): ?><p class="folio-info"><?= e($p['info']) ?></p><?php endif; ?>
          </div>
        <?= $hasLink ? '</a>' : '</div>' ?>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
