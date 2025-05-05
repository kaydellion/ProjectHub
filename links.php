<?php
$current_page = basename($_SERVER['PHP_SELF']);

function generateLink($page, $icon, $text, $current_page) {
    $active_class = ($current_page == $page) ? 'text-primary' : 'text-light';
    if ($page == 'logout.php') {
        $active_class = 'text-danger';
    }
    echo "<a href=\"$page\" class=\"align-items-center m-2 $active_class\"><i class=\"$icon mr-2\"></i> $text</a>";
}
?>

<?php
generateLink('dashboard.php', 'ti-anchor', 'Dashboard', $current_page);
generateLink('loyalty-status.php', 'ti-agenda', 'Subscriptions', $current_page);
generateLink('wallet.php', 'ti-wallet', 'Wallet', $current_page);
// Only show "Add Report" if the user is a seller
if (isset($seller) && $seller == 1) {
    generateLink('add-report.php', 'ti-plus', 'Add Report', $current_page);
    generateLink('saved-models.php', 'ti-edit', 'Draft', $current_page);
}
generateLink('notifications.php', 'ti-bell', 'Notifications', $current_page);
generateLink('settings.php', 'ti-settings', 'Settings', $current_page);
generateLink('logout.php', 'ti-share', 'Logout', $current_page);
?>
