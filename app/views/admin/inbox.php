<div class="inbox-page-container">
    <!-- Header Area -->
    <div class="inbox-header-section">
        <div class="header-left-group">
            <h2 class="page-title">Support Inbox</h2>
            <div class="admin-tabs-pill" style="overflow-x: auto; max-width: 100%; white-space: nowrap;">
                <button class="tab-pill-btn active" id="tab-active" onclick="switchInboxTab('active')">Active</button>
                <button class="tab-pill-btn" id="tab-resolved" onclick="switchInboxTab('resolved')">Resolved</button>
                <button class="tab-pill-btn" id="tab-archived" onclick="switchInboxTab('archived')">Archived</button>
            </div>
        </div>
        <div class="header-right-group">
            <div class="search-pill">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="message-search" placeholder="Search..." onkeyup="filterMessages()">
            </div>
        </div>
    </div>

    <?php if(isset($_GET['success'])): 
        $adminMessages = [
            'reply_sent' => 'Your official response has been transmitted to the client successfully.',
            'status_updated' => 'The ticket status has been successfully updated in the system records.',
            'message_archived' => 'The ticket has been formally moved to the administrative archives.',
            'message_unarchive' => 'The ticket has been successfully restored to the active support queue.',
            'message_deleted' => 'The record has been permanently purged from the database.'
        ];
        $msgKey = $_GET['success'];
        $displayMsg = isset($adminMessages[$msgKey]) ? $adminMessages[$msgKey] : str_replace('_', ' ', $msgKey);
    ?>
        <div class="alert alert-success-proper" id="success-alert">
            <div class="alert-icon-circle">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            </div>
            <span class="alert-text"><?= $displayMsg ?></span>
            <button class="alert-close-btn" onclick="closeSuccessAlert()">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
            </button>
            <div class="alert-progress-bar"></div>
        </div>
        <script>
            function closeSuccessAlert() {
                const alert = document.getElementById('success-alert');
                if(alert) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(-50%) translateY(-20px)';
                    alert.style.transition = 'all 0.5s ease';
                    setTimeout(() => alert.remove(), 500);
                }
            }
            setTimeout(() => {
                closeSuccessAlert();
                const url = new URL(window.location);
                url.searchParams.delete('success');
                window.history.replaceState({}, document.title, url);
            }, 3000);
        </script>
    <?php endif; ?>

    <!-- Main Messenger Layout -->
    <div class="messenger-wrapper" id="messenger-main">
        <!-- Sidebar: Ticket List -->
        <aside class="messenger-sidebar active-panel" id="inbox-sidebar">
            <div class="sidebar-label">Conversations</div>
            <div class="ticket-scroller custom-scrollbar" style="display: flex; flex-direction: column;">
                <?php if(empty($data['messages'])): ?>
                    <div class="empty-tickets">
                        <div class="empty-tickets-icon">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        </div>
                        <h4>No tickets found</h4>
                        <p>All clear! There are no pending inquiries in this category.</p>
                    </div>
                <?php else: ?>
                    <?php foreach($data['messages'] as $msg): ?>
                        <?php 
                            $rowStatus = ($msg['status'] == 'resolved' ? 'resolved' : 'active');
                            $isVisible = (!$msg['is_archived_admin'] && $rowStatus == 'active');
                        ?>
                        <div class="ticket-row <?= $msg['is_read_admin'] == '0' ? 'unread' : '' ?>" 
                             data-status="<?= $rowStatus ?>" 
                             data-archived="<?= $msg['is_archived_admin'] ?>"
                             onclick="showDetail(<?= htmlspecialchars(json_encode($msg)) ?>, this)" 
                             style="display: <?= $isVisible ? 'flex' : 'none' ?>;">
                            <div class="ticket-row-header">
                                <span class="t-id"><?= $msg['ticket_number'] ?: '#TCK-OLD' ?></span>
                                <div style="display:flex; align-items:center; gap:6px;">
                                    <?php if($msg['is_read_admin'] == '0'): ?>
                                        <span class="admin-unread-indicator"></span>
                                    <?php endif; ?>
                                    <span class="t-status-dot <?= $rowStatus ?>"></span>
                                </div>
                            </div>
                            <div class="t-customer"><?= htmlspecialchars($msg['user_name']) ?></div>
                            <div class="t-subject"><?= htmlspecialchars($msg['subject']) ?></div>
                            <div class="t-time"><?= date('M d, h:i A', strtotime($msg['updated_at'])) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </aside>

        <!-- Chat Area -->
        <main class="messenger-chat" id="inbox-content">
            <!-- No Selection State -->
            <div id="no-selection" class="chat-placeholder">
                <div class="placeholder-art">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                </div>
                <h3>Select a conversation</h3>
                <p>Choose a ticket from the left to start responding to customers.</p>
            </div>

            <!-- Active Chat State -->
            <div id="detail-view" class="chat-container" style="display: none;">
                <header class="chat-header">
                    <div class="chat-header-left">
                        <button class="mobile-back-btn" onclick="toggleInboxPanels(true)">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        </button>
                        <div class="chat-meta">
                            <div class="chat-title-group">
                                <span id="view-ticket-num" class="ticket-tag"></span>
                                <h4 id="view-subject"></h4>
                            </div>
                            <div id="view-user" class="chat-user-info"></div>
                        </div>
                    </div>
                    <div class="chat-header-actions">
                        <form action="/php/Webdev/public/admin/message_status" method="POST" class="status-form">
                            <input type="hidden" name="id" id="status-msg-id">
                            <select name="status" id="view-status-select">
                                <option value="active">Active</option>
                                <option value="resolved">Resolved</option>
                            </select>
                            <button type="submit" class="status-save-btn">Update</button>
                        </form>
                        <div class="action-divider"></div>
                        <a id="archive-link" href="#" class="icon-action-btn" title="Archive">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8H3V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2z"></path><path d="M10 12h4"></path><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8"></path></svg>
                        </a>
                        <a id="delete-link" href="#" class="icon-action-btn danger" title="Delete" onclick="return confirm('Delete this ticket permanently?')">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        </a>
                    </div>
                </header>

                <div class="chat-body custom-scrollbar" id="chat-thread">
                    <!-- Messages will be injected here -->
                </div>

                <footer class="chat-footer">
                    <form action="/php/Webdev/public/admin/reply" method="POST" class="reply-form">
                        <input type="hidden" name="id" id="reply-msg-id">
                        <div class="reply-input-wrapper">
                            <textarea name="reply" required placeholder="Write a response..." rows="1" id="reply-textarea"></textarea>
                            <button type="submit" class="send-btn">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            </button>
                        </div>
                    </form>
                </footer>
            </div>
        </main>
    </div>
