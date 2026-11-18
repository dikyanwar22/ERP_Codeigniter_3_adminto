<?php
$show_chat = !empty($show_chat);
$above_bottom_bar = !empty($above_bottom_bar);
$ocModifier = $above_bottom_bar ? ' fab-above-bottom-bar' : '';
?>
<style>
:root { --oc-ocean: #0f4c75; --oc-coral: #ff6b6b; }

.fab-stack {
  position: fixed; right: 18px; bottom: 18px; z-index: 2147483200;
  display: flex; flex-direction: column; align-items: flex-end; gap: 12px;
}
.fab-btn {
  position: relative; width: 52px; height: 52px; border: 0; border-radius: 50%;
  display: flex; align-items: center; justify-content: center; cursor: pointer;
  font-size: 20px; color: #fff; box-shadow: 0 10px 24px rgba(8, 43, 67, .28);
  transition: transform .18s ease, opacity .18s ease, box-shadow .18s ease, visibility .18s;
}
.fab-btn:hover { transform: translateY(-2px); box-shadow: 0 14px 28px rgba(8, 43, 67, .34); }
.fab-btn:active { transform: translateY(0); }
.fab-btn:focus-visible { outline: 3px solid #ffd166; outline-offset: 2px; }
.fab-top {
  background: #fff; color: var(--oc-ocean);
  border: 1px solid rgba(15, 76, 117, .14);
}
.fab-chat { background: linear-gradient(135deg, #2c8fbf 0%, #0f4c75 100%); }
.fab-chat.has-unread::after {
  content: ''; position: absolute; inset: 0; border-radius: 50%;
  border: 2px solid rgba(255, 107, 107, .85);
  animation: ocFabPulse 1.8s ease-out infinite; pointer-events: none;
}
@keyframes ocFabPulse {
  0%   { transform: scale(1);    opacity: .9; }
  70%  { transform: scale(1.5);  opacity: 0; }
  100% { transform: scale(1.5);  opacity: 0; }
}
@media (prefers-reduced-motion: reduce) {
  .fab-chat.has-unread::after { animation: none; }
  .chat-title > i.is-ringing { animation: none; }
}
.fab-badge {
  position: absolute; top: -4px; right: -4px; min-width: 22px; height: 22px; padding: 0 6px;
  border-radius: 999px; background: var(--oc-coral); color: #fff; border: 2px solid #fff;
  font-size: 11px; font-weight: 800; line-height: 18px; text-align: center;
}

.chat-panel {
  position: fixed; right: 18px; bottom: 146px; z-index: 2147483300;
  width: 360px; max-width: calc(100vw - 36px); max-height: calc(100vh - 170px);
  display: none; flex-direction: column; overflow: hidden;
  background: #fff; border: 1px solid rgba(15, 76, 117, .08); border-radius: 18px;
  box-shadow: 0 24px 60px rgba(8, 43, 67, .28);
}
.chat-panel.is-open { display: flex; animation: ocChatIn .18s ease-out; }
@keyframes ocChatIn {
  from { opacity: 0; transform: translateY(10px) scale(.98); }
  to   { opacity: 1; transform: none; }
}
.chat-head {
  display: flex; align-items: center; justify-content: space-between; gap: 10px;
  padding: 12px 14px; color: #fff;
  background: linear-gradient(120deg, #0f4c75 0%, #1b6ca8 100%);
}
.chat-title { display: flex; align-items: center; gap: 8px; font-weight: 800; font-size: 14px; min-width: 0; }
.chat-title > i { font-size: 15px; flex: 0 0 auto; }
.chat-title > i.is-ringing { animation: ocBellRing 1.6s ease-in-out infinite; transform-origin: 50% 10%; }
@keyframes ocBellRing {
  0%, 55%, 100% { transform: rotate(0); }
  60% { transform: rotate(12deg); }
  70% { transform: rotate(-10deg); }
  80% { transform: rotate(6deg); }
  90% { transform: rotate(-4deg); }
}
.chat-unread-pill {
  background: rgba(255, 255, 255, .18); border: 1px solid rgba(255, 255, 255, .28);
  border-radius: 999px; padding: 2px 9px; font-size: 11px; font-weight: 700; white-space: nowrap;
}
.chat-close {
  flex: 0 0 auto; background: transparent; border: 0; color: #fff; cursor: pointer;
  font-size: 22px; line-height: 1; opacity: .85; padding: 0 2px;
}
.chat-close:hover { opacity: 1; }
.chat-search { position: relative; padding: 10px 12px; border-bottom: 1px solid #eef2f6; background: #fff; }
.chat-search > i {
  position: absolute; left: 24px; top: 50%; transform: translateY(-50%);
  color: #94a3b8; font-size: 13px; pointer-events: none;
}
.chat-search input {
  width: 100%; border: 1px solid #e2e8f0; border-radius: 999px; background: #f8fafc;
  padding: 8px 14px 8px 34px; font-size: 13px; color: #23313d; outline: none;
}
.chat-search input:focus { background: #fff; border-color: #93c5fd; box-shadow: 0 0 0 3px rgba(59, 130, 246, .12); }
.chat-list { overflow-y: auto; overscroll-behavior: contain; flex: 1 1 auto; }
.chat-item {
  display: flex; gap: 10px; padding: 12px 14px; cursor: pointer;
  border-bottom: 1px solid #f1f5f9;
}
.chat-item:hover { background: #f8fafc; }
.chat-item.is-unread { background: #f5fbff; }
.chat-avatar {
  position: relative; flex: 0 0 auto; width: 42px; height: 42px; border-radius: 50%;
  overflow: hidden; display: flex; align-items: center; justify-content: center;
  background: linear-gradient(135deg, #2c8fbf, #0f4c75); color: #fff;
  font-size: 13px; font-weight: 800; letter-spacing: .02em;
}
.chat-avatar img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
.chat-body { flex: 1 1 auto; min-width: 0; }
.chat-row { display: flex; align-items: baseline; justify-content: space-between; gap: 8px; }
.chat-name {
  font-size: 13px; font-weight: 700; color: #1e2a35;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.chat-time {
  flex: 0 0 auto; font-size: 10.5px; color: #94a3b8;
  font-variant-numeric: tabular-nums;
}
.chat-msg {
  margin-top: 2px; font-size: 12px; color: #64748b;
  white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.chat-item.is-unread .chat-msg { color: #3f4d5a; }
.chat-dot {
  display: inline-block; width: 7px; height: 7px; margin-right: 6px;
  border-radius: 50%; background: var(--oc-coral); vertical-align: middle;
}
.chat-empty { padding: 28px 18px; text-align: center; color: #94a3b8; font-size: 12.5px; }
.chat-foot {
  padding: 8px 12px; border-top: 1px solid #eef2f6; background: #f8fafc;
  font-size: 11px; color: #94a3b8; text-align: center;
}
@media (max-width: 575.98px) {
  .fab-stack { right: 12px; bottom: 12px; }
  .chat-panel { right: 12px; left: 12px; bottom: 136px; width: auto; max-height: calc(100vh - 160px); }
  .chat-list { max-height: 52vh; }
}

@media (max-width: 991.98px) {
  .fab-stack.fab-above-bottom-bar { right: 12px; bottom: 78px; }
  .chat-panel.fab-above-bottom-bar {
    right: 12px; left: 12px; bottom: 202px; width: auto; max-height: calc(100vh - 224px);
  }
}
</style>

<div class="fab-stack<?= $ocModifier ?>">
  <button type="button" id="backToTop" class="fab-btn fab-top" title="Kembali ke atas" aria-label="Kembali ke atas">
    <i class="bi bi-arrow-up"></i>
  </button>

  <?php if ($show_chat): ?>
  <button type="button" id="chatToggle" class="fab-btn fab-chat" title="Pesan masuk"
          aria-label="Buka pesan masuk" aria-expanded="false" aria-controls="chatPanel">
    <i class="bi bi-chat-dots-fill"></i>
    <span id="chatBadge" class="fab-badge" hidden>0</span>
  </button>
  <?php endif; ?>
</div>

<?php if ($show_chat): ?>
<section id="chatPanel" class="chat-panel<?= $ocModifier ?>" aria-hidden="true" aria-label="Pesan masuk">
  <div class="chat-head">
    <div class="chat-title">
      <i id="chatBell" class="bi bi-bell-fill"></i>
      <span>Pesan Masuk</span>
      <span id="chatUnread" class="chat-unread-pill">0 belum dibaca</span>
    </div>
    <button type="button" id="chatClose" class="chat-close" aria-label="Tutup pesan">&times;</button>
  </div>

  <div class="chat-search">
    <i class="bi bi-search"></i>
    <input type="search" id="chatSearch" placeholder="Cari nama atau pesan..." autocomplete="off" aria-label="Cari pesan">
  </div>

  <div id="chatList" class="chat-list">
    <div class="chat-empty">Memuat pesan…</div>
  </div>

  <div id="chatFoot" class="chat-foot">Data dummy • belum dimuat</div>
</section>
<?php endif; ?>

<script>
(function () {
  'use strict';
  var btn = document.getElementById('backToTop');
  if (!btn) return;
  btn.addEventListener('click', function (e) {
    e.preventDefault();
    try {
      window.scrollTo({ top: 0, left: 0, behavior: 'smooth' });
    } catch (err) {
      window.scrollTo(0, 0);
    }
  });
})();
</script>

<?php if ($show_chat): ?>
<script>
(function ($) {
  'use strict';
  if (!$) return;

  var CHAT_URL = <?= json_encode(site_url('api/chat-dummy'), JSON_UNESCAPED_SLASHES) ?>;

  $(function () {
    var $toggle = $('#chatToggle');
    var $panel  = $('#chatPanel');
    var $list   = $('#chatList');
    var $search = $('#chatSearch');
    var $badge  = $('#chatBadge');
    var $unread = $('#chatUnread');
    var $foot   = $('#chatFoot');
    var $bell   = $('#chatBell');

    var messages = [];
    var loaded   = false;
    var loading  = false;

    function esc(value) {
      return $('<div/>').text(value == null ? '' : String(value)).html();
    }

    function initials(nama) {
      var parts = String(nama || '').trim().split(/\s+/);
      if (!parts[0]) return '?';
      var first = parts[0].charAt(0);
      var last  = parts.length > 1 ? parts[parts.length - 1].charAt(0) : '';
      return (first + last).toUpperCase();
    }

    function shorten(text, max) {
      text = String(text == null ? '' : text);
      return text.length > max ? text.slice(0, max).replace(/\s+$/, '') + '...' : text;
    }

    function countUnread() {
      var n = 0;
      for (var i = 0; i < messages.length; i++) {
        if (messages[i].unread) n++;
      }
      return n;
    }

    function paintUnread() {
      var n = countUnread();

      if (n > 0) {
        $badge.text(n > 99 ? '99+' : n).prop('hidden', false);
        $toggle.addClass('has-unread');
      } else {
        $badge.prop('hidden', true);
        $toggle.removeClass('has-unread');
      }

      $unread.text(n > 0 ? n + ' belum dibaca' : 'semua terbaca');
      $bell.toggleClass('is-ringing', n > 0);
    }

    function itemHtml(m) {
      var unread = !!m.unread;

      return '' +
        '<div class="chat-item' + (unread ? ' is-unread' : '') + '" data-id="' + esc(m.id) + '" tabindex="0" role="button">' +
          '<span class="chat-avatar">' +
            '<span>' + esc(initials(m.nama)) + '</span>' +
            (m.avatar ? '<img src="' + esc(m.avatar) + '" alt="" loading="lazy" onerror="this.remove()">' : '') +
          '</span>' +
          '<div class="chat-body">' +
            '<div class="chat-row">' +
              '<span class="chat-name">' + (unread ? '<span class="chat-dot"></span>' : '') + esc(m.nama) + '</span>' +
              '<span class="chat-time">' + esc(m.waktu) + '</span>' +
            '</div>' +
            '<div class="chat-msg" title="' + esc(m.pesan) + '">' + esc(shorten(m.pesan, 70)) + '</div>' +
          '</div>' +
        '</div>';
    }

    function renderList() {
      var keyword = $.trim(String($search.val() || '')).toLowerCase();
      var rows = [];
      var i, m;

      for (i = 0; i < messages.length; i++) {
        m = messages[i];
        if (!keyword || (String(m.nama) + ' ' + String(m.pesan)).toLowerCase().indexOf(keyword) !== -1) {
          rows.push(m);
        }
      }

      if (!rows.length) {
        $list.html('<div class="chat-empty">' +
          (loaded ? 'Tidak ada pesan yang cocok dengan pencarian.' : 'Memuat pesan…') +
          '</div>');
        return;
      }

      var html = '';
      for (i = 0; i < rows.length; i++) {
        html += itemHtml(rows[i]);
      }
      $list.html(html);
      $list.scrollTop(0);
    }

    function loadMessages() {
      if (loading || loaded) return;
      loading = true;

      $list.html('<div class="chat-empty"><span class="spinner-border spinner-border-sm me-2"></span>Memuat pesan…</div>');

      $.ajax({
        url: CHAT_URL,
        type: 'GET',
        dataType: 'json',
        cache: false,
        timeout: 15000
      })
      .done(function (res) {
        messages = (res && $.isArray(res.data)) ? res.data : [];
        loaded = true;
        paintUnread();
        renderList();
        $foot.text('Total ' + (res && res.total ? res.total : messages.length) +
                   ' pesan • dimuat ' + (res && res.generated_at ? res.generated_at : '-') + ' • data dummy');
      })
      .fail(function () {
        $list.html('<div class="chat-empty">Gagal memuat pesan.<br>' +
          '<button type="button" id="chatRetry" class="btn btn-sm btn-outline-primary rounded-pill mt-2">Coba lagi</button>' +
          '</div>');
      })
      .always(function () {
        loading = false;
      });
    }

    function openPanel() {
      $panel.addClass('is-open').attr('aria-hidden', 'false');
      $toggle.attr('aria-expanded', 'true');
      loadMessages();
      if (loaded) renderList();
      $search.trigger('focus');
    }

    function closePanel() {
      $panel.removeClass('is-open').attr('aria-hidden', 'true');
      $toggle.attr('aria-expanded', 'false');
      $toggle.trigger('focus');
    }

    $toggle.on('click', function () {
      if ($panel.hasClass('is-open')) {
        closePanel();
      } else {
        openPanel();
      }
    });

    $('#chatClose').on('click', closePanel);

    $(document).on('keydown', function (e) {
      if (e.key === 'Escape' && $panel.hasClass('is-open')) closePanel();
    });

    $search.on('input', renderList);

    $list.on('click', '.chat-item', function () {
      var id = String($(this).data('id'));
      var changed = false;

      for (var i = 0; i < messages.length; i++) {
        if (String(messages[i].id) === id && messages[i].unread) {
          messages[i].unread = false;
          changed = true;
        }
      }

      if (changed) {
        paintUnread();
        $(this).removeClass('is-unread').find('.chat-dot').remove();
      }
    });

    $list.on('click', '#chatRetry', loadMessages);
  });
})(window.jQuery);
</script>
<?php endif; ?>
