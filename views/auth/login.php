<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= isset($title) ? $title : 'Dashboard' ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_PATH ?>/assets/css/styles.css">
    <link rel="icon" href="assets/images/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <style>
        /* Set the background color outside the login card */
        body {
            background: url('assets/images/bg.gif') no-repeat center center fixed;
            background-size: cover;
            background-color: #e9ecef;
            /* fallback color */
        }


        /* Apply background color to card header */
        .card-header {
            background-color: #1f2732;
            color: rgb(255, 230, 0);
            /* Ensure text is readable */
        }

        /* Increase the border radius of input fields */
        .form-control {
            border-radius: 10px;
            /* Adjust the radius as per your preference */
        }

        /* Optional: Adjust card margins to better position it on the page */
        .card {
            border-radius: 15px;
            /* Optional: Round the card corners */
        }
    </style>
</head>

<body>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <!-- Card for the login form -->
                <div class="card card-outline card-warning">
                    <div class="card-header text-center">
                        <!-- Logo -->
                        <img src="assets/images/weblogo.png" alt="Logo" class="img-fluid mb-4"
                            style="max-height: 100px;">
                        <h6>Accedi al Web Manager</h6>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>

                        <!-- Login Form -->
                        <form method="POST" action="index.php?action=login">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password"
                                    placeholder="password" required>
                            </div>
                            <button type="submit" class="btn btn-warning btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>