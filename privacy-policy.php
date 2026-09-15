<?php
$page = [
  'title'       => 'Privacy Policy | Eformics Systems',
  'description' => "How Eformics Systems collects, uses, discloses and protects personal information through its website and client relationships, in line with Canada's PIPEDA.",
  'canonical'   => '/privacy-policy.php',
];
require __DIR__ . '/partials/head.php';
?>

<?php $ph = page_hero('privacy-policy'); ?>
<section class="page-hero">
  <div class="container<?= $ph !== '' ? ' page-hero-grid' : '' ?>">
    <?php if ($ph !== ''): ?><div><?php endif; ?>
      <div class="breadcrumb"><a href="<?= url('/') ?>">Home</a> / Privacy Policy</div>
      <h1>Privacy Policy</h1>
    <?php if ($ph !== ''): ?></div>
    <?= $ph ?>
    <?php endif; ?>
  </div>
</section>
<section>
  <div class="container legal">
    <p class="legal-meta">Last updated: 8 September 2026</p>
    <p class="lede">This Privacy Policy explains how Eformics Systems collects, uses, discloses and protects personal information through this website and in our dealings with clients and prospective clients.</p>

    <div class="legal-note">
      Our SaaS products &mdash; <strong>Meccora</strong>, <strong>Quotaire</strong> and <strong>Chantley</strong> &mdash; are operated as separate services, each with its own privacy policy and terms published on its own website. This policy covers the Eformics Systems website at <strong>www.eformics.com</strong> and our direct client relationships only.
    </div>

    <nav class="toc" aria-label="Table of contents">
      <h2>On this page</h2>
      <ol class="list">
        <li><a href="#pp-about">Who we are</a></li>
        <li><a href="#pp-scope">What this policy covers</a></li>
        <li><a href="#pp-collect">Information we collect</a></li>
        <li><a href="#pp-use">How we use information</a></li>
        <li><a href="#pp-consent">Your consent</a></li>
        <li><a href="#pp-cookies">Cookies and analytics</a></li>
        <li><a href="#pp-share">How we share information</a></li>
        <li><a href="#pp-retention">How long we keep information</a></li>
        <li><a href="#pp-security">How we protect information</a></li>
        <li><a href="#pp-location">Where your information is processed</a></li>
        <li><a href="#pp-rights">Your rights and choices</a></li>
        <li><a href="#pp-links">Third-party links</a></li>
        <li><a href="#pp-children">Children's privacy</a></li>
        <li><a href="#pp-changes">Changes to this policy</a></li>
        <li><a href="#pp-contact">How to contact us</a></li>
      </ol>
    </nav>

    <h2 class="legal-h" id="pp-about">1. Who we are</h2>
    <p>Eformics Systems (&ldquo;Eformics&rdquo;, &ldquo;we&rdquo;, &ldquo;us&rdquo; or &ldquo;our&rdquo;) is a software and web development company based in Mississauga, Ontario, Canada. We have built websites and custom software for businesses since 2009, and we build and operate our own SaaS products.</p>
    <p>We handle personal information in accordance with Canada's <em>Personal Information Protection and Electronic Documents Act</em> (PIPEDA) and other privacy laws that apply to us. For the purposes of this policy, Eformics is the organization responsible for the personal information described below.</p>

    <h2 class="legal-h" id="pp-scope">2. What this policy covers</h2>
    <ul>
      <li><strong>This website.</strong> Information collected when you browse www.eformics.com or contact us through it.</li>
      <li><strong>Client and prospect relationships.</strong> Information we collect and use to prepare proposals, deliver projects, provide support and manage our business.</li>
    </ul>
    <p>This policy does <strong>not</strong> cover:</p>
    <ul>
      <li><strong>Our SaaS products.</strong> Meccora, Quotaire and Chantley each have their own privacy policy and terms on their own websites.</li>
      <li><strong>Data we process for a client.</strong> When we design, build, host or maintain software for a client, we may process personal information about that client's own customers or staff. In those cases the client decides how and why that data is used, the client's privacy notice applies to those individuals, and Eformics acts as the client's service provider under a written agreement. We use that data only to perform the services and on the client's instructions.</li>
    </ul>

    <h2 class="legal-h" id="pp-collect">3. Information we collect</h2>
    <h3>Information you give us</h3>
    <ul>
      <li><strong>Enquiries.</strong> When you complete our contact form, email us or call us, we collect your name, email address, phone number (if you provide it), the product or service you're interested in, and the content of your message.</li>
      <li><strong>Client information.</strong> If you engage us, we collect what we need to deliver and manage the work &mdash; business and billing contact details, project requirements and materials, and any access credentials you choose to share with us.</li>
      <li><strong>Recruitment.</strong> If you send us a r&eacute;sum&eacute; or application, we collect the information it contains.</li>
    </ul>
    <h3>Information we collect automatically</h3>
    <ul>
      <li><strong>Log and device data.</strong> IP address, browser type and version, operating system, referring page, pages viewed and date/time stamps.</li>
      <li><strong>Cookies and similar technologies.</strong> A small number of cookies and, where enabled, privacy-respecting analytics. See section 6.</li>
    </ul>
    <h3>Information from other sources</h3>
    <p>We may receive limited information about you from a colleague who refers you, from publicly available business sources, or from our service providers (for example, delivery or security information from our email or hosting providers).</p>

    <h2 class="legal-h" id="pp-use">4. How we use information</h2>
    <ul>
      <li>Respond to enquiries and provide the information, proposals and quotes you ask for;</li>
      <li>Provide, manage and support the services our clients engage us to deliver;</li>
      <li>Send administrative messages, project updates and service notices;</li>
      <li>Send occasional company updates where you have asked to receive them (you can opt out at any time);</li>
      <li>Operate, secure, maintain and improve this website;</li>
      <li>Manage invoicing, payments, accounting and our business records;</li>
      <li>Detect, prevent and respond to fraud, abuse, security incidents and technical problems;</li>
      <li>Comply with legal obligations and enforce our agreements.</li>
    </ul>

    <h2 class="legal-h" id="pp-consent">5. Your consent</h2>
    <p>We collect, use and disclose personal information with your knowledge and consent, except where the law permits or requires otherwise. By submitting information through this website, you consent to us handling it as described in this policy.</p>
    <p>You may withdraw your consent at any time, subject to legal and contractual limits and on reasonable notice, by contacting us. If you withdraw consent, we may no longer be able to provide certain services or respond to your request.</p>

    <h2 class="legal-h" id="pp-cookies">6. Cookies and analytics</h2>
    <ul>
      <li><strong>Strictly necessary cookies</strong> keep the site working &mdash; for example, security and load balancing.</li>
      <li><strong>Analytics and performance cookies</strong>, where used, help us understand in aggregate how the site is used so we can improve it.</li>
      <li>We do <strong>not</strong> use advertising cookies and we do <strong>not</strong> sell data to advertisers.</li>
    </ul>
    <p>Most browsers let you refuse or delete cookies through their settings. Blocking some cookies may affect how the site works.</p>

    <h2 class="legal-h" id="pp-share">7. How we share information</h2>
    <p>We do not sell your personal information. We share it only in these situations:</p>
    <ul>
      <li><strong>Service providers.</strong> Hosting and infrastructure, email delivery, analytics, payment processing and similar vendors that process information on our behalf under contract and may not use it for their own purposes.</li>
      <li><strong>Clients.</strong> Where your enquiry relates to a specific client project, or where the information forms part of work we perform for a client.</li>
      <li><strong>Professional advisors.</strong> Lawyers, accountants, auditors and insurers, as needed.</li>
      <li><strong>Legal and safety.</strong> Where required by law, regulation, court order or a regulator, or to protect the rights, property or safety of Eformics, our clients or others.</li>
      <li><strong>Business transfers.</strong> In connection with a merger, acquisition, financing or sale of assets, subject to appropriate confidentiality protections.</li>
    </ul>

    <h2 class="legal-h" id="pp-retention">8. How long we keep information</h2>
    <p>We keep personal information only as long as we need it for the purposes set out above, including to meet legal, tax, accounting and contractual requirements. Enquiries that do not lead to an engagement are kept for a limited period and then deleted or anonymized. Client project records are kept for the life of the engagement and for a reasonable period afterward.</p>

    <h2 class="legal-h" id="pp-security">9. How we protect information</h2>
    <p>We use administrative, technical and physical safeguards appropriate to the sensitivity of the information, including access controls, encryption of data in transit, restricted credentials, logging and vendor due diligence. No method of transmission or storage is completely secure, so we cannot guarantee absolute security.</p>

    <h2 class="legal-h" id="pp-location">10. Where your information is processed</h2>
    <p>We are based in Canada and prefer Canadian or otherwise appropriate hosting. Some of our service providers may store or process information outside Canada, including in the United States. Where that happens, the information may be accessible to courts, law enforcement and regulatory authorities in those countries, and we take reasonable steps to ensure it remains protected to a comparable standard.</p>

    <h2 class="legal-h" id="pp-rights">11. Your rights and choices</h2>
    <p>Subject to applicable law, you may:</p>
    <ul>
      <li>Ask what personal information we hold about you and request access to it;</li>
      <li>Ask us to correct information that is inaccurate or incomplete;</li>
      <li>Withdraw consent to future use, or unsubscribe from company updates;</li>
      <li>Ask us to delete information we are not required to keep.</li>
    </ul>
    <p>To make a request, contact us using the details in section 15. We may need to verify your identity before we act, and we will respond within the time limits set by law. If you are not satisfied with our response, you may contact the Office of the Privacy Commissioner of Canada at <a href="https://www.priv.gc.ca">priv.gc.ca</a>.</p>

    <h2 class="legal-h" id="pp-links">12. Third-party links</h2>
    <p>This website links to third-party sites and services, including meccora.com, quotaire.com and chantley.com and external tools we reference. We are not responsible for the privacy practices or content of those sites. Please review their policies separately.</p>

    <h2 class="legal-h" id="pp-children">13. Children's privacy</h2>
    <p>This website and our services are intended for businesses and adults. We do not knowingly collect personal information from children. If you believe a child has given us personal information, contact us and we will delete it.</p>

    <h2 class="legal-h" id="pp-changes">14. Changes to this policy</h2>
    <p>We may update this Privacy Policy from time to time. The &ldquo;Last updated&rdquo; date at the top of this page shows the current version. We will post material changes on this page.</p>

    <h2 class="legal-h" id="pp-contact">15. How to contact us</h2>
    <p>For any question about this policy or about how we handle your personal information, contact our Privacy Officer:</p>
    <div class="legal-contact">
      <p><strong>Eformics Systems &mdash; Privacy Officer</strong></p>
      <p>Mississauga, Ontario, Canada</p>
      <p>Email: <a href="mailto:<?= SITE_EMAIL ?>"><?= SITE_EMAIL ?></a></p>
      <p>Phone: <a href="<?= SITE_PHONE_HREF ?>"><?= SITE_PHONE ?></a></p>
    </div>
  </div>
</section>

<?php require __DIR__ . '/partials/footer.php'; ?>
