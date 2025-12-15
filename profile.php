<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch Orders
$sql_orders = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$orders = $conn->query($sql_orders);

// Fetch Messages/Contacts
$sql_contacts = "SELECT * FROM contacts WHERE user_id = $user_id ORDER BY created_at DESC";
$contacts = $conn->query($sql_contacts);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Hồ Sơ Của Tôi - Football Shop</title>
    <link rel="stylesheet" href="css/style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-container {
            display: grid;
            grid-template-columns: 250px 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }

        .profile-sidebar {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 10px;
            height: fit-content;
        }



        .tab-btn {
            display: block;
            width: 100%;
            padding: 1rem;
            text-align: left;
            background: none;
            border: none;
            color: #ccc;
            cursor: pointer;
            border-bottom: 1px solid #333;
        }

        .tab-btn:hover,
        .tab-btn.active {
            color: var(--primary-color);
            background: #333;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .order-card,
        .msg-card {
            background: var(--card-bg);
            border: 1px solid #333;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1rem;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="container profile-container">
        <aside class="profile-sidebar">
            <h3 style="margin-bottom: 1rem; text-align:center;">Xin chào, <?php echo $_SESSION['username']; ?></h3>
            <button class="tab-btn active" onclick="openTab(event, 'orders')"><i class="fas fa-shopping-bag"></i> Đơn Hàng Của Tôi</button>
            <button class="tab-btn" onclick="openTab(event, 'messages')"><i class="fas fa-envelope"></i> Tin Nhắn & Hỗ Trợ</button>
            <a href="logout.php" class="tab-btn" style="color:red;"><i class="fas fa-sign-out-alt"></i> Đăng Xuất</a>
        </aside>

        <main class="profile-main">
            <!-- ORDERS TAB -->
            <div id="orders" class="tab-content active">
                <h2 class="section-title">Đơn Hàng Gần Đây</h2>
                <?php if ($orders->num_rows > 0): ?>
                    <?php while ($o = $orders->fetch_assoc()): ?>
                        <div class="order-card">
                            <div style="display:flex; justify-content:space-between; margin-bottom:1rem; border-bottom:1px solid #444; padding-bottom:0.5rem;">
                                <span><strong>Đơn hàng #<?php echo $o['id']; ?></strong></span>
                                <span class="badge badge-<?php echo $o['status']; ?>"><?php echo $o['status']; ?></span>
                            </div>
                            <p>Ngày đặt: <?php echo date('d/m/Y H:i', strtotime($o['created_at'])); ?></p>
                            <p>Tổng tiền: <span style="color:var(--secondary-color); font-weight:bold;"><?php echo formatPrice($o['total_money']); ?></span></p>
                            <p>Địa chỉ: <?php echo $o['customer_address']; ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Bạn chưa có đơn hàng nào.</p>
                <?php endif; ?>
            </div>

            <!-- MESSAGES TAB -->
            <div id="messages" class="tab-content">
                <h2 class="section-title">Hộp Thư Hỗ Trợ</h2>
                <?php if ($contacts->num_rows > 0): ?>
                    <?php while ($c = $contacts->fetch_assoc()): ?>
                        <div class="msg-card">
                            <p style="color:#aaa; font-size:0.9rem;">Gửi lúc: <?php echo date('d/m/Y H:i', strtotime($c['created_at'])); ?></p>
                            <p style="margin: 0.5rem 0; font-style: italic;">"<?php echo $c['message']; ?>"</p>

                            <?php if ($c['reply']): ?>
                                <div style="background: #1e3a29; padding: 1rem; border-radius: 5px; margin-top: 1rem; border-left: 3px solid var(--primary-color);">
                                    <strong style="color: var(--primary-color);">Admin phản hồi:</strong>
                                    <p style="margin-top: 5px;"><?php echo $c['reply']; ?></p>
                                    <small style="color:#888;">Lúc: <?php echo date('d/m/Y H:i', strtotime($c['reply_at'])); ?></small>
                                </div>
                            <?php else: ?>
                                <p style="color: #666; margin-top: 1rem;"><i class="fas fa-clock"></i> Đang chờ phản hồi...</p>
                            <?php endif; ?>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Bạn chưa gửi tin nhắn hỗ trợ nào.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        function openTab(evt, tabName) {
            var i, tabcontent, tablinks;
            tabcontent = document.getElementsByClassName("tab-content");
            for (i = 0; i < tabcontent.length; i++) {
                tabcontent[i].style.display = "none";
            }
            tablinks = document.getElementsByClassName("tab-btn");
            for (i = 0; i < tablinks.length; i++) {
                tablinks[i].className = tablinks[i].className.replace(" active", "");
            }
            document.getElementById(tabName).style.display = "block";
            evt.currentTarget.className += " active";
        }
    </script>
    <?php include 'footer.php'; ?>
</body>

</html>