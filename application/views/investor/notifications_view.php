<style>
    .notif-container {
        margin-top: 10px;
    }
    .notif-list-card {
        background: #fff;
        border: 1px solid #ede9fe;
        border-radius: 20px;
        box-shadow: 0 14px 40px rgba(49, 32, 90, 0.05);
        padding: 24px;
    }
    .notif-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #f5f3ff;
        padding-bottom: 16px;
    }
    .notif-header h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
        color: #201a2f;
    }
    .notif-list {
        display: grid;
        gap: 12px;
    }
    .notif-row-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 20px;
        background: #fff;
        border: 1px solid #f3f0ff;
        border-radius: 16px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }
    .notif-row-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(109, 40, 217, 0.05);
        border-color: #ddd6fe;
    }
    .notif-row-item.unread {
        background: #fdfaff;
        border-color: #ddd6fe;
    }
    .notif-left {
        display: flex;
        gap: 16px;
        align-items: center;
        flex: 1;
    }
    .notif-icon-circle {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .notif-icon-unread {
        background: #f3f0ff;
        color: #6d28d9;
    }
    .notif-icon-read {
        background: #f1f5f9;
        color: #64748b;
    }
    .notif-content-block {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .notif-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .notif-title-text {
        font-size: 15px;
        font-weight: 600;
        color: #1f2937;
    }
    .unread-dot {
        width: 6px;
        height: 6px;
        background: #6d28d9;
        border-radius: 50%;
    }
    .notif-msg-text {
        font-size: 13.5px;
        color: #6b7280;
        line-height: 1.4;
    }
    .notif-date-block {
        text-align: right;
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 6px;
        margin-left: 20px;
        flex-shrink: 0;
    }
    .notif-time-text {
        font-size: 11.5px;
        color: #9ca3af;
    }
    .btn-action-view {
        background: #f5f3ff;
        color: #6d28d9;
        padding: 6px 14px;
        border-radius: 10px;
        font-size: 12px;
        font-weight: 600;
        transition: all 0.2s ease;
    }
    .notif-row-item:hover .btn-action-view {
        background: #6d28d9;
        color: #fff;
    }
    .no-records {
        text-align: center;
        padding: 60px 20px;
        color: #9485aa;
    }
    .no-records-icon {
        font-size: 40px;
        margin-bottom: 12px;
    }
</style>

<div class="notif-container">
    <div class="notif-list-card">
        <div class="notif-header">
            <h2>Notifications Log</h2>
        </div>

        <div class="notif-list">
            <?php if (!empty($notifications)): ?>
                <?php foreach ($notifications as $notif): ?>
                    <a href="<?php echo base_url('investor/notifications/view/' . $notif['id']); ?>" class="notif-row-item <?php echo !$notif['is_read'] ? 'unread' : ''; ?>">
                        <div class="notif-left">
                            <div class="notif-icon-circle <?php echo !$notif['is_read'] ? 'notif-icon-unread' : 'notif-icon-read'; ?>">
                                🔔
                            </div>
                            <div class="notif-content-block">
                                <div class="notif-title-row">
                                    <span class="notif-title-text"><?php echo html_escape($notif['title']); ?></span>
                                    <?php if (!$notif['is_read']): ?>
                                        <span class="unread-dot"></span>
                                    <?php endif; ?>
                                </div>
                                <div class="notif-msg-text"><?php echo html_escape($notif['message']); ?></div>
                            </div>
                        </div>
                        <div class="notif-date-block">
                            <span class="notif-time-text"><?php echo date('d M Y, h:i A', strtotime($notif['created_at'])); ?></span>
                            <span class="btn-action-view">View Details</span>
                        </div>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="no-records">
                    <div class="no-records-icon">🔔</div>
                    <p style="margin:0; font-size:15px;">You have no notifications at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
