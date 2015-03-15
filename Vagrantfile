VAGRANTFILE_API_VERSION = "2"

box             = 'centos64'
url             = 'http://puppet-vagrant-boxes.puppetlabs.com/centos-64-x64-vbox4210.box'
hostname        = 'familylounger.local'
ram             = '768'

bootstrap_script = <<SCRIPT

# Flush iptables
iptables -F

# Install common
yum install -y git wget curl

# Install apache, php, mysql
yum install -y httpd mod_ssl mod_php
yum install -y php php-mysql php-pdo
yum install -y mysql mysql-server
/etc/init.d/mysqld start
/etc/init.d/httpd start

# Setup app
mkdir -p /var/www/vhosts/www.familylounger.local/
git clone https://github.com/mjuszczak/familylounger.git /var/www/vhosts/www.familylounger.local
cp /var/www/vhosts/www.familylounger.local/app/Config/simple-config.php.example /var/www/vhosts/www.familylounger.local/app/Config/simple-config.php
chown -R apache:apache /var/www/vhosts/www.familylounger.local/app/tmp

# Setup database
mysql -u root -e "create database familylounger"
mysql -u root -e "GRANT ALL ON familylounger.* TO 'app_fl'@'localhost' IDENTIFIED BY 'dfag4ya34yababataa4abaha'"
mysql -u root familylounger < /var/www/vhosts/www.familylounger.local/app/Config/Schema/schema.sql

# Setup vhost
cat > /etc/httpd/conf.d/25-familylounger.conf <<EOF
<VirtualHost *:80>
  ServerName familylounger.local
  ServerAlias *.familylounger.local
  DocumentRoot /var/www/vhosts/www.familylounger.local/app/webroot
  <Directory /var/www/vhosts/www.familylounger.local/app/webroot>
    Options FollowSymLinks
    AllowOverride All
    Order allow,deny
    Allow from all
  </Directory>
  SetEnv CODE_ENVIRONMENT development
</VirtualHost>
EOF
/etc/init.d/httpd restart

SCRIPT

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

  config.vm.box = box
  config.vm.box_url = url
  config.vm.host_name = hostname
  config.vm.network "private_network", ip: "192.168.77.22"

  config.vm.provider "virtualbox" do |v|
    v.name = hostname
  end

  config.vm.provision "shell", inline: bootstrap_script
end
