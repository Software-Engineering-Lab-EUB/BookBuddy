<?php
session_start();
include "db.php";
include "header.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .card {
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.05);
        }

        .card-img-top {
            height: 200px; /* Set a fixed height for images */
            object-fit: cover; /* Ensure images cover the area without distortion */
        }
    </style>
    <title>Book Store</title>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-center">Welcome, <?= htmlspecialchars($_SESSION["user_name"] ?? "Guest"); ?>!</h2>
    <p class="text-center">Explore our collection of books and shop your favorites.</p>

    <!-- Search Form -->
    <form method="GET" action="index.php" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Search by title, author, or price" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-primary" type="submit">Search</button>
            </div>
        </div>
    </form>

    <div class="row">
        <?php
        // Fetching books based on search criteria
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $searchQuery = "SELECT * FROM books WHERE title LIKE ? OR author LIKE ? OR price LIKE ?";
        $stmt = $conn->prepare($searchQuery);
        $searchTerm = '%' . $search . '%';
        $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="<?= htmlspecialchars($row['image'] ?? 'Lore.jpg'); ?>" class="card-img-top thumbnail" alt="<?= htmlspecialchars($row['title'] ?? 'No Title'); ?>" data-toggle="modal" data-target="#imageModal" data-image="<?= htmlspecialchars($row['image']); ?>">
                <div class="card-body">
                <h5 class="card-title">
                    <a href="description.php?id=<?= $row['id']; ?>" class="text-decoration-none text-Blue">
                        <?= htmlspecialchars($row["title"] ?? 'No Title'); ?>
                    </a>
                </h5>
                    <p class="card-text">Author: <?= htmlspecialchars($row["author"] ?? 'Unknown Author'); ?></p>
                    <p class="card-text">Price: $<?= htmlspecialchars($row["price"] ?? '0.00'); ?></p>
                    
                    <?php if (isset($_SESSION["user_id"])): ?>
                        <a href="cart.php?id=<?= $row["id"]; ?>" class="btn btn-primary">Add to Cart</a>
                    <?php else: ?>
                        <button class="btn btn-warning" onclick="redirectToLogin();">Add to Cart</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; else: ?>
            <p class="text-center">No books found matching your search.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Modal for displaying full-size image -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Book Image</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Redirect to Login -->
<script>
function redirectToLogin() {
    alert("You are not logged in. Please log in first.");
    window.location.href = "login.php"; // Redirect to the login page
}
</script>

<!-- Include jQuery and Bootstrap JS (if not already included) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        $('.thumbnail').on('click', function() {
            var imageSrc = $(this).data('image');
            $('#modalImage').attr('src', imageSrc);
        });
    });
</script>

<?php include "footer.php"; ?>
</body>
</html>
