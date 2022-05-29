alter session set "_ORACLE_SCRIPT"=true;
declare
userexist integer;

begin
select count(*) into userexist from dba_users where username='workflow';
if (userexist = 0) then
        execute immediate 'CREATE USER workflow IDENTIFIED BY mypassword';
end if;
end;

/
DROP USER workflow CASCADE;

CREATE USER workflow IDENTIFIED BY mypassword;

ALTER USER workflow DEFAULT TABLESPACE users
    QUOTA UNLIMITED ON users;
ALTER USER workflow TEMPORARY TABLESPACE temp;

GRANT CREATE DIMENSION         TO workflow;
GRANT QUERY REWRITE            TO workflow;
GRANT CREATE MATERIALIZED VIEW TO workflow;


GRANT CREATE SESSION           TO workflow;
GRANT CREATE SYNONYM           TO workflow;
GRANT CREATE TABLE             TO workflow;
GRANT CREATE VIEW              TO workflow;
GRANT CREATE SEQUENCE          TO workflow;
GRANT CREATE CLUSTER           TO workflow;
GRANT CREATE DATABASE LINK     TO workflow;
GRANT ALTER SESSION            TO workflow;

GRANT RESOURCE , UNLIMITED TABLESPACE              TO workflow;
GRANT select_catalog_role   TO workflow;
