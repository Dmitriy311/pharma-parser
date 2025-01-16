<?php

namespace App\Parser;

class WebPageParser
{
    private string $host = 'zakupki.gov.ru';
    private string $baseurl = 'https://zakupki.gov.ru/epz/order/notice/ea44/view/protocol/';
    private string $bidReviewUrl = 'protocol-bid-review.html'; //AJAX link for cards
    private string $bidListUrl = 'protocol-bid-list.html';
    private string $protocolInfoUrl = 'protocol-main-info.html';
    private string $docsUrl = 'protocol-docs.html';
    private string $regNumber;
    private string $protocolLotId;

    public function __construct(string $regNumber, string $protocolLotId)
    {
        $this->regNumber = $regNumber;
        $this->protocolLotId = $protocolLotId;
    }
    public function loadPage(string $pageType)
    {
        switch ($pageType) {
            case 'applications':
                $baseUrl = $this->baseurl . $this->bidListUrl;
                break;
            case 'protocol':
                $baseUrl = $this->baseurl . $this->protocolInfoUrl;
                break;
            case 'docs':
                $baseUrl = $this->baseurl . $this->docsUrl;
                break;
        }

        $url = $baseUrl.'?regNumber='.$this->regNumber.'&protocolId='.$this->protocolLotId;

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Host:  zakupki.gov.ru',
                'User-Agent:  Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/115.0'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
    public function loadCards(array $bidIds):array
    {
        $count = $bidIds['count'];
        for ($i = 0; $i < $count; $i++) {

            $protocolLotId = $bidIds['protocolLotId'][$i];
            $protocolBidId = $bidIds['protocolBidId'][$i];
            $url = $this->baseurl . $this->bidReviewUrl.'?regNumber='.$this->regNumber.'&protocolLotId='.$protocolLotId.'&protocolBidId=' . $protocolBidId;

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Host: '.$this->host,
                    'User-Agent:  Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/115.0'
                ),
            ));

            $response[] = curl_exec($curl);
            curl_close($curl);
        }

        return $response;
    }

    public function regexDocs(string $html)
    {
        //тест на загрузку нескольких файлов
        //$html = file_get_contents(__DIR__ . '/../html');
        preg_match_all('/<a\s+href="([^"]+)"\s+title="([^"]+)">/', $html, $matches, PREG_PATTERN_ORDER);

        $result['content'] = $matches[1];
        $result['title'] = $matches[2];

        $permaLinks = $this->persistDocs($result);

        for ($i = 0; $i < count($result['content']); $i++) {
            $result['content'][$i] = $permaLinks[$i];
        }

        return $result;
    }
    public function persistDocs(array $files)
    {
        for ($i = 0; $i < count($files['title']); $i++) {

            $url = $files['content'][$i];

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Host: '.$this->host,
                    'User-Agent:  Mozilla/5.0 (X11; Linux x86_64; rv:109.0) Gecko/20100101 Firefox/115.0'
                ),
            ));

            $fileContents = curl_exec($curl);
            curl_close($curl);

            if ($fileContents === false) {
                echo 'Failed to load file';
                return;
            }

            $date = date('Y-m-d_H-i-s');
            $newFilename = $date . '_' . $i . '_' . $files['title'][$i] ;
            $newFilePath = '/app/storage/'. $newFilename;
            $permaLinks[] = $newFilePath;

            file_put_contents($newFilePath, $fileContents);
        }
        return $permaLinks;
    }
    public function regexFullPage($html)
    {
        $patterns = [
            'title' => '/<span class="(?:cardMainInfo__title|section__title)">(.*?)<\/span>/s',
            'content' => '/<span class="(?:cardMainInfo__content|cardMainInfo__content cost|section__info|section__info lineHeight24)">(.*?)<\/span>/s',
        ];

        $results = [];

        $currentResult = [];
        foreach ($patterns as $field => $pattern) {
            if (preg_match_all($pattern, $html, $match)) {
                $currentResult[$field] = $match[1];
            } else {
                $currentResult[$field] = null;
            }
        }

        $results[] = $currentResult;

        //удаляем теги со ссылок и спанов
        //зачищаем пробелы и корректно отображаем символы
        //из тайтлов и значений

        foreach ($results[0]['title'] as &$result) {

            if (str_contains($result, 'a href')) {
                $pattern = "/>(.*)</";
                preg_match($pattern, $result, $matchLinkText);
                $result = $matchLinkText[1];
                unset($matchLinkText[1]);
            }

            if (str_contains($result, 'span class')) {
                $result = str_replace(
                    "<span class='timeZoneName' title='Москва, стандартное время'>(МСК)",
                    "",
                    $result
                );
            }

            $result = html_entity_decode($result);
            $result = trim($result);
        }

        foreach ($results[0]['content'] as $key => &$result) {

            if (str_contains($result, 'Подано заявок')) {
                unset($results[0]['content'][$key]);
                continue;
            }

            if (str_contains($result, 'a href')) {
                $pattern = "/>(.*)</";
                preg_match($pattern, $result, $matchLinkText);
                $result = $matchLinkText[1];
                unset($matchLinkText[1]);
            }

            if (str_contains($result, 'span class')) {
                $result = str_replace(
                    "<span class='timeZoneName' title='Москва, стандартное время'>(МСК)",
                    "",
                    $result
                );
            }

            $result = html_entity_decode($result);
            $result = trim($result);
        }

        return $results;

    }

    public function regexBidIds($html)
    {

        $pattern1 = "/data-id=\"([0-9]+)\">/";
        $pattern2 = "/data-lot-id=\"([0-9]+)\"/";

        preg_match_all($pattern1, $html, $match1, PREG_PATTERN_ORDER);
        preg_match_all($pattern2, $html, $match2, PREG_PATTERN_ORDER);

        $data_id = $match1[1];
//        $data_id = $match1[1][1];
        $data_lot_id = $match2[1];
//        $data_lot_id = $match2[1][1];
        $cardsCount = count($match1[1]);

        return array(
            'protocolBidId' => $data_id,
            'protocolLotId' => $data_lot_id,
            'count' => $cardsCount
        );
    }
}
