-- SQLite
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS movie;
DROP TABLE IF EXISTS rating;

create table user (
    user_id INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE NOT NULL,
    username varchar(50) NOT NULL UNIQUE,
    pass NOT NULL
);

create table movie (
    movie_id INTEGER PRIMARY KEY UNIQUE NOT NULL,
    movie_name varchar(255) NOT NULL,
    movie_type varchar(30) NOT NULL,
    movie_time INTEGER,
    movie_date text,
    movie_language varchar(30) NOT NULL,
    movie_companie varchar(150) NOT NULL
);

create table rating (
    rating_realisation float,
    rating_script float,
    id_user INTEGER NOT NULL,
    id_movie INTEGER NOT NULL,
    FOREIGN KEY (id_user) REFERENCES user(user_id),
    FOREIGN KEY (id_movie) REFERENCES movie(movie_id)
);

INSERT INTO user (username, pass) VALUES ("username@gmail.com", "$2y$12$NgrZduNPvL11OcN87iWaMeBPKMBpWk/gDr2Rz592u0eFQH7P4fynO"); 
