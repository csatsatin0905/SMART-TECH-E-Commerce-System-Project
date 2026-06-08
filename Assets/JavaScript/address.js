function goBackProfile() {
    window.location.href = "profile.php";
}

const provinceCities = {
    "Abra": [],
    "Agusan del Norte": ["Butuan City", "Cabadbaran City"],
    "Agusan del Sur": ["Bayugan City"],
    "Aklan": [],
    "Albay": ["Legazpi City", "Ligao City", "Tabaco City"],
    "Antique": [],
    "Apayao": [],
    "Aurora": [],
    "Basilan": ["Isabela City", "Lamitan City"],
    "Bataan": ["Balanga City"],
    "Batanes": [],
    "Batangas": ["Batangas City", "Lipa City", "Santo Tomas City", "Tanauan City"],
    "Benguet": ["Baguio City"],
    "Biliran": [],
    "Bohol": ["Tagbilaran City"],
    "Bukidnon": ["Malaybalay City", "Valencia City"],
    "Bulacan": ["Baliwag City", "Malolos City", "Meycauayan City", "San Jose del Monte City"],
    "Cagayan": ["Tuguegarao City"],
    "Camarines Norte": [],
    "Camarines Sur": ["Iriga City", "Naga City"],
    "Camiguin": [],
    "Capiz": ["Roxas City"],
    "Catanduanes": [],
    "Cavite": [
        "Bacoor City",
        "Carmona City",
        "Cavite City",
        "Dasmariñas City",
        "General Trias City",
        "Imus City",
        "Tagaytay City",
        "Trece Martires City"
    ],
    "Cebu": [
        "Bogo City",
        "Carcar City",
        "Cebu City",
        "Danao City",
        "Lapu-Lapu City",
        "Mandaue City",
        "Naga City",
        "Talisay City",
        "Toledo City"
    ],
    "Cotabato": ["Kidapawan City"],
    "Davao de Oro": [],
    "Davao del Norte": ["Panabo City", "Samal City", "Tagum City"],
    "Davao del Sur": ["Davao City", "Digos City"],
    "Davao Occidental": [],
    "Davao Oriental": ["Mati City"],
    "Dinagat Islands": [],
    "Eastern Samar": ["Borongan City"],
    "Guimaras": [],
    "Ifugao": [],
    "Ilocos Norte": ["Batac City", "Laoag City"],
    "Ilocos Sur": ["Candon City", "Vigan City"],
    "Iloilo": ["Iloilo City", "Passi City"],
    "Isabela": ["Cauayan City", "Ilagan City", "Santiago City"],
    "Kalinga": ["Tabuk City"],
    "La Union": ["San Fernando City"],
    "Laguna": [
        "Biñan City",
        "Cabuyao City",
        "Calamba City",
        "San Pablo City",
        "San Pedro City",
        "Santa Rosa City"
    ],
    "Lanao del Norte": ["Iligan City"],
    "Lanao del Sur": ["Marawi City"],
    "Leyte": ["Baybay City", "Ormoc City", "Tacloban City"],
    "Maguindanao del Norte": ["Cotabato City"],
    "Maguindanao del Sur": [],
    "Marinduque": [],
    "Masbate": ["Masbate City"],
    "Metro Manila": [
        "Caloocan City",
        "Las Piñas City",
        "Makati City",
        "Malabon City",
        "Mandaluyong City",
        "Manila City",
        "Marikina City",
        "Muntinlupa City",
        "Navotas City",
        "Parañaque City",
        "Pasay City",
        "Pasig City",
        "Quezon City",
        "San Juan City",
        "Taguig City",
        "Valenzuela City",
        "Pateros"
    ],
    "Misamis Occidental": ["Oroquieta City", "Ozamiz City", "Tangub City"],
    "Misamis Oriental": ["Cagayan de Oro City", "El Salvador City", "Gingoog City"],
    "Mountain Province": [],
    "Negros Occidental": [
        "Bacolod City",
        "Bago City",
        "Cadiz City",
        "Escalante City",
        "Himamaylan City",
        "Kabankalan City",
        "La Carlota City",
        "Sagay City",
        "San Carlos City",
        "Silay City",
        "Sipalay City",
        "Talisay City",
        "Victorias City"
    ],
    "Negros Oriental": [
        "Bais City",
        "Bayawan City",
        "Canlaon City",
        "Dumaguete City",
        "Guihulngan City",
        "Tanjay City"
    ],
    "Northern Samar": [],
    "Nueva Ecija": [
        "Cabanatuan City",
        "Gapan City",
        "Muñoz City",
        "Palayan City",
        "San Jose City"
    ],
    "Nueva Vizcaya": [],
    "Occidental Mindoro": [],
    "Oriental Mindoro": ["Calapan City"],
    "Palawan": ["Puerto Princesa City"],
    "Pampanga": ["Angeles City", "Mabalacat City", "San Fernando City"],
    "Pangasinan": ["Alaminos City", "Dagupan City", "San Carlos City", "Urdaneta City"],
    "Quezon": ["Lucena City", "Tayabas City"],
    "Quirino": [],
    "Rizal": ["Antipolo City"],
    "Romblon": [],
    "Samar": ["Calbayog City"],
    "Sarangani": [],
    "Siquijor": [],
    "Sorsogon": ["Sorsogon City"],
    "South Cotabato": ["General Santos City", "Koronadal City"],
    "Southern Leyte": ["Maasin City"],
    "Sultan Kudarat": ["Tacurong City"],
    "Sulu": [],
    "Surigao del Norte": ["Surigao City"],
    "Surigao del Sur": ["Bislig City", "Tandag City"],
    "Tarlac": ["Tarlac City"],
    "Tawi-Tawi": [],
    "Zambales": ["Olongapo City"],
    "Zamboanga del Norte": ["Dapitan City", "Dipolog City"],
    "Zamboanga del Sur": ["Pagadian City", "Zamboanga City"],
    "Zamboanga Sibugay": []
};

