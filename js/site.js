/**
 * Single bundle: content enhancements, archive filters + tag cloud, post TOC.
 * No jQuery.
 */
(function () {
  'use strict';

  function onReady(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  function enhanceTablesAndEmbeds() {
    document.querySelectorAll('table').forEach(function (table) {
      if (table.closest('.table-responsive')) return;
      var wrap = document.createElement('div');
      wrap.className = 'table-responsive';
      table.parentNode.insertBefore(wrap, table);
      wrap.appendChild(table);
      if (!table.classList.contains('table')) table.classList.add('table');
    });
    document.querySelectorAll('iframe[src*="youtube.com"], iframe[src*="vimeo.com"]').forEach(function (iframe) {
      if (iframe.closest('.embed-responsive')) return;
      var wrap = document.createElement('div');
      wrap.className = 'embed-responsive embed-responsive-16by9';
      iframe.parentNode.insertBefore(wrap, iframe);
      wrap.appendChild(iframe);
      iframe.classList.add('embed-responsive-item');
    });
  }

  function initTagCloud() {
    var root = document.getElementById('tag_cloud');
    if (!root) return;
    var links = root.querySelectorAll('a.tag-button[rel]');
    if (!links.length) return;
    var weights = Array.prototype.map.call(links, function (a) {
      return parseInt(a.getAttribute('rel'), 10) || 0;
    }).sort(function (a, b) { return a - b; });
    var low = weights[0];
    var high = weights[weights.length - 1];
    var range = high - low || 1;
    var sizeStart = 14;
    var sizeEnd = 18;
    var fontIncr = (sizeEnd - sizeStart) / range;
    var c0 = [0xbb, 0xbb, 0xee];
    var c1 = [0x2f, 0x93, 0xb4];
    var cIncr = c1.map(function (c, i) { return (c - c0[i]) / range; });
    Array.prototype.forEach.call(links, function (a) {
      var w = parseInt(a.getAttribute('rel'), 10) || low;
      var t = w - low;
      a.style.fontSize = (sizeStart + t * fontIncr) + 'pt';
      var rgb = c0.map(function (c, i) {
        return Math.max(0, Math.min(255, Math.round(c + cIncr[i] * t)));
      });
      a.style.backgroundColor = 'rgb(' + rgb.join(',') + ')';
    });
  }

  function tagFromQueryString() {
    var q = window.location.search.slice(1);
    var parts = q.split('&');
    for (var i = 0; i < parts.length; i++) {
      var pair = parts[i].split('=');
      if (pair[0] === 'tag') return pair.length > 1 ? pair.slice(1).join('=') : '';
    }
    return undefined;
  }

  function initArchiveFilter() {
    var tagsRoot = document.querySelector('.js-tags');
    var resultRoot = document.querySelector('.js-result');
    if (!tagsRoot || !resultRoot) return;

    var baseUrl = window.location.href.split('?')[0];
    function setUrlQuery(query) {
      window.history.replaceState(null, '', query ? baseUrl + query : baseUrl);
    }

    var articleTags = tagsRoot.querySelectorAll('.tag-button');
    var tagShowAll = tagsRoot.querySelector('.tag-button--all');
    var sections = resultRoot.querySelectorAll('section');
    var sectionArticles = [];
    sections.forEach(function (sec) {
      sectionArticles.push(sec.querySelectorAll('.item'));
    });
    var lastFocus = null;
    var hasInit = false;

    function buttonFocus(el) {
      if (!el) return;
      el.classList.add('focus');
      if (lastFocus && lastFocus !== el) lastFocus.classList.remove('focus');
      lastFocus = el;
    }

    function searchButtonsByTag(tag) {
      if (tag == null || tag === '') return tagShowAll;
      var found = null;
      articleTags.forEach(function (btn) {
        if (btn.classList.contains('tag-button--all')) return;
        if (btn.getAttribute('data-encode') === tag) found = btn;
      });
      return found || tagShowAll;
    }

    function tagSelect(tag, targetEl) {
      var result = {};
      var i, j, k;
      for (i = 0; i < sectionArticles.length; i++) {
        var articles = sectionArticles[i];
        for (j = 0; j < articles.length; j++) {
          if (tag === '' || tag === undefined) {
            result[i] = result[i] || {};
            result[i][j] = true;
          } else {
            var dt = articles[j].getAttribute('data-tags') || '';
            var parts = dt.split(',');
            for (k = 0; k < parts.length; k++) {
              if (parts[k] === tag) {
                result[i] = result[i] || {};
                result[i][j] = true;
                break;
              }
            }
          }
        }
      }
      for (i = 0; i < sectionArticles.length; i++) {
        if (result[i]) sections[i].classList.remove('d-none');
        else sections[i].classList.add('d-none');
        for (j = 0; j < sectionArticles[i].length; j++) {
          if (result[i] && result[i][j]) sectionArticles[i][j].classList.remove('d-none');
          else sectionArticles[i][j].classList.add('d-none');
        }
      }
      if (!hasInit) {
        resultRoot.classList.remove('d-none');
        hasInit = true;
      }
      if (targetEl) {
        buttonFocus(targetEl);
        var enc = targetEl.getAttribute('data-encode');
        if (enc === '' || enc == null) setUrlQuery();
        else setUrlQuery('?tag=' + enc);
      } else {
        buttonFocus(searchButtonsByTag(tag));
      }
    }

    tagsRoot.addEventListener('click', function (e) {
      var a = e.target.closest('a');
      if (!a || !tagsRoot.contains(a)) return;
      e.preventDefault();
      if (a.classList.contains('tag-button--all')) {
        tagSelect('', a);
        return;
      }
      var enc = a.getAttribute('data-encode');
      if (enc == null) return;
      tagSelect(enc, a);
    });

    tagSelect(tagFromQueryString());
    initTagCloud();
  }

  function initPostCatalog() {
    var container = document.querySelector('article .post-container');
    var body = document.getElementById('catalog-body');
    var side = document.querySelector('.side-catalog');
    var toggle = document.querySelector('.catalog-toggle');
    if (!container || !body || !side) return;

    var headings = container.querySelectorAll('h1[id],h2[id],h3[id],h4[id],h5[id],h6[id]');
    if (!headings.length) {
      side.classList.add('fold');
      return;
    }

    body.innerHTML = '';
    headings.forEach(function (h) {
      var id = h.id;
      var text = h.textContent.trim();
      if (!id || !text) return;
      var li = document.createElement('li');
      li.className = (h.tagName.toLowerCase()) + '_nav';
      var a = document.createElement('a');
      a.href = '#' + id;
      a.setAttribute('rel', 'nofollow');
      a.title = text;
      a.textContent = text;
      li.appendChild(a);
      body.appendChild(li);
    });

    if (toggle) {
      toggle.addEventListener('click', function (e) {
        e.preventDefault();
        side.classList.toggle('fold');
      });
    }

    var links = body.querySelectorAll('a');
    var headingList = Array.prototype.slice.call(headings);
    var byHref = {};
    links.forEach(function (a) {
      byHref[a.getAttribute('href')] = a;
    });

    links.forEach(function (a) {
      a.addEventListener('click', function (e) {
        e.preventDefault();
        var id = a.getAttribute('href').slice(1);
        var target = document.getElementById(id);
        if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      });
    });

    function syncActive() {
      var pad = 96;
      var y = window.scrollY + pad;
      var current = headingList[0];
      for (var i = 0; i < headingList.length; i++) {
        var top = headingList[i].getBoundingClientRect().top + window.scrollY;
        if (top <= y) current = headingList[i];
      }
      var href = '#' + current.id;
      var activeA = byHref[href];
      links.forEach(function (x) {
        var li = x.closest('li');
        if (li) li.classList.toggle('active', x === activeA);
      });
    }

    window.addEventListener('scroll', syncActive, { passive: true });
    syncActive();
  }

  onReady(function () {
    enhanceTablesAndEmbeds();
    initArchiveFilter();
    initPostCatalog();
  });
})();
