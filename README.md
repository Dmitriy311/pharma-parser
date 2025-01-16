Задача для PHP разработчика.
Необходимо спарсить страницу используя регулярные выражения

https://zakupki.gov.ru/epz/order/notice/ea44/view/protocol/protocol-bid-list.html?regNumber=0329200062221006202&protocolId=35530565


1. Собрать данные из основного раздела, тип номер аукциона, начальная цена и т.д, все поля основного блока.
2. Собрать данные со страницы, раздел "Список заявок" из каждой строки в этом разделе необходимо достать данные по Юр Лицу,
3. Из раздела "Общая информация о протоколе" собрать все данные из раздела "Информация о протоколе"
4. Из раздела Документы выгрузить все доступные документы

все поля и сохранить в БД и представить на странице в табличном виде с возможностью скачать файл с локального хранилища

---

Можно собрать на docker compose. https://docs.docker.com/compose/install/

    git clone git@github.com:Dmitriy311/pharma-parser.git
    cd pharma-parser
    docker compose up -d (!в случае сборки на Windows, следует поменять CRLF->LF файла entrypoint.sh!)
    http://localhost:8081/parse?url=https://zakupki.gov.ru/epz/order/notice/ea44/view/protocol/protocol-bid-list.html?regNumber=0329200062221006202&protocolId=35530565

    Ссылку для парсинга можно передавать в GET как указано выше.
