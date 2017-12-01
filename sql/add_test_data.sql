-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO ServiceUser (name, password) VALUES ('MikkoMies', 'zalazana');
INSERT INTO Sample (serviceuser_id, filename, name, duration, comment) VALUES (1, 'sample1.wav', 'Tuulen tuhinaa', 16.4, 'Melekoisen tuulekasta.');
INSERT INTO Sample (serviceuser_id, filename, name, duration, comment) VALUES (1, 'sample2.wav', 'Hiiren kihinää', 9.6, 'Hiiri oli kyllä vihainen.');
INSERT INTO Tag (name) VALUES ('bass');
INSERT INTO Tag (name) VALUES ('luonto');
INSERT INTO SampleTag (sample_id, tag_id) VALUES (1, 1);
INSERT INTO SampleTag (sample_id, tag_id) VALUES (1, 2);
INSERT INTO SampleTag (sample_id, tag_id) VALUES (2, 2);
INSERT INTO Project (name) VALUES ('megaprojekti');
INSERT INTO ProjectSample (project_id, sample_id) VALUES (1, 1);
INSERT INTO ProjectSample (project_id, sample_id) VALUES (1, 2);

/*INSERT INTO Comment (sample_id, comment) VALUES (1, 'Melekoisen tuulekasta.');
INSERT INTO Comment (sample_id, comment) VALUES (2, 'Hiiri oli kyllä vihainen.');
INSERT INTO Comment (sample_id, comment) VALUES (2, 'Tätä voisi käyttää myös projektissa Y.');*/