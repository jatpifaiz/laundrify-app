<?php
require_once 'config/session.php';
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['role'] === 'admin' ? '/laundrify-app/dashboard.php' : '/laundrify-app/user/index.php'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Laundrify — Laundry Lebih Mudah, Lebih Cepat</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
  <style>
    /* ── Variables ── */
    :root {
      --white:      #ffffff;
      --bg:         #f8fafc;
      --bg-card:    #ffffff;
      --gray-50:    #f8fafc;
      --gray-100:   #f1f5f9;
      --gray-200:   #e2e8f0;
      --gray-400:   #94a3b8;
      --gray-500:   #64748b;
      --gray-700:   #334155;
      --gray-900:   #0f172a;
      --blue:       #2563eb;
      --blue-dark:  #1d4ed8;
      --blue-light: #eff6ff;
      --cyan:       #0ea5e9;
      --cyan-light: #e0f2fe;
      --grad:       linear-gradient(135deg, #2563eb 0%, #0ea5e9 100%);
      --grad-soft:  linear-gradient(135deg, #eff6ff 0%, #e0f2fe 100%);
      --shadow-sm:  0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
      --shadow-md:  0 4px 16px rgba(0,0,0,.08);
      --shadow-lg:  0 12px 40px rgba(37,99,235,.15);
      --radius-sm:  10px;
      --radius-md:  16px;
      --radius-lg:  24px;
      --radius-xl:  32px;
      --transition: .25s cubic-bezier(.4,0,.2,1);
      --font-body:  'DM Sans', sans-serif;
      --font-disp:  'DM Serif Display', serif;
    }

    /* ── Reset ── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { font-size: 16px; scroll-behavior: smooth; }
    body { font-family: var(--font-body); background: var(--bg); color: var(--gray-900); line-height: 1.6; }
    a { text-decoration: none; color: inherit; }
    img { max-width: 100%; display: block; }

    /* ── Utilities ── */
    .container { max-width: 1160px; margin: 0 auto; padding: 0 24px; }
    .grad-text {
      background: var(--grad);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    /* ══════════════════════════════════
       NAVBAR
    ══════════════════════════════════ */
    .navbar {
      position: sticky;
      top: 0;
      z-index: 100;
      background: rgba(255,255,255,.85);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid var(--gray-200);
    }
    .navbar-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
      height: 68px;
    }
    .nav-brand {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .nav-brand .icon {
      width: 36px; height: 36px;
      background: var(--grad);
      border-radius: 10px;
      display: flex; align-items: center; justify-content: center;
    }
    .nav-brand .icon svg { color: white; }
    .nav-brand span {
      font-family: var(--font-disp);
      font-size: 1.3rem;
      color: var(--gray-900);
      letter-spacing: -.02em;
    }
    .nav-links {
      display: flex;
      align-items: center;
      gap: 4px;
    }
    .nav-links a {
      padding: 8px 16px;
      border-radius: var(--radius-sm);
      font-size: .875rem;
      font-weight: 500;
      color: var(--gray-500);
      transition: color var(--transition), background var(--transition);
    }
    .nav-links a:hover { background: var(--gray-100); color: var(--gray-900); }
    .nav-cta {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .btn-ghost {
      padding: 9px 20px;
      border-radius: var(--radius-sm);
      font-size: .875rem;
      font-weight: 500;
      color: var(--gray-700);
      border: 1.5px solid var(--gray-200);
      background: transparent;
      transition: all var(--transition);
      cursor: pointer;
      font-family: var(--font-body);
    }
    .btn-ghost:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-light); }
    .btn-primary {
      padding: 9px 22px;
      border-radius: var(--radius-sm);
      font-size: .875rem;
      font-weight: 600;
      color: white;
      background: var(--grad);
      border: none;
      cursor: pointer;
      font-family: var(--font-body);
      transition: all var(--transition);
      box-shadow: 0 2px 12px rgba(37,99,235,.3);
    }
    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(37,99,235,.4);
    }
    .btn-primary:active { transform: translateY(0); }
    .nav-hamburger {
      display: none;
      background: none;
      border: none;
      cursor: pointer;
      padding: 6px;
      border-radius: 8px;
      color: var(--gray-700);
    }
    .nav-hamburger:hover { background: var(--gray-100); }

    /* ══════════════════════════════════
       HERO
    ══════════════════════════════════ */
    .hero {
      padding: 96px 0 80px;
      overflow: hidden;
      position: relative;
    }
    .hero::before {
      content: '';
      position: absolute;
      top: -120px; right: -120px;
      width: 600px; height: 600px;
      background: radial-gradient(circle, rgba(14,165,233,.08) 0%, transparent 70%);
      border-radius: 50%;
      pointer-events: none;
    }
    .hero-inner {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 64px;
      align-items: center;
    }
    .hero-badge {
      display: inline-flex;
      align-items: center;
      gap: 7px;
      background: var(--grad-soft);
      border: 1px solid rgba(37,99,235,.15);
      border-radius: 99px;
      padding: 5px 14px 5px 7px;
      font-size: .78rem;
      font-weight: 600;
      color: var(--blue);
      margin-bottom: 24px;
    }
    .hero-badge .dot {
      width: 20px; height: 20px;
      background: var(--grad);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
    }
    .hero-badge .dot svg { width: 11px; height: 11px; color: white; }
    .hero-title {
      font-family: var(--font-disp);
      font-size: clamp(2.2rem, 5vw, 3.5rem);
      line-height: 1.12;
      letter-spacing: -.03em;
      color: var(--gray-900);
      margin-bottom: 20px;
    }
    .hero-desc {
      font-size: 1.05rem;
      color: var(--gray-500);
      line-height: 1.75;
      margin-bottom: 36px;
      max-width: 480px;
    }
    .hero-actions {
      display: flex;
      align-items: center;
      gap: 14px;
      flex-wrap: wrap;
    }
    .btn-hero {
      padding: 14px 32px;
      border-radius: var(--radius-md);
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      font-family: var(--font-body);
      transition: all var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .btn-hero.primary {
      background: var(--grad);
      color: white;
      border: none;
      box-shadow: 0 4px 20px rgba(37,99,235,.35);
    }
    .btn-hero.primary:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 30px rgba(37,99,235,.45);
    }
    .btn-hero.secondary {
      background: transparent;
      color: var(--gray-700);
      border: 1.5px solid var(--gray-200);
    }
    .btn-hero.secondary:hover {
      border-color: var(--blue);
      color: var(--blue);
      background: var(--blue-light);
    }
    .hero-trust {
      margin-top: 40px;
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .trust-avatars {
      display: flex;
    }
    .trust-avatar {
      width: 32px; height: 32px;
      border-radius: 50%;
      border: 2px solid white;
      background: var(--grad);
      margin-left: -8px;
      display: flex; align-items: center; justify-content: center;
      font-size: .7rem;
      font-weight: 700;
      color: white;
    }
    .trust-avatar:first-child { margin-left: 0; }
    .trust-text { font-size: .82rem; color: var(--gray-500); }
    .trust-text strong { color: var(--gray-900); }

    /* Hero Visual */
    .hero-visual {
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 460px;
    }
    .mockup-main {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-lg);
      padding: 24px;
      width: 280px;
      box-shadow: var(--shadow-lg);
      position: relative;
      z-index: 2;
      animation: floatMain 4s ease-in-out infinite;
    }
    @keyframes floatMain {
      0%, 100% { transform: translateY(0); }
      50%       { transform: translateY(-12px); }
    }
    .mockup-top {
      display: flex;
      align-items: center;
      gap: 8px;
      margin-bottom: 20px;
      padding-bottom: 16px;
      border-bottom: 1px solid var(--gray-100);
    }
    .mockup-logo-icon {
      width: 28px; height: 28px;
      background: var(--grad);
      border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
    }
    .mockup-logo-icon svg { width: 14px; height: 14px; color: white; }
    .mockup-brand { font-family: var(--font-disp); font-size: .95rem; }
    .mockup-label { font-size: .72rem; color: var(--gray-400); font-weight: 600; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 4px; }
    .mockup-price { font-family: var(--font-disp); font-size: 1.9rem; color: var(--gray-900); margin-bottom: 16px; }
    .mockup-item {
      display: flex;
      justify-content: space-between;
      font-size: .82rem;
      color: var(--gray-500);
      padding: 6px 0;
      border-bottom: 1px solid var(--gray-100);
    }
    .mockup-item:last-of-type { border-bottom: none; }
    .mockup-item span:last-child { color: var(--gray-900); font-weight: 500; }
    .mockup-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: #dcfce7;
      color: #15803d;
      font-size: .72rem;
      font-weight: 600;
      padding: 4px 10px;
      border-radius: 99px;
      margin-top: 14px;
    }
    .mockup-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #16a34a; }

    /* Floating Cards */
    .float-card {
      position: absolute;
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-sm);
      padding: 12px 16px;
      box-shadow: var(--shadow-md);
      font-size: .8rem;
      display: flex;
      align-items: center;
      gap: 10px;
      white-space: nowrap;
      z-index: 3;
    }
    .float-card .fc-icon {
      width: 34px; height: 34px;
      border-radius: 9px;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      font-size: 1rem;
    }
    .float-card .fc-label { font-size: .7rem; color: var(--gray-400); }
    .float-card .fc-value { font-weight: 600; color: var(--gray-900); font-size: .82rem; }

    .fc-1 { top: 40px; left: -20px; animation: floatA 3.5s ease-in-out infinite; }
    .fc-2 { bottom: 60px; left: -30px; animation: floatB 4.2s ease-in-out infinite; }
    .fc-3 { top: 80px; right: -30px; animation: floatC 3.8s ease-in-out infinite; }
    .fc-4 { bottom: 40px; right: -20px; animation: floatA 4.5s ease-in-out infinite reverse; }

    @keyframes floatA { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-8px)} }
    @keyframes floatB { 0%,100%{transform:translateY(0)} 50%{transform:translateY(8px)} }
    @keyframes floatC { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-6px)} }

    .hero-blob {
      position: absolute;
      width: 360px; height: 360px;
      background: var(--grad-soft);
      border-radius: 50%;
      z-index: 0;
      filter: blur(2px);
    }

    /* ══════════════════════════════════
       STATS
    ══════════════════════════════════ */
    .stats {
      padding: 48px 0;
      border-top: 1px solid var(--gray-200);
      border-bottom: 1px solid var(--gray-200);
      background: white;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 0;
    }
    .stat-item {
      text-align: center;
      padding: 20px 16px;
      border-right: 1px solid var(--gray-200);
    }
    .stat-item:last-child { border-right: none; }
    .stat-num {
      font-family: var(--font-disp);
      font-size: 2.2rem;
      background: var(--grad);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      line-height: 1;
      margin-bottom: 6px;
    }
    .stat-desc { font-size: .85rem; color: var(--gray-500); font-weight: 500; }

    /* ══════════════════════════════════
       FEATURES
    ══════════════════════════════════ */
    .features { padding: 96px 0; }
    .section-label {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .1em;
      text-transform: uppercase;
      color: var(--blue);
      margin-bottom: 14px;
    }
    .section-label::before {
      content: '';
      width: 20px; height: 2px;
      background: var(--grad);
      border-radius: 2px;
    }
    .section-title {
      font-family: var(--font-disp);
      font-size: clamp(1.8rem, 3.5vw, 2.6rem);
      letter-spacing: -.02em;
      color: var(--gray-900);
      line-height: 1.2;
      margin-bottom: 16px;
    }
    .section-desc {
      font-size: 1rem;
      color: var(--gray-500);
      max-width: 520px;
      line-height: 1.75;
    }
    .section-header { margin-bottom: 56px; }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 24px;
    }
    .feature-card {
      background: white;
      border: 1px solid var(--gray-200);
      border-radius: var(--radius-md);
      padding: 32px 28px;
      transition: all var(--transition);
      position: relative;
      overflow: hidden;
    }
    .feature-card::after {
      content: '';
      position: absolute;
      inset: 0;
      background: var(--grad-soft);
      opacity: 0;
      transition: opacity var(--transition);
    }
    .feature-card:hover {
      border-color: rgba(37,99,235,.25);
      transform: translateY(-4px);
      box-shadow: var(--shadow-lg);
    }
    .feature-card:hover::after { opacity: 1; }
    .feature-card > * { position: relative; z-index: 1; }
    .feat-icon {
      width: 48px; height: 48px;
      background: var(--grad-soft);
      border-radius: 12px;
      display: flex; align-items: center; justify-content: center;
      margin-bottom: 20px;
      transition: all var(--transition);
    }
    .feature-card:hover .feat-icon {
      background: var(--grad);
    }
    .feat-icon svg { width: 22px; height: 22px; color: var(--blue); transition: color var(--transition); }
    .feature-card:hover .feat-icon svg { color: white; }
    .feat-title { font-size: 1rem; font-weight: 600; color: var(--gray-900); margin-bottom: 8px; }
    .feat-desc { font-size: .875rem; color: var(--gray-500); line-height: 1.65; }

    /* ══════════════════════════════════
       HOW IT WORKS
    ══════════════════════════════════ */
    .how { padding: 96px 0; background: white; }
    .steps {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 40px;
      position: relative;
    }
    .steps::before {
      content: '';
      position: absolute;
      top: 28px;
      left: calc(16.66% + 28px);
      right: calc(16.66% + 28px);
      height: 2px;
      background: linear-gradient(to right, var(--blue), var(--cyan));
      border-radius: 2px;
    }
    .step { text-align: center; }
    .step-num {
      width: 56px; height: 56px;
      background: var(--grad);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-disp);
      font-size: 1.4rem;
      color: white;
      margin: 0 auto 24px;
      box-shadow: 0 4px 16px rgba(37,99,235,.3);
      position: relative;
      z-index: 1;
    }
    .step-title { font-size: 1.05rem; font-weight: 600; color: var(--gray-900); margin-bottom: 10px; }
    .step-desc { font-size: .875rem; color: var(--gray-500); line-height: 1.65; }

    /* ══════════════════════════════════
       MEMBERSHIP
    ══════════════════════════════════ */
    .membership { padding: 96px 0; }
    .pricing-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 28px;
      max-width: 780px;
      margin: 0 auto;
    }
    .pricing-card {
      background: white;
      border: 1.5px solid var(--gray-200);
      border-radius: var(--radius-lg);
      padding: 40px 36px;
      transition: all var(--transition);
      position: relative;
      overflow: hidden;
    }
    .pricing-card.featured {
      border-color: var(--blue);
      box-shadow: 0 8px 32px rgba(37,99,235,.18);
    }
    .pricing-card.featured::before {
      content: '';
      position: absolute;
      top: 0; left: 0; right: 0;
      height: 4px;
      background: var(--grad);
    }
    .pricing-badge {
      display: inline-block;
      background: var(--grad);
      color: white;
      font-size: .72rem;
      font-weight: 700;
      padding: 3px 12px;
      border-radius: 99px;
      margin-bottom: 20px;
      letter-spacing: .05em;
    }
    .pricing-name {
      font-family: var(--font-disp);
      font-size: 1.4rem;
      color: var(--gray-900);
      margin-bottom: 6px;
    }
    .pricing-tagline { font-size: .875rem; color: var(--gray-500); margin-bottom: 28px; }
    .pricing-discount {
      font-family: var(--font-disp);
      font-size: 2.8rem;
      line-height: 1;
      margin-bottom: 6px;
    }
    .pricing-discount span { font-size: 1rem; font-family: var(--font-body); color: var(--gray-500); }
    .pricing-note { font-size: .8rem; color: var(--gray-400); margin-bottom: 28px; }
    .pricing-divider { height: 1px; background: var(--gray-200); margin: 24px 0; }
    .pricing-perks { list-style: none; display: flex; flex-direction: column; gap: 12px; margin-bottom: 32px; }
    .pricing-perks li {
      display: flex;
      align-items: flex-start;
      gap: 10px;
      font-size: .875rem;
      color: var(--gray-700);
    }
    .pricing-perks li .check {
      width: 18px; height: 18px;
      background: var(--grad-soft);
      border-radius: 50%;
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
      margin-top: 1px;
    }
    .pricing-perks li .check svg { width: 10px; height: 10px; color: var(--blue); }
    .pricing-perks li.muted { color: var(--gray-400); }
    .pricing-perks li.muted .check { background: var(--gray-100); }
    .pricing-perks li.muted .check svg { color: var(--gray-400); }
    .btn-pricing {
      width: 100%;
      padding: 13px;
      border-radius: var(--radius-sm);
      font-size: .95rem;
      font-weight: 600;
      font-family: var(--font-body);
      cursor: pointer;
      transition: all var(--transition);
      border: none;
      display: block;
      text-align: center;
    }
    .btn-pricing.outlined {
      background: transparent;
      border: 1.5px solid var(--gray-200);
      color: var(--gray-700);
    }
    .btn-pricing.outlined:hover { border-color: var(--blue); color: var(--blue); background: var(--blue-light); }
    .btn-pricing.filled {
      background: var(--grad);
      color: white;
      box-shadow: 0 4px 16px rgba(37,99,235,.3);
    }
    .btn-pricing.filled:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(37,99,235,.4); }

    /* ══════════════════════════════════
       CTA BANNER
    ══════════════════════════════════ */
    .cta-section {
      padding: 80px 0;
      background: var(--gray-900);
      position: relative;
      overflow: hidden;
    }
    .cta-section::before {
      content: '';
      position: absolute;
      top: -100px; left: -100px;
      width: 400px; height: 400px;
      background: radial-gradient(circle, rgba(37,99,235,.3), transparent 70%);
      pointer-events: none;
    }
    .cta-section::after {
      content: '';
      position: absolute;
      bottom: -80px; right: -80px;
      width: 350px; height: 350px;
      background: radial-gradient(circle, rgba(14,165,233,.25), transparent 70%);
      pointer-events: none;
    }
    .cta-inner { text-align: center; position: relative; z-index: 1; }
    .cta-inner h2 {
      font-family: var(--font-disp);
      font-size: clamp(1.8rem, 4vw, 2.8rem);
      color: white;
      letter-spacing: -.02em;
      margin-bottom: 16px;
      line-height: 1.2;
    }
    .cta-inner p { font-size: 1rem; color: var(--gray-400); margin-bottom: 36px; max-width: 440px; margin-left: auto; margin-right: auto; }
    .cta-actions { display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; }
    .btn-cta {
      padding: 14px 32px;
      border-radius: var(--radius-md);
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      font-family: var(--font-body);
      transition: all var(--transition);
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .btn-cta.white {
      background: white;
      color: var(--blue);
      border: none;
      box-shadow: 0 4px 16px rgba(0,0,0,.2);
    }
    .btn-cta.white:hover { transform: translateY(-3px); box-shadow: 0 8px 28px rgba(0,0,0,.25); }
    .btn-cta.outline-white {
      background: transparent;
      color: white;
      border: 1.5px solid rgba(255,255,255,.3);
    }
    .btn-cta.outline-white:hover { border-color: white; background: rgba(255,255,255,.08); }

    /* ══════════════════════════════════
       FOOTER
    ══════════════════════════════════ */
    footer {
      background: var(--gray-900);
      border-top: 1px solid rgba(255,255,255,.07);
      padding: 48px 0 32px;
    }
    .footer-inner {
      display: grid;
      grid-template-columns: 2fr 1fr 1fr;
      gap: 48px;
      margin-bottom: 40px;
    }
    .footer-brand .brand-name {
      font-family: var(--font-disp);
      font-size: 1.3rem;
      color: white;
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      gap: 9px;
    }
    .footer-brand .brand-icon {
      width: 28px; height: 28px;
      background: var(--grad);
      border-radius: 7px;
      display: flex; align-items: center; justify-content: center;
    }
    .footer-brand .brand-icon svg { width: 14px; height: 14px; color: white; }
    .footer-brand p { font-size: .875rem; color: var(--gray-400); line-height: 1.65; max-width: 300px; }
    .footer-col h4 { font-size: .8rem; font-weight: 700; color: white; letter-spacing: .08em; text-transform: uppercase; margin-bottom: 16px; }
    .footer-col ul { list-style: none; display: flex; flex-direction: column; gap: 10px; }
    .footer-col ul li a { font-size: .875rem; color: var(--gray-400); transition: color var(--transition); }
    .footer-col ul li a:hover { color: white; }
    .footer-bottom {
      border-top: 1px solid rgba(255,255,255,.07);
      padding-top: 24px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
    }
    .footer-bottom p { font-size: .8rem; color: var(--gray-400); }
    .footer-bottom a { color: var(--cyan); }

    /* ══════════════════════════════════
       RESPONSIVE
    ══════════════════════════════════ */
    @media (max-width: 1024px) {
      .hero-inner { grid-template-columns: 1fr; gap: 48px; }
      .hero-visual { display: none; }
      .hero { padding: 72px 0 64px; }
      .hero-title { font-size: 2.8rem; }
      .features-grid { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 768px) {
      .nav-links { display: none; }
      .nav-hamburger { display: flex; }
      .nav-cta .btn-ghost { display: none; }
      .hero { padding: 56px 0 48px; }
      .hero-title { font-size: 2.2rem; }
      .stats-grid { grid-template-columns: 1fr 1fr; }
      .stat-item:nth-child(2) { border-right: none; }
      .stat-item:nth-child(3) { border-top: 1px solid var(--gray-200); }
      .stat-item:nth-child(4) { border-top: 1px solid var(--gray-200); border-right: none; }
      .features-grid { grid-template-columns: 1fr; }
      .steps { grid-template-columns: 1fr; gap: 32px; }
      .steps::before { display: none; }
      .pricing-grid { grid-template-columns: 1fr; max-width: 420px; }
      .footer-inner { grid-template-columns: 1fr; gap: 32px; }
      .footer-inner > div:first-child { grid-column: 1; }
    }
    @media (max-width: 480px) {
      .hero-title { font-size: 1.9rem; }
      .btn-hero { padding: 12px 22px; font-size: .9rem; }
      .hero-actions { flex-direction: column; }
      .btn-hero { width: 100%; justify-content: center; }
      .cta-actions { flex-direction: column; align-items: center; }
      .btn-cta { width: 100%; max-width: 320px; justify-content: center; }
      .stats-grid { grid-template-columns: 1fr; }
      .stat-item { border-right: none; border-top: 1px solid var(--gray-200); }
      .stat-item:first-child { border-top: none; }
    }
  </style>
</head>
<body>

<!-- ══════════════════════
     NAVBAR
══════════════════════ -->
<header>
  <nav class="navbar">
    <div class="container navbar-inner">
      <a href="/laundrify-app/landing.php" class="nav-brand">
        <div class="icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
          </svg>
        </div>
        <span>Laundrify</span>
      </a>

      <div class="nav-links">
        <a href="#features">Fitur</a>
        <a href="#cara-kerja">Cara Kerja</a>
        <a href="#membership">Membership</a>
      </div>

      <div class="nav-cta">
        <a href="/laundrify-app/auth/register.php" class="btn-ghost">Daftar</a>
        <a href="/laundrify-app/auth/login.php" class="btn-primary">Masuk &rarr;</a>
      </div>

      <button class="nav-hamburger" id="nav-hamburger" aria-label="Menu">
        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
        </svg>
      </button>
    </div>
  </nav>
</header>

<main>

<!-- ══════════════════════
     HERO
══════════════════════ -->
<section class="hero" id="home">
  <div class="container">
    <div class="hero-inner">

      <div class="hero-content">
        <div class="hero-badge">
          <div class="dot">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
              <path fill-rule="evenodd" d="M10.788 3.21c.448-1.077 1.976-1.077 2.424 0l2.082 5.007 5.404.433c1.164.093 1.636 1.545.749 2.305l-4.117 3.527 1.257 5.273c.271 1.136-.964 2.033-1.96 1.425L12 18.354 7.373 21.18c-.996.608-2.231-.29-1.96-1.425l1.257-5.273-4.117-3.527c-.887-.76-.415-2.212.749-2.305l5.404-.433 2.082-5.006z" clip-rule="evenodd"/>
            </svg>
          </div>
          Sistem Laundry Terpercaya
        </div>

        <h1 class="hero-title">
          Laundry Lebih<br>
          <span class="grad-text">Mudah & Cepat</span><br>
          Dari Mana Saja
        </h1>

        <p class="hero-desc">
          Laundrify menghadirkan pengalaman laundry modern — pesan secara mandiri, pantau status real-time, dan nikmati diskon eksklusif untuk pelanggan member.
        </p>

        <div class="hero-actions">
          <a href="/laundrify-app/auth/register.php" class="btn-hero primary">
            Mulai Sekarang
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
            </svg>
          </a>
          <a href="#cara-kerja" class="btn-hero secondary">
            Pelajari Lebih Lanjut
          </a>
        </div>

        <div class="hero-trust">
          <div class="trust-avatars">
            <div class="trust-avatar">A</div>
            <div class="trust-avatar" style="background:linear-gradient(135deg,#06b6d4,#2563eb)">B</div>
            <div class="trust-avatar" style="background:linear-gradient(135deg,#8b5cf6,#06b6d4)">C</div>
            <div class="trust-avatar" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">D</div>
          </div>
          <p class="trust-text"><strong>50+ pelanggan</strong> sudah mempercayai Laundrify</p>
        </div>
      </div>

      <!-- App Mockup -->
      <div class="hero-visual" aria-hidden="true">
        <div class="hero-blob"></div>

        <div class="mockup-main">
          <div class="mockup-top">
            <div class="mockup-logo-icon">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
              </svg>
            </div>
            <span class="mockup-brand">Laundrify</span>
          </div>
          <div class="mockup-label">Estimasi Total</div>
          <div class="mockup-price">Rp 36.000</div>
          <div class="mockup-item"><span>Cuci + Setrika</span><span>4 kg</span></div>
          <div class="mockup-item"><span>Diskon Member</span><span style="color:#2563eb">−5%</span></div>
          <div class="mockup-item"><span>Total Bayar</span><span>Rp 34.200</span></div>
          <div class="mockup-badge">Sedang Diproses</div>
        </div>

        <div class="float-card fc-1">
          <div class="fc-icon" style="background:#dcfce7">📦</div>
          <div>
            <div class="fc-label">Status</div>
            <div class="fc-value">2 Pesanan Aktif</div>
          </div>
        </div>

        <div class="float-card fc-2">
          <div class="fc-icon" style="background:#ede9fe">⭐</div>
          <div>
            <div class="fc-label">Membership</div>
            <div class="fc-value">Member — Diskon 5%</div>
          </div>
        </div>

        <div class="float-card fc-3">
          <div class="fc-icon" style="background:#fef3c7">✓</div>
          <div>
            <div class="fc-label">Notifikasi</div>
            <div class="fc-value">Pesanan Selesai!</div>
          </div>
        </div>

        <div class="float-card fc-4">
          <div class="fc-icon" style="background:#e0f2fe">🕐</div>
          <div>
            <div class="fc-label">Estimasi</div>
            <div class="fc-value">Siap Besok</div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- ══════════════════════
     STATS
══════════════════════ -->
<section class="stats">
  <div class="container">
    <div class="stats-grid">
      <div class="stat-item">
        <div class="stat-num">50+</div>
        <div class="stat-desc">Pelanggan Aktif</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">8</div>
        <div class="stat-desc">Jenis Layanan</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">200+</div>
        <div class="stat-desc">Transaksi Selesai</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">98%</div>
        <div class="stat-desc">Kepuasan Pelanggan</div>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════
     FEATURES
══════════════════════ -->
<section class="features" id="features">
  <div class="container">
    <div class="section-header">
      <div class="section-label">Fitur Unggulan</div>
      <h2 class="section-title">Semua yang Kamu Butuhkan<br>Ada di Satu Tempat</h2>
      <p class="section-desc">Dari booking hingga pembayaran, Laundrify menyederhanakan setiap langkah pengalaman laundry Anda.</p>
    </div>

    <div class="features-grid">

      <article class="feature-card">
        <div class="feat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
          </svg>
        </div>
        <h3 class="feat-title">Booking Mandiri 24/7</h3>
        <p class="feat-desc">Pesan layanan laundry kapan saja dan di mana saja langsung dari perangkat Anda tanpa perlu menghubungi admin.</p>
      </article>

      <article class="feature-card">
        <div class="feat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
          </svg>
        </div>
        <h3 class="feat-title">Pantau Status Real-time</h3>
        <p class="feat-desc">Lacak setiap tahap pesanan Anda — dari antri, sedang diproses, hingga selesai dan siap diambil.</p>
      </article>

      <article class="feature-card">
        <div class="feat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
          </svg>
        </div>
        <h3 class="feat-title">Multi Jenis Layanan</h3>
        <p class="feat-desc">Pilih layanan sesuai kebutuhan: cuci biasa, cuci + setrika, cuci kering, setrika saja, dan masih banyak lagi.</p>
      </article>

      <article class="feature-card">
        <div class="feat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.562.562 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/>
          </svg>
        </div>
        <h3 class="feat-title">Diskon Eksklusif Member</h3>
        <p class="feat-desc">Upgrade ke Member dan hemat hingga 10% untuk setiap pesanan. Makin banyak cucian, makin besar diskon yang kamu dapat.</p>
      </article>

      <article class="feature-card">
        <div class="feat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
          </svg>
        </div>
        <h3 class="feat-title">Riwayat Lengkap</h3>
        <p class="feat-desc">Akses seluruh riwayat transaksi kapan saja. Lihat detail pesanan, total tagihan, dan status pengerjaan dengan mudah.</p>
      </article>

      <article class="feature-card">
        <div class="feat-icon">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/>
          </svg>
        </div>
        <h3 class="feat-title">Akun Aman & Terproteksi</h3>
        <p class="feat-desc">Data dan akun Anda dilindungi dengan enkripsi password standar industri. Ubah informasi kapan saja dari halaman profil.</p>
      </article>

    </div>
  </div>
</section>

<!-- ══════════════════════
     HOW IT WORKS
══════════════════════ -->
<section class="how" id="cara-kerja">
  <div class="container">
    <div class="section-header" style="text-align:center;">
      <div class="section-label" style="justify-content:center;">Cara Kerja</div>
      <h2 class="section-title">Cukup 3 Langkah Mudah</h2>
      <p class="section-desc" style="margin:0 auto;">Tidak perlu antri, tidak perlu telepon. Semua bisa dilakukan dari layar HP atau laptop Anda.</p>
    </div>

    <div class="steps">
      <div class="step">
        <div class="step-num">1</div>
        <h3 class="step-title">Daftar & Masuk</h3>
        <p class="step-desc">Buat akun dalam hitungan detik. Isi nama, email, nomor HP, dan alamat — langsung bisa digunakan.</p>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <h3 class="step-title">Buat Booking</h3>
        <p class="step-desc">Pilih jenis layanan, masukkan estimasi berat, dan tentukan tanggal antar. Harga otomatis terhitung termasuk diskon member.</p>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <h3 class="step-title">Pantau & Ambil</h3>
        <p class="step-desc">Ikuti status pengerjaan laundry Anda secara real-time hingga selesai dan siap diambil.</p>
      </div>
    </div>
  </div>
</section>

<!-- ══════════════════════
     MEMBERSHIP
══════════════════════ -->
<section class="membership" id="membership">
  <div class="container">
    <div class="section-header" style="text-align:center;">
      <div class="section-label" style="justify-content:center;">Program Membership</div>
      <h2 class="section-title">Pilih Paket yang Sesuai</h2>
      <p class="section-desc" style="margin:0 auto;">Mulai gratis sebagai pelanggan Reguler, atau upgrade ke Member untuk menikmati diskon eksklusif di setiap pesanan.</p>
    </div>

    <div class="pricing-grid">

      <div class="pricing-card">
        <div class="pricing-name">Reguler</div>
        <div class="pricing-tagline">Cocok untuk pengguna baru</div>
        <div class="pricing-discount">
          0% <span>diskon</span>
        </div>
        <div class="pricing-note">Harga normal untuk semua layanan</div>
        <div class="pricing-divider"></div>
        <ul class="pricing-perks">
          <li>
            <span class="check"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span>
            Booking mandiri 24/7
          </li>
          <li>
            <span class="check"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span>
            Pantau status real-time
          </li>
          <li>
            <span class="check"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span>
            Riwayat transaksi lengkap
          </li>
          <li class="muted">
            <span class="check"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span>
            Diskon per pesanan
          </li>
          <li class="muted">
            <span class="check"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg></span>
            Diskon bulk ≥ 6 kg
          </li>
        </ul>
        <a href="/laundrify-app/auth/register.php" class="btn-pricing outlined">Daftar Gratis</a>
      </div>

      <div class="pricing-card featured">
        <div class="pricing-badge">TERPOPULER</div>
        <div class="pricing-name">Member</div>
        <div class="pricing-tagline">Untuk pelanggan setia yang ingin hemat</div>
        <div class="pricing-discount grad-text">
          5–10% <span style="-webkit-text-fill-color:var(--gray-500)">diskon</span>
        </div>
        <div class="pricing-note">5% semua order · 10% untuk order ≥ 6 kg</div>
        <div class="pricing-divider"></div>
        <ul class="pricing-perks">
          <li>
            <span class="check" style="background:var(--grad)"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span>
            Booking mandiri 24/7
          </li>
          <li>
            <span class="check" style="background:var(--grad)"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span>
            Pantau status real-time
          </li>
          <li>
            <span class="check" style="background:var(--grad)"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span>
            Riwayat transaksi lengkap
          </li>
          <li>
            <span class="check" style="background:var(--grad)"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span>
            Diskon 5% setiap pesanan
          </li>
          <li>
            <span class="check" style="background:var(--grad)"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="white"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg></span>
            Diskon 10% untuk order ≥ 6 kg
          </li>
        </ul>
        <a href="/laundrify-app/auth/register.php" class="btn-pricing filled">Daftar & Ajukan Upgrade</a>
      </div>

    </div>
  </div>
</section>

<!-- ══════════════════════
     CTA BANNER
══════════════════════ -->
<section class="cta-section">
  <div class="container">
    <div class="cta-inner">
      <h2>Siap Mencoba Laundrify?</h2>
      <p>Bergabunglah dengan pelanggan yang sudah merasakan kemudahan laundry modern. Daftar gratis, tidak diperlukan kartu kredit.</p>
      <div class="cta-actions">
        <a href="/laundrify-app/auth/register.php" class="btn-cta white">
          Buat Akun Gratis
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
          </svg>
        </a>
        <a href="/laundrify-app/auth/login.php" class="btn-cta outline-white">Sudah punya akun? Masuk</a>
      </div>
    </div>
  </div>
</section>

</main>

<!-- ══════════════════════
     FOOTER
══════════════════════ -->
<footer>
  <div class="container">
    <div class="footer-inner">
      <div class="footer-brand">
        <div class="brand-name">
          <div class="brand-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
            </svg>
          </div>
          Laundrify
        </div>
        <p>Sistem manajemen laundry modern yang memudahkan pelanggan memesan dan admin mengelola bisnis dari satu platform terpadu.</p>
      </div>
      <nav class="footer-col">
        <h4>Aplikasi</h4>
        <ul>
          <li><a href="#features">Fitur</a></li>
          <li><a href="#cara-kerja">Cara Kerja</a></li>
          <li><a href="#membership">Membership</a></li>
        </ul>
      </nav>
      <nav class="footer-col">
        <h4>Akun</h4>
        <ul>
          <li><a href="/laundrify-app/auth/login.php">Masuk</a></li>
          <li><a href="/laundrify-app/auth/register.php">Daftar</a></li>
        </ul>
      </nav>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> Laundrify App. Dibuat sebagai proyek akhir tugas sekolah.</p>
    </div>
  </div>
</footer>

<script>
  // Smooth scroll untuk anchor links
  document.querySelectorAll('a[href^="#"]').forEach(function(a) {
    a.addEventListener('click', function(e) {
      var id = this.getAttribute('href').slice(1);
      var el = document.getElementById(id);
      if (el) {
        e.preventDefault();
        el.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  // Navbar scroll shadow 
  var navbar = document.querySelector('.navbar');
  window.addEventListener('scroll', function() {
    navbar.style.boxShadow = window.scrollY > 10
      ? '0 2px 20px rgba(0,0,0,.08)'
      : 'none';
  });

  // Hamburger (mobile)
  var hamburger = document.getElementById('nav-hamburger');
  var navLinks  = document.querySelector('.nav-links');
  if (hamburger && navLinks) {
    hamburger.addEventListener('click', function() {
      var open = navLinks.style.display === 'flex';
      navLinks.style.display = open ? 'none' : 'flex';
      navLinks.style.flexDirection = 'column';
      navLinks.style.position = 'absolute';
      navLinks.style.top = '68px';
      navLinks.style.left = '0';
      navLinks.style.right = '0';
      navLinks.style.background = 'white';
      navLinks.style.padding = '12px 24px 16px';
      navLinks.style.borderBottom = '1px solid #e2e8f0';
      navLinks.style.boxShadow = '0 8px 24px rgba(0,0,0,.08)';
      navLinks.style.zIndex = '99';
      if (open) navLinks.style.display = 'none';
    });
  }

  // Intersection Observer for fade-in animations
  var observer = new IntersectionObserver(function(entries) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
        observer.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1 });

  document.querySelectorAll('.feature-card, .step, .pricing-card').forEach(function(el) {
    el.style.opacity = '0';
    el.style.transform = 'translateY(24px)';
    el.style.transition = 'opacity .5s ease, transform .5s ease';
    observer.observe(el);
  });
</script>

</body>
</html>
