# Paradise Island - Theme Park & Picnic Island Booking System

A comprehensive Laravel-based online booking system for a theme park and picnic island destination. This system implements a multi-business platform where visitors can book hotels, ferry tickets, theme park activities, and beach events.

## 🏝️ Key Features

### Business Rules Implementation
- **Hotel-First Booking**: Visitors MUST book a hotel stay before accessing ferry tickets or theme park tickets
- **Multi-Business Management**: Separate dashboards for hotel owners, ferry operators, theme park owners, and event organizers
- **Sequential Booking Flow**: Hotel → Ferry → Theme Park Activities
- **Independent Beach Events**: Beach events can be booked without hotel requirement

### Core Functionality
- ✅ Hotel booking and management system
- ✅ Ferry ticket booking with hotel validation
- ✅ Theme park entrance and activity tickets
- ✅ Beach event registration system
- ✅ Advertisement management for homepage
- ✅ Interactive island map with locations
- ✅ Multi-role user authentication (visitors, business owners, admins)
- ✅ Real-time booking validation and availability checking

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js (for frontend compilation)
- SQLite or MySQL database

### Installation

1. **Clone and Setup Dependencies**
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   ```

2. **Database Setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

3. **Start Development Server**
   ```bash
   php artisan serve
   ```

4. **Visit Application**
   Open [http://localhost:8000](http://localhost:8000) in your browser

## 👥 Sample User Accounts

The seeder creates these test accounts:

| Role | Email | Password | Description |
|------|-------|----------|-------------|
| Admin | admin@paradiseisland.com | admin123 | System administrator |
| Hotel Owner | hotel@paradiseisland.com | hotel123 | Manages hotels |
| Park Owner | park@paradiseisland.com | park123 | Manages theme parks |
| Ferry Operator | ferry@paradiseisland.com | ferry123 | Manages ferry services |
| Beach Organizer | events@paradiseisland.com | beach123 | Manages beach events |
| Visitor | visitor@example.com | password | Regular customer |

## 🏗️ System Architecture

### Database Schema
- **users** - Multi-role user system
- **hotels** - Hotel listings and details
- **hotel_bookings** - Hotel reservations (required for other bookings)
- **ferries** - Ferry schedules and operators
- **ferry_tickets** - Ferry bookings (requires hotel booking)
- **theme_parks** - Theme park information
- **park_activities** - Individual park activities/rides
- **park_tickets** - Park entrance tickets (requires hotel booking)
- **activity_tickets** - Individual activity bookings
- **beach_events** - Beach event listings
- **beach_tickets** - Beach event reservations (no hotel required)
- **advertisements** - Homepage promotional content

### Business Logic Flow

1. **Visitor Registration/Login**
2. **Hotel Booking** (MANDATORY FIRST STEP)
   - Choose hotel and dates
   - Confirm reservation
3. **Ferry Booking** (Hotel booking required)
   - Select ferry schedule
   - System validates active hotel booking
4. **Theme Park Access** (Hotel booking required)
   - Purchase park entrance ticket
   - Book individual activities/rides
5. **Beach Events** (Independent)
   - Browse and book beach activities
   - No hotel booking requirement

## 🎢 Sample Theme Park Activities

The system includes creative theme park experiences:

- **Space Exploration Roller Coaster** - High-speed space journey simulation
- **Glow-in-the-Dark Coral Ride** - Bioluminescent underwater experience
- **Superhero 4D Experience** - Immersive cinema adventure
- **Water Sports Arena** - Jet skiing, kayaking, snorkeling activities

## 🏖️ Beach Events Examples

- **Sunset Beach Volleyball Tournament** - Competitive sports events
- **Island Music Festival** - Live entertainment under the stars
- **Snorkeling Adventure Tours** - Guided reef exploration

## 🗺️ Interactive Features

### Island Map
- Hotel locations with contact information
- Theme park and activity locations
- Beach event venues
- Ferry terminal points
- Restaurant and amenity locations

### Advertisement System
- Targeted ads by business type (hotels, parks, events)
- Multiple ad positions (banner, sidebar, featured)
- Click tracking and analytics
- Date-based campaign management

## 🛠️ Development

### Key Laravel Features Used
- **Eloquent ORM** with relationships
- **Migration system** for database schema
- **Seeder classes** for sample data
- **Middleware** for hotel booking validation
- **Form validation** and authorization
- **Blade templating** with component reuse
- **Route model binding** for clean URLs

### Code Organization
- **Controllers**: Business logic for each entity type
- **Models**: Database relationships and business rules
- **Middleware**: `RequireHotelBooking` enforces booking flow
- **Seeders**: Comprehensive sample data for testing
- **Views**: Responsive Bootstrap-based UI

## 🚀 Deployment Considerations

### Production Setup
- Configure proper database (MySQL/PostgreSQL)
- Set up queue system for booking notifications
- Implement caching for improved performance
- Add image upload functionality for hotels/events
- Integrate payment processing system
- Set up email notifications for bookings

### Security Features
- Role-based access control
- Form validation and CSRF protection
- Database relationship constraints
- Booking conflict prevention
- User authorization for business operations

## 📱 Technical Stack

- **Backend**: Laravel 12.x, PHP 8.2+
- **Frontend**: Blade templates, Bootstrap 5, Alpine.js
- **Database**: SQLite (development), MySQL/PostgreSQL (production)
- **Maps**: Leaflet.js integration ready
- **Authentication**: Laravel Breeze
- **Styling**: Bootstrap 5 with custom CSS

## 🎯 Future Enhancements

- [ ] Real-time booking availability updates
- [ ] Mobile app API endpoints
- [ ] Payment gateway integration
- [ ] Email notification system
- [ ] Advanced reporting dashboard
- [ ] Multi-language support
- [ ] Review and rating system
- [ ] Calendar-based booking interface
- [ ] Photo gallery for hotels/events
- [ ] Weather integration for outdoor events

---

## 📞 Support

For questions about this booking system implementation:
- Check the comprehensive seeded data for examples
- Review the middleware logic for booking flow enforcement
- Examine model relationships for data structure understanding

**Built with ❤️ using Laravel Framework**

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
