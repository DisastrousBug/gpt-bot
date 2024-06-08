DO
$do$
BEGIN
   -- Create chatbot_user if it does not exist
   IF NOT EXISTS (
      SELECT FROM pg_catalog.pg_roles
      WHERE rolname = 'chatbot_user') THEN
      CREATE ROLE chatbot_user WITH LOGIN PASSWORD 'password';
   END IF;

   -- Create postgres role if it does not exist
   IF NOT EXISTS (
      SELECT FROM pg_catalog.pg_roles
      WHERE rolname = 'postgres') THEN
      CREATE ROLE postgres WITH LOGIN SUPERUSER PASSWORD 'password';
   END IF;

   -- Create chatbot database if it does not exist
   IF NOT EXISTS (
      SELECT FROM pg_catalog.pg_database
      WHERE datname = 'chatbot') THEN
      CREATE DATABASE chatbot;
   END IF;
END
$do$;

-- Grant privileges
GRANT ALL PRIVILEGES ON DATABASE chatbot TO chatbot_user;
