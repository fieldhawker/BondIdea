CREATE TABLE IF NOT EXISTS ideas (
  id         INTEGER AUTO_INCREMENT,
  keyword    VARCHAR(256) NOT NULL,
  created_at DATETIME,
  updated_at DATETIME,
  PRIMARY KEY (id),
  KEY keyword_index(keyword)
)
  ENGINE = INNODB
  DEFAULT CHARSET = utf8mb4;