</div>

<style>
/* Responsive Core */
.inbox-page-container { display: flex; flex-direction: column; height: calc(100vh - 140px); gap: 1.5rem; }
.inbox-header-section { display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; }
.page-title { font-size: 1.5rem; font-weight: 800; color: var(--admin-text-main); margin: 0; }
.admin-tabs-pill { display: flex; background: var(--admin-bg-soft); padding: 4px; border-radius: 12px; border: 1px solid var(--admin-border); }
.tab-pill-btn { padding: 6px 16px; border: none; background: transparent; color: var(--admin-text-muted); font-weight: 700; font-size: 0.85rem; cursor: pointer; border-radius: 8px; transition: all 0.2s; }
.tab-pill-btn.active { background: var(--admin-card); color: var(--admin-accent); box-shadow: var(--shadow-sm); }
.search-pill { display: flex; align-items: center; gap: 10px; background: var(--admin-card); border: 2px solid var(--admin-border); padding: 8px 16px; border-radius: 99px; width: 320px; color: var(--admin-text-muted); }
.search-pill input { border: none; background: transparent; color: var(--admin-text-main); width: 100%; font-weight: 500; outline: none; }
.messenger-wrapper { display: flex; flex: 1; background: var(--admin-card); border: 1px solid var(--admin-border); border-radius: 20px; overflow: hidden; position: relative; }
.messenger-sidebar { width: 340px; border-right: 1px solid var(--admin-border); display: flex; flex-direction: column; background: var(--admin-bg-soft); transition: transform 0.3s ease; }
.sidebar-label { padding: 16px 24px; font-size: 0.7rem; font-weight: 900; text-transform: uppercase; letter-spacing: 0.1em; color: var(--admin-text-muted); border-bottom: 1px solid var(--admin-border); }
.ticket-scroller { flex: 1; overflow-y: auto; }
.ticket-row { padding: 20px 24px; border-bottom: 1px solid var(--admin-border); cursor: pointer; display: flex; flex-direction: column; gap: 4px; transition: all 0.2s; }
.ticket-row:hover { background: var(--admin-card); }
.ticket-row.active { background: var(--admin-card); border-left: 4px solid var(--admin-accent); padding-left: 20px; }
.ticket-row.unread { background: var(--admin-card); }
.ticket-row.unread .t-customer { font-weight: 900; }
.ticket-row-header { display: flex; justify-content: space-between; align-items: center; }
.t-id { font-size: 0.75rem; font-weight: 900; color: var(--admin-accent); }
.t-status-dot { width: 8px; height: 8px; border-radius: 50%; }
.t-status-dot.active { background: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1); }
.t-status-dot.resolved { background: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }
.admin-unread-indicator { width: 8px; height: 8px; background: #3b82f6; border-radius: 50%; }
.t-customer { font-size: 1rem; font-weight: 800; color: var(--admin-text-main); }
.t-subject { font-size: 0.85rem; font-weight: 600; color: var(--admin-text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.t-time { font-size: 0.7rem; font-weight: 700; color: var(--admin-text-muted); margin-top: 4px; }
.messenger-chat { flex: 1; display: flex; flex-direction: column; background: var(--admin-card); }
.chat-placeholder { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; padding: 40px; color: var(--admin-text-muted); }
.placeholder-art { width: 80px; height: 80px; border-radius: 50%; background: var(--admin-bg-soft); display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem; border: 2px dashed var(--admin-border); }
.chat-container { display: flex; flex-direction: column; height: 100%; }
.chat-header { padding: 20px 32px; border-bottom: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center; }
.chat-header-left { display: flex; align-items: center; gap: 1rem; }
.chat-title-group { display: flex; align-items: center; gap: 12px; }
.ticket-tag { font-size: 0.9rem; font-weight: 900; color: var(--admin-accent); }
.chat-header h4 { font-size: 1.25rem; font-weight: 800; color: var(--admin-text-main); margin: 0; }
.chat-user-info { font-size: 0.9rem; font-weight: 600; color: var(--admin-text-muted); margin-top: 2px; }
.chat-header-actions { display: flex; align-items: center; gap: 8px; }
.status-form { display: flex; background: var(--admin-bg-soft); padding: 4px; border-radius: 10px; border: 1px solid var(--admin-border); }
.status-form select { background: var(--admin-bg-soft); border: none; color: var(--admin-text-main); font-weight: 700; font-size: 0.85rem; padding: 4px 8px; outline: none; cursor: pointer; border-radius: 6px; }
.status-save-btn { background: var(--admin-text-main); color: var(--admin-sidebar); border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.75rem; font-weight: 800; cursor: pointer; }
.action-divider { width: 1px; height: 24px; background: var(--admin-border); margin: 0 4px; }
.icon-action-btn { padding: 8px; border-radius: 8px; color: var(--admin-text-muted); transition: all 0.2s; }
.icon-action-btn:hover { background: var(--admin-bg-soft); color: var(--admin-text-main); }
.icon-action-btn.danger:hover { color: var(--admin-danger); background: rgba(239, 68, 68, 0.1); }
.chat-body { flex: 1; padding: 32px; overflow-y: auto; display: flex; flex-direction: column; gap: 1.5rem; background: var(--admin-bg); }
.msg-group { display: flex; flex-direction: column; max-width: 80%; }
.msg-group.admin { align-self: flex-end; align-items: flex-end; }
.msg-group.user { align-self: flex-start; align-items: flex-start; }
.bubble { padding: 16px 20px; border-radius: 20px; font-size: 1rem; line-height: 1.5; font-weight: 500; box-shadow: var(--shadow-sm); }
.admin .bubble { background: var(--admin-text-main); color: var(--admin-sidebar); border-radius: 20px 20px 4px 20px; }
.user .bubble { background: var(--admin-card); color: var(--admin-text-main); border: 1px solid var(--admin-border); border-radius: 20px 20px 20px 4px; }
.msg-meta { margin-top: 8px; font-size: 0.7rem; font-weight: 800; color: var(--admin-text-muted); text-transform: uppercase; letter-spacing: 0.05em; }
.chat-footer { padding: 24px 32px; border-top: 1px solid var(--admin-border); background: var(--admin-card); }
.reply-input-wrapper { display: flex; align-items: flex-end; gap: 12px; background: var(--admin-bg-soft); border: 2px solid var(--admin-border); padding: 8px 16px; border-radius: 24px; transition: border-color 0.2s; }
.reply-input-wrapper:focus-within { border-color: var(--admin-accent); }
.reply-input-wrapper textarea { flex: 1; background: transparent; border: none; resize: none; padding: 8px 0; color: var(--admin-text-main); font-family: inherit; font-size: 1rem; font-weight: 500; outline: none; max-height: 120px; }
.send-btn { background: var(--admin-text-main); color: var(--admin-sidebar); border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: transform 0.2s; }
.send-btn:hover { transform: scale(1.1); }
.mobile-back-btn { display: none; background: transparent; border: none; color: var(--admin-text-main); cursor: pointer; }
@media (max-width: 850px) { 
    .messenger-sidebar { 
        position: absolute; 
        inset: 0; 
        width: 100%; 
        z-index: 20; 
        display: none;
    } 
    .messenger-sidebar.active-panel {
        display: flex;
    }
    .mobile-back-btn { 
        display: block; 
    } 
    .messenger-chat { 
        width: 100%; 
        display: none;
    } 
    .messenger-chat.active-panel {
        display: flex;
    }
}
.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: var(--admin-border); border-radius: 10px; }
.empty-tickets { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 40px 24px; text-align: center; color: var(--admin-text-muted); }
.empty-tickets-icon { width: 60px; height: 60px; background: var(--admin-bg); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 16px; color: var(--admin-border); border: 2px dashed var(--admin-border); }
.empty-tickets h4 { color: var(--admin-text-main); font-size: 1rem; font-weight: 800; margin-bottom: 8px; }
.empty-tickets p { font-size: 0.8rem; line-height: 1.5; font-weight: 600; }
</style>

<script>
let lastReplyIdAdmin = 0;
let adminPollingInterval = null;

function showDetail(msg, el) {
    if(adminPollingInterval) clearInterval(adminPollingInterval);
    lastReplyIdAdmin = 0;

    // Mark as read visually
    if(el.classList.contains('unread')) {
        el.classList.remove('unread');
        const indicator = el.querySelector('.admin-unread-indicator');
        if(indicator) indicator.remove();
        fetch(`/php/Webdev/public/admin/mark_as_read/${msg.id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    }

    document.querySelectorAll('.ticket-row').forEach(row => row.classList.remove('active'));
    el.classList.add('active');

    document.getElementById('no-selection').style.display = 'none';
    document.getElementById('detail-view').style.display = 'flex';

    // Toggle panels: show chat, hide sidebar on mobile
    toggleInboxPanels(false); // <--- Changed here

    document.getElementById('view-ticket-num').innerText = msg.ticket_number || '#TCK-OLD';
    document.getElementById('view-subject').innerText = msg.subject;
    document.getElementById('view-user').innerText = msg.user_name + ' • ' + msg.user_email;
    document.getElementById('status-msg-id').value = msg.id;
    document.getElementById('reply-msg-id').value = msg.id;
    document.getElementById('view-status-select').value = (msg.status == 'resolved' ? 'resolved' : 'active');

    const aLink = document.getElementById('archive-link');
    if(msg.is_archived_admin == '1') {
        aLink.href = '/php/Webdev/public/admin/message_unarchive/' + msg.id;
        aLink.title = 'Unarchive';
    } else {
        aLink.href = '/php/Webdev/public/admin/message_archive/' + msg.id;
        aLink.title = 'Archive';
    }
    document.getElementById('delete-link').href = '/php/Webdev/public/admin/message_delete/' + msg.id;

    const thread = document.getElementById('chat-thread');
    thread.innerHTML = '';

    const initialDiv = document.createElement('div');
    initialDiv.className = 'msg-group user';
    initialDiv.innerHTML = `<div class="bubble">${msg.message.replace(/\n/g, '<br>')}</div><div class="msg-meta">${new Date(msg.created_at).toLocaleString([], {month:'short', day:'numeric', hour:'2-digit', minute:'2-digit'})}</div>`;
    thread.appendChild(initialDiv);

    if (msg.replies && msg.replies.length > 0) {
        msg.replies.forEach(reply => {
            const rid = parseInt(reply.id);
            if(rid > lastReplyIdAdmin) lastReplyIdAdmin = rid;
            const isAdmin = (reply.sender_role !== 'user');
            const div = document.createElement('div');
            div.className = 'msg-group ' + (isAdmin ? 'admin' : 'user');
            div.innerHTML = `<div class="bubble">${reply.reply_text.replace(/\n/g, '<br>')}</div><div class="msg-meta">${isAdmin ? 'Support' : reply.sender_name} • ${new Date(reply.created_at).toLocaleString([], {hour:'2-digit', minute:'2-digit', month:'short', day:'numeric'})}</div>`;
            thread.appendChild(div);
        });
    }

    setTimeout(() => { thread.scrollTop = thread.scrollHeight; }, 50);

    adminPollingInterval = setInterval(() => {
        fetch(`/php/Webdev/public/admin/fetch_new_replies/${msg.id}/${lastReplyIdAdmin}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            if(data.success && data.replies.length > 0) {
                data.replies.forEach(reply => {
                    const rid = parseInt(reply.id);
                    if(rid > lastReplyIdAdmin) {
                        lastReplyIdAdmin = rid;
                        const isAdmin = (reply.sender_role !== 'user');
                        const div = document.createElement('div');
                        div.className = 'msg-group ' + (isAdmin ? 'admin' : 'user');
                        div.innerHTML = `<div class="bubble">${reply.reply_text.replace(/\n/g, '<br>')}</div><div class="msg-meta">${isAdmin ? 'Support' : reply.sender_name} • ${new Date(reply.created_at).toLocaleString([], {hour:'2-digit', minute:'2-digit', month:'short', day:'numeric'})}</div>`;
                        thread.appendChild(div);
                        thread.scrollTop = thread.scrollHeight;
                    }
                });
            }
        });
    }, 4000);
}

