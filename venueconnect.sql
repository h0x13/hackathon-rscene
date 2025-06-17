drop database if exists venueconnect;
create database venueconnect;
use venueconnect;

CREATE TABLE user_profile (
	id int primary key AUTO_INCREMENT,
    first_name varchar(255) not null,
    middle_name varchar(255) not null,
    last_name varchar(255) not null,
    birthdate date not null,
    image_path varchar(255) not null,
    created_at datetime not null,
    updated_at datetime not null
);

CREATE TABLE user_credential (
	id int primary key AUTO_INCREMENT,
    user_profile_id int not null, 
    email varchar(255) not null,
    password varchar(255) not null,
	user_type varchar(255) not null,
		FOREIGN KEY (user_profile_id) REFERENCES user_profile(id) ON DELETE CASCADE
);

CREATE TABLE venue_pin (
	id int primary key AUTO_INCREMENT,
    lat float not null,
    lon float not null
);

CREATE TABLE venue (
	id int primary key AUTO_INCREMENT,
    venue_name varchar(255) not null,
    pin_id int not null,
    owner_profile int not null,
    venue_description text not null,
    street varchar(255) not null,
    barangay varchar(255) not null,
    city varchar(255) not null,
    zip_code varchar(255) not null,
    rent decimal(8,2) not null,
    capacity int not null,
		FOREIGN KEY (pin_id) REFERENCES venue_pin(id) ON DELETE CASCADE,
        FOREIGN KEY (owner_profile) REFERENCES user_credential(id)
);

CREATE TABLE event_performance (
	id int primary key AUTO_INCREMENT,
    venue_id int not null, 
    organizer_id int not null,
    event_name varchar(255) not null,
    event_description text not null,
    event_startdate date not null,
    event_enddate date not null,
    event_status varchar(255) not null,
	booking_status varchar(255) not null,
		FOREIGN KEY (venue_id) REFERENCES venue(id) ON DELETE CASCADE,
        FOREIGN KEY (organizer_id) REFERENCES user_credential(id) ON DELETE CASCADE
);

CREATE TABLE artist (
	id int primary key AUTO_INCREMENT, 
    performer int not null,
    artist_name varchar(255) not null,
    price_range varchar(255) not null,
    payment_option varchar(255) not null,
    hours int,
		FOREIGN KEY (performer) REFERENCES user_credential(id)
);

CREATE TABLE booking (
	id int primary key AUTO_INCREMENT,
    booking_event int not null,
    artist int not null,
    date_created datetime not null,
    booking_status varchar(255) not null,
		FOREIGN KEY (artist) REFERENCES artist(id) ON DELETE CASCADE,
        FOREIGN KEY (booking_event) REFERENCES event_performance(id) ON DELETE CASCADE
);

