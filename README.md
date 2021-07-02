# Principle

Implements LDAP and 2 factors authentication (Google Authentificator)

# Use it

1. Delete Folder `vendor`and file `composer.lock`
2. Regenerate them by usin the following command (it requires to install, before, the php library manager : composer.exe) 

```bash
composer install
```

3. In `index.php` choose the action of the form => `ldapManager` or the ldap test `ldapManagerTest`
4. If using `ldapManager` Update the values in `.htaccess` file 

