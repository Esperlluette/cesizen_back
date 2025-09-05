WITH pk AS (
  SELECT c.conrelid AS relid, a.attname AS col
  FROM pg_constraint c
  JOIN unnest(c.conkey) WITH ORDINALITY AS cols(attnum, ord) ON true
  JOIN pg_attribute a ON a.attrelid = c.conrelid AND a.attnum = cols.attnum
  WHERE c.contype = 'p'
),
fk AS (
  SELECT c.conrelid AS relid, a.attname AS col, c.confrelid AS refrelid, ra.attname AS refcol, cols.ord
  FROM pg_constraint c
  JOIN unnest(c.conkey)  WITH ORDINALITY AS cols(attnum, ord) ON true
  JOIN pg_attribute a    ON a.attrelid = c.conrelid AND a.attnum = cols.attnum
  JOIN unnest(c.confkey) WITH ORDINALITY AS rcols(attnum, ord) ON rcols.ord = cols.ord
  JOIN pg_attribute ra   ON ra.attrelid = c.confrelid AND ra.attnum = rcols.attnum
  WHERE c.contype = 'f'
)
SELECT
  n.nspname        AS schema_name,
  cls.relname      AS table_name,
  col.ordinal_position AS col_pos,
  col.column_name  AS column,
  col.data_type    AS type,
  col.is_nullable  AS nullable,
  COALESCE(pk.col IS NOT NULL, FALSE) AS is_pk,
  COALESCE(fk.col IS NOT NULL, FALSE) AS is_fk,
  n2.nspname       AS ref_schema,
  cls2.relname     AS ref_table,
  fk.refcol        AS ref_column
FROM information_schema.columns col
JOIN pg_class cls        ON cls.relname = col.table_name
JOIN pg_namespace n      ON n.oid = cls.relnamespace AND n.nspname = col.table_schema
LEFT JOIN pk             ON pk.relid = cls.oid AND pk.col = col.column_name
LEFT JOIN fk             ON fk.relid = cls.oid AND fk.col = col.column_name
LEFT JOIN pg_class cls2  ON cls2.oid = fk.refrelid
LEFT JOIN pg_namespace n2 ON n2.oid = cls2.relnamespace
WHERE col.table_schema NOT IN ('pg_catalog','information_schema')
ORDER BY schema_name, table_name, col_pos;
