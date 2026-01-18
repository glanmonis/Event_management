<?php
session_start();
include 'db_connect.php';
// Add Decoration
if (isset($_POST['add'])) {
    $event_type = $_POST['event_type'];
    $price = $_POST['price'];
    // Process the image upload
    $target_dir = "images/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    // Insert into database
    $stmt = $conn->prepare("INSERT INTO decorations (event_type, image_path, price) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $event_type, $target_file, $price);
    $stmt->execute();
    $stmt->close();
    // Redirect to avoid resubmission on refresh
    header('Location: manage_decorations.php');
    exit();
}
// Delete Decoration
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM decorations WHERE id=$id");
    header("Location: manage_decorations.php");
    exit();
}
// Edit Decoration
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $price = $_POST['price'];

    if (!empty($_FILES["image"]["name"])) {
        $target_file = "images/" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        $conn->query("UPDATE decorations SET image_path='$target_file', price=$price WHERE id=$id");
    } else {
        $conn->query("UPDATE decorations SET price=$price WHERE id=$id");
    }
}
// Handle search and pagination
$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;
// Total rows for pagination
$count_query = "SELECT COUNT(*) AS total FROM decorations";
if ($search) {
    $count_query .= " WHERE event_type LIKE '%$search%'";
}
$count_result = $conn->query($count_query);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch filtered, paginated results
$sql = "SELECT * FROM decorations";
if ($search) {
    $sql .= " WHERE event_type LIKE '%$search%'";
}
$sql .= " ORDER BY id DESC LIMIT $limit OFFSET $offset";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Decorations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
            background: linear-gradient(to right, #e2e2e2, #c9d6ff);
        }

        .card {
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 40px;
        }

        h2, h3 {
            color: #2da0a8;
            margin-bottom: 10px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        select, input[type="number"], input[type="file"] {
            width: 40%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            background: linear-gradient(to right, #5c6bc0, #2da0a8);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            
            transform 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3);
            background: linear-gradient(to right, #2da0a8, #5c6bc0);
        }
        .back-button {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #5c6bc0;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #3f51b5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        img {
            width: 100px;
            border-radius: 8px;
        }

        .pagination {
            text-align: center;
            margin-top: 20px;
        }

        .pagination a, .pagination strong {
            margin: 0 5px;
            text-decoration: none;
            color: #333;
        }

        .pagination strong {
            font-weight: bold;
            color: #5c6bc0;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
        }

        .form-section {
            flex: 1;
            min-width: 280px;
        }

        #preview-img {
            display: none;
            max-width: 100%;
            margin-top: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        a.delete-link {
            color: red;
            text-decoration: none;
        }

        a.delete-link:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('preview-img');
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
            }
        }
    </script>
</head>
    <body>
        <div class="card">
            <h2>🎨 Manage Decorations</h2>
            <div class="form-row">
                <!-- Add Decoration -->
                <div class="form-section">
                    <h3>Add Decoration</h3>
                    <form method="POST" enctype="multipart/form-data">
                <label>Event Type:</label>
                <select name="event_type" required>
                    <option value="Birthday">Birthday</option>
                    <option value="Engagement">Engagement</option>
                    <option value="Roce">Roce</option>
                    <option value="Marriage">Marriage</option>
                    <option value="Haldi">Haldi</option>
                    <option value="Mehendi">Mehendi</option>
                    <option value="Reception">Reception</option>
                    <option value="Baby Shower">Baby Shower</option>
                    <option value="Cradle Ceremony">Cradle Ceremony</option>
                </select>
                        <label>Image:</label>
                        <input type="file" name="image" accept="image/*" required onchange="previewImage(event)">
                        <img id="preview-img" src="">

                        <label>Price (₹):</label>
                        <input type="number" name="price" step="100.00" required placeholder="e.g. 1500">

                        <input type="submit" name="add" value="Add Decoration">
                    </form>
                </div>

                <!-- Search Decoration -->
                <div class="form-section">
                    <h3>Search Decoration</h3>
                    <form method="GET">
                        <label>Search by Event Type:</label>
                        <input type="text" name="search" placeholder="e.g. Birthday" value="<?php echo htmlspecialchars($search); ?>">
                        <input type="submit" value="Search">
                    </form>
                    <a href="admin_dashboard.php" class="back-button">⬅ Back</a>
                </div>
            </div>
        </div>
        <!-- Decorations Table -->
        <h2>All Decorations</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Event Type</th>
                <th>Image</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <form method="POST" enctype="multipart/form-data">
                        <td>
                            <?php echo $row['id']; ?>
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        </td>
                        <td><?php echo htmlspecialchars($row['event_type']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Decoration Image"></td>
                        <td><input type="number" name="price" value="<?php echo $row['price']; ?>" step="0.01" required></td>
                        <td>
                            <input type="file" name="image" accept="image/*">
                            <input type="submit" name="edit" value="Update">
                            <br>
                            <a href="?delete=<?php echo $row['id']; ?>" class="delete-link" onclick="return confirm('Delete this decoration?')">Delete</a>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </table>
        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
            <?php endif; ?>
            <strong>Page <?php echo $page; ?> of <?php echo $total_pages; ?></strong>
            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
            <?php endif; ?>
        </div>
    </body>
</html>
