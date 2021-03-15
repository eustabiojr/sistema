--
-- PostgreSQL database dump
--

-- Dumped from database version 12.6 (Ubuntu 12.6-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.6 (Ubuntu 12.6-0ubuntu0.20.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: id_cidade; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_cidade
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_cidade OWNER TO eustabiojr;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cidade; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.cidade (
    id integer DEFAULT nextval('public.id_cidade'::regclass) NOT NULL,
    nome character varying(100) NOT NULL,
    cep character varying(10),
    id_estado integer
);


ALTER TABLE public.cidade OWNER TO eustabiojr;

--
-- Name: id_conta_apagar; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_conta_apagar
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_conta_apagar OWNER TO eustabiojr;

--
-- Name: conta_apagar; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.conta_apagar (
    id integer DEFAULT nextval('public.id_conta_apagar'::regclass) NOT NULL,
    cancelada boolean,
    id_fornecedor integer,
    referencia integer,
    data_emissao date,
    data_vencimento date,
    valor numeric(20,4) NOT NULL,
    data_pagamento date,
    anotacoes text,
    observacoes text
);


ALTER TABLE public.conta_apagar OWNER TO eustabiojr;

--
-- Name: id_conta_areceber; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_conta_areceber
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_conta_areceber OWNER TO eustabiojr;

--
-- Name: conta_areceber; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.conta_areceber (
    id integer DEFAULT nextval('public.id_conta_areceber'::regclass) NOT NULL,
    cancelada boolean,
    id_cliente integer,
    referencia integer,
    data_emissao date,
    data_vencimento date,
    valor numeric(20,4) NOT NULL,
    data_pagamento date,
    anotacoes text,
    observacoes text,
    situacao character varying(1)
);


ALTER TABLE public.conta_areceber OWNER TO eustabiojr;

--
-- Name: id_estado; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_estado
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_estado OWNER TO eustabiojr;

--
-- Name: estado; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.estado (
    id integer DEFAULT nextval('public.id_estado'::regclass) NOT NULL,
    sigla character varying(2),
    nome character varying(50) NOT NULL,
    capital character varying(50)
);


ALTER TABLE public.estado OWNER TO eustabiojr;

--
-- Name: id_fabricante; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_fabricante
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_fabricante OWNER TO eustabiojr;

--
-- Name: fabricante; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.fabricante (
    id integer DEFAULT nextval('public.id_fabricante'::regclass) NOT NULL,
    nome character varying(50) NOT NULL,
    telefone character varying(15),
    site character varying(30)
);


ALTER TABLE public.fabricante OWNER TO eustabiojr;

--
-- Name: id_fornecedor; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_fornecedor
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_fornecedor OWNER TO eustabiojr;

--
-- Name: fornecedor; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.fornecedor (
    id integer DEFAULT nextval('public.id_fornecedor'::regclass) NOT NULL,
    razao_social character varying(100) NOT NULL,
    rg_ie character varying(20),
    cpf_cnpj character varying(20),
    data_fundacao date,
    site character varying(100),
    email character varying(100),
    cep character varying(10),
    endereco character varying(100),
    complemento character varying(120),
    numero character varying(5),
    bairro character varying(100),
    cidade character varying(100),
    telefone character varying(15),
    referencia integer NOT NULL,
    anotacoes text,
    representante character varying(60),
    contato_representante character varying(20),
    data_cadastro timestamp without time zone NOT NULL,
    data_atualizacao timestamp without time zone NOT NULL
);


ALTER TABLE public.fornecedor OWNER TO eustabiojr;

--
-- Name: id_funcionario; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_funcionario
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_funcionario OWNER TO eustabiojr;

--
-- Name: funcionario; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.funcionario (
    id integer DEFAULT nextval('public.id_funcionario'::regclass) NOT NULL,
    nome character varying(100) NOT NULL,
    endereco character varying(120),
    email character varying(100),
    departamento integer,
    idiomas character varying(100),
    contratacao integer
);


ALTER TABLE public.funcionario OWNER TO eustabiojr;

--
-- Name: id_grupo; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_grupo
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_grupo OWNER TO eustabiojr;

--
-- Name: grupo; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.grupo (
    id integer DEFAULT nextval('public.id_grupo'::regclass) NOT NULL,
    nome character varying(50) NOT NULL
);


ALTER TABLE public.grupo OWNER TO eustabiojr;

--
-- Name: id_pessoa_grupo; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_pessoa_grupo
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_pessoa_grupo OWNER TO eustabiojr;

--
-- Name: grupo_pessoa; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.grupo_pessoa (
    id integer DEFAULT nextval('public.id_pessoa_grupo'::regclass) NOT NULL,
    id_pessoa integer,
    id_grupo integer
);


ALTER TABLE public.grupo_pessoa OWNER TO eustabiojr;

--
-- Name: id_item_movimento_estoque; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_item_movimento_estoque
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_item_movimento_estoque OWNER TO eustabiojr;

--
-- Name: id_movimento_estoque; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_movimento_estoque
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_movimento_estoque OWNER TO eustabiojr;

--
-- Name: id_pessoa; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_pessoa
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_pessoa OWNER TO eustabiojr;

--
-- Name: id_produto; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_produto
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_produto OWNER TO eustabiojr;

--
-- Name: id_tipo; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_tipo
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_tipo OWNER TO eustabiojr;

--
-- Name: id_unidade; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_unidade
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_unidade OWNER TO eustabiojr;

--
-- Name: id_usuario; Type: SEQUENCE; Schema: public; Owner: eustabiojr
--

CREATE SEQUENCE public.id_usuario
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.id_usuario OWNER TO eustabiojr;

--
-- Name: item_movimento_estoque; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.item_movimento_estoque (
    id integer DEFAULT nextval('public.id_item_movimento_estoque'::regclass) NOT NULL,
    id_movimento_estoque integer,
    id_produto integer,
    referencia integer NOT NULL,
    quantidade double precision NOT NULL,
    preco numeric(20,4) NOT NULL,
    desconto numeric(3,3),
    acrescimos numeric(3,3),
    ipi numeric(2,2),
    icms numeric(2,2),
    cfop character varying(5),
    ncm integer,
    detalhes text
);


ALTER TABLE public.item_movimento_estoque OWNER TO eustabiojr;

--
-- Name: movimento_estoque; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.movimento_estoque (
    id integer DEFAULT nextval('public.id_movimento_estoque'::regclass) NOT NULL,
    numero_nota_fiscal integer,
    data_movimento timestamp without time zone NOT NULL,
    tipo character varying(1) NOT NULL,
    id_cliente integer,
    nome_cliente character varying(100),
    cpf_cnpj character varying(20),
    cep character varying(10),
    endereco character varying(100),
    complemento character varying(120),
    numero character varying(5),
    bairro character varying(100),
    cidade character varying(100),
    telefone character varying(15),
    referencia integer NOT NULL,
    valor numeric(20,4) NOT NULL,
    desconto numeric(20,4),
    acrescimos numeric(20,4),
    valor_final numeric(20,4) NOT NULL,
    transportadora character varying(100),
    cnpj_transportadora character varying(20),
    frete_por_conta character varying(10),
    qtde_volumes integer,
    valor_frete numeric(10,4),
    danfe character varying(100),
    observacoes text
);


ALTER TABLE public.movimento_estoque OWNER TO eustabiojr;

--
-- Name: pessoa; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.pessoa (
    id integer DEFAULT nextval('public.id_pessoa'::regclass) NOT NULL,
    nome character varying(100) NOT NULL,
    identidade character varying(20),
    cpf character varying(15),
    data_nascimento date,
    pai character varying(100),
    mae character varying(100),
    endereco character varying(120),
    telefone character varying(16),
    email character varying(100),
    id_endereco integer,
    id_telefone integer,
    id_referencia_pessoa integer,
    id_ocupacao integer,
    data_cadastro timestamp without time zone NOT NULL,
    data_atualizacao timestamp without time zone NOT NULL,
    cep character varying(10),
    complemento character varying(120),
    numero character varying(5),
    id_cidade integer,
    bairro character varying(100)
);


ALTER TABLE public.pessoa OWNER TO eustabiojr;

--
-- Name: produto; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.produto (
    id integer DEFAULT nextval('public.id_produto'::regclass) NOT NULL,
    ativacao boolean,
    mostra_internet boolean,
    id_unidade integer,
    descricao character varying(120) NOT NULL,
    estoque double precision,
    id_fabricante integer,
    id_categoria integer,
    id_classe integer,
    id_familia integer,
    id_grupo integer,
    preco_custo_liquido numeric(20,4),
    preco_custo_bruto numeric(20,4) NOT NULL,
    preco_venda numeric(20,4) NOT NULL,
    margem_lucro real,
    codigo_barras character varying(20),
    descricao_breve text,
    caracteristicas text,
    ncm character varying(80),
    codigo_fornecedor_id integer,
    ultima_compra timestamp without time zone,
    ultima_venda timestamp without time zone,
    data_cadastro timestamp without time zone NOT NULL,
    data_atualizacao timestamp without time zone NOT NULL,
    id_tipo integer,
    qr_code character varying(200),
    desconto numeric(2,2)
);


ALTER TABLE public.produto OWNER TO eustabiojr;

--
-- Name: tipo; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.tipo (
    id integer DEFAULT nextval('public.id_tipo'::regclass) NOT NULL,
    nome character varying(50) NOT NULL
);


ALTER TABLE public.tipo OWNER TO eustabiojr;

--
-- Name: unidade; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.unidade (
    id integer DEFAULT nextval('public.id_unidade'::regclass) NOT NULL,
    sigla character varying(50) NOT NULL,
    nome character varying(50) NOT NULL
);


ALTER TABLE public.unidade OWNER TO eustabiojr;

--
-- Name: usuario; Type: TABLE; Schema: public; Owner: eustabiojr
--

CREATE TABLE public.usuario (
    id integer DEFAULT nextval('public.id_usuario'::regclass) NOT NULL,
    nome text,
    senha character varying(120),
    email character varying(80),
    privilegio character varying(15),
    validade date
);


ALTER TABLE public.usuario OWNER TO eustabiojr;

--
-- Name: visao_saldo_pessoa; Type: VIEW; Schema: public; Owner: eustabiojr
--

CREATE VIEW public.visao_saldo_pessoa AS
 SELECT pessoa.id,
    pessoa.nome,
    pessoa.endereco,
    pessoa.bairro,
    pessoa.telefone,
    pessoa.email,
    ( SELECT sum(conta_areceber.valor) AS sum
           FROM public.conta_areceber
          WHERE (conta_areceber.id_cliente = pessoa.id)) AS total,
    ( SELECT sum(conta_areceber.valor) AS sum
           FROM public.conta_areceber
          WHERE ((conta_areceber.id_cliente = pessoa.id) AND ((conta_areceber.situacao)::text = 'N'::text))) AS aberto
   FROM public.pessoa
  ORDER BY ( SELECT sum(conta_areceber.valor) AS sum
           FROM public.conta_areceber
          WHERE ((conta_areceber.id_cliente = pessoa.id) AND ((conta_areceber.situacao)::text = 'N'::text))) DESC;


ALTER TABLE public.visao_saldo_pessoa OWNER TO eustabiojr;

--
-- Data for Name: cidade; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.cidade (id, nome, cep, id_estado) FROM stdin;
1	Prado	45980-000	1
2	Mucuri		1
3	Nova Viçosa		1
4	Caravelas		1
5	Alcobaça		1
6	Porto Seguro		1
7	Belmonte		1
8	Canavieiras		1
9	Una		1
10	Ilhéus		1
11	Itabuna		1
12	Camacã		1
13	Itapebi		1
14	Itagimirim		1
15	Eunápolis		1
16	Itabela		1
17	Itamarajú		1
18	Teixeira de Freitas		1
19	Itanhém		1
20	Ibirapuã		1
21	Vereda		1
22	Buerarema		1
23	Santa Luzia		1
24	Santa Cruz Cabrália		1
25	Medeiros Neto		1
26	Lajedão		1
27	Guaratinga		1
28	São Mateus		25
29	Conceição da Barra		25
30	Linhares		25
31	Fundão		25
32	Cariacica		25
33	Vila Velha		25
34	Serra		25
35	Pinheiros		25
36	Pedro Canário		25
37	Nanuque		19
38	Teófilo Otoni		19
39	Montes Claros		19
40	Salto da Divisa		19
41	Pedra Azul		19
42	Governador Valadares		19
44	Sooretama		25
43	Ouro Preto		19
45	Ipatinga	\N	19
46	Ipatinga	\N	19
\.


--
-- Data for Name: conta_apagar; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.conta_apagar (id, cancelada, id_fornecedor, referencia, data_emissao, data_vencimento, valor, data_pagamento, anotacoes, observacoes) FROM stdin;
\.


--
-- Data for Name: conta_areceber; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.conta_areceber (id, cancelada, id_cliente, referencia, data_emissao, data_vencimento, valor, data_pagamento, anotacoes, observacoes, situacao) FROM stdin;
1	\N	1	1	2020-05-01	2020-06-01	110.5000	\N	\N	\N	N
2	\N	2	1	2020-05-01	2020-06-15	110.5000	\N	\N	\N	N
3	\N	4	\N	2020-05-15	2020-05-17	1710.0000	\N	\N	\N	N
4	\N	4	\N	2020-05-15	2020-06-17	1710.0000	\N	\N	\N	N
5	\N	4	\N	2020-05-15	2020-07-17	1710.0000	\N	\N	\N	N
6	\N	3	\N	2020-05-17	2020-05-19	2700.0000	\N	\N	\N	N
7	\N	3	\N	2020-05-17	2020-06-19	2700.0000	\N	\N	\N	N
8	\N	3	\N	2020-05-17	2020-07-19	2700.0000	\N	\N	\N	N
\.


--
-- Data for Name: estado; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.estado (id, sigla, nome, capital) FROM stdin;
1	BA	Bahia	Salvador
2	AM	Amazonas	Manaus
3	AP	Amapá	
4	AL	Alagoas	
5	SE	Sergipe	
6	PE	Pernanbuco	Recife
7	PB	Paraíba	São Luiz
8	MA	Maranhão	
9	RN	Rio Grande do Norte	
10	CE	Ceará	Fortaleza
11	TO	Tocantins	Palmas
12	GO	Goiás	Goiânia
13	DF	Distrito Federal	Brasília
15	RR	Roraima	
17	MG	Mato Grosso	Cuiabá
18	MS	Mato Grosso do Sul	
19	MG	Minas Gerais	Belo Horizonte
20	SP	São Paulo	São Paulo
21	RJ	Rio de Janeiro	Rio de Janeiro
22	PR	Paraná	Curitiba
23	SC	Santa Catarina	Florionópolis
24	RS	Rio Grande do Sul	
25	ES	Espírito Santo	Vitória
26	AC	Acre	Boa Vista
14	PI	Piauí	
16	RD	Rodônia	
\.


--
-- Data for Name: fabricante; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.fabricante (id, nome, telefone, site) FROM stdin;
1	Samsung	0800 124 421	www.samsung.com
2	LG	0800 707 5454	www.lge.com.br
3	Motorola	0800 000	www.motorola.com.br
\.


--
-- Data for Name: fornecedor; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.fornecedor (id, razao_social, rg_ie, cpf_cnpj, data_fundacao, site, email, cep, endereco, complemento, numero, bairro, cidade, telefone, referencia, anotacoes, representante, contato_representante, data_cadastro, data_atualizacao) FROM stdin;
\.


--
-- Data for Name: funcionario; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.funcionario (id, nome, endereco, email, departamento, idiomas, contratacao) FROM stdin;
3	Tamiles Pereira da Silva	Avenida Amazonas, 26	tamiles@ageueletro.com.br	2	\N	\N
2	Evanilde Pereira da Silva	Avenida Amazonas, 26	evanilde@ageueletro.com.br	1	2	\N
1	Eustábio Jesus Silva Jr.	Avenida 2 de Julho, 610	eustabiojr@ageueletro.com.br	1	1	\N
\.


--
-- Data for Name: grupo; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.grupo (id, nome) FROM stdin;
1	Cliente
2	Fornecedor
3	Transportadora
4	Funcionario
\.


--
-- Data for Name: grupo_pessoa; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.grupo_pessoa (id, id_pessoa, id_grupo) FROM stdin;
1	1	1
2	1	3
\.


--
-- Data for Name: item_movimento_estoque; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.item_movimento_estoque (id, id_movimento_estoque, id_produto, referencia, quantidade, preco, desconto, acrescimos, ipi, icms, cfop, ncm, detalhes) FROM stdin;
1	15	12	1	2	1350.0000	\N	\N	\N	\N	\N	\N	\N
2	15	15	1	1	1150.0000	\N	\N	\N	\N	\N	\N	\N
3	16	12	1	2	1350.0000	\N	\N	\N	\N	\N	\N	\N
4	16	15	1	1	1150.0000	\N	\N	\N	\N	\N	\N	\N
\.


--
-- Data for Name: movimento_estoque; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.movimento_estoque (id, numero_nota_fiscal, data_movimento, tipo, id_cliente, nome_cliente, cpf_cnpj, cep, endereco, complemento, numero, bairro, cidade, telefone, referencia, valor, desconto, acrescimos, valor_final, transportadora, cnpj_transportadora, frete_por_conta, qtde_volumes, valor_frete, danfe, observacoes) FROM stdin;
1	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	0.0000	0.0000	0.0000	0.0000	\N	\N	\N	\N	\N	\N	Obs
2	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	0.0000	0.0000	0.0000	0.0000	\N	\N	\N	\N	\N	\N	Obs
3	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	0.0000	0.0000	0.0000	0.0000	\N	\N	\N	\N	\N	\N	Obs
4	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	0.0000	0.0000	0.0000	0.0000	\N	\N	\N	\N	\N	\N	Obs
5	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	0.0000	0.0000	0.0000	0.0000	\N	\N	\N	\N	\N	\N	Obs
6	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	0.0000	0.0000	0.0000	0.0000	\N	\N	\N	\N	\N	\N	Obs
7	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	0.0000	0.0000	0.0000	0.0000	\N	\N	\N	\N	\N	\N	Obs
8	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	0.0000	0.0000	0.0000	0.0000	\N	\N	\N	\N	\N	\N	Obs
9	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	0.0000	0.0000	0.0000	0.0000	\N	\N	\N	\N	\N	\N	Obs
10	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	0.0000	0.0000	0.0000	0.0000	\N	\N	\N	\N	\N	\N	Obs
11	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	3850.0000	0.0000	0.0000	3850.0000	\N	\N	\N	\N	\N	\N	Obs
12	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	3850.0000	0.0000	0.0000	3850.0000	\N	\N	\N	\N	\N	\N	Obs
13	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	3850.0000	0.0000	0.0000	3850.0000	\N	\N	\N	\N	\N	\N	Obs
14	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	3850.0000	0.0000	0.0000	3850.0000	\N	\N	\N	\N	\N	\N	Obs
15	\N	2020-05-01 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	3850.0000	0.0000	0.0000	3850.0000	\N	\N	\N	\N	\N	\N	Obs
16	\N	2020-05-02 00:00:00	S	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	3850.0000	0.0000	0.0000	3850.0000	\N	\N	\N	\N	\N	\N	Obs
17	\N	2020-05-15 00:00:00	S	4	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	10260.0000	0.0000	0.0000	5130.0000	\N	\N	\N	\N	\N	\N	\N
18	\N	2020-05-17 00:00:00	S	3	\N	\N	\N	\N	\N	\N	\N	\N	\N	1	16200.0000	\N	\N	8100.0000	\N	\N	\N	\N	\N	\N	fadfad
\.


--
-- Data for Name: pessoa; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.pessoa (id, nome, identidade, cpf, data_nascimento, pai, mae, endereco, telefone, email, id_endereco, id_telefone, id_referencia_pessoa, id_ocupacao, data_cadastro, data_atualizacao, cep, complemento, numero, id_cidade, bairro) FROM stdin;
1	Eustábio Jesus da Silva Júnior	\N	61519880510	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2020-04-10 00:00:00	2020-04-10 00:00:00	\N	\N	\N	1	\N
2	Evanilde Pereira da Silva	\N	03629822776	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2020-04-10 00:00:00	2020-04-10 00:00:00	\N	\N	\N	1	\N
3	afdfdf	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	\N	2020-05-03 00:00:00	2020-05-03 00:00:00	\N	\N	\N	1	\N
4	Eustábio J. Silva Jr.	\N	\N	\N	\N	\N	Avenida Amazonas, 26	São Brás	evanilde@ageueletro.com.br	\N	\N	\N	\N	2020-05-07 00:00:00	2020-05-07 00:00:00	\N	\N	\N	1	São Brás
\.


--
-- Data for Name: produto; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.produto (id, ativacao, mostra_internet, id_unidade, descricao, estoque, id_fabricante, id_categoria, id_classe, id_familia, id_grupo, preco_custo_liquido, preco_custo_bruto, preco_venda, margem_lucro, codigo_barras, descricao_breve, caracteristicas, ncm, codigo_fornecedor_id, ultima_compra, ultima_venda, data_cadastro, data_atualizacao, id_tipo, qr_code, desconto) FROM stdin;
15	\N	\N	\N	Celular Samsung Galaxy A10S Preto	4	\N	\N	\N	\N	\N	\N	880.0000	1150.0000	0.16	\N	\N	\N	\N	\N	\N	\N	2020-05-01 00:00:00	2020-05-01 00:00:00	\N	\N	0.20
16	\N	\N	\N	Celular Samsung Galaxy A20S Vermelho	4	\N	\N	\N	\N	\N	\N	990.0000	1450.0000	0.16	\N	\N	\N	\N	\N	\N	\N	2020-05-01 00:00:00	2020-05-01 00:00:00	\N	\N	0.20
13	\N	\N	1	Televisor LED 32 Pol. LG Smart TV	4	2	\N	\N	\N	\N	\N	895.0000	1350.0000	0.16	\N	\N	\N	\N	\N	\N	\N	2020-05-01 00:00:00	2020-05-01 00:00:00	\N	\N	0.20
14	\N	\N	\N	Televisor LED 43 Pol. Samsung Smart TV	4	\N	\N	\N	\N	\N	\N	1250.0000	1890.0000	0.16	\N	\N	\N	\N	\N	\N	\N	2020-05-01 00:00:00	2020-05-01 00:00:00	\N	\N	0.25
12	\N	\N	1	Televisor LED 32 Pol. Samsung Smart TV	4	1	\N	\N	\N	\N	\N	895.0000	1350.0000	0.16	\N	\N	\N	\N	\N	\N	\N	2020-05-01 00:00:00	2020-05-01 00:00:00	\N	\N	0.22
\.


--
-- Data for Name: tipo; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.tipo (id, nome) FROM stdin;
1	produto
2	servico
\.


--
-- Data for Name: unidade; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.unidade (id, sigla, nome) FROM stdin;
1	UN	Unidade
2	PC	Pacote
\.


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: eustabiojr
--

COPY public.usuario (id, nome, senha, email, privilegio, validade) FROM stdin;
\.


--
-- Name: id_cidade; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_cidade', 44, true);


--
-- Name: id_conta_apagar; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_conta_apagar', 1, false);


--
-- Name: id_conta_areceber; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_conta_areceber', 2, true);


--
-- Name: id_estado; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_estado', 27, true);


--
-- Name: id_fabricante; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_fabricante', 3, true);


--
-- Name: id_fornecedor; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_fornecedor', 1, false);


--
-- Name: id_funcionario; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_funcionario', 1, false);


--
-- Name: id_grupo; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_grupo', 4, true);


--
-- Name: id_item_movimento_estoque; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_item_movimento_estoque', 1, false);


--
-- Name: id_movimento_estoque; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_movimento_estoque', 1, false);


--
-- Name: id_pessoa; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_pessoa', 1, false);


--
-- Name: id_pessoa_grupo; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_pessoa_grupo', 1, false);


--
-- Name: id_produto; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_produto', 16, true);


--
-- Name: id_tipo; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_tipo', 2, true);


--
-- Name: id_unidade; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_unidade', 2, true);


--
-- Name: id_usuario; Type: SEQUENCE SET; Schema: public; Owner: eustabiojr
--

SELECT pg_catalog.setval('public.id_usuario', 1, false);


--
-- Name: cidade cidade_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.cidade
    ADD CONSTRAINT cidade_pkey PRIMARY KEY (id);


--
-- Name: pessoa cliente_cpf_key; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.pessoa
    ADD CONSTRAINT cliente_cpf_key UNIQUE (cpf);


--
-- Name: pessoa cliente_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.pessoa
    ADD CONSTRAINT cliente_pkey PRIMARY KEY (id);


--
-- Name: conta_apagar conta_apagar_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.conta_apagar
    ADD CONSTRAINT conta_apagar_pkey PRIMARY KEY (id);


--
-- Name: conta_areceber conta_areceber_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.conta_areceber
    ADD CONSTRAINT conta_areceber_pkey PRIMARY KEY (id);


--
-- Name: estado estado_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.estado
    ADD CONSTRAINT estado_pkey PRIMARY KEY (id);


--
-- Name: fabricante fabricante_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.fabricante
    ADD CONSTRAINT fabricante_pkey PRIMARY KEY (id);


--
-- Name: fornecedor fornecedor_cpf_cnpj_key; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.fornecedor
    ADD CONSTRAINT fornecedor_cpf_cnpj_key UNIQUE (cpf_cnpj);


--
-- Name: fornecedor fornecedor_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.fornecedor
    ADD CONSTRAINT fornecedor_pkey PRIMARY KEY (id);


--
-- Name: funcionario funcionario_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.funcionario
    ADD CONSTRAINT funcionario_pkey PRIMARY KEY (id);


--
-- Name: grupo grupo_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.grupo
    ADD CONSTRAINT grupo_pkey PRIMARY KEY (id);


--
-- Name: item_movimento_estoque item_movimento_estoque_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.item_movimento_estoque
    ADD CONSTRAINT item_movimento_estoque_pkey PRIMARY KEY (id);


--
-- Name: movimento_estoque movimento_estoque_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.movimento_estoque
    ADD CONSTRAINT movimento_estoque_pkey PRIMARY KEY (id);


--
-- Name: grupo_pessoa pessoa_grupo_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.grupo_pessoa
    ADD CONSTRAINT pessoa_grupo_pkey PRIMARY KEY (id);


--
-- Name: produto produto_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.produto
    ADD CONSTRAINT produto_pkey PRIMARY KEY (id);


--
-- Name: tipo tipo_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.tipo
    ADD CONSTRAINT tipo_pkey PRIMARY KEY (id);


--
-- Name: unidade unidade_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.unidade
    ADD CONSTRAINT unidade_pkey PRIMARY KEY (id);


--
-- Name: usuario usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id);


--
-- Name: cidade cidade_id_estado_fkey; Type: FK CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.cidade
    ADD CONSTRAINT cidade_id_estado_fkey FOREIGN KEY (id_estado) REFERENCES public.estado(id);


--
-- Name: conta_apagar conta_apagar_id_fornecedor_fkey; Type: FK CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.conta_apagar
    ADD CONSTRAINT conta_apagar_id_fornecedor_fkey FOREIGN KEY (id_fornecedor) REFERENCES public.fornecedor(id);


--
-- Name: conta_areceber conta_areceber_id_cliente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.conta_areceber
    ADD CONSTRAINT conta_areceber_id_cliente_fkey FOREIGN KEY (id_cliente) REFERENCES public.pessoa(id);


--
-- Name: item_movimento_estoque item_movimento_estoque_id_movimento_estoque_fkey; Type: FK CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.item_movimento_estoque
    ADD CONSTRAINT item_movimento_estoque_id_movimento_estoque_fkey FOREIGN KEY (id_movimento_estoque) REFERENCES public.movimento_estoque(id);


--
-- Name: item_movimento_estoque item_movimento_estoque_id_produto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.item_movimento_estoque
    ADD CONSTRAINT item_movimento_estoque_id_produto_fkey FOREIGN KEY (id_produto) REFERENCES public.produto(id);


--
-- Name: movimento_estoque movimento_estoque_id_cliente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.movimento_estoque
    ADD CONSTRAINT movimento_estoque_id_cliente_fkey FOREIGN KEY (id_cliente) REFERENCES public.pessoa(id);


--
-- Name: grupo_pessoa pessoa_grupo_id_grupo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.grupo_pessoa
    ADD CONSTRAINT pessoa_grupo_id_grupo_fkey FOREIGN KEY (id_grupo) REFERENCES public.grupo(id);


--
-- Name: grupo_pessoa pessoa_grupo_id_pessoa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.grupo_pessoa
    ADD CONSTRAINT pessoa_grupo_id_pessoa_fkey FOREIGN KEY (id_pessoa) REFERENCES public.pessoa(id);


--
-- Name: pessoa pessoa_id_cidade_fkey; Type: FK CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.pessoa
    ADD CONSTRAINT pessoa_id_cidade_fkey FOREIGN KEY (id_cidade) REFERENCES public.cidade(id);


--
-- Name: produto produto_id_fabricante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.produto
    ADD CONSTRAINT produto_id_fabricante_fkey FOREIGN KEY (id_fabricante) REFERENCES public.fabricante(id);


--
-- Name: produto produto_unidade_fkey; Type: FK CONSTRAINT; Schema: public; Owner: eustabiojr
--

ALTER TABLE ONLY public.produto
    ADD CONSTRAINT produto_unidade_fkey FOREIGN KEY (id_unidade) REFERENCES public.unidade(id);


--
-- PostgreSQL database dump complete
--

