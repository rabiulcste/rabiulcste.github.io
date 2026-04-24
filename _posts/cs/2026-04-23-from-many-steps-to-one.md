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
.eq {
  background: #f5f5f5;
  padding: 14px 20px;
  border-radius: 8px;
  margin: 1.4rem 0 0.4rem;
  border: 0.5px solid #e0e0e0;
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
@media (prefers-color-scheme: dark) {
  .eq { background: #252525; border-color: #333; color: #e0e0e0; }
  .eq .MathJax, .eq .MathJax_Display, .eq .MathJax svg { color: #e0e0e0 !important; fill: #e0e0e0 !important; }
  .eq-label { color: #666; }
  .callout { background: #222; border-color: #444; color: #aaa; }
  .interactive-box { border-color: #333; }
  #unifiedDetail { background: #252525 !important; color: #b0b0b0 !important; }
}
.ref-list { font-size: 13px; color: #888; padding-left: 1.4rem; }
.ref-list li { margin-bottom: 0.35rem; line-height: 1.5; }
sup.cite a { color: #5599cc; text-decoration: none; font-size: 11px; vertical-align: super; }
sup.cite a:hover { text-decoration: underline; }
</style>

I have been spending a lot of time on one-step generative models, specifically MeanFlow and the broader family it belongs to. This is my attempt to build an honest mental model of how these methods work, where the math comes from, and how each one is a response to the limitations of the one before it.

The context: diffusion and flow models can now produce images, audio, and video that are nearly indistinguishable from real data, but generating a single sample requires hundreds of sequential network evaluations. That is fine for offline synthesis. It is nearly unusable for anything interactive or real-time. The last two years have seen a serious push to fix this, and the results are surprisingly good.

Starting from the goal and working backwards: what does a network need to learn to generate in one step?

---

## The trajectory picture

Every method in this family starts from the same setup. You define a *trajectory* through image space: a path from pure noise at time $t=1$ to clean data at time $t=0$. This is the probability flow ODE (PF-ODE), a deterministic path with the same marginal distributions at each time $t$ as the stochastic diffusion process but without the randomness. You can run it forwards or backwards exactly. Think of probability mass as a fluid, like shampoo bubbles slowly changing shape. The PF-ODE is the velocity field that moves each bubble without tearing it apart or compressing it; the density is preserved, just the shape changes. A point $z_t$ on a trajectory is one particle of that fluid at time $t$.

The closer $t$ is to 1, the noisier the particle. The bubble is still spread out, not yet shaped like a real image. The PF-ODE tells you how fast and in which direction to move to stay on the trajectory. Standard diffusion and flow models learn to estimate this velocity locally and integrate it step by step from noise to data.

<figure>
<svg width="100%" viewBox="0 0 680 210" role="img">
<title>A PF-ODE trajectory from noise to data</title>
<desc>A curved path from a diffuse noise cloud on the left to a structured data cluster on the right, with three intermediate points. The path is labeled as the PF-ODE trajectory.</desc>
<defs><marker id="ar1" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse"><path d="M2 1L8 5L2 9" fill="none" stroke="context-stroke" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></marker></defs>
<ellipse cx="100" cy="105" rx="64" ry="64" fill="none" stroke="#ddd" stroke-width="0.5" stroke-dasharray="3 4"/>
<ellipse cx="100" cy="105" rx="40" ry="40" fill="none" stroke="#ddd" stroke-width="0.5" stroke-dasharray="2 4"/>
<circle cx="82" cy="90" r="3" fill="#ddd"/>
<circle cx="114" cy="100" r="3" fill="#ddd"/>
<circle cx="92" cy="122" r="3" fill="#ddd"/>
<circle cx="120" cy="84" r="3" fill="#ddd"/>
<circle cx="76" cy="116" r="3" fill="#ddd"/>
<text style="font-size:12px;fill:#aaa" x="100" y="190" text-anchor="middle">noise  t=1</text>
<ellipse cx="570" cy="105" rx="46" ry="46" fill="none" stroke="#5599cc" stroke-width="0.5" stroke-dasharray="3 4"/>
<ellipse cx="570" cy="105" rx="26" ry="26" fill="none" stroke="#5599cc" stroke-width="0.5"/>
<circle cx="560" cy="98" r="4" fill="#d6e8f7" stroke="#5599cc" stroke-width="1"/>
<circle cx="580" cy="110" r="4" fill="#d6e8f7" stroke="#5599cc" stroke-width="1"/>
<circle cx="558" cy="118" r="4" fill="#d6e8f7" stroke="#5599cc" stroke-width="1"/>
<text style="font-size:12px;fill:#5599cc" x="570" y="190" text-anchor="middle">data  t=0</text>
<path d="M 158 105 C 210 80, 290 128, 348 102 C 405 76, 462 120, 522 105" fill="none" stroke="#bbb" stroke-width="1.5" marker-end="url(#ar1)"/>
<circle cx="210" cy="91" r="5" fill="#f8f8f8" stroke="#bbb" stroke-width="1.5"/>
<circle cx="344" cy="107" r="5" fill="#f8f8f8" stroke="#bbb" stroke-width="1.5"/>
<circle cx="462" cy="114" r="5" fill="#f8f8f8" stroke="#bbb" stroke-width="1.5"/>
<text style="font-size:11px;fill:#ccc" x="210" y="79" text-anchor="middle">t=0.8</text>
<text style="font-size:11px;fill:#ccc" x="344" y="125" text-anchor="middle">t=0.5</text>
<text style="font-size:11px;fill:#ccc" x="462" y="132" text-anchor="middle">t=0.2</text>
<text style="font-size:11px;fill:#ccc" x="340" y="22" text-anchor="middle">PF-ODE trajectory</text>
</svg>
<figcaption>Every method in this family lives on a trajectory like this. The question is not how the trajectory is defined; it is how much of it you have to traverse at inference time.</figcaption>
</figure>

The core problem with step-by-step integration: the local velocity at $z_t$ tells you nothing about where the trajectory ends up globally. You have to follow it closely, one small step at a time, or you drift off course and end up somewhere wrong. This is expensive.

Two strategies for escaping this:

1. **Jump to the endpoint directly.** Learn a function that maps any trajectory point to $x_0$ in one shot. This is the consistency model idea.
2. **Jump to any point, not just the endpoint.** Learn a two-time function that can jump from any $t$ to any $s < t$ in one step. This is the flow map idea, and it is what CTM, shortcut models, and MeanFlow all do.

---

## Flow matching: the foundation

Before getting to one-step methods, it helps to understand flow matching, because all the one-step methods either build on it or borrow its training structure. Flow matching <sup class="cite"><a href="#ref-lipman2022">[1]</a></sup> defines simple straight-line paths between noise and data:

<div class="eq">$$z_t \;=\; (1 - t)\, x_0 \;+\; t\, x_1$$</div>
<div class="eq-label">$x_0$ is clean data, $x_1$ is Gaussian noise, $t \in [0,1]$. At $t=0$ you have data; at $t=1$ you have noise.</div>

The velocity at any point on this path is constant: $v = x_0 - x_1$. This is directly computable from training pairs, no score estimation, no self-referential structure. You train a network to predict this velocity at every $(z_t, t)$, which is just supervised regression on a clean ground-truth target.

Why is inference still slow? Even though each individual path $x_0 \leftrightarrow x_1$ is a straight line, the *marginal* velocity field is not. At any given noisy image $z_t$, many different clean images $x_0$ are plausible, not just one. Each candidate $x_0$ has its own straight-line velocity pointing in a slightly different direction. The network has to output the probability-weighted average of all those directions, which traces a curved path through image space. Following a curved path with only local velocity information requires many small steps.

<style>
.mv-label { font-size: 11px; color: #aaa; letter-spacing: 0.04em; text-transform: uppercase; margin-bottom: 4px; }
.mv-nbtn { transition: background 0.15s, color 0.15s; }
.mv-nbtn.mv-active { background: #5599cc; color: #fff; border-color: #5599cc; }
</style>
<figure>
<div class="interactive-box" style="padding-bottom:14px;">
  <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;flex-wrap:wrap;">
    <button id="mvPlay" class="play-btn">&#9654; play</button>
    <div style="display:flex;align-items:center;gap:8px;flex:1;min-width:160px;">
      <span style="font-size:12px;color:#aaa;white-space:nowrap;">t =</span>
      <input type="range" min="0" max="100" value="100" step="1" id="mvScrub" style="flex:1;">
      <span id="mvTout" style="font-size:12px;font-weight:500;color:#888;min-width:28px;">1.00</span>
    </div>
    <div style="display:flex;align-items:center;gap:6px;">
      <span style="font-size:12px;color:#aaa;">candidates</span>
      <button id="mvN1" class="play-btn mv-nbtn" style="min-width:28px;padding:3px 8px;">1</button>
      <button id="mvN3" class="play-btn mv-nbtn" style="min-width:28px;padding:3px 8px;">3</button>
      <button id="mvN5" class="play-btn mv-nbtn" style="min-width:28px;padding:3px 8px;">5</button>
    </div>
  </div>
  <canvas id="cvMarginal" style="width:100%;display:block;border-radius:4px;"></canvas>
  <div style="display:flex;justify-content:space-between;margin-top:8px;gap:8px;">
    <p id="mvCapL" style="font-size:12px;color:#aaa;margin:0;flex:1;">1 destination: field is uniform everywhere. A single step from any noise point lands exactly at x₀.</p>
    <p id="mvCapR" style="font-size:12px;color:#888;margin:0;flex:1;text-align:right;"></p>
  </div>
</div>
<figcaption>The marginal velocity field $\bar{v}(z,t)$ (amber arrows) and its live weight decomposition (bottom panel). Each cluster on the right represents a region of data space — cats, dogs, cars. At $t=1$ (pure noise) the Gaussian weights $w_i \propto \exp(-\|z - z_t^{(i)}\|^2 / 2\sigma^2)$ over all clusters are nearly equal: the particle has no information about which cluster it will become, so the field averages all their directions and points toward the centroid. As $t$ decreases the weights concentrate: the particle's position becomes informative about which cluster it is heading to, and the field progressively commits. With one cluster the field is uniform and one step is exact. With multiple clusters, a single large step follows the initial average direction and lands between all clusters — the red ghost shows exactly where.</figcaption>
</figure>

<script>
(function(){
  var cv  = document.getElementById('cvMarginal');
  var ctx = cv.getContext('2d');
  var LW = 640, LH = 400, MAIN_H = LH - 110;
  var TRAJ_STEPS = 400, N_CLOUD = 26, ANIM_DUR = 4200;
  var PALETTE = ['#5599cc','#1D9E75','#D85A30','#9B59B6','#E67E22'];
  var AMBER = '#BA7517';
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
    var sigma = 55, wx = 0, wy = 0, ws = 0, i, cpx, cpy, d2, w;
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
    var sigma = 55, i, cpx, cpy, d2, out = [];
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

  // Seeded LCG so scenarios are deterministic across redraws
  function seededRand(seed){
    var s = seed;
    return function(){ s = (s * 1664525 + 1013904223) & 0xffffffff; return (s >>> 0) / 0xffffffff; };
  }

  function makeScenario(n){
    var noiseX = 80, dataX = 540, cy = MAIN_H / 2;
    // Clusters are evenly spaced vertically across most of the canvas.
    // Noise samples are randomly scattered across the full y range —
    // each one will sort to whichever cluster's conditional path it ends up
    // closest to, tracing a genuinely curved trajectory as it commits.
    var dataSpread = n === 1 ? 0 : Math.min(n * 44, MAIN_H - 60);
    var rand = seededRand(n * 7919);
    var modes = [], noisePts = [], i, frac, exactPaths, cloudStarts, cloudPaths;
    var v0, ghost;
    for(i = 0; i < n; i++){
      frac = n === 1 ? 0.5 : i / (n - 1);
      modes.push({x: dataX, y: cy + (frac - 0.5) * dataSpread});
      // Each noise point placed randomly across the full canvas height
      noisePts.push({x: noiseX, y: 20 + rand() * (MAIN_H - 40)});
    }
    exactPaths = noisePts.map(function(np){ return tracePath(np, noisePts, modes); });
    // Cloud: uniform grid of starts — shows the full field structure
    cloudStarts = [];
    for(i = 0; i < N_CLOUD; i++){
      cloudStarts.push({x: noiseX, y: 14 + i * (MAIN_H - 28) / (N_CLOUD - 1)});
    }
    cloudPaths = cloudStarts.map(function(s){ return tracePath(s, noisePts, modes); });
    // Ghost: one-step landing from the centroid of all noise pts
    var cnx = 0, cny = 0;
    for(i = 0; i < n; i++){ cnx += noisePts[i].x; cny += noisePts[i].y; }
    cnx /= n; cny /= n;
    v0 = margV(cnx, cny, 1.0, noisePts, modes);
    ghost = {x: cnx + v0.x, y: cny + v0.y};
    return {n: n, modes: modes, noisePts: noisePts, dataSpread: dataSpread,
            exactPaths: exactPaths, cloudPaths: cloudPaths, ghost: ghost};
  }

  function drawScene(sc, t){
    var dark = window.matchMedia('(prefers-color-scheme:dark)').matches;
    var bg   = dark ? '#1a1a1a' : '#fafafa';
    var n    = sc.n;
    var stepIdx = Math.min(Math.round((1-t) * TRAJ_STEPS), TRAJ_STEPS - 1);
    var gi, gj, px, py, v, vl, ex, ey, alpha, i, j, path, cp, col, prev, by, ws, wsSum, wNorm, maxW, note, bx, blw, bw, bh, w;

    ctx.clearRect(0, 0, LW, LH);
    ctx.fillStyle = bg; ctx.fillRect(0, 0, LW, LH);

    // divider
    ctx.beginPath(); ctx.moveTo(0, MAIN_H); ctx.lineTo(LW, MAIN_H);
    ctx.strokeStyle = dark ? '#2a2a2a' : '#e8e8e8'; ctx.lineWidth = 0.5; ctx.stroke();

    // velocity field
    for(gi = 0; gi < 11; gi++){
      for(gj = 0; gj < 8; gj++){
        px = 50 + gi * (LW - 100) / 10;
        py = 14 + gj * (MAIN_H - 28) / 7;
        v  = margV(px, py, t, sc.noisePts, sc.modes);
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
      ctx.strokeStyle = dark ? 'rgba(160,160,160,0.055)' : 'rgba(0,0,0,0.045)';
      ctx.lineWidth = 1; ctx.stroke();
      cp = path[Math.min(stepIdx, path.length - 1)];
      ctx.beginPath(); ctx.arc(cp.x, cp.y, 2, 0, Math.PI*2);
      ctx.fillStyle = dark ? 'rgba(185,185,185,0.22)' : 'rgba(60,60,60,0.15)'; ctx.fill();
    }

    // exact marginal paths
    for(i = 0; i < sc.exactPaths.length; i++){
      path = sc.exactPaths[i];
      col  = n === 1 ? PALETTE[0] : PALETTE[i % PALETTE.length];
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
      ctx.strokeStyle = '#c0392b'; ctx.lineWidth = 1.5;
      ctx.setLineDash([3, 3]); ctx.stroke(); ctx.setLineDash([]);
      ctx.globalAlpha = 0.4; ctx.fillStyle = '#c0392b'; ctx.fill(); ctx.globalAlpha = 1;
      ctx.font = '9px system-ui,sans-serif'; ctx.textAlign = 'center';
      ctx.fillStyle = '#c0392b'; ctx.globalAlpha = 0.65;
      ctx.fillText('1-step lands here', sc.ghost.x, sc.ghost.y - 11);
      ctx.globalAlpha = 1;
    }

    // data clusters — drawn as sideways Gaussian density bumps
    // Each cluster cy±spread shows a filled bell curve extending leftward from dataX,
    // communicating "this is a region of probability mass, not just a point"
    var CLUSTER_NAMES = ['cats','dogs','cars','birds','boats'];
    var clusterSigma = n === 1 ? 30 : Math.max(8, Math.min(24, sc.dataSpread / (n * 1.8)));
    for(i = 0; i < sc.modes.length; i++){
      col = n === 1 ? PALETTE[0] : PALETTE[i % PALETTE.length];
      var mx = sc.modes[i].x, my = sc.modes[i].y;
      // sideways bell extending LEFT from the data wall — fills space toward trajectory
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
      ctx.font = '10px system-ui,sans-serif'; ctx.textAlign = 'left'; ctx.fillStyle = col;
      var clabel = n === 1 ? 'data' : CLUSTER_NAMES[i] || ('cluster ' + (i+1));
      ctx.fillText(clabel, mx + 7, my + 4);
    }
    // noise side: single wide Gaussian bell extending rightward — unstructured, no clusters
    var noiseSig = MAIN_H * 0.22, noiseBMaxW = 22, noiseX2 = 80, noiseCY = MAIN_H / 2;
    ctx.beginPath();
    ctx.moveTo(noiseX2, noiseCY - noiseSig * 2.2);
    for(var ns = 0; ns <= 32; ns++){
      var ndy = -noiseSig * 2.2 + ns * (noiseSig * 4.4 / 32);
      ctx.lineTo(noiseX2 + noiseBMaxW * Math.exp(-(ndy*ndy)/(2*noiseSig*noiseSig)), noiseCY + ndy);
    }
    ctx.lineTo(noiseX2, noiseCY + noiseSig * 2.2);
    ctx.closePath();
    ctx.fillStyle = dark ? 'rgba(180,180,180,0.07)' : 'rgba(0,0,0,0.05)'; ctx.fill();
    ctx.strokeStyle = dark ? '#3a3a3a' : '#d8d8d8'; ctx.lineWidth = 1; ctx.stroke();
    ctx.font = '10px system-ui,sans-serif'; ctx.textAlign = 'right';
    ctx.fillStyle = dark ? '#444' : '#bbb';
    ctx.fillText('noise', noiseX2 - 3, noiseCY - 4);
    ctx.fillText('(x₁)', noiseX2 - 3, noiseCY + 8);

    // corner labels
    ctx.font = '10px system-ui,sans-serif';
    ctx.fillStyle = dark ? '#555' : '#bbb'; ctx.textAlign = 'left';
    ctx.fillText('t = ' + t.toFixed(2), 8, 13);
    ctx.fillStyle = AMBER; ctx.globalAlpha = 0.5; ctx.textAlign = 'right';
    ctx.fillText('velocity field (amber)', LW - 8, 13);
    ctx.globalAlpha = 1;

    // weight bars (n>1 only)
    var PY = MAIN_H + 7;
    if(n > 1){
      cp  = sc.exactPaths[0][Math.min(stepIdx, sc.exactPaths[0].length - 1)];
      ws  = rawWeights(cp.x, cp.y, t, sc.noisePts, sc.modes);
      wsSum = 0;
      for(i = 0; i < ws.length; i++) wsSum += ws[i];
      if(wsSum < 1e-9) wsSum = 1;
      wNorm = ws.map(function(x){ return x / wsSum; });

      ctx.font = '9px system-ui,sans-serif'; ctx.textAlign = 'left';
      ctx.fillStyle = dark ? '#555' : '#bbb';
      ctx.fillText('w(z,t) at particle 1 — how much does each data cluster pull?', 8, PY + 10);

      bx = 8; blw = 78; bw = 190; bh = 10;
      maxW = 0;
      for(i = 0; i < wNorm.length; i++){
        w   = wNorm[i];
        if(w > maxW) maxW = w;
        col = PALETTE[i % PALETTE.length];
        by  = PY + 18 + i * 14;
        ctx.fillStyle = dark ? '#242424' : '#ebebeb';
        ctx.fillRect(bx + blw, by, bw, bh);
        ctx.fillStyle = col; ctx.globalAlpha = 0.75;
        ctx.fillRect(bx + blw, by, Math.max(0, bw * w), bh);
        ctx.globalAlpha = 1;
        ctx.font = '9px system-ui,sans-serif'; ctx.textAlign = 'left'; ctx.fillStyle = col;
        var cname = n === 1 ? 'data' : (CLUSTER_NAMES[i] || 'cluster ' + (i+1));
        ctx.fillText('w' + (i + 1) + ' (' + cname + ')', bx, by + bh - 1);
        ctx.textAlign = 'right'; ctx.fillStyle = dark ? '#666' : '#999';
        ctx.fillText((w * 100).toFixed(0) + '%', bx + blw - 3, by + bh - 1);
      }

      note = maxW > 0.82 ? 'committed — particle knows its mode'
           : maxW > 0.48 ? 'concentrating — ambiguity resolving'
           :               'near-equal — destination unknown';
      ctx.font = '9px system-ui,sans-serif'; ctx.textAlign = 'left';
      ctx.fillStyle = dark ? '#555' : '#aaa';
      ctx.fillText(note, bx + blw + bw + 10, PY + 22);
    }

    // progress bar
    var pbarX = 8, pbarW = LW - 16, pbarY = LH - 13;
    ctx.fillStyle = dark ? '#242424' : '#e6e6e6';
    ctx.fillRect(pbarX, pbarY, pbarW, 2);
    ctx.fillStyle = '#5599cc'; ctx.globalAlpha = 0.65;
    ctx.fillRect(pbarX, pbarY, (1 - t) * pbarW, 2);
    ctx.globalAlpha = 1;
    ctx.font = '9px system-ui,sans-serif'; ctx.fillStyle = dark ? '#444' : '#ccc';
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
      [1.0, 0.60, 'Near t=1 (noise): the particle could become any cluster — cats, dogs, cars… The field averages all their directions equally, pointing toward the centroid. No cluster has been chosen yet. Watch the weight bars.'],
      [0.60, 0.15, 'Mid-journey: one cluster pulling ahead in the weights. Paths fan apart. A naive single large step from t=1 follows that initial average direction and lands between all clusters, reaching none of them.'],
      [0.15, 0.0, 'Near t=0 (data): weights fully concentrated on one cluster. Particles have sorted. The red ghost shows where the single-step shortcut would have landed — between clusters, nowhere.']
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

Flow matching gives you clean, stable training but slow inference. Every method we discuss next is trying to fix the inference speed without giving up that training clarity.

---

## Consistency models

Consistency models <sup class="cite"><a href="#ref-song2023">[2]</a></sup> are the first serious attack on the inference problem. Rather than learn the velocity and integrate it, learn a function that maps any point on the trajectory directly to the clean endpoint $x_0$:

<div class="eq">$$f_\theta(z_t,\, t) \;=\; x_0 \qquad \text{for all } t \text{ on the same trajectory}$$</div>

Apply this once from pure noise, and you get a clean image. One network call, done.

For this to actually work, the function needs two properties. First, the **boundary condition**: at $t = 0$ (or more precisely, a small cutoff $\varepsilon$ near zero), the function must be the identity, $f(x_0, \varepsilon) = x_0$. A completely clean image maps to itself. This is enforced architecturally, typically by a skip connection that activates near zero. Without it, the network could satisfy the rest of the loss by outputting a constant; the boundary condition pins one end of the function to something meaningful.

Second, the **consistency condition**: any two points on the *same* PF-ODE trajectory must map to the same $x_0$. If two different noisy versions of the same clean image both pass through the network, they should produce identical outputs. This is the key constraint that makes the function globally coherent rather than just locally trained.

<figure>
<svg width="100%" viewBox="0 0 680 200" role="img">
<title>Consistency condition: all trajectory points map to the same x0</title>
<desc>A curved PF-ODE trajectory with four points. Dashed blue arrows from each point all converge on the same x0 endpoint at the right.</desc>
<defs><marker id="ar3" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse"><path d="M2 1L8 5L2 9" fill="none" stroke="context-stroke" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></marker></defs>
<path d="M 58 162 C 140 132, 220 154, 302 126 C 362 108, 412 98, 470 88" fill="none" stroke="#ccc" stroke-width="1.5"/>
<circle cx="58"  cy="162" r="6" fill="#f8f8f8" stroke="#ccc" stroke-width="1.5"/>
<circle cx="190" cy="145" r="6" fill="#f8f8f8" stroke="#ccc" stroke-width="1.5"/>
<circle cx="308" cy="123" r="6" fill="#f8f8f8" stroke="#ccc" stroke-width="1.5"/>
<circle cx="410" cy="101" r="6" fill="#f8f8f8" stroke="#ccc" stroke-width="1.5"/>
<text style="font-size:11px;fill:#aaa" x="58"  y="180" text-anchor="middle">t=1</text>
<text style="font-size:11px;fill:#aaa" x="190" y="163" text-anchor="middle">t=0.7</text>
<text style="font-size:11px;fill:#aaa" x="308" y="141" text-anchor="middle">t=0.4</text>
<text style="font-size:11px;fill:#aaa" x="410" y="119" text-anchor="middle">t=0.1</text>
<circle cx="596" cy="64" r="10" fill="#d6e8f7" stroke="#5599cc" stroke-width="1.5"/>
<text style="font-size:13px;font-weight:500;fill:#5599cc" x="596" y="68" text-anchor="middle">x₀</text>
<line x1="64"  y1="157" x2="584" y2="68" stroke="#5599cc" stroke-width="1" stroke-dasharray="4 3" marker-end="url(#ar3)"/>
<line x1="196" y1="141" x2="584" y2="66" stroke="#5599cc" stroke-width="1" stroke-dasharray="4 3" marker-end="url(#ar3)"/>
<line x1="314" y1="119" x2="584" y2="65" stroke="#5599cc" stroke-width="1" stroke-dasharray="4 3" marker-end="url(#ar3)"/>
<line x1="415" y1="97"  x2="584" y2="65" stroke="#5599cc" stroke-width="1" stroke-dasharray="4 3" marker-end="url(#ar3)"/>
<text style="font-size:12px;fill:#aaa" x="340" y="24" text-anchor="middle">f(z_t, t) = x₀   for every t on the same trajectory</text>
</svg>
<figcaption>The consistency condition says all these dashed arrows must land at exactly the same $x_0$. The function is consistent across the whole trajectory, not just at individual points.</figcaption>
</figure>

### Enforcing consistency via self-distillation

Here is the problem: you never directly observe which trajectory any $z_t$ belongs to. You cannot enumerate all the $(z_t, z_s)$ pairs that should agree. What you can do is take two *adjacent* points on the same trajectory, $z_t$ and $z_{t-\Delta}$ separated by a small step, and ask that their predictions agree:

<div class="eq">$$\mathcal{L}_\text{CD} \;=\; \mathbb{E}\,\bigl\lVert f_\theta(z_t,\,t) \;-\; \operatorname{sg}\!\bigl(f_{\theta^-}(z_{t-\Delta},\,t-\Delta)\bigr) \bigr\rVert^2$$</div>
<div class="eq-label">sg = stop-gradient. $\theta^-$ = EMA copy of $\theta$, updated slowly as $\theta^- \leftarrow m\,\theta^- + (1-m)\,\theta$.</div>

The EMA copy $\theta^-$ is updated slowly after each training step, typically $m \approx 0.99$, so the target moves at roughly 1% of the speed of the main network. This keeps the target stable enough to learn against. Without it, both sides of the loss update simultaneously and they can easily converge to the same wrong answer: outputting a constant everywhere, which technically satisfies the loss but is completely useless for generation.

The stop-gradient on the target side breaks this symmetry. Gradients only flow through the left side of the loss, so only $\theta$ is updated to chase the target. The target then drifts slowly via the EMA rule. This is the same *target network* trick from deep RL; it is what makes self-distillation stable.

To get the adjacent point $z_{t-\Delta}$ on the same trajectory as $z_t$, you need to take one step of the PF-ODE. In consistency *distillation* (CD), you use a pretrained diffusion model to do this and the teacher provides reliable ODE steps. In consistency *training* (CT), you estimate the score from scratch, which introduces additional noise and is harder to stabilise.

### The discretisation curriculum and why it is not optional

One subtlety about consistency model training that matters. You divide the time axis into $N$ discrete steps. Adjacent training pairs are always one step apart, so the gap $\Delta = T/N$.

If $N$ is small, the gap is large. The two adjacent points are far apart on the PF-ODE trajectory. The training signal is strong (there is a lot of distance between the two predictions to align) but the targets are noisy. Taking a large step along the PF-ODE introduces large discretisation error, so $z_{t-\Delta}$ is only approximately on the right trajectory. You are training the network to agree with a somewhat wrong target.

If $N$ is large, the gap is small. The targets are very accurate (a tiny ODE step is nearly exact) but the training signal is weak. The two adjacent points are so close that their predictions are already similar. The loss gradient is tiny and training makes almost no progress.

Neither extreme works. The fix is a curriculum: start with small $N$ (coarse, strong signal, rough targets), then progressively increase $N$ (fine, weak signal, accurate targets). The network first learns a rough consistency function, then refines it.

<figure>
<div class="interactive-box">
<div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;flex-wrap:wrap;">
  <button id="playDisc" class="play-btn">&#9654; play</button>
  <label style="font-size:13px;color:#888;min-width:80px;">steps N</label>
  <input type="range" min="1" max="8" value="1" step="1" id="nSteps" oninput="drawDisc()" style="flex:1;">
  <span id="nStepsOut" style="font-size:13px;font-weight:500;min-width:46px;text-align:right;">N = 1</span>
</div>
<canvas id="cvDisc" style="width:100%;display:block;border-radius:4px;"></canvas>
<p id="discCap" style="font-size:12px;color:#aaa;margin:6px 0 0;text-align:center;">N=1: one step covers the whole trajectory. The tangent step at z_t lands far from the true curve. Large training signal, noisy target.</p>
</div>
<figcaption>The PF-ODE trajectory (grey) curves because the marginal velocity field curves. At each marked time step, the tangent arrow shows the local velocity. A single Euler step along that tangent departs from the true curve; the error is the red gap. More steps reduces the gap but shrinks the per-step gradient.</figcaption>
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
    const dark=window.matchMedia('(prefers-color-scheme:dark)').matches;
    const fg=dark?'#d0d0d0':'#1a1a1a';
    const trueCurveColor=dark?'#444':'#ddd';
    const muted=dark?'#555':'#bbb';
    const blue='#5599cc', red='#D85A30', teal='#1D9E75', amber='#BA7517';

    ctx.clearRect(0,0,LW,LH);

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
    ctx.font='11px system-ui,sans-serif'; ctx.setLineDash([]);
    ctx.fillStyle=muted; ctx.textAlign='left';
    ctx.fillText('x₁  noise  t=1',noiseEnd.x+10,noiseEnd.y+4);
    ctx.fillStyle=teal;
    ctx.fillText('x₀  data  t=0',dataEnd.x-80,dataEnd.y-10);
    ctx.fillStyle=trueCurveColor.replace('#ddd','#aaa').replace('#444','#666');
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

### Limitations

Consistency models prove the point: you can generate decent images in one step. That was not obvious before 2023. The original paper achieved FID 3.55 on CIFAR-10 and 6.20 on ImageNet 64×64 in a single step. iCT <sup class="cite"><a href="#ref-ict2023">[3]</a></sup> improved this substantially with pseudo-Huber losses, a lognormal noise schedule, and progressive discretisation step doubling, reaching FID 2.51 (CIFAR-10) and 3.25 (ImageNet 64×64) in a single step, a 3-4× improvement over the original. But even these required considerable engineering effort just to be reliable.

The training target is always *behavioural*: it constrains what the network outputs at adjacent pairs of points, not what the underlying field should be. There is no ground truth for $f(z_t, t)$ that exists independently of the network. The optimal function is defined only implicitly, via the consistency condition and boundary condition, and can only be learned by having the network agree with itself across adjacent pairs. This is inherently noisy and sensitive to hyperparameters.

The deeper limitation: consistency models are stuck. They can only jump to one destination, the endpoint $x_0$. The function signature is $f(z_t, t) = x_0$; you tell it where you are and what time it is, and it predicts the endpoint. You cannot ask it to jump to an intermediate point. Multi-step generation therefore requires running the network multiple times and renoising between each evaluation, a clunky workaround that does not actually use the trajectory structure.

---

## Consistency trajectory models: any-to-any jumps

CTM <sup class="cite"><a href="#ref-ctm2024">[4]</a></sup> generalises consistency models in one clean move. Recall the PF-ODE trajectory we defined: a path parameterised by time, running from noise at $t=1$ to data at $t=0$. Consistency models always jump to the end of that path. CTM removes that restriction and learns a function that can jump to *any* point along the PF-ODE, not just the endpoint:

<div class="eq">$$G_\theta(x_t,\, t,\, s) \;=\; x_s$$</div>
<div class="eq-label">From any point $x_t$ at time $t$, jump to the state $x_s$ at time $s$. Consistency models are the special case $s=0$.</div>

This is the **two-time function**: a completely flexible jump operator. You can take large steps or small steps, jump to any intermediate point on the trajectory, and compose multiple jumps to refine a generation. Consistency models are just the special case where you always set $s = 0$.

The key constraint that makes this well-posed is the **semigroup property**. If you jump from $t$ to some intermediate $u$, and then jump from $u$ to $s$, you should get the same result as jumping directly from $t$ to $s$:

<div class="eq">$$G_\theta(x_t,\, t,\, s) \;=\; G_\theta\!\bigl(G_\theta(x_t,\, t,\, u),\; u,\; s\bigr) \qquad \text{for any } u \in (s,t)$$</div>
<div class="eq-label">One large jump = two composed smaller jumps. This is the composition rule at the heart of every flow-map method.</div>

<figure>
<div class="interactive-box">
<div style="display:flex;align-items:center;gap:10px;margin-bottom:10px;flex-wrap:wrap;">
  <button id="playSemi" class="play-btn">&#9654; play</button>
  <label style="font-size:13px;color:#888;min-width:80px;">split point</label>
  <input type="range" min="15" max="85" value="40" step="1" id="splitPct" oninput="drawSemi()" style="flex:1;">
  <span id="splitOut" style="font-size:13px;font-weight:500;min-width:46px;text-align:right;">u = 0.40</span>
</div>
<canvas id="cvSemi" style="width:100%;display:block;border-radius:4px;"></canvas>
<p style="font-size:12px;color:#aaa;margin:6px 0 0;text-align:center;">Drag the split point. Both routes (direct and two-step) always land at the same destination.</p>
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
    const dark = window.matchMedia('(prefers-color-scheme:dark)').matches;
    const fg=dark?'#d0d0d0':'#1a1a1a';
    const faint=dark?'#2a2a2a':'#f0f0f0';
    const muted=dark?'#555':'#bbb';
    const nodeFill=dark?'#1e1e1e':'#f8f8f8';
    const blue='#5599cc', teal='#1D9E75', amber='#BA7517';
    ctx.clearRect(0,0,LW,LH);

    // axis line
    ctx.strokeStyle=faint; ctx.lineWidth=0.5; ctx.setLineDash([]);
    ctx.beginPath(); ctx.moveTo(rX-18,baseY); ctx.lineTo(tX+18,baseY); ctx.stroke();

    // time axis label
    ctx.font='11px system-ui,sans-serif'; ctx.textAlign='center';
    ctx.fillStyle=muted;
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

    ctx.font='12px system-ui,sans-serif'; ctx.textAlign='center';
    ctx.fillStyle=blue;
    ctx.fillText('r = 0',rX,baseY+24);
    ctx.fillText('t = 1',tX,baseY+24);
    ctx.fillStyle=muted;
    ctx.fillText('u = '+pct.toFixed(2),sX,baseY+24);

    ctx.fillStyle=muted;
    ctx.fillText('direct: G(z_t, t→r)',(rX+tX)/2,baseY-63);
    ctx.fillStyle=amber;
    ctx.fillText('1st leg: t→u',(sX+tX)/2,baseY+76);
    ctx.fillStyle=teal;
    ctx.fillText('2nd leg: u→r',(rX+sX)/2,baseY+76);

    ctx.fillStyle=fg; ctx.font='11px system-ui,sans-serif';
    ctx.fillText('semigroup: G(z_t, t, r) = G( G(z_t, t, u), u, r )  for any u',LW/2,LH-8);
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

CTM achieves FID 1.73 on CIFAR-10 and 1.92 on ImageNet 64×64 in a single step, the best single-step results at the time by a significant margin. The flexible jump function also makes multi-step generation more natural: you just chain calls with progressively smaller target times, no renoising needed.

The limitation it inherits from consistency models: the training target is still self-referential. $G_{\theta^-}$ is the network evaluated at a slightly lagged version of itself. There is no ground-truth two-time map that exists independently of the network; the only supervision comes from the model agreeing with itself across different decompositions. This makes training more stable than consistency models (because the semigroup structure is richer), but the fundamental self-referential nature remains. CTM also required adversarial training as a core component to reach its best numbers, which limits how cleanly it scales.

---

## Align Your Flow: distilling the jump function

Align Your Flow <sup class="cite"><a href="#ref-ayf2025">[8]</a></sup> works with exactly the same object as CTM: a two-time network $f_\theta(x_t, t, s) = x_s$ that jumps from any noise level $t$ to any cleaner level $s$ in one forward pass. The difference is not the architecture or the mathematical object being learned; it is the training regime. Instead of training from scratch with self-referential targets, AYF distills from a pretrained teacher: the consistency loss is replaced by an objective that asks the student's jump function to agree with one-step moves of the teacher's probability-flow ODE.

<figure>
<img src="https://arxiv.org/html/2506.14603/extracted/6549324/figures/overview_fig_4.jpg"
     alt="Overview of Flow Maps from Align Your Flow (Sabour et al., 2025). Three panels show Consistency Model (s=0), Flow Map (any s,t), and Flow Matching (s→t), with their respective training objectives below."
     style="width:100%;border-radius:4px;display:block;">
<figcaption>Figure 2 from Sabour et al. (2025). Flow maps generalise both consistency models and flow matching by connecting any two noise levels $(s, t)$ in a single step. Setting $s=0$ recovers a consistency model; letting $s \to t$ recovers standard flow matching. (Image: arXiv 2506.14603, reproduced for educational commentary.)</figcaption>
</figure>

This framing resolves something that was implicit in CTM but never fully confronted. CTM trains the jump function so that composed shorter jumps reproduce longer ones, but it never asks whether the jump function is actually correct, only whether it is internally consistent. A pretrained teacher changes that: the teacher's ODE trajectories are ground truth, and the student's jumps are trained to trace them. Internal consistency is still enforced, but now there is an external anchor.

The paper also proves something sharp about consistency models: for any non-optimal consistency model, there exists a step count beyond which adding more steps monotonically worsens FID. This is not a failure of implementation; it is a structural consequence of the self-referential training target accumulating errors under composition. Flow maps trained against a teacher do not have this property. Adding steps always helps or is neutral, because each additional step is a further correction toward the teacher's trajectory rather than a compounding of self-generated error.

In practice this matters a lot. AYF-S, a 280M-parameter model, reaches FID 1.25 at 2 NFE on ImageNet 64×64, and FID 1.70 at 4 NFE on ImageNet 512×512 in 0.24 seconds. The comparable distillation baseline (sCD-XXL, 1.5B parameters, 2 NFE) achieves FID 1.88 in 0.50 seconds: larger model, more compute, worse result. The efficiency gain comes directly from the teacher anchor: the student does not have to waste capacity on self-consistency at high noise levels where the self-referential signal is noisy and unreliable.

---

## Shortcut models

CTM's training samples random triples $(r, s, t)$, which requires managing a flexible two-time input and coordinating two separate network evaluations for the composed target. Shortcut models <sup class="cite"><a href="#ref-shortcut2024">[5]</a></sup> ask: what is the simplest possible way to enforce the semigroup property?

The answer: condition the network on both the current noise level $t$ and the *desired step size* $d$. The network learns to predict where you will end up after a jump of size $d$ from $z_t$. The step size is an input, not a fixed constant.

<div class="eq">$$v_\theta(z_t,\, t,\, d) \;\approx\; \frac{z_t - z_{t-d}}{d}$$</div>
<div class="eq-label">Predict the average displacement per unit time over a step of size $d$. At $d \to 0$ this recovers instantaneous velocity.</div>

Think of it like this. A regular flow matching network only knows "I am at noise level $t$." A shortcut model knows "I am at noise level $t$, and I want to travel a distance of $d$ in one step." With that extra information, it can calibrate its prediction to the correct jump size and can be asked to take different step sizes at different points during generation.

### The bootstrapping training procedure

How do you train this? You cannot compute the ground-truth $z_{t-d}$ directly, because it would require running the full ODE. But you can enforce the semigroup property discretely:

<div class="eq">$$v_\theta(z_t,\,t,\,d) \;=\; \operatorname{compose}\!\bigl(v_{\theta^-}(z_t,\,t,\,d/2),\;\; v_{\theta^-}(z_{t-d/2},\,t-d/2,\,d/2)\bigr)$$</div>
<div class="eq-label">One large step of size $d$ = two composed half-steps of size $d/2$.</div>

The right side is computable: take a half-step along the PF-ODE using the EMA network to reach $z_{t-d/2}$, then take another half-step from there. Compose the two predictions into a target for the full step. The EMA network on the right side plays the same stabilising role as in consistency models; it moves slowly so the target does not chase itself.

In practice, training proceeds by starting with small steps (where the half-step approximation is accurate) and progressively training larger steps using the smaller steps as building blocks. The network learns to predict one-step jumps first, then two-step, then four-step, and so on, bootstrapping upward. At inference, you can choose any step count: one step for speed, many steps for quality.

<figure>
<svg width="100%" viewBox="0 0 680 230" role="img">
<title>Shortcut model bootstrapping: one large step = two composed half-steps</title>
<desc>Three points on a noise timeline. The EMA network takes two half-steps to produce a composed target. The student network takes the full step in one shot.</desc>
<defs><marker id="ar4b" viewBox="0 0 10 10" refX="8" refY="5" markerWidth="6" markerHeight="6" orient="auto-start-reverse"><path d="M2 1L8 5L2 9" fill="none" stroke="context-stroke" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></marker></defs>
<line x1="60" y1="110" x2="630" y2="110" stroke="#eee" stroke-width="0.5"/>
<circle cx="100" cy="110" r="8" fill="#d6e8f7" stroke="#5599cc" stroke-width="1.5"/>
<circle cx="356" cy="110" r="8" fill="#f8f8f8" stroke="#bbb" stroke-width="1.5"/>
<circle cx="600" cy="110" r="8" fill="#f8f8f8" stroke="#bbb" stroke-width="1.5"/>
<text style="font-size:12px;fill:#5599cc" x="100" y="132" text-anchor="middle">z_{t−d}</text>
<text style="font-size:11px;fill:#aaa" x="100" y="148" text-anchor="middle">landing point</text>
<text style="font-size:12px;fill:#888" x="356" y="132" text-anchor="middle">z_{t−d/2}</text>
<text style="font-size:11px;fill:#aaa" x="356" y="148" text-anchor="middle">halfway</text>
<text style="font-size:12px;fill:#888" x="600" y="132" text-anchor="middle">z_t</text>
<text style="font-size:11px;fill:#aaa" x="600" y="148" text-anchor="middle">start</text>
<path d="M 108 110 Q 228 58 348 110" fill="none" stroke="#1D9E75" stroke-width="1.6" marker-end="url(#ar4b)"/>
<path d="M 364 110 Q 480 58 592 110" fill="none" stroke="#BA7517" stroke-width="1.6" marker-end="url(#ar4b)"/>
<text style="font-size:11px;fill:#1D9E75" x="228" y="56" text-anchor="middle">EMA: 2nd half-step</text>
<text style="font-size:11px;fill:#BA7517" x="480" y="56" text-anchor="middle">EMA: 1st half-step</text>
<path d="M 108 110 Q 354 180 592 110" fill="none" stroke="#5599cc" stroke-width="2" stroke-dasharray="6 3" marker-end="url(#ar4b)"/>
<text style="font-size:12px;fill:#5599cc" x="352" y="194" text-anchor="middle">student: one full step (what we are training)</text>
<text style="font-size:11px;fill:#888" x="350" y="24" text-anchor="middle">bootstrapping: half-steps teach full steps</text>
</svg>
<figcaption>The EMA network (solid arcs) takes two half-steps to produce a composed target. The student network (dashed arc) is trained to match this in a single step. Same stop-gradient / EMA pattern as consistency models, applied to the composition property.</figcaption>
</figure>

Shortcut models avoid the JVP computation that MeanFlow requires. The composition is enforced by direct comparison of network outputs, not by differentiating through the network. This is simpler and faster per training step. The tradeoff: the discrete half-step approximation introduces small errors that compound when you compose many steps. The continuous formulation of MeanFlow avoids this, but pays the JVP cost to do so.

---

## MeanFlow: ground truth for the jump function

Now we have the setup to understand what makes MeanFlow <sup class="cite"><a href="#ref-meanflow2025">[6]</a></sup> special. Every method so far has the same fundamental limitation: the training target is self-referential. You are always asking the network to agree with itself, at adjacent points, or at composed decompositions. There is no independent ground truth for what the jump function should output.

MeanFlow's answer is the **average velocity**, a quantity that has a ground-truth value computable directly from data, with no self-reference required.

### Average velocity: a ground-truth two-time quantity

Define the average velocity over an interval $[r, t]$ starting from state $z_t$ as simply displacement divided by time:

<div class="eq">$$\bar{u}(z_t,\, r,\, t) \;=\; \frac{z_t - z_r}{t - r}$$</div>
<div class="eq-label">$z_r$ is where you would land if you followed the PF-ODE from $z_t$ back to time $r$. Average velocity = distance ÷ time, the same definition as in physics.</div>

For the linear interpolation paths of flow matching, this simplifies completely. Since $z_t = (1-t)x_0 + tx_1$ and $z_r = (1-r)x_0 + rx_1$:

<div class="eq">$$\bar{u}(z_t,\, r,\, t) \;=\; \frac{z_t - z_r}{t - r} \;=\; x_1 - x_0$$</div>
<div class="eq-label">Independent of $r$ and $t$: the average velocity over any interval is just $x_1 - x_0$, computable directly from training data.</div>

The average velocity does not depend on which interval you are looking at. It is a fixed quantity for each training pair $(x_0, x_1)$, readable directly from data. No network evaluation, no self-reference, no approximation. One-step generation falls out immediately:

<div class="eq">$$x_0 \;=\; z_1 \;-\; \bar{u}_\theta(z_1,\, 0,\, 1)$$</div>
<div class="eq-label">Start from pure noise $z_1$ at $t=1$, subtract the average velocity, arrive at $x_0$. One network call.</div>

The challenge is training a network to predict $\bar{u}$ correctly for all $(z_t, r, t)$ triples, not just the full-interval case. The definition gives a ground-truth target, but computing it directly during training is intractable.

### The MeanFlow identity: turning the definition into a training signal

Computing $z_r$ requires running the PF-ODE from $z_t$ to time $r$, exactly the slow integration we are trying to avoid. MeanFlow gets around this by deriving an equivalent form that does not require $z_r$ at all. Start from the definition rewritten as an integral:

<div class="eq">$$(t - r)\cdot \bar{u}(z_t,\, r,\, t) \;=\; \int_r^t v(z_\tau,\, \tau)\, d\tau$$</div>
<div class="eq-label">Average velocity × time = total displacement = integral of instantaneous velocity. Think: distance = speed × time.</div>

Now differentiate both sides with respect to $t$. The right side uses the fundamental theorem of calculus; the left side uses the product rule:

<div class="eq">$$\bar{u}(z_t,\, r,\, t) \;=\; v(z_t,\, t) \;-\; (t - r)\cdot \frac{d\bar{u}}{dt}$$</div>
<div class="eq-label">The MeanFlow identity. $v(z_t, t)$ is the instantaneous flow matching velocity (ground truth from data). $d\bar{u}/dt$ is the total time derivative of the network output.</div>

This identity gives a target for $\bar{u}$ that involves no integrals and no ODE simulation. The right side has two pieces: $v(z_t, t)$ is the standard flow matching velocity (the same clean ground-truth target that flow matching uses), and $d\bar{u}/dt$ is the derivative of the network's own output with respect to the time conditioning. The identity is exact, not an approximation.

### Computing $d\bar{u}/dt$: the Jacobian-vector product

The term $d\bar{u}/dt$ is a total derivative: it measures how the network output changes as $t$ increases, accounting for two effects simultaneously, the explicit dependence on $t$ as a conditioning input and the implicit dependence through $z_t$ (which moves along the flow as $t$ changes). Expanding via the chain rule:

<div class="eq">$$\frac{d\bar{u}}{dt} \;=\; \frac{\partial \bar{u}}{\partial z}\cdot v(z_t,\,t) \;+\; \frac{\partial \bar{u}}{\partial t}$$</div>
<div class="eq-label">First term: Jacobian of $\bar{u}$ w.r.t. its input $z$, multiplied by the velocity vector (a JVP). Second term: explicit partial derivative w.r.t. $t$.</div>

The first term is a Jacobian-vector product (JVP): the Jacobian of the network output with respect to its input $z$, dotted with the velocity vector $v(z_t, t)$. This is computed via forward-mode automatic differentiation, a single modified forward pass through the network. In PyTorch: `torch.func.jvp`. In JAX: `jax.jvp`. The overhead is roughly 20% compared to standard flow matching training, which is modest; far less than running a full second forward pass for a teacher network.

The full training loss applies stop-gradient to the entire target to avoid second-order gradients:

<div class="eq">$$\mathcal{L}_\text{MF} \;=\; \mathbb{E}\,\bigl\lVert \bar{u}_\theta(z_t,\,r,\,t) \;-\; \operatorname{sg}\!\Bigl[v_\text{FM}(z_t,t) \;-\; (t-r)\cdot\tfrac{d\bar{u}_\theta}{dt}\Bigr] \bigr\rVert^2$$</div>
<div class="eq-label">Stop-gradient prevents gradients from flowing through the target. $v_\text{FM}$ is the flow matching ground-truth velocity.</div>

---

## The TFM/TC conflict in MeanFlow training

MeanFlow looks clean on paper: ground-truth target, exact identity, minimal overhead. In practice, the training has an interesting internal structure that causes problems.

### The 75% border case

When $r = t$, the interval collapses to a single point, and the average velocity over a zero-length interval equals the instantaneous velocity. The MeanFlow identity reduces to $\bar{u} = v$, and the loss becomes exactly the standard flow matching loss. MeanFlow uses $r = t$ for 75% of training samples. Why spend three-quarters of training on the degenerate case that ignores the average velocity entirely?

The α-Flow paper <sup class="cite"><a href="#ref-alphaflow2025">[7]</a></sup> explains this by showing the MeanFlow loss decomposes into two components:

<div class="eq">$$\mathcal{L}_\text{MF} \;=\; \mathcal{L}_\text{TFM} \;+\; \mathcal{L}_\text{TC}$$</div>
<div class="eq-label">TFM = trajectory flow matching (data-supervised). TC = trajectory consistency (JVP-based). The 75% border case is dominated by TFM.</div>

**TFM** is the flow matching component. It pushes the network to correctly predict the instantaneous velocity field: purely data-supervised, stable, converges quickly. The 75% sampling ensures TFM dominates early training.

**TC** is the consistency enforcement component. It uses the JVP to ensure predictions compose correctly across intervals. This is the part that gives MeanFlow its structure beyond plain flow matching. But TC depends on a JVP computed through the network, which is noisy at high noise levels.

### Why TC is noisy at high $t$

The TC gradient uses the JVP: the Jacobian of the network output with respect to input $z_t$, multiplied by the velocity vector. At high $t$ (near pure noise) the input carries almost no semantic signal. The network's weights at this early stage are unstructured. The Jacobian of an unstructured network with respect to its input is essentially random: large in magnitude, arbitrary in direction. Multiplying this random matrix by the velocity vector produces a JVP that points nowhere useful.

The consequence: TC gradients at high $t$ early in training are large, random vectors. They actively conflict with TFM gradients. α-Flow <sup class="cite"><a href="#ref-alphaflow2025">[7]</a></sup> measured this directly: the cosine similarity between TFM and TC gradient vectors is strongly negative early in training. They are pulling the network in opposite directions.

The fix is the **$\lambda$ curriculum**. The parameter $\lambda \in [0,1]$ interpolates between pure TFM ($\lambda=0$) and full MeanFlow ($\lambda=1$). Start training with $\lambda=0$, which is just flow matching, completely stable. As training progresses and the velocity field converges, the network starts genuinely learning the PF-ODE structure: the Jacobian at high $t$ begins to encode which direction the trajectory is heading, and the JVP becomes a reliable signal rather than noise. Then increase $\lambda$ to bring TC online. By the time TC is fully active, the network has enough PF-ODE structure that the JVP actually means something.

Same coarse-to-fine principle as the discretisation curriculum in consistency models, applied to a continuous parameter. Stabilise the data-supervised component first; introduce the self-referential component after the foundation is solid.

---

## A unified view

Every method we have discussed enforces the same composition rule (that a long jump should equal composed shorter jumps) at different levels of precision and with different tradeoffs.

<figure>
<div class="interactive-box">
<p style="font-size:12px;color:#aaa;margin:0 0 10px;text-align:center;">Click any method to see details</p>
<div style="display:grid;grid-template-columns:repeat(5,1fr);gap:8px;margin-bottom:12px;" id="unifiedGrid"></div>
<div id="unifiedDetail" style="background:#f8f8f8;border-radius:6px;padding:12px 16px;font-size:13px;color:#555;line-height:1.6;min-height:60px;"></div>
<div style="margin-top:12px;position:relative;height:28px;">
  <div style="position:absolute;left:0;right:0;top:14px;height:0.5px;background:#ccc;"></div>
  <div id="alphaThumb" style="position:absolute;top:6px;width:14px;height:14px;border-radius:50%;background:#5599cc;border:2px solid #fff;box-shadow:0 0 0 1px #5599cc;transition:left 0.4s;pointer-events:none;"></div>
  <div style="position:absolute;left:0;top:24px;font-size:11px;color:#bbb;">α=0  stable</div>
  <div style="position:absolute;right:0;top:24px;font-size:11px;color:#bbb;text-align:right;">α=1  expressive</div>
</div>
</div>
<figcaption>The full family, unified. Each method enforces the composition rule more strictly than the one to its left, at increasing cost. MeanFlow is the only one with both a ground-truth target and one-step inference, but pays with the JVP and the TFM/TC conflict.</figcaption>
</figure>

<script>
(function(){
const methods=[
  {name:'Flow matching',alpha:0,
   props:['no composition enforced','ground-truth target ✓','multi-step inference','no JVP needed'],
   propColors:['#888','#1D9E75','#D85A30','#1D9E75'],
   detail:'The foundation. Defines straight-line paths between noise and data, trains on instantaneous velocity v = x₀ − x₁. Clean supervised learning with a fixed ground-truth target. Slow at inference because integration is required to follow the curved marginal path.',
   accent:false},
  {name:'Consistency models',alpha:0.25,
   props:['any point → same x₀','behavioural target ✗','one-step inference ✓','no JVP needed'],
   propColors:['#888','#D85A30','#1D9E75','#1D9E75'],
   detail:'The jump-to-endpoint idea. Learns f(z_t, t) = x₀ using self-distillation: the network trains to agree with its own EMA copy at adjacent trajectory points. One-step generation, but no ground truth exists for the consistency function; training is chasing a moving target.',
   accent:false},
  {name:'CTM / Shortcut',alpha:0.6,
   props:['discrete composition','behavioural target ✗','one-step inference ✓','no JVP needed'],
   propColors:['#888','#D85A30','#1D9E75','#1D9E75'],
   detail:'The any-to-any generalisation. CTM learns G(x_t, t, s) for any pair of times. Shortcut models add step-size conditioning d and bootstrap: two half-steps teach one full step. More flexible than consistency models, same self-referential training structure; no JVP needed.',
   accent:false},
  {name:'MeanFlow',alpha:1,
   props:['continuous composition','ground-truth target ✓','one-step inference ✓','JVP ~20% overhead'],
   propColors:['#888','#1D9E75','#1D9E75','#D85A30'],
   detail:'MeanFlow learns average velocity ū(z_t, r, t), a ground-truth quantity computable directly from (x₀, x₁) pairs. The MeanFlow identity gives a training signal without integrals or ODE simulation. One-step generation. Pays with JVP computation and the TFM/TC training conflict. FID 3.43 on ImageNet 256×256 (1-NFE, trained from scratch).',
   accent:false},
  {name:'Align Your Flow',alpha:1,
   props:['flow maps: any (s,t)','distillation from teacher','FID 1.25 @ 2-NFE IN-64','autoguidance + EMD'],
   propColors:['#888','#888','#1D9E75','#1D9E75'],
   detail:'AYF scales the flow-map idea (f_θ(x_t,t,s)=x_s) to large pretrained models via distillation. Proves consistency models degrade with more steps; flow maps do not. Two objectives: EMD (Eulerian, sharp images) and LMD (Lagrangian, stable but blurry). Replaces CFG with autoguidance from a weaker checkpoint. AYF-S (280M params) reaches FID 1.70 on IN-512 at 4 NFE in 0.24s, beating sCD-XXL (1.5B params, 2 NFE, FID 1.88, 0.50s) at 5x fewer parameters and 2x faster.',
   accent:true},
];
let active=3;
const grid=document.getElementById('unifiedGrid');
const detail=document.getElementById('unifiedDetail');
const thumb=document.getElementById('alphaThumb');

function render(){
  grid.innerHTML='';
  methods.forEach((m,i)=>{
    const card=document.createElement('div');
    const isSel=i===active;
    card.style.cssText='border-radius:6px;padding:10px 8px;cursor:pointer;transition:all .15s;border:'+
      (isSel?(m.accent?'1.5px solid #5599cc':'1.5px solid #888'):'0.5px solid #ddd')+';'+
      'background:'+(isSel?(m.accent?'#eef4fb':'#f0f0f0'):'#f9f9f9')+';';
    card.innerHTML='<div style="font-size:12px;font-weight:500;color:'+(isSel?(m.accent?'#2266aa':'#333'):'#555')+';margin-bottom:6px;line-height:1.3;">'+m.name+'</div>'+
      m.props.map((p,j)=>'<div style="font-size:11px;color:'+m.propColors[j]+';margin-bottom:2px;">'+p+'</div>').join('');
    card.onclick=()=>{active=i;render();};
    grid.appendChild(card);
  });
  const m=methods[active];
  detail.textContent=m.detail;
  detail.style.background=m.accent?'#eef4fb':'#f8f8f8';
  detail.style.color=m.accent?'#2266aa':'#555';
  const pct=(m.alpha/1)*100;
  thumb.style.left='calc('+pct+'% - 7px)';
  thumb.style.background=m.accent?'#5599cc':'#888';
  thumb.style.boxShadow='0 0 0 1px '+(m.accent?'#5599cc':'#888');
}
render();
})();
</script>

α-Flow <sup class="cite"><a href="#ref-alphaflow2025">[7]</a></sup> formalises this: all four methods are special cases of one parameterised objective. The parameter $\alpha$ interpolates between pure flow matching ($\alpha=0$, no composition) and full MeanFlow ($\alpha=1$, continuous composition). The general principle behind every scheduling trick we have seen is to start at $\alpha=0$ and anneal toward 1: the discretisation curriculum in consistency models, the 75% border-case sampling in MeanFlow, the $\lambda$ ramp-up. All of these are different parameterisations of the same idea: learn the stable data-supervised component first, then progressively enforce the self-referential consistency component.

---

## Results and current state

The one-step generation problem is solved in the sense that it works. These numbers did not exist two years ago, and the gap with multi-step diffusion continues to close.

<div style="overflow-x:auto;margin:1.2rem 0 1.6rem;">
<table style="border-collapse:collapse;font-size:13px;width:100%;min-width:480px;">
<thead>
<tr style="border-bottom:1px solid #ddd;">
  <th style="text-align:left;padding:7px 12px;font-weight:600;color:#555;">Method</th>
  <th style="text-align:center;padding:7px 12px;font-weight:600;color:#555;">NFE</th>
  <th style="text-align:center;padding:7px 12px;font-weight:600;color:#555;">Benchmark</th>
  <th style="text-align:center;padding:7px 12px;font-weight:600;color:#555;">FID ↓</th>
  <th style="text-align:left;padding:7px 12px;font-weight:600;color:#555;">Training</th>
</tr>
</thead>
<tbody>
<tr style="border-bottom:1px solid #f0f0f0;">
  <td style="padding:7px 12px;">Consistency Models <sup class="cite"><a href="#ref-song2023">[2]</a></sup></td>
  <td style="text-align:center;padding:7px 12px;">1</td>
  <td style="text-align:center;padding:7px 12px;">CIFAR-10</td>
  <td style="text-align:center;padding:7px 12px;">3.55</td>
  <td style="padding:7px 12px;color:#888;">distillation / from scratch</td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0;">
  <td style="padding:7px 12px;">iCT <sup class="cite"><a href="#ref-ict2023">[3]</a></sup></td>
  <td style="text-align:center;padding:7px 12px;">1</td>
  <td style="text-align:center;padding:7px 12px;">CIFAR-10 / IN-64</td>
  <td style="text-align:center;padding:7px 12px;">2.51 / 3.25</td>
  <td style="padding:7px 12px;color:#888;">from scratch</td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0;">
  <td style="padding:7px 12px;">CTM <sup class="cite"><a href="#ref-ctm2024">[4]</a></sup></td>
  <td style="text-align:center;padding:7px 12px;">1</td>
  <td style="text-align:center;padding:7px 12px;">CIFAR-10 / IN-64</td>
  <td style="text-align:center;padding:7px 12px;">1.73 / 1.92</td>
  <td style="padding:7px 12px;color:#888;">distillation + adversarial</td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0;">
  <td style="padding:7px 12px;">MeanFlow <sup class="cite"><a href="#ref-meanflow2025">[6]</a></sup></td>
  <td style="text-align:center;padding:7px 12px;">1</td>
  <td style="text-align:center;padding:7px 12px;">IN-256</td>
  <td style="text-align:center;padding:7px 12px;">3.43</td>
  <td style="padding:7px 12px;color:#888;">from scratch</td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0;">
  <td style="padding:7px 12px;">α-Flow <sup class="cite"><a href="#ref-alphaflow2025">[7]</a></sup></td>
  <td style="text-align:center;padding:7px 12px;">1 / 2</td>
  <td style="text-align:center;padding:7px 12px;">IN-256</td>
  <td style="text-align:center;padding:7px 12px;">2.58 / 2.15</td>
  <td style="padding:7px 12px;color:#888;">from scratch (DiT)</td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0;">
  <td style="padding:7px 12px;" rowspan="3">Align Your Flow <sup class="cite"><a href="#ref-ayf2025">[8]</a></sup></td>
  <td style="text-align:center;padding:7px 12px;">1</td>
  <td style="text-align:center;padding:7px 12px;">IN-64</td>
  <td style="text-align:center;padding:7px 12px;">2.98</td>
  <td style="padding:7px 12px;color:#888;" rowspan="3">distillation (EMD) + optional adversarial</td>
</tr>
<tr style="border-bottom:1px solid #f0f0f0;">
  <td style="text-align:center;padding:7px 12px;">2</td>
  <td style="text-align:center;padding:7px 12px;">IN-64</td>
  <td style="text-align:center;padding:7px 12px;font-weight:500;color:#1D9E75;">1.25</td>
</tr>
<tr>
  <td style="text-align:center;padding:7px 12px;">4</td>
  <td style="text-align:center;padding:7px 12px;">IN-512 (280M)</td>
  <td style="text-align:center;padding:7px 12px;font-weight:500;color:#1D9E75;">1.70 <span style="font-weight:400;color:#aaa;font-size:11px;">0.24s</span></td>
</tr>
</tbody>
</table>
<p style="font-size:11px;color:#aaa;margin:4px 0 0;">IN = ImageNet. NFE = network function evaluations. AYF-S at 4 NFE (FID 1.70, 0.24s) outperforms sCD-XXL at 2 NFE (FID 1.88, 0.50s) using 5× fewer parameters.</p>
</div>

What I find most satisfying about this whole family is that the composition rule is the single unifying principle, even though it can look like a different trick in each paper. Every method is a different answer to the same question: how do you enforce that a long jump equals composed shorter jumps, while keeping training tractable? Consistency models do it globally via self-distillation. CTM does it for any pair of times. Shortcut models do it discretely with step-size conditioning. MeanFlow does it continuously via calculus. Align Your Flow shows the same ideas transfer cleanly to distillation from pretrained teachers at scale. Once you see this, the curricula, the EMA teachers, the JVP, the 75% border-case sampling, the tangent warmup: all of it falls into place.

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
</ol>
