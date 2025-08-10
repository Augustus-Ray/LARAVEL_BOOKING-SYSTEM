<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Island Map - Paradise Island</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        #map { height: 500px; }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('home') }}">🏝️ Paradise Island</a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('hotels.index') }}">🏨 Hotels</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('ferries.index') }}">⛴️ Ferries</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('theme-parks.index') }}">🎢 Theme Parks</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('beach-events.index') }}">🏖️ Beach Events</a></li>
                    <li class="nav-item"><a class="nav-link active" href="{{ route('map') }}">🗺️ Island Map</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1>🗺️ Paradise Island Interactive Map</h1>
        <p class="lead">Explore hotels, theme parks, beach events, and more on our interactive island map.</p>
        
        <div class="row">
            <div class="col-md-8">
                <div id="map" class="rounded shadow"></div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>🏝️ Map Legend</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <span class="badge bg-primary">🏨</span> Hotels & Accommodations
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-success">🎢</span> Theme Parks
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-info">🏖️</span> Beach Events
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-warning">⛴️</span> Ferry Terminals
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-secondary">🍽️</span> Restaurants
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h5>📍 Quick Locations</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Paradise Water Villa Resort
                                <span class="badge bg-primary">🏨</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Crystal Lagoon Resort
                                <span class="badge bg-primary">🏨</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Paradise Adventure World
                                <span class="badge bg-success">🎢</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Aqua Adventure Park
                                <span class="badge bg-success">🎢</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Sunset Beach BBQ Festival
                                <span class="badge bg-info">🏖️</span>
                            </div>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                Paradise Island Express
                                <span class="badge bg-warning">⛴️</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="alert alert-info mt-4">
            <h5>🧭 Navigation Tips</h5>
            <ul class="mb-0">
                <li>Click on markers to see detailed information</li>
                <li>Use zoom controls to get a closer look</li>
                <li>Drag the map to explore different areas</li>
                <li>All locations show distances from your hotel</li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        // Initialize map centered on Maldives (Paradise Island)
        var map = L.map('map').setView([4.2105, 73.5046], 12);

        // Add tile layer
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Create custom icons
        var hotelIcon = L.divIcon({html: '🏨', className: 'custom-div-icon', iconSize: [30, 30]});
        var parkIcon = L.divIcon({html: '🎢', className: 'custom-div-icon', iconSize: [30, 30]});
        var beachIcon = L.divIcon({html: '🏖️', className: 'custom-div-icon', iconSize: [30, 30]});
        var ferryIcon = L.divIcon({html: '⛴️', className: 'custom-div-icon', iconSize: [30, 30]});

        // Hotels
        L.marker([4.2105, 73.5046], {icon: hotelIcon})
            .bindPopup('<b>Paradise Water Villa Resort</b><br>North Shore, Paradise Island<br>Luxury overwater villas')
            .addTo(map);

        L.marker([4.2150, 73.5080], {icon: hotelIcon})
            .bindPopup('<b>Crystal Lagoon Resort</b><br>Crystal Bay, Paradise Island<br>Premium beachfront resort')
            .addTo(map);

        L.marker([4.2080, 73.5100], {icon: hotelIcon})
            .bindPopup('<b>Sunset Atoll Retreat</b><br>Sunset Point, Paradise Island<br>Romantic escape destination')
            .addTo(map);

        L.marker([4.2120, 73.5020], {icon: hotelIcon})
            .bindPopup('<b>Aqua Dreams Resort</b><br>Lagoon District, Paradise Island<br>Family-friendly resort')
            .addTo(map);

        L.marker([4.2060, 73.5060], {icon: hotelIcon})
            .bindPopup('<b>Tropical Overwater Lodge</b><br>Honeymoon Bay, Paradise Island<br>Intimate overwater experience')
            .addTo(map);

        L.marker([4.2140, 73.5040], {icon: hotelIcon})
            .bindPopup('<b>Blue Horizon Beach Resort</b><br>Blue Horizon Beach, Paradise Island<br>Stunning ocean views')
            .addTo(map);

        // Theme Parks
        L.marker([4.2090, 73.5070], {icon: parkIcon})
            .bindPopup('<b>Paradise Adventure World</b><br>Central Paradise Island<br>Premier theme park experience<br>Entry: $85.00')
            .addTo(map);

        L.marker([4.2170, 73.5030], {icon: parkIcon})
            .bindPopup('<b>Aqua Adventure Park</b><br>North Paradise Island<br>Water theme park adventures<br>Entry: $75.00')
            .addTo(map);

        // Ferry Terminals
        L.marker([4.2100, 73.5000], {icon: ferryIcon})
            .bindPopup('<b>Paradise Island Express</b><br>Main Ferry Terminal<br>Regular island transfers')
            .addTo(map);

        L.marker([4.2130, 73.5110], {icon: ferryIcon})
            .bindPopup('<b>Sunset Ferry</b><br>Sunset Terminal<br>Evening scenic routes')
            .addTo(map);

        L.marker([4.2070, 73.5030], {icon: ferryIcon})
            .bindPopup('<b>Morning Breeze Ferry</b><br>Morning Terminal<br>Early departure services')
            .addTo(map);

        L.marker([4.2160, 73.5070], {icon: ferryIcon})
            .bindPopup('<b>Luxury Yacht Transfer</b><br>VIP Marina<br>Premium transfer service')
            .addTo(map);

        // Beach Events
        L.marker([4.2050, 73.5090], {icon: beachIcon})
            .bindPopup('<b>Sunset Beach BBQ Festival</b><br>Sunset Beach, Paradise Island<br>Beachside dining experience')
            .addTo(map);

        L.marker([4.2110, 73.5110], {icon: beachIcon})
            .bindPopup('<b>Moonlight Beach Yoga</b><br>Tranquil Bay, Paradise Island<br>Relaxing yoga sessions')
            .addTo(map);

        L.marker([4.2080, 73.5010], {icon: beachIcon})
            .bindPopup('<b>Beach Volleyball Tournament</b><br>Sports Beach, Paradise Island<br>Competitive beach sports')
            .addTo(map);

        L.marker([4.2150, 73.5050], {icon: beachIcon})
            .bindPopup('<b>Tropical Music Festival</b><br>Festival Beach, Paradise Island<br>Live music and entertainment')
            .addTo(map);

        L.marker([4.2040, 73.5040], {icon: beachIcon})
            .bindPopup('<b>Cultural Heritage Night</b><br>Heritage Beach, Paradise Island<br>Traditional cultural experiences')
            .addTo(map);
    </script>
    
    <style>
        .custom-div-icon {
            background: transparent;
            border: none;
            font-size: 24px;
        }
    </style>
</body>
</html>
