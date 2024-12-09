$(document).ready(function () {
    // Fetch CSRF token via AJAX
    $.get("/csrf-token", function (data) {
        var csrfToken = data.csrf_token;

        // Set up global AJAX settings to include CSRF token in all requests
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
        });

        // Fetch users via AJAX
        $.ajax({
            url: "/api/users", // Replace with your API endpoint
            method: "GET",
            success: function (response) {
                // Populate table with user data
                let usersTable = $("#users-table tbody");
                usersTable.empty(); // Clear existing rows
                response.forEach((user) => {
                    usersTable.append(`
                        <tr>
                            <td>${user.name}</td>
                            <td><button class="change-password" data-id="${user.id}">Change Password</button></td>
                        </tr>
                    `);
                });

                let userId;

                $(".change-password").on("click", function () {
                    // Show the password change modal
                    userId = $(this).data("id");
                    $("#user-id").val(userId);
                    $("#password-modal").show();
                });

                // Add event listener for "Change Password" button
                $(".generate-otc").on("click", function () {
                    // Generate the OTC
                    $.ajax({
                        url: "/api/users/" + userId + "/generate-otc",
                        method: "GET",
                        success: function (response) {
                            alert("Generate OTC successfully.");
                        },
                        error: function (xhr, status, error) {
                            console.error("Error generating OTC:", error);
                            alert("Error generating OTC. Please try again.");
                        },
                    });
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching users:", error);
            },
        });

        // Logout functionality
        $("#logout-btn").click(function () {
            // Send AJAX POST request to logout
            $.ajax({
                url: "/logout", // The URL to send the request to
                type: "POST",
                success: function (response) {
                    // Redirect to login page after logout
                    window.location.href = "/login"; // Or show a success message
                },
                error: function (xhr, status, error) {
                    // Handle error
                    alert("Logout failed. Please try again.");
                },
            });
        });
    });
});
