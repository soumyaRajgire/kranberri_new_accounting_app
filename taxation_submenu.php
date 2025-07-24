<?php
$currentPage = basename($_SERVER['PHP_SELF']);
$user_id = $_SESSION['id'] ?? null;
$user_role = $_SESSION['role'] ?? null;
$branch_id = $_SESSION['branch_id'] ?? null;
$selected_gstin = $_SESSION['sel_gstin'] ?? '';

// Fetch GSTINs if not already fetched
$gstins = $_SESSION['gstins'] ?? [];

// Only fetch GSTINs if they are not in session
if (empty($gstins)) {
    $gstins = [];

    include("config.php"); // Ensure DB connection is available

    if ($user_role == 'superadmin') {
        $sql = "SELECT DISTINCT GST FROM add_branch WHERE GST IS NOT NULL AND GST != ''";
        $result = $conn->query($sql);
    } else {
        $sql = "SELECT DISTINCT GST FROM add_branch WHERE branch_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $branch_id);
        $stmt->execute();
        $result = $stmt->get_result();
    }

    while ($row = $result->fetch_assoc()) {
        $gstins[] = $row['GST'];
    }

    // Store GSTINs in session for faster access
    $_SESSION['gstins'] = $gstins;
}

// Auto-select first GSTIN if none is set
if (empty($selected_gstin) && !empty($gstins)) {
    $_SESSION['sel_gstin'] = $gstins[0];
    $selected_gstin = $gstins[0];
}
?>


<div class="card">
    <div class="row align-items-center">
        <div class="col-md-12">
            <ul class="ul_filter pl-0 mb-0 nav nav-pills nav-pills-sm nav-pills-label nav-pills-bold mt-0 dash_nav" role="tablist">
                <li class="nav-item">
                    <a class="nav-link exp_li quotes <?php echo ($currentPage == 'gst-compliance.php') ? 'active' : ''; ?>" data-item="quotes" href="gst-compliance.php">GST Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link exp_li invoice <?php echo ($currentPage == 'itc-details.php') ? 'active' : ''; ?>" data-item="invoice" href="itc-details.php">ITC Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link exp_li bos <?php echo ($currentPage == '') ? 'active' : ''; ?>" data-item="bos" href="">e-invoice</a>
                </li>
                <li class="nav-item searchfilter_li">
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">e-Way bill</a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item create" data-doc="domestic-invoice" href="">Generate e-Way bill</a>
                            <a class="dropdown-item create" data-doc="bill" href="">Manage e-Way bill</a>
                            <a class="dropdown-item create" data-doc="credit" href="">Eligible Invoices</a>
                        </div>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link exp_li receipts <?php echo ($currentPage == '') ? 'active' : ''; ?>" data-item="receipts" href="">Reports</a>
                </li>

                <!-- GSTIN Display (Dropdown for Admin, Static Text for Others) -->
      <li class="nav-item">
    <?php if ($currentPage !== 'gstr3b-prepare.php' && $currentPage !== 'gstr1-prepare.php'): ?>
        <?php if ($user_role == 'superadmin'): ?>
            <!-- Superadmin sees the GSTIN dropdown -->
            <form method="post" class="form-inline">
                <label for="gstin_select" class="mr-2">Select GSTIN:</label>
                <select name="gstin_select" id="gstin_select" class="form-control form-control-sm" onchange="this.form.submit()">
                    <?php foreach ($gstins as $gst): ?>
                        <option value="<?php echo htmlspecialchars($gst, ENT_QUOTES, 'UTF-8'); ?>" 
                            <?php echo ($gst == $selected_gstin) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($gst, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php else: ?>
            <!-- Normal users see only their assigned GSTIN -->
            <span class="nav-link disabled">GSTIN: <?php echo htmlspecialchars($selected_gstin, ENT_QUOTES, 'UTF-8'); ?></span>
        <?php endif; ?>
    <?php else: ?>
        <!-- GSTIN displayed as text for `gstr3b-prepare.php` & `gstr1-prepare.php` -->
        <span class="nav-link disabled">GSTIN: <?php echo htmlspecialchars($selected_gstin, ENT_QUOTES, 'UTF-8'); ?></span>
    <?php endif; ?>
</li>



            </ul>
        </div>
    </div>
</div>
