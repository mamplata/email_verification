$(document).ready(function () {
    // Hide the user info and error message initially
    $("#user-info").hide();
    $("#error-message").hide();

    // Variable to store user ID
    let userId = null;

    // Send AJAX request to fetch user data
    $.ajax({
        url: "/api/users/11",
        method: "GET",
        dataType: "json",
        xhrFields: {
            withCredentials: true,
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
        // Fetch the OTC
        $.ajax({
            url: "/api/users/" + userId + "/otc",
            method: "GET",
            success: function (response) {
                // Set the OTC code in the modal body
                $("#otc-modal-body").html(`<strong>${response.otc}</strong>`);
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
});
