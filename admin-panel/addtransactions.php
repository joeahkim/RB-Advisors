<?php require "includes/header.php"; ?>
<?php require "../config/config.php"; ?>

<?php

$users = [];
$result = $conn->query("SELECT id, name FROM users");

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

// Initialize variables to store messages
$success = "";
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data and sanitize it
    if (isset($_POST['user_id'], $_POST['name'], $_POST['date'], $_POST['description'], $_POST['transaction_type'], $_POST['amount'])) {
        $user_id = intval($_POST['user_id']);
        $name = htmlspecialchars($_POST['name']);
        $date = htmlspecialchars($_POST['date']);
        $description = htmlspecialchars($_POST['description']);
        $transaction_type = htmlspecialchars($_POST['transaction_type']);
        $amount = htmlspecialchars($_POST['amount']);

        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, name, date, description, transaction_type, amount) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt) {
          $stmt->bind_param("issssi", $user_id, $name, $date, $description, $transaction_type, $amount);
          if ($stmt->execute()) {
              echo "Transaction added successfully";
          } else {
              echo "Error: " . $stmt->error;
          }
          $stmt->close();
      } else {
          echo "Error: " . $conn->error;
      }
  } else {
      echo "All fields are required.";
  }
}
    // Close the statement and connection
    $conn->close();

?>


     <main id="main" class="main">

    <div class="pagetitle">
      <h1>Add a Transaction</h1>
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Transactions</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4 rounded-4">
            <div class="card-body px-4 pt-4 pb-2">

                            <!-- Display success or error message -->
                            <?php
                            if ($success) {
                                echo '<p style="color: green; font-size: 15px; font-weight:400">' . htmlspecialchars($success) . '</p>';
                            }
                            if ($error) {
                                echo '<p style="color: red; font-size: 15px; font-weight:400">' . htmlspecialchars($error) . '</p>';
                            }
                            ?>

              <form class="form-label" method="post" action="">
                <div class="row mb-3">
                  <label  class="col-form-label col-sm-2">Client Name</label>
                  <div class="col-sm-10">
                  <!-- <input type="text" class="form-control" id="client_name" name="name" list="client_name_list" required> -->
                  <!-- <datalist id="client_name_list"></datalist> -->
                  <div class="col-sm-10">
                        <select name="user_id" class="form-control" required onchange="updateName(this)">
                            <option value="">Select Client</option>
                            <?php foreach ($users as $user): ?>
                                <option value="<?= htmlspecialchars($user['id']) ?>" data-name="<?= htmlspecialchars($user['name']) ?>"><?= htmlspecialchars($user['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                </div>
                <input type="hidden" name="name" id="clientName">
                <div class="row mb-3">
                  <label for="date" class="col-sm-2 col-form-label">Date</label>
                  <div class="col-sm-10">
                    <input type="datetime-local" class="form-control" id="date" name="date">
                  </div>
                </div>
                <div class="row mb-3">
                  <label  class="col-form-label col-sm-2">Description</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" name="description">
                  </div>
                </div>
                <fieldset class="row mb-3">
                  <legend class="col-form-label col-sm-2 pt-0">Transaction Type</legend>
                  <div class="col-sm-10">
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="transaction_type" id="gridRadios1" value="deposit" checked>
                      <label class="form-check-label" for="gridRadios1">
                        Deposit
                      </label>
                    </div>
                    <div class="form-check">
                      <input class="form-check-input" type="radio" name="transaction_type" id="gridRadios2" value="withdraw">
                      <label class="form-check-label" for="gridRadios2">
                        Withdraw
                      </label>
                    </div>
                  </div>
                  
                <div class="row mb-3">
                  <label  class="col-sm-2 col-form-label">Amount</label>
                  <div class="col-sm-10">
                    <input type="number" class="form-control" name="amount">
                  </div>
                </div>
                </fieldset>
                <button type="submit" class="btn btn-primary">SUBMIT</button>
              </form>
            </div>
          </div>
        </div>
      </div>

  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
    <div class="copyright">
      &copy; Copyright <strong><script>
        document.write(new Date().getFullYear())
      </script>,<span>RB Financial Advisors </span></strong>. All Rights Reserved
    </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <script>
    function updateName(selectElement) {
        var name = selectElement.options[selectElement.selectedIndex].getAttribute('data-name');
        document.getElementById('clientName').value = name;
    }
</script>
  <!-- Vendor JS Files -->
  <script src="assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/chart.js/chart.umd.js"></script>
  <script src="assets/vendor/echarts/echarts.min.js"></script>
  <script src="assets/vendor/quill/quill.js"></script>
  <script src="assets/vendor/simple-datatables/simple-datatables.js"></script>
  <script src="assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>

  <!-- Template Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>