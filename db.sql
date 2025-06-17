-- UserProfile table
CREATE TABLE user_profile (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    birthdate DATE NOT NULL,
    image_path VARCHAR(255),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- UserCredential table
CREATE TABLE user_credential (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_profile_id INTEGER NOT NULL,
    email VARCHAR(254) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_type VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_profile_id) REFERENCES user_profile(id) ON DELETE CASCADE
);

-- EventLocation table
CREATE TABLE event_planner_location (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    lat FLOAT NOT NULL,
    long FLOAT NOT NULL
);

-- Event table
CREATE TABLE event_planner_event (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    location_id INTEGER NOT NULL UNIQUE,
    event_name VARCHAR(255) NOT NULL,
    event_description VARCHAR(255) NOT NULL,
    event_organizer_id INTEGER NOT NULL,
    event_date DATETIME NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'draft',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (location_id) REFERENCES event_planner_location(id) ON DELETE CASCADE,
    FOREIGN KEY (event_organizer_id) REFERENCES user_profile(id) ON DELETE CASCADE
);

-- EventAddress table
CREATE TABLE event_planner_address (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    event_id INTEGER NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    street_address VARCHAR(255) NOT NULL,
    barangay VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    country VARCHAR(255) NOT NULL,
    zip_code VARCHAR(20) NOT NULL,
    rent DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES event_planner_event(id) ON DELETE CASCADE
);

-- Bookings table
CREATE TABLE event_planner_booking (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    event_id INTEGER NOT NULL,
    booker_id INTEGER NOT NULL,
    booking_date DATETIME NOT NULL,
    start_time DATETIME NOT NULL,
    end_time DATETIME NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status VARCHAR(20) NOT NULL DEFAULT 'pending',
    notes TEXT,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES event_planner_event(id) ON DELETE CASCADE,
    FOREIGN KEY (booker_id) REFERENCES user_profile(id) ON DELETE CASCADE
);

-- Booking Payments table
CREATE TABLE event_planner_booking_payment (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    booking_id INTEGER NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    payment_date DATETIME NOT NULL,
    transaction_id VARCHAR(255),
    status VARCHAR(20) NOT NULL DEFAULT 'pending',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES event_planner_booking(id) ON DELETE CASCADE
); 
