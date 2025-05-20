drop schema mba cascade;

create schema mba;
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

create table mba.contract
(
    id_contract uuid not null default uuid_generate_v4() primary key,
    description text,
    amount      numeric,
    periods     integer,
    date        timestamp
);

create table mba.payment
(
    id_payment  uuid not null default uuid_generate_v4() primary key,
    id_contract uuid references mba.contract (id_contract),
    amount      numeric,
    date        timestamp
);

INSERT INTO mba.contract (id_contract, description, amount, periods, date)
VALUES ('a1b2c3d4-e5f6-7890-a1b2-c3d4e5f67890',
        'Contrato de Empréstimo Pessoal',
        10000.00,
        12,
        '2023-01-15 09:00:00');

INSERT INTO mba.payment (id_payment, id_contract, amount, date)
VALUES ('b1c2d3e4-f5a6-7890-b1c2-d3e4f5a67890',
        'a1b2c3d4-e5f6-7890-a1b2-c3d4e5f67890',
        6000,
        '2023-02-01 10:00:00');