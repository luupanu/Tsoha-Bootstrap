-- Lisää INSERT INTO lauseet tähän tiedostoon
INSERT INTO ServiceUser (name, password) VALUES ('MikkoMies', '$2y$10$QvtOEwpSKBAjiSa1NqIPj.FrMsQ7rXR.d/IxXuYTuQ0jFZd8Qfdku'); -- 'zalazana'
INSERT INTO ServiceUser (name, password) VALUES ('Maikkeli', '$2y$10$CDWcYmE0v1kG7ZrVKAdCMup813lE3yJbhhe2i9gBt.TCXNOLow0LO'); -- 'salasana'
INSERT INTO Sample (serviceuser_id, filename, name, duration, comment) VALUES (1, 'sample1.wav', 'Tuulen tuhinaa', 16.4, 'Melekoisen tuulekasta.');
INSERT INTO Sample (serviceuser_id, filename, name, duration, comment) VALUES (1, 'sample2.wav', 'Hiiren kihinää', 9.6, 'Hiiri oli kyllä vihainen.');
INSERT INTO Sample (serviceuser_id, filename, name, duration, comment) VALUES (2, 'sample3.wav', 'Kovaa kamaa', 4.4, 'Homma on pihvi.');
INSERT INTO Tag (name) VALUES ('bass');
INSERT INTO Tag (name) VALUES ('luonto');
INSERT INTO Tag (name) VALUES ('jeah');
INSERT INTO SampleTag (sample_id, tag_id) VALUES (1, 1);
INSERT INTO SampleTag (sample_id, tag_id) VALUES (1, 2);
INSERT INTO SampleTag (sample_id, tag_id) VALUES (2, 2);
INSERT INTO SampleTag (sample_id, tag_id) VALUES (3, 1);
INSERT INTO SampleTag (sample_id, tag_id) VALUES (3, 3);
INSERT INTO Project (name) VALUES ('megaprojekti');
INSERT INTO Project (name) VALUES ('supaprojekti');
INSERT INTO ProjectSample (project_id, sample_id) VALUES (1, 1);
INSERT INTO ProjectSample (project_id, sample_id) VALUES (1, 2);
INSERT INTO ProjectSample (project_id, sample_id) VALUES (2, 2);
INSERT INTO ProjectSample (project_id, sample_id) VALUES (2, 3);

/*INSERT INTO Comment (sample_id, comment) VALUES (1, 'Melekoisen tuulekasta.');
INSERT INTO Comment (sample_id, comment) VALUES (2, 'Hiiri oli kyllä vihainen.');
INSERT INTO Comment (sample_id, comment) VALUES (2, 'Tätä voisi käyttää myös projektissa Y.');*/