<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>HorizonBlog</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
<style>
  :root {
    --green: #1a9e5c;
    --green-dark: #157a47;
    --green-light: #e6f7ee;
    --green-pale: #b8ddc8;
    --navy: #1a2456;
    --text: #111827;
    --gray: #6b7280;
  }
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  html { font-size: 16px; }
  body { font-family: 'Poppins', sans-serif; color: var(--text); overflow-x: hidden; background: #fff; }
  h1,h2,h3,h4 { font-family: 'Poppins', sans-serif; }

  /* ─── NAV ─── */
  nav {
    position: sticky; top: 0; z-index: 300;
    background: #fff;
    box-shadow: 0 1px 12px rgba(0,0,0,.08);
    height: 68px;
  }
  .nav-inner {
    max-width: 1440px; margin: 0 auto; padding: 0 48px;
    height: 100%; display: grid;
    grid-template-columns: 220px 1fr 220px;
    align-items: center;
  }
  .nav-logo { display: flex; align-items: center; gap: 10px; text-decoration: none; }
  .nav-logo-text { font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 1.25rem; }
  .nav-logo-text .horizon { color: var(--navy); }
  .nav-logo-text .blog   { color: var(--green); }
  .nav-menu { display: flex; gap: 48px; justify-content: center; }
  .nav-menu a {
    font-size: .95rem; font-weight: 500; color: var(--gray);
    text-decoration: none; transition: color .2s;
  }
  .nav-menu a.active { color: var(--text); font-weight: 600; }
  .nav-menu a:hover  { color: var(--text); }
  /* auth buttons — two separate rounded pills with shadow */
  .auth-wrap { display: flex; justify-content: flex-end; align-items: center; gap: 10px; }
  .auth-fused { display: flex; align-items: center; gap: 10px; }
  .auth-fused button {
    padding: 6px 18px; font-size: .82rem; font-weight: 600;
    font-family: 'Poppins', sans-serif; cursor: pointer;
    transition: background .2s, color .2s, transform .15s;
    border-radius: 8px;
  }
  /* S'inscrire : white bg, green border on all 4 sides = standalone selected look */
  .btn-s {
    background: #fff; color: var(--green);
    border: 2px solid var(--green);
    box-shadow: 0 3px 10px rgba(26,158,92,.2);
  }
  .btn-s:hover { background: var(--green-light); transform: translateY(-1px); }
  /* Se Connecter : solid green, own shadow */
  .btn-c {
    background: var(--green); color: #fff;
    border: 2px solid var(--green);
    box-shadow: 0 3px 14px rgba(26,158,92,.35);
    width: 130px;
  }
  .btn-c:hover { background: var(--green-dark); border-color: var(--green-dark); transform: translateY(-1px); }

  /* ─── HERO ─── */
  .hero {
    position: relative; height: 80vh; min-height: 560px;
    overflow: hidden; background: #0a1628;
  }
  .hero-bg {
    position: absolute; inset: 0;
    background: url('https://images.unsplash.com/photo-1570481662006-a3a1374699e8?w=1800&q=85') center/cover no-repeat;
    filter: brightness(.5);
    transform: scale(1.06);
    transition: transform 9s ease, opacity .4s;
  }
  .hero-bg.zoomed { transform: scale(1); }
  /* side arrows */
  .hero-arrow {
    position: absolute; top: 50%; transform: translateY(-50%);
    z-index: 20; background: #fff; border: none;
    width: 48px; height: 48px; border-radius: 50%; cursor: pointer;
    font-size: 1.4rem; box-shadow: 0 4px 20px rgba(0,0,0,.25);
    display: flex; align-items: center; justify-content: center;
    transition: background .2s; color: var(--text);
  }
  .hero-arrow:hover { background: var(--green-light); }
  .hero-arrow.left  { left: 24px; }
  .hero-arrow.right { right: 24px; }
  /* content */
  .hero-content {
    position: relative; z-index: 2;
    max-width: 1440px; margin: 0 auto; padding: 0 80px;
    height: 100%; display: flex; align-items: center; gap: 48px;
  }
  .hero-text { flex: 1; }
  .hero-text h1 {
    font-size: clamp(2.4rem, 4.5vw, 3.5rem); font-weight: 800;
    color: #fff; line-height: 1.12; margin-bottom: 24px;
    transition: opacity .4s;
  }
  .hero-text p {
    color: rgba(255,255,255,.85); font-size: .97rem; line-height: 1.8;
    max-width: 580px; margin-bottom: 36px; transition: opacity .4s;
  }
  /* lire article btn */
  .btn-lire-hero {
    display: inline-flex; align-items: center; gap: 10px;
    background: #fff; color: var(--text);
    border-radius: 10px; padding: 9px 22px;
    font-size: .88rem; font-weight: 700; font-family: 'Poppins', sans-serif;
    text-decoration: none; border: none; cursor: pointer;
    transition: background .2s, transform .2s;
  }
  .btn-lire-hero:hover { background: var(--green-light); transform: translateY(-2px); }
  .btn-lire-hero .book-icon-wrap {
    background: var(--green); color: #fff;
    border-radius: 7px; padding: 4px 8px;
    display: flex; align-items: center;
  }
  /* hero dots */
  .hero-dots {
    position: absolute; bottom: 24px; left: 50%; transform: translateX(-50%);
    display: flex; gap: 10px; z-index: 10;
  }
  .hero-dot {
    width: 12px; height: 12px; border-radius: 50%;
    background: rgba(255,255,255,.45); cursor: pointer; border: none;
    transition: background .3s, transform .3s;
  }
  .hero-dot.active { background: var(--green); transform: scale(1.25); }
  /* info card */
  .hero-card {
    background: #fff; border-radius: 20px; padding: 28px 28px 80px;
    width: 320px; flex-shrink: 0;
    box-shadow: 0 16px 50px rgba(0,0,0,.22);
    position: relative; overflow: hidden;
  }
  /* double arc top-right */
  .card-arc {
    position: absolute; top: 0; right: 0;
    width: 90px; height: 90px; overflow: hidden; pointer-events: none;
  }
  .card-arc svg { position: absolute; top: -10px; right: -10px; }
  /* intersecting circles bottom-right */
  .card-vc {
    position: absolute; bottom: 22px; right: 22px;
    display: flex; align-items: center;
  }
  .card-vc-c {
    width: 38px; height: 38px; border-radius: 50%;
    border: 3px solid var(--green); background: transparent; display: block;
  }
  .card-vc-c:last-child { margin-left: -14px; }
  .avatar-img {
    width: 56px; height: 56px; border-radius: 50%;
    object-fit: cover; border: 2px solid #e5e7eb;
    display: block; margin-bottom: 16px;
  }
  .meta-label {
    font-size: .82rem; color: var(--gray);
    margin-bottom: 2px; line-height: 1;
  }
  .meta-val { font-size: .95rem; font-weight: 700; margin-bottom: 14px; line-height: 1.3; }
  .meta-section-title {
    font-size: 1rem; font-weight: 800; margin-bottom: 8px;
  }
  .tags-wrap { display: flex; flex-wrap: wrap; gap: 6px; }
  .ctag {
    display: inline-flex; align-items: center;
    padding: 5px 16px; border: 1.5px solid #d1d5db;
    border-radius: 999px; font-size: .78rem; font-weight: 500;
    color: var(--text); cursor: pointer; background: #fff;
    transition: background .25s, color .25s, border-color .25s;
  }
  .ctag:hover { background: var(--green); color: #fff; border-color: var(--green); }

  /* ─── SECTIONS ─── */
  section { padding: 80px 0; }
  .container { max-width: 1440px; margin: 0 auto; padding: 0 80px; }
  .sec-title {
    text-align: center; font-size: 1.6rem; font-weight: 800;
    letter-spacing: .08em; color: var(--green);
    text-transform: uppercase; margin-bottom: 8px;
  }
  .sec-sub {
    text-align: center; font-size: .8rem; letter-spacing: .14em;
    text-transform: uppercase; color: var(--gray); margin-bottom: 52px;
  }

  /* ─── ARTICLES SECTION ─── */
  .articles-section { background: #f0f5f2; position: relative; overflow: hidden; }

  /* Animated bg circles (solid pale green, no gradient like screenshot) */
  .bg-circ {
    position: absolute; border-radius: 50%; pointer-events: none;
    background: #c8dfd2;
  }
  @keyframes fa { 0%,100%{transform:translate(0,0);} 50%{transform:translate(14px,-18px);} }
  @keyframes fb { 0%,100%{transform:translate(0,0);} 50%{transform:translate(-12px,14px);} }
  @keyframes fc { 0%,100%{transform:translate(0,0);} 50%{transform:translate(10px,10px);} }
  @keyframes fd { 0%,100%{transform:translate(0,0);} 50%{transform:translate(-8px,-12px);} }

  /* ─── CARD FLIP ─── */
  /* Slider layout: arrows outside, cards inside */
  .slider-outer {
    position: relative;
    padding: 0 0 56px; /* room for dots */
  }
  .slider-arrow-btn {
    position: absolute; top: calc(50% - 28px); transform: translateY(-50%);
    z-index: 20; background: #fff; border: none;
    width: 50px; height: 50px; border-radius: 50%; cursor: pointer;
    font-size: 1.4rem; box-shadow: 0 4px 18px rgba(0,0,0,.15);
    display: flex; align-items: center; justify-content: center;
    transition: background .2s; color: var(--text);
    flex-shrink: 0;
  }
  .slider-arrow-btn:hover { background: var(--green-light); }
  .slider-arrow-btn.prev { left: -70px; }
  .slider-arrow-btn.next { right: -70px; }

  .slider-viewport { overflow: hidden; }
  .slider-track {
    display: flex; gap: 28px;
    will-change: transform;
    transition: transform .5s cubic-bezier(.4,0,.2,1);
  }

  /* Each card = 1/3 of container minus gaps */
  .flip-card {
    flex: 0 0 calc(33.333% - 19px);
    min-height: 420px;
    perspective: 1200px;
    cursor: pointer;
  }
  .flip-inner {
    width: 100%; height: 100%; min-height: 420px;
    position: relative;
    transform-style: preserve-3d;
    transition: transform .65s cubic-bezier(.4,0,.2,1);
    border-radius: 18px;
  }
  .flip-card:hover .flip-inner { transform: rotateY(180deg); }

  .flip-front, .flip-back {
    position: absolute; inset: 0; border-radius: 18px;
    backface-visibility: hidden; -webkit-backface-visibility: hidden;
    overflow: hidden;
  }
  /* FRONT */
  .flip-front img {
    width: 100%; height: 100%; object-fit: cover; display: block;
  }
  .flip-front-overlay {
    position: absolute; inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,.88) 40%, rgba(0,0,0,.1) 80%);
    display: flex; flex-direction: column;
    justify-content: flex-end; align-items: center;
    padding: 32px 24px; text-align: center;
  }
  .flip-front-overlay h3 {
    color: #fff; font-size: 1.25rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .05em; margin-bottom: 18px;
  }
  /* BACK */
  .flip-back {
    transform: rotateY(180deg);
    background: var(--green);
    display: flex; flex-direction: column;
    justify-content: center; align-items: center;
    padding: 36px 28px; text-align: center; gap: 16px;
  }
  .flip-back::before {
    content: ''; position: absolute;
    width: 140px; height: 140px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,.15); top: -30px; right: -30px;
  }
  .flip-back::after {
    content: ''; position: absolute;
    width: 90px; height: 90px; border-radius: 50%;
    border: 2px solid rgba(255,255,255,.1); bottom: -20px; left: -20px;
  }
  .flip-back h3 {
    color: #fff; font-size: 1.2rem; font-weight: 800;
    text-transform: uppercase; letter-spacing: .05em;
  }
  .flip-back p {
    color: rgba(255,255,255,.9); font-size: .88rem;
    line-height: 1.7; font-weight: 700;
  }

  /* ─── READ BUTTONS ─── */
  .btn-read {
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; color: var(--text);
    border-radius: 999px; padding: 7px 18px;
    font-size: .82rem; font-weight: 700; font-family: 'Poppins', sans-serif;
    text-decoration: none; border: none; cursor: pointer;
    transition: background .2s; white-space: nowrap;
  }
  .btn-read:hover { background: #e8f5ee; }
  .btn-read .book-chip {
    background: var(--green); color: #fff;
    border-radius: 6px; padding: 2px 7px;
    display: flex; align-items: center;
  }

  /* Slider dots */
  .sdots {
    display: flex; justify-content: center; gap: 10px;
    position: absolute; bottom: 16px; left: 50%; transform: translateX(-50%);
  }
  .sdot {
    width: 12px; height: 12px; border-radius: 50%;
    background: #c8dfd2; cursor: pointer; border: none;
    transition: background .3s, transform .3s;
  }
  .sdot.active { background: var(--green); transform: scale(1.3); }

  /* ─── SEE ALL BTN ─── */
  .btn-see-all {
    display: inline-flex; align-items: center; gap: 10px;
    background: var(--green-dark); color: #fff;
    border-radius: 10px; padding: 11px 28px;
    font-size: .9rem; font-weight: 800; font-family: 'Poppins', sans-serif;
    text-decoration: none; border: none; cursor: pointer;
    transition: background .2s, transform .2s;
  }
  .btn-see-all:hover { background: var(--green); transform: translateY(-2px); }
  .btn-see-all .book-chip {
    background: rgba(255,255,255,.2); border-radius: 6px;
    padding: 2px 7px; display: flex; align-items: center;
  }

  /* ─── CATÉGORIE ─── */
  .categ-section { background: #fff; position: relative; overflow: hidden; }
  /* large solid pale-green circles as deco, matching screenshot */
  .categ-deco {
    position: absolute; border-radius: 50%; pointer-events: none;
    background: var(--green);
  }
  .categ-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
  .categ-card {
    position: relative; border-radius: 16px; overflow: hidden;
    height: 240px; cursor: pointer;
  }
  .categ-card img {
    width: 100%; height: 100%; object-fit: cover; display: block;
    transition: transform .45s;
  }
  .categ-card:hover img { transform: scale(1.06); }
  .categ-overlay {
    position: absolute; inset: 0;
    background: rgba(0,0,0,.18);
  }
  /* badge pill */
  .categ-badge {
    position: absolute; top: 18px; left: 18px;
    display: inline-flex; align-items: center; gap: 10px;
    padding: 10px 20px; border-radius: 12px;
    font-size: 1rem; font-weight: 800; font-family: 'Poppins', sans-serif;
    color: #fff;
  }
  /* articles associés */
  .categ-link {
    position: absolute; bottom: 18px; left: 18px;
    display: inline-flex; align-items: center; gap: 8px;
    background: #fff; color: var(--green);
    border-radius: 999px; padding: 7px 16px;
    font-size: .8rem; font-weight: 700; font-family: 'Poppins', sans-serif;
    text-decoration: none; transition: background .2s;
  }
  .categ-link:hover { background: var(--green-light); }
  .categ-link .book-chip {
    background: var(--green); color: #fff;
    border-radius: 5px; padding: 2px 6px;
    display: flex; align-items: center;
  }

  /* ─── JOIN (redesigned v3) ─── */
  .join-section {
    background: #f8faf9; position: relative; overflow: hidden; padding: 0;
  }
  .join-grid {
    display: grid; grid-template-columns: 1fr 1fr; min-height: 540px;
  }

  /* ── Left: image side ── */
  .join-img-panel { position: relative; overflow: hidden; min-height: 540px; }
  .join-img-panel img {
    width: 100%; height: 100%;
    object-fit: cover; object-position: center 20%;
    display: block; position: absolute; inset: 0;
  }
  .join-img-panel::after {
    content: ''; position: absolute; inset: 0;
    background: linear-gradient(135deg, rgba(15,40,25,.7) 0%, rgba(15,40,25,.3) 60%, transparent);
  }
  /* avatars stack */
  .join-avatars {
    position: absolute; top: 32px; left: 32px; z-index: 2;
    display: flex; align-items: center;
  }
  .join-avatars img {
    width: 36px; height: 36px; border-radius: 50%;
    border: 2.5px solid #fff; object-fit: cover;
    display: block; margin-left: -10px;
    position: static; inset: auto;
  }
  .join-avatars img:first-child { margin-left: 0; }
  .join-avatars-count {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--green); border: 2.5px solid #fff;
    display: flex; align-items: center; justify-content: center;
    font-size: .62rem; font-weight: 700; color: #fff;
    margin-left: -10px; flex-shrink: 0;
  }
  .join-avatars-label {
    margin-left: 10px; background: rgba(255,255,255,.18);
    backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,.25);
    border-radius: 999px; padding: 4px 12px;
    font-size: .72rem; font-weight: 600; color: #fff; white-space: nowrap;
  }
  /* stat card bottom-left of image */
  .join-img-stat {
    position: absolute; bottom: 32px; left: 32px; z-index: 2;
    background: rgba(255,255,255,.13);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,.25);
    border-radius: 16px; padding: 16px 22px;
    display: flex; align-items: center; gap: 14px;
  }
  .join-img-stat-icon {
    width: 44px; height: 44px; border-radius: 12px;
    background: var(--green); display: flex;
    align-items: center; justify-content: center; flex-shrink: 0;
  }
  .join-img-stat-num { font-size: 1.25rem; font-weight: 800; color: #fff; line-height: 1; }
  .join-img-stat-label { font-size: .7rem; color: rgba(255,255,255,.65); font-weight: 500; margin-top: 3px; }

  /* ── Right: text side ── */
  .join-text-panel {
    background: #fff; padding: 68px 60px;
    display: flex; flex-direction: column; justify-content: center;
    position: relative; overflow: hidden;
  }
  .join-text-panel::before {
    content: ''; position: absolute;
    width: 220px; height: 220px; border-radius: 50%;
    border: 2px solid var(--green-pale);
    top: -70px; right: -70px; pointer-events: none;
  }
  .join-text-panel::after {
    content: ''; position: absolute;
    width: 130px; height: 130px; border-radius: 50%;
    background: #f0faf5; bottom: -50px; left: -50px; pointer-events: none;
  }
  .join-pill-label {
    display: inline-flex; align-items: center; gap: 6px;
    background: var(--green-light); color: var(--green);
    border-radius: 999px; padding: 4px 14px;
    font-size: .7rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .1em;
    margin-bottom: 18px; width: fit-content;
  }
  .join-text-panel h2 {
    font-size: clamp(1.4rem, 2vw, 1.9rem); font-weight: 800;
    color: var(--text); line-height: 1.25; margin-bottom: 12px;
  }
  .join-text-panel > p {
    color: var(--gray); font-size: .88rem;
    line-height: 1.75; margin-bottom: 28px;
  }
  /* feature list */
  .join-features { display: flex; flex-direction: column; gap: 12px; margin-bottom: 32px; position: relative; z-index: 2; }
  .join-feature-item { display: flex; align-items: flex-start; gap: 12px; }
  .join-feature-icon {
    width: 28px; height: 28px; border-radius: 8px;
    background: var(--green-light); display: flex;
    align-items: center; justify-content: center; flex-shrink: 0; margin-top: 1px;
  }
  .join-feature-item p { font-size: .83rem; color: var(--gray); margin: 0; line-height: 1.5; }
  .join-feature-item strong { color: var(--text); font-weight: 600; display: block; }
  /* CTA buttons */
  .join-cta-row { display: flex; gap: 12px; align-items: center; position: relative; z-index: 2; }
  .join-cta-primary {
    display: inline-flex; align-items: center; gap: 8px;
    background: var(--green); color: #fff;
    border: none; border-radius: 8px;
    padding: 9px 22px; font-size: .85rem; font-weight: 600;
    font-family: 'Poppins', sans-serif; cursor: pointer;
    box-shadow: 0 4px 14px rgba(26,158,92,.35);
    transition: background .2s, transform .15s;
  }
  .join-cta-primary:hover { background: var(--green-dark); transform: translateY(-2px); }
  .join-cta-secondary {
    display: inline-flex; align-items: center; gap: 8px;
    background: transparent; color: var(--green);
    border: 2px solid var(--green); border-radius: 8px;
    padding: 7px 20px; font-size: .85rem; font-weight: 600;
    font-family: 'Poppins', sans-serif; cursor: pointer;
    transition: background .2s, transform .15s;
  }
  .join-cta-secondary:hover { background: var(--green-light); transform: translateY(-2px); }

  /* ─── FOOTER (darker) ─── */
  footer { background: #0b1e14; padding: 52px 0 0; }
  .footer-inner {
    max-width: 1440px; margin: 0 auto; padding: 0 80px 48px;
    display: grid; grid-template-columns: 1.6fr 1fr 1fr; gap: 60px;
  }
  .flogo { display: flex; align-items: center; gap: 10px; margin-bottom: 18px; text-decoration: none; }
  .flogo-text { font-family: 'Poppins', sans-serif; font-weight: 800; font-size: 1.25rem; }
  .flogo-text .fh { color: #fff; }
  .flogo-text .fb { color: var(--green); }
  footer p { font-size: .88rem; line-height: 1.75; font-weight: 400; color: rgba(255,255,255,.55); max-width: 280px; }
  .social-row { display: flex; gap: 12px; margin-top: 24px; }
  .sico {
    width: 38px; height: 38px; border-radius: 9px;
    background: rgba(255,255,255,.08); display: flex;
    align-items: center; justify-content: center;
    color: rgba(255,255,255,.7); text-decoration: none;
    transition: background .2s, color .2s;
  }
  .sico svg { width: 18px; height: 18px; }
  .sico:hover { background: var(--green); color: #fff; }
  footer h4 {
    font-family: 'Poppins', sans-serif; font-weight: 700;
    font-size: 1rem; color: #fff; margin-bottom: 16px;
  }
  footer a {
    display: block; color: rgba(255,255,255,.55);
    text-decoration: none; font-size: .88rem; margin-bottom: 10px;
    transition: color .2s;
  }
  footer a:hover { color: var(--green); }
  .footer-divider {
    border-top: 1px solid rgba(255,255,255,.08);
    padding: 20px 80px 24px;
    text-align: center; font-size: .78rem; color: rgba(255,255,255,.3);
  }

  /* ─── SCROLL FADE ─── */
  .fade-up { opacity: 0; transform: translateY(26px); transition: opacity .6s ease, transform .6s ease; }
  .fade-up.visible { opacity: 1; transform: translateY(0); }

  /* ══════════════════════════════════════
     HAMBURGER / MOBILE NAV
  ══════════════════════════════════════ */
  .hamburger {
    display: none; flex-direction: column; justify-content: center;
    gap: 5px; background: none; border: none;
    cursor: pointer; padding: 6px; z-index: 400;
  }
  .hamburger span {
    display: block; width: 24px; height: 2.5px;
    background: var(--text); border-radius: 4px;
    transition: transform .3s, opacity .3s;
  }
  .hamburger.open span:nth-child(1) { transform: translateY(7.5px) rotate(45deg); }
  .hamburger.open span:nth-child(2) { opacity: 0; }
  .hamburger.open span:nth-child(3) { transform: translateY(-7.5px) rotate(-45deg); }

  /* Mobile drawer */
  .mobile-drawer {
    display: none; position: fixed;
    top: 68px; left: 0; right: 0; bottom: 0;
    background: #fff; z-index: 250;
    flex-direction: column; padding: 32px 24px;
    gap: 0; overflow-y: auto;
    box-shadow: 0 8px 40px rgba(0,0,0,.12);
    transform: translateX(100%);
    transition: transform .35s cubic-bezier(.4,0,.2,1);
  }
  .mobile-drawer.open {
    display: flex; transform: translateX(0);
  }
  .mobile-drawer a {
    font-size: 1.1rem; font-weight: 600; color: var(--text);
    text-decoration: none; padding: 16px 0;
    border-bottom: 1px solid #f3f4f6;
    transition: color .2s;
  }
  .mobile-drawer a:hover { color: var(--green); }
  .mobile-drawer a.active { color: var(--green); }
  .mobile-drawer-auth {
    display: flex; flex-direction: column; gap: 12px;
    margin-top: 28px;
  }
  .mobile-drawer-auth button {
    width: 100%; padding: 12px; font-size: .95rem;
    font-weight: 600; font-family: 'Poppins', sans-serif;
    border-radius: 10px; cursor: pointer;
    transition: background .2s, transform .15s;
  }
  .mobile-btn-s {
    background: #fff; color: var(--green);
    border: 2px solid var(--green);
  }
  .mobile-btn-s:hover { background: var(--green-light); }
  .mobile-btn-c {
    background: var(--green); color: #fff;
    border: 2px solid var(--green);
    box-shadow: 0 4px 14px rgba(26,158,92,.3);
  }
  .mobile-btn-c:hover { background: var(--green-dark); }

  /* ══════════════════════════════════════
     TABLET  ≤ 1024px
  ══════════════════════════════════════ */
  @media (max-width: 1024px) {
    /* Nav */
    .nav-inner {
      padding: 0 28px;
      grid-template-columns: 1fr auto auto;
      gap: 0;
    }
    .nav-menu { display: none; }
    .auth-wrap { display: none; }
    .hamburger { display: flex; }

    /* Container */
    .container { padding: 0 40px; }

    /* Hero */
    .hero { height: auto; min-height: 480px; }
    .hero-content {
      padding: 60px 40px;
      flex-direction: column;
      align-items: flex-start;
      gap: 32px;
    }
    .hero-card { width: 100%; max-width: 420px; }
    .hero-text h1 { font-size: clamp(1.8rem, 4vw, 2.6rem); }
    .hero-text p { font-size: .9rem; }

    /* Articles slider: show 2 cards */
    .flip-card { flex: 0 0 calc(50% - 14px); }
    .flip-inner { min-height: 360px; }
    .slider-arrow-btn.prev { left: -20px; }
    .slider-arrow-btn.next { right: -20px; }

    /* Categ grid 2 cols stays, reduce height */
    .categ-card { height: 200px; }

    /* Join */
    .join-grid { grid-template-columns: 1fr; }
    .join-img-panel { min-height: 340px; }
    .join-text-panel { padding: 52px 40px; }

    /* Footer */
    .footer-inner {
      padding: 0 40px 40px;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
    }
    .footer-inner > div:first-child { grid-column: 1 / -1; }
    .footer-divider { padding: 20px 40px 24px; }
  }

  /* ══════════════════════════════════════
     MOBILE  ≤ 640px
  ══════════════════════════════════════ */
  @media (max-width: 640px) {
    /* Nav */
    nav { height: 60px; }
    .nav-inner { padding: 0 20px; }
    .mobile-drawer { top: 60px; }
    .nav-logo-text { font-size: 1.05rem; }

    /* Sections padding */
    section { padding: 56px 0; }
    .container { padding: 0 20px; }
    .sec-title { font-size: 1.25rem; letter-spacing: .06em; }
    .sec-sub { font-size: .72rem; margin-bottom: 32px; }

    /* Hero */
    .hero { height: auto; min-height: unset; }
    .hero-content {
      padding: 40px 20px 60px;
      flex-direction: column;
      align-items: flex-start;
      gap: 24px;
    }
    .hero-text h1 { font-size: 1.7rem; margin-bottom: 14px; }
    .hero-text p { font-size: .83rem; margin-bottom: 20px; }
    .hero-arrow { width: 36px; height: 36px; font-size: 1.1rem; }
    .hero-arrow.left  { left: 10px; }
    .hero-arrow.right { right: 10px; }
    .hero-card {
      width: 100%; padding: 20px 20px 64px;
    }
    .hero-dots { bottom: 16px; }

    /* Articles slider: 1 card full width */
    .flip-card { flex: 0 0 calc(100% - 0px); min-height: 320px; }
    .flip-inner { min-height: 320px; }
    .slider-outer { padding: 0 0 48px; }
    .slider-arrow-btn { width: 40px; height: 40px; font-size: 1.1rem; }
    .slider-arrow-btn.prev { left: -12px; }
    .slider-arrow-btn.next { right: -12px; }
    .btn-see-all { padding: 10px 20px; font-size: .82rem; }

    /* Categ: 1 column */
    .categ-grid { grid-template-columns: 1fr; }
    .categ-card { height: 200px; }
    .categ-deco { display: none; }

    /* Join */
    .join-grid { grid-template-columns: 1fr; }
    .join-img-panel { min-height: 260px; }
    .join-img-panel img { object-position: center 15%; }
    .join-avatars { top: 20px; left: 20px; }
    .join-avatars-label { display: none; }
    .join-img-stat { bottom: 20px; left: 20px; padding: 12px 16px; }
    .join-img-stat-num { font-size: 1rem; }
    .join-text-panel { padding: 40px 20px; }
    .join-text-panel h2 { font-size: 1.3rem; }
    .join-cta-row { flex-direction: column; align-items: stretch; }
    .join-cta-primary, .join-cta-secondary {
      justify-content: center;
      padding: 11px 20px;
    }

    /* Footer */
    .footer-inner {
      padding: 0 20px 36px;
      grid-template-columns: 1fr;
      gap: 32px;
    }
    .footer-inner > div:first-child { grid-column: auto; }
    .footer-divider { padding: 16px 20px 20px; }

    /* Bg circles hidden on mobile for perf */
    .bg-circ { opacity: .4; }
  }
</style>
</head>
<body>

<!-- ════════ NAV ════════ -->
<nav>
  <div class="nav-inner">
    <a href="#" class="nav-logo">
      <!-- leaf SVG matching logo image -->
      <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
        <path d="M16 4C10 4 5 9 5 15.5c0 3.2 1.6 5.9 4.2 7.8 1.1.8 2.6-.3 2.1-1.6-1-2.8-1-5.8 1.2-8 3.2-3.2 8.5-3.2 11.8-1 1 .6 2.2-.3 1.6-1.5C24 9 20.3 4 16 4z" fill="#1a9e5c"/>
        <path d="M8.5 23.5c0 0 2.2-2.2 5.5-2.2s5.5 1.2 5.5 3.2c0 1.6-1.1 2.2-2.2 2.2-3.3 0-8.8-3.2-8.8-3.2z" fill="#157a47"/>
        <line x1="14" y1="16" x2="8.5" y2="23.5" stroke="#0d6b3c" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
      <span class="nav-logo-text"><span class="horizon">Horizon</span><span class="blog">Blog</span></span>
    </a>

    <div class="nav-menu">
      <a href="<?=path("lecteur","home")?>" class="active">Acceuil</a>
      <a href="<?=path("lecteur","article")?>">Article</a>
      <a href="#">Catégorie</a>
      <a href="#">Contact</a>
    </div>

    <div class="auth-wrap">
      <div class="auth-fused">
        <button class="btn-s">S'inscrire</button>
        <button class="btn-c">Se Connecter</button>
      </div>
    </div>

    <!-- Hamburger (mobile/tablet) -->
    <button class="hamburger" id="hamburger" aria-label="Menu" onclick="toggleMenu()">
      <span></span><span></span><span></span>
    </button>
  </div>
</nav>

<?= $content ?>

</body>
</html>
