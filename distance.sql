/*
SET @user_latitude = ?;  -- Ganti dengan latitude pengguna
SET @user_longitude = ?; -- Ganti dengan longitude pengguna

SELECT *,
    (6371 * ACOS(COS(RADIANS(@user_latitude)) 
    * COS(RADIANS(latitude)) 
    * COS(RADIANS(longitude) - RADIANS(@user_longitude)) 
    + SIN(RADIANS(@user_latitude)) 
    * SIN(RADIANS(latitude)))) AS distance
FROM your_table
ORDER BY distance ASC;
*/