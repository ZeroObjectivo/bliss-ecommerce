<div class="container py-8">
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <!-- Mobile Profile Navigation Trigger -->
    <button class="mobile-profile-nav-trigger" id="mobile-profile-trigger">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
        <span>Profile Menu</span>
    </button>

    <div class="profile-layout">
        <!-- Sidebar -->
        <aside class="profile-sidebar" id="profile-sidebar">
            <div class="user-avatar-section">
                <div class="avatar-wrapper">
                    <?php if(isset($data['user']['profile_picture']) && $data['user']['profile_picture']): ?>
                        <img src="/php/Webdev/public/<?= $data['user']['profile_picture'] ?>" alt="Avatar">
                    <?php else: ?>
                        <div class="avatar-placeholder"><?= strtoupper(substr($_SESSION['user_name'], 0, 1)) ?></div>
                    <?php endif; ?>
                </div>
                <h2 class="user-name"><?= $_SESSION['user_name'] ?></h2>
            </div>
            
            <nav class="profile-nav">
                <a href="/php/Webdev/public/profile">Account Settings</a>
                <a href="/php/Webdev/public/profile/orders" class="active">My Orders</a>
                <a href="/php/Webdev/public/profile/inbox">My Inbox</a>
                <a href="/php/Webdev/public/auth/logout" class="logout-link">Logout</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="profile-content">
            <?php if(isset($_GET['success']) && $_GET['success'] == 'return_requested'): ?>
                <div style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    Return request submitted successfully. Our team will review it shortly.
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['error'])): ?>
                <div style="background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: 0.9rem;">
                    <?php 
                        if($_GET['error'] == 'min_3_images_required') echo 'At least 3 images are required for return/refund requests.';
                        elseif($_GET['error'] == 'invalid_images') echo 'One or more images were invalid. Please try again.';
                        else echo 'Failed to submit return request. Please try again.';
                    ?>
                </div>
            <?php endif; ?>

            <section class="profile-section">
                <div class="section-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <a href="/php/Webdev/public/profile/orders" class="back-btn" style="color: #64748b; text-decoration: none; display: flex; align-items: center;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
                        </a>
                        <h3 style="margin: 0; border: none; padding: 0;">Order #<?= $data['order']['id'] ?></h3>
                    </div>
                    <?php 
                        $status = $data['order']['status'];
                        $badgeClass = strtolower(str_replace(' ', '-', $status));
                        
                        $statusText = $status;
                        if ($status == 'processing') $statusText = 'Payment Confirmed';
                        
                        // Keep delivery/completed consistent green
                        if ($status == 'completed') $badgeClass = 'delivered'; 

                        $isReturn = in_array($status, ['Return Requested', 'Return Approved', 'Return Rejected', 'Refunded']);
                    ?>
                    <span class="status-badge <?= $badgeClass ?>"><?= ucfirst($statusText) ?></span>
                </div>

                <!-- Return Tracking Hub -->
                <?php if($isReturn): ?>
                    <div class="return-status-hub">
                        <h4 class="hub-title">Return Tracking</h4>
                        <div class="milestones-grid">
                            <!-- Milestone 1: Requested -->
                            <div class="milestone-card active completed success">
                                <div class="milestone-badge">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
                                </div>
                                <div class="milestone-info">
                                    <h6>Return Requested</h6>
                                    <div class="milestone-date"><?= date('M d, Y', strtotime($data['order']['return_requested_at'])) ?></div>
                                    
                                    <?php if(!empty($data['order']['return_image_base64'])): ?>
                                        <div style="margin-top: 1rem; display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 8px;">
                                            <?php 
                                                $images = json_decode($data['order']['return_image_base64'], true);
                                                if (json_last_error() === JSON_ERROR_NONE && is_array($images)):
                                                    foreach ($images as $img):
                                            ?>
                                                <img src="<?= $img ?>" alt="Product Evidence" style="width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0; cursor: zoom-in;" onclick="openImageViewer(this.src)">
                                            <?php 
                                                    endforeach;
                                                else:
                                            ?>
                                                <img src="<?= $data['order']['return_image_base64'] ?>" alt="Product Evidence" style="width: 100%; max-width: 150px; border-radius: 12px; border: 1px solid #e2e8f0; cursor: zoom-in;" onclick="openImageViewer(this.src)">
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="reason-tag">
                                        <label>Reason</label>
                                        <p><?= htmlspecialchars($data['order']['return_reason']) ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Milestone 2: Decision -->
                            <?php 
                                $decisionMade = $data['order']['return_approved_at'] || $data['order']['return_rejected_at'];
                                $isRejected = $data['order']['status'] == 'Return Rejected';
                                $decisionClass = $decisionMade ? ($isRejected ? 'active error' : 'active success') : '';
                                $decisionIcon = $isRejected ? 'x-circle' : 'check-circle';
                            ?>
                            <div class="milestone-card <?= $decisionClass ?>">
                                <div class="milestone-badge">
                                    <?php if($isRejected): ?>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                                    <?php else: ?>
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                    <?php endif; ?>
                                </div>
                                <div class="milestone-info">
                                    <h6><?= $isRejected ? 'Request Rejected' : 'Review Decision' ?></h6>
                                    <?php if($decisionMade): ?>
                                        <div class="milestone-date"><?= date('M d, Y', strtotime($isRejected ? $data['order']['return_rejected_at'] : $data['order']['return_approved_at'])) ?></div>
                                        <div class="milestone-content">
                                            <?= $isRejected ? 'Request was not accepted.' : 'Your return has been approved.' ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="milestone-content">Pending review by our team.</div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Milestone 3: Outcome -->
                            <?php 
                                $isRefunded = $data['order']['status'] == 'Refunded';
                                $outcomeClass = $isRefunded ? 'active success' : '';
                                if ($isRejected) $outcomeClass = 'error'; // ghosted error
                            ?>
                            <div class="milestone-card <?= $outcomeClass ?>">
                                <div class="milestone-badge">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                                </div>
                                <div class="milestone-info">
                                    <h6>Refund Status</h6>
                                    <?php if($isRefunded): ?>
                                        <div class="milestone-date"><?= date('M d, Y', strtotime($data['order']['refunded_at'])) ?></div>
                                        <div class="milestone-content">Refund of ₱<?= number_format($data['order']['total_price'], 2) ?> completed.</div>
                                    <?php else: ?>
                                        <div class="milestone-content">
                                            <?= $isRejected ? 'No refund will be issued.' : 'Waiting for approval.' ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Progress Bar -->
                <div class="order-progress-container">
                    <?php 
                        $status = $data['order']['status'];
                        $steps = ['pending', 'processing', 'shipped', 'delivered', 'completed'];
                        
                        // If it's a return, we might want to hide the progress bar or show a different one
                        // For now, if it's completed or delivered, keep showing it.
                        
                        $current_step = array_search($status, $steps);
                        if ($status == 'cancelled' || $isReturn) {
                            if ($status == 'Return Requested' || $status == 'Return Approved' || $status == 'Refunded') {
                                // Keep showing progress up to delivered/completed if it was reached
                                if ($data['order']['status'] == 'Refunded') $current_step = 4;
                                else $current_step = 4; // usually reached completed
                            } else {
                                $current_step = -1;
                            }
                        }
                    ?>
                    <?php if(!$isReturn || $status == 'Refunded'): ?>
                    <div class="progress-track">
                        <div class="progress-line">
                            <div class="progress-line-fill" style="width: <?= $current_step >= 0 ? ($current_step / (count($steps)-1) * 100) : 0 ?>%;"></div>
                        </div>
                        
                        <?php foreach($steps as $index => $step): 
                            $class = '';
                            if ($current_step > $index) $class = 'completed';
                            elseif ($current_step == $index) $class = 'active';
                        ?>
                            <div class="step <?= $class ?>">
                                <div class="step-dot">
                                    <?php if($current_step > $index): ?>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                    <?php else: ?>
                                        <?= $index + 1 ?>
                                    <?php endif; ?>
                                </div>
                                <span class="step-label">
                                    <?php 
                                        if ($step == 'processing') echo 'Paid';
                                        elseif ($step == 'completed') echo 'Completed';
                                        else echo ucfirst($step);
                                    ?>
                                </span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="order-details-grid">
                    <div class="order-items-card">
                        <h4 style="margin-top: 0; margin-bottom: 1rem; font-size: 1.1rem;">Items Ordered</h4>
                        <div class="items-list">
                            <?php foreach($data['items'] as $item): ?>
                                <div class="item-row">
                                    <div class="item-img">
                                        <img src="<?= htmlspecialchars($item['image_main']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                                    </div>
                                    <div class="item-info" style="flex: 1;">
                                        <h5><?= htmlspecialchars($item['name']) ?></h5>
                                        <div class="item-meta">Size: <?= strtoupper($item['size']) ?> | Qty: <?= $item['quantity'] ?></div>
                                        <div style="font-weight: 600;">₱<?= number_format($item['price'], 2) ?></div>
                                    </div>
                                    <div class="item-subtotal" style="text-align: right;">
                                        <div style="font-weight: 700;">₱<?= number_format($item['price'] * $item['quantity'], 2) ?></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="order-summary">
                            <div class="summary-row">
                                <span style="color: #64748b;">Subtotal</span>
                                <span>₱<?= number_format($data['order']['total_price'], 2) ?></span>
                            </div>
                            <div class="summary-row">
                                <span style="color: #64748b;">Shipping</span>
                                <span>Free</span>
                            </div>
                            <div class="summary-row summary-total">
                                <span>Total</span>
                                <span>₱<?= number_format($data['order']['total_price'], 2) ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="order-info-sidebar">
                        <div class="info-card" style="background: #fff; border: 1px solid #f1f5f9; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem;">
                            <h4 style="margin-top: 0; margin-bottom: 1rem;">Shipping Details</h4>
                            <p style="margin: 0; font-size: 0.9rem; line-height: 1.6; color: #475569;">
                                <?= nl2br(htmlspecialchars($data['order']['shipping_address'])) ?>
                            </p>
                            <h4 style="margin-top: 1.5rem; margin-bottom: 1rem;">Payment Method</h4>
                            <p style="margin: 0; font-size: 0.9rem; color: #475569;">
                                <?= strtoupper(str_replace('_', ' ', $data['order']['payment_method'])) ?>
                            </p>
                        </div>

                        <?php if($data['order']['status'] == 'delivered'): ?>
                            <div class="action-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; text-align: center;">
                                <h4 style="margin-top: 0; margin-bottom: 0.5rem;">Confirm Receipt</h4>
                                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">Your order has arrived! Please confirm you've received all items.</p>
                                <form action="/php/Webdev/public/profile/confirm_receipt/<?= $data['order']['id'] ?>" method="POST">
                                    <button type="submit" class="btn-primary" style="width: 100%;">Confirm & Complete</button>
                                </form>
                            </div>
                        <?php endif; ?>

                        <?php if($data['order']['status'] == 'completed'): ?>
                            <div class="action-card" style="background: #ecfdf5; border: 1px solid #10b981; border-radius: 12px; padding: 1.5rem; text-align: center;">
                                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#10b981" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 0.5rem;"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                                <h4 style="margin-top: 0; margin-bottom: 0.5rem; color: #065f46;">Order Completed</h4>
                                <p style="font-size: 0.85rem; color: #065f46; margin-bottom: 0;">Thank you for shopping with us! This transaction is complete.</p>
                            </div>
                        <?php endif; ?>

                        <?php if(($data['order']['status'] == 'delivered' || $data['order']['status'] == 'completed') && !$isReturn): ?>
                            <div class="action-card" style="background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem; text-align: center; margin-top: 1.5rem;">
                                <h4 style="margin-top: 0; margin-bottom: 0.5rem;">Need to Return?</h4>
                                <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 1.25rem;">If there's an issue with your items, you can request a return or refund.</p>
                                <button type="button" class="btn-outline" style="width: 100%;" onclick="openReturnModal()">Request Return / Refund</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        </main>
    </div>
