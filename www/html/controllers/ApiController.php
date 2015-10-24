<?php

/**
 * ApiController.
 *
 * @author Keiji Takano <takano@se-project.co.jp>
 */
class ApiController extends Controller
{

    const LOG_FORMAT               = "%s %s\n %s %s %s (%d)\n=====\n";
    const GET_KIZAPI_KEYWORD_START = "--- getKizapiKeywordAction start ---";
    const GET_KIZAPI_KEYWORD_END   = "--- getKizapiKeywordAction end   ---";

    public function getKizapiKeywordAction()
    {

        $this->log->addInfo(
          sprintf(self::LOG_FORMAT, $this->finger, var_export(
            self::GET_KIZAPI_KEYWORD_START, 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
        );

//        http://192.168.33.20/console/getKizapiKeyword

        $base_url = 'http://kizasi.jp/kizapi.py?type=rank';
        $curl     = curl_init();

//        curl_setopt($curl, CURLOPT_URL, $base_url);
//        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // 証明書の検証を行わない
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);  // curl_execの結果を文字列で返す

        $option = [
          CURLOPT_URL            => $base_url,
          CURLOPT_CUSTOMREQUEST  => 'GET',
          CURLOPT_SSL_VERIFYPEER => false,
          CURLOPT_RETURNTRANSFER => true
        ];
        curl_setopt_array($curl, $option);

        $response = curl_exec($curl);
        $xml      = simplexml_load_string($response);
        $json     = json_encode($xml);
        $array    = json_decode($json, true);

        foreach ($array['channel']['item'] as $key => $value) {

            $keyword = trim($value['title']);

            if (!$this->db_manager->get('Ideas')->isDuplicateKeyword($keyword)) {
                try {
                    $this->db_manager->get('Ideas')->insert($keyword);

                    $this->log->addInfo(
                      sprintf(self::LOG_FORMAT, $this->finger, var_export(
                        $keyword, 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
                    );

                } catch (Exception $e) {

                    $this->log->addInfo(
                      sprintf(self::LOG_FORMAT, $this->finger, var_export(
                        $e->getMessage(), 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
                    );
                }
            }
        }

        $this->log->addInfo(
          sprintf(self::LOG_FORMAT, $this->finger, var_export(
            self::GET_KIZAPI_KEYWORD_END, 1), date(DATE_RFC822), __FILE__, __METHOD__, __LINE__)
        );
        
        return $this->redirect('/');

    }

}
