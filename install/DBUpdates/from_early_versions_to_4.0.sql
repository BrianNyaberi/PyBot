---
---
-- from early versions to 4.0
ALTER TABLE users ADD culture VARCHAR( 5 ) NULL AFTER email;
UPDATE users SET culture = 'en_US';