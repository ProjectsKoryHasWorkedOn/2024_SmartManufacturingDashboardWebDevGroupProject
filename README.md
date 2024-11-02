
--------------------------------------

Instructions to set-up the DB:

1. In XAMPP Control Panel, 
    1. Start Apache and MySQL.
    2. Click 'Admin' for 'MySQL' to view PhpMyAdmin
    3. Paste and run contents of 'db_creation_script.sql' to create the database
    4. Paste and run contents of 'db_insertion_of_values_script_p1.sql', 'db_insertion_of_values_script_p2.sql', and 'db_insertion_of_values_script_p3.sql'
    
    
Oh no! Running ''db_insertion_of_values_script_p3.sql' doesn't work.

This is because ''db_insertion_of_values_script_p3.sql' contains a PDF file in the SQL, which XAMPP doesn't allow users to upload by default due to restrictions on uploads.


Can make this work through changing the 'my.ini' file in XAMPP:
[mysqldump]
max_allowed_packet=2G

    5. Paste and run contents of 'db_create_user_script.sql' and then contents of 'db_user_permissions_script.sql'. 
    
    
Shouldn't need to check this but if it worked, there'll be a user called 'localuser' in the table called 'users' in the database called 'mysql'. Also if this works, you shouldn't see this message 'Fatal error: Uncaught mysqli_sql_exception: Access denied for user 'localuser'@'localhost' to database 'factory_db'' upon trying to access the webpage in your web browser when following the 'Instructions to run the website'
    
Note: In real-world scenario, would run contents of 'db_root_user_permissions_script.sql. For our case, however, DON'T HAVE TO DO THIS. If you want to do or accidentally do this, change $cfg['Servers'][$i]['password'] in 'C:\xampp\phpMyAdmin\config.inc.php' so it looks like this:

$cfg['Servers'][$i]['password'] = 'NrWK8i3vhdmAHbRezjrS7FKwmfi3b47xaKP3zosZ4WmVwKkYAVm8eZDBsZsAkJt2bPG3uXm5Lhx45dnayy84FVEr2WWENnGYDAVyFioLkuPrCVeyTMzRQcVay2EQJQyx'

This is a security measure to make the root user less accessible by setting a root password

--------------------------------------

Instructions to run the website:
    1. Put solo_project folder here
C:\xampp\htdocs\www\

The solo_project folder name has to be called "solo_project"
As this is name I've set for it in "php_resource_paths.php"


    2. Use the actual server address, not the live server address since the live server address downloads PHP files, which is not what we want

Use this address:
http://localhost/www/solo_project/

    3. Use the usernames and passwords supplied in solo_project/user_accounts.pdf


--------------------------------------