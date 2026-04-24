<div style="margin-bottom: var(--spacing-3);">
    <a href="/php/Webdev/public/admin/customers" style="color: var(--admin-text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 8px; font-weight: 500; transition: color 0.2s;" onmouseover="this.style.color='var(--admin-accent)'" onmouseout="this.style.color='var(--admin-text-muted)'">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
        Back to Customers
    </a>
</div>

<div class="admin-card" style="max-width: 800px; padding: 0; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.08);">
    <div style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); padding: 35px; color: white;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <h2 style="font-size: 1.75rem; font-weight: 800; letter-spacing: -0.02em; margin-bottom: 5px;">Security Link Generated</h2>
                <p style="opacity: 0.8; font-size: 0.95rem;">Token successfully minted for <strong><?= htmlspecialchars($data['user']['name']) ?></strong></p>
            </div>
            <div style="background: rgba(255,255,255,0.2); padding: 8px 15px; border-radius: 10px; backdrop-filter: blur(10px); font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                Expires in 2 Hours
            </div>
        </div>
    </div>

    <div style="padding: 40px;">
        <div style="background: #f8fafc; border: 1px solid var(--admin-border); border-radius: 16px; padding: 25px; margin-bottom: 30px;">
            <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; color: var(--admin-accent);">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
                <h3 style="font-size: 1rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.05em; margin: 0;">Copy Formatted Message</h3>
            </div>

            <div style="background: white; border: 1px solid var(--admin-border); border-radius: 12px; padding: 25px; font-family: 'Inter', sans-serif; font-size: 1rem; line-height: 1.6; color: #1e293b; white-space: pre-wrap; overflow-wrap: break-word; word-break: break-word; user-select: all; box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);" id="linkBlock">Hi <?= htmlspecialchars($data['user']['name']) ?>,

We received a request to reset your BLISS account password. Please click the secure link below to choose a new password. 

<?= $data['reset_link'] ?>


Note: This link is time-sensitive and will expire in 2 hours for security purposes. If you did not request this, you may ignore this message.

Thank you,
The BLISS Team</div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center;">
            <p style="font-size: 0.85rem; color: var(--admin-text-muted); max-width: 450px;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 5px;"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>
                Please transmit this message securely. Do not share this link with anyone else.
            </p>
            <button onclick="copyToClipboard(this)" class="btn-admin btn-admin-primary" style="padding: 15px 30px; border-radius: 12px; font-size: 1rem; box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;"><rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                Copy to Clipboard
            </button>
        </div>
    </div>
</div>

<script>
function copyToClipboard(btn) {
    const text = document.getElementById('linkBlock').innerText;
    const originalContent = btn.innerHTML;
    
    navigator.clipboard.writeText(text).then(() => {
        btn.innerHTML = `<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 10px;"><polyline points="20 6 9 17 4 11"></polyline></svg> Copied!`;
        btn.style.background = 'var(--admin-success)';
        
        setTimeout(() => {
            btn.innerHTML = originalContent;
            btn.style.background = 'var(--admin-accent)';
        }, 2000);
    });
}
</script>
