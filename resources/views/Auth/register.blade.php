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




                <form id="registerForm" method="POST">
                    <input id="name" type="text" placeholder="Name" required />
                    <input id="email" type="email" placeholder="Email" required />
                    <input id="password" type="password" placeholder="Password" required />
                    <input id="password_confirmation" type="password" placeholder="Confirm Password" required />
                    <button type="submit">Register</button>
                </form>
                <div class="register-forget opacity">
                    <a href="{{route('login')}}">Login</a>
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
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault(); // منع POST الافتراضي

            const name = document.getElementById('name').value;
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const password_confirmation = document.getElementById('password_confirmation').value;

            try {
                const res = await fetch('/api/register', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ name, email, password, password_confirmation }),
                    credentials: 'include' 
                });



                const data = await res.json();
                console.log(data);

                if (res.ok) {
                    // لو الباكند يرجع token في body
                    localStorage.setItem('jwt_token', data.token);
                    alert('Registered successfully!');
                    window.location.href = '/login'; // بعد التسجيل
                } else {
                    alert(data.error || 'Registration failed');
                }
            } catch (err) {
                console.error(err);
                alert(err.message );
            }
        });
    </script>


</body>


</html>
