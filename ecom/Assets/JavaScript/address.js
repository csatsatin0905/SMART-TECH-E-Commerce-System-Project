function editAddress() {
    document.getElementById("viewMode").style.display = "none";
    document.getElementById("editForm").style.display = "block";

    // Auto-fill inputs with current text
    document.getElementById("newName").value = document.getElementById("nameText").innerText;
    document.getElementById("newPhone").value = document.getElementById("phoneText").innerText;
    document.getElementById("newAddress").value = document.getElementById("addressText").innerText;
    document.getElementById("newLabel").value = document.getElementById("labelText").innerText;
}

function cancelEdit() {
    document.getElementById("editForm").style.display = "none";
    document.getElementById("viewMode").style.display = "block";
}

function goBackProfile() {
    window.location.href = "profile.html";
}

function saveAddress() {
    let name = document.getElementById("newName").value;
    let phone = document.getElementById("newPhone").value;
    let address = document.getElementById("newAddress").value;
    let label = document.getElementById("newLabel").value;

    if(name) document.getElementById("nameText").innerText = name;
    if(phone) document.getElementById("phoneText").innerText = phone;
    if(address) document.getElementById("addressText").innerText = address;
    if(label) document.getElementById("labelText").innerText = label;

    cancelEdit(); // Switch back to view mode
}