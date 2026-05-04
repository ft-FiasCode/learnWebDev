<?php
include 'db.php';

// Validate ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int) $_GET['id'];
$query = "SELECT * FROM employees WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("Location: index.php?msg=Employee+not+found&type=danger");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Edit Employee - EMS</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            .photo-preview-container {
                width: 120px;
                height: 120px;
                border-radius: 50%;
                border: 3px dashed #dee2e6;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;
                cursor: pointer;
                transition: border-color 0.2s;
                margin: 0 auto 10px;
                background: #f8f9fa;
            }
            .photo-preview-container:hover { border-color: #0d6efd; }
            .photo-preview-container img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            .placeholder-icon {
                font-size: 40px;
                color: #adb5bd;
                flex-direction: column;
                display: flex;
                align-items: center;
                gap: 5px;
            }
            .placeholder-icon span { font-size: 11px; color: #6c757d; }
            .required-star { color: #dc3545; }
        </style>
    </head>
    <body class="sb-nav-fixed">

        <!-- Top Navigation -->
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <a class="navbar-brand ps-3" href="index.php">
                <i class="fas fa-building me-2"></i>EMS
            </a>
            <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!">
                <i class="fas fa-bars"></i>
            </button>
            <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user fa-fw"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#!"><i class="fas fa-cog me-2"></i>Settings</a></li>
                        <li><hr class="dropdown-divider" /></li>
                        <li><a class="dropdown-item" href="#!"><i class="fas fa-sign-out-alt me-2"></i>Logout</a></li>
                    </ul>
                </li>
            </ul>
        </nav>

        <div id="layoutSidenav">
            <!-- Sidebar -->
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Main</div>
                            <a class="nav-link" href="index.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                Employees
                            </a>
                            <div class="sb-sidenav-menu-heading">Actions</div>
                            <a class="nav-link" href="add_employee.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-plus"></i></div>
                                Add Employee
                            </a>
                        </div>
                    </div>
                    <div class="sb-sidenav-footer">
                        <div class="small">Employee Management System</div>
                        v1.0
                    </div>
                </nav>
            </div>

            <!-- Main Content -->
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">
                            <i class="fas fa-user-edit me-2"></i>Edit Employee
                        </h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.php">Employees</a></li>
                            <li class="breadcrumb-item active">Edit: <?php echo htmlspecialchars($row['full_name']); ?></li>
                        </ol>

                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i>
                                <?php echo htmlspecialchars($_GET['error']); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-user-edit me-1"></i>
                                Update Employee Information
                            </div>
                            <div class="card-body">
                                <form method="POST" action="update_employee.php" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="old_photo" value="<?php echo htmlspecialchars($row['photo']); ?>">

                                    <!-- Photo -->
                                    <div class="text-center mb-4">
                                        <div class="photo-preview-container" onclick="document.getElementById('photo').click()">
                                            <?php if (!empty($row['photo']) && file_exists('uploads/' . $row['photo'])): ?>
                                                <img id="photoPreview" src="uploads/<?php echo htmlspecialchars($row['photo']); ?>" alt="Photo">
                                                <div class="placeholder-icon" id="photoPlaceholder" style="display:none;">
                                                    <i class="fas fa-camera"></i>
                                                    <span>Change Photo</span>
                                                </div>
                                            <?php else: ?>
                                                <img id="photoPreview" src="" alt="" style="display:none;">
                                                <div class="placeholder-icon" id="photoPlaceholder">
                                                    <i class="fas fa-camera"></i>
                                                    <span>Upload Photo</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <input type="file" id="photo" name="photo" accept="image/*"
                                               style="display:none;" onchange="previewPhoto(this)">
                                        <small class="text-muted">Click to change photo (leave blank to keep current)</small>
                                    </div>

                                    <div class="row g-3">
                                        <!-- CNIC -->
                                        <div class="col-md-6">
                                            <label for="cnic" class="form-label">
                                                CNIC Number <span class="required-star">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                                <input type="text" class="form-control" id="cnic" name="cnic"
                                                       value="<?php echo htmlspecialchars($row['cnic']); ?>"
                                                       placeholder="XXXXX-XXXXXXX-X"
                                                       pattern="\d{5}-\d{7}-\d{1}"
                                                       required>
                                            </div>
                                        </div>

                                        <!-- Full Name -->
                                        <div class="col-md-6">
                                            <label for="full_name" class="form-label">
                                                Full Name <span class="required-star">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                                <input type="text" class="form-control" id="full_name" name="full_name"
                                                       value="<?php echo htmlspecialchars($row['full_name']); ?>" required>
                                            </div>
                                        </div>

                                        <!-- Father Name -->
                                        <div class="col-md-6">
                                            <label for="father_name" class="form-label">
                                                Father Name <span class="required-star">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-user-tie"></i></span>
                                                <input type="text" class="form-control" id="father_name" name="father_name"
                                                       value="<?php echo htmlspecialchars($row['father_name']); ?>" required>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="col-md-6">
                                            <label for="email" class="form-label">
                                                Email Address <span class="required-star">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                <input type="email" class="form-control" id="email" name="email"
                                                       value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                            </div>
                                        </div>

                                        <!-- Phone -->
                                        <div class="col-md-6">
                                            <label for="phone" class="form-label">
                                                Phone Number <span class="required-star">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                <input type="text" class="form-control" id="phone" name="phone"
                                                       value="<?php echo htmlspecialchars($row['phone']); ?>" required>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-save me-2"></i>Update Employee
                                        </button>
                                        <a href="index.php" class="btn btn-secondary">
                                            <i class="fas fa-times me-2"></i>Cancel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Employee Management System &copy; <?php echo date('Y'); ?></div>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script>
            function previewPhoto(input) {
                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        document.getElementById('photoPreview').src = e.target.result;
                        document.getElementById('photoPreview').style.display = 'block';
                        document.getElementById('photoPlaceholder').style.display = 'none';
                    };
                    reader.readAsDataURL(input.files[0]);
                }
            }

            // Auto-format CNIC
            document.getElementById('cnic').addEventListener('input', function(e) {
                let val = e.target.value.replace(/\D/g, '');
                if (val.length > 5) val = val.slice(0, 5) + '-' + val.slice(5);
                if (val.length > 13) val = val.slice(0, 13) + '-' + val.slice(13);
                e.target.value = val.slice(0, 15);
            });
        </script>
    </body>
</html>
