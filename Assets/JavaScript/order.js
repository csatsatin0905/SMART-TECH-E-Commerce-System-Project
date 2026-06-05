// Auto show/hide review button based on status constraints
document.querySelectorAll('.status-col').forEach(col => {
    const statusSpan = col.querySelector('.status');
    const reviewBtn = col.querySelector('.review-btn');

    if (statusSpan && reviewBtn) {
        if (statusSpan.classList.contains('delivered')) {
            statusSpan.style.display = 'none';
            reviewBtn.style.display = 'inline-flex';
        } else {
            reviewBtn.style.display = 'none';
            statusSpan.style.display = 'inline-flex';
        }
    }
});

// Modal Pop-up Control Logic
function openReviewModal() {
    const modal = document.getElementById('reviewModal');
    modal.classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevents scrolling on background page
}

function closeReviewModal() {
    const modal = document.getElementById('reviewModal');
    modal.classList.remove('active');
    document.body.style.overflow = 'auto'; // Restores scrolling
    
    // Clear inputs on close
    document.getElementById('reviewComment').value = '';
    resetStars();
}

// Simple star visual toggler helper logic
let selectedRating = 0;
function setRating(rating) {
    selectedRating = rating;
    const stars = document.querySelectorAll('.rating-picker .stars i');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('fa-regular');
            star.classList.add('fa-solid');
        } else {
            star.classList.remove('fa-solid');
            star.classList.add('fa-regular');
        }
    });
}
//=======================
//function resetStars() {
//    selectedRating = 0;
//    const stars = document.querySelectorAll('.rating-picker .stars i');
//    stars.forEach((star) => {
//        star.classList.remove('fa-solid');
//        star.classList.add('fa-regular');
//    });
//}

// Handler simulation when submit review comment button clicked
function submitReviewForm() {
    const comment = document.getElementById('reviewComment').value.trim();
    if (!comment) {
        alert('Please write down your feedback comment holder first.');
        return;
    }

    alert('Thank you for your feedback! Review processed.');
    closeReviewModal();
}

// Close window safely if background overlay clicked directly
window.onclick = function(event) {
    const modal = document.getElementById('reviewModal');
    if (event.target === modal) {
        closeReviewModal();
    }
}