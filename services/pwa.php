<?php
$page = [
  'title'       => 'Progressive Web App Development | Eformics Systems',
  'description' => 'Eformics Systems builds progressive web apps (PWAs) for small and mid-sized businesses — installable, offline-capable web apps with push notifications, and no app store.',
  'canonical'   => '/services/pwa.php',
  'section'     => 'services',
];
require __DIR__ . '/../partials/head.php';
?>

<?php $ph = page_hero('pwa'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / <a href="<?= url('/') ?>">Services</a> / Progressive Web Apps</div>
      <span class="pill">Progressive Web Apps</span>
      <h1 style="margin-top:16px;max-width:20ch;">Progressive web apps for small and mid-sized businesses.</h1>
      <p class="lede">A progressive web app (PWA) is a website that can be installed to a phone or desktop, keep working with a poor connection or none at all, and send push notifications &mdash; without going through an app store. We build them from scratch, or add the capability to a site you already have.</p>
      <div class="hero-cta">
        <a href="<?= url('/contact.php') ?>" class="btn btn-primary">Contact now</a>
        <a href="#fit" class="btn btn-outline">Where a PWA fits</a>
      </div>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">The basics</span>
      <h2>It's a website with three added parts.</h2>
      <p class="lede">Everything else about the site stays the same. These three pieces are what a browser looks for before it treats a site as an installable app.</p>
    </div>
    <div class="grid-3">
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M9 13h6M9 17h6"/></svg></div>
        <h3>Web app manifest</h3>
        <p>A small JSON file that tells the browser the app's name, icons, colours and how it should open. It is what makes the site installable.</p>
      </div>
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M12 2v3M12 19v3M2 12h3M19 12h3M4.9 4.9l2.1 2.1M17 17l2.1 2.1M19.1 4.9 17 7M7 17l-2.1 2.1"/></svg></div>
        <h3>Service worker</h3>
        <p>A script the browser runs in the background. It caches files so the app still loads on a weak connection, or with no connection at all.</p>
      </div>
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="10" width="16" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/></svg></div>
        <h3>Served over HTTPS</h3>
        <p>PWAs only work on a secure connection. This is already standard for any modern website, so it is rarely extra work.</p>
      </div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Capabilities</span>
      <h2>What the install actually gets you.</h2>
      <p class="lede">Each of these has real limits, mostly on iPhone and iPad. Where that matters, it is noted below.</p>
    </div>
    <div class="grid-3">
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><rect x="4" y="17" width="16" height="4" rx="1"/></svg></div>
        <h3>Install to the home screen</h3>
        <p>Opens in its own window with no address bar. On Android and desktop Chrome or Edge the browser offers an install button; on iOS the user adds it manually from the Safari share menu.</p>
      </div>
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12.5a11 11 0 0 1 14 0"/><path d="M8.5 16a6 6 0 0 1 7 0"/><path d="M12 20h.01"/><path d="M3 3l18 18"/></svg></div>
        <h3>Works offline</h3>
        <p>Pages and data you choose to cache stay available with no connection. What gets cached, and for how long, is decided per project.</p>
      </div>
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.7 21a2 2 0 0 1-3.4 0"/></svg></div>
        <h3>Push notifications</h3>
        <p>Supported on Android and desktop. On iPhone and iPad, push works only on iOS 16.4 or later, and only after the app has been added to the Home Screen.</p>
      </div>
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-3-6.7"/><path d="M21 3v6h-6"/></svg></div>
        <h3>Instant updates</h3>
        <p>Changes go live the moment you publish, the same as a website. There is no app-store review, and nothing for users to update.</p>
      </div>
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="M2 17l10 5 10-5"/><path d="M2 12l10 5 10-5"/></svg></div>
        <h3>One codebase</h3>
        <p>The same app runs on Android, iOS, Windows and macOS. There is no separate native iOS or Android build to write and maintain.</p>
      </div>
      <div class="card reveal">
        <div class="icon"><svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7 0l3-3a5 5 0 0 0-7-7l-1.5 1.5"/><path d="M14 11a5 5 0 0 0-7 0l-3 3a5 5 0 0 0 7 7l1.5-1.5"/></svg></div>
        <h3>Shareable by link</h3>
        <p>Every screen has a URL. You can link straight to it from an email, a QR code, a message or a search result.</p>
      </div>
    </div>
  </div>
</section>

<section id="fit">
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Honest fit</span>
      <h2>A PWA is not always the right choice.</h2>
      <p class="lede">If the requirements point to a native app, we will say so before any work starts.</p>
    </div>
    <div class="grid-2">
      <div class="card reveal">
        <h3>A PWA is a good fit for</h3>
        <ul class="check-list">
          <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Booking, enquiry and quote tools</li>
          <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Customer and account portals</li>
          <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Catalogues, price lists and reference content</li>
          <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Dashboards and internal team tools</li>
          <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Anything you would otherwise build as a website but want installable and usable offline</li>
        </ul>
      </div>
      <div class="card reveal">
        <h3>Consider a native app instead when</h3>
        <ul class="check-list">
          <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>You need reliable background location or background sync on iOS</li>
          <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>The app does heavy 3D, AR or camera processing</li>
          <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>It needs deep device integration such as Bluetooth or NFC on iOS</li>
          <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>Being found and downloaded in the App Store is your main distribution channel</li>
        </ul>
      </div>
    </div>
  </div>
</section>

<section style="background:var(--lavender);">
  <div class="container two-col">
    <div class="reveal">
      <span class="eyebrow">Process</span>
      <h2>From assessment to a tested install.</h2>
      <p class="lede">Whether we start from scratch or add PWA support to your current site, the steps are the same.</p>
      <div class="steps" style="grid-template-columns:1fr;gap:22px;">
        <div class="step"><h3>Assess fit</h3><p>We look at what the app needs to do and confirm a PWA is the right approach before building anything.</p></div>
        <div class="step"><h3>Manifest and icons</h3><p>Set the app name, colours and display mode, and produce a full icon set including maskable icons and iOS touch icons.</p></div>
        <div class="step"><h3>Service worker and offline</h3><p>Choose a caching strategy for your content, add an offline fallback screen, and handle app updates cleanly.</p></div>
        <div class="step"><h3>Test on real devices</h3><p>Check install, offline behaviour and notifications on Android and iOS, and run a Lighthouse audit.</p></div>
        <div class="step"><h3>Optional extras</h3><p>Push notification setup, and a Google Play listing generated from the PWA using a Trusted Web Activity.</p></div>
        <div class="step"><h3>Support</h3><p>The same team maintains it afterwards, the same as our other work.</p></div>
      </div>
    </div>
    <div class="mock-panel reveal">
      <h3 style="margin-bottom:14px;">What you receive</h3>
      <ul class="check-list">
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>A web app manifest with a full icon set</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>A service worker with a caching strategy chosen for your content</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>An offline fallback page</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Install tested on Android and iOS</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>A Lighthouse report for the finished build</li>
        <li><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6 9 17l-5-5"/></svg>Optional: push notifications and a Google Play listing</li>
      </ul>
    </div>
  </div>
</section>

<section>
  <div class="container">
    <div class="section-head reveal">
      <span class="eyebrow">Support</span>
      <h2>What works where, today.</h2>
      <p class="lede">Android and Chromium browsers support the full set. Safari on iPhone and iPad supports most of it, with the differences below.</p>
    </div>
    <div class="table-wrap reveal">
      <table class="table-compare">
        <thead><tr><th>Capability</th><th>Android &amp; desktop (Chrome / Edge)</th><th>iPhone &amp; iPad (Safari)</th></tr></thead>
        <tbody>
          <tr><td>Install to home screen</td><td><strong>Browser prompts to install</strong></td><td>Manual, from the Share menu</td></tr>
          <tr><td>Opens in its own window</td><td>Yes</td><td>Yes</td></tr>
          <tr><td>Offline via service worker</td><td>Yes</td><td>Yes</td></tr>
          <tr><td>Push notifications</td><td>Yes</td><td>iOS / iPadOS 16.4+, only once installed</td></tr>
          <tr><td>Background sync</td><td>Yes</td><td>No</td></tr>
          <tr><td>Listed in an app store</td><td>Optional, via Google Play (TWA)</td><td>Not available</td></tr>
        </tbody>
      </table>
    </div>
    <p style="margin-top:16px;font-size:.85rem;color:var(--ink-soft);">Browser support changes over time. We confirm current behaviour on real devices during the build.</p>
  </div>
</section>

<section>
  <div class="container">
    <div class="cta-band reveal">
      <h2>Not sure a PWA is the right fit?</h2>
      <p class="lede">Tell us what the app needs to do, and we'll tell you honestly whether a PWA, a plain website or a native app makes more sense.</p>
      <div class="hero-cta"><a href="<?= url('/contact.php') ?>" class="btn btn-primary">Get started</a></div>
    </div>
  </div>
</section>

<?php require __DIR__ . '/../partials/footer.php'; ?>
