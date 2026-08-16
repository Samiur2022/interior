<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Admin Login - Interior PMS</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/style.css') }}"
    >

</head>


<body>


<div class="login-page">

    <div class="login-card">


        <div class="login-header">

            <h1>
                Interior PMS
            </h1>

            <p>
                Admin Panel Login
            </p>

        </div>


        {{-- Success message --}}
        @if(session('success'))

            <div class="alert success-alert">
                {{ session('success') }}
            </div>

        @endif


        {{-- Login form --}}
        <form
            action="{{ route('login') }}"
            method="POST"
        >

            @csrf


            <div class="form-group">

                <label for="email">
                    Email Address
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="Enter your email"
                    required
                    autofocus
                >

                @error('email')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    required
                >

                @error('password')

                    <small class="field-error">
                        {{ $message }}
                    </small>

                @enderror

            </div>


            <button
                type="submit"
                class="primary-btn login-btn"
            >
                Login
            </button>


        </form>


    </div>

</div>


</body>

</html>