<div class="help-page">
    <div class="container" style="max-width: 500px; padding: 60px 20px;">
        <div class="glass-card" style="padding: 40px; background: white; border-radius: 24px; box-shadow: var(--shadow-lg);">
            <div style="text-align: center; margin-bottom: 30px;">
                <h2 style="font-size: 1.8rem; font-weight: 900; margin-bottom: 10px;">Track Request</h2>
                <p style="color: var(--text-secondary); font-size: 0.95rem;">Enter your details to view ticket status and replies.</p>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 12px; margin-bottom: 25px; border: 1px solid #fecaca; font-size: 0.85rem; text-align: center;">
                    <?php 
                    if($_GET['error'] == 'not_found') echo "Ticket not found or email mismatch.";
                    else if($_GET['error'] == 'access_denied') echo "You do not have permission to view this ticket.";
                    else echo "An error occurred. Please try again.";
                    ?>
                </div>
            <?php endif; ?>

            <form action="/php/Webdev/public/help/process_track_ticket" method="POST">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="font-weight: 700; margin-bottom: 8px; display: block;">Ticket Number</label>
                    <input type="text" name="ticket_number" required placeholder="TCK-XXXXXXXX" style="width: 100%; padding: 14px; border: 1px solid #e2e8f0; border-radius: 12px; font-family: monospace; text-transform: uppercase;">
                </div>
                <div class="form-group" style="margin-bottom: 30px;">
                    <label style="font-weight: 700; margin-bottom: 8px; display: block;">Email Address</label>
                    <input type="email" name="email" required placeholder="your@email.com" style="width: 100%; padding: 14px; border: 1px solid #e2e8f0; border-radius: 12px;">
                </div>
                <button type="submit" class="btn btn-primary btn-large w-full" style="justify-content: center; padding: 16px;">View Progress</button>
            </form>

            <div style="margin-top: 25px; text-align: center;">
                <a href="/php/Webdev/public/help" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem; font-weight: 600;">Back to Help Center</a>
            </div>
        </div>
    </div>
</div>
