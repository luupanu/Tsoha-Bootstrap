-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE ServiceUser(
	id SERIAL PRIMARY KEY,
	name varchar(16) NOT NULL UNIQUE,
	password varchar(255) NOT NULL,
	superuser boolean DEFAULT FALSE
);

CREATE TABLE Sample(
	id SERIAL PRIMARY KEY,
	serviceuser_id INTEGER REFERENCES ServiceUser(id),
	filename varchar(260) NOT NULL,
	name varchar(50),
	duration DECIMAL(6,2) NOT NULL,
	comment varchar(140)
);

CREATE TABLE Tag(
	id SERIAL PRIMARY KEY,
	name varchar(50) NOT NULL UNIQUE
);

CREATE TABLE SampleTag(
	sample_id INTEGER NOT NULL REFERENCES Sample(id) ON DELETE CASCADE,
	tag_id INTEGER NOT NULL REFERENCES Tag(id) ON DELETE CASCADE,
	PRIMARY KEY(sample_id, tag_id)
);

CREATE TABLE Project(
	id SERIAL PRIMARY KEY,
	name varchar(50) NOT NULL UNIQUE
);

CREATE TABLE ProjectSample(
	sample_id INTEGER NOT NULL REFERENCES Sample(id) ON DELETE CASCADE,
	project_id INTEGER NOT NULL REFERENCES Project(id) ON DELETE CASCADE,
	PRIMARY KEY(sample_id, project_id)
);