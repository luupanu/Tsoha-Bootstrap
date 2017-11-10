-- Lisää CREATE TABLE lauseet tähän tiedostoon
CREATE TABLE ServiceUser(
	id SERIAL PRIMARY KEY,
	name varchar(16) NOT NULL,
	password varchar(50) NOT NULL,
	superuser boolean DEFAULT FALSE
);

CREATE TABLE Sample(
	id SERIAL PRIMARY KEY,
	serviceuser_id INTEGER REFERENCES ServiceUser(id),
	filename varchar(50) NOT NULL,
	name varchar(50),
	duration INTEGER
);

CREATE TABLE Tag(
	name varchar(50) PRIMARY KEY
);

CREATE TABLE SampleTag(
	sample_id INTEGER REFERENCES Sample(id),
	tag_name varchar(50) REFERENCES Tag(name)
);

CREATE TABLE Project(
	id SERIAL PRIMARY KEY,
	name varchar(50) NOT NULL
);

CREATE TABLE ProjectSample(
	project_id INTEGER REFERENCES Project(id),
	sample_id INTEGER REFERENCES Sample(id)
);

CREATE TABLE Comment(
	id SERIAL PRIMARY KEY,
	sample_id INTEGER REFERENCES Sample(id),
	comment varchar(140) NOT NULL
);