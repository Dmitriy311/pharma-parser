CREATE TABLE Purchases (
                           id INT AUTO_INCREMENT PRIMARY KEY, -- Идентификатор (id)
                           purchase_number VARCHAR(100) NOT NULL, -- Номер закупки
                           object_of_purchase VARCHAR(255) NOT NULL, -- Объект закупки
                           initial_price DECIMAL(10, 2), -- Начальная цена
                           placed_in_eis BOOLEAN, -- Размещено в ЕИС
                           placed_on_ep BOOLEAN, -- Размещено на ЭП
                           document_status VARCHAR(100), -- Статус документа
                           protocol_name VARCHAR(255), -- Наименование протокола
                           organization_name VARCHAR(255), -- Организация, осуществляющая размещение протокола
                           announcement TEXT, -- Извещение (изменение извещения) о проведении электронного аукциона
                           auction_result_location VARCHAR(255), -- Место подведения итогов электронного аукциона
                           protocol_creation_datetime DATETIME, -- Дата и время составления протокола
                           protocol_signing_date DATETIME, -- Дата подписания протокола
                           commission VARCHAR(255), -- Комиссия
                           commission_legality VARCHAR(255), -- Комиссия правомочна осуществлять свои функции в соответствии с Федеральным законом №44-ФЗ
                           total_commission_members INT, -- Всего членов комиссии
                           non_voting_members_count INT, -- Количество неголосующих членов комиссии
                           present_members_count INT -- Количество присутствовавших членов комиссии
);

CREATE TABLE Participants (
                              id INT AUTO_INCREMENT PRIMARY KEY, -- Идентификатор (id)
                              type_of_participant VARCHAR(100) NOT NULL, -- Вид
                              legal_form VARCHAR(100), -- Организационно-правовая форма
                              full_name VARCHAR(255) NOT NULL, -- Полное наименование (включая организационно-правовую форму)
                              inn VARCHAR(12) NOT NULL, -- ИНН
                              kpp VARCHAR(12), -- КПП
                              postal_address VARCHAR(255) -- Почтовый адрес
);

CREATE TABLE PurchaseParticipants (
                                      purchase_id INT, -- Идентификатор закупки (purchase_id)
                                      participant_id INT, -- Идентификатор участника (participant_id)
                                      role VARCHAR(255), -- Роль (необязательное поле для хранения информации о роли)
                                      PRIMARY KEY (purchase_id, participant_id), -- Первичный ключ (пара - purchase_id и participant_id)
                                      FOREIGN KEY (purchase_id) REFERENCES Purchases(id), -- Внешний ключ на таблицу Purchases
                                      FOREIGN KEY (participant_id) REFERENCES Participants(id) -- Внешний ключ на таблицу Participants
);

CREATE TABLE DocumentLinks (
                               id INT AUTO_INCREMENT PRIMARY KEY, -- Идентификатор (id)
                               purchase_id INT,  -- Идентификатор закупки (purchase_id)
                               document_link TEXT NOT NULL, -- Ссылка на документ (document_link)
                               FOREIGN KEY (purchase_id) REFERENCES Purchases(id) -- Внешний ключ на таблицу Purchases
);