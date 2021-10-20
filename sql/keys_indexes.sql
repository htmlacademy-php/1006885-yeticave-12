USE yeticave;

ALTER TABLE lot
    ADD CONSTRAINT lot_owner_id_fk
        FOREIGN KEY (owner_id) REFERENCES user(id)
        ON DELETE CASCADE,
    ADD CONSTRAINT lot_winner_id_fk
        FOREIGN KEY (winner_id) REFERENCES user(id)
        ON DELETE SET NULL,
    ADD CONSTRAINT lot_category_id_fk
        FOREIGN KEY (category_id) REFERENCES category(id)
        ON DELETE SET NULL;

ALTER TABLE lot
    ADD CONSTRAINT lot_winner_bet_id_fk
        FOREIGN KEY (winner_bet_id) REFERENCES bet(id)
        ON DELETE SET NULL;

ALTER TABLE bet
    ADD CONSTRAINT lot_user_id_fk
        FOREIGN KEY (user_id) REFERENCES user(id)
            ON DELETE CASCADE,
    ADD CONSTRAINT lot_lot_id_fk
        FOREIGN KEY (lot_id) REFERENCES lot(id)
            ON DELETE CASCADE;

CREATE INDEX user_email ON user(email);

CREATE FULLTEXT INDEX lot_ft_search ON lot(lot_name, lot_desc);
