const modal = document.getElementById("logoutModal");

// OPEN
function openModal() {
    modal.classList.add("show");
}

// CLOSE
function closeModal() {
    modal.classList.remove("show");
}

// CLICK OUTSIDE TO CLOSE
window.addEventListener("click", function (e) {
    if (e.target === modal) {
        closeModal();
    }
});

// LOGOUT
function confirmLogout() {
    localStorage.clear();
    sessionStorage.clear();
    window.location.href = "../index.html";
}