</div>

<!-- Return Request Modal -->
<div id="returnModal" class="modal">
    <div class="modal-content glass-card" style="max-width: 500px;">
        <div class="modal-header">
            <h3>Request Return / Refund</h3>
            <button class="close-modal" onclick="closeReturnModal()">&times;</button>
        </div>
        <form action="/php/Webdev/public/profile/request_return" method="POST" class="address-form" enctype="multipart/form-data">
            <input type="hidden" name="order_id" value="<?= $data['order']['id'] ?>">
            
            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Reason for Return</label>
                <select name="reason" required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit;">
                    <option value="">Select a reason...</option>
                    <option value="Wrong Size">Wrong Size</option>
                    <option value="Damaged Item">Damaged Item</option>
                    <option value="Defective Product">Defective Product</option>
                    <option value="Not as Described">Not as Described</option>
                    <option value="Changed My Mind">Changed My Mind</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Product Evidence (Required, Min 3 Images)</label>
                <input type="file" name="return_images[]" accept="image/*" multiple required style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit;">
                <p style="font-size: 0.75rem; color: #64748b; margin-top: 0.5rem;">Please upload at least 3 photos of the product (e.g., tags, damage, or wrong item) to help us process your request.</p>
            </div>

            <div class="form-group" style="margin-bottom: 1.5rem;">
                <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Additional Message (Optional)</label>
                <textarea name="message" rows="4" style="width: 100%; padding: 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; font-family: inherit; resize: vertical;" placeholder="Tell us more about the issue..."></textarea>
            </div>

            <div id="return-error-msg" style="display: none; color: #ef4444; font-size: 0.85rem; margin-bottom: 1rem; padding: 0.75rem; background: #fef2f2; border-radius: 8px; border: 1px solid #fee2e2;">
                Please upload at least 3 images to proceed.
            </div>

            <div style="margin-top: 2rem; display: flex; gap: 1rem;">
                <button type="button" class="btn-outline" style="flex: 1;" onclick="closeReturnModal()">Cancel</button>
                <button type="submit" class="btn-primary" style="flex: 1;">Submit Request</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelector('form[action*="request_return"]').addEventListener('submit', function(e) {
    const fileInput = this.querySelector('input[name="return_images[]"]');
    const errorMsg = document.getElementById('return-error-msg');
    
    if (fileInput.files.length < 3) {
        e.preventDefault();
        errorMsg.style.display = 'block';
        fileInput.scrollIntoView({ behavior: 'smooth', block: 'center' });
    } else {
        errorMsg.style.display = 'none';
    }
});
function openReturnModal() {
    document.getElementById('returnModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeReturnModal() {
    document.getElementById('returnModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Mobile Navigation Toggle
document.addEventListener('DOMContentLoaded', function() {
    const mobileTrigger = document.getElementById('mobile-profile-trigger');
    const sidebar = document.getElementById('profile-sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (mobileTrigger && sidebar && overlay) {
        mobileTrigger.addEventListener('click', () => {
            sidebar.classList.add('open');
            overlay.classList.add('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        });
    }
});
</script>

<link rel="stylesheet" href="/php/Webdev/public/css/profile.css">

<!-- Image Viewer Modal -->
<div id="imageViewerModal" class="modal" onclick="closeImageViewer()">
    <div class="modal-content glass-card" style="max-width: 90vw; max-height: 90vh; padding: 15px; display: flex; align-items: center; justify-content: center; position: relative;" onclick="event.stopPropagation()">
        <button class="close-modal" style="position: absolute; top: 10px; right: 10px; z-index: 100; background: #fff; box-shadow: 0 4px 12px rgba(0,0,0,0.1);" onclick="closeImageViewer()">&times;</button>
        <img id="viewerImage" src="" style="max-width: 100%; max-height: 80vh; border-radius: 12px; object-fit: contain;">
    </div>
</div>

<script>
function openImageViewer(src) {
    const modal = document.getElementById('imageViewerModal');
    const img = document.getElementById('viewerImage');
    img.src = src;
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeImageViewer() {
    const modal = document.getElementById('imageViewerModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto';
}
</script>
