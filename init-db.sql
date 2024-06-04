DO
$do$
BEGIN
   IF NOT EXISTS (
      SELECT
      FROM pg_catalog.pg_database
      WHERE datname = 'chatbot') THEN
      CREATE DATABASE chatbot;
END IF;
END
$do$;
