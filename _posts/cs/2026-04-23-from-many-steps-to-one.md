---
title: "One-step generative models"
author: "Rabiul Awal"
layout: post
date: 2026-04-23
mathjax: true
catalog: true
category:
    - 'cs'
tag: [machine-learning, generative-models, diffusion]
---

<style>
:root {
  --fig-paper:    #faf6ec;
  --fig-panel:    #f3ede0;
  --fig-frame:    #d8d0bd;
  --fig-ink:      #3a3833;
  --fig-ink-soft: #6a6660;
  --fig-ink-mute: #a8a298;
  --fig-blue:     #6b8caf;
  --fig-sage:     #8fa68a;
  --fig-amber:    #c89060;
  --fig-rose:     #c66c5e;
  --fig-plum:     #997aa8;
  --fig-ochre:    #c9a35c;
}
@media (prefers-color-scheme: dark) {
  :root {
    --fig-paper:    #1c1c20;
    --fig-panel:    #25252b;
    --fig-frame:    #3a3a40;
    --fig-ink:      #d8d4cc;
    --fig-ink-soft: #989288;
    --fig-ink-mute: #6a6660;
    --fig-blue:     #87a4c2;
    --fig-sage:     #a6bba0;
    --fig-amber:    #d8a578;
    --fig-rose:     #d68478;
    --fig-plum:     #ad95bb;
    --fig-ochre:    #d4b574;
  }
}
.fig-card {
  background: var(--fig-paper);
  border: 1px solid var(--fig-frame);
  border-radius: 10px;
  padding: 18px 18px 14px;
  margin: 1.4rem 0 0.4rem;
  position: relative;
}
.fig-card-title {
  font-family: "Iowan Old Style", "Charter", Georgia, serif;
  font-size: 13px;
  font-weight: 600;
  color: var(--fig-ink-soft);
  text-align: center;
  letter-spacing: 0.01em;
  margin: 0 0 10px;
}
.fig-card-inner {
  background: var(--fig-panel);
  border-radius: 6px;
  padding: 14px;
}
.fig-card svg, .fig-card canvas {
  display: block;
  width: 100%;
  height: auto;
}
.fig-card figcaption,
figure.fig-card > figcaption {
  font-size: 13px;
  color: var(--fig-ink-soft);
  line-height: 1.6;
  margin: 10px 4px 0;
  font-style: italic;
}
.fig-paper {
  margin: 1.4rem 0 0.4rem;
}
.fig-paper-title {
  font-family: "Iowan Old Style", "Charter", Georgia, serif;
  font-size: 12px;
  font-weight: 500;
  color: var(--fig-ink-mute);
  text-align: center;
  letter-spacing: 0.02em;
  text-transform: uppercase;
  margin: 0 0 8px;
}
.fig-paper img { display: block; width: 100%; height: auto; border-radius: 4px; }
.fig-paper > figcaption,
figure.fig-paper > figcaption {
  font-size: 13px;
  color: var(--fig-ink-soft);
  line-height: 1.6;
  margin: 10px 4px 0;
  font-style: italic;
}
.eq {
  margin: 1.2rem 0 0.3rem;
  overflow-x: auto;
  text-align: center;
}
.eq .MathJax_Display { margin: 0 !important; }
.eq-label {
  font-size: 12px;
  color: #999;
  margin: 0 0 1.4rem;
  font-style: italic;
  padding-left: 4px;
  line-height: 1.5;
}
.callout {
  background: #f8f8f8;
  border-left: 2.5px solid #ddd;
  padding: 12px 18px;
  margin: 1.4rem 0;
  border-radius: 0 6px 6px 0;
  font-size: 15px;
  color: #555;
  line-height: 1.7;
}
.interactive-box {
  border: 0.5px solid #e0e0e0;
  border-radius: 8px;
  padding: 16px 16px 10px;
  margin: 0;
}
@media (max-width: 540px) {
  #unifiedGrid { grid-template-columns: repeat(2, 1fr) !important; }
}
.trajectory-sketch {
  background: #f5f5f5;
  padding: 14px 20px;
  border-radius: 8px;
  margin: 1rem 0 0.4rem;
  border: 0.5px solid #e0e0e0;
  overflow-x: auto;
  font-family: ui-monospace, SFMono-Regular, "SF Mono", Menlo, Consolas, monospace;
  font-size: 13px;
  line-height: 1.55;
  color: #333;
}
.trajectory-sketch pre {
  margin: 0;
  white-space: pre;
  font-family: inherit;
  font-size: inherit;
  line-height: inherit;
  color: inherit;
}
@media (prefers-color-scheme: dark) {
  .eq .MathJax, .eq .MathJax_Display, .eq .MathJax svg { color: #e0e0e0 !important; fill: #e0e0e0 !important; }
  .eq-label { color: #666; }
  .callout { background: #222; border-color: #444; color: #aaa; }
  .interactive-box { border-color: #333; }
  #unifiedDetail { background: #252525 !important; color: #b0b0b0 !important; }
  .trajectory-sketch { background: #252525; border-color: #333; color: #c8c8c8; }
}
.ref-list { font-size: 13px; color: #888; padding-left: 1.4rem; }
.ref-list li { margin-bottom: 0.35rem; line-height: 1.5; }
sup.cite a { color: var(--fig-blue); text-decoration: none; font-size: 11px; vertical-align: super; }
sup.cite a:hover { text-decoration: underline; }
/* Footnotes: visually distinct from citations — smaller, muted, italic numeral */
sup[role="doc-noteref"] a.footnote {
  color: var(--fig-ink-mute);
  text-decoration: none;
  font-size: 10px;
  font-style: italic;
  vertical-align: super;
  padding: 0 0.05em;
}
sup[role="doc-noteref"] a.footnote::before { content: "†"; margin-right: 0.1em; }
sup[role="doc-noteref"] a.footnote:hover { color: var(--fig-ink); text-decoration: underline; }
.footnotes { font-size: 13px; color: var(--fig-ink-soft); border-top: 1px solid var(--fig-frame); margin-top: 2.4rem; padding-top: 0.8rem; }
.footnotes ol { padding-left: 1.2rem; }
.footnotes li { margin-bottom: 0.4rem; line-height: 1.55; }
.footnotes a.reversefootnote { color: var(--fig-ink-mute); text-decoration: none; margin-left: 0.3em; }
.footnotes a.reversefootnote:hover { color: var(--fig-ink); }
.post-table-wrap { overflow-x: auto; margin: 1.4rem 0 1.6rem; }
.post-table {
  border-collapse: collapse;
  font-size: 14px;
  width: 100%;
  min-width: 480px;
  line-height: 1.55;
  border-top: 1.5px solid #1d1d1f;
  border-bottom: 1.5px solid #1d1d1f;
}
.post-table th {
  text-align: left;
  padding: 10px 14px 8px;
  font-weight: 600;
  color: #1d1d1f !important;
  border-bottom: 1px solid #1d1d1f;
  font-size: 11.5px;
  letter-spacing: 0.06em;
  text-transform: uppercase;
}
.post-table th.center, .post-table td.center { text-align: center; }
.post-table td {
  padding: 12px 14px;
  border-bottom: 1px solid #d8d4cc;
  color: #1d1d1f !important;
  vertical-align: top;
}
.post-table td.muted, .post-table .muted { color: #6a6660; }
.post-table td.good { color: #1d1d1f; font-weight: 500; }
.post-table td.label { color: #1d1d1f; font-weight: 600; }
.post-table tr:last-child td { border-bottom: none; }
.post-table-note { font-size: 12px; color: #6a6660; margin: 8px 2px 0; font-style: italic; }
.post-table code {
  background: transparent;
  padding: 0;
  border: 0;
  font-size: 13px;
  color: #1d1d1f;
  font-family: ui-monospace, SFMono-Regular, "SF Mono", Menlo, Consolas, monospace;
  white-space: nowrap;
}
@media (prefers-color-scheme: dark) {
  .post-table { border-top-color: #f0ece4; border-bottom-color: #f0ece4; }
  .post-table th { color: #f0ece4; border-bottom-color: #f0ece4; }
  .post-table td { color: #f0ece4; border-bottom-color: #4a4a50; }
  .post-table td.muted, .post-table .muted { color: #989288; }
  .post-table td.good, .post-table td.label { color: #f0ece4; }
  .post-table-note { color: #989288; }
  .post-table code { color: #f0ece4; }
}
</style>

I have been spending a lot of time on one-step generative models, specifically MeanFlow and the broader family it belongs to. This is my attempt to build an honest mental model of how these methods work, where the math comes from, and how each one is a response to the limitations of the one before it.

The context: diffusion and flow models can now produce images, audio, and video that are nearly indistinguishable from real data, but generating a single sample requires hundreds of sequential network evaluations. A 1024×1024 image from a model like <a href="https://arxiv.org/abs/2307.01952" target="_blank" rel="noopener noreferrer">SDXL</a> at 50 steps takes several seconds on an A100; a one-step distilled model produces the same resolution in tens of milliseconds. That is fine for offline synthesis. It is nearly unusable for anything interactive or real-time. The last two years, people have been trying hard to fix this, and the results are surprisingly good.

Starting from the goal and working backwards: what does a network need to learn to generate in one step?

---

## Generation as transport

Generating a sample (say, an image of a dog) is moving probability mass from a noise distribution to the data distribution. In general this transport can be either stochastic (a forward and reverse diffusion SDE) or deterministic (an ODE); every method in this post lives in the deterministic regime, on a path called the probability flow ODE (PF-ODE) <sup class="cite"><a href="#ref-songsde2021">[11]</a></sup>.[^pfode-marginal] Parameterise it by time $t$ running from $t=1$ (pure Gaussian noise) to $t=0$ (a clean data sample, like our dog). Write $z_t$ for the point on the trajectory at time $t$, and $x_0$ for the clean data sample at $t=0$. Because it is deterministic, you can run it forwards or backwards exactly.

[^pfode-marginal]: This marginal-equivalence is the result that opened up the entire deterministic-sampler line of work in diffusion (DDIM-style integrators, exact likelihood evaluation, every method in this post). The claim: the PF-ODE shares the same time-marginal distributions $p_t(x)$ as the stochastic diffusion process for every $t$, even though one is deterministic and the other is not. The PF-ODE drift is $\frac{dx}{dt} = f(x,t) - \tfrac{1}{2}g(t)^2 \nabla_x \log p_t(x)$, with $f$ and $g$ the SDE's drift and diffusion coefficients. The proof is direct: both processes induce the same Fokker–Planck equation for $p_t(x)$, so any solution to one is a solution to the other at the marginal level (Song et al. 2021, Appendix D.1). Trajectories differ, marginals do not.

Think of a soap bubble. Press it slowly and the surface deforms; every point on the film follows a smooth path. Release the pressure and it snaps back along the exact same path, not an approximation. The PF-ODE is that elastic surface in probability space, and $z_t$ is one spot on the surface at time $t$.

Standard diffusion and flow models learn to estimate the PF-ODE velocity locally, then numerically solve the learned ODE step by step from noise to data. At each $z_t$ the network tells you how fast and in which direction to move to keep heading toward the dog (or whatever target the trajectory points to). Apply that, take a step, repeat.

<figure class="fig-card">
<div class="fig-card-title">A noise distribution flowing into a data distribution</div>
<div class="fig-card-inner">
<svg viewBox="0 0 680 260" role="img" style="font-family:'Iowan Old Style',Charter,Georgia,serif;">
<title>Distribution snapshots along the PF-ODE from noise to data</title>
<desc>Five snapshots of a probability distribution along the trajectory from t=1 to t=0. At t=1 the points form a wide diffuse Gaussian cloud (noise). At each subsequent snapshot the cloud progressively contracts and structures itself, until at t=0 the points form a tight cluster on the data manifold.</desc>
<defs>
  <marker id="flowArrow" viewBox="0 0 10 10" refX="9" refY="5" markerWidth="7" markerHeight="7" orient="auto-start-reverse">
    <path d="M1 1L9 5L1 9" fill="none" stroke="context-stroke" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
  </marker>
  <radialGradient id="cloudFade" cx="50%" cy="50%" r="50%">
    <stop offset="0%" stop-color="var(--fig-ink-soft)" stop-opacity="0.18"/>
    <stop offset="100%" stop-color="var(--fig-ink-soft)" stop-opacity="0"/>
  </radialGradient>
</defs>

<!-- Snapshot 1: t=1, pure noise, wide diffuse cloud -->
<g transform="translate(70,110)">
  <circle r="58" fill="url(#cloudFade)"/>
  <circle cx="-22" cy="-32" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <circle cx="18" cy="-26" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <circle cx="-34" cy="-6" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <circle cx="28" cy="4" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <circle cx="-10" cy="18" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <circle cx="14" cy="30" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <circle cx="-28" cy="32" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <circle cx="34" cy="-12" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <circle cx="2" cy="-12" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <circle cx="-6" cy="42" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <circle cx="22" cy="40" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <circle cx="-40" cy="14" r="2.2" fill="var(--fig-ink-soft)" opacity="0.55"/>
  <text font-size="11" fill="var(--fig-ink-mute)" y="84" text-anchor="middle">t = 1.0</text>
  <text font-size="10" fill="var(--fig-ink-mute)" y="98" text-anchor="middle" font-style="italic">noise</text>
</g>

<!-- Snapshot 2: t=0.75, slightly concentrated -->
<g transform="translate(210,110)">
  <circle r="44" fill="url(#cloudFade)"/>
  <circle cx="-14" cy="-22" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <circle cx="14" cy="-18" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <circle cx="-24" cy="0" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <circle cx="20" cy="2" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <circle cx="-6" cy="14" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <circle cx="10" cy="22" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <circle cx="-18" cy="24" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <circle cx="24" cy="-8" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <circle cx="0" cy="-8" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <circle cx="-2" cy="32" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <circle cx="16" cy="28" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <circle cx="-28" cy="10" r="2.2" fill="var(--fig-ink-soft)" opacity="0.7"/>
  <text font-size="11" fill="var(--fig-ink-mute)" y="84" text-anchor="middle">t = 0.75</text>
</g>

<!-- Snapshot 3: t=0.5, modes starting to separate -->
<g transform="translate(340,110)">
  <ellipse rx="34" ry="24" fill="url(#cloudFade)"/>
  <!-- early hints of three modes: top-left, top-right, bottom -->
  <circle cx="-22" cy="-12" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <circle cx="-16" cy="-6" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <circle cx="-26" cy="-4" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <circle cx="-12" cy="-14" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <circle cx="22" cy="-12" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <circle cx="18" cy="-4" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <circle cx="26" cy="-6" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <circle cx="12" cy="-10" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <circle cx="-4" cy="14" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <circle cx="4" cy="16" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <circle cx="0" cy="20" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <circle cx="-8" cy="18" r="2.2" fill="var(--fig-blue)" opacity="0.7"/>
  <text font-size="11" fill="var(--fig-ink-mute)" y="84" text-anchor="middle">t = 0.5</text>
</g>

<!-- Snapshot 4: t=0.25, three modes clearly separating -->
<g transform="translate(470,110)">
  <!-- top-left blob -->
  <ellipse cx="-18" cy="-10" rx="10" ry="7" fill="var(--fig-sage)" opacity="0.10"/>
  <circle cx="-20" cy="-12" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <circle cx="-14" cy="-10" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <circle cx="-22" cy="-8" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <circle cx="-16" cy="-14" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <!-- top-right blob -->
  <ellipse cx="18" cy="-10" rx="10" ry="7" fill="var(--fig-sage)" opacity="0.10"/>
  <circle cx="20" cy="-12" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <circle cx="14" cy="-10" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <circle cx="22" cy="-8" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <circle cx="16" cy="-14" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <!-- bottom blob -->
  <ellipse cx="0" cy="14" rx="10" ry="7" fill="var(--fig-sage)" opacity="0.10"/>
  <circle cx="-2" cy="12" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <circle cx="4" cy="14" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <circle cx="-4" cy="16" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <circle cx="2" cy="18" r="2.3" fill="var(--fig-sage)" opacity="0.85"/>
  <text font-size="11" fill="var(--fig-ink-mute)" y="84" text-anchor="middle">t = 0.25</text>
</g>

<!-- Snapshot 5: t=0, three tight data modes ("cats / dogs / cars") -->
<g transform="translate(600,110)">
  <!-- top-left: dogs -->
  <ellipse cx="-15" cy="-12" rx="6" ry="4" fill="var(--fig-sage)" opacity="0.20"/>
  <circle cx="-17" cy="-13" r="2.5" fill="var(--fig-sage)" opacity="0.95"/>
  <circle cx="-13" cy="-12" r="2.5" fill="var(--fig-sage)" opacity="0.95"/>
  <circle cx="-15" cy="-10" r="2.5" fill="var(--fig-sage)" opacity="0.95"/>
  <text font-size="8.5" fill="var(--fig-sage)" x="-15" y="-22" text-anchor="middle" font-style="italic">dogs</text>
  <!-- top-right: cats -->
  <ellipse cx="15" cy="-12" rx="6" ry="4" fill="var(--fig-sage)" opacity="0.20"/>
  <circle cx="13" cy="-13" r="2.5" fill="var(--fig-sage)" opacity="0.95"/>
  <circle cx="17" cy="-12" r="2.5" fill="var(--fig-sage)" opacity="0.95"/>
  <circle cx="15" cy="-10" r="2.5" fill="var(--fig-sage)" opacity="0.95"/>
  <text font-size="8.5" fill="var(--fig-sage)" x="15" y="-22" text-anchor="middle" font-style="italic">cats</text>
  <!-- bottom: cars -->
  <ellipse cx="0" cy="14" rx="6" ry="4" fill="var(--fig-sage)" opacity="0.20"/>
  <circle cx="-2" cy="13" r="2.5" fill="var(--fig-sage)" opacity="0.95"/>
  <circle cx="2" cy="14" r="2.5" fill="var(--fig-sage)" opacity="0.95"/>
  <circle cx="0" cy="16" r="2.5" fill="var(--fig-sage)" opacity="0.95"/>
  <text font-size="8.5" fill="var(--fig-sage)" x="0" y="30" text-anchor="middle" font-style="italic">cars</text>
  <text font-size="11" fill="var(--fig-sage)" y="84" text-anchor="middle">t = 0.0</text>
  <text font-size="10" fill="var(--fig-sage)" y="98" text-anchor="middle" font-style="italic">data manifold</text>
</g>

<!-- Flow arrows between snapshots, drawn faintly -->
<path d="M 138 110 L 162 110" stroke="var(--fig-ink-mute)" stroke-width="1" opacity="0.5" marker-end="url(#flowArrow)"/>
<path d="M 262 110 L 288 110" stroke="var(--fig-ink-mute)" stroke-width="1" opacity="0.5" marker-end="url(#flowArrow)"/>
<path d="M 384 110 L 420 110" stroke="var(--fig-ink-mute)" stroke-width="1" opacity="0.5" marker-end="url(#flowArrow)"/>
<path d="M 500 110 L 574 110" stroke="var(--fig-ink-mute)" stroke-width="1" opacity="0.5" marker-end="url(#flowArrow)"/>

<!-- Time axis at bottom -->
<line x1="60" y1="232" x2="620" y2="232" stroke="var(--fig-frame)" stroke-width="0.8"/>
<text font-size="10.5" fill="var(--fig-ink-mute)" x="60" y="248">time runs this way →</text>
</svg>
</div>
<figcaption>The PF-ODE moves a probability distribution from pure noise at $t=1$ into the data distribution at $t=0$. The five snapshots show the cloud progressively contracting and structuring itself as time decreases. A single sample $z_t$ rides along this flow; every one-step method in the post is trying to skip directly from one snapshot to another.</figcaption>
</figure>

The core problem with step-by-step integration: the local velocity at $z_t$ tells you nothing about where the trajectory ends up globally. You have to follow it closely, one small step at a time, or you drift off course and end up somewhere wrong. This is expensive.

Two strategies:

1. **Jump to the endpoint directly.** Learn a function that maps any trajectory point to $x_0$ in one shot. This is the consistency model <sup class="cite"><a href="#ref-song2023">[2]</a></sup> idea.
2. **Jump to any point, not just the endpoint.** Learn a two-time function that can jump from any $t$ to any $s < t$ in one step. This is the flow map idea, and it is what consistency trajectory models (CTM), shortcut models, and MeanFlow build on.

---

## Flow matching

Flow matching is the foundation every one-step method either builds on or borrows training structure from, so the rest of the post assumes the setup. Flow matching <sup class="cite"><a href="#ref-lipman2022">[1]</a></sup> frames generation as *transport*: learn a continuous-time flow that moves probability mass from one distribution to another. The source and destination can be any two distributions; unlike diffusion, which fixes the noisy end to Gaussian noise by construction, flow matching has no such constraint. In practice, the simplest useful case uses Gaussian noise as the source, giving straight-line paths between noise and data:[^fm-source]

[^fm-source]: Any source distribution works in the flow-matching framework; Gaussian is convenient because the velocity calculation collapses cleanly, not because it is required. Recent work on optimal-transport flow matching exploits this freedom directly.

<div class="eq">$$z_t \;=\; (1 - t)\, x_0 \;+\; t\, x_1$$</div>
<div class="eq-label">$x_0$ is clean data, $x_1 \sim \mathcal{N}(0,I)$, $t \in [0,1]$. At $t=0$ you have data; at $t=1$ you have noise.</div>

The velocity at any point on this path is constant: $v = x_0 - x_1$. It is directly computable from a training pair $(x_0, x_1)$, no integration or simulation needed. A neural network $v_\theta(z_t, t)$ is then trained to predict this velocity at every $(z_t, t)$, by minimising the regression loss $\lVert v_\theta(z_t, t) - (x_0 - x_1) \rVert^2$. Clean supervised learning against a ground-truth target.

Inference is still slow, though. Even though each individual path $x_0 \leftrightarrow x_1$ is a straight line, the *marginal* velocity field is not. At any given noisy image $z_t$, many different clean images $x_0$ are plausible, not just one. Each candidate has its own straight-line velocity pointing in a slightly different direction. The network has to output the probability-weighted average of all those directions, which traces a curved path through image space. Following a curved path with only local velocity information requires many small steps.

Scrub the demo below to watch this happen: at $t=1$ the field points toward the centroid (no cluster has been chosen), and as $t$ decreases the weights concentrate and particles fan out toward different clusters. Try the *candidates* buttons (1, 3, 5) to see how a single cluster gives a uniform field while multiple clusters force curvature.

<style>
.mv-label { font-size: 11px; color: var(--fig-ink-mute); letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 4px; }
.mv-nbtn { transition: background 0.15s, color 0.15s; }
.mv-nbtn.mv-active { background: var(--fig-blue); color: var(--fig-paper); border-color: var(--fig-blue); }
</style>
<figure class="fig-card">
<div class="fig-card-title">Marginal velocity field with k clusters</div>
<div class="fig-card-inner">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;flex-wrap:wrap;">
    <button id="mvPlay" class="play-btn">&#9654; play</button>
    <div style="display:flex;align-items:center;gap:8px;flex:1;min-width:160px;">
      <span style="font-size:12px;color:var(--fig-ink-mute);white-space:nowrap;">t =</span>
      <input type="range" min="0" max="100" value="100" step="1" id="mvScrub" style="flex:1;">
      <span id="mvTout" style="font-size:12px;font-weight:500;color:var(--fig-ink-soft);min-width:28px;">1.00</span>
    </div>
    <div style="display:flex;align-items:center;gap:6px;">
      <span style="font-size:12px;color:var(--fig-ink-mute);">candidates</span>
      <button id="mvN1" class="play-btn mv-nbtn" style="min-width:28px;padding:3px 8px;">1</button>
      <button id="mvN3" class="play-btn mv-nbtn" style="min-width:28px;padding:3px 8px;">3</button>
      <button id="mvN5" class="play-btn mv-nbtn" style="min-width:28px;padding:3px 8px;">5</button>
    </div>
  </div>
  <canvas id="cvMarginal" style="width:100%;display:block;border-radius:4px;"></canvas>
  <div style="display:flex;justify-content:space-between;margin-top:8px;gap:8px;">
    <p id="mvCapL" style="font-size:12px;color:var(--fig-ink-soft);margin:0;flex:1;">1 destination: field is uniform everywhere. A single step from any noise point lands exactly at x₀.</p>
    <p id="mvCapR" style="font-size:12px;color:var(--fig-ink-soft);margin:0;flex:1;text-align:right;"></p>
  </div>
</div>
<figcaption>The marginal velocity field $\bar{v}(z,t)$ (amber arrows) and its live weight decomposition (bottom panel). Each cluster on the right represents a region of data space: cats, dogs, cars. At $t=1$ (pure noise) the Gaussian weights $w_i \propto \exp(-\|z - z_t^{(i)}\|^2 / 2\sigma^2)$ over all clusters are nearly equal: the particle has no information about which cluster it will become, so the field averages all their directions and points toward the centroid. As $t$ decreases the weights concentrate: the particle's position becomes informative about which cluster it is heading to, and the field progressively commits. With one cluster the field is uniform and one step is exact. With multiple clusters, a single large step follows the initial average direction and lands between all clusters. The red ghost shows exactly where.</figcaption>
</figure>

<script>
(function(){
  var cv  = document.getElementById('cvMarginal');
  var ctx = cv.getContext('2d');
  var LW = 640, LH = 400, MAIN_H = LH - 110;
  var TRAJ_STEPS = 400, N_CLOUD = 26, ANIM_DUR = 4200;
  function cssVar(name){ return getComputedStyle(document.documentElement).getPropertyValue(name).trim(); }
  function palette(){
    return [cssVar('--fig-blue'), cssVar('--fig-sage'), cssVar('--fig-amber'), cssVar('--fig-plum'), cssVar('--fig-ochre')];
  }
  var scenarios, nActive = 1, tVal = 1.0;
  var animating = false, rafId = null, animStart = null;

  function setupCanvas(){
    var dpr  = window.devicePixelRatio || 1;
    var cssW = cv.parentElement.clientWidth || LW;
    var cssH = Math.round(cssW * LH / LW);
    cv.width  = cssW * dpr; cv.height = cssH * dpr;
    cv.style.width  = cssW + 'px';
    cv.style.height = cssH + 'px';
    ctx.setTransform(dpr * cssW / LW, 0, 0, dpr * cssH / LH, 0, 0);
  }

  function margV(px, py, t, noisePts, modes){
    var sigma = 38, wx = 0, wy = 0, ws = 0, i, cpx, cpy, d2, w;
    for(i = 0; i < modes.length; i++){
      cpx = (1-t)*modes[i].x + t*noisePts[i].x;
      cpy = (1-t)*modes[i].y + t*noisePts[i].y;
      d2  = (px-cpx)*(px-cpx) + (py-cpy)*(py-cpy);
      w   = Math.exp(-d2 / (2*sigma*sigma));
      wx += w * (modes[i].x - noisePts[i].x);
      wy += w * (modes[i].y - noisePts[i].y);
      ws += w;
    }
    return ws < 1e-9 ? {x:0, y:0} : {x: wx/ws, y: wy/ws};
  }

  function tracePath(start, noisePts, modes){
    var pts = [{x: start.x, y: start.y}], i, t, p, v;
    for(i = 0; i < TRAJ_STEPS; i++){
      t = 1 - i / TRAJ_STEPS;
      p = pts[pts.length - 1];
      v = margV(p.x, p.y, t, noisePts, modes);
      pts.push({x: p.x + v.x / TRAJ_STEPS, y: p.y + v.y / TRAJ_STEPS});
    }
    return pts;
  }

  function rawWeights(px, py, t, noisePts, modes){
    var sigma = 38, i, cpx, cpy, d2, out = [];
    for(i = 0; i < modes.length; i++){
      cpx = (1-t)*modes[i].x + t*noisePts[i].x;
      cpy = (1-t)*modes[i].y + t*noisePts[i].y;
      d2  = (px-cpx)*(px-cpx) + (py-cpy)*(py-cpy);
      out.push(Math.exp(-d2 / (2*sigma*sigma)));
    }
    return out;
  }

  function arrowHead(x, y, angle, size, col){
    ctx.save(); ctx.translate(x, y); ctx.rotate(angle);
    ctx.beginPath(); ctx.moveTo(0, 0);
    ctx.lineTo(-size, -size*0.38); ctx.lineTo(-size, size*0.38);
    ctx.closePath(); ctx.fillStyle = col; ctx.fill(); ctx.restore();
  }

  // Seeded LCG for deterministic randomness
  function seededRand(seed){
    var s = seed >>> 0;
    return function(){ s = (Math.imul(s, 1664525) + 1013904223) >>> 0; return s / 0x100000000; };
  }

  function makeScenario(n){
    var noiseX = 82, dataX = 530, cy = MAIN_H / 2;
    var dataSpread = n === 1 ? 0 : Math.min(n * 46, MAIN_H - 56);

    // --- cluster centres (modes) evenly spaced vertically ---
    var modes = [], i, frac;
    for(i = 0; i < n; i++){
      frac = n === 1 ? 0.5 : i / (n - 1);
      modes.push({x: dataX, y: cy + (frac - 0.5) * dataSpread});
    }

    // Noise samples scatter inside the noise blob (roughly ±14px around noiseX),
    // not stacked on a single vertical line. Mirrors what samples from a 2D
    // Gaussian actually look like.
    var NOISE_JITTER = 14;
    function noiseJX(r){ return noiseX + (r - 0.5) * 2 * NOISE_JITTER; }

    // --- MANY random (noise, mode) pairs that define the marginal field ---
    // Each pair assigns a random noise point to a random cluster.
    // This creates the genuine mixture: the field at any point averages over
    // all nearby conditional paths, producing a curved marginal field.
    var N_PAIRS = 40;
    var rand = seededRand(n * 7919 + 1);
    var noisePts = [], pairModes = [];
    for(i = 0; i < N_PAIRS; i++){
      var clusterIdx = Math.floor(rand() * n);
      noisePts.push({x: noiseJX(rand()), y: 18 + rand() * (MAIN_H - 36)});
      pairModes.push(modes[clusterIdx]);
    }

    // --- highlighted particles: fixed starting positions, NOT matching any pair ---
    // These experience the ambiguous marginal field and must curve to sort.
    // Use a separate seeded jitter so positions are deterministic per n but
    // visually scattered like real noise samples.
    var pRand = seededRand(n * 104729 + 13);
    var particleStarts = [];
    if(n === 1){
      particleStarts = [{x: noiseX, y: cy}];
    } else {
      for(i = 0; i < n; i++){
        frac = i / (n - 1);
        particleStarts.push({x: noiseJX(pRand()), y: 22 + frac * (MAIN_H - 44)});
      }
    }
    var exactPaths = particleStarts.map(function(s){ return tracePath(s, noisePts, pairModes); });

    // --- cloud: scattered starts show full field structure ---
    var cRand = seededRand(n * 31 + 7);
    var cloudStarts = [], cloudPaths;
    for(i = 0; i < N_CLOUD; i++){
      cloudStarts.push({x: noiseJX(cRand()), y: 14 + i * (MAIN_H - 28) / (N_CLOUD - 1)});
    }
    cloudPaths = cloudStarts.map(function(s){ return tracePath(s, noisePts, pairModes); });

    // --- ghost: one large step from the middle highlighted particle ---
    // Stretch along the velocity until x reaches dataX; this is what a 1-step
    // Euler integrator from t=1 to t=0 would do.
    var midIdx = Math.floor(particleStarts.length / 2);
    var ghostStart = particleStarts[midIdx];
    var v0 = margV(ghostStart.x, ghostStart.y, 1.0, noisePts, pairModes);
    var ghost = {x: dataX, y: ghostStart.y + v0.y * (dataX - ghostStart.x) / Math.max(1e-6, v0.x)};

    return {n: n, modes: modes, noisePts: noisePts, pairModes: pairModes,
            dataSpread: dataSpread, exactPaths: exactPaths,
            cloudPaths: cloudPaths, ghost: ghost};
  }

  function drawScene(sc, t){
    var dark   = window.matchMedia('(prefers-color-scheme:dark)').matches;
    var PAL    = palette();
    var AMBER  = cssVar('--fig-amber');
    var ROSE   = cssVar('--fig-rose');
    var BG     = cssVar('--fig-panel');
    var INK    = cssVar('--fig-ink');
    var INK_S  = cssVar('--fig-ink-soft');
    var INK_M  = cssVar('--fig-ink-mute');
    var FRAME  = cssVar('--fig-frame');
    var n    = sc.n;
    var stepIdx = Math.min(Math.round((1-t) * TRAJ_STEPS), TRAJ_STEPS - 1);
    var gi, gj, px, py, v, vl, ex, ey, alpha, i, j, path, cp, col, prev, by, ws, wsSum, wNorm, maxW, note, bx, blw, bw, bh, w;

    ctx.clearRect(0, 0, LW, LH);
    ctx.fillStyle = BG; ctx.fillRect(0, 0, LW, LH);

    // divider
    ctx.beginPath(); ctx.moveTo(0, MAIN_H); ctx.lineTo(LW, MAIN_H);
    ctx.strokeStyle = FRAME; ctx.lineWidth = 0.5; ctx.stroke();

    // velocity field
    for(gi = 0; gi < 11; gi++){
      for(gj = 0; gj < 8; gj++){
        px = 50 + gi * (LW - 100) / 10;
        py = 14 + gj * (MAIN_H - 28) / 7;
        v  = margV(px, py, t, sc.noisePts, sc.pairModes);
        vl = Math.sqrt(v.x*v.x + v.y*v.y);
        if(vl < 0.5) continue;
        ex = px + v.x / vl * 20; ey = py + v.y / vl * 20;
        alpha = n === 1 ? 0.14 : Math.min(0.50, 0.08 + vl / 300);
        ctx.globalAlpha = alpha;
        ctx.beginPath(); ctx.moveTo(px, py); ctx.lineTo(ex, ey);
        ctx.strokeStyle = AMBER; ctx.lineWidth = 1.1; ctx.stroke();
        arrowHead(ex, ey, Math.atan2(v.y, v.x), 5, AMBER);
        ctx.globalAlpha = 1;
      }
    }

    // cloud trails
    for(i = 0; i < sc.cloudPaths.length; i++){
      path = sc.cloudPaths[i];
      if(stepIdx < 2) continue;
      ctx.beginPath();
      for(j = 0; j <= stepIdx && j < path.length; j++){
        if(j === 0) ctx.moveTo(path[j].x, path[j].y);
        else        ctx.lineTo(path[j].x, path[j].y);
      }
      ctx.strokeStyle = dark ? 'rgba(216,212,204,0.06)' : 'rgba(58,56,51,0.07)';
      ctx.lineWidth = 1; ctx.stroke();
      cp = path[Math.min(stepIdx, path.length - 1)];
      ctx.beginPath(); ctx.arc(cp.x, cp.y, 2, 0, Math.PI*2);
      ctx.fillStyle = dark ? 'rgba(216,212,204,0.20)' : 'rgba(58,56,51,0.18)'; ctx.fill();
    }

    // exact marginal paths
    for(i = 0; i < sc.exactPaths.length; i++){
      path = sc.exactPaths[i];
      col  = n === 1 ? PAL[0] : PAL[i % PAL.length];
      if(stepIdx >= 2){
        ctx.beginPath();
        for(j = 0; j <= stepIdx && j < path.length; j++){
          if(j === 0) ctx.moveTo(path[j].x, path[j].y);
          else        ctx.lineTo(path[j].x, path[j].y);
        }
        ctx.strokeStyle = col; ctx.lineWidth = 2.2; ctx.globalAlpha = 0.88; ctx.stroke();
        ctx.globalAlpha = 1;
      }
      cp = path[Math.min(stepIdx, path.length - 1)];
      ctx.beginPath(); ctx.arc(cp.x, cp.y, 5.5, 0, Math.PI*2);
      ctx.fillStyle = col; ctx.globalAlpha = 0.92; ctx.fill(); ctx.globalAlpha = 1;
      if(stepIdx > 5 && stepIdx < path.length - 1){
        prev = path[Math.max(0, stepIdx - 5)];
        arrowHead(cp.x, cp.y, Math.atan2(cp.y - prev.y, cp.x - prev.x), 8, col);
      }
    }

    // 1-step ghost
    if(n > 1){
      ctx.beginPath(); ctx.arc(sc.ghost.x, sc.ghost.y, 6, 0, Math.PI*2);
      ctx.strokeStyle = ROSE; ctx.lineWidth = 1.5;
      ctx.setLineDash([3, 3]); ctx.stroke(); ctx.setLineDash([]);
      ctx.globalAlpha = 0.4; ctx.fillStyle = ROSE; ctx.fill(); ctx.globalAlpha = 1;
      ctx.font = '9px system-ui,sans-serif'; ctx.textAlign = 'center';
      ctx.fillStyle = ROSE; ctx.globalAlpha = 0.75;
      ctx.fillText('1-step lands here', sc.ghost.x, sc.ghost.y - 11);
      ctx.globalAlpha = 1;
    }

    // data clusters, drawn as sideways Gaussian density bumps
    // Each cluster cy±spread shows a filled bell curve extending leftward from dataX,
    // communicating "this is a region of probability mass, not just a point"
    var CLUSTER_NAMES = ['cats','dogs','cars','birds','boats'];
    var clusterSigma = n === 1 ? 30 : Math.max(8, Math.min(24, sc.dataSpread / (n * 1.8)));
    for(i = 0; i < sc.modes.length; i++){
      col = n === 1 ? PAL[0] : PAL[i % PAL.length];
      var mx = sc.modes[i].x, my = sc.modes[i].y;
      // sideways bell extending LEFT from the data wall; fills space toward trajectory
      var bSteps = 32, bMaxW = 28, bSig = clusterSigma, k;
      ctx.beginPath();
      ctx.moveTo(mx, my - bSig * 2.2);
      for(k = 0; k <= bSteps; k++){
        var dy = -bSig * 2.2 + k * (bSig * 4.4 / bSteps);
        var bx2 = mx - bMaxW * Math.exp(-(dy * dy) / (2 * bSig * bSig));
        ctx.lineTo(bx2, my + dy);
      }
      ctx.lineTo(mx, my + bSig * 2.2);
      ctx.closePath();
      ctx.fillStyle = col; ctx.globalAlpha = 0.15; ctx.fill(); ctx.globalAlpha = 1;
      ctx.strokeStyle = col; ctx.lineWidth = 1.4; ctx.globalAlpha = 0.6; ctx.stroke(); ctx.globalAlpha = 1;
      // centre dot on the wall
      ctx.beginPath(); ctx.arc(mx, my, 3.5, 0, Math.PI*2);
      ctx.fillStyle = col; ctx.fill();
      // label to the right of the wall
      ctx.font = '11px "Iowan Old Style",Charter,Georgia,serif'; ctx.textAlign = 'left'; ctx.fillStyle = col;
      var clabel = n === 1 ? 'data' : CLUSTER_NAMES[i] || ('cluster ' + (i+1));
      ctx.fillText(clabel, mx + 7, my + 4);
    }
    // noise side: single wide Gaussian bell extending rightward; unstructured, no clusters
    var noiseSig = MAIN_H * 0.22, noiseBMaxW = 22, noiseX2 = 80, noiseCY = MAIN_H / 2;
    ctx.beginPath();
    ctx.moveTo(noiseX2, noiseCY - noiseSig * 2.2);
    for(var ns = 0; ns <= 32; ns++){
      var ndy = -noiseSig * 2.2 + ns * (noiseSig * 4.4 / 32);
      ctx.lineTo(noiseX2 + noiseBMaxW * Math.exp(-(ndy*ndy)/(2*noiseSig*noiseSig)), noiseCY + ndy);
    }
    ctx.lineTo(noiseX2, noiseCY + noiseSig * 2.2);
    ctx.closePath();
    ctx.fillStyle = dark ? 'rgba(216,212,204,0.07)' : 'rgba(58,56,51,0.06)'; ctx.fill();
    ctx.strokeStyle = FRAME; ctx.lineWidth = 1; ctx.stroke();
    ctx.font = '10px "Iowan Old Style",Charter,Georgia,serif'; ctx.textAlign = 'right';
    ctx.fillStyle = INK_M;
    ctx.fillText('noise', noiseX2 - 3, noiseCY - 4);
    ctx.fillText('(x₁)', noiseX2 - 3, noiseCY + 8);

    // corner labels
    ctx.font = '10px "Iowan Old Style",Charter,Georgia,serif';
    ctx.fillStyle = INK_M; ctx.textAlign = 'left';
    ctx.fillText('t = ' + t.toFixed(2), 8, 13);
    ctx.fillStyle = AMBER; ctx.globalAlpha = 0.7; ctx.textAlign = 'right';
    ctx.fillText('velocity field', LW - 8, 13);
    ctx.globalAlpha = 1;

    // weight bars (n>1 only): aggregate the 40 pair weights into n cluster buckets
    var PY = MAIN_H + 7;
    if(n > 1){
      // Track the middle particle, the same one the ghost is computed from.
      var midI = Math.floor(sc.exactPaths.length / 2);
      cp = sc.exactPaths[midI][Math.min(stepIdx, sc.exactPaths[midI].length - 1)];
      // raw per-pair weights
      ws = rawWeights(cp.x, cp.y, t, sc.noisePts, sc.pairModes);
      // sum into cluster buckets by matching pairMode y to nearest mode y
      var clusterW = [], ci;
      for(ci = 0; ci < n; ci++) clusterW.push(0);
      for(i = 0; i < ws.length; i++){
        // find which cluster this pair belongs to
        var bestC = 0, bestD = Infinity;
        for(ci = 0; ci < n; ci++){
          var dd = Math.abs(sc.pairModes[i].y - sc.modes[ci].y);
          if(dd < bestD){ bestD = dd; bestC = ci; }
        }
        clusterW[bestC] += ws[i];
      }
      wsSum = 0;
      for(ci = 0; ci < n; ci++) wsSum += clusterW[ci];
      if(wsSum < 1e-9) wsSum = 1;
      wNorm = clusterW.map(function(x){ return x / wsSum; });

      ctx.font = '10px "Iowan Old Style",Charter,Georgia,serif'; ctx.textAlign = 'left';
      ctx.fillStyle = INK_S;
      ctx.fillText('w(z,t) at the middle particle: how much does each cluster pull?', 8, PY + 10);

      bx = 8; blw = 78; bw = 190; bh = 10;
      maxW = 0;
      for(i = 0; i < n; i++){
        w = wNorm[i];
        if(w > maxW) maxW = w;
        col = PAL[i % PAL.length];
        by = PY + 18 + i * 14;
        ctx.fillStyle = FRAME;
        ctx.fillRect(bx + blw, by, bw, bh);
        ctx.fillStyle = col; ctx.globalAlpha = 0.75;
        ctx.fillRect(bx + blw, by, Math.max(0, bw * w), bh);
        ctx.globalAlpha = 1;
        ctx.font = '10px "Iowan Old Style",Charter,Georgia,serif'; ctx.textAlign = 'left'; ctx.fillStyle = col;
        var cname = CLUSTER_NAMES[i] || ('cluster ' + (i+1));
        ctx.fillText('w' + (i+1) + ' (' + cname + ')', bx, by + bh - 1);
        ctx.textAlign = 'right'; ctx.fillStyle = INK_M;
        ctx.fillText((w * 100).toFixed(0) + '%', bx + blw - 3, by + bh - 1);
      }

      note = maxW > 0.75 ? 'committed: particle knows its cluster'
           : maxW > 0.45 ? 'concentrating: ambiguity resolving'
           :               'near-equal: destination unknown';
      ctx.font = '10px "Iowan Old Style",Charter,Georgia,serif'; ctx.fontStyle = 'italic'; ctx.textAlign = 'left';
      ctx.fillStyle = INK_M;
      ctx.fillText(note, bx + blw + bw + 10, PY + 22);
    }

    // progress bar
    var pbarX = 8, pbarW = LW - 16, pbarY = LH - 13;
    ctx.fillStyle = FRAME;
    ctx.fillRect(pbarX, pbarY, pbarW, 2);
    ctx.fillStyle = PAL[0]; ctx.globalAlpha = 0.7;
    ctx.fillRect(pbarX, pbarY, (1 - t) * pbarW, 2);
    ctx.globalAlpha = 1;
    ctx.font = '10px "Iowan Old Style",Charter,Georgia,serif'; ctx.fillStyle = INK_M;
    ctx.textAlign = 'left';  ctx.fillText('noise t=1', pbarX, pbarY + 11);
    ctx.textAlign = 'right'; ctx.fillText('data t=0',  pbarX + pbarW, pbarY + 11);
  }

  function redraw(){ drawScene(scenarios[nActive], tVal); updateCap(); }

  function updateCap(){
    var caps1 = [
      [1.0, 0.55, 'One data cluster: the field is uniform everywhere. Marginal velocity = conditional velocity. A single Euler step from any noise point lands exactly on the data.'],
      [0.55, 0.1, 'One data cluster: particle streams straight, no curvature. One step is always exact.'],
      [0.1,  0.0, 'One data cluster: arrived. Single-step generation is perfect here.']
    ];
    var capsM = [
      [1.0, 0.60, 'Near t=1 (noise): the particle could become any cluster (cats, dogs, cars). The field averages all their directions equally, pointing toward the centroid. No cluster has been chosen yet. Watch the weight bars.'],
      [0.60, 0.15, 'Mid-journey: one cluster pulling ahead in the weights. Paths fan apart. A naive single large step from t=1 follows that initial average direction and lands between all clusters, reaching none of them.'],
      [0.15, 0.0, 'Near t=0 (data): weights fully concentrated on one cluster. Particles have sorted. The red ghost shows where the single-step shortcut would have landed: between clusters, reaching none of them.']
    ];
    var caps = nActive === 1 ? caps1 : capsM;
    var text = caps[caps.length - 1][2], k;
    for(k = 0; k < caps.length; k++){
      if(tVal <= caps[k][0] && tVal >= caps[k][1]){ text = caps[k][2]; break; }
    }
    document.getElementById('mvCapL').textContent = text;
    document.getElementById('mvCapR').textContent = nActive > 1
      ? (tVal > 0.62 ? 'ambiguity unresolved' : tVal > 0.18 ? 'structure emerging' : 'fully resolved')
      : '';
  }

  function animate(ts){
    if(!animStart) animStart = ts;
    var elapsed = (ts - animStart) % ANIM_DUR;
    tVal = 1 - elapsed / ANIM_DUR;
    document.getElementById('mvScrub').value = Math.round(tVal * 100);
    document.getElementById('mvTout').textContent = tVal.toFixed(2);
    redraw();
    if(animating) rafId = requestAnimationFrame(animate);
  }

  function stopAnim(){
    animating = false; animStart = null;
    if(rafId){ cancelAnimationFrame(rafId); rafId = null; }
    document.getElementById('mvPlay').innerHTML = '&#9654; play';
  }

  document.getElementById('mvPlay').addEventListener('click', function(){
    if(animating){ stopAnim(); return; }
    animating = true;
    document.getElementById('mvPlay').innerHTML = '&#9209; stop';
    animStart = null;
    var curT = tVal;
    rafId = requestAnimationFrame(function(ts){
      animStart = ts - (1 - curT) * ANIM_DUR;
      rafId = requestAnimationFrame(animate);
    });
  });

  document.getElementById('mvScrub').addEventListener('input', function(){
    stopAnim();
    tVal = parseInt(this.value) / 100;
    document.getElementById('mvTout').textContent = tVal.toFixed(2);
    redraw();
  });

  function setN(n){
    nActive = n;
    var btns = document.querySelectorAll('.mv-nbtn');
    for(var b = 0; b < btns.length; b++) btns[b].classList.remove('mv-active');
    var ids = {1:'mvN1', 3:'mvN3', 5:'mvN5'};
    if(ids[n]) document.getElementById(ids[n]).classList.add('mv-active');
    redraw();
  }
  document.getElementById('mvN1').addEventListener('click', function(){ setN(1); });
  document.getElementById('mvN3').addEventListener('click', function(){ setN(3); });
  document.getElementById('mvN5').addEventListener('click', function(){ setN(5); });

  window.matchMedia('(prefers-color-scheme:dark)').addEventListener('change', function(){ setupCanvas(); redraw(); });
  window.addEventListener('resize', function(){ setupCanvas(); redraw(); });

  // build scenarios, then set up canvas and draw
  scenarios = {1: makeScenario(1), 3: makeScenario(3), 5: makeScenario(5)};
  requestAnimationFrame(function(){ setupCanvas(); setN(1); });
})();
</script>

Flow matching gives you a simple regression objective at training time but slow inference. The rest of this post is about how recent research has explored fixing the inference side without giving up that simplicity.

---

## Consistency models

Consistency models <sup class="cite"><a href="#ref-song2023">[2]</a></sup> were the first serious attempt at fixing the inference problem. Rather than learn the velocity and integrate it, learn a function that maps any point on the trajectory directly to the clean endpoint $x_0$:

<div class="eq">$$f_\theta(z_t,\, t) \;=\; x_0 \qquad \text{for all } t \text{ on the same trajectory}$$</div>

Apply this once from pure noise, and you get a clean image. The model should give the same clean answer from any point on the same trajectory. Same idea as the equation above, drawn along one trajectory:

<div class="trajectory-sketch" role="img" aria-label="One PF-ODE trajectory from noise z_T to data x_0; f theta maps each noisy point to the same x_0, and is the identity at x_0."><pre>same trajectory:

noise side                         clean side
z_T  ─────── z_t ─────── z_s ─────── x_0

f_θ(z_T, T) → x_0
f_θ(z_t, t) → x_0
f_θ(z_s, s) → x_0
f_θ(x_0, 0) → x_0</pre></div>

For this to work, the function needs two properties. First, the **boundary condition**: at $t = 0$ (or more precisely, a small cutoff $\varepsilon$ near zero), the function must be the identity, $f_\theta(x_0, \varepsilon) = x_0$. A completely clean image maps to itself. Without it, the network could satisfy the rest of the loss by outputting a constant; the boundary condition pins one end of the function to something meaningful.

Second, the **consistency condition**: any two points on the *same* PF-ODE trajectory must map to the same $x_0$. If two different noisy versions of the same clean image both pass through the network, they should produce identical outputs. Without this, the network is only locally trained at individual points and the function never becomes globally coherent. The figure below shows what this looks like: every point along one trajectory is required to map to the same destination.

<figure class="fig-paper">
<div class="fig-paper-title">From the paper · Song et al. 2023, Fig. 1</div>
<img src="https://ar5iv.labs.arxiv.org/html/2303.01469/assets/figures/scheme.jpg"
     alt="Figure 1 from Song et al. (2023). A row of images along the probability flow ODE from clean data (a dog) at t=0 progressively to pure noise at t=T. Red arrows below show the consistency function f_theta mapping multiple noisy intermediate points (x_t, x_t', x_T) all back to the same clean endpoint x_0.">
<figcaption>The consistency function $f_\theta$ takes any point on the PF-ODE trajectory and maps it back to the same clean endpoint $x_0$. The condition is consistency across the whole trajectory, not just at individual points.</figcaption>
</figure>

How do you actually build $f_\theta$ so the boundary identity $f_\theta(x_0, \varepsilon) = x_0$ holds? The naive way is piecewise: define $f_\theta(x, t) = x$ when $t = \varepsilon$ and $f_\theta(x, t) = F_\theta(x, t)$ otherwise, where $F_\theta$ is a free neural network. This works for the discrete-time loss but breaks the moment you want continuous-time training, because the function is not differentiable at $\varepsilon$ and the continuous-time loss requires a clean derivative through $f_\theta$.

The fix the paper uses, and the one that has stuck since, is to **wire the boundary identity into the architecture algebraically** with a skip / output split:

<div class="eq">$$f_\theta(x, t) \;=\; c_\text{skip}(t)\, x \;+\; c_\text{out}(t)\, F_\theta(x, t)$$</div>
<div class="eq-label">Two scalar schedules $c_\text{skip}(t)$ and $c_\text{out}(t)$ are differentiable functions of $t$ designed so that $c_\text{skip}(\varepsilon) = 1$ and $c_\text{out}(\varepsilon) = 0$.</div>

At $t = \varepsilon$ the formula collapses to $f_\theta(x, \varepsilon) = 1 \cdot x + 0 \cdot F_\theta = x$. The boundary condition is true by construction; the loss never has to enforce it. Because $c_\text{skip}$, $c_\text{out}$, and $F_\theta$ are all differentiable in $t$, so is $f_\theta$. That matters for continuous-time consistency training, which needs a clean derivative of $f_\theta$ with respect to $t$.

The specific functional forms for $c_\text{skip}$ and $c_\text{out}$ are inherited directly from the EDM (elucidating diffusion models) preconditioning <sup class="cite"><a href="#ref-karras2022">[10]</a></sup>.[^edm-precond]

[^edm-precond]: This is a deliberate inheritance choice, not a derivation. EDM uses these schedules to keep the network's input and output magnitudes well-conditioned across noise levels (specifically $c_\text{skip}(t) = \sigma_\text{data}^2 / (\sigma_\text{data}^2 + t^2)$ and $c_\text{out}(t) = t \cdot \sigma_\text{data} / \sqrt{\sigma_\text{data}^2 + t^2}$ in the EDM noise parameterisation). Borrowing them lets consistency models drop into existing diffusion architectures with no structural changes, just a different head and loss.

<figure class="fig-card">
<div class="fig-card-title">Schedule weights along the PF-ODE</div>
<div class="fig-card-inner">
<svg viewBox="0 0 760 240" role="img" style="font-family:'Iowan Old Style',Charter,Georgia,serif;">
<title>Schedule weights c_skip and c_out along the PF-ODE</title>
<desc>Two curves on a coefficient axis. The skip weight rises toward 1 near the clean end; the trunk weight falls toward 0.</desc>
<!-- axes -->
<line x1="92" y1="186" x2="708" y2="186" stroke="var(--fig-ink-mute)" stroke-width="1"/>
<line x1="92" y1="186" x2="92"  y2="50"  stroke="var(--fig-ink-mute)" stroke-width="1"/>
<text font-size="11" fill="var(--fig-ink-mute)" x="84" y="56"  text-anchor="end">1</text>
<text font-size="11" fill="var(--fig-ink-mute)" x="84" y="121" text-anchor="end">½</text>
<text font-size="11" fill="var(--fig-ink-mute)" x="84" y="190" text-anchor="end">0</text>
<line x1="92" y1="118" x2="708" y2="118" stroke="var(--fig-frame)" stroke-width="0.5" stroke-dasharray="2 4"/>
<!-- curves -->
<path d="M 92 158 C 230 146 380 96 708 60"  fill="none" stroke="var(--fig-blue)"  stroke-width="2.2" stroke-linecap="round"/>
<path d="M 92 72  C 230 88  380 130 708 178" fill="none" stroke="var(--fig-amber)" stroke-width="2.2" stroke-linecap="round"/>
<!-- clean-end guide -->
<line x1="668" y1="50" x2="668" y2="186" stroke="var(--fig-ink-mute)" stroke-width="0.8" stroke-dasharray="3 4" opacity="0.7"/>
<!-- x-axis labels -->
<text font-size="11.5" fill="var(--fig-ink-soft)" x="92"  y="208" text-anchor="start">noisy (t→1)</text>
<text font-size="11.5" font-style="italic" fill="var(--fig-ink-mute)" x="400" y="208" text-anchor="middle">PF-ODE toward data →</text>
<text font-size="11.5" fill="var(--fig-ink-soft)" x="708" y="208" text-anchor="end">clean (t→0)</text>
<!-- legend (inline labels on curves) -->
<text font-size="12" fill="var(--fig-blue)"  x="708" y="48"  text-anchor="end">c_skip(t)</text>
<text font-size="12" fill="var(--fig-amber)" x="708" y="195" text-anchor="end">c_out(t)</text>
<!-- annotation at clean end -->
<text font-size="10.5" font-style="italic" fill="var(--fig-ink-mute)" x="660" y="232" text-anchor="end">at t→ε:  c_skip→1, c_out→0,  output ≈ z_t ≈ x₀</text>
</svg>
</div>
<figcaption markdown="1">
The skip weight $c_{\mathrm{skip}}(t)$ rises toward one as $t \to \varepsilon$ while the trunk weight $c_{\mathrm{out}}(t)$ falls to zero, so the wired output $f_\theta(z_t, t) = c_{\mathrm{skip}}(t)\, z_t + c_{\mathrm{out}}(t)\, F_\theta(z_t, t)$ becomes nearly the identity on $z_t$ near the clean end. The boundary condition falls out of the wiring; the loss does not have to learn it.
</figcaption>
</figure>

### Enforcing consistency via self-distillation

Here is the problem: you never directly observe which trajectory any $z_t$ belongs to. You cannot enumerate all the $(z_t, z_s)$ pairs that should agree. What you can do is take two *adjacent* points on the same trajectory, $z_t$ and $z_{t-\Delta}$ separated by a small step, and ask that their predictions agree:

<div class="eq">$$\mathcal{L}_\text{CD} \;=\; \mathbb{E}\,\bigl\lVert f_\theta(z_t,\,t) \;-\; \operatorname{sg}\!\bigl(f_{\theta^-}(z_{t-\Delta},\,t-\Delta)\bigr) \bigr\rVert^2$$</div>
<div class="eq-label">sg = stop-gradient. $\theta^-$ = EMA copy of $\theta$, updated slowly as $\theta^- \leftarrow m\,\theta^- + (1-m)\,\theta$.</div>

The EMA copy $\theta^-$ is updated slowly after each training step, typically $m \approx 0.99$, so the target moves at roughly 1% of the speed of the main network. This keeps the target stable enough to learn against. Without it, both sides of the loss update simultaneously and they can easily converge to the same wrong answer: outputting a constant everywhere, which technically satisfies the loss but is completely useless for generation.

The stop-gradient on the target side breaks this symmetry. Gradients only flow through the left side of the loss, so only $\theta$ is updated to chase the target. The target then drifts slowly via the EMA rule. This is the same *target network* trick that stabilised DQN in deep RL: when the network is regressing toward a target derived from itself, you keep the target frozen (or slowly-moving) so the optimisation has something fixed enough to converge to.

To get the adjacent point $z_{t-\Delta}$ on the same trajectory as $z_t$, you need to take one step of the PF-ODE. This is where the teacher and student framing becomes explicit. In consistency *distillation* (CD), a pretrained diffusion model acts as the teacher: it provides reliable one-step ODE moves that land on the true trajectory, and the student learns to jump directly to $x_0$ from any point on those teacher-generated trajectories. In consistency *training* (CT), there is no external teacher; the network has to produce its own one-step ODE moves to generate training pairs, which introduces additional noise and makes training harder to stabilise. CD is faster to converge and produces better results; CT avoids the dependency on a pretrained teacher at the cost of more careful engineering.

Worth pinning down the language here, because three different things in this post all get called "teacher" at various points and they are not the same. **Data supervision** means clean targets read directly from training pairs, like flow matching's $v = x_0 - x_1$. A **pretrained teacher** is an external network trained separately, used in CD and later in Align Your Flow (AYF); quality is capped at whatever the teacher can do. The **EMA copy** $\theta^-$ is the network's own slowly-moving lag of itself, used in CT, CTM, Shortcut, and the consistency half of MeanFlow. CT and CD differ exactly in this: CT has only the EMA copy, CD has both. From here on I will say "EMA copy" when I mean the internal lag and "pretrained teacher" when I mean a separately trained network.

### The discretisation curriculum

There is a subtlety in how consistency models are trained. You divide the time axis into $N$ discrete steps. Adjacent training pairs are always one step apart, so the gap $\Delta = T/N$.

If $N$ is small, the gap is large. The two adjacent points are far apart on the PF-ODE trajectory. The training signal is strong (there is a lot of distance between the two predictions to align) but the targets are noisy. Taking a large step along the PF-ODE introduces large discretisation error, so $z_{t-\Delta}$ is only approximately on the right trajectory. You are training the network to agree with a somewhat wrong target.

If $N$ is large, the gap is small. The targets are very accurate (a tiny ODE step is nearly exact) but the training signal is weak. The two adjacent points are so close that their predictions are already similar. The loss gradient is tiny and training makes almost no progress.

Neither extreme works. The fix is a curriculum: start with small $N$ (coarse, strong signal, rough targets), then progressively increase $N$ (fine, weak signal, accurate targets). The network first learns a rough consistency function, then refines it. Slide $N$ in the demo below to see the tradeoff: at $N=1$ the Euler step from $z_t$ falls far off the true curve (large red gap, strong gradient); at $N=8$ it tracks the curve closely but the gradient barely moves the network.

<figure class="fig-card">
<div class="fig-card-title">Discretisation tradeoff: signal vs target accuracy</div>
<div class="fig-card-inner">
<div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;flex-wrap:wrap;">
  <button id="playDisc" class="play-btn">&#9654; play</button>
  <label style="font-size:13px;color:var(--fig-ink-soft);min-width:80px;">steps N</label>
  <input type="range" min="1" max="8" value="1" step="1" id="nSteps" oninput="drawDisc()" style="flex:1;">
  <span id="nStepsOut" style="font-size:13px;font-weight:500;color:var(--fig-ink-soft);min-width:46px;text-align:right;">N = 1</span>
</div>
<canvas id="cvDisc" style="width:100%;display:block;border-radius:4px;"></canvas>
<p id="discCap" style="font-size:12px;color:var(--fig-ink-soft);margin:6px 0 0;text-align:center;">N=1: one step covers the whole trajectory. The tangent step at z_t lands far from the true curve. Large training signal, noisy target.</p>
</div>
<figcaption>The PF-ODE trajectory (grey) curves because the marginal velocity field curves. At each marked time step, the tangent arrow shows the local velocity. A single Euler step along that tangent departs from the true curve; the error is the red gap. More steps reduce the gap but shrink the per-step gradient.</figcaption>
</figure>

<script>
(function(){
  const cv = document.getElementById('cvDisc');
  const LW = 640, LH = 210;
  const ctx = cv.getContext('2d');
  function setupDisc(){
    const dpr = window.devicePixelRatio || 1;
    const cssW = cv.parentElement.clientWidth || LW;
    const cssH = Math.round(cssW * LH / LW);
    cv.width = cssW * dpr; cv.height = cssH * dpr;
    cv.style.width = cssW + 'px'; cv.style.height = cssH + 'px';
    ctx.setTransform(dpr * cssW / LW, 0, 0, dpr * cssH / LH, 0, 0);
  }
  requestAnimationFrame(()=>{ setupDisc(); draw(); });
  window.addEventListener('resize', ()=>{ setupDisc(); draw(); });

  // True marginal path: a quadratic bezier in canvas space
  // t=1 (noise) at left, t=0 (data) at right, curving downward in middle
  const P0={x:60,  y:80};   // t=1, noise end
  const P1={x:340, y:175};  // control point (curve bends down then up)
  const P2={x:580, y:65};   // t=0, data end

  function truePt(s){ // s in [0,1], 0=noise side (t=1), 1=data side (t=0)
    const t=s;
    return {
      x:(1-t)*(1-t)*P0.x + 2*(1-t)*t*P1.x + t*t*P2.x,
      y:(1-t)*(1-t)*P0.y + 2*(1-t)*t*P1.y + t*t*P2.y
    };
  }
  function trueTangent(s){
    const eps=0.004;
    const a=truePt(Math.min(s+eps,1)), b=truePt(Math.max(s-eps,0));
    return {x:(a.x-b.x)/(2*eps), y:(a.y-b.y)/(2*eps)};
  }

  function arrowHead(x,y,angle,size,color){
    ctx.beginPath();
    ctx.moveTo(x,y);
    ctx.lineTo(x-size*Math.cos(angle-0.3),y-size*Math.sin(angle-0.3));
    ctx.lineTo(x-size*Math.cos(angle+0.3),y-size*Math.sin(angle+0.3));
    ctx.closePath(); ctx.fillStyle=color; ctx.fill();
  }

  function draw(){
    const N=parseInt(document.getElementById('nSteps').value);
    document.getElementById('nStepsOut').textContent='N = '+N;
    const css = name => getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    const bg=css('--fig-panel');
    const fg=css('--fig-ink');
    const trueCurveColor=css('--fig-frame');
    const muted=css('--fig-ink-mute');
    const blue=css('--fig-blue'), red=css('--fig-rose'), teal=css('--fig-sage'), amber=css('--fig-amber');

    ctx.clearRect(0,0,LW,LH);
    ctx.fillStyle=bg; ctx.fillRect(0,0,LW,LH);

    // True trajectory
    const trueRes=120;
    ctx.beginPath();
    for(let i=0;i<=trueRes;i++){
      const p=truePt(i/trueRes);
      if(i===0) ctx.moveTo(p.x,p.y); else ctx.lineTo(p.x,p.y);
    }
    ctx.strokeStyle=trueCurveColor; ctx.lineWidth=2.5; ctx.setLineDash([]); ctx.stroke();

    // For each of N steps: mark the start of the interval on the true curve,
    // draw the tangent Euler step, and show where it lands vs true next point
    // Intervals in s-space (generation direction: s=0 is noise, s=1 is data)
    // Step i goes from s_i to s_{i+1}
    let totalErr=0;
    for(let i=0;i<N;i++){
      const s0=i/N, s1=(i+1)/N;
      const start=truePt(s0);
      const trueNext=truePt(s1);
      const tang=trueTangent(s0);
      const tl=Math.sqrt(tang.x**2+tang.y**2);

      // Euler step: move along tangent for the distance of s1-s0 of the arc
      // Scale so that one full step from s=0 reaches the right horizontal distance
      const ds=s1-s0;
      const eulerEnd={x:start.x+tang.x*ds, y:start.y+tang.y*ds};

      // Draw tangent velocity arrow (short, amber)
      const arrowLen=Math.min(40,30/N+15);
      const vtx=start.x+tang.x/tl*arrowLen, vty=start.y+tang.y/tl*arrowLen;
      ctx.beginPath(); ctx.moveTo(start.x,start.y); ctx.lineTo(vtx,vty);
      ctx.strokeStyle=amber; ctx.lineWidth=1.5; ctx.setLineDash([]); ctx.stroke();
      arrowHead(vtx,vty,Math.atan2(tang.y,tang.x),7,amber);

      // Euler step line (blue dashed)
      ctx.beginPath(); ctx.moveTo(start.x,start.y); ctx.lineTo(eulerEnd.x,eulerEnd.y);
      ctx.strokeStyle=blue; ctx.lineWidth=1.8; ctx.setLineDash([4,3]); ctx.stroke();
      ctx.setLineDash([]);

      // Error gap (red)
      const err=Math.sqrt((eulerEnd.x-trueNext.x)**2+(eulerEnd.y-trueNext.y)**2);
      totalErr+=err;
      if(err>3){
        ctx.beginPath(); ctx.moveTo(trueNext.x,trueNext.y); ctx.lineTo(eulerEnd.x,eulerEnd.y);
        ctx.strokeStyle=red; ctx.lineWidth=1.5; ctx.setLineDash([2,2]); ctx.stroke();
        ctx.setLineDash([]);
        // error endpoint dot
        ctx.beginPath(); ctx.arc(eulerEnd.x,eulerEnd.y,4,0,Math.PI*2);
        ctx.fillStyle=blue; ctx.fill();
      }

      // step node on true curve
      ctx.beginPath(); ctx.arc(start.x,start.y,5,0,Math.PI*2);
      ctx.fillStyle=i===0?muted:trueCurveColor;
      ctx.strokeStyle=muted; ctx.lineWidth=1; ctx.fill(); ctx.stroke();
    }

    // Endpoints
    const noiseEnd=truePt(0), dataEnd=truePt(1);
    ctx.beginPath(); ctx.arc(noiseEnd.x,noiseEnd.y,7,0,Math.PI*2);
    ctx.fillStyle=muted; ctx.fill();
    ctx.beginPath(); ctx.arc(dataEnd.x,dataEnd.y,7,0,Math.PI*2);
    ctx.fillStyle=teal; ctx.fill();

    // Labels
    ctx.font='11px "Iowan Old Style",Charter,Georgia,serif'; ctx.setLineDash([]);
    ctx.fillStyle=muted; ctx.textAlign='left';
    ctx.fillText('x₁  noise  t=1',noiseEnd.x+10,noiseEnd.y+4);
    ctx.fillStyle=teal;
    ctx.fillText('x₀  data  t=0',dataEnd.x-80,dataEnd.y-10);
    ctx.fillStyle=muted;
    ctx.fillText('true PF-ODE trajectory',truePt(0.38).x+8,truePt(0.38).y-8);
    ctx.fillStyle=amber;
    ctx.fillText('local velocity',truePt(0.15).x+8,truePt(0.15).y+14);
    if(totalErr>5){
      ctx.fillStyle=red;
      ctx.fillText('tangent error',truePt(0.5).x+8,truePt(0.5).y+16);
    }

    const avgErr=(totalErr/N).toFixed(1);
    const caps=[
      'N=1: one step, Δt=1. The tangent at z_t points straight; a single Euler step overshoots the curve significantly. Large training signal, inaccurate target.',
      'N=2: two steps, Δt=0.5. Each step is shorter; curvature error per step roughly halves. Still noisy targets but more manageable.',
      'N=3: three steps. Errors continue shrinking. The network is trained on finer pairs with smaller per-step gradients.',
      'N=4: four steps. The Euler path tracks the curve much better. Targets are getting accurate.',
      'N=5: five steps. Small steps, small errors. The training signal per pair is weaker.',
      'N=6: six steps. Targets are nearly on the true curve. Very small gradient per step.',
      'N=7: seven steps. Near-exact. Almost no visible error gap. Gradient signal is minimal.',
      'N=8: eight steps. The Euler path and true curve are nearly identical. Curriculum ends here in practice.',
    ];
    document.getElementById('discCap').textContent=caps[N-1];
  }

  window.drawDisc=draw;

  let playTimer=null;
  const playBtn=document.getElementById('playDisc');
  const slider=document.getElementById('nSteps');
  function stopPlay(){if(playTimer){clearInterval(playTimer);playTimer=null;}playBtn.textContent='▶ play';}
  playBtn.addEventListener('click',()=>{
    if(playTimer){stopPlay();return;}
    playBtn.textContent='⏹ stop';
    let dir=1;
    playTimer=setInterval(()=>{
      let v=parseInt(slider.value)+dir;
      if(v>8){v=8;dir=-1;} else if(v<1){v=1;dir=1;}
      slider.value=v; draw();
    },800);
  });

  draw();
  window.matchMedia('(prefers-color-scheme:dark)').addEventListener('change',draw);
})();
</script>

### Discrete-time vs continuous-time

What I described above is the *discrete-time* formulation: pick a grid of $N$ noise levels, define adjacent pairs on that grid, and run the curriculum on $N$. The grid is a crutch. It exists because we cannot directly enforce the consistency condition over a continuum, only at sampled pairs of points. The whole curriculum on $N$ is just managing the bias-variance tradeoff that the grid introduces.

The *continuous-time* formulation removes the grid entirely. Differentiating the consistency condition $f(z_t, t) = f(z_{t-\Delta}, t-\Delta)$ as $\Delta \to 0$ gives a PDE-style identity: $\partial_t f + v(z_t, t) \cdot \partial_z f = 0$ along the PF-ODE.[^cm-pde] The training loss enforces this identity at sampled $(z_t, t)$ pairs, no adjacent point needed, no grid to schedule. sCT and sCD (the simplified continuous-time variants of CT and CD) <sup class="cite"><a href="#ref-sct2024">[9]</a></sup> use this formulation and produce sharper results than the discrete-time version, because the bias from finite $\Delta$ is gone. The cost is a Jacobian-vector product (JVP) through the network to compute $\partial_z f \cdot v$. A JVP is the Jacobian times a vector, but you never have to materialise the full Jacobian: forward-mode autodiff computes it in a single modified forward pass at roughly the same cost as a normal one.[^jvp-primer] MeanFlow uses the same JVP machinery later for a different purpose.

[^cm-pde]: Where the PDE comes from: differentiate $f(z_t, t) = f(z_{t-\Delta}, t-\Delta)$ with respect to $\Delta$ at $\Delta = 0$. The right side gives $-\partial_t f - \partial_z f \cdot (dz/dt)$. Along the PF-ODE, $dz/dt$ is the velocity $v(z_t, t)$. Setting the derivative to zero (so consistency holds for *every* small $\Delta$, not just at sampled pairs) gives $\partial_t f + v(z_t, t) \cdot \partial_z f = 0$. This is the transport equation for $f$ along the flow, the method-of-characteristics statement that $f$ is constant along PF-ODE trajectories.

[^jvp-primer]: The <a href="https://docs.jax.dev/en/latest/notebooks/autodiff_cookbook.html" target="_blank" rel="noopener noreferrer">JAX autodiff cookbook</a> is a good primer on JVPs and forward-mode autodiff if you want a refresher.

Consistency models were the first method to show you can generate decent images in one step, which was not obvious before 2023. iCT (improved consistency training) <sup class="cite"><a href="#ref-ict2023">[3]</a></sup> improved substantially over the original with a bundle of training stability tricks: pseudo-Huber losses, a lognormal noise schedule, and progressive discretisation step doubling.[^ict-tricks] Even those required considerable engineering effort just to be reliable.

[^ict-tricks]: Briefly: *pseudo-Huber* is a smooth approximation to the Huber loss, which behaves like $L_2$ near zero and like $L_1$ further out; it cuts the variance contribution from a few large errors that otherwise destabilise consistency training. The *lognormal noise schedule* concentrates training samples around noise levels where the loss is most informative, instead of uniformly over $t$. *Progressive step doubling* runs the discretisation curriculum on a $\log_2 N$ schedule, doubling $N$ at preset training milestones rather than tuning a continuous ramp. iCT also drops the EMA target network used in the original CT, which is a more important change than its placement in the trick list suggests.

The training target is always *behavioural*: it constrains what the network outputs at adjacent pairs of points, not what the underlying field should be. There is no ground truth for $f(z_t, t)$ that exists independently of the network. The optimal function is defined only implicitly, via the consistency condition and boundary condition, and can only be learned by having the network agree with itself across adjacent pairs. This is inherently noisy and sensitive to hyperparameters.

The deeper limitation: consistency models are stuck. They can only jump to one destination, the endpoint $x_0$. The function signature is $f(z_t, t) = x_0$; you tell it where you are and what time it is, and it predicts the endpoint. You cannot ask it to jump to an intermediate point. Multi-step generation therefore requires running the network multiple times and renoising between each evaluation, a clunky workaround that does not actually use the trajectory structure. But what if the jump function could land anywhere, not just $x_0$?

---

## Consistency trajectory models: any-to-any jumps

CTM <sup class="cite"><a href="#ref-ctm2024">[4]</a></sup> generalises consistency models. Where consistency models always jump to the end of the PF-ODE trajectory, CTM lets the function jump to *any* point along it. This is the **two-time function**:

<div class="eq">$$G_\theta(x_t,\, t,\, s) \;=\; x_s$$</div>
<div class="eq-label">From any point $x_t$ at time $t$, jump to the state $x_s$ at time $s$. Consistency models are the special case $s=0$.</div>

With this object you can take large or small steps, land at any intermediate point on the trajectory, and compose multiple jumps to refine a generation. For this to be well-posed, the function has to satisfy the **semigroup property**. If you jump from $t$ to some intermediate $u$, and then jump from $u$ to $s$, you should get the same result as jumping directly from $t$ to $s$:

<div class="eq">$$G_\theta(x_t,\, t,\, s) \;=\; G_\theta\!\bigl(G_\theta(x_t,\, t,\, u),\; u,\; s\bigr) \qquad \text{for any } u \in (s,t)$$</div>
<div class="eq-label">One large jump = two composed smaller jumps. This is the composition rule at the heart of every flow-map method.</div>

Drag the split point $u$ in the figure below to see this in action: the direct jump (top arc) and the two-leg composition (bottom arcs) always end at the same destination, no matter where you split.

<figure class="fig-card">
<div class="fig-card-title">Semigroup property: one jump = two composed jumps</div>
<div class="fig-card-inner">
<div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;flex-wrap:wrap;">
  <button id="playSemi" class="play-btn">&#9654; play</button>
  <label style="font-size:13px;color:var(--fig-ink-soft);min-width:80px;">split point</label>
  <input type="range" min="15" max="85" value="40" step="1" id="splitPct" oninput="drawSemi()" style="flex:1;">
  <span id="splitOut" style="font-size:13px;font-weight:500;color:var(--fig-ink-soft);min-width:46px;text-align:right;">u = 0.40</span>
</div>
<canvas id="cvSemi" style="width:100%;display:block;border-radius:4px;"></canvas>
<p style="font-size:12px;color:var(--fig-ink-soft);margin:6px 0 0;text-align:center;">Drag the split point. Both routes (direct and two-step) always land at the same destination.</p>
</div>
<figcaption>The semigroup property: one jump from $t$ to $r$ equals two composed jumps through any intermediate $u$. Every flow-map method enforces exactly this constraint.</figcaption>
</figure>

<script>
(function(){
  const cv = document.getElementById('cvSemi');
  const LW = 640, LH = 210;
  const ctx = cv.getContext('2d');
  function setupSemi(){
    const dpr = window.devicePixelRatio || 1;
    const cssW = cv.parentElement.clientWidth || LW;
    const cssH = Math.round(cssW * LH / LW);
    cv.width = cssW * dpr; cv.height = cssH * dpr;
    cv.style.width = cssW + 'px'; cv.style.height = cssH + 'px';
    ctx.setTransform(dpr * cssW / LW, 0, 0, dpr * cssH / LH, 0, 0);
  }
  requestAnimationFrame(()=>{ setupSemi(); draw(); });
  window.addEventListener('resize', ()=>{ setupSemi(); draw(); });
  const rX = 60, tX = 580, baseY = 115;

  function curvedArrow(x1,y1,x2,y2,bulge,color,lw){
    const mx=(x1+x2)/2, my=(y1+y2)/2;
    const dx=x2-x1, dy=y2-y1, len=Math.sqrt(dx*dx+dy*dy);
    const nx=-dy/len, ny=dx/len;
    const cpx=mx+nx*bulge, cpy=my+ny*bulge;
    ctx.beginPath(); ctx.moveTo(x1,y1); ctx.quadraticCurveTo(cpx,cpy,x2,y2);
    ctx.strokeStyle=color; ctx.lineWidth=lw; ctx.setLineDash([]); ctx.stroke();
    const ex=x2-cpx, ey=y2-cpy, el=Math.sqrt(ex*ex+ey*ey);
    const a=Math.atan2(ey/el,ex/el);
    ctx.beginPath(); ctx.moveTo(x2,y2);
    ctx.lineTo(x2-9*Math.cos(a-0.3),y2-9*Math.sin(a-0.3));
    ctx.lineTo(x2-9*Math.cos(a+0.3),y2-9*Math.sin(a+0.3));
    ctx.closePath(); ctx.fillStyle=color; ctx.fill();
  }

  function draw(){
    const pct = parseInt(document.getElementById('splitPct').value)/100;
    document.getElementById('splitOut').textContent = 'u = '+pct.toFixed(2);
    const css = name => getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    const bg=css('--fig-panel');
    const fg=css('--fig-ink');
    const faint=css('--fig-frame');
    const muted=css('--fig-ink-mute');
    const inkSoft=css('--fig-ink-soft');
    const nodeFill=css('--fig-paper');
    const blue=css('--fig-blue'), teal=css('--fig-sage'), amber=css('--fig-amber');
    ctx.clearRect(0,0,LW,LH);
    ctx.fillStyle=bg; ctx.fillRect(0,0,LW,LH);

    // axis line
    ctx.strokeStyle=faint; ctx.lineWidth=1; ctx.setLineDash([]);
    ctx.beginPath(); ctx.moveTo(rX-18,baseY); ctx.lineTo(tX+18,baseY); ctx.stroke();

    // time axis label
    ctx.font='11px "Iowan Old Style",Charter,Georgia,serif'; ctx.textAlign='center';
    ctx.fillStyle=muted; ctx.fontStyle='italic';
    ctx.fillText('← time flows this way (t=1 is noise, t=0 is data)',LW/2,baseY+44);

    const sX = rX + pct*(tX-rX);

    // direct jump arc (grey, above)
    curvedArrow(tX,baseY,rX,baseY,-58,muted,1.8);

    // two-leg arcs (below axis)
    curvedArrow(tX,baseY,sX,baseY,46,amber,2);
    curvedArrow(sX,baseY,rX,baseY,46,teal,2);

    // nodes: r, u, t
    [{x:rX,label:'r = 0',primary:true,color:blue},{x:sX,label:'u = '+pct.toFixed(2),primary:false,color:muted},{x:tX,label:'t = 1',primary:true,color:blue}].forEach(n=>{
      ctx.beginPath(); ctx.arc(n.x,baseY,n.primary?9:7,0,Math.PI*2);
      ctx.fillStyle=n.primary?blue:nodeFill;
      ctx.strokeStyle=n.primary?blue:muted; ctx.lineWidth=1.5; ctx.fill(); ctx.stroke();
    });

    ctx.font='12px "Iowan Old Style",Charter,Georgia,serif'; ctx.textAlign='center';
    ctx.fillStyle=blue;
    ctx.fillText('r = 0',rX,baseY+24);
    ctx.fillText('t = 1',tX,baseY+24);
    ctx.fillStyle=muted;
    ctx.fillText('u = '+pct.toFixed(2),sX,baseY+24);

    ctx.fillStyle=muted;
    ctx.fillText('direct: G(zₜ, t→r)',(rX+tX)/2,baseY-63);
    ctx.fillStyle=amber;
    ctx.fillText('1st leg: t→u',(sX+tX)/2,baseY+76);
    ctx.fillStyle=teal;
    ctx.fillText('2nd leg: u→r',(rX+sX)/2,baseY+76);

    ctx.fillStyle=inkSoft; ctx.font='11px "Iowan Old Style",Charter,Georgia,serif';
    ctx.fillText('semigroup:  G(zₜ, t, r) = G( G(zₜ, t, u), u, r )  for any u',LW/2,LH-8);
  }

  window.drawSemi = draw;

  // play button: sweep split point back and forth
  let playTimer=null;
  const playBtn=document.getElementById('playSemi');
  const slider=document.getElementById('splitPct');
  function stopPlay(){
    if(playTimer){clearInterval(playTimer);playTimer=null;}
    playBtn.textContent='▶ play';
  }
  playBtn.addEventListener('click',()=>{
    if(playTimer){ stopPlay(); return; }
    playBtn.textContent='⏹ stop';
    let dir=1;
    playTimer=setInterval(()=>{
      let v=parseInt(slider.value)+dir*2;
      if(v>=85){v=85;dir=-1;} else if(v<=15){v=15;dir=1;}
      slider.value=v;
      draw();
    },40);
  });

  draw();
  window.matchMedia('(prefers-color-scheme:dark)').addEventListener('change', draw);
})();
</script>

Training enforces this by sampling triples $(r, s, t)$ with $r < s < t$ and comparing the direct jump $G(x_t, t, r)$ against the composed two-step jump:

<div class="eq">$$\mathcal{L}_\text{CTM} \;=\; \mathbb{E}\,\bigl\lVert G_\theta(x_t,\,t,\,r) \;-\; \operatorname{sg}\!\bigl(G_{\theta^-}\!\bigl(G_{\theta^-}(x_t,\,t,\,s),\;s,\;r\bigr)\bigr) \bigr\rVert^2$$</div>
<div class="eq-label">Same stop-gradient and EMA trick as consistency models. $\theta^-$ is used for both the inner and outer jump on the target side.</div>

The flexible jump function makes multi-step generation more natural than consistency models: you chain calls with progressively smaller target times, no renoising needed. At the time of publication, CTM held the best single-step FID numbers (see the results table below).

The limitation it inherits from consistency models: the training target is still self-referential. $G_{\theta^-}$ is the network evaluated at a slightly lagged version of itself. There is no ground-truth two-time map that exists independently of the network; the only supervision comes from the model agreeing with itself across different decompositions. This makes training more stable than consistency models (because the semigroup structure is richer), but the fundamental self-referential nature remains. CTM is also fiddly in practice: random triples $(r, s, t)$, two separate network evaluations on the target side, careful coordination of all the moving parts.

---

## Shortcut models

Where CTM works with continuous-time triples $(r, s, t)$ and pays for that flexibility with fiddly training, shortcut models <sup class="cite"><a href="#ref-shortcut2024">[5]</a></sup> commit to a discrete set of jump sizes and make the training procedure radically simpler. The idea: condition the network on both the current noise level $t$ and the *desired step size* $d$. The network learns to predict where you will end up after a jump of size $d$ from $z_t$. The step size is an input, not a fixed constant.

<div class="eq">$$v_\theta(z_t,\, t,\, d) \;\approx\; \frac{z_t - z_{t-d}}{d}$$</div>
<div class="eq-label">Predict the average displacement per unit time over a step of size $d$. At $d \to 0$ this recovers instantaneous velocity.</div>

A regular flow matching network only knows "I am at noise level $t$." A shortcut model knows "I am at noise level $t$, and I want to travel a distance of $d$ in one step." With that extra information, it can calibrate its prediction to the correct jump size, and you can ask it to take different step sizes at different points during generation.

### The bootstrapping training procedure

How do you train this? You cannot compute the ground-truth $z_{t-d}$ directly, because it would require running the full ODE. The trick is to build the target out of *smaller* steps the network can already make. Two half-steps from the (stop-gradient) EMA copy of the network are composed into a single full-step target the student is trained to match. The picture first, equations after.

<figure class="fig-card">
<div class="fig-card-title">Shortcut bootstrapping: half-steps teach full steps</div>
<div class="fig-card-inner">
<svg viewBox="0 0 680 230" role="img" style="font-family:'Iowan Old Style',Charter,Georgia,serif;">
<title>Shortcut model bootstrapping: one large step = two composed half-steps</title>
<desc>Three points on a noise timeline. The EMA network takes two half-steps to produce a composed target. The student takes the full step in one shot.</desc>
<defs>
  <marker id="ar4b" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse">
    <path d="M1 1L9 5L1 9" fill="none" stroke="context-stroke" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/>
  </marker>
</defs>
<!-- baseline -->
<line x1="60" y1="110" x2="630" y2="110" stroke="var(--fig-frame)" stroke-width="0.5"/>
<!-- nodes -->
<circle cx="100" cy="110" r="7.5" fill="var(--fig-sage)" opacity="0.9"/>
<circle cx="356" cy="110" r="6"   fill="var(--fig-paper)" stroke="var(--fig-ink-soft)" stroke-width="1.3"/>
<circle cx="600" cy="110" r="7.5" fill="var(--fig-paper)" stroke="var(--fig-ink-soft)" stroke-width="1.5"/>
<!-- labels under nodes -->
<text font-size="12" fill="var(--fig-sage)"     x="100" y="134" text-anchor="middle">z<tspan font-size="9" baseline-shift="sub">t−d</tspan></text>
<text font-size="10.5" font-style="italic" fill="var(--fig-ink-mute)" x="100" y="150" text-anchor="middle">landing point</text>
<text font-size="12" fill="var(--fig-ink-soft)" x="356" y="134" text-anchor="middle">z<tspan font-size="9" baseline-shift="sub">t−d/2</tspan></text>
<text font-size="10.5" font-style="italic" fill="var(--fig-ink-mute)" x="356" y="150" text-anchor="middle">halfway</text>
<text font-size="12" fill="var(--fig-ink-soft)" x="600" y="134" text-anchor="middle">z<tspan font-size="9" baseline-shift="sub">t</tspan></text>
<text font-size="10.5" font-style="italic" fill="var(--fig-ink-mute)" x="600" y="150" text-anchor="middle">start</text>
<!-- EMA half-step arcs (drawn left-to-right but read right-to-left in time) -->
<path d="M 108 110 Q 228 58 348 110" fill="none" stroke="var(--fig-sage)"  stroke-width="1.6" stroke-linecap="round" marker-end="url(#ar4b)"/>
<path d="M 364 110 Q 480 58 592 110" fill="none" stroke="var(--fig-amber)" stroke-width="1.6" stroke-linecap="round" marker-end="url(#ar4b)"/>
<text font-size="11" fill="var(--fig-sage)"  x="228" y="54" text-anchor="middle">EMA: 2nd half-step</text>
<text font-size="11" fill="var(--fig-amber)" x="480" y="54" text-anchor="middle">EMA: 1st half-step</text>
<!-- student full step (dashed) -->
<path d="M 108 110 Q 354 180 592 110" fill="none" stroke="var(--fig-blue)" stroke-width="1.8" stroke-dasharray="6 3" stroke-linecap="round" marker-end="url(#ar4b)"/>
<text font-size="12" font-style="italic" fill="var(--fig-blue)" x="352" y="196" text-anchor="middle">student: one full step (what we are training)</text>
</svg>
</div>
<figcaption>Read right-to-left in time. Starting from $z_t$, the EMA network takes one half-step (amber) to reach $z_{t-d/2}$, then another half-step (sage) to land at $z_{t-d}$. The composition of those two half-steps becomes the target. The student (dashed blue) is trained to match it in a single jump of size $d$.</figcaption>
</figure>

In equations, this is the semigroup property enforced discretely:

<div class="eq">$$v_\theta(z_t,\,t,\,d) \;=\; \operatorname{compose}\!\bigl(v_{\theta^-}(z_t,\,t,\,d/2),\;\; v_{\theta^-}(z_{t-d/2},\,t-d/2,\,d/2)\bigr)$$</div>
<div class="eq-label">One large step of size $d$ = two composed half-steps of size $d/2$. $\theta^-$ is the EMA copy, the same stop-gradient stabiliser that appears in consistency models.</div>

In practice, training starts with the smallest steps (where the half-step approximation is most accurate) and progressively learns larger steps using the smaller ones as building blocks. The network learns one-step jumps first, then two-step, then four-step, bootstrapping upward. At inference, you choose any step count: one for speed, many for quality.

Frans et al. draw the same construction with the actual loss notation overlaid; reproduced for cross-reference.

<figure class="fig-paper">
<div class="fig-paper-title">From the paper · Frans et al. 2024, Fig. 3</div>
<img src="https://arxiv.org/html/2410.12557/x3.png"
     alt="Figure 3 from Frans et al. (2024). Overview of shortcut model training: at d≈0 the objective reduces to flow matching against the empirical velocity x1−x0; targets for larger d are constructed by concatenating two d/2 shortcuts, with the network conditioning on the step size d.">
<figcaption>Same idea as the schematic above, drawn the way the paper presents it: at $d \to 0$ the loss matches the empirical flow-matching velocity $x_1 - x_0$; for larger $d$, the target is built by composing two half-step predictions from the EMA model.</figcaption>
</figure>

Shortcut models enforce composition by directly comparing network outputs: no differentiation through the network, no JVP, just a fast per-step training update. The tradeoff is that the discrete half-step approximation introduces small errors that compound when you compose many steps; and like CTM and consistency models before it, the training target is still self-referential. The next two methods both push against that self-reference. Align Your Flow does it by importing ground truth from outside (a pretrained teacher); MeanFlow does it from the inside (an exact identity that lets the network supervise itself against quantities readable directly from data).

---

## Align Your Flow: distilling the jump function

Both CTM and Shortcut models work with the same flow-map object: a two-time network $f_\theta(x_t, t, s) = x_s$ that jumps from any noise level to any cleaner level in one forward pass. They train it from scratch with self-referential targets and pay for that with curriculum schedules and EMA copies of themselves on the target side. Align Your Flow <sup class="cite"><a href="#ref-ayf2025">[8]</a></sup> takes a different bet: instead of training the flow map from scratch, distill it from a pretrained diffusion teacher whose ODE trajectories *are* the ground truth.

<figure class="fig-paper">
<div class="fig-paper-title">From the paper · Sabour et al. 2025, Fig. 2</div>
<img src="https://arxiv.org/html/2506.14603/extracted/6549324/figures/overview_fig_4.jpg"
     alt="Overview of Flow Maps from Align Your Flow (Sabour et al., 2025). Three panels show Consistency Model (s=0), Flow Map (any s,t), and Flow Matching (s→t), with their respective training objectives below.">
<figcaption>Flow maps generalise both consistency models and flow matching by connecting any two noise levels $(s, t)$ in a single step. Setting $s=0$ recovers a consistency model; letting $s \to t$ recovers standard flow matching.</figcaption>
</figure>

This framing resolves something that was implicit in CTM but never fully confronted. CTM trains the jump function so that composed shorter jumps reproduce longer ones, but it never asks whether the jump function is actually correct, only whether it is internally consistent. A pretrained teacher changes that: the teacher's ODE trajectories are ground truth, and the student's jumps are trained to trace them. Internal consistency is still enforced, but now there is an external anchor.

The paper also proves that consistency models eventually get worse with more steps. **Theorem 3.1**: for an isotropic Gaussian data distribution, there exist consistency models arbitrarily close to optimal in $L_2$ such that increasing the sampling step count beyond some $N$ *increases* the Wasserstein-2 distance to the true distribution.[^ayf-thm] The empirical version (Fig. 5 of the paper, on isotropic Gaussian data with standard deviation $c = 0.5$) is just as stark: multi-step CM sampling peaks around 2 steps and then degrades. The mechanism is renoising. CMs jump to clean between steps and reinject Gaussian noise to get back onto the trajectory; over many steps that injected noise does not align with the teacher's PF-ODE trajectory and errors compound.

[^ayf-thm]: To be precise about the existential form: the theorem says that for any $\delta > 0$, there exists a consistency model $f$ with $\mathbb{E}\lVert f(x_t,t) - f^{\ast}(x_t,t)\rVert_2^2 < \delta$ uniformly in $t$, *and* some $N$ beyond which extra sampling steps make the generated distribution worse in $W_2$. It is therefore not a statement about every imperfect CM, but about the existence of arbitrarily-close-to-optimal ones with this pathology, which is enough to make the theorem load-bearing in the post's argument: the failure mode is not a "you trained badly" artifact. The proof and the Gaussian assumption together yield a closed-form analysis; whether the result extends to non-Gaussian data is not formally settled.

Flow maps avoid this by construction: they map directly between any two noise levels in one step, never leaving the trajectory. The paper does not formally prove they monotonically improve, but empirically they keep getting better with more steps, exactly where CMs fall apart.

Distilling the jump function from a teacher raises a practical question: how do you enforce the consistency constraint? AYF gives two answers, borrowing the fluid-dynamics distinction between Eulerian (fixed observer, watch the field) and Lagrangian (move with the particle) frames. The two losses differ in *which time variable they perturb*.

<div class="post-table-wrap">
<table class="post-table">
<thead>
<tr>
  <th>Objective</th>
  <th>What's varied</th>
  <th>Why it works</th>
  <th>Empirical role</th>
</tr>
</thead>
<tbody>
<tr>
  <td class="label">EMD <span class="muted" style="font-weight:400;">(Eulerian Map Distillation)</span></td>
  <td>Endpoint $s$ fixed, perturb starting time $t$; check that $f_\theta(x_t, t, s)$ is invariant as $t$ moves along the teacher trajectory.</td>
  <td>This loss generalises both the continuous-time consistency loss (when $s = 0$) and the flow matching loss (as $s \to t$); structurally the right object to optimise.</td>
  <td class="label">Primary loss in all main results.</td>
</tr>
<tr>
  <td class="label">LMD <span class="muted" style="font-weight:400;">(Lagrangian Map Distillation)</span></td>
  <td>Starting point $t$ fixed, perturb endpoint $s$; check that $f_\theta(x_t, t, s)$ moves correctly as $s$ slides along the trajectory it predicts.</td>
  <td>Uses the teacher's instantaneous velocity at the predicted point, so it stays faithful to the flow geometry the teacher defines.</td>
  <td class="label">Used as a stabiliser; on its own produces over-smoothed samples on real images, per the paper's ablations.</td>
</tr>
</tbody>
</table>
</div>

To replace classifier-free guidance during distillation, AYF uses **autoguidance**: the teacher is mixed with a weaker checkpoint of itself, $v_\phi^{\text{guided}} = \lambda v_\phi + (1 - \lambda) v_\phi^{\text{weak}}$ with $\lambda$ sampled uniformly from $[1, 3]$. This steers samples away from low-quality regions without the overshooting failure mode CFG can have.

Empirically, a small AYF student beats much larger distillation baselines at fewer network function evaluations (NFEs). The efficiency comes from the teacher anchor: unlike CTM, the student does not waste capacity reconciling self-generated targets at high noise levels where those targets are most unreliable. Full numbers are in the table at the end of the post.

---

## MeanFlow: ground truth for the jump function

MeanFlow <sup class="cite"><a href="#ref-meanflow2025">[6]</a></sup> finds a quantity the network can predict whose true value is computable directly from data, no teacher required. That quantity is the **average velocity**.

### Average velocity: a ground-truth two-time quantity

So what does "average velocity" actually mean here? It is the same thing it meant in physics class: total displacement divided by elapsed time. If you go from $z_t$ to $z_r$ over an interval of length $t - r$, the average velocity is just one divided by the other:

<div class="eq">$$\bar{u}(z_t,\, r,\, t) \;=\; \frac{z_t - z_r}{t - r}$$</div>
<div class="eq-label">$z_r$ is where you would land if you followed the PF-ODE from $z_t$ back to time $r$.</div>

This looks unhelpful at first because $z_r$ is exactly the thing we cannot compute without integrating the ODE. But here is where flow matching does us a favor. Because the conditional paths are linear interpolations $z_t = (1-t)x_0 + tx_1$, the numerator $z_t - z_r$ collapses cleanly. Subtract the two interpolations:

<div class="eq">$$z_t - z_r \;=\; \bigl[(1-t)x_0 + t\,x_1\bigr] - \bigl[(1-r)x_0 + r\,x_1\bigr] \;=\; (t - r)(x_1 - x_0)$$</div>
<div class="eq-label">The $x_0$ and $x_1$ coefficients combine into a single $(t-r)$ factor.</div>

Divide by $(t-r)$ and the time variables cancel completely:

<div class="eq">$$\bar{u}(z_t,\, r,\, t) \;=\; \frac{z_t - z_r}{t - r} \;=\; x_1 - x_0$$</div>
<div class="eq-label">The average velocity over any interval is just $x_1 - x_0$. No $r$, no $t$, no integration.</div>

That is the punchline. The average velocity is a fixed quantity for each training pair $(x_0, x_1)$, readable directly from data; no network evaluation, no self-reference, no approximation. And one-step generation falls out for free: start at pure noise, subtract the average velocity over the full interval, you have $x_0$.

<div class="eq">$$x_0 \;=\; z_1 \;-\; \bar{u}_\theta(z_1,\, 0,\, 1)$$</div>
<div class="eq-label">One network call. The whole reason MeanFlow exists.</div>

The catch: this beautiful identity is only directly usable for the full interval $[0, 1]$ where you have ground-truth $(x_0, x_1)$ pairs. For arbitrary intermediate triples $(z_t, r, t)$ during training, computing $\bar{u}$ directly would require running the ODE, which is the slow integration we are trying to avoid. The next subsection is how MeanFlow gets around that.

### The MeanFlow identity

Computing $z_r$ requires running the PF-ODE from $z_t$ to time $r$, the slow integration we just said we want to avoid. MeanFlow derives an equivalent form that does not require $z_r$ at all. Start from the definition rewritten as an integral:

<div class="eq">$$(t - r)\cdot \bar{u}(z_t,\, r,\, t) \;=\; \int_r^t v(z_\tau,\, \tau)\, d\tau$$</div>
<div class="eq-label">Average velocity × time = total displacement = integral of instantaneous velocity. Think: distance = speed × time.</div>

Now differentiate both sides with respect to $t$. The right side uses the fundamental theorem of calculus; the left side uses the product rule:

<div class="eq">$$\bar{u}(z_t,\, r,\, t) \;=\; v(z_t,\, t) \;-\; (t - r)\cdot \frac{d\bar{u}}{dt}$$</div>
<div class="eq-label">The MeanFlow identity. $v(z_t, t)$ is the instantaneous flow matching velocity (ground truth from data). $d\bar{u}/dt$ is the total time derivative of the network output.</div>

This identity gives a target for $\bar{u}$ with no integrals and no ODE simulation. The two pieces on the right side play very different roles. The first, $v(z_t, t)$, is data-supervised, the same target flow matching uses. The second, $d\bar{u}/dt$, is the network differentiating its own output. So the MeanFlow target is a *mix* of data supervision and self-reference; the identity is exact, but the self-referential half still has to be stabilised. That tension is what the next subsection is about.

### Computing $d\bar{u}/dt$: the Jacobian-vector product

The term $d\bar{u}/dt$ is a total derivative: it measures how the network output changes as $t$ increases, accounting for two effects simultaneously, the explicit dependence on $t$ as a conditioning input and the implicit dependence through $z_t$ (which moves along the flow as $t$ changes). Expanding via the chain rule:

<div class="eq">$$\frac{d\bar{u}}{dt} \;=\; \frac{\partial \bar{u}}{\partial z}\cdot v(z_t,\,t) \;+\; \frac{\partial \bar{u}}{\partial t}$$</div>
<div class="eq-label">First term: Jacobian of $\bar{u}$ w.r.t. its input $z$, multiplied by the velocity vector (a JVP). Second term: explicit partial derivative w.r.t. $t$.</div>

The first term is a Jacobian-vector product (JVP): the Jacobian of the network output with respect to its input $z$, dotted with the velocity vector $v(z_t, t)$. This is computed via forward-mode automatic differentiation, a single modified forward pass through the network. In PyTorch: `torch.func.jvp`. In JAX: `jax.jvp`. The overhead in practice is a fraction of an extra forward pass, much less than running a full second forward pass for a teacher network.

The full training loss applies stop-gradient to the entire target to avoid second-order gradients:

<div class="eq">$$\mathcal{L}_\text{MF} \;=\; \mathbb{E}\,\bigl\lVert \bar{u}_\theta(z_t,\,r,\,t) \;-\; \operatorname{sg}\!\Bigl[v_\text{FM}(z_t,t) \;-\; (t-r)\cdot\tfrac{d\bar{u}_\theta}{dt}\Bigr] \bigr\rVert^2$$</div>
<div class="eq-label">Stop-gradient prevents gradients from flowing through the target. $v_\text{FM}$ is the flow matching ground-truth velocity.</div>

### Why this loss is hard to train

α-Flow <sup class="cite"><a href="#ref-alphaflow2025">[7]</a></sup> takes apart the MeanFlow loss and shows that an exact identity does not guarantee a stable optimisation: the loss splits into two components that fight each other in the early stages of training. The decomposition is called TFM/TC.

When $r = t$, the interval collapses to a single point and the average velocity over a zero-length interval equals the instantaneous velocity. The MeanFlow identity reduces to $\bar{u} = v$, and the loss becomes exactly the standard flow matching loss. MeanFlow uses $r = t$ for a large fraction of training samples (around three-quarters in the paper's main configuration). Why spend that much of training on the degenerate case that ignores the average velocity entirely?

The decomposition is:

<div class="eq">$$\mathcal{L}_\text{MF} \;=\; \mathcal{L}_\text{TFM} \;+\; \mathcal{L}_\text{TC}$$</div>
<div class="eq-label">TFM = trajectory flow matching (data-supervised). TC = trajectory consistency (JVP-based).</div>

**TFM** is the flow matching component. It pushes the network to predict the instantaneous velocity field, supervised directly by data and stable to optimise. The large-fraction $r=t$ sampling ensures TFM dominates early training.

**TC** is the consistency enforcement component. It uses the JVP to ensure predictions compose correctly across intervals. This is the part that gives MeanFlow its structure beyond plain flow matching, but it depends on a JVP through the network, which is noisy at high noise levels.

Why? The TC gradient uses the JVP: the Jacobian of the network output with respect to input $z_t$, multiplied by the velocity vector. At high $t$ (near pure noise) the input carries almost no semantic signal. The network's weights at this early stage are unstructured, so the Jacobian of an unstructured network with respect to its input is essentially random: large in magnitude, arbitrary in direction. Multiplying this random matrix by the velocity vector produces a JVP that points nowhere useful.

The consequence is that TC gradients at high $t$ are large, random vectors that actively conflict with TFM gradients. α-Flow documents this conflict and uses it to motivate a curriculum: a parameter $\lambda \in [0,1]$ interpolates between pure TFM ($\lambda=0$, just flow matching, completely stable) and full MeanFlow ($\lambda=1$). Training starts at $\lambda=0$ and increases as the velocity field converges, by which time the Jacobian at high $t$ begins to encode which direction the trajectory is heading, and the JVP carries real signal.

Same coarse-to-fine principle as the discretisation curriculum in consistency models, applied to a continuous parameter: stabilise the data-supervised component first; turn on the self-referential one only after the velocity field has converged enough for the JVP to mean something.

---

## Where this leaves us

Stepping back, the methods sit on a continuum from cheap-and-local training signals to expensive-and-global ones.

<div class="post-table-wrap">
<table class="post-table">
<thead>
<tr>
  <th>Method</th>
  <th>Composition</th>
  <th>Training target</th>
  <th>Inference</th>
  <th>Per-step cost</th>
</tr>
</thead>
<tbody>
<tr>
  <td class="label">Flow matching</td>
  <td class="muted">none</td>
  <td>data-supervised</td>
  <td class="muted">multi-step</td>
  <td>regression</td>
</tr>
<tr>
  <td class="label">Consistency models</td>
  <td>jump to $x_0$</td>
  <td class="muted">self-referential (EMA)</td>
  <td>one-step</td>
  <td>regression</td>
</tr>
<tr>
  <td class="label">CTM / Shortcut</td>
  <td>any-pair / discrete sizes</td>
  <td class="muted">self-referential (EMA)</td>
  <td>one- or few-step</td>
  <td>regression</td>
</tr>
<tr>
  <td class="label">Align Your Flow</td>
  <td>any-pair via distillation</td>
  <td>teacher-anchored</td>
  <td>one- or few-step</td>
  <td>regression + teacher</td>
</tr>
<tr>
  <td class="label">MeanFlow</td>
  <td>continuous (semigroup identity)</td>
  <td>data-supervised + self-referential mix</td>
  <td>one-step</td>
  <td>regression + JVP</td>
</tr>
</tbody>
</table>
<p class="post-table-note">MeanFlow is the only row with a ground-truth target and one-step inference; it pays with the JVP and the training conflict from the previous section.</p>
</div>

There is a structural reason this looks like a continuum and not a set of unrelated tricks. The signal you can compute cheaply is local: an instantaneous velocity from data, or a short ODE step from a teacher. The thing you want is global: a one-step jump that has to be correct over a long interval. Self-reference, the network agreeing with a lagged copy of itself, is the only way to bridge the two. It is also unstable until the data-supervised part is solid, which is why every method here needs a curriculum that turns the self-referential part on gradually. A pretrained teacher is an external oracle for the long-jump answer; an EMA copy is an internal one. Which you pick mostly depends on whether you have a good teacher available.

Two years ago none of these numbers existed. The gap with multi-step diffusion is closing faster than most expected. CIFAR-10 at 1 NFE looks effectively saturated in this family; the ImageNet rows show what teacher anchoring buys you, with AYF at 2 NFE reaching the best FID in the table and a 280M-parameter AYF-S beating the 1.5B-parameter sCD-XXL on IN-512.

<div class="post-table-wrap">
<table class="post-table">
<thead>
<tr>
  <th>Method</th>
  <th class="center">NFE</th>
  <th class="center">Benchmark</th>
  <th class="center">FID ↓</th>
  <th>Training</th>
</tr>
</thead>
<tbody>
<tr>
  <td>Consistency Models <sup class="cite"><a href="#ref-song2023">[2]</a></sup></td>
  <td class="center">1</td>
  <td class="center">CIFAR-10</td>
  <td class="center">3.55</td>
  <td class="muted">distillation / from scratch</td>
</tr>
<tr>
  <td>iCT <sup class="cite"><a href="#ref-ict2023">[3]</a></sup></td>
  <td class="center">1</td>
  <td class="center">CIFAR-10 / IN-64</td>
  <td class="center">2.51 / 3.25</td>
  <td class="muted">from scratch</td>
</tr>
<tr>
  <td>CTM <sup class="cite"><a href="#ref-ctm2024">[4]</a></sup></td>
  <td class="center">1</td>
  <td class="center">CIFAR-10 / IN-64</td>
  <td class="center">1.73 / 1.92</td>
  <td class="muted">distillation + adversarial</td>
</tr>
<tr>
  <td>MeanFlow <sup class="cite"><a href="#ref-meanflow2025">[6]</a></sup></td>
  <td class="center">1</td>
  <td class="center">IN-256</td>
  <td class="center">3.43</td>
  <td class="muted">from scratch</td>
</tr>
<tr>
  <td>α-Flow <sup class="cite"><a href="#ref-alphaflow2025">[7]</a></sup></td>
  <td class="center">1 / 2</td>
  <td class="center">IN-256</td>
  <td class="center">2.58 / 2.15</td>
  <td class="muted">from scratch (DiT)</td>
</tr>
<tr>
  <td rowspan="3">Align Your Flow <sup class="cite"><a href="#ref-ayf2025">[8]</a></sup></td>
  <td class="center">1</td>
  <td class="center">IN-64</td>
  <td class="center">2.98</td>
  <td class="muted" rowspan="3">distillation (EMD) + optional adversarial</td>
</tr>
<tr>
  <td class="center">2</td>
  <td class="center">IN-64</td>
  <td class="center good">1.25</td>
</tr>
<tr>
  <td class="center">4</td>
  <td class="center">IN-512 (280M)</td>
  <td class="center good">1.70 <span style="font-weight:400;color:#aaa;font-size:11px;">0.24s</span></td>
</tr>
</tbody>
</table>
<p class="post-table-note">IN = ImageNet. NFE = network function evaluations. AYF-S at 4 NFE (FID 1.70, 0.24s) outperforms sCD-XXL at 2 NFE (FID 1.88, 0.50s) using 5× fewer parameters.</p>
</div>

This is one branch of the one-step literature. Parallel lines, flow rectification, distribution matching distillation, and adversarial distillation, currently dominate at SDXL-scale text-to-image and are not covered here.

A few things still feel unresolved to me. Guidance is the obvious one. CFG is what makes large-scale conditional diffusion deployable, and none of the one-step methods have a clean equivalent. AYF's autoguidance is the best answer so far, but it needs a second trained model and only really works in the distillation setting. The architectures are also borrowed: every model here is a diffusion U-Net or DiT being repurposed, with the skip/output split from EDM and the two-time conditioning bolted on as an extra input embedding. I have not seen anyone ask what a network designed for the one-step objective from scratch would look like. MeanFlow's $\bar u = x_1 - x_0$ identity is more fragile than it looks too; it relies on linear interpolation paths, and the moment you want curved schedules (which matter for sample quality at scale) the algebra stops and you are back to the integral form. And the benchmarks here are all ImageNet at 64, 256, and 512. A real one-step video model does not exist yet.

My guess is the next jump is either an architecture redesign that bakes in the boundary and composition constraints, or a clean way to do guidance at one step. The compositional principle feels right. What is missing is the engineering around it.

What I find most satisfying about this whole family is that the composition rule is the single unifying principle, even though it can look like a different trick in each paper. Every method is a different answer to the same question: how do you enforce that a long jump equals composed shorter jumps, while keeping training tractable? Consistency models do it globally via self-distillation. CTM generalises to any pair of times. Shortcut models go discrete and condition on step size. Align Your Flow imports an external teacher and shows the ideas transfer cleanly to distillation at scale. MeanFlow goes continuous via an exact calculus identity, with no teacher at all. Once you see that, the curricula, the EMA copies, the JVP, and the 75% border-case sampling stop looking like separate tricks.

---

**References**

<ol class="ref-list">
<li id="ref-lipman2022">Lipman et al., <a href="https://arxiv.org/abs/2210.02747">Flow Matching for Generative Modeling</a>, 2022.</li>
<li id="ref-song2023">Song et al., <a href="https://arxiv.org/abs/2303.01469">Consistency Models</a>, 2023.</li>
<li id="ref-ict2023">Song &amp; Dhariwal, <a href="https://arxiv.org/abs/2310.14189">Improved Consistency Training for Consistency Models</a>, 2023.</li>
<li id="ref-ctm2024">Kim et al., <a href="https://arxiv.org/abs/2310.02279">Consistency Trajectory Models: Learning Probability Flow ODE Trajectory of Diffusion</a>, ICLR 2024.</li>
<li id="ref-shortcut2024">Frans et al., <a href="https://arxiv.org/abs/2410.12557">One Step Diffusion via Shortcut Models</a>, ICLR 2025.</li>
<li id="ref-meanflow2025">Geng et al., <a href="https://arxiv.org/abs/2505.13447">MeanFlow: Unified Average-Velocity Learning for Flow-Based Generative Models</a>, 2025.</li>
<li id="ref-alphaflow2025">Zhang et al., <a href="https://arxiv.org/abs/2510.20771">α-Flow: Unifying Flow Matching and Consistency Models</a>, 2025.</li>
<li id="ref-ayf2025">Sabour et al., <a href="https://arxiv.org/abs/2506.14603">Align Your Flow: Scaling Continuous-Time Flow Map Distillation</a>, 2025.</li>
<li id="ref-sct2024">Lu &amp; Song, <a href="https://arxiv.org/abs/2410.11081">Simplifying, Stabilizing &amp; Scaling Continuous-Time Consistency Models</a>, 2024.</li>
<li id="ref-karras2022">Karras et al., <a href="https://arxiv.org/abs/2206.00364">Elucidating the Design Space of Diffusion-Based Generative Models</a>, NeurIPS 2022.</li>
<li id="ref-songsde2021">Song et al., <a href="https://arxiv.org/abs/2011.13456">Score-Based Generative Modeling through Stochastic Differential Equations</a>, ICLR 2021.</li>
</ol>
