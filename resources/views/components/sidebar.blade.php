<aside class="sidebar">
    <div class="brand">
        <div class="logo">⚡</div>
        <h1>{{ config('app.name', 'Goal Getter') }}</h1>
    </div>

    <div class="profile">
        <div class="avatar" id="userAvatar"></div>
        <div class="meta">
            <div class="name " id="name"> Salma</div>
            <div class="sub">Your productivity</div>
        </div>
    </div>

    <nav class="nav">
        <a class="" href="">
            <span class="icon"> <i class="fa-solid fa-grip" style="color: var(--accent); "></i></span> Dashboard
        </a>
        <a class="{{ request()->routeIs('tasks.index') ? 'active' : '' }}" href="">
            <span class="icon"><i class="fa-solid fa-list-check" style="color: var(--accent);"></i></span> Pending
            Tasks
        </a>

        <a class="{{ request()->routeIs('tasks.index') ? 'active' : '' }}" href="">
            <span class="icon"><i class="fa-regular fa-circle-check" style="color: var(--accent);"></i></span>
            Completed Tasks
        </a>
    </nav>

    <div class="pro-tip">
        <div class="title">Pro Tip</div>
        Use keyboard shortcuts and quick add to speed up.
    </div>
</aside>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            try {
                const token = localStorage.getItem('jwt_token');

                const response = await fetch('/api/user', {
                    method: 'GET',
                    headers: {
                        'Authorization': `Bearer ${token}`
                    }
                });

                const res = response;

                console.log('Status:', res.status, 'OK:', res.ok);
                 

                if (res.status === 401 || res.status === 404) {
                    //  window.location.href = '/login';
                    return;
                }

                if (!res.ok) {
                    throw new Error(`API error: ${res.status}`);
                }

                const user = await res.json();

                if (user && user.name) {
                    const initials = user.name
                        .split(' ')
                        .map(w => w[0] || '')
                        .join('')
                        .toUpperCase()
                        .slice(0, 2);

                    document.getElementById('userAvatar').textContent = initials;
                    document.getElementById('name').textContent = user.name;
                } else {
                    throw new Error('Invalid user data');
                }

            } catch (error) {
                console.error('Error:', error);
                document.getElementById('userAvatar').textContent = 'GU';
                document.getElementById('name').textContent = 'Guest User';
                // window.location.href = '/login';
            }
        });
    </script>
@endpush
