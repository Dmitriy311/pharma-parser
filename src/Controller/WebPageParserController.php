<?php

namespace App\Controller;

use App\Parser\DBpersister;
use App\Parser\WebPageParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
class WebPageParserController extends AbstractController
{
    #[Route('/parse', name: 'web_page_parser', methods: ['GET'])]
    public function parse(DBpersister $DBpersister): Response
    {

        //парсим параметры
        $url = $_GET['url'] . "&protocolId=" . $_GET['protocolId'];
        $parsed_url = parse_url($url);
        $query_string = isset($parsed_url['query']) ? $parsed_url['query'] : '';
        parse_str($query_string, $query_params);
        $protocolId = $query_params['protocolId'];
        $regNumber = $query_params['regNumber'];


        //загружаем документ со всех сторон...
        $parser = new WebPageParser($regNumber, $protocolId);

        $mainPage = $parser->loadPage('protocol');
        $appsPage = $parser->loadPage('applications');
        $docsPage = $parser->loadPage('docs');

        //...подключаем магию регулярок...
        $mainPageInfo = $parser->regexFullPage($mainPage);
        $docsPageInfo = $parser->regexDocs($docsPage);

        //...вытаскиваем параметры для симуляции AJAX...
        $bidIds = $parser->regexBidIds($appsPage);
        //...загружаем и парсим карточки участников аяксами...
        $cards = $parser->loadCards($bidIds);

        $count = $bidIds['count'];
        for ($i = 0; $i < $count; $i++) {
            $cardInfo[$i] = $parser->regexFullPage($cards[$i]);
        }

        //здесь готовим данные для таблицы
        //массивы для тайтлов и их значений
        $mainPageTitles = array_values($mainPageInfo[0]['title']);
        $docsPageTitles = ['Прикрепленные файлы'];
        $names = array_merge($mainPageTitles, $docsPageTitles);

        $mainPageValues = array_values($mainPageInfo[0]['content']);
        $docsPageLinksTitles = array_values($docsPageInfo['title']);
        $docsPageLinksValues = array_values($docsPageInfo['content']);


        for ($i = 0; $i < count($docsPageLinksTitles); $i++) {
            $docsPageLinksValues[$i] = '<a href="' . $docsPageLinksValues[$i] . '">' . $docsPageLinksTitles[$i] . '</a>';
        }
        $docsPageLinksValues = [implode(" ", $docsPageLinksValues)];

        $values = array_merge($mainPageValues, $docsPageLinksValues);

        //готовим данные для сохранения результата в бд
        //ссылки с их тайтлами сериализуем и добавляем к таблице закупок
        $persistLinks = serialize($docsPageInfo);
        array_push($mainPageValues, $persistLinks);
        $DBpersister->persist($cardInfo, $mainPageValues);

        echo '<table border="1">';
        echo '<tr>';
        echo '<th colspan="2">ОБЩАЯ ИНФОРМАЦИЯ</th>';
        echo '</tr>';

        for ($i = 0; $i < count($names); $i++) {
            echo '<tr>';
            echo '<td>' . $names[$i] . '</td>';
            echo '<td>' . $values[$i] . '</td>';
            echo '</tr>';
        }
        echo '</table>';
        echo '</br></br>';

        echo '<table border="1">';
        echo '<tr>';
        echo '<th colspan="2">УЧАСТНИКИ</th>';
        echo '</tr>';

        for ($j = 0; $j < count($cardInfo); $j++) {
            echo '<tr>';
            echo '<th colspan="2">'.($j+1).'</th>';
            echo '</tr>';
            for ($i = 0; $i < count($cardInfo[$j][0]['title']); $i++) {
                echo '<tr>';
                echo '<td>' . $cardInfo[$j][0]['title'][$i] . '</td>';
                echo '<td>' . $cardInfo[$j][0]['content'][$i] . '</td>';
                echo '</tr>';
            }
        }

        die();

    }

    //кликабельная ссылка на загрузку файлов
    #[Route('/app/storage/{file}')]
    public function loadfile($file):Response
    {
        header('Content-type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.$file);
        readfile('/app/storage/'.$file);

        return new Response();
    }
}