<div class="container py-8">
    <div class="profile-layout">
        <!-- Sidebar -->
        <aside class="profile-sidebar">
            <div class="user-avatar-section">
                <div class="avatar-wrapper">
                    <?php if($data['user']['profile_picture']): ?>
                        <img src="/php/Webdev/public/<?= $data['user']['profile_picture'] ?>" alt="Avatar" id="avatar-preview">
                    <?php else: ?>
                        <div class="avatar-placeholder"><?= strtoupper(substr($data['user']['name'], 0, 1)) ?></div>
                    <?php endif; ?>
                </div>
                <h2 class="user-name"><?= $data['user']['name'] ?></h2>
                <p class="user-email"><?= $data['user']['email'] ?></p>
            </div>
            
            <nav class="profile-nav">
                <a href="/php/Webdev/public/profile">Account Settings</a>
                <a href="/php/Webdev/public/profile/orders">My Orders</a>
                <a href="/php/Webdev/public/profile/inbox" class="active">My Inbox</a>
                <a href="/php/Webdev/public/auth/logout" class="logout-link">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="profile-content" style="padding: 0; overflow: hidden;">
            <div class="inbox-header-row">
                <div class="header-text">
                    <h3>Support Inbox</h3>
                    <p class="text-muted">Conversations with our support team.</p>
                </div>
                <div class="inbox-tabs-wrapper" style="display: flex; gap: 10px; align-items: center;">
                    <div style="display: flex; background: #f1f5f9; padding: 4px; border-radius: 12px;">
                        <button onclick="filterUserInbox('active')" id="tab-active" class="tab-btn active">Active</button>
                        <button onclick="filterUserInbox('resolved')" id="tab-resolved" class="tab-btn">Resolved</button>
                        <button onclick="filterUserInbox('archived')" id="tab-archived" class="tab-btn">Archived</button>
                    </div>
                    <button onclick="document.getElementById('new-ticket-modal').classList.add('active')" class="btn-new-ticket">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        New Ticket
                    </button>
                </div>
            </div>

            <?php if(empty($data['messages'])): ?>
                <div class="empty-state-messenger">
                    <div class="empty-icon-circle">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    </div>
                    <h3>No Support Tickets Yet</h3>
                    <p>Your support history is currently empty. If you have any concerns or questions, we're here to help.</p>
                    <a href="javascript:void(0)" onclick="document.getElementById('new-ticket-modal').classList.add('active')" class="btn-pill-primary">Start a Conversation</a>
                </div>
            <?php else: ?>
                <div class="messenger-layout" id="user-messenger">
                    <!-- Conversations Sidebar -->
                    <div class="messenger-sidebar">
                        <div class="sidebar-header">Tickets</div>
                        <div class="ticket-list custom-scrollbar">
                            <?php foreach($data['messages'] as $msg): ?>
                                <?php 
                                    $rowStatus = ($msg['status'] == 'resolved' ? 'resolved' : 'active');
                                    $isVisible = (!$msg['is_archived_user'] && $rowStatus == 'active');
                                ?>
                                <div class="ticket-item <?= $msg['is_read_user'] == '0' ? 'unread' : '' ?>" 
                                     data-status="<?= $rowStatus ?>" 
                                     data-archived="<?= $msg['is_archived_user'] ?>"
                                     onclick="showUserTicket(<?= htmlspecialchars(json_encode($msg)) ?>, this)"
                                     style="display: <?= $isVisible ? 'flex' : 'none' ?>;">
                                    <div class="ticket-item-info">
                                        <div class="ticket-meta">
                                            <span class="ticket-id"><?= $msg['ticket_number'] ?: '#TCK-OLD' ?></span>
                                            <div style="display:flex; align-items:center; gap:6px;">
                                                <?php if($msg['is_read_user'] == '0'): ?>
                                                    <span class="unread-indicator"></span>
                                                <?php endif; ?>
                                                <span class="status-dot <?= $rowStatus ?>"></span>
                                            </div>
                                        </div>
                                        <div class="ticket-title"><?= htmlspecialchars($msg['subject']) ?></div>
                                        <div class="ticket-date"><?= date('M d, Y', strtotime($msg['updated_at'])) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Conversation Detail -->
                    <div class="messenger-view">
                        <div id="no-ticket-selected" class="view-placeholder">
                            <div class="placeholder-icon">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                            </div>
                            <p>Select a ticket to view conversation</p>
                        </div>

                        <div id="ticket-detail-view" style="display: none; flex-direction: column; height: 100%;">
                            <div class="detail-header">
                                <div class="detail-info">
                                    <div style="display:flex; align-items:center; gap:10px;">
                                        <button class="back-list-btn" onclick="toggleMessengerSidebar(true)">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                                        </button>
                                        <h4 id="view-subject"></h4>
                                    </div>
                                    <span id="view-status-pill" class="status-pill"></span>
                                </div>
                                <div class="detail-actions">
                                    <a id="archive-link" href="#" class="action-icon-btn" title="Archive">
                                        <svg id="archive-icon-svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8H3V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2z"></path><path d="M10 12h4"></path><path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8"></path></svg>
                                        <svg id="unarchive-icon-svg" style="display:none;" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 10h10a8 8 0 0 1 8 8v2M3 10l6 6m-6-6 6-6"></path></svg>
                                    </a>
                                    <a id="delete-link" href="#" class="action-icon-btn delete" onclick="return confirm('Permanently delete this ticket?')" title="Delete">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg>
                                    </a>
                                </div>
                            </div>

                            <div class="chat-thread custom-scrollbar" id="chat-thread-container">
                                <!-- Thread will be populated by JS -->
                            </div>

                            <div class="chat-footer">
                                <form id="reply-ticket-form" action="/php/Webdev/public/profile/reply_ticket" method="POST">
                                    <input type="hidden" name="id" id="reply-ticket-id">
                                    <div class="input-container">
                                        <input type="text" name="reply" required placeholder="Type a message..." autocomplete="off">
                                        <button type="submit">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                                        </button>
                                    </div>
                                </form>
                                <div id="resolved-note" class="resolved-note" style="display:none;">
                                    Ticket is resolved. Sending a message will reopen it.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>

