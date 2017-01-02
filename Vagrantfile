# Fix permissions after you run commands on both hosts and guest machine
system("
    if [ #{ARGV[0]} = 'up' ]; then
        echo 'Setting world write permissions for ./logs/*'
        chmod a+w ./logs
        chmod a+w ./logs/*
    fi
")

VAGRANTFILE_API_VERSION = "2"

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|
  config.vm.define "web" do |web|
      web.vm.box = "puppetlabs/centos-7.0-64-nocm"
      web.vm.network "private_network", ip: "192.168.50.52"
      web.vm.box_download_insecure = true

      web.vm.provider :virtualbox do |v|
        v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        v.customize ["modifyvm", :id, "--memory", 512]
        v.customize ["modifyvm", :id, "--name", "web"]
      end

      # Configure cached packages to be shared between instances of the same base box.
      # More info on http://fgrehm.viewdocs.io/vagrant-cachier/usage
      if Vagrant.has_plugin?("vagrant-cachier")
          web.cache.scope = :box
      end
      
      config.vm.synced_folder ".", "/vagrant", type: "nfs"
      
      # Make sure logs folder will be writable for Apache
      web.vm.synced_folder "logs", "/vagrant/logs", owner: 48, group: 48
      


      # Install all needed packages
      web.vm.provision "shell", name: "rpm", inline: <<-SHELL
        rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm
        rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm
      SHELL

      # PHP and modules
      web.vm.provision "shell", name: "php", inline: <<-SHELL
        sudo yum -y install php70w php70w-opcache
        sudo yum -y install php70w-bcmath
        sudo yum -y install php70w-cli
        sudo yum -y install php70w-imap
        sudo yum -y install php70w-common
        sudo yum -y install php70w-pdo
        sudo yum -y install php70w-odbc
        sudo yum -y install php70w-mcrypt
        sudo yum -y install php70w-mysqlnd
        sudo yum -y install mod_ssl
        sudo yum -y install php70w-xmlrpc
        sudo yum -y install vim
      SHELL

      # Use the provided example environment
      web.vm.provision "shell", name: "environment", inline: <<-SHELL
        cd /vagrant && cp .env.example .env
      SHELL

      # Update Apache config and restart
      web.vm.provision "shell", name: "apache", inline: <<-'SHELL'
        # Set DocumentRoot in Apache config file to the project files where it is shared in /vagrant
        echo "Setting Apache's DocumentRoot"
        sed -i 's/^DocumentRoot .*/DocumentRoot "\/vagrant\/public"/g' /etc/httpd/conf/httpd.conf
        sed -i 's/\/var\/www\/html/\/vagrant\/public/g' /etc/httpd/conf/httpd.conf

        # Set ServerName in Apache config file to localhost
        echo "Setting Apache's ServerName"
        sed -i 's/^#ServerName .*/ServerName localhost/g' /etc/httpd/conf/httpd.conf

        # Set AllowOverride in all directory settings in Apache config to enable .htaccess
        echo "Setting Apache's AllowOverride"
        sed -i 's/^\s*AllowOverride .*/AllowOverride All/g' /etc/httpd/conf/httpd.conf

        # Disable apache sendfile to fix "cache" when serving static files
        echo "Disable Apache's sendfile"
        sed -i 's/^#EnableSendfile off/EnableSendfile off/g' /etc/httpd/conf/httpd.conf
        sed -i 's/^EnableSendfile on/EnableSendfile off/g' /etc/httpd/conf/httpd.conf

        # Register Apache as a service
        echo "Registering Apache as a service"
        systemctl enable httpd.service

        # Start Apache service
        echo "Starting Apache Service"
        systemctl restart httpd.service
      SHELL

      # Stop iptable because it will cause too much confusion
      web.vm.provision "shell", name: "iptables", inline: <<-SHELL
        sudo systemctl stop firewalld.service
        sudo systemctl disable firewalld.service
      SHELL

      # Install Grunt and npm dependencies
      #config.vm.provision "shell", name: "grunt", inline: <<-SHELL
      #  yum -y install npm
      #  npm install -g grunt-cli
      #  cd /vagrant && npm install
      #SHELL
  end

  config.vm.define "db" do |db|
      db.vm.box = "puppetlabs/centos-7.0-64-nocm"
      db.vm.network "private_network", ip: "192.168.50.53"
      db.vm.network "forwarded_port",guest:3306, host:3306
      db.vm.box_download_insecure = true

      db.vm.provider :virtualbox do |v|
        v.customize ["modifyvm", :id, "--natdnshostresolver1", "on"]
        v.customize ["modifyvm", :id, "--memory", 512]
        v.customize ["modifyvm", :id, "--name", "db"]
      end

      # MySQL
      db.vm.provision "shell", name: "mysql", inline: <<-SHELL
        sudo yum -y install mariadb-server
        sudo systemctl enable mariadb
        sudo systemctl start mariadb

        echo "CREATE DATABASE komodoapi; CREATE USER 'sa'@'localhost' IDENTIFIED BY 'P@ssword123'; CREATE USER 'sa'@'%' IDENTIFIED BY 'P@ssword123'; GRANT ALL PRIVILEGES ON *.* TO 'sa'@'localhost' WITH GRANT OPTION; GRANT ALL PRIVILEGES ON *.* TO 'sa'@'%' WITH GRANT OPTION; FLUSH PRIVILEGES;" | mysql -u root

        mysql -uroot
        sudo mysql -e "UPDATE mysql.user SET Password = PASSWORD('P@ssword123') WHERE User = 'root'"
        sudo mysql -e "DELETE FROM mysql.user WHERE User='root' AND Host NOT IN ('localhost', '127.0.0.1', '::1')"
        sudo mysql -e "DROP USER ''@'localhost'"
        sudo mysql -e "DROP USER ''@'$(hostname)'"
        sudo mysql -e "DROP DATABASE test"
        sudo mysql -e "FLUSH PRIVILEGES"
      SHELL

      # Stop iptable because it will cause too much confusion
      db.vm.provision "shell", name: "iptables", inline: <<-SHELL
        sudo systemctl stop firewalld.service
        sudo systemctl disable firewalld.service
      SHELL
  end
end