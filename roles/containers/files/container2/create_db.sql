CREATE USER dog WITH PASSWORD 'dogowner';
CREATE DATABASE doghouse;

GRANT CONNECT ON DATABASE doghouse TO dog;

\c doghouse

CREATE TABLE dogs (
    id            SERIAL PRIMARY KEY,
    email         VARCHAR(120) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO dogs (email, password_hash) 
VALUES (
    'cosmo@doghouse.ctf',
    '$2a$10$veYzKvV18txubDLwknd9BOoruC3rUSpLkSlxyNs475J7sG4.75xLS'
);

GRANT pg_read_all_data TO dog;