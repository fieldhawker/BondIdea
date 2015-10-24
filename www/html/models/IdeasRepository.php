<?php

/**
 * IdeasRepository.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class IdeasRepository extends DbRepository
{
    const LOG_FORMAT                = "%s %s\n %s %s %s (%d)\n=====\n";
    const ERR_MSG_NOT_INPUT_KEYWORD = '名前を入力してください';
    const ERR_MSG_NOT_RANGE_KEYWORD = 'キーワードは1 ～ 255 文字以内で入力してください';
    const LENGTH_KEYWORD_MIN        = 0;
    const LENGTH_KEYWORD_MAX        = 255;


    public function insert($keyword = null)
    {
        $now = new DateTime();

        $sql = "
            INSERT INTO ideas(keyword, created_at, updated_at)
                VALUES(:keyword, :created_at, :updated_at)
        ";

        try {

            $stmt = $this->execute($sql, array(
              ':keyword'    => $keyword,
              ':created_at' => $now->format('Y-m-d H:i:s'),
              ':updated_at' => $now->format('Y-m-d H:i:s'),
            ));

        } catch (Exception $e) {

            throw $e;

        }

        return true;
    }

    public function isDuplicateKeyword($keyword)
    {
        $sql = "
            SELECT count(*) as cnt
                FROM `ideas` AS i
                WHERE i.keyword = :keyword
        ";

        $row = $this->fetch($sql, array(':keyword' => $keyword,));
        
        return ($row['cnt']) ? true : false;
    }

    public function fetchRndKeyword()
    {
        $sql = "
          SELECT i.keyword as keyword
            FROM `ideas` AS i
          INNER JOIN (
            SELECT CEIL(RAND() * (SELECT MAX(`id`) FROM `ideas`)) AS `id`
          ) AS `tmp` ON i.id >= tmp.id
          ORDER BY i.id
          LIMIT 5;
        ";

        return $this->fetchAll($sql, array());
    }


    public function validInsert($keyword)
    {
        $errors = array();

        if ($this->valid->isEmpty($keyword)) {
            $errors[] = self::ERR_MSG_NOT_INPUT_KEYWORD;
        }

        if (!$this->valid->isCharaLengthRange(
          $keyword,
          self::LENGTH_KEYWORD_MIN,
          self::LENGTH_KEYWORD_MAX)
        ) {
            $errors[] = self::ERR_MSG_NOT_RANGE_KEYWORD;
        }

        return $errors;
    }
}
