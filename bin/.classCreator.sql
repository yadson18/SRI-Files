WITH VALIDATOR_METHOD AS(
    SELECT DISTINCT LOWER(R.RDB$FIELD_NAME) AS COLUMNS, 
    
        CASE R.RDB$NULL_FLAG 
            WHEN 1 THEN 'notEmpty'
            ELSE 'empty'
        END AS IS_NULL,
        
        CASE F.RDB$FIELD_TYPE 
            WHEN 7 THEN 'int'
            WHEN 8 THEN 'int'
            WHEN 9 THEN 'string'
            WHEN 10 THEN 'float'
            WHEN 11 THEN 'float'
            WHEN 12 THEN 'string'
            WHEN 13 THEN 'string'
            WHEN 14 THEN 'string'
            WHEN 16 THEN 'int'
            WHEN 27 THEN 'float'
            WHEN 35 THEN 'string'
            WHEN 37 THEN 'string'
            WHEN 40 THEN 'string'
            WHEN 261 THEN 'string'
            ELSE 'string'
        END AS TYPE,
        
        F.RDB$FIELD_LENGTH AS SIZE
        
    FROM RDB$RELATION_FIELDS R
        LEFT JOIN RDB$FIELDS F ON R.RDB$FIELD_SOURCE = F.RDB$FIELD_NAME
        LEFT JOIN RDB$COLLATIONS COLL ON F.RDB$COLLATION_ID = COLL.RDB$COLLATION_ID
        LEFT JOIN RDB$CHARACTER_SETS CSET ON F.RDB$CHARACTER_SET_ID = CSET.RDB$CHARACTER_SET_ID
    WHERE R.RDB$RELATION_NAME = '%table_name%' ORDER BY R.RDB$FIELD_POSITION 
),
PRIMARY_KEY AS(
    SELECT FIRST 1 LOWER(IDX.RDB$FIELD_NAME) AS PK
    FROM RDB$RELATION_CONSTRAINTS TC
        JOIN RDB$INDEX_SEGMENTS IDX ON (IDX.RDB$INDEX_NAME = TC.RDB$INDEX_NAME)
    WHERE 
        IDX.RDB$FIELD_NAME != 'EMPRESA' AND
        TC.RDB$RELATION_NAME = '%table_name%'
        ORDER BY IDX.RDB$FIELD_POSITION
) 

SELECT REPLACE(
    LIST(PK), ' ', ''
) AS METHODS
FROM PRIMARY_KEY

UNION ALL

SELECT REPLACE(
    '$validator->addRule(''' || COLUMNS || ''')->' || 
    IS_NULL || '()->' ||
    TYPE || '()->' ||
    'size(' || SIZE || ');', ' ', ''
) AS METHODS
FROM VALIDATOR_METHOD;