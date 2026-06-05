// --PROFILE JSCRIPT--

// DEFAULT PROFILE DATA
let profileData = {
    name: "User",
    phone: "06967676711",
    gender: "Male",
    dob: "2000-01-01"
};

// LOAD SAVED DATA
window.onload = function () {
    const savedData = localStorage.getItem("profileData");

    if (savedData) {
        profileData = JSON.parse(savedData);
    }

    updateProfileUI();
};

// UPDATE UI
function updateProfileUI() {
    document.getElementById("displayName").innerText = profileData.name;
    document.getElementById("displayPhone").innerText = profileData.phone;
    document.getElementById("displayGender").innerText = profileData.gender;
    document.getElementById("displayDob").innerText = profileData.dob;

    document.getElementById("profileName").innerText = profileData.name;
    document.getElementById("sidebarName").innerText = profileData.name;
    document.getElementById("welcomeText").innerText = `Welcome back, ${profileData.name}!`;
}

// OPEN MODAL
function openEditModal() {
    document.getElementById("editModal").style.display = "flex";

    document.getElementById("editName").value = profileData.name;
    document.getElementById("editPhone").value = profileData.phone;
    document.getElementById("editGender").value = profileData.gender;
    document.getElementById("editDob").value = profileData.dob;
}

// CLOSE MODAL
function closeEditModal() {
    document.getElementById("editModal").style.display = "none";
}

// SAVE PROFILE
function saveProfile() {
    profileData.name = document.getElementById("editName").value;
    profileData.phone = document.getElementById("editPhone").value;
    profileData.gender = document.getElementById("editGender").value;
    profileData.dob = document.getElementById("editDob").value;

    // SAVE TO LOCAL STORAGE
    localStorage.setItem("profileData", JSON.stringify(profileData));

    updateProfileUI();
    closeEditModal();

    // SHOW SUCCESS MODAL
    document.getElementById("successModal").style.display = "flex";
}

// CLOSE SUCCESS MODAL
function closeSuccessModal() {
    document.getElementById("successModal").style.display = "none";
}

// LOGOUT FUNCTIONS
function logoutUser() {
    document.getElementById("logoutModal").style.display = "flex";
}

function closeLogoutModal() {
    document.getElementById("logoutModal").style.display = "none";
}

function confirmLogout() {
    window.location.href = "log-in.html";
}
