$(document).ready(function () {
    // Fetch CSRF token from /csrf-token route
    $.get("/csrf-token", function (data) {
        var csrfToken = data.csrf_token;

        // Set up the CSRF token for AJAX requests globally
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": csrfToken,
            },
        });

        // Hide the user info and error message initially
        $("#user-info").hide();
        $("#error-message").hide();

        // Variable to store user ID
        let userId = null;

        // Send AJAX request to fetch user data
        $.ajax({
            url: "/api/users/23", // Update the user ID here if dynamic
            method: "GET",
            dataType: "json",
            xhrFields: {
                withCredentials: true, // Send credentials with requests
            },
            success: function (user) {
                // Hide the loading spinner
                $("#loading-spinner").hide();

                // Store user ID
                userId = user.id;

                // Display user information on the dashboard
                var userInfoHtml = `
                    <h3>Welcome, ${user.name}!</h3>
                `;
                $("#user-info").prepend(userInfoHtml);
                $("#user-info").fadeIn();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // Hide the loading spinner
                $("#loading-spinner").hide();

                console.log("Error fetching user data:", errorThrown);
                $("#error-message").text(
                    "Error loading user data. Please try again later."
                );
                $("#error-message").fadeIn();
            },
        });

        // Event handler for "Get OTC Code" button
        $(document).on("click", "#get-otc-btn", function () {
            if (userId === null) {
                alert("User data is not available.");
                return;
            }

            // Fetch the OTC code
            $.ajax({
                url: "/api/users/" + userId + "/otc", // Assuming the URL to get the OTC
                method: "GET",
                success: function (response) {
                    // Set the OTC code in the modal body
                    $("#otc-modal-body").html(
                        `<strong>${response.otc}</strong>`
                    );
                    // Show the modal
                    $("#otcModal").modal("show");
                },
                error: function (xhr, status, error) {
                    if (xhr.status === 404) {
                        $("#otc-modal-body").html(
                            `<strong>No valid OTC found.</strong>`
                        );
                        // Show the modal
                        $("#otcModal").modal("show");
                    } else {
                        console.error("Error fetching OTC:", error);
                        alert("Error fetching OTC. Please try again.");
                    }
                },
            });
        });

        // Handle the logout button click event
        $("#logout-btn").click(function () {
            // Show loading spinner while making the request
            $("#loading-spinner").show();
            $("#error-message").hide(); // Hide any previous error messages

            // Send AJAX POST request to logout endpoint
            $.ajax({
                url: "/logout", // The URL to send the request to
                type: "POST",
                success: function (response) {
                    // Handle successful logout
                    $("#loading-spinner").hide();
                    // Redirect to login page or show a success message
                    window.location.href = "/login"; // Or display a message
                },
                error: function (xhr, status, error) {
                    // Handle error
                    $("#loading-spinner").hide();
                    $("#error-message")
                        .text("Logout failed. Please try again.")
                        .show();
                },
            });
        });
    });
});
