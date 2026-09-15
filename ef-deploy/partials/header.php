<?php
$sec = $page['section'] ?? '';

$products = array_filter([
    'products/meccora'       => ['Meccora',       'Service reminders &amp; CRM for auto repair shops'],
    'products/quotaire'      => ['Quotaire',      'Build-your-own online quote calculators'],
    'products/chantley'      => ['Chantley',      'AI chatbot trained on your website content'],
    'products/smart-qr-menu' => ['Smart QR Menu', 'Managed digital menus for restaurants'],
], 'page_is_published', ARRAY_FILTER_USE_KEY);

$services = array_filter([
    'services/development'        => ['Custom Development',   'Web apps built around your workflow'],
    'services/web-design'         => ['Website Designing',    'Modern, conversion-ready websites'],
    'services/pwa'                => ['Progressive Web Apps', 'Installable, offline-capable web apps'],
    'services/visual-enhancement' => ['Visual Enhancement',   'Restaurant food photo &amp; video enhancement'],
], 'page_is_published', ARRAY_FILTER_USE_KEY);

$showAbout   = page_is_published('about');
$showContact = page_is_published('contact');
?>
<header class="site-header">
  <div class="container nav">
    <a href="<?= url('/') ?>" class="brand brand-logo"><img src="<?= url('/assets/img/eformics-logo-red.webp') ?>" alt="<?= e(SITE_NAME) ?>" class="logo-red"></a>
    <nav>
      <ul class="nav-links" id="navLinks">
        <li><a href="<?= url('/') ?>"<?= $sec === 'home' ? ' aria-current="page"' : '' ?>>Home</a></li>
        <?php if ($products): ?>
        <li class="has-dropdown<?= $sec === 'products' ? ' active' : '' ?>">
          <button type="button">Products <span class="caret"></span></button>
          <div class="dropdown">
            <?php foreach ($products as $key => [$name, $desc]): ?>
            <a href="<?= url('/' . $key . '.php') ?>"><strong><?= $name ?></strong><span><?= $desc ?></span></a>
            <?php endforeach; ?>
          </div>
        </li>
        <?php endif; ?>
        <?php if ($services): ?>
        <li class="has-dropdown<?= $sec === 'services' ? ' active' : '' ?>">
          <button type="button">Services <span class="caret"></span></button>
          <div class="dropdown">
            <?php foreach ($services as $key => [$name, $desc]): ?>
            <a href="<?= url('/' . $key . '.php') ?>"><strong><?= $name ?></strong><span><?= $desc ?></span></a>
            <?php endforeach; ?>
          </div>
        </li>
        <?php endif; ?>
        <?php if ($showAbout): ?><li><a href="<?= url('/about.php') ?>"<?= $sec === 'about' ? ' aria-current="page"' : '' ?>>Who We Are</a></li><?php endif; ?>
        <?php if ($showContact): ?><li><a href="<?= url('/contact.php') ?>"<?= $sec === 'contact' ? ' aria-current="page"' : '' ?>>Contact</a></li><?php endif; ?>
      </ul>
    </nav>
    <div class="nav-cta">
      <span class="nav-phone"><?= SITE_PHONE ?></span>
      <?php if ($showContact): ?><a href="<?= url('/contact.php') ?>" class="btn btn-outline btn-sm btn-sm-desktop">Get Started</a><?php endif; ?>
      <button class="theme-toggle" type="button" aria-label="Switch colour theme" aria-pressed="false" title="Switch to dark mode">
        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3a6 6 0 0 0 9 9 9 9 0 1 1-9-9Z"/></svg>
        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"/></svg>
      </button>
      <button class="menu-toggle" aria-label="Menu"><span></span><span></span><span></span></button>
    </div>
  </div>
</header>
