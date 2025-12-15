<?php
require_once 'config.php';

$baseDir = __DIR__ . '/files';
$subDir = isset($_GET['dir']) ? $_GET['dir'] : '';

// Security: Prevent directory traversal
// Remove any null bytes or '..' sequences first for cleaner path handling
$subDir = str_replace(array('..', "\0"), '', $subDir);
$subDir = trim($subDir, '/\\');

$currentDir = $baseDir;
if (!empty($subDir)) {
    $currentDir .= '/' . $subDir;
}

// Fallback if path doesn't exist
if (!file_exists($currentDir) || !is_dir($currentDir)) {
    $subDir = '';
    $currentDir = $baseDir;
}

// Get breadcrumbs
$breadCrumbs = !empty($subDir) ? explode('/', $subDir) : [];

// Scan items
$items = scandir($currentDir);
$folders = [];
$files = [];

foreach ($items as $item) {
    if ($item != '.' && $item != '..') {
        $path = $currentDir . '/' . $item;
        if (is_dir($path)) {
            $folders[] = $item;
        } else {
            $files[] = $item;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab - Tài liệu học tập</title>
    <link rel="stylesheet" href="css/style.css?v=2">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .lab-container {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1rem;
            min-height: 60vh;
        }

        .lab-header {
            margin-bottom: 2rem;
            background: #fff;
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .file-list {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .file-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #f0f0f0;
            transition: background 0.2s;
            text-decoration: none;
            color: #333;
        }

        .file-item:last-child {
            border-bottom: none;
        }

        .file-item:hover {
            background: #f8f9fa;
        }

        .item-icon {
            font-size: 1.2rem;
            margin-right: 1.5rem;
            width: 24px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .folder-icon {
            color: #ffc107;
        }

        .file-icon {
            color: #6c757d;
        }

        .file-php {
            color: #777bb4;
        }

        .file-html {
            color: #e34c26;
        }

        .file-css {
            color: #264de4;
        }

        .file-js {
            color: #f7df1e;
        }

        .item-name {
            flex: 1;
            font-weight: 500;
            font-size: 1rem;
        }

        .item-type {
            font-size: 0.75rem;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            background: #f1f1f1;
            padding: 2px 8px;
            border-radius: 4px;
        }

        .breadcrumb {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            font-size: 1rem;
            flex-wrap: wrap;
        }

        .breadcrumb a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .breadcrumb a:hover {
            text-decoration: underline;
        }

        .breadcrumb span {
            color: #ccc;
        }

        .section-title.lab-title {
            margin-bottom: 0.5rem;
            text-align: left;
            font-size: 1.5rem;
        }
    </style>
</head>

<body>
    <?php include 'header.php'; ?>

    <main class="lab-container">
        <div class="lab-header">
            <h1 class="section-title lab-title">REPOSITORY FILE</h1>

            <div class="breadcrumb">
                <a href="lab.php"><i class="fas fa-home"></i> Files</a>
                <?php
                $pathAccum = '';
                foreach ($breadCrumbs as $crumb):
                    $pathAccum .= ($pathAccum ? '/' : '') . $crumb;
                ?>
                    <span>/</span>
                    <a href="?dir=<?php echo urlencode($pathAccum); ?>"><?php echo htmlspecialchars($crumb); ?></a>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="file-list">
            <?php if (!empty($subDir)):
                $parentDir = dirname($subDir);
                $parentDir = str_replace('\\', '/', $parentDir);
                if ($parentDir === '.') $parentDir = '';
            ?>
                <a href="?dir=<?php echo urlencode($parentDir); ?>" class="file-item" style="background-color: #fcfcfc;">
                    <span class="item-icon folder-icon"><i class="fas fa-level-up-alt"></i></span>
                    <span class="item-name" style="font-style: italic; color: #666;">... Quay lại</span>
                </a>
            <?php endif; ?>

            <?php if (empty($folders) && empty($files)): ?>
                <div style="padding: 3rem; text-align: center; color: #999;">
                    <i class="far fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                    Thư mục trống
                </div>
            <?php endif; ?>

            <?php foreach ($folders as $folder): ?>
                <a href="?dir=<?php echo urlencode(($subDir ? $subDir . '/' : '') . $folder); ?>" class="file-item">
                    <span class="item-icon folder-icon"><i class="fas fa-folder"></i></span>
                    <span class="item-name"><?php echo htmlspecialchars($folder); ?></span>
                </a>
            <?php endforeach; ?>

            <?php foreach ($files as $file):
                $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                $iconClass = 'file-icon';
                $icon = 'fa-file';

                switch ($ext) {
                    case 'php':
                        $iconClass = 'file-php';
                        $icon = 'fa-file-code';
                        break;
                    case 'html':
                        $iconClass = 'file-html';
                        $icon = 'fa-file-code';
                        break;
                    case 'css':
                        $iconClass = 'file-css';
                        $icon = 'fa-file-code';
                        break;
                    case 'js':
                        $iconClass = 'file-js';
                        $icon = 'fa-file-code';
                        break;
                    case 'pdf':
                        $icon = 'fa-file-pdf';
                        break;
                    case 'jpg':
                    case 'png':
                    case 'jpeg':
                        $icon = 'fa-file-image';
                        break;
                }

                $filePath = 'files/' . ($subDir ? $subDir . '/' : '') . $file;
            ?>
                <a href="<?php echo htmlspecialchars($filePath); ?>" target="_blank" class="file-item">
                    <span class="item-icon <?php echo $iconClass; ?>"><i class="far <?php echo $icon; ?>"></i></span>
                    <span class="item-name"><?php echo htmlspecialchars($file); ?></span>
                    <span class="item-type"><?php echo htmlspecialchars(strtoupper($ext)); ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>
</body>

</html>