// Function to toggle between sidebar and chat view on mobile
function toggleInboxPanels(showSidebar) {
    const sidebar = document.getElementById('inbox-sidebar');
    const chat = document.getElementById('inbox-content');

    if (window.innerWidth <= 850) { // Only apply on mobile
        if (showSidebar) {
            sidebar.classList.add('active-panel');
            chat.classList.remove('active-panel');
        } else {
            sidebar.classList.remove('active-panel');
            chat.classList.add('active-panel');
        }
    }
}

// Ensure toggleSidebar is called correctly by the mobile-back-btn
document.querySelector('.mobile-back-btn')?.addEventListener('click', () => toggleInboxPanels(true)); // <--- New event listener

function switchInboxTab(tab) {
    document.querySelectorAll('.tab-pill-btn').forEach(btn => btn.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    const rows = document.querySelectorAll('.ticket-row');
    rows.forEach(row => {
        const isArchived = (row.dataset.archived === '1');
        const status = row.dataset.status;
        if (tab === 'archived') row.style.display = isArchived ? 'flex' : 'none';
        else row.style.display = (!isArchived && status === tab) ? 'flex' : 'none';
    });
    document.getElementById('no-selection').style.display = 'flex';
    document.getElementById('detail-view').style.display = 'none';
    if (window.innerWidth <= 850) toggleInboxPanels(true); // Show sidebar when switching tabs on mobile
}

function filterMessages() {
    const q = document.getElementById('message-search').value.toLowerCase();
    const rows = document.querySelectorAll('.ticket-row');
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(q) ? 'flex' : 'none';
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelector('.reply-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const replyText = formData.get('reply');
        fetch(form.action, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                form.reset();
                const thread = document.getElementById('chat-thread');
                const div = document.createElement('div');
                div.className = 'msg-group admin';
                div.innerHTML = `<div class="bubble">${replyText.replace(/\n/g, '<br>')}</div><div class="msg-meta">YOU • JUST NOW</div>`;
                thread.appendChild(div);
                thread.scrollTop = thread.scrollHeight;
                const activeItem = document.querySelector('.ticket-row.active');
                if (activeItem) {
                    activeItem.querySelector('.t-status-dot').className = 't-status-dot active';
                    activeItem.dataset.status = 'active';
                }
                showDynamicAlert('Your official response has been transmitted successfully.');
            } else alert('Failed to send reply.');
        });
    });
});

function showDynamicAlert(message) {
    const alertHtml = `<div class="alert alert-success-proper" id="success-alert"><div class="alert-icon-circle"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg></div><span class="alert-text">${message}</span><button class="alert-close-btn" onclick="closeSuccessAlert()"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg></button><div class="alert-progress-bar"></div></div>`;
    const div = document.createElement('div');
    div.innerHTML = alertHtml.trim();
    document.body.appendChild(div.firstChild);
    setTimeout(() => { closeSuccessAlert(); }, 3000);
}

function closeSuccessAlert() {
    const alert = document.getElementById('success-alert');
    if(alert) {
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(-50%) translateY(-20px)';
        alert.style.transition = 'all 0.5s ease';
        setTimeout(() => alert.remove(), 500);
    }
}
</script>