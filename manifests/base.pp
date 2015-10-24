
# ネットワーク設定
file { "/etc/sysconfig/network":
  ensure  => present,
  content =>
    "NETWORKING=yes
    HOSTNAME=$myfqdn"
}

# ホスト名設定
exec { "hostname":
  command => "hostname $myfqdn",
  path    => "/bin:/sbin:/usr/bin:/usr/sbin"
}

# hosts設定
file { "/etc/hosts":
  ensure  => present,
  content =>
    "127.0.0.1 localhost.vagrantup.com localhost 
    $myaddr $myfqdn $myname"
}

# Firewall無効化
service { "iptables":
  provider   => "redhat",
  enable     => false,
  ensure     => stopped,
  hasrestart => false
}
service { "iptables6":
  provider   => "redhat",
  enable     => false,
  ensure     => stopped,
  hasrestart => false
}

# SELinux無効化
exec { "SELinux":
  command => '/bin/sed -i -e "s|^SELINUX=.*$|SELINUX=disabled|" /etc/sysconfig/selinux'
}

exec { "setenforce":
  command => '/usr/sbin/setenforce 0'
}

# 起動オプション設定
exec { "grub":
  command => '/bin/sed -i -e "s|quiet.*$|quiet enforcing=0|" /etc/grub.conf'
}

# MACアドレスの自動保存無効化
exec { "ignore mac":
  command => "/bin/sed -i -e 's|/etc/udev/rules.d/70-persistent-net.rules|/dev/null|g' /lib/udev/write_net_rules"
}

# TimeZone設定
exec { "timezone":
  command => '/bin/cp /usr/share/zoneinfo/Asia/Tokyo /etc/localtime'
}

# Locale設定
exec { "locale":
  command => "/bin/sed -i -e 's/LANG.*$/LANG=\"ja_JP.utf8\"/' /etc/sysconfig/i18n"
}




# パッケージインストール
package { "httpd":                   ensure => "latest"}
package { "mysql-community-server":  ensure => "latest"}
package { "mysql":                   ensure => "latest"}

exec { "php56":
  command => "/usr/bin/yum -y install php php-mysqlnd php-gd php-odbc php-pear php-xml php-xmlrpc php-mbstring php-mcrypt php-soap php-tidy --enablerepo=remi,remi-php56"
}

# サービス起動と自動起動設定
service { "httpd":
  name       => "httpd",
  enable     => true,
  ensure     => running,
  require    => Package["httpd"],
  hasrestart => true
}
service { "mysqld":
  name       => "mysqld",
  enable     => true,
  ensure     => running,
  require    => Package["mysql-community-server"],
  hasrestart => true
}

# 設定ファイル置換(httpd.conf)
exec { "copy_httpd.conf":
  command => "bash -c 'if ! test -f /etc/httpd/conf/httpd.conf.org; then cp -p /etc/httpd/conf/httpd.conf /etc/httpd/conf/httpd.conf.org; fi'",
  before  => File["/etc/httpd/conf/httpd.conf"],
  require => Package["httpd"],
  path    => ["/bin", "/usr/bin"],
}
file { "/etc/httpd/conf/httpd.conf":
  owner  => "root",
  group  => "root",
  mode   => "0644",
  ensure => file,
  before => Service["httpd"],
  source => "puppet:///modules/puppet/httpd.conf",
}

# 設定ファイル置換(php.ini)
exec { "copy_php.ini":
  command => "bash -c 'if ! test -f /etc/php.ini.org; then cp -p /etc/php.ini /etc/php.ini.org; fi'",
  before  => File["/etc/php.ini"],
  require => Package["httpd"],
  path    => ["/bin", "/usr/bin"],
}
file { "/etc/php.ini":
  owner  => "root",
  group  => "root",
  mode   => "0644",
  ensure => file,
  before => Service["httpd"],
  source => "puppet:///modules/puppet/php.ini",
}

file { "/etc/my.cnf":
  owner   => "mysql",
  group   => "mysql",
  source  => "puppet:///modules/puppet/my.cnf",
  before  => Service["mysqld"],
  require => Package["mysql"],
}

# MySQL接続設定
exec { "set-mysql-password":
  unless  => "mysqladmin -uroot -p$mysql_password status",
  path    => ["/bin", "/usr/bin"],
  command => "mysqladmin -uroot password $mysql_password",
  require => Service["mysqld"],
}
define mysqldb( $user, $password ) {
  exec { "create-${name}-db":
    unless  => "/usr/bin/mysql -u${user} -p${password} ${name}",
    command => "/usr/bin/mysql -uroot -p$mysql_password -e \"create database ${name}; grant all on ${name}.* to ${user}@localhost identified by '$password';\"",
    require => Service["mysqld"],
  }
}

#DB作成
mysqldb { "bond_idea":
  user     => "bond_admin",
  password => "bondpass",
}
mysqldb { "bond_idea_test":
  user     => "bond_admin",
  password => "bondpass",
}

# phpinfo.php作成
file { '/var/www/html/web/info.php':
  ensure  => file,
  content => '<?php  phpinfo(); ?>',
  require => Package['httpd'],
}

package { "curl":  ensure => "installed"}

exec { "install composer":
  command => "/usr/bin/curl -sS https://getcomposer.org/installer | php && sudo mv composer.phar /usr/local/bin/composer",
  require => Package["curl"],
}