const viewMode = document.getElementById("viewMode");
const editMode = document.getElementById("editMode");

const addressSelectSection = document.getElementById("addressSelectSection");
const selectedAddress = document.getElementById("selectedAddress");

const editForm = document.getElementById("editForm");

const nameText = document.getElementById("nameText");
const phoneText = document.getElementById("phoneText");
const addressText = document.getElementById("addressText");

const addressId = document.getElementById("addressId");
const newFullName = document.getElementById("newFullName");
const newPhone = document.getElementById("newPhone");
const newAddressLine = document.getElementById("newAddressLine");
const newProvince = document.getElementById("newProvince");
const newCity = document.getElementById("newCity");
const newPostalCode = document.getElementById("newPostalCode");

document.addEventListener("DOMContentLoaded", function () {
    loadProvinces();
});

function showEditMode() {
    viewMode.style.display = "none";
    editMode.style.display = "block";

    addressSelectSection.style.display = "block";
    editForm.style.display = "none";
}

function cancelEdit() {
    editMode.style.display = "none";
    viewMode.style.display = "block";

    addressSelectSection.style.display = "block";
    editForm.style.display = "none";
}

function showAddAddressForm() {
    addressSelectSection.style.display = "none";
    editForm.style.display = "block";

    addressId.value = "";
    newFullName.value = "";
    newPhone.value = "";
    newAddressLine.value = "";
    newProvince.value = "";
    newCity.innerHTML = `<option value="">Select City</option>`;
    newCity.disabled = true;
    newPostalCode.value = "";
}

function backToAddressSelect() {
    editForm.style.display = "none";
    addressSelectSection.style.display = "block";
}

async function useSelectedAddress() {
    const selectedOption = selectedAddress.options[selectedAddress.selectedIndex];

    if (!selectedAddress.value) {
        alert("Please select an address first.");
        return;
    }

    const id = selectedOption.dataset.addressId;
    const formData = new FormData();
    formData.append('address_id', id);

    try {
        const response = await fetch('../Actions/Address/save_default_address.php', {
            method: "POST",
            body: formData
        });
        const data = await response.json();
        if (data.success) {
            location.reload();
        }

    } catch (error) {
        alert('Error');
    }
}

function loadProvinces() {
    newProvince.innerHTML = `<option value="">Select Province</option>`;

    Object.keys(provinceCities).forEach(function (province) {
        const option = document.createElement("option");
        option.value = province;
        option.textContent = province;
        newProvince.appendChild(option);
    });
}

newProvince.addEventListener("change", function () {
    loadCitiesByProvince(newProvince.value);
});

function loadCitiesByProvince(province) {
    const cities = provinceCities[province] || [];

    newCity.innerHTML = `<option value="">Select City</option>`;

    if (cities.length === 0) {
        newCity.innerHTML = `<option value="">No city available</option>`;
        newCity.disabled = true;
        return;
    }

    cities.forEach(function (city) {
        const option = document.createElement("option");
        option.value = city;
        option.textContent = city;
        newCity.appendChild(option);
    });

    newCity.disabled = false;
}

function saveAddress() {
    alert("Connect this to your PHP INSERT address process.");
    
}

function goBack() {
    window.history.back();
}