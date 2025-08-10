# Copilot Instructions for Theme Park & Picnic Island Booking System

<!-- Use this file to provide workspace-specific custom instructions to Copilot. For more details, visit https://code.visualstudio.com/docs/copilot/copilot-customization#_use-a-githubcopilotinstructionsmd-file -->

## Project Overview
This is a Laravel-based Online Booking System for a Theme Park and Picnic Island with the following key requirements:

### Business Rules
1. **Hotel-First Booking**: Visitors MUST book a hotel stay on the main island before they can purchase ferry tickets or theme park tickets
2. **Multi-Business Management**: Hotels, ferry operators, theme park owners, and beach event organizers can manage their own bookings
3. **Sequential Booking Flow**: Hotel → Ferry → Theme Park Activities
4. **Separate Beach Events**: Main island beach events are independent of theme park

### Key Features
- Hotel booking system with validation
- Ferry ticket booking (requires valid hotel booking)
- Theme park entrance tickets and individual activity tickets
- Beach events booking system
- Advertisement management for homepage
- Interactive island map showing locations
- Multi-role user management (visitors, business owners, admins)

### Technical Stack
- Laravel 11.x
- PHP 8.1+
- MySQL/SQLite database
- Blade templating
- Bootstrap/Tailwind CSS
- JavaScript for interactive features
- Maps integration (Google Maps/Leaflet)

### Database Structure
Key entities: users, hotels, hotel_bookings, ferries, ferry_tickets, parks, park_tickets, park_activities, activity_tickets, beach_events, beach_tickets, advertisements

### Code Style Guidelines
- Follow Laravel conventions and PSR-12 standards
- Use Eloquent relationships properly
- Implement form validation and authorization
- Create reusable components for booking flows
- Use middleware for hotel booking validation
- Implement proper error handling and user feedback
