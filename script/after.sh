set -e
DONE=/tmp/.after_done

if [ ! -f ${DONE} ]; then

  /usr/bin/mysql -u bond_admin -pbondpass -D bond_idea < /vagrant/sql/create_ideas_table.sql
  /usr/bin/mysql -u bond_admin -pbondpass -D bond_idea_test < /vagrant/sql/create_ideas_table.sql

  cd /var/www/html
  /usr/local/bin/composer install
  
  touch  ${DONE}
fi
