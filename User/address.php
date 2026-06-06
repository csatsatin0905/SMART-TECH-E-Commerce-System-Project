<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Address | Smart Tech</title>

<link rel="stylesheet" href="../Assets/CSS/address.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="../Assets/JavaScript/script.js" defer></script>
<script src="../Assets/JavaScript/address.js" defer></script>
</head>

<body>

<div class="glow-orb orb-1"></div>
<div class="glow-orb orb-2"></div>

<div class="info-card">
    <h2 class="page-title">Delivery Address</h2>

    <div id="viewMode">
        <div class="info-row">
            <span>Full Name</span>
            <p id="nameText">Mark Francis G. Lampit</p>
        </div>

        <div class="info-row">
            <span>Phone Number</span>
            <p id="phoneText">09329676767</p>
        </div>

        <div class="info-row">
            <span>Address</span>
            <p id="addressText">Block 5 Lot 25, Santa Rosa 1, Saint Rose Village, Noveleta, Cavite 4105</p>
        </div>

        <div class="info-row">
            <span>Label</span>
            <p id="labelText">Home</p>
        </div>

        <div class="btn-group">
            <button class="btn-secondary" onclick="goBack()">
                <i class="fa-solid fa-arrow-left"></i> Back
            </button>
            <button class="btn-primary" onclick="editAddress()">
                <i class="fa-solid fa-pen"></i> Edit
            </button>
        </div>
    </div>

    <div class="edit-form" id="editForm">
        <input type="text" id="newName" placeholder="Enter Full Name">
        <input type="text" id="newPhone" placeholder="Enter Phone Number">
        <input type="text" id="newAddress" placeholder="Enter Complete Address">
        <input type="text" id="newLabel" placeholder="e.g., Home or Work">
        
        <div class="btn-group">
            <button class="btn-secondary" onclick="cancelEdit()">Cancel</button>
            <button class="btn-primary" onclick="saveAddress()"><i class="fa-solid fa-check"></i> Save</button>
        </div>
    </div>

</div>

</body>
</html>