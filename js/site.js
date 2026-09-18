/**
 * Single bundle: content enhancements, archive filters + tag cloud, post TOC.
 * No jQuery.
 */
(function () {
  'use strict';

  var $$ = function (sel, root) {
    return Array.prototype.slice.call((root || document).querySelectorAll(sel));
  };

  function wrap(el, className) {
    if (el.closest('.' + className)) return;
    var box = document.createElement('div');
    box.className = className;
    el.parentNode.insertBefore(box, el);
    box.appendChild(el);
  }

  function enhanceTablesAndEmbeds() {
    $$('table').forEach(function (table) {
      wrap(table, 'table-responsive');
      table.classList.add('table');
    });
    $$('iframe[src*="youtube.com"], iframe[src*="vimeo.com"]').forEach(function (iframe) {
      wrap(iframe, 'embed-responsive');
    });
  }

  // Deliberately not URLSearchParams: that decodes, and tags are compared
  // against Liquid's already-encoded data-encode attribute.
  function rawQueryParam(name) {
    var found = '';
    window.location.search.slice(1).split('&').forEach(function (pair) {
      var eq = pair.indexOf('=');
      if (pair.slice(0, eq < 0 ? pair.length : eq) === name) {
        found = eq < 0 ? '' : pair.slice(eq + 1);
      }
    });
    return found;
  }

  function initArchiveFilter() {
    var tagsRoot = document.querySelector('.js-tags');
    var resultRoot = document.querySelector('.js-result');
    if (!tagsRoot || !resultRoot) return;

    var buttons = $$('a[data-encode]', tagsRoot);
    var showAll = tagsRoot.querySelector('.tag-button--all');
    var items = $$('.item', resultRoot);
    var baseUrl = window.location.href.split('?')[0];

    function select(tag, pushUrl) {
      tag = tag || '';
      // data-encode holds Liquid's url_encode output, so tags stay percent-encoded
      // on both sides of this comparison and in the ?tag= query.
      var active = buttons.filter(function (btn) {
        return btn.getAttribute('data-encode') === tag;
      })[0] || showAll;
      buttons.forEach(function (btn) {
        btn.classList.toggle('focus', btn === active);
      });
      items.forEach(function (item) {
        var tags = (item.getAttribute('data-tags') || '').split(',');
        item.classList.toggle('d-none', tag !== '' && tags.indexOf(tag) === -1);
      });
      if (pushUrl) {
        window.history.replaceState(null, '', tag ? baseUrl + '?tag=' + tag : baseUrl);
      }
    }

    tagsRoot.addEventListener('click', function (e) {
      var a = e.target.closest('a[data-encode]');
      if (!a || !tagsRoot.contains(a)) return;
      e.preventDefault();
      select(a.getAttribute('data-encode'), true);
    });

    select(rawQueryParam('tag'), false);
  }

  function initPostCatalog() {
    var container = document.querySelector('article .post-container');
    var list = document.getElementById('catalog-body');
    var panel = document.querySelector('.top-catalog');
    if (!container || !list || !panel) return;

    var entries = $$('h1[id],h2[id],h3[id],h4[id],h5[id],h6[id]', container)
      .filter(function (h) { return h.textContent.trim(); })
      .map(function (h) {
        var li = document.createElement('li');
        li.className = h.tagName.toLowerCase() + '_nav';
        var a = document.createElement('a');
        a.href = '#' + h.id;
        a.rel = 'nofollow';
        a.title = a.textContent = h.textContent.trim();
        li.appendChild(a);
        list.appendChild(li);
        return { heading: h, li: li };
      });

    if (!entries.length) {
      panel.classList.add('fold');
      return;
    }

    list.addEventListener('click', function (e) {
      var a = e.target.closest('a');
      if (!a) return;
      e.preventDefault();
      var target = document.getElementById(a.getAttribute('href').slice(1));
      if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    function syncActive() {
      var y = window.scrollY + 96;
      var current = entries[0];
      entries.forEach(function (entry) {
        if (entry.heading.getBoundingClientRect().top + window.scrollY <= y) current = entry;
      });
      entries.forEach(function (entry) {
        entry.li.classList.toggle('active', entry === current);
      });
    }

    window.addEventListener('scroll', syncActive, { passive: true });
    syncActive();
  }

  function initDisqus() {
    var cfg = window.__disqus;
    var el = document.getElementById('disqus_thread');
    if (!cfg || !el) return;

    function load() {
      window.disqus_shortname = cfg.shortname;
      window.disqus_identifier = cfg.id;
      window.disqus_url = cfg.url;
      var s = document.createElement('script');
      s.async = true;
      s.src = 'https://' + cfg.shortname + '.disqus.com/embed.js';
      s.setAttribute('data-timestamp', String(Date.now()));
      document.head.appendChild(s);
    }

    if (!('IntersectionObserver' in window)) return load();
    var io = new IntersectionObserver(function (entries) {
      if (entries[0].isIntersecting) { io.disconnect(); load(); }
    }, { rootMargin: '320px', threshold: 0.01 });
    io.observe(el);
  }

  function initAnchorJS() {
    if (!window.__anchorjs) return;
    var s = document.createElement('script');
    s.src = 'https://cdnjs.cloudflare.com/ajax/libs/anchor-js/1.1.1/anchor.min.js';
    s.addEventListener('load', function () {
      anchors.options = { visible: 'hover', placement: 'right' };
      anchors.add().remove('.intro-header h1').remove('.subheading');
    });
    document.head.appendChild(s);
  }

  function ready(fn) {
    if (document.readyState !== 'loading') fn();
    else document.addEventListener('DOMContentLoaded', fn);
  }

  ready(function () {
    enhanceTablesAndEmbeds();
    initArchiveFilter();
    initPostCatalog();
    initDisqus();
    initAnchorJS();
  });
})();
