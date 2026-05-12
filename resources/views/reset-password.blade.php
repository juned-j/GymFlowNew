<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg p-8">
        <h1 class="text-2xl font-bold text-center mb-6">
            Reset Password
        </h1>
        <form id="resetForm" class="space-y-4">
            <div>
                <label class="block mb-2 text-sm font-medium">
                    New Password
                </label>
                <input
                    type="password"
                    id="password"
                    placeholder="Enter new password"
                    class="w-full border rounded-lg px-4 py-3"
                    required />
            </div>
            <div>
                <label class="block mb-2 text-sm font-medium">
                    Confirm Password
                </label>
                <input
                    type="password"
                    id="confirmPassword"
                    placeholder="Confirm password"
                    class="w-full border rounded-lg px-4 py-3"
                    required />
            </div>

            <div
                id="error"
                class="hidden bg-red-100 text-red-600 px-4 py-3 rounded-lg text-sm"></div>

            <div
                id="success"
                class="hidden bg-green-100 text-green-700 px-4 py-3 rounded-lg text-sm"></div>

            <button
                type="submit"
                id="submitBtn"
                class="w-full bg-black text-white py-3 rounded-lg font-medium">
                Reset Password
            </button>

        </form>

    </div>

    <script>
        const form = document.getElementById('resetForm');
        const errorDiv = document.getElementById('error');
        const successDiv = document.getElementById('success');
        const submitBtn = document.getElementById('submitBtn');
        const urlParams = new URLSearchParams(window.location.search);
        const token = urlParams.get('token');
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            errorDiv.classList.add('hidden');
            successDiv.classList.add('hidden');
            const password = document.getElementById('password').value;
            const confirmPassword =
                document.getElementById('confirmPassword').value;
            if (!token) {
                errorDiv.innerText = 'Invalid or missing token';
                errorDiv.classList.remove('hidden');
                return;
            }
            if (password !== confirmPassword) {
                errorDiv.innerText = 'Passwords do not match';
                errorDiv.classList.remove('hidden');
                return;
            }
            try {
                submitBtn.disabled = true;
                submitBtn.innerText = 'Resetting...';
                const response = await fetch(
                    'https://api.ptbuddy.io/api/v1/auth/reset-password', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            token,
                            password,
                        }),
                    }
                );
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.message || 'Something went wrong');
                }
                successDiv.innerText =
                    'Password reset successful';
                successDiv.classList.remove('hidden');
                form.reset();
                setTimeout(() => {
                    window.location.href = '/admin/login';
                }, 2000);

            } catch (error) {
                errorDiv.innerText =
                    error.message || 'Failed to reset password';
                errorDiv.classList.remove('hidden');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Reset Password';
            }
        });
    </script>
</body>

</html>