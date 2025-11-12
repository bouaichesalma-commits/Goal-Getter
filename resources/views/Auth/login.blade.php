<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Goal Getter | Login</title>

    @vite(['resources/css/auth.css', 'resources/js/app.js', 'resources/js/script.js'])


</head>

<body>
    <section class="container">
        <div class="login-container">
            <div class="circle circle-one"></div>
            <div class="form-container">
                <img src="https://raw.githubusercontent.com/hicodersofficial/glassmorphism-login-form/master/assets/illustration.png"
                    alt="illustration" class="illustration" />
                <h1 class="opacity">LOGIN</h1>


                <form id="loginForm" action="" method="POST">
                    <input id="email" type="email" placeholder="USERNAME" required />
                    <input id="password" type="password" placeholder="PASSWORD" required />
                    <button type="submit" class="opacity">SUBMIT</button>
                </form>
                <div class="register-forget opacity">
                    <a href="{{ route('register') }}">REGISTER</a>
                    {{-- <a href="">FORGOT PASSWORD</a> --}}
                </div>
            </div>
            <div class="circle circle-two"></div>
        </div>
        <div class="theme-btn-container"></div>
    </section>

    <script>
        const themes = [{
                background: "#1A1A2E",
                color: "#FFFFFF",
                primaryColor: "#0F3460"
            },
            {
                background: "#461220",
                color: "#FFFFFF",
                primaryColor: "#E94560"
            },
            {
                background: "#192A51",
                color: "#FFFFFF",
                primaryColor: "#967AA1"
            },
            {
                background: "#F7B267",
                color: "#000000",
                primaryColor: "#F4845F"
            },
            {
                background: "#F25F5C",
                color: "#000000",
                primaryColor: "#642B36"
            },
            {
                background: "#231F20",
                color: "#FFF",
                primaryColor: "#BB4430"
            }
        ];

        const setTheme = (theme) => {
            const root = document.querySelector(":root");
            root.style.setProperty("--background", theme.background);
            root.style.setProperty("--color", theme.color);
            root.style.setProperty("--primary-color", theme.primaryColor);
            root.style.setProperty("--glass-color", theme.glassColor);
        };

        const displayThemeButtons = () => {
            const btnContainer = document.querySelector(".theme-btn-container");
            themes.forEach((theme) => {
                const div = document.createElement("div");
                div.className = "theme-btn";
                div.style.cssText = `background: ${theme.background}; width: 25px; height: 25px`;
                btnContainer.appendChild(div);
                div.addEventListener("click", () => setTheme(theme));
            });
        };

        displayThemeButtons();
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('loginForm');
            form.addEventListener('submit', async (e) => {
                e.preventDefault();
                const email = document.getElementById('email').value.trim();
                const password = document.getElementById('password').value;


                try {
                    const res = await fetch('/api/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            email,
                            password
                        }),
                        credentials: 'include'
                    });

                    const data = await res.json();

                    if (res.ok) {

                        window.location.href = '/';
                    } else {
                        alert(data.error || 'Login failed');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Network error');
                }
            });
        });
    </script>



</body>


</html>
