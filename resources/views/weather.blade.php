<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cek Cuaca</title>
    @vite(['resources/css/style.css', 'resources/js/animated.js'])
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>🌤️ Cek Cuaca</h1>
            <p>Dapatkan informasi cuaca terkini untuk kota favorit Anda</p>
        </div>

        <form class="search-form" method="POST" action="{{ route('weather.get') }}">
            @csrf
            <div class="form-group">
                <input type="text" name="city" class="form-input"
                    placeholder="Masukkan nama kota (contoh: Jakarta, Surabaya)" required>
                <button type="submit" class="btn-search">🔍 Cari Cuaca</button>
            </div>
        </form>

        <div class="loading" id="loading">
            <div class="spinner"></div>
            <p>Mencari data cuaca...</p>
        </div>

        <!-- World Weather Section -->
        <div class="world-weather-section">
            <h2 class="section-title">🌍 Cuaca Dunia Saat Ini</h2>
            <div class="world-weather-grid">
                <div class="world-city-card">
                    <div class="city-header">
                        <h3>🇮🇩 Jakarta</h3>
                        <span class="time">18:30 WIB</span>
                    </div>
                    <div class="city-temp">32°C</div>
                    <div class="city-desc">Berawan</div>
                    <div class="city-details">
                        <span>💧 78%</span>
                        <span>💨 12 km/h</span>
                    </div>
                </div>

                <div class="world-city-card">
                    <div class="city-header">
                        <h3>🇺🇸 New York</h3>
                        <span class="time">06:30 EST</span>
                    </div>
                    <div class="city-temp">18°C</div>
                    <div class="city-desc">Cerah</div>
                    <div class="city-details">
                        <span>💧 45%</span>
                        <span>💨 8 km/h</span>
                    </div>
                </div>

                <div class="world-city-card">
                    <div class="city-header">
                        <h3>🇬🇧 London</h3>
                        <span class="time">11:30 GMT</span>
                    </div>
                    <div class="city-temp">15°C</div>
                    <div class="city-desc">Hujan Ringan</div>
                    <div class="city-details">
                        <span>💧 85%</span>
                        <span>💨 15 km/h</span>
                    </div>
                </div>

                <div class="world-city-card">
                    <div class="city-header">
                        <h3>🇯🇵 Tokyo</h3>
                        <span class="time">20:30 JST</span>
                    </div>
                    <div class="city-temp">22°C</div>
                    <div class="city-desc">Berawan Sebagian</div>
                    <div class="city-details">
                        <span>💧 62%</span>
                        <span>💨 10 km/h</span>
                    </div>
                </div>

                <div class="world-city-card">
                    <div class="city-header">
                        <h3>🇦🇺 Sydney</h3>
                        <span class="time">22:30 AEDT</span>
                    </div>
                    <div class="city-temp">25°C</div>
                    <div class="city-desc">Cerah</div>
                    <div class="city-details">
                        <span>💧 55%</span>
                        <span>💨 18 km/h</span>
                    </div>
                </div>

                <div class="world-city-card">
                    <div class="city-header">
                        <h3>🇫🇷 Paris</h3>
                        <span class="time">12:30 CET</span>
                    </div>
                    <div class="city-temp">16°C</div>
                    <div class="city-desc">Mendung</div>
                    <div class="city-details">
                        <span>💧 72%</span>
                        <span>💨 12 km/h</span>
                    </div>
                </div>

                <div class="world-city-card">
                    <div class="city-header">
                        <h3>🇸🇬 Singapore</h3>
                        <span class="time">19:30 SGT</span>
                    </div>
                    <div class="city-temp">29°C</div>
                    <div class="city-desc">Hujan Petir</div>
                    <div class="city-details">
                        <span>💧 88%</span>
                        <span>💨 8 km/h</span>
                    </div>
                </div>

                <div class="world-city-card">
                    <div class="city-header">
                        <h3>🇮🇳 Mumbai</h3>
                        <span class="time">17:00 IST</span>
                    </div>
                    <div class="city-temp">28°C</div>
                    <div class="city-desc">Lembab</div>
                    <div class="city-details">
                        <span>💧 82%</span>
                        <span>💨 14 km/h</span>
                    </div>
                </div>
            </div>
        </div>

        @if (session('error'))
            <div class="error-message">
                ❌ {{ session('error') }}
            </div>
        @endif

        @if (session('weather'))
            @php
                $weather = session('weather');
                $sunrise = date('H:i', $weather['sys']['sunrise']);
                $sunset = date('H:i', $weather['sys']['sunset']);
                $feelsLike = round($weather['main']['feels_like']);
                $humidity = $weather['main']['humidity'];
                $pressure = $weather['main']['pressure'];
                $visibility = isset($weather['visibility']) ? $weather['visibility'] / 1000 : 'N/A';
                $clouds = $weather['clouds']['all'];
                $windDirection = isset($weather['wind']['deg']) ? $weather['wind']['deg'] : 0;

                function getWindDirection($deg)
                {
                    $directions = [
                        'Utara',
                        'Timur Laut',
                        'Timur',
                        'Tenggara',
                        'Selatan',
                        'Barat Daya',
                        'Barat',
                        'Barat Laut',
                    ];
                    return $directions[round($deg / 45) % 8];
                }
            @endphp

            <div class="weather-container" id="results">
                <div class="weather-main">
                    <h2>📍 {{ $weather['name'] }}, {{ $weather['sys']['country'] }}</h2>
                    <div class="temp-display">{{ round($weather['main']['temp']) }}°C</div>
                    <div class="weather-description">{{ $weather['weather'][0]['description'] }}</div>
                    <p style="color: #b3b3f0; font-size: 1.1rem;">Terasa seperti {{ $feelsLike }}°C</p>
                </div>

                <div class="weather-details">
                    <h3>📊 Detail Cuaca</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <div class="detail-icon">💨</div>
                            <div class="detail-label">Kecepatan Angin</div>
                            <div class="detail-value">{{ $weather['wind']['speed'] }} m/s</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">🧭</div>
                            <div class="detail-label">Arah Angin</div>
                            <div class="detail-value">{{ getWindDirection($windDirection) }}</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">💧</div>
                            <div class="detail-label">Kelembaban</div>
                            <div class="detail-value">{{ $humidity }}%</div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon">🌡️</div>
                            <div class="detail-label">Tekanan</div>
                            <div class="detail-value">{{ $pressure }} hPa</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="additional-info">
                <div class="info-card">
                    <h4>🌅 Waktu Matahari</h4>
                    <div class="sun-times">
                        <div class="sun-time">
                            <span>Terbit</span>
                            <strong>{{ $sunrise }}</strong>
                        </div>
                        <div class="sun-time">
                            <span>Terbenam</span>
                            <strong>{{ $sunset }}</strong>
                        </div>
                    </div>
                </div>

                <div class="info-card">
                    <h4>👁️ Jarak Pandang</h4>
                    <div style="font-size: 1.5rem; font-weight: 600; color: #74b9ff; margin-top: 10px;">
                        {{ $visibility !== 'N/A' ? $visibility . ' km' : 'N/A' }}
                    </div>
                </div>

                <div class="info-card">
                    <h4>☁️ Tutupan Awan</h4>
                    <div style="font-size: 1.5rem; font-weight: 600; color: #a29bfe; margin-top: 10px;">
                        {{ $clouds }}%
                    </div>
                </div>

                <div class="info-card">
                    <h4>🌡️ Rentang Suhu</h4>
                    <div style="margin-top: 10px;">
                        <div style="color: #fd79a8;">Min: {{ round($weather['main']['temp_min']) }}°C</div>
                        <div style="color: #fdcb6e;">Max: {{ round($weather['main']['temp_max']) }}°C</div>
                    </div>
                </div>

                @if (isset($weather['rain']))
                    <div class="info-card">
                        <h4>🌧️ Curah Hujan</h4>
                        <div style="font-size: 1.3rem; font-weight: 600; color: #74b9ff; margin-top: 10px;">
                            {{ $weather['rain']['1h'] ?? 0 }} mm/jam
                        </div>
                    </div>
                @endif

                @if (isset($weather['wind']['gust']))
                    <div class="info-card">
                        <h4>💨 Hembusan Angin</h4>
                        <div style="font-size: 1.3rem; font-weight: 600; color: #00b894; margin-top: 10px;">
                            {{ $weather['wind']['gust'] }} m/s
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- footer --}}
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <!-- Brand Section -->
                <div class="footer-brand">
                    <h3>🌤️ Cek Cuaca</h3>
                    <p>Platform terpercaya untuk mendapatkan informasi cuaca terkini dan akurat dari seluruh dunia.
                        Selalu siap memberikan data cuaca real-time untuk kebutuhan Anda.</p>
                    <div class="social-links">
                        <a href="https://github.com/fykids" class="social-link" title="GitHub">
                            <span>🐙</span>
                        </a>
                        <a href="https://l.instagram.com/?u=https%3A%2F%2Fwww.linkedin.com%2Fin%2Ffyosua20580628a%3Futm_source%3Dshare%26utm_campaign%3Dshare_via%26utm_content%3Dprofile%26utm_medium%3Dandroid_app%26fbclid%3DPAZXh0bgNhZW0CMTEAAadug-VXdzkTWXxRVyDZBFmYJQK_Ex1wRmDxHKrcff2DwlrRCKF2M_MOXo6n3A_aem_KmctmZglNInMrN27m7LPAw&e=AT2_9CfcYflXrc9UN0O8NlUNBT-jug5fWpQni4qu1DI46wnI2_k856iItY_D5-Mh26qCU3Vkm6Nk8POv6h3gC77eyIRpM8vKDxjePpwb_JH4Gnp8LzD6x9M"
                            class="social-link" title="LinkedIn">
                            <span>💼</span>
                        </a>
                    </div>
                </div>

                <!-- API Info Section -->
                <div class="footer-section">
                    <h4>🔗 Data & API</h4>
                    <div class="api-info">
                        <div class="api-badge">Powered by</div>
                        <p style="color: #b3b3f0; font-size: 0.9rem; margin-bottom: 8px;">
                            Data cuaca disediakan oleh:
                        </p>
                        <a href="https://api.openweathermap.org/data/2.5/weather" target="_blank" class="api-link">
                            OpenWeather
                        </a>
                    </div>
                    <ul class="footer-links">
                        <li><a href="https://openweathermap.org/current" target="_blank">📊 Dokumentasi API</a></li>
                    </ul>
                </div>

                <!-- Quick Links -->
                <div class="footer-section">
                    <h4>⚡ Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="/">🏠 Beranda</a></li>
                        <li><a href="#">🌍 Cuaca Global</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom">
                <div class="footer-bottom-content">
                    <div class="copyright">
                        © {{ date('Y') }} Cek Cuaca. All rights reserved.
                    </div>
                    <div class="developer-info">
                        <div class="developer-avatar">FY</div>
                        <div class="developer-text">
                            Dibuat dengan oleh Febrian Yosua
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    @if (session('weather'))
        <script>
            // Scroll otomatis ke elemen dengan ID 'results'
            window.onload = function() {
                const resultElement = document.getElementById('results');
                if (resultElement) {
                    resultElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }
        </script>
    @endif

</body>

</html>
