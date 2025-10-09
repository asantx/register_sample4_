$(document).ready(function() {
    // Regex patterns
    const nameRegex = /^[a-zA-Z\s]{1,100}$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const phoneRegex = /^\+?[\d\s\-\(\)]{7,15}$/;
    const countryRegex = /^[a-zA-Z\s]{1,30}$/;
    const cityRegex = /^[a-zA-Z\s]{1,30}$/;
    const passwordRegex = /^.{6,}$/; // At least 6 characters

    // Validate individual fields
    function validateField(field, regex, errorMsg) {
        const value = field.val().trim();
        if (!regex.test(value)) {
            field.addClass('is-invalid');
            field.siblings('.invalid-feedback').text(errorMsg).show();
            return false;
        } else {
            field.removeClass('is-invalid').addClass('is-valid');
            field.siblings('.invalid-feedback').hide();
            return true;
        }
    }

    // Check email availability
    $('#email').on('blur', function() {
        const email = $(this).val().trim();
        if (emailRegex.test(email)) {
            $.post('../actions/check_email_action.php', { email: email }, function(data) {
                if (data.exists) {
                    $('#email').addClass('is-invalid');
                    $('#email').siblings('.invalid-feedback').text('Email already exists').show();
                } else {
                    $('#email').removeClass('is-invalid').addClass('is-valid');
                    $('#email').siblings('.invalid-feedback').hide();
                }
            }, 'json');
        }
    });

    // Form submission
    $('#register-form').on('submit', function(e) {
        e.preventDefault();

        let isValid = true;

        // Validate all fields
        isValid &= validateField($('#name'), nameRegex, 'Full name must be 1-100 characters, letters and spaces only.');
        isValid &= validateField($('#email'), emailRegex, 'Please enter a valid email address.');
        isValid &= validateField($('#password'), passwordRegex, 'Password must be at least 6 characters.');
        isValid &= validateField($('#country'), countryRegex, 'Country must be 1-30 characters, letters and spaces only.');
        isValid &= validateField($('#city'), cityRegex, 'City must be 1-30 characters, letters and spaces only.');
        isValid &= validateField($('#phone_number'), phoneRegex, 'Please enter a valid phone number.');

        if (!isValid) return;

        // Show loading
        const submitBtn = $('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Registering...');

        // Prepare data
        const formData = {
            name: $('#name').val().trim(),
            email: $('#email').val().trim(),
            password: $('#password').val(),
            country: $('#country').val().trim(),
            city: $('#city').val().trim(),
            phone_number: $('#phone_number').val().trim(),
            role: $('input[name="role"]:checked').val()
        };

        // AJAX submit
        $.post('../actions/register_customer_action.php', formData, function(response) {
            if (response.status === 'success') {
                alert('Registration successful! Redirecting to login...');
                window.location.href = 'login.php';
            } else {
                alert('Error: ' + response.message);
            }
        }, 'json').fail(function() {
            alert('An error occurred. Please try again.');
        }).always(function() {
            submitBtn.prop('disabled', false).html('Register');
        });
    });
});
