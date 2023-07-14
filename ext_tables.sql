# #####################
# Modifying pages table
#

CREATE TABLE pages (
	geo_lat varchar(255) DEFAULT '' NOT NULL,
	geo_long varchar(255) DEFAULT '' NOT NULL,
	geo_title varchar(255) DEFAULT '' NOT NULL,
	geo_subtitle varchar(255) DEFAULT '' NOT NULL,
	geo_address text NULL,
	geo_phone varchar(255) DEFAULT '' NOT NULL,
	geo_email varchar(255) DEFAULT '' NOT NULL,
	geo_www varchar(255) DEFAULT '' NOT NULL,
	geo_booking varchar(500) DEFAULT '' NOT NULL,
	geo_type varchar(255) DEFAULT '' NOT NULL
);