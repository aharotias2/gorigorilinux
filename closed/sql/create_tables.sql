DROP TABLE comments;
CREATE TABLE IF NOT EXISTS comments (
    comment_id INTEGER PRIMARY KEY,
    article_name TEXT NOT NULL,
    comment_no INTEGER NOT NULL,
    user_name TEXT,
    mail_address TEXT,
    comment TEXT,
    anchor INTEGER,
    post_date DATE NOT NULL,
    delete_flag INT NOT NULL
);

DROP TABLE goodbad;
CREATE TABLE IF NOT EXISTS goodbad (
  article_name TEXT NOT NULL,
  good INT NOT NULL,
  bad INT NOT NULL
);