<style>
/* Header */
.inbox-header-row { padding: 24px 32px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: white; }
.header-text h3 { margin: 0; font-size: 1.5rem; font-weight: 800; border: none !important; padding: 0 !important; }
.text-muted { color: #64748b; font-size: 0.9rem; margin-top: 4px; }

/* Tabs */
.tab-btn { padding: 8px 18px; border-radius: 8px; border: none; font-size: 0.85rem; font-weight: 700; cursor: pointer; background: transparent; color: #64748b; transition: all 0.2s; }
.tab-btn.active { background: white; color: #000; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }

/* Messenger Action Button */
.btn-new-ticket { display: flex; align-items: center; gap: 8px; background: #000; color: white; border: none; padding: 10px 20px; border-radius: 99px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: all 0.2s; margin-left: 5px; }
.btn-new-ticket:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.15); background: #1e293b; }

/* Messenger Layout */
.messenger-layout { display: flex; height: 600px; background: white; }

/* Messenger Sidebar (Inner) */
.messenger-sidebar { width: 300px; border-right: 1px solid #f1f5f9; display: flex; flex-direction: column; background: #fafafa; }
.sidebar-header { padding: 16px 24px; font-size: 0.75rem; font-weight: 900; text-transform: uppercase; color: #94a3b8; letter-spacing: 0.1em; border-bottom: 1px solid #f1f5f9; }
.ticket-list { flex: 1; overflow-y: auto; }

.ticket-item { padding: 20px 24px; border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: all 0.2s; display: flex; flex-direction: column; gap: 6px; }
.ticket-item:hover { background: #f1f5f9; }
.ticket-item.active { background: white; border-left: 4px solid #000; padding-left: 20px; }
.ticket-item.unread { background: #f8fafc; }
.ticket-item.unread .ticket-title { font-weight: 800; color: #000; }

.ticket-meta { display: flex; justify-content: space-between; align-items: center; }
.ticket-id { font-size: 0.75rem; font-weight: 800; color: #94a3b8; }
.status-dot { width: 8px; height: 8px; border-radius: 50%; }
.status-dot.active { background: #f59e0b; box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1); }
.status-dot.resolved { background: #10b981; box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1); }

.unread-indicator { width: 8px; height: 8px; background: #3b82f6; border-radius: 50%; }

.ticket-title { font-size: 0.95rem; font-weight: 700; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.ticket-date { font-size: 0.75rem; color: #94a3b8; font-weight: 600; }

/* Messenger View */
.messenger-view { flex: 1; display: flex; flex-direction: column; background: white; position: relative; }
.view-placeholder { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #94a3b8; gap: 1rem; }
.placeholder-icon { width: 64px; height: 64px; border-radius: 50%; background: #f8fafc; display: flex; align-items: center; justify-content: center; border: 2px dashed #e2e8f0; }

/* Detail Header */
.detail-header { padding: 20px 32px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center; background: white; }
.detail-info h4 { margin: 0; font-size: 1.15rem; font-weight: 800; color: #0f172a; }
.status-pill { margin-top: 6px; display: inline-block; font-size: 0.65rem; font-weight: 900; text-transform: uppercase; padding: 3px 10px; border-radius: 99px; letter-spacing: 0.05em; }
.pill-active { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
.pill-resolved { background: #ecfdf5; color: #059669; border: 1px solid #10b981; }

.back-list-btn { display: none; background: #f1f5f9; border: none; padding: 6px; border-radius: 8px; cursor: pointer; color: #0f172a; }

.detail-actions { display: flex; gap: 8px; }
.action-icon-btn { padding: 8px; border-radius: 10px; background: #f1f5f9; color: #64748b; transition: all 0.2s; display: flex; align-items: center; justify-content: center; }
.action-icon-btn:hover { background: #e2e8f0; color: #000; transform: translateY(-2px); }
.action-icon-btn.delete:hover { background: #fee2e2; color: #dc2626; }

/* Chat Thread */
.chat-thread { flex: 1; padding: 32px; overflow-y: auto; background: #fdfdfd; display: flex; flex-direction: column; gap: 24px; }
.msg-group { display: flex; flex-direction: column; max-width: 85%; }
.msg-group.me { align-self: flex-end; align-items: flex-end; }
.msg-group.support { align-self: flex-start; align-items: flex-start; }

.bubble { padding: 14px 20px; border-radius: 18px; font-size: 0.95rem; line-height: 1.5; font-weight: 500; }
.me .bubble { background: #0f172a; color: white; border-radius: 18px 18px 4px 18px; }
.support .bubble { background: #f1f5f9; color: #0f172a; border-radius: 18px 18px 18px 4px; border: 1px solid #e2e8f0; }

.msg-time { margin-top: 6px; font-size: 0.65rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; }

/* Footer */
.chat-footer { padding: 24px 32px; border-top: 1px solid #f1f5f9; background: white; }
.input-container { display: flex; gap: 12px; align-items: center; }
.input-container input { flex: 1; padding: 12px 24px; border-radius: 99px; border: 2px solid #f1f5f9; outline: none; font-size: 0.95rem; font-weight: 500; transition: all 0.2s; }
.input-container input:focus { border-color: #000; background: white; }
.input-container button { width: 48px; height: 48px; border-radius: 50%; background: #000; color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.input-container button:hover { transform: scale(1.05); background: #1e293b; }

.resolved-note { margin-top: 12px; font-size: 0.75rem; font-weight: 800; color: #b45309; text-transform: uppercase; text-align: center; }

/* Empty State Messenger */
.empty-state-messenger { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 60px 40px; text-align: center; background: #fdfdfd; }
.empty-icon-circle { width: 80px; height: 80px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 24px; color: #e2e8f0; border: 2px dashed #e2e8f0; }
.empty-state-messenger h3 { font-size: 1.5rem; font-weight: 800; color: #0f172a; margin-bottom: 12px; }
.empty-state-messenger p { font-size: 1rem; color: #64748b; max-width: 400px; margin-bottom: 30px; line-height: 1.6; }
.btn-pill-primary { background: #000; color: white; padding: 12px 32px; border-radius: 99px; font-size: 0.95rem; font-weight: 700; transition: all 0.2s; text-decoration: none; display: inline-block; }
.btn-pill-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); background: #1e293b; }

/* New Ticket Modal */
.modal-overlay { position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(8px); z-index: 9999; display: none; align-items: center; justify-content: center; padding: 20px; }
.modal-overlay.active { display: flex; animation: modalFadeIn 0.3s ease; }
@keyframes modalFadeIn { from { opacity: 0; } to { opacity: 1; } }

/* Responsive Messenger */
@media (max-width: 992px) {
    .messenger-sidebar { width: 100%; position: absolute; inset: 0; z-index: 10; }
    .back-list-btn { display: block; }
}

.custom-scrollbar::-webkit-scrollbar { width: 6px; }
.custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
</style>

<script>
let lastReplyId = 0;
let pollingInterval = null;

function showUserTicket(msg, el) {
    if(pollingInterval) clearInterval(pollingInterval);
    lastReplyId = 0;

    // Mark as read visually
    if(el.classList.contains('unread')) {
        el.classList.remove('unread');
        const indicator = el.querySelector('.unread-indicator');
        if(indicator) indicator.remove();
        
        // Call server to mark as read
        fetch(`/php/Webdev/public/profile/mark_as_read/${msg.id}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    }

    document.querySelectorAll('.ticket-item').forEach(item => item.classList.remove('active'));
    el.classList.add('active');
    document.getElementById('no-ticket-selected').style.display = 'none';
    const detail = document.getElementById('ticket-detail-view');
    detail.style.display = 'flex';
    if (window.innerWidth <= 992) toggleMessengerSidebar(false);
    document.getElementById('view-subject').innerText = msg.subject;
    document.getElementById('reply-ticket-id').value = msg.id;
    const pill = document.getElementById('view-status-pill');
    const isRes = (msg.status === 'resolved');
    pill.innerText = isRes ? 'Resolved' : 'Active';
    pill.className = 'status-pill ' + (isRes ? 'pill-resolved' : 'pill-active');
    document.getElementById('resolved-note').style.display = isRes ? 'block' : 'none';
    const aLink = document.getElementById('archive-link');
    const archIcon = document.getElementById('archive-icon-svg');
    const unarchIcon = document.getElementById('unarchive-icon-svg');
    if (msg.is_archived_user == '1') {
        aLink.href = '/php/Webdev/public/profile/message_unarchive/' + msg.id;
        archIcon.style.display = 'none';
        unarchIcon.style.display = 'block';
        aLink.title = "Unarchive";
    } else {
        aLink.href = '/php/Webdev/public/profile/message_archive/' + msg.id;
        archIcon.style.display = 'block';
        unarchIcon.style.display = 'none';
        aLink.title = "Archive";
    }
    document.getElementById('delete-link').href = '/php/Webdev/public/profile/message_delete/' + msg.id;
    const thread = document.getElementById('chat-thread-container');
    thread.innerHTML = '';
    const initialDiv = document.createElement('div');
    initialDiv.className = 'msg-group me';
    initialDiv.innerHTML = `<div class="bubble">${msg.message.replace(/\n/g, '<br>')}</div><span class="msg-time">${new Date(msg.created_at).toLocaleString([], {month:'short', day:'numeric', hour:'2-digit', minute:'2-digit'})}</span>`;
    thread.appendChild(initialDiv);
    if (msg.replies && msg.replies.length > 0) {
        msg.replies.forEach(reply => {
            const rid = parseInt(reply.id);
            if(rid > lastReplyId) lastReplyId = rid;
            const isMe = (reply.sender_role === 'user');
            const div = document.createElement('div');
            div.className = 'msg-group ' + (isMe ? 'me' : 'support');
            div.innerHTML = `<div class="bubble">${reply.reply_text.replace(/\n/g, '<br>')}</div><span class="msg-time">${isMe ? 'You' : 'Support'} • ${new Date(reply.created_at).toLocaleString([], {month:'short', day:'numeric', hour:'2-digit', minute:'2-digit'})}</span>`;
            thread.appendChild(div);
        });
    }
    setTimeout(() => { thread.scrollTop = thread.scrollHeight; }, 50);

    pollingInterval = setInterval(() => {
        fetch(`/php/Webdev/public/profile/fetch_new_replies/${msg.id}/${lastReplyId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(res => res.json())
        .then(data => {
            if(data.success && data.replies.length > 0) {
                data.replies.forEach(reply => {
                    const rid = parseInt(reply.id);
                    if(rid > lastReplyId) {
                        lastReplyId = rid;
                        const isMe = (reply.sender_role === 'user');
                        const div = document.createElement('div');
                        div.className = 'msg-group ' + (isMe ? 'me' : 'support');
                        div.innerHTML = `<div class="bubble">${reply.reply_text.replace(/\n/g, '<br>')}</div><span class="msg-time">${isMe ? 'You' : 'Support'} • ${new Date(reply.created_at).toLocaleString([], {month:'short', day:'numeric', hour:'2-digit', minute:'2-digit'})}</span>`;
                        thread.appendChild(div);
                        thread.scrollTop = thread.scrollHeight;
                    }
                });
            }
        });
    }, 4000);
}

function toggleMessengerSidebar(show) {
    const sidebar = document.querySelector('.messenger-sidebar');
    sidebar.style.display = show ? 'flex' : 'none';
}

function filterUserInbox(tab) {
    document.querySelectorAll('.inbox-tabs-wrapper .tab-btn').forEach(t => t.classList.remove('active'));
    document.getElementById('tab-' + tab).classList.add('active');
    const items = document.querySelectorAll('.ticket-item');
    items.forEach(item => {
        const isArchived = (item.dataset.archived === '1');
        const status = item.dataset.status;
        if (tab === 'archived') item.style.display = isArchived ? 'flex' : 'none';
        else if (tab === 'active') item.style.display = (!isArchived && status === 'active') ? 'flex' : 'none';
        else if (tab === 'resolved') item.style.display = (!isArchived && status === 'resolved') ? 'flex' : 'none';
    });
    document.getElementById('no-ticket-selected').style.display = 'flex';
    document.getElementById('ticket-detail-view').style.display = 'none';
    if (window.innerWidth <= 992) toggleMessengerSidebar(true);
}

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
        alert.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
        setTimeout(() => alert.remove(), 400);
    }
}

function appendTicketToSidebar(ticket) {
    const list = document.querySelector('.ticket-list');
    if (!list) return;
    const item = document.createElement('div');
    item.className = 'ticket-item active';
    item.dataset.status = 'active';
    item.dataset.archived = '0';
    item.onclick = function() { showUserTicket(ticket, this); };
    item.innerHTML = `<div class="ticket-item-info"><div class="ticket-meta"><span class="ticket-id">${ticket.ticket_number || '#TCK-NEW'}</span><span class="status-dot active"></span></div><div class="ticket-title">${ticket.subject}</div><div class="ticket-date">${new Date().toLocaleDateString('en-US', {month:'short', day:'numeric', year:'numeric'})}</div></div>`;
    list.prepend(item);
    showUserTicket(ticket, item);
}

document.addEventListener('DOMContentLoaded', () => {
    document.getElementById('new-ticket-form-modal')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        fetch(form.action, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById('new-ticket-modal').classList.remove('active');
                form.reset();
                showDynamicAlert('Your concern has been successfully submitted to our support team.');
                if (document.querySelector('.empty-state-messenger')) { location.reload(); return; }
                appendTicketToSidebar(data.ticket);
            } else alert('Failed to submit ticket.');
        });
    });

    document.getElementById('reply-ticket-form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const replyText = formData.get('reply');
        fetch(form.action, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest' }, body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                form.reset();
                const thread = document.getElementById('chat-thread-container');
                const div = document.createElement('div');
                div.className = 'msg-group me';
                div.innerHTML = `<div class="bubble">${replyText.replace(/\n/g, '<br>')}</div><span class="msg-time">YOU • JUST NOW</span>`;
                thread.appendChild(div);
                thread.scrollTop = thread.scrollHeight;
                const pill = document.getElementById('view-status-pill');
                pill.innerText = 'Active';
                pill.className = 'status-pill pill-active';
                document.getElementById('resolved-note').style.display = 'none';
                const activeItem = document.querySelector('.ticket-item.active');
                if (activeItem) {
                    activeItem.querySelector('.status-dot').className = 'status-dot active';
                    activeItem.dataset.status = 'active';
                }
                showDynamicAlert('Your reply has been transmitted successfully.');
            } else alert('Failed to send reply.');
        });
    });
});
</script>

<!-- New Ticket Modal -->
<div id="new-ticket-modal" class="modal-overlay">
    <div class="glass-card" style="width: 100%; max-width: 500px; padding: 30px; background: white; position: relative; border-radius: 24px;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: #000;">New Support Ticket</h3>
            <button onclick="document.getElementById('new-ticket-modal').classList.remove('active')" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; color: #64748b;">&times;</button>
        </div>
        <form id="new-ticket-form-modal" action="/php/Webdev/public/help/send_concern" method="POST">
            <input type="hidden" name="redirect" value="profile/inbox">
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; margin-bottom: 8px; color: #0f172a; font-size: 0.9rem;">Subject Category</label>
                <select name="subject" required style="width: 100%; padding: 14px; border: 2px solid #f1f5f9; border-radius: 12px; background: #f8fafc; cursor: pointer; font-weight: 600; outline: none;">
                    <option value="" disabled selected>Select a category</option>
                    <option value="Order Inquiry">Order Inquiry</option>
                    <option value="Returns & Refunds">Returns & Refunds</option>
                    <option value="Account Issues">Account Issues</option>
                    <option value="Product Information">Product Information</option>
                    <option value="Technical Support">Technical Support</option>
                    <option value="Feedback">Feedback</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 700; margin-bottom: 8px; color: #0f172a; font-size: 0.9rem;">Describe your concern</label>
                <textarea name="message" required style="width: 100%; padding: 14px; border: 2px solid #f1f5f9; border-radius: 12px; height: 150px; font-family: inherit; background: #f8fafc; outline: none; resize: none; font-weight: 500;" placeholder="Provide as much detail as possible..."></textarea>
            </div>
            <button type="submit" class="btn-pill-primary" style="width: 100%; border: none; cursor: pointer; justify-content: center;">Submit Ticket</button>
        </form>
    </div>
</div>

<link rel="stylesheet" href="/php/Webdev/public/css/profile.css">
