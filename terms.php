<?php
$page = [
  'title'       => 'Terms of Use | Eformics Systems',
  'description' => 'The terms that govern use of the Eformics Systems website. Paid engagements and our SaaS products are covered by separate agreements.',
  'canonical'   => '/terms.php',
];
require __DIR__ . '/partials/head.php';
?>

<?php $ph = page_hero('terms'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / Terms of Use</div>
      <h1>Terms of Use</h1>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>
<section>
  <div class="container legal">
    <p class="legal-meta">Last updated: 8 September 2026</p>
    <p class="lede">These Terms of Use govern your access to and use of the Eformics Systems website. Paid engagements and our SaaS products are covered by separate agreements, described below.</p>

    <div class="legal-note">
      These Terms apply to the <strong>www.eformics.com</strong> website only. If we do paid work for you, that work is governed by a signed proposal, statement of work or services agreement. Our products &mdash; <strong>Meccora</strong>, <strong>Quotaire</strong> and <strong>Chantley</strong> &mdash; are governed by their own terms of service on their own websites.
    </div>

    <nav class="toc" aria-label="Table of contents">
      <h2>On this page</h2>
      <ol class="list">
        <li><a href="#t-accept">Acceptance of these terms</a></li>
        <li><a href="#t-who">Who we are</a></li>
        <li><a href="#t-scope">Scope: website, services and products</a></li>
        <li><a href="#t-use">Use of the website</a></li>
        <li><a href="#t-ip">Intellectual property</a></li>
        <li><a href="#t-quotes">Quotes, estimates and proposals</a></li>
        <li><a href="#t-thirdparty">Third-party content and links</a></li>
        <li><a href="#t-submissions">Your submissions</a></li>
        <li><a href="#t-availability">Availability of the website</a></li>
        <li><a href="#t-disclaimer">Disclaimers</a></li>
        <li><a href="#t-liability">Limitation of liability</a></li>
        <li><a href="#t-indemnity">Indemnification</a></li>
        <li><a href="#t-privacy">Privacy</a></li>
        <li><a href="#t-changes">Changes to these terms</a></li>
        <li><a href="#t-law">Governing law and jurisdiction</a></li>
        <li><a href="#t-general">General</a></li>
        <li><a href="#t-contact">How to contact us</a></li>
      </ol>
    </nav>

    <h2 class="legal-h" id="t-accept">1. Acceptance of these terms</h2>
    <p>These Terms of Use (&ldquo;Terms&rdquo;) govern your access to and use of the website at www.eformics.com (the &ldquo;Site&rdquo;). By accessing or using the Site, you agree to be bound by these Terms and by our <a href="<?= url('/privacy-policy.php') ?>">Privacy Policy</a>. If you do not agree, please do not use the Site.</p>

    <h2 class="legal-h" id="t-who">2. Who we are</h2>
    <p>The Site is operated by Eformics Systems (&ldquo;Eformics&rdquo;, &ldquo;we&rdquo;, &ldquo;us&rdquo; or &ldquo;our&rdquo;), a software and web development company based in Mississauga, Ontario, Canada.</p>

    <h2 class="legal-h" id="t-scope">3. Scope: website, services and products</h2>
    <ul>
      <li><strong>The Site.</strong> These Terms apply to your use of the Site as an informational resource.</li>
      <li><strong>Client services.</strong> Custom development, website design, support and related work are provided only under a separate written agreement &mdash; a proposal, statement of work and/or master services agreement signed by both parties. Nothing on the Site or in these Terms creates such an agreement, or any obligation on us to provide services.</li>
      <li><strong>Our products.</strong> Meccora, Quotaire and Chantley are provided under their own terms of service and privacy policies, published on their respective websites. These Terms do not govern your use of those products.</li>
    </ul>

    <h2 class="legal-h" id="t-use">4. Use of the website</h2>
    <p>We grant you a limited, non-exclusive, non-transferable and revocable licence to access and use the Site for your own informational and business purposes. You agree not to:</p>
    <ul>
      <li>use the Site in any way that breaches applicable laws or regulations;</li>
      <li>copy, reproduce, republish, scrape, frame or redistribute Site content except as allowed in section 5;</li>
      <li>attempt to gain unauthorized access to the Site, its servers or any connected systems or networks;</li>
      <li>introduce malware or otherwise interfere with the proper working of the Site;</li>
      <li>use automated systems in a way that places an unreasonable load on our infrastructure;</li>
      <li>use the Site to transmit unsolicited or unlawful communications.</li>
    </ul>

    <h2 class="legal-h" id="t-ip">5. Intellectual property</h2>
    <p>All content on the Site &mdash; text, graphics, logos, page layouts, code and design &mdash; is owned by or licensed to Eformics and is protected by copyright, trademark and other laws. &ldquo;Eformics&rdquo;, &ldquo;Eformics Systems&rdquo;, &ldquo;Meccora&rdquo;, &ldquo;Quotaire&rdquo; and &ldquo;Chantley&rdquo;, together with their associated logos, are trademarks of Eformics Systems.</p>
    <p>You may view and print pages from the Site for your own reference. You may not use our content or trademarks for commercial purposes without our prior written permission.</p>

    <h2 class="legal-h" id="t-quotes">6. Quotes, estimates and proposals</h2>
    <p>Any pricing, timelines or scope shown on the Site or shared in preliminary discussions is indicative only and does not constitute a binding offer. A binding engagement arises only when its terms are set out in a written agreement signed by both parties.</p>

    <h2 class="legal-h" id="t-thirdparty">7. Third-party content and links</h2>
    <p>The Site may reference or link to third-party websites, products and services. We provide these for convenience only. We do not control or endorse them and are not responsible for their content, policies or practices. Your use of any third-party site is at your own risk and subject to that site's terms.</p>

    <h2 class="legal-h" id="t-submissions">8. Your submissions</h2>
    <p>If you send us ideas, feedback, requirements or other materials through the Site (for example, through the contact form), you confirm that you have the right to share them, and you grant us a non-exclusive, royalty-free licence to use that material to respond to you and, where applicable, to deliver services you request.</p>
    <p>Please do not send confidential or sensitive information through the contact form. Once an engagement is underway we will agree a secure channel for exchanging that material.</p>

    <h2 class="legal-h" id="t-availability">9. Availability of the website</h2>
    <p>We aim to keep the Site available but do not guarantee uninterrupted or error-free access. We may modify, suspend or discontinue any part of the Site at any time without notice.</p>

    <h2 class="legal-h" id="t-disclaimer">10. Disclaimers</h2>
    <p>The Site and its content are provided &ldquo;as is&rdquo; and &ldquo;as available&rdquo;, without warranties of any kind, whether express or implied, including implied warranties of merchantability, fitness for a particular purpose, non-infringement, and the accuracy or completeness of content. Information on the Site is general in nature and does not constitute professional, legal or technical advice.</p>

    <h2 class="legal-h" id="t-liability">11. Limitation of liability</h2>
    <p>To the fullest extent permitted by law, Eformics and its directors, officers, employees and contractors will not be liable for any indirect, incidental, special, consequential or punitive damages, or for any loss of profits, revenue, data or goodwill, arising out of or relating to your use of, or inability to use, the Site &mdash; even if we have been advised of the possibility.</p>
    <p>Our total aggregate liability arising out of or relating to the Site will not exceed CAD&nbsp;$100. Liability arising from a paid engagement is addressed exclusively in the applicable services agreement. Nothing in these Terms excludes or limits liability that cannot be excluded or limited under applicable law.</p>

    <h2 class="legal-h" id="t-indemnity">12. Indemnification</h2>
    <p>You agree to indemnify and hold harmless Eformics from any claims, losses, liabilities and expenses (including reasonable legal fees) arising from your misuse of the Site or your breach of these Terms.</p>

    <h2 class="legal-h" id="t-privacy">13. Privacy</h2>
    <p>Your use of the Site is also governed by our <a href="<?= url('/privacy-policy.php') ?>">Privacy Policy</a>, which explains how we collect and handle personal information.</p>

    <h2 class="legal-h" id="t-changes">14. Changes to these terms</h2>
    <p>We may update these Terms from time to time. The &ldquo;Last updated&rdquo; date at the top of this page shows the current version. If you continue to use the Site after changes are posted, you accept the revised Terms.</p>

    <h2 class="legal-h" id="t-law">15. Governing law and jurisdiction</h2>
    <p>These Terms are governed by the laws of the Province of Ontario and the federal laws of Canada that apply there, without regard to conflict-of-laws rules. You agree that the courts located in Ontario, Canada have exclusive jurisdiction over any dispute arising out of or relating to the Site or these Terms.</p>

    <h2 class="legal-h" id="t-general">16. General</h2>
    <ul>
      <li>If any provision of these Terms is found to be unenforceable, the remaining provisions stay in full effect.</li>
      <li>These Terms and the Privacy Policy are the entire agreement between you and Eformics regarding the Site.</li>
      <li>Our failure to enforce any provision is not a waiver of it.</li>
      <li>You may not assign your rights under these Terms. We may assign ours in connection with a reorganization, merger or sale of our business.</li>
      <li>We are not responsible for any failure or delay caused by events beyond our reasonable control.</li>
    </ul>

    <h2 class="legal-h" id="t-contact">17. How to contact us</h2>
    <p>Questions about these Terms can be sent to:</p>
    <div class="legal-contact">
      <p><strong>Eformics Systems</strong></p>
      <p>Mississauga, Ontario, Canada</p>
      <p>Email: <a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></p>
      <p>Phone: <a href="<?= SITE_PHONE_HREF ?>"><?= SITE_PHONE ?></a></p>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
