insert into captcha (question, answer) values ('sin&sup2;(&theta;) + cos&sup2;(&theta;)', 'c4ca4238a0b923820dcc509a6f75849b f97c5d29941bfb1b2fdab0874906ab82');

insert into default_conf (conf, name, value) values ('user_conf', 'admin', '0');
insert into default_conf (conf, name, value) values ('user_conf', 'editor', '0');
insert into default_conf (conf, name, value) values ('user_conf', 'javascript_enabled', '1');
insert into default_conf (conf, name, value) values ('user_conf', 'hide_threshold', '0');
insert into default_conf (conf, name, value) values ('user_conf', 'expand_threshold', '1');
insert into default_conf (conf, name, value) values ('user_conf', 'list_enabled', '0');
insert into default_conf (conf, name, value) values ('user_conf', 'real_name', '');
insert into default_conf (conf, name, value) values ('user_conf', 'time_zone', 'UTC');
insert into default_conf (conf, name, value) values ('user_conf', 'email', '');
insert into default_conf (conf, name, value) values ('user_conf', 'password', '');
insert into default_conf (conf, name, value) values ('user_conf', 'salt', '');
insert into default_conf (conf, name, value) values ('user_conf', 'karma', '0');
insert into default_conf (conf, name, value) values ('user_conf', 'joined', '0');
insert into default_conf (conf, name, value) values ('user_conf', 'email_verified', '1');

insert into poll_question (type_id, zid, time, question) values (1, 'bryan@pipedot.org', 1399255921, 'Pipecode setup difficulty was...');
insert into poll_answer(qid, answer, position) values (1, 'Too easy', 0);
insert into poll_answer(qid, answer, position) values (1, 'Just right', 1);
insert into poll_answer(qid, answer, position) values (1, 'Too hard', 2);

insert into pipe (tid, zid, editor, title, ctitle, icon, time, closed, story) values (1, 'bryan@pipedot.org', 'bryan@pipedot.org', 'Welcome to Pipecode!', 'welcome-to-pipecode', 'accessories', 1399255921, 1, 'It worked!<br/><br/>If you are reading this, then Pipecode was successfully setup and you are on your way to publishing some nerdy news!');
insert into story (pid, tid, title, ctitle, icon, time, story) values (1, 1, 'Welcome to Pipecode!', 'welcome-to-pipecode', 'accessories', 1399255921, 'It worked!<br/><br/>If you are reading this, then Pipecode was successfully setup and you are on your way to publishing some nerdy news!');

insert into reason (reason, value, pos) values ('Offtopic', -1, 0);
insert into reason (reason, value, pos) values ('Flamebait', -1, 1);
insert into reason (reason, value, pos) values ('Troll', -1, 2);
insert into reason (reason, value, pos) values ('Redundant', -1, 3);
insert into reason (reason, value, pos) values ('Insightful', 1, 4);
insert into reason (reason, value, pos) values ('Interesting', 1, 5);
insert into reason (reason, value, pos) values ('Informative', 1, 6);
insert into reason (reason, value, pos) values ('Funny', 1, 7);
insert into reason (reason, value, pos) values ('Overrated', -1, 8);
insert into reason (reason, value, pos) values ('Underrated', 1, 9);

insert into topic (topic, icon, promoted) values ('code', 'hardhat', 1);
insert into topic (topic, icon, promoted) values ('ask', 'microphone', 1);
insert into topic (topic, icon, promoted) values ('games', 'joystick', 1);
insert into topic (topic, icon, promoted) values ('internet', 'internet', 1);
insert into topic (topic, icon, promoted) values ('movies', 'movies', 0);
insert into topic (topic, icon, promoted) values ('science', 'beakers', 1);