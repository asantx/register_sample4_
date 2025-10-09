$(document).ready(function() {
	// Regex patterns
	const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
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

	// Add invalid-feedback divs if not present
	if ($('#email').siblings('.invalid-feedback').length === 0) {
		$('#email').after('<div class="invalid-feedback"></div>');
	}
	if ($('#password').siblings('.invalid-feedback').length === 0) {
		$('#password').after('<div class="invalid-feedback"></div>');
	}

	// Form submission
	$('#login-form').on('submit', function(e) {
		e.preventDefault();

		let isValid = true;
		isValid &= validateField($('#email'), emailRegex, 'Please enter a valid email address.');
		isValid &= validateField($('#password'), passwordRegex, 'Password must be at least 6 characters.');
		if (!isValid) return;

		// Show loading
		const submitBtn = $('button[type="submit"]');
		submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Logging in...');

		// Prepare data
		const formData = {
			email: $('#email').val().trim(),
			password: $('#password').val()
		};

		// AJAX submit
		$.post('../actions/login_customer_action.php', formData, function(response) {
			if (response.status === 'success') {
				Swal.fire({
					icon: 'success',
					title: 'Login successful!',
					text: 'Redirecting...'
				}).then(() => {
					window.location.href = '../index.php';
				});
			} else {
				Swal.fire({
					icon: 'error',
					title: 'Login Failed',
					text: response.message || 'Invalid credentials.'
				});
			}
		}, 'json').fail(function() {
			Swal.fire({
				icon: 'error',
				title: 'Error',
				text: 'An error occurred. Please try again.'
			});
		}).always(function() {
			submitBtn.prop('disabled', false).html('Login');
		});
	});
});
