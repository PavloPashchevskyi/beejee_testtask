This is the first part of testtask for Vladimir Skirga.
The main deffect is with CAPTCHA plugin.

For running the application:
1. Create database
2. Import /data/gb.sql file to database you have created
3. Copy the content of file /application/config/options.sample to the file /application/config/options.php and in the "options.php" file chanhe database connection options to yours.
4. If /var/logs directory is not present, create it.
5. Configure you virtual host like a following:

    <VirtualHost *:80>
    	ServerName <your_host_name>
    	ServerAdmin <your_email_address>

    	DocumentRoot /home/ppd/sites/<directory_with_git_reposetory>
    <Directory /home/ppd/sites/<directory_with_git_reposetory>>
        Options FollowSymLinks MultiViews
        AllowOverride All
        require all granted
    </Directory>

    	ErrorLog /home/ppd/sites/<directory_with_git_reposetory>/var/logs/error.log

    	LogLevel warn

    	ServerSignature On

</VirtualHost>

6. Execute the command sudo a2ensite <your_virtual_host_file.conf> (for Apache on Linux)
7. Restart Apache via command:
    sudo service apache2 reload
    or
    sudo service apache2 restart
8. Add to your hosts file (on Linux it is /etc/hosts) following
    127.0.0.1	<your_host_name>
9. Follow the link http://<your_host_name> in your browser and enjoy!

Note! Items from 5 to 8 (including path to hosts file) is for Linux-like operating systems. 
    
