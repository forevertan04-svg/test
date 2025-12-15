<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Football Shop</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            background: #000;
            padding: 2rem 1rem;
            border-right: 1px solid #333;
            flex-shrink: 0;
        }

        .sidebar a {
            display: block;
            padding: 1rem;
            color: #aaa;
            margin-bottom: 0.5rem;
            border-radius: 5px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #333;
            color: var(--primary-color);
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 10px;
            border: 1px solid #333;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .stat-info h3 {
            font-size: 1rem;
            color: #aaa;
            margin-bottom: 5px;
        }

        .stat-number {
            font-size: 2rem;
            color: var(--primary-color);
            font-weight: bold;
        }

        .stat-icon {
            font-size: 2.5rem;
            color: #333;
        }
    </style>
</head>

<body>
    <div class="admin-wrapper">
        <?php include 'sidebar.php'; ?>
        <main class="main-content">