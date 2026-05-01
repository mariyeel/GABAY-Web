<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GABAY - Profile</title>

    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <aside class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <img src="../images/GABAY_Logo.png" alt="GABAY Logo">
            </div>
            <span class="logo-text">GABAY</span>
        </div>

        <div class="user-profile">
            <img src="../images/GABAY_Logo.png" class="avatar">
            <h3>Glaiza Mae Corbs</h3>
            <p>New York, USA</p>
        </div>

        <nav class="nav-menu">
            <a href="dashboard.html"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="tracking.html"><i class="fa-solid fa-location-crosshairs"></i> Tracking</a>
            <a href="reports.html"><i class="fa-solid fa-route"></i> Reports</a>
            <a href="notification.html"><i class="fa-solid fa-bell"></i> Notifications</a>
            <a href="profile.html" class="active"><i class="fa-solid fa-user"></i> Profile</a>
        </nav>

        <button class="logout-btn" onclick="openModal()">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </button>
    </aside>

    <main class="main-content">
        <header class="top-header">
            <h1>Dashboard</h1>
        </header>

        <section class="Profile-container glass">

            <div class="user-profile">
                <img src="../images/GABAY_Logo.png" class="avatar">
                <h3>Glaiza Mae Corbs</h3>
                <p class="location">New York, USA</p>
                <p class="role">Caregiver</p>
            </div>

            <div class="personal-info">

                <h2>Personal Information</h2>

                <div class="gender-wrapper">
                    <div class="form-options">
                        <label class="gender-radio">
                            <input type="radio" name="gender">
                            <span class="circle"></span>
                            Female
                        </label>

                        <label class="gender-radio">
                            <input type="radio" name="gender">
                            <span class="circle"></span>
                            Male
                        </label>
                    </div>
                </div>
                <div class="row">
                    <div class="input-group">
                        <label>First Name</label>
                        <input type="text" placeholder="Enter your First Name">
                    </div>

                    <div class="input-group">
                        <label>Last Name</label>
                        <input type="text" placeholder="Enter your Last Name">
                    </div>
                </div>

                <div class="row">
                    <div class="input-group">
                        <label>Email</label>
                        <input type="email" placeholder="Enter your Email">
                    </div>

                    <div class="input-group">
                        <label>Address</label>
                        <input type="text" placeholder="Enter your Address">
                    </div>

                </div>


                <div class="row">
                    <div class="input-group">
                        <label>Phone Number</label>
                        <input type="tel" placeholder="+63 9XX XXX XXXX" maxlength="11">
                    </div>

                    <div class="input-group">
                        <label>Date of Birth</label>
                        <input type="date">
                    </div>
                </div>

                <div class="row">
                    <div class="input-group">
                        <label>Location</label>
                        <select>
                            <option disabled selected>Select Location</option>
                            <option>Davao City</option>
                            <option>Cebu</option>
                            <option>Manila</option>
                        </select>
                    </div>

                    <div class="input-group">
                        <label>Postal Code</label>
                        <input type="text" placeholder="Enter your Postal Code">
                    </div>
                </div>

                <div class="button-row">
                    <button class="btn btn-discard">Discard Changes</button>
                    <button class="btn btn-save">Save Changes</button>
                </div>

            </div>

        </section>

    </main>


    <!-- MODAL -->
    <div class="modal-overlay" id="logoutModal">
        <div class="glass-card modal-content">
            <i class="fa-solid fa-right-from-bracket modal-icon"></i>
            <h2>Logout Account?</h2>
            <p>Are you sure you want to logout your account?</p>

            <div class="modal-buttons">
                <button class="btn-cancel" onclick="closeModal()">Cancel</button>
                <button class="btn-logout-confirm">Logout</button>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('logoutModal');

        function openModal() {
            modal.style.display = 'flex';
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                closeModal();
            }
        }
    </script>
</body>

</html>
