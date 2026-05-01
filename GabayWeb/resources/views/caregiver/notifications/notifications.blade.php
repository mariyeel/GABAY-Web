<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GABAY - Notifications</title>

    <link rel="stylesheet" href="../css/notification.css">
    <link rel="stylesheet" href="../css/sidebar.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">
            <div class="logo-icon">
                <img src="../images/GABAY_Logo.png">
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
            <a href="notification.html" class="active"><i class="fa-solid fa-bell"></i> Notifications</a>
            <a href="profile.html"><i class="fa-solid fa-user"></i> Profile</a>
        </nav>

        <button class="logout-btn" onclick="openModal()">
            <i class="fa-solid fa-right-from-bracket"></i> Logout
        </button>
    </aside>

    <!-- MAIN -->
    <main class="main-content">
        <header class="top-header">
            <h1>Notifications</h1>
        </header>

        <section class="notification-container glass">

            <div class="filter-tabs">
                <button class="tab active" onclick="filterNotifs('all', this)">All</button>
                <button class="tab" onclick="filterNotifs('update', this)">Update</button>
                <button class="tab" onclick="filterNotifs('alert', this)">Alerts</button>
            </div>

            <div class="notification-list" id="notifList"></div>

            <div class="pagination">
                <button class="page-arrow" onclick="prevPage()">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>

                <div id="pageNumbers"></div>

                <button class="page-arrow" onclick="nextPage()">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
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

        window.onclick = e => {
            if (e.target == modal) closeModal();
        };

        const notifications = [{
                type: "update",
                title: "Arrived at Destination",
                desc: "User arrived safely",
                icon: "fa-check",
                color: "green"
            },
            {
                type: "alert",
                title: "Low Battery",
                desc: "Battery below 20%",
                icon: "fa-battery-quarter",
                color: "red"
            },
            {
                type: "update",
                title: "Route Completed",
                desc: "Navigation finished",
                icon: "fa-route",
                color: "green"
            },
            {
                type: "alert",
                title: "GPS Lost",
                desc: "Signal unstable",
                icon: "fa-location-crosshairs",
                color: "red"
            }
        ];

        let currentFilter = 'all';
        let currentPage = 1;
        const itemsPerPage = 4;
        const maxVisiblePages = 3;

        function filterNotifs(type, btn) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            currentFilter = type;
            currentPage = 1;
            renderNotifications();
        }

        function getFiltered() {
            return notifications.filter(n => currentFilter === 'all' || n.type === currentFilter);
        }

        function renderNotifications() {
            const list = document.getElementById("notifList");
            list.innerHTML = "";

            const filtered = getFiltered();
            const start = (currentPage - 1) * itemsPerPage;
            const pageItems = filtered.slice(start, start + itemsPerPage);

            pageItems.forEach(n => {
                list.innerHTML += `
        <div class="notification-item">
            <div class="status-icon ${n.color}">
                <i class="fa-solid ${n.icon}"></i>
            </div>
            <div class="notif-details">
                <p class="notif-title">${n.title}</p>
                <p class="notif-desc">${n.desc}</p>
                <span class="tag ${n.type}">${n.type}</span>
            </div>
            <div class="notif-actions">
                <button class="action-btn delete-btn">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
        </div>`;
            });

            renderPagination(filtered.length);
        }

        function renderPagination(totalItems) {
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            const container = document.getElementById("pageNumbers");
            container.innerHTML = "";

            let start = Math.max(1, currentPage - 1);
            let end = Math.min(totalPages, start + maxVisiblePages - 1);

            for (let i = start; i <= end; i++) {
                container.innerHTML += `
            <button class="page-num ${i === currentPage ? 'active' : ''}"
                onclick="goToPage(${i})">${i}</button>`;
            }
        }

        function goToPage(p) {
            currentPage = p;
            renderNotifications();
        }

        function nextPage() {
            currentPage++;
            renderNotifications();
        }

        function prevPage() {
            currentPage--;
            renderNotifications();
        }

        document.addEventListener("click", e => {
            if (e.target.closest(".delete-btn")) {
                const item = e.target.closest(".notification-item");
                item.classList.add("delete");
                setTimeout(() => item.remove(), 400);
            }
        });

        renderNotifications();
    </script>

</body>

</html>
