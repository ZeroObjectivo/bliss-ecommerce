<div class="help-page">
    <div class="container" style="max-width: 900px; padding: 40px 20px;">
        <div style="margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center;">
            <a href="/php/Webdev/public/help/track_ticket" style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 8px; font-weight: 600;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                Back to Tracking
            </a>
            <div style="display: flex; align-items: center; gap: 15px;">
                <span style="font-size: 0.85rem; color: var(--text-secondary);">Ticket Number: <strong style="color: #000; font-family: monospace;"><?= $data['message']['ticket_number'] ?></strong></span>
                <span class="badge" style="background: var(--accent-color); color: white; padding: 6px 15px; border-radius: 20px; font-weight: 700; font-size: 0.75rem; text-transform: uppercase;">
                    <?= $data['message']['status'] ?>
                </span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr; gap: 30px;">
            <!-- Message Thread -->
            <div class="glass-card" style="padding: 0; background: white; border-radius: 24px; overflow: hidden; box-shadow: var(--shadow-md);">
                <div style="padding: 25px; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
                    <h3 style="font-size: 1.25rem; font-weight: 800; margin: 0;"><?= htmlspecialchars($data['message']['subject'] ?: 'Support Request') ?></h3>
                </div>

                <div style="padding: 30px; display: flex; flex-direction: column; gap: 25px;">
                    <!-- Original Message -->
                    <div style="display: flex; gap: 15px; align-items: start;">
                        <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; color: #64748b; flex-shrink: 0;">
                            <?= strtoupper(substr($data['message']['user_name'], 0, 1)) ?>
                        </div>
                        <div style="flex-grow: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                <strong style="font-size: 0.95rem;"><?= htmlspecialchars($data['message']['user_name']) ?></strong>
                                <span style="font-size: 0.75rem; color: var(--text-secondary);"><?= date('M d, H:i', strtotime($data['message']['created_at'])) ?></span>
                            </div>
                            <div style="background: #f1f5f9; padding: 15px; border-radius: 0 16px 16px 16px; font-size: 0.95rem; line-height: 1.6; color: #334155;">
                                <?= nl2br(htmlspecialchars($data['message']['message'])) ?>
                            </div>
                        </div>
                    </div>

                    <!-- Replies -->
                    <?php foreach($data['replies'] as $reply): ?>
                        <?php $isAdmin = ($reply['sender_role'] === 'admin' || $reply['sender_role'] === 'superadmin'); ?>
                        <div style="display: flex; gap: 15px; align-items: start; <?= $isAdmin ? 'flex-direction: row-reverse;' : '' ?>">
                            <div style="width: 40px; height: 40px; background: <?= $isAdmin ? 'var(--accent-color)' : '#e2e8f0' ?>; color: <?= $isAdmin ? 'white' : '#64748b' ?>; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 800; flex-shrink: 0;">
                                <?= $isAdmin ? 'B' : strtoupper(substr($reply['sender_name'], 0, 1)) ?>
                            </div>
                            <div style="flex-grow: 1; max-width: 80%;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px; <?= $isAdmin ? 'flex-direction: row-reverse;' : '' ?>">
                                    <strong style="font-size: 0.95rem;"><?= $isAdmin ? 'BLISS Support' : htmlspecialchars($reply['sender_name']) ?></strong>
                                    <span style="font-size: 0.75rem; color: var(--text-secondary);"><?= date('M d, H:i', strtotime($reply['created_at'])) ?></span>
                                </div>
                                <div style="background: <?= $isAdmin ? 'var(--accent-color)' : '#f1f5f9' ?>; color: <?= $isAdmin ? 'white' : '#334155' ?>; padding: 15px; border-radius: <?= $isAdmin ? '16px 0 16px 16px' : '0 16px 16px 16px' ?>; font-size: 0.95rem; line-height: 1.6;">
                                    <?= nl2br(htmlspecialchars($reply['reply_text'])) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <?php if(empty($data['replies'])): ?>
                        <div style="text-align: center; padding: 20px; background: #fffbeb; border: 1px solid #fef3c7; border-radius: 12px; color: #92400e; font-size: 0.9rem;">
                            Your request is currently awaiting a response from our team.
                        </div>
                    <?php endif; ?>
                </div>
                
                <div style="padding: 25px; background: #f8fafc; border-top: 1px solid #f1f5f9; text-align: center;">
                    <p style="margin: 0 0 20px 0; font-size: 0.85rem; color: var(--text-secondary);">
                        To send a follow-up message, use the form below.
                    </p>
                    <form action="/php/Webdev/public/help/reply_guest" method="POST" style="text-align: left;">
                        <input type="hidden" name="ticket_number" value="<?= $data['ticket_number'] ?>">
                        <input type="hidden" name="email" value="<?= $data['email'] ?>">
                        <div class="form-group">
                            <textarea name="reply" required placeholder="Write your follow-up message here..." style="width: 100%; min-height: 100px; padding: 15px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom: 15px; font-family: inherit; resize: vertical;"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-large" style="width: 100%; justify-content: center;">Send Follow-up</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
