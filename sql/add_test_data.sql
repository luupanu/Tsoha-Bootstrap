-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO ServiceUser (name, password) VALUES ('MikkoMies', 'zalazana');
INSERT INTO Sample (serviceuser_id, filename, name, duration) VALUES (1, 'sample1.wav', 'Tuulen tuhinaa', 16);
INSERT INTO Sample (serviceuser_id, filename, name, duration) VALUES (1, 'sample2.wav', 'Hiiren kihinää', 9);
INSERT INTO Tag (name) VALUES ('bass');
INSERT INTO Tag (name) VALUES ('luonto');
INSERT INTO SampleTag (sample_id, tag_name) VALUES (1, 'bass');
INSERT INTO SampleTag (sample_id, tag_name) VALUES (1, 'luonto');
INSERT INTO SampleTag (sample_id, tag_name) VALUES (2, 'luonto');
INSERT INTO Project (name) VALUES ('megaprojekti');
INSERT INTO ProjectSample (project_id, sample_id) VALUES (1, 1);
INSERT INTO ProjectSample (project_id, sample_id) VALUES (1, 2);
INSERT INTO Comment (sample_id, comment) VALUES (1, 'Melekoisen tuulekasta.');
INSERT INTO Comment (sample_id, comment) VALUES (2, 'Hiiri oli kyllä vihainen.');
INSERT INTO Comment (sample_id, comment) VALUES (2, 'Tätä voisi käyttää myös projektissa Y.');