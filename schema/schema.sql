--
-- PostgreSQL database dump
--

-- Dumped from database version 9.5.0
-- Dumped by pg_dump version 9.5.0

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner:
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner:
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: boiler; Type: TABLE; Schema: public; Owner: doug
--

CREATE TABLE boiler (
    id integer NOT NULL,
    datetime timestamp without time zone,
    data jsonb,
    error text,
    state smallint
);


ALTER TABLE boiler OWNER TO doug;

--
-- Name: boiler_id_seq; Type: SEQUENCE; Schema: public; Owner: doug
--

CREATE SEQUENCE boiler_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE boiler_id_seq OWNER TO doug;

--
-- Name: boiler_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: doug
--

ALTER SEQUENCE boiler_id_seq OWNED BY boiler.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: doug
--

ALTER TABLE ONLY boiler ALTER COLUMN id SET DEFAULT nextval('boiler_id_seq'::regclass);


--
-- Name: boiler_pkey; Type: CONSTRAINT; Schema: public; Owner: doug
--

ALTER TABLE ONLY boiler
    ADD CONSTRAINT boiler_pkey PRIMARY KEY (id);


--
-- Name: public; Type: ACL; Schema: -; Owner: doug
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM doug;
GRANT ALL ON SCHEMA public TO doug;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

