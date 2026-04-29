<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <title>Employee Management System</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
        <style>
            .employee-photo {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                object-fit: cover;
                border: 2px solid #dee2e6;
            }
            .photo-placeholder {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: #dee2e6;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                color: #6c757d;
                font-size: 18px;
            }
            .alert-flash {
                position: fixed;
                top: 70px;
                right: 20px;
                z-index: 9999;
                min-width: 280px;
            }
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
                            <a class="nav-link active" href="index.php">
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
                            <i class="fas fa-users me-2"></i>Employee Management
                        </h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item active">Employee List</li>
                        </ol>

                        <?php if (isset($_GET['msg'])): ?>
                            <div class="alert alert-flash alert-<?php echo htmlspecialchars($_GET['type'] ?? 'success'); ?> alert-dismissible fade show" role="alert">
                                <?php
                                $messages = [
                                    'added'   => '<i class="fas fa-check-circle me-2"></i>Employee added successfully!',
                                    'updated' => '<i class="fas fa-check-circle me-2"></i>Employee updated successfully!',
                                    'deleted' => '<i class="fas fa-trash-alt me-2"></i>Employee deleted successfully!',
                                ];
                                echo $messages[htmlspecialchars($_GET['msg'])] ?? htmlspecialchars($_GET['msg']);
                                ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>
                                    <i class="fas fa-table me-1"></i>
                                    All Employees
                                </span>
                                <a href="add_employee.php" class="btn btn-primary btn-sm">
                                    <i class="fas fa-user-plus me-1"></i>Add New Employee
                                </a>
                            </div>
                            <div class="card-body">
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>Photo</th>
                                            <th>Full Name</th>
                                            <th>CNIC</th>
                                            <th>Father Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT * FROM employees ORDER BY id DESC";
                                        $result = mysqli_query($conn, $query);

                                        if (mysqli_num_rows($result) > 0):
                                            while ($row = mysqli_fetch_assoc($result)):
                                        ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($row['photo']) && file_exists('uploads/' . $row['photo'])): ?>
                                                    <img src="uploads/<?php echo htmlspecialchars($row['photo']); ?>"
                                                         alt="Photo" class="employee-photo">
                                                <?php else: ?>
                                                    <span class="photo-placeholder">
                                                        <i class="fas fa-user"></i>
                                                    </span>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo htmlspecialchars($row['full_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['cnic']); ?></td>
                                            <td><?php echo htmlspecialchars($row['father_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                            <td>
                                                <a href="edit_employee.php?id=<?php echo $row['id']; ?>"
                                                   class="btn btn-warning btn-sm me-1">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                                <a href="delete_employee.php?id=<?php echo $row['id']; ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Are you sure you want to delete this employee?');">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </a>
                                            </td>
                                        </tr>
                                        <?php
                                            endwhile;
                                        else:
                                        ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="fas fa-users-slash fa-2x mb-2 d-block"></i>
                                                No employees found. <a href="add_employee.php">Add one now!</a>
                                            </td>
                                        </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
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
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <script>
            // Auto-dismiss flash alert after 4 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert-flash');
                if (alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            }, 4000);
        </script>
    </body>
</html>
