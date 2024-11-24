$(document).ready(function () {
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
                        alert("Generate OTC succesfully.");
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

    // Close modal when clicking on 'x'
    $("#close-modal").on("click", function () {
        $("#password-modal").hide();
        $("#password-form")[0].reset();
    });

    // Handle form submission
    $("#password-form").on("submit", function (event) {
        event.preventDefault();
        let userId = $("#user-id").val();
        let password = $("#new-password").val();
        let passwordConfirmation = $("#confirm-password").val();
        let otc = $("#otc").val();

        $.ajax({
            url: "/api/users/" + userId + "/password",
            method: "PUT",
            data: {
                password: password,
                password_confirmation: passwordConfirmation,
                otc: otc,
            },
            success: function (response) {
                alert(response.message);
                $("#password-modal").hide();
                $("#password-form")[0].reset();
                $("#otc-display").empty();
            },
            error: function (xhr, status, error) {
                let errors = xhr.responseJSON.errors || {};
                let errorMsg =
                    xhr.responseJSON.message || "Error updating password.";
                for (let key in errors) {
                    errorMsg += "\n" + errors[key];
                }
                alert(errorMsg);
            },
        });
    });
});
