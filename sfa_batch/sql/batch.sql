TRUNCATE SRRTB010;
insert into SRRTB010 select * from v_SRRTB010;
TRUNCATE SRRTB011;
INSERT INTO SRRTB011 select * from v_SRRTB011;
TRUNCATE SRRTB012;
INSERT INTO SRRTB012 select * from v_SRRTB012;
TRUNCATE SRRTB013;
INSERT INTO SRRTB013 select * from v_SRRTB013;

TRUNCATE SRRTB020;
insert into SRRTB020 select * from v_SRRTB020;
TRUNCATE SRRTB021;
insert into SRRTB021 select * from v_SRRTB021;
TRUNCATE SRRTB022;
insert into SRRTB022 select * from v_SRRTB022;
TRUNCATE SRRTB023;
insert into SRRTB023 select * from v_SRRTB023;
  
--①担当者別訪問時間 データ作成SQL
TRUNCATE SRRTB030;
INSERT INTO SRRTB030 select * from v_SRRTB030;

--②ユニット別行動時間 データ作成SQL
TRUNCATE SRRTB031;
INSERT INTO SRRTB031 select * from v_SRRTB031;

--③部別行動時間 データ作成SQL
TRUNCATE SRRTB032;
INSERT INTO SRRTB032 select * from v_SRRTB032;

--本部別行動時間
TRUNCATE SRRTB033;
INSERT INTO SRRTB033 select * from v_SRRTB033;


-- ①販売店別半期提案進行状況作成SQL

-- 販売店別半期提案進行状況を作成するのに必要な情報
-- 1.社員毎の担当販売店（進捗を知るため、該当実績がない販売店も表示する。）
-- 2.社員毎の販売店営業実績(一度提案した内容については重複してカウントしない！)
TRUNCATE SRRTB040;
INSERT INTO SRRTB040 SELECT * FROM v_SRRTB040;

-- ②担当者別半期提案進行状況作成SQL
-- （①で作成したデータをベースとしてinsertする。）
TRUNCATE SRRTB041;
INSERT INTO SRRTB041 select * from v_SRRTB041;

-- ③ユニット別半期提案進行状況作成SQL
-- （②で作成したデータをベースとしてinsertする。）
TRUNCATE SRRTB042;
INSERT INTO SRRTB042 select * from v_SRRTB042;

-- ④部別別半期提案進行状況作成SQL
-- （③で作成したデータをベースとしてinsertする。）
TRUNCATE SRRTB043;
INSERT INTO SRRTB043 select * from v_SRRTB043;

-- ⑤本部別別半期提案進行状況作成SQL
-- （④で作成したデータをベースとしてinsertする。）
TRUNCATE SRRTB044;
INSERT INTO SRRTB044 select * from v_SRRTB044;

TRUNCATE SRRTB050;
INSERT INTO SRRTB050 select * from v_SRRTB050;
TRUNCATE SRRTB051;
INSERT INTO SRRTB051 select * from v_SRRTB051;
TRUNCATE SRRTB052;
INSERT INTO SRRTB052 select * from v_SRRTB052;
TRUNCATE  SRRTB053;
INSERT INTO SRRTB053 select * from v_SRRTB053;
TRUNCATE  SRRTB054;
INSERT INTO SRRTB054 select * from v_SRRTB054;


