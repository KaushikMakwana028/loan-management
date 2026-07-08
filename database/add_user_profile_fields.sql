ALTER TABLE `users`
    ADD COLUMN `marriage_status` VARCHAR(30) NULL AFTER `mobile`,
    ADD COLUMN `dob` DATE NULL AFTER `marriage_status`,
    ADD COLUMN `education` VARCHAR(150) NULL AFTER `dob`,
    ADD COLUMN `employment` VARCHAR(150) NULL AFTER `education`;
