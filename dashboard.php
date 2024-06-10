<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ./auth/login.php");
    exit();
}

// Retrieve the name from URL parameter
if (isset($_GET['name'])) {
    $name = $_GET['name'];
} else {
    // Default name if not provided in URL
    $name = "Guest";
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rb_advisors";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
// Fetch user's transactions
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM transactions WHERE user_id = '$user_id' ORDER BY date DESC";
$result = $conn->query($sql);

// Get the current balance
$sql_current = "SELECT SUM(amount) as current_balance FROM transactions WHERE user_id = $user_id";
$result_current = $conn->query($sql_current);
$current_balance = $result_current->fetch_assoc()['current_balance'];

// Get the balance from the previous period (e.g., last year)
$last_year = date('Y', strtotime('-1 year'));
$sql_previous = "SELECT SUM(amount) as previous_balance FROM transactions WHERE user_id = $user_id AND YEAR(date) = $last_year";
$result_previous = $conn->query($sql_previous);
$previous_balance = $result_previous->fetch_assoc()['previous_balance'];

// Calculate the percentage increase
if ($previous_balance > 0) {
    $percentage_increase = (($current_balance - $previous_balance) / $previous_balance) * 100;
} else {
    $percentage_increase = 100; // If there was no previous balance, treat it as a 100% increase
}

// Define the date ranges
$current_end_date = date('Y-m-d');
$current_start_date = date('Y-m-d', strtotime('-30 days'));
$previous_start_date = date('Y-m-d', strtotime('-60 days'));
$previous_end_date = date('Y-m-d', strtotime('-30 days'));

// Get the current withdrawals (last 30 days)
$stmt_current = $conn->prepare("SELECT SUM(amount) as current_withdrawals FROM transactions WHERE user_id = ? AND type = 'withdrawal' AND date BETWEEN ? AND ?");
$stmt_current->bind_param("iss", $user_id, $current_start_date, $current_end_date);
$stmt_current->execute();
$result_current = $stmt_current->get_result();
$current_withdrawals = $result_current->fetch_assoc()['current_withdrawals'];

// Get the previous withdrawals (30 days before the last 30 days)
$stmt_previous = $conn->prepare("SELECT SUM(amount) as previous_withdrawals FROM transactions WHERE user_id = ? AND type = 'withdrawal' AND date BETWEEN ? AND ?");
$stmt_previous->bind_param("iss", $user_id, $previous_start_date, $previous_end_date);
$stmt_previous->execute();
$result_previous = $stmt_previous->get_result();
$previous_withdrawals = $result_previous->fetch_assoc()['previous_withdrawals'];

// Calculate the percentage change
if ($previous_withdrawals > 0) {
    $percentage_change = (($current_withdrawals - $previous_withdrawals) / $previous_withdrawals) * 100;
} else {
    $percentage_change = ($current_withdrawals > 0) ? 100 : 0;
}


// Get the current deposits (last 30 days)
$stmt_current = $conn->prepare("SELECT SUM(amount) as current_deposits FROM transactions WHERE user_id = ? AND type = 'deposit' AND date BETWEEN ? AND ?");
$stmt_current->bind_param("iss", $user_id, $current_start_date, $current_end_date);
$stmt_current->execute();
$result_current = $stmt_current->get_result();
$current_deposits = $result_current->fetch_assoc()['current_deposits'];

// Get the previous deposits (30 days before the last 30 days)
$stmt_previous = $conn->prepare("SELECT SUM(amount) as previous_deposits FROM transactions WHERE user_id = ? AND type = 'deposit' AND date BETWEEN ? AND ?");
$stmt_previous->bind_param("iss", $user_id, $previous_start_date, $previous_end_date);
$stmt_previous->execute();
$result_previous = $stmt_previous->get_result();
$previous_deposits = $result_previous->fetch_assoc()['previous_deposits'];

// Calculate the percentage change
if ($previous_deposits > 0) {
    $percentage_change_deposits = (($current_deposits - $previous_deposits) / $previous_deposits) * 100;
} else {
    $percentage_change_deposits = ($current_deposits > 0) ? 100 : 0;
}

// Close the connection
$stmt_current->close();
$stmt_previous->close();
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="./assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="./assets/img/favicon.jpg">
  <title>
    RB Financial Advisors
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="./assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <link href="./assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="./assets/css/argon-dashboard.css" rel="stylesheet" />
</head>

<body class="g-sidenav-show   bg-gray-100">
  <div class="min-height-300 bg-primary position-absolute w-100"></div>
  <aside class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4 " id="sidenav-main">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
      <a class="navbar-brand m-0" href="./index.html">
        <img src="./assets/img/favicon.jpg" class="navbar-brand-img h-100" alt="main_logo">
        <span class="ms-1 font-weight-bold">RB Advisors</span>
      </a>
    </div>
    <hr class="horizontal dark mt-0">
    <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active" href="./dashboard.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-tv-2 text-primary text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Dashboard</span>
          </a>
        </li>
       
        <li class="nav-item mt-3">
          <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="./pages/profile.html">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
              <i class="ni ni-single-02 text-dark text-sm opacity-10"></i>
            </div>
            <span class="nav-link-text ms-1">Profile</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link " href="./auth/logout.php">
            <div class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
             <img src="./images/signout.png">
            </div>
            <span class="nav-link-text ms-1">Log Out</span>
          </a>
        </li>
      </ul>
    </div>

    <div class="sidenav-footer mx-3 ">
      <div class="card card-plain shadow-none" id="sidenavCard">
        <div class="card-body pt-sm-3 pt-0 overflow-auto">
      
          <div class="mt-2 mb-5 d-flex">
            <h6 class="mb-0">Light / Dark</h6>
            <div class="form-check form-switch ps-0 ms-auto my-auto">
              <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
            </div>
          </div>
         
        </div>
      </div>
    </div>
  </aside>
  <main class="main-content position-relative border-radius-lg ">
    <!-- Navbar -->
    <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur" data-scroll="false">
      <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
            <li class="breadcrumb-item text-sm text-white active" aria-current="page">Welcome To RB Financial Advisors</li>
          </ol>
          <h6 class="font-weight-bolder text-white mb-0">Hi, <span id="username"><?php echo htmlspecialchars($name); ?></span></h6>
        </nav>
         <ul class="navbar-nav  justify-content-end">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                  <i class="sidenav-toggler-line bg-white"></i>
                </div>
              </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0">
                <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
              </a>
            </li>
            <li class="nav-item dropdown pe-2 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-white p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa fa-bell cursor-pointer"></i>
              </a>
             </li>
          </ul>
        </div>
      </div>
    </nav>
    <!-- End Navbar -->
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">This Month's Deposits</p>
                    <h5 class="font-weight-bolder">
                       Ksh <?php echo htmlspecialchars(number_format($current_deposits, 2)); ?>

                    </h5>
                    <p class="mb-0">
                      <span class="text-success text-sm font-weight-bolder">  <?php echo htmlspecialchars(number_format($percentage_change_deposits, 2)); ?>%</span>
                      since  <?php echo htmlspecialchars(date('j/n/Y', strtotime($current_start_date))); ?>
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                    <i class="ni ni-money-coins text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">This Years Total Deposits</p>
                    <h5 class="font-weight-bolder">
                      Ksh 2,300,570
                    </h5>
                    <p class="mb-0">
                      <span class="text-success text-sm font-weight-bolder">+3%</span>
                      since 1/1/2024
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                    <i class="ni ni-world text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Withdrawals</p>
                    <h5 class="font-weight-bolder">
                      Ksh<?php echo htmlspecialchars(number_format($current_withdrawals, 2)); ?>
                    </h5>
                    <p class="mb-0">
                      <span class="text-danger text-sm font-weight-bolder">  <?php echo htmlspecialchars(number_format($percentage_change, 2)); ?>%</span>
                      from <?php echo htmlspecialchars(date('j/n/Y', strtotime($current_start_date))); ?>
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                    <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
          <div class="card">
            <div class="card-body p-3">
              <div class="row">
                <div class="col-8">
                  <div class="numbers">
                    <p class="text-sm mb-0 text-uppercase font-weight-bold">Account Balance</p>
                    <h5 class="font-weight-bolder">
                      Ksh <?php echo number_format($current_balance, 2); ?>
                    </h5>
                    <p class="mb-0">
                      <span class="text-success text-sm font-weight-bolder"><?php echo number_format($percentage_increase, 2); ?>% more</span> in <?php echo date('Y'); ?>
                    </p>
                  </div>
                </div>
                <div class="col-4 text-end">
                  <div class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                    <i class="ni ni-cart text-lg opacity-10" aria-hidden="true"></i>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-7 mb-lg-0 mb-4">
          <div class="card z-index-2 h-100">
            <div class="card-header pb-0 pt-3 bg-transparent">
              <h6 class="text-capitalize">Account Balance overview</h6>
              <p class="text-sm mb-0">
                <i class="fa fa-arrow-up text-success"></i>
                <span class="font-weight-bold">5% more</span> in 2024
              </p>
            </div>
            <div class="card-body p-3">
              <div class="chart">
                <canvas id="chart-line" class="chart-canvas" height="300"></canvas>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-5">
          <div class="card card-carousel overflow-hidden h-100 p-0">
            <div id="carouselExampleCaptions" class="carousel slide h-100" data-bs-ride="carousel">
              <div class="carousel-inner border-radius-lg h-100">
                <div class="carousel-item h-100 active" style="background-image: url('./assets/img/carousel-1.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-camera-compact text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">Grow with us</h5>
                    <p>Continue Saving with us and watch your Financial life mature with time.</p>
                  </div>
                </div>
                <div class="carousel-item h-100" style="background-image: url('./assets/img/carousel-2.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-bulb-61 text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">Refer your Friends</h5>
                    <p>Invite your friends and family to join the the RB financial world.</p>
                  </div>
                </div>
                <div class="carousel-item h-100" style="background-image: url('./assets/img/carousel-3.jpg');
      background-size: cover;">
                  <div class="carousel-caption d-none d-md-block bottom-0 text-start start-0 ms-5">
                    <div class="icon icon-shape icon-sm bg-white text-center border-radius-md mb-3">
                      <i class="ni ni-trophy text-dark opacity-10"></i>
                    </div>
                    <h5 class="text-white mb-1">We appreciate you</h5>
                    <p>Thank you for being a vital member of the RB Finance team.</p>
                  </div>
                </div>
              </div>
              <button class="carousel-control-prev w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
              </button>
              <button class="carousel-control-next w-5 me-3" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
              </button>
            </div>
          </div>
        </div>
      </div>
          <div class="card ">
            <div class="card-header pb-0 p-3">
              <div class="d-flex justify-content-between">
                <h6 class="mb-2">Transactions history</h6>
              </div>
            </div>
            <!-- 
            <div class="table-responsive">
              <table class="table align-items-center ">
                <tbody>
                  <tr>
                    <td class="w-30">
                      <div class="d-flex px-2 py-1 align-items-center">
                        <div class="ms-4">
                          <h6 class="text-sm mb-0">Date</h6>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="text-center">
                        <h6 class="text-sm mb-0">Description</h6>
                      </div>
                    </td>
                    <td>
                      <div class="text-center">
                        <h6 class="text-sm mb-0">Deposit</h6>
                      </div>
                    </td>
                    <td class="align-middle text-sm">
                      <div class="col text-center">
                        <h6 class="text-sm mb-0">Withdraw</h6>
                      </div>
                    </td>
                    <td class="align-middle text-sm">
                      <div class="col text-center">
                        <h6 class="text-sm mb-0">Transaction code</h6>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div> -->
            <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Description</th>
                <th>Transaction Type</th>
                <th>Amount</th>
            </tr>
        </thead>

            <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['date']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['transaction_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['amount']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No transactions found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
            </table>
          
      <footer class="footer pt-3  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                RB Financial Advisors
              </div>
            </div>
           </div>
        </div>
      </footer>
    </div>
  </main>
  <div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="fa fa-cog py-2"> </i>
    </a>
    <div class="card shadow-lg">
      <div class="card-header pb-0 pt-3 ">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Configurations</h5>
          <p>See our dashboard options.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="fa fa-close"></i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0 overflow-auto">
        <!-- Sidebar Backgrounds -->
        <div>
          <h6 class="mb-0">Sidebar Colors</h6>
        </div>
        <a href="javascript:void(0)" class="switch-trigger background-color">
          <div class="badge-colors my-2 text-start">
            <span class="badge filter bg-gradient-primary active" data-color="primary" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-dark" data-color="dark" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
            <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
          </div>
        </a>
        <!-- Sidenav Type -->
        <div class="mt-3">
          <h6 class="mb-0">Sidenav Type</h6>
          <p class="text-sm">Choose between 2 different sidenav types.</p>
        </div>
        <div class="d-flex">
          <button class="btn bg-gradient-primary w-100 px-3 mb-2 active me-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
          <button class="btn bg-gradient-primary w-100 px-3 mb-2" data-class="bg-default" onclick="sidebarType(this)">Dark</button>
        </div>
        <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
             <hr class="horizontal dark my-sm-4">
        <div class="mt-2 mb-5 d-flex">
          <h6 class="mb-0">Light / Dark</h6>
          <div class="form-check form-switch ps-0 ms-auto my-auto">
            <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
          </div>
        </div>
        </div>
    </div>
  </div>
  <!--   Core JS Files   -->
  <script src="./assets/js/core/popper.min.js"></script>
  <script src="./assets/js/core/bootstrap.min.js"></script>
  <script src="./assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="./assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="./assets/js/plugins/chartjs.min.js"></script>
  <script>
    var ctx1 = document.getElementById("chart-line").getContext("2d");

    var gradientStroke1 = ctx1.createLinearGradient(0, 230, 0, 50);

    gradientStroke1.addColorStop(1, 'rgba(94, 114, 228, 0.2)');
    gradientStroke1.addColorStop(0.2, 'rgba(94, 114, 228, 0.0)');
    gradientStroke1.addColorStop(0, 'rgba(94, 114, 228, 0)');
    new Chart(ctx1, {
      type: "line",
      data: {
        labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
        datasets: [{
          label: "Mobile apps",
          tension: 0.4,
          borderWidth: 0,
          pointRadius: 0,
          borderColor: "#5e72e4",
          backgroundColor: gradientStroke1,
          borderWidth: 3,
          fill: true,
          data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
          maxBarThickness: 6

        }],
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false,
          }
        },
        interaction: {
          intersect: false,
          mode: 'index',
        },
        scales: {
          y: {
            grid: {
              drawBorder: false,
              display: true,
              drawOnChartArea: true,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              padding: 10,
              color: '#fbfbfb',
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
          x: {
            grid: {
              drawBorder: false,
              display: false,
              drawOnChartArea: false,
              drawTicks: false,
              borderDash: [5, 5]
            },
            ticks: {
              display: true,
              color: '#ccc',
              padding: 20,
              font: {
                size: 11,
                family: "Open Sans",
                style: 'normal',
                lineHeight: 2
              },
            }
          },
        },
      },
    });
  </script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="../assets/js/argon-dashboard.min.js?v=2.0.4"></script>
</body>

</html>