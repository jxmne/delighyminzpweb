@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
<main style="padding: 30px; max-width: 600px; margin: auto;">
    <h2>⚙️ Pengaturan Preferensi</h2>

    <form id="preferences-form" style="margin-top: 20px; display: flex; flex-direction: column; gap: 15px;">
        <section>
            <label>Pilihan Tema:</label><br>
            <select id="pref-theme" style="padding: 8px; width: 100%; border-radius: 5px;">
                <option value="light" {{ $theme == 'light' ? 'selected' : '' }}>☀️ Light Mode</option>
                <option value="dark" {{ $theme == 'dark' ? 'selected' : '' }}>🌙 Dark Mode</option>
            </select>
        </section>

        <section>
            <label>Ukuran Font:</label><br>
            <select id="pref-font" style="padding: 8px; width: 100%; border-radius: 5px;">
                <option value="small" {{ $fontSize == 'small' ? 'selected' : '' }}>Kecil</option>
                <option value="base" {{ $fontSize == 'base' ? 'selected' : '' }}>Normal</option>
                <option value="large" {{ $fontSize == 'large' ? 'selected' : '' }}>Besar</option>
            </select>
        </section>

        <button type="submit" style="background: #12b368; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer;">
            Simpan Pengaturan
        </button>
    </form>

    <p id="msg-box" style="margin-top: 15px; color: green; font-weight: bold; display: none;"></p>
</main>

<script>
    function setCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }

    function applyTheme(theme) {
        if (theme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
        
        const themeBtn = document.getElementById('theme-toggle');
        if (themeBtn) {
            themeBtn.innerHTML = theme === 'dark' ? '☀️' : '🌙';
        }
    }

    document.getElementById('preferences-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const theme = document.getElementById('pref-theme').value;
        const fontSize = document.getElementById('pref-font').value;

        const response = await fetch("{{ route('pengaturan.save') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ theme: theme, font_size: fontSize })
        });

        const data = await response.json();

        if (data.status === 'success') {
            // Terapkan tema
            applyTheme(theme);
            
            // Simpan ke cookie
            setCookie('theme', theme, 7);
            setCookie('font_size', fontSize, 7);

            const msgBox = document.getElementById('msg-box');
            msgBox.innerText = data.message;
            msgBox.style.display = 'block';
            
            setTimeout(() => {
                msgBox.style.display = 'none';
            }, 3000);
        }
    });
</script>
@endsection